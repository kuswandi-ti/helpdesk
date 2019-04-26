<script>
	
$(document).ready(function(){
	//********	DataTable ***********//
	$('#table-data-detail').DataTable({
		"bProcessing": true,
		"bSort" : true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "operasional/planning/populate_data",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
	});	
	//disabled
	$("#total_visit").prop('disabled',true);
	$("#total_customer").prop('disabled',true);
	$("#avg_visit").prop('disabled',true);
	$("#btn-save").prop('disabled',true);
	
	//Get Sales
	var opt = '<option disabled selected hidden>Select Sales</option>';
	$.ajax ({
		url:'operasional/planning/get_sales',
		type:'get',
		async:false,
		dataType:'json',
		success:function(json){
			$.each(json,function(i,obj)
			{
				opt+="<option value='"+obj.id+"'>"+obj.nama_sales+"</option>";
			})
			$("#id_sales").html(opt);
			$("#id_sales").selectpicker('refresh');
			$("#eid_sales").html(opt);
			$("#eid_sales").selectpicker('refresh');
			$("#eid_sales").prop('disabled',true);
		}
	});
	
	//Get Customer
	$("#id_sales").on('change',function(){
		var id_sales = $(this).val();
		var opt = '<option disabled selected hidden>Select Customer</option>';
		
		$.ajax ({
			url:'operasional/planning/get_customer/'+id_sales,
			type:'get',
			dataType:'json',
			success:function(json){
				$.each(json,function(i,obj)
				{
					opt+="<option value='"+obj.id+"'>"+obj.nama+"</option>";
				})
				$("#id_customer").html(opt);
				$("#id_customer").selectpicker('refresh');
				
			}
			
		});
		
		$("#total_visit").prop('disabled',false);
		$("#total_customer").prop('disabled',false);
		$("#avg_visit").prop('disabled',false);
		$("#btn-save").prop('disabled',false);
	});
	
	//Calc
	$("#total_visit").val(1);
	$("#total_customer").val(1);
	$("#avg_visit").val(1);
	
	$("#total_visit, #total_customer").on('keyup',function(){
		var total_visit = $('#total_visit').val();
		var customer = $("#total_customer").val();
		var avg = $("#avg_visit");
		if(total_visit=='' || total_visit=='0')
		{
			total_visit=1;
			return false;
		}
		if(customer==''|| customer=='0')
		{
			customer=1;
			return false;
		}
		if(parseInt(total_visit)<parseInt(customer)){
			alert(parseInt(total_visit)+" , "+parseInt(customer));
			alert("Total visit < customer!");
			$('#total_customer').val(total_visit);
		}
		else
		{
			avg.val(Math.floor(total_visit/customer));
		}
	});
	$("#etotal_visit, #etotal_customer").on('keyup',function(){
		var total_visit = $('#etotal_visit').val();
		var customer = $("#etotal_customer").val();
		var avg = $("#eavg_visit");
		if(total_visit=='' || total_visit=='0')
		{
			total_visit=1;
			return false;
		}
		if(customer==''|| customer=='0')
		{
			customer=1;
			return false;
		}
		if(parseInt(total_visit)<parseInt(customer)){
			alert(parseInt(total_visit)+" , "+parseInt(customer));
			alert("Total visit < customer!");
			$('#etotal_customer').val(total_visit);
		}
		else
		{
			avg.val(Math.floor(total_visit/customer));
		}
	});
	
	
	//save
	$("#btn-save").click(function(){
		var id_sales = $("#id_sales").val();
		var bulan = $("#bulan").val();
		var tahun = $("#tahun").val();
		var total_visit = $("#total_visit").val();
		var total_customer = $("#total_customer").val();
		var avg_visit = $("#avg_visit").val();
		
		 $.ajax({
			url:'operasional/planning/insert_data',
			type:'post',
			data:
			{
				id_sales:id_sales,
				bulan:bulan,
				tahun:tahun,
				total_visit:total_visit,
				total_customer:total_customer,
				avg_visit:avg_visit
			},
			success:function(response){
				if(response=="done")
				{
					swal({
						title: 'Success!',
						text: "Press OK to continue",
						type: 'success',
						timer: 2000,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'OK'
					},function(){
						$("#id_sales").val();
						$("#bulan").val();
						$("#tahun").val();
						$("#total_visit").val();
						$("#total_customer").val();
						$("#avg_visit").val();
						$("#table-data-detail").DataTable().ajax.reload();
					});
				}
				else alert(response);
			}
		});
	});
	
	//update
	$("#editbutton").click(function(){
		var id = $("#eid").val();
		var id_sales = $("#eid_sales").val();
		var bulan = $("#ebulan").val();
		var tahun = $("#etahun").val();
		var total_visit = $("#etotal_visit").val();
		var total_customer = $("#etotal_customer").val();
		var avg_visit = $("#eavg_visit").val();
		
		 $.ajax({
			url:'operasional/planning/update_data/'+id,
			type:'post',
			data:
			{
				id_sales:id_sales,
				bulan:bulan,
				tahun:tahun,
				total_visit:total_visit,
				total_customer:total_customer,
				avg_visit:avg_visit
			},
			success:function(response){
				if(response=="done")
				{
					swal({
						title: 'Update Success!',
						text: "Press OK to continue",
						type: 'success',
						timer: 2000,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'OK'
					},function(){
						$("#eid_sales").val();
						$("#ebulan").val();
						$("#etahun").val();
						$("#etotal_visit").val();
						$("#etotal_customer").val();
						$("#eavg_visit").val();
						$("#table-data-detail").DataTable().ajax.reload();
						$("#modal-edit-detail").modal('hide');
					});
				}
				else alert(response);
			}
		});
		
	});
});


