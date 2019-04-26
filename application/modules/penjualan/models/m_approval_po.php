<?php

class m_approval_po extends CI_Model {
	
	function insertKeterangan($data)
	{
		$status = $data['res'];
		$id_po = $data['id_po'];
		$approvedby = $data['approvedby'];
		$keterangan = "<table border=0>
						<tr> 
							<td style=\"width:30%\"><b>Status</b> </td>
							<td style=\"width:13%\" > : </td>
							<td>".$data['res']."</td>
						</tr>";
		$keterangan .= "<tr> 
							<td><b>Oleh</b> </td>
							<td> : </td>
							<td>".$approvedby."</td>
						</tr>";
		$keterangan .= "<tr> 
							<td><b>Tanggal</b> </td>
							<td> : </td>
							<td>".$data['tanggal']."</td>
						</tr>";
		$keterangan .= "<tr> 
							<td><b>Jam</b> </td>
							<td> : </td>
							<td>".$data['jam']."</td>
						</tr>";
		$keterangan .= "<tr> 
							<td><b>Pesan</b> </td>
							<td> : </td>
							<td>".$data['msg']."</td>
						</tr>
						</table><br>";
		//echo $keterangan;	
		if($status == 'approved')
		{
			$insert = $this->db->query("UPDATE ck_po_customer_header set keterangan = CONCAT(keterangan,'".$keterangan."'),
			status = '$status', approved_by = '$approvedby', approved_date = date(NOW()) WHERE id='$id_po'");
		}
		
		else $insert = $this->db->query("UPDATE ck_po_customer_header set keterangan = CONCAT(keterangan,'".$keterangan."'), status = '$status' WHERE id='$id_po'");
		
		if($status=='rejected')
		{
			$reject = $this->db->query("UPDATE ck_po_customer_detail SET acc='0', keterangan='rejected PO' where id_po = '$id_po'");
		}
		return $insert;
	}
	function getTotal($id_po)
	{
		return $this->db->query("SELECT sum(sub_total) as total from ck_po_customer_detail where id_po='$id_po'");
	}
		
	function getHistori($id_po)
	{
		return $this->db->query("SELECT keterangan from ck_po_customer_header WHERE id='$id_po'");
	}
	function getInputDetail($idpo)
    {
				return $this->db->query("
                SELECT a.*,b.qty_akhir FROM (SELECT det.id,det.id_produk, produk.nama, kemasan.nama AS kemasan, det.batch_number, det.expired_date,DATE_FORMAT( det.expired_date, '%d/%m/%Y') AS kadaluarsa, head.status,
				DAY(det.expired_date) AS tgl_expired,
				MONTHNAME(det.expired_date) AS bln_expired,
				YEAR(det.expired_date) AS  tahun_expired, det.keterangan, det.acc AS acc,
				 det.harga_jual, det.jumlah_pesanan,
				 det.sub_total, det.disc_rp, det.disc_persen, det.back_order, det.jumlah_back_order,
						 det.jumlah_acc AS Qacc
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
    }
	
	function updateStok($idpo)
	{
		$batch_number;
		$id_produk;
		$expired_date;
		$jumlah_pesanan;
		$detail_po;
		$header_po;
		$select = $this->db->query("SELECT * FROM ck_po_customer_detail WHERE id_po = '$idpo' and acc='1'"); //potong yg di acc ajaa
		foreach($select->result() as $data)
		{
			$batch_number = $data->batch_number;
			$id_produk = $data->id_produk;
			$expired_date = $data->expired_date;
			$jumlah_pesanan = $data->jumlah_acc;
			$header_po = $data->id_po;
			$detail_po = $data->id;
			/* 
			
			for($pesanan_terambil=1;$pesanan_terambil<=$jumlah_pesanan;$pesanan_terambil++) //loop potong sebanyak jumlah pesanan
			{
				
				//Dapatkan list row yang akan dipotong stoknya
				$getStok = $this->db->query("	SELECT * FROM ck_tbl_beli_goodsreceive_dtl 
												WHERE stok>0 
												AND batch_number='$batch_number'
												AND expired_date = '$expired_date'
												AND produk_id = '$id_produk'
												ORDER by id ASC 
												LIMIT 1
											");
											
				//Ketemu lalu potong stoknya
				foreach($getStok->result() as $r)
					$id = $r->id;
				
				$potongStok = $this->db->query("	UPDATE ck_tbl_beli_gr_dtl
														SET stok = stok-1
														WHERE id='$id'
														order by id desc
												   ");
				
			} */
			$qtyAwal;
			$qtyAkhir;
			//Get Qty Akhir untuk Qty Awal di ck_stok
			$getqty = $this->db->query("SELECT qty_akhir
										FROM ck_stok 
										WHERE id_produk = '$id_produk' AND batch_number='$batch_number' AND expired_date='$expired_date'
										ORDER BY id DESC LIMIT 1");
			foreach($getqty->result() as $r)
				$qtyAwal = $r->qty_akhir;
			
			//getSum untuk qty_akhir
			 $getsum = $this->db->query("SELECT SUM(stok_akhir) as qty_akhir 
										 FROM ck_view_logistik_stok_akhir_2
										 WHERE id_produk = '$id_produk' AND batch_number='$batch_number' AND expired_date='$expired_date'
										 ");
			 foreach($getsum->result() as $rs)
				 $qtyAkhir = $rs->qty_akhir;
			//$qtyAkhir =	$qtyAwal - $pesanan_terambil;	
			//insert ke kartu stok
			$qtyAkhir = $qtyAwal - $jumlah_pesanan;
			$insertStok = $this->db->query("INSERT into ck_stok(date_time,id_produk,expired_date,batch_number,qty_awal,qty_masuk,qty_keluar,status,qty_akhir,status_keterangan,id_header,id_detail)
											VALUES(now(),'$id_produk','$expired_date','$batch_number','$qtyAwal','0','$jumlah_pesanan','7','$qtyAkhir','Pengeluaran Penjualan',
											'$header_po','$detail_po')
											");
											
			//Generate nomer SalesOrder
			//format SO + id customer + no_PO
			$noSO ="SO-"; 
			$getDate = $this->db->query("SELECT date_format(NOW(),'%d/%m/%Y') as tanggal");
			foreach($getDate->result() as $r):
				$noSO.=$r->tanggal.'/';
			endforeach;
				$noSO.=$idpo;
			$setSO = $this->db->query("UPDATE ck_po_customer_header set no_so = '$noSO' where id='$idpo'");
		}
	}
	
	function setAcc()
	{
		$id = $this->input->post('id');
		$id_po = $this->input->post('id_po');
		$jumlah_acc = $this->input->post('jumlah_acc');
		$keterangan = $this->input->post('keterangan');

		if($this->input->post('acc')=='1') //set flag 1
		{
			if($keterangan == '')
				$this->db->query("UPDATE ck_po_customer_detail SET acc='1', jumlah_acc='$jumlah_acc', keterangan = NULL WHERE id='$id'");
			else $this->db->query("UPDATE ck_po_customer_detail SET acc='1', jumlah_acc='$jumlah_acc', keterangan = '$keterangan' WHERE id='$id'");
			echo "UPDATE ck_po_customer_detail SET acc='1', keterangan = NULL WHERE id='$id'";
		}
		else {
			$this->db->query("UPDATE ck_po_customer_detail SET acc='0', jumlah_acc='0', keterangan = '$keterangan' WHERE id='$id'");
			echo "UPDATE ck_po_customer_detail SET acc='0', jumlah_acc='0', keterangan = '$keterangan' WHERE id='$id'";
		}
		return $this->setTotalHeader($id_po);
	}
	function setQtyAcc()
	{
		$id = $this->input->post('id_detail');
		$idpo = $this->input->post('idpo');
		$qty = $this->input->post('qty');
		$stok = $this->input->post('stok');
		
		//disable back_order, nulling jml back_order
		$exe = $this->db->query("UPDATe ck_po_customer_detail SET back_order='0', jumlah_back_order = '0', jumlah_acc='$qty' where id = '$id'");
		if($exe)
		{
			//update subtotal di detail, qty acc * harga_jual 
			$hrgjual_exe = $this->db->query("UPDATE ck_po_customer_detail SET sub_total = (harga_jual*$qty)-disc_rp WHERE id='$id' and acc='1'");
			
			if($hrgjual_exe)
			{
				//update total di header
				$total_exe = $this->db->query("UPDATE ck_po_customer_header SET subtotal=(SELECT SUM(sub_total) FROM ck_po_customer_detail WHERE id_po='$idpo' and acc='1') where id='$idpo'");
				$then = $this->db->query("UPDATE ck_po_customer_header set total = subtotal-diskon_rupiah+((subtotal-diskon_rupiah)*ppn/100) where id='$idpo'");
				if($then)
					echo "done";
			}
		}
		
		else echo "failed";
	}
	function getTotalRow($id)
	{
		$total =$this->db->query("select id_produk as total from ck_po_customer_detail where id_po='$id'");
		echo $total->num_rows();
	}
	
	function setTotalHeader($idpo)
	{
			$q = $this->db->query("UPDATE ck_po_customer_header SET subtotal=(SELECT SUM(sub_total) FROM ck_po_customer_detail WHERE id_po='$idpo' and acc='1') where id='$idpo'");
			$then = $this->db->query("UPDATE ck_po_customer_header set total = subtotal+(subtotal*ppn/100)-diskon_rupiah where id='$idpo'");
			return $q;
	}
    
	
}