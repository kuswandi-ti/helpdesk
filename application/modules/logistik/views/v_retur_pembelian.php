<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {		
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "logistik/retur_pembelian/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Bulan 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tahun 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Supplier 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. GR 6
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Alasan Retur 7
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Keterangan 8
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 9
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 1, 2, 3, 4, 6, 9] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "10%", "targets": [9] } // Action
			]
		});
		
		$("#cbosupplier").on('change',function(){
			var id_supplier = $(this).val();
			var opt = '<option selected hidden>Pilih Nomor GR</option>';
			$.ajax({
				url: 'logistik/retur_pembelian/get_gr/'+id_supplier,
				dataType: 'json',
				type: 'get',
				success: function(json) {
					$.each(json, function(i, obj) {
						opt += "<option value='"+obj.id+"'>"+obj.no_transaksi+"</option>";
					});
					$("#cbonomorgr").html(opt);
					$("#cbonomorgr").selectpicker('refresh');
				}
			});
		});	
	});
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
				&nbsp;&nbsp;
				<button type="button" class="btn btn-info btn-icon-fixed pull-right btn-buat-retur-baru" data-toggle="modal" data-target="#modal-primary"><span class="icon-file-add"></span> Tambah Data Baru</button>
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
							<th class='text-center'>No.</th>
							<th class='text-center'>No. Transaksi</th>
							<th class='text-center'>Tgl Transaksi</th>
							<th class='text-center'>Bulan</th>
							<th class='text-center'>Tahun</th>
							<th>Nama Supplier</th>
							<th class='text-center'>No. GR</th>
							<th>Alasan Retur</th>
							<th>Keterangan</th>
							<th class='text-center'>Actions</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>		
	</div>
</div>

<!-- Begin : Modal Add Header -->
<div class="modal fade" id="modal-primary" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<form method="POST" action="logistik/retur_pembelian/create_header" class="form-horizontal">
			<div class="modal-content">
				<div class="modal-header">                        
					<h4 class="modal-title" id="modal-primary-header">Data Header</h4>
				</div>					
				<div class="modal-body">
					<div class="form-group">
						<label class="col-md-3 control-label">Bulan</label>
						<div class="col-md-4">
							<select name="cbobulan" class="bs-select" data-live-search="true">
								<?php
									for ($i=1; $i<=12; $i+=1) {
										$sel = $i == date('n') ? 'selected="selected"' : '';
										echo "<option value=$i $sel>".set_month_to_string_ind($i)."</option>";
									}
								?>
							</select>
						</div>
						<label class="col-md-1 control-label">Tahun</label>
						<div class="col-md-4">
							<select name="cbotahun" class="bs-select" data-live-search="true">
								<?php
									for($i=2020; $i>=2017; $i-=1) {
										$sel = $i == date('Y') ? ' selected="selected"' : '';
										echo"<option value=$i $sel> $i </option>";
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Supplier</label>
						<div class="col-md-9">
							<select id="cbosupplier" name="cbosupplier" class="bs-select" data-live-search="true">
								<option value="0" selected>Pilih Supplier</option>
								<?php 
									foreach($get_supplier->result() as $row) {										
										echo "<option value='".$row->supplier_id."'>".$row->nama_supplier."</option>";
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Nomor GR</label>
						<div class="col-md-9">
							<select id="cbonomorgr" name="cbonomorgr" class="bs-select" data-live-search="true">
								<option hidden>Pilih Nomor GR</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Alasan Retur</label>
						<div class="col-md-9">
							<textarea name="txtalasanretur" id="txtalasanretur" class="form-control" rows="3" placeholder="Alasan Retur"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Keterangan</label>
						<div class="col-md-9">
							<textarea name="txtketerangan" id="txtketerangan" class="form-control" rows="3" placeholder="Keterangan"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="form-group">
						<div class="col-md-12">
							<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>					
							<button type="submit" class="btn btn-primary">Simpan</button>
						</div>
					</div>	
				</div>
			</div>
		</form>
	</div>            
</div>
<!-- End : Modal Add Header -->
