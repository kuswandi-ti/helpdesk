<?php defined('BASEPATH') OR exit('No direct script access allowed');

class quarantine extends CI_Controller {
	
	var $tbl_quarantine_hdr		= 'ck_tbl_logistik_quarantine_hdr';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_quarantine');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Quarantine & Remove Produk',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Manajemen Data</li>
							 <li>Mutasi Stok</li>
                             <li><a href="logistik/quarantine">Quarantine & Remove Produk</a></li>',
			'page_icon' => 'icon-shield-alert',
			'page_title' => 'Quarantine & Remove Produk',
			'page_subtitle' => 'Quarantine & Remove Produk',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_quarantineproduk').addClass('active');</script>"
		);
		$this->template->build('v_quarantine', $data);
	}
	
	public function get_data() {		
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'no_transaksi', 'tgl_transaksi', 'keterangan');
        
        // DB table to use
		$sTable = $this->tbl_quarantine_hdr;
    
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
			$row[] = $aRow['no_transaksi'];
			$row[] = date_format(new DateTime($aRow['tgl_transaksi']), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
            $row[] = $aRow['keterangan'];
			$row[] = '<a title="Edit Detail" href="logistik/quarantine/quarantine_detail/?hid='.$aRow['id'].'"><span class="icon-pencil5"></span></a>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function get_produk($id_po) {
		$this->m_quarantine->get_produk($id_po);
	}
	
	function get_data_produk($id_produk) {
		$res = $this->m_quarantine->get_data_produk($id_produk);
		if ($res->num_rows() > 0) {
			foreach ($res->result_array() as $row) {
				$data['id_produk'] = $row['id_produk'];
				$data['batch_number'] = $row['batch_number'];
				$data['expired_date'] = $row['expired_date'];
			}
		} else {
			$data['id_produk'] = '';
			$data['batch_number'] = '';
			$data['expired_date'] = '';
		}
		echo json_encode($data);
	}
	
	function get_stok() {
		$id_produk = $this->input->post('id_produk');
		$batch_number = $this->input->post('batch_number');
		$expired_date = $this->input->post('expired_date');
		$res = $this->m_quarantine->get_stok($id_produk, $batch_number, $expired_date);
		if ($res->num_rows() > 0) {
			foreach ($res->result_array() as $row) {
				$data['stok'] = $row['stok'];
			}
		} else {
			$data['stok'] = 0;
		}
		echo json_encode($data);
	}
	
	function create_header() {		
        $data = array(
			'no_transaksi'          => $this->m_quarantine->create_doc_no(date('n'), date('Y')),
			'tgl_transaksi'         => $this->config->item('FORMAT_NOW_DATE_TO_INSERT'),
			'keterangan'           	=> $this->input->post('txtketerangan'),
			'created_by'            => $this->session->userdata('user_name'),
			'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by'           => $this->session->userdata('user_name'),
			'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
		$create_header = $this->m_quarantine->create_header($data);	
		$hid = $this->session->userdata('hid');
		if ($create_header) {
			redirect('logistik/quarantine/quarantine_detail/?hid='.$hid);
		} else {
			echo "<script>alert('Fail')</script>";
		}		 
	}
	
	function create_detail() {
        $data = array(
            'id_header'             => $this->input->post('id_header'),
            'id_produk'             => $this->input->post('id_produk'),
			'id_po'             	=> $this->input->post('id_po'),
            'batch_number'          => $this->input->post('batch_number'),
			'expired_date'			=> date_format(new DateTime($this->input->post('expired_date')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'qty'      				=> $this->input->post('qty'),
			'alasan'      			=> $this->input->post('alasan'),
            'created_by'            => $this->session->userdata('user_name'),
            'created_date'          => date($this->config->item('FORMAT_DATETIME_TO_INSERT')),
            'modified_by'           => $this->session->userdata('user_name'),
            'modified_date'         => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
        );
        $create_detail = $this->m_quarantine->create_detail($data);
        if ($create_detail)
            echo "done";
        else
            echo "fail";
    }
	
	function quarantine_detail() {
		$hid = $this->input->get('hid');
		
		$data = array(
			'title' => 'Quarantine & Remove Produk',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Manajemen Data</li>
							 <li>Mutasi Stok</li>
                             <li><a href="logistik/quarantine">Quarantine & Remove Produk</a></li>
							 <li>Quarantine & Remove Produk Detail</li>',
			'page_icon' => 'icon-shield-alert',
			'page_title' => '',
			'page_subtitle' => '',
			'get_header' => $this->m_quarantine->get_header($hid),
            'get_detail' => $this->m_quarantine->get_detail($hid),
			'get_po' => $this->m_quarantine->get_po(),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_quarantineproduk').addClass('active');</script>"
		);
		$this->template->build('v_quarantine_detail', $data);		
	}
    
    function detail_list() {
		$hid = $this->input->get('hid');
        $data = $this->m_quarantine->detail_list($hid);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
                echo '<tr>';
                echo    '<td id="id_detail" style="display:none;">'.$row->id_detail.'</td>';
                echo    '<td id="no" style="width: 4%; text-align: center;">'.$i.'</td>';
                echo    '<td id="id_produk" style="display:none;">'.$row->id_produk.'</td>';
                echo    '<td id="nama_produk" style="width: 29%;">'.$row->nama_produk.'</td>';
                echo    '<td id="id_kemasan" style="display:none;">'.$row->id_kemasan.'</td>';
				echo    '<td id="nama_kemasan" style="width: 6%; text-align: center;">'.$row->nama_kemasan.'</td>';
				echo    '<td id="batch_number" style="width: 12%; text-align: center;">'.$row->batch_number.'</td>';
				echo    '<td id="expired_date" style="width: 10%; text-align: center;">'.date_format(new DateTime($row->expired_date), $this->config->item('FORMAT_DATE_TO_DISPLAY')).'</td>';
				echo    '<td id="qty" style="width: 8%; text-align: right;">'.number_format($row->qty).'</td>';
				echo    '<td id="alasan">'.$row->alasan.'</td>';
				echo    '<td id="no_po" style="text-align: center;">'.$row->no_po.'</td>';
                echo    '<td style="width: 9%; text-align: center;">';
				echo 		'<span id="edit_detail" title="Edit" class="icon-register text-info text-lg" style="cursor: pointer"></span>&nbsp;
							 <span id="hapus_detail" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
                echo    '</td>';
                echo '</tr>';
                
                $i++;
            }
        }		
    }
		
    function delete_item_detail() {        
        $del = $this->m_quarantine->delete_item_detail();        
        if ($del) {
			echo "done";
        } else {
            echo "fail";
		}
    }
    
    function update_item_detail() {		
        $del = $this->m_quarantine->update_item_detail();
        if ($del) {
            echo "done";
        } else {
            echo "fail";
		}
    }
		
	function update_header() {
        $del = $this->m_quarantine->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }

} 