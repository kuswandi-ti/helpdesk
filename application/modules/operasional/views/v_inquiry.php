<script>
$(document).ready(function(){
	$('#btn_get_data_visit').on('click',function(){
		$("#visit_result").empty();
		var tahun = $("#input_visit_year").val();
		var bulan = $("#select_visit_bulan").val();
		var cust = $("#input_visit_cust").val();
		var id_cust = $("#visit_id_customer").val();
		var group = $("#select_visit_data").val();
		switch(group){
			case '1':
				$.ajax({
					url:'operasional/inquiry/get_visit_by_period',
					type:'post',
					data:{tahun:tahun, id_customer:id_cust},
					success:function(data){
						$("#visit_result").html(data);
					}
				});
				break;
				
			case '2': //customer
			
				$.ajax({
					url:'operasional/inquiry/get_visit_by_cust',
					type:'post',
					data:{tahun:tahun, bulan:bulan},
					success:function(data){
						$("#visit_result").html(data);
					}
				});
				break;
				
			default:
				break;
				
		}
	});
	
	$('#btn_get_data_sales').on('click',function(){
		$("#sales_result").html('');
		var tahun = $("#input_sales_year").val();
		var group = $("#select_sales_data").val();
		switch(group){
			case '2':
				$.ajax({
					url:'operasional/inquiry/get_penjualan_by_sales',
					type:'post',
					data:{year:tahun},
					success:function(data){
						$("#sales_result").html(data);
					}
				});
				break;
			case '3':
				$.ajax({
					url:'operasional/inquiry/get_penjualan_by_cust/'+tahun,
					type:'post',
					data:{year:tahun},
					success:function(data){
						$("#sales_result").html(data);
					}
				});
				break;
			case '4':
				$.ajax({
					url:'operasional/inquiry/get_penjualan_by_product/'+tahun,
					type:'post',
					data:{year:tahun},
					success:function(data){
						$("#sales_result").html(data);
					}
				});
				break;
			default:
				break;
		}
	});
	
	
	$("#select_visit_data").on('change',function(){
		$this = $(this);
		if($this.val()=='1'){
			$(".select_v_bulan").hide();
			$(".input_v_cust").show();
		}
		else if ($this.val() == '2'){
			//customer
			$(".select_v_bulan").show();
			$(".input_v_cust").hide();
		}
	});
	
	
	$("#select_grafik_data").on('change',function(){
		$this = $(this);
		if($this.val()=='2'){
			$(".input_grafik_cust").hide();
			$(".input_grafik_prod").hide();
		}
		else if ($this.val() == '3'){
			//customer
			$(".input_grafik_cust").show();
			$(".input_grafik_prod").hide();
		}
		else if ($this.val() == '4'){
			//product
			$(".input_grafik_cust").hide();
			$(".input_grafik_prod").show();
		}
		
	});
	$("#input_grafik_prod").autocomplete({
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
												value: v.id
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
				$("#grafik_id_prod").val(ui.item.value);
			}	
	});
	$("#input_grafik_cust, #input_visit_cust").autocomplete
        ({ 
            minLength:2,
            source: function (request, response) {
                if($('#customerList').val()=='')
                      $("#buat").prop('disabled',true);
				  
                    var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                    $.ajax({ 
                            url:"penjualan/po_customer/populateCust",
                            datatype:"json",
                            type:"get",
                            success:function(data)
                            {

                                    var result = response($.map(data,function(v,i)
                                    {
                                            var text = v.nama;
                                            if ( text && ( !request.term || matcher.test(text) ) ) {
                                                return {
														//label: v.nama + ' | '+ v.alamat+' | Cabang '+v.cabang+' | Kabupaten/Kota '+v.kabupatenkota+' | Provinsi '+v.provinsi,
														label: v.nama ,
                                                        value: v.id
                                                        };
                                            }
                                    }))
                                    //response(results);
                            }
                    }) 
                },
                        focus: function(event, ui) {
                        // prevent autocomplete from updating the textbox
                        event.preventDefault();
                        // manually update the textbox and hidden field
                        $(this).val(ui.item.label);
						$("#grafik_id_customer").val(ui.item.value);
						$("#visit_id_customer").val(ui.item.value);
                },
                        select: function(event, ui) {
                        // prevent autocomplete from updating the textbox
                        event.preventDefault();
                        // manually update the textbox and hidden field
                        $(this).val(ui.item.label);
						$("#grafik_id_customer").val(ui.item.value);
						$("#visit_id_customer").val(ui.item.value);
		}
	});
	$("#btn_get_data_graphic").on('click',function(){
		var group = $("#select_grafik_data").val();
		switch(group){
			case '2':
				var year = $('#input_grafik_year').val();
				var ThisYear = year;
				var LastYear = year-1;
				var Last2Year = year-2;
				
				var valThisYear = [], valLastYear = [], valLastLastYear = [];
				
				$.ajax({
					url:'operasional/inquiry/get_sales_by_year/'+ThisYear,
					async:false,
					type:'get',
					dataType:'json',
					success:function(data){
						$.each(data,function(i,v){
							valThisYear.push(parseInt(v.total));
						});
					}
				});
				
				$.ajax({
					url:'operasional/inquiry/get_sales_by_year/'+LastYear,
					async:false,
					type:'get',
					dataType:'json',
					success:function(data){
						$.each(data,function(i,v){
							valLastYear.push(parseInt(v.total));
						});
					}
				});
				
				$.ajax({
					url:'operasional/inquiry/get_sales_by_year/'+Last2Year,
					async:false,
					type:'get',
					dataType:'json',
					success:function(data){
						$.each(data,function(i,v){
							valLastLastYear.push(parseInt(v.total));
						});
					}
				});
								
				Highcharts.chart('graphics_result', {
					chart: {
						type: 'line'
					},
					title: {
						text: 'Monthly Sales in Last 3 Years'
					},
					subtitle: {
						text: 'PT. Tata Usaha Indonesia'
					},
					xAxis: {
						categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
					},
					yAxis: {
						title: {
							text: 'Sales (IDR)'
						}
					},
					plotOptions: {
						line: {
							dataLabels: {
								enabled: true
							},
							enableMouseTracking: false
						}
					},
					series: [
						{
							name: ThisYear,
							data: valThisYear
						}, 
						{
							name: LastYear,
							data: valLastYear
						}, 
						{
							name: Last2Year,
							data: valLastLastYear
						}
					]
				});
				break;
			case '3': 
				var nama_cust = $("#input_grafik_cust").val();
				var id_cust = $("#grafik_id_customer").val();
				var year = $("#input_grafik_year").val();
				var custSales=[];
				$.ajax({
					url:'operasional/inquiry/get_sales_by_cust/'+id_cust+'/'+year,
					type:'get',
					async:false,
					dataType:'json',
					success:function(data){
						$.each(data,function(i,v){
							custSales.push(parseInt(v.total));
						});
					}
				});
				Highcharts.chart('graphics_result', {
					chart: {
						type: 'line'
					},
					title: {
						text: 'Customer Sales'
					},
					subtitle: {
						text: nama_cust
					},
					xAxis: {
						categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
					},
					yAxis: {
						title: {
							text: 'Sales (IDR)'
						}
					},
					plotOptions: {
						line: {
							dataLabels: {
								enabled: true
							},
							enableMouseTracking: false
						}
					},
					series: [
						{
							name: nama_cust+' Sales',
							data: custSales
						}
					]
				});
				break;
			case '4':
				var id_prod = $("#grafik_id_prod").val();
				var nama_prod = $("#input_grafik_prod").val();
				var year = $("#input_grafik_year").val();
				var prodSales = [];
				$.ajax({
					url:'operasional/inquiry/get_sales_by_product/'+id_prod+'/'+year,
					type:'get',
					async:false,
					dataType:'json',
					success:function(data){
						$.each(data,function(i,v){
							prodSales.push(parseInt(v.total));
						});
					}
				});
				Highcharts.chart('graphics_result', {
					chart: {
						type: 'line'
					},
					title: {
						text: 'Product Sales'
					},
					subtitle: {
						text: nama_prod
					},
					xAxis: {
						categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
					},
					yAxis: {
						title: {
							text: 'QTY'
						}
					},
					plotOptions: {
						line: {
							dataLabels: {
								enabled: true
							},
							enableMouseTracking: false
						}
					},
					series: [
						{
							name: nama_prod+' Sales',
							data: prodSales
						}
					]
				});
				
				//get Top 10
				var productTopName= [];
				var productTopQty= [];
				var productTopSales= [];
				$.ajax({
					url:'operasional/inquiry/get_top_ten/'+year,
					type:'get',
					async:false,
					dataType:'json',
					success:function(data){
						$.each(data,function(i,v){
							productTopName.push(v.nama);
							productTopQty.push(parseInt(v.total_qty));
							productTopSales.push(parseInt(v.total_sales));
						});
					}
				});
				Highcharts.chart('graphics_result_top10', {
					chart: {
						type: 'column'
					},
					title: {
						text: 'Top Product Sales'
					},
					subtitle: {
						text: 'In '+year
					},
					xAxis: {
						// categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
						categories:productTopName
					},
					yAxis: {
						title: {
							text: 'Sales (IDR)'
						}
					},
					plotOptions: {
						line: {
							dataLabels: {
								enabled: true
							},
							enableMouseTracking: false
						}
					},
					series: [
						{
							name: 'Sales',
							data: productTopSales
						}
					]
				});
				break; 
			default:
				break;
		}
	});
});
</script>
<div class="row">            
	<div class="col-md-12">            
       <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icon-pencil5"></span>Inquiry</h3>     
				<div class="panel-elements pull-right">
					<!--button id="btn-add-data" class="btn btn-info btn-icon-fixed"  data-toggle="modal" data-target="#modal-add-user"><span class="icon-user-plus"></span>Add Data</button-->
				</div>
			</div>
			<div class="panel-body"> 
				<div>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tabs-1" data-toggle="tab">Sales</a></li>
						<li><a href="#tabs-2" data-toggle="tab">Visit</a></li>
						<li><a href="#tabs-3" data-toggle="tab">Graphics</a></li>
					</ul>
					<div class="tab-content tab-content-bordered">
						<!-----------------------------------------------SALES ---------------------------------- !-->
						<div class="tab-pane active" id="tabs-1">
							<div class="col-md-2">
								<select class="by_sales form-control" id="select_sales_data">
									<option value='0' disabled selected >Group By:</option>
									<option value="1" hidden>Region/Branch/Area/Office</option>
									<option value="2">Sales Executive</option>
									<option value="3">Customer</option>
									<option value="4">Product</option>
								</select>
							</div>
							<div class="col-md-1">	
								<input class="by_sales form-control" id="input_sales_year" placeholder="Year" value="2018" type="number">
							</div>
							<div class="col-md-2"> 
								<button id="btn_get_data_sales" class="btn btn-info"> Get Data </button>
							</div>
							<div>
								<div id="sales_result" class="col-md-12 margin-top-10">
								</div>
							</div>
						</div>
						<!-----------------------------------------------SALES END---------------------------------- !-->
						
						<!-----------------------------------------------VISIT --------------------------------------- !-->
						<div class="tab-pane" id="tabs-2">
							<div class="col-md-2">
								<select class="by_sales form-control" id="select_visit_data">
									<option value='0' disabled selected >Group By:</option>
									<option value="1">Period</option>
									<option value="2">Customer</option>
								</select>					
							</div>
							<div class="col-md-3 input_v_cust" hidden>	
								<input class="form-control" id="input_visit_cust"  placeholder="Input Customer" type="text">
								<input class="form-control" id="visit_id_customer"   placeholder="Input Customer" type="hidden">
							</div>
							<div class="col-md-2 select_v_bulan" hidden>
								<select id="select_visit_bulan" name="input-bulan" required class=" bs-select">
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
							<div class="col-md-1">	
								<input class="form-control" id="input_visit_year" placeholder="Year" value="2018" type="number">
							</div>
							<div class="col-md-2"> 
								<button id="btn_get_data_visit" class="btn btn-success"> Get Data </button>
							</div>
							<div>
								<div id="visit_result" class="col-md-12 margin-top-10">
								</div>
							</div>
						</div>
						<!-----------------------------------------------VISIT END ---------------------------------- !-->
						
						<!-----------------------------------------------GRAPHIC -------------------------------------- !-->
						<div class="tab-pane" id="tabs-3">
							<div class="col-md-2">
								<select class="by_sales form-control" id="select_grafik_data">
									<option value='0' disabled selected >Group By:</option>
									<option value="1" hidden>Region/Branch/Area/Office</option>
									<option value="2">Sales Period</option>
									<option value="3">Customer</option>
									<option value="4">Product</option>
								</select>					
							</div>
							<div class="col-md-3 input_grafik_cust" hidden>	
								<input class="form-control" id="input_grafik_cust"  placeholder="Input Customer" type="text">
								<input class="form-control" id="grafik_id_customer"   placeholder="Input Customer" type="hidden">
							</div>
							<div class="col-md-1 input_grafik_prod" hidden>	
								<input class="form-control" id="input_grafik_prod"  placeholder="Input Product"  type="text">
								<input class="form-control" id="grafik_id_prod"  placeholder="Input Product"  type="hidden">
							</div>
							<div class="col-md-1">	
								<input class="form-control" id="input_grafik_year" placeholder="Year" value="2018" type="number">
							</div>
							<div class="col-md-2"> 
								<button id="btn_get_data_graphic" class="btn btn-success"> Get Chart </button>
							</div>
							<div>
								<div id="graphics_result" class="col-md-12 margin-top-10">
								</div>
							</div>
							<div class="block-divider"></div>
							<div>
								<div id="graphics_result_top10" class="col-md-12 margin-top-10">
								</div>
							</div>
						</div>
						<!-----------------------------------------------GRAPHIC END ---------------------------------- !-->
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
