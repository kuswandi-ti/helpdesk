<?php defined('BASEPATH') OR exit('No direct script access allowed');

class lokasi_produk extends CI_Controller {
	
	var $tbl_lokasi			= 'ck_lokasi';
	var $tbl_perbekalan		= 'ck_produk_perbekalan';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_lokasi_produk');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Lokasi Penyimpanan Produk',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
			                 <li>Manajemen Data</li>
			                 <li>Produk</li>
                             <li><a href="logistik/lokasi_produk">Lokasi Penyimpanan Produk</a></li>',
			'page_icon' => 'fa fa-anchor',
			'page_title' => 'Lokasi Penyimpanan Produk',
			'page_subtitle' => 'Data Master Lokasi Penyimpanan Produk',
			'get_kelompok_produk' => $this->m_lokasi_produk->get_kelompok_produk(),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_lokasiproduk').addClass('active');</script>"
		);
		$this->template->build('v_lokasi_produk', $data);
	}
	
	public function get_data() {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'kode', 'id_kelompok_produk', 'nama_kelompok_produk', 'lorong', 'rak', 'baris', 'kolom', 'deskripsi', 'gambar', 'activated');
        
        // DB table to use
		$sTable = "(SELECT
						a.id,
						a.kode,
						a.id_kelompok_produk,
						b.nama AS nama_kelompok_produk,
						a.lorong,
						a.rak,
						a.baris,
						a.kolom,
						a.deskripsi,
						a.gambar,
						a.activated
					FROM
						".$this->tbl_lokasi." a
						LEFT OUTER JOIN ".$this->tbl_perbekalan." b ON a.id_kelompok_produk = b.id
							AND b.level = '2') ck_lokasi";
						
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
			$row[] = $aRow['kode'];
			$row[] = $aRow['nama_kelompok_produk'];
			$row[] = $aRow['lorong'];
			$row[] = $aRow['rak'];
			$row[] = $aRow['baris'];
			$row[] = $aRow['kolom'];
			$row[] = $aRow['deskripsi'];
			if ($aRow['activated'] == '1') { // Aktif
				$row[] = '<span class="label label-success">Aktif</span>';
			} elseif ($aRow['activated'] == '0') { // Non Aktif
				$row[] = '<span class="label label-danger">Non Aktif</span>';
			}
			$row[] = '<button type="button" class="btn btn-default btn-xs btn-edit" value="'.$aRow['id'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
  	function lokasi_produk_view() {
		$id = $this->input->post('id');
		$lokasi_produk = $this->m_lokasi_produk->lokasi_produk_view($id);
		foreach ($lokasi_produk->result_array() as $row) {
			$data['id'] = $row['id'];
			$data['kode'] = $row['kode'];
			$data['id_kelompok_produk'] = $row['id_kelompok_produk'];
			$data['nama_kelompok_produk'] = $row['nama_kelompok_produk'];
			$data['lorong'] = $row['lorong'];
			$data['rak'] = $row['rak'];
			$data['baris'] = $row['baris'];
			$data['kolom'] = $row['kolom'];
			$data['deskripsi'] = $row['deskripsi'];			
			$data['gambar'] = $row['gambar'];
		}
    	echo json_encode($data);
  	}

	function lokasi_produk_create() {
		$notifikasi = $this->m_lokasi_produk->lokasi_produk_create();
		if ($notifikasi) {
			redirect('logistik/lokasi_produk');
		} else {
			echo "<script>alert('Fail')</script>";
		}
	}

	function lokasi_produk_update() {
		$notifikasi = $this->m_lokasi_produk->lokasi_produk_update();
		if ($notifikasi) {
			redirect('logistik/lokasi_produk');
		} else {
			echo "<script>alert('Fail')</script>";
		}
	}

	function lokasi_produk_delete($id = '') {
		$notifikasi = $this->m_lokasi_produk->lokasi_produk_delete($id);
		if ($notifikasi) {
			redirect('logistik/lokasi_produk');
		} else {
			echo "<script>alert('Fail')</script>";
		}
	}

}
