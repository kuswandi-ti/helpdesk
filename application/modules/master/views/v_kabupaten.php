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
            url : '<?php echo base_url();?>index.php/MasterKabupatenkota/kabupatenkota_view/' + id,
            type: "POST",
            success : function(result){
            var data=jQuery.parseJSON(result)
                $('#update-form').modal('show');
                $('#form_update').show();
                $('#id').val(data.id);
                $('#nama').val(data.nama);
                $('#kemendagri').val(data.kode_kemendagri);
                $('#region').val(data.region_id);
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
            url : '<?php echo base_url();?>index.php/MasterKabupatenkota/kabupatenkota_delete/' + id,
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
                            <form id="form_create" class="form-horizontal" action="MasterKabupatenkota/kabupatenkota_create" method="POST">
                                <input type="hidden" name="created_by" value="<?php echo $data['user_name'];?>">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Kabupaten / Kota <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nama" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kemendagri">Kode Kemendagri <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="kemendagri" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Provinsi</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="provinsi" id="provinsi">
                                      <?php 
                                        for($i=0;$i<sizeof($data['provinsi']);$i++)
                                        {
                                          echo "<option value=\"".$data['provinsi'][$i]-> id."\">".$data['provinsi'][$i]->nama."</option>";
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
                              <form id="form_update" class="form-horizontal" action="MasterKabupatenkota/kabupatenkota_update" method="POST">
                                <input type="hidden" name="created_by" value="<?php echo $data['user_name'];?>">
                                <input type="hidden" name="id" id="id" class="form-control">
                                 <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Kabupaten / Kota <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="nama" id="nama" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kemendagri">Kode Kemendagri <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="kemendagri" id="kemendagri" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                  </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Provinsi</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" name="provinsi" id="provinsi">
                                      <?php 
                                        for($i=0;$i<sizeof($data['provinsi']);$i++)
                                        {
                                          echo "<option value=\"".$data['provinsi'][$i]-> id."\">".$data['provinsi'][$i]->nama."</option>";
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
                    <ul class="nav navbar-left panel_toolbox">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12"><h2>Provinsi</h2></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control" name="region" id="region" onChange="">
                          <?php 
                            for($i=0;$i<sizeof($data['provinsi']);$i++)
                            {
                              echo "<option value=\"".$data['provinsi'][$i]-> id."\">".$data['provinsi'][$i]->nama."</option>";
                            }
                          ?>
                        </select>
                      </button>
                      </div>
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
								
								if(sizeof($data['provinsi']) != 0)
                                {
                                  echo '
                                        <table id="datatable-buttons" class="table table-striped table-bordered">
                                          <thead>
                                            <tr>
                                              <th>No</th>
                                              <th>Nama Kabupaten / Kota</th>
                                              <th>Nama Provinsi</th>
                                              <th>Operasi</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                       ';
                                  $no=0;
                                  for ($i=0; $i < sizeof($data['kabupatenkota']); $i++) 
                                  { 
                                    $no++;
									echo '<tr>';
                                    echo '<td>'.$no.'</td>';
                                    echo '<td>'.$data['kabupatenkota'][$i]->nama.'</td>';
                                    echo '<td>';
									echo ($data['kabupatenkota'][$i]->provinsi_id!=0) ? strtoupper($arr_provinsi[$data['kabupatenkota'][$i]->provinsi_id]) : '';
									echo '</td>';
                                    echo '<td class="text-center">';
                                    echo '<button value="'.$data['kabupatenkota'][$i]->id.'" id="btn_'.$i.'" class="update btn btn-labeled btn-success" title="Ubah"><i  class="glyphicon glyphicon-pencil"></i></button>';
                                    echo '<button value="'.$data['kabupatenkota'][$i]->id.'" id="btn_'.$i.'" class="del btn btn-labeled btn-danger" title="Hapus"><i class="glyphicon glyphicon-remove"></i></button>';
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