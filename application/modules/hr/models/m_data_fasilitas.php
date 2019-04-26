<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_data_fasilitas extends CI_Model {
	
	var $tbl_data_fasilitas		= 'ck_fasilitas';

    function __construct() {
        parent::__construct();
    }

    function data_fasilitas_view($id) {
      $sql = "SELECT
				*
			  FROM
				".$this->tbl_data_fasilitas."
			  WHERE id = '".$id."'";
	  return $this->db->query($sql);
    }

    function data_fasilitas_create() {
		$data = array(
			'nama' => $this->input->post('txtnama_add'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT')),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
		);
        $result = $this->db->insert($this->tbl_data_fasilitas, $data);
        return $result;
    }

    function data_fasilitas_update() {
		$id = $this->input->post('txtid_edit');
        $data = array(
			'nama' => $this->input->post('txtnama_edit'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
		);
        $result = $this->db->update($this->tbl_data_fasilitas, $data, array('id' => $id));
        return $result;
    }

    function data_fasilitas_delete($id) {
		$result = $this->db->delete($this->tbl_data_fasilitas, array('id' => $id));
		return $result;
    }
}
?>
