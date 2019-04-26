<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
    
    $id_transaksi = '';
    $no_transaksi = ''; 
	$tgl_transaksi = ''; 
    $id_supplier = '';
	$nama_supplier = '';
	$no_invoice_supplier = '';
	$tgl_invoice_supplier = '';
	$tgl_jatuh_tempo = '';
	$keterangan = '';
	$sub_total = '0';
	$disc_persen = '0';
	$disc_rp = '0';
	$dpp = '0';
	$ppn_persen = '0';
	$ppn_rp = '0';
	$materai = '0';
	$grand_total = '0';
	$validasi = '';

    if ($get_header->num_rows() > 0) {
        foreach($get_header->result() as $row) {
			$id_transaksi = $row->id_header;
			$no_transaksi = $row->no_transaksi; 
			$tgl_transaksi = $row->tgl_transaksi; 
			$id_supplier = $row->id_supplier;
			$nama_supplier = $row->nama_supplier;
			$no_invoice_supplier = $row->no_invoice_supplier;
			$tgl_invoice_supplier = $row->tgl_invoice_supplier;
			$tgl_jatuh_tempo = $row->tgl_jatuh_tempo;
			$keterangan = $row->keterangan;
			$sub_total = $row->sub_total;
			$disc_persen = $row->disc_persen;
			$disc_rp = $row->disc_rupiah;
			$dpp = $row->dpp;
			$ppn_persen = $row->ppn_persen;
			$ppn_rp = $row->ppn_rupiah;
			$materai = $row->materai;
			$grand_total = $row->grand_total;
			$validasi = $row->validasi;
        } 
    }
?>

<!-- Begin : Block Header -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Data Header</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-2">
				<label>No. Transaksi</label>
				<div class="input-group">
					<input id="txtnotransaksi_hdr" name="txtnotransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo $no_transaksi; ?>" readonly>
					<input id="txtidtransaksi_hdr" name="txtidtransaksi_hdr" type="hidden" class="form-control" value="<?php echo $id_transaksi; ?>" readonly>
				</div>
			</div>
			<div class="col-md-2">
				<label>Tgl. Transaksi</label>
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
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-6">
						<label>No. Invoice Supplier</label>
						<div class="input-group group-noinvoicesupplier-hdr">
							<input id="txtnoinvoicesupplier_hdr" name="txtnoinvoicesupplier_hdr" type="text" class="form-control input-sm" value="<?php echo $no_invoice_supplier; ?>" readonly>
						</div>
					</div>
					<div class="col-md-6">
						<label>Tgl. Jatuh Tempo</label>
						<div class="input-group group-tgljatuhtempo-hdr">
							<input id="txttgljatuhtempo_hdr" name="txttgljatuhtempo_hdr" type="text" class="form-control input-sm" value="<?php echo date_format(new DateTime($tgl_jatuh_tempo), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>Tgl. Invoice Supplier</label>
						<div class="input-group group-tglinvoicesupplier-hdr">
							<input id="txttglinvoicesupplier_hdr" name="txttglinvoicesupplier_hdr" type="text" class="form-control input-sm" value="<?php echo date_format(new DateTime($tgl_invoice_supplier), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>" readonly>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<label>Keterangan</label>
				<div class="input-group">
					<textarea id="txtketerangan_hdr" name="txtketerangan_hdr" class="form-control" rows="4" readonly><?php echo $keterangan; ?></textarea>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End : Block Header -->

