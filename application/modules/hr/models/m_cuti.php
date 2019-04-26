<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_cuti extends CI_Model {
	
	var $tbl_cuti_hdr			= 'ck_tbl_hr_cuti_hdr';
	var $tbl_cuti_dtl			= 'ck_tbl_hr_cuti_dtl';
	var $tbl_cuti				= 'ck_cuti';	
	var $tbl_karyawan			= 'ck_karyawan';
	
	var $view_cuti_dtl			= 'ck_view_hr_cuti_dtl';
	
	public function __construct() {
		parent::__construct();
	}
	
	function get_karyawan() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_karyawan."
								 WHERE
									activated = '1'
								 ORDER BY
									nama");
	}
	
	function get_cuti() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->tbl_cuti."
								 ORDER BY
									id");
	}
    
    function detail_list($hid = '') {
        $q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_cuti_dtl."
							   WHERE
									id_header='".$hid."'
							   ORDER BY
									id");								
		return $q;
    }
    
	function create_doc_no($bulan, $tahun) {
		$q = $this->db->query("SELECT
									MAX(RIGHT(no_transaksi, 4)) AS no_transaksi
							   FROM
									".$this->tbl_cuti_hdr." 
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
		
		$pre = "CU"; // PP-YYMM-XXXX
		
		return $pre.
			   "-".
		       substr($tahun, -2).
               substr(("00".$bulan), -2).
               "-".
               $kode;
		
	}
	
	function create_header($data) {		
        $result = $this->db->insert($this->tbl_cuti_hdr, $data);
		$this->session->set_userdata('hid', $this->db->insert_id());
        return $result;
	}
    
    function create_detail($data) {        
		$result = $this->db->insert($this->tbl_cuti_dtl, $data);
		return $result;
	}
	
	function get_header($hid) {
		$q = $this->db->query("	SELECT
									a.id,
									a.no_transaksi,
									a.tgl_transaksi,
									a.id_karyawan,
									b.nik,
									b.nama AS nama_karyawan,
									a.keterangan
								FROM
									".$this->tbl_cuti_hdr." a
									LEFT OUTER JOIN ".$this->tbl_karyawan." b ON a.id_karyawan = b.id
							   WHERE
									a.id = '".$hid."'
							  ");
		return $q;
	}
	
	function get_detail($hid) {
		$q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_cuti_dtl." a
							   WHERE
									a.id_header='".$hid."'");
								
		return $q;
	}
    
    function delete_item_detail() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_cuti_dtl."
								 WHERE
									id = '".$id_detail."'");
    }
    
    function update_item_detail() {
		$id = $this->input->post('id');
		$id_cuti = $this->input->post('id_cuti');
		$tanggal_awal = date_format(new DateTime($this->input->post('tanggal_awal')), $this->config->item('FORMAT_DATE_TO_INSERT'));
		$tanggal_akhir = date_format(new DateTime($this->input->post('tanggal_akhir')), $this->config->item('FORMAT_DATE_TO_INSERT'));
		$keterangan = $this->input->post('keterangan');
        return $this->db->query("UPDATE
									".$this->tbl_cuti_dtl." 
                                 SET
									id_cuti = '".$id_cuti."',
									tanggal_awal = '".$tanggal_awal."',
									tanggal_akhir = '".$tanggal_akhir."',
									keterangan = '".$keterangan."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".date($this->config->item('FORMAT_DATETIME_TO_INSERT'))."'
                                 WHERE
									id = '".$id."'");
    }
	
	function update_header() {
		$hid = $this->input->post('id');
		$keterangan = $this->input->post('keterangan');
        return $this->db->query("UPDATE "
									.$this->tbl_cuti_hdr." 
                                 SET
									keterangan = '".$keterangan."'
								 WHERE
									id = '".$hid."'");
    }
	
} 