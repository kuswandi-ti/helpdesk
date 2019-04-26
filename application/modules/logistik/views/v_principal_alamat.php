<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {		
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "logistik/principal_alamat/get_data/<?php echo $this->input->get('pid'); ?>",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[2, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Alamat 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Alamat 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Telp. 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Email 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // PIC Nama -> CP Nama 6
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // PIC Jabatan -> CP Jabatan 7
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // PIC HP -> CP HP 8
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 9
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 10
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 2, 4, 5, 6, 7, 8, 9, 10] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "5%", "targets": [9] },  // Status
				{ "width": "10%", "targets": [10] } // Action
			]
		});
		
		$(".btn-tambah").click(function() {
			$('#modal-primary-header').html('Tambah Data Alamat Principal');
			reset_input();
		});
		
		$("#cboprovinsi").on('change',function(){
			var id_provinsi = $(this).val();
			var opt = '<option selected hidden>Pilih Kab./Kota</option>';
			$.ajax({
				url: 'logistik/principal_alamat/get_kabkota/'+id_provinsi,
				dataType: 'json',
				type: 'get',
				success: function(json) {
					$.each(json, function(i, obj) {
						opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
					});
					$("#cbokabupatenkota").html(opt);
					$("#cbokabupatenkota").selectpicker('refresh');
				}
			});
		});
		
		$("body").on("click", "#table_data .btn-edit", function() {
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('.btn-edit').val();
			
			$.ajax({
				url : 'logistik/principal_alamat/principal_alamat_view',
				type: 'POST',
				data: {
					id: id
				},
				dataType: 'json',
				success : function(data) {
					$('#modal-create-edit').modal('show');
					$('#modal-primary-header').html('Edit Data Alamat Principal');
					$('#txtid').val(data.id);
					$('#cboprincipal').val(data.id_principal);
					$("#cboprincipal").selectpicker('refresh');
					$('#txtnama').val(data.nama_alamat);
					$('#txtalamat').val(data.alamat);
					$('#cboprovinsi').val(data.id_provinsi);
					$("#cboprovinsi").selectpicker('refresh');
					var id_provinsi = $('#cboprovinsi').val();
					var opt = '<option selected hidden>Pilih Kab./Kota</option>';
					$.ajax({
						url: 'logistik/principal_alamat/get_kabkota/'+id_provinsi,
						dataType: 'json',
						type: 'get',
						async: false,
						success: function(json) {
							$.each(json, function(i, obj) {
								opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
							});
							$("#cbokabupatenkota").html(opt);
							$("#cbokabupatenkota").selectpicker('refresh');							
						}
					});			
					$('#cbokabupatenkota').val(data.id_kabupatenkota);
					$("#cbokabupatenkota").selectpicker('refresh');
					$('#txtkodepos').val(data.kode_pos);
					$('#txttelepon').val(data.telepon);
					$('#txtfaks').val(data.faks);
					$('#txtemail').val(data.email);
					$('#txtwebsite').val(data.website);
					$('#txtlongitude').val(data.longitude);
					$('#txtlatitude').val(data.latitude);
					if (data.is_default == 1) {
						$("#chkdefault").prop('checked', true);
					} else {
						$("#chkdefault").prop('checked', false);
					}
					$('#txtpicnama').val(data.pic_nama);
					$('#txtpicjabatan').val(data.pic_jabatan);
					$('#txtpichp').val(data.pic_hp);
					$('#txtdeskripsi').val(data.deskripsi);
					//$('#gambar_1').val(data.id);
					//$('#gambar_2').val(data.id);
					//$('#gambar_3').val(data.id);
				},
				error: function() {
				  alert("failure");
				}
			});
		});
		
		$(".add-edit").click(function(e) {			
			var form = $('#form-create-edit')[0];
			var formData = new FormData(form);
			
			formData.append('cboprincipal', $("#cboprincipal").val());
			formData.append('cboprovinsi', $("#cboprovinsi").val());
			formData.append('cbokabupatenkota', $("#cbokabupatenkota").val());
			formData.append('chkdefault', $('#chkdefault').is(':checked') ? '1'  : '0');
			
			if ($('#txtid').val() == '') {
				var urls = 'logistik/principal_alamat/principal_alamat_create';
			} else {
				var urls = 'logistik/principal_alamat/principal_alamat_update';
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
	});
	
	function reset_input() {
		$('#txtid').val('');
		$('#cboprincipal').val('');
		$("#cboprincipal").selectpicker('refresh');
		$('#txtnama').val('');
		$('#txtalamat').val('');
		$('#cbopropinsi').val('');
		$("#cbopropinsi").selectpicker('refresh');
		$('#cbokabkota').val('');
		$("#cbokabkota").selectpicker('refresh');
		$('#txtkodepos').val('');
		$('#txtnotelp').val('');
		$('#txtnofax').val('');
		$('#txtemail').val('');
		$('#txtwebsite').val('');
		$('#txtlongitude').val('');
		$('#txtlatitude').val('');
		$("#chkdefault").prop('checked', false);
		$('#txtnamapic').val('');
		$('#txtjabatanpic').val('');
		$('#txtnohppic').val('');
		$('#txtdeskripsi').val('');
		//$('#gambar_1').val(data.id);
		//$('#gambar_2').val(data.id);
		//$('#gambar_3').val(data.id);
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
							<th>Principal</th>
							<th class="text-center">Tipe Alamat</th>
							<th>Alamat</th>
							<th class="text-center">No. Telp.</th>							
							<th class="text-center">Email</th>
							<th class="text-center">CP Nama</th>
							<th class="text-center">CP Jabatan</th>
							<th class="text-center">CP No. HP</th>
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
	<div class="modal-dialog modal-info modal-fw" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<form name="form-create-edit" id="form-create-edit" method="POST" class="form-horizontal">
			<div class="modal-content">
				<div class="modal-header">                        
					<h4 class="modal-title" id="modal-primary-header">Add / Edit Data Alamat Supplier</h4>
				</div>
				<div class="modal-body">
					<div>
						<ul class="nav nav-tabs tab-content-bordered">
							<li class="active"><a href="#tabs-data-pokok" data-toggle="tab"><span class="fa fa-institution"></span> Data Alamat</a></li>
							<li><a href="#tabs-pic" data-toggle="tab"><span class="fa fa-child"></span> Contact Person (CP)</a></li>
							<li><a href="#tabs-gambar" data-toggle="tab"><span class="fa fa-camera"></span> Gambar</a></li>
							<li><a href="#tabs-lainnya" data-toggle="tab"><span class="fa fa-puzzle-piece"></span> Lainnya</a></li>
						</ul>
						
						<div class="tab-content tab-content-bordered">
							<div class="tab-pane active" id="tabs-data-pokok">
								<div class="row">
									<input type="hidden" name="txtid" id="txtid">
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-md-3 control-label">Principal</label>
											<div class="col-md-9">
												<select id="cboprincipal" name="cboprincipal" class="bs-select" data-live-search="true">												
													<!--<option hidden>Pilih Principal</option>-->
													<?php 
														foreach($get_principal->result() as $row) {
															echo '<option value="'.$row->id_principal.'">'.$row->nama_principal.'</option>';
														}
													?>
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-md-3 control-label">Tipe Alamat</label>
											<div class="col-md-9">												
												<input type="text" name="txtnama" id="txtnama" class="form-control" placeholder="HO, Cabang, Pengiriman, Penagihan, dll" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Alamat</label>
											<div class="col-md-9">
												<textarea name="txtalamat" id="txtalamat" class="form-control" rows="3" placeholder="Alamat"></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Propinsi</label>
											<div class="col-md-9">
												<select id="cboprovinsi" name="cboprovinsi" class="bs-select" data-live-search="true">												
													<option hidden>Pilih Propinsi</option>
													<?php 
														foreach($get_propinsi->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Kab./Kota</label>
											<div class="col-md-9">
												<select id="cbokabupatenkota" name="cbokabupatenkota" class="bs-select" data-live-search="true">
													<option hidden>Pilih Kab./Kota</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Kode Pos</label>
											<div class="col-md-9">
												<input type="text" name="txtkodepos" id="txtkodepos" class="form-control" placeholder="Kode Pos">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-md-2 control-label">No. Telp.</label>
											<div class="col-md-10">
												<input type="text" name="txttelepon" id="txttelepon" class="form-control" placeholder="No. Telp.">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">No. Fax.</label>
											<div class="col-md-10">
												<input type="text" name="txtfaks" id="txtfaks" class="form-control" placeholder="No. Fax.">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">Email</label>
											<div class="col-md-10">
												<input type="text" name="txtemail" id="txtemail" class="form-control" placeholder="Email">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">Website</label>
											<div class="col-md-10">
												<input type="text" name="txtwebsite" id="txtwebsite" class="form-control" placeholder="Website">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">Longitude</label>
											<div class="col-md-10">
												<input type="text" name="txtlongitude" id="txtlongitude" class="form-control" placeholder="Longitude">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">Latitude</label>
											<div class="col-md-10">
												<input type="text" name="txtlatitude" id="txtlatitude" class="form-control" placeholder="Latitude">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-2 control-label">&nbsp;</label>
											<div class="col-md-10">
												<p class="pull-right"><input type='checkbox' name='chkdefault' id='chkdefault'> Default</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="tab-pane" id="tabs-pic">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="col-md-1 control-label">Nama</label>
											<div class="col-md-11">
												<input type="text" name="txtpicnama" id="txtpicnama" class="form-control" placeholder="Nama">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-1 control-label">Jabatan</label>
											<div class="col-md-11">
												<input type="text" name="txtpicjabatan" id="txtpicjabatan" class="form-control" placeholder="Jabatan">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-1 control-label">No. HP</label>
											<div class="col-md-11">
												<input type="text" name="txtpichp" id="txtpichp" class="form-control" placeholder="No. HP">
											</div>
										</div>
									</div>
								</div>
							</div>							
							
							<div class="tab-pane" id="tabs-gambar">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="col-md-3 control-label">Gambar 1</label>
											<div class="col-md-9">
												<input type="file" name="gambar_1" accept="image/*"
													   onchange="document.getElementById('img_1').src = window.URL.createObjectURL(this.files[0])">
												<img id="img_1" width="50%" height="50%" />
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="col-md-3 control-label">Gambar 2</label>
											<div class="col-md-9">
												<input type="file" name="gambar_2" accept="image/*"
													   onchange="document.getElementById('img_2').src = window.URL.createObjectURL(this.files[0])">
												<img id="img_2" width="50%" height="50%" />
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="col-md-3 control-label">Gambar 3</label>
											<div class="col-md-9">
												<input type="file" name="gambar_3" accept="image/*"
													   onchange="document.getElementById('img_3').src = window.URL.createObjectURL(this.files[0])">
												<img id="img_3" width="50%" height="50%" />
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="tab-pane" id="tabs-lainnya">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="col-md-1 control-label">Deskripsi</label>
											<div class="col-md-11">
												<textarea name="txtdeskripsi" id="txtdeskripsi" class="form-control" rows="5" placeholder="Deskripsi"></textarea>
											</div>											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">					
					<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary add-edit">Simpan</button>					
				</div>
			</div>
		</form>
	</div>            
</div>
<!-- End : Modal Add / Edit -->