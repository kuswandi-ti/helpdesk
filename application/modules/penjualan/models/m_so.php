<?php

class m_so extends CI_Model {
	
	function populatedetail($idpo){
		return $this->db->query("SELECT h.id,h.no_so, h.bukti_gambar, h.no_po, h.no_sj,
								h.id_customer, cust.nama, al.alamat_kirim as alamat,
								h.sales AS idsales, s.nama_sales AS sales, date_format(now(), ' %d-%m-%Y') as now,
								h.bukti_gambar, 
                                DATE_FORMAT( h.tanggal_po, '%m-%d-%Y') as tanggal_po, 
								h.created_by , h.subtotal, h.total, h.diskon_persen, 
								h.diskon_rupiah, h.ppn, h.tipe_bayar, h.jangka_waktu, h.ppn/100*(h.subtotal-h.diskon_rupiah) as ppn_rp
								FROM ck_po_customer_header h 
								LEFT OUTER JOIN ck_customer cust
								ON cust.id  = h.`id_customer`
								LEFT OUTER JOIN ck_sales s
								ON h.sales = s.id
								LEFT OUTER JOIN ck_alamat_customer al on al.id = cust.id_alamat_default
								WHERE h.id='$idpo' ");
	}
	
	function isiDetail($idpo) //Cetak SJ
	{
		
		//////////////////////////////////////////////////////////////
		//buat nomer sj  hanya jika belum ada nomernya ////
		//////////////////////////////////////////////////////////////
		
		$getSJ = $this->db->query("SELECT no_faktur, no_sj, tanggal_sj FROM ck_po_customer_header where id=$idpo");
		foreach($getSJ->result() as $x) 
		{
			if($x->tanggal_sj=='') // kalau belum tercetak
			{
				///////////////////////////////////
				// buat tanggal sj .//
				///////////////////////////////////
				
				$this->db->query("UPDATE ck_po_customer_header set tanggal_sj = NOW() where id='$idpo'");
			}	
				
				///////////////////////////////////
				//generate kode surat jalan ///////
				///////////////////////////////////
			$no_sj = "DN-";
			$getDate = $this->db->query("SELECT date_format(tanggal_sj,'%d/%m/%Y') as tanggal from ck_po_customer_header where id='$idpo'");
				foreach($getDate->result() as $r):
					$no_sj.=$r->tanggal.'/';
				endforeach;
					$no_sj.=$idpo;
			$set_sj = $this->db->query("UPDATE ck_po_customer_header set no_sj = '$no_sj' where id='$idpo'");
			
		}
		
		return $this->db->query("SELECT pro.`nama`, kem.`nama` as kemasan,
		det.`batch_number`, DATE_FORMAT(det.`expired_date`,'%d-%m-%Y') as expired_date, det.`jumlah_pesanan`,
		det.harga_jual, det.disc_persen, det.sub_total, det.expired_date + h.jangka_waktu AS jatuh_tempo,  al.alamat_kirim as alamat, h.no_faktur, h.tanggal_faktur, det.acc, det.keterangan, det.jumlah_acc
							FROM ck_po_customer_detail det
							left outer join ck_po_customer_header h
								on h.id = det.id_po
							LEFT OUTER JOIN ck_produk pro
								ON pro.id = det.`id_produk`
							LEFT OUTER JOIN ck_produk_kemasan kem
								ON kem.`id` = det.`id_kemasan`
							LEFT OUTER JOIN ck_customer cust 
								ON cust.id = h.id_customer
							LEFT OUTER JOIN ck_alamat_customer al on al.id = cust.id_alamat_default
							where id_po='$idpo' and acc='1'");
	}
	
	function isiDetailFaktur($idpo)
	{
		
		//////////////////////////////////////////////////////////////
		//buat nomer  faktur hanya jika belum ada nomernya ////
		//////////////////////////////////////////////////////////////
		
		$getSJFAK = $this->db->query("SELECT no_faktur, no_sj FROM ck_po_customer_header where id=$idpo");
		foreach($getSJFAK->result() as $x) 
		{
			if($x->no_sj=='') // kalau belum tercetak surat jalan , karena faktur berbasis tanggal dari surat jalan
			{
				///////////////////////////////////
				// buat tanggal faktur .//
				///////////////////////////////////
				
				$this->db->query("UPDATE ck_po_customer_header set tanggal_sj = now() where id='$idpo'");
				$this->db->query("UPDATE ck_po_customer_header set tanggal_faktur = DATE(tanggal_sj) where id='$idpo'");
			}
			else $this->db->query("UPDATE ck_po_customer_header set tanggal_faktur = DATE(tanggal_sj) where id='$idpo'");
				////////////////////////	
				//generate kode faktur//
				////////////////////////	
					$no_inv = "INV-";
					$getDate = $this->db->query("SELECT date_format(tanggal_sj,'%d/%m/%Y') as tanggal from ck_po_customer_header where id='$idpo'");
						foreach($getDate->result() as $r):
							$no_inv.=$r->tanggal.'/';
						endforeach;
							$no_inv.=$idpo;
					$set_inv = $this->db->query("UPDATE ck_po_customer_header set no_faktur = '$no_inv' where id='$idpo'");
					
			
		}
		
		return $this->db->query("SELECT pro.`nama`, kem.`nama` as kemasan,
		det.`batch_number`, DATE_FORMAT(det.`expired_date`,'%d-%m-%Y') as expired_date, det.`jumlah_pesanan`,
		det.harga_jual, det.disc_persen, det.sub_total, det.expired_date + h.jangka_waktu AS jatuh_tempo,  al.alamat_kirim as alamat, h.no_faktur, h.tanggal_faktur, det.acc, det.keterangan, det.jumlah_acc
							FROM ck_po_customer_detail det
							left outer join ck_po_customer_header h
								on h.id = det.id_po
							LEFT OUTER JOIN ck_produk pro
								ON pro.id = det.`id_produk`
							LEFT OUTER JOIN ck_produk_kemasan kem
								ON kem.`id` = det.`id_kemasan`
							LEFT OUTER JOIN ck_customer cust 
								ON cust.id = h.id_customer
							LEFT OUTER JOIN ck_alamat_customer al on al.id = cust.id_alamat_default
							where id_po='$idpo' and acc='1' and jumlah_acc>0");
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
}