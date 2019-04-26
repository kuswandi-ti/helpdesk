<script>
 $(document).ready(function(){
	
	
	  $('#table_data').DataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "penjualan/ar_settlement/populate",
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
$(document).on('change','#settler',function(){
	var billing_id = $(this).closest('tr').find('#billing_id').val();
	var ckb = $(this).is(':checked');
	
	if(ckb)
	{
		var conf = confirm("Settle This AR?");
		if(!conf){
			
			$(this).prop('checked',false);
			return false;
			
		}
		$.ajax({
			url:'penjualan/ar_settlement/settleAR',
			type:'get',
			data:{id:billing_id},
			success:function(data)
			{
				if(data=='done')
				{
					alert("Setting Done");
					$('#table_data').DataTable().ajax.reload();
				}
			}
		});
	}	
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
				<!--span class="icon-list3"></span-->
			</div>
			<div class="title">
				<h2>AR Settlement</h2>
				
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
                    <th>Aksi</th>
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