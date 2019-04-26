$('#menu_pembelian_approve_po').addClass('active');

$(document).ready(function() {
	
	$('#table_data_approve').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/approve_po/get_data",
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
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 11
		],
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 3, 4, 7, 8, 9, 11] },
			{ "className": "text-right", "targets": [10] },
			{ "width": "5%", "targets": [0] },  // No.
			{ "width": "11%", "targets": [11] } // Action
		]
	});
	
	$("body").on("click", ".histori", function() {
		var id_po = this.id_po;
		var id_po = $('#'+id_po).val();
		$('#modal-histori').modal('show');
		$('.modal-body').html(id_po);
    });
	
	$("#submit_approve").click(function() {
		var v_id_header = $("#txtidtransaksi_hdr").val();
		var v_oleh = $("#txtusersession").val();
		var v_status = $("input[name=rdostatus]:checked").val();
		var v_keterangan_status = $("#txtketerangan_input").val().replace(/\r\n|\r|\n/g,"<br />");
		if (v_status == 3) {
			v_status_text = 'Approve';
		} else if (v_status == 4) {
			v_status_text = 'Revisi';
		} else if (v_status == 5) {
			v_status_text = 'Reject';
		}
		var v_tahun = new Date().getFullYear();
		var v_bulan = ((new Date().getMonth() + 1) < 10 ? '0' : '') + (new Date().getMonth() + 1); //new Date().getMonth() + 1;
		var v_tanggal = (new Date().getDate() < 10 ? '0' : '') + new Date().getDate(); //new Date().getDate();
		var v_jam = (new Date().getHours() < 10 ? '0' : '' ) + new Date().getHours(); //new Date().getHours();
		var v_menit = (new Date().getMinutes() < 10 ? '0' : '') + new Date().getMinutes(); //new Date().getMinutes();
		var v_detik = (new Date().getSeconds() < 10 ? '0' : '' ) + new Date().getSeconds(); //new Date().getSeconds();
		
		var v_status_1 = "Status : " + v_status_text + "<br />";
		var v_status_2 = "Oleh : " + v_oleh + "<br />";
		var v_status_3 = "Tanggal : " + v_tanggal + "-" + v_bulan + "-" + v_tahun + "<br />";
		var v_status_4 = "Jam : " + v_jam + ":" + v_menit + ":" + v_detik + "<br />";
		var v_status_5 = "----- Pesan : -----<br />" + v_keterangan_status + "<br />-------------------------<br /><br />";
		
		var v_res_keterangan_status = v_status_1.concat (v_status_2, v_status_3, v_status_4, v_status_5);
        var c = confirm("Submit data ? ");
        
        if (c == true) {
            $.ajax({
                url: 'pembelian/approve_po/update_approve',
                type: 'post',
                data: { 
					id_header: v_id_header, 
					status_po: v_status, 
					status_histori: v_res_keterangan_status 
				},
				success: function(data) {
					window.location = "pembelian/approve_po";
				}
            });					
        }
    });
        
});