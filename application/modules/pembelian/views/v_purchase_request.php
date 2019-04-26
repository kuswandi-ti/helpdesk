<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
	$table_head = "
					<thead>
						<tr>
							<th class='text-center'>No.</th>
							<th class='text-center'>No. Transaksi</th>
							<th class='text-center'>Tgl Transaksi</th>
							<th class='text-center'>Bulan</th>
							<th class='text-center'>Tahun</th>
							<th>Deskripsi</th>
							<th class='text-center'>Jenis PR</th>
							<th class='text-center'>Status PR</th>
							<th class='text-center'>Histori</th>
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
				<button type="button" class="btn btn-info btn-icon-fixed" data-toggle="modal" data-target="#modal-primary"><span class="icon-file-add"></span> Buat PR Baru</button>     
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
						<li><a href="#tabs-pending" data-toggle="tab"><span class="fa fa-calendar-plus-o"></span> Pending</a></li>
						<li><a href="#tabs-approve" data-toggle="tab"><span class="fa fa-calendar-check-o"></span> Approved</a></li>
						<li><a href="#tabs-revisi" data-toggle="tab"><span class="fa fa-calendar-minus-o"></span> Revised</a></li>
						<li><a href="#tabs-reject" data-toggle="tab"><span class="fa fa-calendar-times-o"></span> Rejected</a></li>
					</ul>
					<div class="tab-content tab-content-bordered">						
						<div class="tab-pane active" id="tabs-draft">							
							<table id="table_data_draft" class="table table-head-custom table-striped" style="width: 100%">
								<?php echo $table_head; ?>
								<tbody></tbody>
							</table>
						</div>
						<div class="tab-pane" id="tabs-pending">
							<table id="table_data_pending" class="table table-head-custom table-striped" style="width: 100%">
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
						<div class="tab-pane" id="tabs-revisi">
							<table id="table_data_revisi" class="table table-head-custom table-striped" style="width: 100%">
								<?php echo $table_head; ?>
								<tbody></tbody>
							</table>
						</div>
						<div class="tab-pane" id="tabs-reject">
							<table id="table_data_reject" class="table table-head-custom table-striped" style="width: 100%">
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
		<form class="form-horizontal" method="POST" action="pembelian/purchase_request/create_header">
			<div class="modal-content">
				<div class="modal-header">                        
					<h4 class="modal-title" id="modal-primary-header">Data Header</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-md-2 control-label">Jenis PR</label>
						<div class="col-md-3">
							<div class="app-radio inline"> 
								<label class="text-danger"><input type="radio" name="rdojenispr" value="1" checked> Rutin</label>                                         
							</div>
						</div>
						<div class="col-md-3">
							<div class="app-radio inline"> 
								<label class="text-success"><input type="radio" name="rdojenispr" value="2"> Non Rutin</label> 
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Bulan</label>
						<div class="col-md-6">
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
						<div class="col-md-6">
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
						<label class="col-md-2 control-label">Deskripsi</label>
						<div class="col-md-10">
							<textarea name="txtdeskripsi" class="form-control" rows="10" placeholder="Deskripsi Transaksi"></textarea>
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

<div class="modal fade" id="modal-histori" tabindex="-1" role="dialog" aria-labelledby="modal-default-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-default-header">Histori Status Purchase Request (PR)</h4>
			</div>
			<div class="modal-body">
				
			</div>
		</div>
	</div>            
</div>