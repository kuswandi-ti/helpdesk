<?php
class m_karyawan extends CI_Model {
	
	var $tbl_karyawan						= 'ck_karyawan';
	var $tbl_karyawan_bpjs					= 'ck_karyawan_bpjs';
	var $tbl_karyawan_fasilitas				= 'ck_karyawan_fasilitas';
	var $tbl_karyawan_gaji					= 'ck_karyawan_gaji';
	var $tbl_karyawan_jabatan				= 'ck_karyawan_jabatan';
	var $tbl_karyawan_keluarga				= 'ck_karyawan_keluarga';
	var $tbl_karyawan_kewajiban				= 'ck_karyawan_kewajiban';
	var $tbl_karyawan_kompetensi			= 'ck_karyawan_kompetensi';
	var $tbl_karyawan_pajak					= 'ck_karyawan_pajak';
	var $tbl_karyawan_pelatihan				= 'ck_karyawan_pelatihan';
	var $tbl_karyawan_pendidikan			= 'ck_karyawan_pendidikan';
	var $tbl_karyawan_pengalaman_kerja		= 'ck_karyawan_pengalaman_kerja';
	var $tbl_karyawan_presensi_cuti			= 'ck_karyawan_presensi_cuti';
	var $tbl_karyawan_prestasi				= 'ck_karyawan_prestasi';
	var $tbl_karyawan_rekening				= 'ck_karyawan_rekening';
	var $tbl_karyawan_sanksi				= 'ck_karyawan_sanksi';
	var $tbl_karyawan_tunjangan				= 'ck_karyawan_tunjangan';
	
	var $tbl_agama							= 'ck_agama';
	var $tbl_bank							= 'ck_bank';
	var $tbl_bpjs							= 'ck_bpjs';
	var $tbl_fasilitas						= 'ck_fasilitas';
	var $tbl_hari_kerja						= 'ck_hari_kerja';
	var $tbl_jabatan						= 'ck_jabatan';
	var $tbl_kabupatenkota					= 'ck_kabupatenkota';
	var $tbl_kewajiban						= 'ck_kewajiban';
	var $tbl_pajak							= 'ck_pajak';
	var $tbl_pekerjaan						= 'ck_pekerjaan';
	var $tbl_pendidikan						= 'ck_pendidikan';
	var $tbl_provinsi						= 'ck_provinsi';
	var $tbl_sanksi							= 'ck_sanksi';
	var $tbl_status_keluarga				= 'ck_status_keluarga';
	var $tbl_tunjangan						= 'ck_tunjangan';
	var $tbl_unit_kerja						= 'ck_unit_kerja';	
	
	var $view_karyawan_keluarga				= 'ck_view_hr_karyawan_keluarga';

    function __construct() {
        parent::__construct();
    }
	
