<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$("#cbotahun").on('change',function(){
			var tahun = $(this).val();
			var opt = '<option selected hidden value="0">Pilih Produk</option>';
			$.ajax({
				url: 'logistik/tren_penjualan/get_data_produk/'+tahun,
				dataType: 'json',
				type: 'get',
				success: function(json) {
					$.each(json, function(i, obj) {
						opt += "<option value='"+obj.id_produk+"'>"+obj.nama_produk+"</option>";
					});
					$("#cboproduk").html(opt);
					$("#cboproduk").selectpicker('refresh');
				}
			});
		});
		
		$(".btn-search").click(function() {
			var tahun = $("#cbotahun").val(); // 2018
			var tahun_1 = tahun - 1; // 2017
			var tahun_2 = tahun - 2;  // 2016
			
			$.ajax({
				url: 'logistik/tren_penjualan/get_data_penjualan',
				type: "post",
				dataType: 'json',
				data: {
					tahun: tahun,
					id_produk: $("#cboproduk").val()
				},
				success: function(data) {
					var qty_jan_penjualan = [];
					var qty_feb_penjualan = [];
					var qty_mar_penjualan = [];
					var qty_apr_penjualan = [];
					var qty_mei_penjualan = [];
					var qty_jun_penjualan = [];
					var qty_jul_penjualan = [];
					var qty_agu_penjualan = [];
					var qty_sep_penjualan = [];
					var qty_okt_penjualan = [];
					var qty_nov_penjualan = [];
					var qty_des_penjualan = [];
					
					$.each(data, function(idx, obj) {
						qty_jan_penjualan.push(obj.jan);
						qty_feb_penjualan.push(obj.feb);
						qty_mar_penjualan.push(obj.mar);
						qty_apr_penjualan.push(obj.apr);
						qty_mei_penjualan.push(obj.mei);
						qty_jun_penjualan.push(obj.jun);
						qty_jul_penjualan.push(obj.jul);
						qty_agu_penjualan.push(obj.agu);
						qty_sep_penjualan.push(obj.sep);
						qty_okt_penjualan.push(obj.okt);
						qty_nov_penjualan.push(obj.nov);
						qty_des_penjualan.push(obj.des);
					});
					
					Highcharts.chart('divcanvas', {
						chart: {
							type: 'line'
						},
						title: {
							text: 'Tren Penjualan, ' + tahun_2 + ' - ' + tahun
						},
						subtitle: {
							//text: 'Source: thesolarfoundation.com'
						},
						xAxis: {
							categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
						},
						yAxis: {
							title: {
								text: 'Qty Pembelian'
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
						legend: {
							layout: 'horizontal',
							align: 'center',
							verticalAlign: 'bottom'
						},
						series: [{
							name: tahun_2,
							data: [parseFloat(qty_jan_penjualan[0]), parseFloat(qty_feb_penjualan[0]), parseFloat(qty_mar_penjualan[0]),
							       parseFloat(qty_apr_penjualan[0]), parseFloat(qty_mei_penjualan[0]), parseFloat(qty_jun_penjualan[0]),
								   parseFloat(qty_jul_penjualan[0]), parseFloat(qty_agu_penjualan[0]), parseFloat(qty_sep_penjualan[0]),
								   parseFloat(qty_okt_penjualan[0]), parseFloat(qty_nov_penjualan[0]), parseFloat(qty_des_penjualan[0])
							]
						}, {
							name: tahun_1,
							data: [parseFloat(qty_jan_penjualan[1]), parseFloat(qty_feb_penjualan[1]), parseFloat(qty_mar_penjualan[1]),
							       parseFloat(qty_apr_penjualan[1]), parseFloat(qty_mei_penjualan[1]), parseFloat(qty_jun_penjualan[1]),
								   parseFloat(qty_jul_penjualan[1]), parseFloat(qty_agu_penjualan[1]), parseFloat(qty_sep_penjualan[1]),
								   parseFloat(qty_okt_penjualan[1]), parseFloat(qty_nov_penjualan[1]), parseFloat(qty_des_penjualan[1])
							]
						}, {
							name: tahun,
							data: [parseFloat(qty_jan_penjualan[2]), parseFloat(qty_feb_penjualan[2]), parseFloat(qty_mar_penjualan[2]),
							       parseFloat(qty_apr_penjualan[2]), parseFloat(qty_mei_penjualan[2]), parseFloat(qty_jun_penjualan[2]),
								   parseFloat(qty_jul_penjualan[2]), parseFloat(qty_agu_penjualan[2]), parseFloat(qty_sep_penjualan[2]),
								   parseFloat(qty_okt_penjualan[2]), parseFloat(qty_nov_penjualan[2]), parseFloat(qty_des_penjualan[2])
							]
						}],
						responsive: {
							rules: [{
								condition: {
									maxWidth: 500
								},
								chartOptions: {
									legend: {
										layout: 'horizontal',
										align: 'center',
										verticalAlign: 'bottom'
									}
								}
							}]
						}
					});
				}
			});
		});
	});
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
		</div>                                                         
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-body">
				<div class="form-group">
					<div class="col-md-2"></div>
					<div class="col-md-2">
						<b><center>Per Tahun</center></b><br />
						<select name='cbotahun' id='cbotahun' class='form-control bs-select' data-live-search='true'>
							<option selected hidden value="0">Pilih Tahun</option>
							<?php
								for($i=2020; $i>=2017; $i-=1) {
									echo"<option value=".$i.">".$i."</option>";
								}
							?>
						</select>
					</div>
					<div class="col-md-4">
						<b><center>Per Produk</center></b><br />
						<select id="cboproduk" name="cboproduk" class="bs-select" data-live-search="true">
							<option hidden value="0">Pilih Produk</option>
						</select>
					</div>
					<div class="col-md-2">
						<b><center>&nbsp;</center></b><br />
						<button type="button" class="btn btn-info btn-icon-fixed btn-block btn-search">
								<span class="icon-find-replace"></span>
								Tampilkan Data
						</button>
					</div>
					<div class="col-md-2"></div>
				</div>
				
				<br />&nbsp;&nbsp;<br />
				
				<div class="col-md-12" id="divcanvas"></div>
			</div>
		</div>		
	</div>
</div>