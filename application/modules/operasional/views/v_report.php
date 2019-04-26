<script>
	$(document).ready(function(){
		$("#sel_perbekalan").on('change',function(){
			var id_perbekalan = $(this).val();
			var year = $("#input_year").val();
			
			$.ajax({
				url:'operasional/report/get_by_perbekalan/'+id_perbekalan+'/'+year,
				type:'get',
				success:function(data){
					$("#tbody_result").html(data);
				}
			})
			$.ajax({
				url:'operasional/report/pop_kelompok/'+id_perbekalan,
				type:'get',
				success:function(data){
					$("#sel_kelompok").html(data);
				}
			});
			$(".kelompok").show();
			$(".golongan").hide();
			$(".jenis").hide();
			$(".produk").hide();
			
		});
		
		
		$("#sel_kelompok").on('change',function(){
			var id_kelompok = $(this).val();
			var year = $("#input_year").val();
			
			$.ajax({
				url:'operasional/report/get_by_kelompok/'+id_kelompok+'/'+year,
				type:'get',
				success:function(data){
					$("#tbody_result").html(data);
				}
			})
			$.ajax({
				url:'operasional/report/pop_golongan/'+id_kelompok,
				type:'get',
				success:function(data){
					$("#sel_golongan").html(data);
				}
			});
			$(".golongan").show();
			$(".jenis").hide();
			$(".produk").hide();
		});			
		
		$("#sel_golongan").on('change',function(){
			var id_gol = $(this).val();
			var year = $("#input_year").val();
			
			$.ajax({
				url:'operasional/report/get_by_golongan/'+id_gol+'/'+year,
				type:'get',
				success:function(data){
					$("#tbody_result").html(data);
				}
			})
			$.ajax({
				url:'operasional/report/pop_jenis/'+id_gol,
				type:'get',
				success:function(data){
					$("#sel_jenis").html(data);
				}
			});
			$(".jenis").show();
			$(".produk").hide();
		});
						
		$("#sel_jenis").on('change',function(){
			var id_jenis = $(this).val();
			var year = $("#input_year").val();
			
			$.ajax({
				url:'operasional/report/get_by_jenis/'+id_jenis+'/'+year,
				type:'get',
				success:function(data){
					$("#tbody_result").html(data);
				}
			})
			$.ajax({
				url:'operasional/report/pop_produk/'+id_jenis,
				type:'get',
				success:function(data){
					$("#sel_produk").html(data);
				}
			});
			$(".produk").show();
		});
		
							
		$("#sel_produk").on('change',function(){
			var id = $(this).val();
			var year = $("#input_year").val();
			
			$.ajax({
				url:'operasional/report/get_by_produk/'+id+'/'+year,
				type:'get',
				success:function(data){
					$("#tbody_result").html(data);
				}
			})
		});
		
							
		$("#submit_year").on('click',function(){
			var id_perbekalan = $("#sel_perbekalan").val();
			var id_kelompok = $("#sel_kelompok").val();
			var id_golongan = $("#sel_golongan").val();
			var id_jenis = $("#id_jenis").val();
			var year = $("#input_year").val();
			
			if(id_perbekalan>0)
			{
				var url ='operasional/report/get_by_perbekalan/'+id_perbekalan+'/'+year;
				if(id_kelompok>0)
				{
					url ='operasional/report/get_by_kelompok/'+id_kelompok+'/'+year;
					if(id_golongan>0)
					{
						url ='operasional/report/get_by_golongan/'+id_golongan+'/'+year;
						if(id_jenis>0)
						{
							url ='operasional/report/get_by_jenis/'+id_jenis+'/'+year;
						}
					}
				}
			}
			
			$.ajax({
				url:url,
				type:'get',
				success:function(data){
					$("#tbody_result").html(data);
				}
			})
		});
		
					
		
	});
</script>

<div class="row">            
	<div class="col-md-12">            
       <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icon-pencil5"></span>Sales Report By Product</h3>     
				<div class="panel-elements pull-right">
					<!--button id="btn-add-data" class="btn btn-info btn-icon-fixed"  data-toggle="modal" data-target="#modal-add-user"><span class="icon-user-plus"></span>Add Data</button-->
				</div>
			</div>
			<div class="panel-body"> 
				<div class="col-md-2">
					<select class="by_sales form-control" id="sel_perbekalan">
						<option disabled selected hidden>Perbekalan</option>
						<?php 
							
							$perb = $this->db->query("SELECT * FROM ck_produk_perbekalan where id_parent='0'");
							foreach($perb->result() as $res)
							{
								echo "<option value='$res->id'>$res->nama($res->kode)</option>";
							}
						
						?>
					</select>
				</div>
			
				<div class="col-md-2 kelompok" hidden>
					<select class="by_sales form-control" id="sel_kelompok" >
						
					</select>
				</div>
			
				<div class="col-md-2 golongan" hidden>
					<select class="by_sales form-control" id="sel_golongan" >
						<option disabled selected hidden>Golongan</option>
					</select>
				</div>
			
				<div class="col-md-1 jenis" hidden>
					<select class="by_sales form-control" id="sel_jenis" >
						<option disabled selected hidden>Jenis</option>
					</select>
				</div>
			
			
				<div class="col-md-2 produk" hidden>
					<select class="by_sales form-control" id="sel_produk" >
						<option disabled selected hidden>Produk</option>
					</select>
				</div>
			
				<div class="col-md-1 tahun" >
					<input class="input form-control" type="number" value="2018" placeholder="tahun" id="input_year" required>
				</div>
				<div class="col-md-2" >
					<button class="btn btn-success" id="submit_year"> Get Data </button>
				</div>
			
				
				<div class="col-md-12 result">&nbsp;</div>
				<div class="col-md-12 result">
					<table id="table_data" class="table table-head-custom table-striped" style="width: 100%"> 
						<thead>
							<tr>
								<th>No</th>
								<th>Product</th>
								<th>Jan</th>
								<th>Feb</th>
								<th>Mar</th>
								<th>Apr</th>
								<th>May</th>
								<th>Jun</th>
								<th>Jul</th>
								<th>Aug</th>
								<th>Sep</th>
								<th>Oct</th>
								<th>Nov</th>
								<th>Dec</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody id="tbody_result"></tbody>
					</table>
				</div>
			
			
			</div>
		</div>
	</div>
