<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_perbekalan_farmasi extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function perbekalan_farmasi_view($id) {
      $sql = "SELECT
				*
			  FROM
				ck_produk_perbekalan
			  WHERE id = '".$id."'";
	  return $this->db->query($sql);
    }

    function perbekalan_farmasi_create() {
		$data = array(
			'kode' => $this->input->post('txtkode_add'),
			'nama' => $this->input->post('txtnama_add'),
			'deskripsi' => $this->input->post('txtdeskripsi_add'),
			'activated_by' => $this->session->userdata('user_name'),
			'activated_date' => date('Y-m-d H:i:s'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date('Y-m-d H:i:s'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->insert('ck_produk_perbekalan', $data);
        return $result;
    }

    function perbekalan_farmasi_update() {
		$id = $this->input->post('txtid_edit');
        $data = array(
			'kode' => $this->input->post('txtkode_edit'),
			'nama' => $this->input->post('txtnama_edit'),
			'deskripsi' => $this->input->post('txtdeskripsi_edit'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->update('ck_produk_perbekalan', $data, array('id' => $id));
        return $result;
    }

    function perbekalan_farmasi_delete($id) {
		$result = $this->db->delete('ck_produk_perbekalan', array('id' => $id));
		return $result;
    }
}
?>
