<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
    
    $id_transaksi = '';
    $no_transaksi = ''; 
	$tgl_transaksi = ''; 
    $id_supplier = '';
	$nama_supplier = '';
	$id_po = '';
	$no_po = '';
	$no_sj_supplier = '';
	$tgl_terima = '';
	$keterangan = '';
	$flag_piutang = '';

    if ($get_header->num_rows() > 0) {
        foreach($get_header->result() as $row) {
			$id_transaksi = $row->id_header;
			$no_transaksi = $row->no_transaksi; 
			$tgl_transaksi = $row->tgl_transaksi; 
			$id_supplier = $row->id_supplier;
			$nama_supplier = $row->nama_supplier;
			$id_po = $row->id_po;
			$no_po = $row->no_po;
			$no_sj_supplier = $row->no_sj_supplier;
			$tgl_terima = $row->tgl_terima;
			$keterangan = $row->keterangan;
			$flag_piutang = $row->flag_piutang;
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
			<div class="col-md-6">
				<label>Supplier</label>
				<div class="input-group">
					<input id="txtnamasupplier_hdr" name="txtnamasupplier_hdr" type="text" class="form-control input-sm" value="<?php echo $nama_supplier; ?>" readonly>
					<input id="txtsupplierid_hdr" name="txtsupplierid_hdr" type="hidden" class="form-control input-sm" value="<?php echo $id_supplier; ?>" readonly>
				</div>
			</div>
			<div class="col-md-2">
				<label>No. PO</label>
				<div class="input-group">
					<input id="txtnopo_hdr" name="txtnopo_hdr" type="text" class="form-control input-sm" value="<?php echo $no_po; ?>" readonly>
					<input id="txtidpo_hdr" name="txtidpo_hdr" type="hidden" class="form-control input-sm" value="<?php echo $id_po; ?>" readonly>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-2">
				<div class="row">
					<div class="col-md-12">
						<label>No. SJ Supplier</label>
						<div class="input-group group-nosjsupplier">
							<input id="txtnosjsupplier_hdr" name="txtnosjsupplier_hdr" type="text" class="form-control input-sm" value="<?php echo $no_sj_supplier; ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label>Tgl. Terima</label>
						<div class="input-group group-tglterima">
							<input id="txttglterima_hdr" name="txttglterima_hdr" type="text" class="form-control input-sm" value="<?php echo date_format(new DateTime($tgl_terima), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<label>Keterangan</label>
				<div class="input-group">
					<textarea id="txtketerangan_hdr" name="txtketerangan_hdr" class="form-control" rows="4"><?php echo $keterangan; ?></textarea>
				</div>
			</div>
			<div class="col-md-2">
				<div class="row">
					<div class="col-md-12">
						<label>&nbsp;</label>
						<div class="input-group">
							&nbsp;
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label>&nbsp;</label>
						<div class="input-group">
							<button class="btn btn-block btn-success btn-icon-fixed" id="simpan_header"><span class="icon-floppy-disk"></span> Simpan</button>
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
		<?php
			if ($row->flag_piutang != 1) {
		?>
		<div class="row">
			<div class="col-md-2 pull-right">
				<label>&nbsp;</label>
				<div class="input-group">
					<button id="get_data" type="submit" class="btn btn-block btn-warning btn-icon-fixed"><span class="icon-plus"></span> Data Dari PO</button>					
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
							<th style="width: 28%;">NAMA PRODUK</th>
							<th style="display: none;">ID KEMASAN</th>
							<th style="width: 8%; text-align: center;">KEMASAN</th>
							<th style="width: 10%; text-align: center;">BATCH NUMBER</th>
							<th style="width: 10%; text-align: center;">EXPIRED DATE</th>
							<th style="width: 6%; text-align: right;">QTY TERIMA</th>
							<th style="width: 10%; text-align: center;">ID LOKASI</th>
							<th style="display: none">KODE LOKASI</th>
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

<!-- Begin : Modal Edit Detail -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
        <div class="modal-content">
            <div class="modal-header">                        
                <h4 class="modal-title" id="modal-primary-header">Edit Data Detail</h4>
            </div>
            <input type="hidden" id="txtid_edit" class="form-control" />
			<input type="hidden" id="txtpoid_edit" class="form-control" />
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
						<label>Batch Number</label>
						<div class="input-group group-batchnumber_edit">
							<input id="txtbatchnumber_edit" name="txtbatchnumber_edit" type="text" class="form-control" placeholder="Batch Number">
						</div>
					</div>
					<div class="col-md-4">
						<label>Expired Date</label>
						<div class="input-group group-expireddate_edit">
							<input id="txtexpireddate_edit" name="txtexpireddate_edit" type="text" class="form-control" placeholder="Expired Date">
						</div>
					</div>
					<div class="col-md-4">
						<label class="pull-right">Qty</label>
						<div class="input-group group-qty_gr-edit">
							<input id="txtqty_gr_edit" name="txtqty_gr_edit" type="text" class="form-control" style="text-align: right" placeholder="Qty" onkeypress="return hanya_angka(event)">
							<input id="txtqty_gr_edit_hidden" name="txtqty_gr_edit_hidden" type="hidden" class="form-control" style="text-align: right" placeholder="Qty">
						</div>
					</div>
					<div class="col-md-12">
						<label>Lokasi</label>
						<div class="input-group group-lokasi-edit">
							<select id="cbolokasi_edit" name="cbolokasi_edit" class="bs-select" data-live-search="true">
								<option hidden>Pilih Lokasi</option>
							</select>
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