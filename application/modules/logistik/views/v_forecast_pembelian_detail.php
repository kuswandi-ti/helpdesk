<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

    $id = '';
    $no_transaksi = '';
	$tgl_transaksi = '';
	$tahun = '';
	$keterangan = '';

    if ($get_header->num_rows() > 0) {
        foreach($get_header->result() as $row) {
            $id = $row->id;
            $no_transaksi = $row->no_transaksi;
            $tgl_transaksi = $row->tgl_transaksi;
			$tahun = $row->tahun;
            $keterangan = $row->keterangan;
        } 
    }
?>

<script>
	$(document).ready(function() {
		detail_list();
		
		$("#simpan_header").click(function() {
			$('#simpan_header').attr('disabled', true);
			
			var v_id_header = $("#txtidtransaksi_hdr").val();
			var v_keterangan = $("#txtketerangan_hdr").val();
			$.ajax({
				url: 'logistik/forecast_pembelian/update_header',
				data: {
					id_header: v_id_header,
					keterangan: v_keterangan
				},
				type: 'post',
				success: function(data) {
					if (data == 'done')
						window.location = "logistik/forecast_pembelian";
				}
			});
		});
		
		$("#simpan_detail").click(function() {
			$('#simpan_detail').attr('disabled', true);
			$.ajax({
				url: 'logistik/forecast_pembelian/update_item_detail',
				data: {
					id_detail: $("#txtid_edit").val(),
					bulan_01: $("#txtqty01_edit").val(),
					bulan_02: $("#txtqty02_edit").val(),
					bulan_03: $("#txtqty03_edit").val(),
					bulan_04: $("#txtqty04_edit").val(),
					bulan_05: $("#txtqty05_edit").val(),
					bulan_06: $("#txtqty06_edit").val(),
					bulan_07: $("#txtqty07_edit").val(),
					bulan_08: $("#txtqty08_edit").val(),
					bulan_09: $("#txtqty09_edit").val(),
					bulan_10: $("#txtqty10_edit").val(),
					bulan_11: $("#txtqty11_edit").val(),
					bulan_12: $("#txtqty12_edit").val(),
					keterangan: $("#txtketerangan_edit").val()
				},
				type: 'post',
				success: function(data) {
					detail_list();					
					$('#modal-edit').modal('hide');
				}
			});
		});
	});
	
	function detail_list() {
		var hid = $("#txtidtransaksi_hdr").val();
        $.ajax({
            url   : 'logistik/forecast_pembelian/detail_list/?hid='+hid,
            async : false,
            success : function(data) {
                $('#show_detail').html(data);
            }
        });
    }
	
	$("body").on("click", "#submit_detail", function() {
		$('#submit_detail').attr('disabled', true);
		
		var v_id_produk = $('#cboproduk').val();
		
		/* Untuk mengecek duplikat item produk */
		var is_available = 0;
		$('#table_detail #id_produk').each(function() {
			v_cek_produk_id = $(this).html();
			if (v_id_produk == v_cek_produk_id) {
				is_available++;
			}
		});
		if (is_available > 0) {
			alert("Produk sudah ada!  Pilih produk yang lain.");
			$('#submit_detail').attr('disabled', false);
			return;
		}
		
		$.ajax({			
			url: "logistik/forecast_pembelian/create_detail",
			data: {   
					id_header: $("#txtidtransaksi_hdr").val(),
					id_produk: v_id_produk,
					bulan_01: $("#txtqty01").val(),
					bulan_02: $("#txtqty02").val(),
					bulan_03: $("#txtqty03").val(),
					bulan_04: $("#txtqty04").val(),
					bulan_05: $("#txtqty05").val(),
					bulan_06: $("#txtqty06").val(),
					bulan_07: $("#txtqty07").val(),
					bulan_08: $("#txtqty08").val(),
					bulan_09: $("#txtqty09").val(),
					bulan_10: $("#txtqty10").val(),
					bulan_11: $("#txtqty11").val(),
					bulan_12: $("#txtqty12").val(),
					keterangan: $("#txtketerangan").val()
			},
			method: "post",
			success: function(data) {				
				detail_list();
				$('#submit_detail').attr('disabled', false);
			},
			beforeSend: function() {
				//$('#responsecontainer').html("<img src='assets/apps/img/ajax-loader.gif' /><br /><br />");
			},
			error: function() {
				alert("failure");
				$('#submit_detail').attr('disabled', false);
			}
		});
    });
	
	$("body").on("click", "#table_detail #edit_detail", function() {		
		var parentTr = $(this).closest('tr');
		var id_detail = parentTr.find('#id_detail').html();
		var nama_produk = parentTr.find('#nama_produk').html();
		var bulan_01 = parentTr.find('#bulan_01').html();
		var bulan_02 = parentTr.find('#bulan_02').html();
		var bulan_03 = parentTr.find('#bulan_03').html();
		var bulan_04 = parentTr.find('#bulan_04').html();
		var bulan_05 = parentTr.find('#bulan_05').html();
		var bulan_06 = parentTr.find('#bulan_06').html();
		var bulan_07 = parentTr.find('#bulan_07').html();
		var bulan_08 = parentTr.find('#bulan_08').html();
		var bulan_09 = parentTr.find('#bulan_09').html();
		var bulan_10 = parentTr.find('#bulan_10').html();
		var bulan_11 = parentTr.find('#bulan_11').html();
		var bulan_12 = parentTr.find('#bulan_12').html();
		var keterangan = parentTr.find('#keterangan').html();
		$("#txtid_edit").val(id_detail);
		$("#txtnamaproduk_edit").val(nama_produk);
		$("#txtqty01_edit").val(bulan_01);
		$("#txtqty02_edit").val(bulan_02);
		$("#txtqty03_edit").val(bulan_03);
		$("#txtqty04_edit").val(bulan_04);
		$("#txtqty05_edit").val(bulan_05);
		$("#txtqty06_edit").val(bulan_06);
		$("#txtqty07_edit").val(bulan_07);
		$("#txtqty08_edit").val(bulan_08);
		$("#txtqty09_edit").val(bulan_09);
		$("#txtqty10_edit").val(bulan_10);
		$("#txtqty11_edit").val(bulan_11);
		$("#txtqty12_edit").val(bulan_12);
		$("#txtketerangan_edit").val(keterangan);
		$("#modal-edit").modal("show");
	});
	
	$("body").on("click", "#table_detail #hapus_detail", function() {
		var parentTr = $(this).closest('tr');
		var v_id_detail = parentTr.find('#id_detail').html();
		var v_nama_produk = parentTr.find('#nama_produk').html();
		var del = confirm("Hapus " + v_nama_produk + " ?");
		
		if (del == true) {
			$.ajax({
				url: 'logistik/forecast_pembelian/delete_item_detail',
				type: 'post',
				data: {
					id_detail: v_id_detail
				},
				success: function(data) {
					if (data == 'done') {
						detail_list();
					}
				}
			});					
		}
	});
