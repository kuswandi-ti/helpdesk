<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_data_kewajiban extends CI_Model {
	
	var $tbl_data_kewajiban		= 'ck_kewajiban';

    function __construct() {
        parent::__construct();
    }

    function data_kewajiban_view($id) {
      $sql = "SELECT
				*
			  FROM
				".$this->tbl_data_kewajiban."
			  WHERE id = '".$id."'";
	  return $this->db->query($sql);
    }

    function data_kewajiban_create() {
		$data = array(
			'nama' => $this->input->post('txtnama_add'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT')),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
		);
        $result = $this->db->insert($this->tbl_data_kewajiban, $data);
        return $result;
    }

    function data_kewajiban_update() {
		$id = $this->input->post('txtid_edit');
        $data = array(
			'nama' => $this->input->post('txtnama_edit'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
		);
        $result = $this->db->update($this->tbl_data_kewajiban, $data, array('id' => $id));
        return $result;
    }

    function data_kewajiban_delete($id) {
		$result = $this->db->delete($this->tbl_data_kewajiban, array('id' => $id));
		return $result;
    }
}
?>
