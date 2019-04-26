<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PBF - PT. TATA USAHA INDONESIA (TAUHID)</title>

    <!-- Bootstrap -->
    <link href="/sitauhid/assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/sitauhid/assets/vendors/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    

    <!-- Custom Theme Style -->
    <link href="/sitauhid/assets/build/css/custom.min.css" rel="stylesheet">
	<style>
	*{
		font: 11px 'a_FuturaOrto', tahoma, arial, sans-serif;
		color: #fff;
	}
	body{		
	  	background-image: url('<?php echo base_url() ?>assets/img/medicine.jpg');
	    background-repeat: no-repeat;
	    background-attachment: fixed;
		background-size: 100% 100%;
	}
	</style>
  </head>

  <body>
    <div>
      <div style="width:700px;height:420px;margin-left:auto;margin-right:auto;margin-top:10%;background:url(<?php echo base_url() ?>assets/img/bg-login-form.png) no-repeat;">
			<div class="rows">
				<div class="col-md-4">
					&nbsp;
				</div>
				<div class="col-md-8" style="margin-top:4px;">
					<h1>PT. TAUHID</h1><h4>Sistem Informasi Pedagang Besar Farmasi</h5><br/>
				</div>
			</div>
			<div class="rows">
				<?php 
                    $notif=$this->session->flashdata('notif');
                    if(!empty($notif))
                    { 
                        echo "
                                <div class=\"col-md-4\">&nbsp;</div>
								<div class=\"alert alert-danger\" style=\"margin-top:20px;\"><h5>".$notif."</h5></div>";
                    }
                ?>
				<div class="col-md-4">
					&nbsp;
				</div>
			  <form class="form col-md-6 center-block" action="<?php echo base_url(); ?>login/auth" method="post">
				<div class="form-group">
				  <input type="text" class="form-control input-lg" placeholder="User-ID" name="username">
				</div>
				<div class="form-group">
				  <input type="password" class="form-control input-lg" placeholder="Password" name="password">
				</div>
				<div class="form-group">
				 <input type="submit" value="Log in" class="btn btn-default submit" />             
				</div>
                
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                
                <div class="clearfix"></div>

                <div style="margin-left:150px;">
				  <h5>Anda mengalami kesulitan dengan sistem ini?<br /><br />silahkan hubungi contact center melalui,<br />
				  email : adirimata@tauhid.co.id<br />Telp. 021-9999 999</h5>
                  <p>©2017 PBF - PT. TATA USAHA INDONESIA, All Rights Reserved.</p>
                </div>
              </div>
			  </form>
		  </div>
     </div>
  </body>
</html>