</script>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Data Header</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<label>No. Transaksi</label>
				<div class="input-group">
					<input id="txtnotransaksi_hdr" name="txtnotransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo $no_transaksi; ?>" readonly>
					<input id="txtidtransaksi_hdr" name="txtidtransaksi_hdr" type="hidden" class="form-control" value="<?php echo $id; ?>" readonly>
				</div>
			</div>
			<div class="col-md-4">
				<label>Tgl. Transaksi</label>
				<div class="input-group">
					<input id="txttgltransaksi_hdr" name="txttgltransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo date_format(new DateTime($tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>" readonly>
				</div>
			</div>
			<div class="col-md-4">
				<label>Tahun</label>
				<div class="input-group">
					<input id="txttahun_hdr" name="txttahun_hdr" type="text" class="form-control input-sm" value="<?php echo $tahun ?>" readonly>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<label>Keterangan</label>
				<div class="input-group">
					<textarea id="txtketerangan_hdr" name="txtketerangan_hdr" class="form-control" rows="4"><?php echo $keterangan; ?></textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">
				<?php
					echo "&nbsp;&nbsp;";
					echo '<button class="btn btn-block btn-success btn-icon-fixed pull-right" id="simpan_header" type="button"><span class="icon-floppy-disk"></span>Simpan</button>';
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
		<div class="row">
			<div class="col-md-7">
				<label>Produk</label>
				<div>
					<select id="cboproduk" name="cboproduk" class="bs-select" data-live-search="true">
						<?php 
							foreach($get_produk_aktif->result() as $row) {										
								echo "<option value='".$row->id_produk."'>".$row->nama_produk."</option>";
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-1">
				<label class="pull-right">Qty Jan</label>
				<div>
					<input id="txtqty01" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-1">
				<label class="pull-right">Qty Feb</label>
				<div>
					<input id="txtqty02" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-1">
				<label class="pull-right">Qty Mar</label>
				<div>
					<input id="txtqty03" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-1">
				<label class="pull-right">Qty Apr</label>
				<div>
					<input id="txtqty04" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-1">
				<label class="pull-right">Qty Mei</label>
				<div>
					<input id="txtqty05" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
		</div>
		&nbsp;
		<div class="row">
			<div class="col-md-1">
				<label class="pull-right">Qty Jun</label>
				<div>
					<input id="txtqty06" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-1">
				<label class="pull-right">Qty Jul</label>
				<div>
					<input id="txtqty07" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-1">
				<label class="pull-right">Qty Agu</label>
				<div>
					<input id="txtqty08" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-1">
				<label class="pull-right">Qty Sep</label>
				<div>
					<input id="txtqty09" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-1">
				<label class="pull-right">Qty Okt</label>
				<div>
					<input id="txtqty10" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-1">
				<label class="pull-right">Qty Nov</label>
				<div>
					<input id="txtqty11" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-1">
				<label class="pull-right">Qty Des</label>
				<div>
					<input id="txtqty12" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-4">
				<label>Keterangan</label>
				<div>
					<input id="txtketerangan" type="text" class="form-control" placeholder="Keterangan">
				</div>
			</div>
			<div class="col-md-1">
				<label>&nbsp;</label>
				<div>
					<button id="submit_detail" type="submit" class="btn btn-warning pull-right"> Tambah</button>
				</div>
			</div>
		</div>
		&nbsp;
		<div class="row">
			<div class="col-md-12">            
				<table id="table_detail" class="table table-head-custom table-striped" style="width: 100%;">
					<thead>
						<tr>
							<th style="display: none">ID</th>
							<th style="width: 4%; text-align: center;">NO</th>
							<th style="display: none;">ID PRODUK</th>
							<th style="width: 20%;">NAMA PRODUK</th>
							<th style="width: 5%; text-align: right;">QTY JAN</th>
							<th style="width: 5%; text-align: right;">QTY FEB</th>
							<th style="width: 5%; text-align: right;">QTY MAR</th>
							<th style="width: 5%; text-align: right;">QTY APR</th>
							<th style="width: 5%; text-align: right;">QTY MEI</th>
							<th style="width: 5%; text-align: right;">QTY JUN</th>
							<th style="width: 5%; text-align: right;">QTY JUL</th>
							<th style="width: 5%; text-align: right;">QTY AGU</th>
							<th style="width: 5%; text-align: right;">QTY SEP</th>
							<th style="width: 5%; text-align: right;">QTY OKT</th>
							<th style="width: 5%; text-align: right;">QTY NOV</th>
							<th style="width: 5%; text-align: right;">QTY DES</th>
							<th style="width: 10%;">KETERANGAN</th>
							<th style="width: 6%; text-align: center;">ACTIONS</th>
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
	<div class="modal-dialog modal-primary modal-fw" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-primary-header">Edit Data Detail</h4>
			</div>
			<input type="hidden" id="txtid_edit" class="form-control" />
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label>Nama Produk</label>
						<div class="input-group">
							<input id="txtnamaproduk_edit" name="txtnamaproduk_edit" type="text" class="form-control" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-1">
						<label class="pull-right">Qty Jan</label>
						<div>
							<input id="txtqty01_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty Feb</label>
						<div>
							<input id="txtqty02_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty Mar</label>
						<div>
							<input id="txtqty03_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty Apr</label>
						<div>
							<input id="txtqty04_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty Mei</label>
						<div>
							<input id="txtqty05_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty Jun</label>
						<div>
							<input id="txtqty06_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty Jul</label>
						<div>
							<input id="txtqty07_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty Agu</label>
						<div>
							<input id="txtqty08_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty Sep</label>
						<div>
							<input id="txtqty09_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty Okt</label>
						<div>
							<input id="txtqty10_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty Nov</label>
						<div>
							<input id="txtqty11_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty Des</label>
						<div>
							<input id="txtqty12_edit" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>
				</div>
				&nbsp;
				<div class="row">
					<div class="col-md-12">
						<label>Keterangan</label>
						<div class="input-group">
							<textarea id="txtketerangan_edit" name="txtketerangan_edit" class="form-control" rows="4"></textarea>
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