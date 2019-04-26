<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row margin-bottom-5">
	<div class="col-md-12">
		<div class="app-heading app-heading-small">
			<div class="icon icon-lg">
				<span class="<?php echo $page_icon; ?>"></span>
			</div>
			<div class="title">
				<h2><?php echo $page_title; ?></h2>
				<p><?php echo $page_subtitle; ?></p>
			</div>                        
			<div class="heading-elements">
				<button type="button" class="btn btn-info btn-icon-fixed btn-tambah" data-toggle="modal" data-target="#modal-primary"><span class="icon-file-add"></span> Buat GR Baru</button>     
			</div>
		</div>                                                         
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-body">   
				<table id="table_data" class="table table-head-custom table-striped" style="width: 100%">
					<thead>
						<tr>
							<th class="text-center">No.</th>
							<th class="text-center">No. Transaksi</th>
							<th class="text-center">Tgl Transaksi</th>
							<th class="text-center">Supplier ID</th>
                            <th>Nama Supplier</th>
							<th class="text-center">No. PO</th>
							<th class="text-center">No. SJ Supplier</th>
							<th class="text-center">Tgl. Terima</th>
							<th>Keterangan</th>
							<th class="text-center">Actions</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>		
	</div>
</div>

<!-- Begin : Modal Add Header -->
<div class="modal fade" id="modal-primary" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<form method="POST" action="pembelian/goods_receive/create_header" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">                        
					<h4 class="modal-title" id="modal-primary-header">Data Header</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label>Supplier</label>
							<select name="cbosupplier" id="cbosupplier" class="bs-select" data-live-search="true">
								<?php
									echo "<option value='0'>Pilih Supplier</option>";
									if ($get_supplier->num_rows() > 0) {										
										foreach ($get_supplier->result() as $r) {
											echo "<option value=".$r->id_supplier.">".$r->nama_supplier."</option>";
										}
									}
								?>
							</select>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-4">
							<label>No. PO</label>
							<select name="cbopo" id="cbopo" style="width: 100%; height: 40px;">
							
                            </select>
						</div>
						<div class="col-md-4">
							<label>No. SJ. Supplier</label>
							<input type="text" name="txtnosjsupplier" id="txtnosjsupplier" class="form-control" placeholder="No. SJ Supplier">
						</div>
						<div class="col-md-4">
							<label>Tgl. Terima</label>
							<input type="text" name="txttglterima" id="txttglterima" class="form-control" value="<?php echo date($this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>">
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-12">
							<label>Keterangan</label>
							<textarea name="txtketerangan" class="form-control" rows="3" placeholder="Keterangan Transaksi"></textarea>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-6">
							<label>Bukti Dokumen</label>
							<input type="file" name="file_bukti_dokumen" accept="image/x-png,image/gif,image/jpeg,image/jpg" onChange="readURL(this);" required>
							<span style="font-size:11px; color:#666666;">only gif, png, jpg, jpeg files. <br />max. size image 250x400</span>							
						</div>
						<div class="col-md-6">
							<img id="image" name="image" src="#" alt="" height="400" width="250" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>					
					<button type="submit" class="btn btn-primary">Simpan</button>					
				</div>
			</div>
		</form>
	</div>            
</div>
<!-- End : Modal Add Header -->
