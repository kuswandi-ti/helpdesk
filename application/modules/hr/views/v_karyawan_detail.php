<?php
for ($i=0; $i < sizeof($data['karyawan']); $i++) 
{ 
  	$nik = $data['karyawan'][$i]->nik;
  	$nama = $data['karyawan'][$i]->nama;
}
?>
		<!-- page content -->
		<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h1></h1>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
			
			
			<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Data Karyawan:  <?php echo '<b>#'.$nik.' '.$nama.'</b>'; ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <button type="button" class="btn btn-primary" data-toggle="modal" onClick="window.parent.location.href='<?php echo base_url();?>index.php/MasterProduk';">
                        <span class="glyphicon glyphicon-arrow-left"></span> Back
                      </button>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_title">
                    Identitas | Keluarga | Pendidikan | Port Folio | Rekening | Kompetensi | Presensi | Jabatan | Bagian | Kesehatan | Pelatihan / Seminar | Prestasi | Sanksi / Peringatan | Gaji | BPJS | Pajak
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                              <form id="form_update" class="form-horizontal" action="Karyawan/karyawan_update" method="POST">
                                <input type="hidden" name="created_by" value="<?php echo $data['user_name'];?>">
                                <input type="hidden" name="id" id="id" class="form-control">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">NIK <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nik" id="nik" required="required" class="form-control col-md-7 col-xs-12" maxlength="12" disabled>
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nama" id="nama" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                              </form>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- /page content -->