<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_retur_pembelian extends CI_Model {
	
	var $tbl_supplier			= 'ck_supplier';
	var $tbl_gr_hdr				= 'ck_tbl_beli_goodsreceive_hdr';
	var $tbl_retur_dtl			= 'ck_tbl_logistik_returbeli_dtl';
	
	var $view_retur_hdr			= 'ck_view_logistik_returbeli_hdr';
	var $view_retur_dtl			= 'ck_view_logistik_returbeli_dtl';
	var $view_produk_gr_dtl		= 'ck_view_beli_goodsreceive_dtl';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_supplier() {
		return $this->db->query("SELECT
									a.id AS supplier_id,
									a.nama AS nama_supplier
								 FROM
									".$this->tbl_supplier." a
								 GROUP BY
									a.id,
									a.nama
								 ORDER BY
									a.nama");
    }
	
	function get_gr($id_supplier) {
		$result =  $this->db->query("SELECT
										*
									 FROM
										".$this->tbl_gr_hdr."
									 WHERE
										id_supplier = '".$id_supplier."'");
		echo json_encode($result->result());
	}
    
    function detail_list($hid = '') {
        $q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_retur_dtl."
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
									".$this->tbl_retur_hdr." 
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
		
		$pre = "RB"; // RB-YYMM-XXXX
		
		return $pre.
			   "-".
		       substr($tahun, -2).
               substr(("00".$bulan), -2).
               "-".
               $kode;
		
	}
	
	function header_create($data) {		
        $result = $this->db->insert($this->tbl_retur_hdr, $data);
		$this->session->set_userdata('hid', $this->db->insert_id());
        return $result;
	}
    
    function detail_create($data) {        
		$result = $this->db->insert($this->tbl_retur_dtl, $data);
		return $result;
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
									* 
                               FROM
									".$this->view_retur_hdr."
							   WHERE
									id_header = '".$hid."'
							  ");
		return $q;
	}
	
	function get_detail($hid) {
		$q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_retur_dtl." a
							   WHERE
									a.id_header = '".$hid."'");
								
		return $q;
	}
    
    function get_produk_gr($grid) {
        return $this->db->query("SELECT
									id_produk,
									CONCAT_WS(' - ', nama_produk, batch_number, date_format(expired_date, '%d-%m-%Y')) AS nama_produk
                                 FROM
									".$this->view_produk_gr_dtl."
                                 WHERE
									id_header = '".$grid."'
                                 ORDER BY
									CONCAT_WS(' - ', batch_number, expired_date, nama_produk_ori)");
    }
	
	function get_info_produk_gr($gr_id, $produk_id) {
		return $this->db->query("SELECT
									qty_gr,
									batch_number,
									expired_date
								 FROM
									".$this->view_produk_gr_dtl."
								 WHERE
									id_header = '".$gr_id."' AND 
									id_produk = '".$produk_id."'");
    }
    
    function delete_item_detail() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_retur_dtl."
								 WHERE
									id = '".$id_detail."'");
    }
    
    function update_item_detail() {
		$id_detail = $this->input->post('id_detail');
		$qty_retur = $this->input->post('qty_retur');
		$keterangan = $this->input->post('keterangan');
        return $this->db->query("UPDATE
									".$this->tbl_retur_dtl." 
                                 SET
									qty_retur = '".$qty_retur."', 
									keterangan = '".$keterangan."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".date($this->config->item('FORMAT_DATETIME_TO_INSERT'))."'
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function update_header() {
		$id_header = $this->input->post('id_header');
		$alasan_retur = $this->input->post('alasan_retur');
		$keterangan = $this->input->post('keterangan');
        return $this->db->query("UPDATE "
									.$this->tbl_retur_hdr." 
                                 SET
									alasan_retur = '".$alasan_retur."',
									keterangan = '".$keterangan."'
								 WHERE
									id = '".$id_header."'");
    }
	
} 