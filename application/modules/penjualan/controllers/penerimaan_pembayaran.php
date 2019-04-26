<?php defined('BASEPATH') OR exit('No direct script access allowed');


class penerimaan_pembayaran extends CI_Controller
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
            'title' => 'Penerimaan Pembayaran',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Penjualan</li>
                             <li>Penerimaan Pembayaran</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Penerimaan Pembayaran',
            'page_subtitle' => '',
            'custom_scripts' => '<script>$("#menu_penerimaan_pembayaran").addClass("active");</script>'
            
            
        );
        $this->template->build('v_penerimaan_pembayaran', $data);
    }
	
	function populate()
	{
		$aColumns = array('id','no_faktur','billing_no','nama','jumlah_tagihan','jumlah_pembayaran','sisa_tagihan','status');
        
        // DB table to use
        //$sTable = 'v_so_header';
         $sTable = '(SELECT bill.id, bill.`id_header`, head.`id_customer`,
 bill.`billing_no`, head.`no_faktur`,
cust.`nama`,bill.`jumlah_tagihan`, 
SUM(bayar.jumlah_pembayaran) AS jumlah_pembayaran, 
(bill.`jumlah_tagihan`)-IF(bayar.jumlah_pembayaran IS NULL,0,SUM(bayar.`jumlah_pembayaran`)) AS sisa_tagihan,
IF(bill.settlement_status="1","SETTLED",IF(bill.`jumlah_tagihan`=SUM(bayar.jumlah_pembayaran),"LUNAS","AR")) AS STATUS
FROM ck_billing_statement bill
LEFT OUTER JOIN ck_po_customer_header head
ON bill.`id_header` = head.`id`
LEFT OUTER JOIN ck_penerimaan_pembayaran bayar
ON bayar.`id_billing` = bill.`id`
LEFT OUTER JOIN ck_customer cust
ON cust.`id` = head.`id_customer`
GROUP BY bill.`id`

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
            $row[] = '<input type="hidden"  id="billing_id" value="'.$aRow['id'].'"></span><span id="detail_no_inv" style="text-align:right;">'.$aRow['no_faktur'].'</span>';
			
			if($aRow['status']=="SETTLED")$label = "label label-success";
			else if($aRow['status']=="LUNAS")
					$label = "label label-default";
			else $label = "label label-warning";
            $row[] = '<span id="detail_nama" style="text-align:right;">'.$aRow['nama'].'</span>';
            $row[] = '<span id="detail_total"  style="text-align:right;">'.number_format($aRow['jumlah_tagihan']).'</span>';
            $row[] = '<span style="text-align:right;">'.number_format($aRow['jumlah_pembayaran']).'</span>';
            $row[] = '<span style="text-align:right;">'.number_format($aRow['sisa_tagihan']).'</span>';
			$row[] = '<span class="'.$label.'">'.$aRow['status'].'</span>';
            $row[] = '<button class="button" id="showDetailPembayaran" ><span class="fa fa-file-image-o"> Detail Pembayaran</span></button>';
			
				$output['aaData'][] = $row;								
			}
		
		header('Content-Type: application/json');
        echo json_encode($output);
			
        }

			
		
	function populate_no_so()
	{
		$select = $this->db->query("SELECT bill.id, bill.`id_header`, head.`id_customer`, bill.settlement_status,
 bill.`billing_no`, head.`no_faktur`,
cust.`nama`,bill.`jumlah_tagihan`, 
SUM(bayar.jumlah_pembayaran) AS jumlah_pembayaran, 
(bill.`jumlah_tagihan`)-IF(bayar.jumlah_pembayaran IS NULL,'0',SUM(bayar.`jumlah_pembayaran`)) AS sisa_tagihan,
IF(bill.`jumlah_tagihan`=SUM(bayar.jumlah_pembayaran),'SETTLE','AR') AS STATUS
FROM ck_billing_statement bill
LEFT OUTER JOIN ck_po_customer_header head
ON bill.`id_header` = head.`id`
LEFT OUTER JOIN ck_penerimaan_pembayaran bayar
ON bayar.`id_billing` = bill.`id`
LEFT OUTER JOIN ck_customer cust
ON cust.`id` = head.`id_customer`
GROUP BY bill.`id` having settlement_status < 1
		");
		
       header('Content-Type: application/json');
		echo json_encode($select->result());
	}

	function insert_payment()
	{
		$id_billing = $this->input->post('id_billing');
		$id_customer = $this->input->post('id_customer');
		$jumlah_tagihan = $this->input->post('jumlah_tagihan');
		$jumlah_bayar = $this->input->post('jumlah_bayar');
		$sisa_bayar = $this->input->post('sisa_bayar');
		$lebih_bayar = $this->input->post('lebih_bayar');
		$keterangan = $this->input->post('keterangan');
		$jam_pembayaran = $this->input->post('jam_pembayaran');
		$user = $_SESSION['user_name'];
		$tanggal_pembayaran = date_format( new DateTime($this->input->post('tanggal_pembayaran')),$this->config->item('FORMAT_DATE_TO_INSERT')).' '.$this->input->post('jam_pembayaran');
		/* 
		 */
		//$q = "update ck_po_customer_header set delivered='$delivered', ket_delivered='$ket_delivered', receiver_name='$receiver_name', tanggal_delivered='$tanggal_delivered', bukti_gambar_delivered='$path' where id='$id'";
		$q = "insert into ck_penerimaan_pembayaran(	
		id_billing,id_customer,jumlah_tagihan,
		jumlah_pembayaran,sisa_bayar,keterangan,tanggal_pembayaran,created_date,created_by) 
		VALUES('$id_billing','$id_customer','$jumlah_tagihan','$jumlah_bayar','$sisa_bayar',
		'$keterangan','$tanggal_pembayaran ',NOW(),'$user');
		";
		$ex = $this->db->query($q);
		
		echo $this->db->insert_id();
		
	}
	
	function upd_img($payment_id)
	{
		//$id = $this->input->post('id');
		$fileName = $_FILES['file']['name'];
        $extension=(explode(".", $fileName));
        $tmp = $_FILES['file']['tmp_name'];    
        
        $PATH_GAMBAR = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/img/penerimaan_pembayaran/'.$payment_id.'.'.end($extension);
        move_uploaded_file($tmp, $PATH_GAMBAR);
		$path='sitauhid/assets/img/penerimaan_pembayaran/'.$payment_id.'.'.end($extension);
		
		$q = "update ck_penerimaan_pembayaran set  bukti_pembayaran='$path' where id='$payment_id'";
		$ex = $this->db->query($q);
		if($ex) echo "done ".$fileName;
		else echo "fail";
	}
	
	function pembayaran_detail($billing_id)
	{
		$x = $this->db->query("SELECT jumlah_pembayaran,DATE_FORMAT(tanggal_pembayaran,'%d-%m-%Y %H:%i:%s') AS tanggal_pembayaran, sisa_bayar, byr.keterangan,bukti_pembayaran
							FROM ck_penerimaan_pembayaran byr
							LEFT OUTER JOIN ck_billing_statement bill
							ON byr.`id_billing` = bill.id
							LEFT OUTER JOIN ck_po_customer_header h
							ON h.id = bill.id_header
							LEFT OUTER JOIN ck_customer c
							ON c.id = byr.`id_customer`
							WHERE id_billing = '$billing_id'");
		$no = 1;		
		$sisa;
		foreach($x->result() as $row)
		{
			echo '<tr>';
			echo 	'<td>'.$no.'</td>';
			echo 	'<td>'.$row->tanggal_pembayaran.'</td>';
			echo 	'<td>'.number_format($row->jumlah_pembayaran).'</td>';
			echo 	'<td>'.number_format($row->sisa_bayar).'</td>';
			echo 	'<td>'.$row->keterangan.'</td>';
			echo 	'<td><a target="_blank" href="/'.$row->bukti_pembayaran.'">View</a></td>';
			echo '</tr>';
			$no++;
			$sisa = number_format($row->sisa_bayar);
		}
		if($x->num_rows()>0)
			echo '<tr><td colspan="6" style="text-align:center;font-size:15px;font-weight:bold;">Sisa Tagihan : Rp '.$sisa.'</td></tr>';
		else
			echo '<tr><td colspan="6" style="text-align:center;font-size:13px;font-weight:bold;">Belum Ada Pembayaran</td></tr>';
	}
}