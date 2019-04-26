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
				<div class="row">
					<div class="col-md-5">&nbsp;</div>
					<div class="col-md-2">
						<label>Lorong</label>
						<select name='cbolorong' id='cbolorong' class='form-control bs-select' data-live-search='true'>
							<option value="0" selected>Pilih Lorong</option>
							<?php
								if ($get_lorong->num_rows() > 0) {
									foreach ($get_lorong->result() as $row) {
										echo "<option value='".$row->lorong."'>".$row->lorong."</option>";
									}
								}
							?>
						</select>
					</div>
					<div class="col-md-5">&nbsp;</div>
				</div>
				&nbsp;
				<div class="row">
					<div class="col-md-5">&nbsp;</div>
					<div class="col-md-2">
						<label>Rak</label>
						<select name='cborak' id='cborak' class='form-control bs-select' data-live-search='true'>
							<option value="0" selected>Pilih Rak</option>
							<?php
								if ($get_rak->num_rows() > 0) {
									foreach ($get_rak->result() as $row) {
										echo "<option value='".$row->rak."'>".$row->rak."</option>";
									}
								}
							?>
						</select>
					</div>
					<div class="col-md-5">&nbsp;</div>
				</div>
				&nbsp;
				<div class="row">
					<div class="col-md-5">&nbsp;</div>
					<div class="col-md-2">
						<label>Baris</label>
						<select name='cbobaris' id='cbobaris' class='form-control bs-select' data-live-search='true'>
							<option value="0" selected>Pilih Baris</option>
							<?php
								if ($get_baris->num_rows() > 0) {
									foreach ($get_baris->result() as $row) {
										echo "<option value='".$row->baris."'>".$row->baris."</option>";
									}
								}
							?>
						</select>
					</div>
					<div class="col-md-5">&nbsp;</div>
				</div>
				&nbsp;
				<div class="row">
					<div class="col-md-5">&nbsp;</div>
					<div class="col-md-2">
						<label>Kolom</label>
						<select name='cbokolom' id='cbokolom' class='form-control bs-select' data-live-search='true'>
							<option value="0" selected>Pilih Kolom</option>
							<?php
								if ($get_kolom->num_rows() > 0) {
									foreach ($get_kolom->result() as $row) {
										echo "<option value='".$row->kolom."'>".$row->kolom."</option>";
									}
								}
							?>
						</select>
					</div>
					<div class="col-md-5">&nbsp;</div>
				</div>
				&nbsp;
				<div class="row">
					<div class="col-md-5">&nbsp;</div>
					<div class="col-md-2">
						<button type="button" class="btn btn-info btn-icon-fixed btn-block"
						        onclick="window.open('logistik/cetak_stok_opname/cetak_stok_opname?l='+ document.getElementById('cbolorong').value +'&r='+document.getElementById('cborak').value+'&b='+document.getElementById('cbobaris').value+'&k='+document.getElementById('cbokolom').value, '_blank')">
								<span class="fa fa-print"></span>
								Cetak
						</button>
					</div>
					<div class="col-md-5">&nbsp;</div>
				</div>
			</div>
		</div>		
	</div>
</div>