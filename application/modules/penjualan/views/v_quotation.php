<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
	$id_karyawan = $_SESSION['user_id'];
	$q = $this->db->query("select * from ck_sales where id_karyawan='$id_karyawan'");
	$id_sales = '';
	foreach($q->result() as $x)
		$id_sales = $x->id;
?>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
	
	$( document ).ready(function() 
	{
		
		var id_karyawan = <?php echo $_SESSION['user_id']; ?>;
		var id_sales = <?=$id_sales?>;
		
		/****GET CUST BY ID SALES *****/
		
		var opt='<option selected hidden disabled>Select Customer</option>';
		$.ajax({
			url:'operasional/account_planning/get_customer/'+id_sales,
			dataType:'json',
			type:'get',
			async:false,
			success:function(json)
			{
				$.each(json,function(i,obj){
					//console.log("Value : "+obj.id+", Nama : "+obj.nama);
					opt += "<option value='"+'a'+obj.id+"'>"+obj.nama+"</option>";
				})
				$("#customerList").html(opt);
				$("#customerList").selectpicker('refresh');
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
				$("#customerList").append(options);
				$("#customerList").selectpicker('refresh');
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
                                    <label class="col-md-2 control-label">Sales </label>
                                    <div class="col-md-3">
                                        <input name="sales" type="text" id="custList" class="form-control" readonly value="<?=$_SESSION['user_nama']?>"> 																						
                                        </select>
                                    </div>
                                </div>	

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Customer</label>
                                    <div class="col-md-10">
                                        <select name="customer" id="customerList" class="bs-select" data-live-search="true">

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
                                        <input id="customerCabang" type="text" class="form-control" placeholder="Cabang" disabled>
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
                    <th>No</th>
                    <th>Quotation No</th>
                    <th id='hid' style="display:none;">Header Id</th>
                    <th>Quotation Date</th>
                    <th>Customer</th>
                    <th>Sales</th>
                    <th>Sub Total</th>
                    <th>Diskon</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>                                    
            <tbody>
                <?php
                $no = 1;
                foreach ($headerList->result() as $row) {
                    echo "<tr>";
                    echo "<td>" . $no . "</td>";
                    echo "<td>" . $row->no_quotation . "</td>";
                    echo "<td id='id' style='display:none;'>" . $row->id . "</td>";
                    echo "<td>" . $row->tgl_quotation . "</td>";
                    echo "<td>" . $row->nama . "</td>";
                    echo "<td>" . $row->created_by . "</td>";
                    echo "<td>" . number_format($row->subtotal) . "</td>";
                    echo "<td>" . $row->diskon . "</td>";
                    echo "<td>" . number_format($row->total) . "</td>";
                    echo "	<td>
					   <a title='Lihat Detail' href='/sitauhid/penjualan/quotation/quotationdetail/?last_id=$row->id'><span alt='Detail' class='icon-launch'></span></a>&nbsp;
					   <a target='blank' title='Print' href='/sitauhid/penjualan/quotation/printquotation/$row->id'><span class='icon-printer'></span></a>&nbsp;
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
