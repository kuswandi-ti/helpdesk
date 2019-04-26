<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
	$id_karyawan = $_SESSION['user_id'];
	$q = $this->db->query("select * from ck_karyawan where id='$id_karyawan'");
	$id_karyawan = '';
	foreach($q->result() as $x)
		$id_karyawan = $x->id;
?>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
	
	$( document ).ready(function() 
	{
		
		var id_karyawan = <?php echo $_SESSION['user_id']; ?>;
		var id_karyawan = <?=$id_karyawan?>;
		
		/****GET CUST BY ID karyawan *****/
		
		var opt='<option selected hidden disabled>Select karyawan</option>';
		$.ajax({
			url:'operasional/account_planning/get_karyawan/'+id_karyawan,
			dataType:'json',
			type:'get',
			async:false,
			success:function(json)
			{
				$.each(json,function(i,obj){
					//console.log("Value : "+obj.id+", Nama : "+obj.nama);
					opt += "<option value='"+'a'+obj.id+"'>"+obj.nama+"</option>";
				})
				$("#karyawanList").html(opt);
				$("#karyawanList").selectpicker('refresh');
			}
		});
		
		/////
		$("#menu_penjualan_quotation").addClass("active");
		$.ajax({
			url:'<?php echo base_url(); ?>penjualan/quotation/populateCust',
			type:'post',
			success: function(data){
				data = JSON.parse(data);
				var options, cabang;
				for (var i = 0; i < data['length']; i++) {
					options += "<option data-tokens='"+data[i]['nama']+"' value='"+data[i]['id']+"'>"+data[i]['nama']+' - '+data[i]['alamat']+', '+data[i]['cabang']+', '+data[i]['kabupatenkota']+', '+data[i]['provinsi']+"</option>";
				}
				$("#karyawanList").append(options);
				$("#karyawanList").selectpicker('refresh');
			}
		});
		
		
		$("#tabel #hapus").click(function(){
			var parentTr = $(this).closest('tr');
			var id=parentTr.find('#id').html();
			
			var conf = confirm("Anda Yakin?");
			if(conf)
			{
				$.ajax({
					url:'penjualan/quotation/deleteHeader/'+id,
					type:'get',
					success:function(data)
					{
						location.reload();
					}
				});
			}
		});
	});
</script>
<div class="row margin-bottom-5 ">                            
    <div class="col-md-12">

        <div class="panel panel-default" >
            <div class="panel-heading">
                <h3 class="panel-title">Quotation</h3>
                <div class="panel-elements panel-elements-cp pull-right">                                            
                    <button class="btn btn-info" data-toggle="modal" data-target="#modal-default">Buat Quotation</button>
                </div>
            </div>


            <!--modal add quotation-->
            <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default-header">                        
                <div class="modal-dialog" role="document">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

                    <div class="modal-content">
                        <div class="modal-header">                        
                            <h4 class="modal-title" id="modal-default-header">Quotation</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" method="POST" action="<?php echo base_url(); ?>penjualan/quotation/createHeader">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">karyawan </label>
                                    <div class="col-md-3">
                                        <input name="karyawan" type="text" id="custList" class="form-control" readonly value="<?=$_SESSION['user_nama']?>"> 																						
                                        </select>
                                    </div>
                                </div>	

                                <div class="form-group">
                                    <label class="col-md-2 control-label">karyawan</label>
                                    <div class="col-md-10">
                                        <select name="karyawan" id="karyawanList" class="bs-select" data-live-search="true">

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Date</label>
                                    <div class="col-md-3">
                                        <input name="tanggal" type="text" id="custList" class="form-control bs-datepicker-weekends" data-date-format="YYYY-MM-DD">
                                    </div>
                                </div>

                                <div class="form-group" style="display:none;">
                                    <label class="col-md-2 control-label">Cabang</label>
                                    <div class="col-md-4">
                                        <input id="karyawanCabang" type="text" class="form-control" placeholder="Cabang" disabled>
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">

                            <input name="submit" type="submit" class="btn btn-info" value="Buat Quotation">
                            </form>
                            <button type="button"  class="btn btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>            
            </div>
            <!--modal add quotation end -->
            <div class="panel-body" style="display:none;">    

            </div>				
            <div class="panel-footer" style="display:none;">    

            </div>				



        </div>

    </div>
</div>

<div class="block block-condensed">
    <!-- START HEADING -->
    <div class="app-heading app-heading-small">
        <div class="title">
            <h5>List Quotation</h5>

        </div>
    </div>
    <!-- END HEADING -->

    <div class="block-content" style="overflow-x:auto;">
        <table id="tabel" class="table table-striped table-bordered datatable-extended">
            <thead>
                <tr> 
                    <th>No.</th>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Unit Kerja</th>
                    <th>Jabatan</th>
                    <th>Golongan / Ruang</th>
                    <th>Actions</th>
                </tr>
            </thead>                                    
            <tbody>
                <?php
                $no = 1;
                foreach ($headerList->result() as $row) {
                    echo "<tr>";
                    echo "<td>" . $no . "</td>";
                    echo "<td>" . $row->nik . "</td>";
                    echo "<td>" . $row->nama_karyawan . "</td>";
                    echo "<td>" . $row->nama_unit . "</td>";
                    echo "<td>" . $row->nama_jabatan . "</td>";
                    echo "<td>" . $row->golongan." / ". $row->ruang. "</td>";
                    echo "	<td>
					   <a title='Lihat Detail' href='/sitauhid/hr/karyawan_detail/$row->id_karyawan'><span alt='Detail' class='icon-launch'></span></a>&nbsp;
					   <a id='hapus' title='Hapus'><span class='icon-trash2'></span></a>&nbsp;
							   </td>";

                    echo "</tr>";

                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
