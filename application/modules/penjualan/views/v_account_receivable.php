
<script>
$(document).on('change','#checker',function(){
	var id_header = $(this).closest('tr').find('#id').val();
	var jumlah_tagihan = $(this).closest('tr').find('#jumlah_tagihan').val();
	var no_inv = $(this).closest('tr').find('#no_inv').val();
	var ckb = $(this).is(':checked');
	
	if(ckb)
	{
		var conf = confirm("Set this SO to AR?");
		if(!conf){
			
			$(this).prop('checked',false);
			return false;
			
		}
		$.ajax({
			url:'penjualan/account_receivable/setAR',
			type:'get',
			data:{id_po:id_header, jumlah_tagihan:jumlah_tagihan,no_inv:no_inv},
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

$(document).on('click','#btn_detail',function(){
	var id_po = $(this).closest('tr').find("#idpo").val();
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
		
		$("#quo").hide();
		$("#buat").prop('disabled',true);
		$("#namacust").autocomplete
        ({ 
            minLength:2,
            source: function (request, response) {
                if($('#customerList').val()=='')
                      $("#buat").prop('disabled',true);
				  
                    var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                    $.ajax({ 
                            url:"penjualan/po_customer/populateCust",
                            datatype:"json",
                            type:"get",
                            success:function(data)
                            {

                                    var result = response($.map(data,function(v,i)
                                    {
                                            var text = v.nama;
                                            if ( text && ( !request.term || matcher.test(text) ) ) {
                                                return {
														label: v.nama + ' | '+ v.alamat+' | Cabang '+v.cabang+' | Kabupaten/Kota '+v.kabupatenkota+' | Provinsi '+v.provinsi,
                                                        value: v.id,
														idsales: v.idsales,
														namasales: v.namasales,
														idcust : v.id,
														namacust: v.nama
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
						$("#sales").val(ui.item.idsales);
						$("#namasales").val(ui.item.namasales);
						$("#customerList").val(ui.item.idcust);
                },
                        select: function(event, ui) {
                        // prevent autocomplete from updating the textbox
                        event.preventDefault();
                        // manually update the textbox and hidden field
                        $(this).val(ui.item.label);
						$("#sales").val(ui.item.idsales);
						$("#namasales").val(ui.item.namasales);
						$("#customerList").val(ui.item.idcust);
                        var idcust = $("#customerList").val();
						
						$("#buat").prop('disabled',false);
						$.ajax({
						url:'penjualan/po_customer/popQuotation/'+idcust,
						success: function(data){
							data = JSON.parse(data);
							var options, cabang;
							options = "<option value=''>Tanpa Quotation</option>";
							for (var i = 0; i < data['length']; i++) {
								
								options += "<option value='"+data[i]['id']+"'>"+data[i]['no_quotation']+"</option>";
							}
							
							$("#quo").show();
							//$("#fromquotation").html(options).selectmenu('refresh', true);;
							$("#fromquotation").empty();
							$("#fromquotation").html(options);
						}
					});
                }
        }); 
		$("#tipebayar").change(function(){
			if($(this).val()=='cod')
			{
				$("#jangkawaktu").val("0").change();
				$("#formjw").hide();
			}
			else {
				$("#jangkawaktu").val("21").change();
				$("#formjw").show();
			}
		})
		$('#table_data').DataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "penjualan/account_receivable/populate",
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
<div class="row margin-bottom-5 ">                            
    <div class="col-md-12">

        <div class="panel panel-default" >
            <div class="panel-heading">
                <h3 class="panel-title">Account Receivable</h3>
                <!--div class="panel-elements panel-elements-cp pull-right">                                            
                    <button class="btn btn-info" data-toggle="modal" data-target="#modal-default">+ Input Baru</button>
                </div-->
            </div>


            <!--modal add po-
            <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default-header">                        
                <div class="modal-dialog" role="document">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

                    <div class="modal-content">
                        <div class="modal-header">                        
                            <h4 class="modal-title" id="modal-default-header">Tambah PO</h4>
                        </div>
                        <div class="modal-body">
                            <form enctype="multipart/form-data" class="form-horizontal" method="POST" action="<?php echo base_url(); ?>penjualan/po_customer/createHeader">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">No. PO</label>
                                    <div class="col-md-9">
                                        <input type="text" name="no_po"  class="form-control text-uppercase" id="no_po" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Customer</label>
                                    <div class="col-md-9">
                                        <input name="namacustomer" id="namacust" class="form-control">
                                        <input name="customer" id="customerList" class="form-control" type="hidden" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Sales </label>
                                    <div class="col-md-3">
                                       <input name="sales" id="sales" class="form-control" type="hidden">
                                       <input name="namasales" id="namasales" class="form-control"  readonly>
                                    </div>
                                </div>
								<div class="form-group" id="quo">
                                    <label class="col-md-3 control-label">Quotation </label>
                                    <div class="col-md-4">
											<select name="idquotation" id="fromquotation" class="form-control">
												
											</select>
										</div>
								</div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Tanggal PO</label>
                                    <div class="col-md-3">
                                        <input name="tanggal" type="text" id="tgl" class="form-control bs-datepicker-weekends"  data-date-format="DD-MM-YYYY">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Tipe Pembayaran</label>
                                    <div class="col-md-5">
                                        <select name="tipebayar" id="tipebayar" class="form-control" >
											<option value="credit">Credit</option>
											<option value="cod">Cash</option>
                                        </select>
                                    </div>
                                </div>
								<div class="form-group" id="formjw">
                                    <label class="col-md-3 control-label">Jangka Waktu</label>
                                    <div class="col-md-2">
                                        <select name="jangkawaktu"  class="form-control" id="jangkawaktu">
										
											<option value="14">14</option>
											<option value="21" selected>21</option>
											<option value="30">30</option>
											<option hidden value="0">0</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Bukti PO</label>
                                    <div class="col-md-3">
                                        <input required   type="file" name="buktiPO" accept="image/*"> 
                                    </div>
                                </div>

                                <div class="form-group" style="display:none;">
                                    <label class="col-md-2 control-label">Cabang</label>
                                    <div class="col-md-4">
                                        <input id="customerCabang" type="text" class="form-control" placeholder="Cabang" disabled>
                                    </div>
                                </div>

						</div>
                        <div class="modal-footer">

                            <input name="submit" id="buat" type="submit" class="btn btn-info" value="Buat PO">
                            </form>
                            <button type="button"  class="btn btn-warning" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>            
            </div>
            <!--modal add quotation end -->
            <div class="panel-body" style="display:none;">    

            </div>				
            <div class="panel-footer" style="display:none;">    

            </div>				



        </div>

    </div>
</div>
<div class="block block-condensed">
    <!-- START HEADING -->
    <div class="app-heading app-heading-small">
        <div class="title">
            <h5>List Sales Order</h5>
        </div>
    </div>
    <!-- END HEADING -->
    
    <div class="block-content">
		
					<table id="table_data" class="table table-head-custom  table-striped" style="width:100%;" >
						<thead> 
							<tr  class="font11">
								<th style="width:5%;">No</th>
								<th style="width:20%;">No Faktur</th>
								<th style="width:10%;">No SO</th>
								<th style="width:15%;">Customer</th>
								<th style="width:15%;">Status</th>
								<th style="width:15%;">Note</th>
									<th>SubTotal</th>
								<th>PPn</th>
								<th>Total</th>
								<th style="width:15%;">Item Detail</th>
								<th style="width:5%;">Action</th>
							</tr>
						</thead>                                    
						<tbody  class="font11">
						   
						</tbody>
					</table>
				
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