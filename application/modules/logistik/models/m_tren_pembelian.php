<?php defined('BASEPATH') OR exit('No direct script access allowed');

class m_tren_pembelian extends CI_Model {
	
	var $tbl_po_hdr				= 'ck_tbl_beli_purchaseorder_hdr';
	var $tbl_po_dtl				= 'ck_tbl_beli_purchaseorder_dtl';
	
	var $view_produk_all		= 'ck_view_logistik_produk_all';
	var $view_pembelian			= 'ck_view_logistik_tren_pembelian';

    function __construct() {
        parent::__construct();
    }
	
	function get_data_pembelian() {
		$tahun = $this->input->post('tahun');
		$id_produk = $this->input->post('id_produk');
		
		$tahun_1 = $tahun - 1;
		$tahun_2 = $tahun - 2;
		
		$data = array();
		
		$sql = "SELECT
					SUM(jan) AS jan,
					SUM(feb) AS feb,
					SUM(mar) AS mar,
					SUM(apr) AS apr,
					SUM(mei) AS mei,
					SUM(jun) AS jun,
					SUM(jul) AS jul,
					SUM(agu) AS agu,
					SUM(sep) AS sep,
					SUM(okt) AS okt,
					SUM(nov) AS nov,
					SUM(des) AS des
				FROM
					".$this->view_pembelian."
				WHERE
					tahun IN (".$tahun_2.", ".$tahun_1.", ".$tahun.")";
					
		if ($id_produk == '0') { // per tahun
		
		} else { // per tahun & per produk
			$sql .= " AND id_produk = '".$id_produk."'";
		}
		
		$sql .= "GROUP BY
					tahun
				 ORDER BY
					tahun";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			foreach($query->result() as $r) {
				$data[] = $r;
			}
		}
		
		return $data;
	}
	
	function get_data_produk($tahun) {
		$result =  $this->db->query("SELECT
											b.id_produk,
											c.nama_produk
									 FROM
											".$this->tbl_po_hdr." a
											LEFT OUTER JOIN ".$this->tbl_po_dtl." b ON a.id = b.id_header
											LEFT OUTER JOIN ".$this->view_produk_all." c ON b.id_produk = c.id_produk
									 WHERE
											YEAR(a.tgl_transaksi) = '".$tahun."'
									 GROUP BY
											b.id_produk,
											c.nama_produk
									 ORDER BY
											c.nama_produk");
		echo json_encode($result->result());
	}
}

?>
