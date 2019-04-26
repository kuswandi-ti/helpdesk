<?php
$nama_cust; $bulan; $tahun; $total_visit;
foreach($detail->result() as $r){
	$nama_cust = $r->nama;
	$bulan = $r->bulan;
	$tahun = $r->tahun;
	$total_visit = $r->total_visit;
}
?>
<script>
$(document).ready(function(){	
	
	<?php
		for($x=1;$x<=$total_visit;$x++):
	?>
		
	$(".vtanggal").datepicker({
	   dateFormat: 'yy-mm-dd', 
		 defaultDate: new Date(<?=$tahun?>, parseInt(<?=$bulan?>)-1, 1),
	});	
	<?php endfor;
	?>
	
	var id_lembar_kerja = <?=$id?>;
	
	$(".save").click(function(){
		
		var tanggal = $(this).closest('tr').find('.tanggal').val();
		var jam_mulai = $(this).closest('tr').find('.jam_mulai').val();
		var menit_mulai = $(this).closest('tr').find('.menit_mulai').val();
		var jam_selesai = $(this).closest('tr').find('.jam_selesai').val();
		var menit_selesai = $(this).closest('tr').find('.menit_selesai').val();
		var materi =  $(this).closest('tr').find('#materi').val();
		var strategi =  $(this).closest('tr').find('#strategi').val();
		var done = 0;
		var id_detail=$(this).closest('tr').find(".id_detail").val();
		
		if(id_detail=='') // kalo gak ada id detail berarti insert
		{
			$.ajax({
				url:'operasional/account_planning/set_detail/',
				type:'post',
				async:false,
				data:{
					id_lembar_kerja:id_lembar_kerja,
					tanggal:tanggal,
					jam_mulai:jam_mulai,
					menit_mulai:menit_mulai,
					jam_selesai:jam_selesai,
					menit_selesai:menit_selesai,
					materi:materi,
					strategi:strategi
				},
				success:function(data)
				{
					response=data.substr(0,4);
					id_detail=data.substr(4);
					if(response=="done")
					{
						alert("Planning Set!");
						done = 1;
					}
					else alert(data);
				}
			});
		}
		else //kalo ada berarti update
		{
			$.ajax({
				url:'operasional/account_planning/update_detail/'+id_detail,
				type:'post',
				async:false,
				data:{
					id_lembar_kerja:id_lembar_kerja,
					tanggal:tanggal,
					jam_mulai:jam_mulai,
					menit_mulai:menit_mulai,
					jam_selesai:jam_selesai,
					menit_selesai:menit_selesai,
					materi:materi,
					strategi:strategi
				},
				success:function(data)
				{
					if(data=="done")
					{
						alert("Planning Updated!");
						done = 1;
					}
					else alert(data);
				}
			});
		}
			if(done==1)
			{
				$(this).prop("disabled",true);
				$(this).closest('tr').find('.edit').prop("disabled",false);
				$(this).closest('tr').find('.id_detail').val(id_detail);
				$(this).closest('tr').find('.id_detail').prop("readonly",true);
				$(this).closest('tr').find('.tanggal').prop("disabled",true);
				$(this).closest('tr').find('.jam_mulai').prop("disabled",true);
				$(this).closest('tr').find('.menit_mulai').prop("disabled",true);
				$(this).closest('tr').find('.jam_selesai').prop("disabled",true);
				$(this).closest('tr').find('.menit_selesai').prop("disabled",true);
				$(this).closest('tr').find('#materi').prop("disabled",true);
				$(this).closest('tr').find('#strategi').prop("disabled",true);
			}
		
		
	});
	
	$(".edit").click(function(){
		$(this).prop("disabled",true);
		$(this).closest('tr').find('.save').prop("disabled",false);
		$(this).closest('tr').find('.tanggal').prop("disabled",false);
		$(this).closest('tr').find('.jam_mulai').prop("disabled",false);
		$(this).closest('tr').find('.menit_mulai').prop("disabled",false);
		$(this).closest('tr').find('.jam_selesai').prop("disabled",false);
		$(this).closest('tr').find('.menit_selesai').prop("disabled",false);
		$(this).closest('tr').find('#materi').prop("disabled",false);
		$(this).closest('tr').find('#strategi').prop("disabled",false);
	});
	
	
	$("#btn_save").click(function(){
		var id_visit = $("#vid_visit").val();
		var tanggal = $("#vtanggal").val();
		var jam_mulai = $("#vjam_mulai").val();
		var jam_selesai = $("#vjam_selesai").val();
		var menit_mulai = $("#vmenit_mulai").val();
		var menit_selesai = $("#vmenit_selesai").val();
		var hasil = $("#vhasil").val();
		
		$.ajax({
			url:'operasional/account_planning/set_realisasi/'+id_visit,
			type:'post',
			data:{
				tanggal:tanggal,
				id_visit:id_visit,
				jam_mulai:jam_mulai,
				jam_selesai:jam_selesai,
				menit_mulai:menit_mulai,
				menit_selesai:menit_selesai,
				hasil:hasil
			},
			success:function(respon){
				if(respon=='done')
					alert("done");
				else alert(respon);
			}
		});
		
	});
});

	$(document).on('click',".realization", function(){
		
		$(".mtanggal,.vtanggal").val($(this).closest('tr').find('.tanggal').val());
		
		var jam_mulai = "<option selected>"+$(this).closest('tr').find('.jam_mulai').html()+"</option>";
		$("#mjam_mulai").append(jam_mulai);
		var menit_mulai = "<option selected>"+$(this).closest('tr').find('.menit_mulai').html()+"</option>";
		$("#mmenit_mulai").append(menit_mulai);
		
		var jam_selesai = "<option selected>"+$(this).closest('tr').find('.jam_selesai').html()+"</option>";
		$("#mjam_selesai").append(jam_selesai);
		var menit_selesai = "<option selected>"+$(this).closest('tr').find('.menit_selesai').html()+"</option>";
		$("#mmenit_selesai").append(menit_selesai);
		
		$("#mmateri").val($(this).closest('tr').find('#materi').val());
		$("#mstrategi").val($(this).closest('tr').find('#strategi').val());
		
		var id_visit = $(this).closest('tr').find('#id_visit').val();
		$("#vid_visit").val(id_visit);
		console.log(id_visit);
		/// COUNT REALISASI by id Visit //
		
		var is_set = 0; // kalo udah ada realisasinya, is_set = 1
		
		$.ajax({
			url:'operasional/account_planning/count_realisasi/'+id_visit,
			type:'get',
			beforeSend: function() {
			  $.LoadingOverlay("show"); 
		   },
			success:function(data)
			{
				
				if(data>0)
				{
					is_set=1;
					
				}
				if(is_set==1) // kalo udah ada datanya
				{
					$("#btn_save").prop('disabled',true);
					console.log('data found '+id_visit);
					$.ajax({
						url:'operasional/account_planning/get_realisasi/'+id_visit,
						type:'get',
						dataType:'json',
						
						success:function(data)
						{
							
						 $.LoadingOverlay("hide");
							$.each(data, function(i,v){
								$("#vtanggal").val(v.tanggal);
								$("#vjam_mulai").val(v.jam_mulai);
								$("#vjam_selesai").val(v.jam_selesai);
								$("#vmenit_mulai").val(v.menit_mulai);
								$("#vmenit_selesai").val(v.menit_selesai);
								$("#vhasil").val(v.hasil);
								$("#modal-realisasi").modal('show');
							});
						}
					});
				}
				else {
					 $.LoadingOverlay("hide"); 
					$("#btn_save").prop('disabled',false);
					$("#vtanggal").val('');
					$("#vjam_mulai").val(0);
					$("#vjam_selesai").val(0);
					$("#vmenit_mulai").val(0);
					$("#vmenit_selesai").val(0);
					$("#vhasil").val('');
					$("#modal-realisasi").modal('show');
				}
				

			}
			
		});
		
	});

