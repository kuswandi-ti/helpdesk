<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Approve_Pb extends CI_Controller {
    
    var $tbl_pb_hdr			= 'ck_tbl_beli_pengajuanpembayaran_hdr';
	var $tbl_pb_dtl  		= 'ck_tbl_beli_pengajuanpembayaran_dtl';
	
	var $view_pb_hdr		= 'ck_view_beli_pengajuanpembayaran_hdr';

	public function __construct() {
		parent::__construct();
		$this->load->model('M_Approve_Pb');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Approve Pengajuan Pembayaran (PB)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/approve_pb">Approve Pengajuan Pembayaran (PB)</a></li>',
			'page_icon' => 'icon-link2',
			'page_title' => 'Approve Pengajuan Pembayaran (PB)',
			'page_subtitle' => 'Approve pengajuan pembayaran',
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_approve_pb.js'></script>"
		);
		$this->template->build('v_approve_pb', $data);
	}
	
	public function get_data() {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_header', 'no_transaksi', 'tgl_transaksi', 'id_supplier', 'nama_supplier', 'keterangan', 'grand_total');
        
        // DB table to use
        $sTable = $this->view_pb_hdr;
    
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
        //$rResult = $this->db->get($sTable);
		$rResult = $this->db->get_where($sTable, array('approve' => '0'));
    
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
			$row[] = $aRow['no_transaksi'];
			$row[] = date_format(new DateTime($aRow['tgl_transaksi']), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
			$row[] = $aRow['id_supplier'];
			$row[] = $aRow['nama_supplier'];
			$row[] = $aRow['keterangan'];
			$row[] = number_format($aRow['grand_total']);
			$row[] = '<a title="Edit Detail" href="pembelian/approve_pb/approve_pb_detail/?hid='.$aRow['id_header'].'&sid='.$aRow['id_supplier'].'"><span class="icon-pencil4"></span></a>&nbsp;&nbsp;';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function approve_pb_detail() {
		$hid = $this->input->get('hid');
		$sid = $this->input->get('sid');
				
		$data = array(
			'title' => 'Approve Pengajuan Pembayaran (PB)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/approve_pb">Approve Pengajuan Pembayaran (PB)</a></li>
							 <li>Approve Pengajuan Pembayaran (PB) Detail</li>',
			'page_icon' => 'icon-link-3',
			'page_title' => '',
			'page_subtitle' => '',			
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_approve_pb.js'></script>",
			'get_header' => $this->M_Approve_Pb->get_header($hid),
			'get_detail' => $this->M_Approve_Pb->get_detail($hid)
		);
		$this->template->build('v_approve_pb_detail', $data);		
	}
	
	function detail_list() {
		$hid = $this->input->get('hid');
        $data = $this->M_Approve_Pb->detail_list($hid);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
				echo '<tr>';
				echo    '<td id="id_detail" style="display:none;">'.$row->id_detail.'</td>';
				echo    '<td id="no" style="text-align: center;">'.$i.'</td>';
				echo    '<td id="no_invoice_supplier" style="text-align: center;">'.$row->no_invoice_supplier.'</td>';
				echo    '<td id="tgl_invoice_supplier" style="text-align: center;">'.date_format(new DateTime($row->tgl_invoice_supplier), $this->config->item('FORMAT_DATE_TO_DISPLAY')).'</td>';
				echo    '<td id="tgl_jatuh_tempo" style="text-align: center;">'.date_format(new DateTime($row->tgl_jatuh_tempo), $this->config->item('FORMAT_DATE_TO_DISPLAY')).'</td>';
				echo    '<td id="amount_invoice" style="text-align: right;">'.number_format($row->amount_invoice).'</td>';
				echo '</tr>';
				
				$i++;
            }
        }		
    }
	
	function update_header() {		
        $del = $this->M_Approve_Pb->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }

} 