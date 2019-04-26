$('#menu_pembelian_approve_pr').addClass('active');

$(document).ready(function() {
	
	$('#table_data_approve').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/approve_pr/get_data",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[1, 'asc']], // 0 kolom 1
		"aoColumns": [
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No 0 
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi 1
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi 2
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Bulan 3
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tahun 4
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Deskripsi 5
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Jenis PR 6
            { "bVisible": true, "bSearchable": true, "bSortable": true }, // Status PR 7
			{ "bVisible": true, "bSearchable": true, "bSortable": false }, // Status Histori 8
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 9
		],
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 3, 4, 6, 7, 9] },
			{ "width": "5%", "targets": [0] }, // No
			{ "width": "11%", "targets": [9] } // Action
		]
	});
	
	$("body").on("click", ".histori", function() {
		var id_header = this.id_header;
		var id_header = $('#'+id_header).val();
		$('#modal-histori').modal('show');
		$('.modal-body').html(id_header);
    });
	
	$("#submit_approve").click(function() {
		var v_id = $("#txtidtransaksi").val();
		var v_oleh = $("#txtusersession").val();
		var v_status = $("input[name=rdostatus]:checked").val();
		var v_status_histori = $("#txtketerangan_input").val().replace(/\r\n|\r|\n/g,"<br />");
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
		var v_status_5 = "----- Pesan : -----<br />" + v_status_histori + "<br />-------------------------<br /><br />";
		
		var v_res_status_histori = v_status_1.concat (v_status_2, v_status_3, v_status_4, v_status_5);
        var c = confirm("Submit data ? ");
        
        if (c == true) {
            $.ajax({
                url: 'pembelian/approve_pr/update_approve',
                type: 'post',
                data: { 
					id_header: v_id, 
					status_pr: v_status, 
					status_histori: v_res_status_histori 
				},
				success: function(data) {
					window.location = "pembelian/approve_pr";
				}
            });					
        }
    });
	
	$("body").on("click", "#table_detail #hapus_detail", function() {
        var parentTr = $(this).closest('tr');
        var id_detail = parentTr.find('#id_detail').html();
        var nama_produk = parentTr.find('#nama_produk').html();
        var del = confirm("Hapus " + nama_produk + " ?");
        
        if (del == true) {
            $.ajax({
                url: 'pembelian/approve_pr/delete_item_detail',
                type: 'post',
                data: { id_detail: id_detail },
                success: function(data) {
                    if(data == 'done') {								
                        location.reload();
                    }	
                    else alert("Item "+nama+ " gagal terhapus");							
                }
            });					
        }
    });
    
	$("body").on("click", "#table_detail #edit_detail", function() {
        var parentTr = $(this).closest('tr');
        var id_detail = parentTr.find('#id_detail').html();
		var id_produk = parentTr.find('#id_produk').html();
        var nama_produk = parentTr.find('#nama_produk').html();
		var isi_kemasan = parentTr.find('#isi_kemasan').html();
        var tgl_diperlukan = parentTr.find('#tgl_diperlukan').html();
        var qty_pr = parentTr.find('#qty_pr').html();
		var qty_approve = parentTr.find('#qty_approve').html();
        $("#txtid_edit").val(id_detail);
        $("#txtnamaproduk_edit").val(nama_produk);
		$("#txtisikemasan_edit").val(isi_kemasan);
        $("#txttgldiperlukan_edit").val(tgl_diperlukan);
        $("#txtqtypr_edit").val(qty_pr);		
		$.ajax({
			url: "pembelian/purchase_request/get_qty_stok_akhir",
			method: "post",
			data: { id_produk: id_produk },
			success: function(data) {
				$("#txtstok_edit").val(data);
				$("#txtstok_edit").formatCurrency({symbol: '', roundToDecimalPlace: 0});
			}
		});
		$("#txtqtyapprove_edit").val(qty_approve);
		$("#txtstok_edit").formatCurrency({symbol: '', roundToDecimalPlace: 0});
		
        $("#modal-edit").modal("show");
    });
	
	$("#simpan_detail").click(function() {				
        var id_detail = $("#txtid_edit").val();
        var tgl_diperlukan = $("#txttgldiperlukan_edit").val();
        var qty_approve = $("#txtqtyapprove_edit").asNumber({ parseType: 'Float' });
        $.ajax({
            url: 'pembelian/approve_pr/update_item_detail',
			data: {
				id_detail: id_detail,
				tgl_diperlukan: tgl_diperlukan,
				qty_approve: qty_approve
			},
            type: 'post',
            success: function(data) {
                if (data == 'done')
                    location.reload();
					$('#modal-edit').modal('hide');
            }
        });
    });
        
});