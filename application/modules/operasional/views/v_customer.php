<script>
$(document).on({
    ajaxStart: function() { $.LoadingOverlay("show");  },
     ajaxStop: function() {  $.LoadingOverlay("hide"); }    
});
$(document).ready(function(){
	
	//********	DataTable ***********//
	$('#table-data-customer').DataTable({
		"bProcessing": true,
		"bSort" : true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "operasional/customer/populate_customer",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		  "order": [[ 0, "desc" ]]
	});
	
	
	//********	Get kota ***********//
	$("#select-provinsi").on('change',function(){
		var id_provinsi = $(this).val();
		var opt = '<option disabled selected hidden>Select City</option>';
		$.ajax({
			url:'operasional/customer/get_kota/'+id_provinsi,
			dataType:'json',
			type:'get',
			success:function(json)
			{
				$.each(json,function(i,obj){
					//console.log("Value : "+obj.id+", Nama : "+obj.nama);
					opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
				})
				$("#select-kota").html(opt);
				$("#select-kota").selectpicker('refresh');
			}
		});
	});
	$("#e-select-provinsi").on('select change',function(){
		var id_provinsi = $(this).val();
		var opt = '<option disabled selected hidden>Select City</option>';
		$.ajax({
			url:'operasional/customer/get_kota/'+id_provinsi,
			dataType:'json',
			type:'get',
			success:function(json)
			{
				$.each(json,function(i,obj){
					//console.log("Value : "+obj.id+", Nama : "+obj.nama);
					opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
				})
				$("#e-select-kota").html(opt);
				$("#e-select-kota").selectpicker('refresh');
			}
		});
	});
	
	
	//********	Get Branch ***********//
	$("#select-region").on('change',function(){
		var id_region = $(this).val();
		var opt = '<option disabled selected hidden>Select Branch</option>';
		$.ajax({
			url:'operasional/customer/get_branch/'+id_region,
			dataType:'json',
			type:'get',
			success:function(json)
			{
				$.each(json,function(i,obj){
					//console.log("Value : "+obj.id+", Nama : "+obj.nama);
					opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
				})
				$("#select-branch").html(opt);
				$("#select-branch").selectpicker('refresh');
			}
		});
	});
	$("#e-select-region").on('select change',function(){
		var id_region = $(this).val();
		var opt = '<option disabled selected hidden>Select Branch</option>';
		$.ajax({
			url:'operasional/customer/get_branch/'+id_region,
			dataType:'json',
			type:'get',
			success:function(json)
			{
				$.each(json,function(i,obj){
					//console.log("Value : "+obj.id+", Nama : "+obj.nama);
					opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
				})
				$("#e-select-branch").html(opt);
				$("#e-select-branch").selectpicker('refresh');
			}
		});
	});
	
	
	//********	Get Area ***********//
	$("#select-branch").on('change',function(){
		var id_branch = $(this).val();
		var opt = '<option disabled selected hidden>Select Area</option>';
		$.ajax({
			url:'operasional/customer/get_area/'+id_branch,
			dataType:'json',
			type:'get',
			success:function(json)
			{
				$.each(json,function(i,obj){
					//console.log("Value : "+obj.id+", Nama : "+obj.nama);
					opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
				})
				$("#select-area").html(opt);
				$("#select-area").selectpicker('refresh');
			}
		});
	});
	$("#e-select-branch").on('select change',function(){
		var id_branch = $(this).val();
		var opt = '<option disabled selected hidden>Select Area</option>';
		$.ajax({
			url:'operasional/customer/get_area/'+id_branch,
			dataType:'json',
			type:'get',
			success:function(json)
			{
				$.each(json,function(i,obj){
					//console.log("Value : "+obj.id+", Nama : "+obj.nama);
					opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
				})
				$("#e-select-area").html(opt);
				$("#e-select-area").selectpicker('refresh');
			}
		});
	});
	
	
	//********	Get Sales ***********//
	$("#select-area").on('change',function(){
		var id_area = $(this).val();
		var opt = '<option disabled selected hidden>Select Sales Executive</option>';
		$.ajax({
			url:'operasional/customer/get_sales/'+id_area,
			dataType:'json',
			type:'get',
			success:function(json)
			{
				$.each(json,function(i,obj){
					//console.log("Value : "+obj.id+", Nama : "+obj.nama);
					opt += "<option value='"+obj.id+"'>"+obj.nama_sales+"</option>";
				})
				$("#select-sales").html(opt);
				$("#select-sales").selectpicker('refresh');
			}
		});
	});
	$("#e-select-area").on('select change',function(){
		var id_area = $(this).val();
		var opt = '<option disabled selected hidden>Select Sales Executive</option>';
		$.ajax({
			url:'operasional/customer/get_sales/'+id_area,
			dataType:'json',
			type:'get',
			success:function(json)
			{
				$.each(json,function(i,obj){
					//console.log("Value : "+obj.id+", Nama : "+obj.nama);
					opt += "<option value='"+obj.id+"'>"+obj.nama_sales+"</option>";
				})
				$("#e-select-sales").html(opt);
				$("#e-select-sales").selectpicker('refresh');
			}
		});
	});
	
	
	/**** Processing Insert Data ****/
	$("#submitter").click(function(){
		$(this).prop('disabled',true);
		var has_empty = false;

		$('#form-tambah-customer').find(':input[required]').each(function () {

		  if ( ! $(this).val() )
			  { has_empty = true; return false; }
		});

		if ( has_empty ) { 
			alert("Cannot process empty value!");
			return false;
		}

		var form =$('#form-tambah-customer')[0]
		var formData = new FormData(form);
		formData.append('select-region',$("#select-region").val());
		formData.append('select-kelas',$("#select-kelas").val());
		formData.append('select-branch',$("#select-branch").val());
		formData.append('select-area',$("#select-area").val());
		formData.append('select-sales',$("#select-sales").val());
		$.ajax({
		  url: 'operasional/customer/input_data',
		  type: 'POST',
		  data: formData,
		  cache: false,
		  contentType: false,
		  processData: false,
		  success: function(respons)
		  {
			 if(respons=="done")
			 {
				 swal({
				  title: 'Adding Customer Success!',				  
				  type: 'success',
				  timer: 2000,
				  onOpen: () => {
					swal.showLoading()
				  },
				  showCancelButton: false,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'OK'
				},function(){
					form.reset();
					$("#modal-add-user").modal('hide');
					$("#table-data-customer").DataTable().ajax.reload();
				});
			 }
			else alert(respons);
			
		  }
		})
		$(this).prop('disabled',false);
	});
	
	
	/**** Processing Update Data ****/
	$("#editbutton").click(function(){
		
		var id = $("#id").val();
		var has_empty = false;

		$('#form-edit-customer').find(':input[required]').each(function () {

		  if ( ! $(this).val() )
			  { has_empty = true; return false; }
		});

		if ( has_empty ) { 
			alert("Cannot process empty value!");
			return false;
		}

		var form =$('#form-edit-customer')[0];
		var formData = new FormData(form);
		formData.append('nama',$("#e-input-nama").val());
		formData.append('website',$("#e-input-web").val());
		formData.append('email',$("#e-input-email").val());
		formData.append('id_region',$("#e-select-region").val());
		formData.append('id_provinsi',$("#e-select-provinsi").val());
		formData.append('id_kabupatenkota',$("#e-select-kota").val());
		formData.append('id_branch',$("#e-select-branch").val());
		formData.append('id_area',$("#e-select-area").val());
		formData.append('id_sales',$("#e-select-sales").val());
		formData.append('id',id);
		formData.append('id_customer_kelompok',$("#e-select-kelompok").val());
		formData.append('id_kelas_customer',$("#e-select-kelas").val());
		$.ajax({
		  url: 'operasional/customer/update_data/'+id,
		  type: 'POST',
		  data: formData,
		  cache: false,
		  contentType: false,
		  processData: false,
		  success: function(respons)
		  {
			  
			 if(respons=="done")
			 {
				 swal({
				  title: 'Update Customer Data Success!',
				  text: "Press OK to continue",
				  
				  type: 'success',
				  timer: 2000,
				  onOpen: () => {
					swal.showLoading()
				  },
				  showCancelButton: false,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'OK'
				},function(){
					form.reset();
					$("#modal-edit-user").modal('hide');
					$("#table-data-customer").DataTable().ajax.reload();
				});
			 }
			else alert(respons);
			$("#epre1").removeAttr('src');
			$("#epre2").removeAttr('src');
			$("#epre3").removeAttr('src');
			//kosongkan input foto
			$("#ep1").removeAttr('src');
			$("#ep2").removeAttr('src');
			$("#ep3").removeAttr('src');
			
		  }
		})
	});
	
	$("#samain_alamat").change(function(){
		if(this.checked) {
			$("#input-alamat-tagihan").val($("#input-alamat").val());
		}
		else
		{
			
		}
		
	});
});


