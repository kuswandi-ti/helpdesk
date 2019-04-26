<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Validasi_Tagihan extends CI_Model {
	
	var $tbl_pt_hdr				= 'ck_tbl_beli_penerimaantagihan_hdr';
	var $tbl_pt_dtl				= 'ck_tbl_beli_penerimaantagihan_dtl';
	var $tbl_gr_hdr				= 'ck_tbl_beli_goodsreceive_hdr';
	var $tbl_supplier			= 'ck_supplier';
	
	var $view_pt_hdr			= 'ck_view_beli_penerimaantagihan_hdr';
	var $view_pt_dtl			= 'ck_view_beli_penerimaantagihan_dtl';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
									* 
                               FROM
									".$this->view_pt_hdr."
							   WHERE
									id_header = '".$hid."'
							  ");
		return $q;
	}
	
	function get_detail($hid) {
		$hid = $this->input->get('hid');
		$q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_pt_dtl." 
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
									".$this->view_pt_dtl." 
							     WHERE
									id_header = '".$hid."'	
						         ORDER BY
									id_detail");
    }
	
	function update_header() {
		$id_header = $this->input->post('id_header');
        return $this->db->query("UPDATE
									".$this->tbl_pt_hdr." 
                                 SET
									validasi = '1',
									validasi_by = '".$this->session->userdata('user_name')."',
									validasi_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_header."'");
    }
	
} 