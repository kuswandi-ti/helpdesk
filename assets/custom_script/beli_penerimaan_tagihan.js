$('#menu_pembelian_tagihan').addClass('active');

$(document).ready(function() {
	
	//$('.b-footer').hide();
	
	detail_list();
	
	$('#table_data').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/penerimaan_tagihan/get_data",
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
	
	$('#txttglinvoicesupplier').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	$('#txttgljatuhtempo_hdr').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	$('#txttgljatuhtempo').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	$('#txttglinvoicesupplier_hdr').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	$("body").on("change", "#cbogr", function() {
		$.ajax({
			url: 'pembelian/penerimaan_tagihan/get_info_gr',
			data: {
				'id_gr': $('#cbogr').val()
			},
			dataType: 'json',
			type: 'post',
			success: function(data) {
				if (data.result == 'done') {
					$('#txttglgr').val(data.tgl_gr);
					$('#txtnopo').val(data.no_po);
					$('#txtjumlahtagihan').val(data.jumlah_tagihan);
				} else {
					$('#txtjumlahtagihan').val(0);
				}
			},
			error: function() {
				alert('failure');
			}
		});
	});
	
	$("#submit_detail").click(function() {
		if ($.trim($('#cbogr').val()) == 0) {
            $('.group-gr-add').addClass('has-error');
			return false;
        } else {
			$('.group-gr-add').removeClass('has-error');
            var v_gr_id = $("#cbogr").val();
        }
		var v_header_id = $("#txtidtransaksi_hdr").val();
		var v_total_tagihan = $("#txtjumlahtagihan").asNumber({parseType: 'Float'});
        $.ajax({
            url: 'pembelian/penerimaan_tagihan/create_detail',
			data: {
				id_header: v_header_id,
				id_gr: v_gr_id,
				total_tagihan: v_total_tagihan
			},
			dataType: 'json',
            type: 'post',
            success: function(data) {
                if (data.result == 'done') {
					window.setTimeout(
						function(){
							location.reload(true)
						},
						0
					);
					detail_list();
					
					$('#txtsubtotal_hdr').val(data.sub_total);
					$("#txtsubtotal_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
					
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
    
    $("body").on("click", "#table_detail #hapus_detail", function() {
		var parentTr = $(this).closest('tr');
        var id_detail = parentTr.find('#id_detail').html();
        var no_gr = parentTr.find('#no_gr').html();
        var del = confirm("Hapus " + no_gr + " ?");
        
        if (del == true) {
            $.ajax({
                url: 'pembelian/penerimaan_tagihan/delete_item_detail',
                type: 'post',
				dataType: 'json',
                data: {
					id_detail: id_detail,
					id_header: $('#txtidtransaksi_hdr').val(),
					disc_persen_hdr: $("#txtdiscpersen_hdr").asNumber({parseType: 'Float'}),
					disc_rupiah_hdr: $("#txtdiscrupiah_hdr").asNumber({parseType: 'Float'}),
					ppn_persen_hdr: $("#txtppnpersen_hdr").asNumber({parseType: 'Float'}),
					materai: $("#txtmaterai_hdr").asNumber({parseType: 'Float'})
				},
				success: function(data) {
					if (data.result == 'done') {
						window.setTimeout(
							function(){
								location.reload(true)
							},
							0
						);
						detail_list();
						
						$('#txtsubtotal_hdr').val(data.sub_total);
						$("#txtsubtotal_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
						
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
    
    function detail_list() {
		var hid = $("#txtidtransaksi_hdr").val();
        $.ajax({
            url   : 'pembelian/penerimaan_tagihan/detail_list/?hid='+hid,
            async : false,
            success : function(data) {
                $('#show_detail').html(data);				
            }
        });
    }

	$("#simpan_header").click(function() {
		if ($.trim($('#txtnoinvoicesupplier_hdr').val()).length == 0) {
            $('.group-noinvoicesupplier-hdr').addClass('has-error');
			return false;
        } else {
			$('.group-noinvoicesupplier-hdr').removeClass('has-error');
            var v_no_invoice_supplier = $("#txtnoinvoicesupplier_hdr").val();
        }
		if ($.trim($('#txttglinvoicesupplier_hdr').val()).length == 0) {
            $('.group-tglinvoicesupplier-hdr').addClass('has-error');
			return false;
        } else {
			$('.group-tglinvoicesupplier-hdr').removeClass('has-error');
            var v_tgl_invoice_supplier = $("#txttglinvoicesupplier_hdr").val();
        }
		if ($.trim($('#txttgljatuhtempo_hdr').val()).length == 0) {
            $('.group-tgljatuhtempo-hdr').addClass('has-error');
			return false;
        } else {
			$('.group-tgljatuhtempo-hdr').removeClass('has-error');
            var v_tgl_jatuh_tempo = $("#txttgljatuhtempo_hdr").val();
        }
		var v_id_header = $("#txtidtransaksi_hdr").val();
		var v_keterangan = $("#txtketerangan_hdr").val();
		var v_sub_total = $("#txtsubtotal_hdr").asNumber({parseType: 'Float'});
		var v_disc_persen = $("#txtdiscpersen_hdr").asNumber({parseType: 'Float'});
		var v_disc_rupiah = $("#txtdiscrupiah_hdr").asNumber({parseType: 'Float'});
		var v_dpp = $("#txtdpp_hdr").asNumber({parseType: 'Float'});
		var v_ppn_persen = $("#txtppnpersen_hdr").asNumber({parseType: 'Float'});
		var v_ppn_rupiah = $("#txtppnrupiah_hdr").asNumber({parseType: 'Float'});
		var v_materai = $("#txtmaterai_hdr").asNumber({parseType: 'Float'});
		var v_grand_total = $("#txtgrandtotal_hdr").asNumber({parseType: 'Float'});
        $.ajax({
            url: 'pembelian/penerimaan_tagihan/update_header',
			data: {
				id_header: v_id_header,
				no_invoice_supplier: v_no_invoice_supplier,
				tgl_invoice_supplier: v_tgl_invoice_supplier,
				tgl_jatuh_tempo: v_tgl_jatuh_tempo,
				keterangan: v_keterangan,
				sub_total: v_sub_total,
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
					window.location = "pembelian/penerimaan_tagihan";
            }
        });
    });
	
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
	
	function hitung_total_header(flag = 'disc_persen') {
        $("#txtsubtotal_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
        var v_sub_total = $("#txtsubtotal_hdr").asNumber({parseType: 'Float'});
		
		v_disc_persen = 0;
		v_disc_rupiah = 0;		
		if (flag == 'disc_persen') {
			var v_disc_persen = $("#txtdiscpersen_hdr").asNumber({parseType: 'Float'});
			var v_disc_rupiah = (v_disc_persen / 100) * v_sub_total;
			$("#txtdiscrupiah_hdr").val(v_disc_rupiah);
		} else if (flag == 'disc_rupiah') {
			var v_disc_rupiah = $("#txtdiscrupiah_hdr").asNumber({parseType: 'Float'});
			var v_disc_persen =  (v_disc_rupiah / v_sub_total) * 100;
			$("#txtdiscpersen_hdr").val(v_disc_persen);
		}
		$("#txtdiscpersen_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 1});
		$("#txtdiscrupiah_hdr").formatCurrency({symbol: '', roundToDecimalPlace: 0});
		
		var v_dpp = v_sub_total - v_disc_rupiah;
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
        
});