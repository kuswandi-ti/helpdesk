<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "hr/data_cuti/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Jumlah 2
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 3
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 2, 3] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "10%", "targets": [3] } // Action
			]
		});
		
		$("body").on("click", "#table_data .btn-edit", function() {
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('.btn-edit').val();
			
			$.ajax({
				url : 'hr/data_cuti/data_cuti_view',
				type: 'POST',
				data: {
					id: id
				},
				dataType: 'json',
				success : function(data) {
					$('#modal-edit').modal('show');
					$('#txtid_edit').val(data.id);
					$('#txtnama_edit').val(data.nama);
					$('#txtjumlah_edit').val(data.jumlah);
					$('#cbosatuan_edit').val(data.satuan); $("#cbosatuan_edit").selectpicker('refresh');
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
							<th>Nama Jenis Cuti</th>
							<th style="text-align: center">Jumlah</th>
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
		<form id="form-create" method="POST" action="hr/data_cuti/data_cuti_create">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modal-primary-header">Tambah Data Jenis Cuti</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label>Nama Jenis Cuti</label>
							<input type="text" name="txtnama_add" id="txtnama_add" class="form-control" placeholder="Nama Jenis Cuti" required>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-6">
							<label>Jumlah</label>
							<input type="text" name="txtjumlah_add" id="txtjumlah_add" class="form-control" placeholder="0" onkeypress="return hanya_angka(event)" required>
						</div>
						<div class="col-md-6">
							<label>&nbsp;</label>
							<select id="cbosatuan_add" name="cbosatuan_add" class="bs-select" data-live-search="true">												
								<?php 
									foreach ($get_satuan as $key => $val) {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								?>
							</select>
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
		<form id="form-edit" method="POST" action="hr/data_cuti/data_cuti_update">
			<div class="modal-content">
				<div class="modal-header">                        
					<h4 class="modal-title" id="modal-primary-header">Ubah Data Jenis Cuti</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label>Nama Jenis Cuti</label>
							<input type="hidden" name="txtid_edit" id="txtid_edit" class="form-control">
							<input type="text" name="txtnama_edit" id="txtnama_edit" class="form-control" placeholder="Nama Bank" required>
						</div>
					</div>
					&nbsp;
					<div class="row">
						<div class="col-md-6">
							<label>Jumlah</label>
							<input type="text" name="txtjumlah_edit" id="txtjumlah_edit" class="form-control" placeholder="0" onkeypress="return hanya_angka(event)" required>
						</div>
						<div class="col-md-6">
							<label>&nbsp;</label>
							<select id="cbosatuan_edit" name="cbosatuan_edit" class="bs-select" data-live-search="true">												
								<?php 
									foreach ($get_satuan as $key => $val) {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								?>
							</select>
						</div>
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