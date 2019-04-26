<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	
	$id_transaksi = '';
    $no_transaksi = ''; 
	$tgl_transaksi = '';
	$kode_lokasi = '';
	$bulan = '';
	$tahun = '';
	$deskripsi = '';
	$status = '';
    $nama_status = '';

    if ($get_header->num_rows() > 0) {
        foreach($get_header->result() as $row) {
			$id_transaksi = $row->id_header;
            $no_transaksi = $row->no_transaksi;
            $tgl_transaksi = $row->tgl_transaksi;
			$kode_lokasi = $row->kode_lokasi;
			$bulan = $row->bulan;
			$tahun = $row->tahun;
            $deskripsi = $row->deskripsi;
			$status = $row->status;
            $nama_status = $row->nama_status;
        } 
    }
?>

<script>
	$(document).ready(function() {	
		detail_list();
		
		$('#txtnamaproduk').autocomplete ({
			minLength: 2,
			source: function (request, response) {
				var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
				$.ajax({
					url: "logistik/stock_opname/get_produk_lokasi",
					datatype: "json",
					type: "post",
					async: false,
					data: {
						id_lokasi: $('#cbolokasi').val()
					},
					success: function(data) {
						var result = response($.map(data, function(v, i) {
							var text = v.nama_produk;
							if ( text && ( !request.term || matcher.test(text) ) ) {
								return {
									label: v.nama_produk,
									value: v.id_produk,
									stok: v.stok_data
								};
							}
						}))
					},
					error: function() {
						alert("failure");
					}
				})
			},
			focus: function(event, ui) {
				event.preventDefault();
				$(this).val(ui.item.label);
				$("#txtnamaproduk").val(ui.item.label);
				$("#txtprodukid").val(ui.item.value);
				$("#txtstokdata").val(ui.item.stok);
				$("#txtstokaktual").val(ui.item.stok);
				$("#txtbatchnumber").val('');
				$("#txtexpireddate").val('');
			},
			select: function(event, ui) {
				event.preventDefault();
				$(this).val(ui.item.label);
				$("#txtnamaproduk").val(ui.item.label);
				$("#txtprodukid").val(ui.item.value);
				$("#txtstokdata").val(ui.item.stok);
				$("#txtstokaktual").val(ui.item.stok);
				$("#txtbatchnumber").val('');
				$("#txtexpireddate").val('');
				var opt = '<option selected hidden value="0">Pilih Batch Number</option>';
				$.ajax({
					url: 'logistik/stock_opname/get_batch_number',
					dataType: 'json',
					type: 'post',
					async: false,
					data: {
						id_produk: ui.item.value,
						id_lokasi: $('#cbolokasi').val()
					},
					success: function(json) {
						$.each(json, function(i, obj) {
							opt += "<option value='"+obj.batch_number_text+"' bn='"+obj.batch_number+"' ed='"+obj.expired_date+"'>"+obj.batch_number_text+"</option>";
						});
						$("#cbobatchnumber").html(opt);
						$("#cbobatchnumber").selectpicker('refresh');
					}
				});
			}
		});
		
		$("body").on("change", "#cbobatchnumber", function() {
			var batch_number = $('#cbobatchnumber option:selected').attr('bn');
			var expired_date = $('#cbobatchnumber option:selected').attr('ed');
			
			$("#txtbatchnumber").val(batch_number);
			$("#txtexpireddate").val(expired_date);
		});
		
		$("#submit_detail").click(function() {
			if ($.trim($('#txtnamaproduk').val()).length == 0) {
				$('.group-namaproduk').addClass('has-error');
				return false;
			} else {
				$('.group-namaproduk').removeClass('has-error');
				var v_id_produk = $("#txtprodukid").val();
			}
			if ($('#cbobatchnumber').val() == 0) {
				$('.group-batchnumber').addClass('has-error');
				return false;
			} else {
				$('.group-batchnumber').removeClass('has-error');
				var v_batch_number = $('#txtbatchnumber').val();
				var v_expired_date = $('#txtexpireddate').val();
			}
			var v_qty_aktual = $("#txtstokaktual").val();
			if(isNaN(v_qty_aktual)) v_qty_aktual = 0;
			var v_selisih = $("#txtselisih").val();
			if(isNaN(v_selisih)) v_selisih = 0;
			
			$.ajax({
				url: 'logistik/stock_opname/create_detail',
				data: {
					id_header: $("#txtidtransaksi").val(),
					id_produk: v_id_produk,
					batch_number: v_batch_number,
					expired_date: v_expired_date,
					qty_data: $("#txtstokdata").val(),
					qty_aktual: v_qty_aktual,
					qty_selisih: v_selisih,
					keterangan: $("#txtketerangan").val(),
					rekomendasi: $("#txtrekomendasi").val()
				},
				type: 'post',
				success: function(data) {
					if (data == 'done')
						reset_input();
						detail_list();
				}
			});
		});
		
		$("body").on("click", "#table_detail #hapus_detail", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_produk = parentTr.find('#nama_produk').html();
			var del = confirm("Hapus " + nama_produk + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'logistik/stock_opname/delete_item_detail',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						reset_input();
						detail_list();
					}
				});					
			}
		});
		
		$("body").on("click", "#table_detail #edit_detail", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var id_produk = parentTr.find('#id_produk').html();
			var nama_produk = parentTr.find('#nama_produk').html();
			var nama_kemasan = parentTr.find('#nama_kemasan').html();
			var batch_number = parentTr.find('#batch_number').html();
			var expired_date = parentTr.find('#expired_date').html();
			var qty_data = parentTr.find('#qty_data').html();
			var qty_fisik = parentTr.find('#qty_fisik').html();
			var qty_selisih = parentTr.find('#qty_selisih').html();
			var keterangan = parentTr.find('#keterangan').html();
			var rekomendasi = parentTr.find('#rekomendasi').html();
			$("#txtiddetail_edit").val(id_detail);
			$("#txtidproduk_edit").val(id_produk);
			$("#txtnamaproduk_edit").val(nama_produk);
			$("#txtkemasan_edit").val(nama_kemasan);
			$("#txtbatchnumber_edit").val(batch_number);
			$("#txtexpireddate_edit").val(expired_date);
			$("#txtstokdata_edit").val(qty_data);
			$("#txtstokaktual_edit").val(qty_fisik);
			$("#txtselisih_edit").val(qty_selisih);
			$("#txtketerangan_edit").val(keterangan);
			$("#txtrekomendasi_edit").val(rekomendasi);
			$("#modal-edit").modal("show");
		});
		
		$("#simpan_detail").click(function() {			
			if ($.trim($('#txtbatchnumber_edit').val()).length == 0) {
				$('.group-batchnumber-edit').addClass('has-error');
				return false;
			} else {
				$('.group-batchnumber-edit').removeClass('has-error');
				var v_batch_number = $("#txtbatchnumber_edit").val();
			}
			if ($.trim($('#txtexpireddate_edit').val()).length == 0) {
				$('.group-expireddate-edit').addClass('has-error');
				return false;
			} else {
				$('.group-expireddate-edit').removeClass('has-error');
				var v_expired_date = $("#txtexpireddate_edit").val();
			}
			var v_qty_aktual = $("#txtstokaktual_edit").val();
			if(isNaN(v_qty_aktual)) v_qty_aktual = 0;
			var v_selisih = $("#txtselisih_edit").val();
			if(isNaN(v_selisih)) v_selisih = 0;
			
			$.ajax({
				url: 'logistik/stock_opname/update_item_detail',
				data: {
					id_detail: $("#txtiddetail_edit").val(),
					id_produk: $("#txtidproduk_edit").val(),
					batch_number: v_batch_number,
					expired_date: v_expired_date,
					qty_data: $("#txtstokdata_edit").val(),
					qty_aktual: v_qty_aktual,
					qty_selisih: v_selisih,
					keterangan: $("#txtketerangan_edit").val(),
					rekomendasi: $("#txtrekomendasi_edit").val()
				},
				type: 'post',
				success: function(data) {
					if (data == 'done')
						detail_list();
						$('#modal-edit').modal('hide');
				}
			});
		});
		
		function detail_list() {
			var hid = $("#txtidtransaksi").val();
			$.ajax({
				url   : 'logistik/stock_opname/detail_list/?hid='+hid,
				async : false,
				success : function(data) {
					$('#show_detail').html(data);
				}
			});
		}

		$("#simpan_header").click(function() {
			$.ajax({
				url: 'logistik/stock_opname/update_header',
				data: {
					id_header: $("#txtidtransaksi").val(),
					deskripsi: $("#txtdeskripsi_header").val()
				},
				type: 'post',
				success: function(data) {
					if (data == 'done')
						window.location = "logistik/stock_opname";
				}
			});
		});
			
	});
	
	$(document).on("keyup keypress", "#txtstokaktual", function() {
		var v_qty_data = $('#txtstokdata').val();
		var v_qty_aktual = $('#txtstokaktual').val();
		var v_selisih = v_qty_data - v_qty_aktual;
		
		$('#txtselisih').val(v_selisih);
	});
	
	$(document).on("keyup keypress", "#txtstokaktual_edit", function() {
		var v_qty_data = $('#txtstokdata_edit').val();
		var v_qty_aktual = $('#txtstokaktual_edit').val();
		var v_selisih = v_qty_data - v_qty_aktual;
		
		$('#txtselisih_edit').val(v_selisih);
	});
	
	function reset_input() {
		$('#txtprodukid').val('');
		$('#txtnamaproduk').val('');
		$("#cbobatchnumber").html("<option value='0' selected>Pilih Batch Number</option>");
		$("#cbobatchnumber").selectpicker('refresh');
		$('#txtstokdata').val('0');
		$('#txtstokaktual').val('0');
		$('#txtselisih').val('0');
		$('#txtketerangan').val('');
		$('#txtrekomendasi').val('');
		$("#txtbatchnumber").val('');
		$("#txtexpireddate").val('');
	}