</div>

<div class="row">            
	<div class="col-md-12">            
       <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icon-pencil5"></span>Sales Executive Report</h3>     
				<div class="panel-elements pull-right">
					<!--button id="btn-add-data" class="btn btn-info btn-icon-fixed"  data-toggle="modal" data-target="#modal-add-user"><span class="icon-user-plus"></span>Add Data</button-->
				</div>
			</div>
			<div class="panel-body"> 
				<div class="col-md-2">
					<select class="by_sales form-control region"  id="sel_region">
						<option disabled selected hidden>Region</option>
						<?php 
							
							$perb = $this->db->query("SELECT * FROM ck_region");
							foreach($perb->result() as $res)
							{
								echo "<option value='$res->id'>$res->nama($res->deskripsi)</option>";
							}
						
						?>
					</select>
				</div>
				<div class="col-md-2 branch" hidden>
					<select class="by_sales form-control branch"  id="sel_branch">
						<option disabled selected hidden>SE..</option>
						
					</select>
				</div>
				<div class="col-md-2 area" hidden>
					<select class="by_sales form-control area"  id="sel_area">
						<option disabled selected hidden>SE..</option>
						
					</select>
				</div>
				<div class="col-md-2 sales" hidden>
					<select class="by_sales form-control sales"  id="sel_sales">
						<option disabled selected hidden>SE..</option>
						
					</select>
				</div>
				<div class="col-md-1 tahun" >
					<input class="input form-control" type="number" value="2018" placeholder="tahun" id="input_year_sales" required>
				</div>
				<div class="col-md-2" >
					<button class="btn btn-success" id="submit_sales"> Get Data </button>
				</div>
				<div class="col-md-12" >&nbsp;</div>
				<div class="col-md-12 result_sales" >
					<table id="table_data" class="table table-head-custom table-striped" style="width: 100%"> 
						<thead>
							<tr>
								<th>No</th>
								<th>Sales</th>
								<th>Jan</th>
								<th>Feb</th>
								<th>Mar</th>
								<th>Apr</th>
								<th>May</th>
								<th>Jun</th>
								<th>Jul</th>
								<th>Aug</th>
								<th>Sep</th>
								<th>Oct</th>
								<th>Nov</th>
								<th>Dec</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody id="tbody_result_sales"></tbody>
					</table>
				</div>
				<script>
				$(document).ready(function(){
					var year = $("#input_year_sales").val();
					$("#sel_region").on('change',function(){
						var id = $(this).val();
						$.ajax({
							url:'operasional/report/pop_branch/'+id,
							type:'get',
							success:function(data){
								$("#sel_branch").html(data);
								$(".branch").show();
								$(".area").hide();
								$(".sales").hide();
							}
						});
						
						
						// $.ajax({
							// url:'operasional/report/get_by_region/'+year,
							// type:'get',
							// success:function(data){
								// $("#tbody_result_sales").html(data);
							// }
						// });
						var id_reg = $("#sel_region").val();
						$.ajax({
							url:'operasional/report/get_by_branch/'+year+'/'+id_reg,
							type:'get',
							success:function(data){
								$("#tbody_result_sales").html(data);
							}
						});
						
					});
					
					$("#sel_branch").on('change',function(){
						var id = $(this).val();
						$.ajax({
							url:'operasional/report/pop_area/'+id,
							type:'get',
							success:function(data){
								$("#sel_area").html(data);
								$(".branch").show();
								$(".area").show();
								$(".sales").hide();
							}
						});
						var id_branch = $("#sel_branch").val();
						$.ajax({
							url:'operasional/report/get_by_area/'+year+'/'+id_branch,
							type:'get',
							success:function(data){
								$("#tbody_result_sales").html(data);
							}
						});
					});
					
					$("#sel_area").on('change',function(){
						var id = $(this).val();
						$.ajax({
							url:'operasional/report/pop_sales/'+id,
							type:'get',
							success:function(data){
								$("#sel_sales").html(data);
								$(".branch").show();
								$(".area").show();
								$(".sales").show();
							}
						});
						$.ajax({
							url:'operasional/report/get_by_sales/'+year+'/'+id,
							type:'get',
							success:function(data){
								$("#tbody_result_sales").html(data);
							}
						});
					});
					
					$("#sel_sales").on('change',function(){
						var id = $(this).val();
						$.ajax({
							url:'operasional/report/get_per_sales/'+year+'/'+id,
							type:'get',
							success:function(data){
								$("#tbody_result_sales").html(data);
							}
						});
					});
					
					$("#submit_sales").on('click',function(){
						var id_sales = $("#sel_sales").val();
						var year = $("#input_year_sales").val();
						$.ajax({
							url:'operasional/report/sales_executive',
							data:{id_sales:id_sales, tahun:year},
							success:function(data){
								$("#result_sales").html();
							}
						});
					});
				});
				</script>
			</div>
		</div>
	</div>
</div>