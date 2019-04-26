$('#menu_pembelian_ap').addClass('active');

$(document).ready(function() {
	
	$('#table_data').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/account_payable/get_data",
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
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 6
		],
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 3, 6] },
			{ "width": "5%", "targets": [0] },  // No.
			{ "width": "10%", "targets": [6] } // Action
		]
	});
	
	$('#check_all').on('click', function() {
		if (this.checked) {
			$('.checkbox').each(function() {
				this.checked = true;
			});
		} else {
			 $('.checkbox').each(function() {
				this.checked = false;
			});
		}
    });
	
	$("body").on("click", ".checkbox", function() {
		if ($('.checkbox:checked').length == $('.checkbox').length) {
			$('#check_all').prop('checked', true);
		} else {
			$('#check_all').prop('checked', false);
		}
    });

	$(".btn-proses-ap").click(function() {		
		if ($(".checkbox:checked").length < 1) { 
			alert('Pilih Nomor Penerimaan minimal 1'); 
			return false;
		} else { 
			var result = confirm("Lanjutkan proses AP ?");
			if (result) {
				var checkValues = $('input[name=selected_id]:checked').map(function() {
                    return $(this).val();
                }).get();
				$.ajax({
					url: 'pembelian/account_payable/update_header',
					data: {
						arr_id: checkValues
					},
					type: 'post',
					success: function(data) {
						if (data == 'done') {
							window.setTimeout(
								function() {
									location.reload(true)
								},
								0
							);
						}
					}
				});
			} else {
				return false;
			}
		}
    });
	
	$('#modal-detail').on('show.bs.modal', function (e) {
		$('.fetched-data').html('');
		var rowid = $(e.relatedTarget).data('id');
		$.ajax({
			type : 'post',
			url : 'pembelian/account_payable/detail_list',
			data :  'rowid='+ rowid,
			success : function(data) {
				$('.fetched-data').html(data);
			}
		});
	});
        
});