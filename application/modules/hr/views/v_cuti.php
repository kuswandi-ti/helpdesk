<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {		
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "hr/cuti/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // NIK 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Karyawan 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Keterangan 5
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 6
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 1, 2, 3, 6] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "10%", "targets": [6] } // Action
			]
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
							<th class='text-center'>NIK</th>
							<th>Nama Karyawan</th>
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
		<form method="POST" action="hr/cuti/create_header" class="form-horizontal">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modal-primary-header">Data Header</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-md-3 control-label">Nama Karyawan</label>
						<div class="col-md-9">
							<select id="cbokaryawan" name="cbokaryawan" class="bs-select" data-live-search="true">
								<option selected hidden value="0">Pilih Karyawan</option>
								<?php 
									foreach($get_karyawan->result() as $row) {
										echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
									}
								?>
							</select>
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