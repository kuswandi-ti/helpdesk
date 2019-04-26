<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_data_cuti extends CI_Model {
	
	var $tbl_data_cuti		= 'ck_cuti';

    function __construct() {
        parent::__construct();
    }

    function data_cuti_view($id) {
      $sql = "SELECT
				*
			  FROM
				".$this->tbl_data_cuti."
			  WHERE
				id = '".$id."'";
	  return $this->db->query($sql);
    }

    function data_cuti_create() {
		$data = array(
			'nama' => $this->input->post('txtnama_add'),
			'jumlah' => $this->input->post('txtjumlah_add'),
			'satuan' => $this->input->post('cbosatuan_add'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT')),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
		);
        $result = $this->db->insert($this->tbl_data_cuti, $data);
        return $result;
    }

    function data_cuti_update() {
		$id = $this->input->post('txtid_edit');
        $data = array(
			'nama' => $this->input->post('txtnama_edit'),
			'jumlah' => $this->input->post('txtjumlah_edit'),
			'satuan' => $this->input->post('cbosatuan_edit'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
		);
        $result = $this->db->update($this->tbl_data_cuti, $data, array('id' => $id));
        return $result;
    }

    function data_cuti_delete($id) {
		$result = $this->db->delete($this->tbl_data_cuti, array('id' => $id));
		return $result;
    }
}
?>