/********** Update ***************/
$(document).on('click','#button-edit',function(){
	
	var id = $(this).closest('tr').find('#id').val();
	$.ajax({
		url:'operasional/planning/get_data/'+id,
		type:'get',
		dataType:'json',
		success:function(json){
			$.each(json,function(i,obj){
				$("#eid").val(obj.id);
				$("#eid_sales").val(obj.id_sales);
				$("#eid_sales").selectpicker('refresh');
				$("#ebulan").val(obj.bulan);
				$("#etahun").val(obj.tahun);
				$("#etotal_visit").val(obj.total_visit);
				$("#etotal_customer").val(obj.total_customer);
				$("#eavg_visit").val(obj.avg_visit);
			})
		}
	});
	$("#modal-edit-detail").modal('show');
})


/********** Delete ***************/
$(document).on('click','#button-delete',function(){

	//zerofill, tambah karakter di depan agar zerofill tidak hilang //
	var id = $(this).closest('tr').find('#id').val();
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
			url:'operasional/planning/delete_data/'+id,
			type:'get',
			success:function(data)
			{
				if(data=='done'){
					swal({
					  title: 'Customer Deleted!',
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
						$("#table-data-detail").DataTable().ajax.reload();
					});
				}
				else alert(data);
			}
		});
	});
	
});
</script>

