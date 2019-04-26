<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_stock_opname extends CI_Model {
    
    var $tbl_stockopname_hdr   	= 'ck_tbl_logistik_stockopname_hdr';
	var $tbl_stockopname_dtl  	= 'ck_tbl_logistik_stockopname_dtl';
	var $tbl_lokasi				= 'ck_lokasi';
	var $tbl_stok				= 'ck_stok';
	
	var $view_stockopname_hdr  	= 'ck_view_logistik_stockopname_hdr';
	var $view_stockopname_dtl   = 'ck_view_logistik_stockopname_dtl';
	var $view_produk_lokasi		= 'ck_view_logistik_produk_lokasi';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_lokasi() {
		$query = "SELECT
					 *
				  FROM
					 ".$this->tbl_lokasi."
				  ORDER BY
				     kode";
        return $this->db->query($query);
	}
	
	function create_doc_no($bulan, $tahun) {
		$q = $this->db->query("SELECT
									MAX(RIGHT(no_transaksi, 4)) AS no_transaksi
							   FROM
									".$this->tbl_stockopname_hdr." 
                               WHERE
									MONTH(tgl_transaksi) = '".$bulan."' 
                                    AND YEAR(tgl_transaksi) = '".$tahun."'");
		$kode = '';
		if($q->num_rows() > 0) {
			foreach($q->result() as $kode) {
				$kode = ((int)$kode->no_transaksi) + 1;
				$kode = sprintf('%04s', $kode);
			}
		} else {
			$kode = '0001';
		}
		
		$pre = "OP"; // OP-YYMM-XXXX
		
		return $pre.
			   "-".
		       substr($tahun, -2).
               substr(("00".$bulan), -2).
               "-".
               $kode;
		
	}
	
	function header_create($data) {		
        $result = $this->db->insert($this->tbl_stockopname_hdr, $data);
		$this->session->set_userdata('hid', $this->db->insert_id());
        return $result;
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
									*,
									date_format(tgl_transaksi, '%d/%m/%Y') AS tanggal_transaksi 
                               FROM
									".$this->view_stockopname_hdr." 
                               WHERE
									id_header = '".$hid."'");
		return $q;
	}
	
	function get_batch_number($id_lokasi, $id_produk) {
		$result =  $this->db->query("SELECT
										batch_number,
										expired_date,
										CONCAT(batch_number, ' - ', date_format(expired_date, '%d-%m-%Y')) AS batch_number_text
									 FROM
										".$this->tbl_stok."
									 WHERE
										id_produk = '".$id_produk."'
										AND id_lokasi = '".$id_lokasi."'
									 GROUP BY
										batch_number,
										expired_date
									 ORDER BY
										batch_number,
										expired_date");
		echo json_encode($result->result());
	}
	
	function get_produk_lokasi() {
		$id_lokasi = $this->input->post('id_lokasi');
        $query = "SELECT
						*
				  FROM
						".$this->view_produk_lokasi."
				  WHERE
						id_lokasi = '".$id_lokasi."'
				  ORDER BY
						kode_lokasi";
        return $this->db->query($query)->result();
    }
	
	function detail_list($hid) {
		$q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_stockopname_dtl." 
							   WHERE
									id_header = '".$hid."'
							   ORDER BY
									id_detail");
		return $q;
	}
    
    function create_detail($data) {        
		$result = $this->db->insert($this->tbl_stockopname_dtl, $data);
		return $result;
	}
    
    function delete_item_detail() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_stockopname_dtl." 
                                 WHERE
									id = '".$id_detail."'");
    }
    
    function update_item_detail() {
		$id_detail = $this->input->post('id_detail');
		$id_produk = $this->input->post('id_produk');
		$batch_number = $this->input->post('batch_number');
		$expired_date = date_format(new DateTime($this->input->post('expired_date')), $this->config->item('FORMAT_DATE_TO_INSERT'));
		$qty_aktual = $this->input->post('qty_aktual');
		$qty_selisih = $this->input->post('qty_selisih');
		$keterangan = $this->input->post('keterangan');
		$rekomendasi = $this->input->post('rekomendasi');
        return $this->db->query("UPDATE
									".$this->tbl_stockopname_dtl." 
                                 SET
									id_produk = '".$id_produk."',
									batch_number = '".$batch_number."',
									expired_date = '".$expired_date."',
									qty_fisik = '".$qty_aktual."',
									qty_selisih = '".$qty_selisih."',
									keterangan = '".$keterangan."',
									rekomendasi = '".$rekomendasi."',
                                    modified_by = '".$this->session->userdata('user_name')."',
                                    modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function update_header() {
		$id_header = $this->input->post('id_header');
		$deskripsi = $this->input->post('deskripsi');
        return $this->db->query("UPDATE
									".$this->tbl_stockopname_hdr." 
                                 SET
									deskripsi = '".$deskripsi."',
                                    modified_by = '".$this->session->userdata('user_name')."',
                                    modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_header."'");
    }
	
}