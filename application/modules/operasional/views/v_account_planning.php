<?php
	$id_karyawan = $_SESSION['user_id'];
	$q = $this->db->query("select * from ck_sales where id_karyawan='$id_karyawan'");
	$id_sales = '';
	foreach($q->result() as $x)
		$id_sales = $x->id;
?>
<script>
$(document).ready(function(){
	
	$.ajax({
		url:'operasional/account_planning/get_header_cal',
		async:false,
		type:'get',
		success:function(data)
		{
			$("#table-2").html(data);
		}
	});
	var table = $("#cal").DataTable({
		"fixedHeader": {
		  header: true,
		},
		"bLengthChange": false,
		"bFilter": false,
		"bInfo": false,
		'bAutoWidth':false,
		"bSort" : false,
		"paging":   false,
	});
	$("#cal").css("width","100%");
	$("#cal").css("text-align","center");
	$.ajax({
		url:'operasional/account_planning/get_calendar/yes',
		type:'post',
		async:false,
		success:function(data){
			$("#calbody").html(data);
			
		}
	});
	$("#calbody").on( 'mouseenter', 'td', function () {
            var colIdx = table.cell(this).column;
 
            $( table.cells().nodes() ).removeClass( 'highlight' );
            $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
        });
	var id_karyawan = <?php echo $_SESSION['user_id'];?>;
	var id_sales = <?=$id_sales?>;
	
	$("#inputbulan").val(<?=date('n')?>);
	
	//********	DataTable ***********//
	$('#table-data').DataTable({
		"bProcessing": true,
		"bSort" : true,
		"bServerSide": true,
		"sServerMethod": "GET",
		//"sAjaxSource": "operasional/account_planning/get_list/"+id_sales+"/"+1+"/"+2018,
		"sAjaxSource": "operasional/account_planning/get_list/"+id_sales+"/"+<?=date('n')?>+"/"+<?=date('Y')?>,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		//  "order": [[ 0, "desc" ]],
		   "aoColumnDefs": [

			  { "sClass": "text-right", "aTargets": [ 3,4,5,6,7,8 ] }
			  //You can also set 'sType' to 'numeric' and use the built in css.           
			]
	});
	$("#view_data").click(function(){
		var bulan = $("#inputbulan").val();
		var tahun = $("#inputtahun").val();
		
		$('#table-data').DataTable().destroy();
		$('#table-data').DataTable({
		"bProcessing": true,
		"bSort" : true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "operasional/account_planning/get_list/"+id_sales+"/"+bulan+"/"+tahun+"/viewdata",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		//  "order": [[ 0, "desc" ]],
		   "aoColumnDefs": [

			  { "sClass": "text-right", "aTargets": [ 3,4,5,6,7,8 ] }
			  //You can also set 'sType' to 'numeric' and use the built in css.           
			]
		});
		$.ajax({
			url:'operasional/account_planning/get_calendar/no',
			data:{bulan:bulan,tahun:tahun},
			type:'post',
			async:false,
			success:function(data){
				$("#calbody").html(data);
				
			}
		})
	});
	
	/****GET CUST BY ID SALES *****/
	
		var opt='<option selected hidden disabled>Select Customer</option>';
		$.ajax({
			url:'operasional/account_planning/get_customer/'+id_sales,
			dataType:'json',
			type:'get',
			async:false,
			success:function(json)
			{
				$.each(json,function(i,obj){
					//console.log("Value : "+obj.id+", Nama : "+obj.nama);
					opt += "<option value='"+'a'+obj.id+"'>"+obj.nama+"</option>";
				})
				$("#select-customer").html(opt);
				$("#select-customer").selectpicker('refresh');
			}
		});
	$("#set-needs").hide();
	$("#set-needs").prop('readonly',true);
	
	//************ GET CUST NEED	*************//
	$("#select-customer").on('change',function(){
		//get cust need
		var id_customer = $(this).val();
		$.ajax({
			url:'operasional/account_planning/get_kebutuhan/'+id_customer,
			type:'post',
			success:function(nominal){
				
				if(nominal>0)
				{
					$("#input-needs").val(nominal);
					$("#set-needs").show();
					$("#set-needs").prop('readonly',false);
					$("#set-needs").focus();
				}
				else{
					$("#input-needs").val('');
					alert("Please set customer needs first");
					$("#set-needs").show();
					$("#set-needs").prop('readonly',false);
					$("#set-needs").focus();
				}
			}
		});
	});
	
	////////// SAVE NEEDS /////////////
	
	$("#set-needs").click(function(){
		var id_customer = $("#select-customer").val();
		var kebutuhan = $("#input-needs").asNumber({ parseType: 'float' });
		$.ajax({
			url:'operasional/account_planning/set_needs',
			type:'post',
			data:{id:id_customer,kebutuhan:kebutuhan},
			success:function(respons)
			{
				if(respons=='done')
				{
					alert("Customer needs set!");
				}
				else alert (respons);
			}
		});
		//alert();
	});
	
	
	
	//////////////////format target///////////////////
	$("#input-target").on('keyup',function(){
		var target = $(this).asNumber({parseType:'float'});
		if(!$.isNumeric(target))
		{
			alert("Please input number only");
			$(this).val('');
			$(this).focus();
			return false;
		}
		else
		{
			$(this).formatCurrency({symbol:'',roundToDecimalPlace:0});
		}
	});
	
	//////////////////format needs///////////////////
	$("#input-needs").on('keyup',function(){
		var needs = $(this).asNumber({parseType:'float'});
		if(!$.isNumeric(needs))
		{
			alert("Please input number only");
			$(this).val('');
			$(this).focus();
			return false;
		}
		else
		{
			$(this).formatCurrency({symbol:'',roundToDecimalPlace:0});
		}
	});
	
	
	/****** PROCESS DATA *******/
	
	$("#submitter").click(function(){
		var id_sales = $("#select-sales").val();
		var id_cust = $("#select-customer").val();
		var bulan = $("#select-bulan").val();
		var tahun = $("#input-tahun").val();
		var kebutuhan_customer = $("#input-needs").asNumber({ parseType: 'float' });
		var total_visit = $("#input-visit").val();
		var target = $("#input-target").asNumber({ parseType: 'float' });
		var deskripsi = $("#input-deskripsi").val();
		if(id_sales!=null && id_cust!=null && bulan!=''&& total_visit!='' && tahun!='' && kebutuhan_customer!='' && target != '' && deskripsi !='')
		{
			$.ajax({
				url:'operasional/account_planning/set_account_planning',
				data:{
					id_sales:id_sales,
					id_customer:id_cust,
					bulan:bulan,
					tahun:tahun,
					kebutuhan_customer:kebutuhan_customer,
					total_visit:total_visit,
					target:target,
					deskripsi:deskripsi
				},
				type:'post',
				success:function(data)
				{	
					var response = data.substr(0,4);
					var id_detail = data.substr(4);
					if(response=="done")
					{
						swal({
							title: 'Add Planning Success!',
							text: "Press OK to continue",

							type: 'success',
							timer: 2000,
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'OK'
							},function(){
								$("#modal-add-planning").modal('hide');
								$("#select-customer").val('');
								$("#select-customer").selectpicker('refresh');
								$("#select-bulan").val('');
								$("#select-bulan").selectpicker('refresh');
								$("#input-tahun").val('');
								$("#input-target").val('');
								$("#input-needs").val('');
								// window.open('operasional/account_planning/insert_detail/'+id_detail,'_self');		
								window.open('operasional/account_planning/edit_detail/'+id_detail,'_self');		
							})
					
					}
					else alert(data);
				}
			});
		}
		else alert("Fill empty form!");
	});
	
	
	$("#save-evaluate").on('click',function(){
		var id = $("#id_lk").val();
		var reason = $("#vreason").val();
		var corrective = $("#vcorrective").val();
		if(reason=='' && corrective=='')
		{
			alert("Please Fill Empty Field");
			return false;
		}
		$.ajax({
			url:'operasional/account_planning/update_reason/'+id,
			data:{
				reason:reason,corrective:corrective
			},
			type:'post',
			success:
				function(respons){
					if(respons=='done')
					{
						alert("Saved!");
						$("#modal-evaluate").modal('hide');
					}
					else 
						alert(respons);
				}
		});
	});
	
	$('#modal-evaluate').on('hidden.bs.modal', function () {
		$("#vcorrective").val('');
		$("#vreason").val('');
	});
	
	$("a#atab2").on("click",function(){
		var bulan = $("#inputbulan").val();
		var tahun = $("#inputtahun").val();
		//console.log(bulan);
		$.ajax({
			url:'operasional/account_planning/get_calendar/no',
			data:{bulan:bulan,tahun:tahun},
			type:'post',
			async:false,
			success:function(data){
				$("#calbody").html(data);
				
			}
		})
	});
	
	$("#filter_sales").on('change',function(){
		var bulan = $("#inputbulan").val();
		var tahun = $("#inputtahun").val();
		var id_sales = $(this).val();
		if(id_sales==0)
		{
			$.ajax({
				url:'operasional/account_planning/get_calendar/no',
				data:{bulan:bulan,tahun:tahun},
				type:'post',
				async:false,
				success:function(data){
					$("#calbody").html(data);
					
				}
			})
		}
		else
		{
			$.ajax({
				url:'operasional/account_planning/get_calendar/no/yes',
				data:{
						id_sales:id_sales,
						bulan:bulan,
						tahun:tahun
					},
				type:'post',
				async:false,
				success:function(data){
					$("#calbody").html(data);
					
				}
			})
			
		}			
	});
}); // End JQUERY

