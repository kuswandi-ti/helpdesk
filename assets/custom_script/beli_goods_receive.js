$('#menu_pembelian_gr').addClass('active');

$(document).ready(function() {
	
	detail_list();
	
	$('#table_data').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/goods_receive/get_data",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[1, 'asc']], // 0 kolom 1
		"aoColumns": [
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi 1
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi 2
			{ "bVisible": false, "bSearchable": false, "bSortable": false }, // ID Supplier 3
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Supplier 4
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. PO 5
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. SJ Supplier 6
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl Terima 7
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Keterangan 8
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 9
		],
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 3, 5, 6, 7, 9] },
			{ "width": "5%", "targets": [0] },  // No.
			{ "width": "10%", "targets": [9] } // Action
		]
	});
	
	$('#txttglterima').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	$('#txttglterima_hdr').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	$('#txtexpireddate').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	$('#txtexpireddate_edit').datetimepicker({
        format: "DD-MM-YYYY"
    });
	
	$('#cbosupplier').change(function() {
		var v_id_supplier = $("#cbosupplier").val();
		$.ajax({
			url: 'pembelian/goods_receive/get_po_supplier',
			data: { id_supplier: v_id_supplier },
			type: 'post',
			success: function(data) {
				$('#cbopo').html(data);
			}
		});
	});
	
	$("#get_data").on("click", function() { 
		var conf = confirm("Lanjutkan proses ?");
		if (conf == true) {
			$.ajax({
				url: "pembelian/goods_receive/get_data_po/",
				data: {
						id_header: $('#txtidtransaksi_hdr').val(),
						id_po: $('#txtidpo_hdr').val()
				},
				method: "post",
				success: function(data) {				
					detail_list();
				},
				error: function() {
					alert("failure");
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
                url: 'pembelian/goods_receive/delete_item_detail',
                type: 'post',
                data: {
					id_detail: id_detail
				},
				success: function(data) {
					detail_list();
				}
            });					
        }
    });
    
	$("body").on("click", "#table_detail #edit_detail", function() {
		$("#txtid_edit").val('');
		$("#txtpoid_edit").val('');
		$("#txtidproduk_edit").val('');
        $("#txtnamaproduk_edit").val('');
		$("#txtbatchnumber_edit").val('');
		$("#txtexpireddate_edit").val('');
        $("#txtqty_gr_edit").val('');
		$("#txtqty_gr_edit_hidden").val('');
		$('#cbolokasi_edit').val('');
		$("#cbolokasi_edit").selectpicker('refresh');
		
        var parentTr = $(this).closest('tr');
        var id_detail = parentTr.find('#id_detail').html();
		var id_po = $("#txtidpo_hdr").val();
		var id_produk = parentTr.find('#id_produk').html();
		var nama_produk = parentTr.find('#nama_produk').html();
		var batch_number = parentTr.find('#batch_number').html();
		var expired_date = parentTr.find('#expired_date').html();
        var qty_gr = parentTr.find('#qty_gr').html();
		var id_lokasi = parentTr.find('#id_lokasi').html();
        
		$("#txtid_edit").val(id_detail);
		$("#txtpoid_edit").val(id_po);
		$("#txtidproduk_edit").val(id_produk);
        $("#txtnamaproduk_edit").val(nama_produk);
		$("#txtbatchnumber_edit").val(batch_number);
		$("#txtexpireddate_edit").val(expired_date);
        $("#txtqty_gr_edit").val(qty_gr);
		$("#txtqty_gr_edit_hidden").val(qty_gr);		

		var opt = '<option selected hidden>Pilih Lokasi</option>';
		$.ajax({
			url: 'pembelian/goods_receive/get_lokasi/'+id_produk,
			dataType: 'json',
			type: 'get',
			async: false,
			success: function(json) {
				$.each(json, function(i, obj) {
					opt += "<option value='"+obj.id_lokasi+"'>"+obj.kode_lokasi+"</option>";
				});
				$("#cbolokasi_edit").html(opt);
				$("#cbolokasi_edit").selectpicker('refresh');
			}
		});
		
		$('#cbolokasi_edit').val(id_lokasi);
		$("#cbolokasi_edit").selectpicker('refresh');
		
        $("#modal-edit").modal("show");
    });
    
    $("#simpan_detail").click(function() {
		if ($.trim($('#txtbatchnumber_edit').val()).length == 0) {
            $('.group-batchnumber_edit').addClass('has-error');
			return false;
        } else {
			$('.group-batchnumber_edit').removeClass('has-error');
            var v_batch_number = $("#txtbatchnumber_edit").val();
        }
		if ($.trim($('#txtexpireddate_edit').val()).length == 0) {
            $('.group-expireddate_edit').addClass('has-error');
			return false;
        } else {
			$('.group-expireddate_edit').removeClass('has-error');
            var v_expired_date = $("#txtexpireddate_edit").val();
        }
		if ($.trim($('#txtqty_gr_edit').val()).length == 0) {
            $('.group-qty_gr-edit').addClass('has-error');
			return false;
        } else {
			$('.group-qty_gr-edit').removeClass('has-error');
            var v_qty_gr = $("#txtqty_gr_edit").val();
        }
		var v_id_detail = $("#txtid_edit").val();
		var v_id_po = $("#txtpoid_edit").val();
		var v_id_produk = $("#txtidproduk_edit").val();
		var v_qty_gr_hidden = $("#txtqty_gr_edit_hidden").val();
		
        $.ajax({
            url: 'pembelian/goods_receive/update_item_detail',
			data: {
				id_detail: v_id_detail,
				id_po: v_id_po,
				id_produk: v_id_produk,
				batch_number: v_batch_number,
				expired_date: v_expired_date,
				qty_gr: v_qty_gr,
				qty_gr_hidden: v_qty_gr_hidden,
				id_lokasi: $("#cbolokasi_edit").val()
			},
            type: 'post',
            success: function(data) {
                if (data == 'done')
					detail_list();
					$('#modal-edit').modal('hide');
			}
        });
    });
    
    function detail_list() {
		var hid = $("#txtidtransaksi_hdr").val();
        $.ajax({
            url   : 'pembelian/goods_receive/detail_list/?hid='+hid,
            async : false,
            success : function(data) {
                $('#show_detail').html(data);
            }
        });
    }

	$("#simpan_header").click(function() {
		if ($.trim($('#txttglterima_hdr').val()).length == 0) {
            $('.group-tglterima').addClass('has-error');
			return false;
        } else {
			$('.group-tglterima').removeClass('has-error');
            var v_tgl_terima = $("#txttglterima_hdr").val();
        }
		if ($.trim($('#txtnosjsupplier_hdr').val()).length == 0) {
            $('.group-nosjsupplier').addClass('has-error');
			return false;
        } else {
			$('.group-nosjsupplier').removeClass('has-error');
            var v_no_sj_supplier = $("#txtnosjsupplier_hdr").val();
        }
		var v_id_header = $("#txtidtransaksi_hdr").val();
		var v_keterangan = $("#txtketerangan_hdr").val();
        $.ajax({
            url: 'pembelian/goods_receive/update_header',
			data: {
				id_header: v_id_header,
				no_sj_supplier: v_no_sj_supplier,
				tgl_terima: v_tgl_terima,
				keterangan: v_keterangan
			},
            type: 'post',
            success: function(data) {
                if (data == 'done')
					window.location = "pembelian/goods_receive";
            }
        });
    });        
});

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();			
		reader.onload = function (e) {
			$('#image')
				.attr('src', e.target.result)
				.width(250)
				.height(400);
		};
		reader.readAsDataURL(input.files[0]);
	}
}