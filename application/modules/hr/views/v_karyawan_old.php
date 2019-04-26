 <script>
  $(document).ready(function(){
    $('.update').button().click(function(){
              var id = this.id;
              var id = $('#'+id).val();
              update(id);
        return false;
    })

    function update(id){
		$.ajax({
            url : '<?php echo base_url();?>index.php/Karyawan/karyawan_view/' + id,
            type: "POST",
            success : function(result){
            var data=jQuery.parseJSON(result)
                $('#update-form').modal('show');
                $('#form_update').show();
                $('#id').val(data.id);
                $('#nik').val(data.nik);
                //$('#user_name').val(data.user_name);
                $('#nama').val(data.nama);
                $('#jenis_kelamin').val(data.jenis_kelamin);
                $('#tempat_lahir').val(data.tempat_lahir);
                $('#tanggal_lahir_edit').val(data.tanggal_lahir);
				$('#alamat').val(data.alamat);
                //$('#kelurahan').val(data.kelurahan);
                //$('#kecamatan').val(data.kecamatan);
				$("#kabupatenkota_edit").val(data.kabupatenkota_id).change();
				$("#provinsi_edit").val(data.provinsi_id).change();
                //$('#kode_pos').val(data.kode_pos);
                //$('#nomor_telepon').val(data.nomor_telepon);
                $('#nomor_ktp').val(data.nomor_ktp);
                $('#nomor_hp').val(data.nomor_hp);
                $('#nomor_wa').val(data.nomor_wa);
                $('#email').val(data.email);
                //$('#tanggal_terima').val(data.tanggal_terima);
                //$('#nomor_sk').val(data.nomor_sk);
                //$('#deskripsi').val(data.deskripsi);
                $('#tmt_kerja_edit').val(data.tmt_kerja);
                $('#jabatan').val(data.jabatan_id);
                $('#unit_kerja').val(data.unit_kerja_id);
                //$('#ruang').val(data.ruang);
            },
            error: function(){
              alert("failure");
            }
        })
    }

     $('.del').button().click(function(){
              var id = this.id;
              var id = $('#'+id).val();
              $('#delete-form').modal('show');
              $('#dlt_btn').val(id);

        return false;
    })

    $('.delete').button().click(function (){
        var id = $('#dlt_btn').val();
        var baseurl = "<?php echo base_url();?>";
        $.ajax({
            url : '<?php echo base_url();?>index.php/Karyawan/karyawan_delete/' + id,
            type: "POST",
            success : function(result){
              var data=jQuery.parseJSON(result);
                   $('#delete-form').modal('hide');
                   $('#notification').modal('show');
                   $("#notification").html("<div class=\"alert alert-success\" align=\"center\">"+data.notifikasi+"</div>");
                    window.setTimeout(
                      function(){
                        location.reload(true)
                      },
                      1500
                    );
            },
            error: function(){
              alert("failure");
            }
        })
    })


     $("#form_update").submit(function(e) {
        var url = $(this).attr('action');
        $.ajax({
               type: "POST",
               url: url,
               data: $("#form_update").serialize(), 
               success: function(result)
               {
				   var data=jQuery.parseJSON(result);
                    $('#update-form').modal('hide');
                   $('#notification').modal('show');
                   $("#notification").html("<div class=\"alert alert-success\" align=\"center\">"+data.notifikasi+"</div>");
                    window.setTimeout(
                      function(){
                        location.reload(true)
                      },
                      1500
                    );
               },
               error: function()
               {
                  $( "#dialog_failure" ).dialog({
                      resizable: false,
                      height:140,
                      modal: true
                    });
               }
             });
        e.preventDefault();
    });

    $("#form_create").submit(function(e) {
        var url = $(this).attr('action');
        $.ajax({
               type: "POST",
               url: url,
               data: $("#form_create").serialize(), 
               success: function(result)
               {
                   var data=jQuery.parseJSON(result);
                   $('#create-form').modal('hide');
                   $('#notification').modal('show');
                   $("#notification").html("<div class=\"alert alert-success\" align=\"center\">"+data.notifikasi+"</div>");
                    window.setTimeout(
                      function(){
                        location.reload(true)
                      },
                      1500
                    );
               },
               error: function()
               {
                  $( "#dialog_failure" ).dialog({
                      resizable: false,
                      height:140,
                      modal: true
                    });
               }
             });
        e.preventDefault();
    });
  });
  </script>
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
			
        	<div class="modal fade" id="create-form" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center" id="myModalLabel">Tambah Data <?php echo $data['page_title']; ?></h3>
                        </div>
                        <div class="modal-body">
                            <form id="form_create" class="form-horizontal" action="Karyawan/karyawan_create" method="POST">
                                <input type="hidden" name="created_by" value="<?php echo $data['user_name'];?>">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nik">NIK <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nik" class="form-control col-md-7 col-xs-12" maxlength="12" disabled> * Generated by system
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nama" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Tempat Lahir</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="tempat_lahir" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Lahir</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" id="tanggal_lahir" name="tanggal_lahir" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>
								<div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Kelamin</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="radio-inline"><input type="radio" name="jenis_kelamin" value="P">Pria</label><label class="radio-inline"><input type="radio" name="jenis_kelamin" value="W">Wanita</label>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <textarea name="alamat" required="required" class="form-control col-md-7 col-xs-12"></textarea>
                                    </div>
                                  </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Provinsi</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="provinsi" id="provinsi">
									<option></option>
                                      <?php 
									  for ($i=0; $i < sizeof($data['provinsi']); $i++) {
                                      	echo "<option value=\"".$data['provinsi'][$i]->id."\">".$data['provinsi'][$i]->nama."</option>";
                                      }
                                      ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Kabupaten / Kota</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="kabupatenkota" id="kabupatenkota">
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomor_hp">No. HP <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nomor_hp" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomor_wa">No. WA <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nomor_wa" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Alamat Email <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="email" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="ln_solid"></div>
                            	<h4 class="modal-title text-center" id="myModalLabel">Data Organisasi</h4>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">TMT Kerja</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" id="tmt_kerja" name="tmt_kerja" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Unit Kerja</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="unit_kerja">
									<option></option>
                                      <?php 
									  for ($i=0; $i < sizeof($data['unit_kerja']); $i++) {
                                      	echo "<option value=\"".$data['unit_kerja'][$i]->id."\">".$data['unit_kerja'][$i]->nama."</option>";
                                      }
                                      ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Jabatan</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="jabatan">
									<option></option>
                                      <?php 
									  for ($i=0; $i < sizeof($data['jabatan']); $i++) {
                                      	echo "<option value=\"".$data['jabatan'][$i]->id."\">".$data['jabatan'][$i]->nama."</option>";
                                      }
                                      ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="ln_solid"></div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 text-center">
                                      <input type="submit" value="Simpan" class="form-control col-md-7 col-xs-12 btn btn-success" />
                                    </div>
                                  </div>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="update-form" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center" id="myModalLabel">Ubah Data <?php echo $data['page_title']; ?></h3>
                        </div>
                        <div class="modal-body">
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
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Tempat Lahir</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="tempat_lahir" id="tempat_lahir"required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Lahir</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="tanggal_lahir" id="tanggal_lahir_edit" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>
								  <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Kelamin</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="radio-inline"><input type="radio" name="jenis_kelamin" value="P">Pria</label><label class="radio-inline"><input type="radio" name="jenis_kelamin" value="W">Wanita</label>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <textarea name="alamat" id="alamat" required="required" class="form-control col-md-7 col-xs-12"></textarea>
                                    </div>
                                  </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Provinsi</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="provinsi" id="provinsi_edit">
									<option></option>
                                      <?php 
									  for ($i=0; $i < sizeof($data['provinsi']); $i++) {
                                      	echo "<option value=\"".$data['provinsi'][$i]->id."\">".$data['provinsi'][$i]->nama."</option>";
                                      }
                                      ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Kabupaten / Kota</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="kabupatenkota" id="kabupatenkota_edit">
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomor_hp">No. HP <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nomor_hp" id="nomor_hp" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomor_wa">No. WA <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nomor_wa" id="nomor_wa" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Alamat Email <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="email" id="email" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="ln_solid"></div>
                            	<h4 class="modal-title text-center" id="myModalLabel">Data Organisasi</h4>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">TMT Kerja</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" id="tmt_kerja_edit" name="tmt_kerja" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Unit Kerja</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="unit_kerja" id="unit_kerja">
									<option></option>
                                      <?php 
									  for ($i=0; $i < sizeof($data['unit_kerja']); $i++) {
                                      	echo "<option value=\"".$data['unit_kerja'][$i]->id."\">".$data['unit_kerja'][$i]->nama."</option>";
                                      }
                                      ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Jabatan</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="jabatan" id="jabatan">
									<option></option>
                                      <?php 
									  for ($i=0; $i < sizeof($data['jabatan']); $i++) {
                                      	echo "<option value=\"".$data['jabatan'][$i]->id."\">".$data['jabatan'][$i]->nama."</option>";
                                      }
                                      ?>
                                    </select>
                                  </div>
                                </div>
                                  <div class="ln_solid"></div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 text-center">
                                      <input type="submit" value="Simpan" class="form-control col-md-7 col-xs-12 btn btn-success" />
                                    </div>
                                  </div>  
                              </form>
                        </div>
                    </div>
                </div>
            </div>
			
            <div class="modal fade" id="delete-form" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 class="modal-title text-center" id="myModalLabel">Hapus Data <?php echo $data['page_title']; ?> </h3>
                        </div>
                        <div class="modal-body">
                          <div style="text-align:center; margin-bottom:20px;"><h3>Apakah Anda Yakin ?</h3></div>
                          <br>
                          <form class="form-inline" role="form">
                            <div class="form-group" style="text-align: right;width: 43%;margin-right: 50px;">
                                  <button type="button" value="" id="dlt_btn" class="delete btn btn-danger">Yakin</button>
                            </div>
                            <div class="form-group" style="text-align: left;width: 40%;">
                                  <button type="submit" class="btn btn-default">Tidak</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="notification" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="notification"></div>
                        </div>
                    </div>
                </div>
            </div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Daftar <?php echo $data['page_title']; ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-form">
                        <span class="glyphicon glyphicon-plus"></span> Tambah 
                      </button>
                      
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                              <?php 
								$arr_unit_kerja = array();
								for($i=0;$i<sizeof($data['unit_kerja']);$i++)
                                {
                                  $arr_unit_kerja[$data['unit_kerja'][$i]->id] = $data['unit_kerja'][$i]->nama;
                                }
								
								$arr_jabatan = array();
								for($i=0;$i<sizeof($data['jabatan']);$i++)
                                {
                                  $arr_jabatan[$data['jabatan'][$i]->id] = $data['jabatan'][$i]->nama;
                                }
								
								if(sizeof($data['karyawan']) != 0)
                                {
                                  echo '
                                        <table id="datatable-buttons" class="table table-striped table-bordered">
                                          <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>NIK</th>
                                              <th>Nama Karyawan</th>
                                              <th>Kontak</th>
                                              <th>Unit Kerja</th>
                                              <th>Jabatan</th>
                                              <th>Operasi</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                       ';
                                  $no=0;
                                  for ($i=0; $i < sizeof($data['karyawan']); $i++) 
                                  { 
                                    $no++;
									echo '<tr>';
                                    echo '<td>'.$no.'</td>';
                                    echo '<td>'.$data['karyawan'][$i]->nik.'</td>';
                                    echo '<td>'.$data['karyawan'][$i]->nama.'</td>';
                                    echo '<td>Nomor HP: '.$data['karyawan'][$i]->nomor_hp.'<br />
									Nomor WA: '.$data['karyawan'][$i]->nomor_wa.'<br />
									Email: '.$data['karyawan'][$i]->email.'<br /></td>';
                                    echo '<td>';
									echo (array_key_exists ($data['karyawan'][$i]->unit_kerja_id, $arr_unit_kerja)) ?  $arr_unit_kerja[$data['karyawan'][$i]->unit_kerja_id] : '';
									echo '</td>';
                                    echo '<td>';
									echo (array_key_exists ($data['karyawan'][$i]->jabatan_id, $arr_jabatan)) ?  $arr_jabatan[$data['karyawan'][$i]->jabatan_id] : '';
									echo '</td>';
                                    echo '<td class="text-center">';
                                    echo '<a href="'.base_url().'index.php/KaryawanDetail/'.$data['karyawan'][$i]->id.'"><button value="'.$data['karyawan'][$i]->id.'" id="btn_'.$i.'" class="btn btn-labeled btn-warning" title="Data Detail"><i class="glyphicon glyphicon-ok"></i></button></a>';
                                    echo '<button value="'.$data['karyawan'][$i]->id.'" id="btn_'.$i.'" class="update btn btn-labeled btn-success" title="Ubah"><i  class="glyphicon glyphicon-pencil"></i></button>';
                                    echo '<button value="'.$data['karyawan'][$i]->id.'" id="btn_'.$i.'" class="del btn btn-labeled btn-danger" title="Hapus"><i class="glyphicon glyphicon-remove"></i></button>';
                                    echo '</td>';
                                    echo '</tr>';
                                  }

                                  echo '</tbody>
                                        </table>';
                                }
                                else
                                {
                                  echo '<div class="alert alert-info text-center"><strong>Tidak terdapat provinsi</strong></div>';
                                }
                              ?>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- /page content -->

        <!--Update provinsi script-->
        <script type="text/javascript">
          $('#provinsi').on('change', function(){

            var val_provinsi = $(this).val();
            $('#kabupatenkota').empty();
			$.ajax({
              url   :'MasterKabupatenkota/kabupatenkota_list',
              type  :'POST',
              data  :'provinsi='+val_provinsi,
              beforeSend  : function(){},
              success : function(response){
                $.each(JSON.parse(response), function(idx, obj) {
                  $('#kabupatenkota').append('<option value='+obj.id+'>'+obj.nama+'</option>');
                });

              }
            });
          });
        </script>
        <!--ENDScript create provinsi-->


        <!--Update provinsi script-->
        <script type="text/javascript">
          $('#provinsi_edit').on('change', function(){

            var val_provinsi = $(this).val();
            $('#kabupatenkota_edit').empty();
            $.ajax({
              url   :'MasterKabupatenkota/kabupatenkota_list',
              type  :'POST',
              data  :'provinsi='+val_provinsi,
              beforeSend  : function(){},
              success : function(response){
                $.each(JSON.parse(response), function(idx, obj) {
                  $('#kabupatenkota_edit').append('<option value='+obj.id+'>'+obj.nama+'</option>');
                });

              }
            });
          });
        </script>
        <!--ENDUpdate provinsi script-->
		
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/vendors/bootstrap-daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="<?php echo base_url();?>asset/vendors/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>



<script>
  $('#tanggal_lahir').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale:{
      format:'YYYY-MM-DD'
    }
  });

  $('#tanggal_lahir_edit').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale:{
      format:'YYYY-MM-DD'
    }
  });

  $('#tmt_kerja').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale:{
      format:'YYYY-MM-DD'
    }
  });

  $('#tmt_kerja_edit').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale:{
      format:'YYYY-MM-DD'
    }
  });
</script>