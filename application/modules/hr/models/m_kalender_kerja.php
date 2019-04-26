<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_kalender_kerja extends CI_Model {
	
	var $tbl_kalender_kerja		= 'ck_kalender_kerja';

    function __construct() {
        parent::__construct();
    }

    function kalender_kerja_view($id) {
      $sql = "SELECT
				*
			  FROM
				".$this->tbl_kalender_kerja."
			  WHERE
				id = '".$id."'";
	  return $this->db->query($sql);
    }

    function kalender_kerja_create() {
		$data = array(
			'tahun' => $this->input->post('txttahun_add'),
			'bulan' => $this->input->post('cbobulan_add'),
			'jumlah_hari_kerja' => $this->input->post('txtjumlahharikerja_add'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT')),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
		);
        $result = $this->db->insert($this->tbl_kalender_kerja, $data);
        return $result;
    }

    function kalender_kerja_update() {
		$id = $this->input->post('txtid_edit');
        $data = array(
			'tahun' => $this->input->post('txttahun_edit'),
			'bulan' => $this->input->post('cbobulan_edit'),
			'jumlah_hari_kerja' => $this->input->post('txtjumlahharikerja_edit'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
		);
        $result = $this->db->update($this->tbl_kalender_kerja, $data, array('id' => $id));
        return $result;
    }

    function kalender_kerja_delete($id) {
		$result = $this->db->delete($this->tbl_kalender_kerja, array('id' => $id));
		return $result;
    }
}
?>
