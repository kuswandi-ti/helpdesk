<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$("#cbotahun").on('change',function(){
			var tahun = $(this).val();
			var opt = '<option selected hidden value="0">Pilih Produk</option>';
			$.ajax({
				url: 'logistik/tren_pembelian/get_data_produk/'+tahun,
				dataType: 'json',
				type: 'get',
				success: function(json) {
					$.each(json, function(i, obj) {
						opt += "<option value='"+obj.id_produk+"'>"+obj.nama_produk+"</option>";
					});
					$("#cboproduk").html(opt);
					$("#cboproduk").selectpicker('refresh');
					$("#cboproduktest").html(opt);
					$("#cboproduktest").selectpicker('refresh');
				}
			});
		});
		
		$(".btn-search").click(function() {
			var tahun = $("#cbotahun").val(); // 2018
			var tahun_1 = tahun - 1; // 2017
			var tahun_2 = tahun - 2;  // 2016
		
			$.ajax({
				url: 'logistik/tren_pembelian/get_data_pembelian',
				type: "post",
				dataType: 'json',
				data: {
					tahun: tahun,
					id_produk: $("#cboproduk").val()
				},
				success: function(data) {
					var qty_jan_pembelian = [];
					var qty_feb_pembelian = [];
					var qty_mar_pembelian = [];
					var qty_apr_pembelian = [];
					var qty_mei_pembelian = [];
					var qty_jun_pembelian = [];
					var qty_jul_pembelian = [];
					var qty_agu_pembelian = [];
					var qty_sep_pembelian = [];
					var qty_okt_pembelian = [];
					var qty_nov_pembelian = [];
					var qty_des_pembelian = [];
					
					$.each(data, function(idx, obj) {
						qty_jan_pembelian.push(obj.jan);
						qty_feb_pembelian.push(obj.feb);
						qty_mar_pembelian.push(obj.mar);
						qty_apr_pembelian.push(obj.apr);
						qty_mei_pembelian.push(obj.mei);
						qty_jun_pembelian.push(obj.jun);
						qty_jul_pembelian.push(obj.jul);
						qty_agu_pembelian.push(obj.agu);
						qty_sep_pembelian.push(obj.sep);
						qty_okt_pembelian.push(obj.okt);
						qty_nov_pembelian.push(obj.nov);
						qty_des_pembelian.push(obj.des);
					});
					
					Highcharts.chart('divcanvas', {
						chart: {
							type: 'line'
						},
						title: {
							text: 'Tren Pembelian, ' + tahun_2 + ' - ' + tahun
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
							data: [parseFloat(qty_jan_pembelian[0]), parseFloat(qty_feb_pembelian[0]), parseFloat(qty_mar_pembelian[0]),
							       parseFloat(qty_apr_pembelian[0]), parseFloat(qty_mei_pembelian[0]), parseFloat(qty_jun_pembelian[0]),
								   parseFloat(qty_jul_pembelian[0]), parseFloat(qty_agu_pembelian[0]), parseFloat(qty_sep_pembelian[0]),
								   parseFloat(qty_okt_pembelian[0]), parseFloat(qty_nov_pembelian[0]), parseFloat(qty_des_pembelian[0])
							]
						}, {
							name: tahun_1,
							data: [parseFloat(qty_jan_pembelian[1]), parseFloat(qty_feb_pembelian[1]), parseFloat(qty_mar_pembelian[1]),
							       parseFloat(qty_apr_pembelian[1]), parseFloat(qty_mei_pembelian[1]), parseFloat(qty_jun_pembelian[1]),
								   parseFloat(qty_jul_pembelian[1]), parseFloat(qty_agu_pembelian[1]), parseFloat(qty_sep_pembelian[1]),
								   parseFloat(qty_okt_pembelian[1]), parseFloat(qty_nov_pembelian[1]), parseFloat(qty_des_pembelian[1])
							]
						}, {
							name: tahun,
							data: [parseFloat(qty_jan_pembelian[2]), parseFloat(qty_feb_pembelian[2]), parseFloat(qty_mar_pembelian[2]),
							       parseFloat(qty_apr_pembelian[2]), parseFloat(qty_mei_pembelian[2]), parseFloat(qty_jun_pembelian[2]),
								   parseFloat(qty_jul_pembelian[2]), parseFloat(qty_agu_pembelian[2]), parseFloat(qty_sep_pembelian[2]),
								   parseFloat(qty_okt_pembelian[2]), parseFloat(qty_nov_pembelian[2]), parseFloat(qty_des_pembelian[2])
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