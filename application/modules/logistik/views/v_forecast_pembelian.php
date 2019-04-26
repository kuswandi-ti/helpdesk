<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "logistik/forecast_pembelian/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tahun 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Keterangan 4
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 5
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 1, 2, 3, 5] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "10%", "targets": [5] } // Action
			]
		});
	});
	
	function reset_input() {
		$('#txtid').val('');
		$('#txtketerangan').val('');
	}
</script>

<?php
	$table_head = "
					<thead>
						<tr>
							<th class='text-center'>No.</th>
							<th class='text-center'>No. Transaksi</th>
							<th class='text-center'>Tgl Transaksi</th>
							<th class='text-center'>Tahun</th>
							<th>Keterangan</th>
							<th class='text-center'>Actions</th>
						</tr>
					</thead>";
?>

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
				<button type="button" class="btn btn-info btn-icon-fixed pull-right btn-buat-fb-baru" data-toggle="modal" data-target="#modal-create-edit"><span class="icon-file-add"></span> Buat Forecast Beli Baru</button>
			</div>
		</div>                                                         
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-body">   					
				<table id="table_data" class="table table-head-custom table-striped" style="width: 100%">
					<?php echo $table_head; ?>
					<tbody></tbody>
				</table>
			</div>
		</div>		
	</div>
</div>

<!-- Begin : Modal Add Header -->
<div class="modal fade" id="modal-create-edit" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-info modal-md" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<form name="form-create-edit" id="form-create-edit" action="logistik/forecast_pembelian/create_header" method="POST" class="form-horizontal">
			<div class="modal-content">
				<div class="modal-header">                        
					<h4 class="modal-title" id="modal-primary-header"></h4>
					<input type="hidden" name="txtid" id="txtid">
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-2 control-label">Tahun</label>
								<div class="col-md-10">									
									<select name="cbotahun" id="cbotahun" class="bs-select" data-live-search="true">
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
								<label class="col-md-2 control-label">Keterangan</label>
								<div class="col-md-10">
									<textarea name="txtketerangan" id="txtketerangan" class="form-control" rows="5" placeholder="Keterangan"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary add-edit">Simpan</button>					
				</div>
			</div>
		</form>
	</div>
</div>
<!-- End : Modal Add Header -->