</script>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Data Header</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">
						<label>No. Transaksi</label>
						<div class="input-group">
							<input type="text" class="form-control" value="<?php echo $no_transaksi; ?>" readonly>
							<input id="txtidtransaksi" type="hidden" class="form-control input-sm" value="<?php echo $id_transaksi; ?>" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<label>Tgl. Transaksi</label>
						<div class="input-group">
							<input type="text" class="form-control" value="<?php echo date_format(new DateTime($tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<label>Lokasi</label>
						<div class="input-group">
							<select name='cbolokasi' id='cbolokasi' class='form-control bs-select' data-live-search='true' disabled>
								<?php
									if ($get_lokasi->num_rows() > 0) {										
										foreach ($get_lokasi->result() as $row) {
											$sel = $kode_lokasi == $row->kode ? ' selected="selected"' : '';
											echo "<option value='".$row->id."' $sel>".$row->kode."</option>";
										}
									}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>Bulan</label>
						<div class="input-group">
							<select name="cbobulan_header" id="cbobulan_header" class='form-control bs-select' data-live-search='true' disabled>
								<?php
									for ($i=1; $i<=12; $i+=1) {
										$sel = $i == $bulan ? 'selected="selected"' : '';
										echo "<option value=$i $sel>".set_month_to_string_ind($i)."</option>";
									}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<label>Tahun</label>
						<div class="input-group">
							<select name="cbotahun_header" id="cbotahun_header" class='form-control bs-select' data-live-search='true' disabled>
								<?php
									for($i=2020; $i>=2017; $i-=1) {
										$sel = $i == $tahun ? ' selected="selected"' : '';
										echo"<option value=$i $sel> $i </option>";
									}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="col-md-12">
					<label>Deskripsi</label>
					<div class="input-group">
						<textarea class="form-control" rows="5" id="txtdeskripsi_header"><?php echo $deskripsi; ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">
				<?php
					echo "&nbsp;&nbsp;";
					if ($status == '0') {                
						echo '<button class="btn btn-block btn-success btn-icon-fixed" id="simpan_header"><span class="icon-floppy-disk"></span> Simpan</button>';
					}
				?>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Data Detail</h3>
	</div>
	<div class="panel-body">
		<?php
			if ($status == 0) {
		?>
				<div class="row">
					<div class="col-md-3">
						<label>Nama Produk</label>
						<div class="input-group group-namaproduk">
							<input id="txtnamaproduk" type="text" class="form-control" placeholder="Ketik nama produk" required>
							<input id="txtprodukid" type="hidden" class="form-control">
						</div>
					</div>
					<div class="col-md-4">
						<label>Batch Number</label>
						<div class="input-group group-batchnumber">
							<select id="cbobatchnumber" name="cbobatchnumber" class="bs-select" data-live-search="true">
								<option value="0" hidden>Pilih Batch Number</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<label class="pull-right">Stok (Data)</label>
						<div class="input-group group-stokdata">
							<input id="txtstokdata" name="txtstokdata" type="text" class="form-control" style="text-align: right;" value="0" readonly onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-2">
						<label class="pull-right">Stok (Aktual)</label>
						<div class="input-group group-stokaktual">
							<input id="txtstokaktual" name="txtstokaktual" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Selisih</label>
						<div class="input-group group-selisih">
							<input id="txtselisih" name="txtselisih" type="text" class="form-control" style="text-align: right;" value="0" readonly onkeypress="return hanya_angka(event)">
						</div>
					</div>
				</div>
				
				<input id="txtbatchnumber" name="txtstokaktual" type="hidden" />
				<input id="txtexpireddate" name="txtstokaktual" type="hidden" />
					
				<div class="row">
					<div class="col-md-5">
						<label>Keterangan</label>
						<div class="input-group">
							<textarea class="form-control" rows="3" id="txtketerangan"></textarea>
						</div>
					</div>
					<div class="col-md-5">
						<label>Rekomendasi</label>
						<div class="input-group">
							<textarea class="form-control" rows="3" id="txtrekomendasi"></textarea>
						</div>
					</div>
					<div class="col-md-2">
						<div class="row">
							<div class="col-md-12">
								&nbsp;
							</div>
							<div class="col-md-12">
								&nbsp;
							</div>
							<div class="col-md-12">
								<label>&nbsp;</label>
								<div class="input-group">
									<button id="submit_detail" type="submit" class="btn btn-block btn-warning btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
								</div>
							</div>
						</div>
					</div>
				</div>
		<?php
			}
		?>    

		<div class="row">
			<div class="col-md-12">
				<table id="table_detail" class="table table-head-custom table-striped" style="width: 100%;">
					<thead>
						<tr>
							<th style="display: none">ID</th>
							<th style="width: 4%; text-align: center;">NO</th>
							<th style="display: none;">ID PRODUK</th>
							<th style="width: 16%;">NAMA PRODUK</th>
							<th style="display: none;">ID KEMASAN</th>
							<th style="width: 8%; text-align: center;">KEMASAN</th>
							<th style="width: 9%; text-align: center;">BATCH NUMBER</th>
							<th style="width: 8%; text-align: center;">EXPIRED DATE</th>
							<th style="width: 8%; text-align: right;">STOK <br/>(DATA)</th>
							<th style="width: 8%; text-align: right;">STOK (AKTUAL)</th>
							<th style="width: 8%; text-align: right;">SELISIH</th>
							<th style="width: 11%;">KETERANGAN</th>
							<th style="width: 11%;">REKOMENDASI</th>
							<th style="width: 9%; text-align: center;">ACTIONS</th>
						</tr>
					</thead>
					<tbody id="show_detail">

					</tbody>                
				</table>
				
				<div id="responsecontainer" align="center"></div>
				
			</div>
		</div>
	</div>
