<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_kelompok_kerja extends CI_Model {
	
	var $tbl_kelompok_kerja		= 'ck_kelompok_kerja';

    function __construct() {
        parent::__construct();
    }

    function kelompok_kerja_view($id) {
      $sql = "SELECT
				*
			  FROM
				".$this->tbl_kelompok_kerja."
			  WHERE
				id = '".$id."'";
	  return $this->db->query($sql);
    }

    function kelompok_kerja_create() {
		$data = array(
			'nama' => $this->input->post('txtnama'),
			'level' => $this->input->post('cbolevel'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'activated' => $this->input->post('chkaktif'),
			'activated_by' => $this->session->userdata('user_name'),
			'activated_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
		);
        $result = $this->db->insert($this->tbl_kelompok_kerja, $data);
        return $result;
    }

    function kelompok_kerja_update() {
		$id = $this->input->post('txtid');
        $data = array(
			'nama' => $this->input->post('txtnama'),
			'level' => $this->input->post('cbolevel'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'activated' => $this->input->post('chkaktif'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
		);
        $result = $this->db->update($this->tbl_kelompok_kerja, $data, array('id' => $id));
        return $result;
    }

    function kelompok_kerja_delete($id) {
		$result = $this->db->delete($this->tbl_kelompok_kerja, array('id' => $id));
		return $result;
    }
}

?>