$(document).on('click','#popuper, #set-new-target',function(){
	$("#modalDetail").modal('show');
	var id_visit = $(this).closest('td').find('#id_visit').val();
	var id_lk =  $(this).closest('td').find('#id_lk').val();
	var cust =  $(this).closest('td').find('#cust_nama').val();
	var str =  $(this).closest('td').find('#str').val();
	var mat =  $(this).closest('td').find('#mat').val();
	var time =  $(this).closest('td').find('#jam').val();
	
	$("#span_topics").val(mat);
	$("#span_strategy").val(str);
	$("#span_time").html(time);
	$("#span_visit_to").html(cust);
	
	
});
$(document).on('click','#btn-add-data, #set-new-target',function(){
	var id_customer = $(this).closest('tr').find("#id_cust").val();
	var bulan =	<?=date('n')?>;
	var tahun =	<?=date('Y')?>;
	
	$("#input-tahun").val(tahun);
	$("#select-bulan").val(bulan);
	$("#select-bulan").selectpicker('refresh');
	//alert(id);
	var bulan = $(this).closest
	$("#select-customer").val(id_customer);
	$("#select-customer").prop('readonly',true);
	$("#select-customer").selectpicker('refresh');
	
	$.ajax({
		url:'operasional/account_planning/get_kebutuhan/'+id_customer,
		type:'post',
		success:function(nominal){
			
			if(nominal>0)
			{
				$("#input-needs").val(nominal);
				$("#input-needs").formatCurrency({symbol:'',roundToDecimalPlace:0});
				$("#set-needs").show();
				$("#set-needs").prop('readonly',false);
				$("#set-needs").focus();
			}
			else{
				$("#input-needs").val('');
				alert("Please set customer needs first");
				$("#set-needs").show();
				$("#set-needs").prop('readonly',false);
				$("#set-needs").focus();
			}
		}
	});
	$("#modal-add-planning").modal('show');
});


