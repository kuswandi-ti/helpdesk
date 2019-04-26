/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).on('click', '#showhis',function(){
		//var id_po=$("#idpo").val();
		var id_po = $(this).closest('tr').find('#idpo').val();
		$.ajax({
			url:'penjualan/approvalpo/gethistori/'+id_po,
			type:'get',
			success:function(data)
			{
				//alert(data);
				if(data=='')
					$('.isi').html('No Data');
				$('.isi').html(data);
				$('#listhistori').modal('show');
			}
		});
	});
$(document).ready(function() {
$("#menu_penjualan_po").addClass("active");
       $('#table_data_pending').DataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "penjualan/po_customer/populatePO/pending",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[1, 'asc']] /* // 0 kolom 1
		"aoColumns": [
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No.
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Supplier
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Pengiriman
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // Keterangan
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // No. PR
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // Status
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action
		],
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 4, 6, 7, 8] },
			{ "width": "5%", "targets": [0] },  // No.
			//{ "width": "12%", "targets": [1] }, // No. Transaksi
			//{ "width": "10%", "targets": [2] }, // Tgl. Transaksi
			//{ "width": "21%", "targets": [3] }, // Nama Supplier
            //{ "width": "10%", "targets": [4] }, // Tgl. Pengiriman
			//{ "width": "20%", "targets": [5] }, // Keterangan
            //{ "width": "12%", "targets": [6] }, // No. PR
                        { "width": "12%", "targets": [7] }, // Status
                        { "width": "10%", "targets": [8] } // Action
		]*/
	});
       $('#table_data_draft').DataTable({
		"bProcessing": true,
		 "bSort" : true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "penjualan/po_customer/populatePO/draft",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[4, 'asc']],		// 0 kolom 1
		"order": [[ 4, 'desc' ]],
		"aoColumns": [
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No.
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Supplier
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Pengiriman
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // Keterangan
			{ "bVisible": true, "bSearchable": true, "bSortable": false }, // No. PR
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // Status
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action
		]/*
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 4, 6, 7, 8] },
			{ "width": "5%", "targets": [0] },  // No.
			//{ "width": "12%", "targets": [1] }, // No. Transaksi
			//{ "width": "10%", "targets": [2] }, // Tgl. Transaksi
			//{ "width": "21%", "targets": [3] }, // Nama Supplier
            //{ "width": "10%", "targets": [4] }, // Tgl. Pengiriman
			//{ "width": "20%", "targets": [5] }, // Keterangan
            //{ "width": "12%", "targets": [6] }, // No. PR
                        { "width": "12%", "targets": [7] }, // Status
                        { "width": "10%", "targets": [8] } // Action
		]*/
	});
       $('#table_data_revisi').DataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "penjualan/po_customer/populatePO/revisi",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[1, 'asc']] /* // 0 kolom 1
		"aoColumns": [
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No.
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Supplier
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Pengiriman
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // Keterangan
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // No. PR
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // Status
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action
		],
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 4, 6, 7, 8] },
			{ "width": "5%", "targets": [0] },  // No.
			//{ "width": "12%", "targets": [1] }, // No. Transaksi
			//{ "width": "10%", "targets": [2] }, // Tgl. Transaksi
			//{ "width": "21%", "targets": [3] }, // Nama Supplier
            //{ "width": "10%", "targets": [4] }, // Tgl. Pengiriman
			//{ "width": "20%", "targets": [5] }, // Keterangan
            //{ "width": "12%", "targets": [6] }, // No. PR
                        { "width": "12%", "targets": [7] }, // Status
                        { "width": "10%", "targets": [8] } // Action
		]*/
	});
       $('#table_data_approved').DataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "penjualan/po_customer/populatePO/approved",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[1, 'asc']], // 0 kolom 1
		"aoColumns": [
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No.
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Supplier
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Pengiriman
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // Keterangan
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. PR
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // Status
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Action
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Action
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action
		]
	});
       $('#table_data_rejected').DataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "GET",
		"sAjaxSource": "penjualan/po_customer/populatePO/reject",
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"aaSorting": [[1, 'asc']] /* // 0 kolom 1
		"aoColumns": [
			{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No.
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // No. Transaksi
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Transaksi
			{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Supplier
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // Tgl. Pengiriman
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // Keterangan
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // No. PR
                        { "bVisible": true, "bSearchable": true, "bSortable": true }, // Status
			{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action
		],
		"columnDefs": [
			{ "className": "text-center", "targets": [0, 1, 2, 4, 6, 7, 8] },
			{ "width": "5%", "targets": [0] },  // No.
			//{ "width": "12%", "targets": [1] }, // No. Transaksi
			//{ "width": "10%", "targets": [2] }, // Tgl. Transaksi
			//{ "width": "21%", "targets": [3] }, // Nama Supplier
            //{ "width": "10%", "targets": [4] }, // Tgl. Pengiriman
			//{ "width": "20%", "targets": [5] }, // Keterangan
            //{ "width": "12%", "targets": [6] }, // No. PR
                        { "width": "12%", "targets": [7] }, // Status
                        { "width": "10%", "targets": [8] } // Action
		]*/
	});
	
   
})

function delHeader(id,no_po)
{
    var yes=confirm("Hapus PO :  "+no_po+" ?");
    if(yes)
    {
        $.ajax({
            url:'penjualan/po_customer/delheader',  
            type:'post',
            data:{id:id},
            success:function(data){
				$('#table_data_draft').DataTable().ajax.reload();
               // window.open('penjualan/po_customer/?last_id'+id,'_self')
            }
        });

    }
}
