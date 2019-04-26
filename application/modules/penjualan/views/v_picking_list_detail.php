<?php
	$id;$noso;$nopo;$namacust;
	foreach($header->result() as $r)
	{
		$id=$r->id;
		$noso = $r->no_so;
		$nopo = $r->no_po;
		$namacust = $r->namacust;
	}
?>
<script>
var totalRow;
function loadDetail()
{
	$.ajax({
		url:'penjualan/pickinglist/loadDetail/'+<?php echo $id;?>,
		type:'get',
		success:function(data){
			$("#showdetail").html(data);
		}
	});
}

function getTotal()
{
	
}
$(document).on('change', '.ceklist', function() {
    if(this.checked) {
      var totalcek=$('input[class="ceklist"]:checked').length;
	 // alert("totalcek : "+totalcek+", totalRow : "+totalRow)
    }
	if(!this.checked) {
      var totalcek=$('input[class="ceklist"]:checked').length;
	 totalcek;
    }
	if(totalcek == totalRow)
	{
		$("#simpan").prop("disabled",false);
	}
	else $("#simpan").prop("disabled",true);
});
$(function(){
	var checkedChecklist = 0;
	
	$.ajax({
		url:'penjualan/pickinglist/getTotalRow/'+<?php echo $id;?>,
                    async:false,
		type:'get',
		success:function(data){
			totalRow = data;
		}
	});
	loadDetail();
	$("#simpan").prop("disabled",true);
	$("#cetak").click(function(){ 
		var id=<?php echo $id;?>;
		window.open("penjualan/pickinglist/cetakform/"+id);
	});
	
	
	$("#simpan").on('click',function(){
		$.ajax({
			url:'penjualan/pickinglist/donepicking/<?php echo $id;?>',
			type:'get',
			success:function(data){
				alert("Done!");
				window.open("penjualan/pickinglist",'_self');
			}
		});
	});
});
</script>
<div class="row margin-bottom-5 ">

    <div class="col-md-12">

        <div class="block block-default" style="">
            <div class="block-body" >
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label class="control-label text-right">No. SO.</label>
                            <input id="noSO" type="text" class="form-control   input-sm" readonly value="<?php echo $noso;?>">
                        </div>
                        <div class="col-md-2">
                            <label class="control-label text-right">No. PO.</label>
                            <input id="noPO" type="text" class="form-control   input-sm" readonly value="<?php echo $nopo;?>">
                        </div>
                        <div class="col-md-2 ">
                            <label class=" control-label text-right">Customer</label>
                            <input id="quoCust" type="text" class="form-control   input-sm" readonly value="<?php echo $namacust;?>">
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
                <table class="table table-head-custom table-striped dataTable no-footer datatable" style="margin-top:16px !important;">
                    <thead> 
                        <tr >
                            <th>No</th>
                            <th>Product</th>
                            <th>Batch Number / Exp</th>
                            <th>Qty</th>
                            <th>Stock</th>
                            <th>Pick</th>
                        </tr>
                    </thead>                                    
                    <tbody id="showdetail">

                    </tbody>
                </table>

            </div>
			
        </div>
    </div> 
</div>
<div class="row margin-bottom-5 ">
    <div class="col-md-12">
		<div class="block block-default">
			<div class="block-body" style="	">
				<div class='row'>
					
					<div class="col-md-12 pull-right">
									<div class="col-md-8"></div>
									<div class="col-md-2">
										 <button id="cetak" class="btn btn-default btn-icon-fixed pull-right"><span class="icon-printer"></span>Print Form</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button id="simpan" class="btn btn-success btn-icon-fixed "><span class="icon-floppy-disk"></span>Save</button>&nbsp;
                                    </div>
                        
					</div>
				</div>
				<div class="row">
                       
				</div>
			</div>
		</div>
	</div>
</div>