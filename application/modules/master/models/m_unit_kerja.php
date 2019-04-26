<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_unit_kerja extends CI_Model {
	
	var $tbl_unit_kerja		= 'ck_unit_kerja';
	var $tbl_kelompok_kerja	= 'ck_kelompok_kerja';
	
	var $view_unit_kerja	= 'ck_view_master_unit_kerja';

    function __construct() {
        parent::__construct();
    }

    function unit_kerja_view($id) {
      $sql = "SELECT
				*
			  FROM
				".$this->view_unit_kerja."
			  WHERE
				id_unit_kerja = '".$id."'";
	  return $this->db->query($sql);
    }

    function unit_kerja_create() {
		$data = array(
			'kode' => $this->input->post('txtkode'),
			'nama' => $this->input->post('txtnama'),			
			'kelompok_kerja_id' => $this->input->post('cbokelompokkerja'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'activated' => $this->input->post('chkaktif'),
			'activated_by' => $this->session->userdata('user_name'),
			'activated_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
		);
        $result = $this->db->insert($this->tbl_unit_kerja, $data);
        return $result;
    }

    function unit_kerja_update() {
		$id = $this->input->post('txtid');
        $data = array(
			'kode' => $this->input->post('txtkode'),
			'nama' => $this->input->post('txtnama'),
			'kelompok_kerja_id' => $this->input->post('cbokelompokkerja'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'activated' => $this->input->post('chkaktif'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
		);
        $result = $this->db->update($this->tbl_unit_kerja, $data, array('id' => $id));
        return $result;
    }

    function unit_kerja_delete($id) {
		$result = $this->db->delete($this->tbl_unit_kerja, array('id' => $id));
		return $result;
    }
	
	function get_kelompok_kerja() {
        $query = "SELECT
					 *
				  FROM
					 ".$this->tbl_kelompok_kerja."
				  ORDER BY
				     level";
        return $this->db->query($query);
    }
}

?>
