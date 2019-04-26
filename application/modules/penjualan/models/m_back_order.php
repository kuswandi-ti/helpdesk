<?php

class m_back_order extends CI_Model {

	function getInputDetail($idpo)
	{
		return $this->db->query("
				SELECT det.id,det.id_produk, produk.nama, kemasan.nama AS kemasan, det.batch_number, det.expired_date,DATE_FORMAT( det.expired_date, '%d/%m/%Y') AS kadaluarsa, head.status,
				DAY(det.expired_date) AS tgl_expired,
				MONTHNAME(det.expired_date) AS bln_expired,
				YEAR(det.expired_date) AS  tahun_expired, det.keterangan, det.acc,
				 det.harga_jual, det.jumlah_pesanan, det.harga_jual*jumlah_back_order AS sub_total, det.disc_rp, det.disc_persen, det.back_order, det.jumlah_back_order,
				 det.jumlah_acc AS acc, SUM(stok.stok) AS qty_akhir
				FROM ck_po_customer_detail det
				LEFT OUTER JOIN ck_po_customer_header head
				ON det.id_po = head.id
				LEFT OUTER JOIN ck_produk produk
				ON produk.id = det.id_produk
				LEFT OUTER JOIN ck_produk_kemasan kemasan
				ON kemasan.id = det.id_kemasan
				LEFT JOIN ck_tbl_beli_gr_dtl stok
				ON det.id_produk = stok.produk_id
				WHERE back_order = '1' AND det.id_po='$idpo' AND det.acc = '1'  AND stok.stok >= det.jumlah_back_order
				 GROUP BY stok.batch_number, stok.expired_date, det.id_produk 
				");
	}

	function populatePO($idheader)
	{
		return $this->db->query("SELECT h.id, h.no_po, h.id_customer, cust.nama, cust.alamat,h.sales as idsales, s.nama_sales as sales ,h.bukti_gambar, 
									DATE_FORMAT( h.tanggal_po, '%m/%d/%Y'), h.created_by , h.subtotal, h.total, h.diskon_persen, 
									h.diskon_rupiah, h.ppn, h.tipe_bayar, h.jangka_waktu
									FROM ck_po_customer_header h 
									LEFT OUTER JOIN ck_customer cust
									ON cust.id  = h.`id_customer`
									left outer join ck_sales s
									on h.sales = s.id
									where h.id='$idheader'");
				
	}
	function getBOnominal($id_po)
	{
	   return $this->db->query("SELECT sum(sub_total) as subtotal  from ck_po_customer_detail where id_po='$id_po' and back_order = 1");
	}
	function processBO()
	{
		$id_po = $this->input->post('id_po');
		$id_detail;
		
		//ubah status bo jadi 2. 2 = bo kelar, 1 = bo belom beres, 0 = gaada bo.
		//dari list
		$list = $this->getInputDetail($id_po);
		//get id detail dr hasil $list.
		foreach($list->result() as $x)
		{
			$id_detail = $x->id;
			$changeStatusBackOrder = $this->db->query("update ck_po_customer_detail set back_order = '2' where id='$id_detail'");
			
		}
		
		//echo json_encode($list->result());
		
		
	}
	function delDetail($id, $id_po)
    {
		$x = $this->db->query("update ck_po_customer_detail set where id='$id' AND id_po='$id_po'  AND det.acc = '1'  and stok.stok >= det.jumlah_back_order");
        return  $this->setTotalHeader($id_po);
    }
}