$(document).on('click','#button-edit',function(){
	var id_ap = $(this).closest('tr').find('#id_ap').val();
	window.open('operasional/account_planning/edit_detail/'+id_ap,'_self');
});
$(document).on('click','#button-realization',function(){
	var id_ap = $(this).closest('tr').find('#id_ap').val();
	window.open('operasional/account_planning/realization/'+id_ap,'_self');
});
$(document).on('click','#button-detail',function(){
	
	var id_ap=$(this).closest('tr').find('#id_ap').val();
	var nama=$(this).closest('tr').find('#nama').html();
	var namabulan=$(this).closest('tr').find('#nama_bulan').val();
	var tahun=$(this).closest('tr').find('#tahun').val();
	var total_visit=$(this).closest('tr').find('#total_visit').html();
	var realisasi=$(this).closest('tr').find('#realisasi').html();
	var deskripsi=$(this).closest('tr').find('#deskripsi').html();
	
	$("#title-namacust").html(nama);
	$("#detail-planning").html("Plan For : "+namabulan+" "+tahun+"<br> "+total_visit+" visits");
	if(id_ap=='')
	{
		alert("No Planning for this customer!");
		return false;
	}
	$.ajax({ //showDetail
		url:'operasional/account_planning/show_detail/'+id_ap,
		type:'get',
		success:function(detail)
		{
			$("#showDetail").html(detail);
		}
	});
	$("#modal-view-planning").modal('show');
	
});

