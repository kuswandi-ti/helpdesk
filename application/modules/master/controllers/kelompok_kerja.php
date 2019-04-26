<?php defined('BASEPATH') OR exit('No direct script access allowed');

class kelompok_kerja extends CI_Controller {
	
	var $tbl_kelompok_kerja		= 'ck_kelompok_kerja';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_kelompok_kerja');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Kelompok Kerja',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Master Data</li>
							 <li>Struktur Organisasi</li>
                             <li><a href="master/kelompok_kerja">Kelompok Kerja</a></li>',
			'page_icon' => 'icon-users',
			'page_title' => 'Kelompok Kerja',
			'page_subtitle' => 'Data Master Kelompok Kerja',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_master_kelompok_kerja').addClass('active');</script>"
		);
		$this->template->build('v_kelompok_kerja', $data);
	}
	
	public function get_data() {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'nama', 'level', 'deskripsi', 'activated');
        
        // DB table to use
		$sTable = $this->tbl_kelompok_kerja;
						
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
			$row[] = $aRow['nama'];
			$row[] = $aRow['level'];
			$row[] = $aRow['deskripsi'];			
			if ($aRow['activated'] == '1') {
				$row[] = '<span class="label label-success">Aktif</span>';
			} elseif ($aRow['activated'] == '0') {
				$row[] = '<span class="label label-danger">Non Aktif</span>';
			}
			$row[] = '<button id="btn-edit" value="'.$aRow['id'].'" style="border: none;" class="btn-info btn-edit" title="Edit Data"><span class="icon-pencil"></span></button>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
  	function kelompok_kerja_view() {
		$id = $this->input->post('id');
		$results = $this->m_kelompok_kerja->kelompok_kerja_view($id);
		foreach ($results->result_array() as $row) {
			$data['id'] = $row['id'];
			$data['nama'] = $row['nama'];
			$data['level'] = $row['level'];
			$data['deskripsi'] = $row['deskripsi'];
			$data['activated'] = $row['activated'];
		}
    	echo json_encode($data);
  	}

	function kelompok_kerja_create() {
		$notifikasi = $this->m_kelompok_kerja->kelompok_kerja_create();
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

	function kelompok_kerja_update() {
		$notifikasi = $this->m_kelompok_kerja->kelompok_kerja_update();
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

	function kelompok_kerja_delete($id = '') {
		$notifikasi = $this->m_kelompok_kerja->kelompok_kerja_delete($id);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

}
