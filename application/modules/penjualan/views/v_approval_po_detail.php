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
var nominalToSubmit;
var totalCheckBox;

$(window).load(function(){
	$.ajax({
		url:'penjualan/approvalpo/getTotalRow/'+<?php echo $id_po;?>,
                    async:false,
		type:'get',
		success:function(data){
			nominalToSubmit = data;
		}
	});
	
	totalCheckBox = nominalToSubmit;
	//alert("cb : "+totalCheckBox+" ts: "+nominalToSubmit);
		console.log("NominalToSubmit: "+nominalToSubmit+", totalChecked: "+totalCheckBox);
	
});

$(document).on('click','#setqtyacc',function(){
	var id = $(this).closest('tr').find("#id_detail").val();
	var idpo = $(this).closest('tr').find("#id_po").val();
	var qtyAcc = $(this).closest('tr').find("#numqtyacc").val();
	var stok = $(this).closest('tr').find("#numstok").val();
	//alert(stok);
	 $.ajax({
		url:'penjualan/approvalpo/setQtyAcc/',
		type:'post',
		data:{id_detail:id, qty:qtyAcc,stok:stok, idpo:idpo},
		success:function(data){
			if(data=="done")
			{
				location.reload();
			}
		}
	}); 
});
$(document).on('change','#checker',function(){
	var id_po = $(this).closest('tr').find('#id_po').val();
	var id_detail= $(this).closest('tr').find('#id_detail').val();
	ckb = $(this).is(':checked');
	if(ckb) //kalo dicek
	{
		var jumlah_acc = $(this).closest('tr').find("#numqtyacc").val();
		
		//do ajax restore flag acc to 1
		
		$.ajax({
			url:'penjualan/approvalpo/setAcc/',
			type:'post',
			data:{acc:'1', id_po:id_po, id:id_detail,jumlah_acc:jumlah_acc},
			success: function(data){
				console.log(data);
						location.reload();
				getnominal();
			//	alert('Undo Done!');
			}
		});
	}
	else
	{
		$.ajax({
					url:'penjualan/approvalpo/setAcc/',
					type:'post',
					data:{acc:'0', id_po:id_po, id:id_detail, keterangan:'reject item'},
					success: function(data){
						console.log(data);
						location.reload();
						getnominal();
						//alert('Rejecting Item Done!');
					}
				});
	}
	
});

$(document).on('click','#setter',function(){
	var $this =$(this);
	
	var id_po = $(this).closest('tr').find('#id_po').val();
	var id_detail = $(this).closest('tr').find('#id_detail').val();
	
	if($this.hasClass('alreadyset')){ // is undo
		//do ajax restore flag acc to 1
		$.ajax({
			url:'penjualan/approvalpo/setAcc/',
			type:'post',
			data:{acc:'1', id_po:id_po, id:id_detail},
			success: function(data){
				console.log(data);
				getnominal();
			//	alert('Undo Done!');
			}
		});
		$this.closest('tr').find('#reason').val('');
		
		$this.toggleClass('set');
		$this.toggleClass('alreadyset');
		$this.text('Set');
		$this.prop('disabled',true);
	}
	
	else{  //kasih keterangan aja
		
		//get acc qty
		var reason=$this.closest('tr').find("#reason").val();
		var jumlah_acc = $this.closest('tr').find("#numqtyacc").val();
		
		//do ajax restore flag acc to 1
		
		
		$.ajax({
			url:'penjualan/approvalpo/setAcc/',
			type:'post',
			data:{acc:'1', id_po:id_po, id:id_detail,jumlah_acc:jumlah_acc,keterangan:reason},
			success: function(data){
				console.log(data);
				getnominal();
			//	alert('Undo Done!');
			}
		});
		
	}
	
});
function ceksubmit(a,b)
{
	if(a==b)
		$("#submitRespon").prop("disabled",false);
}
function loadDetail()
{
$.ajax({
	url:'penjualan/approvalpo/loadDetail/'+<?php echo $id_po;?>,
	type:'get',
	success:function(data){
		$("#showdetail").html(data);
		
		getNumDetail();
		
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
	   else $("#simpan").prop("disabled",false);
	}
});
}
  function getnominal()
	{
		//updateResult
		var id_po =<?php echo $id_po;?>;
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
						var total = subtotal+ppnrp-diskon_rupiah;
						$("#total").val(total);
						$("#total").formatCurrency({symbol:'',roundToDecimalPlace:0});
				  }
		 });
		console.log('called');
		
		
	}
