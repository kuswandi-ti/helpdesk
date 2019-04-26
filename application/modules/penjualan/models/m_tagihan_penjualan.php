<?php

class m_tagihan_penjualan extends CI_Model {
	
	
	function populatedetail($idpo){
		return $this->db->query("SELECT h.id,h.no_so, h.bukti_gambar, h.no_po, h.no_sj,
								h.id_customer, cust.nama, al.alamat_kirim as alamat,
								h.sales AS idsales, s.nama_sales AS sales, DATE_FORMAT(NOW(), ' %d-%m-%Y') AS sekarang,
								h.bukti_gambar, 
								DATE_FORMAT( h.tanggal_po, '%m-%d-%Y') AS tanggal_po, 
								h.created_by ,SUM(det.`sub_total`) AS subtotal, (SUM(det.`sub_total`)-h.diskon_rupiah)+(SUM(det.`sub_total`)-h.diskon_rupiah)*h.ppn/100 AS total , h.diskon_persen, 
								h.diskon_rupiah, h.ppn, h.tipe_bayar, h.jangka_waktu, h.ppn/100*(SUM(det.`sub_total`)-h.diskon_rupiah) AS ppn_rp
								FROM ck_po_customer_header h 
								LEFT OUTER JOIN ck_customer cust
								ON cust.id  = h.`id_customer`
								LEFT OUTER JOIN ck_sales s
								ON h.sales = s.id
								LEFT OUTER JOIN ck_penjualan_detail det
								ON det.`id_po` = h.`id`
								LEFT OUTER JOIN ck_alamat_customer al on al.id = cust.id_alamat_default
								WHERE h.id='$idpo'
								");
	}
	
	function printBilling($idpo)
	{
		$user = $_SESSION['user_name'];
		return $this->db->query("UPDATE ck_billing_statement SET print_date = NOW(), printed_by = '$user' WHERE id_header='$idpo' ");
	}
	function isiDetailTagihan($idpo)
	{
		
		return $this->db->query("SELECT det.id_detail,det.id_produk, produk.nama, kemasan.nama AS kemasan, 
				det.batch_number, det.expired_date,DATE_FORMAT( det.expired_date, '%d/%m/%Y') AS kadaluarsa, head.status,head.no_faktur,head.tanggal_faktur,
                DAY(det.expired_date) AS tgl_expired,det.acc,det.keterangan, bil.billing_no, bil.created_date,
                MONTHNAME(det.expired_date) AS bln_expired,det.jumlah_diterima,
                YEAR(det.expired_date) AS  tahun_expired,DATE_FORMAT(NOW(), ' %d-%m-%Y') AS NOW,
				det.harga_jual, jumlah_pesanan, SUM(stok.stok) AS qty_akhir, 
				SUM(stok.stok)+det.jumlah_pesanan AS qty_gudang, det.sub_total, det.disc_rp, det.disc_persen, 
				det.back_order, det.jumlah_back_order, det.jumlah_acc as Qacc
				 
                FROM ck_penjualan_detail det
                LEFT OUTER JOIN ck_po_customer_header head
					ON det.id_po = head.id
				LEFT OUTER JOIN ck_billing_statement bil
					ON bil.id_header = head.id
                LEFT OUTER JOIN ck_produk produk
					ON produk.id = det.id_produk
                LEFT OUTER JOIN ck_produk_kemasan kemasan
					ON kemasan.id = det.id_kemasan
				LEFT JOIN ck_tbl_beli_gr_dtl stok
					ON det.id_produk = stok.produk_id
					AND det.batch_number = stok.batch_number
					AND stok.expired_date = det.expired_date
                WHERE det.id_po='$idpo' and acc='1'
                GROUP BY stok.batch_number, stok.expired_date, det.`id_produk`");
		
		/* return $this->db->query("SELECT pro.`nama`, kem.`nama` as kemasan,
		det.`batch_number`, DATE_FORMAT(det.`expired_date`,'%d-%m-%Y') as expired_date, det.`jumlah_pesanan`,
		det.harga_jual, det.disc_persen, det.sub_total,
		det.expired_date + h.jangka_waktu AS jatuh_tempo,  
		cust.alamat, h.no_faktur, h.tanggal_faktur, det.acc, det.keterangan, det.jumlah_acc
							FROM ck_po_customer_detail det
							left outer join ck_po_customer_header h
								on h.id = det.id_po
							LEFT OUTER JOIN ck_produk pro
								ON pro.id = det.`id_produk`
							LEFT OUTER JOIN ck_produk_kemasan kem
								ON kem.`id` = det.`id_kemasan`
							LEFT OUTER JOIN ck_customer cust 
								ON cust.id = h.id_customer

							where id_po='$idpo' and acc='1' and jumlah_acc>0"); */
	}
	function getInputDetail($idpo)
    {
				return $this->db->query("
                SELECT det.id,det.id_produk, produk.nama, kemasan.nama AS kemasan, 
				det.batch_number, det.expired_date,DATE_FORMAT( det.expired_date, '%d/%m/%Y') AS kadaluarsa, head.status,
                DAY(det.expired_date) AS tgl_expired,det.acc,det.keterangan,
                MONTHNAME(det.expired_date) AS bln_expired,
                YEAR(det.expired_date) AS  tahun_expired,
				 det.harga_jual, jumlah_pesanan, SUM(stok.stok) AS qty_akhir, 
				 SUM(stok.stok)+det.jumlah_pesanan AS qty_gudang, det.sub_total, det.disc_rp, det.disc_persen, 
				 det.back_order, det.jumlah_back_order, det.jumlah_acc as Qacc
				 
                FROM ck_po_customer_detail det
                LEFT OUTER JOIN ck_po_customer_header head
					ON det.id_po = head.id
                LEFT OUTER JOIN ck_produk produk
					ON produk.id = det.id_produk
                LEFT OUTER JOIN ck_produk_kemasan kemasan
					ON kemasan.id = det.id_kemasan
		LEFT JOIN ck_tbl_beli_gr_dtl stok
			ON det.id_produk = stok.produk_id
			AND det.batch_number = stok.batch_number
			AND stok.expired_date = det.expired_date
                WHERE det.id_po='$idpo' and acc='1'
                GROUP BY stok.batch_number, stok.expired_date, det.`id_produk`
                				
                ");
    }
	
	function approve_billing()
	{
		$id_header = $this->input->get('id_header');
		$user = $_SESSION['user_name'];
		$appr = $this->db->query("UPDATE ck_billing_statement SET approved = '1', approved_by='$user', approved_date=NOW() WHERE id_header='$id_header'");
		if($appr)
			echo "done";
		else echo "fail";
	}
	
}