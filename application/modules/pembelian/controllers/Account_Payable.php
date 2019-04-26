<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account_Payable extends CI_Controller {
	
	var $view_gr_hdr	= 'ck_view_beli_goodsreceive_hdr';

	public function __construct() {
		parent::__construct();
		$this->load->model('M_Account_Payable');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Account Payable (AP)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/account_payable">Account Payable (AP)</a></li>',
			'page_icon' => 'icon-cash-dollar',
			'page_title' => 'Account Payable (AP)',
			'page_subtitle' => 'Pengakuan hutang usaha dari supplier',
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_account_payable.js'></script>"
		);
		$this->template->build('v_account_payable', $data);
	}
	
	public function get_data() {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_header', 'no_transaksi', 'tgl_transaksi', 'id_supplier', 'nama_supplier', 'keterangan');
        
        // DB table to use
        $sTable = $this->view_gr_hdr;
    
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
        $rResult = $this->db->get_where($sTable, array('flag_piutang' => '0'));
    
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
			$row[] = '<a href="#modal-detail" id="id" data-toggle="modal" data-id='.$aRow['id_header'].'>'.$aRow['no_transaksi'].'</a>';
			$row[] = date_format(new DateTime($aRow['tgl_transaksi']), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
			$row[] = $aRow['id_supplier'];
			$row[] = $aRow['nama_supplier'];
			$row[] = $aRow['keterangan'];
			$row[] = '<input type="checkbox" name="selected_id" id="checkbox" class="checkbox" value="'.$aRow['id_header'].'" />';	
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function detail_list() {
		echo "<table class='table table-striped'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th style='display:none;'>ID</th>";
					echo "<th style='text-align: center;'>No.</th>";
					echo "<th style='display:none;'>Produk ID</th>";
					echo "<th>Nama Produk</th>";
					echo "<th style='display:none;'>Kemasan ID</th>";
					echo "<th style='text-align: center;'>Kemasan</th>";
					echo "<th style='text-align: center;'>Batch Number</th>";
					echo "<th style='text-align: center;'>Expired Date</th>";
					echo "<th style='text-align: right;'>Qty GR</th>";
				echo "</tr>";
			echo "</thead>";
		
		$hid = $this->input->post('rowid');
        $data = $this->M_Account_Payable->detail_list($hid);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
				echo "<tbody>";
					echo '<tr>';
					echo    '<td style="display:none;">'.$row->id_detail.'</td>';
					echo    '<td style="text-align: center; width: 4%;">'.$i.'</td>';
					echo    '<td style="display:none;">'.$row->id_produk.'</td>';
					echo    '<td>'.$row->nama_produk.'</td>';
					echo    '<td style="display:none;">'.$row->id_kemasan.'</td>';
					echo    '<td style="text-align: center; width: 15%;">'.$row->nama_kemasan.'</td>';
					echo    '<td style="text-align: center; width: 15%;">'.$row->batch_number.'</td>';
					echo    '<td style="text-align: center; width: 15%;">'.date_format(new DateTime($row->expired_date), $this->config->item('FORMAT_DATE_TO_DISPLAY')).'</td>';
					echo    '<td style="text-align: right; width: 15%;">'.number_format($row->qty_gr).'</td>';
					echo '</tr>';
				echo "</tbody>";
                
                $i++;
            }
        }

		echo "</table>";		
    }
	
	function update_header() {		
        $del = $this->M_Account_Payable->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }

} 