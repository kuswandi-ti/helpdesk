<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
							<th class="text-center">No. Transaksi</th>
							<th class="text-center">Tgl Transaksi</th>
							<th class="text-center">Supplier ID</th>
                            <th class="text-center">Nama Supplier</th>
							<th class="text-center">No. Invoice Supplier</th>
							<th class="text-center">Tgl. Invoice Supplier</th>
							<th class="text-center">Tgl. Jatuh Tempo</th>
							<th class="text-center">Keterangan</th>
							<th class="text-center">Validasi</th>
							<th class="text-center">Grand Total</th>
							<th class="text-center">Actions</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>		
	</div>
</div>