<div class="row">                        
	 <div class="col-md-12">
       <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icon-pencil5"></span>Global Planning</h3>     
				<div class="panel-elements pull-right">
					<!--button id="btn-add-data" class="btn btn-info btn-icon-fixed"  data-toggle="modal" data-target="#modal-add-user"><span class="icon-user-plus"></span>Add Data</button-->
				</div>
			</div>
			<div class="panel-body">      
				<div class="col-md-3">
					<fieldset class="x-border">
						<legend class="x-border"><b>Create Monthly Planning</b></legend>
						<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
							<tr>
								<td style="width:20%">Sales</td>
								<td style="width:1%"></td>
								<td style="width:79%">
									<select required id="id_sales" name="id_sales" class="bs-select" data-live-search="true">
									
									</select>
								</td>
							</tr>
							<tr>
								<td>Month</td>
								<td></td>
								<td><select required id="bulan" name="id_bulan" class="bs-select" data-live-search="true">
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
								</td>
									
							</tr>
							
							<tr>
								<td>Year</td>
								<td></td>
								<td>
									 <input class="input form-control" type="number" min="1" id="tahun" name="tahun" value="2017">
								</td>
							</tr>
							<tr>
								<td>Total Visit Planning</td>
								<td></td>
								<td>
									<div class="input-group">
										<input class="input form-control" type="number" min="1" id="total_visit">
										<span class="input-group-addon">Times</span>
									</div>	
								</td>
							</tr>
							<tr>
								<td>Total Customer</td>
								<td></td>
								<td>
									<input class="input form-control" type="number" min="1" id="total_customer">
								
								</td>
							</tr>
							<tr>
								<td>Avg Visit per Customer</td>
								<td></td>
								<td>
									<input readonly class="input form-control" type="text" id="avg_visit">
								</td>
							</tr>
						</table>
						<button id="btn-save" class="btn btn-info btn-icon-fixed pull-right"><span class="icon-floppy-disk"></span>Save</button>
					</fieldset>
				
				</div>
				<div class="col-md-9">
					<table style="width:100%;"id="table-data-detail" class="table table-head-custom table-hover dataTable no-footer font11" style="">
						<thead> 
							<tr class=" font11">
								<th style="width:3%;">No</th>
								<th style="width:8%;">Area</th>
								<th style="width:10%;">Sales</th>
								<th style="width:16%;">Month</th>
								<th style="width:10%;">Year</th>
								<th style="width:8%;">Total Visit</th>
								<th style="width:13%;">Total Customer</th>
								<th style="width:9%;">Avg Visit</th>
								<th style="width:12%;">Action</th>
							</tr>
						</thead>                                    
						<tbody id="showdetail" style="padding:0px;"> 
							
						</tbody>
					</table>       
				</div>
				                
			<!--/div>
			<div class="panel-footer">   
				<!--div class="panel-elements pull-right">
					<button class="btn btn-info pull-right"><span class="icon-launch"></span> Submit</button>
				</div!>                                        
			</div-->
		</div>

	</div>
</div>

<!------------------------------ MODAL EDIT ---------------------------->
<div class="modal fade" id="modal-edit-detail" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
	<div class="modal-dialog modal-m modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Add New Customer</h4>
			</div>
			<div class="modal-body">
			<div class="col-md-12">
					<fieldset class="x-border">
						<legend class="x-border"><b>Update Monthly Planning</b></legend>
						<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
							<tr>
								<td style="width:20%">Sales</td>
								<td style="width:1%"></td>
								<td style="width:79%">
									<select required id="eid_sales" name="id_sales" class="bs-select" data-live-search="true">
									
									</select>
								</td>
							</tr>
							<tr>
								<td>Month</td>
								<td></td>
								<td><select required id="ebulan" name="id_bulan" class="bs-select" data-live-search="true">
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
								</td>
									
							</tr>
							
							<tr>
								<td>Year</td>
								<td></td>
								<td>
									 <input class="input form-control" type="number" min="1" id="etahun" name="tahun" value="2017">
								</td>
							</tr>
							<tr>
								<td>Total Visit Planning</td>
								<td></td>
								<td>
									<div class="input-group">
										<input class="input form-control" type="number" min="1" id="etotal_visit">
										<span class="input-group-addon">Times</span>
									</div>	
								</td>
							</tr>
							<tr>
								<td>Total Customer</td>
								<td></td>
								<td>
									<input class="input form-control" type="number" min="1" id="etotal_customer">
									<input class="input form-control" type="hidden" id="eid">
								
								</td>
							</tr>
							<tr>
								<td>Avg Visit per Customer</td>
								<td></td>
								<td>
									<input readonly class="input form-control" type="text" id="eavg_visit">
								</td>
							</tr>
						</table>
					</fieldset>
				
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
				<button id="editbutton" type="button" class="btn btn-info"><span class="icon-share"></span>Update Data</button>
			</div>
		</div>
	</div>            
</div>