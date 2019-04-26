<script>
$(document).ready(function(){

    $('#jam_delivered').datetimepicker({
        format: 'HH:mm'
    });
	$('#no_faktur').autocomplete
        ({ 
            minLength:1,
            source: function (request, response) {
					var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
				$.ajax({ 
						url:"penjualan/pencatatan_realisasi/populate_no_so",
						datatype:"json",
						type:"get",
						success:function(data)
						{
								var result = response($.map(data,function(v,i)
								{
										var text = v.no_so;
										if ( text && ( !request.term || matcher.test(text) ) ) {
											return {
													label: v.no_so,
													value: v.no_so,
													id: v.id,
													nama: v.nama
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
                },
					select: function(event, ui) {
					// prevent autocomplete from updating the textbox
					event.preventDefault();
					// manually update the textbox and hidden field
					$(this).val(ui.item.label);
					$("#nama_customer").val(ui.item.nama);
					$("#id_faktur").val(ui.item.id);
                }
        });
	
	$("#loadDetail").on("click", function(e){
		//insert to table penjualan detail, by idpo
		
		var id_po = $("#id_faktur").val();
		if(id_po=='')
			return false;
		$.ajax({
			url:'penjualan/pencatatan_realisasi/insertPenjualan/'+id_po,
			type:'get',
			success:function(data)
			{
				$("#detailnya").modal('show');
				$("#showdetail").html(data);
			}
		});
	});
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
		"sAjaxSource": "penjualan/pencatatan_realisasi/populate",
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
$(document).on('click','#set',function(){
	var id_detail = $(this).closest('tr').find('#id_detail').val();
	var jumlah_diterima = $(this).closest('tr').find('#jml_diterima').val();
	$.ajax({
		url:'penjualan/pencatatan_realisasi/setJumlahDiterima',
		type:'post',
		data:{id_detail:id_detail,jumlah_diterima:jumlah_diterima},
		success:function(data){
			alert(data);
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
				<h2>Pencatatan Realisasi</h2>
				
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
						<input id="no_faktur" class="ui-widgets form-control  input-sm" type="text" autofocus placeholder="Masukkan no SO" >
					</div>
					<div class="col-md-2 ">
						<label>Customer</label>
						<input id="nama_customer" class="ui-widgets form-control  input-sm" type="text" placeholder="" readonly>
					</div>
					
					<div class="col-md-2 ">
						<label>Status</label>
						<select id="delivered" name="bulan" class="form-control   input-sm">
							<option value='1'>Diterima</option>
							<option value='2'>Diterima dengan catatan</option>
						</select>
					</div>
					
					<div class="col-md-2 ">
						<label>Receiver</label>
						<input id="receiver_name" class="ui-widgets form-control  input-sm" type="text" placeholder="Nama Penerima" autofocus="">
					</div>
				   
					<div class="col-md-2">
						<label>Received Date</label>
						<input id="tanggal_delivered" class="form-control bs-datepicker-weekends   input-sm"  type="text" placeholder=" " autofocus="" data-date-format="DD-MM-YYYY">
					</div>
				   
					<div class="col-md-1">
						<label>Time</label>
						<input id="jam_delivered" class="form-control input-sm"  type="text" min="00" max="23" placeholder=" " autofocus="" data-time-format="HH:mm" >
						
					</div>
				   
				</div>
				
				
				<div class="form-group" style="	">
					<div class="col-md-2">
						<label>Keterangan</label>
						<input class="form-control  input-sm" id="ket_delivered">
					</div>
					<div class="col-md-2">
						<label>Upload Berkas</label>
						<input type="file" class="  input-sm" id="file">
					</div>
					
					<div class="col-md-1 ">
						<label>&nbsp;</label>
						<input id="loadDetail" class="form-control input-sm btn-info" style="background:#17b8f1; color:white;" type="submit" value="Load Detail">
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
                    <th>Status</th>
                    <th>Delivered Date</th>
                    <th>Receiver</th>
                    <th>Note</th>
                    <th>Image</th>
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
								<th style="width:31%;">Product</th>
								<th style="width:15%;">Batch Number / Exp</th>
								<th style="width:5%;">Qty</th>
								<th style="width:9%;">Approved Qty</th>
								<th style="width:9%;">Sent Qty</th>
								<th style="width:5%;">BO Qty</th>
								
							</tr>
						</thead>                                    
						<tbody id="showdetail">

						</tbody>
					</table>
				<div class="pull-right">	
				<button id="submitDetail" type="button" class="btn btn-primary" data-dismiss="modal">Submit</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				</div>
			</div>
		</div>
		
		</div>
      </div>
    </div>

</div>