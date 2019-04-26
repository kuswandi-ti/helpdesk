<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_data extends CI_Controller {
	
	var $tbl_asset		= 'ck_aset';

	public function __construct() {
		parent::__construct();
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
		$this->load->model('m_asset');
		$this->load->model('m_asset_kelompok');
	}
	
	public function index() {
		$data = array(
			'title' 					=> 'Asset',
			'breadcrumb_home_active' 	=> '',
            'breadcrumb' 				=> '<li>Master Data</li>
											<li>Sarana & Prasarana</li>
											<li><a href="master/asset_data">Asset</a></li>',
			'page_icon' 				=> 'icon-clipboard-pencil',
			'page_title' 				=> 'Asset',
			'page_subtitle' 			=> 'Data Master Asset',
			'kel_asset'		 			=> $this->m_asset_kelompok->asset_kelompok(),
			'custom_scripts' 			=> "<script type='text/javascript'></script>",
		);
		$this->template->build('v_asset', $data);
	}

	
	public function get_data() {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'kode', 'nama', 'harga', 'diskon', 'harga_beli', 'tgl_perolehan', 'cara_perolehan', 'metode_bayar', 'kredit_dp', 'kredit_angsuran', 'kredit_mulai', 'kredit_hingga', 'prosentasi_penyusutan', 'masa_penyusutan', 'periode_penyusutan', 'tgl_penghapusan', 'yang_mengajukan', 'nama_pengguna', 'lokasi', 'kondisi', 'deskripsi', 'kelompok_id', 'nama_kelompok', 'pembelian_barang_id', 'activated', 'activated_by', 'activated_date', 'created_by', 'created_date', 'modified_by', 'modified_date');
        
        // DB table to use
		// $sTable = $this->tbl_asset;
		$sTable = "(
			select a.*, ak.nama as nama_kelompok from ck_aset a left join ck_aset_kelompok ak on ak.id = a.kelompok_id
		) tbl";
						
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
			if(!empty($aRow['diskon'])){
				$diskon = $aRow['diskon'].'%';
			}else{
				$diskon = '-';
			}
			// $no++;
			// $row[] = $no;
			$row[] = $aRow['kode'];
			$row[] = $aRow['nama'];
			$row[] = $aRow['nama_kelompok'];
			$row[] = $aRow['deskripsi'];
			$row[] = $aRow['nama_pengguna'];
			$row[] = $aRow['lokasi'];
			$row[] = $this->get_func->rupiah($aRow['harga']);
			$row[] = $diskon;
			$row[] = $this->get_func->rupiah($aRow['harga_beli']);
			$row[] = '
				<button id="btn-edit" value="'.$aRow['id'].'" class="btn btn-info btn-sm btn-edit" title="Edit Data"><span class="fa fa-pencil"></span></button>
			';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
  	function asset_view() {
		$id = $this->input->post('id');
		$results = $this->m_asset->asset_view($id);
		foreach ($results->result_array() as $row) {
			$data['id'] 				= $row['id'];
			$data['kode'] 				= $row['kode'];
			$data['nama'] 				= $row['nama'];
			$data['harga'] 				= $row['harga'];
			$data['diskon'] 			= $row['diskon'];
			$data['harga_beli'] 		= $row['harga_beli'];
			$data['tgl_perolehan'] 		= $this->get_func->formDate($row['tgl_perolehan']);
			$data['cara_perolehan'] 	= $row['cara_perolehan'];
			$data['metode_bayar'] 		= $row['metode_bayar'];
			$data['kredit_dp'] 			= $row['kredit_dp'];
			$data['kredit_angsuran'] 	= $row['kredit_angsuran'];
			$data['kredit_mulai'] 		= $this->get_func->formDate($row['kredit_mulai']);
			$data['kredit_hingga'] 		= $this->get_func->formDate($row['kredit_hingga']);
			$data['nama_pengguna'] 		= $row['nama_pengguna'];
			$data['lokasi'] 			= $row['lokasi'];
			$data['kondisi'] 			= $row['kondisi'];
			$data['kelompok_id'] 		= $row['kelompok_id'];
			$data['deskripsi'] 			= $row['deskripsi'];
		}
    	echo json_encode($data);
  	}
	

	function asset_create() {
		$notifikasi = $this->m_asset->create_asset();
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

	function asset_update() {
		$notifikasi = $this->m_asset->update_asset();
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}
	
}

?>