<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_produk_min_over extends CI_Model {
	
	var $tbl_stok			= 'ck_stok';
	var $view_stok			= 'ck_view_logistik_stok_akhir_1';
	var $view_stok_detail	= 'ck_view_logistik_stok_detail';
	var $view_all_produk	= 'ck_view_logistik_produk_all';
	
	function get_stok_produk($id_produk) {
		$sql = "SELECT
					a.id_produk,
					c.nama_produk,
					c.nama_kemasan,
					c.nama_kadar_satuan AS nama_satuan,
					a.expired_date,
					a.batch_number,
					c.nama_principal,
					b.qty_akhir AS stok,
					c.min_stok,
					c.max_stok
				FROM
					".$this->view_stok." a
					LEFT OUTER JOIN ".$this->tbl_stok." b ON a.date_time = b.date_time
						AND a.id_produk = b.id_produk
						AND a.expired_date = b.expired_date
						AND a.batch_number = b.batch_number
					LEFT OUTER JOIN ".$this->view_all_produk." c ON a.id_produk = c.id_produk
				WHERE
					a.id_produk = '".$id_produk."'
				ORDER BY
					c.nama_produk,
					a.expired_date,
					a.batch_number";
		return $this->db->query($sql);
	}
	
	function detail_stok($id_produk,
	                     $batch_number,
						 $expired_date) {
		$sql = "SELECT
					*
				FROM
					".$this->view_stok_detail."
				WHERE
					id_produk = '".$id_produk."'
					AND batch_number = '".$batch_number."'
					AND expired_date = '".$expired_date."'
				ORDER BY date_time ASC";
		return $this->db->query($sql);
	}
}