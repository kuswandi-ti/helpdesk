<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "master/unit_kerja/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[5, 'asc'], [1, 'asc']],
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kode 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Kelompok Kerja 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Level Kelompok Kerja 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Deskripsi 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 6
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 7
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 1, 3, 4, 6, 7] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "5%", "targets": [6] },  // Status
				{ "width": "10%", "targets": [7] } // Action
			]
		});
		
		$(".btn-tambah").click(function() {
			$('#modal-primary-header').html('Tambah Data Master Unit Kerja');
			reset_input();
		});
		
		$("body").on("click", "#table_data .btn-edit", function() {
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('.btn-edit').val();
			
			$.ajax({
				url : 'master/unit_kerja/unit_kerja_view',
				type: 'POST',
				data: {
					id_unit_kerja: id
				},
				dataType: 'json',
				success : function(data) {
					$('#modal-create-edit').modal('show');
					$('#modal-primary-header').html('Edit Data Master Unit Kerja');
					$('#txtid').val(data.id_unit_kerja);
					$('#txtkode').val(data.kode_unit_kerja);
					$('#txtnama').val(data.nama_unit_kerja);
					$('#cbokelompokkerja').val(data.id_kelompok_kerja);
					$("#cbokelompokkerja").selectpicker('refresh');
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
			formData.append('chkaktif', $('#chkaktif').is(':checked') ? '1'  : '0');
			
			if ($('#txtid').val() == '') {
				var urls = 'master/unit_kerja/unit_kerja_create';
			} else {
				var urls = 'master/unit_kerja/unit_kerja_update';
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
							<th class="text-center">Kode Unit Kerja</th>
							<th>Nama Unit Kerja</th>
							<th class="text-center">Nama Kelompok Kerja</th>
							<th class="text-center">Level Kelompok Kerja</th>
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
								<label class="col-md-3 control-label">Kode</label>
								<div class="col-md-9">
									<input type="text" name="txtkode" id="txtkode" class="form-control" placeholder="Kode Unit Kerja" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Nama</label>
								<div class="col-md-9">
									<input type="text" name="txtnama" id="txtnama" class="form-control" placeholder="Nama Unit Kerja" required>
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