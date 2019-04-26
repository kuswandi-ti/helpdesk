$('#menu_pembelian_pr').addClass('active');

$(document).ready(function() {
	
	detail_list();
	
	$('#table_data_draft').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/purchase_request/get_data/1",
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
	
	$('#table_data_pending').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/purchase_request/get_data/2",
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
	
	$('#table_data_approve').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/purchase_request/get_data/3",
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
	
	$('#table_data_revisi').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/purchase_request/get_data/4",
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
	
	$('#table_data_reject').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/purchase_request/get_data/5",
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
	
	$('#txttgldiperlukan').datetimepicker({
        format: "DD-MM-YYYY"
    });
	    
    $('#txttgldiperlukan_edit').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	function detail_list() {
		var hid = $("#txtidtransaksi").val();
        $.ajax({
            url   : 'pembelian/purchase_request/detail_list/?hid='+hid,
            async : false,
            success : function(data) {
                $('#show_detail').html(data);
            }
        });
    }
    
    $('#txtnamaproduk').autocomplete ({
        minLength: 2,
        source: function (request, response) {
            var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
            $.ajax({
				url: "pembelian/purchase_request/get_produk_aktif",
                datatype: "json",
                type: "get",
                success: function(data) {
                    var result = response($.map(data, function(v, i) {
                        var text = v.nama_produk;
                        if ( text && ( !request.term || matcher.test(text) ) ) {
                            return {
                                label: v.nama_produk,
                                value: v.id_produk,
								info: v.info_kemasan
                            };
                        }
                    }))
                },
                error: function() {
                    alert("failure");
                }
            }) 
        },
        focus: function(event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);
            $("#txtnamaproduk").val(ui.item.label);
            $("#txtprodukid").val(ui.item.value);
			$("#txtisikemasan").val(ui.item.info);
        },
        select: function(event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);
            $("#txtnamaproduk").val(ui.item.label);
            $("#txtprodukid").val(ui.item.value);
			$("#txtisikemasan").val(ui.item.info);
            $("#txtqtypr").focus();
			$.ajax({
				url: "pembelian/purchase_request/get_qty_stok_akhir",
				method: "post",
				data: { produk_id: $("#txtprodukid").val() },
				success: function(data) {
					$("#txtstok").val(data);
					$("#txtstok").formatCurrency({symbol: '', roundToDecimalPlace: 0});
				}
			});
			//$("#txttgldiperlukan").val(moment().format("DD-MM-YYYY"));
        }
    });
    
    $("#submit_detail").on("click", function() {         
        if ($('#txtprodukid').val() == '') {
            alert("Produk harus dipilih !");
            $("#txtnamaproduk").focus();
            return false;
        } else {
            var v_produk_id = $("#txtprodukid").val();
        }
        if ($('#txtqtypr').val() == '') {
            alert("Qty PR harus diisi !");
            $("#txtqtypr").focus();
            return false;
        } else {
            var v_qty_pr = $("#txtqtypr").val();
        }
        if ($('#txttgldiperlukan').val() == '') {
            alert("Tanggal Diperlukan harus diisi !");
            $("#txttgldiperlukan").focus();
            return false;
        } else {
            var v_tgl_diperlukan = $("#txttgldiperlukan").val();
        }
        if(isNaN(v_qty_pr)) v_qty_pr = 0;
		
		/* Untuk mengecek duplikat item produk */
		var is_available = 0;
		$('#table_detail #id_produk').each(function() {
			v_cek_produk_id = $(this).html();
			if (v_produk_id == v_cek_produk_id) {
				is_available++;
			}
		});
		if (is_available > 0) {
			alert("Produk sudah ada!  Pilih produk yang lain.");
			return;
		}
		
		var v_id = $("#txtidtransaksi").val();
		$.ajax({			
			url: "pembelian/purchase_request/create_detail/",
			data: {   
					id_header: v_id,
					tgl_diperlukan: v_tgl_diperlukan,
					id_produk: v_produk_id,
					qty_pr: v_qty_pr
			},
			method: "post",
			success: function(data) {     
				$("#txtprodukid").val('');
				$("#txtnamaproduk").val('');
				$("#txtisikemasan").val('');
				$("#txtstok").val('0');
				$("#txtqtypr").val('0');		
				//$("#txttgldiperlukan").val(moment().format("DD-MM-YYYY"));
				//location.reload();
				detail_list();
			},
			beforeSend: function() {
				//$('#responsecontainer').html("<img src='assets/apps/img/ajax-loader.gif' /><br /><br />");
			},
			error: function() {
				alert("failure");
			}
		});
    });
    
	$("body").on("click", "#table_detail #hapus_detail", function() {
        var parentTr = $(this).closest('tr');
        var id_detail = parentTr.find('#id_detail').html();
        var nama_produk = parentTr.find('#nama_produk').html();
        var del = confirm("Hapus " + nama_produk + " ?");
        
        if (del == true) {
            $.ajax({
                url: 'pembelian/purchase_request/delete_item_detail',
                type: 'post',
                data: {id_detail: id_detail},
                success: function(data) {
                    if(data == 'done') {								
                        detail_list();
                    }	
                    else alert("Item "+nama+ " gagal terhapus");							
                }
            });					
        }
    });
    
	$("body").on("click", "#table_detail #edit_detail", function() {
        var parentTr = $(this).closest('tr');
        var id_detail = parentTr.find('#id_detail').html();
        var nama_produk = parentTr.find('#nama_produk').html();
		var isi_kemasan = parentTr.find('#isi_kemasan').html();
        var tgl_diperlukan = parentTr.find('#tgl_diperlukan').html();
        var qty_pr = parentTr.find('#qty_pr').html();
        $("#txtid_edit").val(id_detail);
        $("#txtnamaproduk_edit").val(nama_produk);
		$("#txtisikemasan_edit").val(isi_kemasan);
        $("#txttgldiperlukan_edit").val(tgl_diperlukan);
        $("#txtqtypr_edit").val(qty_pr);
        $("#modal-edit").modal("show");
    });
    
    $("#simpan_detail").click(function() {				
        var id_detail = $("#txtid_edit").val();
        var tgl_diperlukan = $("#txttgldiperlukan_edit").val();
        var qty_pr = $("#txtqtypr_edit").asNumber({ parseType: 'Float' });
        $.ajax({
            url: 'pembelian/purchase_request/update_item_detail',
			data: {
				id_detail: id_detail,
				tgl_diperlukan: tgl_diperlukan,
				qty_pr: qty_pr
			},
            type: 'post',
            success: function(data) {
                if (data == 'done')
                    detail_list();
					$('#modal-edit').modal('hide');
            }
        });
    });
	
	$("#simpan_header").click(function() {
		var v_id_header = $("#txtidtransaksi").val();
        var v_deskripsi = $("#txtdeskripsi_header").val();
		var v_bulan = $("#cbobulan_header option:selected").val();
		var v_tahun = $("#cbotahun_header option:selected").val();
        $.ajax({
            url: 'pembelian/purchase_request/update_header',
			data: {
				id_header: v_id_header,
				deskripsi: v_deskripsi,
				bulan: v_bulan,
				tahun: v_tahun
			},
            type: 'post',
            success: function(data) {
                if (data == 'done')
					window.location = "pembelian/purchase_request";
            }
        });
    });
	
	$("body").on("click", ".histori", function() {
		var id_header = this.id;
		var id_header = $('#'+id_header).val();
		$('#modal-histori').modal('show');
		$('.modal-body').html(id_header);
    });
        
});