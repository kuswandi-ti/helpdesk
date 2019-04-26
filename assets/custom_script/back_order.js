$(document).on('click', '#bo_detail',function(){
	var id_po = $(this).closest('tr').find('#id_po').val();
	//alert(id_po);
	window.open('penjualan/back_order/bo_detail/?last_id='+id_po,'_self');
})


/* 
$(document).on('click', '#showdetailnya',function(){
	var id_po = $(this).closest('tr').find('#idpo').val();
	//alert(id_po);
	$.ajax({
		url:'penjualan/approvalpo/loadDetail/'+id_po,
		type:'get',
		success:function(data){
			$("#showdetail").html(data);
			}
	});	
	
	//pop header detail + total
	$.ajax({
		url:'penjualan/sales_order/populateDetail/'+id_po,
		method:'get',
		datatype:'json',
		success:function(data){
			data = JSON.parse(data);
			$("#idso").html(data[0]['no_so']);
			
			$("#nopo").val(data[0]['no_po']);
			$("#namacustomer").val(data[0]['nama']);
			$("#alamat").val(data[0]['alamat']);
			$("#namasales").val(data[0]['sales']);
			$('#btnpreview').attr('data-preview-image',data[0]['bukti_gambar']);
			$("#tipebayar").val(data[0]['tipe_bayar']);
			$("#jangkawaktu").val(data[0]['jangka_waktu']);
			$("#subtotalx").val(data[0]['subtotal']);
			$("#subtotalx").formatCurrency({symbol:'',roundToDecimalPlace:0});
			$("#diskonpersen").val(data[0]['diskon_persen']);
			$("#diskonrp").val(data[0]['diskon_rupiah']);
			$("#diskonrp").formatCurrency({symbol:'',roundToDecimalPlace:0});
			$("#ppn").val(data[0]['ppn']);
			$("#ppnrp").val(parseInt(data[0]['ppn'])/100*parseInt(data[0]['subtotal']));
			$("#ppnrp").formatCurrency({symbol:'',roundToDecimalPlace:0});
			$("#total").val(data[0]['total']);
			$("#total").formatCurrency({symbol:'',roundToDecimalPlace:0});
		}
	});
	$('#detailnya').modal('show');
	
});
 */
$(document).ready(function() {
	$("#menu_bo").addClass("active");
		   $('#table_data').DataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "penjualan/back_order/populateSO/",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[2, 'desc']]/* ,
			"aoColumnDefs":[
			   { aTargets: [ 1 ], bSortable: true },
			   { aTargets: [ 2 ], bSortable: false },
			   { aTargets: [ 3 ], bSortable: false },
			   { aTargets: [ 4 ], bSortable: false },
			   { aTargets: [ 5 ], bSortable: false },
			   { aTargets: [ 6 ], bSortable: false },
			   { aTargets: [ 7 ], bSortable: false },
			   { aTargets: [ 8 ], bSortable: false }
			] */
		});
	
});

$(document).on('click', '#showhis',function(){
		//var id_po=$("#idpo").val();
		var id_po = $(this).closest('tr').find('#idpo').val();
		$.ajax({
			url:'penjualan/approvalpo/gethistori/'+id_po,
			type:'get',
			success:function(data)
			{
				//alert(data);
				$('#kontenhistori').html(data);
				$('#listhistori').modal('show');
			}
		});
	});