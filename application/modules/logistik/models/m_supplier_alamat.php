<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_supplier_alamat extends CI_Model {
	
	var $tbl_alamat_supplier	= 'ck_supplier_alamat';
	var $tbl_provinsi			= 'ck_provinsi';
	var $tbl_kabupatenkota		= 'ck_kabupatenkota';
	
	var $view_alamat_supplier	= 'ck_supplier_alamat_view';
	var $view_supplier			= 'ck_supplier_view';

    function __construct() {
        parent::__construct();
    }
	
	function get_supplier() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->view_supplier."
								 WHERE
									id_supplier = '".$this->input->get('sid')."'
								 ORDER BY
									nama_supplier");
	}
	
	function get_propinsi() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_provinsi."
								 ORDER BY
									nama");
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

    function supplier_alamat_view($id) {
      $sql = "SELECT
				*
			  FROM
				".$this->view_alamat_supplier."
			  WHERE
				id = '".$id."'";
	  return $this->db->query($sql);
    }
	
	function supplier_alamat_create() {
		$nama  = $this->input->post('txtnama');
		$find = ["/", ".", "`"];
		$replace   = ["_"];
		$new_nama = str_replace($find, $replace, $nama);
		
		$file_name_1 = $_FILES['gambar_1']['name'];
		$array_var_1 = explode(".", $file_name_1);
		$file_ext_1 = end($array_var_1);
		$file_tmp_1 = $_FILES['gambar_1']['tmp_name'];
		$new_file_1 = "assets/img/supplier/".$file_name_1;
		$rename_file_1 = "assets/img/supplier/".$new_nama."_1.".$file_ext_1;
		move_uploaded_file($file_tmp_1, $new_file_1);
		rename($new_file_1, $rename_file_1);
		
		$file_name_2 = $_FILES['gambar_2']['name'];
		$array_var_2 = explode(".", $file_name_2);
		$file_ext_2 = end($array_var_2);
		$file_tmp_2 = $_FILES['gambar_2']['tmp_name'];
		$new_file_2 = "assets/img/supplier/".$file_name_2;
		$rename_file_2 = "assets/img/supplier/".$new_nama."_2.".$file_ext_2;
		move_uploaded_file($file_tmp_2, $new_file_2);
		rename($new_file_2, $rename_file_2);
		
		$file_name_3 = $_FILES['gambar_3']['name'];
		$array_var_3 = explode(".", $file_name_3);
		$file_ext_3 = end($array_var_3);
		$file_tmp_3 = $_FILES['gambar_3']['tmp_name'];
		$new_file_3 = "assets/img/supplier/".$file_name_3;
		$rename_file_3 = "assets/img/supplier/".$new_nama."_3.".$file_ext_3;
		move_uploaded_file($file_tmp_3, $new_file_3);
		rename($new_file_3, $rename_file_3);
		
		$data = array(
			'id_supplier' => $this->input->post('cbosupplier'),
			'nama_alamat' => $this->input->post('txtnama'),
			'alamat' => $this->input->post('txtalamat'),
			'id_provinsi' => $this->input->post('cboprovinsi'),
			'id_kabupatenkota' => $this->input->post('cbokabupatenkota'),
			'kode_pos' => $this->input->post('txtkodepos'),			
			'telepon' => $this->input->post('txttelepon'),
			'faks' => $this->input->post('txtfaks'),
			'email' => $this->input->post('txtemail'),			
			'website' => $this->input->post('txtwebsite'),
			'pic_nama' => $this->input->post('txtpicnama'),
			'pic_jabatan' => $this->input->post('txtpicjabatan'),
			'pic_hp' => $this->input->post('txtpichp'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'foto_1' => $rename_file_1,
			'foto_2' => $rename_file_2,
			'foto_3' => $rename_file_3,
			'latitude' => $this->input->post('txtlatitude'),
			'longitude' => $this->input->post('txtlongitude'),
			'is_default' => $this->input->post('chkdefault'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date('Y-m-d H:i:s'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->insert($this->tbl_alamat_supplier, $data);
        return $result;
    }

    function supplier_alamat_update() {
		$id = $this->input->post('txtid');
        $data = array(
			'id_supplier' => $this->input->post('cbosupplier'),
			'nama_alamat' => $this->input->post('txtnama'),
			'alamat' => $this->input->post('txtalamat'),
			'id_provinsi' => $this->input->post('cboprovinsi'),
			'id_kabupatenkota' => $this->input->post('cbokabupatenkota'),
			'kode_pos' => $this->input->post('txtkodepos'),			
			'telepon' => $this->input->post('txttelepon'),
			'faks' => $this->input->post('txtfaks'),
			'email' => $this->input->post('txtemail'),			
			'website' => $this->input->post('txtwebsite'),
			'pic_nama' => $this->input->post('txtpicnama'),
			'pic_jabatan' => $this->input->post('txtpicjabatan'),
			'pic_hp' => $this->input->post('txtpichp'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'foto_1' => $rename_file_1,
			'foto_2' => $rename_file_2,
			'foto_3' => $rename_file_3,
			'latitude' => $this->input->post('txtlatitude'),
			'longitude' => $this->input->post('txtlongitude'),
			'is_default' => $this->input->post('chkdefault'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->update($this->tbl_alamat_supplier, $data, array('id' => $id));
        return $result;
    }

    function supplier_alamat_delete($id) {
		$result = $this->db->delete($this->tbl_alamat_supplier, array('id' => $id));
		return $result;
    }
}
?>
