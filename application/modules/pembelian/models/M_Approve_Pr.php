<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Approve_Pr extends CI_Model {
	
	var $tbl_pr_hdr     = 'ck_tbl_beli_purchaserequest_hdr';
	var $tbl_pr_dtl     = 'ck_tbl_beli_purchaserequest_dtl';
	
	var $view_pr_hdr 	= 'ck_view_beli_purchaserequest_hdr';
	var $view_pr_dtl   	= 'ck_view_beli_purchaserequest_dtl';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
									* 
                               FROM
									".$this->view_pr_hdr."
							   WHERE
									id_header = '".$hid."'
							  ");
		return $q;
	}
	
	function get_detail($hid) {
		$q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_pr_dtl."
							   WHERE
									id_header = '".$hid."'
							   ORDER BY
									id_detail");
		return $q;
	}
	
	function update_approve() {
		$id_header = $this->input->post('id_header');
		$status_pr = $this->input->post('status_pr');
		$status_histori = $this->input->post('status_histori');
        return $this->db->query("UPDATE
									".$this->tbl_pr_hdr." 
                                 SET
									status_pr = '".$status_pr."',
									status_histori = CONCAT(status_histori, '".$status_histori."')
                                 WHERE
									id = '".$id_header."'");
    }
	
	function update_item_detail() {
		$id_detail = $this->input->post('id_detail');
		$tgl_diperlukan = date_format(new DateTime($this->input->post('tgl_diperlukan')), $this->config->item('FORMAT_DATE_TO_INSERT'));
		$qty_approve = $this->input->post('qty_approve');
        return $this->db->query("UPDATE
									".$this->tbl_pr_dtl." 
                                 SET
									tgl_diperlukan = '".$tgl_diperlukan."', 
									qty_approve = '".$qty_approve."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function delete_item_detail() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
									FROM
									".$this->tbl_pr_dtl." 
                                 WHERE
									id = '".$id_detail."'");
    }
	
} 