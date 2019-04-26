<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "master/asset_data/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']],
			"aoColumns": [
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kode 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kelompok 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Deskripsi 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Pengguna 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Lokasi 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Harga 6
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Diskon 7
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Harga Beli 8
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 9
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 9] },
				{ "width": "5%", "targets": [0] },  // Kode
				{ "width": "10%", "targets": [1] },  // Nama
				{ "width": "10%", "targets": [2] },  // Kelompok
				{ "width": "30%", "targets": [3] },  // Deskripsi
				{ "width": "10%", "targets": [4] },  // Nama Pengguna
				{ "width": "10%", "targets": [5] },  // Lokasi
				{ "width": "10%", "targets": [6] },  // Harga
				{ "width": "5%", "targets": [7] },  // Diskon
				{ "width": "15%", "targets": [8] },  // Harga Beli
				{ "width": "5%", "targets": [9] },  // Action
			]
		});
		
		$(".btn-tambah").click(function() {
			$('#modal-primary-header').html('Tambah Data Master Asset');
			reset_input();
		});
		
		$("body").on("click", "#table_data .btn-edit", function() {
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('.btn-edit').val();

			$('.kast').show();
			
			$.ajax({
				url : 'master/asset_data/asset_view',
				type: 'POST',
				data: {
					id: id
				},
				dataType: 'json',
				success : function(data) {
					$('#modal-create-edit').modal('show');
					$('#modal-primary-header').html('Edit Data Master Asset');
					$('#txtid').val(data.id);
					$('#txtkode').val(data.kode);
					$('#txtkelompok').val(data.kelompok_id);
					$('#txtnama').val(data.nama);
					$('#txtdeskripsi').val(data.deskripsi);
					$('#txtharga').val(data.harga);
					if(data.diskon > 0){
						$('.txtdiskon').each(function(){
							if($(this).val() == 'Ya'){
								$(this).attr('checked', true);
								$('#txtdispot').show();
							}
						});
					}else{
						$('.txtdiskon').each(function(){
							if($(this).val() == 'Tidak'){
								$(this).attr('checked', true);
							}
						});
					}
					$('#txtjmldiskon').val(data.diskon);
					$('#txtbuydate').val(data.tgl_perolehan);
					$('#txtperoleh option').each(function(){
						if($(this).val() == data.cara_perolehan){
							$(this).attr('selected', true);
							if($(this).val() == '1'){
								$('#itxtmetode').show();
							}
						}
					});
					$('#txtmetode option').each(function(){
						if($(this).val() == data.metode_bayar){
							$(this).attr('selected', true);
							if($(this).val() == '2'){
								$('#itxtdp, #itxtperbulan, #itxtkredit').show();
							}
						}
					});
					$('#txtdp').val(data.kredit_dp);
					$('#txtperbulan').val(data.kredit_angsuran);
					$('#txtmulai').val(data.kredit_mulai);
					$('#txtselesai').val(data.kredit_hingga);
					$('#txtuser').val(data.nama_pengguna);
					$('#txtlokasi').val(data.lokasi);
					$('#txtkondisi').val(data.kondisi);
					$('#txtdeskripsi').val(data.deskripsi);
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
				var urls = 'master/asset_data/asset_create';
			} else {
				var urls = 'master/asset_data/asset_update';
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
		// Date Format
		$('.datepicker').datetimepicker({
			// dateFormat: 'dd-mm-yy',
			format: 'DD-MM-YYYY',
		});
		// Show - Hide Field
		$('#txtdispot, #itxtmetode, #itxtdp, #itxtperbulan, #itxtkredit').css('display','none');
		$('#txtperoleh').change(function(){
			var val = $(this).val();
			if(val == '1'){
				$('#itxtmetode').slideDown();
				$('#txtmetode').attr('required', true);
			}else{
				$('#itxtmetode').slideUp();
				$('#txtmetode').attr('required', false);
				$('#txtmetode, #txtdp, #txtperbulan, #txtmulai, #txtselesai').val('');
			}
		});
		$('.txtdiskon').change(function(){
			var val = $(this).val();
			if(val == 'Ya'){
				$('#txtdispot').slideDown();
				$('#txtjmldiskon').attr('required', true);
			}else{
				$('#txtdispot').slideUp();
				$('#txtjmldiskon').attr('required', false);
				$('#txtjmldiskon, #txtpotongan').val('');
			}
		});
		$('#txtjmldiskon').keyup(function(){
			var val 	= $(this).val();
			// var harga 	= $('#txtharga').val();
			if(val >= 1 && val <= 99){
				var harga 	= $('#txtharga').val();
				var hitung 	= (harga * val) / 100;
				$('#txtpotongan').val(hitung);
			}else{
				$('#txtpotongan').val('');
			}
		});
		$('#txtmetode').change(function(){
			var val = $(this).val();
			if(val == '2'){
				$('#itxtdp, #itxtperbulan, #itxtkredit').slideDown();
				$('#txtdp, #txtperbulan, #txtmulai, #txtselesai').attr('required', true);
			}else{
				$('#itxtdp, #itxtperbulan, #itxtkredit').slideUp();
				$('#txtdp, #txtperbulan, #txtmulai, #txtselesai').attr('required', false);
				$('#txtdp, #txtperbulan, #txtmulai, #txtselesai').val('');
			}
		});
	});
	
	
	function reset_input() {
		$('#txtid').val('');
		$('#txtnama').val('');
		$('#txtkelompok').val('');
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
							<th class="text-center">Kode</th>
							<th>Nama</th>
							<th>Kelompok</th>
							<th>Deskripsi</th>
							<th>Nama Pengguna</th>
							<th>Lokasi</th>
							<th>Harga</th>
							<th>%</th>
							<th>Harga Beli</th>
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
				<div class="modal-body" style="height:600px; overflow-y:auto;">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group kast">
								<label class="col-md-3 control-label">Kode Asset</label>
								<div class="col-md-9">
									<input type="hidden" name="txtid" id="txtid">
									<input type="text" name="txtkode" id="txtkode" class="form-control" placeholder="Kode Asset" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Nama</label>
								<div class="col-md-9">
									<input type="text" name="txtnama" id="txtnama" class="form-control" placeholder="Nama Asset" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Kelompok</label>
								<div class="col-md-9">
									<select name="txtkelompok" id="txtkelompok" class="form-control">
										<option value="">- Pilih Kelompok -</option>
								<?php
									foreach($kel_asset->result() as $kas){
								?>
										<option value="<?php echo $kas->id; ?>"><?php echo $kas->nama; ?></option>
								<?php
									}
								?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Harga</label>
								<div class="col-md-9">
									<div class="input-group">
										<span class="input-group-addon">Rp.</span>
										<input type="text" name="txtharga" id="txtharga" class="form-control" placeholder="Harga Asset" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Diskon</label>
								<div class="col-md-9" style="margin-top:1%;">
									<label style="margin-right:5%;">
									<input type="radio" name="txtdiskon" class="txtdiskon" value="Tidak" required>
									Tidak</label>
									<label>
										<input type="radio" name="txtdiskon" class="txtdiskon" value="Ya" required>
									Ya</label>
								</div>
							</div>
							<div class="form-group" id="txtdispot">
								<label class="col-md-3 control-label">Jml Diskon</label>
								<div class="col-md-4" style="padding-right:0;">
									<div class="input-group">
										<input type="number" name="txtjmldiskon" id="txtjmldiskon" class="form-control" min="1" max="99">
										<span class="input-group-addon">%</span>
									</div>
								</div>
								<label class="col-md-1 control-label">=</label>
								<div class="col-md-4" style="padding-left:0;">
									<div class="input-group">
										<span class="input-group-addon">Rp.</span>
										<input type="text" name="txtpotongan" id="txtpotongan" class="form-control" placeholder="Potongan">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Tgl. Perolehan</label>
								<div class="col-md-9">
									<input type="text" name="txtbuydate" id="txtbuydate" class="form-control datepicker">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Diperoleh</label>
								<div class="col-md-9">
									<select class="form-control" name="txtperoleh" id="txtperoleh">
										<option value="">- Pilih -</option>
										<option value="1">Beli</option>
										<option value="2">Hadiah</option>
										<option value="9">Diskon</option>
									</select>
								</div>
							</div>
							<div class="form-group" id="itxtmetode">
								<label class="col-md-3 control-label">Metode Bayar</label>
								<div class="col-md-9">
									<select class="form-control" name="txtmetode" id="txtmetode">
										<option value="">- Pilih -</option>
										<option value="1">Tunai</option>
										<option value="2">Kredit</option>
									</select>
								</div>
							</div>
							<div class="form-group" id="itxtdp">
								<label class="col-md-3 control-label">DP</label>
								<div class="col-md-9">
									<div class="input-group">
										<span class="input-group-addon">Rp.</span>
										<input type="number" name="txtdp" id="txtdp" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group" id="itxtperbulan">
								<label class="col-md-3 control-label">Angsuran Perbulan</label>
								<div class="col-md-9">
									<div class="input-group">
										<span class="input-group-addon">Rp.</span>
										<input type="number" name="txtperbulan" id="txtperbulan" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group" id="itxtkredit">
								<label class="col-md-3 control-label">Tgl. Kredit</label>
								<div class="col-md-4" style="padding-right:0;">
									<input type="text" name="txtmulai" id="txtmulai" class="form-control datepicker" placeholder="Tgl. Mulai">
								</div>
								<label class="col-md-1 control-label">s/d</label>
								<div class="col-md-4" style="padding-left:0;">
									<input type="text" name="txtselesai" id="txtselesai" class="form-control datepicker" placeholder="Tgl. Selesai">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Deskripsi</label>
								<div class="col-md-9">
									<textarea name="txtdeskripsi" id="txtdeskripsi" class="form-control" rows="10" placeholder="Deskripsi Asset"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Pengguna</label>
								<div class="col-md-9">
									<input type="text" name="txtuser" id="txtuser" class="form-control" placeholder="Pengguna Asset" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Lokasi</label>
								<div class="col-md-9">
									<input type="text" name="txtlokasi" id="txtlokasi" class="form-control" placeholder="Lokasi Asset" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Kondisi</label>
								<div class="col-md-9">
									<div class="input-group">
										<input type="number" name="txtkondisi" id="txtkondisi" class="form-control" placeholder="Kondisi Asset" required>
										<span class="input-group-addon">%</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<!-- p class="pull-right"><input type='checkbox' name='chkaktif' id='chkaktif'> Aktif</p-->
					<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary add-edit">Simpan</button>					
				</div>
			</div>
		</form>
	</div>            
</div>
<!-- End : Modal Add / Edit -->
