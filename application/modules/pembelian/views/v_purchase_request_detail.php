<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
	
	$id_transaksi = '';
    $no_transaksi = ''; 
	$tgl_transaksi = '';
	$bulan = '';
	$tahun = '';
	$deskripsi = '';
	$jenis_pr = '';
	$nama_jenis_pr = '';
	$status_pr = '';
    $nama_status_pr = '';

    if ($get_header->num_rows() > 0) {
        foreach($get_header->result() as $row) {
			$id_transaksi = $row->id_header;
            $no_transaksi = $row->no_transaksi;
            $tgl_transaksi = $row->tgl_transaksi;
			$bulan = $row->bulan;
			$tahun = $row->tahun;
            $deskripsi = $row->deskripsi;
			$jenis_pr = $row->jenis_pr;
			$nama_jenis_pr = $row->nama_jenis_pr;
			$status_pr = $row->status_pr;
            $nama_status_pr = $row->nama_status_pr;
        } 
    }
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Data Header</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">
						<label>No. Transaksi</label>
						<div class="input-group">
							<input type="text" class="form-control input-sm" value="<?php echo $no_transaksi; ?>" readonly>
							<input id="txtidtransaksi" type="hidden" class="form-control input-sm" value="<?php echo $id_transaksi; ?>" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<label>Tgl. Transaksi</label>
						<div class="input-group">
							<input type="text" class="form-control input-sm" value="<?php echo date_format(new DateTime($tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY')); ?>" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<label>Bulan</label>
						<div class="input-group">
							<select name="cbobulan_header" id="cbobulan_header" style="width: 180px; height: 30px;">
								<?php
									for ($i=1; $i<=12; $i+=1) {
										$sel = $i == $bulan ? 'selected="selected"' : '';
										echo "<option value=$i $sel>".set_month_to_string_ind($i)."</option>";
									}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label>Jenis PR</label>
						<div class="input-group">
							<input type="text" class="form-control input-sm" value="<?php echo $nama_jenis_pr; ?>" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<label>Status PR</label>
						<div class="input-group">
							<input type="text" class="form-control input-sm" value="<?php echo $nama_status_pr; ?>" readonly>
						</div>
					</div>
					<div class="col-md-4">
						<label>Tahun</label>
						<div class="input-group">
							<select name="cbotahun_header" id="cbotahun_header" style="width: 180px; height: 30px;">
								<?php
									for($i=2020; $i>=2017; $i-=1) {
										$sel = $i == $tahun ? ' selected="selected"' : '';
										echo"<option value=$i $sel> $i </option>";
									}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="col-md-12">
					<label>Deskripsi</label>
					<div class="input-group">
						<textarea class="form-control" rows="4" id="txtdeskripsi_header"><?php echo $deskripsi; ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2">
				<?php
					echo "&nbsp;&nbsp;";
					if ($status_pr == 1 || $status_pr == 4) { // Draft / Revisi                
						echo '<button class="btn btn-block btn-success btn-icon-fixed" id="simpan_header"><span class="icon-floppy-disk"></span> Simpan</button>';
					}
				?>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Data Detail</h3>
	</div>
	<div class="panel-body">
		<?php
			if ($status_pr == 1 || $status_pr == 4) {
		?>
				<div class="row">
					<div class="col-md-4">
						<label>Nama Produk</label>
						<div class="input-group">
							<input id="txtnamaproduk" type="text" class="form-control" placeholder="Ketik nama produk" required>
							<input id="txtprodukid" type="hidden" class="form-control">
						</div>
					</div>
					<div class="col-md-2">
						<label>Isi Kemasan</label>
						<div class="input-group">
							<input id="txtisikemasan" type="text" class="form-control" readonly>
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Stok</label>
						<div class="input-group">
							<input id="txtstok" type="text" class="form-control" style="text-align: right;" value="0" readonly>
						</div>
					</div>
					<div class="col-md-1">
						<label class="pull-right">Qty PR</label>
						<div class="input-group">
							<input id="txtqtypr" type="text" class="form-control" style="text-align: right;" value="0" onkeypress="return hanya_angka(event)">
						</div>
					</div>				
					<div class="col-md-2">
						<label>Tgl. Diperlukan</label>
						<div class="input-group">
							<input id="txttgldiperlukan" type="text" class="form-control" placeholder="Tgl Diperlukan" required>
						</div>
					</div>
					<div class="col-md-2">
						<label>&nbsp;</label>
						<div class="input-group">
							<button id="submit_detail" type="submit" class="btn btn-block btn-warning btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
						</div>
					</div>
				</div>
		<?php
			}
		?>    

		<div class="row">
			<div class="col-md-12">
				<div id='responsecontainer' align='center'></div>
				<table id="table_detail" class="table table-head-custom table-striped" style="width: 100%;">
					<thead>
						<tr>
							<th style="display: none">ID</th>
							<th style="width: 4%; text-align: center;">NO</th>
							<th style="display: none;">ID PRODUK</th>
							<th style="width: 30%;">NAMA PRODUK</th>
							<th style="display: none;">ID KEMASAN</th>
							<th style="width: 8%; text-align: center;">KEMASAN</th>
							<th style="width: 13%; text-align: center;">ISI KEMASAN</th>
							<th style="width: 12%; text-align: center;">TGL. DIPERLUKAN</th>
							<th style="width: 12%; text-align: right;">QTY PR</th>
							<th style="width: 12%; text-align: right;">QTY APPROVE</th>
							<th style="width: 9%; text-align: center;">ACTIONS</th>
						</tr>
					</thead>				
					<tbody id="show_detail">
					
					</tbody>
				</table>            
			</div>
		</div>
	</div>
</div>

<!-- Begin : Modal Edit Detail -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-primary" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
        <div class="modal-content">
            <div class="modal-header">                        
                <h4 class="modal-title" id="modal-primary-header">Edit Data Detail</h4>
            </div>
            <input type="hidden" id="txtid_edit" class="form-control" />
            <div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-md-3 control-label">Nama Produk</label>
						<div class="col-md-9">
							<input type="text" id="txtnamaproduk_edit" class="form-control" readonly />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Isi Kemasan</label>
						<div class="col-md-9">
							<input type="text" id="txtisikemasan_edit" class="form-control" readonly />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Tgl. Diperlukan</label>
						<div class="col-md-9">
							<input type="text" id="txttgldiperlukan_edit" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Qty PR</label>
						<div class="col-md-9">
							<input type="text" id="txtqtypr_edit" class="form-control" onkeypress="return hanya_angka(event)" />
						</div>
					</div>
				</form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>					
                <button type="button" class="btn btn-primary" id="simpan_detail">Simpan</button>					
            </div>
        </div>
	</div>            
</div>
<!-- End : Modal Edit Detail -->