<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {	
		detail_list();
		
		$("#simpan_header").click(function() {
			var v_id = $("#txtidtransaksi_hdr").val();
			var v_pic = $("#txtpic_hdr").val();
			var v_keterangan = $("#txtketerangan_hdr").val();
			$.ajax({
				url: 'logistik/pengawasan_pemeliharaan/update_header',
				data: {
					id: v_id,
					pic: v_pic,
					keterangan: v_keterangan
				},
				type: 'post',
				success: function(data) {
					if (data == 'done')
						window.location = "logistik/pengawasan_pemeliharaan";
				}
			});
		});
		
		$("#submit_detail").on("click", function() {
			var v_header_id = $("#txtidtransaksi_hdr").val();
			$.ajax({			
				url: "logistik/pengawasan_pemeliharaan/create_detail/",
				data: {   
						id_header: v_header_id,
						uraian: $("#txturaian").val(),
						tindak_lanjut: $("#txttindaklanjut").val(),
						status: $("#txtstatus").val()
				},
				method: "post",
				success: function(data) {
					$('#txturaian').val('');
					$("#txttindaklanjut").val('');
					$("#txtstatus").val('');
					detail_list();
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
				url   : 'logistik/pengawasan_pemeliharaan/detail_list/?hid='+hid,
				async : false,
				success : function(data) {
					$('#show_detail').html(data);
				}
			});
		}
		
		$("body").on("click", "#table_detail #hapus_detail", function() {
			var parentTr = $(this).closest('tr');
			var v_detail_id = parentTr.find('#id').html();
			var del = confirm("Hapus data ?");
			
			if (del == true) {
				$.ajax({
					url: 'logistik/pengawasan_pemeliharaan/delete_item_detail',
					type: 'post',
					data: {
						id_detail: v_detail_id
					},
					success: function(data) {
						if (data == 'done') {
							detail_list();
						}
					}
				});					
			}
		});
		
		$("body").on("click", "#table_detail #edit_detail", function() {
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('#id').html();
			var uraian = parentTr.find('#uraian').html();
			var tindak_lanjut = parentTr.find('#tindak_lanjut').html();
			var status = parentTr.find('#status').html();
			$("#txtid_edit").val(id);
			$("#txturaian_edit").val(uraian);
			$("#txttindaklanjut_edit").val(tindak_lanjut);
			$("#txtstatus_edit").val(status);
			$("#modal-edit").modal("show");
		});
		
		$("#simpan_detail").click(function() {
			var v_id = $("#txtid_edit").val();
			var v_uraian = $("#txturaian_edit").val();
			var v_tindak_lanjut = $("#txttindaklanjut_edit").val();
			var v_status = $("#txtstatus_edit").val();
			$.ajax({
				url: 'logistik/pengawasan_pemeliharaan/update_item_detail',
				data: {
					id: v_id,
					uraian: v_uraian,
					tindak_lanjut: v_tindak_lanjut,
					status: v_status
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
	$pic = '';
	$kategori = '';
	$keterangan = '';

    if ($get_header->num_rows() > 0) {
        foreach($get_header->result() as $row) {
			$id_transaksi = $row->id;
			$no_transaksi = $row->no_transaksi; 
			$tgl_transaksi = $row->tgl_transaksi;
			$pic = $row->pic;
			$kategori = $row->kategori == '1' ? 'Rutin' : 'Non Rutin';
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
						<div class="input-group">
							<label>No. Transaksi</label>
							<input id="txtnotransaksi_hdr" name="txtnotransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo $no_transaksi; ?>" readonly>
							<input id="txtidtransaksi_hdr" name="txtidtransaksi_hdr" type="hidden" class="form-control" value="<?php echo $id_transaksi; ?>" readonly>
						</div>						
						<div class="input-group">
							<label>Tgl. Transaksi</label>
							<input id="txttgltransaksi_hdr" name="txttgltransaksi_hdr" type="text" class="form-control input-sm" value="<?php echo date_format(new DateTime($tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>" readonly>
						</div>
					</div>
				</div>				
			</div>
			<div class="col-md-2">
				<div class="row">
					<div class="col-md-12">						
						<div class="input-group">
							<label>PIC</label>
							<input id="txtpic_hdr" name="txtpic_hdr" type="text" class="form-control input-sm" value="<?php echo $pic; ?>">
						</div>						
						<div class="input-group">
							<label>Kategori</label>
							<input id="txtkategori_hdr" name="txtkategori_hdr" type="text" class="form-control input-sm" value="<?php echo $kategori; ?>" readonly>
						</div>
					</div>
				</div>				
			</div>
			<div class="col-md-8">
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
			<div class="col-md-12">
				<label>Uraian</label>
				<div>
					<textarea id="txturaian" name="txturaian" class="form-control" rows="4" placeholder="Uraian"></textarea>
				</div>
			</div>
		</div>
		&nbsp;
		<div class="row">
			<div class="col-md-12">
				<label>Tindak Lanjut</label>
				<div>
					<textarea id="txttindaklanjut" name="txttindaklanjut" class="form-control" rows="4" placeholder="Tindak Lanjut"></textarea>
				</div>
			</div>
		</div>
		&nbsp;
		<div class="row">
			<div class="col-md-11">
				<label>Status</label>
				<div>
					<input id="txtstatus" name="txtstatus" type="text" class="form-control" placeholder="Status">
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
							<th style="width: 38%;">URAIAN</th>
							<th style="width: 38%;">TINDAK LANJUT</th>
							<th style="width: 11%; text-align: center;">STATUS</th>
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
						<label>Uraian</label>
						<div>
							<textarea id="txturaian_edit" name="txturaian_edit" class="form-control" rows="4" placeholder="Uraian"></textarea>
						</div>
					</div>
				</div>
				&nbsp;
				<div class="row">
					<div class="col-md-12">
						<label>Tindak Lanjut</label>
						<div>
							<textarea id="txttindaklanjut_edit" name="txttindaklanjut_edit" class="form-control" rows="4" placeholder="Tindak Lanjut"></textarea>
						</div>
					</div>
				</div>
				&nbsp;
				<div class="row">
					<div class="col-md-12">
						<label>Status</label>
						<div>
							<input id="txtstatus_edit" name="txtstatus_edit" type="text" class="form-control" placeholder="Status">
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