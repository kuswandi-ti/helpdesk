<?php 

class m_picking_list extends CI_Model {

	function populateSO(){
		return $this->db->query("SELECT * FROM ck_po_customer_header h WHERE h.status='approved' AND picked=0");
	}
	
	function populateHeader($id)
	{
		return $this->db->query("SELECT * FROM ck_po_customer_header WHERE id='$id'");
	}
	
	function populateCetak($id)
	{
		return $this->db->query("SELECT h.id, d.id_produk, d.batch_number, DATE_FORMAT(d.expired_date, '%d-%m-%Y') as expired_date, 
		d.jumlah_pesanan, p.nama AS namaproduk , h.`no_so`, k.`nama` as namakemasan,  cu.nama AS namacust, h.no_po,
		SUM(stok.stok)+d.jumlah_pesanan AS qty_akhir
		FROM ck_po_customer_detail d 
		LEFT OUTER JOIN ck_produk p 
			ON d.id_produk = p.id 
		LEFT OUTER JOIN ck_po_customer_header h
			ON d.`id_po` = h.id
		LEFT OUTER JOIN ck_produk_kemasan k
			ON d.`id_kemasan` = k.`id`
		LEFT OUTER JOIN ck_customer cu
			ON cu.id = h.`id_customer`
		LEFT JOIN ck_tbl_beli_gr_dtl stok
			ON d.id_produk = stok.produk_id
			AND d.batch_number = stok.batch_number
			AND stok.expired_date = d.expired_date
		WHERE d.id_po='$id' and d.acc='1'");
	}
	
	function getTotalRow($id)
	{
		return $this->db->query("select id_produk as total from ck_po_customer_detail d where id_po='$id'  and d.acc='1'");
	}
	
	function donepicking($id)
	{
		$user = $_SESSION['user_name'];
		
		return $this->db->query("update ck_po_customer_header set picked='1', picked_by='$user', picked_date=NOW() where id='$id'");
	}
	
	function getInputDetail($idpo)
    {
				return $this->db->query(" SELECT a.*,b.qty_akhir,a.jumlah_acc+b.qty_akhir AS qty_gudang FROM (SELECT det.id,det.id_produk, produk.nama, kemasan.nama AS kemasan, det.batch_number, det.expired_date,DATE_FORMAT( det.expired_date, '%d/%m/%Y') AS kadaluarsa, head.status,
				DAY(det.expired_date) AS tgl_expired,
				MONTHNAME(det.expired_date) AS bln_expired,
				YEAR(det.expired_date) AS  tahun_expired, det.keterangan, det.acc AS acc,
				 det.harga_jual, det.jumlah_pesanan,
				 det.sub_total, det.disc_rp, det.disc_persen, det.back_order, det.jumlah_back_order,
						 det.jumlah_acc
				FROM ck_po_customer_detail det

				LEFT OUTER JOIN ck_produk produk
				ON produk.id = det.id_produk

				LEFT OUTER JOIN ck_po_customer_header head
				ON det.id_po = head.id

				LEFT OUTER JOIN ck_produk_kemasan kemasan
				ON kemasan.id = det.id_kemasan
				WHERE det.id_po='$idpo' ) AS a
				LEFT OUTER JOIN 
				(SELECT t.id_produk AS produk_id,  t.qty_akhir , t.batch_number, t.expired_date
								FROM (SELECT * FROM ck_stok  ORDER BY id DESC) AS t
								LEFT OUTER JOIN ck_produk b
										ON t.id_produk = b.id
								
								GROUP BY t.id_produk, t.batch_number, b.kemasan_default, t.expired_date HAVING t.qty_akhir > 0) AS b
				ON a.id_produk = b.produk_id
				AND a.batch_number = b.batch_number
				AND a.expired_date = b.expired_date
");
				//return $this->db->query("
                // SELECT det.id,det.id_produk, produk.nama, kemasan.nama AS kemasan, 
				// det.batch_number, det.expired_date,DATE_FORMAT( det.expired_date, '%d/%m/%Y') AS kadaluarsa, head.status,
                // DAY(det.expired_date) AS tgl_expired,det.acc,det.keterangan,
                // MONTHNAME(det.expired_date) AS bln_expired,
                // YEAR(det.expired_date) AS  tahun_expired,
				 // det.harga_jual, jumlah_pesanan, SUM(stok.stok) AS qty_akhir, SUM(stok.stok)+det.jumlah_acc AS qty_gudang, det.sub_total, det.disc_rp, det.disc_persen,
				 // det.jumlah_acc 
				 
                // FROM ck_po_customer_detail det
                // LEFT OUTER JOIN ck_po_customer_header head
					// ON det.id_po = head.id
                // LEFT OUTER JOIN ck_produk produk
					// ON produk.id = det.id_produk
                // LEFT OUTER JOIN ck_produk_kemasan kemasan
					// ON kemasan.id = det.id_kemasan
		// LEFT JOIN ck_tbl_beli_gr_dtl stok
			// ON det.id_produk = stok.produk_id
			// AND det.batch_number = stok.batch_number
			// AND stok.expired_date = det.expired_date
                // WHERE det.id_po='$idpo' and det.acc='1'
                // GROUP BY stok.batch_number, stok.expired_date, det.`id_produk`
                				
               // ");
    }
}
