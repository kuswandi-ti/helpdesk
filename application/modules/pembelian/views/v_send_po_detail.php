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
	$nama_tipe_pembayaran = '';
	$top = '';
	$tgl_pengiriman = '';
	$keterangan = ''; 
	$status_po = '';
	$status_histori = '';
	$total_barang = '0';
	$disc_persen = '0';
	$disc_rupiah = '0';
	$dpp = '0';
	$ppn_persen = '0';
	$ppn_rupiah = '0';
	$materai = '0';
	$grand_total = '0';
	$pengirim = '';
	$keterangan_kirim = '';
	$tgl_kirim = '';

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
            $tipe_pembayaran = $row->id_tipe_pembayaran;
			$nama_tipe_pembayaran = $row->nama_tipe_pembayaran;
            $top = $row->top;
			$tgl_pengiriman = $row->tgl_pengiriman;
            $keterangan = $row->keterangan; 
			$status_po = $row->status_po;
			$status_histori = $row->status_histori;   
			$total_barang = $row->total_barang;
			$disc_persen = $row->disc_persen;
			$disc_rupiah = $row->disc_rupiah;
			$dpp = $row->dpp;
			$ppn_persen = $row->ppn_persen;
			$ppn_rupiah = $row->ppn_rupiah;
			$materai = $row->materai;
			$grand_total = $row->grand_total;
			$pengirim = $row->pengirim;
			$keterangan_kirim = $row->keterangan_kirim;
			$tgl_kirim = date_format(new DateTime($row->tgl_kirim), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
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
					
					<input id="txtusersession" name="txtusersession" type="hidden" class="form-control" value="<?php echo $username; ?>" readonly>
				</div>
			</div>
			<div class="col-md-2">
				<label>Tgl. PO</label>
				<div class="input-group">
					<input id="txttgltransaksi_hdr" name="txttgltransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo date_format(new DateTime($tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>" readonly>
				</div>
			</div>        
			<div class="col-md-6">
				<label>Supplier</label>
				<div class="input-group">
					<input id="txtnamasupplier_hdr" name="txtnamasupplier_hdr" type="text" class="form-control input-sm" value="<?php echo $nama_supplier; ?>" readonly>
					<input id="txtsupplierid_hdr" name="txtsupplierid_hdr" type="hidden" class="form-control input-sm" value="<?php echo $id_supplier; ?>" readonly>
				</div>
			</div>
			<div class="col-md-2">
				<label>No. PR</label>
				<div class="input-group">
					<input id="txtnopr_hdr" name="txtnopr_hdr" type="text" class="form-control input-sm" value="<?php echo $no_pr; ?>" readonly>
					<input id="txtidpr_hdr" name="txtidpr_hdr" type="hidden" value="<?php echo $id_pr; ?>" readonly>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-3">
				<label>Alamat Pengiriman</label>
				<div class="input-group group-alamatpengiriman">
					<textarea id="txtalamatpengiriman_hdr" name="txtalamatpengiriman_hdr" class="form-control" rows="4" readonly><?php echo $alamat_pengiriman; ?></textarea>
				</div>
			</div>
			<div class="col-md-3">
				<label>Keterangan</label>
				<div class="input-group">
					<textarea id="txtketerangan_hdr" name="txtketerangan_hdr" class="form-control" rows="4" readonly><?php echo $keterangan; ?></textarea>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">
						<label>Tipe Pembayaran</label>
						<div class="input-group group-tipepembayaran">
							<input id="txtnamatipepembayaran_hdr" name="txtnamatipepembayaran_hdr" type="text" class="form-control input-sm" value="<?php echo $nama_tipe_pembayaran; ?>" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<label>T.O.P. (hari)</label>
						<div class="input-group group-tipepembayaran">
							<input id="txttop_hdr" name="txttop_hdr" type="text" class="form-control input-sm" value="<?php echo $top; ?>" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<label>Bulan</label>
						<div class="input-group group-bulan">
							<input id="txtbulan_hdr" name="txtbulan_hdr" type="text" class="form-control input-sm" value="<?php echo $bulan; ?>" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label>Tgl. Pengiriman</label>
						<div class="input-group group-tipepembayaran">
							<input id="txttglpengiriman_hdr" name="txttglpengiriman_hdr" type="text" class="form-control input-sm" value="<?php echo $tgl_pengiriman; ?>" readonly>
						</div>
					</div>
					<div class="col-md-4">
						&nbsp;
					</div>
					<div class="col-md-4">
						<label>Tahun</label>
						<div class="input-group group-tahun">
							<input id="txttahun_hdr" name="txttahun_hdr" type="text" class="form-control input-sm" value="<?php echo $tahun; ?>" readonly>
						</div>
					</div>
				</div>
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
				<table id="table_detail" class="table table-head-custom table-striped" style="width: 100%;">
					<thead>
						<tr>
							<th style="display: none">ID</th>
							<th style="width: 4%; text-align: center;">NO</th>
							<th style="display: none;">ID PRODUK</th>
							<th style="width: 38%;">NAMA PRODUK</th>
							<th style="display: none;">ID KEMASAN</th>
							<th style="width: 6%; text-align: center;">KEMASAN</th>
							<th style="width: 8%; text-align: right;">JUMLAH PO</th>
							<th style="width: 10%; text-align: right;">HARGA SATUAN</th>
							<th style="width: 10%; text-align: right;">TOTAL</th>
							<th style="width: 6%; text-align: right;">DISC. %</th>
							<th style="width: 8%; text-align: right;">DISC. RP</th>
							<th style="width: 10%; text-align: right;">NETTO</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if ($get_detail->num_rows() > 0) {
								$i = 1;
								foreach ($get_detail->result() as $row) {
									echo '<tr>';
									echo    '<td id="id_detail" style="display:none;">'.$row->id_detail.'</td>';
									echo    '<td id="no" style="width: 4%; text-align: center;">'.$i.'</td>';
									echo    '<td id="id_produk" style="display:none;">'.$row->id_produk.'</td>';
									echo    '<td id="nama_produk" style="width: 38%;">'.$row->nama_produk.'</td>';
									echo    '<td id="id_kemasan" style="display:none;">'.$row->id_kemasan.'</td>';
									echo    '<td id="nama_kemasan" style="width: 6%; text-align: center;">'.$row->nama_kemasan.'</td>';
									echo    '<td id="qty_po" style="width: 8%; text-align: right;">'.number_format($row->qty_po).'</td>';
									echo    '<td id="harga_satuan" style="width: 10%; text-align: right;">'.number_format($row->harga_satuan).'</td>';
									echo    '<td id="total" style="width: 10%; text-align: right;">'.number_format($row->total).'</td>';
									echo    '<td id="disc_persen" style="width: 6%; text-align: right;">'.number_format($row->disc_persen).'</td>';
									echo    '<td id="disc_rupiah" style="width: 8%; text-align: right;">'.number_format($row->disc_rupiah).'</td>';
									echo    '<td id="netto" style="width: 10%; text-align: right;">'.number_format($row->netto).'</td>';								
									echo '</tr>';
									
									$i++;
								}
							}
						?>
					</tbody>
				</table>            
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Summary</h3>
	</div>
	<div class="panel-body">
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
							<input id="txtdiscpersen_hdr" name="txtdiscpersen_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($disc_persen, 0); ?>" onkeypress="return hanya_angka(event)" readonly>
							<span class="input-group-addon">%</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="input-group">	
							<span class="input-group-addon">Rp.</span>
							<input id="txtdiscrupiah_hdr" name="txtdiscrupiah_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($disc_rupiah); ?>" onkeypress="return hanya_angka(event)" readonly>						
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
							<input id="txtppnpersen_hdr" name="txtppnpersen_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($ppn_persen); ?>" onkeypress="return hanya_angka(event)" readonly>
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
							<input id="txtmaterai_hdr" name="txtmaterai_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($materai); ?>" onkeypress="return hanya_angka(event)" readonly>
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
	
<div class="block">
	<div class="row">
		<div class="col-md-6 pull-right">
			<div class="row">
				<div class="col-md-12">
					<div class="input-group">
						<span class="input-group-addon">Pengirim</span>
						<input id="txtpengirim" name="txtpengirim" type="text" class="form-control" value="<?php echo $pengirim; ?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="input-group">
						<span class="input-group-addon">Keterangan</span>
						<input id="txtketerangankirim" name="txtketerangankirim" type="text" class="form-control" value="<?php echo $keterangan_kirim; ?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="input-group">
						<span class="input-group-addon">Tgl. Kirim</span>
						<input id="txttglkirim" name="txttglkirim" type="text" class="form-control" value="<?php echo $tgl_kirim; ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-12">
					<button type="button" id="simpan_kirim" class="btn btn-danger pull-right">Simpan</button>
				</div>				
			</div>
		</div>
	</div>
</div>
<!-- End : Block Detail -->