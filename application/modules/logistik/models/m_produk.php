<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_produk extends CI_Model {
	
	var $tbl_produk					= 'ck_produk';
	var $tbl_produk_perbekalan		= 'ck_produk_perbekalan';
	var $tbl_principal				= 'ck_supplier';
	var $tbl_kelas_terapi			= 'ck_produk_klasifikasi';
	var $tbl_produk_lokasi			= 'ck_produk_lokasi';
	var $tbl_lokasi					= 'ck_lokasi';
	var $tbl_produk_supplier		= 'ck_produk_supplier';
	
	var $view_produk_all			= 'ck_view_logistik_produk_all';

    function __construct() {
        parent::__construct();
    }
	
	function get_data_master($source) {
		$query = "SELECT
					 *
				  FROM
					 ".$source."
				  ORDER BY
				     nama";
        return $this->db->query($query);
	}
	
	function get_data_perbekalan() {
		$query = "SELECT
					 *
				  FROM
					 ".$this->tbl_produk_perbekalan."
				  WHERE
					 level = '1'
				  ORDER BY
					 nama";
        return $this->db->query($query);
	}
	
	function get_data_kelompok($id_perbekalan) {
		$query = "SELECT
					 *
				  FROM
					 ".$this->tbl_produk_perbekalan."
				  WHERE
					 level = '2'
					 AND id_parent = '".$id_perbekalan."'
				  ORDER BY
					 nama";
        echo json_encode($this->db->query($query)->result());
	}
	
	function get_data_golongan($id_kelompok) {
		$query = "SELECT
					 *
				  FROM
					 ".$this->tbl_produk_perbekalan."
				  WHERE
					 level = '3'
					 AND id_parent = '".$id_kelompok."'
				  ORDER BY
					 nama";
        echo json_encode($this->db->query($query)->result());
	}
	
	function get_data_jenis($id_golongan) {
		$query = "SELECT
					 *
				  FROM
					 ".$this->tbl_produk_perbekalan."
				  WHERE
					 level = '4'
					 AND id_parent = '".$id_golongan."'
				  ORDER BY
					 nama";
        echo json_encode($this->db->query($query)->result());
	}
	
	function get_data_kelas_terapi() {
		$query = "SELECT
					 *
				  FROM
					 ".$this->tbl_kelas_terapi."
				  ORDER BY
					 nama";
        echo json_encode($this->db->query($query)->result());
	}
	
	function get_data_lokasi($id_kelompok) {
		$query = "SELECT
					 *
				  FROM
					 ".$this->tbl_lokasi."
				  WHERE
					 id_kelompok_produk = '".$id_kelompok."'
				  ORDER BY
					 lorong,
					 rak,
					 baris,
					 kolom";
        echo json_encode($this->db->query($query)->result());
	}
	
	function get_mitra_usaha($kelompok) {
        $query = "SELECT
					 *
				  FROM
					 ".$this->tbl_principal."
				  WHERE
					 kelompok = '".$kelompok."'
				  ORDER BY
				     nama";
        return $this->db->query($query);
    }

    function produk_view($id_produk) {
      $sql = "SELECT
				*
			  FROM
				".$this->view_produk_all."
			  WHERE
				id_produk = '".$id_produk."'";
	  return $this->db->query($sql);
    }
	
	function produk_supplier_view($id_produk) {
      $sql = "SELECT
				*
			  FROM
				".$this->tbl_produk_supplier."
			  WHERE
				id_produk = '".$id_produk."'";
	  return $this->db->query($sql);
    }
	
	function produk_lokasi_view($id_produk) {
      $sql = "SELECT
				*
			  FROM
				".$this->tbl_produk_lokasi."
			  WHERE
				id_produk = '".$id_produk."'";
	  return $this->db->query($sql);
    }

    function produk_create() {
		$kode  = $this->input->post('txtkode');
		$find = ["/", ".", "`"];
		$replace   = ["_"];
		$new_kode = str_replace($find, $replace, $kode);
		
		if ($_FILES['gambar_1']['name'] != '') {
			$file_name_1 = $_FILES['gambar_1']['name'];
			$array_var_1 = explode(".", $file_name_1);
			$file_ext_1 = end($array_var_1);
			$file_tmp_1 = $_FILES['gambar_1']['tmp_name'];
			$new_file_1 = "assets/img/produk/".$file_name_1;
			$rename_file_1 = "assets/img/produk/".$new_kode."_1.".$file_ext_1;
			move_uploaded_file($file_tmp_1, $new_file_1);
			rename($new_file_1, $rename_file_1);
		} else {
			$rename_file_1 = '';
		}
		
		if ($_FILES['gambar_2']['name'] != '') {
			$file_name_2 = $_FILES['gambar_2']['name'];
			$array_var_2 = explode(".", $file_name_2);
			$file_ext_2 = end($array_var_2);
			$file_tmp_2 = $_FILES['gambar_2']['tmp_name'];
			$new_file_2 = "assets/img/produk/".$file_name_2;
			$rename_file_2 = "assets/img/produk/".$new_kode."_2.".$file_ext_2;
			move_uploaded_file($file_tmp_2, $new_file_2);
			rename($new_file_2, $rename_file_2);
		} else {
			$rename_file_2 = '';
		}
		
		if ($_FILES['gambar_3']['name'] != '') {
			$file_name_3 = $_FILES['gambar_3']['name'];
			$array_var_3 = explode(".", $file_name_3);
			$file_ext_3 = end($array_var_3);
			$file_tmp_3 = $_FILES['gambar_3']['tmp_name'];
			$new_file_3 = "assets/img/produk/".$file_name_3;
			$rename_file_3 = "assets/img/produk/".$new_kode."_3.".$file_ext_3;
			move_uploaded_file($file_tmp_3, $new_file_3);
			rename($new_file_3, $rename_file_3);
		} else {
			$rename_file_3 = '';
		}
		
		$data = array(
			'kode' => $this->input->post('txtkode'),
			'nama' => $this->input->post('txtnama'),
			'produk_perbekalan_id' => $this->input->post('cboperbekalanproduk'),
			'produk_kelompok_id' => $this->input->post('cbokelompokproduk'),
			'produk_golongan_id' => $this->input->post('cbogolonganproduk'),
			'produk_jenis_id' => $this->input->post('cbojenisproduk'),
			'produk_bentuk_sediaan_id' => $this->input->post('cbobentuksediaan'),
			'kadar_isi' => $this->input->post('txtkadarisi'),			
			'kadar_satuan_id' => $this->input->post('cbosatuanproduk'),
			'id_fungsi' => $this->input->post('cbofungsiproduk'),
			'komposisi' => $this->input->post('txtkomposisi'),
			'indikasi' => $this->input->post('txtindikasi'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'kemasan_id_primer' => $this->input->post('cbokemasanprimer'),
			'kemasan_isi_primer' => $this->input->post('txtisiprimer'),
			'kemasan_id_sekunder' => $this->input->post('cbokemasansekunder'),
			'kemasan_isi_sekunder' => $this->input->post('txtisisekunder'),
			'kemasan_id_tersier' => $this->input->post('cbokemasantersier'),
			'kemasan_isi_tersier' => $this->input->post('txtisitersier'),
			'kemasan_default' => $this->input->post('cbokemasanutama'),
			'klasifikasi_id' => $this->input->post('cboklasifikasi'),
			'principal_id' => $this->input->post('cboprincipal'),
			'min_stok' => $this->input->post('txtminstok'),
			'max_stok' => $this->input->post('txtmaxstok'),
			'harga_beli' => $this->input->post('txthargabeli'),
			'harga_jual_min' => $this->input->post('txthargajualmin'),
			'harga_jual_max' => $this->input->post('txthargajualmax'),
			'diskon_max' => $this->input->post('txtdiskonmax'),
			'expired_limit' => $this->input->post('txtexpiredlimit'),
			'lead_time' => $this->input->post('txtleadtime'),
			'gambar_1' => $rename_file_1,
			'gambar_2' => $rename_file_2,
			'gambar_3' => $rename_file_3,
			'activated' => $this->input->post('chkaktif'),
			'activated_by' => $this->session->userdata('user_name'),
			'activated_date' => date('Y-m-d H:i:s'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date('Y-m-d H:i:s'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $this->db->insert($this->tbl_produk, $data);
		$result = $this->db->insert_id();		
		
		$this->db->delete($this->tbl_produk_supplier, array('id_produk' => $result));		
		$arr_supplier = explode(',', $this->input->post('cbosupplier'));
		while(list($key, $val) = each ($arr_supplier)) {
			$data_supp = array(
				'id_produk' => $result,
				'id_supplier' => $val,
				'created_by' => $this->session->userdata('user_name'),
				'created_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
				'modified_by' => $this->session->userdata('user_name'),
				'modified_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
			);
			$this->db->insert($this->tbl_produk_supplier, $data_supp);
		}
		
		$this->db->delete($this->tbl_produk_lokasi, array('id_produk' => $result));		
		$arr_lokasi = explode(',', $this->input->post('cbolokasiproduk'));
		while(list($key, $val) = each ($arr_lokasi)) {
			$data_lokasi = array(
				'id_produk' => $result,
				'id_lokasi' => $val,		
				'created_by' => $this->session->userdata('user_name'),
				'created_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
				'modified_by' => $this->session->userdata('user_name'),
				'modified_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
			);
			$this->db->insert($this->tbl_produk_lokasi, $data_lokasi);
		}
		
        return $result;
    }

    function produk_update() {
		$id_produk = $this->input->post('txtid');
		
		$this->db->delete($this->tbl_produk_supplier, array('id_produk' => $id_produk));		
		$arr_supplier = explode(',', $this->input->post('cbosupplier'));
		while(list($key, $val) = each ($arr_supplier)) {
			$data_supp = array(
				'id_produk' => $id_produk,
				'id_supplier' => $val,
				'modified_by' => $this->session->userdata('user_name'),
				'modified_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
			);
			$this->db->insert('ck_produk_supplier', $data_supp);
		}
		
		$this->db->delete($this->tbl_produk_lokasi, array('id_produk' => $id_produk));		
		$arr_lokasi = explode(',', $this->input->post('cbolokasiproduk'));
		while(list($key, $val) = each ($arr_lokasi)) {
			$data_lokasi = array(
				'id_lokasi' => $val,
				'id_produk' => $id_produk,
				'modified_by' => $this->session->userdata('user_name'),
				'modified_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
			);
			$this->db->insert($this->tbl_produk_lokasi, $data_lokasi);
		}
		
		$kode  = $this->input->post('txtkode');
		$find = ["/", ".", "`"];
		$replace   = ["_"];
		$new_kode = str_replace($find, $replace, $kode);
		
		if ($_FILES['gambar_1']['name'] != '') {
			$file_name_1 = $_FILES['gambar_1']['name'];
			$array_var_1 = explode(".", $file_name_1);
			$file_ext_1 = end($array_var_1);
			$file_tmp_1 = $_FILES['gambar_1']['tmp_name'];
			$new_file_1 = "assets/img/produk/".$file_name_1;
			$rename_file_1 = "assets/img/produk/".$new_kode."_1.".$file_ext_1;
			move_uploaded_file($file_tmp_1, $new_file_1);
			rename($new_file_1, $rename_file_1);
		} else {
			$rename_file_1 = $this->input->post('txtimage_1');
		}
		
		if ($_FILES['gambar_2']['name'] != '') {
			$file_name_2 = $_FILES['gambar_2']['name'];
			$array_var_2 = explode(".", $file_name_2);
			$file_ext_2 = end($array_var_2);
			$file_tmp_2 = $_FILES['gambar_2']['tmp_name'];
			$new_file_2 = "assets/img/produk/".$file_name_2;
			$rename_file_2 = "assets/img/produk/".$new_kode."_2.".$file_ext_2;
			move_uploaded_file($file_tmp_2, $new_file_2);
			rename($new_file_2, $rename_file_2);
		} else {
			$rename_file_2 = $this->input->post('txtimage_2');
		}
		
		if ($_FILES['gambar_3']['name'] != '') {
			$file_name_3 = $_FILES['gambar_3']['name'];
			$array_var_3 = explode(".", $file_name_3);
			$file_ext_3 = end($array_var_3);
			$file_tmp_3 = $_FILES['gambar_3']['tmp_name'];
			$new_file_3 = "assets/img/produk/".$file_name_3;
			$rename_file_3 = "assets/img/produk/".$new_kode."_3.".$file_ext_3;
			move_uploaded_file($file_tmp_3, $new_file_3);
			rename($new_file_3, $rename_file_3);
		} else {
			$rename_file_3 = $this->input->post('txtimage_3');
		}
		
        $data = array(
			'nama' => $this->input->post('txtnama'),
			'produk_perbekalan_id' => $this->input->post('cboperbekalanproduk'),
			'produk_kelompok_id' => $this->input->post('cbokelompokproduk'),
			'produk_golongan_id' => $this->input->post('cbogolonganproduk'),
			'produk_jenis_id' => $this->input->post('cbojenisproduk'),
			'produk_bentuk_sediaan_id' => $this->input->post('cbobentuksediaan'),
			'kadar_isi' => $this->input->post('txtkadarisi'),
			'kadar_satuan_id' => $this->input->post('cbosatuanproduk'),
			'id_fungsi' => $this->input->post('cbofungsiproduk'),
			'komposisi' => $this->input->post('txtkomposisi'),
			'indikasi' => $this->input->post('txtindikasi'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'kemasan_id_primer' => $this->input->post('cbokemasanprimer'),
			'kemasan_isi_primer' => $this->input->post('txtisiprimer'),
			'kemasan_id_sekunder' => $this->input->post('cbokemasansekunder'),
			'kemasan_isi_sekunder' => $this->input->post('txtisisekunder'),
			'kemasan_id_tersier' => $this->input->post('cbokemasantersier'),
			'kemasan_isi_tersier' => $this->input->post('txtisitersier'),
			'kemasan_default' => $this->input->post('cbokemasanutama'),
			'klasifikasi_id' => $this->input->post('cboklasifikasi'),
			'principal_id' => $this->input->post('cboprincipal'),
			'min_stok' => $this->input->post('txtminstok'),
			'max_stok' => $this->input->post('txtmaxstok'),
			'harga_beli' => $this->input->post('txthargabeli'),
			'harga_jual_min' => $this->input->post('txthargajualmin'),
			'harga_jual_max' => $this->input->post('txthargajualmax'),
			'diskon_max' => $this->input->post('txtdiskonmax'),
			'expired_limit' => $this->input->post('txtexpiredlimit'),
			'lead_time' => $this->input->post('txtleadtime'),
			'gambar_1' => $rename_file_1,
			'gambar_2' => $rename_file_2,
			'gambar_3' => $rename_file_3,
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->update($this->tbl_produk, $data, array('id' => $id_produk));
        return $result;
    }

    function produk_delete($id_produk) {
		$result = $this->db->delete($this->tbl_produk, array('id' => $id_produk));
		return $result;
    }
	
}