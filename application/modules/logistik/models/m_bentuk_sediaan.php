<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_bentuk_sediaan extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function bentuk_sediaan_view($id) {
      $sql = "SELECT
				a.id,
				a.nama AS nama_bentuk_sediaan,
				a.deskripsi,
				a.produk_perbekalan_id,
				b.nama AS nama_perbekalan,
				a.activated
			  FROM
				ck_produk_bentuk_sediaan a
				LEFT OUTER JOIN ck_produk_perbekalan b ON a.produk_perbekalan_id = b.id
			  WHERE a.id = '".$id."'";
	  return $this->db->query($sql);
    }

    function bentuk_sediaan_create() {
		$data = array(
			'nama' => $this->input->post('txtnama_add'),
			'deskripsi' => $this->input->post('txtdeskripsi_add'),
			'produk_perbekalan_id' => $this->input->post('cboprodukperbekalan_add'),
			'activated_by' => $this->session->userdata('user_name'),
			'activated_date' => date('Y-m-d H:i:s'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date('Y-m-d H:i:s'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->insert('ck_produk_bentuk_sediaan', $data);
        return $result;
    }

    function bentuk_sediaan_update() {
		$id = $this->input->post('txtid_edit');
        $data = array(
			'nama' => $this->input->post('txtnama_edit'),
			'deskripsi' => $this->input->post('txtdeskripsi_edit'),
			'produk_perbekalan_id' => $this->input->post('cboprodukperbekalan_edit'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date('Y-m-d H:i:s')
		);
        $result = $this->db->update('ck_produk_bentuk_sediaan', $data, array('id' => $id));
        return $result;
    }

    function bentuk_sediaan_delete($id) {
		$result = $this->db->delete('ck_produk_bentuk_sediaan', array('id' => $id));
		return $result;
    }
	
	function get_perbekalan_farmasi() {
        $query = "SELECT
					 *
				  FROM
					 ck_produk_perbekalan
				  ORDER BY
				     nama";
        return $this->db->query($query);
    }
}
?>
