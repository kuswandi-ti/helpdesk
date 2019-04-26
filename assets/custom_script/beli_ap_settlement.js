$('#menu_pembelian_ap_settlement').addClass('active');

$(document).ready(function() {
	
	$('#table_data').DataTable({
		"bFilter": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "pembelian/ap_settlement/get_data",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[1, 'asc']], // 0 kolom 1
		"aoColumns": [
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. 0
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Invoice Supplier 1
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Invoice Supplier 2
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Total Invoice 3
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Bayar 4
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Total Bayar 5
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 6
		],
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 4, 6] },
			{ "className": "text-right", "targets": [3, 5] },
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

	$(".btn-proses-settlement").click(function() {		
		if ($(".checkbox:checked").length < 1) { 
			alert('Pilih Nomor Invoice Supplier minimal 1'); 
			return false;
		} else { 
			var result = confirm("Lanjutkan proses AP Settlement ?");
			if (result) {
				var checkValues = $('input[name=selected_id]:checked').map(function() {
                    return $(this).val();
                }).get();
				$.ajax({
					url: 'pembelian/ap_settlement/update_header',
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
			url : 'pembelian/ap_settlement/detail_list',
			data :  'rowid='+ rowid,
			success : function(data) {
				$('.fetched-data').html(data);
			}
		});
	});
        
});