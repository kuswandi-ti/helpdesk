<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Payment_Ap extends CI_Model {
	
	var $tbl_pay_hdr			= 'ck_tbl_beli_payment_hdr';
	var $tbl_pay_dtl			= 'ck_tbl_beli_payment_dtl';
	var $tbl_cara_bayar			= 'ck_cara_bayar';
	
	var $view_pay_hdr			= 'ck_view_beli_payment_hdr';
	var $view_pay_dtl			= 'ck_view_beli_payment_dtl';
	var $view_os_pay			= 'ck_view_beli_os_invoice_vs_payment_2';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_supplier() {
		return $this->db->query("SELECT
									a.id_supplier,
									a.nama_supplier
								 FROM
									".$this->view_os_pay." a
								 WHERE
									a.os_bayar > 0
								 GROUP BY
									a.id_supplier,
									a.nama_supplier								 
								 ORDER BY
									a.nama_supplier");
	}
	
	function get_cara_bayar() {
		return $this->db->query("SELECT
									a.id,
									a.nama
								 FROM
									".$this->tbl_cara_bayar." a
								 ORDER BY
									a.nama");
	}
	
	function create_doc_no($bulan, $tahun) {
		$q = $this->db->query("SELECT
									MAX(RIGHT(no_transaksi, 4)) AS no_transaksi
							   FROM 
									".$this->tbl_pay_hdr." 
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
		
		$pre = "PY"; // PY-YYMM-XXXX
		
		return $pre.
			   "-".
		       substr($tahun, -2).
               substr(("00".$bulan), -2).
               "-".
               $kode;
		
	}
	
	function header_create($data) {		
        $result = $this->db->insert($this->tbl_pay_hdr, $data);
		$this->session->set_userdata('hid', $this->db->insert_id());
        return $result;
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
									* 
                               FROM
									".$this->view_pay_hdr."
							   WHERE
									id_header = '".$hid."'
							  ");
		return $q;
	}
	
	function get_detail($hid) {
		$q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_pay_dtl." 
							   WHERE
									id_header = '".$hid."'	
						       ORDER BY
									id_detail");								
		return $q;
	}
	
	function get_noinvoice($sid) {
		$q = $this->db->query("SELECT
									a.no_invoice_supplier
							   FROM
									".$this->view_os_pay." a
							   WHERE
									a.id_supplier = '".$sid."'
									AND a.os_bayar > 0
							   GROUP BY
									a.no_invoice_supplier
							   ORDER BY
									a.no_invoice_supplier");								
		return $q;
	}
	
	function get_info_invoice($no_invoice) {
		return $this->db->query("SELECT
									a.tgl_invoice_supplier,
									SUM(a.total_tagihan) AS total_tagihan,
									SUM(a.os_bayar) AS os_bayar
								 FROM
									".$this->view_os_pay." a								 
								 WHERE
									a.no_invoice_supplier = '".$no_invoice."'
								 GROUP BY
									a.no_invoice_supplier");
	}
	
	function detail_create($data) {        
		$result = $this->db->insert($this->tbl_pay_dtl, $data);
		return $result;
	}
    
    function detail_list($hid) {
		return $this->db->query("SELECT
									*
							     FROM
									".$this->view_pay_dtl." 
							     WHERE
									id_header = '".$hid."'	
						         ORDER BY
									id_detail");
    }
    
    function delete_item_detail($id_detail) {		
        return $this->db->query("DELETE
		                         FROM
									".$this->tbl_pay_dtl." 
								 WHERE
									id = '".$id_detail."'");
    }
	
	function update_header() {
		$id_header = $this->input->post('id_header');
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
									".$this->tbl_pay_hdr." 
                                 SET
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
									modified_date = '".date('Y-m-d H:i:s')."'
                                 WHERE
									id = '".$id_header."'");
    }
    
    function get_sub_total_detail($id_header, 
								  $disc_persen = 0, 
								  $disc_rp = 0, 
								  $ppn_persen = 0, 
								  $materai = 0) {
        $sub_total = $this->db->query("SELECT
											SUM(total_bayar) AS sub_total
                                       FROM 
											".$this->tbl_pay_dtl."
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
								".$this->tbl_pay_hdr." 
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
									".$this->tbl_pay_hdr."
                                 WHERE
									id = '".$id_header."'");
        
    }
	
} 