<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class account_receivable extends CI_Controller
{
    function __construct() {
		parent::__construct();
		$this->load->model('m_po_customer'); 
		$this->load->library('pdf');
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
            'title' => 'Account Receivable',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Accounting</li>
                             <li>Account Receivable</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
            
            
        );
        $this->template->build('v_account_receivable', $data);
	}
	function setAR()
	{
		$idpo = $this->input->get('id_po');
		$jumlah_tagihan = $this->input->get('jumlah_tagihan');
		$no_inv = $this->input->get('no_inv');
		// Entry data to Billing Statement.
		$temp = substr($no_inv, 3);
		$billing_no = "BIL".$temp;
		
		$user = $_SESSION['user_name'];
		$billing_statement = $this->db->query("insert into ck_billing_statement(id_header,billing_no,jumlah_tagihan,created_by) VALUES('$idpo','$billing_no','$jumlah_tagihan','$user')"); 
		
		// Set AR Flag to 1
		$exec = $this->db->query("UPDATE ck_po_customer_header SET account_receivable='1' WHERE id='$idpo'");
		if($exec)
			echo "done";
	}
	function populate()
	{
		$aColumns = array('id','no_faktur','no_so','status', 'nama','ket_delivered','ar', 'sub_total', 'ppn', 'total');
        
        // DB table to use
        //$sTable = 'v_so_header';
         // $sTable = '(SELECT h.id, h.`no_faktur`, h.no_so, c.`nama`, IF(h.`delivered`=1,"delivered","not done") AS STATUS, h.`ket_delivered`, h.account_receivable as ar
						// FROM ck_po_customer_header h
						// LEFT OUTER JOIN ck_customer c
						// ON c.id = h.`id_customer`
						// WHERE h.`delivered` IN ("1","2") 
						
					// ) x '; 
		$sTable = '(SELECT h.id, h.`no_faktur`, h.no_so, c.`nama`, 
		IF(h.`delivered`=1,"delivered","not done") AS STATUS, h.`ket_delivered`, h.account_receivable as ar,
		SUM(pj.sub_total) as sub_total,SUM(pj.sub_total)+h.ppn/100*SUM(pj.sub_total) AS total, h.ppn
						FROM ck_po_customer_header h
						LEFT OUTER JOIN ck_customer c
						ON c.id = h.`id_customer`
						LEFT OUTER JOIN ck_penjualan_detail pj
						ON pj.`id_po` = h.id
						WHERE h.`delivered` IN ("1","2") 
						
						GROUP BY id_po
					) x '; 
		
    
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
            $row[] = $aRow['no_faktur'];
            $row[] = $aRow['no_so'];
            $row[] = $aRow['nama'];
			
			$label = "label label-success";
			if($aRow['status']!="delivered")
					$label = "label label-warning";
			$row[] = '<span class="'.$label.'">'.$aRow['status'].'</span><input type="hidden" id="idpo" value="'.$aRow['id'].'">';
            
            $row[] = $aRow['ket_delivered'];
			
            $row[] = number_format($aRow['sub_total']);
            $row[] = $aRow['ppn'];
            $row[] = number_format($aRow['total']);
            $row[] = '<button class="button" id="btn_detail">Show Detail</button>';
            $row[] = '<input type="hidden" id="jumlah_tagihan" value="'.$aRow['total'].'"><input type="hidden" id="no_inv" value="'.$aRow['no_faktur'].'">
					<input type="hidden" id="id" value="'.$aRow['id'].'"><input type="checkbox" id="checker" '.($aRow['ar']>0?'checked disabled':'').'>&nbsp;<span class="label label-default">AR</span>';
			
				$output['aaData'][] = $row;								
			}
		
		header('Content-Type: application/json');
        echo json_encode($output);
			
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
				det.batch_number, det.expired_date,DATE_FORMAT( det.expired_date, '%d/%m/%Y') AS kadaluarsa, head.status,head.no_faktur,
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
            echo '<td><input type="hidden" id="no_inv" value="'.$r->no_faktur.'"><input type="hidden" id="jumlah_tagihan" value="'.$r->sub_total.'"><input type="hidden" id="id_detail" value="'.$r->id_detail.'"><input type="hidden" value="'.$r->id_produk.'">'.$r->nama.' ('.$r->kemasan.')</td>';
            echo '<td>'.$r->batch_number.' - '.$r->kadaluarsa.'</td>';
            echo '<td style="text-align:right;"><b>'.number_format($r->harga_jual).'</b></td>';
            echo '<td style="text-align:right;"><b>'.$r->jumlah_pesanan.'</b></td>';
            echo '<td style="text-align:right;"><b>'.$r->Qacc.'</b></td>';
            echo '<td style="text-align:right;"><b>'.$r->jumlah_back_order.'</b></td>';
            echo '<td style="text-align:right;"><b>'.$r->jumlah_diterima.'</b></td>';
            echo '<td style="text-align:right;"><b>'.number_format(($r->jumlah_diterima*$r->harga_jual)*$r->persen/100).' ('.$r->disc_persen.'%)</b></td>';
            echo '<td style="text-align:right;"><b>'.number_format($r->sub_total).'</b></td>';
            echo '</tr>';
            $no++;
           }
		}
	}
}