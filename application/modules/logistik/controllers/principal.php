<?php defined('BASEPATH') OR exit('No direct script access allowed');

class principal extends CI_Controller {
	
	var $view_principal		= 'ck_principal_view';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_principal');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Principal',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
			                 <li>Manajemen Data</li>
			                 <li>Mitra Usaha</li>
                             <li><a href="logistik/principal">Principal</a></li>',
			'page_icon' => 'icon-group-work',
			'page_title' => 'Principal',
			'page_subtitle' => 'Data Master Principal',
			'get_tipe_pembayaran' => $this->m_principal->get_tipe_pembayaran(),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_principal').addClass('active');</script>"
		);
		$this->template->build('v_principal', $data);
	}
	
	public function get_data() {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_principal', 'nama_principal', 'kelompok', 'npwp', 'id_tipe_pembayaran', 
		                  'nama_tipe_pembayaran', 'no_rekening', 
						  'nama_rekening', 'cabang_rekening', 'deskripsi', 'activated');
        
        // DB table to use
		$sTable = $this->view_principal;
						
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
			$row[] = $aRow['nama_principal'];
			$row[] = $aRow['nama_tipe_pembayaran'];
			$row[] = $aRow['npwp'];			
			$row[] = $aRow['no_rekening'];
			$row[] = $aRow['nama_rekening'];
			$row[] = $aRow['cabang_rekening'];
			$row[] = $aRow['deskripsi'];
			if ($aRow['activated'] == '1') { // Aktif
				$row[] = '<span class="label label-success">Aktif</span>';
			} elseif ($aRow['activated'] == '0') { // Non Aktif
				$row[] = '<span class="label label-danger">Non Aktif</span>';
			}
			$row[] = '<button id="btn-edit" value="'.$aRow['id_principal'].'" style="border: none;" class="btn-info btn-edit" title="Edit Data"><span class="icon-pencil"></span></button>
			          <button id="btn-alamat" value="'.$aRow['id_principal'].'" style="border: none;" class="btn-warning btn-alamat" title="Alamat"><span class="icon-home2"></span></button>';
			
			$output['aaData'][] = $row;
        }
		// padding: 3px 10px 3px 10px;
        echo json_encode($output);
    }
	
	function principal_alamat_view() {
		$this->session->set_flashdata('pid', $this->input->post('pid'));
		redirect('logistik/principal_alamat', '_blank');
	}
	
  	function principal_view() {
		$id = $this->input->post('id');
		$principal = $this->m_principal->principal_view($id);
		foreach ($principal->result_array() as $row) {
			$data['id_principal'] = $row['id_principal'];
			$data['nama_principal'] = $row['nama_principal'];
			$data['kelompok'] = $row['kelompok'];
			$data['npwp'] = $row['npwp'];
			$data['id_tipe_pembayaran'] = $row['id_tipe_pembayaran'];
			$data['nama_tipe_pembayaran'] = $row['nama_tipe_pembayaran'];
			$data['no_rekening'] = $row['no_rekening'];
			$data['nama_rekening'] = $row['nama_rekening'];
			$data['cabang_rekening'] = $row['cabang_rekening'];
			$data['deskripsi'] = $row['deskripsi'];
			$data['activated'] = $row['activated'];
		}
    	echo json_encode($data);
  	}

	function principal_create() {
		$notifikasi = $this->m_principal->principal_create();
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

	function principal_update() {
		$notifikasi = $this->m_principal->principal_update();
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

	function principal_delete($id = '') {
		$notifikasi = $this->m_principal->principal_delete($id);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

}
