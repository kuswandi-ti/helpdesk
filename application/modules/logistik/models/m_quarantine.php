<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_quarantine extends CI_Model {
	
	var $tbl_quarantine_hdr		= 'ck_tbl_logistik_quarantine_hdr';
	var $tbl_quarantine_dtl		= 'ck_tbl_logistik_quarantine_dtl';
	var $tbl_po_dtl				= 'ck_tbl_beli_purchaseorder_dtl';
	var $tbl_stok				= 'ck_stok';
	var $tbl_gr_hdr				= 'ck_tbl_beli_goodsreceive_hdr';
	
	var $view_quarantine_dtl	= 'ck_view_logistik_quarantine_dtl';
	var $view_gr_hdr			= 'ck_view_beli_goodsreceive_hdr';
	var $view_stok				= 'ck_view_logistik_stok_akhir_1';
	var $view_stok_akhir		= 'ck_view_logistik_stok_akhir_2';
	var $view_stok_detail		= 'ck_view_logistik_stok_detail';
	var $view_produk_all		= 'ck_view_logistik_produk_all';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_po() {
		return $this->db->query("SELECT
									b.id_po,
									b.no_po,
									b.nama_supplier
								 FROM
									".$this->tbl_stok." a
									LEFT OUTER JOIN ".$this->view_gr_hdr." b ON a.id_header = b.id_header
								 WHERE
									status = '1'
								 GROUP BY
									b.id_po,
									b.no_po
								 ORDER BY
									b.nama_supplier,
									b.no_po");
    }
	
	function get_produk($id_po) {
		$result =  $this->db->query("SELECT
										a.id_produk,
										b.nama_produk,
										a.batch_number,
										a.expired_date
									 FROM
										".$this->tbl_stok." a
										LEFT OUTER JOIN ".$this->view_produk_all." b ON a.id_produk = b.id_produk
										LEFT OUTER JOIN ".$this->tbl_gr_hdr." c ON a.id_header = c.id
									 WHERE
										c.id_po = '".$id_po."'
									 GROUP BY
										a.id_produk,
										b.nama_produk,
										a.batch_number,
										a.expired_date
									 ORDER BY
										b.nama_produk,
										a.batch_number,
										a.expired_date");
		echo json_encode($result->result());
	}
	
	function get_data_produk($id_produk) {
		$result =  $this->db->query("SELECT
										id_produk,
										batch_number,
										expired_date
									 FROM
										".$this->tbl_stok."
									 WHERE
										id = '".$id_produk."'");
		return $result;
	}
	
	function get_stok($id_produk, $batch_number, $expired_date) {
		/*$result = $this->db->query("SELECT
										b.qty_akhir AS stok
									 FROM
										".$this->view_stok." a
										LEFT OUTER JOIN ".$this->view_stok_detail." b ON a.id_produk = b.id_produk
											AND a.batch_number = b.batch_number
											AND a.expired_date = b.expired_date
											AND a.date_time = b.date_time
										LEFT OUTER JOIN ".$this->tbl_gr_hdr." c ON a.id_header = c.id
									 WHERE
										a.id_produk IN (SELECT id_produk FROM ".$this->tbl_stok." WHERE id = '".$id_stok."')
											AND a.batch_number = '".$batch_number."'
											AND a.expired_date = '".$expired_date."'
										AND c.id_po = '".$id_po."'");*/
		$result = $this->db->query("SELECT
										a.stok_akhir AS stok
									 FROM
										".$this->view_stok_akhir." a
									 WHERE
										a.id_produk = '".$id_produk."'
										AND a.batch_number = '".$batch_number."'
										AND a.expired_date = '".$expired_date."'");
		return $result;
	}
    
    function detail_list($hid = '') {
        $q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_quarantine_dtl."
							   WHERE
									id_header='".$hid."'
							   ORDER BY
									id_detail");								
		return $q;
    }
    
	function create_doc_no($bulan, $tahun) {
		$q = $this->db->query("SELECT
									MAX(RIGHT(no_transaksi, 4)) AS no_transaksi
							   FROM
									".$this->tbl_quarantine_hdr." 
							   WHERE
									month(tgl_transaksi) = '".$bulan."'
									AND year(tgl_transaksi) = '".$tahun."'
							  ");
		$kode = '';
		if($q->num_rows() > 0) {
			foreach($q->result() as $kode) {
				$kode = ((int)$kode->no_transaksi) + 1;
				$kode = sprintf('%04s', $kode);
			}
		} else {
			$kode = '0001';
		}
		
		$pre = "QP"; // QP-YYMM-XXXX
		
		return $pre.
			   "-".
		       substr($tahun, -2).
               substr(("00".$bulan), -2).
               "-".
               $kode;
		
	}
	
	function create_header($data) {		
        $result = $this->db->insert($this->tbl_quarantine_hdr, $data);
		$this->session->set_userdata('hid', $this->db->insert_id());
        return $result;
	}
    
    function create_detail($data) {        
		$result = $this->db->insert($this->tbl_quarantine_dtl, $data);
		return $result;
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
									* 
                               FROM
									".$this->tbl_quarantine_hdr."
							   WHERE
									id = '".$hid."'
							  ");
		return $q;
	}
	
	function get_detail($hid) {
		$q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_quarantine_dtl." a
							   WHERE
									a.id_header='".$hid."'");
								
		return $q;
	}
    
    function delete_item_detail() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM 
									".$this->tbl_quarantine_dtl."
								 WHERE id = '".$id_detail."'");
    }
    
    function update_item_detail() {
		$id_detail = $this->input->post('id_detail');
		$qty = $this->input->post('qty');
		$alasan = $this->input->post('alasan');
        return $this->db->query("UPDATE
									".$this->tbl_quarantine_dtl." 
                                 SET
									qty = '".$qty."',
									alasan = '".$alasan."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".date($this->config->item('FORMAT_DATETIME_TO_INSERT'))."'
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function update_header() {
		$id_header = $this->input->post('id_header');
		$keterangan = $this->input->post('keterangan');
        return $this->db->query("UPDATE "
									.$this->tbl_quarantine_hdr." 
                                 SET
									keterangan = '".$keterangan."'
								 WHERE id = '".$id_header."'");
    }
	
} 