<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "logistik/lokasi_produk/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kode 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Kelompok Produk 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Lorong 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Rak 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Baris 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kolom 6
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Deskripsi 7
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 8
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 9
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 1, 3, 4, 5, 6, 8, 9] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "5%", "targets": [8] },  // Status
				{ "width": "10%", "targets": [9] } // Action
			]
		});
		
		$("body").on("click", "#table_data .btn-edit", function() {
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('.btn-edit').val();
			
			$.ajax({
				url : 'logistik/lokasi_produk/lokasi_produk_view',
				type: 'POST',
				data: {
					id: id
				},
				dataType: 'json',
				success : function(data) {
					$('#modal-edit').modal('show');
					$('#txtid_edit').val(data.id);
					$('#txtkode_edit').val(data.kode);
					$('#cbokelompokproduk_edit').selectpicker('val', data.id_kelompok_produk);
					$('#txtlorong_edit').val(data.lorong);
					$('#txtrak_edit').val(data.rak);
					$('#txtbaris_edit').val(data.baris);
					$('#txtkolom_edit').val(data.kolom);
					$('#txtdeskripsi_edit').val(data.deskripsi);
					var win_path = data.gambar;
					$('.file-gambar-edit').html(win_path.split(/[\\\/]/).pop());
				},
				error: function() {
				  alert("failure");
				}
			});
		});

		$("#form-edit").submit(function(e) {
			var url = $(this).attr('action');
			$.ajax({
				type: 'POST',
				url: url,
				data: $("#form-edit").serialize(), 
				success: function(result) {
					$('#modal-edit').modal('hide');
					window.setTimeout(
						function() {
							location.reload(true)
						}, 0
					);
				},
				error: function() {
					alert("failure");
				}
			});
			e.preventDefault();
		});

		$("#form-create").submit(function(e) {
			var url = $(this).attr('action');
			$.ajax({
				type: 'POST',
				url: url,
				data: $("#form-create").serialize(), 
				success: function(result) {
					$('#modal-create').modal('hide');
					window.setTimeout(
						function() {
							location.reload(true)
						}, 0
					);
				},
				error: function() {
					alert("failure");
				}
			});
			e.preventDefault();
		});
	});
</script>
  
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
				<button type="button" class="btn btn-info btn-icon-fixed btn-tambah" data-toggle="modal" data-target="#modal-create"><span class="icon-file-add"></span> Tambah Data Baru</button>     
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
							<th class="text-center">Kode</th>
							<th class="text-center">Kelompok Produk</th>
							<th class="text-center">Lorong</th>
							<th class="text-center">Rak</th>
							<th class="text-center">Baris</th>
							<th class="text-center">Kolom</th>
							<th>Deskripsi</th>
							<th class="text-center">Status</th>
							<th class="text-center">Actions</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>		
	</div>
</div>

<!-- Begin : Modal Add -->
<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<form method="POST" action="logistik/lokasi_produk/lokasi_produk_create" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">                        
					<h4 class="modal-title" id="modal-primary-header">Tambah Data Lokasi Produk</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label>Kode</label>
							<input type="text" name="txtkode_add" id="txtkode_add" class="form-control" placeholder="Kode Lokasi Produk" required>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-12">
							<label>Kelompok Produk</label>
							<select name='cbokelompokproduk_add' id='cbokelompokproduk_add' class='form-control bs-select' data-live-search='true'>
								<?php
									if ($get_kelompok_produk->num_rows() > 0) {
										foreach ($get_kelompok_produk->result() as $row) {
											echo "<option value='".$row->id."'>".$row->kode." - ".$row->nama."</option>";
										}
									}
								?>
							</select>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-6">
							<label>Lorong</label>
							<input type="text" name="txtlorong_add" id="txtlorong_add" class="form-control" placeholder="Lorong Ke-" required>
						</div>
						<div class="col-md-6">
							<label>Rak</label>
							<input type="text" name="txtrak_add" id="txtrak_add" class="form-control" placeholder="Rak Ke-" required>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-6">
							<label>Baris</label>
							<input type="text" name="txtbaris_add" id="txtbarisadd" class="form-control" placeholder="Rak Ke -" required>
						</div>
						<div class="col-md-6">
							<label>Kolom</label>
							<input type="text" name="txtkolom_add" id="txtkolom_add" class="form-control" placeholder="Kolom Ke -" required>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-12">
							<label>Deskripsi</label>
							<textarea name="txtdeskripsi_add" id="txtdeskripsi_add" class="form-control" rows="5" placeholder="Deskripsi"></textarea>
						</div>
					</div>
					&nbsp;					
					<div class="row">
						<div class="col-md-12">
							<label>Gambar</label>
							<input type="file" name="gambar_add" accept="image/*" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary add">Simpan</button>					
				</div>
			</div>
		</form>
	</div>            
</div>
<!-- End : Modal Add -->

<!-- Begin : Modal Edit -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<form method="POST" action="logistik/lokasi_produk/lokasi_produk_update" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modal-primary-header">Ubah Data Lokasi Produk</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label>Kode</label>
							<input type="hidden" name="txtid_edit" id="txtid_edit" class="form-control">
							<input type="text" name="txtkode_edit" id="txtkode_edit" class="form-control" placeholder="Kode Perbekalan Farmasi" readonly>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-12">
							<label>Kelompok Produk</label>
							<select name='cbokelompokproduk_edit' id='cbokelompokproduk_edit' class='form-control bs-select' data-live-search='true'>
								<?php
									if ($get_kelompok_produk->num_rows() > 0) {
										foreach ($get_kelompok_produk->result() as $row) {
											echo "<option value='".$row->id."'>".$row->kode." - ".$row->nama."</option>";
										}
									}
								?>
							</select>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-6">
							<label>Lorong</label>
							<input type="text" name="txtlorong_edit" id="txtlorong_edit" class="form-control" placeholder="Lorong Ke-" required>
						</div>
						<div class="col-md-6">
							<label>Rak</label>
							<input type="text" name="txtrak_edit" id="txtrak_edit" class="form-control" placeholder="Rak Ke-" required>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-6">
							<label>Baris</label>
							<input type="text" name="txtbaris_edit" id="txtbaris_edit" class="form-control" placeholder="Baris Ke -" required>
						</div>
						<div class="col-md-6">
							<label>Kolom</label>
							<input type="text" name="txtkolom_edit" id="txtkolom_edit" class="form-control" placeholder="Kolom Ke -" required>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-12">
							<label>Deskripsi</label>
							<textarea name="txtdeskripsi_edit" id="txtdeskripsi_edit" class="form-control" rows="5" placeholder="Deskripsi"></textarea>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-4">
							<label>Gambar</label>
							<input type="file" name="gambar_edit" accept="image/*" required>
						</div>
						<div class="col-md-8 file-gambar-edit"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>					
					<button type="submit" class="btn btn-primary edit">Simpan</button>					
				</div>
			</div>
		</form>
	</div>            
</div>
<!-- End : Modal Edit -->