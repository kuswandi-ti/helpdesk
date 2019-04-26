
<div class="block block-condensed">
    <!-- START HEADING -->
    <div class="app-heading app-heading-small">
        <div class="title">
            <h5>List Sales Order</h5>
        </div>
    </div>
    <!-- END HEADING -->
    
    <div class="block-content" style="overflow-x:auto;font-size: 12px;">
        <table id="table_data" class="table table-head-custom table-striped  datatable no-footer font11">
            <thead> 
                <tr >
                    <th>No</th>
                    <th>No SO</th>
                    <th>SO Date</th>
                    <th>No PO</th>
                    <th>Customer</th>
                    <th>Sales</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Time Period</th>
                    <th>Status SO</th>
                    <th>Action</th>
                </tr>
            </thead>                                    
            <tbody>
               
            </tbody>
        </table>

    </div>
  
</div> 

<script src="assets/custom_script/sales_order.js"></script>
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
					<form class="form-horizontal" role="form">
						<div class="col-md-12">
							<div class="form-group">
								<div class="col-md-2">
									<label class="control-label text-right ">No. PO.</label>
									<input id="nopo" type="text" class="form-control text-uppercase   input-sm" readonly value="">
								</div>
								<div class="col-md-3 ">
									<label class=" control-label text-right">Customer</label>
									<input id="namacustomer" type="text" class="form-control   input-sm" readonly value="">
								</div>	
								<div class="col-md-3">
									<label class=" control-label text-right">Credit Limit</label>
									<div class="input-group">
										<span class="input-group-addon">Rp</span>
										<input id="cl" type="text" class="form-control text-right  input-sm" readonly value="2.000.000">
									</div>
								</div>	
								<div class="col-md-4 ">
									<label class="control-label text-right">Address</label>
									<input id="alamat" type="text" class="form-control   input-sm" readonly value="">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								
								<div class="col-md-2 ">
									<label class=" control-label text-right">Sales</label>
									<input id="namasales" type="text" class="form-control   input-sm" readonly value="">
								</div>	
									
								<div class="col-md-2 ">
									<label class="control-label text-right">Payment</label>
									<input id="tipebayar" type="text" class="form-control text-uppercase     input-sm" readonly value="">
								</div>	
								<div class="col-md-2 ">
									<label class="control-label text-right">Time Period</label>
									<input id="jangkawaktu" type="text" class="form-control text-uppercase     input-sm" readonly value="">
								</div>
								<div class="col-md-2">
									<label class=" control-label text-right">Image</label>
								   <button id="btnpreview" class="form-control input-sm btn-info preview" style="background:#17b8f1; color:white;"  data-preview-image=""  data-preview-size="modal-lg">Preview</button>
								</div>	
							</div>
						</div>
					</form>
					
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="block">
				<div class="block-body" >
					<table class="table table-head-custom table-striped table-bordered dataTable no-footer datatable font11" style="">
						<thead> 
							<tr class=" font11">
								<th style="width:3%;">No</th>
								<th style="width:40%;">Product</th>
								<th style="width:15%;">Batch Number / Exp</th>
								<th style="width:8%;">Price</th>
								<th style="width:5%;">Qty</th>
								<th style="width:5%;">BO Qty</th>
								<th style="width:9%;">Approved Qty</th>
								<th style="width:8%;">Discount</th>
								<th style="width:8%;">SubTotal</th>
								
							</tr>
						</thead>                                    
						<tbody id="showdetail">

						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class='col-md-12'>
			<div class="col-md-7 ">
			</div>
			<div class="col-md-5 ">
				<div class="form-group">
				<div class="col-md-1 "></div>
							<label class="col-md-2 control-label">SubTotal</label>
							<div class="col-md-9">
								<input type="text" name="subtotalx"  class="form-control input-sm text-right" id="subtotalx" value="" required readonly>
							</div>
				</div>
				<div class="form-group">
				<div class="col-md-1 "></div>
							<label class="col-md-2 control-label">Diskon </label>
							<div class="col-md-4">
							<div class="input-group">
								<input type="text" name="diskonpersen"  class="form-control input-sm text-right" id="diskonpersen" value="" required readonly>
								<span class="input-group-addon">%</span>
							</div>
							</div>
				
							<div class="col-md-5">
							<div class="input-group">
							   <span class="input-group-addon">Rp</span>
							   <input type="text" name="diskonrp"  class="form-control input-sm text-right" id="diskonrp" value="" required readonly>
							</div>
							</div>
				</div>
				<div class="form-group">
				<div class="col-md-1 "></div>
							<label class="col-md-2 control-label">PPN</label>
							<div class="col-md-4">
							<div class="input-group">
								<input type="text" name="ppn"  class="form-control input-sm text-right" id="ppn" value="" required readonly>
								<span class="input-group-addon">%</span>
							</div>
							
							</div>
							<div class="col-md-5">
							<div class="input-group">
							   <span class="input-group-addon">Rp</span>
								<input type="text" name="ppnrp"  class="form-control input-sm text-right" id="ppnrp" value="" required readonly> 
							</div>	
							</div>	
				</div>
				<div class="form-group">
				<div class="col-md-1 "></div>
							<label class="col-md-2 control-label">Total</label>
							<div class="col-md-9">
								<input type="text" name="total"  class="form-control input-sm text-right" id="total" value="" required  readonly>
							</div>
				</div>
			</div>
		</div>
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
        
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
    </div>            
</div>