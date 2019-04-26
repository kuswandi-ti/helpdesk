<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_principal extends CI_Model {
	
	var $tbl_principal			= 'ck_supplier';
	var $tbl_tipe_pembayaran	= 'ck_tipe_pembayaran';
	var $view_principal			= 'ck_principal_view';

    function __construct() {
        parent::__construct();
    }
	
	function get_tipe_pembayaran() {
		return $this->db->query('SELECT
									*
								 FROM
									'.$this->tbl_tipe_pembayaran);
	}

    function principal_view($id) {
      $sql = "SELECT
				*
			  FROM
				".$this->view_principal."
			  WHERE
				id_principal = '".$id."'";
	  return $this->db->query($sql);
    }
	
	function principal_create() {
		$nama  = $this->input->post('txtnama');
		$find = ["/", ".", "`"];
		$replace   = ["_"];
		$new_nama = str_replace($find, $replace, $nama);
		
		$file_name_1 = $_FILES['gambar_1']['name'];
		$array_var_1 = explode(".", $file_name_1);
		$file_ext_1 = end($array_var_1);
		$file_tmp_1 = $_FILES['gambar_1']['tmp_name'];
		$new_file_1 = "assets/img/principal/".$file_name_1;
		$rename_file_1 = "assets/img/principal/".$new_nama."_1.".$file_ext_1;
		move_uploaded_file($file_tmp_1, $new_file_1);
		rename($new_file_1, $rename_file_1);
		
		$file_name_2 = $_FILES['gambar_2']['name'];
		$array_var_2 = explode(".", $file_name_2);
		$file_ext_2 = end($array_var_2);
		$file_tmp_2 = $_FILES['gambar_2']['tmp_name'];
		$new_file_2 = "assets/img/principal/".$file_name_2;
		$rename_file_2 = "assets/img/principal/".$new_nama."_2.".$file_ext_2;
		move_uploaded_file($file_tmp_2, $new_file_2);
		rename($new_file_2, $rename_file_2);
		
		$file_name_3 = $_FILES['gambar_3']['name'];
		$array_var_3 = explode(".", $file_name_3);
		$file_ext_3 = end($array_var_3);
		$file_tmp_3 = $_FILES['gambar_3']['tmp_name'];
		$new_file_3 = "assets/img/principal/".$file_name_3;
		$rename_file_3 = "assets/img/principal/".$new_nama."_3.".$file_ext_3;
		move_uploaded_file($file_tmp_3, $new_file_3);
		rename($new_file_3, $rename_file_3);
		
		$data = array(
			'nama' => $this->input->post('txtnama'),
			'kelompok' => '1',
			'id_tipe_pembayaran' => $this->input->post('cbotop'),
			'npwp' => $this->input->post('txtnpwp'),			
			'no_rekening' => $this->input->post('txtnorek'),
			'nama_rekening' => $this->input->post('txtnamarek'),
			'cabang_rekening' => $this->input->post('txtcabangrek'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'activated' => $this->input->post('chkaktif'),
			'activated_by' => $this->session->userdata('user_name'),
			'activated_date' => date('Y-m-d H:i:s'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date('Y-m-d H:i:s'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->insert($this->tbl_principal, $data);
        return $result;
    }

    function principal_update() {
		$id = $this->input->post('txtid');
        $data = array(
			'nama' => $this->input->post('txtnama'),
			'kelompok' => '1',
			'id_tipe_pembayaran' => $this->input->post('cbotop'),
			'npwp' => $this->input->post('txtnpwp'),			
			'no_rekening' => $this->input->post('txtnorek'),
			'nama_rekening' => $this->input->post('txtnamarek'),
			'cabang_rekening' => $this->input->post('txtcabangrek'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'activated' => $this->input->post('chkaktif'),
			'activated_by' => $this->session->userdata('user_name'),
			'activated_date' => date('Y-m-d H:i:s'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->update($this->tbl_principal, $data, array('id' => $id));
        return $result;
    }

    function principal_delete($id) {
		$result = $this->db->delete($this->tbl_principal, array('id' => $id));
		return $result;
    }
}
?>
