<?php defined('BASEPATH') OR exit('No direct script access allowed');


class pencatatan_realisasi extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if (isset($_SESSION['user_name']))
			$this->load->view('theme_default/setting');
		else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
			}
	}	
	
	function index()
	{
        $data = array(
            'title' => 'Pencatatan Realisasi',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Penjualan</li>
                             <li>Pencatatan Realisasi</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Faktur Penjualan',
            'page_subtitle' => '',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
            
            
        );
        $this->template->build('v_pencatatan_realisasi', $data);
    }
	
	function populate_no_so()
	{
		$select = $this->db->query("
		SELECT c.id, c.`no_so`, cust.`nama`, c.`delivered`
		FROM ck_po_customer_header c
		LEFT OUTER JOIN ck_customer cust
		ON cust.`id` = c.`id_customer`
		WHERE picked = 1 AND delivered IS NULL
		");
		
       header('Content-Type: application/json');
		echo json_encode($select->result());
	}
	
	function setJumlahDiterima()
	{
		$id_detail = $this->input->post('id_detail');
		$jumlah_diterima = $this->input->post('jumlah_diterima');
		$set = $this->db->query("UPDATE ck_penjualan_detail SET jumlah_diterima = '$jumlah_diterima' WHERE id_detail='$id_detail' ");
		if($set)
		{
			$updSubtotal = $this->db->query("UPDATE ck_penjualan_detail SET sub_total = (jumlah_diterima*harga_jual)-(jumlah_diterima*harga_jual)*disc_persen/100 WHERE id_detail='$id_detail'");
			if($updSubtotal)
				echo "Setting Done!";
		}
	}
	function insertPenjualan($idpo)
	{
		$copyToPenjualan = $this->db->query("INSERT IGNORE INTO
		ck_penjualan_detail (id_po,id_detail,id_produk,id_kemasan,batch_number,expired_date,harga_jual,jumlah_pesanan,jumlah_acc,jumlah_diterima,back_order,disc_rp,disc_persen,sub_total,acc,keterangan)
		SELECT id_po,id,id_produk,id_kemasan,batch_number,expired_date,harga_jual,jumlah_pesanan,jumlah_acc,jumlah_acc,back_order,disc_rp,disc_persen,sub_total,acc,keterangan
		FROM ck_po_customer_detail
		WHERE id_po='$idpo'"
		);
		if($copyToPenjualan)
		{
			$detail = $this->db->query("
			SELECT det.id_detail,det.id_produk, produk.nama, kemasan.nama AS kemasan, 
				det.batch_number, det.expired_date,DATE_FORMAT( det.expired_date, '%d/%m/%Y') AS kadaluarsa, head.status,
                DAY(det.expired_date) AS tgl_expired,det.acc,det.keterangan,
                MONTHNAME(det.expired_date) AS bln_expired,det.jumlah_diterima,
                YEAR(det.expired_date) AS  tahun_expired,
				 det.harga_jual, jumlah_pesanan, SUM(stok.stok) AS qty_akhir, 
				 SUM(stok.stok)+det.jumlah_pesanan AS qty_gudang, det.sub_total, det.disc_rp, det.disc_persen, 
				 det.back_order, det.jumlah_back_order, det.jumlah_acc as Qacc
				 
                FROM ck_penjualan_detail det
                LEFT OUTER JOIN ck_po_customer_header head
					ON det.id_po = head.id
                LEFT OUTER JOIN ck_produk produk
					ON produk.id = det.id_produk
                LEFT OUTER JOIN ck_produk_kemasan kemasan
					ON kemasan.id = det.id_kemasan
		LEFT JOIN ck_tbl_beli_gr_dtl stok
			ON det.id_produk = stok.produk_id
			AND det.batch_number = stok.batch_number
			AND stok.expired_date = det.expired_date
                WHERE det.id_po='$idpo' and acc='1'
                GROUP BY stok.batch_number, stok.expired_date, det.`id_produk`
			");
			$no = 1;
           foreach ($detail->result() as $r)
           {
            echo '<tr>';
            echo '<td>'.$no.'</td>';
            echo '<td><input type="hidden" id="id_detail" value="'.$r->id_detail.'"><input type="hidden" value="'.$r->id_produk.'">'.$r->nama.' ('.$r->kemasan.')</td>';
            echo '<td>'.$r->batch_number.' - '.$r->kadaluarsa.'</td>';
            echo '<td style="text-align:right;"><b>'.$r->jumlah_pesanan.'</b></td>';
            echo '<td style="text-align:right;"><b>'.$r->Qacc.'</b></td>';
            echo '<td style="text-align:right;"><b><input type="number" id="jml_diterima" max="'.$r->Qacc.'" value="'.$r->jumlah_diterima.'"><button id="set" class="button">SET</button></b></td>';
            echo '<td style="text-align:right;"><b>'.$r->jumlah_back_order.'</b></td>';
            echo '</tr>';
            $no++;
           }
		}
	}
	
	function insert_delivery()
	{
		$id = $this->input->post('id');
		$delivered = $this->input->post('delivered');
		$ket_delivered = $this->input->post('ket_delivered');
		$receiver_name = $this->input->post('receiver_name');
		$tanggal_delivered = date_format( new DateTime($this->input->post('tanggal_delivered')),$this->config->item('FORMAT_DATE_TO_INSERT')).' '.$this->input->post('jam_delivered');
		/* 
		 */
		//$q = "update ck_po_customer_header set delivered='$delivered', ket_delivered='$ket_delivered', receiver_name='$receiver_name', tanggal_delivered='$tanggal_delivered', bukti_gambar_delivered='$path' where id='$id'";
		$q = "update ck_po_customer_header set delivered='$delivered', ket_delivered='$ket_delivered', receiver_name='$receiver_name', tanggal_delivered='$tanggal_delivered' where id='$id'";
		$ex = $this->db->query($q);
		
		echo $q;
		
	}
	function upd_img($id)
	{
		//$id = $this->input->post('id');
		$fileName = $_FILES['file']['name'];
        $extension=(explode(".", $fileName));
        $tmp = $_FILES['file']['tmp_name'];    
        
        $PATH_GAMBAR = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/img/realisasi_penjualan/'.$id.'.'.end($extension);
        move_uploaded_file($tmp, $PATH_GAMBAR);
		$path='sitauhid/assets/img/realisasi_penjualan/'.$id.'.'.end($extension);
		
		$q = "update ck_po_customer_header set  bukti_gambar_delivered='$path' where id='$id'";
		$ex = $this->db->query($q);
		if($ex) echo "done ".$fileName;
		else echo "fail";
	}
	
	function populate()
	{
		$aColumns = array('id','no_so','status', 'tanggal_delivered','receiver_name', 'ket_delivered', 'bukti_gambar_delivered');
        
        // DB table to use
        //$sTable = 'v_so_header';
         $sTable = '(SELECT h.id,h.`no_so`, IF(h.`delivered`="1","Delivered","Not Done") AS status, h.`tanggal_delivered`,h.`receiver_name`,h.`ket_delivered`,
					h.`bukti_gambar_delivered`
					FROM ck_po_customer_header h 
					WHERE h.`delivered` IN ("1","2")
					) x '; 
		/* $sTable = '(SELECT *
					FROM ck_po_customer_header h 
					WHERE h.`delivered` IN ("1","2")
					) x '; */
    
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
    
        // Paging
        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        
        // Ordering
        if (isset($iSortCol_0)) {
            for ($i=0; $i<intval($iSortingCols); $i++) {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
    
                if ($bSortable == 'true') {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if (isset($sSearch) && !empty($sSearch)) {
            for ($i=0; $i<count($aColumns); $i++) {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);
                
                // Individual column filtering
                if (isset($bSearchable) && $bSearchable == 'true') {
                    $this->db->or_like($aColumns[$i], $this->db->escape_like_str($sSearch));
                }
            }
        }
        
        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $this->db->get($sTable);
    
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
        
		$no = $iDisplayStart;
		
        foreach ($rResult->result_array() as $aRow) {
            $row = array();
           
            $no++;
            $row[] = $no;
            $row[] = $aRow['no_so'];
			
			$label = "label label-success";
			if($aRow['status']!="Delivered")
					$label = "label label-warning";
			$row[] = '<span class="'.$label.'">'.$aRow['status'].'</span>';
            $row[] = $aRow['tanggal_delivered'];
            $row[] = $aRow['receiver_name'];
            $row[] = $aRow['ket_delivered'];
            $row[] = '<a target="_blank" href="/'.$aRow['bukti_gambar_delivered'].'"><span class="fa fa-file-image-o"> Image</span></a>';
			
				$output['aaData'][] = $row;								
			}
		
		header('Content-Type: application/json');
        echo json_encode($output);
			
        }	
        
	}