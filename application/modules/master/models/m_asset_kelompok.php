<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_asset_kelompok extends CI_Model {
	
	var $tbl_asset_kelompok		= 'ck_aset_kelompok';

    function __construct() {
        parent::__construct();
    }

    function asset_kelompok(){
	  $this->db->select('*');
	  $this->db->from($this->tbl_asset_kelompok);
	  $this->db->order_by('nama', 'asc');
	  
	  return $query = $this->db->get();
    }
	
	function asset_view($id){
		$id = array('id' => $this->db->escape_str($id));
		$q 	= $this->db->get_where($this->tbl_asset_kelompok, $id);
		return $q;
	}
	
	function create_asset_kelompok(){
		$data = array(
			'nama' 			=> $this->db->escape_str($this->input->post('txtnama')),
			'kode' 			=> $this->db->escape_str(strtoupper($this->input->post('txtkode'))),
			'deskripsi' 	=> $this->db->escape_str($this->input->post('txtdeskripsi')),
			'created_by' 	=> $this->db->escape_str($this->session->userdata('user_name')),
			'created_date' 	=> $this->db->escape_str($this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'))
		);
        $result = $this->db->insert($this->tbl_asset_kelompok, $data);
        return $result;
	}
	
	function update_asset_kelompok(){
		$id = $this->db->escape_str($this->input->post('txtid'));
		$data = array(
			'nama' 			=> $this->db->escape_str($this->input->post('txtnama')),
			'kode' 			=> $this->db->escape_str(strtoupper($this->input->post('txtkode'))),
			'deskripsi' 	=> $this->db->escape_str($this->input->post('txtdeskripsi')),
			'created_by' 	=> $this->db->escape_str($this->session->userdata('user_name')),
			'created_date' 	=> $this->db->escape_str($this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'))
		);
        $result = $this->db->update($this->tbl_asset_kelompok, $data, array('id' => $id));
        return $result;
	}
	
	
    function delete_asset_kelompok($id) {
		$result = $this->db->delete($this->tbl_asset_kelompok, array('id' => $id));
		return $result;
    }

}
?>