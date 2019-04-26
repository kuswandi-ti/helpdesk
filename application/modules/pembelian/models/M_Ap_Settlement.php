<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Ap_Settlement extends CI_Model {
	
	var $tbl_py_dtl		= 'ck_tbl_beli_payment_dtl';
	
	var $view_pt_hdr	= 'ck_view_beli_penerimaantagihan_hdr';
	var $view_pt_dtl	= 'ck_view_beli_penerimaantagihan_dtl';
	var $view_pay_dtl	= 'ck_view_beli_payment_dtl';
	var $view_gr_hdr	= 'ck_view_beli_goodsreceive_hdr';
	
	public function __construct() {
		parent::__construct();
	}
	
	function detail_list($no_invoice = '') {
        $q = $this->db->query("SELECT
									b.id_gr,
									b.no_gr,
									b.tgl_gr,
									b.no_po,
									b.no_sj_supplier,
									d.tgl_terima,
									b.total_tagihan,
									c.tgl_bayar,
									COALESCE(c.total_bayar, 0) AS total_bayar
							   FROM
									".$this->view_pt_hdr." a
									LEFT OUTER JOIN ".$this->view_pt_dtl." b ON a.id_header = b.id_header
									LEFT OUTER JOIN ".$this->view_pay_dtl." c ON a.no_invoice_supplier = c.no_invoice_supplier
									LEFT OUTER JOIN ".$this->view_gr_hdr." d ON b.id_gr = d.id_header
							   WHERE
									a.no_invoice_supplier = '".$no_invoice."'	
							   ORDER BY
									b.no_gr");								
		return $q;
    }
	
	function update_header() {
		$all = $this->input->post('arr_id');
		$data = array(
			'flag_settlement' => '1'
		);
		return $this->db
					->where_in('no_invoice_supplier', $all)
					->update($this->tbl_py_dtl, $data);
    }
	
}