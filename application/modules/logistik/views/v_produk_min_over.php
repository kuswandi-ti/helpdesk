<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "logistik/produk_min_over/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kemasan 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Satuan 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Supplier 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Stok 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Min. Stok 6
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Max. Stok 7
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 8
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 9
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 2, 3, 5, 6, 7, 8, 9] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "10%", "targets": [9] } // Action
			],
			// 5 = Stok Aktual, 6 = Min. Stok, 7 = Max. Stok
			"createdRow": function(row, data, dataIndex) {
                if (parseFloat(data[5]) < parseFloat(data[6])) {
                    $(row).addClass("text-danger");
                } else if (parseFloat(data[5]) > parseFloat(data[7])) {
                    $(row).addClass("text-warning");
                } else {
					$(row).addClass("text-default");
				}
            }
		});
	});
	
	$("body").on("click", "#table_data .btn-detail", function() {
		$('.fetched-data').empty();
		
		var parentTr = $(this).closest('tr');
		var id_produk = parentTr.find('.btn-detail').val();
		$.ajax({
			url: 'logistik/produk_min_over/tampil_stok_produk',
			type: 'post',
			async: false,
			data: {
				'id_produk': id_produk
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
		<div class="panel panel-primary">
			<div class="panel-body">				
				<table id="table_data" class="table table-head-custom table-striped" style="width: 100%">
					<thead>
						<tr>
							<th class="text-center">No.</th>
							<th>Nama Produk</th>
							<th class="text-center">Kemasan</th>
							<th class="text-center">Satuan</th>
							<th>Supplier</th>
							<th class="text-center">Stok Akhir</th>
							<th class="text-center">Min. Stok</th>
							<th class="text-center">Max. Stok</th>
							<th class="text-center">Status</th>
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