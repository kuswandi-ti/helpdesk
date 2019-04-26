<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Penerimaan_Tagihan extends CI_Model {
	
	var $tbl_pt_hdr				= 'ck_tbl_beli_penerimaantagihan_hdr';
	var $tbl_pt_dtl				= 'ck_tbl_beli_penerimaantagihan_dtl';
	var $tbl_gr_hdr				= 'ck_tbl_beli_goodsreceive_hdr';
	var $tbl_gr_dtl				= 'ck_tbl_beli_goodsreceive_dtl';
	var $tbl_po_hdr				= 'ck_tbl_beli_purchaseorder_hdr';
	var $tbl_po_dtl				= 'ck_tbl_beli_purchaseorder_dtl';
	var $tbl_supplier			= 'ck_supplier';
	
	var $view_pt_hdr			= 'ck_view_beli_penerimaantagihan_hdr';
	var $view_pt_dtl			= 'ck_view_beli_penerimaantagihan_dtl';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_supplier() {
		return $this->db->query("SELECT
									a.id_supplier,
									b.nama AS nama_supplier
								 FROM
									".$this->tbl_gr_hdr." a
									LEFT OUTER JOIN ".$this->tbl_supplier." b ON a.id_supplier = b.id
								 WHERE
									a.id NOT IN (SELECT id_gr FROM ".$this->tbl_pt_dtl.")
								 GROUP BY
									a.id_supplier,
									b.nama
								 ORDER BY
									b.nama");
	}
	
	function create_doc_no($bulan, $tahun) {
		$q = $this->db->query("SELECT
									MAX(RIGHT(no_transaksi, 4)) AS no_transaksi
							   FROM 
									".$this->tbl_pt_hdr." 
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
		
		$pre = "PT"; // PT-YYMM-XXXX
		
		return $pre.
			   "-".
		       substr($tahun, -2).
               substr(("00".$bulan), -2).
               "-".
               $kode;
		
	}
	
	function header_create($data) {		
        $result = $this->db->insert($this->tbl_pt_hdr, $data);
		$this->session->set_userdata('hid', $this->db->insert_id());
        return $result;
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
	
	function get_gr($sid) {
		$q = $this->db->query("SELECT
									a.id AS id_gr,
									a.no_transaksi AS no_gr
							   FROM
									".$this->tbl_gr_hdr." a
							   WHERE
									a.id NOT IN (SELECT id_gr FROM ".$this->tbl_pt_dtl.")
									AND a.id_supplier = '".$sid."'
							   ORDER BY
									a.no_transaksi");								
		return $q;
	}
	
	function get_info_gr($id_gr) {
		return $this->db->query("SELECT
									a.tgl_transaksi AS tgl_gr,
									b.no_transaksi AS no_po,
									SUM(COALESCE(c.qty_gr, 0) * COALESCE(d.harga_satuan, 0)) AS total
								 FROM
									".$this->tbl_gr_hdr." a
									LEFT OUTER JOIN ".$this->tbl_po_hdr." b ON a.id_po = b.id
									LEFT OUTER JOIN ".$this->tbl_gr_dtl." c ON a.id = c.id_header
									LEFT OUTER JOIN ".$this->tbl_po_dtl." d ON b.id = d.id_header
										AND a.id_po = d.id_header
										AND c.id_produk = d.id_produk
								 WHERE
									a.id = '".$id_gr."'");
	}
	
	function detail_create($data) {        
		$result = $this->db->insert($this->tbl_pt_dtl, $data);
		return $result;
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
    
    function delete_item_detail($id) {		
        return $this->db->query("DELETE
		                         FROM
									".$this->tbl_pt_dtl." 
								 WHERE
									id = '".$id."'");
    }
	
	function update_header() {
		$id_header = $this->input->post('id_header');
		$no_faktur_supplier = $this->input->post('no_invoice_supplier');
		$tgl_faktur_supplier = date_format(new DateTime($this->input->post('tgl_invoice_supplier')), $this->config->item('FORMAT_DATE_TO_INSERT'));
		$tgl_jatuh_tempo = date_format(new DateTime($this->input->post('tgl_jatuh_tempo')), $this->config->item('FORMAT_DATE_TO_INSERT'));
		$keterangan = $this->input->post('keterangan');
		$sub_total = $this->input->post('sub_total');
		$disc_persen = $this->input->post('disc_persen');
		$disc_rupiah = $this->input->post('disc_rupiah');
		$dpp = $this->input->post('dpp');
		$ppn_persen = $this->input->post('ppn_persen');
		$ppn_rupiah = $this->input->post('ppn_rupiah');
		$materai = $this->input->post('materai');
		$grand_total = $this->input->post('grand_total');
        return $this->db->query("UPDATE
									".$this->tbl_pt_hdr." 
                                 SET
									no_invoice_supplier = '".$no_faktur_supplier."',
									tgl_invoice_supplier = '".$tgl_faktur_supplier."',
									tgl_jatuh_tempo = '".$tgl_jatuh_tempo."',
									keterangan = '".$keterangan."',
									sub_total = '".$sub_total."',
									disc_persen = '".$disc_persen."',
									disc_rupiah = '".$disc_rupiah."',
									dpp = '".$dpp."',
									ppn_persen = '".$ppn_persen."',
									ppn_rupiah = '".$ppn_rupiah."',
									materai = '".$materai."',
									grand_total = '".$grand_total."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_header."'");
    }
    
    function get_sub_total_detail($id_header, 
								  $disc_persen = 0, 
								  $disc_rp = 0, 
								  $ppn_persen = 0, 
								  $materai = 0) {
        $sub_total = $this->db->query("SELECT
											SUM(total_tagihan) AS sub_total
                                       FROM 
											".$this->tbl_pt_dtl."
                                       WHERE
											id_header = '".$id_header."'")->row()->sub_total;
        if ($disc_persen > 0) {
            $disc_rp_result = ($disc_persen / 100) * $sub_total;
        } else {
            $disc_persen = 0;
            $disc_rp_result = $disc_persen;
        }
        $dpp_result = $sub_total - $disc_rp_result;
        if ($ppn_persen > 0) {
            $ppn_rp_result = ($ppn_persen / 100) * $dpp_result;
        } else {
            $ppn_rp_result = 0;
        }
        $grand_total = $dpp_result + $ppn_rp_result + $materai;
        
        // Update header
        $this->db->query("UPDATE
								".$this->tbl_pt_hdr." 
                          SET
								sub_total = '".$sub_total."',
                                disc_persen = '".$disc_persen."',
                                disc_rupiah = '".$disc_rp_result."',
                                dpp = '".$dpp_result."',
                                ppn_persen = '".$ppn_persen."',
                                ppn_rupiah = '".$ppn_rp_result."',
                                materai = '".$materai."',
                                grand_total = '".$grand_total."'
                          WHERE
								id = '".$id_header."'");
        
        // Output ke controller
        return $this->db->query("SELECT
									*
                                 FROM
									".$this->tbl_pt_hdr."
                                 WHERE
									id = '".$id_header."'");
        
    }
	
} 