	// ========================================= MASTER DATA - BEGIN ===========================================
	function get_agama() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_agama."
								 ORDER BY
									id");
	}
	
	function get_bank() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_bank."
								 ORDER BY
									nama");
	}
	
	function get_bpjs() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_bpjs."
								 ORDER BY
									id");
	}
	
	function get_fasilitas() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_fasilitas."
								 ORDER BY
									id");
	}
	
	function get_jabatan($id) {
		$result =  $this->db->query("SELECT
										*
									 FROM
										".$this->tbl_jabatan."
									 WHERE
										unit_kerja_id = '".$id."'");
		echo json_encode($result->result());
	}
	
	function get_kabkota($id) {
		$result =  $this->db->query("SELECT
										*
									 FROM
										".$this->tbl_kabupatenkota."
									 WHERE
										provinsi_id = '".$id."'");
		echo json_encode($result->result());
	}
	
	function get_kewajiban() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_kewajiban."
								 ORDER BY
									id");
	}
	
	function get_pajak() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_pajak."
								 ORDER BY
									nama");
	}
	
	function get_pekerjaan() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_pekerjaan."
								 ORDER BY
									nama");
	}
	
	function get_pendidikan() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_pendidikan."
								 ORDER BY
									id");
	}
	
	function get_provinsi() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_provinsi."
								 ORDER BY
									nama");
	}
	
	function get_sanksi() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_sanksi."
								 ORDER BY
									id");
	}
	
	function get_status_keluarga() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_status_keluarga."
								 ORDER BY
									nama");
	}
	
	function get_tunjangan() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_tunjangan."
								 ORDER BY
									nama");
	}
	
	function get_unit_kerja() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_unit_kerja."
								 ORDER BY
									nama");
	}
	// ========================================= MASTER DATA - END ==============================================
	
	// ========================================= BPJS - BEGIN ===================================================
	function create_detail_bpjs($data) {        
		return $this->db->insert($this->tbl_karyawan_bpjs, $data);
	}
	
	function delete_detail_bpjs() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_bpjs." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_bpjs($id_karyawan) {
		$q = $this->db->query(" SELECT
									a.id,
									a.id_karyawan,
									a.id_bpjs,
									b.nama AS nama_bpjs,
									a.kelas,
									a.jumlah_karyawan,
									a.jumlah_perusahaan,
									a.keterangan
								FROM
									".$this->tbl_karyawan_bpjs." a
									LEFT OUTER JOIN ".$this->tbl_bpjs." b ON a.id_bpjs = b.id
								WHERE
									a.id_karyawan = '".$id_karyawan."'
								ORDER BY
									a.id");
								
		return $q;
	}
	// ========================================= BPJS - END =====================================================
	
	// ========================================= FASILITAS - BEGIN ==============================================
	function create_detail_fasilitas($data) {        
		return $this->db->insert($this->tbl_karyawan_fasilitas, $data);
	}
	
	function delete_detail_fasilitas() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_fasilitas." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_fasilitas($id_karyawan) {
		$q = $this->db->query(" SELECT
									a.id,
									a.id_karyawan,
									a.id_fasilitas,
									b.nama AS nama_fasilitas,
									a.tanggal_diberikan,
									a.tanggal_dikembalikan,
									a.keterangan
								FROM
									".$this->tbl_karyawan_fasilitas." a
									LEFT OUTER JOIN ".$this->tbl_fasilitas." b ON a.id_fasilitas = b.id
								WHERE
									a.id_karyawan = '".$id_karyawan."'
								ORDER BY
									a.id");
								
		return $q;
	}
	// ========================================= FASILITAS - END ================================================
	
	// ========================================= GAJI - BEGIN ===================================================
	function create_detail_gaji($data) {        
		return $this->db->insert($this->tbl_karyawan_gaji, $data);
	}
	
	function delete_detail_gaji() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_gaji." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_gaji($id_karyawan) {
		$q = $this->db->query(" SELECT
									*
								FROM
									".$this->tbl_karyawan_gaji."
								WHERE
									id_karyawan = '".$id_karyawan."'
								ORDER BY
									id");
								
		return $q;
	}
	// ========================================= GAJI - END =====================================================
	
	// ========================================= JABATAN - BEGIN ================================================
	function create_detail_jabatan($data) {        
		return $this->db->insert($this->tbl_karyawan_jabatan, $data);
	}
	
	function delete_detail_jabatan() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_jabatan." 
								 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_jabatan($id_karyawan) {
		$q = $this->db->query(" SELECT
									a.id,
									a.id_karyawan,
									a.id_unit_kerja,
									b.nama AS nama_unit_kerja,
									a.id_jabatan,
									c.nama AS nama_jabatan,
									a.golongan,
									a.ruang,
									a.tmt_kerja,
									a.tst_kerja,
									a.nomor_sk,
									a.tanggal_sk,
									a.file_sk
								FROM
									".$this->tbl_karyawan_jabatan." a
									LEFT OUTER JOIN ".$this->tbl_unit_kerja." b ON a.id_unit_kerja = b.id
									LEFT OUTER JOIN ".$this->tbl_jabatan." c ON a.id_jabatan = c.id
								WHERE
									a.id_karyawan = '".$id_karyawan."'
								ORDER BY
									a.id");
								
		return $q;
	}
	// ========================================= JABATAN - END ==================================================
	
	// ========================================= KELUARGA - BEGIN ===============================================
	function create_detail_keluarga($data) {        
		return $this->db->insert($this->tbl_karyawan_keluarga, $data);
	}
	
	function delete_detail_keluarga() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_keluarga." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_keluarga($id_karyawan) {
		$q = $this->db->query(" SELECT
									*
								FROM
									".$this->view_karyawan_keluarga." 
								WHERE
									id_karyawan = '".$id_karyawan."'
								ORDER BY
									id");
								
		return $q;
	}
	// ========================================= KELUARGA - END =================================================
	
	// ========================================= KEWAJIBAN - BEGIN ==============================================
	function create_detail_kewajiban($data) {        
		return $this->db->insert($this->tbl_karyawan_kewajiban, $data);
	}
	
	function delete_detail_kewajiban() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_kewajiban." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_kewajiban($id_karyawan) {
		$q = $this->db->query(" SELECT
									a.id,
									a.id_karyawan,
									a.id_kewajiban,
									b.nama AS nama_kewajiban,
									a.tahun,
									a.jumlah,
									a.keterangan
								FROM
									".$this->tbl_karyawan_kewajiban." a
									LEFT OUTER JOIN ".$this->tbl_kewajiban." b ON a.id_kewajiban = b.id
								WHERE
									a.id_karyawan = '".$id_karyawan."'
								ORDER BY
									a.id");
								
		return $q;
	}
	// ========================================= KEWAJIBAN - END ================================================
	
	// ========================================= KOMPETENSI - BEGIN =============================================
	function create_detail_kompetensi($data) {        
		return $this->db->insert($this->tbl_karyawan_kompetensi, $data);
	}
	
	function delete_detail_kompetensi() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_kompetensi." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_kompetensi($id_karyawan) {
		$q = $this->db->query(" SELECT
									id,
									id_karyawan,
									nama_keahlian,
									CASE level_keahlian
										WHEN 'B' THEN 'Beginner'
										WHEN 'I' THEN 'Intermediate'
										WHEN 'E' THEN 'Expert'
										ELSE ''
									END AS level_keahlian,
									keterangan
								FROM
									".$this->tbl_karyawan_kompetensi."
								WHERE
									id_karyawan = '".$id_karyawan."'
								ORDER BY
									id");
								
		return $q;
	}
	// ========================================= KOMPETENSI - END ===============================================
	
	// ========================================= PAJAK - BEGIN ==================================================
	function create_detail_pajak($data) {        
		return $this->db->insert($this->tbl_karyawan_pajak, $data);
	}
	
	function delete_detail_pajak() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_pajak." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_pajak($id_karyawan) {
		$q = $this->db->query(" SELECT
									a.id,
									a.id_karyawan,
									a.id_pajak,
									b.nama AS nama_pajak,
									a.pajak_karyawan,
									a.pajak_perusahaan,
									a.keterangan
								FROM
									".$this->tbl_karyawan_pajak." a
									LEFT OUTER JOIN ".$this->tbl_pajak." b ON a.id_pajak = b.id
								WHERE
									a.id_karyawan = '".$id_karyawan."'
								ORDER BY
									a.id");
								
		return $q;
	}
	// ========================================= PAJAK - END ====================================================
	
	// ====================================== PELATIHAN - BEGIN =================================================
	function create_detail_pelatihan($data) {
		return $this->db->insert($this->tbl_karyawan_pelatihan, $data);
	}
	
	function delete_detail_pelatihan() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_pelatihan."
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_pelatihan($id_karyawan) {
		$q = $this->db->query(" SELECT
									*
								FROM
									".$this->tbl_karyawan_pelatihan."
								WHERE
									id_karyawan = '".$id_karyawan."'
								ORDER BY
									id");
								
		return $q;
	}
	// ====================================== PELATIHAN - END ===================================================
	
	// ========================================= PENDIDIKAN - BEGIN =============================================
	function create_detail_pendidikan($data) {        
		return $this->db->insert($this->tbl_karyawan_pendidikan, $data);
	}
	
	function delete_detail_pendidikan() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_pendidikan." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_pendidikan($id_karyawan) {
		$q = $this->db->query(" SELECT
									a.id,
									a.id_pendidikan,
									b.nama AS nama_pendidikan,
									a.nama,
									a.tahun_masuk,
									a.tahun_lulus,
									a.jurusan,
									a.ipk
								FROM
									".$this->tbl_karyawan_pendidikan." a
									LEFT OUTER JOIN ".$this->tbl_pendidikan." b ON a.id_pendidikan = b.id
								WHERE
									a.id_karyawan = '".$id_karyawan."'
								ORDER BY
									a.id_pendidikan");
								
		return $q;
	}
	// ========================================= PENDIDIKAN - END ===============================================
	
	// ==================================== PENGALAMAN KERJA - BEGIN ============================================	
	function create_detail_pengalaman_kerja($data) {        
		return $this->db->insert($this->tbl_karyawan_pengalaman_kerja, $data);
	}
	
	function delete_detail_pengalaman_kerja() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_pengalaman_kerja." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_pengalaman_kerja($id_karyawan) {
		$q = $this->db->query(" SELECT
									*
								FROM
									".$this->tbl_karyawan_pengalaman_kerja." 
								WHERE
									id_karyawan = '".$id_karyawan."'
								ORDER BY
									id");
								
		return $q;
	}
	// ==================================== PENGALAMAN KERJA - END ==============================================
	
	// ========================================= POKOK - BEGIN =================================================
	function karyawan_create() {
		// Generate NIK - Begin //
		// Format NIK
		// 1-2 => Tahun TMT
		// 3-6 => Kode Unit Kerja
		// 7-10 => No Urut
		// Contoh : 17 2100 007
		$tahun = date("y");
		$kode_unit_kerja = $this->input->post('kodeunitkerja_pokok');		
		$query = $this->db->query("SELECT
										nik
								   FROM
										".$this->tbl_karyawan."
								   WHERE
										LEFT(nik, 6) = '".$tahun.$kode_unit_kerja."'
								   ORDER BY
										nik DESC
								   LIMIT
										1");
		if ($query->num_rows() > 0) {
			foreach($query->result() as $r) {
				$field_nik = $r->nik;
			}
			$maks_nik = (int)substr($field_nik, -3);
		} else {
			$maks_nik = 0;
		}		
		$nik = $tahun.$kode_unit_kerja.substr(('0000'.($maks_nik + 1)), -3);
		// Generate NIK - End //

		$file_name_foto = $_FILES['filefoto_pokok']['name'];
		$array_var_foto = explode(".", $file_name_foto);
		$file_ext_foto = end($array_var_foto);
		$file_tmp_foto = $_FILES['filefoto_pokok']['tmp_name'];
		$new_file_foto = "assets/img/karyawan/foto/".$file_name_foto;
		$rename_file_foto = "assets/img/karyawan/foto/".$nik.".".$file_ext_foto;		
		move_uploaded_file($file_tmp_foto, $new_file_foto);
		rename($new_file_foto, $rename_file_foto);
		
		$file_name_ktp = $_FILES['filektp_pokok']['name'];
		$array_var_ktp = explode(".", $file_name_ktp);
		$file_ext_ktp = end($array_var_ktp);
		$file_tmp_ktp = $_FILES['filektp_pokok']['tmp_name'];
		$new_file_ktp = "assets/img/karyawan/ktp/".$file_name_ktp;
		$rename_file_ktp = "assets/img/karyawan/ktp/".$nik.".".$file_ext_ktp;		
		move_uploaded_file($file_tmp_ktp, $new_file_ktp);
		rename($new_file_ktp, $rename_file_ktp);
		
		$data = array(
			'nik' => $nik,
			'nama' => $this->input->post('txtnama_pokok'),
			'jenis_kelamin' => $this->input->post('cbojeniskelamin_pokok'),
			'tempat_lahir' => $this->input->post('txttempatlahir_pokok'),
			'tanggal_lahir' => date_format(new DateTime($this->input->post('txttanggallahir_pokok')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'alamat' => $this->input->post('txtalamat_pokok'),
			'id_provinsi' => $this->input->post('cboprovinsi_pokok'),
			'id_kabupatenkota' => $this->input->post('cbokabkota_pokok'),			
			'kode_pos' => $this->input->post('txtkodepos_pokok'),
			'nomor_telepon' => $this->input->post('txtnomortelepon_pokok'),
			'nomor_hp' => $this->input->post('txtnomorhp_pokok'),
			'nomor_wa' => $this->input->post('txtnomorwa_pokok'),
			'email' => $this->input->post('txtemail_pokok'),
			'nomor_ktp' => $this->input->post('txtnomorktp_pokok'),
			'file_ktp' => $new_file_ktp,
			'file_foto' => $new_file_foto,
			'id_unit_kerja' => $this->input->post('cbounitkerja_pokok'),
			'keterangan' => $this->input->post('txtketerangan_pokok'),
			'activated' => $this->input->post('chkaktif_pokok'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT')),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
		);
        $result = $this->db->insert($this->tbl_karyawan, $data);
        return $result;
    }
	
	function karyawan_update() {
		$id_karyawan = $this->input->post('txtid_pokok');
		$nik = $this->input->post('txtnik_pokok');
		
		$file_name_foto = $_FILES['filefoto_pokok']['name'];
		$array_var_foto = explode(".", $file_name_foto);
		$file_ext_foto = end($array_var_foto);
		$file_tmp_foto = $_FILES['filefoto_pokok']['tmp_name'];
		$new_file_foto = "assets/img/karyawan/foto/".$file_name_foto;
		$rename_file_foto = "assets/img/karyawan/foto/".$nik.".".$file_ext_foto;
		move_uploaded_file($file_tmp_foto, $new_file_foto);
		rename($new_file_foto, $rename_file_foto);
		
		$file_name_ktp = $_FILES['filektp_pokok']['name'];
		$array_var_ktp = explode(".", $file_name_ktp);
		$file_ext_ktp = end($array_var_ktp);
		$file_tmp_ktp = $_FILES['filektp_pokok']['tmp_name'];
		$new_file_ktp = "assets/img/karyawan/ktp/".$file_name_ktp;
		$rename_file_ktp = "assets/img/karyawan/ktp/".$nik.".".$file_ext_ktp;		
		move_uploaded_file($file_tmp_ktp, $new_file_ktp);
		rename($new_file_ktp, $rename_file_ktp);
			
		$data = array(
			'nama' => $this->input->post('txtnama_pokok'),
			'jenis_kelamin' => $this->input->post('cbojeniskelamin_pokok'),
			'tempat_lahir' => $this->input->post('txttempatlahir_pokok'),
			'tanggal_lahir' => date_format(new DateTime($this->input->post('txttanggallahir_pokok')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'alamat' => $this->input->post('txtalamat_pokok'),
			'id_provinsi' => $this->input->post('cboprovinsi_pokok'),
			'id_kabupatenkota' => $this->input->post('cbokabkota_pokok'),			
			'kode_pos' => $this->input->post('txtkodepos_pokok'),
			'nomor_telepon' => $this->input->post('txtnomortelepon_pokok'),
			'nomor_hp' => $this->input->post('txtnomorhp_pokok'),
			'nomor_wa' => $this->input->post('txtnomorwa_pokok'),
			'email' => $this->input->post('txtemail_pokok'),
			'nomor_ktp' => $this->input->post('txtnomorktp_pokok'),
			'file_ktp' => $new_file_ktp,
			'file_foto' => $new_file_foto,
			'id_unit_kerja' => $this->input->post('cbounitkerja_pokok'),
			'keterangan' => $this->input->post('txtketerangan_pokok'),
			'activated' => $this->input->post('chkaktif_pokok'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
		);
		$result = $this->db->update($this->tbl_karyawan, $data, array('id' => $id_karyawan));
		return $result;
	}
	
	function karyawan_view($id_karyawan) {
      $sql = "SELECT
				*
			  FROM
				".$this->view_karyawan."
			  WHERE
				id_karyawan = '".$id_karyawan."'";
	  return $this->db->query($sql);
    }
	// ========================================= POKOK - END ====================================================
	
	// ========================================= PRESENSI & CUTI - BEGIN ========================================
	function create_detail_presensi_cuti($data) {        
		return $this->db->insert($this->tbl_karyawan_presensi_cuti, $data);
	}
	
	function delete_detail_presensi_cuti() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_presensi_cuti." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_presensi_cuti($id_karyawan) {
		$q = $this->db->query(" SELECT
									a.id,
									a.id_karyawan,
									a.tahun,
									a.bulan,
									b.hari_kerja,
									a.hari_bekerja,
									a.hari_ijin,
									a.hari_cuti,
									a.hari_mangkir,
									a.keterangan
								FROM
									".$this->tbl_karyawan_presensi_cuti." a
									LEFT OUTER JOIN ".$this->tbl_hari_kerja." b ON a.tahun = b.tahun
										AND a.bulan = b.bulan
								WHERE
									a.id_karyawan = '".$id_karyawan."'
								ORDER BY
									a.id");
								
		return $q;
	}
	// ========================================= PRESENSI & CUTI - END ==========================================
	
	// ====================================== PRESTASI - BEGIN ==================================================
	function create_detail_prestasi($data) {
		return $this->db->insert($this->tbl_karyawan_prestasi, $data);
	}
	
	function delete_detail_prestasi() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_prestasi."
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_prestasi($id_karyawan) {
		$q = $this->db->query(" SELECT
									*
								FROM
									".$this->tbl_karyawan_prestasi."
								WHERE
									id_karyawan = '".$id_karyawan."'
								ORDER BY
									id");
								
		return $q;
	}
	// ====================================== PRESTASI - END ====================================================
	
	// ====================================== REKENING - BEGIN ==================================================
	function create_detail_rekening($data) {
		return $this->db->insert($this->tbl_karyawan_rekening, $data);
	}
	
	function delete_detail_rekening() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_rekening."
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_rekening($id_karyawan) {
		$q = $this->db->query(" SELECT
									a.id,
									a.id_karyawan,
									a.id_bank,
									b.nama AS nama_bank,
									a.nomor_rekening,
									a.nama_rekening,
									a.file_rekening
								FROM
									".$this->tbl_karyawan_rekening." a
									LEFT OUTER JOIN ".$this->tbl_bank." b ON a.id_bank = b.id
								WHERE
									a.id_karyawan = '".$id_karyawan."'
								ORDER BY
									a.id");
								
		return $q;
	}
	// ====================================== REKENING - END ====================================================
	
	// ========================================= SANKSI - BEGIN =================================================
	function create_detail_sanksi($data) {        
		return $this->db->insert($this->tbl_karyawan_sanksi, $data);
	}
	
	function delete_detail_sanksi() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_sanksi." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_sanksi($id_karyawan) {
		$q = $this->db->query(" SELECT
									a.id,
									a.id_karyawan,
									a.id_sanksi,
									b.nama AS nama_sanksi,
									a.tanggal_diberikan,
									a.keterangan,
									a.file_sanksi
								FROM
									".$this->tbl_karyawan_sanksi." a
									LEFT OUTER JOIN ".$this->tbl_sanksi." b ON a.id_sanksi = b.id
								WHERE
									a.id_karyawan = '".$id_karyawan."'
								ORDER BY
									a.id");
								
		return $q;
	}
	// ========================================= SANKSI - END ===================================================
	
	// ========================================= TUNJANGAN - BEGIN ==============================================
	function create_detail_tunjangan($data) {        
		return $this->db->insert($this->tbl_karyawan_tunjangan, $data);
	}
	
	function delete_detail_tunjangan() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_karyawan_tunjangan." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function list_detail_tunjangan($id_karyawan) {
		$q = $this->db->query(" SELECT
									a.id,
									a.id_karyawan,
									a.id_tunjangan,
									b.nama AS nama_tunjangan,
									a.tahun,
									a.bulan,
									a.tunjangan,
									a.keterangan
								FROM
									".$this->tbl_karyawan_tunjangan." a
									LEFT OUTER JOIN ".$this->tbl_tunjangan." b ON a.id_tunjangan = b.id
								WHERE
									a.id_karyawan = '".$id_karyawan."'
								ORDER BY
									a.id");
								
		return $q;
	}
	// ========================================= TUNJANGAN - END ================================================
	
}

?>
