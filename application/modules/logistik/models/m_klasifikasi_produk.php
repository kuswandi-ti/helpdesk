<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_klasifikasi_produk extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function klasifikasi_produk_view($id) {
      $sql = "SELECT
				*
			  FROM
				ck_produk_klasifikasi
			  WHERE id = '".$id."'";
	  return $this->db->query($sql);
    }

    function klasifikasi_produk_create() {
		$data = array(
			'nama' => $this->input->post('txtnama_add'),
			'activated_by' => $this->session->userdata('user_name'),
			'activated_date' => date('Y-m-d H:i:s'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date('Y-m-d H:i:s'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->insert('ck_produk_klasifikasi', $data);
        return $result;
    }

    function klasifikasi_produk_update() {
		$id = $this->input->post('txtid_edit');
        $data = array(
			'nama' => $this->input->post('txtnama_edit'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->update('ck_produk_klasifikasi', $data, array('id' => $id));
        return $result;
    }

    function klasifikasi_produk_delete($id) {
		$result = $this->db->delete('ck_produk_klasifikasi', array('id' => $id));
		return $result;
    }
}
?>
