<?php defined('BASEPATH') OR exit('No direct script access allowed'); 


	$customerName; $quotationNo; $customerAlamat; $sales; $keterangan;
	foreach($getHeaderDetail->result() as $headerDetail)
	{
		$quotationNo = $headerDetail->no_quotation;
		$customerName = $headerDetail->nama;
		$customerAlamat = $headerDetail->alamat;
		$created_by = $headerDetail->created_by;
		$diskon = $headerDetail->diskon;
		$keterangan = $headerDetail->keterangan;
	}
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
var jq = jQuery.noConflict();
</script>

<script>
var jui = jQuery.noConflict();
</script>
<script>
		
		 
		jq(function() {$("#menu_penjualan_quotation").addClass("active");
			$('#diskon').prop('disabled',false);
			$('#diskon').val(0);
			$('#submitDetail').prop('disabled',true);
			$('#idproduk').hide();
			$('#notiferor').hide();
			$('#namaproduk').focus();
			$("#grandtotal").formatCurrency({symbol:'',roundToDecimalPlace:0});
			$("#totalPrice").formatCurrency({symbol:'',roundToDecimalPlace:0});
			var hargamin=0;
			
			//totaldiskon
			$("#totaldiskon").val('<?php echo $diskon;?>');
			var totDis = $("#totaldiskon").val();
				var totHar = $("#totalPrice").asNumber({ parseType: 'int' });
				if(!$.isNumeric(totDis))
				{
					totDis = 0;
				}
				var Dis = parseInt(totHar) * totDis /100;
				console.log("Dis = "+Dis);
				$("td#totaldiskon").html(Dis);
				$("td#totaldiskon").formatCurrency({symbol: '',roundToDecimalPlace :0});
				var Dis = $("td#totaldiskon").asNumber({ parseType: 'int' });
				var FinalResult = parseInt(totHar) - Dis;
				$("#grandtotal").html(FinalResult);
				$("#grandtotal").formatCurrency({symbol: '',roundToDecimalPlace :0});
			//End totaldiskon
			
			$("#diskon").keypress(function(e)
				{
					if(e.which==13)
					{
						$('#submitDetail').focus();
					}
				});
				
			$("#diskon").on("keyup change",function()
			{
				var hargaproduk = $("#hargaproduk").val();
				
				
				var disc = $("#diskon").val();
				
				var hargadiskon = hargaproduk - (hargaproduk*disc/100);
					
				if(hargadiskon >= hargamin)
				{
					$('#submitDetail').prop('disabled',false);
					$("#harganett").val(hargadiskon);
					return false;
					
				}
				else if(hargadiskon<hargamin)
				{
					$('#submitDetail').prop('disabled',false);
					$("#harganett").val(hargadiskon);
					$('#diskon').val('');
					$('#diskon').focus();
				}
					
				
			});
			
			$("#submitDetail").on("click",function()
			{
				var produkId = $("#idproduk").val();
				var hargaProduk = $("#hargaproduk").asNumber({parseType:'float'});
				var disc = $("#diskon").val();
				var hargaNett = $("#harganett").asNumber({parseType:'float'});
				var totalprice =  $("#totalPrice").asNumber({parseType:'float'})
				if(isNaN(totalprice)) totalprice=0;
				var total = totalprice+parseInt(hargaNett);
				
				$.ajax({
					url:"penjualan/quotation/createdetail/",
					data:{produk_id:produkId,harga_jual:hargaProduk,disc:disc,harga_nett:hargaNett,created_by:'<?php echo $created_by;?>',total:total},
					method:"post",
					success:function(data)
					{
						$("#idproduk").val('');
						$("#namaproduk").val('');
						$("#hargaproduk").val('');
						$("#diskon").val('');
						$("#harganett").val('');
						$("#stokproduk").val('');
						location.reload();
					}
				});
			});
			
			$('#namaproduk').autocomplete
			({
				minLength:2,
				source: function (request, response) {
					if($('#namaproduk').val()=='')
                      $("#submitDetail").prop('disabled',true);
					var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
					$.ajax({ 
                            url:"penjualan/quotation/populateinputbarang",
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
                                                        idkemasan: v.kemasan_id,
                                                        exp_date:   v.tanggal_exp+' '+v.bulan_exp+' '+v.tahun_exp,
                                                        kadaluarsa :v.kadaluarsa,
                                                        stok:v.Stok,
                                                        namaproduk: v.nama,
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
                        $("#hargaproduk").val(ui.item.hargajual);
				}
				,
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
                        $("#hargaproduk").val(ui.item.hargajual);
                        $("#harganett").val(parseInt(ui.item.hargajual));
						$("#hargaproduk").formatCurrency({symbol: '',roundToDecimalPlace :0});
						$("#harganett").formatCurrency({symbol: '',roundToDecimalPlace :0});
                        $("#jumlahpesanan").focus();
                        $('#submitDetail').prop('disabled',false);
					
				}
			});
			
			$("#detailBarangQuotation #hapus").click(function()
			{
				var parentTr = $(this).closest('tr');
				var id=parentTr.find('#idDetail').html();
				var nama=parentTr.find('#namaObat').html();
				var hrg=parseInt(parentTr.find('#hargaObat').html());
				var harga= parseInt($("#totalPrice").html())-hrg;
				var del = confirm("Hapus "+nama+" ?");
				if(del == true)
				{
					jq.ajax({
						url:'penjualan/quotation/deleteQuotationDetail',
						type:'post',
						data:{id:id,harga:harga},
						success:function(data)
						{
							if(data=='done')
							{
								
								location.reload();
							}	
							else alert("Item "+nama+ " gagal terhapus");
							
						}
					});
					
				}
			});
			
			$("#detailBarangQuotation #edit").click(function(){
				
				var parentTr = $(this).closest('tr');
				var namaproduk = parentTr.find('#namaObat').html();
				var idprodukobat =parentTr.find('#idprodukObat').html();
				var hargaproduk = parentTr.find('#hargaObat').html();
				var diskon = parentTr.find('#diskonObat').html();
				var harganett = parentTr.find('#harganettObat').html();
				$.ajax({
						'async': false,
						url:"penjualan/quotation/getHargaJualMin/"+idprodukobat,
						type:"GET",
						success:function(data)
						{
							hargamin= data;
						}
				});
				$("#ednamaproduk").val(namaproduk);
				$("#edidproduk").val(idprodukobat);
				$("#eddiskon").val(diskon);
				$("#edhargaproduk").val(hargaproduk);
				$("#edharganett").val(harganett);
				//alert(harganett);
				$("#modal-edit").modal("show");
				
			});
			$("#eddiskon").on("keyup keypress", function(e){
					var hrg = $("#edhargaproduk").val();
					var hrgnett = $("#edharganett").val();
					var disk = $(this).val();
					var hargadiskon = hrg-(hrg*disk/100);
					if(hargadiskon<hargamin)
					{
						alert('Di bawah harga minimums!');
						$("#eddiskon").val('');						
					}
					else
					{
						 $("#edharganett").val(hargadiskon);
					}
				} );
			$('#modal-edit').on('shown.bs.modal', function () {
				$('#eddiskon').focus();
				
			});
			
			$("#edsubmitDetail").click(function(){
				
				var id = $("#idDetail").html();
				var diskon = $("#eddiskon").val();
				if(diskon<1) diskon = 0;
				var nett = $("#edharganett").val();
				
				$.ajax({
					url:'penjualan/quotation/editQuotationDetail/'+id+'/'+diskon+'/'+nett,
					type:'get',
					success:function(data)
					{
						if(data=='done')
							location.reload();
					}
				});
			});
			
			$("#totaldiskon").on("keyup keypress change", function(){
				
				var totDis = $("#totaldiskon").val();
				var totHar = $("#totalPrice").asNumber({ parseType: 'int' });
				if(!$.isNumeric(totDis))
				{
					totDis = 0;
				}
				var Dis = parseInt(totHar) * totDis /100;
				console.log("Dis = "+Dis);
				$("td#totaldiskon").html(Dis);
				$("td#totaldiskon").formatCurrency({symbol: '',roundToDecimalPlace :0});
				var Dis = $("td#totaldiskon").asNumber({ parseType: 'int' });
				var FinalResult = parseInt(totHar) - Dis;
				$("#grandtotal").html(FinalResult);
				$("#grandtotal").formatCurrency({symbol: '',roundToDecimalPlace :0});
				
			});
			
			$("a#simpan").click(function()
			{
				
				var subtotal = $("#totalPrice").asNumber({ parseType: 'int' });
				var diskon = $("#totaldiskon").val();
				var total = $("#grandtotal").asNumber({ parseType: 'int' });
				var keterangan = $("#ket").val();
				var id = <?php echo $_GET['last_id']; ?>;
				//alert(subtotal+' '+diskon+' '+total+' '+id);
				$.ajax({
					url:'penjualan/quotation/updatediskon',
					type:'post',
					data:{subtotal:subtotal,diskon:diskon,total:total, id:id, keterangan:keterangan},
					success:function(data)
					{
                                            if(data=="done")
                                                location.reload();
                                           
					}
				});
				
			}); 
		});
	</script>
<div class="row margin-bottom-5 ">

    <div class="col-md-12">

        <div class="block block-default" style="padding: 10px 20px 0px;margin-bottom: 4px;margin-top:3px;">
            <div class="block-body" >
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label class="control-label text-right">Quotation No.</label>
                            <input id="quoNo" type="text" class="form-control   input-sm" readonly value="<?php echo $quotationNo; ?>">
                        </div>
                        <div class="col-md-3 ">
                            <label class=" control-label text-right">Customer</label>
                            <input id="quoCust" type="text" class="form-control   input-sm" readonly value="<?php echo $customerName; ?>">
                        </div>						
                        <div class="col-md-4 ">
                            <label class="control-label text-right">Address</label>
                            <input id="quoAddr" type="text" class="form-control   input-sm" readonly value="<?php echo $customerAlamat; ?>">
                        </div>

                    </div>
                </form>
                <div class=" block-divider-text" style="margin-bottom: 5px;font-size: 8px; height: 20px;padding-bottom: 3px;padding-top: 1px;background: #ddf8eb;">Input Detail</div>

                <div class="col-md-3 ">
                    <label>Product</label>
                    <input id="namaproduk" class="ui-widgets form-control  input-sm" type="text" placeholder="Ketik Nama Produk">
                    <input id="idproduk" class="ui-widgets form-control  input-sm" type="text" placeholder="id">
                </div>
                <div class="col-md-1 ">
                    <label>Stock</label>
                    <input id="stokproduk" class="form-control  input-sm" type="text" placeholder="" readonly>
                </div>
                <div class="col-md-2 ">
                    <label>Price</label>
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input id="hargaproduk" class="form-control  input-sm" type="text" placeholder="" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <label>Disc(%)</label>
                    <div class="input-group">
                        <input id="diskon" class="form-control input-sm" type="text" placeholder="" value="" >
                    </div>
                </div>
                <div class="col-md-2 ">
                    <label>Nett Price</label>
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input  id="harganett" class="form-control  input-sm" type="text" placeholder="" readonly>
                    </div>
                </div>
                <div class="col-md-1 ">
                    <label>&nbsp;</label>
                    <input id="submitDetail" class="form-control input-sm btn-info" style="background:#17b8f1; color:white;" type="submit" value="Tambah">
                    <button id="notiferor" type="button" class="btn btn-default notify" data-notify-type="error" data-notify="<strong>Error</strong>Di Bawah Harga Minimum!"><span>Show Me</span></button>
                </div>

            </div>
        </div>
    </div>

</div>

        <div class="row"> 
            <div class="col-md-12">
                <div class="panel panel-default" style="margin-bottom:5px;">
                    <div class="panel-body" style="max-height:231px;overflow:auto;">
                        <table id="detailBarangQuotation" class="table table-striped table-bordered" >
                            <thead>
                                <tr>
                                    <th style="width:3%;">No.</th>
                                    <th style="display:none;">Id Detail Obat</th>
                                    <th style="width:55%;">Product</th>
                                    <th style="width:10%;">Price </th>
                                    <th style="display:none;">Harga </th>
                                    <th style="width:8%;">Discount (%) </th>
                                    <th style="width:10%;">Sub Total</th>
                                    <th style="display:none;">Id Detail Obat</th>
                                    <th style="width:10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($quotationDetailList->result() as $r) {
                                    echo "<tr>";
                                    echo "<td>" . $no . "</td>";
                                    echo "<td id='idDetail' style='display:none;'>" . $r->id . "</td>";
                                    echo "<td id='idprodukObat' style='display:none;'>" . $r->produk_id . "</td>";
                                    echo "<td id='namaObat'>" . $r->nama . "</td>";
                                    echo "<td id='hargaObatMasks'>" . number_format($r->harga_jual) . "</td>";
                                    echo "<td id='hargaObat'  style='display:none;'>" . $r->harga_jual . "</td>";
                                    echo "<td id='diskonObat'>" . $r->disc . "</td>";
                                    echo "<td id='hargaObatMask'>" . number_format($r->harga_nett) . "</td>";
                                    echo "<td id='harganettObat' style='display:none;'>" . $r->harga_nett . "</td>";
                                    echo "<td>" .
                                    "<a id='hapus' title='Hapus' ><span class='icon-trash2'></span></a>&nbsp;
											 <a id='edit'  title='Edit' ><span class='icon-register'></span></a>" .
                                    "</td>";
                                    echo "</tr>";
                                    $no++;
                                }
                                ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
			</div>
            <div class="col-md-3">
				<div class="panel panel-default" style="margin-bottom:0px;">
					<div class="panel-body" >
						<label for="ket">Note:</label>
						<textarea cols="100" rows="50" id="ket" style="width:259px; height:160px;"><?= $keterangan ?></textarea>
					</div>
				</div>
			</div>
            <div class="col-md-4">
                <div class="panel panel-default" style="margin-bottom:0px;">
				<div class="panel-body" >		
                     <table class="table  " align="right" style="text-align:center;">
                        <thead>
                            <tr>
                                <td style="text-align:right;">Sub Total </td>
                                <td id="totalPrice"><?php foreach ($totalHarga->result() as $r)
                                    echo $r->Total; ?> </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;" >Additional Discount&nbsp;<input style="text-align:right; width:40px; margin-left:2px;" id="totaldiskon" type="text">&nbsp;% </td>
                                <td id="totaldiskon">0</td>
                            </tr>
                            <tr>
                                <td style="text-align:right" >Total &nbsp; </td>
                                <td id="grandtotal"><?php foreach ($totalHarga->result() as $r)
                                    echo $r->Total; ?></td>
                            </tr>
                            <tr>
                                <td style="text-align:right">

                                    <a target='blank' title='Cetak' href="penjualan/quotation/printquotation/<?php echo $_GET['last_id'] ?> "><span class='icon-printer'></span> Cetak</a>  
                                    &nbsp;	&nbsp;	<a title='Simpan' id="simpan"><span class='icon-floppy-disk'></span> Simpan</a>  </td>
                                <td id=""></td>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>
