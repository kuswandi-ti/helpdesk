<?php defined('BASEPATH') OR exit('No direct script access allowed');

class produk extends CI_Controller {
	
	var $tbl_produk					= 'ck_produk';
	var $tbl_bentuk_sediaan			= 'ck_produk_bentuk_sediaan';
	var $tbl_produk_satuan			= 'ck_produk_satuan';
	var $tbl_kemasan_produk			= 'ck_produk_kemasan';
	var $tbl_kelas_terapi			= 'ck_produk_klasifikasi';
	var $tbl_produk_fungsi			= 'ck_produk_fungsi';
	
	var $view_produk_all			= 'ck_view_logistik_produk_all';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_produk');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Produk',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
			                 <li>Manajemen Data</li>
			                 <li>Produk</li>
                             <li><a href="logistik/produk">Produk Utama</a></li>',
			'page_icon' => 'icon-icons',
			'page_title' => 'Produk',
			'page_subtitle' => 'Data Master Produk',
			'get_bentuk_sediaan' => $this->m_produk->get_data_master($this->tbl_bentuk_sediaan),
			'get_satuan_produk' => $this->m_produk->get_data_master($this->tbl_produk_satuan),
			'get_kemasan_produk' => $this->m_produk->get_data_master($this->tbl_kemasan_produk),
			'get_perbekalan_produk' => $this->m_produk->get_data_perbekalan(),
			'get_fungsi_produk' => $this->m_produk->get_data_master($this->tbl_produk_fungsi),
			'get_principal' => $this->m_produk->get_mitra_usaha('1'),
			'get_supplier' => $this->m_produk->get_mitra_usaha('2'),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_produk').addClass('active');</script>"
		);
		$this->template->build('v_produk', $data);
	}
	
	public function get_data() {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_produk', 'kode_produk', 'nama_produk_ori', 'nama_perbekalan_produk', 
						  'nama_bentuk_sediaan_produk', 'kadar_isi', 'nama_kadar_satuan',
						  'nama_kemasan', 'nama_principal', 'activated');
        
        // DB table to use
		$sTable = $this->view_produk_all;
						
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
			$row[] = $aRow['kode_produk'];
			$row[] = $aRow['nama_produk_ori'];
			$row[] = $aRow['nama_perbekalan_produk'];
			$row[] = $aRow['nama_bentuk_sediaan_produk'];
			$row[] = $aRow['kadar_isi'];
			$row[] = $aRow['nama_kadar_satuan'];
			$row[] = $aRow['nama_kemasan'];
			$row[] = $aRow['nama_principal'];
			if ($aRow['activated'] == '1') { // Aktif
				$row[] = '<span class="label label-success">Aktif</span>';
			} elseif ($aRow['activated'] == '0') { // Non Aktif
				$row[] = '<span class="label label-danger">Non Aktif</span>';
			}
			$row[] = '<button type="button" class="btn btn-default btn-xs btn-edit" value="'.$aRow['id_produk'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function get_data_kelompok() {
		$id_perbekalan = $this->input->post('id_perbekalan');
		$this->m_produk->get_data_kelompok($id_perbekalan);
	}
	
	function get_data_golongan() {
		$id_kelompok = $this->input->post('id_kelompok');
		$this->m_produk->get_data_golongan($id_kelompok);
	}
	
	function get_data_kelas_terapi() {
		$this->m_produk->get_data_kelas_terapi();
	}
	
	function get_data_jenis() {
		$id_golongan = $this->input->post('id_golongan');
		$this->m_produk->get_data_jenis($id_golongan);
	}
	
	function get_data_lokasi() {
		$id_kelompok = $this->input->post('id_kelompok_s');
		$this->m_produk->get_data_lokasi($id_kelompok);
	}
	
	function produk_exists() {
		$nama = $this->input->post('nama');
		$this->db->where('nama', $nama);
		$query = $this->db->get($this->tbl_produk);
		echo $query->num_rows();
	}
	
  	function produk_view() {
		$id = $this->input->post('id');
		$produk = $this->m_produk->produk_view($id);
		foreach ($produk->result_array() as $row) {
			$data['id_produk'] = $row['id_produk'];
			$data['kode_produk'] = $row['kode_produk'];
			$data['nama_produk'] = $row['nama_produk_ori'];
			$data['id_produk_bentuk_sediaan'] = $row['id_produk_bentuk_sediaan'];
			$data['id_fungsi'] = $row['id_fungsi'];
			$data['id_kadar_satuan'] = $row['id_kadar_satuan'];
			$data['kadar_isi'] = $row['kadar_isi'];
			$data['activated'] = $row['activated'];
			$data['komposisi'] = $row['komposisi'];
			$data['indikasi'] = $row['indikasi'];
			$data['deskripsi'] = $row['deskripsi'];
			$data['id_kemasan'] = $row['id_kemasan'];
			$data['id_kemasan_primer'] = $row['id_kemasan_primer'];
			$data['isi_kemasan_primer'] = $row['isi_kemasan_primer'];
			$data['id_kemasan_sekunder'] = $row['id_kemasan_sekunder'];
			$data['isi_kemasan_sekunder'] = $row['isi_kemasan_sekunder'];
			$data['id_kemasan_tersier'] = $row['id_kemasan_tersier'];
			$data['isi_kemasan_tersier'] = $row['isi_kemasan_tersier'];
			$data['id_produk_perbekalan'] = $row['id_produk_perbekalan'];
			$data['id_produk_kelompok'] = $row['id_produk_kelompok'];
			$data['id_produk_golongan'] = $row['id_produk_golongan'];
			$data['id_produk_jenis'] = $row['id_produk_jenis'];
			$data['id_klasifikasi'] = $row['id_klasifikasi'];
			$data['id_principal'] = $row['id_principal'];
			$data['min_stok'] = $row['min_stok'];
			$data['max_stok'] = $row['max_stok'];
			$data['expired_limit'] = $row['expired_limit'];
			$data['lead_time'] = $row['lead_time'];
			$data['harga_jual_min'] = $row['harga_jual_min'];
			$data['harga_jual_max'] = $row['harga_jual_max'];
			$data['harga_beli'] = $row['harga_beli'];
			$data['diskon_max'] = $row['diskon_max'];
			$data['gambar_1'] = $row['gambar_1'];
			$data['gambar_2'] = $row['gambar_2'];
			$data['gambar_3'] = $row['gambar_3'];
		}
		
		$arr_produk_supplier = [];
		$produk_supplier = $this->m_produk->produk_supplier_view($id);
		foreach ($produk_supplier->result_array() as $row) {
			$arr_produk_supplier[] = $row['id_supplier'];
		}
		$data['produk_supplier'] = implode(',', $arr_produk_supplier);
		
		$arr_produk_lokasi = [];
		$produk_lokasi = $this->m_produk->produk_lokasi_view($id);
		foreach ($produk_lokasi->result_array() as $row) {
			$arr_produk_lokasi[] = $row['id_lokasi'];
		}
		$data['produk_lokasi'] = implode(',', $arr_produk_lokasi);
		
		echo json_encode($data);
  	}

	function produk_create() {
		$notifikasi = $this->m_produk->produk_create();
		if ($notifikasi >= 1) {
			$notifikasi = " Selamat ! Anda berhasil";
		} else {
			$notifikasi = " Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

	function produk_update() {
		$notifikasi = $this->m_produk->produk_update();
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

	function produk_delete($id = '') {
		$notifikasi = $this->m_produk->produk_delete($id);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}

}
