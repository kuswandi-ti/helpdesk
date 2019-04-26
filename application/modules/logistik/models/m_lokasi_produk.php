<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_lokasi_produk extends CI_Model {
	
	var $tbl_lokasi			= 'ck_lokasi';
	var $tbl_perbekalan		= 'ck_produk_perbekalan';

    function __construct() {
        parent::__construct();
    }

    function lokasi_produk_view($id) {
      $sql = "SELECT
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
			  WHERE
				a.id = '".$id."'";
	  return $this->db->query($sql);
    }
	
	function get_kelompok_produk() {
        $query = "SELECT
					 *
				  FROM
					 ".$this->tbl_perbekalan."
				  WHERE
					 level = '2'
				  ORDER BY
				     nama";
        return $this->db->query($query);
    }

    function lokasi_produk_create() {
		$kode  = $this->input->post('txtkode_add');
		$find = ["/", ".", "`"];
		$replace   = ["_"];
		$new_kode = str_replace($find, $replace, $kode);
		
		$file_name = $_FILES['gambar_add']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['gambar_add']['tmp_name'];
		$new_file = "assets/img/lokasi_produk/".$file_name;
		$rename_file = "assets/img/lokasi_produk/".$new_kode.".".$file_ext;		
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		$data = array(
			'kode' => $new_kode,
			'id_kelompok_produk' => $this->input->post('cbokelompokproduk_add'),
			'lorong' => $this->input->post('txtlorong_add'),
			'rak' => $this->input->post('txtrak_add'),
			'baris' => $this->input->post('txtbaris_add'),
			'kolom' => $this->input->post('txtkolom_add'),
			'deskripsi' => $this->input->post('txtdeskripsi_add'),
			'gambar' => $rename_file,
			'activated_by' => $this->session->userdata('user_name'),
			'activated_date' => date('Y-m-d H:i:s'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date('Y-m-d H:i:s'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->insert($this->tbl_lokasi, $data);
        return $result;
    }

    function lokasi_produk_update() {
		$kode  = $this->input->post('txtkode_edit');		
		$file_name = $_FILES['gambar_edit']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['gambar_edit']['tmp_name'];
		$new_file = "assets/img/lokasi_produk/".$file_name;
		$rename_file = "assets/img/lokasi_produk/".$kode.".".$file_ext;
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
		$id = $this->input->post('txtid_edit');
        $data = array(
			'kode' => $this->input->post('txtkode_edit'),
			'id_kelompok_produk' => $this->input->post('cbokelompokproduk_edit'),
			'lorong' => $this->input->post('txtlorong_edit'),
			'rak' => $this->input->post('txtrak_edit'),
			'baris' => $this->input->post('txtbaris_edit'),
			'kolom' => $this->input->post('txtkolom_edit'),
			'deskripsi' => $this->input->post('txtdeskripsi_edit'),
			'gambar' => $rename_file,
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->update($this->tbl_lokasi, $data, array('id' => $id));
        return $result;
    }

    function lokasi_produk_delete($id) {
		$result = $this->db->delete($this->tbl_lokasi, array('id' => $id));
		return $result;
    }
}
?>
