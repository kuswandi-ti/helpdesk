<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "logistik/principal/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tipe Pembayaran 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // NPWP 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Rekening 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Rekening 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Cabang Rekening 6
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Deskripsi 7
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 8
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 9
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 2, 3, 4, 8, 9] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "5%", "targets": [8] },  // Status
				{ "width": "10%", "targets": [9] } // Action
			]
		});
		
		$(".btn-tambah").click(function() {
			$('#modal-primary-header').html('Tambah Data Principal');
			reset_input();
		});
		
		$("body").on("click", "#table_data .btn-edit", function() {
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('.btn-edit').val();
			
			$.ajax({
				url : 'logistik/principal/principal_view',
				type: 'POST',
				data: {
					id: id
				},
				dataType: 'json',
				success : function(data) {
					$('#modal-create-edit').modal('show');
					$('#modal-primary-header').html('Edit Data Principal');
					$('#txtid').val(data.id_principal);
					$('#txtnama').val(data.nama_principal);
					$('#txtnpwp').val(data.npwp);
					$('#cbotop').val(data.id_tipe_pembayaran);
					$("#cbotop").selectpicker('refresh');
					$('#txtnorek').val(data.no_rekening);
					$('#txtnamarek').val(data.nama_rekening);
					$('#txtcabangrek').val(data.cabang_rekening);
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
			
			formData.append('cbotop', $("#cbotop").val());
			formData.append('chkaktif', $('#chkaktif').is(':checked') ? '1'  : '0');
			
			if ($('#txtid').val() == '') {
				var urls = 'logistik/principal/principal_create';
			} else {
				var urls = 'logistik/principal/principal_update';
			}
			
			$.ajax({
				type: 'POST',
				url: urls,
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					//reset_input();
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
			//e.preventDefault();
		});
		
		$("body").on("click", "#table_data .btn-alamat", function() {
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('.btn-alamat').val();
			window.open('logistik/principal_alamat/?pid='+id, '_blank');
		});
	});
	
	function reset_input() {
		$('#txtid').val('');
		$('#txtnama').val('');
		$('#txtnpwp').val('');
		$('#cbotop').val('');
		$("#cbotop").selectpicker('refresh');
		$('#txtnorek').val('');
		$('#txtnamarek').val('');
		$('#txtcabangrek').val('');
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
							<th class="text-center">Tipe Bayar</th>
							<th class="text-center">NPWP</th>
							<th class="text-center">No. Rekening</th>
							<th class="text-center">Nama Rekening</th>
							<th class="text-center">Cabang Rekening</th>
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
	<div class="modal-dialog modal-info modal-lg" role="document">
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
								<label class="col-md-2 control-label">Nama Principal</label>
								<div class="col-md-10">
									<input type="hidden" name="txtid" id="txtid">
									<input type="text" name="txtnama" id="txtnama" class="form-control" placeholder="Nama Principal" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">NPWP</label>
								<div class="col-md-10">
									<input type="text" name="txtnpwp" id="txtnpwp" class="form-control" placeholder="NPWP">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Term of Payment</label>
								<div class="col-md-10">
									<select id="cbotop" name="cbotop" class="bs-select" data-live-search="true">												
										<option hidden>Pilih Tipe Pembayaran</option>
										<?php 
											foreach($get_tipe_pembayaran->result() as $row) {
												echo '<option value="'.$row->id.'">'.$row->tipe_pembayaran.'</option>';
											}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">No. Rekening</label>
								<div class="col-md-10">
									<input type="text" name="txtnorek" id="txtnorek" class="form-control" placeholder="No. Rekening">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Nama Rekening</label>
								<div class="col-md-10">
									<input type="text" name="txtnamarek" id="txtnamarek" class="form-control" placeholder="Nama di Rekening">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Cabang Rekening</label>
								<div class="col-md-10">
									<input type="text" name="txtcabangrek" id="txtcabangrek" class="form-control" placeholder="Cabang Rekening">
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