<script>
$(document).ready(function(){
	var sisa_pembayaran;
     $('#jam_delivered').datetimepicker({
        format: 'HH:mm'
    });
	$('#no_faktur').autocomplete
        ({ 
            minLength:1,
            source: function (request, response) {
					var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
				$.ajax({ 
						url:"penjualan/penerimaan_pembayaran/populate_no_so",
						datatype:"json",
						type:"get",
						success:function(data)
						{
								var result = response($.map(data,function(v,i)
								{
										var text = v.no_faktur;
										if ( text && ( !request.term || matcher.test(text) ) ) {
											return {
													label: v.no_faktur,
													value: v.no_faktur,
													id: v.id,
													id_customer: v.id_customer,
													nama: v.nama,
													tagihan: v.jumlah_tagihan,
													sisa_tagihan:v.sisa_tagihan
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
					$("#nama_customer").val(ui.item.nama);
					$("#id_faktur").val(ui.item.id);
					$("#jumlah_tagihan").val(ui.item.tagihan);
					$("#jumlah_tagihan").formatCurrency({symbol:'',roundToDecimalPlace:0});
					$("#sisa_tagihan").val(ui.item.sisa_tagihan);
					$("#sisa_tagihan").formatCurrency({symbol:'',roundToDecimalPlace:0});
                },
					select: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox and hidden field
					$(this).val(ui.item.label);
					$("#nama_customer").val(ui.item.nama);
					$("#id_faktur").val(ui.item.id);
					$("#id_customer").val(ui.item.id_customer);
					
					$("#jumlah_tagihan").val(ui.item.tagihan);
					$("#jumlah_tagihan").formatCurrency({symbol:'',roundToDecimalPlace:0});
					$("#sisa_tagihan").val(ui.item.sisa_tagihan);
					$("#sisa_tagihan").formatCurrency({symbol:'',roundToDecimalPlace:0});
					sisa_pembayaran = ui.item.sisa_tagihan;
                }
        });
	
	$("#jumlah_pembayaran").on("keyup change", function(e){
		var total = $("#jumlah_tagihan").asNumber({ parseType: 'float' });;
		//var sisa = $("#sisa_tagihan").asNumber({ parseType: 'float' });
		var bayar = $(this).asNumber({ parseType: 'float' });;
		
		if(!$.isNumeric(bayar))
		{
			$(this).val('');
			return false;
		}
		// if(bayar>sisa_pembayaran)
		// {
			// alert("Pembayaran melebihi sisa tagihan");
			// $(this).val(0);
			// return false;
		// }
		var sisa_tagihan = sisa_pembayaran-bayar;
		$("#sisa_tagihan").val(sisa_tagihan);
		$("#sisa_tagihan").formatCurrency({symbol:'',roundToDecimalPlace:0});
		$(this).formatCurrency({symbol:'',roundToDecimalPlace:0});
	//	alert($("#sisa_tagihan").asNumber({parseType:'float'})*-1)
	});
	
	$("#submitPayment").on("click", function(e){
		var id_billing = $("#id_faktur").val();
		var id_customer = $("#id_customer").val();
		var jumlah_tagihan = $("#jumlah_tagihan").asNumber({parseType:'float'});
		var jumlah_bayar = $("#jumlah_pembayaran").asNumber({parseType:'float'});
		var sisa_bayar = $("#sisa_tagihan").asNumber({parseType:'float'});
		var lebih_bayar = 0;
		if(sisa_bayar<0)
		{
			lebih_bayar = sisa_bayar*-1;
			sisa_bayar=0;
		}
		var keterangan = $("#ket_delivered").val();
		var tanggal_pembayaran = $("#tanggal_delivered").val();
		var jam_pembayaran = $("#jam_delivered").val()+':00';
		console.log(
		" id_billing "+id_billing+
		" id_customer "+id_customer+
		" jumlah_tagihan "+jumlah_tagihan+
		" jumlah_bayar "+jumlah_bayar+
		" sisa_bayar "+sisa_bayar+
		" keterangan "+keterangan+
		" tanggal_pembayaran "+tanggal_pembayaran+
		" jam_pembayaran "+jam_pembayaran
		);
		var fdata = new FormData();
		//alert(tanggal_delivered+' '+jam_delivered);
		 if($("#file")[0].files.length>0){
			fdata.append("file",$("#file")[0].files[0]);
		
			$.ajax({
				url : 'penjualan/penerimaan_pembayaran/insert_payment',
				type: 'post',
				data: {
					id_billing:id_billing, 
					id_customer:id_customer,
					jumlah_tagihan:jumlah_tagihan,
					jumlah_bayar:jumlah_bayar, 
					sisa_bayar:sisa_bayar, 
					lebih_bayar:lebih_bayar,
					keterangan:keterangan, 
					tanggal_pembayaran:tanggal_pembayaran, 
					jam_pembayaran:jam_pembayaran 
					},
				
				success: function(insert_id)
				{
					$.ajax({
						url: 'penjualan/penerimaan_pembayaran/upd_img/'+insert_id,
						type: 'POST',            
						data: fdata,
						processData: false,
						contentType: false,
						success: function (data)
						{
							alert("Done!");
							$("#id_faktur").val('');
							$("#id_customer").val('');
							$("#nama_customer").val('');
							$("#no_faktur").val('');
							$("#jumlah_tagihan").val('');
							$("#jumlah_pembayaran").val('');
							$("#sisa_tagihan").val('');
							$("#ket_delivered").val('');
							$("#tanggal_delivered").val('');
							$("#jam_delivered").val('');
							$("#file").val('');
							$('#table_data').DataTable().ajax.reload();
						}
					});
				}
			});
		}
		else alert("Harap Masukkan Berkas Hasil Scan!");
	});
	
	  $('#table_data').DataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "penjualan/penerimaan_pembayaran/populate",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		/* "aoColumnDefs": [
           { aTargets: [ '_all' ], bSortable: false },
           { aTargets: [ 1 ], bSortable: true },
           { aTargets: [ 2 ], bSortable: true }
        ], */
		"aaSorting": [[1, 'desc']]
	});
	 
});
$(document).on('click','#showDetailPembayaran',function(){
	$("#detail_pembayaran").modal('show');
	var billing_id = $(this).closest('tr').find('#billing_id').val();
	var no_inv = $(this).closest('tr').find('#detail_no_inv').text();
	var total_tagihan = $(this).closest('tr').find('#detail_total').text();
	var nama = $(this).closest('tr').find('#detail_nama').text();
	$("#detInv").html(no_inv);
	$("#detNama").html(nama);
	$("#detTotal").html(total_tagihan);
	
	$.ajax({
		url:'penjualan/penerimaan_pembayaran/pembayaran_detail/'+billing_id,
		type:'get',
		success:function(data){
			$("#showdetail").html(data);
		}
	});
	
});
</script>

<div class="row margin-bottom-5">
	<div class="col-md-12">
		<div class="app-heading app-heading-small">
			<div class="icon icon-lg">
				<span class="icon-list3"></span>
			</div>
			<div class="title">
				<h2>Penerimaan Pembayaran</h2>
				
			</div>                        
			<div class="heading-elements">
				
			</div>
		</div>                                                         
	</div>
</div>
<div class="row margin-bottom-5 ">
    <div class="col-md-12">
        <div class="block">
			<div class="block-body" >
			
				<div class="form-group" style="	">
					<div class="col-md-1 ">
						<label>ID</label>
						<input id="id_faktur" class="ui-widgets form-control  input-sm" type="text" placeholder="" readonly>
					</div>
					<div class="col-md-2 ">
						<label>No.</label>
						<input id="no_faktur" class="ui-widgets form-control  input-sm" type="text" autofocus placeholder="Masukkan no Faktur" >
					</div>
					<div class="col-md-2 ">
						<label>Customer</label>
						<input id="nama_customer" class="ui-widgets form-control  input-sm" type="text" placeholder="" readonly>
						<input id="id_customer" class="ui-widgets form-control  input-sm" type="hidden" placeholder="" readonly>
					</div>
					
					<div class="col-md-2 ">
						<label>Total Tagihan</label>
						<input id="jumlah_tagihan" class=" form-control  input-sm" type="text"  placeholder="" autofocus="">
					</div>
					<div class="col-md-2 ">
						<label>Jumlah Bayar</label>
						<input id="jumlah_pembayaran" class=" form-control  input-sm" type="text" placeholder="" autofocus="">
					</div>
					<div class="col-md-2 ">
						<label>Sisa Tagihan</label>
						<input id="sisa_tagihan" class=" form-control  input-sm" type="text" readonly placeholder="" autofocus="">
					</div>
				   
				   
				</div>
				
				
				<div class="form-group" style="	">
				
					<div class="col-md-2">
						<label>Received Date</label>
						<input id="tanggal_delivered" class="form-control bs-datepicker-weekends   input-sm"  type="text" placeholder=" " autofocus="" data-date-format="DD-MM-YYYY">
					</div>
				   
					<div class="col-md-1">
						<label>Time</label>
						<input id="jam_delivered" class="form-control input-sm"  type="text" min="00" max="23" placeholder=" " autofocus="" data-time-format="HH:mm" >
						
					</div>
					<div class="col-md-2">
						<label>Keterangan</label>
						<input class="form-control  input-sm" id="ket_delivered">
					</div>
					<div class="col-md-2">
						<label>Bukti Pembayaran</label>
						<input type="file" class="  input-sm" id="file">
					</div>
					
					<div class="col-md-1 ">
						<label>&nbsp;</label>
						<input id="submitPayment" class="form-control input-sm btn-info" style="background:#17b8f1; color:white;" type="submit" value="Submit Payment">
						<!--input id="submitDetail" class="form-control input-sm btn-info" style="background:#17b8f1; color:white;" type="submit" value="Submit"-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
				
<div class="row margin-bottom-5 ">
    <div class="col-md-12">
        <div class="block">
			<div class="block-body" >
			<table id="table_data" class="table table-head-custom table-striped  datatable no-footer font11">
			<thead> 
                <tr >
                    <th>No</th>
                    <th>No SO</th>
                    <th>Customer</th>
                    <th>Total Tagihan</th>
                    <th>Total Pembayaran</th>
                    <th>Sisa Tagihan</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>                                    
            <tbody>
               
            </tbody>
        </table>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="detail_pembayaran">
    <div class="modal-dialog custom-class">
        <div class="modal-content">
            
      <div class="modal-body isi">
       	
		<div class="col-md-12">
			<div class="block">
				<div class="block-body" >
					<center><h1>Detail Pembayaran <span id="detInv"> </span></h1><hr>
					<h3> <b><span id="detNama"></span> </b></h3>
					<h3>Total Tagihan : Rp <b><span id="detTotal"></span></b></h3></center>
					<table class="table table-head-custom table-striped table-bordered dataTable no-footer datatable font11" style="">
						<thead> 
							<tr class=" font11">
								<th style="width:4%;">Pembayaran</th>
								<th style="width:7%;">Tanggal Pembayaran</th>
								<th style="width:13%;">Jumlah Pembayaran</th>
								<th style="width:13%;">Sisa Pembayaran</th>
								<th style="width:13%;">Keterangan</th>
								<th style="width:13%;">Bukti Pembayaran</th>
							</tr>
						</thead>                                    
						<tbody id="showdetail">

						</tbody>
					</table>
				<div class="pull-right">	
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				</div>
			</div>
		</div>
		
		</div>
      </div>
    </div>

</div>