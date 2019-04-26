<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "master/kelompok_kerja/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[2, 'asc']],
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Level 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Deskripsi 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 4
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 5
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 2, 4, 5] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "5%", "targets": [4] },  // Status
				{ "width": "10%", "targets": [5] } // Action
			]
		});
		
		$(".btn-tambah").click(function() {
			$('#modal-primary-header').html('Tambah Data Master Kelompok Kerja');
			reset_input();
		});
		
		$("body").on("click", "#table_data .btn-edit", function() {
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('.btn-edit').val();
			
			$.ajax({
				url : 'master/kelompok_kerja/kelompok_kerja_view',
				type: 'POST',
				data: {
					id: id
				},
				dataType: 'json',
				success : function(data) {
					$('#modal-create-edit').modal('show');
					$('#modal-primary-header').html('Edit Data Master Kelompok Kerja');
					$('#txtid').val(data.id);
					$('#txtnama').val(data.nama);
					$('#cbolevel').val(data.level);
					$("#cbolevel").selectpicker('refresh');
					$('#txtdeskripsi').val(data.deskripsi);
					if (data.activated == 1) {
						$("#chkaktif").prop('checked', true);
					} else {
						$("#chkaktif").prop('checked', false);
					}
				},
				error: function() {
				  alert("failure");
				}
			});
		});
		
		$(".add-edit").click(function(e) {			
			var form = $('#form-create-edit')[0];
			var formData = new FormData(form);
			
			formData.append('cbolevel', $("#cbolevel").val());
			formData.append('chkaktif', $('#chkaktif').is(':checked') ? '1'  : '0');
			
			if ($('#txtid').val() == '') {
				var urls = 'master/kelompok_kerja/kelompok_kerja_create';
			} else {
				var urls = 'master/kelompok_kerja/kelompok_kerja_update';
			}
			
			$.ajax({
				type: 'POST',
				url: urls,
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$('#modal-create-edit').modal('hide');
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
		});
	});
	
	function reset_input() {
		$('#txtid').val('');
		$('#txtnama').val('');
		$('#cbolevel').val('0');
		$("#cbolevel").selectpicker('refresh');
		$('#txtdeskripsi').val('');
		$("#chkaktif").prop('checked', true);
	}
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
				<button type="button" class="btn btn-info btn-icon-fixed btn-tambah" data-toggle="modal" data-target="#modal-create-edit"><span class="icon-file-add"></span> Tambah Data Baru</button>
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
							<th>Nama</th>
							<th class="text-center">Level</th>
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

<!-- Begin : Modal Add / Edit -->
<div class="modal fade" id="modal-create-edit" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<form name="form-create-edit" id="form-create-edit" method="POST" class="form-horizontal">
			<div class="modal-content">
				<div class="modal-header">                        
					<h4 class="modal-title" id="modal-primary-header"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-2 control-label">Nama</label>
								<div class="col-md-10">
									<input type="hidden" name="txtid" id="txtid">
									<input type="text" name="txtnama" id="txtnama" class="form-control" placeholder="Nama Kelompok Kerja" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Level</label>
								<div class="col-md-10">
									<select name='cbolevel' id='cbolevel' class='form-control bs-select' data-live-search='true'>
										<option selected hidden value="0">Pilih Level</option>
										<?php
											for($i=1; $i<=5; $i++) {
												echo"<option value=".$i.">".$i."</option>";
											}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Deskripsi</label>
								<div class="col-md-10">
									<textarea name="txtdeskripsi" id="txtdeskripsi" class="form-control" rows="3" placeholder="Deskripsi"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<p class="pull-right"><input type='checkbox' name='chkaktif' id='chkaktif'> Aktif</p>
					<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary add-edit">Simpan</button>					
				</div>
			</div>
		</form>
	</div>            
</div>
<!-- End : Modal Add / Edit -->