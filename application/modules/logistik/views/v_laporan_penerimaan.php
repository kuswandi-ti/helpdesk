<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		table = $('#table_data').DataTable({
					"processing": true, // Feature control the processing indicator.
					"serverSide": true, // Feature control DataTables' server-side processing mode.
					"order": [], // Initial no order.
	 
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "logistik/laporan_penerimaan/ajax_list",
						"type": "POST",
					},
					"aoColumns": [
						{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. GR 1
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. GR 2
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Supplier 3
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. PO 4
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. SJ Supplier 5
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Terima 6
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Keterangan 7
						{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 8
					],
					"columnDefs": [
						{ "className": "text-center", "targets": [0, 1, 2, 4, 5, 6, 8] },
						{ "width": "5%", "targets": [0] },  // No.
						{ "width": "10%", "targets": [8] } // Action
					]
				});		
		
	});
	
	$("body").on("click", "#table_data .btn-detail", function() {
		$('.fetched-data').empty();
		
		var parentTr = $(this).closest('tr');
		var id = parentTr.find('.btn-detail').val();
		$.ajax({
			url: 'logistik/laporan_penerimaan/tampil_detail',
			type: 'post',
			async: false,
			data: {
				'id': id
			},
			success: function(data) {				
				$('#modal-detail').modal('show');
				$('#modal-primary-header').html('Detail Stok Produk');				
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
							<th class="text-center">No. GR</th>
							<th class="text-center">Tgl. GR</th>
							<th>Supplier</th>
							<th class="text-center">No. PO</th>
							<th class="text-center">No. SJ Supplier</th>
							<th class="text-center">Tgl. Terima</th>
							<th>Keterangan</th>
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
</div>-