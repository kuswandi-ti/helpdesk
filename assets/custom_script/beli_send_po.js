$('#menu_pembelian_send_po').addClass('active');

$(document).ready(function() {
	
	$('#table_data_approve').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/send_po/get_data",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[1, 'asc']], // 0 kolom 1
		"aoColumns": [
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi 1
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi 2
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Bulan 3
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tahun 4
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Supplier 5
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Keterangan 6
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. PR 7
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 8
			{ "bVisible": true, "bSearchable": true, "bSortable": false }, // Keterangan Status 9
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Grand Total 10
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Pengirim 11
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 12
		],
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 3, 4, 7, 8, 9, 11, 12] },
			{ "className": "text-right", "targets": [10] },
			{ "width": "5%", "targets": [0] },  // No.
			{ "width": "11%", "targets": [12] } // Action
		]
	});
	
	$('#txttglkirim').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	$("body").on("click", ".histori", function() {
		var id_header = this.id;
		var id_header = $('#'+id_header).val();
		$('#modal-histori').modal('show');
		$('.modal-body').html(id_header);
    });
	
	$("body").on("click", "#simpan_kirim", function() {
		$.ajax({			
			url: 'pembelian/send_po/simpan_kirim',
			data: {
				id_header: $('#txtidtransaksi_hdr').val(),
				pengirim: $('#txtpengirim').val(),
				keterangan: $('#txtketerangankirim').val(),
				tgl_kirim: $('#txttglkirim').val()
				
			},
			type: 'post',
			success: function (data) {
				if (data == 'done') {
					window.location = "pembelian/send_po";
				}
			},
			error: function() {
				alert("failure");
			}
		});
    });
        
});