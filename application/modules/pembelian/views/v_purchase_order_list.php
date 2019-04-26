<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
    
    $id_pr = '';
    $no_pr = '';
    $id_transaksi = '';
    $no_transaksi = ''; 
	$tgl_transaksi = '';
	$bulan = '';
	$tahun = '';
	$id_supplier = '';
    $nama_supplier = '';	
    $alamat_pengiriman = '';
    $tipe_pembayaran = '';
	$top = '0';
    $tgl_pengiriman = '';
	$keterangan = ''; 
    $status_po = '';
	$total_barang = '0';
	$disc_persen = '0';
	$disc_rupiah = '0';
	$dpp = '0';
	$ppn_persen = '0';
	$ppn_rupiah = '0';
	$materai = '0';
	$grand_total = '0';

    if ($get_header->num_rows() > 0) {
        foreach($get_header->result() as $row) {
            $id_pr = $row->id_pr;
            $no_pr = $row->no_pr;
            $id_transaksi = $row->id_po;
            $no_transaksi = $row->no_po; 
            $tgl_transaksi = $row->tgl_po;
			$bulan = $row->bulan;
			$tahun = $row->tahun;
			$id_supplier = $row->id_supplier;
            $nama_supplier = $row->nama_supplier;
            $alamat_pengiriman = $row->alamat_pengiriman;
            $tipe_pembayaran = $row->nama_tipe_pembayaran;
			$top = $row->top;
            $tgl_pengiriman = $row->tgl_pengiriman;
            $keterangan = $row->keterangan; 
            $status_po = $row->status_po;
			$total_barang = $row->total_barang;
			$disc_persen = $row->disc_persen;
			$disc_rupiah = $row->disc_rupiah;
			$dpp = $row->dpp;
			$ppn_persen = $row->ppn_persen;
			$ppn_rupiah = $row->ppn_rupiah;
			$materai = $row->materai;
			$grand_total = $row->grand_total;
        } 
    }
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Data Header</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-2">
				<label>No. PO</label>
				<div class="input-group">
					<input id="txtnotransaksi_hdr" name="txtnotransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo $no_transaksi; ?>" readonly>
					<input id="txtidtransaksi_hdr" name="txtidtransaksi_hdr" type="hidden" class="form-control" value="<?php echo $id_transaksi; ?>" readonly>
				</div>
			</div>
			<div class="col-md-2">
				<label>Tgl. PO</label>
				<div class="input-group">
					<input id="txttgltransaksi_hdr" name="txttgltransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo date_format(new DateTime($tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>" readonly>
				</div>
			</div>        
			<div class="col-md-8">
				<label>Supplier</label>
				<div class="input-group">
					<input id="txtnamasupplier_hdr" name="txtnamasupplier_hdr" type="text" class="form-control input-sm" value="<?php echo $nama_supplier; ?>" readonly>
					<input id="txtsupplierid_hdr" name="txtsupplierid_hdr" type="hidden" class="form-control input-sm" value="<?php echo $id_supplier; ?>" readonly>
				</div>
			</div>
		</div>

		<div class="row">          
			<div class="col-md-3">
				<label>Alamat Pengiriman</label>
				<div class="input-group group-alamatpengiriman">
					<textarea id="txtalamatpengiriman_hdr" name="txtalamatpengiriman_hdr" class="form-control" rows="4"><?php echo $alamat_pengiriman; ?></textarea>
				</div>
			</div>
			<div class="col-md-3">
				<div class="row">
					<div class="col-md-12">
						<label>Keterangan</label>
						<div class="input-group">
							<textarea id="txtketerangan_hdr" name="txtketerangan_hdr" class="form-control" rows="4"><?php echo $keterangan; ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="row">			
					<div class="col-md-6">
						<label>Tipe Pembayaran</label>
						<div class="input-group group-tipepembayaran">
							<select name='cbotipepembayaran_hdr' id='cbotipepembayaran_hdr' class='form-control input-sm' style="width: 100%; height: 30px;">
								<?php
									if ($get_tipe_pembayaran->num_rows() > 0) {
										foreach ($get_tipe_pembayaran->result() as $row) {
											$string = "";
											if ($row->tipe_pembayaran == $tipe_pembayaran) {
												$string = "selected";
											}
											echo "<option value='".$row->id."' $string>".$row->tipe_pembayaran."</option>";
										}
									}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<label>T.O.P. (hari)</label>
						<div class="input-group group-top">
							<input id="txttop_hdr" name="txttop_hdr" type="text" class="form-control input-sm" value="<?php echo $top; ?>" onkeypress="return hanya_angka(event)">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>Tgl. Pengiriman</label>
						<div class="input-group group-tglpengiriman">
							<input id="txttglpengiriman_hdr" name="txttglpengiriman_hdr" type="text" class="form-control input-sm" value="<?php echo date_format(new DateTime($tgl_pengiriman), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="row">
					<div class="col-md-12">
						<label>Bulan</label>
						<div class="input-group">
							<select name="cbobulan_header" id="cbobulan_header" style="width: 180px; height: 30px;">
								<?php
									for ($i=1; $i<=12; $i+=1) {
										$sel = $i == $bulan ? 'selected="selected"' : '';
										echo "<option value=$i $sel>".set_month_to_string_ind($i)."</option>";
									}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label>Tahun</label>
						<div class="input-group">
							<select name="cbotahun_header" id="cbotahun_header" style="width: 180px; height: 30px;">
								<?php
									for($i=2020; $i>=2017; $i-=1) {
										$sel = $i == $tahun ? ' selected="selected"' : '';
										echo"<option value=$i $sel> $i </option>";
									}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div> 
		<div class="row">
			<div class="col-md-2">
				<?php
					echo "&nbsp;&nbsp;";
					if ($status_po == 1 or $status_po == 4) {                    
						echo '<button class="btn btn-block btn-success btn-icon-fixed pull-right" id="simpan_header" type="button"><span class="icon-floppy-disk"></span>Simpan</button>';
					}
				?>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Data Detail</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?php 
					$sid = $_GET['sid'];
					$sql1 = "SELECT
								id_pr,
								no_pr
							 FROM
								ck_view_beli_os_qty_purchaserequest_vs_purchaseorder_2
							 WHERE
								qty_os_pr > 0
							 GROUP BY
								id_pr,
								no_pr
							 ORDER BY
								no_pr";
					$res1 = $this->db->query($sql1);
					if ($res1->num_rows() > 0) {
						foreach($res1->result() as $row1) {
							echo "<table id='table-list' class='table' style='width: 100%'>";
								echo "<thead>";
									echo "<tr>";
										echo "<th><h3>".$row1->no_pr."</h3></th>";
									echo "</tr>";
								echo "</thead>";
								echo "<tbody>";
									$sql2 = "SELECT
												 a.*
											 FROM
												 ck_view_beli_os_qty_purchaserequest_vs_purchaseorder_2 a
												 LEFT OUTER JOIN ck_produk_supplier b ON a.id_produk = b.id_produk
											 WHERE
												 b.id_supplier = '".$sid."'
												 AND id_pr = '".$row1->id_pr."'
												 AND qty_os_pr > 0
											 ORDER BY
												 a.nama_produk";
									$res2 = $this->db->query($sql2);
									if ($res2->num_rows() > 0) {
										$i = 1;
										echo "<tr bgcolor='#ccffdc'>";
											echo "<td style='display: none'>Produk ID</td>";
											echo "<td style='width: 37%; text-align: left'><b>Nama Produk</b></td>";
											echo "<td style='width: 5%; text-align: center'><b>Kemasan</b></td>";
											echo "<td style='width: 10%; text-align: right'><b>Qty PO</b></td>";
											echo "<td style='width: 12%; text-align: right'><b>Harga Satuan</b></td>";
											echo "<td style='width: 7%; text-align: center'><b>Disc %</b></td>";
											echo "<td style='width: 10%; text-align: right'><b>Disc Rp.</b></td>";
											echo "<td style='width: 13%; text-align: right'><b>Netto</b></td>";
											echo "<td style='width: 2%; text-align: center'>&nbsp;</td>";
										echo "</tr>";
										foreach($res2->result() as $row2) {
											echo "<tr>";
												echo "<td style='display: none'>
															<input type='hidden' id='txtprodukid' value=".$row2->id_produk." />
															<input type='hidden' id='txtprid' value=".$row1->id_pr." />
															<input type='hidden' name='txtdetailid' id='txtdetailid' value='' />
													  </td>";
												echo "<td style='width: 37%; text-align: left'>".$row2->nama_produk."</td>";
												echo "<td style='width: 5%; text-align: center'>".$row2->nama_kemasan."</td>";
												echo "<td style='width: 10%; text-align: right'>
															<input type='text' id='txtqtypo' value='".$row2->qty_os_pr."' class='form-control input-sm' style='text-align: right' onkeypress='return hanya_angka(event)' />
													  </td>";
												echo "<td style='width: 12%; text-align: right'>
															<input type='text' id='txthargasatuan' value=".number_format($row2->harga_beli)." class='form-control input-sm' style='text-align: right' onkeypress='return hanya_angka(event)' readonly />
													  </td>";
												echo "<td style='width: 7%; text-align: center'>
															<input type='text' id='txtdiscpersen' value='0.0' class='form-control input-sm' style='text-align: right' onkeypress='return hanya_angka(event)' />
													  </td>";
												echo "<td style='width: 10%; text-align: right'>
															<input type='text' id='txtdiscrupiah' value='0' class='form-control input-sm' style='text-align: right' onkeypress='return hanya_angka(event)' />
													  </td>";
												echo "<td style='width: 13%; text-align: right'>
															<input type='text' id='txtnetto' value=".number_format($row2->harga_beli * $row2->qty_pr)." class='form-control input-sm' style='text-align: right' readonly />
													  </td>";
												echo "<td style='width: 2%; text-align: center'>
															<input type='checkbox' name='selected_id' id='checkbox' class='app-checkbox' value=".$row1->id_pr." />
													  </td>";
											echo "</tr>";
											$i++;
										}
									}							
								echo "</tbody>";
							echo "</table>";
						}
					}
				?>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Summary</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<form class="form-horizontal">
					<div class="row margin-bottom-5">
						<div class="form-group">
							<div class="col-md-7">
								<label class="control-label pull-right">Total Barang : </label>			
							</div>
							<div class="col-md-5">
								<div class="input-group">
									<span class="input-group-addon">Rp.</span>
									<input id="txttotalbarang_hdr" name="txttotalbarang_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($total_barang); ?>" readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="row margin-bottom-5">
						<div class="form-group">
							<div class="col-md-7">
								<label class="control-label pull-right">Disc. : </label>			
							</div>
							<div class="col-md-2">
								<div class="input-group">						
									<input id="txtdiscpersen_hdr" name="txtdiscpersen_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($disc_persen, 1); ?>" onkeypress="return hanya_angka(event)">
									<span class="input-group-addon">%</span>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group">	
									<span class="input-group-addon">Rp.</span>
									<input id="txtdiscrupiah_hdr" name="txtdiscrupiah_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($disc_rupiah); ?>" onkeypress="return hanya_angka(event)">						
								</div>
							</div>
						</div>
					</div>
					<div class="row margin-bottom-5">
						<div class="form-group">
							<div class="col-md-7">
								<label class="control-label pull-right">DPP : </label>			
							</div>
							<div class="col-md-5">
								<div class="input-group">
									<span class="input-group-addon">Rp.</span>
									<input id="txtdpp_hdr" name="txtdpp_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($dpp); ?>" readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="row margin-bottom-5">
						<div class="form-group">
							<div class="col-md-7">
								<label class="control-label pull-right">PPN : </label>			
							</div>
							<div class="col-md-2">
								<div class="input-group">						
									<input id="txtppnpersen_hdr" name="txtppnpersen_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($ppn_persen); ?>" onkeypress="return hanya_angka(event)">
									<span class="input-group-addon">%</span>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group">	
									<span class="input-group-addon">Rp.</span>
									<input id="txtppnrupiah_hdr" name="txtppnrupiah_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($ppn_rupiah); ?>" readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="row margin-bottom-5">
						<div class="form-group">
							<div class="col-md-7">
								<label class="control-label pull-right">Biaya Materai : </label>			
							</div>
							<div class="col-md-5">
								<div class="input-group">
									<span class="input-group-addon">Rp.</span>
									<input id="txtmaterai_hdr" name="txtmaterai_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($materai); ?>" onkeypress="return hanya_angka(event)">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-7">
								<label class="control-label pull-right">Grand Total : </label>			
							</div>
							<div class="col-md-5">
								<div class="input-group">
									<span class="input-group-addon">Rp.</span>
									<input id="txtgrandtotal_hdr" name="txtgrandtotal_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($grand_total); ?>" readonly>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>