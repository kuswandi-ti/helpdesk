<?php
$id_po;$nopo;$namacust;$alamatcust;$sales;$buktigambar;
foreach($po_header->result() as $r)
{
    $id_po = $r->id;
    $nopo = $r->no_po;
	$idcust = $r->id_cust;
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
	$credit_limit = $r->credit_limit;
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
            url:'penjualan/po_customer/loadDetail/'+<?php echo $id_po;?>,
            type:'get',
            success:function(data){
                $("#showdetail").html(data);
				getStatusPo();
            }
        });
    }
	
    function getNumDetail()
    {
        $.ajax({
            url:'penjualan/po_customer/getNumDetail/'+<?php echo $id_po;?>,
            type:'get',
            success:function(data){
               if(data=='0')
				   $("#simpan").prop("disabled",true);
            }
        });
    }
	
	function getHeaderNominal()
	{
		var id_po = <?php echo $id_po;?>;
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
					  
					  $("#ppn").val(ppn);
					  $("#total").val(total);
					   $("#subtotalx").val(subtotal);
					   $("#subtotalx").formatCurrency({symbol:'',roundToDecimalPlace:0});
					   $("#diskonrp").val( parseInt($("#diskonpersen").val())/100*subtotal);
					   $("#diskonrp").formatCurrency({symbol:'',roundToDecimalPlace:0});
					  getnominal();
				  }
			  });
	}
	
	function getStatusPo()
	{
		$.ajax({
            url:'penjualan/po_customer/getStatus/'+<?php echo $id_po;?>,
            type:'get',
            success:function(data){
               if(data=='draft'){
				   $("#simpan").prop("disabled",false);
				   $("#namaproduk").prop("disabled",false);
				   $("#diskonrp").prop("readonly",false);
				   console.log("dis draft");
				   $("#diskonpersen").prop("readonly",false);
				   $("#ppn").prop("readonly",false);
				   $("#ppnrp").prop("readonly",false);
				   getNumDetail();
				}
               else if(data=='revisi'){
				   $("#simpan").prop("disabled",false);
				   $("#namaproduk").prop("disabled",false);
				   $("#diskonrp").prop("readonly",false);
				   $("#diskonpersen").prop("readonly",false);
				   console.log("dis rev");
				   $("#ppn").prop("readonly",false);
				   $("#ppnrp").prop("readonly",false);
				   getNumDetail();
				}
                else if(data=='pending'){
				   $("#simpan").hide();
				   $("#namaproduk").prop("disabled",true);
				   $("#diskonrp").prop("readonly",true);
				   $("#diskonpersen").prop("readonly",true);
				   console.log("dis pen");
				   $("#ppn").prop("readonly",true);
				   $("#ppnrp").prop("readonly",true);
				   $(".abcd").hide();
				   $("#dividerblock").hide();
				}
                else if(data=='reject'){
				   $("#simpan").hide();
				   $("#namaproduk").prop("disabled",true);
				   $("#diskonrp").prop("readonly",true);
				   $("#diskonpersen").prop("readonly",true);
				   console.log("dis rej");
				   $("#ppn").prop("readonly",true);
				   $("#ppnrp").prop("readonly",true);
				   $(".abcd").hide();
				   $("#dividerblock").hide();
				}
				 else if(data=='approved'){
				   $("#simpan").hide();
				   $("#namaproduk").prop("disabled",true);
				   $("#diskonrp").prop("readonly",true);
				   $("#diskonpersen").prop("readonly",true);
				   console.log("dis apr");
				   $("#ppn").prop("readonly",true);
				   $("#ppnrp").prop("readonly",true);
				   $(".abcd").hide();
				   $("#dividerblock").hide();
				}
			}
        });
	}
    function del(id,id_po)
    {
		//alert();
		var conf=confirm("Delete this item?");
		if(!conf)
			return;
        $.ajax({
            url:'penjualan/po_customer/delDetail',
            type:'post',
            data:{
                id:id,
                id_po:id_po
            },
            success:function(data){
                loadDetail();
				getNumDetail();
				getHeaderNominal();
            }
        });
    }
	
	$(document).on('click', '#edit',function()
    {
		$("#edit_detail").modal('show');
		$("#edit_product").val($(this).closest('tr').find('#detail_product').html());
		$("#edit_product").prop('disabled',true);
		$("#edit_expired").val($(this).closest('tr').find('#detail_expbatch').html());
		$("#edit_expired").prop('disabled',true)
		$("#edit_price").val($(this).closest('tr').find('#detail_sellingprice').html());
		$("#edit_qty").val($(this).closest('tr').find('#detail_qty').text());
		$("#edit_disc_persen").val($(this).closest('tr').find('#detail_discpersen').asNumber({ parseType: 'float' }));
		$("#edit_disc_rupiah").val($(this).closest('tr').find('#detail_discrp').asNumber({ parseType: 'float' }));
		$("#edit_subtotal").val($(this).closest('tr').find('#detail_subtotal').text());
		$("#edit_backorder").val($(this).closest('tr').find('#detail_jumlah_backorder').val());
		$("#edit_stock").val($(this).closest('tr').find('#detail_stok').val());
		$("#edit_id").val($(this).closest('tr').find('#cek_id').val());
		var stok = parseInt($(this).closest('tr').find('#detail_stok').val());
		var request = $(this).closest('tr').find('#detail_qty').text();
				
		if(request>stok)
		{
			
			$("#edit_flagbo").prop('disabled',false)
			$("#edit_flagbo").prop('checked',true)
			$(this).closest('tr').find('#detail_jumlah_backorder').val(request-stok);
		}
		else
		{
			$("#edit_flagbo").prop('checked',false)
			$("#edit_flagbo").prop('disabled',true)
		}
		
    });
	
	
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
		getStatusPo();
		getNumDetail();
		getHeaderNominal();
		getnominal();
		
        $('#submitDetail').prop('disabled',true);
        $("#diskonitemrp").prop('disabled',true);
        $("#flagbo").prop('disabled',true);
        $("#diskonitempersen").prop('disabled',true);
		$("#subtotalx").formatCurrency({symbol:'',roundToDecimalPlace:0});
        $('#namaproduk').autocomplete
        ({ 
			max:10,
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
											} ;
								}
							}))
								//response(results);		
						}
				})
                },
				search: function (e, u) {
					$('#loader').html("<img src='<?php echo base_url();?>assets/img/ui-anim_basic_16x16.gif'>");
					
				},
				response: function (e, u) {
					$('#loader').html("<img src='<?php echo base_url();?>assets/img/done_16x16.png'>");
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
                        $("#diskonitempersen").val(0);
                        $("#diskonitemrp").val(0);
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
						$("#diskonitempersen").val(0);
                        $("#diskonitemrp").val(0);
                        $('#submitDetail').prop('disabled',false);
						$("#jumlahpesanan").val('1');
						$("#subtotal").val(ui.item.hargajual);
						
						$("#jumlahpesanan").focus();
						$('#loader').html("");
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
    $("#jumlahpesanan").on("keyup change", function(e){
		//e.preventDefault();
        var hrg = $("#hargajualproduk").asNumber({ parseType: 'float' });
        var qty = $("#jumlahpesanan").val();
        var disc_persen = $('#diskonitempersen').asNumber({parseType:'float'}) / 100;
		
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
			subtotalitem = tot-(tot*disc_persen);
			$("#subtotal").val(subtotalitem);
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
			subtotalitem = tot-(tot*disc_persen);;
			$("#subtotal").val(subtotalitem);
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
	
	$("#diskonitempersen").on("keyup", function(e){
		var diskper = $(this).asNumber({parseType:'float'});
		if($(this).val()=='') 
			diskper = 0;
		var stok = parseInt($("#stokproduk").val());
		var diskrp = $("#diskonitemrp").asNumber({parseType:'float'});
		if($("#diskonitemrp").val()=='')
			diskrp = 0;
		var hrg = $("#hargajualproduk").asNumber({ parseType: 'float' });
        var qty = $("#jumlahpesanan").val();
		if(qty > stok)
        {
			var subtotal = hrg*stok;
		}
		if(qty <= stok)
        {
			var subtotal = hrg*qty;
		}
		
		var potongan = diskper/100 * subtotal;
		//alert(subtotalitem);
		$("#diskonitemrp").val(potongan);
		$("#diskonitemrp").formatCurrency({symbol:'',roundToDecimalPlace:0});
		$("#subtotal").val(subtotal-potongan);
		$("#subtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
	});
	$("#diskonitemrp").on("keyup", function(e){
		var diskper = $(this).asNumber({parseType:'float'});
		
		var stok = parseInt($("#stokproduk").val());
		if($(this).val()=='') 
			diskper = 0;
		var diskrp = $("#diskonitemrp").asNumber({parseType:'float'});
		if($("#diskonitemrp").val()=='')
			diskrp = 0;
		var hrg = $("#hargajualproduk").asNumber({ parseType: 'float' });
        var qty = $("#jumlahpesanan").val();
		if(qty > stok)
        {
			var subtotal = hrg*stok;
		}
		if(qty <= stok)
        {
			var subtotal = hrg*qty;
		}
		var persenan = diskper*100/subtotal;
		//alert(subtotalitem);
		$("#diskonitempersen").val(persenan);
		
		
		$("#subtotal").val(subtotal-diskrp);
		$("#subtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
	});
	
    $("#diskonpersen").on("keyup change", function(e){
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
	
	$("#diskonrp").on("keyup change", function(e){
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
    
    $("#submitDetail").on("click",function(){
	//cari id ini di dalem tabel//
       var id_produk =  $("#idproduk").val();
       var batch_number =  $("#batchnumber").val();
       var expired_date = $("#expireddate").val();
       var expired_produk = $("#expiredproduk").val();
	/// 
       var info_stok =  $("#stokproduk").val();
       var id_kemasan =  $("#idkemasanproduk").val();
       var harga_jual =  $("#hargajualproduk").asNumber({ parseType: 'float' });
       var harga_jual_min =  $("#hargajualprodukmin").asNumber({ parseType: 'float' });
       var disc_persen =  $("#diskonitempersen").asNumber({ parseType: 'float' });
       var disc_rp =  $("#diskonitemrp").asNumber({ parseType: 'float' });
       var jumlah_pesanan =  $("#jumlahpesanan").val();
       var jumlahbo =  $("#jumlahbo").val();
	   if(jumlah_pesanan=='') jumlah_pesanan=1;
       var sub_total =  $("#subtotal").asNumber({ parseType: 'float' });;
       var id_po =  <?php echo $id_po; ?>;
	   
	   var cek_id_produk ='';
	   var cek_batch_number ='';
	   var cek_kadaluarsa ='';
	   var is_available=0;
	   $( '#table_detail tbody tr td:nth-child(2)').each( function(){
		   //add item to array
		 
		   cek_id_produk = $(this).closest('tr').find('#cek_id_produk').val();
		    cek_batch_number = $(this).closest('tr').find('#cek_batch_number').val();
		    cek_kadaluarsa = $(this).closest('tr').find('#cek_kadaluarsa').val();
		   
		    if(id_produk == cek_id_produk && cek_batch_number == batch_number && cek_kadaluarsa==expired_produk)
			{	
				is_available++;
			}
		   
		});
	   
	 if(is_available>0)
	 {
		alert("Item already exist on table!");
		return;
	 }
	 var subtotal_min = harga_jual_min * jumlah_pesanan;
	 if(subtotal_min > sub_total)
	 {
		 alert("Subtotal is lower than minimum price!");
		 return;
	 }
	   
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
		var total = $("#total").asNumber({ parseType: 'float' });
		var diskon_persen = $("#diskonpersen").asNumber({ parseType: 'float' });
		var diskon_rupiah = $("#diskonrp").asNumber({ parseType: 'float' });
		var ppn = $("#ppn").asNumber({ parseType: 'float' });
		var credit_limit =  $("#cl").asNumber({ parseType: 'float' });
		var id = <?php echo $id_po;?>;
		var con = confirm("Process this request?");
		var min_faktur = 200000;
		
		var param = 1; // 1 -> direct to SO, 0 -> approval.
		var reason = '';
		if(con){
			
			var aging;
			var idcust = '<?php echo 'c'.$idcust;?>';
			$.ajax({
				url:'penjualan/po_customer/getaging/'+idcust,
				type:'get',
				async:false,
				success:function(data)
				{
					if(data=='clear')
						aging = 0;
					else aging = data;
					
				}
			});
			
			if(aging!=0)
			{
				 var proses = confirm("Customer ini memiliki tunggakan, "+aging+" lanjutkan ke approval?");
				if(proses)
				{
					param = 0;
					reason += aging+' <br>';
				}
				else {
					alert("Process canceled");
					return false;
				} 
			}
			
			if(total>credit_limit)
			{
				var proses = confirm("Total price is higher than credit limit, continue to approval?");
				if(proses)
				{
					param = 0;
					reason = 'Total Price Exceeds Credit Limit <br>';
				}
				else {
					alert("Process canceled");
					return false;
				}
			}
			if(total<min_faktur)
			{
				var proses = confirm("Total price is lower than minimum invoice, continue to approval?");
				if(proses)
				{
					param = 0;
					reason = 'Total Price Below Minimum Invoice <br>';
				}
				else {
					param = 1;
					alert("Process canceled");
					return false;
				} 
			}
			if(param==0)
			{
				//alert("Ke Aproval Dulu Dengan Alasan : "+reason);
			
				// send to approval
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
							data:{id:id, total:total, param:param, reason:reason},
							success:function(data){
								if(data=='done'){
									alert("Waiting Approval");
								   window.open('penjualan/po_customer','_self');
								}
							
							   else alert(data);
							}
						});
					}
					});	
			}	
			else {
				//alert("Langsung approved");
				var msg = '';
				var respon = 'approved'
				var id_po = <?php echo $id_po;?>;
				var user = <?php  echo "'".$_SESSION['user_name']."'";?>;
				var tanggal = moment().format('DD-MM-YYYY');
				var jam = moment().format('h:mm:ss');
				//alert(msg);
				$.ajax({
					url:'penjualan/approvalpo/approval',
					type:'post',
					data:{respon:respon, message:msg, approved_by:user, id_po:id_po, tanggal:tanggal, jam:jam },
					success:function(data){
							alert("SO Created!");
							window.open('penjualan/po_customer/','_self');
						
					}
				});
			}
			
			
		}
				
    });
	
	
	//EDIT SECTION
	
	
	
	$("#edit_qty").on("change keyup keypress", function(e){

		var request = $(this).val();
		if(request=='' || !$.isNumeric(request))	
			request==0;
		var harga = $("#edit_price").asNumber({parseType:'float'});
		var backorder = $("#edit_backorder").val();
		var stok = parseInt($("#edit_stock").val());
		
		var diskper = parseInt($("#edit_disc_persen").val());
		if(diskper=='' || !$.isNumeric(diskper))
		{
			diskper=0;
		}
		//console.log('backorder '+backorder );
		var subtotal=0;
		
		if(request>stok)
		{
			$("#edit_flagbo").prop('disabled',false);
			$("#edit_flagbo").prop('checked',true);
			$("#edit_backorder").val(request-stok);
			subtotal=harga*stok-(harga*stok)*diskper/100;
			var diskrup = diskper/100*harga*stok;
		}
		else
		{
			$("#edit_flagbo").prop('checked',false);
			$("#edit_flagbo").prop('disabled',true);
			$("#edit_backorder").val(0);
			subtotal=harga*request-(harga*request)*diskper/100;
			var diskrup = diskper/100*harga*request;
		}
		
		$("#edit_subtotal").val(subtotal);
		$("#edit_subtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
		
		$('#edit_disc_rupiah').val(diskrup);
		$('#edit_disc_rupiah').formatCurrency({symbol:'',roundToDecimalPlace:0});
		
	});
	
	$("#edit_flagbo").change(function() {
		var edit_cflagbo;
		var qty = $("#edit_qty").val();
        var hrg = $("#edit_price").asNumber({ parseType: 'float' });
		var stok = parseInt($("#edit_stock").val());
		if(!this.checked) { //kalo diuncek
			$("#edit_flagbo").prop("disabled",true);
			$("#edit_backorder").val(0);
			$("#edit_qty").val(stok);
			edit_cflagbo = 0;
			$("#edit_subtotal").val(hrg*stok);
			$("#edit_subtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
		}
	});	
	
	$("#edit_disc_persen").on("change keyup keypress", function(e){
		
		var hrg = $("#edit_price").asNumber({ parseType: 'float' });
		var diskper = parseInt($(this).val());
		if(diskper=='' || !$.isNumeric(diskper))
		{
			
			diskper=0;
		}
		if(diskper>100){
			$(this).val(100);
			diskper=100;
		}
		
		//console.log(diskrup);
		
		var request = $("#edit_qty").val();
		var stok = parseInt($("#edit_stock").val());
		var subtotal = 0;
		
		if(request>stok)
		{
			var diskrup = diskper/100*hrg*stok;
			subtotal = (hrg*stok) - (hrg*stok)*diskper/100;
		}
		else
		{
			var diskrup = diskper/100*hrg*request;
			subtotal = (hrg*request)-(hrg*request)*diskper/100;
		}
		$('#edit_disc_rupiah').val(diskrup);
		$('#edit_disc_rupiah').formatCurrency({symbol:'',roundToDecimalPlace:0});
		
		
		$("#edit_subtotal").val(subtotal);
		$("#edit_subtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
	})
	
	$("#edit_disc_rupiah").on("change keyup keypress", function(e){
	
		var hrg = $("#edit_price").asNumber({ parseType: 'float' });
		var diskrup = $(this).asNumber({ parseType: 'float' });
		if(diskrup=='' || !$.isNumeric(diskrup))
		{
			diskrup=0;
		}
		var diskper = 100/hrg*diskrup;
		
		$('#edit_disc_persen').val(diskper);
		
		
		var request = $("#edit_qty").val();
		var stok = parseInt($("#edit_stock").val());
		var subtotal = 0;
		if(request>stok)
		{
			subtotal = (hrg*stok) - diskrup;
		}
		else subtotal = (hrg*request)-diskrup;
		
		$("#edit_subtotal").val(subtotal);
		$("#edit_subtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
		$(this).formatCurrency({symbol:'',roundToDecimalPlace:0});
	});
		
	$("#simpan_edit").on('click',function(){
		var back_order = $("#edit_flagbo").is(':checked');
		var jumlah_pesanan = parseInt($("#edit_qty").val());
		var jumlah_back_order = parseInt($("#edit_backorder").val());
		var disc_rp = $('#edit_disc_rupiah').asNumber({parseType:'float'});
		var disc_persen = $('#edit_disc_persen').asNumber({parseType:'float'});
		var sub_total = $('#edit_subtotal').asNumber({parseType:'float'});
		var id_detail = $('#edit_id').val();
		var idpo = <?php echo $_GET['last_id'] ;?>;
		if(back_order)
			back_order=1;
		else back_order=0;
		console.log('Last id : '+idpo+', QTY : '+jumlah_pesanan+', QTY BO : '+jumlah_back_order+', DISC RP : '+disc_rp+', DISC % : '+disc_persen+', SUBTOTL : '+sub_total+', ID DET : '+id_detail);
		var conf = confirm("Save the changes?");
		if(conf)
		{
			$.ajax({
				url:'penjualan/po_customer/edit_detail',
				type:'post',
				data:
				{
					id:id_detail, back_order:back_order, jumlah_pesanan:jumlah_pesanan,jumlah_back_order:jumlah_back_order,
					disc_rp:disc_rp, disc_persen:disc_persen, sub_total:sub_total, idpo:idpo
				},
				success:function(data){
					if(data=='done')
						alert("Edit Success");
						location.reload()
				}
			});
		}
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
                                <input id="cl" type="text" class="form-control text-right  input-sm" readonly value="<?=number_format($credit_limit)?>">
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
								<div class="input-group">
									<span id="loader" class="input-group-addon"></span>
									<input id="namaproduk" class="ui-widgets form-control  input-sm" type="text" placeholder="Ketik Nama Produk" autofocus="">
								</div>
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
								<input id="submitDetail" class="form-control input-sm btn-info" style="background:#17b8f1; color:white;" type="submit" value="Add Item +">
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
                <table id="table_detail" class="table table-head-custom  table-striped table-bordered datatable dataTable no-footer font11">
                    <thead> 
                        <tr>
                            <th style="width:3%;">No</th>
                            <th style="width:24%;">Product</th>
                            <th style="width:15%;">Batch Number / Exp</th>
                            <th style="width:8%;">Price</th>
                            <th style="width:8%;">Qty Request</th>
                            <th style="width:8%;">Back Order</th>
                            <th style="width:8%;">Discount</th>
                            <th style="width:8%;">SubTotal</th>
                            <th>&nbsp;</th>
                        </thead>
                    </th>                                    
                    <tbody id="showdetail">

                    </tbody>
                </table>

            </div>

        </div>
    </div> 
</div>
<div class="row">
	<div class="col-md-6">
			
	</div>
	
	
	<div class="col-md-6">
			<div class="panel panel-default" style="">
				<div class="panel-body" >	
					<div class="form-group">
						<label class="col-md-3 control-label">SubTotal</label>
						<div class="col-md-9">
							<input type="text" name="subtotalx"  class="form-control input-sm text-right" id="subtotalx" value="<?php echo number_format($subtotal); ?>" required readonly>
						</div>
					</div>
					
					<div class="form-group">
							<label class="col-md-3 control-label">Discount </label>
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
 <div class="modal fade" id="edit_detail" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">                    
                    <div class="modal-content">                    
                        <div class="modal-body">

                            <div class="app-heading app-heading-small app-heading-condensed">                                
                                <div class="title">
                                    <h5>Edit Detail</h5>
                                    
                                </div>
                            </div>

                            <table class="table">
								<tr>
									<td class="text-right" style="width:30%;">Product</td>
									<td style="width:50%;">
										<input class="bg-primary"  type="text" id="edit_product" >
										<input class="bg-primary"  type="hidden" id="edit_id" >
									</td>
								</tr>
								<tr>
									<td class="text-right">Batch - Expired Date </td>
									<td><input class="bg-primary" type="text" id="edit_expired" readonly></td>
								</tr>
								<tr>
									<td class="text-right">Selling Price</td>
									<td><input class="bg-primary " type="text" id="edit_price" readonly></td>
								</tr>
								<tr>
									<td class="text-right">Stock</td>
									<td><input class="bg-primary" type="text" id="edit_stock" readonly></td>
								</tr>
								<tr>
									<td class="text-right">Qty Request</td>
									<td><input type="number" id="edit_qty" min='0'></td>
								</tr>
								<tr>
									<td class="text-right">Back Order</td>
									<td><input class="bg-primary"  id="edit_flagbo" type="checkbox">&nbsp; <input type="number" id="edit_backorder" readonly></td>
								</tr>
								<tr>
									<td class="text-right">Disc (%)</td>
									<td><input type="text" id="edit_disc_persen" >%</td>
								</tr>
								<tr>
									<td class="text-right">Disc (Rp)</td>
									<td><input type="text" id="edit_disc_rupiah" ></td>
								</tr>
								<tr>
									<td class="text-right">Subtotal</td>
									<td><input type="text" id="edit_subtotal" readonly></td>
								</tr>
							</table>
							<center><button id="simpan_edit" class="btn btn-success">Save Edit</button></center>
                            <p class="text-right"><button class="btn btn-default" data-dismiss="modal">Close</button></p>
                        </div>                    
                    </div>
                </div>            
            </div>