

<script src="assets/custom_script/po_customer.js"></script>
<script>
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
														//label: v.nama + ' | '+ v.alamat+' | Cabang '+v.cabang+' | Kabupaten/Kota '+v.kabupatenkota+' | Provinsi '+v.provinsi,
														label: v.nama ,
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
	});
</script>
<div class="row margin-bottom-5 ">                            
    <div class="col-md-12">

        <div class="panel panel-default" >
            <div class="panel-heading">
                <h3 class="panel-title">PO Customer</h3>
                <div class="panel-elements panel-elements-cp pull-right">                                            
                    <button class="btn btn-info" data-toggle="modal" data-target="#modal-default">+ Input Baru</button>
                </div>
            </div>


            <!--modal add po-->
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
            <h5>List PO</h5>
        </div>
    </div>
    <!-- END HEADING -->
    
    <div class="block-content">
		<div>
			<ul class="nav nav-tabs">
				<li class="active" ><a href="#tabs-draft" data-toggle="tab"><span class="fa fa-calendar-o"></span>Draft</a></li>
				<li><a href="#tabs-pending" data-toggle="tab"><span class="fa fa-calendar-plus-o"></span>Pending</a></li>
				<li><a href="#tabs-revisi" data-toggle="tab"><span class="fa fa-calendar-check-o"></span>Revisi</a></li>
				<li><a href="#tabs-rejected" data-toggle="tab"><span class="fa fa-calendar-minus-o"></span>Rejected</a></li>
				<li><a href="#tabs-approved" data-toggle="tab"><span class="fa fa-calendar-times-o"></span>Approved</a></li>
			</ul>
			<div class="tab-content tab-content-bordered">
				<div class="tab-pane active " id="tabs-draft">
					<table id="table_data_draft" class="table table-head-custom  table-striped" style="width:100%;" >
						<thead> 
							<tr  class="font11">
								<th>No</th>
								<th>No PO</th>
								<th>Customer</th>
								<th>Sales</th>
								<th>PO Date</th>
								<th>Image</th>
								<th colspan='1'>Status</th>
								<th>&nbsp</th>
								<th>Action</th>
							</tr>
						</thead>                                    
						<tbody  class="font11">
						   
						</tbody>
					</table>
				</div>
				<div class="tab-pane " id="tabs-pending">
					<table id="table_data_pending" class="table table-head-custom  table-striped" style="width:100%;" >
						<thead> 
							<tr  class="font11">
								<th>No</th>
								<th>No PO</th>
								<th>Customer</th>
								<th>Sales</th>
								<th>PO Date</th>
								<th>Image</th>
								<th>Status</th>
								<th>History</th>
								<th>Action</th>
							</tr>
						</thead>                                    
						<tbody  class="font11"> 
						   
						</tbody>
					</table>
				</div>
				<div class="tab-pane " id="tabs-rejected">
					<table id="table_data_rejected" class="table table-head-custom  table-striped" style="width:100%;" >
						<thead> 
							<tr  class="font11">
								<th>No</th>
								<th>No PO</th>
								<th>Customer</th>
								<th>Sales</th>
								<th>PO Date</th>
								<th>Image</th>
								<th>Status</th>
								<th>History</th>
								<th>Action</th>
							</tr>
						</thead>                                    
						<tbody  class="font11">
						   
						</tbody>
					</table>
				</div>
				<div class="tab-pane " id="tabs-approved">
					<table  id="table_data_approved" class="table table-head-custom  table-striped font11" style="width:100%;" >
						<thead  class="font11"> 
							<tr  class="font11">
								<th class="font11">No</th>
								<th>No PO</th>
								<th>Customer</th>
								<th>Sales</th>
								<th>PO Date</th>
								<th>Image</th>
								<th>Status</th>
								<th>History</th>
								<th>Approved By</th>
								<th>Approval Date</th>
								<th>Action</th>
							</tr>
						</thead>                                    
						<tbody class="font11">
						   
						</tbody>
					</table>
				</div>
				<div class="tab-pane " id="tabs-revisi">
					<table id="table_data_revisi" class="table table-head-custom  table-striped" style="width:100%;" >
						<thead> 
							<tr class="font11" >
								<th>No</th>
								<th>No PO</th>
								<th>Customer</th>
								<th>Sales</th>
								<th>PO Date</th>
								<th>Image</th>
								<th>Status</th>
								<th>History</th>
								<th>Action</th>
							</tr>
						</thead>                                    
						<tbody  class="font11">
						   
						</tbody> 
					</table>
				</div>
				
			</div>
			
			
		</div>
       
    </div>
  
</div> 
<div id="listhistori" tabindex="-1" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Histori</h4>
      </div>
      <div  class="modal-body isi">
        sss
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
