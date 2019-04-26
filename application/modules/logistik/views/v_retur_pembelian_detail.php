<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {	
		detail_list();
		
		$("body").on("change", "#cboprodukgr", function() {
			$.ajax({
				url: 'logistik/retur_pembelian/get_info_produk_gr',
				data: {
					'id_gr': $('#txtidgr_hdr').val(),
					'id_produk': $('#cboprodukgr').val()
				},
				dataType: 'json',
				type: 'post',
				success: function(data) {
					if (data.result == 'done') {
						$('#txtbatchnumber').val(data.batch_number);
						$('#txtexpireddate').val(data.expired_date);
						$('#txtqtyretur').focus();
					} else {
						$('#txtqtygr').val(0);
						$('#txtbatchnumber').val('');
						$('#txtexpireddate').val('');
						$('#txtqtyretur').val('0');
					}
				},
				error: function() {
					alert('failure');
				}
			});
		});
		
		$("#simpan_header").click(function() {
			var v_id_header = $("#txtidtransaksi_hdr").val();
			var v_alasan_retur = $("#txtalasanretur_hdr").val();
			var v_keterangan = $("#txtketerangan_hdr").val();
			$.ajax({
				url: 'logistik/retur_pembelian/update_header',
				data: {
					id_header: v_id_header,
					alasan_retur: v_alasan_retur,
					keterangan: v_keterangan
				},
				type: 'post',
				success: function(data) {
					if (data == 'done')
						window.location = "logistik/retur_pembelian";
				}
			});
		});
		
		$("#submit_detail").on("click", function() {
			if ($('#txtqtygr').val() == '') {
				v_qty_retur = 0;
			} else {
				var v_qty_retur = $("#txtqtyretur").val();
			}
			var v_id_header = $("#txtidtransaksi_hdr").val();
			$.ajax({			
				url: "logistik/retur_pembelian/create_detail/",
				data: {   
						id_header: v_id_header,
						id_produk: $("#cboprodukgr").val(),
						batch_number: $("#txtbatchnumber").val(),
						expired_date: $("#txtexpireddate").val(),
						qty_retur: $("#txtqtyretur").asNumber({parseType: 'Float'}),
						keterangan: $("#txtketerangan").val(),
				},
				method: "post",
				success: function(data) {
					$("#cboprodukgr").val('0');
					$("#txtbatchnumber").val('');
					$("#txtexpireddate").val('');
					$("#txtqtyretur").val('0');
					$("#txtketerangan").val('');
					location.reload();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		/* ======================================================================================= */
		/* END - HEADER */
		/* ======================================================================================= */
		
		/* ======================================================================================= */
		/* BEGIN - DETAIL */
		/* ======================================================================================= */
		function detail_list() {
			var hid = $("#txtidtransaksi_hdr").val();
			$.ajax({
				url   : 'logistik/retur_pembelian/detail_list/?hid='+hid,
				async : false,
				success : function(data) {
					$('#show_detail').html(data);
				}
			});
		}
		
		$("body").on("click", "#table_detail #hapus_detail", function() {
			var parentTr = $(this).closest('tr');
			var v_id_detail = parentTr.find('#id_detail').html();
			var v_nama_produk = parentTr.find('#nama_produk').html();
			var del = confirm("Hapus " + v_nama_produk + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'logistik/retur_pembelian/delete_item_detail',
					type: 'post',
					data: {
						id_detail: v_id_detail
					},
					success: function(data) {
						if (data == 'done') {
							detail_list();
							location.reload();
						}
					}
				});					
			}
		});
		
		$("body").on("click", "#table_detail #edit_detail", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_produk = parentTr.find('#nama_produk').html();
			var nama_kemasan = parentTr.find('#nama_kemasan').html();
			var batch_number = parentTr.find('#batch_number').html();
			var expired_date = parentTr.find('#expired_date').html();
			var qty_retur = parentTr.find('#qty_retur').html();
			var keterangan = parentTr.find('#keterangan').html();
			$("#txtid_edit").val(id_detail);
			$("#txtnamaproduk_edit").val(nama_produk);
			$("#txtkemasan_edit").val(nama_kemasan);
			$("#txtbatchnumber_edit").val(batch_number);
			$("#txtexpireddate_edit").val(expired_date);
			$("#txtqtyretur_edit").val(qty_retur);
			$("#txtketerangan_edit").val(keterangan);
			$("#modal-edit").modal("show");
		});
		
		$("#simpan_detail").click(function() {
			if ($.trim($('#txtqtyretur_edit').val()).length == 0) {
				$('.group-qtyretur-edit').addClass('has-error');
				return false;
			} else {
				$('.group-qtyretur-edit').removeClass('has-error');
				var v_qty_retur = $("#txtqtyretur_edit").asNumber({parseType: 'Float'});
			}
			var v_id_detail = $("#txtid_edit").val();
			var v_keterangan = $("#txtketerangan_edit").val();
			$.ajax({
				url: 'logistik/retur_pembelian/update_item_detail',
				data: {
					id_detail: v_id_detail,
					qty_retur: v_qty_retur,
					keterangan: v_keterangan
				},
				type: 'post',
				success: function(data) {
					detail_list();					
					$('#modal-edit').modal('hide');
				}
			});
		});
		/* ======================================================================================= */
		/* END - DETAIL */
		/* ======================================================================================= */	
	});
</script>

<?php
    $id_transaksi = '';
    $no_transaksi = '';
	$tgl_transaksi = '';
	$bulan = '';
	$tahun = '';
	$id_supplier = '';
    $nama_supplier = '';
	$id_gr = '';
	$no_gr = '';
	$alasan_retur = '';
	$keterangan = '';

    if ($get_header->num_rows() > 0) {
        foreach($get_header->result() as $row) {
			$id_transaksi = $row->id_header;
			$no_transaksi = $row->no_transaksi; 
			$tgl_transaksi = $row->tgl_transaksi;
			$bulan = set_month_to_string_ind($row->bulan);
			$tahun = $row->tahun;
			$id_supplier = $row->id_supplier;
			$nama_supplier = $row->nama_supplier;
			$id_gr = $row->id_gr;
			$no_gr = $row->no_gr;
			$alasan_retur = $row->alasan_retur;
			$keterangan = $row->keterangan;
        }
    }
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Data Header</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-2">
				<label>No. Retur</label>
				<div class="input-group">
					<input id="txtnotransaksi_hdr" name="txtnotransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo $no_transaksi; ?>" readonly>
					<input id="txtidtransaksi_hdr" name="txtidtransaksi_hdr" type="hidden" class="form-control" value="<?php echo $id_transaksi; ?>" readonly>
				</div>
			</div>
			<div class="col-md-2">
				<label>Tgl. Retur</label>
				<div class="input-group">
					<input id="txttgltransaksi_hdr" name="txttgltransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo date_format(new DateTime($tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>" readonly>
				</div>
			</div>        
			<div class="col-md-6">
				<label>Supplier</label>
				<div class="input-group">
					<input id="txtnamasupplier_hdr" name="txtnamasupplier_hdr" type="text" class="form-control input-sm" value="<?php echo $nama_supplier; ?>" readonly>
					<input id="txtsupplierid_hdr" name="txtsupplierid_hdr" type="hidden" class="form-control input-sm" value="<?php echo $id_supplier; ?>" readonly>
				</div>
			</div>
			<div class="col-md-2">
				<label>No. GR</label>
				<div class="input-group">
					<input id="txtnogr_hdr" name="txtnogr_hdr" type="text" class="form-control input-sm" value="<?php echo $no_gr; ?>" readonly>
					<input id="txtidgr_hdr" name="txtidgr_hdr" type="hidden" class="form-control input-sm" value="<?php echo $id_gr; ?>" readonly>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-2">
				<div class="row">
					<div class="col-md-12">
						<label>Bulan</label>
						<div class="input-group">
							<input id="txtbulan_hdr" name="txtnotransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo $bulan; ?>" readonly>
						</div>
					</div>
					<div class="col-md-12">
						<label>Tahun</label>
						<div class="input-group">
							<input id="txttahun_hdr" name="txtnotransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo $tahun; ?>" readonly>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-6">
						<label>Alasan Retur</label>
						<div class="input-group">
							<textarea id="txtalasanretur_hdr" name="txtalasanretur_hdr" class="form-control" rows="4"><?php echo $alasan_retur; ?></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<label>Keterangan</label>
						<div class="input-group">
							<textarea id="txtketerangan_hdr" name="txtketerangan_hdr" class="form-control" rows="4"><?php echo $keterangan; ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div> 
		<div class="row">
			<div class="col-md-2">
				<button class="btn btn-block btn-success btn-icon-fixed pull-right" id="simpan_header" type="button"><span class="icon-floppy-disk"></span>Simpan</button>
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
					<select id="cboprodukgr" name="cboprodukgr" class="bs-select" data-live-search="true">
						<option value="0" selected>Pilih Produk</option>
						<?php 
							foreach($get_produk_gr->result() as $row) {										
								echo "<option value='".$row->id_produk."'>".$row->nama_produk."</option>";
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-1">
				<label>Qty Retur</label>
				<div>
					<input id="txtqtyretur" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
			<div class="col-md-3">
				<label>Keterangan</label>
				<div>
					<input id="txtketerangan" type="text" class="form-control" placeholder="Keterangan Retur">
				</div>
			</div>
			<div class="col-md-1">
				<label>&nbsp;</label>
				<div>
					<button id="submit_detail" type="submit" class="btn btn-warning pull-right"> Tambah</button>
				</div>
			</div>
			<input id="txtbatchnumber" type="hidden">
			<input id="txtexpireddate" type="hidden">
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
							<th style="width: 30%;">NAMA PRODUK</th>
							<th style="display: none;">ID KEMASAN</th>
							<th style="width: 6%; text-align: center;">KEMASAN</th>
							<th style="width: 12%; text-align: center;">BATCH NUMBER</th>
							<th style="width: 10%; text-align: center;">EXPIRED DATE</th>
							<th style="width: 8%; text-align: right;">QTY RETUR</th>
							<th style="width: 21%;">KETERANGAN</th>
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
					<div class="col-md-12">
						<label>Kemasan</label>
						<div class="input-group">
							<input id="txtkemasan_edit" name="txtkemasan_edit" type="text" class="form-control" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label>Batch Number</label>
						<div class="input-group">
							<input id="txtbatchnumber_edit" name="txtbatchnumber_edit" type="text" class="form-control" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<label>Expired Date</label>
						<div class="input-group">
							<input id="txtexpireddate_edit" name="txtexpireddate_edit" type="text" class="form-control" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<label>Qty Retur</label>
						<div class="input-group group-qtyretur-edit">
							<input id="txtqtyretur_edit" name="txtqtyretur_edit" type="text" class="form-control" onkeypress="return hanya_angka(event)">
						</div>
					</div>
				</div>
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