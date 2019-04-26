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
		<?php
			if ($status_po == 1 or $status_po == 4) {
		?>
				<div class="row">
					<div class="col-md-2 pull-right">
						<label>&nbsp;</label>
						<div class="input-group">
							<button id="get_data" type="button" class="btn btn-block btn-warning btn-icon-fixed"><span class="icon-plus"></span> Data Dari PR</button>
						</div>
					</div>
				</div>
		<?php
			}
		?>    

		<div class="row">
			<div class="col-md-12">            
				<table id="table_detail" class="table table-head-custom table-striped" style="width: 100%;">
					<thead>
						<tr>
							<th style="display: none">ID</th>
							<th style="width: 4%; text-align: center;">NO</th>
							<th style="display: none;">ID PRODUK</th>
							<th style="width: 29%;">NAMA PRODUK</th>
							<th style="display: none;">ID KEMASAN</th>
							<th style="width: 6%; text-align: center;">KEMASAN</th>
							<th style="width: 8%; text-align: right;">QTY PO</th>
							<th style="width: 10%; text-align: right;">HARGA SATUAN</th>
							<th style="width: 10%; text-align: right;">TOTAL</th>
							<th style="width: 6%; text-align: right;">DISC. %</th>
							<th style="width: 8%; text-align: right;">DISC. RP</th>
							<th style="width: 10%; text-align: right;">NETTO</th>
							<th style="width: 9%; text-align: center;">ACTIONS</th>
						</tr>
					</thead>
					<tbody id="show_detail">

					</tbody>
				</table>
				
				<div id="responsecontainer" align="center"></div>
				
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

<!-- Begin : Modal Edit Detail -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
        <div class="modal-content">
            <div class="modal-header">                        
                <h4 class="modal-title" id="modal-primary-header">Edit Data Detail</h4>
            </div>
            <input type="hidden" id="txtid_edit" class="form-control" />
            <div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label>Nama Produk</label>
						<div class="input-group group-namaproduk-edit">
							<input id="txtnamaproduk_edit" name="txtnamaproduk_edit" type="text" class="form-control" readonly>
							<input id="txtidproduk_edit" name="txtidproduk_edit" type="hidden" class="form-control" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label class="pull-right">Qty PO</label>
						<div class="input-group group-qtypo-edit">
							<input id="txtqtypo_edit" name="txtqtypo_edit" type="text" class="form-control" style="text-align: right" value="0" onkeypress="return hanya_angka(event)">
							<input id="txtqtypo_edit_hidden" name="txtqtypo_edit_hidden" type="hidden" class="form-control" style="text-align: right" value="0">
						</div>
					</div>
					<div class="col-md-4">
						<label class="pull-right">Harga Satuan</label>
						<div class="input-group group-hargasatuan-edit">
							<input id="txthargasatuan_edit" name="txthargasatuan_edit" type="text" class="form-control" style="text-align: right" value="0" onkeypress="return hanya_angka(event)">
							<input id="txthargasatuan_edit_hidden" name="txthargasatuan_edit_hidden" type="hidden" class="form-control" style="text-align: right" value="0">
						</div>
					</div>
					<div class="col-md-4">
						<label class="pull-right">Total</label>
						<div class="input-group group-total-edit">
							<input id="txttotal_edit" name="txttotal_edit" type="text" class="form-control" style="text-align: right" value="0" onkeypress="return hanya_angka(event)" readonly>
							<input id="txttotal_edit_hidden" name="txttotal_edit_hidden" type="hidden" class="form-control" style="text-align: right" value="0">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label class="pull-right">Disc. %</label>
						<div class="input-group group-discpersen-edit">
							<input id="txtdiscpersen_edit" name="txtdiscpersen_edit" type="text" class="form-control" style="text-align: right" value="0" onkeypress="return hanya_angka(event)">
							<input id="txtdiscpersen_edit_hidden" name="txtdiscpersen_edit_hidden" type="hidden" class="form-control" style="text-align: right" value="0">
						</div>
					</div>
					<div class="col-md-4">
						<label class="pull-right">Disc. Rp.</label>
						<div class="input-group group-discrupiah-edit">
							<input id="txtdiscrupiah_edit" name="txtdiscrupiah_edit" type="text" class="form-control" style="text-align: right" value="0" onkeypress="return hanya_angka(event)">
							<input id="txtdiscrupiah_edit_hidden" name="txtdiscrupiah_edit_hidden" type="hidden" class="form-control" style="text-align: right" value="0">
						</div>
					</div>
					<div class="col-md-4">
						<label class="pull-right">Netto</label>
						<div class="input-group group-netto-edit">
							<input id="txtnetto_edit" name="txtnetto_edit" type="text" class="form-control" style="text-align: right" value="0" onkeypress="return hanya_angka(event)" readonly>
							<input id="txtnetto_edit_hidden" name="txtnetto_edit_hidden" type="hidden" class="form-control" style="text-align: right" value="0">
						</div>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>					
                <button type="button" class="btn btn-primary" id="simpan_detail">Simpan</button>					
            </div>
        </div>
	</div>            
</div>
<!-- End : Modal Edit Detail -->