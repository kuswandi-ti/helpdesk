<?php
$id_po;$nopo;$namacust;$alamatcust;$sales;$buktigambar;
foreach($po_header->result() as $r)
{
    $id_po = $r->id;
    $nopo = $r->no_po;
    $namacust = $r->nama;
    $alamatcust = $r->alamat;
    $sales = $r->sales;
    $buktigambar=$r->bukti_gambar;
	$subtotal = $r->subtotal;
	$total = $r->total;
	$diskon_persen = $r->diskon_persen;
	$diskon_rupiah = $r->diskon_rupiah;
	$ppn = $r->ppn;
	$tipebayar = $r->tipe_bayar;
	$jangkawaktu = $r->jangka_waktu;
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<script>
     function loadDetail()
    {
        $.ajax({
            url:'penjualan/back_order/loadDetail/'+<?php echo $id_po;?>,
            type:'get',
            success:function(data){
                $("#showdetail").html(data);
				
            }
        });
    }
	
    
	function getBONominal()
	{
		var id_po = <?php echo $id_po;?>;
		$.ajax({
				  url:'penjualan/back_order/getBOnominal',
				  type:'post',
				  data:{id_po:id_po},
				  success:function(datax){
					  datax = JSON.parse(datax);
					  var subtotal;
					  for (var i = 0; i < datax['length']; i++) {
						  subtotal = datax[i]['subtotal'];
						  
					  }
					 
					   $("#subtotalx").val(subtotal);
					   $("#subtotalx").formatCurrency({symbol:'',roundToDecimalPlace:0});
					   $("#diskonrp").val( parseInt($("#diskonpersen").val())/100*subtotal);
					   $("#diskonrp").formatCurrency({symbol:'',roundToDecimalPlace:0});
					  getnominal();
				  }
			  });
	}
	
	
    function del(id,id_po)
    {
		//alert();
		var conf=confirm("Delete item ini?");
		if(!conf)
			return;
        $.ajax({
            url:'penjualan/back_order/delDetail',
            type:'post',
            data:{
                id:id,
                id_po:id_po
            },
            success:function(data){
                loadDetail();
				
				getHeaderNominal();
            }
        });
    }
    function getnominal()
	{
		var subtotal = $("#subtotalx").asNumber({ parseType: 'float' });
		if($("#subtotalx").val()=='')
			subtotal=0;
		var diskon_rupiah = $("#diskonrp").asNumber({ parseType: 'float' });
		if($("#diskonrp").val()=='')
			diskon_rupiah=0;
		var diskon_persen =  $("#diskonpersen").asNumber({ parseType: 'float' });
		if($("#diskonpersen").val()=='')
			diskon_persen=0;
		var ppn = $("#ppn").asNumber({ parseType: 'float' });
		var ppnrp = (subtotal-diskon_rupiah)*ppn/100;
		$("#ppnrp").val(ppnrp);
		$("#ppnrp").formatCurrency({symbol:'',roundToDecimalPlace:0});
		$("#subtotalx").formatCurrency({symbol:'',roundToDecimalPlace:0});
		var total = subtotal+ppnrp-diskon_rupiah;
		$("#total").val(total);
		$("#total").formatCurrency({symbol:'',roundToDecimalPlace:0});
	}
    $(function(){
		var flagbo = 0;
		var subtotalitem = 0;
		$("#menu_penjualan_po").addClass("active");
        loadDetail();
		$("#dividerblock").hide();
		$("#panel_nominal").hide();
		$(".abcd").hide();
		getnominal();
		
        $('#submitDetail').prop('disabled',true);
        $("#diskonitemrp").prop('disabled',true);
        $("#flagbo").prop('disabled',true);
        $("#diskonitempersen").prop('disabled',true);
		$("#subtotalx").formatCurrency({symbol:'',roundToDecimalPlace:0});
        $('#namaproduk').autocomplete
        ({ 
            minLength:2,
            source: function (request, response) {
                if($('#namaproduk').val()=='')
                      $("#submitDetail").prop('disabled',true);
                    var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                    $.ajax({ 
                            url:"penjualan/po_customer/populateinputbarang",
                            datatype:"json",
                            type:"get",
                            success:function(data)
                            {
                                    var result = response($.map(data,function(v,i)
                                    {
                                            var text = v.nama;
                                            if ( text && ( !request.term || matcher.test(text) ) ) {
                                                return {
                                                        label: v.nama+' |  '+v.supplier+' | BATCH: '+v.batch_number+' | EXP: '+v.tanggal_exp+' '+v.bulan_exp+' '+v.tahun_exp+' | stok: '+v.Stok,
                                                        value: v.produk_id,
                                                        kemasan: v.kemasan,
                                                        idkemasan: v.kemasan_default,
                                                        exp_date:   v.tanggal_exp+' '+v.bulan_exp+' '+v.tahun_exp,
                                                        kadaluarsa :v.kadaluarsa,
                                                        stok:v.Stok,
                                                        namaproduk: v.nama,
                                                        hargajualmin: v.harga_jual_min,
                                                        hargajual: v.harga_jual_max,
                                                        batch: v.batch_number,
                                                        expired_date : v.expired_date
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
                        $("#namaproduk").val(ui.item.namaproduk);
                        $("#idproduk").val(ui.item.value);
                        $("#kemasanproduk").val(ui.item.kemasan);
                        $("#idkemasanproduk").val(ui.item.idkemasan);
                        $("#expiredproduk").val(ui.item.kadaluarsa);
                        $("#expireddate").val(ui.item.expired_date);
                        $("#batchnumber").val(ui.item.batch);
                        $("#stokproduk").val(ui.item.stok);
                        $("#hargajualproduk").val(ui.item.hargajual);
                        $("#hargajualproduk").formatCurrency({symbol:'',roundToDecimalPlace:0});
                        $("#hargajualprodukmin").val(ui.item.hargajualmin);
                        $("#hargajualprodukmin").formatCurrency({symbol:'',roundToDecimalPlace:0});
                },
                        select: function(event, ui) {
                        // prevent autocomplete from updating the textbox
                        event.preventDefault();
                        // manually update the textbox and hidden field
                        $(this).val(ui.item.label);
                        $("#namaproduk").val(ui.item.namaproduk);
                        $("#idproduk").val(ui.item.value);
                        $("#kemasanproduk").val(ui.item.kemasan);
                        $("#idkemasanproduk").val(ui.item.idkemasan);
                        $("#expiredproduk").val(ui.item.kadaluarsa);
                        $("#expireddate").val(ui.item.expired_date);
                        $("#batchnumber").val(ui.item.batch);
                        $("#stokproduk").val(ui.item.stok);
                        $("#hargajualproduk").val(ui.item.hargajual);
                        $("#hargajualproduk").formatCurrency({symbol:'',roundToDecimalPlace:0});
                        $("#hargajualprodukmin").val(ui.item.hargajualmin);
                        $("#hargajualprodukmin").formatCurrency({symbol:'',roundToDecimalPlace:0});
                        
						$("#diskonitemrp").prop('disabled',false);
						$("#diskonitempersen").prop('disabled',false);
                        $('#submitDetail').prop('disabled',false);
						$("#jumlahpesanan").focus();
                }
        });
	$("#flagbo").change(function() {
		var qty = $("#jumlahpesanan").val();
        var hrg = $("#hargajualproduk").asNumber({ parseType: 'float' });
		var stok = parseInt($("#stokproduk").val());
		if(!this.checked) { //kalo diuncek
			$("#flagbo").prop("disabled",true);
			$("#jumlahbo").prop("readonly",true);
			$("#jumlahbo").val(0);
			$("#jumlahpesanan").val(stok);
			flagbo = 0;
			$("#subtotal").val(hrg*stok);
			$("#subtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
		}
		else;
	});	
    $("#jumlahpesanan").on("keypress keyup change", function(e){
		//e.preventDefault();
        var hrg = $("#hargajualproduk").asNumber({ parseType: 'float' });
        var qty = $("#jumlahpesanan").val();
        var stok = parseInt($("#stokproduk").val());
        if (qty<1)
        {
			$("#flagbo").prop("checked",false);
			$("#flagbo").prop("disabled",true);
			qty=1;
            
        }
        if(qty > stok)
        {
           // alert("stok tidak cukup!");
			flagbo =1;
            $("#jumlahpesanan").val(qty);
			$("#flagbo").prop("disabled",false);
			$("#flagbo").prop("checked",true);
			$("#jumlahbo").prop("readonly",true);
			$("#jumlahbo").val(qty-stok);
			var tot = stok*hrg;
			subtotalitem = tot;
			$("#subtotal").val(tot);
			$("#subtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
        } 
        if(qty <= stok)
        {
           // alert("stok tidak cukup!");
		   flagbo =0;
			$("#flagbo").prop("checked",false);
			$("#flagbo").prop("disabled",true);
			$("#jumlahbo").prop("readonly",true);
			$("#jumlahbo").val(0);
			var tot = qty*hrg;
			subtotalitem = tot;
			$("#subtotal").val(tot);
			$("#subtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
        } 
		
			
			/* if($("#jumlahpesanan").val!='')
			{
				var code = e.keyCode || e.which;
				if(code == 13) { //Enter keycode
				$("#diskonitempersen").focus();
			} 
		 }*/
		
        
    });
	
	$("#diskonitempersen").on("keyup keypress change", function(e){
		var diskper = $(this).asNumber({parseType:'float'});
		if($(this).val()=='') 
			diskper = 0;
		var diskrp = $("#diskonitemrp").asNumber({parseType:'float'});
		if($("#diskonitemrp").val()=='')
			diskrp = 0;
		
		var potongan = diskper/100 * subtotalitem;
		//alert(subtotalitem);
		$("#diskonitemrp").val(potongan);
		$("#diskonitemrp").formatCurrency({symbol:'',roundToDecimalPlace:0});
		
		$("#subtotal").val(subtotalitem-potongan);
		$("#subtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
	});
	$("#diskonitemrp").on("keyup keypress", function(e){
		var diskper = $(this).asNumber({parseType:'float'});
		if($(this).val()=='') 
			diskper = 0;
		var diskrp = $("#diskonitemrp").asNumber({parseType:'float'});
		if($("#diskonitemrp").val()=='')
			diskrp = 0;
		
		var persenan = diskper*100/subtotalitem;
		//alert(subtotalitem);
		$("#diskonitempersen").val(persenan);
		
		
		$("#subtotal").val(subtotalitem-diskrp);
		$("#subtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
	});
	
    $("#diskonpersen").on("keyup keypress", function(e){
		var diskper = $("#diskonpersen").asNumber({ parseType: 'float' });
		if ($(this).val()=='')
		{
			$("#diskonrp").val(0);
			getnominal();
		}
		
			var subtotal = $("#subtotalx").asNumber({ parseType: 'float' });
			var ppn = $("#ppnrp").asNumber({ parseType: 'float' });
			
			var diskonrp = diskper/100*subtotal;
			if(isNaN(diskonrp))
				diskonrp=0;
			$("#diskonrp").val(diskonrp);
			$("#diskonrp").formatCurrency({symbol:'',roundToDecimalPlace:0});;
			
			var total = subtotal-diskonrp+ppn;
			$("#total").val(total);
			$("#total").formatCurrency({symbol:'',roundToDecimalPlace:0});
			getnominal();
	});
	
	$("#diskonrp").on("keyup keypress", function(e){
		var diskrp = $("#diskonrp").asNumber({ parseType: 'float' });
		if ($("#diskonrp").val()=='')
		{
			$("#diskonpersen").val(0);
			getnominal();
		}
		
			if ($("#diskonrp").val()==0)
				diskrp = 0;
			var subtotal = $("#subtotalx").asNumber({ parseType: 'float' });
			var ppn = parseInt($("#ppnrp").val());
			
			var diskonpersen = diskrp*100/subtotal;
			if(isNaN(diskonpersen))
				diskonpersen=0;
			$("#diskonpersen").val(diskonpersen);
			
			var total = subtotal-diskrp+ppn;
			$("#total").val(total);
		getnominal();
	});
	
    $("#namaproduk").on("keyup keypress", function(e){
        var isi = $("#namaproduk").val();
        if(isi=='')
            $("#submitDetail").prop("disabled",true);
        
    });
    
    $("#createBO").on("click",function(){
		var id_po = <?= $id_po ?>;
		$.ajax({
			url:'penjualan/back_order/processBO',
			type:'post',
			data:{id_po:id_po},
			success:function(data)
			{
				alert(data)
			}
		});
	});
	
	
    $("#submitDetail").on("click",function(){
		
       var id_produk =  $("#idproduk").val();
       var info_stok =  $("#stokproduk").val();
       var id_kemasan =  $("#idkemasanproduk").val();
       var batch_number =  $("#batchnumber").val();
       var expired_date = $("#expireddate").val();
       var harga_jual =  $("#hargajualproduk").asNumber({ parseType: 'float' });
       var disc_persen =  $("#diskonitempersen").asNumber({ parseType: 'float' });
       var disc_rp =  $("#diskonitemrp").asNumber({ parseType: 'float' });
       var jumlah_pesanan =  $("#jumlahpesanan").val();
       var jumlahbo =  $("#jumlahbo").val();
	   if(jumlah_pesanan=='') jumlah_pesanan=1;
       var sub_total =  $("#subtotal").asNumber({ parseType: 'float' });;
       var id_po =  <?php echo $id_po; ?>;
       $.ajax({
          url:'penjualan/po_customer/insertDetail',
          type: 'post',
          data:{
              id_po:id_po,
              id_produk : id_produk,
              info_stok : info_stok,
              id_kemasan:id_kemasan,
              batch_number:batch_number,
              expired_date:expired_date,
              harga_jual:harga_jual,
			  disc_persen:disc_persen,
			  disc_rp:disc_rp,
              jumlah_pesanan:jumlah_pesanan,
              sub_total:sub_total,
			  back_order:flagbo,
			  jumlah_back_order:jumlahbo
          },
          success:function(data)
          {
			  
			  $.ajax({
				  url:'penjualan/po_customer/getheadernominal',
				  type:'post',
				  data:{id_po:id_po},
				  success:function(datax){
					  datax = JSON.parse(datax);
					  var subtotal,diskon_persen, diskon_rupiah, ppn, total;
					  for (var i = 0; i < datax['length']; i++) {
						  subtotal = datax[i]['subtotal'];
						  total = datax[i]['total'];
						  diskon_persen = datax[i]['diskon_persen'];
						  diskon_rupiah = datax[i]['diskon_rupiah'];
						  ppn = datax[i]['ppn'];
					  }
					 
					  $("#diskonpersen").val(diskon_persen);
					  //$("#diskonrp").val(diskon_rupiah);
					  $("#ppn").val(ppn);
					  $("#total").val(total);
					   $("#subtotalx").val(subtotal);
					   $("#subtotalx").formatCurrency({symbol:'',roundToDecimalPlace:0});
					  $("#diskonrp").val( parseInt($("#diskonpersen").val())/100*subtotal);
					   $("#diskonrp").formatCurrency({symbol:'',roundToDecimalPlace:0});
					   getnominal();
				  }
			  });
			
            $("#showdetail").html(data);
            $("#namaproduk").val('');
            $("#idproduk").val('');
            $("#kemasanproduk").val('');
            $("#idkemasanproduk").val('');
            $("#expiredproduk").val('');
            $("#expireddate").val('');
            $("#batchnumber").val('');
            $("#stokproduk").val('');
            $("#hargajualproduk").val('');
            $("#jumlahpesanan").val('');
            $("#hargajualprodukmin").val('');
            $("#subtotal").val('');
            $("#diskonitempersen").val('');
            $("#diskonitemrp").val('');
            $("#submitDetail").prop("disabled",true);
			$("#simpan").prop("disabled",false);
			$("#namaproduk").focus();
          }
       });
    });
    
    $("#simpan").on('click', function(){
		//simpan nominal 
		var subtotal = $("#subtotalx").asNumber({ parseType: 'float' });
		var total = $("#total").asNumber({ parseType: 'float' });;
		var diskon_persen = $("#diskonpersen").asNumber({ parseType: 'float' });;
		var diskon_rupiah = $("#diskonrp").asNumber({ parseType: 'float' });;
		var ppn = $("#ppn").asNumber({ parseType: 'float' });;
		var id = <?php echo $id_po;?>;
		$.ajax({
			url : 'penjualan/po_customer/updatenominal',
			type:'post',
			data:{
				id:id,
				subtotal:subtotal,
				diskon_rupiah:diskon_rupiah,
				diskon_persen:diskon_persen,
				ppn:ppn,
				total:total
			},
			success:function(data){
				$.ajax({
					url:'penjualan/po_customer/simpanPO/<?php echo $id_po;?>',
					type:'post',
					data:{id:id, total:total},
					success:function(data){
						if(data=='done')
						   window.open('penjualan/po_customer','_self');
					   else alert("FAILED");
					}
				});
			}
		});
				
    });
});
</script>

<div class="row margin-bottom-5 ">

    <div class="col-md-12">

        <div class="block" style="">
            <div class="block-body" >
                <form class="form-horizontal" role="form">
				<div class="col-md-12">
                    <div class="form-group">
                        <div class="col-md-1">
                            <label class="control-label text-right ">No. PO.</label>
                            <input id="quoNo" type="text" class="form-control text-uppercase   input-sm" readonly value="<?php echo $nopo;?>">
                        </div>
                        <div class="col-md-2 ">
                            <label class=" control-label text-right">Customer</label>
                            <input id="quoCust" type="text" class="form-control   input-sm" readonly value="<?php echo $namacust;?>">
                        </div>	
                        <div class="col-md-2">
                            <label class=" control-label text-right">Credit Limit</label>
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input id="quoCust" type="text" class="form-control text-right  input-sm" readonly value="2.000.000">
                            </div>
                        </div>	
                        <div class="col-md-4 ">
                            <label class="control-label text-right">Address</label>
                            <input id="quoAddr" type="text" class="form-control   input-sm" readonly value="<?php echo $alamatcust;?>">
                        </div>
                        <div class="col-md-2 ">
                            <label class=" control-label text-right">Sales</label>
                            <input id="quoCust" type="text" class="form-control   input-sm" readonly value="<?php echo $sales;?>">
                        </div>	
                        <div class="col-md-1 ">
                            <label class=" control-label text-right">Image</label>
                           <button  class="form-control input-sm btn-info preview" style="background:#17b8f1; color:white;"  data-preview-image="<?php echo $buktigambar;?>"  data-preview-size="modal-lg">Preview</button>
                        </div>		
                        <div class="col-md-1 ">
                            <label class="control-label text-right">Payment</label>
                            <input id="quoAddr" type="text" class="form-control text-uppercase     input-sm" readonly value="<?php echo $tipebayar;?>">
                        </div>	
                        <div class="col-md-2 ">
                            <label class="control-label text-right">Time Period</label>
                            <input id="quoAddr" type="text" class="form-control text-uppercase     input-sm" readonly value="<?php echo $jangkawaktu;?>">
                        </div>
                        
                    </div>
                
				</div>
                </form>
                <div id="dividerblock" class=" block-divider-text" style="background: #2D3349; padding:0 0 0 5px; color:white; font-size:8px; height:18px;vertical-align:middle;"><span>Input Detail</span>	</div>
				
					<div  class="abcd col-md-12 form-horizontal">
					<!--legend class="font11 padding-top-2"><center><b><i>Input Detail</b></i></center></legend-->
						<div class="form-group">
							<div class="col-md-1 " style="display:none;">
								<label>Produk-Id</label>
								<input id="idproduk" class="ui-widgets form-control  input-sm" type="text" placeholder="" readonly>
							</div>
							<div class="col-md-2 ">
								<label>Produk</label>
								<input id="namaproduk" class="ui-widgets form-control  input-sm" type="text" placeholder="Ketik Nama Produk" autofocus="">
							</div>
							
							<div class="col-md-1 ">
								<label>Packing</label>
								<input id="kemasanproduk" class="form-control  input-sm" type="text" placeholder="" readonly>
								<input id="idkemasanproduk" class="form-control  input-sm" type="hidden" placeholder="" readonly>
							</div>
							<div class="col-xs-2 ">
								<label>Exp Date</label>
								<input id="expiredproduk" class="form-control  input-sm" type="text" placeholder="" readonly>
								<input id="expireddate" class="form-control  input-sm" type="hidden" placeholder="" readonly>
							</div>
							<div class="col-md-1 ">
								<label>Batch</label>
								<input id="batchnumber" class="form-control  input-sm" type="text" placeholder="" readonly>
							</div>
							<div class="col-md-1 ">
								<label>Stock</label>
								<input id="stokproduk" class="form-control  input-sm" type="text" placeholder="" readonly>
							</div>
							<div class="col-md-2 ">
								<label>Qty Request</label>
								<div class="input-group">
									<input id="jumlahpesanan" class="form-control  input-sm" type="number" min="1" placeholder="" >
								</div>
							</div>
							<div class="col-md-1 ">
								<label>Back Order<input id="flagbo" class="form-control input-sm" type="checkbox"></label>
								
							</div>
							<div class="col-md-1 ">
								<label>Back Order Qty</label>
								<input id="jumlahbo" class="form-control  input-sm" type="number" min="1" readonly="" > 
							</div>
							
						</div>
						<div class=" abcd form-group form-horizontal">
							<div class="col-md-2 ">
							</div>
							<div class="col-md-2 ">
								<label>Minimum Selling Price</label>
								<div class="input-group">
									<span class="input-group-addon">Rp</span>
									<input  id="hargajualprodukmin" class="form-control  input-sm text-right" type="text" placeholder="" readonly>
								</div>
							</div>
							<div class="col-md-2 ">
								<label>Price</label>
								<div class="input-group">
									<span class="input-group-addon">Rp</span>
									<input  id="hargajualproduk" class="form-control  input-sm text-right" type="text" placeholder="" readonly>
								</div>
							</div>
							<div class="col-md-1 ">
								<label>Disc %</label>
								<div class="input-group">
									<input id="diskonitempersen" class="form-control  input-sm"  placeholder="" >
								</div>
							</div>
							<div class="col-md-2 ">
								<label>Disc Rp</label>
								<div class="input-group">
								<span class="input-group-addon">Rp</span>
									<input id="diskonitemrp" class="form-control  input-sm" placeholder="" >
								</div>
							</div>
							
							<div class="col-md-2 ">
								<label>SubTotal</label>
								<div class="input-group">
									<span class="input-group-addon">Rp</span>
									<input  id="subtotal" class="form-control  input-sm text-right" type="text" placeholder="" readonly>
								</div>
							</div>
							<div class="col-md-1 ">
								<label>&nbsp;</label>
								<input id="submitDetail" class="form-control input-sm btn-info" style="background:#17b8f1; color:white;" type="submit" value="Tambah">
							</div>
						</div>
					</div>
            </div>
        </div>
    </div>
</div>
<div class="row margin-bottom-5 "  style="">
    <div class="col-md-12">
    <div class="block block-condensed">
        <!-- START HEADING -->
        

        <!-- END HEADING -->

            <div class="block-content" style="overflow-x:auto;">
                <!--table class="table table-head-custom table-striped dataTable no-footer datatable"-->
                <table class="table table-head-custom  table-striped table-bordered datatable dataTable no-footer font11">
                    <thead> 
                        <tr>
                            <th style="width:3%;">No</th>
                            <th style="width:26%;">Product</th>
                            <th style="width:15%;">Batch Number / Exp</th>
                            <th style="width:8%;">Price</th>
                            <th style="width:8%;">Qty Request</th>
                            <th style="width:8%;">Back Order</th>
                            <th style="width:8%;">Current Stock</th>
                            <th style="width:8%;">Discount</th>
                            <th style="width:8%;">SubTotal Back Order</th> 
                        </thead>
                    </th>                                    
                    <tbody id="showdetail">

                    </tbody>
                </table>
			<button id="createBO" class="btn btn-success btn-icon-fixed pull-right"><span class="icon-floppy-disk"></span>Create BO</button>

            </div>
        </div>
    </div> 
</div>
<div class="row">
	<div class="col-md-6">
			
	</div>
	
	
	<div class="col-md-6" id="panel_nominal">
			<div class="panel panel-default" style="">
				<div class="panel-body" >	
					<div class="form-group">
						<label class="col-md-3 control-label">SubTotal</label>
						<div class="col-md-9">
							<input type="text" name="subtotalx"  class="form-control input-sm text-right" id="subtotalx" value="<?php echo number_format($subtotal); ?>" required readonly>
						</div>
					</div>
					
					<div class="form-group">
							<label class="col-md-3 control-label">Diskon </label>
							<div class="col-md-3">
							<div class="input-group">
								<input type="text" name="diskonpersen"  class="form-control input-sm text-right" id="diskonpersen" value="<?php echo $diskon_persen; ?>" readonly>
								<span class="input-group-addon">%</span>
							</div>
							</div>
				
							<div class="col-md-6">
							<div class="input-group">
							   <span class="input-group-addon">Rp</span>
							   <input type="text" name="diskonrp"  class="form-control input-sm text-right" id="diskonrp" value="<?php echo number_format( $diskon_rupiah); ?>" required>
							</div>
							</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-3 control-label">PPN</label>
						<div class="col-md-3">
						<div class="input-group">
							<input type="text" name="ppn"  class="form-control input-sm text-right" id="ppn" value="<?php echo $ppn; ?>" readonly>
							<span class="input-group-addon">%</span>
						</div>
						
						</div>
						<div class="col-md-6">
						<div class="input-group">
						   <span class="input-group-addon">Rp</span>
							<input type="text" name="ppnrp"  class="form-control input-sm text-right" id="ppnrp" value="" readonly>
						</div>	
						</div>	
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Total</label>
						<div class="col-md-9">
							<input type="text" name="total"  class="form-control input-sm text-right" id="total" value="<?php echo $total; ?>" required  readonly>
						</div>
					</div>
					<button id="simpan" class="btn btn-success btn-icon-fixed pull-right"><span class="icon-floppy-disk"></span>Save</button>
				</div>
			</div>
	</div>
</div>

<div class="modal fade" id="preview" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog modal-lg">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

        <div class="modal-content">
            <div class="modal-body"></div>
        </div>
    </div>            
</div>