<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {	
		detail_list();
		
		$("#cbonopo").on('change',function(){
			var id_po = $(this).val();
			var opt = '<option selected hidden value="0">Pilih Produk</option>';
			$.ajax({
				url: 'logistik/quarantine/get_produk/'+id_po,
				dataType: 'json',
				type: 'get',
				success: function(json) {
					$.each(json, function(i, obj) {
						opt += "<option value='"+obj.id_produk+"' bn='"+obj.batch_number+"' ed='"+obj.expired_date+"'>"+obj.nama_produk+" - "+obj.batch_number+" - "+obj.expired_date+"</option>";
					});
					$("#cboproduk").html(opt);
					$("#cboproduk").selectpicker('refresh');
					$('#txtidproduk').val('');
					$('#txtbatchnumber').val('');
					$('#txtexpireddate').val('');
					$('#txtqtystok').val('0');
				}
			});
		});
		
		$("#cboproduk").on('change',function(){
			$('#txtidproduk').val('');
			$('#txtbatchnumber').val('');
			$('#txtexpireddate').val('');
			$('#txtqtystok').val('0');
					
			var id_produk = $(this).val();
			var batch_number = $('#cboproduk option:selected').attr('bn');
			var expired_date = $('#cboproduk option:selected').attr('ed');
			
			$('#txtidproduk').val(id_produk);
			$('#txtbatchnumber').val(batch_number);
			$('#txtexpireddate').val(expired_date);
			
			$.ajax({						
				url: 'logistik/quarantine/get_stok',
				data: {
					id_produk: id_produk,
					batch_number: batch_number,
					expired_date: expired_date
				},
				dataType: 'json',
				type: 'post',
				success: function(data) {
					$('#txtqtystok').val(data.stok);
				}
			});
		});
		
		$("#simpan_header").click(function() {
			var v_id_header = $("#txtidtransaksi_hdr").val();
			var v_keterangan = $("#txtketerangan_hdr").val();
			$.ajax({
				url: 'logistik/quarantine/update_header',
				data: {
					id_header: v_id_header,
					keterangan: v_keterangan
				},
				type: 'post',
				success: function(data) {
					if (data == 'done')
						window.location = "logistik/quarantine";
				}
			});
		});
		
		$("#submit_detail").on("click", function() {
			if ($('#txtqty').val() == '') {
				v_qty = 0;
			} else {
				var v_qty = $("#txtqty").val();
			}
			var v_header_id = $("#txtidtransaksi_hdr").val();
			$.ajax({			
				url: "logistik/quarantine/create_detail/",
				data: {   
						id_header: v_header_id,
						id_produk: $("#txtidproduk").val(),
						id_po: $("#cbonopo").val(),
						batch_number: $("#txtbatchnumber").val(),
						expired_date: $("#txtexpireddate").val(),
						qty: $("#txtqty").asNumber({parseType: 'Float'}),
						alasan: $("#txtalasan").val()
				},
				method: "post",
				success: function(data) {
					$("#cbonopo").val('0');
					$('#txtidproduk').val('');
					$("#txtbatchnumber").val('');
					$("#txtexpireddate").val('');
					$("#txtqty").val('0');
					$("#txtalasan").val('');
					$('#txtqtystok').val('0');
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
				url   : 'logistik/quarantine/detail_list/?hid='+hid,
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
					url: 'logistik/quarantine/delete_item_detail',
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
			var qty = parentTr.find('#qty').html();
			var no_po = parentTr.find('#no_po').html();
			var alasan = parentTr.find('#alasan').html();
			$("#txtid_edit").val(id_detail);
			$("#txtnamaproduk_edit").val(nama_produk);
			$("#txtkemasan_edit").val(nama_kemasan);
			$("#txtbatchnumber_edit").val(batch_number);
			$("#txtexpireddate_edit").val(expired_date);
			$("#txtqty_edit").val(qty);
			$("#txnopo_edit").val(no_po);
			$("#txtalasan_edit").val(alasan);
			$("#modal-edit").modal("show");
		});
		
		$("#simpan_detail").click(function() {
			if ($.trim($('#txtqty_edit').val()).length == 0) {
				$('.group-qty-edit').addClass('has-error');
				return false;
			} else {
				$('.group-qty-edit').removeClass('has-error');
				var v_qty = $("#txtqty_edit").asNumber({parseType: 'Float'});
			}
			var v_id_detail = $("#txtid_edit").val();
			var v_alasan = $("#txtalasan_edit").val();
			$.ajax({
				url: 'logistik/quarantine/update_item_detail',
				data: {
					id_detail: v_id_detail,
					qty: v_qty,
					alasan: v_alasan
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
	$keterangan = '';

    if ($get_header->num_rows() > 0) {
        foreach($get_header->result() as $row) {
			$id_transaksi = $row->id;
			$no_transaksi = $row->no_transaksi; 
			$tgl_transaksi = $row->tgl_transaksi;
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
				<div class="row">
					<div class="col-md-12">
						<label>No. Quarantine</label>
						<div class="input-group">
							<input id="txtnotransaksi_hdr" name="txtnotransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo $no_transaksi; ?>" readonly>
							<input id="txtidtransaksi_hdr" name="txtidtransaksi_hdr" type="hidden" class="form-control" value="<?php echo $id_transaksi; ?>" readonly>
						</div>
						<label>Tgl. Quarantine</label>
						<div class="input-group">
							<input id="txttgltransaksi_hdr" name="txttgltransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo date_format(new DateTime($tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>" readonly>
						</div>
					</div>
				</div>				
			</div>
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-12">
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
			<div class="col-md-4">
				<label>No. PO</label>
				<div>
					<select id="cbonopo" name="cbonopo" class="bs-select" data-live-search="true">
						<option value="0" selected>Pilih Nomor PO</option>
						<?php 
							foreach($get_po->result() as $row) {										
								echo "<option value='".$row->id_po."'>".$row->no_po." - ".$row->nama_supplier."</option>";
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-5">
				<label>Produk</label>
				<div>
					<select id="cboproduk" name="cboproduk" class="bs-select" data-live-search="true">
						<option hidden>Pilih Produk</option>
					</select>
				</div>
			</div>
			<div class="col-md-1">
				<label>Qty (Stok)</label>
				<div>
					<input id="txtqtystok" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)" disabled>
				</div>
			</div>
			<div class="col-md-2">
				<label>Qty Quarantine</label>
				<div>
					<input id="txtqty" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
				</div>
			</div>
		</div>
		<input id="txtidproduk" type="hidden">
		<input id="txtbatchnumber" type="hidden">
		<input id="txtexpireddate" type="hidden">
		&nbsp;
		<div class="row">
			<div class="col-md-11">
				<label>Alasan</label>
				<div>
					<input id="txtalasan" type="text" class="form-control" placeholder="Alasan Quarantine">
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
							<th style="display: none;">ID KEMASAN</th>
							<th style="width: 6%; text-align: center;">KEMASAN</th>
							<th style="width: 12%; text-align: center;">BATCH NUMBER</th>
							<th style="width: 10%; text-align: center;">EXPIRED DATE</th>
							<th style="width: 12%; text-align: right;">QTY QUARANTINE</th>
							<th style="width: 17%;">ALASAN</th>
							<th style="width: 10%; text-align: center;">NO. PO</th>
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
						<label>Qty</label>
						<div class="input-group group-qtyretur-edit">
							<input id="txtqty_edit" name="txtqty_edit" type="text" class="form-control" onkeypress="return hanya_angka(event)">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label>Alasan</label>
						<div class="input-group">
							<textarea id="txtalasan_edit" name="txtalasan_edit" class="form-control" rows="4"></textarea>
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