$(document).on('click','#button-detail',function(){
	
	//kosongkan input foto
	$("#dp1").removeAttr('src');
	$("#dp2").removeAttr('src');
	$("#dp3").removeAttr('src');
	
	
	
	//zerofill, tambah karakter di depan agar zerofill tidak hilang //
	var id = 'a'+$(this).closest('tr').find('#idcustomer').val();
	
	$("#id").val(id);
	// //get detail user by id
	
	$.ajax({
		url:'operasional/customer/get_customer_detail/'+id,
		type:'get',
		dataType:'json',
		beforeSend: function() {
              $.LoadingOverlay("show"); 
           },
		success:function(json)
		{
			$.LoadingOverlay("hide"); 
			$.each(json,function(i,obj){
				
				$("#d-kelas").val(obj.kelas);
				$("#d-select-kelompok").val(obj.nama_kelompok);
				$("#d-input-nama").val(obj.nama);
				$("#d-input-kodepos").val(obj.kode_pos);
				
				$("#d-input-alamat").val(obj.alamat_kirim);
				$("#d-input-alamat-tagihan").val(obj.alamat_tagihan);
				
				
				$("#d-select-provinsi").val(obj.provinsi);
				$("#d-select-kota").val(obj.kota);
				
				$("#d-input-npwp").val(obj.npwp);
				$("#d-input-telpon").val(obj.telepon);
				$("#d-input-fax").val(obj.faks);
				$("#d-input-email").val(obj.email);
				$("#d-input-web").val(obj.website);
				$("#d-input-lat").val(obj.latitude);
				$("#d-input-long").val(obj.longitude);
				$("#d-input-deskripsi").val(obj.deskripsi);
				
				
				$("#d-select-region").val(obj.region);
				console.log("obj region : "+obj.region);
				$("#d-select-branch").val(obj.branch);
				
				$("#d-select-area").val(obj.area);
				
				$("#d-select-sales").val(obj.nama_sales);
				$.ajax({
					//get cp
					url:'operasional/customer/get_cp/'+id,
					type:'get',
					success:function(data){
						$("#cp_detail").html(data);
					}
				});
				// $("#d-input-pic-nama").val(obj.pic_nama);
				// $("#d-input-pic-jabatan").val(obj.pic_jabatan);
				// $("#d-input-pic-hp").val(obj.pic_hp);
				// $("#d-input-pic-email").val(obj.pic_email);
				
				// d = new Date();
				// //foto
				// $("#dp1").removeAttr("src").attr('src', obj.foto_1+'?'+d.getTime());
				// $("#dp2").removeAttr("src").attr('src', obj.foto_2+'?'+d.getTime());
				// $("#dp3").removeAttr("src").attr('src', obj.foto_3+'?'+d.getTime());
				// $("#cpfoto").removeAttr("src").attr('src', obj.pic_foto+'?'+d.getTime());
				$("#modal-detail-user").modal('show');
			});
			
		}
	});	
});

