<?php defined('BASEPATH') OR exit('No direct script access allowed');

class karyawan extends CI_Controller {
	
	var $tbl_karyawan		= 'ck_karyawan';
	var $view_karyawan		= 'ck_view_hr_karyawan';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_karyawan');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Data Karyawan',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Human Resource (HR)</li>
			                 <li><a href="hr/haryawan">Karyawan</a></li>',
			'page_icon' => 'icon-man-woman',
			'page_title' => 'Data Karyawan',
			'page_subtitle' => 'Data Master Karyawan',
			'get_agama' => $this->m_karyawan->get_agama(),
			'get_bank' => $this->m_karyawan->get_bank(),
			'get_bpjs' => $this->m_karyawan->get_bpjs(),
			'get_fasilitas' => $this->m_karyawan->get_fasilitas(),
			'get_kewajiban' => $this->m_karyawan->get_kewajiban(),
			'get_pajak' => $this->m_karyawan->get_pajak(),
			'get_pekerjaan' => $this->m_karyawan->get_pekerjaan(),
			'get_pendidikan' => $this->m_karyawan->get_pendidikan(),
			'get_provinsi' => $this->m_karyawan->get_provinsi(),
			'get_sanksi' => $this->m_karyawan->get_sanksi(),
			'get_status_keluarga' => $this->m_karyawan->get_status_keluarga(),
			'get_tunjangan' => $this->m_karyawan->get_tunjangan(),
			'get_unit_kerja' => $this->m_karyawan->get_unit_kerja(),
			'jenis_kelamin' => array ('P'=>'Pria', 'W'=>'Wanita'),
			'kelas_bpjs' => array ('I'=>'Kelas I', 'II'=>'Kelas II', 'III'=>'Kelas III'),
			'kewarganegaraan' => array ('1'=>'WNI', '2'=>'WNA'),
			'level_keahlian' => array ('B'=>'Beginner', 'I'=>'Intermediate', 'E'=>'Expert'),
			'ruang_golongan' => array ('batas_bawah'=>'1', 'batas_atas'=>'20'),
			'status_kawin' => array ('1'=>'Belum Kawin', '2'=>'Kawin'),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_hrga_karyawan').addClass('active');</script>"
		);
		$this->template->build('v_karyawan', $data);
  	}
	
	public function get_data() {
		/*
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_karyawan', 'nik', 'nama_karyawan', 'jenis_kelamin', 'tempat_lahir',
						  'tanggal_lahir', 'alamat', 'nama_unit_kerja', 'activated');
        
        // DB table to use
		$sTable = $this->view_karyawan;
						
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
			$row[] = $aRow['nik'];
			$row[] = $aRow['nama_karyawan'];
			$row[] = $aRow['jenis_kelamin'];
			$row[] = $aRow['tempat_lahir'];
			$row[] = date_format(new DateTime($aRow['tanggal_lahir']), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
			$row[] = $aRow['alamat'];
			$row[] = $aRow['nama_unit_kerja'];
			if ($aRow['activated'] == '1') {
				$row[] = '<span class="label label-success">Aktif</span>';
			} elseif ($aRow['activated'] == '0') {
				$row[] = '<span class="label label-danger">Non Aktif</span>';
			}
			$row[] = '<button id="btn-edit" value="'.$aRow['id_karyawan'].'" style="border: none;" class="btn-info btn-edit" title="Edit Data"><span class="icon-pencil"></span></button>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	// ========================================= MASTER DATA - BEGIN ===========================================
	function get_jabatan($id) {
		$this->m_karyawan->get_jabatan($id);
	}
	
	function get_kabkota($id) {
		$this->m_karyawan->get_kabkota($id);
	}
	// ========================================= MASTER DATA - END =============================================
	
	// ======================================= BPJS - BEGIN ====================================================
	function create_detail_bpjs() {
        $data = array(
            'id_karyawan'             	=> $this->input->post('id_karyawan'),
			'id_bpjs'            		=> $this->input->post('id_bpjs'),
			'kelas'            			=> $this->input->post('kelas'),
			'jumlah_karyawan'         	=> $this->input->post('jumlah_karyawan'),
			'jumlah_perusahaan'         => $this->input->post('jumlah_perusahaan'),
			'keterangan'             	=> $this->input->post('keterangan'),
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $create_detail = $this->m_karyawan->create_detail_bpjs($data);
        if ($create_detail)
            echo "done";
        else
            echo "fail";
    }
	
	function delete_detail_bpjs() {
        $del = $this->m_karyawan->delete_detail_bpjs();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_bpjs() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_bpjs($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='id_bpjs' style='display:none;'>".$r->id_bpjs."</td>";
					echo "<td id='nama_bpjs' style='width: 20%;'>".$r->nama_bpjs."</td>";
					echo "<td id='nama_bpjs' style='width: 10%; text-align: center;'>".$r->kelas."</td>";
					echo "<td id='jumlah_karyawan' style='width: 15%; text-align: center;'>".number_format($r->jumlah_karyawan)."</td>";
					echo "<td id='jumlah_perusahaan' style='width: 15%; text-align: center;'>".number_format($r->jumlah_perusahaan)."</td>";
					echo "<td id='keterangan' style='width: 27%;'>".$r->keterangan."</td>";	
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_bpjs" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ======================================= BPJS - END ======================================================
	
	// ======================================= FASILITAS - BEGIN ===============================================
	function create_detail_fasilitas() {
        $data = array(
            'id_karyawan'             	=> $this->input->post('id_karyawan'),
			'id_fasilitas'            	=> $this->input->post('id_fasilitas'),
			'tanggal_diberikan'         => date_format(new DateTime($this->input->post('tanggal_diberikan')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'tanggal_dikembalikan'      => date_format(new DateTime($this->input->post('tanggal_dikembalikan')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'keterangan'             	=> $this->input->post('keterangan'),
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $create_detail = $this->m_karyawan->create_detail_fasilitas($data);
        if ($create_detail)
            echo "done";
        else
            echo "fail";
    }
	
	function delete_detail_fasilitas() {
        $del = $this->m_karyawan->delete_detail_fasilitas();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_fasilitas() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_fasilitas($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='id_fasilitas' style='display:none;'>".$r->id_fasilitas."</td>";
					echo "<td id='nama_fasilitas' style='width: 20%;'>".$r->nama_fasilitas."</td>";
					echo "<td id='tanggal_diberikan' style='width: 15%; text-align: center;'>".date_format(new DateTime($r->tanggal_diberikan), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";
					echo "<td id='tanggal_dikembalikan' style='width: 15%; text-align: center;'>".date_format(new DateTime($r->tanggal_dikembalikan), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";
					echo "<td id='keterangan' style='width: 37%;'>".$r->keterangan."</td>";	
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_fasilitas" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ======================================= FASILITAS - END =================================================
	
	// ======================================= GAJI - BEGIN ====================================================
	function create_detail_gaji() {
		$id_karyawan = $this->input->post('txtid_pokok');
		$nik = $this->input->post('txtnik_pokok');
		
		$directory = "assets/img/karyawan/gaji/";
		$count_files = count(scandir($directory)) - 2;
		
		$file_name = $_FILES['filegaji_gaji']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['filegaji_gaji']['tmp_name'];
		$new_file = $directory.$file_name;
		$rename_file = $directory.$nik.'_'.$count_files.".".$file_ext;		
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
        $data = array(
            'id_karyawan'             	=> $id_karyawan,
			'tahun'            			=> $this->input->post('txttahun_gaji'),
			'bulan'            			=> $this->input->post('cbobulan_gaji'),
			'gaji'             			=> $this->input->post('txtgaji_gaji'),
			'keterangan'             	=> $this->input->post('txtketerangan_gaji'),
			'file_gaji'             	=> $rename_file,
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $notifikasi = $this->m_karyawan->create_detail_gaji($data);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
    }
	
	function delete_detail_gaji() {
        $del = $this->m_karyawan->delete_detail_gaji();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_gaji() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_gaji($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='tahun' style='width: 10%; text-align: center;'>".$r->tahun."</td>";
					echo "<td id='bulan' style='width: 10%; text-align: center;'>".set_month_to_string_ind($r->bulan)."</td>";
					echo "<td id='gaji' style='width: 20%; text-align: center;'>".number_format($r->gaji)."</td>";
					echo "<td id='keterangan' style='width: 47%;'>".$r->keterangan."</td>";
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_gaji" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ======================================= GAJI - END ======================================================
	
	// ======================================= JABATAN - BEGIN =================================================
	function create_detail_jabatan() {
		$nomor_sk = $this->input->post('txtnomorsk_jabatan');
		
		$file_name = $_FILES['filesk_jabatan']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['filesk_jabatan']['tmp_name'];
		$new_file = "assets/img/karyawan/sk_jabatan/".$file_name;
		$rename_file = "assets/img/karyawan/sk_jabatan/".$nomor_sk.".".$file_ext;
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
        $data = array(
            'id_karyawan'             	=> $this->input->post('txtid_pokok'),
			'id_unit_kerja'            	=> $this->input->post('cbounitkerja_jabatan'),
			'id_jabatan'            	=> $this->input->post('cbojabatan_jabatan'),
			'golongan'             		=> $this->input->post('txtgolongan_jabatan'),			
			'ruang'             		=> $this->input->post('txtruang_jabatan'),
			'tmt_kerja'             	=> date_format(new DateTime($this->input->post('txttmtkerja_jabatan')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'tst_kerja'             	=> date_format(new DateTime($this->input->post('txttstkerja_jabatan')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'nomor_sk'             		=> $nomor_sk,			
			'tanggal_sk'             	=> date_format(new DateTime($this->input->post('txttanggalsk_jabatan')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'file_sk'             		=> $new_file,
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $notifikasi = $this->m_karyawan->create_detail_jabatan($data);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
    }
	
	function delete_detail_jabatan() {
        $del = $this->m_karyawan->delete_detail_jabatan();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_jabatan() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_jabatan($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='id_unit_kerja' style='display:none;'>".$r->id_unit_kerja."</td>";
					echo "<td id='nama_unit_kerja' style='text-align: center;'>".$r->nama_unit_kerja."</td>";
					echo "<td id='id_jabatan' style='display:none;'>".$r->id_jabatan."</td>";
					echo "<td id='nama_jabatan' style='text-align: center;'>".$r->nama_jabatan."</td>";					
					echo "<td id='golongan' style='text-align: center;'>".$r->golongan."</td>";
					echo "<td id='ruang' style='text-align: center;'>".$r->ruang."</td>";
					echo "<td id='tmt_kerja' style='text-align: center;'>".date_format(new DateTime($r->tmt_kerja), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";
					echo "<td id='tst_kerja' style='text-align: center;'>".date_format(new DateTime($r->tst_kerja), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";
					echo "<td id='nomor_sk' style='text-align: center;'>".$r->nomor_sk."</td>";
					echo "<td id='tanggal_sk' style='text-align: center;'>".date_format(new DateTime($r->tanggal_sk), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";					
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_jabatan" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ======================================= JABATAN - END ===================================================	
	
	// ========================================= KELUARGA - BEGIN ===============================================
	function create_detail_keluarga() {
		$nik_keluarga = $this->input->post('txtnik_keluarga');
		$kk_keluarga = $this->input->post('txtnokk_keluarga');
		
		$directory = "assets/img/karyawan/keluarga/kk/";
		$count_files = count(scandir($directory)) - 2;
		$file_name_kk = $_FILES['filekk_keluarga']['name'];
		$array_var_kk = explode(".", $file_name_kk);
		$file_ext_kk = end($array_var_kk);
		$file_tmp_kk = $_FILES['filekk_keluarga']['tmp_name'];
		$new_file_kk = $directory.$file_name_kk;
		$rename_file_kk = $directory.$kk_keluarga.'_'.$count_files.".".$file_ext_kk;		
		move_uploaded_file($file_tmp_kk, $new_file_kk);
		rename($new_file_kk, $rename_file_kk);
		
		$directory = "assets/img/karyawan/keluarga/ktp/";
		$count_files = count(scandir($directory)) - 2;
		$file_name_ktp = $_FILES['filektp_keluarga']['name'];
		$array_var_ktp = explode(".", $file_name_ktp);
		$file_ext_ktp = end($array_var_ktp);
		$file_tmp_ktp = $_FILES['filektp_keluarga']['tmp_name'];
		$new_file_ktp = $directory.$file_name_ktp;
		$rename_file_ktp = $directory.$nik_keluarga.'_'.$count_files.".".$file_ext_ktp;		
		move_uploaded_file($file_tmp_ktp, $new_file_ktp);
		rename($new_file_ktp, $rename_file_ktp);
		
        $data = array(
            'id_karyawan'             	=> $this->input->post('txtid_pokok'),
			'nama'             			=> $this->input->post('txtnama_keluarga'),
			'nik'             			=> $nik_keluarga,
			'jenis_kelamin'             => $this->input->post('cbojeniskelamin_keluarga'),
			'tempat_lahir'             	=> $this->input->post('txttempatlahir_keluarga'),
			'tanggal_lahir'             => date_format(new DateTime($this->input->post('txttanggallahir_keluarga')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'id_agama'             		=> $this->input->post('cboagama_keluarga'),
			'id_pendidikan'             => $this->input->post('cbopendidikan_keluarga'),
			'id_pekerjaan'             	=> $this->input->post('cbopekerjaan_keluarga'),
			'status_kawin'             	=> $this->input->post('cbostatuskawin_keluarga'),
			'id_status_keluarga'        => $this->input->post('cbostatus_keluarga'),
			'kewarganegaraan'           => $this->input->post('cbokewarganegaraan_keluarga'),
			'nama_ayah'             	=> $this->input->post('txtnamaayah_keluarga'),
			'nama_ibu'             		=> $this->input->post('txtnamaibu_keluarga'),
			'no_kk'             		=> $kk_keluarga,
			'file_kk'             		=> $rename_file_kk,
			'file_ktp'             		=> $rename_file_ktp,
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $notifikasi = $this->m_karyawan->create_detail_keluarga($data);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
    }
	
	function delete_detail_keluarga() {
        $del = $this->m_karyawan->delete_detail_keluarga();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_keluarga() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_keluarga($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='nama' class='word-wrap'>".$r->nama."</td>";
					echo "<td id='nik' class='word-wrap'>".$r->nik."</td>";
					echo "<td id='jenis_kelamin' class='word-wrap' style='text-align: center;'>".$r->jenis_kelamin."</td>";
					echo "<td id='tempat_lahir' class='word-wrap' style='text-align: center;'>".$r->tempat_lahir."</td>";
					echo "<td id='tanggal_lahir' class='word-wrap' style='text-align: center;'>".date_format(new DateTime($r->tanggal_lahir), $this->config->item('FORMAT_DATE_TO_INSERT'))."</td>";
					echo "<td id='id_agama' class='word-wrap' style='display:none;'>".$r->id_agama."</td>";
					echo "<td id='nama_agama' class='word-wrap' style='text-align: center;'>".$r->nama_agama."</td>";
					echo "<td id='id_pendidikan' class='word-wrap' style='display:none;'>".$r->id_pendidikan."</td>";
					echo "<td id='nama_pendidikan' class='word-wrap' style='text-align: center;'>".$r->nama_pendidikan."</td>";
					echo "<td id='id_pekerjaan' class='word-wrap' style='display:none;'>".$r->id_pekerjaan."</td>";
					echo "<td id='nama_pekerjaan' class='word-wrap' style='text-align: center;'>".$r->nama_pekerjaan."</td>";
					echo "<td id='status_kawin' class='word-wrap' style='text-align: center;'>".$r->status_kawin."</td>";
					echo "<td id='id_status_keluarga' class='word-wrap' style='display:none;'>".$r->id_status_keluarga."</td>";
					echo "<td id='nama_status_keluarga' class='word-wrap' style='text-align: center;'>".$r->nama_status_keluarga."</td>";
					echo "<td id='kewarganegaraan' class='word-wrap' style='text-align: center;'>".$r->kewarganegaraan."</td>";
					echo "<td id='nama_ayah' class='word-wrap' style='text-align: center;'>".$r->nama_ayah."</td>";
					echo "<td id='nama_ibu' class='word-wrap' style='text-align: center;'>".$r->nama_ibu."</td>";
					echo "<td id='no_kk' class='word-wrap' style='text-align: center;'>".$r->no_kk."</td>";
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_keluarga" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ========================================= KELUARGA - END =================================================
	
	// ======================================= KEWAJIBAN - BEGIN ================================================
	function create_detail_kewajiban() {
        $data = array(
            'id_karyawan'             	=> $this->input->post('id_karyawan'),
			'id_kewajiban'         		=> $this->input->post('id_kewajiban'),
			'tahun'         			=> $this->input->post('tahun'),
			'jumlah'         			=> $this->input->post('jumlah'),
			'keterangan'             	=> $this->input->post('keterangan'),
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $create_detail = $this->m_karyawan->create_detail_kewajiban($data);
        if ($create_detail)
            echo "done";
        else
            echo "fail";
    }
	
	function delete_detail_kewajiban() {
        $del = $this->m_karyawan->delete_detail_kewajiban();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_kewajiban() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_kewajiban($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='id_kewajiban' style='display:none;'>".$r->id_kewajiban."</td>";
					echo "<td id='nama_kewajiban' style='width: 20%;'>".$r->nama_kewajiban."</td>";
					echo "<td id='tahun' style='width: 15%; text-align: center;'>".$r->tahun."</td>";
					echo "<td id='jumlah' style='width: 15%; text-align: center;'>".number_format($r->jumlah)."</td>";
					echo "<td id='keterangan' style='width: 37%;'>".$r->keterangan."</td>";	
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_kewajiban" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ======================================= KEWAJIBAN - END ==================================================
	
	// ======================================= KOMPETENSI - BEGIN ===============================================
	function create_detail_kompetensi() {
		$nik = $this->input->post('txtnik_pokok');
		
		$directory = "assets/img/karyawan/kompetensi/";
		$count_files = count(scandir($directory)) - 2;
		$file_name = $_FILES['filekompetensi_kompetensi']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['filekompetensi_kompetensi']['tmp_name'];
		$new_file = $directory.$file_name;
		$rename_file = $directory.$nik.'_'.$count_files.".".$file_ext;		
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
        $data = array(
            'id_karyawan'             	=> $this->input->post('txtid_pokok'),
			'nama_keahlian'            	=> $this->input->post('txtnamakeahlian_kompetensi'),
			'level_keahlian'         	=> $this->input->post('cbolevelkeahlian_kompetensi'),
			'keterangan'             	=> $this->input->post('txtketerangan_kompetensi'),
			'file_kompetensi'			=> $rename_file,
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $notifikasi = $this->m_karyawan->create_detail_kompetensi($data);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
    }
	
	function delete_detail_kompetensi() {
        $del = $this->m_karyawan->delete_detail_kompetensi();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_kompetensi() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_kompetensi($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='nama_keahlian' style='width: 30%;'>".$r->nama_keahlian."</td>";
					echo "<td id='level_keahlian' style='width: 10%; text-align: center;'>".$r->level_keahlian."</td>";
					echo "<td id='keterangan' style='width: 47%;'>".$r->keterangan."</td>";	
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_kompetensi" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ======================================= KOMPETENSI - END =================================================
	
	// ======================================= PAJAK - BEGIN ====================================================
	function create_detail_pajak() {
        $data = array(
            'id_karyawan'             	=> $this->input->post('id_karyawan'),
			'id_pajak'            		=> $this->input->post('id_pajak'),
			'pajak_karyawan'         	=> $this->input->post('pajak_karyawan'),
			'pajak_perusahaan'         	=> $this->input->post('pajak_perusahaan'),
			'keterangan'             	=> $this->input->post('keterangan'),
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $create_detail = $this->m_karyawan->create_detail_pajak($data);
        if ($create_detail)
            echo "done";
        else
            echo "fail";
    }
	
	function delete_detail_pajak() {
        $del = $this->m_karyawan->delete_detail_pajak();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_pajak() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_pajak($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='id_pajak' style='display:none;'>".$r->id_pajak."</td>";
					echo "<td id='nama_pajak' style='width: 20%;'>".$r->nama_pajak."</td>";
					echo "<td id='pajak_karyawan' style='width: 15%; text-align: center;'>".number_format($r->pajak_karyawan)."</td>";
					echo "<td id='pajak_perusahaan' style='width: 15%; text-align: center;'>".number_format($r->pajak_perusahaan)."</td>";
					echo "<td id='keterangan' style='width: 37%;'>".$r->keterangan."</td>";	
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_pajak" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ======================================= PAJAK - END ======================================================
	
	// ====================================== PELATIHAN - BEGIN =================================================
	function create_detail_pelatihan() {
		$nik = $this->input->post('txtnik_pokok');
		
		$directory = "assets/img/karyawan/pelatihan/";
		$count_files = count(scandir($directory)) - 2;
		$file_name = $_FILES['filesertifikat_pelatihan']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['filesertifikat_pelatihan']['tmp_name'];
		$new_file = $directory.$file_name;
		$rename_file = $directory.$nik.'_'.$count_files.".".$file_ext;		
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
        $data = array(
            'id_karyawan'             	=> $this->input->post('txtid_pokok'),
			'nama_pelatihan'            => $this->input->post('txtnama_pelatihan'),
			'nama_institusi'            => $this->input->post('txtnamainstitusi_pelatihan'),
			'tahun'             		=> $this->input->post('txttahun_pelatihan'),
			'file_sertifikat'           => $rename_file,
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $notifikasi = $this->m_karyawan->create_detail_pelatihan($data);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
    }
	
	function delete_detail_pelatihan() {
        $del = $this->m_karyawan->delete_detail_pelatihan();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_pelatihan() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_pelatihan($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='nama_pelatihan' style='width: 40%;'>".$r->nama_pelatihan."</td>";
					echo "<td id='nama_institusi' style='width: 36%;'>".$r->nama_institusi."</td>";
					echo "<td id='tahun' style='width: 11%; text-align: center;'>".$r->tahun."</td>";
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_pelatihan" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ====================================== PELATIHAN - END ===================================================
	
	// ========================================= PENDIDIKAN - BEGIN =============================================
	function create_detail_pendidikan() {
		$nik = $this->input->post('txtnik_pokok');
		
		$directory = "assets/img/karyawan/pendidikan/";
		$count_files = count(scandir($directory)) - 2;
		$file_name = $_FILES['fileijazah_pendidikan']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['fileijazah_pendidikan']['tmp_name'];
		$new_file = $directory.$file_name;
		$rename_file = $directory.$nik.'_'.$count_files.".".$file_ext;		
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
        $data = array(
            'id_karyawan'             	=> $this->input->post('txtid_pokok'),
			'id_pendidikan'             => $this->input->post('cbojenjang_pendidikan'),
			'nama'             			=> $this->input->post('txtnamainstitusi_pendidikan'),
			'tahun_masuk'             	=> $this->input->post('txttahunmasuk_pendidikan'),
			'tahun_lulus'             	=> $this->input->post('txttahunlulus_pendidikan'),
			'jurusan'             		=> $this->input->post('txtjurusan_pendidikan'),
			'ipk'             			=> $this->input->post('txtipk_pendidikan'),
			'file_ijazah'             	=> $rename_file,
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $notifikasi = $this->m_karyawan->create_detail_pendidikan($data);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);

    }
	
	function delete_detail_pendidikan() {
        $del = $this->m_karyawan->delete_detail_pendidikan();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_pendidikan() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_pendidikan($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='id_pendidikan' style='display:none;'>".$r->id_pendidikan."</td>";
					echo "<td id='nama_pendidikan' style='width: 16%; text-align: center;'>".$r->nama_pendidikan."</td>";
					echo "<td id='nama' style='width: 30%;'>".$r->nama."</td>";
					echo "<td id='tahun_masuk' style='width: 8%; text-align: center;'>".$r->tahun_masuk."</td>";
					echo "<td id='tahun_lulus' style='width: 8%; text-align: center;'>".$r->tahun_lulus."</td>";
					echo "<td id='jurusan' style='width: 14%; text-align: center;'>".$r->jurusan."</td>";
					echo "<td id='ipk' style='width: 11%; text-align: right;'>".$r->ipk."</td>";
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_pendidikan" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ========================================= PENDIDIKAN - END ===============================================
	
	// ==================================== PENGALAMAN KERJA - BEGIN ============================================
	function create_detail_pengalaman_kerja() {
		$nik = $this->input->post('txtnik_pokok');
		
		$directory = "assets/img/karyawan/pengalaman_kerja/";
		$count_files = count(scandir($directory)) - 2;
		$file_name = $_FILES['filepengalamankerja_pengalamankerja']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['filepengalamankerja_pengalamankerja']['tmp_name'];
		$new_file = $directory.$file_name;
		$rename_file = $directory.$nik.'_'.$count_files.".".$file_ext;		
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
        $data = array(
            'id_karyawan'             	=> $this->input->post('txtid_pokok'),
			'nama'             			=> $this->input->post('txtnama_pengalamankerja'),
			'tahun_masuk'             	=> $this->input->post('txttahunmasuk_pengalamankerja'),
			'tahun_keluar'             	=> $this->input->post('txttahunkeluar_pengalamankerja'),
			'jabatan'             		=> $this->input->post('txtjabatan_pengalamankerja'),
			'gaji_terakhir'             => $this->input->post('txtgajiterakhir_pengalamankerja'),
			'alasan_berhenti'           => $this->input->post('txtalasanberhenti_pengalamankerja'),
			'file_pengalaman_kerja'		=> $rename_file,
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $notifikasi = $this->m_karyawan->create_detail_pengalaman_kerja($data);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
    }
	
	function delete_detail_pengalaman_kerja() {
        $del = $this->m_karyawan->delete_detail_pengalaman_kerja();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_pengalaman_kerja() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_pengalaman_kerja($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='nama' style='width: 20%;'>".$r->nama."</td>";
					echo "<td id='tahun_masuk' style='width: 10%; text-align: center;'>".$r->tahun_masuk."</td>";
					echo "<td id='tahun_keluar' style='width: 10%; text-align: center;'>".$r->tahun_keluar."</td>";
					echo "<td id='jabatan' style='width: 10%; text-align: center;'>".$r->jabatan."</td>";
					echo "<td id='gaji_terakhir' style='width: 11%; text-align: right;'>".number_format($r->gaji_terakhir)."</td>";
					echo "<td id='alasan_berhenti' style='width: 26%;'>".$r->alasan_berhenti."</td>";
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_pengalaman_kerja" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ==================================== PENGALAMAN KERJA - END ==============================================
	
	// ========================================= POKOK - BEGIN ==================================================
	function karyawan_create() {
		$notifikasi = $this->m_karyawan->karyawan_create();
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}
	
	function karyawan_update() {
		$notifikasi = $this->m_karyawan->karyawan_update();
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
	}
	
  	function karyawan_view() {
		$id_karyawan = $this->input->post('id_karyawan');
		$karyawan = $this->m_karyawan->karyawan_view($id_karyawan);
		foreach ($karyawan->result_array() as $row) {
			$data['id_karyawan'] = $row['id_karyawan'];
			$data['nik'] = $row['nik'];
			$data['nama_karyawan'] = $row['nama_karyawan'];
			$data['jenis_kelamin'] = $row['jenis_kelamin'];
			$data['tempat_lahir'] = $row['tempat_lahir'];
			$data['tanggal_lahir'] = date_format(new DateTime($row['tanggal_lahir']), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
			$data['alamat'] = $row['alamat'];
			$data['id_provinsi'] = $row['id_provinsi'];
			$data['id_kabupatenkota'] = $row['id_kabupatenkota'];
			$data['kode_pos'] = $row['kode_pos'];
			$data['nomor_telepon'] = $row['nomor_telepon'];
			$data['nomor_hp'] = $row['nomor_hp'];
			$data['nomor_wa'] = $row['nomor_wa'];
			$data['email'] = $row['email'];
			$data['nomor_ktp'] = $row['nomor_ktp'];
			//$data['file_ktp'] = $row['file_ktp'];
			//$data['file_foto'] = $row['file_foto'];
			$data['id_unit_kerja'] = $row['id_unit_kerja'];
			$data['keterangan'] = $row['keterangan'];
			$data['activated'] = $row['activated'];
		}
    	echo json_encode($data);
  	}
	// ========================================= POKOK - END ====================================================
	
	// ======================================= PRESENSI & CUTI - BEGIN ==========================================
	function create_detail_presensi_cuti() {
        $data = array(
            'id_karyawan'             	=> $this->input->post('txtid_pokok'),
			'tahun'         			=> $this->input->post('txttahun_presensi_cuti'),
			'bulan'            			=> $this->input->post('cbobulan_presensi_cuti'),			
			'hari_bekerja'         		=> $this->input->post('txtharibekerja_presensi_cuti'),
			'hari_ijin'             	=> $this->input->post('txthariijin_presensi_cuti'),
			'hari_cuti'             	=> $this->input->post('txtharicuti_presensi_cuti'),
			'hari_mangkir'             	=> $this->input->post('txtharimangkir_presensi_cuti'),
			'keterangan'             	=> $this->input->post('txtketerangan_presensi_cuti'),
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $notifikasi = $this->m_karyawan->create_detail_presensi_cuti($data);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
    }
	
	function delete_detail_presensi_cuti() {
        $del = $this->m_karyawan->delete_detail_presensi_cuti();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_presensi_cuti() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_presensi_cuti($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";					
					echo "<td id='tahun' style='width: 10%; text-align: center;'>".$r->tahun."</td>";
					echo "<td id='bulan' style='width: 10%; text-align: center;'>".set_month_to_string_ind($r->bulan)."</td>";
					echo "<td id='hari_bekerja' style='width: 10%; text-align: center;'>".number_format($r->hari_kerja)."</td>";
					echo "<td id='hari_bekerja' style='width: 10%; text-align: center;'>".number_format($r->hari_bekerja)."</td>";
					echo "<td id='hari_ijin' style='width: 10%; text-align: center;'>".number_format($r->hari_ijin)."</td>";
					echo "<td id='hari_cuti' style='width: 10%; text-align: center;'>".number_format($r->hari_cuti)."</td>";
					echo "<td id='hari_mangkir' style='width: 10%; text-align: center;'>".number_format($r->hari_mangkir)."</td>";
					echo "<td id='keterangan' style='width: 17%;'>".$r->keterangan."</td>";	
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_presensi_cuti" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ======================================= PRESENSI & CUTI - END ============================================
	
	// ====================================== PRESTASI - BEGIN ==================================================
	function create_detail_prestasi() {
		$nik = $this->input->post('txtnik_pokok');
		
		$directory = "assets/img/karyawan/prestasi/";
		$count_files = count(scandir($directory)) - 2;
		$file_name = $_FILES['fileprestasi_prestasi']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['fileprestasi_prestasi']['tmp_name'];
		$new_file = $directory.$file_name;
		$rename_file = $directory.$nik.'_'.$count_files.".".$file_ext;		
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
        $data = array(
            'id_karyawan'             	=> $this->input->post('txtid_pokok'),
			'nama_prestasi'            	=> $this->input->post('txtnamaprestasi_prestasi'),
			'tahun'             		=> $this->input->post('txttahun_prestasi'),
			'keterangan'             	=> $this->input->post('txtketerangan_prestasi'),
			'file_prestasi'				=> $rename_file,
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $notifikasi = $this->m_karyawan->create_detail_prestasi($data);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
    }
	
	function delete_detail_prestasi() {
        $del = $this->m_karyawan->delete_detail_prestasi();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_prestasi() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_prestasi($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='nama_prestasi' style='width: 37%;'>".$r->nama_prestasi."</td>";
					echo "<td id='tahun' style='width: 10%; text-align: center;'>".$r->tahun."</td>";
					echo "<td id='keterangan' style='width: 40%; text-align: center;'>".$r->keterangan."</td>";
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_prestasi" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";           
                $i++;
            }
        }
    }	
	// ====================================== PRESTASI - END ====================================================
	
	// ====================================== REKENING - BEGIN ==================================================
	function create_detail_rekening() {
		$no_rekening = $this->input->post('txtnomor_rekening');
		
		$file_name = $_FILES['filerekening_rekening']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['filerekening_rekening']['tmp_name'];
		$new_file = "assets/img/karyawan/rekening/".$file_name;
		$rename_file = "assets/img/karyawan/rekening/".$no_rekening.".".$file_ext;		
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
        $data = array(
            'id_karyawan'             	=> $this->input->post('txtid_pokok'),
			'id_bank'            		=> $this->input->post('cbobank_rekening'),
			'nomor_rekening'            => $this->input->post('txtnomor_rekening'),
			'nama_rekening'             => $this->input->post('txtnama_rekening'),
			'file_rekening'             => $new_file,
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $notifikasi = $this->m_karyawan->create_detail_rekening($data);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
    }
	
	function delete_detail_rekening() {
        $del = $this->m_karyawan->delete_detail_rekening();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_rekening() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_rekening($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='id_bank' style='display:none;'>".$r->id_bank."</td>";
					echo "<td id='nama_bank' style='width: 20%;'>".$r->nama_bank."</td>";
					echo "<td id='nomor_rekening' style='width: 16%; text-align: center;'>".$r->nomor_rekening."</td>";
					echo "<td id='tahun' style='width: 51%; text-align: center;'>".$r->nama_rekening."</td>";
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_rekening" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";           
                $i++;
            }
        }
    }	
	// ====================================== REKENING - END ====================================================
	
	// ====================================== SANKSI - BEGIN ====================================================
	function create_detail_sanksi() {
		$nik = $this->input->post('txtnik_pokok');
		
		$directory = "assets/img/karyawan/sanksi/";
		$count_files = count(scandir($directory)) - 2;
		$file_name = $_FILES['filesanksi_sanksi']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['filesanksi_sanksi']['tmp_name'];
		$new_file = $directory.$file_name;
		$rename_file = $directory.$nik.'_'.$count_files.".".$file_ext;		
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
        $data = array(
            'id_karyawan'             	=> $this->input->post('txtid_pokok'),
			'id_sanksi'            		=> $this->input->post('cbosanksi_sanksi'),
			'tanggal_diberikan'         => date_format(new DateTime($this->input->post('txttanggaldiberikan_sanksi')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'keterangan'         		=> $this->input->post('txtketerangan_sanksi'),
			'file_sanksi'             	=> $rename_file,
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $notifikasi = $this->m_karyawan->create_detail_sanksi($data);
		if ($notifikasi == 1) {
			$notifikasi=" Selamat ! Anda berhasil";
		} else {
			$notifikasi=" Maaf ! Anda gagal";
		}
		$notifikasi = array('notifikasi' => $notifikasi);
		echo json_encode($notifikasi, true);
    }
	
	function delete_detail_sanksi() {
        $del = $this->m_karyawan->delete_detail_sanksi();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_sanksi() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_sanksi($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='id_sanksi' style='display:none;'>".$r->id_sanksi."</td>";
					echo "<td id='nama_sanksi' style='width: 20%;'>".$r->nama_sanksi."</td>";
					echo "<td id='tanggal_diberikan' style='width: 20%; text-align: center;'>".date_format(new DateTime($r->tanggal_diberikan), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";
					echo "<td id='keterangan' style='width: 47%;'>".$r->keterangan."</td>";
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_sanksi" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";           
                $i++;
            }
        }
    }	
	// ====================================== SANKSI - END ======================================================
	
	// ======================================= TUNJANGAN - BEGIN ================================================
	function create_detail_tunjangan() {
        $data = array(
            'id_karyawan'             	=> $this->input->post('id_karyawan'),
			'id_tunjangan'            	=> $this->input->post('id_tunjangan'),
			'tahun'         			=> $this->input->post('tahun'),
			'bulan'         			=> $this->input->post('bulan'),
			'tunjangan'         		=> $this->input->post('tunjangan'),
			'keterangan'             	=> $this->input->post('keterangan'),
            'created_by'            	=> $this->session->userdata('user_name'),
            'created_date'          	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           	=> $this->session->userdata('user_name'),
            'modified_date'         	=> $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $create_detail = $this->m_karyawan->create_detail_tunjangan($data);
        if ($create_detail)
            echo "done";
        else
            echo "fail";
    }
	
	function delete_detail_tunjangan() {
        $del = $this->m_karyawan->delete_detail_tunjangan();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function list_detail_tunjangan() {
		$id_karyawan = $this->input->get('id_karyawan');
        $data = $this->m_karyawan->list_detail_tunjangan($id_karyawan);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";
					echo "<td id='id_tunjangan' style='display:none;'>".$r->id_tunjangan."</td>";
					echo "<td id='nama_tunjangan' style='width: 20%;'>".$r->nama_tunjangan."</td>";
					echo "<td id='tahun' style='width: 15%; text-align: center;'>".$r->tahun."</td>";
					echo "<td id='bulan' style='width: 15%; text-align: center;'>".set_month_to_string_ind($r->bulan)."</td>";
					echo "<td id='tunjangan' style='width: 15%; text-align: center;'>".number_format($r->tunjangan)."</td>";
					echo "<td id='keterangan' style='width: 22%;'>".$r->keterangan."</td>";	
					echo "<td style='width: 9%; text-align: center;'>";
							echo '<span id="hapus_detail_tunjangan" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
					echo "</td>";
				echo "</tr>";
                $i++;
            }
        }
    }
	// ======================================= TUNJANGAN - END ==================================================
	
}