function getStatusPo()
{
	$.ajax({
		url:'penjualan/po_customer/getStatus/'+<?php echo $id_po;?>,
		type:'get',
		success:function(data){
		   if(data=='pending'){
			   $("#simpan").prop("disabled",true);
			   $("#namaproduk").prop("disabled",true);
		   }
		}
	});
}

$(function(){
	$("#menu_approval").addClass("active");
	loadDetail();
	getStatusPo();
	getnominal();
	$("#submitRespon").on('click',function()
	{
		var msg = $("#txtketerangan_input").val();
		var respon = $("input[name='rdostatus']:checked").val();
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
				
					window.open('penjualan/approvalpo/','_self');
				
			}
		});
	});
	
	$("#histori").on("click",function(){
		$.ajax({
			url:'penjualan/approvalpo/gethistori/'+<?php echo $id_po;?>,
			type:'get',
			success:function(data)
			{
				//alert(data);
				$('.modal-body').html(data);
				$('#listhistori').modal('show');
			}
		});
	});
});


</script>

<div class="row margin-bottom-5 ">

    <div class="col-md-12">

        <div class="block">
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
							<div class="input-group">
								<input id="quoAddr" type="text" class="form-control text-uppercase     input-sm" readonly value="<?php echo $jangkawaktu;?>">
								<span class="input-group-addon">Days</span>
							</div>
                        </div>
                        
                    </div>
                
				</div>
                </form>
                
            </div>
        </div>
    </div>
</div>
<div class="row margin-bottom-5 ">
    <div class="col-md-12">
    <div class="block block-condensed">
        <!-- START HEADING -->
        

        <!-- END HEADING -->

            <div class="block-content" style="overflow-x:auto;">
                <table class="table table-head-custom table-striped table-bordered dataTable no-footer datatable  font11" style="margin-top:16px !important;">
                    <thead> 
                        <tr class="font11">
                             <th style="width:3%;">No</th>
                            <th style="width:15%;">Product</th>
                            <th style="width:15%;">Batch Number / Exp</th>
                            <th style="width:8%;">Price</th>
                            <th style="width:5%;">Qty Request</th>
                            <th style="width:5%;">Back Order</th>
                            <th style="width:5%;">Stock</th>
                            <th style="width:12%;">Qty Approve</th>
                            <th style="width:5%;">Discount</th>
                            <th style="width:8%;">SubTotal</th>
                            <th style="width:3%;">Acc</th>
                            <th style="width:20%;"></th>
                        </tr>
                    </thead>                                    
                    <tbody id="showdetail">

                    </tbody>
                </table>

            </div>
			
        </div>
    </div> 
</div>

<div class="row">
	<div class="col-md-6">
			<div class="panel panel-default" style="">
				
				<div class="panel-body" >
					<div class="col-md-4">
							<label class="control-label">History:</label><br>
							<a class="btn btn-success" id='histori'>History</a>
					</div>
					<div class="col-md-3">
						<label class="control-label">Status:</label>
						<div class="app-radio success"> 
							<label class="text-success"><input  name="rdostatus" value="approved" checked="" type="radio"> Approve</label>
						</div>
						<div class="app-radio warning"> 
							<label class="text-warning"><input name="rdostatus" value="revisi" type="radio"> Revise</label> 
						</div>
						<div class="app-radio danger"> 
							<label class="text-danger"><input name="rdostatus" value="reject" type="radio"> Reject</label>
						</div>
					</div>
					<div class="col-md-5">
						<label class="control-label">Note:</label>
						<textarea id="txtketerangan_input" name="txtketerangan_input" class="form-control" rows="5"></textarea>
						<button id="submitRespon"	type="button" class="btn btn-danger btn-sm pull-right">Submit</button>
					</div>
					
				</div>
			</div>
		
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
							   <input type="text" name="diskonrp"  class="form-control input-sm text-right" id="diskonrp" value="<?php echo number_format($diskon_rupiah); ?>" readonly>
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
				</div>
			</div>
	</div>
</div>

<div id="listhistori" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Histori</h4>
      </div>
      <div class="modal-body isi">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
