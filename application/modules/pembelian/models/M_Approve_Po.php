<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Approve_Po extends CI_Model {
    
    var $tbl_po_hdr       	= 'ck_tbl_beli_purchaseorder_hdr';
    var $tbl_po_dtl       	= 'ck_tbl_beli_purchaseorder_dtl';
    var $tbl_supplier     	= 'ck_supplier';
    var $tbl_produk       	= 'ck_produk';
    var $tbl_produk_kemasan	= 'ck_produk_kemasan';
    
	var $view_po_hdr 		= 'ck_view_beli_purchaseorder_hdr';
	var $view_produk_aktif	= 'ck_view_logistik_produk_aktif';
	var $view_stok_akhir	= 'ck_view_logistik_stok_akhir_2';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
									* 
                               FROM
									".$this->view_po_hdr."
							   WHERE
									id_po='".$hid."'
							  ");
		return $q;
	}
	
	function get_detail($hid) {
		$q = $this->db->query("SELECT
									a.id AS id_detail,
									a.id_produk,
									b.nama_produk, 
                                    b.id_kemasan,
									b.nama_kemasan, 
									a.qty_po,
									a.harga_satuan,
									a.total,
									a.disc_persen,
									a.disc_rupiah,
									a.netto
							   FROM
									".$this->tbl_po_dtl." a
									LEFT OUTER JOIN	".$this->view_produk_aktif." b ON a.id_produk = b.id_produk
							   WHERE
									a.id_header='".$hid."'");
								
		return $q;
	}
	
	function update_approve() {
		$id_header = $this->input->post('id_header');
		$status_po = $this->input->post('status_po');
		$status_histori = $this->input->post('status_histori');
        return $this->db->query("UPDATE
									".$this->tbl_po_hdr." 
                                 SET
									status_po = '".$status_po."',
									status_histori = CONCAT(status_histori, '".$status_histori."')
                                 WHERE
									id = '".$id_header."'");
    }
	
} 