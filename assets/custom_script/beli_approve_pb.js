$('#menu_pembelian_approve_pb').addClass('active');

$(document).ready(function() {
	
	//$('.b-footer').hide();
	
	detail_list();
	
	$('#table_data').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/approve_pb/get_data",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[1, 'asc']], // 0 kolom 1
		"aoColumns": [
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi 1
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi 2
			{ "bVisible": false, "bSearchable": false, "bSortable": false }, // ID Supplier 3
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Supplier 4
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Keterangan 5
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Grand Total 6
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 7
		],
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 7] },
			{ "className": "text-right", "targets": [6] },
			{ "width": "5%", "targets": [0] },  // No.
			{ "width": "10%", "targets": [7] } // Action
		]
	});
    
    function detail_list() {
		var hid = $("#txtidtransaksi_hdr").val();
        $.ajax({
            url   : 'pembelian/approve_pb/detail_list/?hid='+hid,
            async : false,
            success : function(data) {
                $('#show_detail').html(data);				
            }
        });
    }

	$("#submit_approve").click(function() {
		var conf = confirm("Lanjutkan proses validasi tagihan ?");
		var id_header = $("#txtidtransaksi_hdr").val();
		if (conf == true) {
			$.ajax({
				url: 'pembelian/approve_pb/update_header',
				data: { id_header: id_header },
				type: 'post',
				success: function(data) {
					if (data == 'done')
						window.location = "pembelian/approve_pb";
				}
			});
		}
    });
        
});