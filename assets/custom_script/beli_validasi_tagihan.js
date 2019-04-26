$('#menu_pembelian_validasi_tagihan').addClass('active');

$(document).ready(function() {
	
	//$('.b-footer').hide();
	
	detail_list();
	
	$('#table_data').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/validasi_tagihan/get_data",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[1, 'asc']], // 0 kolom 1
		"aoColumns": [
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi 1
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi 2
			{ "bVisible": false, "bSearchable": false, "bSortable": false }, // ID Supplier 3
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Supplier 4
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Invoice Supplier 5
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl Invoice Supplier 6
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl Terima 7
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Keterangan 8
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Validasi 9
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Grand Total 10
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 11
		],
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 3, 5, 9, 11] },
			{ "className": "text-right", "targets": [6, 7, 10] },
			{ "width": "5%", "targets": [0] },  // No.
			{ "width": "10%", "targets": [11] } // Action
		]
	});
    
    function detail_list() {
		var hid = $("#txtidtransaksi_hdr").val();
        $.ajax({
            url   : 'pembelian/validasi_tagihan/detail_list/?hid='+hid,
            async : false,
            success : function(data) {
                $('#show_detail').html(data);				
            }
        });
    }

	$("#submit_approve").click(function() {
		var conf = confirm("Lanjutkan proses validasi tagihan ?");
		var v_hid = $("#txtidtransaksi_hdr").val();
		if (conf == true) {
			$.ajax({
				url: 'pembelian/validasi_tagihan/update_header',
				data: { id_header: v_hid },
				type: 'post',
				success: function(data) {
					if (data == 'done')
						window.location = "pembelian/validasi_tagihan";
				}
			});
		}
    });
        
});