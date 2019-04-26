<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Send_Po extends CI_Model {
    
    var $tbl_po_hdr       	= 'ck_tbl_beli_purchaseorder_hdr';
	var $tbl_po_dtl       	= 'ck_tbl_beli_purchaseorder_dtl';
	
    var $view_po_hdr 		= 'ck_view_beli_purchaseorder_hdr';
	var $view_produk_aktif	= 'ck_view_logistik_produk_aktif';
	
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
									a.id_header = '".$hid."'
							   ORDER BY
									a.id");						
		return $q;
	}
	
	function simpan_kirim() {
		$id_header = $this->input->post('id_header');
		$pengirim = $this->input->post('pengirim');
		$keterangan = $this->input->post('keterangan');
		$tgl_kirim = date_format(new DateTime($this->input->post('tgl_kirim')), $this->config->item('FORMAT_DATE_TO_INSERT'));
        return $this->db->query("UPDATE
									".$this->tbl_po_hdr." 
                                 SET
									pengirim = '".$pengirim."', 
									keterangan_kirim = '".$keterangan."',
									tgl_kirim = '".$tgl_kirim."'
                                 WHERE
									id = '".$id_header."'");
    }
	
} 