$('#menu_pembelian_po').addClass('active');

$(document).ready(function() {
	
	detail_list();
	
	/* ======================================================================================= */
	/* BEGIN - HEADER */
	/* ======================================================================================= */
	$('#table_data_draft').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/purchase_order/get_data/1",
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
			{ "width": "10%", "targets": [11] } // Action
		]
	});
	
	$('#table_data_pending').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/purchase_order/get_data/2",
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
			{ "width": "10%", "targets": [11] } // Action
		]
	});
	
	$('#table_data_approve').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/purchase_order/get_data/3",
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
			{ "width": "10%", "targets": [11] } // Action
		]
	});
	
	$('#table_data_revisi').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/purchase_order/get_data/4",
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
			{ "width": "10%", "targets": [11] } // Action
		]
	});
	
	$('#table_data_reject').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/purchase_order/get_data/5",
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
			{ "width": "10%", "targets": [11] } // Action
		]
	});
	
	$('#txttglpengiriman').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	$('#txttglpengirimanlist').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	$("body").on("click", ".btn-buat-po-baru", function() {
		$('#txtidpr').val('');
		$('#txtnomorpr').val('');
		$("#cbotipepembayaran").val("1").change(); // COD
		$('#txttop').val('0');
		$('#txtketerangan').val('');
    });
	
	$("body").on("click", ".btn-buat-po-list", function() {
		$('#txtnomorpr').val('');
		$("#cbotipepembayaran").val("1").change(); // COD
		$('#txttop').val('0');
		$('#txtketerangan').val('');
    });
		
	$('#txtnomorpr').autocomplete ({        
        minLength: 2,
        source: function (request, response) {
            var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
            $.ajax({ 
                url: "pembelian/purchase_order/get_pr_aktif",
                datatype: "json",
                type: "get",
                success: function(data) {
                    var result = response($.map(data, function(v, i) {
                        var text = v.no_pr;
                        if ( text && ( !request.term || matcher.test(text) ) ) {
                            return {
                                label: v.no_pr,
                                value: v.id_pr
                            };
                        }
                    }));					
                },
                error: function() {
                    alert("failure");
                }
            }) 
        },
        focus: function(event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);
            $("#txtnomorpr").val(ui.item.label);
            $("#txtidpr").val(ui.item.value);			
        },
        select: function(event, ui) {
            event.preventDefault();
            $(this).val(ui.item.label);
            $("#txtnomorpr").val(ui.item.label);
            $("#txtidpr").val(ui.item.value);
            $("#cbosupplier").focus();
			
			$.ajax({
                url: 'pembelian/purchase_order/supplier_per_pr',
				data: 'id_pr='+ui.item.value,
                type: 'post',
				success: function(data) {
					$('#cbosupplier').html(data);
				}
            });
        }
    });
	
	$('#cbotipepembayaran').change(function() {
		var v_id = $("#cbotipepembayaran option:selected").val();
		if (v_id == '1' || v_id == '3') { // COD / KONSINYASI
			$('#txttop').val('0');
		} else {
			$('#txttop').val('30');
		}
	});
	
	$('#cbotipepembayaranlist').change(function() {
		var v_id = $("#cbotipepembayaranlist option:selected").val();
		if (v_id == '1' || v_id == '3') { // COD / KONSINYASI
			$('#txttoplist').val('0');
		} else {
			$('#txttoplist').val('30');
		}
	});
	
	$('#cbotipepembayaran_hdr').change(function() {
		var v_id = $("#cbotipepembayaran_hdr option:selected").val();
		if (v_id == '1' || v_id == '3') { // COD / KONSINYASI
			$('#txttop_hdr').val('0');
		} else {
			$('#txttop_hdr').val('30');
		}
	});
	
	$("#simpan_header").click(function() {
		if ($.trim($('#txtalamatpengiriman_hdr').val()).length == 0) {
            $('.group-alamatpengiriman').addClass('has-error');
			return false;
        } else {
			$('.group-alamatpengiriman').removeClass('has-error');
            var v_alamat_pengiriman = $("#txtalamatpengiriman_hdr").val();
        }
		if ($.trim($('#txttop_hdr').val()).length == 0) {
            $('.group-top').addClass('has-error');
			return false;
        } else {
			$('.group-top').removeClass('has-error');
            var v_top = $("#txttop_hdr").val();
        }
		if ($.trim($('#txttglpengiriman_hdr').val()).length == 0) {
            $('.group-tglpengiriman').addClass('has-error');
			return false;
        } else {
			$('.group-tglpengiriman').removeClass('has-error');
            var v_tgl_pengiriman = $("#txttglpengiriman_hdr").val();
        }
		var v_bulan = $("#cbobulan_header").val();
		var v_tahun = $("#cbotahun_header").val();
		var v_tipe_pembayaran = $("#cbotipepembayaran_hdr").val();
		var v_keterangan = $("#txtketerangan_hdr").val();
		var v_total_barang = $("#txttotalbarang_hdr").asNumber({parseType: 'Float'});
		var v_disc_persen = $("#txtdiscpersen_hdr").asNumber({parseType: 'Float'});
		var v_disc_rupiah = $("#txtdiscrupiah_hdr").asNumber({parseType: 'Float'});
		var v_dpp = $("#txtdpp_hdr").asNumber({parseType: 'Float'});
		var v_ppn_persen = $("#txtppnpersen_hdr").asNumber({parseType: 'Float'});
		var v_ppn_rupiah = $("#txtppnrupiah_hdr").asNumber({parseType: 'Float'});
		var v_materai = $("#txtmaterai_hdr").asNumber({parseType: 'Float'});
		var v_grand_total = $("#txtgrandtotal_hdr").asNumber({parseType: 'Float'});
		
        $.ajax({
            url: 'pembelian/purchase_order/update_header',
			data: {
				id_header: $("#txtidtransaksi_hdr").val(),
				bulan: v_bulan,
				tahun: v_tahun,
				alamat_pengiriman: v_alamat_pengiriman,
				tipe_pembayaran: v_tipe_pembayaran,
				top: v_top,
				tgl_pengiriman: v_tgl_pengiriman,
				keterangan: v_keterangan,
				total_barang: v_total_barang,
				disc_persen: v_disc_persen,
				disc_rupiah: v_disc_rupiah,
				dpp: v_dpp,
				ppn_persen: v_ppn_persen,
				ppn_rupiah: v_ppn_rupiah,
				materai: v_materai,
				grand_total: v_grand_total				
			},
            type: 'post',
            success: function(data) {
                if (data == 'done')
					window.location = "pembelian/purchase_order";
            },
			error: function() {
                alert("failure");
            }
        });
    });
	/* ======================================================================================= */
	/* END - HEADER */
	/* ======================================================================================= */
	
	/* ======================================================================================= */
	/* BEGIN - DETAIL */
	/* ======================================================================================= */
	$('#txttglpengiriman_hdr').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	function detail_list() {
		var hid = $("#txtidtransaksi_hdr").val();
        $.ajax({
            url   : 'pembelian/purchase_order/detail_list/?hid='+hid,
            async : false,
            success : function(data) {
                $('#show_detail').html(data);
            }
        });
    }
	
	$("#get_data").on("click", function() {		
		var conf = confirm("Lanjutkan proses ?");
		if (conf == true) {
			$.ajax({
				url: 'pembelian/purchase_order/get_data_pr',
				data: {
					id_header: $("#txtidtransaksi_hdr").val(),
					id_pr: $("#txtidpr_hdr").val(),
					id_supplier: $('#txtsupplierid_hdr').val(),
					disc_persen: $("#txtdiscpersen_hdr").asNumber({parseType: 'Float'}),
					disc_rupiah: $("#txtdiscrupiah_hdr").asNumber({parseType: 'Float'}),
					ppn_persen: $("#txtppnpersen_hdr").asNumber({parseType: 'Float'}),
					materai: $("#txtmaterai_hdr").asNumber({parseType: 'Float'})
				},
				dataType: 'json',
				type: 'post',
				success: function(data) {
					if (data.result == 'done') {
						detail_list();
						
						$('#modal-edit').modal('hide');
						
						$('#txttotalbarang_hdr').val(data.total_barang);
						$("#txttotalbarang_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtdiscpersen_hdr').val(data.disc_persen);
						$("#txtdiscpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 1});
						
						$('#txtdiscrupiah_hdr').val(data.disc_rupiah);
						$("#txtdiscrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtdpp_hdr').val(data.dpp);
						$("#txtdpp_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtppnpersen_hdr').val(data.ppn_persen);
						$("#txtppnpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtppnrupiah_hdr').val(data.ppn_rupiah);
						$("#txtppnrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtmaterai_hdr').val(data.materai);
						$("#txtmaterai_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtgrandtotal_hdr').val(data.grand_total);
						$("#txtgrandtotal_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					}
				}
			});
		}
    });
	
	$("body").on("click", "#table_detail #hapus_detail", function() {
		var parentTr = $(this).closest('tr');
        var v_id_detail = parentTr.find('#id_detail').html();
		var v_id_header = $("#txtidtransaksi_hdr").val();
        var v_nama_produk = parentTr.find('#nama_produk').html();
		var v_disc_persen = $("#txtdiscpersen_hdr").asNumber({parseType: 'Float'});
		var v_disc_rupiah = $("#txtdiscrupiah_hdr").asNumber({parseType: 'Float'});
		var v_ppn_persen = $("#txtppnpersen_hdr").asNumber({parseType: 'Float'});
		var v_materai = $("#txtmaterai_hdr").asNumber({parseType: 'Float'});
        var del = confirm("Hapus " + v_nama_produk + " ?");
        
        if (del == true) {
            $.ajax({
                url: 'pembelian/purchase_order/delete_item_detail',
                type: 'post',
				dataType: 'json',
                data: {
					id_detail: v_id_detail,
					id_header: v_id_header,
					disc_persen: v_disc_persen,
					disc_rupiah: v_disc_rupiah,
					ppn_persen: v_ppn_persen,
					materai: v_materai
				},
				success: function(data) {
					if (data.result == 'done') {
						detail_list();
						
						$('#txttotalbarang_hdr').val('data.total_invoice');
						$("#txttotalbarang_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtdiscpersen_hdr').val(data.disc_persen);
						$("#txtdiscpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 1});
						
						$('#txtdiscrupiah_hdr').val(data.disc_rupiah);
						$("#txtdiscrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtdpp_hdr').val(data.dpp);
						$("#txtdpp_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtppnpersen_hdr').val(data.ppn_persen);
						$("#txtppnpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtppnrupiah_hdr').val(data.ppn_rupiah);
						$("#txtppnrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtmaterai_hdr').val(data.materai);
						$("#txtmaterai_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
						$('#txtgrandtotal_hdr').val(data.grand_total);
						$("#txtgrandtotal_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					}
				}
            });					
        }
    });
    
	$("body").on("click", "#table_detail #edit_detail", function() {
        var parentTr = $(this).closest('tr');
        var id_detail = parentTr.find('#id_detail').html();
		var id_pr = $("#txtidpr_hdr").val();
		var id_produk = parentTr.find('#id_produk').html();
        var nama_produk = parentTr.find('#nama_produk').html();
		var tgl_pengiriman = parentTr.find('#tgl_pengiriman').html();
        var qty_po = parentTr.find('#qty_po').html();
		var harga_satuan = parentTr.find('#harga_satuan').html();
		var total = parentTr.find('#total').html();
		var disc_persen = parentTr.find('#disc_persen').html();
		var disc_rupiah = parentTr.find('#disc_rupiah').html();
		var netto = parentTr.find('#netto').html();
        $("#txtid_edit").val(id_detail);
		$("#txtidproduk_edit").val(id_produk);
        $("#txtnamaproduk_edit").val(nama_produk);
        $("#txtqtypo_edit").val(qty_po); $("#txtqtypo_edit_hidden").val(qty_po);
		$("#txthargasatuan_edit").val(harga_satuan); $("#txthargasatuan_edit_hidden").val(harga_satuan);
		$("#txttotal_edit").val(harga_satuan); $("#txttotal_edit_hidden").val(harga_satuan);
		$("#txtdiscpersen_edit").val(disc_persen); $("#txtdiscpersen_edit_hidden").val(disc_persen);
		$("#txtdiscrupiah_edit").val(disc_rupiah); $("#txtdiscrupiah_edit_hidden").val(disc_rupiah);
		$("#txtnetto_edit").val(netto); $("#txtnetto_edit_hidden").val(netto);
        $("#modal-edit").modal("show");
    });
	
	$("body").on("click", ".histori", function() {
		var id_po = this.id;
		var id_po = $('#'+id_po).val();
		$('#modal-histori').modal('show');
		$('.modal-body-histori').html(id_po);
    });
	
	$("#simpan_detail").click(function() {
		if ($.trim($('#txtnamaproduk_edit').val()).length == 0) {
            $('.group-namaproduk-edit').addClass('has-error');
			return false;
        } else {
			$('.group-namaproduk-edit').removeClass('has-error');
            var v_id = $("#txtid_edit").val();
        }
		if ($.trim($('#txtqtypo_edit').val()).length == 0) {
            $('.group-qtypo-edit').addClass('has-error');
			return false;
        } else {
			$('.group-qtypo-edit').removeClass('has-error');
            var v_qty_po = $("#txtqtypo_edit").asNumber({parseType: 'Float'});
        }
		if ($.trim($('#txthargasatuan_edit').val()).length == 0) {
            $('.group-hargasatuan-edit').addClass('has-error');
			return false;
        } else {
			$('.group-hargasatuan-edit').removeClass('has-error');
            var v_harga_satuan = $("#txthargasatuan_edit").asNumber({parseType: 'Float'});
        }
		if ($.trim($('#txttotal_edit').val()).length == 0) {
            $('.group-total-edit').addClass('has-error');
			return false;
        } else {
			$('.group-total-edit').removeClass('has-error');
            var v_total = $("#txttotal_edit").asNumber({parseType: 'Float'});
        }
		if ($.trim($('#txtdiscpersen_edit').val()).length == 0) {
            $('.group-discpersen-edit').addClass('has-error');
			return false;
        } else {
			$('.group-discpersen-edit').removeClass('has-error');
            var v_disc_persen = $("#txtdiscpersen_edit").asNumber({parseType: 'Float'});
        }
		if ($.trim($('#txtdiscrupiah_edit').val()).length == 0) {
            $('.group-discrupiah-edit').addClass('has-error');
			return false;
        } else {
			$('.group-discrupiah-edit').removeClass('has-error');
            var v_disc_rupiah = $("#txtdiscrupiah_edit").asNumber({parseType: 'Float'});
        }
		if ($.trim($('#txtnetto_edit').val()).length == 0) {
            $('.group-netto-edit').addClass('has-error');
			return false;
        } else {
			$('.group-netto-edit').removeClass('has-error');
            var v_netto = $("#txtnetto_edit").asNumber({parseType: 'Float'});
        }
		var v_id_header = $("#txtidtransaksi_hdr").val();
		var v_id_pr = $("#txtidpr_hdr").val();
		var v_id_produk = $("#txtidproduk_edit").val();
		var v_qty_po_hidden = $("#txtqtypo_edit_hidden").asNumber({parseType: 'Float'});
		var v_hargasatuan_hidden = $("#txthargasatuan_edit_hidden").asNumber({parseType: 'Float'});
		var v_total_hidden = $("#txttotal_edit_hidden").asNumber({parseType: 'Float'});
		var v_discpersen_hidden = $("#txtdiscpersen_edit_hidden").asNumber({parseType: 'Float'});
		var v_discrupiah_hidden = $("#txtdiscrupiah_edit_hidden").asNumber({parseType: 'Float'});
		var v_netto_hidden = $("#txtnetto_edit_hidden").asNumber({parseType: 'Float'});
        $.ajax({
            url: 'pembelian/purchase_order/update_item_detail',
			data: {
				id_detail: v_id,
				id_header: v_id_header,
				id_pr: v_id_pr,
				id_produk: v_id_produk,
				qty_po: v_qty_po,
				qty_po_hidden: v_qty_po_hidden,
				harga_satuan: v_harga_satuan,
				harga_satuan_hidden: v_hargasatuan_hidden,
				total: v_total,
				total_hidden: v_total_hidden,
				disc_persen: v_disc_persen,
				disc_persen_hidden: v_discpersen_hidden,
				disc_rupiah: v_disc_rupiah,
				disc_rupiah_hidden: v_discrupiah_hidden,
				netto: v_netto,
				netto_hidden: v_netto_hidden,
				disc_persen_hdr: $("#txtdiscpersen_hdr").asNumber({parseType: 'Float'}),
				disc_rupiah_hdr: $("#txtdiscrupiah_hdr").asNumber({parseType: 'Float'}),
				ppn_persen_hdr: $("#txtppnpersen_hdr").asNumber({parseType: 'Float'}),
				materai: $("#txtmaterai_hdr").asNumber({parseType: 'Float'})
			},
			dataType: 'json',
            type: 'post',
            success: function(data) {
                if (data.result == 'done') {
					detail_list();
					
					$('#modal-edit').modal('hide');
					
					$('#txttotalbarang_hdr').val(data.total_barang);
					$("#txttotalbarang_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtdiscpersen_hdr').val(data.disc_persen);
					$("#txtdiscpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 1});
					
					$('#txtdiscrupiah_hdr').val(data.disc_rupiah);
					$("#txtdiscrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtdpp_hdr').val(data.dpp);
					$("#txtdpp_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtppnpersen_hdr').val(data.ppn_persen);
					$("#txtppnpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtppnrupiah_hdr').val(data.ppn_rupiah);
					$("#txtppnrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtmaterai_hdr').val(data.materai);
					$("#txtmaterai_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtgrandtotal_hdr').val(data.grand_total);
					$("#txtgrandtotal_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
				}
			}
        });
    });
	
	function hitung_total_header(flag = 'disc_persen') {
        $("#txttotalbarang_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
        var v_total_barang = $("#txttotalbarang_hdr").asNumber({parseType: 'Float'});
		
		v_disc_persen = 0;
		v_disc_rupiah = 0;		
		if (flag == 'disc_persen') {
			var v_disc_persen = $("#txtdiscpersen_hdr").asNumber({parseType: 'Float'});
			var v_disc_rupiah = (v_disc_persen / 100) * v_total_barang;
			$("#txtdiscrupiah_hdr").val(v_disc_rupiah);
		} else if (flag == 'disc_rupiah') {
			var v_disc_rupiah = $("#txtdiscrupiah_hdr").asNumber({parseType: 'Float'});
			var v_disc_persen =  (v_disc_rupiah / v_total_barang) * 100;
			$("#txtdiscpersen_hdr").val(v_disc_persen);
		}
		$("#txtdiscpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 1});
		$("#txtdiscrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
		
		var v_dpp = v_total_barang - v_disc_rupiah;
		$("#txtdpp_hdr").val(v_dpp);
		$("#txtdpp_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
        v_dpp = $("#txtdpp_hdr").asNumber({parseType: 'Float'});
		
		v_ppn_rupiah = 0;		
		var v_ppn_persen = $("#txtppnpersen_hdr").asNumber({parseType: 'Float'});
		var v_ppn_rupiah = (v_ppn_persen / 100) * v_dpp;
		$("#txtppnrupiah_hdr").val(v_ppn_rupiah);			
		$("#txtppnrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});		
		
		$("#txtmaterai_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
        var v_materai = $("#txtmaterai_hdr").asNumber({parseType: 'Float'});
		
		/* Grand Total */
		var v_grand_total = v_dpp + v_ppn_rupiah + v_materai;
		$("#txtgrandtotal_hdr").val(v_grand_total);
		$("#txtgrandtotal_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
	}
	
	$("#txtdiscpersen_hdr").on("keyup keypress", function(e){
        hitung_total_header();
    });
	
	$("#txtdiscrupiah_hdr").on("keyup keypress", function(e){
        hitung_total_header('disc_rupiah');
    });
	
	$("#txtppnpersen_hdr").on("keyup keypress", function(e){
        hitung_total_header();
    });
	
	$("#txtmaterai_hdr").on("keyup keypress", function(e){
        hitung_total_header();
    });
	
	function hitung_total_detail(flag = 'persen') {
        $("#txtqtypo_edit").formatCurrency({symbol: '', roundToDecimalPlace: 0});
        var v_jumlah = $("#txtqtypo_edit").asNumber({parseType: 'Float'});

        $("#txthargasatuan_edit").formatCurrency({symbol: '', roundToDecimalPlace: 0});
        var v_harga_satuan = $("#txthargasatuan_edit").asNumber({parseType: 'Float'});
		
        var v_total = v_jumlah * v_harga_satuan;
        $("#txttotal_edit").val(v_total);
        $("#txttotal_edit").formatCurrency({symbol: '', roundToDecimalPlace: 0});
		
		v_disc_persen = 0;
		v_disc_rupiah = 0;
		if (flag == 'persen') {
			var v_disc_persen = $("#txtdiscpersen_edit").asNumber({parseType: 'Float'});
			var v_disc_rupiah = (v_disc_persen / 100) * v_total;
			$("#txtdiscrupiah_edit").val(v_disc_rupiah);
		} else if (flag == 'rupiah') {
			var v_disc_rupiah = $("#txtdiscrupiah_edit").asNumber({parseType: 'Float'});
			var v_disc_persen =  (v_disc_rupiah / v_total) * 100;
			$("#txtdiscpersen_edit").val(v_disc_persen);
		}
		$("#txtdiscpersen_edit").formatCurrency({symbol: '', roundToDecimalPlace: 1});
		$("#txtdiscrupiah_edit").formatCurrency({symbol: '', roundToDecimalPlace: 0});
		
		/* Netto Beli */
		var v_netto = v_total - v_disc_rupiah;
		$("#txtnetto_edit").val(v_netto);
		$("#txtnetto_edit").formatCurrency({symbol: '', roundToDecimalPlace: 0});
	}
	
	$("#txtqtypo_edit").on("keyup keypress", function(e){
        hitung_total_detail();
    });
	
	$("#txthargasatuan_edit").on("keyup keypress", function(e){
        hitung_total_detail();
    });
	
	$("#txtdiscpersen_edit").on("keyup keypress", function(e){
        hitung_total_detail();
    });
	
	$("#txtdiscrp_edit").on("keyup keypress", function(e){
        hitung_total_detail('rupiah');
    });
	/* ======================================================================================= */
	/* END - DETAIL */
	/* ======================================================================================= */	
});

$(document).on("keyup keypress", "#txtqtypo, #txtdiscpersen", function() {
	var v_qty_po = $(this).closest('tr').find('#txtqtypo').val();
	var v_harga_satuan = $(this).closest('tr').find('#txthargasatuan').asNumber({parseType: 'Float'});
	var v_disc_persen = $(this).closest('tr').find('#txtdiscpersen').asNumber({parseType: 'Float'});
	
	if (!$.isNumeric(v_qty_po)) v_qty_po = 0;
	if (!$.isNumeric(v_disc_persen)) v_disc_persen = 0;
	if (!$.isNumeric(v_disc_rupiah)) v_disc_rupiah = 0;
	
	var v_disc_rupiah = ((v_qty_po * v_harga_satuan) * v_disc_persen / 100);
	var v_netto = v_qty_po * v_harga_satuan - v_disc_rupiah;
	
	$(this).closest('tr').find('#txtdiscrupiah').val(v_disc_rupiah);
	$(this).closest('tr').find('#txtdiscrupiah').formatCurrency({symbol: '', roundToDecimalPlace: 0});
	$(this).closest('tr').find('#txtnetto').val(v_netto);
	$(this).closest('tr').find('#txtnetto').formatCurrency({symbol: '', roundToDecimalPlace: 0});
});

$(document).on("change keyup", "#txtdiscrupiah", function() {
	var v_qty_po = $(this).closest('tr').find('#txtqtypo').val();
	var v_harga_satuan = $(this).closest('tr').find('#txthargasatuan').asNumber({parseType: 'Float'});
	var v_disc_persen = $(this).closest('tr').find('#txtdiscpersen').asNumber({parseType: 'Float'});
	var v_disc_rupiah = $(this).closest('tr').find('#txtdiscrupiah').asNumber({parseType: 'Float'});
	
	if (!$.isNumeric(v_qty_po)) v_qty_po = 0;
	if (!$.isNumeric(v_disc_persen)) v_disc_persen = 0;
	if (!$.isNumeric(v_disc_rupiah)) v_disc_rupiah = 0;
	
	var v_disc_persen = v_disc_rupiah * 100 / (v_qty_po * v_harga_satuan);
	var v_netto = v_qty_po * v_harga_satuan - v_disc_rupiah;
	
	$(this).closest('tr').find('#txtdiscpersen').val(v_disc_persen);
	$(this).closest('tr').find('#txtdiscpersen').formatCurrency({symbol: '', roundToDecimalPlace: 1});
	$(this).closest('tr').find('#txtdiscrupiah').formatCurrency({symbol: '', roundToDecimalPlace: 0});
	$(this).closest('tr').find('#txtnetto').val(v_netto);
	$(this).closest('tr').find('#txtnetto').formatCurrency({symbol: '', roundToDecimalPlace: 0});
});

$(document).on("change", "#checkbox", function() {
	var v_check = $(this).is(':checked');
	var v_id_header = $('#txtidtransaksi_hdr').val();
	var v_id_pr = $(this).closest('tr').find('#txtprid').val();
	var v_id_produk = $(this).closest('tr').find('#txtprodukid').val();
	var v_qty_po = $(this).closest('tr').find('#txtqtypo').val();
	var v_harga_satuan = $(this).closest('tr').find('#txthargasatuan').asNumber({parseType: 'Float'});
	var v_total = v_qty_po * v_harga_satuan;
	var v_disc_persen = $(this).closest('tr').find('#txtdiscpersen').asNumber({parseType: 'Float'});
	var v_disc_rupiah = $(this).closest('tr').find('#txtdiscrupiah').asNumber({parseType: 'Float'});
	var v_netto = v_total - v_disc_rupiah;
	
	if (v_check) { /* Insert */
		$.ajax({
			url: "pembelian/purchase_order/create_detail_list",
			data: {
				id_header: v_id_header,
				id_pr: v_id_pr,
				id_produk: v_id_produk,
				qty_po: v_qty_po,
				harga_satuan: v_harga_satuan,
				total: v_total,
				disc_persen: v_disc_persen,
				disc_rupiah: v_disc_rupiah,
				netto: v_netto,
				disc_persen_hdr: $("#txtdiscpersen_hdr").asNumber({parseType: 'Float'}),
				disc_rupiah_hdr: $("#txtdiscrupiah_hdr").asNumber({parseType: 'Float'}),
				ppn_persen_hdr: $("#txtppnpersen_hdr").asNumber({parseType: 'Float'}),
				materai: $("#txtmaterai_hdr").asNumber({parseType: 'Float'})
			},
			type: "post",
			dataType: "json",
			async: 'false',
			success: function(data) {
				if (data.result == 'done') {
					$('#txttotalbarang_hdr').val(data.total_barang);
					$("#txttotalbarang_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtdiscpersen_hdr').val(data.disc_persen);
					$("#txtdiscpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 1});
					
					$('#txtdiscrupiah_hdr').val(data.disc_rupiah);
					$("#txtdiscrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtdpp_hdr').val(data.dpp);
					$("#txtdpp_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtppnpersen_hdr').val(data.ppn_persen);
					$("#txtppnpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtppnrupiah_hdr').val(data.ppn_rupiah);
					$("#txtppnrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtmaterai_hdr').val(data.materai);
					$("#txtmaterai_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtgrandtotal_hdr').val(data.grand_total);
					$("#txtgrandtotal_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
				}
			}
		});
		
	} else { /* Delete */
		$.ajax({
			url: "pembelian/purchase_order/delete_item_detail_list",
			data: {
				id_header: v_id_header,
				id_pr: v_id_pr,
				id_produk: v_id_produk,
				qty_po: v_qty_po,
				disc_persen: v_disc_persen,
				disc_rupiah: v_disc_rupiah,
				netto: v_netto,
				disc_persen_hdr: $("#txtdiscpersen_hdr").asNumber({parseType: 'Float'}),
				disc_rupiah_hdr: $("#txtdiscrupiah_hdr").asNumber({parseType: 'Float'}),
				ppn_persen_hdr: $("#txtppnpersen_hdr").asNumber({parseType: 'Float'}),
				materai: $("#txtmaterai_hdr").asNumber({parseType: 'Float'})
			},
			type: "post",
			dataType: "json",
			async: 'false',
			success: function(data) {
				if (data.result == 'done') {
					$('#txttotalbarang_hdr').val(data.total_barang);
					$("#txttotalbarang_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtdiscpersen_hdr').val(data.disc_persen);
					$("#txtdiscpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 1});
					
					$('#txtdiscrupiah_hdr').val(data.disc_rupiah);
					$("#txtdiscrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtdpp_hdr').val(data.dpp);
					$("#txtdpp_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtppnpersen_hdr').val(data.ppn_persen);
					$("#txtppnpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtppnrupiah_hdr').val(data.ppn_rupiah);
					$("#txtppnrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtmaterai_hdr').val(data.materai);
					$("#txtmaterai_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
					$('#txtgrandtotal_hdr').val(data.grand_total);
					$("#txtgrandtotal_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
				}
			}
		});
	}
});