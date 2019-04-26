<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_pengawasan_pemeliharaan extends CI_Model {
	
	var $tbl_pp_hdr				= 'ck_tbl_logistik_pengawasanpemeliharaan_hdr';
	var $tbl_pp_dtl				= 'ck_tbl_logistik_pengawasanpemeliharaan_dtl';
	
	public function __construct() {
		parent::__construct();
	}
    
    function detail_list($hid = '') {
        $q = $this->db->query("SELECT
									*
							   FROM
									".$this->tbl_pp_dtl."
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
									".$this->tbl_pp_hdr." 
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
		
		$pre = "PP"; // PP-YYMM-XXXX
		
		return $pre.
			   "-".
		       substr($tahun, -2).
               substr(("00".$bulan), -2).
               "-".
               $kode;
		
	}
	
	function create_header($data) {		
        $result = $this->db->insert($this->tbl_pp_hdr, $data);
		$this->session->set_userdata('hid', $this->db->insert_id());
        return $result;
	}
    
    function create_detail($data) {        
		$result = $this->db->insert($this->tbl_pp_dtl, $data);
		return $result;
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT * 
                               FROM
									".$this->tbl_pp_hdr."
							   WHERE
									id='".$hid."'
							  ");
		return $q;
	}
	
	function get_detail($hid) {
		$q = $this->db->query("SELECT
									*
							   FROM
									".$this->tbl_pp_dtl." a
							   WHERE
									a.id_header='".$hid."'");
								
		return $q;
	}
    
    function delete_item_detail() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE FROM ".$this->tbl_pp_dtl." WHERE id='".$id_detail."'");
    }
    
    function update_item_detail() {
		$id = $this->input->post('id');
		$uraian = $this->input->post('uraian');
		$tindak_lanjut = $this->input->post('tindak_lanjut');
		$status = $this->input->post('status');
        return $this->db->query("UPDATE
									".$this->tbl_pp_dtl." 
                                 SET
									uraian = '".$uraian."',
									tindak_lanjut = '".$tindak_lanjut."',
									status = '".$status."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".date($this->config->item('FORMAT_DATETIME_TO_INSERT'))."'
                                 WHERE
									id = '".$id."'");
    }
	
	function update_header() {
		$hid = $this->input->post('id');
		$pic = $this->input->post('pic');
		$keterangan = $this->input->post('keterangan');
        return $this->db->query("UPDATE "
									.$this->tbl_pp_hdr." 
                                 SET
									pic = '".$pic."',
									keterangan = '".$keterangan."'
								 WHERE id = '".$hid."'");
    }
	
} 