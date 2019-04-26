<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$("#chkaktif").prop('checked', true);
		reset_input();
		
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "logistik/produk/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kode Produk 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Produk 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Perbekalan 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Sediaan 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kadar Isi 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Satuan 6
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kemasan 7
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Principal 8
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 9
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 10
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 1, 3, 4, 6, 7, 9, 10] },
				{ "className": "text-right", "targets": [5] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "5%", "targets": [9] },  // Status
				{ "width": "10%", "targets": [10] } // Action
			]
		});
		
		$("#cboperbekalanproduk").on('change',function(){
			var opt = '<option selected hidden>Pilih Kelompok Produk</option>';
			$.ajax({
				url: 'logistik/produk/get_data_kelompok',
				dataType: 'json',
				type: 'post',
				data: {
					id_perbekalan: $(this).val()
				},
				success: function(json) {
					$.each(json, function(i, obj) {
						opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
					});
					$("#cbokelompokproduk").html(opt);
					$("#cbokelompokproduk").selectpicker('refresh');
					
					$('#cbogolonganproduk').html('<option selected hidden>Pilih Golongan Produk</option>').selectpicker('refresh');
					$('#cbojenisproduk').html('<option selected hidden>Pilih Jenis Produk</option>').selectpicker('refresh');
				}
			});
		});
		
		$("#cbokelompokproduk").on('change',function(){
			var opt = '<option selected hidden>Pilih Golongan Produk</option>';
			$.ajax({
				url: 'logistik/produk/get_data_golongan',
				dataType: 'json',
				type: 'post',
				data: {
					id_kelompok: $(this).val()
				},
				success: function(json) {
					$.each(json, function(i, obj) {
						opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
					});
					$("#cbogolonganproduk").html(opt);
					$("#cbogolonganproduk").selectpicker('refresh');
					
					var selectedText = $('#cbokelompokproduk').find('option:selected').text();
					if (selectedText == 'Obat Kimia') {
						var opts = '<option selected hidden>Pilih Kelas Terapi</option>';
						$.ajax({
							url: 'logistik/produk/get_data_kelas_terapi',
							dataType: 'json',
							type: 'post',
							success: function(jsons) {
								$.each(jsons, function(i, obj) {
									opts += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
								});
								$("#cboklasifikasi").html(opts);
								$("#cboklasifikasi").selectpicker('refresh');
							}
						});
					} else {
						$('#cboklasifikasi').html('<option selected hidden>Pilih Kelas Terapi</option>').selectpicker('refresh');
					}
					
					var optss = '<option hidden disabled>Pilih Lokasi Penyimpanan Produk</option>';
					$.ajax({
						url: 'logistik/produk/get_data_lokasi',
						dataType: 'json',
						type: 'post',
						data: {
							id_kelompok_s: $("#cbokelompokproduk").val()
						},
						success: function(jsonss) {
							$.each(jsonss, function(i, obj) {
								optss += "<option value='"+obj.id+"'>"+obj.kode+"</option>";
							});
							$("#cbolokasiproduk").html(optss);
							$("#cbolokasiproduk").selectpicker('refresh');
						}
					});
					
					$('#cbojenisproduk').html('<option selected hidden>Pilih Jenis Produk</option>').selectpicker('refresh');
				}
			});
		});
		
		$("#cbogolonganproduk").on('change',function(){
			var opt = '<option selected hidden>Pilih Jenis Produk</option>';
			$.ajax({
				url: 'logistik/produk/get_data_jenis',
				dataType: 'json',
				type: 'post',
				data: {
					id_golongan: $(this).val()
				},
				success: function(json) {
					$.each(json, function(i, obj) {
						opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
					});
					$("#cbojenisproduk").html(opt);
					$("#cbojenisproduk").selectpicker('refresh');
				}
			});
		});
		
		$(".btn-tambah").click(function() {
			reset_input();
			$('input[name="txtkode"]').prop('readonly', false);
			$('#modal-primary-header').html('Tambah Data Produk');
		});
		
		$("body").on("click", "#table_data .btn-edit", function() {
			reset_input();
			
			$('input[name="txtkode"]').prop('readonly', true);
			
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('.btn-edit').val();
			var supplier = "";
			
			$.ajax({
				url : 'logistik/produk/produk_view',
				type: 'POST',
				data: {
					id: id
				},
				dataType: 'json',
				success : function(data) {
					$('#modal-create-edit').modal('show');
					$('#modal-primary-header').html('Edit Data Produk');
					$('#txtid').val(data.id_produk);
					$('#txtkode').val(data.kode_produk);
					$('#txtnama').val(data.nama_produk);
					$('#cbobentuksediaan').selectpicker('val', data.id_produk_bentuk_sediaan);
					$('#cbosatuanproduk').selectpicker('val', data.id_kadar_satuan);
					$('#txtkadarisi').val(data.kadar_isi);
					$('#cbofungsiproduk').selectpicker('val', data.id_fungsi);
					if (data.activated == 1) {
						$("#chkaktif").prop('checked', true);
					} else {
						$("#chkaktif").prop('checked', false);
					}
					$('#txtkomposisi').val(data.komposisi);
					$('#txtindikasi').val(data.indikasi);
					$('#txtdeskripsi').val(data.deskripsi);
					
					$('#cbokemasanutama').selectpicker('val', data.id_kemasan);
					$('#cbokemasanprimer').selectpicker('val', data.id_kemasan_primer);
					$('#txtisiprimer').val(data.isi_kemasan_primer);
					$('#cbokemasansekunder').selectpicker('val', data.id_kemasan_sekunder);
					$('#txtisisekunder').val(data.isi_kemasan_sekunder);
					$('#cbokemasantersier').selectpicker('val', data.id_kemasan_tersier);
					$('#txtisitersier').val(data.isi_kemasan_tersier);
					
					$('#cboperbekalanproduk').selectpicker('val', data.id_produk_perbekalan);
					var v_id_perbekalan = $('#cboperbekalanproduk').val();
					var opt = '<option selected hidden>Pilih Kelompok Produk</option>';
					$.ajax({
						url: 'logistik/produk/get_data_kelompok',
						dataType: 'json',
						type: 'post',
						data: { id_perbekalan: v_id_perbekalan },
						async: false,
						success: function(json) {
							$.each(json, function(i, obj) {
								opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
							});
							$("#cbokelompokproduk").html(opt);
							$("#cbokelompokproduk").selectpicker('refresh');							
						}
					});
					$('#cbokelompokproduk').selectpicker('val', data.id_produk_kelompok);
					var v_id_kelompok = $('#cbokelompokproduk').val();
					var opt = '<option selected hidden>Pilih Golongan Produk</option>';
					$.ajax({
						url: 'logistik/produk/get_data_golongan',
						dataType: 'json',
						type: 'post',
						data: { id_kelompok: v_id_kelompok },
						async: false,
						success: function(json) {
							$.each(json, function(i, obj) {
								opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
							});
							$("#cbogolonganproduk").html(opt);
							$("#cbogolonganproduk").selectpicker('refresh');							
						}
					});
					$('#cbogolonganproduk').selectpicker('val', data.id_produk_golongan);
					var v_id_golongan = $('#cbogolonganproduk').val();
					var opt = '<option selected hidden>Pilih Jenis Produk</option>';
					$.ajax({
						url: 'logistik/produk/get_data_jenis',
						dataType: 'json',
						type: 'post',
						data: { id_golongan: v_id_golongan },
						async: false,
						success: function(json) {
							$.each(json, function(i, obj) {
								opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
							});
							$("#cbojenisproduk").html(opt);
							$("#cbojenisproduk").selectpicker('refresh');							
						}
					});
					$('#cbojenisproduk').selectpicker('val', data.id_produk_jenis);
					var selectedText = $('#cbokelompokproduk').find('option:selected').text();
					if (selectedText == 'Obat Kimia') {
						var opt = '<option selected hidden>Pilih Kelas Terapi</option>';
						$.ajax({
							url: 'logistik/produk/get_data_kelas_terapi',
							dataType: 'json',
							type: 'post',
							async: false,
							success: function(json) {
								$.each(json, function(i, obj) {
									opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
								});
								$("#cboklasifikasi").html(opt);
								$("#cboklasifikasi").selectpicker('refresh');							
							}
						});
						$('#cboklasifikasi').selectpicker('val', data.id_klasifikasi);
					} else {
						$('#cboklasifikasi').html('<option selected hidden>Pilih Kelas Terapi</option>').selectpicker('refresh');
					}					
					
					$('#cboprincipal').selectpicker('val', data.id_principal);
					$('#cbosupplier').selectpicker();
					supplier = data.produk_supplier.split (',');
					$('#cbosupplier').selectpicker('val', supplier);
					$('#cbosupplier').selectpicker('refresh');
					
					$('#txtminstok').val(data.min_stok);
					$('#txtmaxstok').val(data.max_stok);
					$('#txtexpiredlimit').val(data.expired_limit);
					$('#txtleadtime').val(data.lead_time);
					
					$('#txthargajualmin').val(data.harga_jual_min);
					$('#txthargajualmax').val(data.harga_jual_max);
					$('#txthargabeli').val(data.harga_beli);
					$('#txtdiskonmax').val(data.diskon_max);
					
					$("#image_1").attr("src", data.gambar_1);
					$("#txtimage_1").val(data.gambar_1);
					$("#image_2").attr("src", data.gambar_2);
					$("#txtimage_2").val(data.gambar_2);
					$("#image_3").attr("src", data.gambar_3);
					$("#txtimage_3").val(data.gambar_3);
					
					var opt = '<option hidden disabled>Pilih Lokasi Penyimpanan Produk</option>';
					$.ajax({
						url: 'logistik/produk/get_data_lokasi',
						dataType: 'json',
						type: 'post',
						data: { id_kelompok_s: v_id_kelompok },
						async: false,
						success: function(json) {
							$.each(json, function(i, obj) {
								opt += "<option value='"+obj.id+"'>"+obj.kode+"</option>";
							});
							$("#cbolokasiproduk").html(opt);
							$("#cbolokasiproduk").selectpicker('refresh');							
						}
					});
					$('#cbolokasiproduk').selectpicker();
					lokasi = data.produk_lokasi.split (',');
					$('#cbolokasiproduk').selectpicker('val', lokasi);
					$('#cbolokasiproduk').selectpicker('refresh');
				},
				error: function() {
				  alert("failure");
				}
			});
		});

		$(".add-edit").click(function(e) {
			var form = $('#form-create-edit')[0];
			var formData = new FormData(form);
			
			formData.append('cbobentuksediaan', $("#cbobentuksediaan").val());
			formData.append('cbosatuanproduk', $("#cbosatuanproduk").val());
			formData.append('cbofungsiproduk', $("#cbofungsiproduk").val());
			formData.append('chkaktif', $('#chkaktif').is(':checked') ? '1'  : '0');
			
			formData.append('cbokemasanutama', $("#cbokemasanutama").val());
			formData.append('cbokemasanprimer', $("#cbokemasanprimer").val());			
			formData.append('cbokemasansekunder', $("#cbokemasansekunder").val());
			formData.append('cbokemasantersier', $("#cbokemasantersier").val());
			
			formData.append('cboperbekalanproduk', $("#cboperbekalanproduk").val());
			formData.append('cbokelompokproduk', $("#cbokelompokproduk").val());
			formData.append('cbogolonganproduk', $("#cbogolonganproduk").val());
			formData.append('cbojenisproduk', $("#cbojenisproduk").val());
			formData.append('cboklasifikasi', $("#cboklasifikasi").val());
			
			formData.append('cboprincipal', $("#cboprincipal").val());
			formData.append('cbosupplier', $("#cbosupplier").val());
			
			formData.append('cbolokasiproduk', $("#cbolokasiproduk").val());
			
			if ($('#txtid').val() == '') {
				var urls = 'logistik/produk/produk_create';
				var id = '';
				$.ajax({
					type: 'post',
					url: 'logistik/produk/produk_exists',
					data: { nama: $('#txtnama').val() },
					success: function(data) {
						if (data > 0) {
							alert("Nama produk sudah ada. Isi dengan nama produk yang lain.");
						} else {
							$.ajax({
								type: 'POST',
								url: urls,
								data: formData,
								cache: false,
								contentType: false,
								processData: false,
								success: function(result) {
									reset_input();
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
							e.preventDefault();
						}
					}
				});
			} else {
				var url = 'logistik/produk/produk_update';
				var id = $('#txtid').val();
				$.ajax({
					type: 'POST',
					url: url,
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					success: function(result) {
						reset_input();
						$('#modal-create-edit').modal('hide');
						/*window.setTimeout(
							function() {
								location.reload(true)
							}, 0
						);*/
					},
					error: function() {
						alert("failure");
					}
				});
				e.preventDefault();
			}
		});
	});
	
	function reset_input() {
		$('#txtid').val('');
		$('#txtkode').val('');
		$('#txtnama').val('');
		$('#cbobentuksediaan').val("1").change();
		$('#cbosatuanproduk').val("1").change();
		$('#txtkadarisi').val('0');
		$('#cbofungsiproduk').val("15").change();
		$("#chkaktif").prop('checked', true);
		$('#txtkomposisi').val('');
		$('#txtindikasi').val('');
		$('#txtdeskripsi').val('');
		
		$('#cbokemasanutama').val("1").change();
		$('#cbokemasanprimer').val("0").change();
		$('#txtisiprimer').val(0);
		$('#cbokemasansekunder').val("0").change();
		$('#txtisisekunder').val(0);
		$('#cbokemasantersier').val("0").change();
		$('#txtisitersier').val(0);
		
		$('#cboperbekalanproduk').val("1").change();
		$('#cbokelompokproduk').val("0").change();
		$('#cbogolonganproduk').val("0").change();
		$('#cbojenisproduk').val("0").change();
		$('#cboklasifikasi').val("0").change();
		
		$('#cboprincipal').val("22").change();
		$('#cbosupplier').val('48').change();
		
		$('#txtminstok').val(0);
		$('#txtmaxstok').val(0);
		$('#txtexpiredlimit').val(0);
		$('#txtleadtime').val(0);
		
		$('#txthargajualmin').val(0);
		$('#txthargajualmax').val(0);
		$('#txthargabeli').val(0);
		$('#txtdiskonmax').val(0);
		
		$("#gambar_1").val("");
		$("#gambar_2").val("");
		$("#gambar_3").val("");
		
		$('#cbolokasiproduk').val('1').change();
	}
	
	var _URL = window.URL || window.webkitURL;
	
	function readURL1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();			
            reader.onload = function (e) {
                $('#image_1')
                    .attr('src', e.target.result)
                    .width(250)
                    .height(400);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
	
	function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();			
            reader.onload = function (e) {
                $('#image_2')
                    .attr('src', e.target.result)
                    .width(250)
                    .height(400);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
	
	function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();			
            reader.onload = function (e) {
                $('#image_3')
                    .attr('src', e.target.result)
                    .width(250)
                    .height(400);
            };
            reader.readAsDataURL(input.files[0]);
        }
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
							<th class="text-center">Kode Produk</th>							
							<th>Nama Produk</th>
							<th class="text-center">Perbekalan</th>
							<th class="text-center">Sediaan</th>
							<th class="text-right">Kadar Isi</th>
							<th class="text-center">Satuan</th>
							<th class="text-center">Kemasan</th>
							<th>Principal</th>
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
	<div class="modal-dialog modal-primary modal-fw" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<form name="form-create-edit" id="form-create-edit" method="POST" class="form-horizontal">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modal-primary-header"></h4>
				</div>
				<div class="modal-body">
					<!-- Tab -->
					<div>
						<ul class="nav nav-tabs tab-content-bordered">
							<li class="active"><a href="#tabs-data-pokok" data-toggle="tab"><span class="fa fa-cube"></span> Data Pokok</a></li>
							<li><a href="#tabs-kemasan" data-toggle="tab"><span class="fa fa-folder-open"></span> Kemasan</a></li>
							<li><a href="#tabs-perbekalan" data-toggle="tab"><span class="fa fa-bitbucket"></span> Perbekalan Farmasi</a></li>
							<li><a href="#tabs-mitra-usaha" data-toggle="tab"><span class="fa fa-link"></span> Mitra Usaha</a></li>
							<li><a href="#tabs-mmsl" data-toggle="tab"><span class="fa fa-area-chart"></span> Min Max Stok Level</a></li>
							<li><a href="#tabs-harga" data-toggle="tab"><span class="fa fa-star"></span> Harga</a></li>
							<li><a href="#tabs-gambar" data-toggle="tab"><span class="fa fa-camera"></span> Gambar</a></li>
							<li><a href="#tabs-lokasi" data-toggle="tab"><span class="fa fa-location-arrow"></span> Lokasi Penyimpanan</a></li>
						</ul>
						<div class="tab-content tab-content-bordered">
							<div class="tab-pane active" id="tabs-data-pokok">
								<div class="row">
									<div class="col-md-6">
										<div class="block">											
											<div class="form-group">
												<label class="col-md-4 control-label" style="text-align: right">Kode  :</label>
												<div class="col-md-8">
													<input type="hidden" name="txtid" id="txtid">
													<input type="text" name="txtkode" id="txtkode" class="form-control" placeholder="Kode Produk" required>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label" style="text-align: right">Nama  :</label>
												<div class="col-md-8">
													<input type="text" name="txtnama" id="txtnama" class="form-control" placeholder="Nama Produk" required>
												</div>
											</div>																						
											<div class="form-group">
												<label class="col-md-4 control-label" style="text-align: right">Bentuk Sediaan  :</label>
												<div class="col-md-8">
													<select name='cbobentuksediaan' id='cbobentuksediaan' class='form-control bs-select' data-live-search='true'>
														<?php
															if ($get_bentuk_sediaan->num_rows() > 0) {
																foreach ($get_bentuk_sediaan->result() as $row) {
																	echo "<option value='".$row->id."'>".$row->nama."</option>";
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label" style="text-align: right">Satuan  :</label>
												<div class="col-md-8">
													<select name='cbosatuanproduk' id='cbosatuanproduk' class='form-control bs-select' data-live-search='true'>
														<?php
															if ($get_satuan_produk->num_rows() > 0) {
																foreach ($get_satuan_produk->result() as $row) {
																	echo "<option value='".$row->id."'>".$row->nama."</option>";
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label" style="text-align: right">Kadar Isi  :</label>
												<div class="col-md-8">
													<input type="text" name="txtkadarisi" id="txtkadarisi" class="form-control" placeholder="0" style="text-align: right" onkeypress="return hanya_angka(event)" required>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label" style="text-align: right">Fungsi  :</label>
												<div class="col-md-8">
													<select name='cbofungsiproduk' id='cbofungsiproduk' class='form-control bs-select' data-live-search='true'>
														<?php
															if ($get_fungsi_produk->num_rows() > 0) {
																foreach ($get_fungsi_produk->result() as $row) {
																	echo "<option value='".$row->id."'>".$row->nama."</option>";
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label" style="text-align: right">&nbsp;</label>
												<div class="col-md-8">
													<input type='checkbox' name='chkaktif' id='chkaktif' value="checked"> Aktif
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="block">
											<div class="form-group">
												<label class="col-md-4 control-label" style="text-align: right">Komposisi  :</label>
												<div class="col-md-8">
													<textarea name="txtkomposisi" id="txtkomposisi" class="form-control" rows="4" placeholder="Komposisi"></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label" style="text-align: right">Indikasi  :</label>
												<div class="col-md-8">
													<textarea name="txtindikasi" id="txtindikasi" class="form-control" rows="4" placeholder="Indikasi"></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label" style="text-align: right">Deskripsi  :</label>
												<div class="col-md-8">
													<textarea name="txtdeskripsi" id="txtdeskripsi" class="form-control" rows="5" placeholder="Deskripsi"></textarea>
												</div>
											</div>											
										</div>										
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tabs-kemasan">							
								<br />
								<div class="form-group">
                                    <label class="col-md-2 control-label" style="text-align: right">Kemasan Utama  :</label>
                                    <div class="col-md-10">
                                        <select name='cbokemasanutama' id='cbokemasanutama' class='form-control bs-select' data-live-search='true'>											
											<?php
												if ($get_kemasan_produk->num_rows() > 0) {
													foreach ($get_kemasan_produk->result() as $row) {
														echo "<option value='".$row->id."'>".$row->nama."</option>";
													}
												}
											?>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-md-2 control-label" style="text-align: right">Kemasan Primer  :</label>
                                    <div class="col-md-4">
                                        <select name='cbokemasanprimer' id='cbokemasanprimer' class='form-control bs-select' data-live-search='true'>
											<option hidden select value="0">Pilih Kemasan Produk</option>
											<?php
												if ($get_kemasan_produk->num_rows() > 0) {
													foreach ($get_kemasan_produk->result() as $row) {
														echo "<option value='".$row->id."'>".$row->nama."</option>";
													}
												}
											?>
										</select>
                                    </div>
									<label class="col-md-2 control-label" style="text-align: right">Isi Kemasan Primer  :</label>
                                    <div class="col-md-4">
                                        <input type="text" name="txtisiprimer" id="txtisiprimer" class="form-control" placeholder="0" style="text-align: right" onkeypress="return hanya_angka(event)" required>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-md-2 control-label" style="text-align: right">Kemasan Sekunder  :</label>
                                    <div class="col-md-4">
                                        <select name='cbokemasansekunder' id='cbokemasansekunder' class='form-control bs-select' data-live-search='true'>
											<option hidden select value="0">Pilih Kemasan Produk</option>
											<?php
												if ($get_kemasan_produk->num_rows() > 0) {
													foreach ($get_kemasan_produk->result() as $row) {
														echo "<option value='".$row->id."'>".$row->nama."</option>";
													}
												}
											?>
										</select>
                                    </div>
									<label class="col-md-2 control-label" style="text-align: right">Isi Kemasan Sekunder  :</label>
                                    <div class="col-md-4">
                                        <input type="text" name="txtisisekunder" id="txtisisekunder" class="form-control" placeholder="0" style="text-align: right" onkeypress="return hanya_angka(event)" required>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-md-2 control-label" style="text-align: right">Kemasan Tersier  :</label>
                                    <div class="col-md-4">
                                        <select name='cbokemasantersier' id='cbokemasantersier' class='form-control bs-select' data-live-search='true'>
											<option hidden select value="0">Pilih Kemasan Produk</option>
											<?php
												if ($get_kemasan_produk->num_rows() > 0) {
													foreach ($get_kemasan_produk->result() as $row) {
														echo "<option value='".$row->id."'>".$row->nama."</option>";
													}
												}
											?>
										</select>
                                    </div>
									<label class="col-md-2 control-label" style="text-align: right">Isi Kemasan Tersier  :</label>
                                    <div class="col-md-4">
                                        <input type="text" name="txtisitersier" id="txtisitersier" class="form-control" placeholder="0" style="text-align: right" onkeypress="return hanya_angka(event)" required>
                                    </div>
                                </div>								
							</div>
							<div class="tab-pane" id="tabs-perbekalan">							
								<br />
								<div class="form-group">                                    
									<label class="col-md-2 control-label" style="text-align: right">Perbekalan  :</label>
									<div class="col-md-10">
										<select name='cboperbekalanproduk' id='cboperbekalanproduk' class='form-control bs-select' data-live-search='true'>
											<option hidden>Pilih Perbekalan Produk</option>
											<?php
												if ($get_perbekalan_produk->num_rows() > 0) {
													foreach ($get_perbekalan_produk->result() as $row) {
														echo "<option value='".$row->id."'>".$row->nama."</option>";
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="form-group">                                    
									<label class="col-md-2 control-label" style="text-align: right">Kelompok  :</label>
									<div class="col-md-10">
										<select name='cbokelompokproduk' id='cbokelompokproduk' class='form-control bs-select' data-live-search='true'>
											<option hidden>Pilih Kelompok Produk</option>
										</select>
									</div>
								</div>
								<div class="form-group">                                    
									<label class="col-md-2 control-label" style="text-align: right">Golongan  :</label>
									<div class="col-md-10">
										<select name='cbogolonganproduk' id='cbogolonganproduk' class='form-control bs-select' data-live-search='true'>
											<option hidden>Pilih Golongan Produk</option>
										</select>
									</div>
								</div>
								<div class="form-group">                                    
									<label class="col-md-2 control-label" style="text-align: right">Jenis  :</label>
									<div class="col-md-10">
										<select name='cbojenisproduk' id='cbojenisproduk' class='form-control bs-select' data-live-search='true'>
											<option hidden>Pilih Jenis Produk</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" style="text-align: right">Kelas Terapi  :</label>
									<div class="col-md-10">
										<select name='cboklasifikasi' id='cboklasifikasi' class='form-control bs-select' data-live-search='true'>
											<option hidden>Pilih Kelas Terapi</option>
										</select>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tabs-mitra-usaha">							
								<br />
								<div class="form-group">
                                    <label class="col-md-2 control-label" style="text-align: right">Principal  :</label>
                                    <div class="col-md-10">
                                        <select name='cboprincipal' id='cboprincipal' class='form-control bs-select' data-live-search='true'>
											<?php
												if ($get_principal->num_rows() > 0) {
													foreach ($get_principal->result() as $row) {
														echo "<option value='".$row->id."'>".$row->nama."</option>";
													}
												}
											?>
										</select>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-md-2 control-label" style="text-align: right">Supplier  :</label>
                                    <div class="col-md-10">
                                        <select name='cbosupplier[]' id='cbosupplier' class='form-control bs-select' data-live-search='true' multiple="multiple">
											<option hidden disabled>Pilih Supplier</option>
											<?php
												if ($get_supplier->num_rows() > 0) {
													foreach ($get_supplier->result() as $row) {
														echo "<option value='".$row->id."'>".$row->nama."</option>";
													}
												}
											?>
										</select>
                                    </div>
                                </div>
							</div>
							<div class="tab-pane" id="tabs-mmsl">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-md-4 control-label" style="text-align: right">Minimal Stok  :</label>
											<div class="col-md-8">
												<input type="text" name="txtminstok" id="txtminstok" class="form-control" placeholder="Minimal Stok" style="text-align: right" onkeypress="return hanya_angka(event)" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label" style="text-align: right">Maksimal Stok  :</label>
											<div class="col-md-8">
												<input type="text" name="txtmaxstok" id="txtmaxstok" class="form-control" placeholder="Maksimal Stok" style="text-align: right" onkeypress="return hanya_angka(event)" required>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="col-md-4 control-label" style="text-align: right">Expired Limit (bulan)  :</label>
											<div class="col-md-8">
												<input type="text" name="txtexpiredlimit" id="txtexpiredlimit" class="form-control" placeholder="Expired Limit (bulan)" style="text-align: right" onkeypress="return hanya_angka(event)" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label" style="text-align: right">Lead Time (hari)  :</label>
											<div class="col-md-8">
												<input type="text" name="txtleadtime" id="txtleadtime" class="form-control" placeholder="Lead Time (hari)" style="text-align: right" onkeypress="return hanya_angka(event)" required>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tabs-harga">
								<div class="form-group">
                                    <label class="col-md-2 control-label" style="text-align: right">Harga Jual Minimal  :</label>
                                    <div class="col-md-2">
                                        <input type="text" name="txthargajualmin" id="txthargajualmin" class="form-control" placeholder="0" style="text-align: right" onkeypress="return hanya_angka(event)" required>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-md-2 control-label" style="text-align: right">Harga Jual Maksimal  :</label>
                                    <div class="col-md-2">
                                        <input type="text" name="txthargajualmax" id="txthargajualmax" class="form-control" placeholder="0" style="text-align: right" onkeypress="return hanya_angka(event)" required>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-md-2 control-label" style="text-align: right">Harga Beli (Standard)  :</label>
                                    <div class="col-md-2">
                                        <input type="text" name="txthargabeli" id="txthargabeli" class="form-control" placeholder="0" style="text-align: right" onkeypress="return hanya_angka(event)" required>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-md-2 control-label" style="text-align: right">Diskon Max. (%)  :</label>
                                    <div class="col-md-2">
                                        <input type="text" name="txtdiskonmax" id="txtdiskonmax" class="form-control" placeholder="0" style="text-align: right" onkeypress="return hanya_angka(event)" required>
                                    </div>
                                </div>
							</div>
							<div class="tab-pane" id="tabs-gambar">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="col-md-4 control-label" style="text-align: right">Gambar 1  :</label>
											<div class="col-md-8">
												<input type="file" name="gambar_1" id="gambar_1" accept="image/x-png,image/gif,image/jpeg,image/jpg" onChange="readURL1(this);">
												<span style="font-size:11px; color:#666666;">only gif, png, jpg, jpeg files. <br> max. size image 250x400</span>
												<img id="image_1" name="image_1" src="#" alt="" height="400" width="250" />
												<input type="hidden" name="txtimage_1" id="txtimage_1">
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="col-md-4 control-label" style="text-align: right">Gambar 2  :</label>
											<div class="col-md-8">
												<input type="file" name="gambar_2" id="gambar_2" accept="image/x-png,image/gif,image/jpeg,image/jpg" onChange="readURL2(this);">
												<span style="font-size:11px; color:#666666;">only gif, png, jpg, jpeg files. <br> max. size image 250x400</span>
												<img id="image_2" name="image_2" src="#" alt="" height="400" width="250" />
												<input type="hidden" name="txtimage_2" id="txtimage_2">
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="col-md-4 control-label" style="text-align: right">Gambar 3  :</label>
											<div class="col-md-8">
												<input type="file" name="gambar_3" id="gambar_3" accept="image/x-png,image/gif,image/jpeg,image/jpg" onChange="readURL3(this);">
												<span style="font-size:11px; color:#666666;">only gif, png, jpg, jpeg files. <br> max. size image 250x400</span>
												<img id="image_3" name="image_3" src="#" alt="" height="400" width="250" />
												<input type="hidden" name="txtimage_3" id="txtimage_3">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tabs-lokasi">							
								<div class="form-group">
                                    <label class="col-md-2 control-label" style="text-align: right">Lokasi  :</label>
                                    <div class="col-md-10">
                                        <select name='cbolokasiproduk[]' id='cbolokasiproduk' class='form-control bs-select' data-live-search='true' multiple="multiple">
											<option hidden disabled>Pilih Lokasi Penyimpanan Produk</option>
										</select>
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