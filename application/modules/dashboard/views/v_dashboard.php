<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">

	<div class="col-md-12">
	
		<!-- START PRODUCT SALES HISTORY -->
		<div class="block block-condensed">
			<div class="app-heading">                                        
				<div class="title">
					<h2>Product Sales History</h2>
					<p>In comparison with "Purchase Button"</p>
				</div>              
				<div class="heading-elements">                                            
					<button type="button" class="btn btn-default btn-icon-fixed dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="icon-calendar-full"></span> June 13, 2016 - July 14, 2016
					</button>
					<ul class="dropdown-menu dropdown-form dropdown-left">
						<li>
							<div class="row">
								<div class="col-md-6">
									
									<div class="form-group margin-bottom-10">
										<label>From:</label>
										<div class="input-group">
											<div class="input-group-addon"><span class="icon-calendar-full"></span></div>
											<input type="text" class="form-control bs-datepicker" value="13/06/2016">
										</div>
									</div>
									
								</div>
								<div class="col-md-6">
									
									<div class="form-group">                                                        
										<label>To:</label>
										<div class="input-group">
											<div class="input-group-addon"><span class="icon-calendar-full"></span></div>
											<input type="text" class="form-control bs-datepicker" value="13/07/2016">
										</div>
									</div>
									
								</div>
							</div>
							<button class="btn btn-default btn-block">Confirm</button>
						</li>                                                
					</ul>
				</div>
			</div>
			
			<div class="block-content">
				<div class="app-chart-wrapper app-chart-with-axis">
					<div id="yaxis" class="app-chart-yaxis"></div>
					<div class="app-chart-holder" id="dashboard-chart-line" style="height: 325px;"></div>
					<div id="xaxis" class="app-chart-xaxis"></div>
				</div>
			</div>
		</div>
		
		<div class="col-md-3 text-center">
			<h5>Show on hover</h5>
			<p>Add class <code>.popover-hover</code> to <br>show your popover on hover.</p>
			<button class="btn btn-default popover-hover" data-placement="top" data-container="body"  data-trigger="click" data-content="And here's some amazing content. It's very engaging. Right?">
				Open Popover
			</button>
		</div> 
		<!-- END PRODUCT SALES HISTORY -->
	
	</div>

</div>