</div>

<!-- Begin : Modal Edit Detail -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
        <div class="modal-content">
            <div class="modal-header">                        
                <h4 class="modal-title" id="modal-primary-header">Edit Data Detail</h4>
            </div>
            <div class="modal-body">
				<input type="hidden" id="txtiddetail_edit" class="form-control" />
				
				<div class="row">
					<div class="col-md-8">
						<label>Nama Produk</label>
						<div class="input-group">
							<input type="hidden" id="txtidproduk_edit" class="form-control" readonly />
							<input type="text" id="txtnamaproduk_edit" class="form-control" readonly />
						</div>
					</div>
					<div class="col-md-4">
						<label>Kemasan</label>
						<div class="input-group">
							<input type="text" id="txtkemasan_edit" class="form-control" readonly />
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-8">
						<label>Batch Number</label>
						<div class="input-group group-batchnumber-edit">
							<input type="text" id="txtbatchnumber_edit" class="form-control" readonly />
						</div>
					</div>
					<div class="col-md-4">
						<label>Expired Date</label>
						<div class="input-group group-expireddate-edit">
							<input type="text" id="txtexpireddate_edit" class="form-control" readonly />
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-4">
						<label>Stok (Data)</label>
						<div class="input-group">
							<input type="text" id="txtstokdata_edit" class="form-control" style="text-align: right;" readonly />
						</div>
					</div>
					<div class="col-md-4">
						<label>Stok (Aktual)</label>
						<div class="input-group group-stokaktual-edit">
							<input type="text" id="txtstokaktual_edit" class="form-control" style="text-align: right;" />
						</div>
					</div>
					<div class="col-md-4">
						<label>Selisih</label>
						<div class="input-group">
							<input type="text" id="txtselisih_edit" class="form-control" style="text-align: right;" readonly />
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<label>Keterangan</label>
						<div class="input-group">
							<textarea class="form-control" rows="3" id="txtketerangan_edit"></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<label>Rekomendasi</label>
						<div class="input-group">
							<textarea class="form-control" rows="3" id="txtrekomendasi_edit"></textarea>
						</div>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>					
                <button type="button" class="btn btn-primary" id="simpan_detail">Simpan</button>					
            </div>
        </div>
	</div>            
</div>
<!-- End : Modal Edit Detail -->