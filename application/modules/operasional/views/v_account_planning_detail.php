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
		
	$("#tanggal<?=$x?>").datepicker({
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
	
});

</script>

<div class="row">                        
	 <div class="col-md-12">
       <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icon-chart-bars"></span>Account Planning Detail</h3>     
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
					<!--div class="pull-right col-md-3 margin-bottom-15">
						SELECT SALES : <select id = 'table-filter' class="form-control bs-select">
											<option value='' selected>All sales</option>
											<?php
												$qx = $this->db->query('select * from ck_sales');
												foreach ($qx->result() as $r)
												{
													echo "<option value='$r->nama_sales'>$r->nama_sales</option>";
												}
											?>
										</select>
					</div-->
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
								<th style="width:14%;">Promotion Materials</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
							for($n=1;$n<=$total_visit;$n++):
						?>
							<tr>
								<th><?=$n?><input type="hidden" class="id_detail"></th>
								<th><input id="tanggal<?=$n?>" class="tanggal" placeholder="Select Date" ></th>
								<th>
								<select id="jam_mulai"  class="jam_mulai">
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
									
								</select>:
								<select id="menit_mulai"   class="menit_mulai">
									<?php 
									for($i=0;$i<60;$i+=30)
									{
										if($i<10)
										$x = '0'.$i;
										else 
										$x = $i;
										echo "<option value='$i'>$x</option>";
									}
									?> 
									
								</select></th>
								<th>
								<select id="jam_selesai"  class="jam_selesai">
									<?php 
									for($i=0;$i<24;$i++)
									{
										if($i<10)
										$x = '0'.$i;
										else 
										$x = $i;
										echo "<option value='$i'>$x</option>";
										}
									?> 
									
								</select>:
								<select id="menit_selesai"   class="menit_selesai">
									<?php 
									for($i=0;$i<60;$i+=30)
									{
										if($i<10)
										$x = '0'.$i;
										else 
										$x = $i;
										echo "<option value='$i'>$x</option>";
									}
									?> 
									
								</select></th>
								<th>
									<textarea id="materi"  rows="3" style="width:100%;"></textarea>
								</th>
								<th>
									<textarea id="strategi"  rows="3" style="width:100%;"></textarea>
								</th>
								<th>
									<button id="save" class="save btn btn-info btn-sm" >
										<span class="icon-floppy-disk"></span> Save</button>
									<button id="edit" disabled class="edit btn btn-warning btn-sm" >
										<span class="icon-pencil4"></span> Edit</button>
								</th>
							</tr>
						<?php endfor;?>
						</tbody>
					</table>       
				</div>
			</div>
		</div>
	</div>
</div>