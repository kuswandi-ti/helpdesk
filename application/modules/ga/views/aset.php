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
            url : '<?php echo base_url();?>index.php/Aset/aset_view/' + id,
            type: "POST",
            success : function(result){
            var data=jQuery.parseJSON(result)
                $('#update-form').modal('show');
                $('#form_update').show();
                $('#id').val(data.id);
                $('#kode').val(data.kode);
                $('#nama').val(data.nama);
                //$('#harga').val(data.harga);
                //$('#diskon').val(data.diskon);
                $('#harga_beli').val(data.harga_beli);
                $('#tgl_perolehan').val(data.tgl_perolehan);
                $('#cara_perolehan').val(data.cara_perolehan);
                $('#metode_bayar').val(data.metode_bayar);
                $('#kredit_dp').val(data.kredit_dp);
                $('#kredit_angsuran').val(data.kredit_angsuran);
                $('#kredit_mulai').val(data.kredit_mulai);
                $('#kredit_hingga').val(data.kredit_hingga);
                //$('#prosentasi_penyusutan').val(data.prosentasi_penyusutan);
                //$('#masa_penyusutan').val(data.masa_penyusutan);
                //$('#periode_penyusutan').val(data.periode_penyusutan);
                //$('#yang_mengajukan').val(data.yang_mengajukan);
                $('#nama_pengguna').val(data.nama_pengguna);
                $('#lokasi').val(data.lokasi);
                $('#kondisi').val(data.kondisi);
                $('#deskripsi').val(data.deskripsi);
				$("#kelompok_id").val(data.kelompok_id).change();
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
            url : '<?php echo base_url();?>index.php/Aset/aset_delete/' + id,
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
                            <form id="form_create" class="form-horizontal" action="Aset/aset_create" method="POST">
                                <input type="hidden" name="created_by" value="<?php echo $data['user_name'];?>">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kode">Kode Aset <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="kode" required="required" class="form-control col-md-7 col-xs-12" maxlength="30">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Aset <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nama" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
								  <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Kelompok Aset</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="kelompok">
									<option></option>
                                      <?php 
									  	for ($i=0; $i < sizeof($data['aset_kelompok']); $i++) 
		                                  { 
		                                   	echo "<option value=\"".$data['aset_kelompok'][$i]->id."\">".$data['aset_kelompok'][$i]->nama."</option>";
                                        }
                                      ?>
                                    </select>
                                  </div>
                                </div>
                                <!--<div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="harga">Harga
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="harga" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="diskon">Diskon
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="diskon" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>//-->
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="harga_beli">Harga Beli
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="harga_beli" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Perolehan</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" id="tgl_perolehan" name="tgl_perolehan" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>
								  <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Diperoleh...</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="cara_perolehan">
                                      <?php 
									  	while (list ($key, $val) = each ($data['cara_perolehan'])) 
		                                  { 
		                                   	echo "<option value=\"".$key."\">".$val."</option>";
                                        }
                                      ?>
                                    </select>
                                  </div>
                                </div>
								  <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Metode Bayar (untuk beli)</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="metode_bayar">
									  <option></option>
                                      <?php 
									  	while (list ($key, $val) = each ($data['metode_bayar'])) 
		                                  { 
		                                   	echo "<option value=\"".$key."\">".$val."</option>";
                                        }
                                      ?>
                                    </select>
                                  </div>
                                </div>
								<div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kredit_dp">DP (untuk kredit)
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="kredit_dp" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
								<div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kredit_angsuran">Angsuran Perbulan
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="kredit_angsuran" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
								<div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kredit_mulai">Tgl. Mulai Kredit 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="kredit_mulai" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
								<div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kredit_hingga">Tgl. Akhir Kredit
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="kredit_hingga" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
								<div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_pengguna">Pengguna
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nama_pengguna" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="lokasi">Lokasi
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="lokasi" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kondisi">Kondisi (%)
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="kondisi" class="form-control col-md-7 col-xs-12">
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
                              <form id="form_update" class="form-horizontal" action="Aset/aset_update" method="POST">
                                <input type="hidden" name="created_by" value="<?php echo $data['user_name'];?>">
                                <input type="hidden" name="id" id="id" class="form-control">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kode">Kode <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="kode" id="kode" required="required" class="form-control col-md-7 col-xs-12" maxlength="30">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama aset <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nama" id="nama" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
								  <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="deskripsi">Deskripsi
                                  </label>
                                  	<div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="deskripsi" id="deskripsi" class="form-control col-md-7 col-xs-12"></textarea> 
                                  	</div>
                                  </div>
								  <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="contoh">Contoh
                                  </label>
                                  	<div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="contoh" id="contoh" class="form-control col-md-7 col-xs-12"></textarea> 
                                  	</div>
                                  </div>
								  <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Kategori</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="kategori" id="kategori">
                                      <?php 
                                        reset ($data['kategori']);
                                        while (list ($key, $val) = each ($data['kategori']))
                                        {
                                          echo "<option value=\"".$key."\">".$val."</option>";
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
                    <h2><?php echo $data['page_title']; ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-form">
                        <span class="glyphicon glyphicon-plus"></span> Tambah 
                      </button>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                      <a href="<?php echo base_url();?>index.php/AsetKelompok">Kelompok Aset</a>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                              <?php 
								$arr_kelompok = array();
								for($i=0;$i<sizeof($data['aset_kelompok']);$i++)
                                {
                                  $arr_kelompok[$data['aset_kelompok'][$i]->id] = $data['aset_kelompok'][$i]->nama;
                                }
								
								if(sizeof($data['aset']) != 0)
                                {
                                  echo '
                                        <table id="datatable-buttons" class="table table-striped table-bordered">
                                          <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>Kode</th>
                                              <th>Nama aset</th>
                                              <th>Nama Pengguna</th>
                                              <th>Kelompok Aset</th>
                                              <th>QR Code</th>
                                              <th>Operasi</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                       ';
                                  $no=0;
                                  for ($i=0; $i < sizeof($data['aset']); $i++) 
                                  { 
                                    $no++;
									echo '<tr>';
                                    echo '<td>'.$no.'</td>';
                                    echo '<td>'.$data['aset'][$i]->kode.'</td>';
                                    echo '<td>'.$data['aset'][$i]->nama.'</td>';
                                    echo '<td>'.$data['aset'][$i]->nama_pengguna.'</td>';
                                    echo '<td>'.$arr_kelompok[$data['aset'][$i]->kelompok_id].'</td>';
                                    echo '<td><a href="'.base_url().'index.php/Aset/qr_code/'.$data['aset'][$i]->id.'" target="_blank">click me...</a></td>';
                                    echo '<td class="text-center">';
                                    echo '<button value="'.$data['aset'][$i]->id.'" id="btn_'.$i.'" class="update btn btn-labeled btn-success" title="Ubah"><i  class="glyphicon glyphicon-pencil"></i></button>';
                                    echo '<button value="'.$data['aset'][$i]->id.'" id="btn_'.$i.'" class="del btn btn-labeled btn-danger" title="Hapus"><i class="glyphicon glyphicon-remove"></i></button>';
                                    echo '</td>';
                                    echo '</tr>';
                                  }

                                  echo '</tbody>
                                        </table>';
                                }
                                else
                                {
                                  echo '<div class="alert alert-info text-center"><strong>Tidak terdapat aset</strong></div>';
                                }
                              ?>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- /page content -->
		
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>asset/vendors/bootstrap-daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="<?php echo base_url();?>asset/vendors/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

<script>
  $('#tgl_perolehan').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale:{
      format:'YYYY-MM-DD'
    }
  });

  $('#tgl_perolehan_edit').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale:{
      format:'YYYY-MM-DD'
    }
  });

  $('#kredit_mulai').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale:{
      format:'YYYY-MM-DD'
    }
  });

  $('#kredit_mulai_edit').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale:{
      format:'YYYY-MM-DD'
    }
  });
  
  $('#kredit_hingga').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale:{
      format:'YYYY-MM-DD'
    }
  });

  $('#kredit_hingga_edit').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale:{
      format:'YYYY-MM-DD'
    }
  });
</script>