$(document).on('click','#button-edit',function(){
	//zerofill, tambah karakter di depan agar zerofill tidak hilang //
	var id = 'a'+$(this).closest('tr').find('#idcustomer').val();
	
	$("#id").val(id);
	// //get detail user by id async:false,
	$.ajax({
		url:'operasional/customer/get_customer_detail/'+id,
		type:'get',
		dataType:'json',
		
		beforeSend: function() {
              $.LoadingOverlay("show"); 
           },
		success:function(json)
		{
			$.LoadingOverlay("hide"); 
			$.each(json,function(i,obj){
				
				$("#e-select-kelas").val(obj.id_kelas);
				$("#e-select-kelas").selectpicker('refresh');
				
				$("#e-select-kelompok").val(obj.id_customer_kelompok);
				$("#e-select-kelompok").selectpicker('refresh');
				
				$("input[name='nama']").val(obj.nama);
				$("input[name='kode_pos']").val(obj.kode_pos);
				
				$("textarea[name='alamat_kirim']").val(obj.alamat_kirim);
				$("textarea[name='alamat_tagihan']").val(obj.alamat_tagihan);
				
				
				$("#e-select-provinsi").val(obj.id_provinsi);
				$("#e-select-provinsi").selectpicker('refresh');
				var id_provinsi = $('#e-select-provinsi').val();
				var opt = '<option disabled selected hidden>Select City</option>';
				$.ajax({
					url:'operasional/customer/get_kota/'+id_provinsi,
					dataType:'json',
					type:'get',
					async:false,
					success:function(json)
					{
						$.each(json,function(i,obj){
							//console.log("Value : "+obj.id+", Nama : "+obj.nama);
							opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
						})
						$("#e-select-kota").html(opt);
						$("#e-select-kota").selectpicker('refresh');
					}
				});
				
				$("#e-select-kota").val(obj.id_kabupatenkota);
				$("#e-select-kota").selectpicker('refresh');
				
				$("input[name='npwp']").val(obj.npwp);
				$("input[name='telepon']").val(obj.telepon);
				$("input[name='faks']").val(obj.faks);
				$("input[name='email']").val(obj.email);
				$("input[name='website']").val(obj.website);
				$("input[name='latitude']").val(obj.latitude);
				$("input[name='longitude']").val(obj.longitude);
				$("textarea[name='deskripsi']").val(obj.deskripsi);
				
				
				$("#e-select-region").val(obj.id_region);
				$("#e-select-region").selectpicker('refresh');
				var id_region = $("#e-select-region").val();
				var opt = '<option disabled selected hidden>Select Branch</option>';
				$.ajax({
					url:'operasional/customer/get_branch/'+id_region,
					dataType:'json',
					type:'get',
					async:false,
					success:function(json)
					{
						$.each(json,function(i,obj){
							//console.log("Value : "+obj.id+", Nama : "+obj.nama);
							opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
						})
						$("#e-select-branch").html(opt);
						$("#e-select-branch").selectpicker('refresh');
					}
				});
				
				$("#e-select-branch").val(obj.id_branch);
				$("#e-select-branch").selectpicker('refresh');
				var id_branch = $("#e-select-branch").val();
				var opt = '<option disabled selected hidden>Select Area</option>';
				$.ajax({
					url:'operasional/customer/get_area/'+id_branch,
					dataType:'json',
					type:'get',
					async:false,
					success:function(json)
					{
						$.each(json,function(i,obj){
							//console.log("Value : "+obj.id+", Nama : "+obj.nama);
							opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
						})
						$("#e-select-area").html(opt);
						$("#e-select-area").selectpicker('refresh');
					}
				});
				
				$("#e-select-area").val(obj.id_area);
				$("#e-select-area").selectpicker('refresh');
				var id_area = $("#e-select-area").val();
				var opt = '<option disabled selected hidden>Select Sales Executive</option>';
				$.ajax({
					url:'operasional/customer/get_sales/'+id_area,
					dataType:'json',
					type:'get',
					async:false,
					success:function(json)
					{
						$.each(json,function(i,obj){
							//console.log("Value : "+obj.id+", Nama : "+obj.nama);
							opt += "<option value='"+obj.id+"'>"+obj.nama_sales+"</option>";
						})
						$("#e-select-sales").html(opt);
						$("#e-select-sales").selectpicker('refresh');
					}
				});
				$("#e-select-sales").val(obj.id_sales);
				$("#e-select-sales").selectpicker('refresh');
				
				$("input[name='pic_nama']").val(obj.pic_nama);
				$("input[name='pic_jabatan']").val(obj.pic_jabatan);
				$("input[name='pic_hp']").val(obj.pic_hp);
				$("input[name='pic_email']").val(obj.pic_email);
				
				d = new Date();
				//foto
				$("#ep1").removeAttr("src").attr('src', obj.foto_1+'?'+d.getTime());
				$("#ep2").removeAttr("src").attr('src', obj.foto_2+'?'+d.getTime());
				$("#ep3").removeAttr("src").attr('src', obj.foto_3+'?'+d.getTime());
			});
			 
			$("#modal-edit-user").modal('show');
		}
	});
	
})

$(document).on('click','#button-edit-alamat',function(){
	$("#modal-edit-alamat").modal('show');
	var id_customer = $(this).closest('tr').find('#idcustomer').val();
	$("#id_customer_alamat").val(id_customer);
	
	// Load Address
	$.ajax({
		url:'operasional/customer/load_default_address/'+id_customer,
		type:'get',
		dataType:'json',
		async:false,
		success:function(data)
		{
			$.each(data,function(i,obj){
				$("#id_input_alamat_default").val(obj.id_alamat_default);
				$("#id_input_alamat_tagihan_default").val(obj.id_alamat_tagihan_default);
				$("#e-input-alamat").val(obj.alamat_kirim);
				$("#e-input-alamat-tagihan").val(obj.alamat_tagihan);
			})
		}
		
	});
});

$(document).on('click','#btn_save_input_alamat_default',function(){
	var id = $(this).closest('tr').find('#id_input_alamat_default').val();
	var alamat = $(this).closest('tr').find('#e-input-alamat').val();
	$.ajax({
		url:'operasional/customer/update_alamat_default/'+id,
		data:{alamat:alamat},
		type:'post',
		success:function(data){
			alert(data);
			$("#table-data-customer").DataTable().ajax.reload();
		}
		
	});
});

$(document).on('click','#btn_save_input_alamat_tagihan_default',function(){
	var id = $(this).closest('tr').find('#id_input_alamat_tagihan_default').val();
	var alamat = $(this).closest('tr').find('#e-input-alamat-tagihan').val();
	$.ajax({
		url:'operasional/customer/update_alamat_tagihan_default/'+id,
		data:{alamat:alamat},
		type:'post',
		success:function(data){
			alert(data);
			$("#table-data-customer").DataTable().ajax.reload();
		}
		
	});
});

$(document).on('click','#btn_add_new_address',function(){
	$("#modal-new-address").modal('show');
});
$(document).on('click','#btn_insert_new_address',function(){
	var id_customer = $("#id_customer_alamat").val();
	var alamat = $("#input_new_address").val();
	var address_default = $("#select_new_address_default").val();
	$.ajax({
		url:'operasional/customer/insert_address/'+address_default,
		data:{id_customer:id_customer,alamat_kirim:alamat},
		type:'post',
		success:function(data){
			$("#modal-new-address").modal('hide');
			$("#modal-edit-alamat").modal('hide');
			$("#table-data-customer").DataTable().ajax.reload();
			alert(data);
		}
	});
});

$(document).on('click','#btn_add_new_billing_address',function(){
	$("#modal-new-billing-address").modal('show');
});
$(document).on('click','#btn_insert_new_billing_address',function(){
	var id_customer = $("#id_customer_alamat").val();
	var alamat = $("#input_new_billing_address").val();
	var address_default = $("#select_new_billing_address_default").val();
	$.ajax({
		url:'operasional/customer/insert_billing_address/'+address_default,
		data:{id_customer:id_customer,alamat_tagihan:alamat},
		type:'post',
		success:function(data){
			$("#modal-new-billing-address").modal('hide');
			$("#table-data-customer").DataTable().ajax.reload();
			$("#modal-edit-alamat").modal('hide');
			alert(data);
		}
	});
});

$(document).on('click','#button-edit-pic',function(){
	var id = $(this).closest('tr').find('#idcustomer').val();
	$("#id-cstmr").val(id);
	$.ajax({
		url:'operasional/customer/get_pic_default',
		data:{id:id},
		dataType:'json',
		type:'post',
		success:function(data){
			$.ajax({
					//get cp
					url:'operasional/customer/get_cp/'+id,
					type:'get',
					success:function(data){
						$("#cp_list").html(data);
					}
				});
			$.each(data,function(i,obj){
				$("#id-pics").val(obj.id);
				$("#new-pic-nama").val(obj.pic_nama);
				$("#new-pic-hp").val(obj.pic_hp);
				$("#new-pic-jabatan").val(obj.pic_jabatan);
				$("#new-pic-email").val(obj.pic_email);
				d = new Date();
				//foto
				$("#picfoto").removeAttr("src").attr('src', obj.pic_foto+'?'+d.getTime());
				
			});
		}
	});
	$("#modal-edit-pic").modal('show');
	
});

