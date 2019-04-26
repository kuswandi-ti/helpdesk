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
				<button type="button" class="btn btn-success btn-icon-fixed pull-right btn-buat-po-list" data-toggle="modal" data-target="#modal-list"><span class="icon-file-add"></span> List PR</button>
			</div>
			<div class="heading-elements">
				&nbsp;&nbsp;
				<button type="button" class="btn btn-info btn-icon-fixed pull-right btn-buat-po-baru" data-toggle="modal" data-target="#modal-primary"><span class="icon-file-add"></span> Buat PO Baru</button>
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
		<form method="POST" action="pembelian/purchase_order/create_header" class="form-horizontal">
			<div class="modal-content">
				<div class="modal-header">                        
					<h4 class="modal-title" id="modal-primary-header">Data Header</h4>
				</div>
				<div class="modal-body">
					<div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Bulan</label>
                                </div>
                                <div class="col-md-6">
									<select name="cbobulan" style="width: 190%; height: 40px;">
										<?php
											for ($i=1; $i<=12; $i+=1) {
												$sel = $i == date('n') ? 'selected="selected"' : '';
												echo "<option value=$i $sel>".set_month_to_string_ind($i)."</option>";
											}
										?>
									</select>
                                </div>
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="row">
                                <div class="col-md-7">
                                    <label class="control-label pull-right">Tahun</label>
                                </div>
                                <div class="col-md-5">
                                    <select name="cbotahun" style="width: 100%; height: 40px;">
										<?php
											for($i=2020; $i>=2017; $i-=1) {
												$sel = $i == date('Y') ? ' selected="selected"' : '';
												echo"<option value=$i $sel> $i </option>";
											}
										?>
									</select>
                                </div>
                            </div>
                        </div>
					</div>
					&nbsp;
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Nomor PR</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="txtnomorpr" id="txtnomorpr" class="form-control" placeholder="Ketik nomor PR">
                                    <input type="hidden" name="txtidpr" id="txtidpr" class="form-control">
                                </div>
                            </div>
                        </div>
					</div>
					&nbsp;
					<div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Supplier</label>
                                </div>
                                <div class="col-md-9 cbosupplier">
                                    <select name="cbosupplier" id="cbosupplier" style="width: 100%; height: 40px;">
									
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Alamat Pengiriman</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="txtalamatpengiriman" id="txtalamatpengiriman" class="form-control" rows="3" placeholder="Alamat Pengiriman"><?php echo "PT TATA USAHA INDONESIA \nLimus Pratama Regency \nJl. Sumenep II Blok B1/41 \nCileungsi \nKab. Bogor \nJawa Barat \n16820"; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Tipe Pembayaran</label>
                                </div>
                                <div class="col-md-6">
									<select name="cbotipepembayaran" id="cbotipepembayaran" style="width: 150%; height: 40px;">
										<?php 
											foreach($get_tipe_pembayaran->result() as $row) {
												echo "<option value='$row->id'>".$row->tipe_pembayaran."</option>";
											}
										?>
									</select>
                                </div>
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="row">
                                <div class="col-md-7">
                                    <label class="control-label pull-right">T.O.P (hari)</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="txttop" id="txttop" class="form-control" value="0" onkeypress="return hanya_angka(event)">
                                </div>
                            </div>
                        </div>
					</div>
					&nbsp;
					<div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Tgl. Pengiriman</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="txttglpengiriman" id="txttglpengiriman" class="form-control" value="<?php echo date($this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Keterangan</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="txtketerangan" id="txtketerangan" class="form-control" rows="3" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
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

<!-- Begin : Modal Add Header -->
<div class="modal fade" id="modal-list" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<form method="POST" action="pembelian/purchase_order/create_header_list" class="form-horizontal">
			<div class="modal-content">
				<div class="modal-header">                        
					<h4 class="modal-title" id="modal-primary-header">Data Header (List PR)</h4>
				</div>
				<div class="modal-body">
					<div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Bulan</label>
                                </div>
                                <div class="col-md-6">
									<select name="cbobulan" style="width: 190%; height: 40px;">
										<?php
											for ($i=1; $i<=12; $i+=1) {
												$sel = $i == date('n') ? 'selected="selected"' : '';
												echo "<option value=$i $sel>".set_month_to_string_ind($i)."</option>";
											}
										?>
									</select>
                                </div>
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="row">
                                <div class="col-md-7">
                                    <label class="control-label pull-right">Tahun</label>
                                </div>
                                <div class="col-md-5">
                                    <select name="cbotahun" style="width: 100%; height: 40px;">
										<?php
											for($i=2020; $i>=2017; $i-=1) {
												$sel = $i == date('Y') ? ' selected="selected"' : '';
												echo"<option value=$i $sel> $i </option>";
											}
										?>
									</select>
                                </div>
                            </div>
                        </div>
					</div>
					&nbsp;
					<div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Supplier</label>
                                </div>
                                <div class="col-md-9 cbosupplier">
                                    <select name='cbosupplier' id='cbosupplier' class='form-control bs-select' data-live-search="true">
										<?php
											if ($get_supplier_produk->num_rows() > 0) {
												foreach ($get_supplier_produk->result() as $row) {
													echo "<option value='".$row->supplier_id."'>".$row->nama_supplier."</option>";
												}
											}
										?>
									</select>
                                </div>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Alamat Pengiriman</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="txtalamatpengiriman" id="txtalamatpengiriman" class="form-control" rows="3" placeholder="Alamat Pengiriman"><?php echo "PT TATA USAHA INDONESIA \nLimus Pratama Regency \nJl. Sumenep II Blok B1/41 \nCileungsi \nKab. Bogor \nJawa Barat \n16820"; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Tipe Pembayaran</label>
                                </div>
                                <div class="col-md-6">
									<select name="cbotipepembayaranlist" id="cbotipepembayaranlist" style="width: 150%; height: 40px;">
										<?php 
											foreach($get_tipe_pembayaran->result() as $row) {
												echo "<option value='$row->id'>".$row->tipe_pembayaran."</option>";
											}
										?>
									</select>
                                </div>
                            </div>
                        </div>
						<div class="col-md-6">
                            <div class="row">
                                <div class="col-md-7">
                                    <label class="control-label pull-right">T.O.P (hari)</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="txttoplist" id="txttoplist" class="form-control" value="0" onkeypress="return hanya_angka(event)">
                                </div>
                            </div>
                        </div>
					</div>
					&nbsp;
					<div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Tgl. Pengiriman</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="txttglpengirimanlist" id="txttglpengirimanlist" class="form-control" value="<?php echo date($this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="control-label">Keterangan</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="txtketerangan" id="txtketerangan" class="form-control" rows="3" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
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
				<h4 class="modal-title" id="modal-default-header">Histori Status Purchase Order (PO)</h4>
			</div>
			<div class="modal-body modal-body-histori">
				
			</div>
		</div>
	</div>            
</div>
