<script>
$(document).on("change","#billing_approve", function(e){
	
	var id_header = $(this).closest('tr').find('#idpo').val();
	var ckb = $(this).is(':checked');
	
	if(ckb)
	{
		var conf = confirm("Approve this billing statement?");
		if(!conf){
			
			$(this).prop('checked',false);
			return false;
			
		}
		$.ajax({
			url:'penjualan/tagihan_penjualan/approve_billing',
			type:'get',
			data:{id_header:id_header},
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

$(document).on("click","#cetakBilling", function(e){
	var id = $(this).closest('tr').find('#idpo').val();
	window.open('http://192.168.0.111/sitauhid/penjualan/tagihan_penjualan/cetak_billing/'+id,'_blank');
})
$(document).on("click","#loadDetail", function(e){
	//insert to table penjualan detail, by idpo
	
	var id_po = $(this).closest('tr').find('#idpo').val();
	if(id_po=='')
		return false;
	$.ajax({
		url:'penjualan/account_receivable/insertPenjualan/'+id_po,
		type:'get',
		success:function(data)
		{
			$("#detailnya").modal('show');
			$("#showdetail").html(data);
		}
	});
});

$(document).ready(function(){

    $("#submitDetail").on("click", function(e){
		var id = $("#id_faktur").val();
		var delivered = $("#delivered").val();
		var ket_delivered = $("#ket_delivered").val();
		var tanggal_delivered = $("#tanggal_delivered").val();
		var jam_delivered = $("#jam_delivered").val()+':00';;
		
		var receiver_name = $("#receiver_name").val();
		var fdata = new FormData()
		// alert(tanggal_delivered+' '+jam_delivered);
		if($("#file")[0].files.length>0){
			fdata.append("file",$("#file")[0].files[0]);
		
			$.ajax({
				url : 'penjualan/pencatatan_realisasi/insert_delivery',
				type: 'post',
				data: {id:id, delivered:delivered, ket_delivered:ket_delivered,tanggal_delivered:tanggal_delivered, jam_delivered:jam_delivered, receiver_name:receiver_name},
				
				success: function(data)
				{
					$.ajax({
						url: 'penjualan/pencatatan_realisasi/upd_img/'+id,
						type: 'POST',            
						data: fdata,
						processData: false,
						contentType: false,
						success: function (data)
						{
							alert("Done!");
							$("#id_faktur").val('');
							$("#no_faktur").val('');
							$("#delivered").val('');
							$("#ket_delivered").val('');
							$("#tanggal_delivered").val('');
							$("#receiver_name").val('');
							$("#file").val('');
							$('#table_data').DataTable().ajax.reload()
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
		"sAjaxSource": "penjualan/tagihan_penjualan/populate",
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
</script>

<div class="row margin-bottom-5">
	<div class="col-md-12">
		<div class="app-heading app-heading-small">
			<div class="icon icon-lg">
				<span class="icon-list3"></span>
			</div>
			<div class="title">
				<h2>Tagihan Penjualan</h2>
				
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
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Payment Type</th>
                    <th>Invoice No.</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th>SubTotal</th>
                    <th>PPn</th>
                    <th>Total</th>
                    <th>Detail</th>
                    <th>Action</th>
                </tr>
            </thead>                                    
            <tbody>
               
            </tbody>
        </table>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="detailnya">
    <div class="modal-dialog custom-class">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h1 class="modal-header-text" style="text-align: center;" id="idso"></h1>
            </div>
      <div class="modal-body isi">
       	
		<div class="col-md-12">
			<div class="block">
				<div class="block-body" >
					<table class="table table-head-custom table-striped table-bordered dataTable no-footer datatable font11" style="">
						<thead> 
							<tr class=" font11">
								<th style="width:3%;">No</th>
								<th style="width:25%;">Product</th>
								<th style="width:15%;">Batch Number / Exp</th>
								<th style="width:5%;">Price</th>
								<th style="width:9%;">Request Qty</th>
								<th style="width:9%;">Approved Qty</th>
								<th style="width:5%;">BO Qty</th>
								<th style="width:5%;">Sent Qty</th>
								<th style="width:9%;">Disc</th>
								<th style="width:5%;">SubTotal</th>
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