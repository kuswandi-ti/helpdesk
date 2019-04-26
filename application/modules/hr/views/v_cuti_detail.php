<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {	
		detail_list();
		
		$('#txttanggalawal_add').datetimepicker({ format: "DD-MM-YYYY" });
		$('#txttanggalawal_edit').datetimepicker({ format: "DD-MM-YYYY" });
		
		$('#txttanggalakhir_add').datetimepicker({ format: "DD-MM-YYYY" });
		$('#txttanggalakhir_edit').datetimepicker({ format: "DD-MM-YYYY" });
		
		$("#simpan_header").click(function() {
			var v_id = $("#txtidtransaksi_hdr").val();
			var v_keterangan = $("#txtketerangan_hdr").val();
			$.ajax({
				url: 'hr/cuti/update_header',
				data: {
					id: v_id,
					keterangan: v_keterangan
				},
				type: 'post',
				success: function(data) {
					if (data == 'done')
						window.location = "hr/cuti";
				}
			});
		});
		
		$("#submit_detail").on("click", function() {
			if ($('#cbocuti_add').val() == 0) {
				$('.group-cuti-add').addClass('has-error');
				return false;
			} else {
				$('.group-cuti-add').removeClass('has-error');
			}
			if ($.trim($('#txttanggalawal_add').val()).length == 0) {
				$('.group-tanggal-awal-add').addClass('has-error');
				return false;
			} else {
				$('.group-tanggal-awal-add').removeClass('has-error');
			}
			if ($.trim($('#txttanggalakhir_add').val()).length == 0) {
				$('.group-tanggal-akhir-add').addClass('has-error');
				return false;
			} else {
				$('.group-tanggal-akhir-add').removeClass('has-error');
			}
			
			var v_header_id = $("#txtidtransaksi_hdr").val();
			$.ajax({			
				url: "hr/cuti/create_detail/",
				data: {   
						id_header: v_header_id,
						id_cuti: $("#cbocuti_add").val(),
						tanggal_awal: $("#txttanggalawal_add").val(),
						tanggal_akhir: $("#txttanggalakhir_add").val(),
						keterangan: $("#txtketerangan_add").val()
				},
				method: "post",
				success: function(data) {
					$('#cbocuti_add').val('0'); $("#cbocuti_add").selectpicker('refresh');
					$("#txttanggalawal_add").val('');
					$("#txttanggalakhir_add").val('');
					$("#txtketerangan_add").val('');
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
				url   : 'hr/cuti/detail_list/?hid='+hid,
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
					url: 'hr/cuti/delete_item_detail',
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
			var id_cuti = parentTr.find('#id_cuti').html();
			var nama_cuti = parentTr.find('#nama_cuti').html();
			var tanggal_awal = parentTr.find('#tanggal_awal').html();
			var tanggal_akhir = parentTr.find('#tanggal_akhir').html();
			var keterangan = parentTr.find('#keterangan').html();
			$("#txtid_edit").val(id);
			$('#cbocuti_edit').val(id_cuti); $("#cbocuti_edit").selectpicker('refresh');
			$("#txttanggalawal_edit").val(tanggal_awal);
			$("#txttanggalakhir_edit").val(tanggal_akhir);
			$("#txtketerangan_edit").val(keterangan);
			$("#modal-edit").modal("show");
		});
		
		$("#simpan_detail").click(function() {
			var v_id = $("#txtid_edit").val();
			var v_id_cuti = $("#cbocuti_edit").val();
			var v_tanggal_awal = $("#txttanggalawal_edit").val();
			var v_tanggal_akhir = $("#txttanggalakhir_edit").val();
			var v_keterangan = $("#txtketerangan_edit").val();
			$.ajax({
				url: 'hr/cuti/update_item_detail',
				data: {
					id: v_id,
					id_cuti: v_id_cuti,
					tanggal_awal: v_tanggal_awal,
					tanggal_akhir: v_tanggal_akhir,
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
	$nik = '';
	$nama_karyawan = '';
	$keterangan = '';

    if ($get_header->num_rows() > 0) {
        foreach($get_header->result() as $row) {
			$id_transaksi = $row->id;
			$no_transaksi = $row->no_transaksi; 
			$tgl_transaksi = $row->tgl_transaksi;
			$nik = $row->nik;
			$nama_karyawan = $row->nama_karyawan;
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
							<label>NIK</label>
							<input id="txtnik_hdr" name="txtnik_hdr" type="text" class="form-control input-sm" value="<?php echo $nik; ?>" readonly>
						</div>						
						<div class="input-group">
							<label>Nama Karyawan</label>
							<input id="txtnamakaryawan_hdr" name="txtnamakaryawan_hdr" type="text" class="form-control input-sm" value="<?php echo $nama_karyawan; ?>" readonly>
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
			<div class="col-md-3 group-cuti-add">
				<label>Jenis Cuti</label>
				<div>
					<select id="cbocuti_add" name="cbocuti_add" class="bs-select" data-live-search="true">
						<option selected hidden value="0">Pilih Cuti</option>
						<?php 
							foreach($get_cuti->result() as $row) {
								echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-2 group-tanggal-awal-add">
				<label>Tanggal Awal</label>
				<div>
					<input type="text" name="txttanggalawal_add" id="txttanggalawal_add" class="form-control" placeholder="Tanggal Awal Cuti" />
				</div>
			</div>
			<div class="col-md-2 group-tanggal-akhir-add">
				<label>Tanggal Akhir</label>
				<div>
					<input type="text" name="txttanggalakhir_add" id="txttanggalakhir_add" class="form-control" placeholder="Tanggal Akhir Cuti" />
				</div>
			</div>
			<div class="col-md-5 group-keterangan-add">
				<label>Keterangan</label>
				<div>
					<input type="text" name="txtketerangan_add" id="txtketerangan_add" class="form-control" placeholder="Keterangan" />
				</div>
			</div>
			<div class="col-md-2">
				<label>&nbsp;</label>
				<button id="submit_detail" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
			</div>
		</div>
		&nbsp;
		<div class="row">
			<div class="col-md-12">            
				<table id="table_detail" class="table table-head-custom table-striped" style="width: 100%;">
					<thead>
						<tr>
							<th style="display: none;">ID</th>
							<th style="width: 4%; text-align: center;">NO</th>
							<th style="display: none;">ID CUTI</th>
							<th style="width: 25%; text-align: center;">JENIS CUTI</th>
							<th style="width: 12%; text-align: center;">TANGGAL AWAL</th>
							<th style="width: 12%; text-align: center;">TANGGAL AKHIR</th>
							<th style="width: 38%;">KETERANGAN</th>
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
					<div class="col-md-12 group-cuti-edit">
						<label>Jenis Cuti</label>
						<div>
							<select id="cbocuti_edit" name="cbocuti_edit" class="bs-select" data-live-search="true">
								<option selected hidden value="0">Pilih Cuti</option>
								<?php 
									foreach($get_cuti->result() as $row) {
										echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
									}
								?>
							</select>
						</div>
					</div>
				</div>
				&nbsp;
				<div class="row">
					<div class="col-md-12 group-tanggal-awal-edit">
						<label>Tanggal Awal</label>
						<div>
							<input type="text" name="txttanggalawal_edit" id="txttanggalawal_edit" class="form-control" placeholder="Tanggal Awal Cuti" />
						</div>
					</div>
				</div>
				&nbsp;
				<div class="row">
					<div class="col-md-12 group-tanggal-akhir-edit">
						<label>Tanggal Akhir</label>
						<div>
							<input type="text" name="txttanggalakhir_edit" id="txttanggalakhir_edit" class="form-control" placeholder="Tanggal Akhir Cuti" />
						</div>
					</div>
				</div>
				&nbsp;
				<div class="row">
					<div class="col-md-12 group-keterangan-edit">
						<label>Keterangan</label>
						<div>
							<input type="text" name="txtketerangan_edit" id="txtketerangan_edit" class="form-control" placeholder="Keterangan" />
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