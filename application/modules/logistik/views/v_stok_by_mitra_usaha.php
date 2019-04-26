<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		table = $('#table_data').DataTable({
					"processing": true, // Feature control the processing indicator.
					"serverSide": true, // Feature control DataTables' server-side processing mode.
					"order": [], // Initial no order.
	 
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "logistik/stok_by_mitra_usaha/ajax_list",
						"type": "POST",
						"data": function ( data ) {
							data.id_supplier = $('#cbosupplier').val();
						}
					},
					"aoColumns": [
						{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama 1
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kemasan 2
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Satuan 3
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Batch Number 4
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Expired Date 5
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Supplier 6
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Stok Akhir 7
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Stok Min. 8
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Stok Max. 9
						{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 10
					],
					"columnDefs": [
						{ "className": "text-center", "targets": [0, 2, 3, 4, 5, 10] },
						{ "className": "text-right", "targets": [7, 8, 9] },
						{ "width": "5%", "targets": [0] },  // No.
						{ "width": "10%", "targets": [10] } // Action
					]
				});
		
		$('#btn-search').click(function(){ // button filter event click
			table.ajax.reload();  // just reload table
		});
		
		$('#btn-reset').click(function(){ // button reset event click
			$('#form-filter')[0].reset();
			table.ajax.reload();
		});
	});
	
	$("body").on("click", "#table_data .btn-detail", function() {
		$('.fetched-data').empty();
		
		var parentTr = $(this).closest('tr');
		var vid_produk = parentTr.find('.btn-detail').val();
		var vbatch_number = parentTr.find("td:eq(4)").text();
		var vexpired_date = parentTr.find("td:eq(5)").text();
		
		$.ajax({
			url: 'logistik/stok_by_mitra_usaha/tampil_stok_produk',
			type: 'post',
			async: false,
			data: {
				id_produk: vid_produk,
				batch_number: vbatch_number,
				expired_date: vexpired_date
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
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-12">						
						<center><h5><br>Custom Filter<br><br></h5></center>
					</div>
				</div>
				
				<form id="form-filter" class="form-horizontal">
					<div class="form-group">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<select id="cbosupplier" name="cbosupplier" class="bs-select" data-live-search="true">												
								<option hidden value="0">Pilih Supplier</option>
								<?php 
									foreach($get_supplier->result() as $row) {
										echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-md-4"></div>
					</div>
					<div class="form-group">
						<div class="col-md-4"></div>
						<div class="col-md-4">
							<div class="col-md-6">
								<button type="button" id="btn-search" class="btn btn-info btn-icon-fixed btn-block"><span class="fa fa-search"></span> Search...</button>
							</div>
							<div class="col-md-6">
								<button type="button" id="btn-reset" class="btn btn-danger btn-icon-fixed btn-block"><span class="fa fa-circle-o"></span> Reset</button>
							</div>
						</div>
						<div class="col-md-4"></div>
					</div>
				</form>
			</div>
			<div class="panel-body">			
				<table id="table_data" class="table table-head-custom table-striped" style="width: 100%">
					<thead>
						<tr>
							<th class="text-center">No.</th>
							<th>Nama Produk</th>
							<th class="text-center">Kemasan</th>
							<th class="text-center">Satuan</th>
							<th class="text-center">Batch Number</th>
							<th class="text-center">Expired Date</th>
							<th>Supplier</th>
							<th class="text-right">Stok Akhir</th>
							<th class="text-right">Min. Stok</th>
							<th class="text-right">Max. Stok</th>
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