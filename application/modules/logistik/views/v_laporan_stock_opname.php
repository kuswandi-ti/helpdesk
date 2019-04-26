<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		table = $('#table_data').DataTable({
					"processing": true, // Feature control the processing indicator.
					"serverSide": true, // Feature control DataTables' server-side processing mode.
					"order": [], // Initial no order.
	 
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "logistik/laporan_stock_opname/ajax_list",
						"type": "POST",
					},
					"aoColumns": [
						{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No Transaksi 1
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl Transaksi 2
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kode Lokasi 3
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Deskripsi 4
						{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 5
					],
					"columnDefs": [
						{ "className": "text-center", "targets": [0, 1, 2, 3, 5] },
						{ "width": "5%", "targets": [0] },  // No.
						{ "width": "10%", "targets": [5] } // Action
					]
				});
	});
	
	$("body").on("click", "#table_data .btn-detail", function() {
		$('.fetched-data').empty();
		
		var parentTr = $(this).closest('tr');
		var id_header = parentTr.find('.btn-detail').val();
		$.ajax({
			url: 'logistik/laporan_stock_opname/tampil_data_transaksi',
			type: 'post',
			async: false,
			data: {
				'id_header': id_header
			},
			success: function(data) {				
				$('#modal-detail').modal('show');
				$('#modal-primary-header').html('Detail Transaksi');				
				$('.fetched-data').append(data);
			},
			error: function() {
				alert('failure');
			}
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
		</div>                                                         
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">			
				<table id="table_data" class="table table-head-custom table-striped" style="width: 100%">
					<thead>
						<tr>
							<th class="text-center">No.</th>
							<th class="text-center">No Transaksi</th>
							<th class="text-center">Tgl Transaksi</th>
							<th class="text-center">Lokasi</th>
							<th>Deskripsi</th>
							<th class="text-center">Actions</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-primary modal-fw" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modal-primary-header"></h4>
			</div>
			<div id="modal-body" class="modal-body">
				<div id="fetched-data" class="fetched-data"></div>
			</div>
		</div>
	</div>
</div>