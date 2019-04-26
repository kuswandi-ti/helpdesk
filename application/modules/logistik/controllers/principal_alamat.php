<?php defined('BASEPATH') OR exit('No direct script access allowed');

class principal_alamat extends CI_Controller {
	
	var $view_alamat_principal	= 'ck_principal_alamat_view';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_principal_alamat');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Alamat Principal',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
			                 <li>Manajemen Data</li>
			                 <li>Mitra Usaha</li>
                             <li><a href="logistik/principal_alamat">Alamat Principal</a></li>',
			'page_icon' => 'icon-road-sign',
			'page_title' => 'Alamat Principal',
			'page_subtitle' => 'Data Master Alamat Principal',
			'get_principal' => $this->m_principal_alamat->get_principal(),
			'get_propinsi' => $this->m_principal_alamat->get_propinsi(),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_principal_alamat').addClass('active');</script>"
		);
		$this->template->build('v_principal_alamat', $data);
	}
	
	public function get_data($id_principal = '') {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'id_principal', 'nama_principal', 'nama_alamat', 'alamat', 
		                  'id_provinsi', 'nama_provinsi', 'id_kabupatenkota', 'nama_kabupatenkota', 
						  'kode_pos', 'telepon', 'faks', 'email', 'website', 'longitude', 'latitude', 
						  'is_default', 'pic_nama', 'pic_jabatan', 'pic_hp', 'deskripsi', 
						  'foto_1', 'foto_2', 'foto_3', 'activated');
        
        // DB table to use
		$sTable = "(SELECT
						*
				    FROM ".
						$this->view_alamat_principal."
				    WHERE
						id_principal = '".$id_principal."') principal_alamat";
						
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
			$row[] = $aRow['nama_alamat'];
			$row[] = $aRow['alamat'];
			$row[] = $aRow['telepon'];			
			$row[] = $aRow['email'];
			$row[] = $aRow['pic_nama'];
			$row[] = $aRow['pic_jabatan'];
			$row[] = $aRow['pic_hp'];
			if ($aRow['activated'] == '1') { // Aktif
				$row[] = '<span class="label label-success">Aktif</span>';
			} elseif ($aRow['activated'] == '0') { // Non Aktif
				$row[] = '<span class="label label-danger">Non Aktif</span>';
			}
			$row[] = '<button id="btn-edit" value="'.$aRow['id'].'" style="border: none;" class="btn-info btn-edit" title="Edit Data"><span class="icon-pencil"></span></button>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function get_kabkota($id) {
		$this->m_principal_alamat->get_kabkota($id);
	}
	
  	function principal_alamat_view() {
		$id = $this->input->post('id');
		$principal = $this->m_principal_alamat->principal_alamat_view($id);
		foreach ($principal->result_array() as $row) {
			$data['id'] = $row['id'];
			$data['id_principal'] = $row['id_principal'];
			$data['nama_principal'] = $row['nama_principal'];
			$data['nama_alamat'] = $row['nama_alamat'];
			$data['alamat'] = $row['alamat'];
			$data['id_provinsi'] = $row['id_provinsi'];
			$data['nama_provinsi'] = $row['nama_provinsi'];
			$data['id_kabupatenkota'] = $row['id_kabupatenkota'];
			$data['nama_kabupatenkota'] = $row['nama_kabupatenkota'];
			$data['kode_pos'] = $row['kode_pos'];
			$data['telepon'] = $row['telepon'];
			$data['faks'] = $row['faks'];
			$data['email'] = $row['email'];
			$data['website'] = $row['website'];
			$data['longitude'] = $row['longitude'];
			$data['latitude'] = $row['latitude'];
			$data['is_default'] = $row['is_default'];
			$data['pic_nama'] = $row['pic_nama'];
			$data['pic_jabatan'] = $row['pic_jabatan'];
			$data['pic_hp'] = $row['pic_hp'];
			$data['deskripsi'] = $row['deskripsi'];
			$data['foto_1'] = $row['foto_1'];
			$data['foto_2'] = $row['foto_2'];
			$data['foto_3'] = $row['foto_3'];
		}
    	echo json_encode($data);
  	}

	function principal_alamat_create() {
		$notifikasi = $this->m_principal_alamat->principal_alamat_create();
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

	function principal_alamat_update() {
		$notifikasi = $this->m_principal_alamat->principal_alamat_update();
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

	function principal_alamat_delete($id = '') {
		$notifikasi = $this->m_principal_alamat->principal_alamat_delete($id);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

}
