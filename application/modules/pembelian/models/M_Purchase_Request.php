<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Purchase_Request extends CI_Model {
    
    var $tbl_pr_hdr   			= 'ck_tbl_beli_purchaserequest_hdr';
	var $tbl_pr_dtl   			= 'ck_tbl_beli_purchaserequest_dtl';	
	var $tbl_produk				= 'ck_produk';
	var $tbl_produk_kemasan		= 'ck_produk_kemasan';
	var $tbl_supplier 			= 'ck_supplier';
	
	var $view_pr_hdr   			= 'ck_view_beli_purchaserequest_hdr';    
	var $view_pr_dtl   			= 'ck_view_beli_purchaserequest_dtl';    
	var $view_produk_aktif		= 'ck_view_logistik_produk_aktif';   
	var $view_stok_akhir		= 'ck_view_logistik_stok_akhir_2';
	
	public function __construct() {
		parent::__construct();
	}
	
	function create_doc_no($bulan, $tahun) {
		$q = $this->db->query("SELECT
									MAX(RIGHT(no_transaksi, 4)) AS no_transaksi
							   FROM
									".$this->tbl_pr_hdr." 
                               WHERE
									month(tgl_transaksi) = '".$bulan."'
                                    AND year(tgl_transaksi) = '".$tahun."'");
		$kode = '';
		if($q->num_rows() > 0) {
			foreach($q->result() as $kode) {
				$kode = ((int)$kode->no_transaksi) + 1;
				$kode = sprintf('%04s', $kode);
			}
		} else {
			$kode = '0001';
		}
		
		$pre = "PR"; // PR-YYMM-XXXX
		
		return $pre.
			   "-".
		       substr($tahun, -2).
               substr(("00".$bulan), -2).
               "-".
               $kode;
		
	}
	
	function header_create($data) {		
        $result = $this->db->insert($this->tbl_pr_hdr, $data);
		$this->session->set_userdata('hid', $this->db->insert_id());
        return $result;
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
									*, date_format(tgl_transaksi, '%d/%m/%Y') AS tanggal_transaksi 
                               FROM
									".$this->view_pr_hdr." 
                               WHERE
									id_header = '".$hid."'");
		return $q;
	}
	
	function detail_list($hid) {
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
	
	function get_detail($hid) {
		$q = $this->db->query("SELECT
									*, COALESCE(stok, 0) AS stok
							   FROM
									".$this->view_pr_dtl." 
									LEFT OUTER JOIN ".$this->view_stok_akhir." ON ".$this->view_pr_dtl.".id_produk = ".$this->view_stok_akhir.".id_produk
							   WHERE
									id_header = '".$hid."'");								
		return $q;
	}
	
	function get_status_pr($hid) {
		return $this->db->query("SELECT
									status_pr 
		                         FROM
									".$this->tbl_pr_hdr." 
								 WHERE
									id = '".$hid."'")->row()->status_pr;
	}
    
    function detail_create($data) {        
		$result = $this->db->insert($this->tbl_pr_dtl, $data);
		return $result;
	}
	
	function get_produk_aktif() {
        $query = "SELECT
						*
				  FROM
						".$this->view_produk_aktif."
				  ORDER BY
						nama_produk";
        return $this->db->query($query)->result();
    }
	
	function get_qty_stok_akhir() {
		$id_produk = $this->input->post('id_produk');
        $query = "SELECT
						stok_akhir
				  FROM
						".$this->view_stok_akhir."
				  WHERE
						id_produk = '".$id_produk."'";
        echo $this->db->query($query)->row()->stok;
    }
    
    function delete_item_detail() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_pr_dtl." 
                                 WHERE
									id = '".$id_detail."'");
    }
    
    function update_item_detail() {
		$id_detail = $this->input->post('id_detail');
		$tgl_diperlukan = date_format(new DateTime($this->input->post('tgl_diperlukan')), $this->config->item('FORMAT_DATE_TO_INSERT'));
		$qty_pr = $this->input->post('qty_pr');
        return $this->db->query("UPDATE
									".$this->tbl_pr_dtl." 
                                 SET
									tgl_diperlukan = '".$tgl_diperlukan."', 
									qty_pr = '".$qty_pr."',
									qty_approve = '".$qty_pr."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function update_header() {
		$id_header = $this->input->post('id_header');
		$deskripsi = $this->input->post('deskripsi');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
        return $this->db->query("UPDATE
									".$this->tbl_pr_hdr." 
                                 SET
									bulan = '".$bulan."', 
									tahun = '".$tahun."', 
									deskripsi = '".$deskripsi."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_header."'");
    }
	
	function approve_pr($id_header) {
        return $this->db->query("UPDATE
									".$this->tbl_pr_hdr." 
                                 SET
									status_pr = '2', 
									approved_by = '".$this->session->userdata('user_name')."',
									approved_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_header."'");
    }
	
} 