$(document).on('click','#button-evaluate',function(){
	var id_lk = $(this).closest('tr').find("#id_ap").val();
	
	var target = $(this).closest('tr').find("span#vtarget").html();
	if(target=="Not Set")
		target="0";
	
	var realiz = $(this).closest('tr').find("span#vrealiz").html();
	var cust = $(this).closest('tr').find("span#nama").html()
	var bulan = $(this).closest('tr').find("span#namabulan").html();
	var tahun = $(this).closest('tr').find("span#tahun").html();
	
	$("#id_lk").val(id_lk);
	$("#vcust").html(cust);
	$("#vbt").html(bulan+" "+tahun);
	$("#eval-target").html(target);
	$("#eval-target").html(target);
	$("#eval-realiz").html(realiz);
	
	//get realisasi + corrective for this lk
	$.ajax({
		url:'operasional/account_planning/get_evaluasi/'+id_lk,
		type:'get',
		dataType:'json',
		success:function(data)
		{
			$.each(data, function(i,v){
				$("#vreason").val(v.reason);
				$("#vcorrective").val(v.corrective);
			});
		}
	});
	$("#modal-evaluate").modal('show');
});


function del(id)
{
	swal({
	  title: 'Are you sure?',
	  text: "You won't be able to revert this!",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!',
	  cancelButtonText: 'No, cancel!',
	  confirmButtonClass: 'btn btn-success',
	  cancelButtonClass: 'btn btn-danger',
	  buttonsStyling: false,
	  reverseButtons: true
	},function(){
		$.ajax({
			url:'operasional/account_planning/delete_ap/'+id,
			type:'get',
			success:function(data)
			{
				if(data=='done'){
					swal({
					  title: 'Planning Deleted!',
					  text: "Press OK to continue",
					  
					  type: 'success',
					  timer: 2000,
					  onOpen: () => {
						swal.showLoading()
					  },
					  showCancelButton: false,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'OK'
					},function(){
						$("#table-data").DataTable().ajax.reload();
					});
				}
				else alert(data);
			}
		});
	});
}

function openDetails(id_lk,tahun,bulan,tgl,jam,menit)
{
	$("#modal_calendar_detail").show();
	$.ajax({
		url:'operasional/planning/open_details/'+id,
		type:'get',
		success:function(data)
		{
			
		}
	});
}
</script>
<div class="row">                        
	 <div class="col-md-12">
       <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icon-chart-bars"></span>Account Planning</h3>   <br>
					<div class="col-md-2">
						<select id="inputbulan" name="input-bulan" required class=" bs-select">
								<option disabled selected hidden>Select Month</option>
								<option value="1">January</option>
								<option value="2">February</option>
								<option value="3">March</option>
								<option value="4">April</option>
								<option value="5">May</option>
								<option value="6">June</option>
								<option value="7">July</option>
								<option value="8">August</option>
								<option value="9">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
							</select> 
					</div>
					
					<div class="col-md-2">
						<input id="inputtahun" type="number" value="2018" class="form-control input ">
					</div>
					
					<div class="col-md-2">
						<button id="view_data" class="btn btn-success "> View Data </button>
					</div>
					
				<div class="panel-elements ">
					
						
				</div>
			</div>
			<div class="panel-body">

					<!--div class="pull-right col-md-3 margin-bottom-15">
						SELECT SALES : <select id = 'table-filter' class="form-control bs-select">
											<option value='' selected>All sales</option>
											<?php
												$qx = $this->db->query('select * from ck_sales');
												foreach ($qx->result() as $r)
												{
													echo "<option value='$r->nama_sales'>$r->nama_sales</option>";
												}
											?>
										</select>
					</div-->
					<div>
						<ul class="nav nav-pills">
							<li class="active"><a href="#tab-1" data-toggle="tab">Plan</a></li>
							<li><a id="atab2" href="#tab-2" data-toggle="tab">Schedule</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab-1">
								<table style="width:100%;" id="table-data" class="table table-head-custom table-bordered table-hover dataTable no-footer font11" style="">
									<thead>
										<tr style="text-align:center;">
											<th rowspan="2"  style="width:3%;">No.</th>
											<th rowspan="2" style="width:6%;">Customer</th>
											<!--th rowspan="2" style="width:5%;">Month</th>
											<th rowspan="2" style="width:5%;">Year</th-->
											<th rowspan="2" style="width:2%;">Total Visit</th>
											
											<th colspan="3" style="width:15%;text-align:center;">Prev. Month Sales</th>
											
											<th rowspan="2" style="width:7%;text-align:center;">Potential (IDR)</th>
											<th rowspan="2" style="width:7%;text-align:center;">Target (IDR)</th>
											<th rowspan="2" style="width:7%;text-align:center;">Actual (IDR)</th>
											<th rowspan="2" style="width:6%;text-align:center;">%</th>
											<th rowspan="2" style="width:9%;">Strategy</th>
											<th rowspan="2" style="width:9%;">Action</th>
										</tr>
										<tr style="text-align:center;">
											<th style="width:7%;text-align:center;">-3</th>
											<th style="width:7%;text-align:center;">-2</th>
											<th style="width:7%;text-align:center;">-1</th>
										</tr>
									</thead>
								</table>   
							</div>
							<div class="tab-pane" id="tab-2">
								Filter Sales : 	<select id="filter_sales">
													<option default selected value="0">All Sales</option>
													<?php
														$qx = $this->db->query('select * from ck_sales');
														foreach ($qx->result() as $r)
														{
															echo "<option value='$r->id'>$r->nama_sales</option>";
														}
													?>
												</select>
								<div id="table-2"></div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--===================================================---------------=================================================================-->