$(document).on('click','#btn-update-pic',function(){
	var id = $("#id-pics").val();
	var pic_nama = $("#new-pic-nama").val();
	var pic_hp = $("#new-pic-hp").val();
	var pic_jabatan= $("#new-pic-jabatan").val();
	var pic_email = $("#new-pic-email").val();
	var formData = new FormData();
		
		formData.append('id',id);
		formData.append('pic_nama',pic_nama);
		formData.append('pic_hp',pic_hp);
		formData.append('pic_jabatan',pic_jabatan);
		formData.append('pic_email',pic_email);
		formData.append('pic_foto', $("#new-pic-foto")[0].files[0]);
	$.ajax({
		  url: 'operasional/customer/update_pic/',
		  type: 'POST',
		  data: formData,
		  cache: false,
		  contentType: false,
		  processData: false,
		  success: function(respons)
		  {
			  alert(respons);
			  $("#id-cstmr").val('');
			  $("#new-pic-nama1").val('');
			  $("#new-pic-hp1").val('');
			  $("#new-pic-jabatan1").val('');
			  $("##new-pic-email1").val('');
		  }
	});
});

$(document).on('click','#btn-create-pic',function(){
	var id_customer = $("#id-cstmr").val();
	var pic_nama = $("#new-pic-nama1").val();
	var pic_hp = $("#new-pic-hp1").val();
	var pic_jabatan= $("#new-pic-jabatan1").val();
	var pic_email = $("#new-pic-email1").val();
	var formData = new FormData();
		
		formData.append('id_customer',id_customer);
		formData.append('pic_nama',pic_nama);
		formData.append('pic_hp',pic_hp);
		formData.append('pic_jabatan',pic_jabatan);
		formData.append('pic_email',pic_email);
		formData.append('pic_foto', $("#new-pic-foto1")[0].files[0]);
	$.ajax({
		  url: 'operasional/customer/create_pic/',
		  type: 'POST',
		  data: formData,
		  cache: false,
		  contentType: false,
		  processData: false,
		  success: function(respons)
		  {
			  alert(respons);
			  $("#id-cstmr").val('');
			  $("#new-pic-nama1").val('');
			  $("#new-pic-hp1").val('');
			  $("#new-pic-jabatan1").val('');
			  $("##new-pic-email1").val('');
		  }
	});
});

$(document).on('click','#button-delete',function(){

	//zerofill, tambah karakter di depan agar zerofill tidak hilang //
	var id = 'a'+$(this).closest('tr').find('#idcustomer').val();
	swal({
	  title: 'Are you sure?',
	  text: "You won't be able to revert this!",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!',
	  cancelButtonText: 'No, cancel!',
	  confirmButtonClass: 'btn btn-success',
	  cancelButtonClass: 'btn btn-danger',
	  buttonsStyling: false,
	  reverseButtons: true
	},function(){
		$.ajax({
			url:'operasional/customer/delete_data/'+id,
			type:'get',
			success:function(data)
			{
				if(data=='done'){
					swal({
					  title: 'Customer Deleted!',
					  text: "Press OK to continue",
					  
					  type: 'success',
					  timer: 2000,
					  onOpen: () => {
						swal.showLoading()
					  },
					  showCancelButton: false,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'OK'
					},function(){
						$("#modal-edit-user").modal('hide');
						$("#table-data-customer").DataTable().ajax.reload();
					});
				}
				else alert(data);
			}
		});
	});
	
});
</script>

<div class="row">                        
	 <div class="col-md-12">
       <div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="icon-user"></span>Customer</h3>     
				<div class="panel-elements pull-right">
					<button id="btn-add-data" class="btn btn-info btn-icon-fixed"  data-toggle="modal" data-target="#modal-add-user"><span class="icon-user-plus"></span>Add Data</button>
				</div>
			</div>
			<div class="panel-body">                                    
				<table id="table-data-customer" class="table table-head-custom table-hover dataTable no-footer font11" style="">
					<thead> 
						<tr class=" font11">
							<th style="width:3%;">No</th>
							<th style="width:7%;">Kode</th>
							<th style="width:3%;">Class</th>
							<th style="width:12%;">Cust. Name</th>
							<th style="width:5%;">Area</th>
							<th style="width:16%;">Default Shipping Address</th>
							<th style="width:15%;">Default Billing Address</th>
							<th style="width:5%;">Phone</th>
							<th style="width:5%;">CP</th>
							<th style="width:9%;">Sales Executive</th>
							<th style="width:9%;">Credit Limit</th>
							<th style="width:12%;">Action</th>
						</tr>
					</thead>                                    
					<tbody id="showdetail" style="padding:0px;"> 
						
					</tbody>
				</table>                       
			</div>
			<!--div class="panel-footer">   
				<!--div class="panel-elements pull-right">
					<button class="btn btn-info pull-right"><span class="icon-launch"></span> Submit</button>
				</div!>                                        
			</div-->
		</div>

	</div>
</div>


