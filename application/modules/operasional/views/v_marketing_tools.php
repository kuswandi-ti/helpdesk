<script>
$(document).on('click','#eq-btn-del',function()
{
	var id_produk = $("#eq-id-input-search").val();
	var id_eq = $(this).closest('tr').find('#id_eq').val();
	$.ajax({
		url:'operasional/marketing_tools/del_eq/'+id_eq,
		type:'post',
		success:function(data){
			alert(data);
			$("#eq-input-equal").val('');
			$("#eq-input-search").val('');
			$("#eq-btn-submit").prop('disabled',true);
			$.ajax({
				url:'operasional/marketing_tools/get_equivalent_product/'+id_produk,
				type:'post',
				data:{type:'insert'},
				async:false,
				success:function(data)
				{
					$("#eq-tebodi").html(data);
					
				}
			});
		}
	});
});

$(document).on('click','#manual-btn-del',function(){
	var id_manual =$(this).closest('tr').find("#id_manual").val();
	$.ajax({
		url:'operasional/marketing_tools/del_manual/'+id_manual,
		type:'post',
		success:function(data){
			if(data=='done')
			{
				$.ajax({
					url:'operasional/marketing_tools/populate_manual/',
					type:'get',
					success:function(data){
							$("#manual-tebodi").html(data);
						}
				});
				
			}
			else
			{
				alert(data);
			}
		}
	});
});
$(document).on('click','#manual-btn-edit',function(){
	var id_manual = $(this).closest('tr').find("#id_manual").val();
	var manual_judul = $(this).closest('tr').find(".manual-judul").html();
	$("#modal-manual-edit").modal('show');
	$("#id-manual").val(id_manual);
	$("#manual-judul-edit").val(manual_judul);
	$.ajax({
		url:'operasional/marketing_tools/populate_manual/',
		type:'get',
		success:function(data){
				$("#manual-tebodi").html(data);
			}
	});
				
			
});

