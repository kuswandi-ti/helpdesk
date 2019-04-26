<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ($source == 'PO') {
	foreach($header->result() as $h) {
		echo "<center>Purchase Order : ".$h->po_no;
	}
	
	echo "<br>Dengan Item :<br>";
	echo "<table border=1 style=\"width:40%;\">";
	echo "<thead style=\"background:black; color:white;\">";
	echo "
			<tr>
				<td>No.</td>
				<td>Produk</td>
				<td>Kemasan</td>
				<td>Qty</td>
				<td>Harga</td>
				<td>Disc.</td>
				<td>Total</td>
			</tr>
	";
	echo "</thead>";
	echo "<tbody>";
	$no = 1;
	foreach($detail->result() as $d):
	echo "
			<tr>
				<td>$no</td>
				<td>".$d->nama_produk_ori."</td>
				<td>".$d->nama_kemasan."</td>
				<td>".number_format($d->qty_po)."</td>
				<td>".number_format($d->harga_satuan)."</td>
				<td>".number_format($d->disc_rupiah)."</td>
				<td>".number_format($d->netto)."</td>
			</tr>";
			$no++;
	endforeach;
	echo "</tbody>";
	echo "</table>";
	echo "<center><b>Adalah valid dikeluarkan oleh PT. TATA USAHA INDONESIA</b></center>";
} elseif ($source == 'RB') {
	foreach($header->result() as $h) {
		echo "<center>Retur Pembelian : ".$h->no_transaksi;
	}
	
	echo "<br>Dengan Item :<br>";
	echo "<table border=1 style=\"width:40%;\">";
	echo "<thead style=\"background:black; color:white;\">";
	echo "
			<tr>
				<td>No.</td>
				<td>Produk</td>
				<td>Kemasan</td>
				<td>Batch Number</td>
				<td>Expired Date</td>
				<td>Qty</td>
				<td>Alasan Retur</td>
			</tr>
	";
	echo "</thead>";
	echo "<tbody>";
	$no = 1;
	foreach($detail->result() as $d):
	echo "
			<tr>
				<td>$no</td>
				<td>".$d->nama_produk_ori."</td>
				<td>".$d->nama_kemasan."</td>
				<td>".$d->batch_number."</td>
				<td>".date_format(new DateTime($d->expired_date) ,$this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>
				<td>".number_format($d->qty_retur)."</td>
				<td>".$d->alasan_retur."</td>
			</tr>";
			$no++;
	endforeach;
	echo "</tbody>";
	echo "</table>";
	echo "<center><b>Adalah valid dikeluarkan oleh PT. TATA USAHA INDONESIA</b></center>";
}

