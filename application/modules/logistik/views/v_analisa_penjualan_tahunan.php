<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		table = $('#table_data').DataTable({
					"processing": true, // Feature control the processing indicator.
					"serverSide": true, // Feature control DataTables' server-side processing mode.
					"order": [], // Initial no order.
	 
					// Load data for the table's content from an Ajax source
					"ajax": {
						"url": "logistik/analisa_penjualan_tahunan/ajax_list",
						"type": "POST",
						"data": function ( data ) {
							data.tahun = $('#cbotahun').val();
						}
					},
					"aoColumns": [
						{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tahun 1
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Jan 2
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Feb 3
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Mar 4
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Apr 5
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Mei 6
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Jun 7
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Jul 8
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Agu 9
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Sep 10
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Okt 11
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nov 12
						{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Des 13
					],
					"columnDefs": [
						{ "className": "text-center", "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13] },
						{ "width": "5%", "targets": [0] },  // No.
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
							<select name='cbotahun' id='cbotahun' class='form-control bs-select' data-live-search='true'>
								<option selected hidden value="0">Pilih Tahun</option>
								<?php
									for($i=2020; $i>=2017; $i-=1) {
										echo"<option value=".$i.">".$i."</option>";
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
							<th class="text-center">Tahun</th>
							<th class="text-center">Jan</th>
							<th class="text-center">Feb</th>
							<th class="text-center">Mar</th>
							<th class="text-center">Apr</th>
							<th class="text-center">Mei</th>
							<th class="text-center">Jun</th>
							<th class="text-center">Jul</th>
							<th class="text-center">Agu</th>
							<th class="text-center">Sep</th>
							<th class="text-center">Okt</th>
							<th class="text-center">Nov</th>
							<th class="text-center">Des</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>