<!--===================================================---------------=================================================================-->
<!--=======================================            MODAL CREATE DATA             ====================================================-->
<!--===================================================---------------=================================================================-->
<div class="modal fade" id="modal-add-user" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
	<div class="modal-dialog modal-lg modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Add New Customer</h4>
			</div>
			<div class="modal-body">
				<div>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tabs-main" data-toggle="tab"><span class="fa fa-list-ol"></span> Customer Data</a></li>
						<li><a href="#tabs-sales" data-toggle="tab"><span class="fa fa-male"></span> Sales Executive</a></li>
						<li><a href="#tabs-foto" data-toggle="tab"><span class="fa fa-file-picture-o"></span> Photos</a></li>
						<li><a href="#tabs-pic" data-toggle="tab"><span class="icon icon-telephone"></span> CP</a></li>
					</ul>
					<div class="tab-content tab-content-bordered">
						<div class="tab-pane active" id="tabs-main">
							<div class="col-md-6">
								<fieldset class="x-border">
									<legend class="x-border" ><b>Address</b></legend>
									<form id="form-tambah-customer"  enctype="multipart/form-data">
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%">Class</td>
											<td style="width:1%"></td>
											<td style="width:79%">
												<select required id="select-kelas" name="select-kelas" class="bs-select" data-live-search="true">
												
													<?php 
														$data_kelas=$this->db->query("select * from ck_kelas_customer");
														foreach($data_kelas->result() as $row)
														{
															echo '<option value="'.$row->id.'">'.$row->kelas.'</option>';
														}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td style="width:20%">Group</td>
											<td style="width:1%"></td>
											<td style="width:79%">
												<select required id="select-kelompok" name="select-kelompok" class="bs-select" data-live-search="true">
												
													<?php 
														foreach($data_kelompok->result() as $row)
														{
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td >Customer Name</td>
											<td  ></td>
											<td ><input id="input-nama" name="input-nama" required class="form-control input" type="text"></td>
										</tr>
										<tr>
											<td>Shipping Address</td>
											<td></td>
											<td><textarea id="input-alamat" name="input-alamat" required rows="4" class="form-control input-sm" type="text"></textarea></td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td><input type="checkbox" id="samain_alamat"> Same Address</td>
										</tr>
										<tr>
											<td>Billing Address</td>
											<td></td>
											<td><textarea id="input-alamat-tagihan" name="input-alamat-tagihan" required rows="4" class="form-control input-sm" type="text"></textarea></td>
										</tr>
										<tr>
											<td>Province</td>
											<td></td>
											<td>
												<select id="select-provinsi" name="select-provinsi" class="bs-select" data-live-search="true">
												
													<option disabled  hidden>Select Province </option>
													<?php 
														foreach($data_provinsi->result() as $row)
														{
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>	
											</td>
										</tr>
										<tr>
											<td>City</td>
											<td></td>
											<td>
												<select id="select-kota" name="select-kota" class="bs-select" data-live-search="true">
													<option disabled  hidden>Select Province First </option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Postal</td>
											<td></td>
											<td><input id="input-kodepos" name="input-kodepos" required class="form-control input" type="text"></td>
										</tr>
										<tr>
											<td>Geo Location</td>
											<td></td>
											<td><div class="input-group">
													<span class="input-group-addon">Latitude</span>
													<input id="input-lat" name="input-lat" class="form-control input" type="text">
												</div></td>
										</tr>
										<tr>
											<td> </td>
											<td></td>
											<td><div class="input-group">
												<span class="input-group-addon">Longitude</span>
												<input id="input-long" name="input-long" class="form-control input" type="text">
												</div></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div class="col-md-6">
								<fieldset class="x-border">
									<legend class="x-border"><b>Detail</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>NPWP</td>
											<td style="width:1%"></td>
											<td style="width:79%"><input id="input-npwp" name="input-npwp" required class="form-control input" type="text"></td>
										</tr>
										<tr>
											<td>Phone</td>
											<td></td>
											<td><input id="input-telpon" name="input-telpon" required class="form-control input" type="text"></td>
										</tr>
										<tr>
											<td>Fax</td>
											<td></td>
											<td><input id="input-fax" name="input-fax" required class="form-control input" type="text"></td>
										</tr>
										<tr>
											<td >Email</td>
											<td></td>
											<td><input id="input-email" name="input-email"  required class="form-control" type="email"></td>
										</tr>
										<tr>
											<td >Website</td>
											<td></td>
											<td><input id="input-web"  name="input-web" class="form-control" type="text"></td>
										</tr>
										<tr>
											<td>Description</td>
											<td></td>
											<td><textarea id="input-deskripsi" name="input-deskripsi" rows="4" class="form-control input-sm" type="text"></textarea></td>
										</tr>
										
									</table>
								</fieldset>
							</div>

						</div>
						<div class="tab-pane" id="tabs-sales">
							<div class="col-md-8">
								<fieldset class="x-border">
									<legend class="x-border"><b>Detail</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>Region</td>
											<td style="width:1%"></td>
											<td style="width:79%">
												<select required id="select-region" name="select-region" class="bs-select" data-live-search="true">
													<option disabled  hidden>Select Region</option>
													<?php
													foreach ($data_region->result() as $row):
														echo "<option value='".$row->id."'>$row->nama"." - ".$row->deskripsi."</option>";
													endforeach;
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td>Branch</td>
											<td></td>
											<td>
												<select required id="select-branch" name="select-branch" class="bs-select" data-live-search="true">
													<option disabled  hidden>Select Region First </option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Area</td>
											<td></td>
											<td>
												<select required id="select-area" name="select-area" class="bs-select" data-live-search="true">
													<option disabled  hidden>Select Branch First </option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Sales Executive</td>
											<td></td>
											<td>
												<select required id="select-sales" name="select-sales" class="bs-select" data-live-search="true">
													<option disabled  hidden>Select Area First </option>
												</select>
											</td>
										</tr>
										
									</table>
								</fieldset>
							</div>
						</div>
						<div class="tab-pane" id="tabs-foto">
							<div class="col-md-6">
								<fieldset class="x-border">
									<legend class="x-border"><b>Upload Images</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>Photo 1</td>
											<td style="width:1%"></td>
											<td style="width:79%"> <input id="input-foto1" name="input-foto1" type="file" title="Browse"   onchange="document.getElementById('pre1').src = window.URL.createObjectURL(this.files[0])">
												<img id="pre1" alt="No Preview" width="100" height="100" />
											</td>
										</tr>
										<tr>
											<td>Photo 2</td>
											<td></td>
											<td><input id="input-foto2" name="input-foto2" type="file" title="Browse"  onchange="document.getElementById('pre2').src = window.URL.createObjectURL(this.files[0])">
												<img id="pre2" alt="No Preview" width="100" height="100" /></td>
										</tr>
										<tr>
											<td>Photo 3</td>
											<td></td>
											<td><input id="input-foto3" name="input-foto3" type="file" title="Browse"   onchange="document.getElementById('pre3').src = window.URL.createObjectURL(this.files[0])">
												<img id="pre3" alt="No Preview" width="100" height="100" /></td>
										</tr>
									</table>
								</fieldset>
							</div>
						</div>
						<div class="tab-pane" id="tabs-pic">
							<div class="col-md-6">
								<fieldset class="x-border">
									<legend class="x-border"><b>Add Contact Person Data</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>Name</td>
											<td style="width:1%"></td>
											<td style="width:79%"> <input required id="input-pic-nama" name="input-pic-nama" class="input form-control" type="text" title="Name" required></td>
										</tr>
										<tr>
											<td>Title</td>
											<td></td>
											<td><input id="input-pic-jabatan" name="input-pic-jabatan" class="input form-control" type="text" title="Title"></td>
										</tr>
										<tr>
											<td>Phone Number</td>
											<td></td>
											<td><input id="input-pic-hp" name="input-pic-hp" required class="input form-control" type="text" title="Phone"></td>
										</tr>
										<tr>
											<td>Email</td>
											<td></td>
											<td><input id="input-pic-email" name="input-pic-email" class="input form-control" type="text" title="email"></td>
										</tr>
										<tr>
											<td>Photo</td>
											<td></td>
											<td><input id="input-pic-foto" name="input-pic-foto" type="file" title="foto" onchange="document.getElementById('pre4').src = window.URL.createObjectURL(this.files[0])">
											<img id="pre4" alt="No Preview" width="100" height="100" /></td>
										</tr>
									</table>
									</form>
								</fieldset>
							</div>
							
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
				<button id="submitter" type="button" class="btn btn-info"><span class="icon-share"></span>Process Data</button>
			</div>
		</div>
	</div>            
</div>



<!--===================================================---------------=================================================================-->
<!--=======================================            MODAL EDIT DATA             ====================================================-->
<!--===================================================---------------=================================================================-->

<div class="modal fade" id="modal-edit-alamat" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">
	<div class="modal-dialog modal-lg modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Edit Customer Address</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<fieldset class="x-border">
							<legend class="x-border" ><b>Default Shipping Address</b></legend>
							<table class="col-md-12" style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0">
								<tr>
									<td class="col-md-3">Address</td>
									<td>
										<input type="hidden" id="id_input_alamat_default">
										<input type="hidden" id="id_customer_alamat">
										<textarea id="e-input-alamat" name="alamat_kirim" required rows="2" class="col-md-4" type="text"></textarea>
										<button  id="btn_save_input_alamat_default">Save</button>
									</td>
								</tr>
								<tr>
									<td>Billing Address</td>
									<td>
										<input type="hidden" id="id_input_alamat_tagihan_default">
										<textarea id="e-input-alamat-tagihan" name="alamat_tagihan" required rows="2" class="col-md-4" type="text"></textarea>
										<button  id="btn_save_input_alamat_tagihan_default">Save</button>
									</td>
								</tr>
							</table>
						</fieldset>
					</div>
				</div>
				<div class="row margin-top-15">	
					<div class="col-md-12">
						<fieldset class="x-border">
							<legend class="x-border" ><b>Alternative Address</b></legend>
							<div class="col-md-2">
								<button id="btn_add_new_address" class="btn btn-sm btn-info">Add New Address</button>
							</div>
							<div class="col-md-2">
								<button id="btn_add_new_billing_address" class="btn btn-sm btn-info">Add New Billing Address</button>
							</div>
							<div class="col-md-10">
								<table>
								
								</table>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-new-address" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">
	<div class="modal-dialog modal-sm modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Add New Address</h4>
			</div>
			<div class="modal-body">
				<table>
					<tr>
						<td>Addr</td>
						<td><textarea id="input_new_address" placeholder="new address"></textarea></td>
					<tr>
						<td>Default?</td>
						<td><select id="select_new_address_default">
							<option value="0" selected> &nbsp; </option>
							<option value="1"> Set As Default </option>
						</select></td>
					</tr>
					</tr>
					<tr>
						<td><button id="btn_insert_new_address">Save</button></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-new-billing-address" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">
	<div class="modal-dialog modal-sm modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Add New Billing Address</h4>
			</div>
			<div class="modal-body">
				<table>
					<tr>
						<td>Addr</td>
						<td><textarea id="input_new_billing_address" placeholder="new address"></textarea></td>
					<tr>
						<td>Default?</td>
						<td><select id="select_new_billing_address_default">
							<option value="0" selected> &nbsp; </option>
							<option value="1"> Set As Default </option>
						</select></td>
					</tr>
					</tr>
					<tr>
						<td><button id="btn_insert_new_billing_address">Save</button></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-edit-pic" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">
	<div class="modal-dialog modal-lg modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Customer CP</h4>
			</div>
			<div class="modal-body">
				<div class="col-md-12">
					<fieldset class="x-border">
						<legend class="x-border"><b>List Contact Person Data</b></legend>
						<div id="cp_list"></div>	
					</fieldset>
				</div>
				<div class="col-md-12">&nbsp;</div>
				<div class="col-md-12">
					<fieldset class="x-border">
						<legend class="x-border"><b>Default Contact Person Data</b></legend>
						<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
							<tr>
								<td style="width:20%" class>Name</td>
								<td style="width:1%"></td>
								<td style="width:79%"> 
									<input  id="id-cstmr" name="id-cstmr" class="input form-control" type="hidden" title="idcstmr">
									<input  id="id-pics" name="id-pics" class="input form-control" type="hidden" title="idcstmr">
									<input required id="new-pic-nama" name="new-pic-nama" class="input form-control" type="text" title="Name" required>
								</td>
							</tr>
							<tr>
								<td>Title</td>
								<td></td>
								<td><input id="new-pic-jabatan" name="new-pic-jabatan" class="input form-control" type="text" title="Title"></td>
							</tr>
							<tr>
								<td>Phone Number</td>
								<td></td>
								<td><input id="new-pic-hp" name="new-pic-hp" required class="input form-control" type="text" title="Phone"></td>
							</tr>
							<tr>
								<td>Email</td>
								<td></td>
								<td><input id="new-pic-email" name="new-pic-email" class="input form-control" type="text" title="email"></td>
							</tr>
							<tr>
								<td>Photo</td>
								<td></td>
								<td><input id="new-pic-foto" name="new-pic-foto" type="file" title="foto" onchange="document.getElementById('picfoto').src = window.URL.createObjectURL(this.files[0])">
								<img id="picfoto" alt="No Preview" width="100" height="100" /></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><button id="btn-update-pic" class="btn btn-success">Update</button></td>
							</tr>
						</table>
						</form>
					</fieldset>
				</div>
				<div class="col-md-12">&nbsp;</div>
				<div class="col-md-12">
					<fieldset class="x-border border-top-10 ">
						<legend class="x-border"><b>Add New Contact Person Data</b></legend>
						<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
							<tr>
								<td style="width:20%" class>Name</td>
								<td style="width:1%"></td>
								<td style="width:79%"> 
									<input  id="id-cstmr" name="id-cstmr" class="input form-control" type="hidden" title="idcstmr">
									<input required id="new-pic-nama1" name="new-pic-nama1" class="input form-control" type="text" title="Name" required>
								</td>
							</tr>
							<tr>
								<td>Title</td>
								<td></td>
								<td><input id="new-pic-jabatan1" name="new-pic-jabatan1" class="input form-control" type="text" title="Title"></td>
							</tr>
							<tr>
								<td>Phone Number</td>
								<td></td>
								<td><input id="new-pic-hp1" name="new-pic-hp1" required class="input form-control" type="text" title="Phone"></td>
							</tr>
							<tr>
								<td>Email</td>
								<td></td>
								<td><input id="new-pic-email1" name="new-pic-email1" class="input form-control" type="text" title="email"></td>
							</tr>
							<tr>
								<td>Photo</td>
								<td></td>
								<td><input id="new-pic-foto1" name="new-pic-foto1" type="file" title="foto" onchange="document.getElementById('picfoto1').src = window.URL.createObjectURL(this.files[0])">
								<img id="picfoto1" alt="No Preview" width="100" height="100" /></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><button id="btn-create-pic" class="btn btn-success">Create</button></td>
							</tr>
						</table>
						</form>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-edit-user" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
	<div class="modal-dialog modal-lg modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Edit Customer Data</h4>
			</div>
			<div class="modal-body">
				<div>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tabs-emain" data-toggle="tab"><span class="fa fa-list-ol"></span> Customer Data</a></li>
						<li><a href="#tabs-esales" data-toggle="tab"><span class="fa fa-male"></span> Sales Executive</a></li>
						<li><a href="#tabs-efoto" data-toggle="tab"><span class="fa fa-file-picture-o"></span> Photos</a></li>
					</ul>
					<div class="tab-content tab-content-bordered">
						<div class="tab-pane active" id="tabs-emain">
							<div class="col-md-6">
								<fieldset class="x-border">
									<legend class="x-border" ><b>Address</b></legend>
									<form id="form-edit-customer"  enctype="multipart/form-data">
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%">Class</td>
											<td style="width:1%"></td>
											<td style="width:79%">
												<select required id="e-select-kelas" name="id_customer_kelas" class="bs-select" data-live-search="true">
												
													<?php 
														
														foreach($data_kelas->result() as $row)
														{
															echo '<option value="'.$row->id.'">'.$row->kelas.'</option>';
														}
													?>
												</select>
											</td>
										</tr> 
										<tr>
											<td style="width:20%">Group</td>
											<td style="width:1%"></td>
											<td style="width:79%">
												<select required id="e-select-kelompok" name="id_customer_kelompok" class="bs-select" data-live-search="true">
												
													<?php 
														foreach($data_kelompok->result() as $row)
														{
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</td>
										</tr> 
										<tr>
											<td >Customer Name</td>
											<td  ></td>
											<td ><input id="id" name="id" required class="form-control input" type="hidden" readonly> 
											<input id="e-input-nama" name="nama" required class="form-control input" type="text"></td>
										</tr>
										
										<tr>
											<td>Province</td>
											<td></td>
											<td>
												<select id="e-select-provinsi" name="id_provinsi" class="bs-select" data-live-search="true">
												
													<option disabled  hidden>Select Province </option>
													<?php 
														foreach($data_provinsi->result() as $row)
														{
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>	
											</td>
										</tr>
										<tr>
											<td>City</td>
											<td></td>
											<td>
												<select id="e-select-kota" name="id_kabupatenkota" class="bs-select" data-live-search="true">
													<option disabled  hidden>Select Province First </option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Postal</td>
											<td></td>
											<td><input id="e-input-kodepos" name="kode_pos" required class="form-control input" type="text"></td>
										</tr>
										<tr>
											<td>Geo Location</td>
											<td></td>
											<td><div class="input-group">
													<span class="input-group-addon">Latitude</span>
													<input id="e-input-lat" name="latitude" class="form-control input" type="text">
												</div></td>
										</tr>
										<tr>
											<td> </td>
											<td></td>
											<td><div class="input-group">
												<span class="input-group-addon">Longitude</span>
												<input id="e-input-long" name="longitude" class="form-control input" type="text">
												</div></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div class="col-md-6">
								<fieldset class="x-border">
									<legend class="x-border"><b>Detail</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>NPWP</td>
											<td style="width:1%"></td>
											<td style="width:79%"><input id="e-input-npwp" name="npwp" required class="form-control input" type="text"></td>
										</tr>
										<tr>
											<td>Phone</td>
											<td></td>
											<td><input id="e-input-telpon" name="telepon" required class="form-control input" type="text"></td>
										</tr>
										<tr>
											<td>Fax</td>
											<td></td>
											<td><input id="e-input-fax" name="faks" required class="form-control input" type="text"></td>
										</tr>
										<tr>
											<td >Email</td>
											<td></td>
											<td><input id="e-input-email" name="email"  required class="form-control" type="email"></td>
										</tr>
										<tr>
											<td >Website</td>
											<td></td>
											<td><input id="e-input-web"  name="website" class="form-control" type="text"></td>
										</tr>
										<tr>
											<td>Description</td>
											<td></td>
											<td><textarea id="e-input-deskripsi" name="deskripsi" rows="4" class="form-control input-sm" type="text"></textarea></td>
										</tr>
										
									</table>
								</fieldset>
							</div>

						</div>
						<div class="tab-pane" id="tabs-esales">
							<div class="col-md-8">
								<fieldset class="x-border">
									<legend class="x-border"><b>Detail</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>Region</td>
											<td style="width:1%"></td>
											<td style="width:79%">
												<select required id="e-select-region" name="id_region" class="bs-select" data-live-search="true">
													<option disabled  hidden>Select Region</option>
													<?php
													foreach ($data_region->result() as $row):
														echo "<option value='".$row->id."'>$row->nama"." - ".$row->deskripsi."</option>";
													endforeach;
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td>Branch</td>
											<td></td>
											<td>
												<select required id="e-select-branch" name="id_branch" class="bs-select" data-live-search="true">
													<option disabled  hidden>Select Region First </option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Area</td>
											<td></td>
											<td>
												<select required id="e-select-area" name="id_area" class="bs-select" data-live-search="true">
													<option disabled  hidden>Select Branch First </option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Sales Executive</td>
											<td></td>
											<td>
												<select required id="e-select-sales" name="id_sales" class="bs-select" data-live-search="true">
													<option disabled  hidden>Select Area First </option>
												</select>
											</td>
										</tr>
										
									</table>
								</fieldset>
							</div>
						</div>
						<div class="tab-pane" id="tabs-efoto">
							<div class="col-md-8">
								<fieldset class="x-border">
									<legend class="x-border"><b>Upload Images</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>Photo 1</td>
											<td style="width:1%"></td>
											<td style="width:79%"> <input id="e-input-foto1" name="foto_1" type="file" title="Browse"   onchange="document.getElementById('epre1').src = window.URL.createObjectURL(this.files[0])">
												<img id="epre1" alt="No Preview" width="100" height="100" />
											</td>
										</tr>
										<tr>
											<td>Photo 2</td>
											<td></td>
											<td><input id="e-input-foto2" name="foto_2" type="file" title="Browse"  onchange="document.getElementById('epre2').src = window.URL.createObjectURL(this.files[0])">
												<img id="epre2" alt="No Preview" width="100" height="100" /></td>
										</tr>
										<tr>
											<td>Photo 3</td>
											<td></td>
											<td><input id="e-input-foto3" name="foto_3" type="file" title="Browse"   onchange="document.getElementById('epre3').src = window.URL.createObjectURL(this.files[0])">
												<img id="epre3" alt="No Preview" width="100" height="100" /></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div class="col-md-4">
								<fieldset class="x-border">
									<legend class="x-border"><b>Uploaded On Server</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>Photo 1</td>
											<td style="width:1%"></td>
											<td style="width:79%"><img id="ep1" alt="-" width="100" height="100" /></td>
										</tr>
										<tr>
											<td>Photo 2</td>
											<td></td>
											<td><img id="ep2" alt="-" width="100" height="100" /></td>
										</tr>
										<tr>
											<td>Photo 3</td>
											<td></td>
											<td><img id="ep3" alt="-" width="100" height="100" /></td>
										</tr>
									</table>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
				<button id="editbutton" type="button" class="btn btn-info"><span class="icon-share"></span>Update Data</button>
			</div>
		</div>
	</div>            
</div>


<!--===================================================---------------=================================================================-->
<!--=======================================            MODAL DETAIL DATA             ====================================================-->
<!--===================================================---------------=================================================================-->

<div class="modal fade" id="modal-detail-user" tabindex="-1" role="dialog" aria-labelledby="modal-info-header">                        
	<div class="modal-dialog modal-lg modal-info" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>

		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-info-header"><span class="modal-title icon-register"></span> Detail Customer Data</h4>
			</div>
			<div class="modal-body">
				<div>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tabs-dmain" data-toggle="tab"><span class="fa fa-list-ol"></span> Customer Data</a></li>
						<li><a href="#tabs-dsales" data-toggle="tab"><span class="fa fa-male"></span> Sales Executive</a></li>
						<li><a href="#tabs-dfoto" data-toggle="tab"><span class="fa fa-file-picture-o"></span> Photos</a></li>
						<li><a href="#tabs-dpic" data-toggle="tab"><span class="icon icon-telephone"></span> Contact Person (CP)</a></li>
						<li><a href="#tabs-selling" data-toggle="tab"><span class="icon-receipt"></span> Selling Out</a></li>
					</ul>
					<div class="tab-content tab-content-bordered">
						<div class="tab-pane active" id="tabs-dmain">
							<div class="col-md-6">
								<fieldset class="x-border">
									<legend class="x-border" ><b>Address</b></legend>
									<form id="form-edit-customer"  enctype="multipart/form-data">
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%">Class</td>
											<td style="width:1%"></td>
											<td style="width:79%">
												<input id="d-kelas" name="" class="form-control input" readonly>
												
											</td>
										</tr> 
										<tr>
											<td style="width:20%">Group</td>
											<td style="width:1%"></td>
											<td style="width:79%">
												<input id="d-select-kelompok" name="id_customer_kelompok" class="form-control input" readonly>
												
											</td>
										</tr> 
										<tr>
											<td >Customer Name</td>
											<td  ></td>
											<td ><input id="id" name="id" required class="form-control input" type="hidden" readonly> 
											<input id="d-input-nama" name="nama" required class="form-control input" type="text" readonly></td>
										</tr>
										<tr>
											<td>Address</td>
											<td></td>
											<td><textarea id="d-input-alamat" name="alamat_kirim" required rows="4" class="form-control input-sm" type="text" readonly></textarea></td>
										</tr>
										<tr>
											<td>Billing Address</td>
											<td></td>
											<td><textarea id="d-input-alamat-tagihan" name="alamat_tagihan" required rows="4" class="form-control input-sm" type="text" readonly></textarea></td>
										</tr>
										<tr>
											<td>Province</td>
											<td></td>
											<td>
												<input id="d-select-provinsi" name="id_provinsi"  class="form-control input-sm" type="text" readonly>
											</td>
										</tr>
										<tr>
											<td>City</td>
											<td></td>
											<td>
												<input id="d-select-kota" name="id_kabupatenkota"  class="form-control input-sm" type="text" readonly>
											</td>
										</tr>
										<tr>
											<td>Postal</td>
											<td></td>
											<td><input id="d-input-kodepos" name="kode_pos" required class="form-control input" type="text" readonly></td>
										</tr>
										<tr>
											<td>Geo Location</td>
											<td></td>
											<td><div class="input-group">
													<span class="input-group-addon">Latitude</span>
													<input id="d-input-lat" name="latitude" class="form-control input" type="text" readonly>
												</div></td>
										</tr>
										<tr>
											<td> </td>
											<td></td>
											<td><div class="input-group">
												<span class="input-group-addon">Longitude</span>
												<input id="d-input-long" name="longitude"class="form-control input" type="text" readonly>
												</div></td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div class="col-md-6">
								<fieldset class="x-border">
									<legend class="x-border"><b>Detail</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>NPWP</td>
											<td style="width:1%"></td>
											<td style="width:79%"><input id="d-input-npwp" name="npwp" required class="form-control input" type="text" readonly></td>
										</tr>
										<tr>
											<td>Phone</td>
											<td></td>
											<td><input id="d-input-telpon" name="telepon" required class="form-control input" type="text" readonly></td>
										</tr>
										<tr>
											<td>Fax</td>
											<td></td>
											<td><input id="d-input-fax" name="faks" required class="form-control input" type="text" readonly></td>
										</tr>
										<tr>
											<td >Email</td>
											<td></td>
											<td><input id="d-input-email" name="email"  required class="form-control" type="email" readonly></td>
										</tr>
										<tr>
											<td >Website</td>
											<td></td>
											<td><input id="d-input-web"  name="website" class="form-control" type="text" readonly></td>
										</tr>
										<tr>
											<td>Description</td>
											<td></td>
											<td><textarea id="d-input-deskripsi" name="deskripsi" rows="4" class="form-control input-sm" readonly></textarea></td>
										</tr>
										
									</table>
								</fieldset>
							</div>

						</div>
						<div class="tab-pane" id="tabs-dsales">
							<div class="col-md-8">
								<fieldset class="x-border">
									<legend class="x-border"><b>Detail</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>Region</td>
											<td style="width:1%"></td>
											<td style="width:79%">
												<input  id="d-select-region" name="" class="form-control input" readonly>
											</td>
										</tr>
										<tr>
											<td>Branch</td>
											<td></td>
											<td>
												<input   required id="d-select-branch" name=""  class="form-control input" readonly>
											</td>
										</tr>
										<tr>
											<td>Area</td>
											<td></td>
											<td>
												<input  required id="d-select-area" name="id_area"  class="form-control input" readonly>
										</tr>
										<tr>
											<td>Sales Executive</td>
											<td></td>
											<td>
												<input   required id="d-select-sales" name="id_sales" class="form-control input" readonly>
											</td>
										</tr>
										
									</table>
								</fieldset>
							</div>
						</div>
						<div class="tab-pane" id="tabs-dfoto">
							<div class="col-md6">
								<fieldset class="x-border">
									<legend class="x-border"><b>Uploaded On Server</b></legend>
									<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>Photo 1</td>
											<td style="width:1%"></td>
											<td style="width:79%"><img id="dp1" alt="-" width="100" height="100" /></td>
										</tr>
										<tr>
											<td>Photo 2</td>
											<td></td>
											<td><img id="dp2" alt="-" width="100" height="100" /></td>
										</tr>
										<tr>
											<td>Photo 3</td>
											<td></td>
											<td><img id="dp3" alt="-" width="100" height="100" /></td>
										</tr>
									</table>
								</fieldset>
							</div>
						</div>
						<div class="tab-pane" id="tabs-dpic">
							<div class="col-md-12">
								<fieldset class="x-border">
									<legend class="x-border"><b>CP Data</b></legend>
									<div id="cp_detail" class="col-md-12"></div>
									<!--<table style="width:100%;border-collapse: separate;border-spacing: 0 5px;" cellspacing="0"> 
										<tr>
											<td style="width:20%" class>Name</td>
											<td style="width:1%"></td>
											<td style="width:79%"> <input required id="d-input-pic-nama" name="pic_nama" class="input form-control" type="text" title="Name"  readonly></td>
										</tr>
										<tr>
											<td>Title</td>
											<td></td>
											<td><input id="d-input-pic-jabatan" name="pic_jabatan" class="input form-control" type="text" title="Title" readonly></td>
										</tr>
										<tr>
											<td>Phone Number</td>
											<td></td>
											<td><input id="d-input-pic-hp" name="pic_hp" required class="input form-control" type="text" title="Phone" readonly></td>
										</tr>
										<tr>
											<td>Email</td>
											<td></td>
											<td><input id="d-input-pic-email" name="pic_email" class="input form-control" type="text" title="email" readonly></td>
										</tr>
										<tr>
											<td>Photo</td>
											<td></td>
											<td><img id="cpfoto" alt="No Preview" width="100" height="100" /></td>
										</tr>
									</table>-->
									</form>
								</fieldset>
							</div>
							
						</div>
						<div class="tab-pane" id="tabs-selling">
							<div class="col-md-6">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>            
</div>