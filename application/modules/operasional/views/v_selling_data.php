<script>
$(document).ready(function(){
	
	$("#btn_view").on('click',function(){
		var bulan = $("#input_bulan").val();
		var tahun = $("#input_tahun").val();
		$.ajax({
			url:'operasional/selling_data/selling_target/'+bulan+'/'+tahun,
			type:'get',
			success:function(data){
				$("#tbody_selling").html(data);
			}
		});
	});
	
	$("#add_target").on('click',function(){
		$("#modal_add_target").modal('show');
	});
	
	$("#select_sales_new_target, #input_tahun_new_target").on('change',function(){
		var tahun = $("#input_tahun_new_target").val();
		var id_sales = $("#select_sales_new_target").val();
		$.ajax({
			url:'operasional/selling_data/load_monthly_target/'+id_sales,
			type:'post',
			data:{tahun:tahun},
			success:function(data)
			{
				$("#tbody_new_target").html(data);
			}
		});
	});
	
	$("#btn_add_target").on('click',function(){
		var id_sales = $("#select_sales_new_target").val();
		var bulan = $("#select_bulan_new_target").val();
		var tahun = $("#input_tahun_new_target").val();
		var target = $("#input_target").val();
		
		if(id_sales<1 || bulan<1 || tahun=='' || target =='')
		{
			alert('Please Fill Data!');
			return false;
		}
		else 
		{
			$.ajax({
				url:'operasional/selling_data/insert_target',
				type:'post',
				data:{id_sales:id_sales,bulan:bulan,tahun:tahun,target:target},
				success:function(resp)
				{
					if(resp=='done')
					{
						$.ajax({
							url:'operasional/selling_data/load_monthly_target/'+id_sales,
							type:'post',
							data:{tahun:tahun},
							success:function(data)
							{
								$("#tbody_new_target").html(data);
							}
						});
					}
					else alert(resp);
				}
			});
		}
	});
	
	
});

/// Dynamic Content Action

$(document).on('click','#btn_edit_target',function(){
	$(this).closest('tr').find('#input_target_edit').prop('disabled',false);
	$(this).closest('tr').find('#btn_update_target').prop('disabled',false);
	$(this).prop('disabled',true);
});

$(document).on('change, keyup','#input_target_edit',function(){
	$(this).formatCurrency({symbol: '', roundToDecimalPlace: 0});
});


$(document).on('click','#btn_update_target',function(){
	var target = $(this).closest('tr').find('#input_target_edit').asNumber({parseType:'float'});
	var id = $(this).closest('tr').find('#id_target').val();
	var tahun = $("#input_tahun_new_target").val();
	$(this).prop('disabled',true);
	$(this).closest('tr').find('#input_target_edit').prop('disabled',true);
	$(this).closest('tr').find('#btn_edit_target').prop('disabled',false);
	$.ajax({
		url:'operasional/selling_data/update_target_by_id/'+id,
		data:{target:target},
		type:'post',
		success:function(resp){
			if(resp=='done')
			{
				$.ajax({
					url:'operasional/selling_data/load_monthly_target/'+id_sales,
					type:'post',
					data:{tahun:tahun},
					success:function(data)
					{
						$("#tbody_new_target").html(data);
					}
				});
			}
			
			else alert(resp);
		}
		
	});
});
</script>

