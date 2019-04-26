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
				<button type="button" class="btn btn-info btn-icon-fixed btn-tambah" data-toggle="modal" data-target="#modal-primary"><span class="icon-file-add"></span> Buat Payment AP Baru</button>     
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
                            <th class="text-center">Nama Supplier</th>
							<th class="text-center">Keterangan</th>
							<th class="text-center">Grand Total</th>
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
		<form method="POST" action="pembelian/payment_ap/create_header">
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
									echo "<option value='0' selected>Pilih Supplier</option>";
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
						<div class="col-md-12">
							<label>Keterangan</label>
							<textarea name="txtketerangan" class="form-control" rows="5" placeholder="Keterangan Transaksi"></textarea>
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
