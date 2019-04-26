<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_jabatan extends CI_Model {
	
	var $tbl_jabatan		= 'ck_jabatan';
	var $tbl_unit_kerja		= 'ck_unit_kerja';
	var $tbl_kelompok_kerja	= 'ck_kelompok_kerja';
	
	var $view_jabatan		= 'ck_view_master_jabatan';

    function __construct() {
        parent::__construct();
    }

    function jabatan_view($id) {
      $sql = "SELECT
				*
			  FROM
				".$this->view_jabatan."
			  WHERE
				id_jabatan = '".$id."'";
	  return $this->db->query($sql);
    }

    function jabatan_create() {
		$data = array(
			'kode' => $this->input->post('txtkode'),
			'nama' => $this->input->post('txtnama'),
			'level' => $this->input->post('cboleveljabatan'),
			'kelompok_kerja_id' => $this->input->post('cbokelompokkerja'),
			'unit_kerja_id' => $this->input->post('cbounitkerja'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'activated' => $this->input->post('chkaktif'),
			'activated_by' => $this->session->userdata('user_name'),
			'activated_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'created_by' => $this->session->userdata('user_name'),
			'created_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
		);
        $result = $this->db->insert($this->tbl_jabatan, $data);
        return $result;
    }

    function jabatan_update() {
		$id = $this->input->post('txtid');
        $data = array(
			'kode' => $this->input->post('txtkode'),
			'nama' => $this->input->post('txtnama'),
			'level' => $this->input->post('cboleveljabatan'),
			'kelompok_kerja_id' => $this->input->post('cbokelompokkerja'),
			'unit_kerja_id' => $this->input->post('cbounitkerja'),
			'deskripsi' => $this->input->post('txtdeskripsi'),
			'activated' => $this->input->post('chkaktif'),
			'modified_by' => $this->session->userdata('user_name'),
			'modified_date' => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
		);
        $result = $this->db->update($this->tbl_jabatan, $data, array('id' => $id));
        return $result;
    }

    function jabatan_delete($id) {
		$result = $this->db->delete($this->tbl_jabatan, array('id' => $id));
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
	
	function get_unit_kerja($id_kelompok_kerja) {
		$query = "SELECT
					 *
				  FROM
					 ".$this->tbl_unit_kerja."
				  WHERE
					 kelompok_kerja_id = '".$id_kelompok_kerja."'
				  ORDER BY
					 nama";
        echo json_encode($this->db->query($query)->result());
	}
}

?>
