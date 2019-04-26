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
	$status_histori = '';

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
			$status_histori = $row->status_histori;
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
							
							<input id="txtusersession" name="txtusersession" type="hidden" class="form-control" value="<?php echo $username; ?>" readonly>
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
							<input type="text" class="form-control input-sm" value="<?php echo set_month_to_string_ind($bulan); ?>" readonly>
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
							<input type="text" class="form-control input-sm" value="<?php echo $tahun; ?>" readonly>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="col-md-12">
					<label>Deskripsi</label>
					<div class="input-group">
						<textarea class="form-control" rows="4" readonly><?php echo $deskripsi; ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Data Detail</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">            
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
							<th style="width: 12%; text-align: right;">TGL. DIPERLUKAN</th>
							<th style="width: 12%; text-align: right;">QTY PR</th>
							<th style="width: 12%; text-align: center;">QTY APPROVE</th>
							<th style="width: 8%; text-align: center;">ACTIONS</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if ($get_detail->num_rows() > 0) {
								$i = 1;
								foreach ($get_detail->result() as $r) {
									echo "<tr>";
										echo "<td id='id_detail' style='display:none;'>".$r->id_detail."</td>";
										echo "<td style='width: 4%; text-align: center;'>".$i."</td>";								
										echo "<td id='id_produk' style='display:none;'>".$r->id_produk."</td>";
										echo "<td id='nama_produk' style='width: 38%;'>".$r->nama_produk."</td>";
										echo "<td id='id_kemasan' style='display:none;'>".$r->id_kemasan."</td>";
										echo "<td id='nama_kemasan' style='width: 8%; text-align: center;'>".$r->nama_kemasan."</td>";
										echo "<td id='isi_kemasan' style='width: 13%; text-align: center;'>".$r->info_kemasan."</td>";
										echo "<td id='tgl_diperlukan' style='width: 12%; text-align: right;'>".date_format(new DateTime($r->tgl_diperlukan), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";
										echo "<td id='qty_pr' style='width: 8%; text-align: right;'>".number_format($r->qty_pr)."</td>";
										echo "<td id='qty_approve' style='width: 8%; text-align: right;'>".number_format($r->qty_approve)."</td>";
										echo "<td style='width: 9%; text-align: center;'>";
											echo '<span id="edit_detail" title="Edit" class="icon-register text-info text-lg" style="cursor: pointer"></span>&nbsp;
												  <span id="hapus_detail" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
										echo "</td>";
									echo "</tr>";
									
									$i++;
								}
							}
						?>
					</tbody>
				</table>
				
				<div id="responsecontainer" align="center"></div>
				
			</div>
		</div>
	</div>
</div>
	
<div class="block">
	<div class="row">
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-2">
					<label class="control-label">Status:</label>
				</div>
				<div class="col-md-10">
					<div class="col-md-4">
						<label class="text-success">
							<input type="radio" name="rdostatus" value="3" checked>
							Approve
						</label>						
					</div>
					<div class="col-md-4">
						<label class="text-warning">
							<input type="radio" name="rdostatus" value="4">
							Revisi
						</label>
					</div>
					<div class="col-md-4">
						<label class="text-danger">
							<input type="radio" name="rdostatus" value="5">
							Reject
						</label>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-3">
					<label class="control-label">Keterangan:</label>
				</div>
				<div class="col-md-9">
					<textarea id="txtketerangan_input" name="txtketerangan_input" class="form-control" rows="5"></textarea>
				</div>
			</div>
		</div>
		
		<div class="col-md-3">
			<div class="row">
				<div class="col-md-4">
					<label class="control-label">Keterangan (histori):</label>
				</div>
				<div class="col-md-8">
					<textarea id="txtketerangan_histori" name="txtketerangan_histori" class="form-control" rows="5" readonly><?php echo str_replace('<br />', "\n", $status_histori); ?></textarea>
				</div>
			</div>
		</div>
		
		<div class="col-md-1">
			<div class="row">
				<div class="col-md-12">
					<button type="button" id="submit_approve" class="btn btn-danger pull-right">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End : Block Detail -->

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
							<input type="text" id="txttgldiperlukan_edit" class="form-control" readonly />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Qty PR</label>
						<div class="col-md-9">
							<input type="text" id="txtqtypr_edit" class="form-control" onkeypress="return hanya_angka(event)" readonly />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Stok</label>
						<div class="col-md-9">
							<input type="text" id="txtstok_edit" class="form-control" onkeypress="return hanya_angka(event)" readonly />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Qty Approve</label>
						<div class="col-md-9">
							<input type="text" id="txtqtyapprove_edit" class="form-control" onkeypress="return hanya_angka(event)" />
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