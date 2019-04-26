
<?php

	if($tipe=='dn')
	{
		foreach($header->result() as $r)
		{
			echo "<center>Surat Jalan : ".$r->no_sj;
			
		}
		
		echo "<br>	Dengan Item :<br>";
		echo "<table border=1 style=\"width:40%;\">";
		echo "<thead style=\"background:black; color:white;\">";
		echo "
				<tr>
					<td>No.</td>
					<td>Produk</td>
					<td>Kemasan</td>
					<td>Batch</td>
					<td>Expired</td>
					<td>Qty</td>
				</tr>
		";
		echo "</thead>";
		echo "<tbody>";
		$no = 1;
		foreach($detail->result() as $x):
		echo "
				<tr>
					<td>$no</td>
					<td>".$x->nama."</td>
					<td>".$x->kemasan."</td>
					<td>".$x->batch_number."</td>
					<td>".$x->expired_date."</td>
					<td>".$x->jumlah_pesanan."</td>
				</tr>";
				$no++;
		endforeach;
		echo "</tbody>";
		echo "</table>";
		echo "<center><b>Adalah valid dikeluarkan oleh PT. TATA USAHA INDONESIA</b></center>";
	}

?>

