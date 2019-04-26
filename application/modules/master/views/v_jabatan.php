<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "master/jabatan/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[6, 'asc'], [8, 'asc'], [3, 'asc'], [1, 'asc']],
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kode Jabatan 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Jabatan 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Level Jabatan 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Kelompok Kerja 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Level Kelompok Kerja 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kode Unit Kerja 6
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Unit Kerja 7
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Deskripsi 8
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 9
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 10
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 1, 3, 5, 6, 9, 10] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "5%", "targets": [9] },  // Status
				{ "width": "10%", "targets": [10] } // Action
			]
		});
		
		$("#cbokelompokkerja").on('change',function(){
			var opt = '<option selected hidden value="0">Pilih Unit Kerja</option>';
			$.ajax({
				url: 'master/jabatan/get_unit_kerja',
				dataType: 'json',
				type: 'post',
				data: {
					id_kelompok_kerja: $(this).val()
				},
				success: function(json) {
					$.each(json, function(i, obj) {
						opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
					});
					$("#cbounitkerja").html(opt);
					$("#cbounitkerja").selectpicker('refresh');
				}
			});
		});
		
		$(".btn-tambah").click(function() {
			$('#modal-primary-header').html('Tambah Data Master Jabatan');
			reset_input();
		});
		
		$("body").on("click", "#table_data .btn-edit", function() {
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('.btn-edit').val();
			
			$.ajax({
				url : 'master/jabatan/jabatan_view',
				type: 'POST',
				data: {
					id_jabatan: id
				},
				dataType: 'json',
				success : function(data) {
					$('#modal-create-edit').modal('show');
					$('#modal-primary-header').html('Edit Data Master Jabatan');
					$('#txtid').val(data.id_jabatan);
					$('#txtkode').val(data.kode_jabatan);
					$('#txtnama').val(data.nama_jabatan);
					$('#cboleveljabatan').val(data.level_jabatan);
					$("#cboleveljabatan").selectpicker('refresh');
					$('#cbokelompokkerja').val(data.id_kelompok_kerja);
					$("#cbokelompokkerja").selectpicker('refresh');
					var opt = '<option selected hidden value="0">Pilih Unit Kerja</option>';
					$.ajax({
						url: 'master/jabatan/get_unit_kerja',
						dataType: 'json',
						type: 'post',
						data: {
							id_kelompok_kerja: $('#cbokelompokkerja').val()
						},
						async: false,
						success: function(json) {
							$.each(json, function(i, obj) {
								opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
							});
							$("#cbounitkerja").html(opt);
							$("#cbounitkerja").selectpicker('refresh');							
						}
					});
					$('#cbounitkerja').selectpicker('val', data.id_unit_kerja);
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
			
			formData.append('cbokelompokkerja', $("#cbokelompokkerja").val());
			formData.append('cbounitkerja', $("#cbounitkerja").val());
			formData.append('chkaktif', $('#chkaktif').is(':checked') ? '1'  : '0');
			
			if ($('#txtid').val() == '') {
				var urls = 'master/jabatan/jabatan_create';
			} else {
				var urls = 'master/jabatan/jabatan_update';
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
		$('#txtkode').val('');
		$('#txtnama').val('');
		$('#cbokelompokkerja').val('0');
		$("#cbokelompokkerja").selectpicker('refresh');
		$('#cbounitkerja').val('0');
		$("#cbounitkerja").selectpicker('refresh');
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
							<th class="text-center">Kode Jabatan</th>
							<th>Nama Jabatan</th>
							<th class="text-center">Level Jabatan</th>
							<th>Nama Kelompok Kerja</th>
							<th class="text-center">Level Kelompok Kerja</th>
							<th class="text-center">Kode Unit Kerja</th>
							<th>Nama Unit Kerja</th>
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
				
				<input type="hidden" name="txtid" id="txtid">
				
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3 control-label">Kode Jabatan</label>
								<div class="col-md-9">
									<input type="text" name="txtkode" id="txtkode" class="form-control" placeholder="Kode Jabatan" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Nama Jabatan</label>
								<div class="col-md-9">
									<input type="text" name="txtnama" id="txtnama" class="form-control" placeholder="Nama Jabatan" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Level Jabatan</label>
								<div class="col-md-9">
									<select name='cboleveljabatan' id='cboleveljabatan' class='form-control bs-select' data-live-search='true'>
										<option selected hidden value="0">Pilih Level Jabatan</option>
										<?php
											for($i=1; $i<=10; $i++) {
												echo"<option value=".$i.">".$i."</option>";
											}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Kelompok Kerja</label>
								<div class="col-md-9">
									<select name='cbokelompokkerja' id='cbokelompokkerja' class='form-control bs-select' data-live-search='true'>
										<option value='0'>Pilih Kelompok Kerja</option>
										<?php										
											if ($get_kelompok_kerja->num_rows() > 0) {
												foreach ($get_kelompok_kerja->result() as $row) {
													echo "<option value='".$row->id."'>".$row->nama."</option>";
												}
											}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Unit Kerja</label>
								<div class="col-md-9">
									<select name='cbounitkerja' id='cbounitkerja' class='form-control bs-select' data-live-search='true'>
										<option hidden>Pilih Unit Kerja</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Deskripsi</label>
								<div class="col-md-9">
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