<!-- M O D A L -->
<div class="modal fade" id="modal-edit"  role="dialog" aria-labelledby="modal-default-header">
    <div class="modal-dialog  modal-lg" role="document">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

        <div class="modal-content">
            <div class="modal-header">                        
                <h4 class="modal-title" id="modal-default-header">Edit Detail</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-3 ">
                    <label>Product</label>
                    <input id="ednamaproduk" class="ui-widgets form-control  input-sm" type="text" placeholder="Product Name" readonly>
                    <input id="edidproduk" style="display:none;" class="ui-widgets form-control  input-sm" type="text" placeholder="id">
                </div>
                <div class="col-md-2 ">
                    <label>Price</label>
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input id="edhargaproduk" class="form-control  input-sm" type="text" placeholder="" readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <label>Disc(%)</label>
                    <div class="input-group">
                        <input id="eddiskon" class="form-control input-sm" type="text" placeholder="" >
                    </div>
                </div>
                <div class="col-md-2 ">
                    <label>Nett Price</label>
                    <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input  id="edharganett" class="form-control  input-sm" type="text" placeholder="" readonly>
                    </div>
                </div>
                <div class="col-md-2 ">
                    <label>&nbsp;</label>
                    <input id="edsubmitDetail" class="form-control input-sm btn-info" style="background:#17b8f1; color:white;" type="submit" value="Save">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div> 
</div>
	
<script>

</script>