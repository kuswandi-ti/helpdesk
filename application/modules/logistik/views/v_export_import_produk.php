<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
	$(document).ready(function() {
		$("body").on("click", ".btn-export", function() {
			window.location = "logistik/export_import_produk/export_produk";
		});
	});
</script>
  
<div class="row margin-bottom-5">
	<div class="col-md-12">
		<div class="app-heading app-heading-small">
			<div class="icon icon-lg">
				<span class="<?php echo $page_icon; ?>"></span>
			</div>
			<div class="title">
				<h2><?php echo $page_title; ?></h2>
				<p><?php echo $page_subtitle; ?></p>
			</div>                        
			<div class="heading-elements">
				<div class="row">
					<div class="col-md-6">
						<form class="form-horizontal" action="logistik/export_import_produk/import_produk" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<div class="col-md-8">
									<input type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
								</div>
								<div class="col-md-4">
									<button type="submit" class="btn-import btn btn-info btn-icon-fixed"><span class="fa fa-cloud-download"></span> Impport Data</button>
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-5 pull-right">
						<button type="button" class="btn-export btn btn-info btn-icon-fixed"><span class="fa fa-cloud-upload"></span> Export Data</button>     
					</div>
				</div>
			</div>
		</div>                                                         
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">	
			
			</div>
		</div>
	</div>
</div>