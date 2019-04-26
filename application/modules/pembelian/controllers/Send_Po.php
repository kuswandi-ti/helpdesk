<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Send_Po extends CI_Controller {
    
	var $view_po_hdr_approve	= 'ck_view_beli_purchaseorder_hdr_approve';

	public function __construct() {
		parent::__construct();
		$this->load->model('M_Send_Po');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Send Purchase Order (PO)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/send_po">Send Purchase Order (PO)</a></li>',
			'page_icon' => 'icon-bag',
			'page_title' => 'Send Purchase Order (PO)',
			'page_subtitle' => 'Pengajuan pembelian barang ke supplier',			
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_send_po.js'></script>"
		);
		$this->template->build('v_send_po', $data);
	}
	
	public function get_data() {		
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_po', 'no_po', 'bulan', 'tahun', 'tgl_po', 'id_supplier', 'nama_supplier', 'keterangan', 'no_pr', 'status_po', 'status_histori', 'nama_status_po', 'grand_total', 'pengirim');
        
        // DB table to use
		$sTable = $this->view_po_hdr_approve;
    
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
			$row[] = $aRow['no_po'];
			$row[] = $aRow['tgl_po'];
			$row[] = set_month_to_string_ind($aRow['bulan']);
			$row[] = $aRow['tahun'];
			$row[] = $aRow['nama_supplier'];
            $row[] = $aRow['keterangan'];			
            $row[] = $aRow['no_pr'];
			$row[] = "<span class='label label-info'>".$aRow['nama_status_po']."</span>";
			$row[] = '<button value="'.$aRow['status_histori'].'" id="btn_'.$aRow['id_po'].'" class="histori btn btn-default btn-sm btn-clean" title="Tampilkan histori"><span class="fa fa-play"></span></button>';
			$row[] = number_format($aRow['grand_total']);
			$row[] = $aRow['pengirim'];
			$row[] = '<a title="Lihat Detail" href="pembelian/send_po/send_po_detail/?hid='.$aRow['id_po'].'"><span class="icon-text-align-justify"></span></a>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
    
	function send_po_detail() {
		$hid = $_GET['hid'];
		$this->session->set_userdata('hid', $hid);
				
		$data = array(
			'title' => 'Send Purchase Order (PO)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/send_po">Send Purchase Order (PO)</a></li>
							 <li>Send Purchase Order (PO) Detail</li>',
			'page_icon' => 'icon-link2',
			'page_title' => '',
			'page_subtitle' => '',			
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_send_po.js'></script>",
			'get_header' => $this->M_Send_Po->get_header($hid),
            'get_detail' => $this->M_Send_Po->get_detail($hid),
			'username' => $this->session->userdata('user_name')
		);
		$this->template->build('v_send_po_detail', $data);		
	}
	
	function simpan_kirim() {
        $del = $this->M_Send_Po->simpan_kirim();
        if ($del)
            echo "done";
        else
            echo "fail";
    }

} 