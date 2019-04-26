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
							<th>Nama Supplier</th>
							<th>Keterangan</th>
							<th class='text-center'>No. PR</th>
							<th class='text-center'>Status</th>
							<th class='text-center'>Histori</th>
							<th class='text-right'>Grand Total</th>
							<th class='text-center'>Pengirim</th>
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
		</div>                                                         
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-body"> 
				<table id="table_data_approve" class="table table-head-custom table-striped" style="width: 100%">
					<?php echo $table_head; ?>
					<tbody></tbody>
				</table>
			</div>
		</div>		
	</div>
</div>

<div class="modal fade" id="modal-histori" tabindex="-1" role="dialog" aria-labelledby="modal-default-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-default-header">Histori Status Pemesanan Pembelian (PO)</h4>
			</div>
			<div class="modal-body">
				
			</div>
		</div>
	</div>            
</div>