</script>

<div class="row">                        
	 <div class="col-md-12">
       <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icon-chart-bars"></span>Account Planning Realization</h3>     
				<div class="panel-elements pull-right">
					<button id="btn-add-data" class="btn btn-info btn-icon-fixed"  onclick="window.open('operasional/account_planning/','_self');"><span class="icon-chevron-left-square"></span> Back</button>
				</div>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
				<h3><?=$nama_cust?></h3>
				 <h4>Plan For : <?php
					$dateObj   = DateTime::createFromFormat('!m', $bulan);
					$monthName = $dateObj->format('F'); // March
					echo $monthName.' '.$tahun;
				?>, <?=$total_visit?> Visits</h4>
					
					<?php
					
					?>
					<table style="width:100%;" id="table-data" class="table table-head-custom table-bordered table-hover dataTable no-footer font11" style="">
						<thead>
							<tr>
								<th style="width:5%;">No.</th>
								<th style="width:15%;">Date</th>
								<th style="width:8%;">Time Start</th>
								<th style="width:8%;">Time End</th>
								<th style="width:14%;">Topics</th>
								<th style="width:14%;">Strategy</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$no = 1;
							foreach ($konten->result() as $y):
						?>
							<tr>
								<th><?=$no?><?php $no++;?>
								<input type="hidden" id="id_visit" class="id_detail" value="<?=$y->id?>"></th>
								<th><input disabled id="tanggal<?=$no?>" class="tanggal" placeholder="Select Date" value="<?=$y->tanggal?>"></th>
								<th>
								<select disabled id="jam_mulai"  class="jam_mulai">
									<?php 
									$x='';
									for($i=0;$i<24;$i++)
									{
										if($i<10)
										$x = '0'.$i;
										else 
										$x = $i;
										echo "<option ".($y->jam_mulai==$i?"selected":'')." value='$i'>$x</option>";
										
									}
									?> 
									
								</select>:
								<select disabled id="menit_mulai"   class="menit_mulai">
									<?php 
									for($i=0;$i<60;$i+=5)
									{
										if($i<10)
										$x = '0'.$i;
										else 
										$x = $i;
										echo "<option  ".($y->menit_mulai==$i?"selected":'')."  value='$i'>$x</option>";
									}
									?> 
									
								</select></th>
								<th>
								<select disabled id="jam_selesai"  class="jam_selesai">
									<?php 
									for($i=0;$i<24;$i++)
									{
										if($i<10)
										$x = '0'.$i;
										else 
										$x = $i;
										echo "<option ".($y->jam_selesai==$i?"selected":'')."  value='$i'>$x</option>";
									}
									?> 
									
								</select>:
								<select disabled id="menit_selesai"   class="menit_selesai">
									<?php 
									
									for($i=0;$i<60;$i+=5)
									{
										if($i<10)
										$x = '0'.$i;
										else 
										$x = $i;
										echo "<option ".($y->menit_selesai==$i?"selected":'')."  value='$i'>$x</option>";
									}
									?> 
									
								</select></th>
								<th>
									<textarea disabled id="materi"  rows="3" style="width:100%;"><?=$y->materi?></textarea>
								</th>
								<th>
									<textarea disabled id="strategi"  rows="3" style="width:100%;"><?=$y->strategi?></textarea>
								</th>
								<th>
									<button id="realization" class="realization btn btn-info btn-sm" >
										<span class="icon-checkmark-circle"></span> Realization</button>
								</th>
							</tr>
						<?php endforeach;?>
						</tbody>
					</table>       
				</div>
			</div>
		</div>
	</div>