<!--=======================================            MODAL CREATE DATA             ====================================================-->
<!--===================================================---------------=================================================================-->
<div class="modal fade" id="modal-add-planning" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
	<div class="modal-dialog modal-lg modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Add New Account Planning</h4>
			</div> 
			<div class="modal-body">
				<div>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tabs-main" data-toggle="tab"><span class="fa fa-list-ol"></span> Planning	</a></li>
					</ul>
					<div class="tab-content tab-content-bordered">
						<div class="tab-pane active" id="tabs-main">
							<div class="col-md-6">
								<fieldset class="x-border">
									<legend class="x-border" ><b>Customer</b></legend>
									
									
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%">Sales</td>
											<td style="width:1%"></td>
											<td style="width:79%">
												<select required id="select-sales" name="select-sales" class="bs-select">
												
													<?php
														
														foreach ($q->result() as $r)
														{
															echo "<option value='$r->id'>$r->nama_sales</option>";
														}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td >Customer</td>
											<td  ></td>
											<td ><select required id="select-customer" name="select-customer" class="bs-select" data-live-search="true">
												<option hidden disabled selected>Select Sales First</option>
													
												</select></td>
										</tr>
										<tr>
											<td>Cust Buy Potential</td>
											<td></td>
											<td><div class="input-group">
												<span class="input-group-addon">Rp</span>
												<input id="input-needs" name="input-needs" required class="form-control input" type="text">
												<span class="input-group-addon"><button id="set-needs" class="btn-info">Update</button></span>
												</div>
											</td>
										</tr>
										
										
									</table>
								</fieldset>
								
							</div>

							<div class="col-md-6">
								<fieldset class="x-border">
									<legend class="x-border"><b>Visit Plans</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>Month</td>
											<td style="width:1%"></td>
											<td style="width:79%"><select id="select-bulan" name="input-bulan" required class="form-control input">
												<option value="1">January</option>
												<option value="2">February</option>
												<option value="3">March</option>
												<option value="4">April</option>
												<option value="5">May</option>
												<option value="6">June</option>
												<option value="7">July</option>
												<option value="8">August</option>
												<option value="9">September</option>
												<option value="10">October</option>
												<option value="11">November</option>
												<option value="12">December</option>
											</select></td>
										</tr>
										<tr>
											<td>Year</td>
											<td></td>
											<td><input id="input-tahun" name="input-tahun" required class="form-control input" type="number" value="2017" min="2017"></td>
										</tr>
										<tr>
											<td>Total Visit</td>
											<td></td>
											<td><input id="input-visit" name="input-visit" required class="form-control input" type="number"></td>
										</tr>
										<tr>
											<td>Target</td>
											<td></td>
											<td><div class="input-group">
												<span class="input-group-addon">Rp</span>
												<input id="input-target" name="input-target" required class="form-control input" type="text">
												
												</div>
											</td>
										</tr>
										
										<tr>
											<td>Strategy</td>
											<td></td>
											<td><textarea id="input-deskripsi" name="input-deskripsi" required class="form-control input"></textarea></td>
										</tr>
									</table>
								</fieldset>
							</div>

						</div>
						
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
				<button id="submitter" type="button" class="btn btn-info"><span class="icon-share"></span>Create Planning</button>
			</div>
		</div>
	</div>            
</div>


