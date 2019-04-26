<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Goods_Receive extends CI_Model {
	
	var $tbl_gr_hdr  		= 'ck_tbl_beli_goodsreceive_hdr';
	var $tbl_gr_dtl  		= 'ck_tbl_beli_goodsreceive_dtl';
	var $tbl_po_dtl			= 'ck_tbl_beli_purchaseorder_dtl';
	var $tbl_po_hdr   		= 'ck_tbl_beli_purchaseorder_hdr';
	var $tbl_supplier    	= 'ck_supplier';
	var $tbl_produk       	= 'ck_produk';
	var $tbl_produk_kemasan	= 'ck_produk_kemasan';
	var $tbl_produk_lokasi	= 'ck_produk_lokasi';
	var $tbl_lokasi			= 'ck_lokasi';
	
	var $view_gr_hdr		= 'ck_view_beli_goodsreceive_hdr';
	var $view_gr_dtl		= 'ck_view_beli_goodsreceive_dtl';
	var $view_os_po			= 'ck_view_beli_os_qty_purchaseorder_vs_goodsreceive_2';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_supplier() {
		return $this->db->query("SELECT
									id_supplier,
									nama_supplier 
		                         FROM
									".$this->view_os_po."
								 WHERE
									qty_os_gr > 0
								 GROUP BY
									id_supplier,
									nama_supplier
								 ORDER BY
									nama_supplier");
	}
	
	function create_doc_no($bulan, $tahun) {
		$q = $this->db->query("SELECT
									MAX(RIGHT(no_transaksi, 4)) AS no_transaksi
							   FROM
									".$this->tbl_gr_hdr." 
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
		
		$pre = "GR"; // GR-YYMM-XXXX
		
		return $pre.
			   "-".
		       substr($tahun, -2).
               substr(("00".$bulan), -2).
               "-".
               $kode;
		
	}
	
	function header_create($data) {		
        $result = $this->db->insert($this->tbl_gr_hdr, $data);
		$this->session->set_userdata('hid', $this->db->insert_id());
        return $result;
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
									* 
                               FROM
									".$this->view_gr_hdr."
							   WHERE
									id_header = '".$hid."'
							  ");
		return $q;
	}
	
	function get_detail($hid) {
		$q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_gr_dtl." 
							   WHERE
									id_header = '".$hid."'	
						       ORDER BY
									id_detail");								
		return $q;
	}
	
	function detail_create($data) {        
		$result = $this->db->insert($this->tbl_gr_dtl, $data);
		return $result;
	}
	
	function get_po($sid) {
        $query = "SELECT
						id_po,
						no_po 
				  FROM
						".$this->view_os_po."
				  WHERE
						id_supplier = '".$sid."' 
						AND qty_os_gr > 0
				  GROUP BY
						id_po,
						no_po
				  ORDER BY
						no_po";
        return $this->db->query($query);
    }
    
    function detail_list($hid) {
		return $this->db->query("SELECT
									*
							     FROM
									".$this->view_gr_dtl." 
							     WHERE
									id_header = '".$hid."'	
						         ORDER BY
									id_detail");
    }
    
    function delete_item_detail() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_gr_dtl." 
								 WHERE
									id = '".$id_detail."'");
    }
    
    function update_item_detail() {
		$id_detail = $this->input->post('id_detail');
		$id_po = $this->input->post('id_po');
		$id_produk = $this->input->post('id_produk');
		$batch_number = $this->input->post('batch_number');
		$expired_date = date_format(new DateTime($this->input->post('expired_date')), $this->config->item('FORMAT_DATE_TO_INSERT'));
		$qty_gr = $this->input->post('qty_gr');
		$qty_gr_hidden = $this->input->post('qty_gr_hidden');
		$id_lokasi = $this->input->post('id_lokasi');
        return $this->db->query("UPDATE
									".$this->tbl_gr_dtl." 
                                 SET
									batch_number = '".$batch_number."', 
									expired_date = '".$expired_date."',
									qty_gr = '".$qty_gr."',
									id_lokasi = '".$id_lokasi."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function update_header() {
		$id_header = $this->input->post('id_header');
		$no_sj_supplier = $this->input->post('no_sj_supplier');
		$tgl_terima = $this->input->post('tgl_terima');
		$keterangan = $this->input->post('keterangan');
        return $this->db->query("UPDATE
									".$this->tbl_gr_hdr." 
                                 SET
									no_sj_supplier ='".$no_sj_supplier."',
									tgl_terima = '".date_format(new DateTime($tgl_terima), $this->config->item('FORMAT_DATE_TO_INSERT'))."',
									keterangan = '".$keterangan."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_header."'");
    }
	
	function get_lokasi($id_produk) {
		$result =  $this->db->query("SELECT
										 a.id_lokasi,
										 CONCAT(b.kode, ' ', b.deskripsi) AS kode_lokasi
									 FROM
										 ".$this->tbl_produk_lokasi." a
										 LEFT OUTER JOIN ".$this->tbl_lokasi." b ON a.id_lokasi = b.id
									 WHERE
										 a.id_produk = '".$id_produk."'
									 GROUP BY
										 a.id_lokasi,
										 b.kode
									 ORDER BY
										 b.kode");
		echo json_encode($result->result());
	}
	
	function get_data_po() {
		$id_header = $this->input->post('id_header');
		$id_po = $this->input->post('id_po');
		
		//$this->db->query("DELETE FROM ".$this->tbl_gr_dtl." WHERE header_id = '".$hid."'");		
		return $this->db->query("INSERT INTO ".$this->tbl_gr_dtl." (id_header, id_produk, 
									 batch_number, qty_gr, created_by, created_date, modified_by, modified_date) 
								 SELECT '".$id_header."', id_produk, '', qty_os_gr, 
									 '".$this->session->userdata('user_name')."', '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."', 
									 '".$this->session->userdata('user_name')."', '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
								 FROM ".$this->view_os_po."
								 WHERE
									 id_po = '".$id_po."'
									 AND qty_os_gr > 0 
									 AND status_po = '3'");
	}
	
} 