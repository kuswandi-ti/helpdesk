<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_cetak_stok_opname extends CI_Model {
	
	var $tbl_produk_lokasi		= 'ck_produk_lokasi';
	var $tbl_lokasi				= 'ck_lokasi';
	
	var $view_produk_all		= 'ck_view_logistik_produk_all';
	var $view_stok_akhir		= 'ck_view_logistik_stok_akhir';
	var $view_stok_by_lokasi	= 'ck_view_logistik_stok_akhir_by_lokasi_2';

    function __construct() {
        parent::__construct();
    }
	
	function get_data($lorong, $rak, $baris, $kolom) {		
		if ($lorong != '0' AND ($rak == '0' AND $baris == '0' AND $kolom == '0')) {
			$sWhere = " WHERE a.lorong = '".$lorong."'
						AND COALESCE(a.stok_data, 0) > 0";
		} elseif ($lorong != '0' AND $rak != '0' AND ($baris == '0' AND $kolom == '0')) {
			$sWhere = " WHERE a.lorong = '".$lorong."' 
						AND a.rak = '".$rak."'
						AND COALESCE(a.stok_data, 0) > 0";
		} elseif ($lorong != '0' AND $rak != '0' AND $rak != '0' AND ($kolom == '0')) {
			$sWhere = " WHERE a.lorong = '".$lorong."' 
						AND a.rak = '".$rak."' 
						AND a.baris = '".$baris."'
						AND COALESCE(a.stok_data, 0) > 0";
		} elseif ($lorong != '0' AND $rak != '0' AND $rak != '0' AND $kolom != '0') {
			$sWhere = " WHERE a.lorong = '".$lorong."'
						AND a.rak = '".$rak."' 
						AND a.baris = '".$baris."' 
						AND a.kolom = '".$kolom."'
						AND COALESCE(a.stok_data, 0) > 0";
		} else {
			$sWhere = '';
		}
		
		//print_r($sWhere);
		
		/*$q = $this->db->query("SELECT
									a.id_produk,
									b.nama_produk,
									b.nama_produk_ori,
									b.nama_kemasan,
									COALESCE(c.stok, 0) AS stok_data,
									0 AS stok_aktual,
									0 AS selisih,
									'' AS keterangan,
									'' AS rekomendasi,
									d.lorong,
									d.rak,
									d.baris,
									d.kolom
							   FROM
									".$this->tbl_produk_lokasi." a
									LEFT OUTER JOIN ".$this->view_produk_all." b ON a.id_produk = b.produk_id
									LEFT OUTER JOIN ".$this->view_stok_akhir." c ON a.id_produk = c.id_produk
									LEFT OUTER JOIN ".$this->tbl_lokasi." d ON a.id_lokasi = d.id
							   ".$sWhere."
							   ORDER BY
								    b.nama_produk_ori");*/
		$query = " SELECT
						a.id_produk,
						a.nama_produk,
						a.nama_produk_ori,
						a.batch_number,
						a.expired_date,
						a.nama_kemasan,
						a.kode_lokasi,
						COALESCE(a.stok_data, 0) AS stok_data,
						0 AS stok_aktual,
						0 AS selisih,
						'' AS keterangan,
						'' AS rekomendasi,
						a.lorong,
						a.rak,
						a.baris,
						a.kolom
				   FROM
						".$this->view_stok_by_lokasi." a
				   ".$sWhere."
				   ORDER BY
						a.kode_lokasi,
						a.nama_produk_ori,
						a.batch_number,
						a.expired_date";
		return $this->db->query($query);		
	}
	
	function get_lorong() {
        $query = "SELECT
					  DISTINCT
					  lorong
				  FROM
					  ".$this->tbl_lokasi."
				  ORDER BY
					  lorong";
        return $this->db->query($query);
    }
	
	function get_rak() {
        $query = "SELECT
					  DISTINCT
					  rak
				  FROM
					  ".$this->tbl_lokasi."
				  ORDER BY
					  rak";
        return $this->db->query($query);
    }
	
	function get_baris() {
        $query = "SELECT
					  DISTINCT
					  baris
				  FROM
					  ".$this->tbl_lokasi."
				  ORDER BY
					  baris";
        return $this->db->query($query);
    }
	
	function get_kolom() {
        $query = "SELECT
					  DISTINCT
					  kolom
				  FROM
					  ".$this->tbl_lokasi."
				  ORDER BY
					  kolom";
        return $this->db->query($query);
    }
}
?>
