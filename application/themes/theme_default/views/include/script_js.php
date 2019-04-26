<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/moment/moment.min.js"></script>       
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/moment/moment.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/customscrollbar/jquery.mCustomScrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/bootstrap-select/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/maskedinput/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/form-validator/jquery.form-validator.min.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/noty/jquery.noty.packaged.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/knob/jquery.knob.min.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/jvectormap/jquery-jvectormap.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/jvectormap/jquery-jvectormap-us-aea-en.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/sparkline/jquery.sparkline.min.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/morris/raphael.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/morris/morris.min.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/rickshaw/d3.v3.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/rickshaw/rickshaw.min.js"></script>


<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/isotope/isotope.pkgd.min.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/format_currency/jquery.formatCurrency.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/redirect/jquery.redirect.js"></script>
<!--<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/highcharts/highcharts.js"></script>-->
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/bootstrap-table-expandable/bootstrap-table-expandable.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/app.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/app_plugins.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/app_demo_dashboard.js"></script>

<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/chartjs/chart.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/loading_overlay/loadingoverlay.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/loading_overlay/loadingoverlay_progress.js"></script>

<script>
    function hanya_angka(evt) {
      var charCode = (evt.which) ? evt.which : evt.keyCode
      // if (charCode > 31 && (charCode < 48 || charCode > 57)) // Tanpa . dan ,
      if (charCode != 46 && charCode != 44 && charCode > 31 && (charCode < 48 || charCode > 57)) // Dengan . dan ,
        return false;
      return true;
    }
</script>
<script src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/highcharts/code/highcharts.js"></script>
<script src="<?php echo $this->config->item('PATH_ASSET_APPS') ?>js/vendor/highcharts/code/modules/exporting.js"></script>

