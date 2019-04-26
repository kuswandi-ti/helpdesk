<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_forecast_pembelian extends CI_Model {
    
    var $tbl_fb_hdr				= 'ck_tbl_logistik_forecastbeli_hdr';
	var $tbl_fb_dtl				= 'ck_tbl_logistik_forecastbeli_dtl';
	
	var $view_produk_aktif		= 'ck_view_logistik_produk_aktif';
	var $view_fb_dtl			= 'ck_view_logistik_forecastbeli_dtl';
	
	public function __construct() {
		parent::__construct();
	}
    
    function detail_list($hid = '') {
        $q = $this->db->query("SELECT
									*
							   FROM
									".$this->view_fb_dtl."
							   WHERE
									id_header = '".$hid."'
							   ORDER BY
									id_detail");
		return $q;
    }
    
	function create_doc_no($bulan, $tahun) {
		$q = $this->db->query("SELECT
								 MAX(RIGHT(no_transaksi, 4)) AS no_transaksi
							   FROM 
								 ".$this->tbl_fb_hdr." 
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
		
		$pre = "FB"; // FB-YYMM-XXXX
		
		return $pre.
			   "-".
		       substr($tahun, -2).
               substr(("00".$bulan), -2).
               "-".
               $kode;
		
	}
	
	function create_header($data) {		
        $result = $this->db->insert($this->tbl_fb_hdr, $data);
		$this->session->set_userdata('hid', $this->db->insert_id());
        return $result;
	}
    
    function create_detail($data) {        
		$result = $this->db->insert($this->tbl_fb_dtl, $data);
		return $result;
	}
	
	function get_header($hid) {
		$q = $this->db->query("SELECT
								 * 
                               FROM
								 ".$this->tbl_fb_hdr."
							   WHERE
								 id = '".$hid."'
							  ");
		return $q;
	}
	
	function get_produk_aktif() {
		return $this->db->query("SELECT
									*
								 FROM
									".$this->view_produk_aktif."
								 ORDER BY
									nama_produk");
    }
    
    function delete_item_detail() {
		$id_detail = $this->input->post('id_detail');
        return $this->db->query("DELETE
								 FROM
									".$this->tbl_fb_dtl."
								 WHERE
									id = '".$id_detail."'");
    }
    
    function update_item_detail() {
		$id_detail = $this->input->post('id_detail');
		$bulan_01 = $this->input->post('bulan_01');
		$bulan_02 = $this->input->post('bulan_02');
		$bulan_03 = $this->input->post('bulan_03');
		$bulan_04 = $this->input->post('bulan_04');
		$bulan_05 = $this->input->post('bulan_05');
		$bulan_06 = $this->input->post('bulan_06');
		$bulan_07 = $this->input->post('bulan_07');
		$bulan_08 = $this->input->post('bulan_08');
		$bulan_09 = $this->input->post('bulan_09');
		$bulan_10 = $this->input->post('bulan_10');
		$bulan_11 = $this->input->post('bulan_11');
		$bulan_12 = $this->input->post('bulan_12');
		$keterangan = $this->input->post('keterangan');
        return $this->db->query("UPDATE
									".$this->tbl_fb_dtl." 
                                 SET
									bulan_01 = '".$bulan_01."',
                                    bulan_02 = '".$bulan_02."',
                                    bulan_03 = '".$bulan_03."',
                                    bulan_04 = '".$bulan_04."',
                                    bulan_05 = '".$bulan_05."',
                                    bulan_06 = '".$bulan_06."',
                                    bulan_07 = '".$bulan_07."',
                                    bulan_08 = '".$bulan_08."',
                                    bulan_09 = '".$bulan_09."',
                                    bulan_10 = '".$bulan_10."',
                                    bulan_11 = '".$bulan_11."',
                                    bulan_12 = '".$bulan_12."',
                                    keterangan = '".$keterangan."',
                                    modified_by = '".$this->session->userdata('user_name')."',
                                    modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_detail."'");
    }
	
	function update_header() {
		$id_header = $this->input->post('id_header');
		$keterangan = $this->input->post('keterangan');
        return $this->db->query("UPDATE
									".$this->tbl_fb_hdr." 
                                 SET
									keterangan = '".$keterangan."',
									modified_by = '".$this->session->userdata('user_name')."',
									modified_date = '".$this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')."'
                                 WHERE
									id = '".$id_header."'");
    }
	
} 