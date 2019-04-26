<?php defined('BASEPATH') OR exit('No direct script access allowed');

class pengawasan_pemeliharaan extends CI_Controller {
	
	var $tbl_pp_hdr		= 'ck_tbl_logistik_pengawasanpemeliharaan_hdr';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_pengawasan_pemeliharaan');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Pengawasan & Pemeliharaan',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Maintenance</li>
                             <li><a href="logistik/pengawasan_pemeliharaan">Pengawasan & Pemeliharaan</a></li>',
			'page_icon' => 'fa fa-hourglass-half',
			'page_title' => 'Pengawasan & Pemeliharaan',
			'page_subtitle' => 'Pengawasan & Pemeliharaan',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_pengawasanpemeliharaan').addClass('active');</script>"
		);
		$this->template->build('v_pengawasan_pemeliharaan', $data);
	}
	
	public function get_data() {		
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'no_transaksi', 'tgl_transaksi', 'pic', 'kategori', 'keterangan');
        
        // DB table to use
		$sTable = $this->tbl_pp_hdr;
    
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
            $row[] = $aRow['pic'];
			$row[] = $aRow['kategori'] == '1' ? 'Rutin' : 'Non Rutin';
			$row[] = $aRow['keterangan'];
			$row[] = '<a title="Edit Detail" href="logistik/pengawasan_pemeliharaan/pengawasan_pemeliharaan_detail/?hid='.$aRow['id'].'"><span class="icon-pencil5"></span></a>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function create_header() {		
        $data = array(
			'no_transaksi'          => $this->m_pengawasan_pemeliharaan->create_doc_no(date('n'), date('Y')),
			'tgl_transaksi'         => $this->config->item('FORMAT_NOW_DATE_TO_INSERT'),
			'pic'           		=> $this->input->post('txtpic'),
			'kategori'           	=> $this->input->post('rdokategori'),
			'keterangan'           	=> $this->input->post('txtketerangan'),
			'created_by'            => $this->session->userdata('user_name'),
			'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by'           => $this->session->userdata('user_name'),
			'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
		$create_header = $this->m_pengawasan_pemeliharaan->create_header($data);	
		$hid = $this->session->userdata('hid');
		if ($create_header) {
			redirect('logistik/pengawasan_pemeliharaan/pengawasan_pemeliharaan_detail/?hid='.$hid);
		} else {
			echo "<script>alert('Fail')</script>";
		}		 
	}
	
	function create_detail() {
        $data = array(
            'id_header'             => $this->input->post('id_header'),
            'uraian'             	=> $this->input->post('uraian'),
			'tindak_lanjut'         => $this->input->post('tindak_lanjut'),
            'status'          		=> $this->input->post('status'),
            'created_by'            => $this->session->userdata('user_name'),
            'created_date'          => date($this->config->item('FORMAT_DATETIME_TO_INSERT')),
            'modified_by'           => $this->session->userdata('user_name'),
            'modified_date'         => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
        );
        $create_detail = $this->m_pengawasan_pemeliharaan->create_detail($data);
        if ($create_detail)
            echo "done";
        else
            echo "fail";
    }
		
	function pengawasan_pemeliharaan_detail() {
		$hid = $this->input->get('hid');
		
		$data = array(
			'title' => 'Pengawasan & Pemeliharaan',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Maintenance</li>
                             <li><a href="logistik/pengawasan_pemeliharaan">Pengawasan & Pemeliharaan</a></li>
							 <li>Pengawasan & Pemeliharaan Detail</li>',
			'page_icon' => 'fa fa-hourglass-half',
			'page_title' => '',
			'page_subtitle' => '',
			'get_header' => $this->m_pengawasan_pemeliharaan->get_header($hid),
            'get_detail' => $this->m_pengawasan_pemeliharaan->get_detail($hid),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_pengawasanpemeliharaan').addClass('active');</script>"
		);
		$this->template->build('v_pengawasan_pemeliharaan_detail', $data);		
	}
    
    function detail_list() {
		$hid = $this->input->get('hid');
        $data = $this->m_pengawasan_pemeliharaan->detail_list($hid);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
                echo '<tr>';
                echo    '<td id="id" style="display:none;">'.$row->id.'</td>';
                echo    '<td id="no" style="width: 4%; text-align: center;">'.$i.'</td>';
                echo    '<td id="uraian" style="width: 38%;">'.$row->uraian.'</td>';
				echo    '<td id="tindak_lanjut" style="width: 38%;">'.$row->tindak_lanjut.'</td>';
				echo    '<td id="status" style="width: 11%; text-align: center;">'.$row->status.'</td>';
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
        $del = $this->m_pengawasan_pemeliharaan->delete_item_detail();        
        if ($del) {
			echo "done";
        } else {
            echo "fail";
		}
    }
    
    function update_item_detail() {		
        $del = $this->m_pengawasan_pemeliharaan->update_item_detail();
        if ($del) {
            echo "done";
        } else {
            echo "fail";
		}
    }
		
	function update_header() {
        $del = $this->m_pengawasan_pemeliharaan->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }

} 