$(document).ready(function(){
	$("#equiv-result").hide();
	
	
	$.ajax({
		url:'operasional/marketing_tools/populate_manual/',
		type:'get',
		success:function(data){
				$("#manual-tebodi").html(data);
			}
	});
	$.ajax({
		url:'operasional/marketing_tools/list_competitor',
		type:'get',
		success:function(data){
				$("#tebodi_competitor_list").html(data);
			}
	});
	
	$("#inputdrug").on('change keyup',function(){
		if($(this).val()=='')
		{
			$("#equiv-result").hide();
		}
	});
	$("#eq-btn-submit").prop('disabled',true);
	
	$("#btn-eq-add").on('click',function(){
		$("#modal-eq-add").modal('show');
	});
	$('#modal-eq-add').on('hidden.bs.modal', function () {
	  $("#eq-tebodi").html('');
	  $("#eq-nama-obat").html('');
	  $("#eq-input-equal").val('');
	  $("#eq-input-search").val('');
	  
	});
	$('#eq-input-equal').autocomplete({
		minLength:2,
		source: function (request, response) {
				var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
			$.ajax({ 
					url:"operasional/marketing_tools/populate_drugs",
					datatype:"json",
					type:"get",
					success:function(data)
					{
							var result = response($.map(data,function(v,i)
							{
									var text = v.nama;
									if ( text && ( !request.term || matcher.test(text) ) ) {
										return {
												label: v.nama,
												value: v.id,
												id: v.id,
												nama: v.nama
												};
									}
							}))
					}
				}) 
			},
				focus: function(event, ui) {
				// prevent autocomplete from updating the textbox
				event.preventDefault();
				// manually update the textbox and hidden field
				$(this).val(ui.item.label);
			},
				select: function(event, ui) {
				// prevent autocomplete from updating the textbox
				event.preventDefault();
				// manually update the textbox and hidden field
				$(this).val(ui.item.label);
				$("#eq-btn-submit").prop('disabled',false);
				$("#eq-id-input-equal").val(ui.item.id);
			}	
	});
	$('#eq-input-search').autocomplete({
		minLength:2,
		source: function (request, response) {
				var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
			$.ajax({ 
					url:"operasional/marketing_tools/populate_drugs",
					datatype:"json",
					type:"get",
					success:function(data)
					{
							var result = response($.map(data,function(v,i)
							{
									var text = v.nama;
									if ( text && ( !request.term || matcher.test(text) ) ) {
										return {
												label: v.nama,
												value: v.id,
												id: v.id,
												nama: v.nama
												};
									}
							}))
					}
				}) 
			},
				focus: function(event, ui) {
				// prevent autocomplete from updating the textbox
				event.preventDefault();
				// manually update the textbox and hidden field
				$(this).val(ui.item.label);
			},
				select: function(event, ui) {
				// prevent autocomplete from updating the textbox
				event.preventDefault();
				// manually update the textbox and hidden field
				$(this).val(ui.item.label);
				$("#eq-nama-obat").html(ui.item.nama);
				$("#eq-id-input-search").val(ui.item.id);
				$.ajax({
					url:'operasional/marketing_tools/get_equivalent_product/'+ui.item.id,
					type:'post',
					data:{type:'insert'},			
					success:function(data)
					{
						$("#eq-tebodi").html(data);
						
					}
				});
			}	
	});
	$("#eq-btn-submit").on('click',function(){
		var id_produk_equivalent  = $("#eq-id-input-equal").val();
		var id_produk = $("#eq-id-input-search").val();
		$.ajax({
			url:'operasional/marketing_tools/insert_eq/'+id_produk+'/'+id_produk_equivalent,
			type:'post',
			success:function(data){
				alert(data);
				$("#eq-input-equal").val('');
				$("#eq-input-search").val('');
				$("#eq-btn-submit").prop('disabled',true);
				$.ajax({
					url:'operasional/marketing_tools/get_equivalent_product/'+id_produk,
					type:'post',
					data:{type:'insert'},
					async:false,
					success:function(data)
					{
						$("#eq-tebodi").html(data);
						
					}
				});
			}
		});
	});
	$("#manual-btn-submit").on('click',function(){
		// var judul=$("#manual-judul").val();
		// var judul=$("#manual-judul").val();
		var formData = new FormData($('#upload_form')[0]);
		formData.append('file', $('input[type=file]')[0].files[0]);
		formData.append('uploader', '<?=$_SESSION['user_nama']?>');
		$.ajax({
			url:'operasional/marketing_tools/insert_manual/',
			data:formData,
			type:'post',
			contentType: false,
			processData: false,
			success:function(data){
				if(data=='done')
				{
					$('#upload_form')[0].reset();
					
					$.ajax({
						url:'operasional/marketing_tools/populate_manual/',
						type:'get',
						success:function(data){
								$("#manual-tebodi").html(data);
							}
					});
					
					$("#modal-manual-add").modal('hide');
				}
			}
		});
	});
	
	$("#manual-btn-update").on('click',function(){
		var formData = new FormData($('#edit_form')[0]);
		formData.append('file', $('input[type=file]')[0].files[0]);
		formData.append('uploader', '<?=$_SESSION['user_nama']?>');
		$.ajax({
			url:'operasional/marketing_tools/update_manual/',
			data:formData,
			type:'post',
			contentType: false,
			processData: false,
			success:function(data){
				if(data=='done')
				{
					$('#edit_form')[0].reset();
					
					$.ajax({
						url:'operasional/marketing_tools/populate_manual/',
						type:'get',
						success:function(data){
								$("#manual-tebodi").html(data);
							}
					});
					
					$("#modal-manual-edit").modal('hide');
				}
			}
		});
	});
	
	$('#inputdrug').autocomplete
		({ 
			minLength:2,
			source: function (request, response) {
					var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
				$.ajax({ 
						url:"operasional/marketing_tools/populate_drugs",
						datatype:"json",
						type:"get",
						success:function(data)
						{
								var result = response($.map(data,function(v,i)
								{
										var text = v.nama;
										if ( text && ( !request.term || matcher.test(text) ) ) {
											return {
													label: v.nama,
													value: v.id,
													id: v.id,
													nama: v.nama
													};
										}
								}))
						}
					}) 
				},
					focus: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox and hidden field
					$(this).val(ui.item.label);
				},
					select: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox and hidden field
					$(this).val(ui.item.label);
					$("#namaobat").html(ui.item.nama);
					$.ajax({
						url:'operasional/marketing_tools/get_equivalent_product/'+ui.item.id,
						type:'post',
						success:function(data)
						{
							$("#tebodi").html(data);
							$("#equiv-result").show();
						}
					});
				}
		});
		$('#input_competitor_drug').autocomplete
		({ 
			minLength:2,
			source: function (request, response) {
					var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
				$.ajax({ 
						url:"operasional/marketing_tools/populate_drugs",
						datatype:"json",
						type:"get",
						success:function(data)
						{
								var result = response($.map(data,function(v,i)
								{
										var text = v.nama;
										if ( text && ( !request.term || matcher.test(text) ) ) {
											return {
													label: v.nama,
													value: v.id,
													id: v.id,
													nama: v.nama
													};
										}
								}))
						}
					}) 
				},
					focus: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox and hidden field
					$(this).val(ui.item.label);
				},
					select: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox and hidden field
					$(this).val(ui.item.label);
					$("#namaobat").html(ui.item.nama);
					$.ajax({
						url:'operasional/marketing_tools/get_competitor_drug/'+ui.item.id,
						type:'post',
						success:function(data)
						{
							$("#tebodi_competitor").html(data);
						}
					});
				}
		});
		$('#input_competitor_drug_add').autocomplete
		({ 
			minLength:2,
			source: function (request, response) {
					var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
				$.ajax({ 
						url:"operasional/marketing_tools/populate_drugs",
						datatype:"json",
						type:"get",
						success:function(data)
						{
								var result = response($.map(data,function(v,i)
								{
										var text = v.nama;
										if ( text && ( !request.term || matcher.test(text) ) ) {
											return {
													label: v.nama,
													value: v.id,
													id: v.id,
													nama: v.nama
													};
										}
								}))
						}
					}) 
				},
					focus: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox and hidden field
					$(this).val(ui.item.label);
				},
					select: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox and hidden field
					$(this).val(ui.item.label);
					$("#id_drug").val(ui.item.id);
				}
		});
		$('#input_stock_drug').on('change keyup',function(){
			var val = $(this).val();
			if(val =='')
				$("#tebodi_stock").html('');
			else{
				$.ajax({
					url:'operasional/marketing_tools/stock_det/'+val,
					type:'get',
					success:function(data){
						$("#tebodi_stock").html(data);
					}
				});
			}
		});
		
		
	
	$("#button_add_competitor").click(function(){
		var id_kompetitor = $("#input_competitor_nama_perusahaan").val();
		var id_produk = $("#id_drug").val();
		var harga = $("#input_competitor_harga").val();
		$.ajax({
			url:'operasional/marketing_tools/ins_data_market_intel',
			data:{id_kompetitor:id_kompetitor,id_produk:id_produk,harga:harga},
			type:'post',
			success:function(data)
			{
				if(data=='done')
				{
					$("#input_competitor_nama_perusahaan").val('');
					$("#id_drug").val('');
					$("#modal_competitor_add").modal('hide');
				}
				else alert(data);
			}
			
		});
	});
	$('#btn_competitor_add').click(function(){
		var nama_perusahaan = $("#com_name").val();
		var lokasi= $("#input_competitor_loc").val();
		$.ajax({
			url:'operasional/marketing_tools/add_competitor',
			data:{nama_perusahaan:nama_perusahaan,lokasi:lokasi},
			type:'post',
			success:function(data){
				if(data=='done')
				{
					$("#com_name").val('');
					$("#input_competitor_loc").val('');
					$("#modal_competitor_list_add").modal('hide');
					$.ajax({
						url:'operasional/marketing_tools/list_competitor',
						type:'get',
						success:function(data){
								$("#tebodi_competitor_list").html(data);
							}
					});
				}
				else alert(data);
			}
		});
	});
});
$(document).on('click','#btn_del_kompetitor',function(){
	var id_kom = $(this).closest('tr').find('#id_kom').val();
	$.ajax({
		url:'operasional/marketing_tools/del_kom/'+id_kom,
		type:'get',
		success:function(data)
		{
			$.ajax({
				url:'operasional/marketing_tools/list_competitor',
				type:'get',
				success:function(data){
						$("#tebodi_competitor_list").html(data);
					}
			});
		}
	
	});
});
</script>
<div class="row">                        
	 <div class="col-md-12">
       <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icon-pencil5"></span>Marketing Tools</h3>     
				<div class="panel-elements pull-right">
					<!--button id="btn-add-data" class="btn btn-info btn-icon-fixed"  data-toggle="modal" data-target="#modal-add-user"><span class="icon-user-plus"></span>Add Data</button-->
				</div>
			</div>
			<div class="panel-body"> 
				<div>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tabs-1" data-toggle="tab">Similar Product </a></li>
						<li><a href="#tabs-2" data-toggle="tab">Manual Book</a></li>
						<li><a href="#tabs-3" data-toggle="tab">Market Intelligence</a></li>
						<li><a href="#tabs-4" data-toggle="tab">Competitor</a></li>
						<li><a href="#tabs-5" data-toggle="tab">Stock Overview</a></li>
						<li><a href="#tabs-6" data-toggle="tab">Sales Program</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tabs-1">
							<div class="col-md-2">
								<input id="inputdrug" type="text" placeholder="Input Drugs Name" class="form-control"	>
							</div>
							<div class="col-md-8">
								<div id="equiv-result">
									<span>Showing Similar Product for </span><b><span id="namaobat"></span></b>
									<table class="table datatables">
										<thead>
											<tr>
												<th>No.</th>
												<th>ID </th>
												<th>Product Name</th>
												<th>Indication </th>
											</tr>
										</thead>
										<tbody id="tebodi">
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-md-2">
								<button class="btn btn-info" id="btn-eq-add" >Create New</button>
							</div>
							
							<div class="modal fade" id="modal-eq-add" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
								<div class="modal-dialog modal-m modal-info" role="document">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

									<div class="modal-content">
										<div class="modal-header">                        
											<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Create New Data</h4>
										</div>
										<div class="modal-body">
											<div>
												<input id="eq-input-search" class="input">
												<input type='hidden' id="eq-id-input-search" class="input"> Similar To 
												<input id="eq-input-equal" class="input">
												<input type='hidden' id="eq-id-input-equal" class="input">
												<button id="eq-btn-submit" class="btn btn-sm btn-info">Insert</button>
											</div>
											<div>
												<span>Similar Product of</span> <b><i><span id="eq-nama-obat"></span></i></b>
												<table class="table datatables">
													<thead>
														<tr>
															<th>No.</th>
															<th>ID </th>
															<th>Product Name</th>
															<th>Indication </th>
															<th>Delete </th>
														</tr>
													</thead>
													<tbody id="eq-tebodi">
												</tbody>
											</table>
												</table>
											</div>
										</div>
									</div>									
								</div>									
							</div>									
						</div>
						
						<div class="tab-pane" id="tabs-2">
							<div id="" class="col-md-12">
								
								<table id="manual-table" class="table datatables">
									<thead>
										<tr>
											<th>No.</th>
											<th>Title </th>
											<th>Download</th>
											<th>Uploader </th>
											<th>Upload Date </th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="manual-tebodi">
									</tbody>
								</table>
								<button class="btn btn-info"   data-toggle="modal" data-target="#modal-manual-add"> Upload New Document </button>
								<div class="modal fade" id="modal-manual-add" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
									<div class="modal-dialog modal-m modal-info" role="document">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

										<div class="modal-content">
											<div class="modal-header">                        
												<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Upload New Documents</h4>
											</div>
											<div class="modal-body">
												<form id="upload_form" enctype="multipart/form-data">
													<div class="form-group">
														<span for="judul">Title</span>
														<input id="manual-judul" name="judul" class="input input form-control col-md-4" >
													</div>
													<div class="form-group">
														<label for="file">Upload PDF</label>
														<input accept=".pdf" type="file" id="file" name="file" class="">
													</div>
												</form>	
														<button id="manual-btn-submit" class="btn btn-info pull-right">Upload</button>
											</div>
										</div>									
									</div>									
								</div>
								<div class="modal fade" id="modal-manual-edit" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
									<div class="modal-dialog modal-m modal-info" role="document">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

										<div class="modal-content">
											<div class="modal-header">                        
												<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Edit Documents</h4>
											</div>
											<div class="modal-body">
												<form id="edit_form" enctype="multipart/form-data">
													<div class="form-group">
														<span for="judul">Title</span>
														<input type="hidden" id="id-manual" name="id" class="input input form-control col-md-4" >
														<input id="manual-judul-edit" name="judul" class="input input form-control col-md-4" >
													</div>
													<div class="form-group">
														<label for="file">Upload Revision</label>
														<input type="file" id="file" name="file" class="">
													</div>
												</form>	
														<button id="manual-btn-update" class="btn btn-info pull-right">Save</button>
											</div>
										</div>									
									</div>									
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tabs-3"> 
							<div class=" col-md-2">
								<input id="input_competitor_drug" class="input form-control competitor-drugs" 
								placeholder="Enter Drugs Name" type="text">
							</div>
							<div class="col-md-8">
								<table id="competitor-table" class="table datatables">
									<thead>
										<tr>
											<th>No.</th>
											<th>Competitor</th>
											<th>Location </th>
											<th>Price </th>
											<th>Date </th>
										</tr>
									</thead>
									<tbody id="tebodi_competitor">
									</tbody>
								</table>
							</div>
							<div class="col-md-2">
								<button onclick="$('#modal_competitor_add').modal('show');" class="btn btn-info" id="button_competitor">Add New Data</button>
							</div>
							<div class="modal fade" id="modal_competitor_add" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
								<div class="modal-dialog modal-m modal-info" role="document">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

									<div class="modal-content">
										<div class="modal-header">                        
											<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Add New Data</h4>
										</div>
										<div class="modal-body">
											<table style="border-collapse:separate; border-spacing: 0 1em;">
												<tr>
													<td style="width:100px;">Product</td>
													<td><input id="input_competitor_drug_add" class="input-sm form-control"><input type="hidden" id="id_drug"></td>
												</tr>
												<tr>
													<td>Competitor</td>
													<td>
													<select class="input-sm form-control" id="input_competitor_nama_perusahaan">
														<?php
														
														$get = $this->db->query("select * from ck_kompetitor");
														foreach($get->result() as $r)
														{
															echo "<option value='$r->id'>$r->nama_perusahaan</option>";
														}
														
														?>
													</select>
													</tr>
												<tr>
													<td>Price</td>
													<td><input class="input-sm form-control" id="input_competitor_harga"></td>
												</tr>
												<tr>
													<td></td>
													<td><button id="button_add_competitor">Save</button></td>
												</tr>
											</table>
										</div>
									</div>
								</div>
							</div>
											
						</div>
						
						
						
						
						<div class="tab-pane" id="tabs-4">
							
							<div class="col-md-8">
								<table id="list-competitor-table" class="table datatables">
									<thead>
										<tr>
											<th>No.</th>
											<th>Competitor Name</th>
											<th>Location </th>
											<th>Action </th>
										</tr>
									</thead>
									<tbody id="tebodi_competitor_list">
									</tbody>
								</table>
							</div>
							<div class="col-md-2">
								<button onclick="$('#modal_competitor_list_add').modal('show');" class="btn btn-info" id="button_competitor">Add New Data</button>
							</div>
						<div class="modal fade" id="modal_competitor_list_add" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
								<div class="modal-dialog modal-m modal-info" role="document">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

									<div class="modal-content">
										<div class="modal-header">                        
											<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Add New Data</h4>
										</div>
										<div class="modal-body">
											<table  style="border-collapse:separate; border-spacing: 0 1em;" cellspacing="10">
												<tr>
													<td style="width:100px;">Competitor Name</td>
													<td><input id="com_name" class="input-sm form-control"></td>
												</tr>
												<tr>
													<td>Location</td>
													<td><input id="input_competitor_loc" class="input-sm form-control"></td>
												</tr>
												<tr>
													<td></td>
													<td><button id="btn_competitor_add">Save</button></td>
													
												</tr>
											</table>
										</div>
									</div>
								</div>
							</div>
											
						</div>
						
						
						
						
						
						<div class="tab-pane" id="tabs-5">
							<div class=" col-md-2">
								<input id="input_stock_drug" class="input form-control competitor-drugs" 
								placeholder="Enter Drugs Name" type="text">
							</div>
							<div class="col-md-8">
								<table id="stock-table" class="table datatables">
									<thead>
										<tr>
											<th>No.</th>
											<th>Product Name</th>
											<th>Stock </th>
										</tr>
									</thead>
									<tbody id="tebodi_stock">
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane" id="tabs-6">
							 Coming Soon
						</div>
					</div>
				</div>
							
			</div>
		</div>
	</div>
</div>