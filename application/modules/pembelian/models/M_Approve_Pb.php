<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Approve_Pb extends CI_Model {
	
	var $tbl_pb_hdr				= 'ck_tbl_beli_pengajuanpembayaran_hdr';
	var $tbl_pb_dtl				= 'ck_tbl_beli_pengajuanpembayaran_dtl';
	
	var $view_pb_hdr			= 'ck_view_beli_pengajuanpembayaran_hdr';
	var $view_pb_dtl			= 'ck_view_beli_pengajuanpembayaran_dtl';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
									* 
                               FROM
									".$this->view_pb_hdr."
							   WHERE
									id_header = '".$hid."'
							  ");
		return $q;
	}
	
	function get_detail($hid) {
		$q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_pb_dtl." 
							   WHERE
									id_header = '".$hid."'	
						       ORDER BY
									id_detail");								
		return $q;
	}
    
    function detail_list($hid) {
		return $this->db->query("SELECT
									*
							     FROM
									".$this->view_pb_dtl." 
							     WHERE
									id_header = '".$hid."'	
						         ORDER BY
									id_detail");
    }
	
	function update_header() {
		$id_header = $this->input->post('id_header');
        return $this->db->query("UPDATE
									".$this->tbl_pb_hdr." 
                                 SET
									approve = '1',
									approve_by = '".$this->session->userdata('user_name')."',
									approve_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_header."'");
    }
	
} 