<!-- Begin : Block Detail -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Data Detail</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div id='responsecontainer' align='center'></div>
				<table id="table_detail" class="table table-head-custom table-striped" style="width: 100%;">
					<thead>
						<tr>
							<th style="display: none">ID</th>
							<th style="width: 4%; text-align: center;">NO</th>
							<th style="display: none;">GR ID</th>
							<th style="width: 10%; text-align: center;">NO. GR</th>
							<th style="width: 8%; text-align: center;">TGL. GR</th>
							<th style="width: 10%; text-align: center;">NO. PO</th>
							<th style="width: 10%; text-align: center;">NO. SJ SUPPLIER</th>
							<th style="width: 8%; text-align: center;">TGL. TERIMA</th>
							<th style="width: 12%; text-align: right;">JUMLAH TAGIHAN</th>
						</tr>
					</thead>				
					<tbody id="show_detail">
					
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
		<div class="row">
			<div class="col-md-7">
				<div class="row">
					<!--<div class="col-md-3">
						<label class="control-label">Status :</label>
					</div>
					<div class="col-md-2">
						<label class="text-success">
							<input type="radio" name="rdostatus" value="3" checked>
							Approve
						</label>
					</div>
					<div class="col-md-2">
						<label class="text-warning">
							<input type="radio" name="rdostatus" value="4">
							Revisi
						</label>
					</div>
					<div class="col-md-2">
						<label class="text-danger">
							<input type="radio" name="rdostatus" value="5">
							Reject
						</label>
					</div>-->
					<div class="col-md-2">
						<button type="button" id="submit_approve" class="btn btn-danger pull-right">Submit</button>
					</div>
				</div>
				<!--&nbsp;				
				<div class="row">
					<div class="col-md-3">
						<label class="control-label">Keterangan :</label>
					</div>
					<div class="col-md-9">
						<textarea id="txtketerangan_input" name="txtketerangan_input" class="form-control" rows="4"></textarea>
					</div>
				</div>
				&nbsp;
				<div class="row">
					<div class="col-md-3">
						<label class="control-label">Keterangan (histori) :</label>
					</div>
					<div class="col-md-9">
						<textarea id="txtketerangan_histori" name="txtketerangan_histori" class="form-control" rows="3" readonly></textarea>
					</div>
				</div>-->			
			</div>
			<div class="col-md-5">
				<form class="form-horizontal">
					<div class="row margin-bottom-5">
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label pull-right">Sub Total : </label>			
							</div>
							<div class="col-md-9">
								<div class="input-group">
									<span class="input-group-addon">Rp.</span>
									<input id="txtsubtotal_hdr" name="txtsubtotal_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($sub_total); ?>" readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="row margin-bottom-5">
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label pull-right">Disc. : </label>			
							</div>
							<div class="col-md-3">
								<div class="input-group">						
									<input id="txtdiscpersen_hdr" name="txtdiscpersen_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($disc_persen, 0); ?>" onkeypress="return hanya_angka(event)" readonly>
									<span class="input-group-addon">%</span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group">	
									<span class="input-group-addon">Rp.</span>
									<input id="txtdiscrupiah_hdr" name="txtdiscrupiah_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($disc_rp); ?>" onkeypress="return hanya_angka(event)" readonly>						
								</div>
							</div>
						</div>
					</div>
					<div class="row margin-bottom-5">
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label pull-right">DPP : </label>			
							</div>
							<div class="col-md-9">
								<div class="input-group">
									<span class="input-group-addon">Rp.</span>
									<input id="txtdpp_hdr" name="txtdpp_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($dpp); ?>" readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="row margin-bottom-5">
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label pull-right">PPN : </label>			
							</div>
							<div class="col-md-3">
								<div class="input-group">						
									<input id="txtppnpersen_hdr" name="txtppnpersen_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($ppn_persen); ?>" onkeypress="return hanya_angka(event)" readonly>
									<span class="input-group-addon">%</span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group">	
									<span class="input-group-addon">Rp.</span>
									<input id="txtppnrupiah_hdr" name="txtppnrupiah_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($ppn_rp); ?>" readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="row margin-bottom-5">
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label pull-right">Biaya Materai : </label>			
							</div>
							<div class="col-md-9">
								<div class="input-group">
									<span class="input-group-addon">Rp.</span>
									<input id="txtmaterai_hdr" name="txtmaterai_hdr" type="text" class="form-control text-right text-xlg text-bolder" value="<?php echo number_format($materai); ?>" onkeypress="return hanya_angka(event)" readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label pull-right">Grand Total : </label>			
							</div>
							<div class="col-md-9">
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
<!-- End : Block Detail -->