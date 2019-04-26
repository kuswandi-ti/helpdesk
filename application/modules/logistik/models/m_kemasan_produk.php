<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_kemasan_produk extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function kemasan_produk_view($id) {
      $sql = "SELECT
				a.id,
				a.nama AS nama_kemasan,
				a.deskripsi,
				a.jenis_kemasan,
				b.nama AS nama_jenis_kemasan,
				a.activated
			  FROM
				ck_produk_kemasan a
				LEFT OUTER JOIN ck_produk_kemasan_jenis b ON a.jenis_kemasan = b.id
			  WHERE a.id = '".$id."'";
	  return $this->db->query($sql);
    }

    function kemasan_produk_create() {
		$data = array(
			'nama' => $this->input->post('txtnama_add'),
			'deskripsi' => $this->input->post('txtdeskripsi_add'),
			'jenis_kemasan' => $this->input->post('cbojeniskemasan_add'),
			'activated_by' => $this->session->userdata('user_name'),
			'activated_date' => date('Y-m-d H:i:s'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date('Y-m-d H:i:s'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->insert('ck_produk_kemasan', $data);
        return $result;
    }

    function kemasan_produk_update() {
		$id = $this->input->post('txtid_edit');
        $data = array(
			'nama' => $this->input->post('txtnama_edit'),
			'deskripsi' => $this->input->post('txtdeskripsi_edit'),
			'jenis_kemasan' => $this->input->post('cbojeniskemasan_edit'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->update('ck_produk_kemasan', $data, array('id' => $id));
        return $result;
    }

    function kemasan_produk_delete($id) {
		$result = $this->db->delete('ck_produk_kemasan', array('id' => $id));
		return $result;
    }
	
	function get_jenis_kemasan() {
        $query = "SELECT
					 *
				  FROM
					 ck_produk_kemasan_jenis
				  ORDER BY
				     nama";
        return $this->db->query($query);
    }
}
?>
