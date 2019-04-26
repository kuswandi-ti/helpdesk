<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#table_data_draft').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "logistik/stock_opname/get_data/0",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kode Lokasi 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Bulan 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tahun 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Deskripsi 6
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 7
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 8
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 1, 2, 3, 4, 5, 7, 8] },
				{ "width": "5%", "targets": [0] }, // No
				{ "width": "11%", "targets": [8] } // Action
			]
		});
		
		$('#table_data_approve').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "logistik/stock_opname/get_data/1",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No 0 
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kode Lokasi 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Bulan 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tahun 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Deskripsi 6
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 7
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 8
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 1, 2, 3, 4, 5, 7, 8] },
				{ "width": "5%", "targets": [0] }, // No
				{ "width": "11%", "targets": [8] } // Action
			]
		});			
	});
	
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();			
            reader.onload = function (e) {
                $('#image')
                    .attr('src', e.target.result)
                    .width(250)
                    .height(400);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php
	$table_head = "
					<thead>
						<tr>
							<th class='text-center'>No.</th>
							<th class='text-center'>No. Transaksi</th>
							<th class='text-center'>Tgl Transaksi</th>
							<th class='text-center'>Kode Lokasi</th>
							<th class='text-center'>Bulan</th>
							<th class='text-center'>Tahun</th>
							<th>Deskripsi</th>
							<th class='text-center'>Status</th>
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
				<button type="button" class="btn btn-info btn-icon-fixed" data-toggle="modal" data-target="#modal-primary"><span class="icon-file-add"></span> Buat Data Baru</button>     
			</div>
		</div>                                                         
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-body">   
				<div>
					<ul class="nav nav-tabs tab-content-bordered">
						<li class="active"><a href="#tabs-draft" data-toggle="tab"><span class="fa fa-calendar-o"></span> Drafted</a></li>
						<li><a href="#tabs-approve" data-toggle="tab"><span class="fa fa-calendar-check-o"></span> Approved</a></li>
					</ul>
					<div class="tab-content tab-content-bordered">						
						<div class="tab-pane active" id="tabs-draft">							
							<table id="table_data_draft" class="table table-head-custom table-striped" style="width: 100%">
								<?php echo $table_head; ?>
								<tbody></tbody>
							</table>
						</div>
						<div class="tab-pane" id="tabs-approve">
							<table id="table_data_approve" class="table table-head-custom table-striped" style="width: 100%">
								<?php echo $table_head; ?>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>
</div>

<!-- Begin : Modal Add Header -->
<div class="modal fade" id="modal-primary" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<form class="form-horizontal" method="POST" action="logistik/stock_opname/create_header" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modal-primary-header">Data Header</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-md-2 control-label">Bulan</label>
						<div class="col-md-10">
							<select name="cbobulan" class="bs-select" data-live-search="true">
								<?php
									for ($i=1; $i<=12; $i+=1) {
										$sel = $i == date('n') ? 'selected="selected"' : '';
										echo "<option value=$i $sel>".set_month_to_string_ind($i)."</option>";
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Tahun</label>
						<div class="col-md-10">
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
						<label class="col-md-2 control-label">Lokasi</label>
						<div class="col-md-10">
							<select name='cbolokasi' id='cbolokasi' class='form-control bs-select' data-live-search='true'>
								<?php
									if ($get_lokasi->num_rows() > 0) {
										foreach ($get_lokasi->result() as $row) {
											echo "<option value='".$row->id."'>".$row->kode."</option>";
										}
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Deskripsi</label>
						<div class="col-md-10">
							<textarea name="txtdeskripsi" class="form-control" rows="10" placeholder="Deskripsi Transaksi"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Dokumen</label>
						<div class="col-md-10">
							<input type="file" name="file_bukti_dokumen" accept="image/x-png,image/gif,image/jpeg,image/jpg" onChange="readURL(this);" required>
							<span style="font-size:11px; color:#666666;">only gif, png, jpg, jpeg files. <br> max. size image 250x400</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<img id="image" name="image" src="#" alt="" height="400" width="250" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>					
					<button type="submit" class="btn btn-primary">Simpan</button>					
				</div>
			</div>
		</form>
	</div>            
</div>
<!-- End : Modal Add Header -->