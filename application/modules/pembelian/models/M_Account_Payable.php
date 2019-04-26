<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Account_Payable extends CI_Model {
	
	var $tbl_gr_hdr		= 'ck_tbl_beli_goodsreceive_hdr';
	var $view_gr_dtl	= 'ck_view_beli_goodsreceive_dtl';
	
	public function __construct() {
		parent::__construct();
	}
	
	function detail_list($hid = '') {
        $q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_gr_dtl." a
							   WHERE
									a.id_header='".$hid."'
							   ORDER BY
									a.id_detail");								
		return $q;
    }
	
	function update_header() {
		$all = $this->input->post('arr_id');
		$data = array(
			'flag_piutang' => '1'
		);
		return $this->db
					->where_in('id', $all)
					->update($this->tbl_gr_hdr, $data);
    }
	
}