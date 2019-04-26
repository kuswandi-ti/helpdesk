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
            url : '<?php echo base_url();?>index.php/MasterOffice/office_view/' + id,
            type: "POST",
            success : function(result){
            var data=jQuery.parseJSON(result)
                $('#update-form').modal('show');
                $('#form_update').show();
                $('#id').val(data.id);
                $('#nama').val(data.nama);
                $('#alamat').val(data.alamat);
				$("#kabupatenkota_edit").val(data.kabupatenkota_id).change();
				$("#provinsi_edit").val(data.provinsi_id).change();
                $('#kode_pos').val(data.kode_pos);
                $('#telepon').val(data.telepon);
                $('#faks').val(data.faks);
                $('#email').val(data.email);
                $('#website').val(data.website);
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
            url : '<?php echo base_url();?>index.php/MasterOffice/office_delete/' + id,
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
                            <form id="form_create" class="form-horizontal" action="MasterOffice/office_create" method="POST">
                                <input type="hidden" name="created_by" value="<?php echo $data['user_name'];?>">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Office <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nama" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <textarea name="alamat" class="form-control col-md-7 col-xs-12"></textarea>
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
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kode_pos">Kode Pos 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="kode_pos" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="telepon">Telepon 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="telepon" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="faks">Faksimili 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="faks" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="email" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Website 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="website" class="form-control col-md-7 col-xs-12">
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
                              <form id="form_update" class="form-horizontal" action="MasterOffice/office_update" method="POST">
                                <input type="hidden" name="created_by" value="<?php echo $data['user_name'];?>">
                                <input type="hidden" name="id" id="id" class="form-control">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Office <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nama" id="nama" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <textarea name="alamat" id="alamat" class="form-control col-md-7 col-xs-12"></textarea>
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
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kode_pos">Kode Pos 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="kode_pos" id="kode_pos" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="telepon">Telepon 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="telepon" id="telepon" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="faks">Faksimili 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="faks" id="faks" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="email" id="email" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Website 
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="website" id="website" class="form-control col-md-7 col-xs-12">
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
                  <div class="x_content">
                    <br />
                              <?php 
								$arr_provinsi = array();
								for($i=0;$i<sizeof($data['provinsi']);$i++)
                                {
                                  $arr_provinsi[$data['provinsi'][$i]->id] = $data['provinsi'][$i]->nama;
                                }
								
								$arr_kabupaten_kota = array();
								for($i=0;$i<sizeof($data['kabupaten_kota']);$i++)
                                {
                                  $arr_kabupaten_kota[$data['kabupaten_kota'][$i]->id] = $data['kabupaten_kota'][$i]->nama;
                                }
								
								if(sizeof($data['office']) != 0)
                                {
                                  echo '
                                        <table id="datatable-buttons" class="table table-striped table-bordered">
                                          <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>Nama Office</th>
                                              <th>Alamat</th>
                                              <th>Operasi</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                       ';
                                  $no=0;
                                  for ($i=0; $i < sizeof($data['office']); $i++) 
                                  { 
                                    $no++;
									echo '<tr>';
                                    echo '<td>'.$no.'</td>';
                                    echo '<td>'.$data['office'][$i]->nama.'</td>';
                                    echo '<td>'.nl2br($data['office'][$i]->alamat);
									echo (isset($arr_kabupaten_kota[$data['office'][$i]->kabupatenkota_id]) ? '<br />'.$arr_kabupaten_kota[$data['office'][$i]->kabupatenkota_id] : '');
									echo (isset($arr_provinsi[$data['office'][$i]->provinsi_id]) ? '<br />'.$arr_provinsi[$data['office'][$i]->provinsi_id] : '');
									echo (isset($data['office'][$i]->kode_pos) ? '&nbsp;'.$data['office'][$i]->kode_pos : '');
									echo '<br /><br />Telp. : '.$data['office'][$i]->telepon.'<br />Faks. : '.$data['office'][$i]->faks.'<br />Email : '.$data['office'][$i]->email.'<br />Website : '.$data['office'][$i]->website.'<br /></td>';
                                    echo '<td class="text-center">';
                                    echo '<a href="'.base_url().'index.php/MasterOfficeDetail/'.$data['office'][$i]->id.'"><button value="'.$data['office'][$i]->id.'" id="btn_'.$i.'" class="btn btn-labeled btn-warning" title="Data Detail"><i class="glyphicon glyphicon-ok"></i></button></a>';
                                    echo '<button value="'.$data['office'][$i]->id.'" id="btn_'.$i.'" class="update btn btn-labeled btn-success" title="Ubah"><i  class="glyphicon glyphicon-pencil"></i></button>';
                                    echo '<button value="'.$data['office'][$i]->id.'" id="btn_'.$i.'" class="del btn btn-labeled btn-danger" title="Hapus"><i class="glyphicon glyphicon-remove"></i></button>';
                                    echo '</td>';
                                    echo '</tr>';
                                  }

                                  echo '</tbody>
                                        </table>';
                                }
                                else
                                {
                                  echo '<div class="alert alert-info text-center"><strong>Tidak terdapat office</strong></div>';
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