<!--===================================================---------------=================================================================-->
<!--=======================================            MODAL VIEW DATA             ====================================================-->
<!--===================================================---------------=================================================================-->
<div class="modal fade" id="modal-view-planning" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
	<div class="modal-dialog modal-fw modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Planning Detail</h4>
			</div>
			<div class="modal-body">
			
				<div class="col-md-12">
					<h3 id="title-namacust"></h3>
					 <h4 id="detail-planning"></h4>
						
						<table style="width:100%;" id="table-data" class="table table-head-custom table-bordered table-hover dataTable no-footer font11" style="">
							<thead>
								<tr>
								<th style="width:2%;">No.</th>
								<th style="width:6%;">Date</th>
								<th style="width:5%;">Time Start</th>
								<th style="width:5%;">Time End</th>
								<th style="width:14%;">Topics</th>
								<th style="width:14%;">Strategy</th>
								</tr>
							</thead>
							<tbody id="showDetail">
								
							</tbody>
						</table>       
					</div>
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>



<!--===================================================---------------=================================================================-->
<!--=======================================            MODAL EVALUATE              ====================================================-->
<!--===================================================---------------=================================================================-->
<div class="modal fade" id="modal-evaluate" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
	<div class="modal-dialog modal-lg modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-clipboard-alert"></span> PICA Identification</h4>
			</div>
			<div class="modal-body">
			<div class="col-md-6">
			<fieldset class="x-border">
				<legend class="x-border" ><b>Summary</b></legend>
					<table class="table hover font11">
						<tr>
							<td style="width:20%;"><b>Customer</b></td>
							<td style="width:30%;"><span id="vcust"></span></td>
							<td style="width:20%;"><b>Month/Year</b></td> 
							<td style="width:30%;"><span id="vbt"></span></td>
						</tr>
					</table>
					<div class="app-widget-tile">
						<div class="line">
							<div class="title">Sales Target</div>
							<!--<div class="title pull-right"><span class="label label-success label-bordered">+14.2%</span></div>-->
						</div>                                        
						<div id="eval-target" class="intval"></div>                                        
						<!--<div class="line">
							<div class="subtitle">Total items sold</div>
							div class="subtitle pull-right text-success"><span class="icon-arrow-up"></span> good</div>
						</div>-->
					</div>  
					<div class="app-widget-tile">
						<div class="line"> 
							<div class="title">Actual</div>
							<!--<div class="title pull-right"><span class="label label-warning label-bordered">+14.2%</span></div>-->
						</div>                                        
						<div id="eval-realiz" class="intval"></div>                                        
						<div class="line">
							<!--<div class="subtitle">Total items sold</div>-->
							<!--div class="subtitle pull-right text-success"><span class="icon-arrow-up"></span> good</div-->
						</div>
					</div>  
					<!--<fieldset class="x-border">
						<legend class="x-border" ><b>Completion</b></legend>
							<div class="progress">
								<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">80%</div>
							</div>	
						</legend>
					</fieldset>-->
			</fieldset>
			</div>
			<div class="col-md-6">
				<fieldset class="x-border">
						<legend class="x-border" ><b>PICA</b></legend>
						<input type="hidden" id="vid_visit" class="vid_visit" value=""></th>
						<table style="width:100%;" id="table-data" class="table table-head-custom table-bordered table-hover dataTable no-footer font11" style="">
							
							<tr>
								<th style="width:20%;">Problem Identification</th>
								<th style="">
								<input type="hidden" value="" id="id_lk">
								<textarea  id="vreason"  rows="4" style="width:100%;"></textarea></th>
							
							</tr>
							<tr>
								<th style="width:20%;">Corrective Action</th> 
								<th style=""><textarea  id="vcorrective"  rows="4" style="width:100%;"></textarea>
								<button id="save-evaluate" class="btn btn-info pull-right">Save</button></th>
								
							</tr>
						</table>    
					</fieldset>   
			</div>
				
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
						
						
						
						
<!-- MODAL DETAIL VISIT -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
	<div class="modal-dialog modal-m modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-clipboard-alert"></span> Detail</h4>
			</div>
			<div class="modal-body">
				<div class="col-md-12">
					<b>Visit To  <span id="span_visit_to"></span></b>
				</div>
				<div class="col-md-12">
					<b>Time  <span id="span_time"></span></b>
				</div>
				<div class="col-md-2">
					<label for="span_topics">Topics</label>
				</div>
				<div class="col-md-10">
					<textarea style="width:100%;" rows="4" id="span_topics"></textarea>
				</div>
				<div class="col-md-2">
					<label for="span_strategy">Strategy</label>
				</div>
				<div class="col-md-10">
					<textarea style="width:100%;" rows="4" id="span_strategy"></textarea>
				</div>
			</div>
		</div>
	</div>
</div>