<div class="row">                        
	 <div class="col-md-12">
       <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icon-chart-bars"></span>Monthly Target</h3>   <br>
					
				<div class="panel-elements ">
					<div class="col-md-4 ">
						<div class="form-group">
							<label> Month </label>
							<select id="input_bulan" name="input-bulan" required class=" bs-select">
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
					</div>
					<div class="col-md-4 ">
						<div class="form-group">
							<label> Year </label>
							<input type="number" value="2018" id="input_tahun" class="input form-control ">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label> &nbsp; </label>
							<button class="btn btn-info form-control" id="btn_view">View</button>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label> &nbsp; </label>
							<button id="add_target"  class="btn btn-info form-control">Manage </button>

						</div>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="modal fade" id="modal_add_target" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
					<div class="modal-dialog modal-lg modal-info" role="document">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

						<div class="modal-content">
							<div class="modal-header">                        
								<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Manage Target</h4>
							</div>
							<div class="modal-body">
							
								<div class="col-md-2">
									<select id="select_sales_new_target" name="idsales" required class=" bs-select">
										<option disabled selected hidden>Select Sales Executive</option>
										<?php
											$qx = $this->db->query('select * from ck_sales');
											foreach ($qx->result() as $r)
											{
												echo "<option value='$r->id'>$r->nama_sales</option>";
											}
										?>
									</select>
								</div>
								<div class="col-md-2">
									<input id="input_tahun_new_target" type="number" value="2018" class="form-control input ">
								</div>
								<div class="col-md-3">
									<select id="select_bulan_new_target" name="input-bulan" required class=" bs-select">
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
								<div class="col-md-3">
									<div class="input-group">
										<span class="input-group-addon">Rp </span>
										<input id="input_target" type="number" required class="form-control input ">
									</div>
								</div>
								<div class="col-md-2">
									<button id="btn_add_target" class="btn btn-success">Save</button>
								</div>
								<div>
									
									<table class="table datatables">
										<thead>
											<tr>
												<th style="width:4%">No.</th>
												<th style="width:21%">Month </th>
												<th style="width:25%">Year </th>
												<th style="width:25%">Target</th>
												<th style="width:25%">Action</th>
											</tr>
										</thead>
										<tbody id="tbody_new_target">
										</tbody>
								</table>
									</table>
								</div>
							</div>
						</div>									
					</div>									
				</div>	
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th style="width:5%;"> No </th>
							<th style="width:15%;"> Sales Executive </th>
							<th style="width:15%;"> Target </th>
							<th style="width:15%;"> Actual </th>
							<th style="width:15%;"> % A/T </th>
							<th style="width:15%;"> Deviation </th>
						</tr>
					</thead>
					<tbody id="tbody_selling">
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row">                        
	 <div class="col-md-6">
       <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icon-chart-bars"></span>Selling Chart</h3>   <br>
					
				<div class="panel-elements ">
					
						
				</div>
			</div>
			<div class="panel-body sc">
				<div class="col-md-3">
					<select id="id_sales" name="idsales" required class=" bs-select">
						<option disabled selected hidden>Select Sales Executive</option>
						<?php
							$qx = $this->db->query('select * from ck_sales');
							foreach ($qx->result() as $r)
							{
								echo "<option value='$r->id'>$r->nama_sales</option>";
							}
						?>
					</select>
				</div>
				<div class="col-md-3">
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
					
				<div class="col-md-3">
					<input id="inputtahun" type="number" value="2018" class="form-control input ">
				</div>
				
				<div class="col-md-3">
					<button id="view_data" class="btn btn-success "> View Data </button>
				</div>
				<canvas id="myChart" height="200"></canvas>
				
				<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/chartjs/chart.js"></script>
				<script>
				$(document).ready(function(){
					$("#view_data").on('click',function(){
						var id_sales = $("#id_sales").val();
						var month = $("#inputbulan").val();
						var year = $("#inputtahun").val();
						var res = '';
						$("#myChart").remove();
						$(".sc").append('<canvas id="myChart" width="400" height="300"></canvas>');
						$.ajax({
							url:'operasional/selling_data/chart_penjualan',
							type:'get',
							data:{id_sales:id_sales,month:month,year:year},
							async:false,
							success:function(data)
							{
								res=data.split(',');
								var ctx = document.getElementById("myChart");
								var myChart = new Chart(ctx, {
									type: 'bar',
									data: {
										labels: ["Current Sales", "Average", "Highest"],
										datasets: [{
											data: res,
											backgroundColor: [
												'rgba(255, 99, 132, 0.2)',
												'rgba(54, 162, 235, 0.2)',
												'rgba(255, 206, 86, 0.2)'
											],
											borderColor: [
												'rgba(255,99,132,1)',
												'rgba(54, 162, 235, 1)',
												'rgba(255, 206, 86, 1)'
											],
											borderWidth: 1
										}]
									},
									options: {
										scales: {
											yAxes: [{
												ticks: {
													beginAtZero:false
												}
											}]
										}
									}
								});
							}
						});
					});
				});
				
				</script>				
			</div>
		</div>
	</div>
	
</div>