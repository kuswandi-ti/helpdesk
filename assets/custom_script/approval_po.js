$(document).on('click', '#showhis',function(){
		//var id_po=$("#idpo").val();
		var id_po = $(this).closest('tr').find('#idpo').val();
		$.ajax({
			url:'penjualan/approvalpo/gethistori/'+id_po,
			type:'get',
			success:function(data)
			{
				//alert(data);
				if(data=='')
					$('.isi').html('No Data');
				$('.isi').html(data);
				$('#listhistori').modal('show');
			}
		});
	});
$(document).ready(function() {
$("#menu_approval").addClass("active");
       $('#table_data').DataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "penjualan/approvalpo/populatePO",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[4, 'asc']]
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