</div>


<!--===================================================---------------=================================================================-->
<!--=======================================            MODAL REALIZATION DATA             ====================================================-->
<!--===================================================---------------=================================================================-->
<div class="modal fade" id="modal-realisasi" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
	<div class="modal-dialog modal-lg modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Planning Detail</h4>
			</div>
			<div class="modal-body">
			
				<div class="col-md-6">
					<fieldset class="x-border">
									<legend class="x-border" ><b>Planning</b></legend>
						<table style="width:100%;" id="table-data" class="table table-head-custom table-bordered table-hover dataTable no-footer font11" style="">
							<tr>
								<th style="width:10%;">Date</th>
								<th style="width:90%;"><input readonly id="mtanggal<?=$no?>" class="mtanggal" placeholder="Select Date" value=""></th>
							</tr>
							<tr>
								<th style="width:5%;">Time Start</th>
								<th style="width:5%;"><select disabled readonly id="mjam_mulai"  class="mjam_mulai"></select>:<select disabled readonly id="mmenit_mulai"  class="mmenit_mulai"></select></th>
							</tr>
							<tr>
								<th style="width:5%;">Time End</th>
								<th style="width:5%;"><select disabled readonly id="mjam_selesai"  class="mjam_selesai"></select>:<select disabled readonly id="mmenit_selesai"  class="mmenit_selesai"></select></th>
							</tr>
							<tr>
								<th style="width:14%;">Topics</th>
								<th style="width:14%;"><textarea readonly id="mmateri"  rows="3" style="width:100%;"></textarea></th>
							</tr>
							<tr>
								<th style="width:14%;">Strategy</th>
								<th style="width:14%;"><textarea readonly id="mstrategi"  rows="3" style="width:100%;"></textarea></th>
							</tr>
						</table>    
					</fieldset>
				</div>
				<div class="col-md-6">
					<fieldset class="x-border">
						<legend class="x-border" ><b>Realization</b></legend>
						<input type="hidden" id="vid_visit" class="vid_visit" value=""></th>
						<table style="width:100%;" id="table-data" class="table table-head-custom table-bordered table-hover dataTable no-footer font11" style="">
							<tr>
								<th style="width:10%;">Date</th>
								<th style="width:90%;">
								<input  id="vtanggal" class="vtanggal" placeholder="Select Date" value=""></th>
							</tr>
							<tr>
								<th style="width:5%;">Time Start</th>
								<th style="width:5%;"><select  id="vjam_mulai"  class="vjam_mulai">
								<?php 
									$x='';
									for($i=0;$i<24;$i++)
									{
										if($i<10)
										$x = '0'.$i;
										else 
										$x = $i;
										echo "<option value='$i'>$x</option>";
										
									}
									?> 
								</select>:<select  id="vmenit_mulai"  class="vmenit_mulai">
								<?php 
									for($i=0;$i<60;$i+=5)
									{
										if($i<10)
										$x = '0'.$i;
										else 
										$x = $i;
										echo "<option value='$i'>$x</option>";
									}
									?> 
								</select></th>
							</tr>
							<tr>
								<th style="width:5%;">Time End</th>
								<th style="width:5%;"><select  id="vjam_selesai"  class="vjam_selesai">
								<?php 
									$x='';
									for($i=0;$i<24;$i++)
									{
										if($i<10)
										$x = '0'.$i;
										else 
										$x = $i;
										echo "<option value='$i'>$x</option>";
										
									}
									?> </select>:<select  id="vmenit_selesai"  class="vmenit_selesai">
									<?php 
									for($i=0;$i<60;$i+=5)
									{
										if($i<10)
										$x = '0'.$i;
										else 
										$x = $i;
										echo "<option value='$i'>$x</option>";
									}
									?> </select></th>
							</tr>
							<tr>
								<th style="width:14%;">Hasil</th>
								<th style="width:14%;"><textarea  id="vhasil"  rows="6" style="width:100%;"></textarea></th>
								
							</tr>
							<tr>
								<th style="width:14%;"></th>
								<th style="width:14%;"><button id="btn_save" class="btn btn-warning">Save</button></th>
								
							</tr>
						</table>    
					</fieldset>
				</div>
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
			