<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "master/asset_kelompok/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[1, 'asc']],
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Kode 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Deskripsi 3
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 4
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 1, 4] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "8%", "targets": [1] },  // Kode
				{ "width": "5%", "targets": [4] },  // Action
			]
		});
		
		$(".btn-tambah").click(function() {
			$('#modal-primary-header').html('Tambah Data Master Kelompok Asset');
			reset_input();
		});
		
		$("body").on("click", "#table_data .btn-edit", function() {
			var parentTr = $(this).closest('tr');
			var id = parentTr.find('.btn-edit').val();
			
			$.ajax({
				url : 'master/asset_kelompok/kelompok_asset_view',
				type: 'POST',
				data: {
					id: id
				},
				dataType: 'json',
				success : function(data) {
					$('#modal-create-edit').modal('show');
					$('#modal-primary-header').html('Edit Data Master Kelompok Asset');
					$('#txtid').val(data.id);
					$('#txtkode').val(data.kode);
					$('#txtnama').val(data.nama);
					$('#txtdeskripsi').val(data.deskripsi);
				},
				error: function() {
				  alert("failure");
				}
			});
		});
		
		$(".add-edit").click(function(e) {			
			var form = $('#form-create-edit')[0];
			var formData = new FormData(form);
			
			formData.append('cbolevel', $("#cbolevel").val());
			formData.append('chkaktif', $('#chkaktif').is(':checked') ? '1'  : '0');
			
			if ($('#txtid').val() == '') {
				var urls = 'master/asset_kelompok/kelompok_asset_create';
			} else {
				var urls = 'master/asset_kelompok/kelompok_asset_update';
			}
			
			$.ajax({
				type: 'POST',
				url: urls,
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$('#modal-create-edit').modal('hide');
					window.setTimeout(
						function() {
							location.reload(true)
						}, 0
					);
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		// Function Max
		function max_val(id, max, min, text){
			$(id).keypress(function(){
		    	var val 	= $(id).val();
				if(val.length > max){
		   			$('#isub').attr('disabled', true);
					$('#notify1 p').replaceWith('<p>'+text+'</p>');
					$('#notify1').fadeIn(500);
				}else if(val > min && val < max){
		    		$('#isub').attr('disabled', false);
					$('#notify1 p').replaceWith('<p></p>');
					$('#notify1').hide(200);
				}
		    });
		}
		// Function Min
	    function min_val(id, min, text){
			$(id).focusout(function(){
		    	var val 	= $(id).val().length;
				if(val > 0 && val < min){
		    		$('#isub').attr('disabled', true);
					$('#notify1 p').replaceWith('<p>'+text+'</p>');
					$('#notify1').fadeIn(500);
					$(id).focus();
				}
			});
	    }
	    // Kode
		var t_id 	= '#txtkode';
		var t_max	= 4;
		var t_min	= 2;
		var t_text2	= 'Kode asset tidak boleh kurang dari 2 karakter!';
		min_val(t_id, t_min, t_text2);
	});
	
	
	function reset_input() {
		$('#txtid').val('');
		$('#txtnama').val('');
		$('#cbolevel').val('0');
		$("#cbolevel").selectpicker('refresh');
		$('#txtdeskripsi').val('');
		$("#chkaktif").prop('checked', true);
	}
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
			<div class="heading-elements">
				<button type="button" class="btn btn-info btn-icon-fixed btn-tambah" data-toggle="modal" data-target="#modal-create-edit"><span class="icon-file-add"></span> Tambah Data Baru</button>
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
							<th>Kode</th>
							<th>Nama</th>
							<th>Deskripsi</th>
							<th class="text-center">Actions</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>		
	</div>
</div>

<!-- Begin : Modal Add / Edit -->
<div class="modal fade" id="modal-create-edit" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>
		<form name="form-create-edit" id="form-create-edit" method="POST" class="form-horizontal">
			<div class="modal-content">
				<div class="modal-header">                        
					<h4 class="modal-title" id="modal-primary-header"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div id="notify1" style="width:100%;float:left;margin-bottom:3%; display: none;">
								<div class="alert alert-danger" role="alert">
									<p></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Nama</label>
								<div class="col-md-10">
									<input type="hidden" name="txtid" id="txtid">
									<input type="text" name="txtnama" id="txtnama" class="form-control" placeholder="Nama Kelompok Asset" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Kode</label>
								<div class="col-md-10">
									<input type="text" name="txtkode" id="txtkode" class="form-control" placeholder="Kode Kelompok" maxlength="4" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Deskripsi</label>
								<div class="col-md-10">
									<textarea name="txtdeskripsi" id="txtdeskripsi" class="form-control" rows="3" placeholder="Deskripsi"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<!-- p class="pull-right"><input type='checkbox' name='chkaktif' id='chkaktif'> Aktif</p-->
					<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary add-edit" id="isub">Simpan</button>					
				</div>
			</div>
		</form>
	</div>            
</div>
<!-- End : Modal Add / Edit -->
