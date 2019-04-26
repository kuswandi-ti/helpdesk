<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
	.word-wrap {
        word-break: break-all;
    }
    .no-wrap {
        white-space: nowrap;
    }
    .fixed {
        table-layout: fixed;
    }
</style>

<script>
	$(document).ready(function() {
		
		$('#table_data').DataTable({
			"bFilter": true,
			"bProcessing": true,
			"bServerSide": true,
			"sServerMethod": "GET",
			"sAjaxSource": "hr/karyawan/get_data",
			"iDisplayLength": 10,
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"aaSorting": [[2, 'asc']], // 0 kolom 1
			"aoColumns": [
				{ "bVisible": true, "bSearchable": false, "bSortable": false }, // No. 0
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // NIK 1
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Nama Karyawan 2
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Jenis Kelamin 3
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tempat Lahir 4
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Tanggal Lahir 5
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Alamat 6
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Unit Kerja 7
				{ "bVisible": true, "bSearchable": true, "bSortable": true }, // Status 8
				{ "bVisible": true, "bSearchable": false, "bSortable": false } // Action 9
			],
			"columnDefs": [
				{ "className": "text-center", "targets": [0, 1, 3, 4, 7, 8, 9] },
				{ "width": "5%", "targets": [0] },  // No.
				{ "width": "5%", "targets": [8] },  // Status
				{ "width": "10%", "targets": [9] } // Action
			]
		});
		
		// ========================================= ACTIONS - BEGIN ========================================		
		$(".btn-tambah").click(function() {
			$('#modal-primary-header').html('Tambah Data Karyawan Baru');
			reset_input();
		});
		
		$("#cboprovinsi_pokok").on('change',function(){
			var id_provinsi = $(this).val();
			var opt = '<option selected hidden value="0">Pilih Kab./Kota</option>';
			$.ajax({
				url: 'hr/karyawan/get_kabkota/'+id_provinsi,
				dataType: 'json',
				type: 'get',
				success: function(json) {
					$.each(json, function(i, obj) {
						opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
					});
					$("#cbokabkota_pokok").html(opt);
					$("#cbokabkota_pokok").selectpicker('refresh');
				}
			});
		});
		
		$("#cbounitkerja_jabatan").on('change',function(){
			var id_unitkerja = $(this).val();
			var opt = '<option selected hidden value="0">Pilih Jabatan</option>';
			$.ajax({
				url: 'hr/karyawan/get_jabatan/'+id_unitkerja,
				dataType: 'json',
				type: 'get',
				success: function(json) {
					$.each(json, function(i, obj) {
						opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
					});
					$("#cbojabatan_jabatan").html(opt);
					$("#cbojabatan_jabatan").selectpicker('refresh');
				}
			});
		});
		
		$("a[href='#tabs-unit-kerja']").on('show.bs.tab', function(e) {
			list_detail_jabatan();
		});
		$("a[href='#tabs-presensi-cuti']").on('show.bs.tab', function(e) {
			list_detail_presensi_cuti();
		});
		$("a[href='#tabs-gaji']").on('show.bs.tab', function(e) {
			list_detail_gaji();
		});
		$("a[href='#tabs-tunjangan']").on('show.bs.tab', function(e) {
			list_detail_tunjangan();
		});
		$("a[href='#tabs-rekening']").on('show.bs.tab', function(e) {
			list_detail_rekening();
		});
		$("a[href='#tabs-pajak']").on('show.bs.tab', function(e) {
			list_detail_pajak();
		});
		$("a[href='#tabs-bpjs']").on('show.bs.tab', function(e) {
			list_detail_bpjs();
		});
		$("a[href='#tabs-fasilitas']").on('show.bs.tab', function(e) {
			list_detail_fasilitas();
		});
		$("a[href='#tabs-keluarga']").on('show.bs.tab', function(e) {
			list_detail_keluarga();
		});
		$("a[href='#tabs-pendidikan']").on('show.bs.tab', function(e) {
			list_detail_pendidikan();
		});
		$("a[href='#tabs-kompetensi']").on('show.bs.tab', function(e) {
			list_detail_kompetensi();
		});
		$("a[href='#tabs-prestasi']").on('show.bs.tab', function(e) {
			list_detail_prestasi();
		});
		$("a[href='#tabs-pengalaman-kerja']").on('show.bs.tab', function(e) {
			list_detail_pengalaman_kerja();
		});
		$("a[href='#tabs-pelatihan-seminar']").on('show.bs.tab', function(e) {
			list_detail_pelatihan();
		});
		$("a[href='#tabs-sanksi-peringatan']").on('show.bs.tab', function(e) {
			list_detail_sanksi();
		});
		$("a[href='#tabs-kewajiban']").on('show.bs.tab', function(e) {
			list_detail_kewajiban();
		});
		// ========================================= ACTIONS - END ==========================================
		
		// ========================================= INIT - BEGIN ===========================================
		$('#txttanggaldiberikan_fasilitas').datetimepicker({ format: "DD-MM-YYYY" });
		$('#txttanggaldikembalikan_fasilitas').datetimepicker({ format: "DD-MM-YYYY" });
		$('#txttanggaldiberikan_sanksi').datetimepicker({ format: "DD-MM-YYYY" });
		$('#txttmtkerja_jabatan').datetimepicker({ format: "DD-MM-YYYY" });
		$('#txttstkerja_jabatan').datetimepicker({ format: "DD-MM-YYYY" });
		$('#txttanggalsk_jabatan').datetimepicker({ format: "DD-MM-YYYY" });
		$('#txttanggallahir_keluarga').datetimepicker({ format: "DD-MM-YYYY" });
		$('#txttanggallahir_pokok').datetimepicker({ format: "DD-MM-YYYY" });
		// ========================================= INIT - END =============================================
		
		// ========================================= BPJS - BEGIN ===========================================
		$("#submit_detail_bpjs").on("click", function() {
			if ($('#cbobpjs_bpjs').val() == 0) {
				$('.group-nama-bpjs').addClass('has-error');
				return false;
			} else {
				$('.group-nama-bpjs').removeClass('has-error');
			}
			if ($('#cbokelas_bpjs').val() == 0) {
				$('.group-kelas-bpjs').addClass('has-error');
				return false;
			} else {
				$('.group-kelas-bpjs').removeClass('has-error');
			}
			if ($.trim($('#txtjumlahkaryawan_bpjs').val()).length == 0) {
				$('.group-jumlah-bpjs-karyawan').addClass('has-error');
				return false;
			} else {
				$('.group-jumlah-bpjs-karyawan').removeClass('has-error');
			}
			if ($.trim($('#txtjumlahperusahaan_bpjs').val()).length == 0) {
				$('.group-jumlah-bpjs-perusahaan').addClass('has-error');
				return false;
			} else {
				$('.group-jumlah-bpjs-perusahaan').removeClass('has-error');
			}
			
			$.ajax({			
				url: "hr/karyawan/create_detail_bpjs/",
				data: {   
						id_karyawan: $("#txtid_pokok").val(),
						id_bpjs: $("#cbobpjs_bpjs").val(),
						kelas: $("#cbokelas_bpjs").val(),
						jumlah_karyawan: $("#txtjumlahkaryawan_bpjs").val(),
						jumlah_perusahaan: $("#txtjumlahperusahaan_bpjs").val(),
						keterangan: $("#txtketerangan_bpjs").val()
				},
				method: "post",
				success: function(data) {
					$('#cbobpjs_bpjs').val('0'); $("#cbobpjs_bpjs").selectpicker('refresh');
					$('#cbokelas_bpjs').val('0'); $("#cbokelas_bpjs").selectpicker('refresh');
					$("#txtjumlahkaryawan_bpjs").val('0');
					$("#txtjumlahperusahaan_bpjs").val('0');
					$("#txtketerangan_bpjs").val('');
					list_detail_bpjs();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_bpjs #hapus_detail_bpjs", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_bpjs = parentTr.find('#nama_bpjs').html();
			var del = confirm("Hapus BPJS " + nama_bpjs + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_bpjs',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_bpjs();
						}	
						else alert("Item "+nama_bpjs+ " gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= BPJS - END =============================================
		
		// ========================================= FASILITAS - BEGIN ======================================
		$("#submit_detail_fasilitas").on("click", function() {
			if ($('#cbofasilitas_fasilitas').val() == 0) {
				$('.group-nama-fasilitas').addClass('has-error');
				return false;
			} else {
				$('.group-nama-fasilitas').removeClass('has-error');
			}
			if ($.trim($('#txttanggaldiberikan_fasilitas').val()).length == 0) {
				$('.group-tanggaldiberikan-fasilitas').addClass('has-error');
				return false;
			} else {
				$('.group-tanggaldiberikan-fasilitas').removeClass('has-error');
			}
			if ($.trim($('#txttanggaldikembalikan_fasilitas').val()).length == 0) {
				$('.group-tanggaldikembalikan-fasilitas').addClass('has-error');
				return false;
			} else {
				$('.group-tanggaldikembalikan-fasilitas').removeClass('has-error');
			}
			
			$.ajax({			
				url: "hr/karyawan/create_detail_fasilitas/",
				data: {   
						id_karyawan: $("#txtid_pokok").val(),
						id_fasilitas: $("#cbofasilitas_fasilitas").val(),
						tanggal_diberikan: $("#txttanggaldiberikan_fasilitas").val(),
						tanggal_dikembalikan: $("#txttanggaldikembalikan_fasilitas").val(),
						keterangan: $("#txtketerangan_fasilitas").val()
				},
				method: "post",
				success: function(data) {
					$('#cbofasilitas_fasilitas').val('0'); $("#cbofasilitas_fasilitas").selectpicker('refresh');
					//$("#txttanggaldiberikan_fasilitas").val(moment().format('DD-MM-YYYY'));
					$("#txtketerangan_fasilitas").val('');
					list_detail_fasilitas();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_fasilitas #hapus_detail_fasilitas", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_fasilitas = parentTr.find('#nama_fasilitas').html();
			var del = confirm("Hapus Fasilitas " + nama_fasilitas + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_fasilitas',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_fasilitas();
						}	
						else alert("Item "+nama_fasilitas+ " gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= FASILITAS - END ========================================
		
		// ========================================= GAJI - BEGIN ===========================================
		$("#submit_detail_gaji").on("click", function() {
			if ($.trim($('#txttahun_gaji').val()).length == 0) {
				$('.group-tahun-gaji').addClass('has-error');
				return false;
			} else {
				$('.group-tahun-gaji').removeClass('has-error');
			}
			if ($('#cbobulan_gaji').val() == 0) {
				$('.group-bulan-gaji').addClass('has-error');
				return false;
			} else {
				$('.group-bulan-gaji').removeClass('has-error');
			}
			if ($.trim($('#txtgaji_gaji').val()).length == 0) {
				$('.group-gaji-gaji').addClass('has-error');
				return false;
			} else {
				$('.group-gaji-gaji').removeClass('has-error');
			}
			if ($("input[type=file][name=filegaji_gaji]" ).val() == '') {
				alert("File Gaji harap diisi");
				return false;
			}
		
			var form = $('#form-gaji')[0];
			var formData = new FormData(form);
			
			formData.append('txtid_pokok', $("#txtid_pokok").val());
			formData.append('txtnik_pokok', $("#txtnik_pokok").val());
			
			$.ajax({
				type: 'POST',
				url: 'hr/karyawan/create_detail_gaji/',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					//$("#txttahun_gaji").val(moment().format('YYYY'));
					$('#cbobulan_gaji').val('0'); $("#cbobulan_gaji").selectpicker('refresh');
					$("#txtgaji_gaji").val('0');
					$("#txtketerangan_gaji").val('');
					$("#filegaji_gaji").val('');
					list_detail_gaji();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_gaji #hapus_detail_gaji", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var del = confirm("Hapus data ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_gaji',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_gaji();
						}	
						else alert("Item gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= GAJI - END =============================================
		
		// ========================================= JABATAN - BEGIN ========================================
		$("#submit_detail_jabatan").on("click", function() {
			if ($('#cbounitkerja_jabatan').val() == 0) {
				$('.group-unitkerja-jabatan').addClass('has-error');
				return false;
			} else {
				$('.group-unitkerja-jabatan').removeClass('has-error');
			}
			if ($('#cbojabatan_jabatan').val() == 0) {
				$('.group-jabatan-jabatan').addClass('has-error');
				return false;
			} else {
				$('.group-jabatan-jabatan').removeClass('has-error');
			}
			if ($.trim($('#txtgolongan_jabatan').val()).length == 0) {
				$('.group-golongan-jabatan').addClass('has-error');
				return false;
			} else {
				$('.group-golongan-jabatan').removeClass('has-error');
			}
			if ($.trim($('#txtruang_jabatan').val()).length == 0) {
				$('.group-ruang-jabatan').addClass('has-error');
				return false;
			} else {
				$('.group-ruang-jabatan').removeClass('has-error');
			}
			if ($.trim($('#txttmtkerja_jabatan').val()).length == 0) {
				$('.group-tmtkerja-jabatan').addClass('has-error');
				return false;
			} else {
				$('.group-tmtkerja-jabatan').removeClass('has-error');
			}
			if ($.trim($('#txttstkerja_jabatan').val()).length == 0) {
				$('.group-tstkerja-jabatan').addClass('has-error');
				return false;
			} else {
				$('.group-tstkerja-jabatan').removeClass('has-error');
			}
			if ($.trim($('#txtnomorsk_jabatan').val()).length == 0) {
				$('.group-nomorsk-jabatan').addClass('has-error');
				return false;
			} else {
				$('.group-nomorsk-jabatan').removeClass('has-error');
			}
			if ($.trim($('#txttanggalsk_jabatan').val()).length == 0) {
				$('.group-tanggalsk-jabatan').addClass('has-error');
				return false;
			} else {
				$('.group-tanggalsk-jabatan').removeClass('has-error');
			}
			if ($("input[type=file][name=filesk_jabatan]" ).val() == '') {
				alert('File SK harap diisi');
				return false;
			}
			
			var form = $('#form-jabatan')[0];
			var formData = new FormData(form);
			
			formData.append('txtid_pokok', $("#txtid_pokok").val());
			
			$.ajax({
				type: 'POST',
				url: 'hr/karyawan/create_detail_jabatan/',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$('#cbounitkerja_jabatan').val('0'); $("#cbounitkerja_jabatan").selectpicker('refresh');
					$('#cbojabatan_jabatan').val('0'); $("#cbojabatan_jabatan").selectpicker('refresh');
					$("#txtgolongan_jabatan").val('');
					$("#txtruang_jabatan").val('');
					//$("#txttmtkerja_jabatan").val(moment().format('DD-MM-YYYY'));
					//$("#txttstkerja_jabatan").val(moment().format('DD-MM-YYYY'));
					$("#txtnomorsk_jabatan").val('');
					//$("#txttanggalsk_jabatan").val(moment().format('DD-MM-YYYY'));
					$("#filesk_jabatan").val('');
					list_detail_jabatan();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_jabatan #hapus_detail_jabatan", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_jabatan = parentTr.find('#nama_jabatan').html();
			var del = confirm("Hapus Jabatan " + nama_jabatan + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_jabatan',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_jabatan();
						}	
						else alert("Item "+nama_jabatan+ " gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= JABATAN - END ==========================================
		
		// ========================================= KELUARGA - BEGIN =======================================
		$("#submit_detail_keluarga").on("click", function() {
			if ($.trim($('#txtnama_keluarga').val()).length == 0) {
				$('.group-nama-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-nama-keluarga').removeClass('has-error');
			}
			if ($.trim($('#txtnik_keluarga').val()).length == 0) {
				$('.group-nik-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-nik-keluarga').removeClass('has-error');
			}
			if ($('#cbojeniskelamin_keluarga').val() == 0) {
				$('.group-jeniskelamin-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-jeniskelamin-keluarga').removeClass('has-error');
			}
			if ($('#cboagama_keluarga').val() == 0) {
				$('.group-agama-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-agama-keluarga').removeClass('has-error');
			}
			if ($('#cbopendidikan_keluarga').val() == 0) {
				$('.group-pendidikan-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-pendidikan-keluarga').removeClass('has-error');
			}
			if ($.trim($('#txttempatlahir_keluarga').val()).length == 0) {
				$('.group-tempatlahir-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-tempatlahir-keluarga').removeClass('has-error');
			}
			if ($.trim($('#txttanggallahir_keluarga').val()).length == 0) {
				$('.group-tanggallahir-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-tanggallahir-keluarga').removeClass('has-error');
			}
			if ($('#cbopekerjaan_keluarga').val() == 0) {
				$('.group-pekerjaan-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-pekerjaan-keluarga').removeClass('has-error');
			}
			if ($('#cbostatuskawin_keluarga').val() == 0) {
				$('.group-statuskawin-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-statuskawin-keluarga').removeClass('has-error');
			}
			if ($('#cbostatus_keluarga').val() == 0) {
				$('.group-statuskeluarga-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-statuskeluarga-keluarga').removeClass('has-error');
			}
			if ($('#cbokewarganegaraan_keluarga').val() == 0) {
				$('.group-kewarganegaraan-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-kewarganegaraan-keluarga').removeClass('has-error');
			}
			if ($.trim($('#txtnamaayah_keluarga').val()).length == 0) {
				$('.group-namaayah-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-namaayah-keluarga').removeClass('has-error');
			}
			if ($.trim($('#txtnamaibu_keluarga').val()).length == 0) {
				$('.group-namaibu-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-namaibu-keluarga').removeClass('has-error');
			}
			if ($.trim($('#txtnokk_keluarga').val()).length == 0) {
				$('.group-nokk-keluarga').addClass('has-error');
				return false;
			} else {
				$('.group-nokk-keluarga').removeClass('has-error');
			}
			if ($("input[type=file][name=filektp_keluarga]" ).val() == '') {
				alert("File File KTP harap diisi");
				return false;
			}
			if ($("input[type=file][name=filekk_keluarga]" ).val() == '') {
				alert("File File KK harap diisi");
				return false;
			}
			
			var is_available = 0;
			var v_nik = $("#txtnik_keluarga").val();
			
			$('#table_keluarga #nik').each(function() {
				v_cek_nik = $(this).html();
				if (v_nik == v_cek_nik) {
					is_available++;
				}
			});
			if (is_available > 0) {
				alert("NIK "+v_nik+" sudah ada! Masukkan NIK yang lain.");
				return;
			}
			
			var form = $('#form-keluarga')[0];
			var formData = new FormData(form);
			
			formData.append('txtid_pokok', $("#txtid_pokok").val());
			
			$.ajax({
				type: 'POST',
				url: 'hr/karyawan/create_detail_keluarga/',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$("#txtnama_keluarga").val('');
					$("#txtnik_keluarga").val('');
					$('#cbojeniskelamin_keluarga').val('0'); $("#cbojeniskelamin_keluarga").selectpicker('refresh');
					$("#txttempatlahir_keluarga").val('');
					//$("#txttanggallahir_keluarga").val(moment().format('DD-MM-YYYY'));
					$('#cboagama_keluarga').val('0'); $("#cboagama_keluarga").selectpicker('refresh');
					$('#cbopendidikan_keluarga').val('0'); $("#cbopendidikan_keluarga").selectpicker('refresh');
					$('#cbopekerjaan_keluarga').val('0'); $("#cbopekerjaan_keluarga").selectpicker('refresh');
					$('#cbostatuskawin_keluarga').val('0'); $("#cbostatuskawin_keluarga").selectpicker('refresh');
					$('#cbostatus_keluarga').val('0'); $("#cbostatus_keluarga").selectpicker('refresh');
					$('#cbokewarganegaraan_keluarga').val('0'); $("#cbokewarganegaraan_keluarga").selectpicker('refresh');
					$("#txtnamaayah_keluarga").val('');
					$("#txtnamaibu_keluarga").val('');
					$("#txtnokk_keluarga").val('');
					$("#filekk_keluarga").val('');
					$("#filektp_keluarga").val('');
					list_detail_keluarga();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_keluarga #hapus_detail_keluarga", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nik = parentTr.find('#nik').html();
			var del = confirm("Hapus NIK " + nik + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_keluarga',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_keluarga();
						}	
						else alert("Item "+nik+ " gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= KELUARGA - END =========================================
		
		// ========================================= KEWAJIBAN - BEGIN ======================================
		$("#submit_detail_kewajiban").on("click", function() {
			if ($('#cbokewajiban_kewajiban').val() == 0) {
				$('.group-kewajiban-kewajiban').addClass('has-error');
				return false;
			} else {
				$('.group-kewajiban-kewajiban').removeClass('has-error');
			}
			if ($.trim($('#txttahun_kewajiban').val()).length == 0) {
				$('.group-tahun-kewajiban').addClass('has-error');
				return false;
			} else {
				$('.group-tahun-kewajiban').removeClass('has-error');
			}
			if ($.trim($('#txtjumlah_kewajiban').val()).length == 0) {
				$('.group-jumlah-kewajiban').addClass('has-error');
				return false;
			} else {
				$('.group-jumlah-kewajiban').removeClass('has-error');
			}
			
			$.ajax({			
				url: "hr/karyawan/create_detail_kewajiban/",
				data: {   
						id_karyawan: $("#txtid_pokok").val(),
						id_kewajiban: $("#cbokewajiban_kewajiban").val(),
						tahun: $("#txttahun_kewajiban").val(),
						jumlah: $("#txtjumlah_kewajiban").val(),
						keterangan: $("#txtketerangan_kewajiban").val()
				},
				method: "post",
				success: function(data) {
					$('#cbokewajiban_kewajiban').val('0'); $("#cbokewajiban_kewajiban").selectpicker('refresh');
					//$("#txttahun_kewajiban").val(moment().format('YYYY'));
					$("#txtjumlah_kewajiban").val('0');
					$("#txtketerangan_kewajiban").val('');
					list_detail_kewajiban();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_kewajiban #hapus_detail_kewajiban", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_kewajiban = parentTr.find('#nama_kewajiban').html();
			var del = confirm("Hapus BPJS " + nama_kewajiban + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_kewajiban',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_kewajiban();
						}	
						else alert("Item "+nama_kewajiban+ " gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= KEWAJIBAN - END ========================================
		
		// ========================================= KOMPETENSI - BEGIN =====================================
		$("#submit_detail_kompetensi").on("click", function() {
			if ($.trim($('#txtnamakeahlian_kompetensi').val()).length == 0) {
				$('.group-namakeahlian-kompetensi').addClass('has-error');
				return false;
			} else {
				$('.group-namakeahlian-kompetensi').removeClass('has-error');
			}
			if ($('#cbolevelkeahlian_kompetensi').val() == 0) {
				$('.group-levelkeahlian-kompetensi').addClass('has-error');
				return false;
			} else {
				$('.group-levelkeahlian-kompetensi').removeClass('has-error');
			}
			if ($("input[type=file][name=filekompetensi_kompetensi]" ).val() == '') {
				alert("File Kompetensi harap diisi");
				return false;
			}
			
			var form = $('#form-kompetensi')[0];
			var formData = new FormData(form);
			
			formData.append('txtid_pokok', $("#txtid_pokok").val());
			formData.append('txtnik_pokok', $("#txtnik_pokok").val());
			
			$.ajax({
				type: 'POST',
				url: 'hr/karyawan/create_detail_kompetensi/',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$("#txtnamakeahlian_kompetensi").val('');
					$('#cbolevelkeahlian_kompetensi').val('0'); $("#cbolevelkeahlian_kompetensi").selectpicker('refresh');
					$("#txtketerangan_kompetensi").val('');
					$("#filekompetensi_kompetensi").val('');
					list_detail_kompetensi();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_kompetensi #hapus_detail_kompetensi", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_keahlian = parentTr.find('#nama_keahlian').html();
			var del = confirm("Hapus Kompetensi " + nama_keahlian + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_kompetensi',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_kompetensi();
						}	
						else alert("Item "+nama_kompetensi+ " gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= KOMPETENSI - END =======================================
		
		// ========================================= PAJAK - BEGIN ==========================================
		$("#submit_detail_pajak").on("click", function() {
			if ($('#cbopajak_pajak').val() == 0) {
				$('.group-nama-pajak').addClass('has-error');
				return false;
			} else {
				$('.group-nama-pajak').removeClass('has-error');
			}
			if ($.trim($('#txtpajakkaryawan_pajak').val()).length == 0) {
				$('.group-jumlah-pajak-karyawan').addClass('has-error');
				return false;
			} else {
				$('.group-jumlah-pajak-karyawan').removeClass('has-error');
			}
			if ($.trim($('#txtpajakperusahaan_pajak').val()).length == 0) {
				$('.group-jumlah-pajak-perusahaan').addClass('has-error');
				return false;
			} else {
				$('.group-jumlah-pajak-perusahaan').removeClass('has-error');
			}
			
			var is_available = 0;
			var v_id_pajak = $("#cbopajak_pajak").val();
			
			$('#table_pajak #id_pajak').each(function() {
				v_cek_id_pajak = $(this).html();
				if (v_id_pajak == v_cek_id_pajak) {
					is_available++;
				}
			});
			if (is_available > 0) {
				alert("Jenis Pajak sudah ada! Masukkan Jenis Pajak yang lain.");
				return;
			}
			
			$.ajax({			
				url: "hr/karyawan/create_detail_pajak/",
				data: {   
						id_karyawan: $("#txtid_pokok").val(),
						id_pajak: $("#cbopajak_pajak").val(),
						pajak_karyawan: $("#txtpajakkaryawan_pajak").val(),
						pajak_perusahaan: $("#txtpajakperusahaan_pajak").val(),
						keterangan: $("#txtketerangan_pajak").val()
				},
				method: "post",
				success: function(data) {
					$('#cbopajak_pajak').val('0'); $("#cbopajak_pajak").selectpicker('refresh');
					$("#txtpajakkaryawan_pajak").val('0');
					$("#txtpajakperusahaan_pajak").val('0');
					$("#txtketerangan_pajak").val('');
					list_detail_pajak();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_pajak #hapus_detail_pajak", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_pajak = parentTr.find('#nama_pajak').html();
			var del = confirm("Hapus Pajak " + nama_pajak + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_pajak',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_pajak();
						}	
						else alert("Item "+nama_pajak+ " gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= PAJAK - END ============================================
		
		// ====================================== PELATIHAN - BEGIN =========================================
		$("#submit_detail_pelatihan").on("click", function() {
			if ($.trim($('#txtnama_pelatihan').val()).length == 0) {
				$('.group-nama-pelatihan').addClass('has-error');
				return false;
			} else {
				$('.group-nama-pelatihan').removeClass('has-error');
			}
			if ($.trim($('#txtnamainstitusi_pelatihan').val()).length == 0) {
				$('.group-namainstitusi-pelatihan').addClass('has-error');
				return false;
			} else {
				$('.group-namainstitusi-pelatihan').removeClass('has-error');
			}
			if ($.trim($('#txttahun_pelatihan').val()).length == 0) {
				$('.group-tahun-pelatihan').addClass('has-error');
				return false;
			} else {
				$('.group-tahun-pelatihan').removeClass('has-error');
			}
			if ($("input[type=file][name=filesertifikat_pelatihan]" ).val() == '') {
				alert("File Sertifikat Pelatihan harap diisi");
				return false;
			}
			
			var form = $('#form-pelatihan')[0];
			var formData = new FormData(form);
			
			formData.append('txtid_pokok', $("#txtid_pokok").val());
			formData.append('txtnik_pokok', $("#txtnik_pokok").val());
			
			$.ajax({
				type: 'POST',
				url: 'hr/karyawan/create_detail_pelatihan/',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$("#txtnama_pelatihan").val('');
					$("#txtnamainstitusi_pelatihan").val('');
					//$("#txttahun_pelatihan").val(moment().format('YYYY'));
					$("#filesertifikat_pelatihan").val('');
					list_detail_pelatihan();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_pelatihan #hapus_detail_pelatihan", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_pelatihan = parentTr.find('#nama_pelatihan').html();
			var del = confirm("Hapus pelatihan " + nama_pelatihan + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_pelatihan',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_pelatihan();
						}	
						else alert("Item "+nama_pelatihan+ " gagal terhapus");							
					}
				});					
			}
		});
		// ====================================== PELATIHAN - END ===========================================
		
		// ========================================= PENDIDIKAN - BEGIN =====================================
		$("#submit_detail_pendidikan").on("click", function() {
			if ($('#cbojenjang_pendidikan').val() == 0) {
				$('.group-jenjang-pendidikan').addClass('has-error');
				return false;
			} else {
				$('.group-jenjang-pendidikan').removeClass('has-error');
			}
			if ($.trim($('#txtnamainstitusi_pendidikan').val()).length == 0) {
				$('.group-namainstitusi-pendidikan').addClass('has-error');
				return false;
			} else {
				$('.group-namainstitusi-pendidikan').removeClass('has-error');
			}			
			if ($.trim($('#txttahunmasuk_pendidikan').val()).length == 0) {
				$('.group-tahunmasuk-pendidikan').addClass('has-error');
				return false;
			} else {
				$('.group-tahunmasuk-pendidikan').removeClass('has-error');
			}
			if ($.trim($('#txttahunlulus_pendidikan').val()).length == 0) {
				$('.group-tahunlulus-pendidikan').addClass('has-error');
				return false;
			} else {
				$('.group-tahunlulus-pendidikan').removeClass('has-error');
			}
			if ($.trim($('#txtjurusan_pendidikan').val()).length == 0) {
				$('.group-jurusan-pendidikan').addClass('has-error');
				return false;
			} else {
				$('.group-jurusan-pendidikan').removeClass('has-error');
			}
			if ($.trim($('#txtipk_pendidikan').val()).length == 0) {
				$('.group-ipk-pendidikan').addClass('has-error');
				return false;
			} else {
				$('.group-ipk-pendidikan').removeClass('has-error');
			}
			if ($("input[type=file][name=fileijazah_pendidikan]" ).val() == '') {
				alert("File Ijazah harap diisi");
				return false;
			}
			
			var is_available = 0;
			var v_id_pendidikan = $("#cbojenjang_pendidikan").val();
			
			$('#table_pendidikan #id_pendidikan').each(function() {
				v_cek_id_pendidikan = $(this).html();
				if (v_id_pendidikan == v_cek_id_pendidikan) {
					is_available++;
				}
			});
			if (is_available > 0) {
				alert("Jenjang Pendidikan sudah ada!  Pilih yang lain.");
				return;
			}
			
			var form = $('#form-pendidikan')[0];
			var formData = new FormData(form);
			
			formData.append('txtid_pokok', $("#txtid_pokok").val());
			formData.append('txtnik_pokok', $("#txtnik_pokok").val());
			
			$.ajax({
				type: 'POST',
				url: 'hr/karyawan/create_detail_pendidikan/',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$('#cbojenjang_pendidikan').val('0'); $("#cbojenjang_pendidikan").selectpicker('refresh');
					$("#txtnamainstitusi_pendidikan").val('');
					$("#txttahunmasuk_pendidikan").val('');
					$("#txttahunlulus_pendidikan").val('');
					$("#txtjurusan_pendidikan").val('');
					$("#txtipk_pendidikan").val('0');
					$('#fileijazah_pendidikan').val('');
					list_detail_pendidikan();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_pendidikan #hapus_detail_pendidikan", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_pendidikan = parentTr.find('#nama_pendidikan').html();
			var del = confirm("Hapus pendidikan " + nama_pendidikan + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_pendidikan',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {								
							list_detail_pendidikan();
						}	
						else alert("Item "+nama_pendidikan+ " gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= PENDIDIKAN - END =======================================
		
		// ==================================== PENGALAMAN KERJA - BEGIN ====================================
		$("#submit_detail_pengalaman_kerja").on("click", function() {
			if ($.trim($('#txtnama_pengalamankerja').val()).length == 0) {
				$('.group-nama-pengalamankerja').addClass('has-error');
				return false;
			} else {
				$('.group-nama-pengalamankerja').removeClass('has-error');
			}
			if ($.trim($('#txttahunmasuk_pengalamankerja').val()).length == 0) {
				$('.group-tahunmasuk-pengalamankerja').addClass('has-error');
				return false;
			} else {
				$('.group-tahunmasuk-pengalamankerja').removeClass('has-error');
			}
			if ($.trim($('#txttahunkeluar_pengalamankerja').val()).length == 0) {
				$('.group-tahunkeluar-pengalamankerja').addClass('has-error');
				return false;
			} else {
				$('.group-tahunkeluar-pengalamankerja').removeClass('has-error');
			}
			if ($.trim($('#txtjabatan_pengalamankerja').val()).length == 0) {
				$('.group-jabatan-pengalamankerja').addClass('has-error');
				return false;
			} else {
				$('.group-jabatan-pengalamankerja').removeClass('has-error');
			}
			if ($.trim($('#txtgajiterakhir_pengalamankerja').val()).length == 0) {
				$('.group-gajiterakhir-pengalamankerja').addClass('has-error');
				return false;
			} else {
				$('.group-gajiterakhir-pengalamankerja').removeClass('has-error');
			}
			if ($.trim($('#txtalasanberhenti_pengalamankerja').val()).length == 0) {
				$('.group-alasanberhenti-pengalamankerja').addClass('has-error');
				return false;
			} else {
				$('.group-alasanberhenti-pengalamankerja').removeClass('has-error');
			}			
			if ($("input[type=file][name=filepengalamankerja_pengalamankerja]" ).val() == '') {
				alert("File Pengalaman Kerja harap diisi");
				return false;
			}
			
			var form = $('#form-pengalaman-kerja')[0];
			var formData = new FormData(form);
			
			formData.append('txtid_pokok', $("#txtid_pokok").val());
			formData.append('txtnik_pokok', $("#txtnik_pokok").val());
			
			$.ajax({
				type: 'POST',
				url: 'hr/karyawan/create_detail_pengalaman_kerja/',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$("#txtnama_pengalamankerja").val('');
					$("#txttahunmasuk_pengalamankerja").val('');
					$("#txttahunkeluar_pengalamankerja").val('');
					$("#txtjabatan_pengalamankerja").val('');
					$("#txtgajiterakhir_pengalamankerja").val('0');
					$('#txtalasanberhenti_pengalamankerja').val('');
					$('#filepengalamankerja_pengalamankerja').val('');
					list_detail_pengalaman_kerja();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_pengalaman_kerja #hapus_detail_pengalaman_kerja", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_perusahaan = parentTr.find('#nama').html();
			var del = confirm("Hapus pekerjaan " + nama_perusahaan + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_pengalaman_kerja',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {								
							list_detail_pengalaman_kerja();
						}	
						else alert("Item "+nama_perusahaan+ " gagal terhapus");							
					}
				});					
			}
		});
		// ==================================== PENGALAMAN KERJA - END ======================================
		
		// ========================================= POKOK - BEGIN ==========================================
		$("body").on("click", "#table_data .btn-edit", function() {
			reset_input();
			
			$('#nav-data-pokok').addClass('active');
			$('#tabs-data-pokok').addClass('active');
			
			var parentTr = $(this).closest('tr');
			var id_karyawan = parentTr.find('.btn-edit').val();
			
			$.ajax({
				url : 'hr/karyawan/karyawan_view',
				type: 'POST',
				data: {
					id_karyawan: id_karyawan
				},
				dataType: 'json',
				beforeSend: function() {
					$.LoadingOverlay('show'); 
				},
				success : function(data) {
					$.LoadingOverlay('hide');
					$('#modal-create-edit').modal('show');
					$('#modal-primary-header').html('Edit Data Karyawan');
					$('#txtid_pokok').val(data.id_karyawan);
					$('#txtnik_pokok').val(data.nik);
					$('#txtnama_pokok').val(data.nama_karyawan);
					$('#cbojeniskelamin_pokok').val(data.jenis_kelamin); $("#cbojeniskelamin_pokok").selectpicker('refresh');
					$('#txttempatlahir_pokok').val(data.tempat_lahir);
					$('#txttanggallahir_pokok').val(data.tanggal_lahir);
					$('#txtalamat_pokok').val(data.alamat);
					$('#cboprovinsi_pokok').val(data.id_provinsi); $("#cboprovinsi_pokok").selectpicker('refresh');
					var id_provinsi = $('#cboprovinsi_pokok').val();
					var opt = '<option selected hidden value="0">Pilih Kab./Kota</option>';
					$.ajax({
						url: 'hr/karyawan/get_kabkota/'+id_provinsi,
						dataType: 'json',
						type: 'get',
						async: false,
						success: function(json) {
							$.each(json, function(i, obj) {
								opt += "<option value='"+obj.id+"'>"+obj.nama+"</option>";
							});
							$("#cbokabkota_pokok").html(opt);
							$("#cbokabkota_pokok").selectpicker('refresh');							
						}
					});
					$('#cbokabkota_pokok').val(data.id_kabupatenkota); $("#cbokabkota_pokok").selectpicker('refresh');
					$('#txtkodepos_pokok').val(data.kode_pos);
					$('#txtnomortelepon_pokok').val(data.nomor_telepon);
					$('#txtnomorhp_pokok').val(data.nomor_hp);
					$('#txtnomorwa_pokok').val(data.nomor_wa);
					$('#txtemail_pokok').val(data.email);
					$('#txtnomorktp_pokok').val(data.nomor_ktp);
					//$('#txtfilektp_pokok').val(data.file_ktp);
					//$('#txtfilefoto_pokok').val(data.file_foto);
					$('#cbounitkerja_pokok').val(data.id_unit_kerja); $("#cbounitkerja_pokok").selectpicker('refresh');
					$('#txtketerangan_pokok').val(data.keterangan);
					if (data.activated == 1) {
						$("#chkaktif_pokok").prop('checked', true);
					} else {
						$("#chkaktif_pokok").prop('checked', false);
					}
					
					/*list_detail_bpjs();
					list_detail_fasilitas();
					list_detail_gaji();
					list_detail_jabatan();
					list_detail_keluarga();
					list_detail_kewajiban();
					list_detail_kompetensi();
					list_detail_pajak();
					list_detail_pelatihan();
					list_detail_pendidikan();
					list_detail_pengalaman_kerja();
					list_detail_presensi_cuti();
					list_detail_prestasi();
					list_detail_rekening();
					list_detail_sanksi();
					list_detail_tunjangan();*/
				},
				error: function() {
				  alert("failure...");
				}
			});
		});
		
		$(".add-edit").click(function(e) {
			if ($.trim($('#txtnama_pokok').val()).length == 0) {
				$('.group-nama-pokok').addClass('has-error');
				return false;
			} else {
				$('.group-nama-pokok').removeClass('has-error');
			}
			if ($.trim($('#txttempatlahir_pokok').val()).length == 0) {
				$('.group-tempatlahir-pokok').addClass('has-error');
				return false;
			} else {
				$('.group-tempatlahir-pokok').removeClass('has-error');
			}
			if ($.trim($('#txttanggallahir_pokok').val()).length == 0) {
				$('.group-tanggallahir-pokok').addClass('has-error');
				return false;
			} else {
				$('.group-tanggallahir-pokok').removeClass('has-error');
			}
			if ($.trim($('#txtnomortelepon_pokok').val()).length == 0) {
				$('.group-notelp-pokok').addClass('has-error');
				return false;
			} else {
				$('.group-notelp-pokok').removeClass('has-error');
			}
			if ($.trim($('#txtnomorhp_pokok').val()).length == 0) {
				$('.group-nohp-pokok').addClass('has-error');
				return false;
			} else {
				$('.group-nohp-pokok').removeClass('has-error');
			}
			if ($.trim($('#txtnomorwa_pokok').val()).length == 0) {
				$('.group-nowa-pokok').addClass('has-error');
				return false;
			} else {
				$('.group-nowa-pokok').removeClass('has-error');
			}
			if ($.trim($('#txtnomorktp_pokok').val()).length == 0) {
				$('.group-noktp-pokok').addClass('has-error');
				return false;
			} else {
				$('.group-noktp-pokok').removeClass('has-error');
			}
			if ($("input[type=file][name=filektp_pokok]" ).val() == '') {
				$('.group-filektp-pokok').addClass('has-error');
				return false;
			} else {
				$('.group-filektp-pokok').removeClass('has-error');
			}
			if ($("input[type=file][name=filefoto_pokok]" ).val() == '') {
				$('.group-filefoto-pokok').addClass('has-error');
				return false;
			} else {
				$('.group-filefoto-pokok').removeClass('has-error');
			}
			if ($('#cbounitkerja_pokok').val() == 0) {
				$('.group-unitkerja-pokok').addClass('has-error');
				return false;
			} else {
				$('.group-unitkerja-pokok').removeClass('has-error');
			}
			
			var form = $('#form-create-edit')[0];
			var formData = new FormData(form);
			
			formData.append('cbojeniskelamin_pokok', $("#cbojeniskelamin_pokok").val());
			formData.append('cboprovinsi_pokok', $("#cboprovinsi_pokok").val());
			formData.append('cbokabkota_pokok', $("#cbokabkota_pokok").val());
			formData.append('cbounitkerja_pokok', $("#cbounitkerja_pokok").val());
			formData.append('kodeunitkerja_pokok', $('#cbounitkerja_pokok option:selected').attr('kode'));
			formData.append('chkaktif_pokok', $('#chkaktif_pokok').is(':checked') ? '1'  : '0');
			
			if ($('#txtid_pokok').val() == '') {
				var urls = 'hr/karyawan/karyawan_create';
			} else {
				var urls = 'hr/karyawan/karyawan_update';
			}
			
			$.ajax({
				type: 'POST',
				url: urls,
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$('#modal-create-edit').modal('hide');
					window.setTimeout(
						function() {
							location.reload(true)
						}, 0
					);
				},
				error: function() {
					alert("failure");
				}
			});
		});
		// ========================================= POKOK - END ============================================
		
		// ========================================= PRESENSI & CUTI - BEGIN =================================
		$("#submit_detail_presensi_cuti").on("click", function() {
			if ($.trim($('#txttahun_presensi_cuti').val()).length == 0) {
				$('.group-tahun-presensi-cuti').addClass('has-error');
				return false;
			} else {
				$('.group-tahun-presensi-cuti').removeClass('has-error');
			}
			if ($('#cbobulan_presensi_cuti').val() == 0) {
				$('.group-bulan-presensi-cuti').addClass('has-error');
				return false;
			} else {
				$('.group-bulan-presensi-cuti').removeClass('has-error');
			}
			if ($.trim($('#txtharibekerja_presensi_cuti').val()).length == 0) {
				$('.group-haribekerja-presensi-cuti').addClass('has-error');
				return false;
			} else {
				$('.group-haribekerja-presensi-cuti').removeClass('has-error');
			}
			if ($.trim($('#txthariijin_presensi_cuti').val()).length == 0) {
				$('.group-hariijin-presensi-cuti').addClass('has-error');
				return false;
			} else {
				$('.group-hariijin-presensi-cuti').removeClass('has-error');
			}
			if ($.trim($('#txtharicuti_presensi_cuti').val()).length == 0) {
				$('.group-haricuti-presensi-cuti').addClass('has-error');
				return false;
			} else {
				$('.group-haricuti-presensi-cuti').removeClass('has-error');
			}
			if ($.trim($('#txtharimangkir_presensi_cuti').val()).length == 0) {
				$('.group-harimangkir-presensi-cuti').addClass('has-error');
				return false;
			} else {
				$('.group-harimangkir-presensi-cuti').removeClass('has-error');
			}
			
			var form = $('#form-presensi-cuti')[0];
			var formData = new FormData(form);
			
			formData.append('txtid_pokok', $("#txtid_pokok").val());
			
			$.ajax({
				type: 'POST',
				url: 'hr/karyawan/create_detail_presensi_cuti/',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					//$("#txttahun_presensi_cuti").val(moment().format('YYYY'));
					$('#cbobulan_presensi_cuti').val('0'); $("#cbobulan_presensi_cuti").selectpicker('refresh');					
					$("#txtharibekerja_presensi_cuti").val('0');
					$("#txthariijin_presensi_cuti").val('0');
					$("#txtharicuti_presensi_cuti").val('0');
					$("#txtharimangkir_presensi_cuti").val('0');
					$("#txtketerangan_presensi_cuti").val('');
					list_detail_presensi_cuti();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_presensi_cuti #hapus_detail_presensi_cuti", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var del = confirm("Hapus Data ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_presensi_cuti',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_presensi_cuti();
						}	
						else alert("Item gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= PRESENSI & CUTI - END ==================================
		
		// ====================================== PRESTASI - BEGIN ==========================================
		$("#submit_detail_prestasi").on("click", function() {
			if ($.trim($('#txtnamaprestasi_prestasi').val()).length == 0) {
				$('.group-namaprestasi-prestasi').addClass('has-error');
				return false;
			} else {
				$('.group-namaprestasi-prestasi').removeClass('has-error');
			}
			if ($.trim($('#txttahun_prestasi').val()).length == 0) {
				$('.group-tahun-prestasi').addClass('has-error');
				return false;
			} else {
				$('.group-tahun-prestasi').removeClass('has-error');
			}
			if ($("input[type=file][name=fileprestasi_prestasi]" ).val() == '') {
				alert("File Prestasi harap diisi");
				return false;
			}
			
			var form = $('#form-prestasi')[0];
			var formData = new FormData(form);
			
			formData.append('txtid_pokok', $("#txtid_pokok").val());
			formData.append('txtnik_pokok', $("#txtnik_pokok").val());
			
			$.ajax({
				type: 'POST',
				url: 'hr/karyawan/create_detail_prestasi/',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$("#txtnamaprestasi_prestasi").val('');
					//$("#txttahun_prestasi").val(moment().format('YYYY'));
					$("#txtketerangan_prestasi").val('');
					$("#fileprestasi_prestasi").val('');
					list_detail_prestasi();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_prestasi #hapus_detail_prestasi", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_prestasi = parentTr.find('#nama_prestasi').html();
			var del = confirm("Hapus prestasi " + nama_prestasi + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_prestasi',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_prestasi();
						}	
						else alert("Item "+nama_prestasi+ " gagal terhapus");							
					}
				});					
			}
		});
		// ====================================== PRESTASI - END ============================================
		
		// ====================================== REKENING - BEGIN ==========================================
		$("#submit_detail_rekening").on("click", function() {
			if ($('#cbobank_rekening').val() == 0) {
				$('.group-bank-rekening').addClass('has-error');
				return false;
			} else {
				$('.group-bank-rekening').removeClass('has-error');
			}
			if ($.trim($('#txtnomor_rekening').val()).length == 0) {
				$('.group-nomor-rekening').addClass('has-error');
				return false;
			} else {
				$('.group-nomor-rekening').removeClass('has-error');
			}
			if ($.trim($('#txtnama_rekening').val()).length == 0) {
				$('.group-nama-rekening').addClass('has-error');
				return false;
			} else {
				$('.group-nama-rekening').removeClass('has-error');
			}			
			if ($("input[type=file][name=filerekening_rekening]" ).val() == '') {
				alert("File Rekening harap diisi");
				return false;
			}
			
			var form = $('#form-rekening')[0];
			var formData = new FormData(form);
			
			formData.append('txtid_pokok', $("#txtid_pokok").val());
			
			$.ajax({
				type: 'POST',
				url: 'hr/karyawan/create_detail_rekening/',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$('#cbobank_rekening').val('0'); $("#cbobank_rekening").selectpicker('refresh');
					$("#txtnomor_rekening").val('');
					$("#txtnama_rekening").val('');
					$("#filerekening_rekening").val('');
					list_detail_rekening();
				},
				error: function() {
					alert("failure");
				}
			});
			
			/*$.ajax({			
				url: "hr/karyawan/create_detail_rekening/",
				data: {   
						id_karyawan: $("#txtid_pokok").val(),
						id_bank: $("#cbobank_rekening").val(),
						nomor_rekening: $("#txtnomor_rekening").val(),
						nama_rekening: $("#txtnama_rekening").val(),
						file_rekening: $("#filerekening_rekening").val('')
				},
				method: "post",
				success: function(data) {
					$('#cbobank_rekening').val('0'); $("#cbobank_rekening").selectpicker('refresh');
					$("#txtnomor_rekening").val('');
					$("#txtnama_rekening").val('');
					$("#filerekening_rekening").val('');
					list_detail_rekening();
				},
				error: function() {
					alert("failure");
				}
			});*/
		});
		
		$("body").on("click", "#table_rekening #hapus_detail_rekening", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nomor_rekening = parentTr.find('#nomor_rekening').html();
			var del = confirm("Hapus Nomor Rekening " + nomor_rekening + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_rekening',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_rekening();
						}	
						else alert("Item "+nama_rekening+ " gagal terhapus");							
					}
				});					
			}
		});
		// ====================================== REKENING - END ============================================
		
		// ========================================= SANKSI - BEGIN =========================================
		$("#submit_detail_sanksi").on("click", function() {
			if ($('#cbosanksi_sanksi').val() == 0) {
				$('.group-sanksi-sanksi').addClass('has-error');
				return false;
			} else {
				$('.group-sanksi-sanksi').removeClass('has-error');
			}
			if ($.trim($('#txttanggaldiberikan_sanksi').val()).length == 0) {
				$('.group-tanggaldiberikan-sanksi').addClass('has-error');
				return false;
			} else {
				$('.group-tanggaldiberikan-sanksi').removeClass('has-error');
			}
			if ($("input[type=file][name=filesanksi_sanksi]" ).val() == '') {
				alert("File Sanksi harap diisi");
				return false;
			}
			
			var form = $('#form-sanksi')[0];
			var formData = new FormData(form);
			
			formData.append('txtid_pokok', $("#txtid_pokok").val());
			formData.append('txtnik_pokok', $("#txtnik_pokok").val());
			
			$.ajax({
				type: 'POST',
				url: 'hr/karyawan/create_detail_sanksi/',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result) {
					$('#cbosanksi_sanksi').val('0'); $("#cbosanksi_sanksi").selectpicker('refresh');
					//$("#txttanggaldiberikan_sanksi").val(moment().format('DD-MM-YYYY'));
					$("#txtketerangan_sanksi").val('');
					$("#filesanksi_sanksi").val('');
					list_detail_sanksi();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_sanksi #hapus_detail_sanksi", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_sanksi = parentTr.find('#nama_sanksi').html();
			var del = confirm("Hapus Sanksi " + nama_sanksi + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_sanksi',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_sanksi();
						}	
						else alert("Item "+nama_sanksi+ " gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= SANKSI - END ===========================================
		
		// ========================================= TUNJANGAN - BEGIN ======================================
		$("#submit_detail_tunjangan").on("click", function() {
			if ($('#cbotunjangan_tunjangan').val() == 0) {
				$('.group-tunjangan-tunjangan').addClass('has-error');
				return false;
			} else {
				$('.group-tunjangan-tunjangan').removeClass('has-error');
			}
			if ($.trim($('#txttahun_tunjangan').val()).length == 0) {
				$('.group-tahun-tunjangan').addClass('has-error');
				return false;
			} else {
				$('.group-tahun-tunjangan').removeClass('has-error');
			}
			if ($.trim($('#cbobulan_tunjangan').val()).length == 0) {
				$('.group-bulan-tunjangan').addClass('has-error');
				return false;
			} else {
				$('.group-bulan-tunjangan').removeClass('has-error');
			}
			if ($.trim($('#txttunjangan_tunjangan').val()).length == 0) {
				$('.group-jumlah-tunjangan').addClass('has-error');
				return false;
			} else {
				$('.group-jumlah-tunjangan').removeClass('has-error');
			}
			
			$.ajax({			
				url: "hr/karyawan/create_detail_tunjangan/",
				data: {   
						id_karyawan: $("#txtid_pokok").val(),
						id_tunjangan: $("#cbotunjangan_tunjangan").val(),
						tahun: $("#txttahun_tunjangan").val(),
						bulan: $("#cbobulan_tunjangan").val(),
						tunjangan: $("#txttunjangan_tunjangan").val(),
						keterangan: $("#txtketerangan_tunjangan").val()
				},
				method: "post",
				success: function(data) {
					$('#cbotunjangan_tunjangan').val('0'); $("#cbotunjangan_tunjangan").selectpicker('refresh');
					$('#cbobulan_tunjangan').val('0'); $("#cbobulan_tunjangan").selectpicker('refresh');
					$("#txttunjangan_tunjangan").val('0');
					$("#txtketerangan_tunjangan").val('');
					list_detail_tunjangan();
				},
				error: function() {
					alert("failure");
				}
			});
		});
		
		$("body").on("click", "#table_tunjangan #hapus_detail_tunjangan", function() {
			var parentTr = $(this).closest('tr');
			var id_detail = parentTr.find('#id_detail').html();
			var nama_tunjangan = parentTr.find('#nama_tunjangan').html();
			var del = confirm("Hapus Tunjangan " + nama_tunjangan + " ?");
			
			if (del == true) {
				$.ajax({
					url: 'hr/karyawan/delete_detail_tunjangan',
					type: 'post',
					data: { id_detail: id_detail },
					success: function(data) {
						if(data == 'done') {
							list_detail_tunjangan();
						}	
						else alert("Item "+nama_tunjangan+ " gagal terhapus");							
					}
				});					
			}
		});
		// ========================================= TUNJANGAN - END ========================================
		
	});
	
	// ========================================= FUNCTIONS - BEGIN ======================================
	function list_detail_bpjs() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_bpjs/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_bpjs').html(data);
            }
        });
    }
	
	function list_detail_fasilitas() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_fasilitas/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_fasilitas').html(data);
            }
        });
    }
	
	function list_detail_gaji() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_gaji/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_gaji').html(data);
            }
        });
    }
	
	function list_detail_jabatan() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_jabatan/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_jabatan').html(data);
            }
        });
    }
	
	function list_detail_keluarga() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_keluarga/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_keluarga').html(data);
            }
        });		
    }
	
	function list_detail_kewajiban() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_kewajiban/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_kewajiban').html(data);
            }
        });
    }
	
	function list_detail_kompetensi() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_kompetensi/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_kompetensi').html(data);
            }
        });
    }
	
	function list_detail_pajak() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_pajak/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_pajak').html(data);
            }
        });
    }
	
	function list_detail_pelatihan() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_pelatihan/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_pelatihan').html(data);
            }
        });
    }
	
	function list_detail_pendidikan() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_pendidikan/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_pendidikan').html(data);
            }
        });
    }
	
	function list_detail_pengalaman_kerja() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_pengalaman_kerja/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_pengalaman_kerja').html(data);
            }
        });
    }
	
	function list_detail_presensi_cuti() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_presensi_cuti/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_presensi_cuti').html(data);
            }
        });
    }
	
	function list_detail_prestasi() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_prestasi/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_prestasi').html(data);
            }
        });
    }
	
	function list_detail_rekening() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_rekening/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_rekening').html(data);
            }
        });
    }
	
	function list_detail_sanksi() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_sanksi/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_sanksi').html(data);
            }
        });
    }
	
	function list_detail_tunjangan() {
		var id_karyawan = $("#txtid_pokok").val();
        $.ajax({
            url   	: 'hr/karyawan/list_detail_tunjangan/?id_karyawan='+id_karyawan,
			async 	: false,
            success : function(data) {
                $('#show_detail_tunjangan').html(data);
            }
        });
    }
	
	function reset_input() {
		// BPJS
		$('#nav-bpjs').removeClass('active');
		$('#cbobpjs_bpjs').val('0'); $("#cbobpjs_bpjs").selectpicker('refresh');
		$('#cbokelas_bpjs').val('0'); $("#cbokelas_bpjs").selectpicker('refresh');
		$("#txtjumlahkaryawan_bpjs").val('0');
		$("#txtjumlahperusahaan_bpjs").val('0');
		$("#txtketerangan_bpjs").val('');
		$("#show_detail_bpjs").empty();
		
		// FASILITAS
		$('#nav-fasilitas').removeClass('active');
		$('#cbofasilitas_fasilitas').val('0'); $("#cbofasilitas_fasilitas").selectpicker('refresh');
		$("#txttanggaldiberikan_fasilitas").val(moment().format('DD-MM-YYYY'));
		$("#txttanggaldikembalikan_fasilitas").val(moment().format('DD-MM-YYYY'));
		$("#txtketerangan_fasilitas").val('');
		$("#show_detail_fasilitas").empty();
		
		// GAJI
		$('#nav-gaji').removeClass('active');
		$("#txttahun_gaji").val(moment().format('YYYY'));
		$('#cbobulan_gaji').val('0'); $("#cbobulan_gaji").selectpicker('refresh');
		$("#txtgaji_gaji").val('0');
		$("#txtketerangan_gaji").val('');
		$("#filegaji_gaji").val('');
		$("#show_detail_gaji").empty();
		
		// JABATAN
		$('#nav-unit-kerja').removeClass('active');
		$('#cbounitkerja_jabatan').val('0'); $("#cbounitkerja_jabatan").selectpicker('refresh');
		$('#cbojabatan_jabatan').val('0'); $("#cbojabatan_jabatan").selectpicker('refresh');
		$("#txtgolongan_jabatan").val('');
		$("#txtruang_jabatan").val('');
		$("#txttmtkerja_jabatan").val(moment().format('DD-MM-YYYY'));
		$("#txttstkerja_jabatan").val(moment().format('DD-MM-YYYY'));
		$("#txtnomorsk_jabatan").val('');
		$("#txttanggalsk_jabatan").val(moment().format('DD-MM-YYYY'));
		$("#filesk_jabatan").val('');
		$("#show_detail_jabatan").empty();
		
		// KELUARGA
		$('#nav-keluarga').removeClass('active');
		$("#txtnama_keluarga").val('');
		$("#txtnik_keluarga").val('');
		$('#cbojeniskelamin_keluarga').val('0'); $("#cbojeniskelamin_keluarga").selectpicker('refresh');
		$("#txttempatlahir_keluarga").val('');
		$("#txttanggallahir_keluarga").val(moment().format('DD-MM-YYYY'));
		$('#cboagama_keluarga').val('0'); $("#cboagama_keluarga").selectpicker('refresh');
		$('#cbopendidikan_keluarga').val('0'); $("#cbopendidikan_keluarga").selectpicker('refresh');
		$('#cbopekerjaan_keluarga').val('0'); $("#cbopekerjaan_keluarga").selectpicker('refresh');
		$('#cbostatuskawin_keluarga').val('0'); $("#cbostatuskawin_keluarga").selectpicker('refresh');
		$('#cbostatus_keluarga').val('0'); $("#cbostatus_keluarga").selectpicker('refresh');
		$('#cbokewarganegaraan_keluarga').val('0'); $("#cbokewarganegaraan_keluarga").selectpicker('refresh');
		$("#txtnamaayah_keluarga").val('');
		$("#txtnamaibu_keluarga").val('');
		$("#txtnokk_keluarga").val('');
		$('#filektp_keluarga').val('');
		$('#filekk_keluarga').val('');
		$("#show_detail_keluarga").empty();
		
		// KEWAJIBAN
		$('#nav-kewajiban').removeClass('active');
		$('#cbokewajiban_kewajiban').val('0'); $("#cbokewajiban_kewajiban").selectpicker('refresh');
		$("#txttahun_kewajiban").val(moment().format('YYYY'));
		$("#txtjumlah_kewajiban").val('0');
		$("#txtketerangan_kewajiban").val('');
		$("#show_detail_kewajiban").empty();
		
		// KOMPETENSI
		$('#nav-kompetensi').removeClass('active');
		$('#txtnamakeahlian_kompetensi').val('');
		$('#cbolevelkeahlian_kompetensi').val('0'); $("#cbolevelkeahlian_kompetensi").selectpicker('refresh');		
		$('#txtketerangan_kompetensi').val('');
		$('#filekompetensi_kompetensi').val('');
		$("#show_detail_kompetensi").empty();
		
		// PAJAK
		$('#nav-pajak').removeClass('active');
		$('#cbopajak_pajak').val('0'); $("#cbopajak_pajak").selectpicker('refresh');
		$("#txtpajakkaryawan_pajak").val('0');
		$("#txtpajakperusahaan_pajak").val('0');
		$("#txtketerangan_pajak").val('');
		$("#show_detail_pajak").empty();
		
		// PELATIHAN
		$('#nav-pelatihan-seminar').removeClass('active');
		$("#txtnama_pelatihan").val('');
		$("#txtnamainstitusi_pelatihan").val('');
		$("#txttahun_pelatihan").val(moment().format('YYYY'));
		$("#filesertifikat_pelatihan").val('');
		$("#filesertifikat_pelatihan").val('');
		$("#show_detail_pelatihan").empty();
		
		// PENDIDIKAN
		$('#nav-pendidikan').removeClass('active');
		$('#cbojenjang_pendidikan').val('0'); $("#cbojenjang_pendidikan").selectpicker('refresh');
		$('#txtnamainstitusi_pendidikan').val('');
		$('#txttahunmasuk_pendidikan').val(moment().format('YYYY'));
		$('#txttahunlulus_pendidikan').val(moment().format('YYYY'));
		$('#txtjurusan_pendidikan').val('');
		$('#txtipk_pendidikan').val('0');
		$('#fileijazah_pendidikan').val('');
		$("#show_detail_pendidikan").empty();
		
		// PENGALAMAN KERJA
		$('#nav-pengalaman-kerja').removeClass('active');
		$("#txtnama_pengalamankerja").val('');
		$("#txttahunmasuk_pengalamankerja").val(moment().format('YYYY'));
		$("#txttahunkeluar_pengalamankerja").val(moment().format('YYYY'));
		$("#txtjabatan_pengalamankerja").val('');
		$("#txtgajiterakhir_pengalamankerja").val('0');
		$('#txtalasanberhenti_pengalamankerja').val('');
		$('#filepengalamankerja_pengalamankerja').val('');
		$("#show_detail_pengalaman_kerja").empty();
		
		// POKOK
		$('#nav-data-pokok').removeClass('active');
		$('#txtid_pokok').val('');
		$('#txtnik_pokok').val('');
		$('#txtnama_pokok').val('');
		$('#cbojeniskelamin_pokok').val('0'); $("#cbojeniskelamin_pokok").selectpicker('refresh');
		$('#txttempatlahir_pokok').val('');
		$('#txttanggallahir_pokok').val(moment().format('DD-MM-YYYY'));
		$('#txtalamat_pokok').val('');
		$('#cboprovinsi_pokok').val('0'); $("#cboprovinsi_pokok").selectpicker('refresh');
		$('#cbokabkota_pokok').val('0'); $("#cbokabkota_pokok").selectpicker('refresh');
		$('#txtkodepos_pokok').val('');
		$('#txtnomortelepon_pokok').val('');
		$('#txtnomorhp_pokok').val('');
		$('#txtnomorwa_pokok').val('');
		$('#txtemail_pokok').val('');
		$('#txtnomorktp_pokok').val('');
		$('#filektp_pokok').val('');
		$('#filefoto_pokok').val('');
		$('#cbounitkerja_pokok').val('0'); $("#cbounitkerja_pokok").selectpicker('refresh');
		$('#txtketerangan_pokok').val('');
		$("#chkaktif_pokok").prop('checked', true);
		
		// PRESENSI & CUTI
		$('#nav-presensi-cuti').removeClass('active');
		$("#txttahun_presensi_cuti").val(moment().format('YYYY'));
		$('#cbobulan_presensi_cuti').val('0'); $("#cbobulan_presensi_cuti").selectpicker('refresh');
		$("#txtharibekerja_presensi_cuti").val('0');
		$("#txthariijin_presensi_cuti").val('0');
		$("#txtharicuti_presensi_cuti").val('0');
		$("#txtharimangkir_presensi_cuti").val('0');
		$("#txtketerangan_presensi_cuti").val('');
		$("#show_detail_presensi_cuti").empty();
		
		// PRESTASI
		$('#nav-prestasi').removeClass('active');
		$("#txtnamaprestasi_prestasi").val('');
		$("#txttahun_prestasi").val(moment().format('YYYY'));
		$("#txtketerangan_prestasi").val('');
		$("#fileprestasi_prestasi").val('');
		$("#show_detail_prestasi").empty();
		
		// REKENING
		$('#nav-rekening').removeClass('active');
		$('#cbobank_rekening').val('0'); $("#cbobank_rekening").selectpicker('refresh');
		$("#txtnomor_rekening").val('');
		$("#txtnama_rekening").val('');
		$("#filerekening_rekening").val('');
		$("#show_detail_rekening").empty();
		
		// SANKSI
		$('#nav-sanksi-peringatan').removeClass('active');
		$('#cbosanksi_sanksi').val('0'); $("#cbosanksi_sanksi").selectpicker('refresh');
		$("#txttanggaldiberikan_sanksi").val(moment().format('DD-MM-YYYY'));
		$("#txtketerangan_sanksi").val('');
		$("#filesanksi_sanksi").val('');
		$("#show_detail_sanksi").empty();
		
		// TUNJANGAN
		$('#nav-tunjangan').removeClass('active');
		$('#cbotunjangan_tunjangan').val('0'); $("#cbotunjangan_tunjangan").selectpicker('refresh');
		$("#txttahun_tunjangan").val(moment().format('YYYY'));
		$('#cbobulan_tunjangan').val('0'); $("#cbobulan_tunjangan").selectpicker('refresh');
		$("#txttunjangan_tunjangan").val('0');
		$("#txtketerangan_tunjangan").val('');
		$("#show_detail_tunjangan").empty();
	}
	// ========================================= FUNCTIONS - END ========================================
</script>
  
<div class="row margin-bottom-5">
	<div class="col-md-12">
		<div class="app-heading app-heading-small">
			<div class="icon icon-lg">
				<span class="<?php echo $page_icon; ?>"></span>
			</div>
			<div class="title">
				<h2><?php echo $page_title; ?></h2>
				<p><?php echo $page_subtitle; ?></p>
			</div>                        
			<div class="heading-elements">
				<button type="button" class="btn btn-info btn-icon-fixed btn-tambah" data-toggle="modal" data-target="#modal-create-edit"><span class="icon-file-add"></span> Tambah Data Karyawan Baru</button>
			</div>
		</div>                                                         
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-body"> 
				<table id="table_data" class="table table-head-custom table-striped" style="width: 100%">
					<thead>
						<tr>
							<th class="text-center">No.</th>
                            <th>NIK</th>
                            <th>Nama Karyawan</th>
							<th>Jenis Kelamin</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
							<th>Alamat</th>
							<th>Unit Kerja</th>
                            <th>Status</th>
                            <th>Actions</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>		
	</div>
</div>

<!-- Begin : Modal Add / Edit -->
<div class="modal fade" id="modal-create-edit" tabindex="-1" role="dialog" aria-labelledby="modal-primary-header">                        
	<div class="modal-dialog modal-info modal-fw" role="document">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross"></span></button>		
		<div class="modal-content">
			<div class="modal-header">                        
				<h4 class="modal-title" id="modal-primary-header"></h4>
			</div>
			<div class="modal-body">
				<div>
					<ul class="nav nav-tabs tab-content-bordered">
						<li id="nav-data-pokok" class="active"><a href="#tabs-data-pokok" data-toggle="tab"><span class="fa fa-cube"></span> Data Pokok</a></li>
						<li id="nav-unit-kerja"><a href="#tabs-unit-kerja" data-toggle="tab"><span class="fa fa-folder-open"></span> Unit Kerja & Jabatan</a></li>
						<li id="nav-presensi-cuti"><a href="#tabs-presensi-cuti" data-toggle="tab"><span class="fa fa-calendar"></span> Presensi & Cuti</a></li>
						<li id="nav-gaji"><a href="#tabs-gaji" data-toggle="tab"><span class="fa fa-cutlery"></span> Gaji Pokok </a></li>
						<li id="nav-tunjangan"><a href="#tabs-tunjangan" data-toggle="tab"><span class="fa fa-briefcase"></span> Tunjangan</a></li>
						<li id="nav-rekening"><a href="#tabs-rekening" data-toggle="tab"><span class="fa fa-database"></span> Rekening </a></li>
						<li id="nav-pajak"><a href="#tabs-pajak" data-toggle="tab"><span class="fa fa-briefcase"></span> Pajak </a></li>
						<li id="nav-bpjs"><a href="#tabs-bpjs" data-toggle="tab"><span class="fa fa-shopping-basket"></span> BPJS </a></li>
						<li id="nav-fasilitas"><a href="#tabs-fasilitas" data-toggle="tab"><span class="fa fa-car"></span> Fasilitas </a></li>
						<li id="nav-keluarga"><a href="#tabs-keluarga" data-toggle="tab"><span class="fa fa-user-plus"></span> Keluarga</a></li>
						<li id="nav-pendidikan"><a href="#tabs-pendidikan" data-toggle="tab"><span class="fa fa-graduation-cap"></span> Pendidikan</a></li>
						<li id="nav-kompetensi"><a href="#tabs-kompetensi" data-toggle="tab"><span class="fa fa-heartbeat"></span> Kompetensi </a></li>
						<li id="nav-prestasi"><a href="#tabs-prestasi" data-toggle="tab"><span class="fa fa-train"></span> Prestasi </a></li>
						<li id="nav-pengalaman-kerja"><a href="#tabs-pengalaman-kerja" data-toggle="tab"><span class="fa fa-camera"></span> Pengalaman Kerja</a></li>							
						<li id="nav-pelatihan-seminar"><a href="#tabs-pelatihan-seminar" data-toggle="tab"><span class="fa fa-desktop"></span> Pelatihan / Seminar </a></li>
						<li id="nav-sanksi-peringatan"><a href="#tabs-sanksi-peringatan" data-toggle="tab"><span class="fa fa-legal"></span> Sanksi / Peringatan </a></li>
						<li id="nav-kewajiban"><a href="#tabs-kewajiban" data-toggle="tab"><span class="fa fa-group"></span> Kewajiban </a></li>
					</ul>
					
					<div class="tab-content tab-content-bordered">
					
						<div class="tab-pane active" id="tabs-data-pokok">
							<form name="form-create-edit" id="form-create-edit" method="POST" class="form-horizontal">
								<input type="hidden" name="txtid_pokok" id="txtid_pokok">
								<div class="row">
									<h1 style="text-align: center" class="text-info">Data Pokok Karyawan</h1>
									<div class="col-md-6">										
										<div class="form-group">
											<label class="col-md-4 control-label">NIK</label>
											<div class="col-md-8">
												<input type="text" name="txtnik_pokok" id="txtnik_pokok" class="form-control" placeholder="Nomor Induk Karyawan" required readonly>
											</div>
										</div>
										<div class="form-group group-nama-pokok">
											<label class="col-md-4 control-label">Nama</label>
											<div class="col-md-8">												
												<input type="text" name="txtnama_pokok" id="txtnama_pokok" class="form-control" placeholder="Nama Karyawan" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Jenis Kelamin</label>
											<div class="col-md-8">
												<select id="cbojeniskelamin_pokok" name="cbojeniskelamin_pokok" class="bs-select" data-live-search="true">												
													<?php 
														foreach ($jenis_kelamin as $key => $val) {
															echo '<option value="'.$key.'">'.$val.'</option>';
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group group-tempatlahir-pokok">
											<label class="col-md-4 control-label">Tempat Lahir</label>
											<div class="col-md-8">
												<input type="text" name="txttempatlahir_pokok"  id="txttempatlahir_pokok" class="form-control" placeholder="Tempat Lahir">
											</div>
										</div>
										<div class="form-group group-tanggallahir-pokok">
											<label class="col-md-4 control-label">Tanggal Lahir</label>
											<div class="col-md-8">
												<input type="text" name="txttanggallahir_pokok" id="txttanggallahir_pokok" class="form-control" placeholder="Tanggal Lahir" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Alamat</label>
											<div class="col-md-8">
												<textarea name="txtalamat_pokok" id="txtalamat_pokok" class="form-control" rows="5" placeholder="Alamat"></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Provinsi</label>
											<div class="col-md-8">
												<select id="cboprovinsi_pokok" name="cboprovinsi_pokok" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Provinsi</option>
													<?php 
														foreach($get_provinsi->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Kab./Kota</label>
											<div class="col-md-8">
												<select id="cbokabkota_pokok" name="cbokabkota_pokok" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Kab./Kota</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Kode Pos</label>
											<div class="col-md-8">
												<input type="text" name="txtkodepos_pokok" id="txtkodepos_pokok" class="form-control" placeholder="Kode Pos">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group group-notelp-pokok">
											<label class="col-md-4 control-label">No. Telp.</label>
											<div class="col-md-8">
												<input type="text" name="txtnomortelepon_pokok" id="txtnomortelepon_pokok" class="form-control" placeholder="No. Telp.">
											</div>
										</div>
										<div class="form-group group-nohp-pokok">
											<label class="col-md-4 control-label">No. HP</label>
											<div class="col-md-8">
												<input type="text" name="txtnomorhp_pokok" id="txtnomorhp_pokok" class="form-control" placeholder="No. HP">
											</div>
										</div>
										<div class="form-group group-nowa-pokok">
											<label class="col-md-4 control-label">No. WA</label>
											<div class="col-md-8">
												<input type="text" name="txtnomorwa_pokok" id="txtnomorwa_pokok" class="form-control" placeholder="No. WA">
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Email</label>
											<div class="col-md-8">
												<input type="text" name="txtemail_pokok" id="txtemail_pokok" class="form-control" placeholder="Email">
											</div>
										</div>
										<div class="form-group group-noktp-pokok">
											<label class="col-md-4 control-label">Nomor KTP</label>
											<div class="col-md-8">
												<input type="text" name="txtnomorktp_pokok" id="txtnomorktp_pokok" class="form-control" placeholder="Nomor KTP">
											</div>
										</div>
										<div class="form-group group-filektp-pokok">
											<label class="col-md-4 control-label">File KTP</label>
											<div class="col-md-8">
												<input type="file" name="filektp_pokok" accept="image/*">
											</div>
										</div>
										<div class="form-group group-filefoto-pokok">
											<label class="col-md-4 control-label">File Foto</label>
											<div class="col-md-8">
												<input type="file" name="filefoto_pokok" accept="image/*">
											</div>
										</div>
										<div class="form-group group-unitkerja-pokok">
											<label class="col-md-4 control-label">Unit Kerja</label>
											<div class="col-md-8">
												<select id="cbounitkerja_pokok" name="cbounitkerja_pokok" class="bs-select" data-live-search="true">												
													<option selected hidden value="0">Pilih Unit Kerja</option>
													<?php 
														foreach($get_unit_kerja->result() as $row) {
															echo "<option value=".$row->id."
																		  kode=".$row->kode.">".$row->nama."</option>";
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Keterangan</label>
											<div class="col-md-8">
												<textarea name="txtketerangan_pokok" id="txtketerangan_pokok" class="form-control" rows="5" placeholder="Keterangan"></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">&nbsp;</label>
											<div class="col-md-8">
												<p class="pull-right"><input type='checkbox' name='chkaktif_pokok' id='chkaktif_pokok'> Aktif</p>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
						
						<div class="tab-pane" id="tabs-unit-kerja">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Unit Kerja & Jabatan Karyawan</h1>
									<form name="form-jabatan" id="form-jabatan" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-4 group-unitkerja-jabatan">
												<label class="text-success">Unit Kerja</label>
												<select id="cbounitkerja_jabatan" name="cbounitkerja_jabatan" class="bs-select" data-live-search="true">												
													<option selected hidden value="0">Pilih Unit Kerja</option>
													<?php 
														foreach($get_unit_kerja->result() as $row) {
															echo "<option value=".$row->id."
																		  kode=".$row->kode.">".$row->nama."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-md-4 group-jabatan-jabatan">
												<label class="text-success">Jabatan</label>
												<select id="cbojabatan_jabatan" name="cbojabatan_jabatan" class="bs-select" data-live-search="true">												
													<option selected hidden value="0">Pilih Jabatan</option>
												</select>
											</div>
											<div class="col-md-2 group-golongan-jabatan">
												<label class="text-success">Golongan</label>
												<input type="text" name="txtgolongan_jabatan" id="txtgolongan_jabatan" class="form-control" placeholder="Golongan" />
											</div>
											<div class="col-md-2 group-ruang-jabatan">
												<label class="text-success">Ruang</label>
												<input type="text" name="txtruang_jabatan" id="txtruang_jabatan" class="form-control" placeholder="Ruang" />
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-2 group-tmtkerja-jabatan">
												<label class="text-success">TMT Kerja</label>
												<input type="text" name="txttmtkerja_jabatan" id="txttmtkerja_jabatan" class="form-control" placeholder="TMT Kerja" />
											</div>
											<div class="col-md-2 group-tstkerja-jabatan">
												<label class="text-success">TST Kerja</label>
												<input type="text" name="txttstkerja_jabatan" id="txttstkerja_jabatan" class="form-control" placeholder="TST Kerja" />
											</div>
											<div class="col-md-2 group-nomorsk-jabatan">
												<label class="text-success">Nomor SK</label>
												<input type="text" name="txtnomorsk_jabatan" id="txtnomorsk_jabatan" class="form-control" placeholder="Nomor SK" />
											</div>
											<div class="col-md-2 group-tanggalsk-jabatan">
												<label class="text-success">Tanggal SK</label>
												<input type="text" name="txttanggalsk_jabatan" id="txttanggalsk_jabatan" class="form-control" placeholder="Tanggal SK" />
											</div>
											<div class="col-md-2 group-filesk-jabatan">
												<label class="text-success">File SK</label>
												<input type="file" name="filesk_jabatan" accept="image/*">
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_jabatan" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_jabatan">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style="display: none">ID Unit Kerja</th>
												<th style='text-align: center;'>Unit Kerja</th>
												<th style="display: none">ID Jabatan</th>
												<th style='text-align: center;'>Jabatan</th>
												<th style='text-align: center;'>Golongan</th>
												<th style='text-align: center;'>Ruang</th>
												<th style='text-align: center;'>TMT Kerja</th>
												<th style='text-align: center;'>TST Kerja</th>
												<th style='text-align: center;'>Nomor SK</th>
												<th style='text-align: center;'>Tanggal SK</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_jabatan"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-presensi-cuti">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Presensi & Cuti Karyawan</h1>
									<a href="hr/cuti" target="_blank">Pengajuan Ijin & Cuti</a>
									<div>&nbsp;</div>
									<form name="form-presensi-cuti" id="form-presensi-cuti" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-2 group-tahun-presensi-cuti">
												<label class="text-success">Tahun</label>
												<input type="text" name="txttahun_presensi_cuti" id="txttahun_presensi_cuti" class="form-control" placeholder="Tahun" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-bulan-presensi-cuti">
												<label class="text-success">Bulan</label>
												<select name="cbobulan_presensi_cuti" id="cbobulan_presensi_cuti" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Bulan</option>
													<?php
														for ($i=1; $i<=12; $i+=1) {
															echo "<option value=".$i.">".set_month_to_string_ind($i)."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-md-2 group-haribekerja-presensi-cuti">
												<label class="text-success">Jml. Hari Bekerja</label>
												<input type="text" name="txtharibekerja_presensi_cuti" id="txtharibekerja_presensi_cuti" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-hariijin-presensi-cuti">
												<label class="text-success">Jml. Hari Ijin</label>
												<input type="text" name="txthariijin_presensi_cuti" id="txthariijin_presensi_cuti" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-haricuti-presensi-cuti">
												<label class="text-success">Jml. Hari Cuti</label>
												<input type="text" name="txtharicuti_presensi_cuti" id="txtharicuti_presensi_cuti" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-harimangkir-presensi-cuti">
												<label class="text-success">Jml. Hari Mangkir</label>
												<input type="text" name="txtharimangkir_presensi_cuti" id="txtharimangkir_presensi_cuti" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-10">
												<label class="text-success">Keterangan</label>
												<input type="text" name="txtketerangan_presensi_cuti" id="txtketerangan_presensi_cuti" class="form-control" placeholder="Keterangan" />
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_presensi_cuti" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_presensi_cuti">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style='width: 10%; text-align: center;'>Tahun</th>
												<th style='width: 10%; text-align: center;'>Bulan</th>
												<th style='width: 10%; text-align: center;'>Jml. Hari Kerja</th>
												<th style='width: 10%; text-align: center;'>Jml. Hari Bekerja</th>
												<th style='width: 10%; text-align: center;'>Jml. Hari Ijin</th>
												<th style='width: 10%; text-align: center;'>Jml. Hari Cuti</th>
												<th style='width: 10%; text-align: center;'>Jml. Hari Mangkir</th>
												<th style='width: 17%;'>Keterangan</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_presensi_cuti"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-gaji">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Gaji Pokok Karyawan</h1>
									<form name="form-gaji" id="form-gaji" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-2 group-tahun-gaji">
												<label class="text-success">Tahun Berlaku</label>
												<input type="text" name="txttahun_gaji" id="txttahun_gaji" class="form-control" placeholder="Tahun" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-bulan-gaji">
												<label class="text-success">Bulan Berlaku</label>
												<select name="cbobulan_gaji" id="cbobulan_gaji" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Bulan</option>
													<?php
														for ($i=1; $i<=12; $i+=1) {
															echo "<option value=".$i.">".set_month_to_string_ind($i)."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-md-2 group-gaji-gaji">
												<label class="text-success">Gaji Pokok</label>
												<input type="text" name="txtgaji_gaji" id="txtgaji_gaji" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2">
												<label class="text-success">Keterangan</label>
												<input type="text" name="txtketerangan_gaji" id="txtketerangan_gaji" class="form-control" placeholder="Keterangan" />
											</div>
											<div class="col-md-2 group-filegaji-gaji">
												<label class="text-success">File Gaji</label>
												<input type="file" name="filegaji_gaji" accept="image/*">
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_gaji" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_gaji">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style='width: 10%; text-align: center;'>Tahun Berlaku</th>
												<th style='width: 10%; text-align: center;'>Bulan Berlaku</th>
												<th style='width: 20%; text-align: center;'>Gaji Pokok</th>
												<th style='width: 47%;'>Keterangan</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_gaji"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-tunjangan">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Tunjangan Karyawan</h1>
									<form name="form-tunjangan" id="form-tunjangan" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-2 group-tunjangan-tunjangan">
												<label class="text-success">Nama Tunjangan</label>
												<select id="cbotunjangan_tunjangan" name="cbotunjangan_tunjangan" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Tunjangan</option>
													<?php 
														foreach($get_tunjangan->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-2 group-tahun-tunjangan">
												<label class="text-success">Tahun Berlaku</label>
												<input type="text" name="txttahun_tunjangan" id="txttahun_tunjangan" class="form-control" placeholder="Tahun" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-bulan-tunjangan">
												<label class="text-success">Bulan Berlaku</label>
												<select name="cbobulan_tunjangan" id="cbobulan_tunjangan" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Bulan</option>
													<?php
														for ($i=1; $i<=12; $i+=1) {
															echo "<option value=".$i.">".set_month_to_string_ind($i)."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-md-2 group-jumlah-tunjangan">
												<label class="text-success">Jumlah Tunjangan</label>
												<input type="text" name="txttunjangan_tunjangan" id="txttunjangan_tunjangan" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2">
												<label class="text-success">Keterangan</label>
												<input type="text" name="txtketerangan_tunjangan" id="txtketerangan_tunjangan" class="form-control" placeholder="Keterangan" />
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_tunjangan" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_tunjangan">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style="display: none">ID Tunjangan</th>
												<th style='width: 20%;'>Nama Tunjangan</th>
												<th style='width: 15%; text-align: center;'>Tahun Berlaku</th>
												<th style='width: 15%; text-align: center;'>Bulan Berlaku</th>
												<th style='width: 15%; text-align: center;'>Jumlah Tunjangan</th>
												<th style='width: 22%;'>Keterangan</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_tunjangan"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-rekening">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Rekening Karyawan</h1>
									<form name="form-rekening" id="form-rekening" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-3 group-bank-rekening">
												<label class="text-success">Nama Bank</label>
												<select id="cbobank_rekening" name="cbobank_rekening" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Bank</option>
													<?php 
														foreach($get_bank->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-2 group-nomor-rekening">
												<label class="text-success">Nomor Rekening</label>
												<input type="text" name="txtnomor_rekening" id="txtnomor_rekening" class="form-control" placeholder="Nomor Rekening" />
											</div>
											<div class="col-md-3 group-nama-rekening">
												<label class="text-success">Nama Rekening</label>
												<input type="text" name="txtnama_rekening" id="txtnama_rekening" class="form-control" placeholder="Nama Rekening" />
											</div>
											<div class="col-md-2 group-filerekening-rekening">
												<label class="text-success">File Rekening</label>
												<input type="file" name="filerekening_rekening" accept="image/*">
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_rekening" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_rekening">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style="display: none">ID Bank</th>
												<th style='width: 20%;'>Nama Bank</th>
												<th style='width: 16%; text-align: center;'>Nomor Rekening</th>
												<th style='width: 51%; text-align: center;'>Nama Rekening</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_rekening"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-pajak">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Pajak Karyawan</h1>
									<form name="form-pajak" id="form-pajak" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-2 group-nama-pajak">
												<label class="text-success">Nama Pajak</label>
												<select id="cbopajak_pajak" name="cbopajak_pajak" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Pajak</option>
													<?php 
														foreach($get_pajak->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-2 group-jumlah-pajak-karyawan">
												<label class="text-success">Jumlah (Karyawan)</label>
												<input type="text" name="txtpajakkaryawan_pajak" id="txtpajakkaryawan_pajak" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-jumlah-pajak-perusahaan">
												<label class="text-success">Jumlah (Perusahaan)</label>
												<input type="text" name="txtpajakperusahaan_pajak" id="txtpajakperusahaan_pajak" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-4">
												<label class="text-success">Keterangan</label>
												<input type="text" name="txtketerangan_pajak" id="txtketerangan_pajak" class="form-control" placeholder="Keterangan" />
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_pajak" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_pajak">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style="display: none">ID Pajak</th>
												<th style='width: 20%;'>Nama Pajak</th>
												<th style='width: 15%; text-align: center;'>Jumlah (Karyawan)</th>
												<th style='width: 15%; text-align: center;'>Jumlah (Perusahaan)</th>
												<th style='width: 37%;'>Keterangan</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_pajak"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-bpjs">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data BPJS Karyawan</h1>
									<form name="form-bpjs" id="form-bpjs" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-2 group-nama-bpjs">
												<label class="text-success">Nama BPJS</label>
												<select id="cbobpjs_bpjs" name="cbobpjs_bpjs" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih BPJS</option>
													<?php 
														foreach($get_bpjs->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-2 group-kelas-bpjs">
												<label class="text-success">Kelas</label>
												<select id="cbokelas_bpjs" name="cbokelas_bpjs" class="bs-select" data-live-search="true">												
													<?php 
														foreach ($kelas_bpjs as $key => $val) {
															echo '<option value="'.$key.'">'.$val.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-2 group-jumlah-bpjs-karyawan">
												<label class="text-success">Jumlah (Karyawan)</label>
												<input type="text" name="txtjumlahkaryawan_bpjs" id="txtjumlahkaryawan_bpjs" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-jumlah-bpjs-perusahaan">
												<label class="text-success">Jumlah (Perusahaan)</label>
												<input type="text" name="txtjumlahperusahaan_bpjs" id="txtjumlahperusahaan_bpjs" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2">
												<label class="text-success">Keterangan</label>
												<input type="text" name="txtketerangan_bpjs" id="txtketerangan_bpjs" class="form-control" placeholder="Keterangan" />
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_bpjs" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_bpjs">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style="display: none">ID BPJS</th>
												<th style='width: 20%;'>Nama BPJS</th>
												<th style='width: 10%; text-align: center;'>Kelas</th>
												<th style='width: 15%; text-align: center;'>Jumlah (Karyawan)</th>
												<th style='width: 15%; text-align: center;'>Jumlah (Perusahaan)</th>
												<th style='width: 27%;'>Keterangan</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_bpjs"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-fasilitas">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Fasilitas Karyawan</h1>
									<form name="form-fasilitas" id="form-fasilitas" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-2 group-nama-fasilitas">
												<label class="text-success">Nama Fasilitas</label>
												<select id="cbofasilitas_fasilitas" name="cbofasilitas_fasilitas" class="bs-select" data-live-search="true">												
													<option selected hidden value="0">Pilih Nama Fasilitas</option>
													<?php 
														foreach($get_fasilitas->result() as $row) {
															echo "<option value=".$row->id.">".$row->nama."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-md-2 group-tanggaldiberikan-fasilitas">
												<label class="text-success">Tanggal Diberikan</label>
												<input type="text" name="txttanggaldiberikan_fasilitas" id="txttanggaldiberikan_fasilitas" class="form-control" placeholder="Tanggal Diberikan" />
											</div>
											<div class="col-md-2 group-tanggaldikembalikan-fasilitas">
												<label class="text-success">Tanggal Dikembalikan</label>
												<input type="text" name="txttanggaldikembalikan_fasilitas" id="txttanggaldikembalikan_fasilitas" class="form-control" placeholder="Tanggal Dikembalikan" />
											</div>
											<div class="col-md-4">
												<label class="text-success">Keterangan</label>
												<input type="text" name="txtketerangan_fasilitas" id="txtketerangan_fasilitas" class="form-control" placeholder="Keterangan" />
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_fasilitas" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_fasilitas">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style="display: none">ID Fasilitas</th>
												<th style='width: 20%;'>Nama Fasilitas</th>
												<th style='width: 15%; text-align: center;'>Tanggal Diberikan</th>
												<th style='width: 15%; text-align: center;'>Tanggal Dikembalikan</th>
												<th style='width: 37%;'>Keterangan</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_fasilitas"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-keluarga">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Keluarga Karyawan</h1>
									<form name="form-keluarga" id="form-keluarga" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-3 group-nama-keluarga">
												<label class="text-success">Nama Anggota Keluarga</label>
												<input type="text" name="txtnama_keluarga" id="txtnama_keluarga" class="form-control" placeholder="Nama Anggota Keluarga" />
											</div>
											<div class="col-md-3 group-nik-keluarga">
												<label class="text-success">NIK Anggota Keluarga</label>
												<input type="text" name="txtnik_keluarga" id="txtnik_keluarga" class="form-control" placeholder="Nomor NIK" />
											</div>
											<div class="col-md-3 group-jeniskelamin-keluarga">
												<label class="text-success">Jenis Kelamin</label>
												<select id="cbojeniskelamin_keluarga" name="cbojeniskelamin_keluarga" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Jenis Kelamin</option>
													<?php
														foreach ($jenis_kelamin as $key => $val) {
															echo '<option value="'.$key.'">'.$val.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-3 group-agama-keluarga">
												<label class="text-success">Agama</label>
												<select id="cboagama_keluarga" name="cboagama_keluarga" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Agama</option>
													<?php
														foreach($get_agama->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>												
										</div>
										<div class="form-group">
											<div class="col-md-3 group-pendidikan-keluarga">
												<label class="text-success">Jenjang Pendidikan</label>
												<select id="cbopendidikan_keluarga" name="cbopendidikan_keluarga" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Jenjang Pendidikan</option>
													<?php
														foreach($get_pendidikan->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-3 group-tempatlahir-keluarga">
												<label class="text-success">Tempat Lahir</label>
												<input type="text" name="txttempatlahir_keluarga" id="txttempatlahir_keluarga" class="form-control" placeholder="Tempat Lahir" />
											</div>
											<div class="col-md-3 group-tanggallahir-keluarga">
												<label class="text-success">Tanggal Lahir</label>
												<input type="text" name="txttanggallahir_keluarga" id="txttanggallahir_keluarga" class="form-control" placeholder="Tanggal Lahir" />
											</div>
											<div class="col-md-3 group-pekerjaan-keluarga">
												<label class="text-success">Pekerjaan</label>
												<select id="cbopekerjaan_keluarga" name="cbopekerjaan_keluarga" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Pekerjaan</option>
													<?php
														foreach($get_pekerjaan->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>												
										</div>
										<div class="form-group">
											<div class="col-md-3 group-statuskawin-keluarga">
												<label class="text-success">Status Kawin</label>
												<select id="cbostatuskawin_keluarga" name="cbostatuskawin_keluarga" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Status Kawin</option>
													<?php
														foreach ($status_kawin as $key => $val) {
															echo '<option value="'.$key.'">'.$val.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-3 group-statuskeluarga-keluarga">
												<label class="text-success">Status Keluarga</label>
												<select id="cbostatus_keluarga" name="cbostatus_keluarga" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Status Keluarga</option>
													<?php
														foreach($get_status_keluarga->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-3 group-kewarganegaraan-keluarga">
												<label class="text-success">Kewarganegaraan</label>
												<select id="cbokewarganegaraan_keluarga" name="cbokewarganegaraan_keluarga" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Kewarganegaraan</option>
													<?php
														foreach ($kewarganegaraan as $key => $val) {
															echo '<option value="'.$key.'">'.$val.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-3 group-namaayah-keluarga">
												<label class="text-success">Nama Ayah</label>
												<input type="text" name="txtnamaayah_keluarga" id="txtnamaayah_keluarga" class="form-control" placeholder="Nama Ayah" />
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-3 group-namaibu-keluarga">
												<label class="text-success">Nama Ibu</label>
												<input type="text" name="txtnamaibu_keluarga" id="txtnamaibu_keluarga" class="form-control" placeholder="Nama Ibu" />
											</div>
											<div class="col-md-3 group-nokk-keluarga">
												<label class="text-success">No. KK</label>
												<input type="text" name="txtnokk_keluarga" id="txtnokk_keluarga" class="form-control" placeholder="No. KK" />
											</div>
											<div class="col-md-2 group-filektp-keluarga">
												<label class="text-success">File KTP</label>
												<input type="file" name="filektp_keluarga" accept="image/*">
											</div>
											<div class="col-md-2 group-filekk-keluarga">
												<label class="text-success">File KK</label>
												<input type="file" name="filekk_keluarga" accept="image/*">
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_keluarga" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_keluarga" style="width: 95%">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th>Nama</th>
												<th style='text-align: center;'>NIK</th>
												<th style='text-align: center;'>Jenis Kelamin</th>
												<th style='text-align: center;'>Tempat Lahir</th>
												<th style='text-align: center;'>Tanggal Lahir</th>
												<th style="display: none">ID Agama</th>
												<th style='text-align: center;'>Agama</th>													
												<th style="display: none">ID Pendidikan</th>
												<th style='text-align: center;'>Pendidikan</th>
												<th style="display: none">ID Pekerjaan</th>
												<th style='text-align: center;'>Pekerjaan</th>
												<th style='text-align: center;'>Status Kawin</th>
												<th style="display: none">ID Status Keluarga</th>
												<th style='text-align: center;'>Status Keluarga</th>
												<th style='text-align: center;'>Kewarganegaraan</th>
												<th style='text-align: center;'>Nama Ayah</th>
												<th style='text-align: center;'>Nama Ibu</th>
												<th style='text-align: center;'>No. KK</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_keluarga"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-pendidikan">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Pendidikan Karyawan</h1>
									<form name="form-pendidikan" id="form-pendidikan" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-4 group-jenjang-pendidikan">
												<label class="text-success">Jenjang Pendidikan</label>
												<select id="cbojenjang_pendidikan" name="cbojenjang_pendidikan" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Jenjang Pendidikan</option>
													<?php 
														foreach($get_pendidikan->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-8 group-namainstitusi-pendidikan">
												<label class="text-success">Nama Institusi Pendidikan</label>
												<input type="text" name="txtnamainstitusi_pendidikan" id="txtnamainstitusi_pendidikan" class="form-control" placeholder="Nama Institusi Pendidikan" />
											</div>											
										</div>
										<div class="form-group group-tahunmasuk-pendidikan">
											<div class="col-md-2">
												<label class="text-success">Tahun Masuk</label>
												<input type="text" name="txttahunmasuk_pendidikan" id="txttahunmasuk_pendidikan" class="form-control" placeholder="Tahun Masuk" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-tahunlulus-pendidikan">
												<label class="text-success">Tahun Lulus</label>
												<input type="text" name="txttahunlulus_pendidikan" id="txttahunlulus_pendidikan" class="form-control" placeholder="Tahun Lulus" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-jurusan-pendidikan">
												<label class="text-success">Jurusan</label>
												<input type="text" name="txtjurusan_pendidikan" id="txtjurusan_pendidikan" class="form-control" placeholder="Jurusan Pendidikan" />
											</div>
											<div class="col-md-2 group-ipk-pendidikan">
												<label class="text-success">IPK</label>
												<input type="text" name="txtipk_pendidikan" id="txtipk_pendidikan" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-fileijazah-pendidikan">
												<label class="text-success">File Ijazah</label>
												<input type="file" name="fileijazah_pendidikan" accept="image/*">
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_pendidikan" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_pendidikan">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style="display: none">ID Pendidikan</th>
												<th style='width: 16%; text-align: center;'>Jenjang</th>
												<th style='width: 30%;'>Nama Institusi</th>
												<th style='width: 10%; text-align: center;'>Tahun Masuk</th>
												<th style='width: 10%; text-align: center;'>Tahun Lulus</th>
												<th style='width: 10%; text-align: center;'>Jurusan</th>
												<th style='width: 11%; text-align: right;'>IPK</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_pendidikan"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-kompetensi">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Kompetensi Karyawan</h1>
									<form name="form-kompetensi" id="form-kompetensi" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-3 group-namakeahlian-kompetensi">
												<label class="text-success">Nama Keahlian</label>
												<input type="text" name="txtnamakeahlian_kompetensi" id="txtnamakeahlian_kompetensi" class="form-control" placeholder="Nama Keahlian" />
											</div>
											<div class="col-md-2 group-levelkeahlian-kompetensi">
												<label class="text-success">Level</label>
												<select id="cbolevelkeahlian_kompetensi" name="cbolevelkeahlian_kompetensi" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Level</option>
													<?php
														foreach ($level_keahlian as $key => $val) {
															echo '<option value="'.$key.'">'.$val.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-3">
												<label class="text-success">Keterangan</label>
												<input type="text" name="txtketerangan_kompetensi" id="txtketerangan_kompetensi" class="form-control" placeholder="Keterangan" />
											</div>
											<div class="col-md-2 group-filekompetensi-kompetensi">
												<label class="text-success">File</label>
												<input type="file" name="filekompetensi_kompetensi" accept="image/*">
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_kompetensi" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_kompetensi">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style='width: 30%;'>Nama Keahlian</th>
												<th style='width: 10%; text-align: center;'>Level</th>
												<th style='width: 47%;'>Keterangan</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_kompetensi"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-prestasi">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Prestasi Karyawan</h1>
									<form name="form-prestasi" id="form-prestasi" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-3 group-namaprestasi-prestasi">
												<label class="text-success">Nama Prestasi</label>
												<input type="text" name="txtnamaprestasi_prestasi" id="txtnamaprestasi_prestasi" class="form-control" placeholder="Nama Prestasi" />
											</div>
											<div class="col-md-2 group-tahun-prestasi">
												<label class="text-success">Tahun</label>
												<input type="text" name="txttahun_prestasi" id="txttahun_prestasi" class="form-control" placeholder="Tahun" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-3">
												<label class="text-success">Keterangan</label>
												<input type="text" name="txtketerangan_prestasi" id="txtketerangan_prestasi" class="form-control" placeholder="Keterangan" />
											</div>
											<div class="col-md-2 group-fileprestasi-prestasi">
												<label class="text-success">File</label>
												<input type="file" name="fileprestasi_prestasi" accept="image/*">
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_prestasi" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_prestasi">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style='width: 37%;'>Nama Prestasi</th>
												<th style='width: 10%; text-align: center;'>Tahun</th>
												<th style='width: 40%; text-align: center;'>Keterangan</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_prestasi"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-pengalaman-kerja">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Pengalaman Kerja Karyawan</h1>
									<form name="form-pengalaman-kerja" id="form-pengalaman-kerja" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-6 group-nama-pengalamankerja">
												<label class="text-success">Nama Perusahaan</label>
												<input type="text" name="txtnama_pengalamankerja" id="txtnama_pengalamankerja" class="form-control" placeholder="Nama Perusahaan" />
											</div>
											<div class="col-md-2 group-tahunmasuk-pengalamankerja">
												<label class="text-success">Tahun Masuk</label>
												<input type="text" name="txttahunmasuk_pengalamankerja" id="txttahunmasuk_pengalamankerja" class="form-control" placeholder="Tahun Masuk" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-tahunkeluar-pengalamankerja">
												<label class="text-success">Tahun Keluar</label>
												<input type="text" name="txttahunkeluar_pengalamankerja" id="txttahunkeluar_pengalamankerja" class="form-control" placeholder="Tahun Keluar" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-jabatan-pengalamankerja">
												<label class="text-success">Jabatan</label>
												<input type="text" name="txtjabatan_pengalamankerja" id="txtjabatan_pengalamankerja" class="form-control" placeholder="Jabatan" />
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-2 group-gajiterakhir-pengalamankerja">
												<label class="text-success">Gaji Terakhir</label>
												<input type="text" name="txtgajiterakhir_pengalamankerja" id="txtgajiterakhir_pengalamankerja" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-6 group-alasanberhenti-pengalamankerja">
												<label class="text-success">Alasan Berhenti</label>
												<input type="text" name="txtalasanberhenti_pengalamankerja" id="txtalasanberhenti_pengalamankerja" class="form-control" placeholder="Alasan Berhenti" />
											</div>
											<div class="col-md-2 group-filepengalamankerja-pengalamankerja">
												<label class="text-success">File</label>
												<input type="file" name="filepengalamankerja_pengalamankerja" accept="image/*">
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_pengalaman_kerja" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_pengalaman_kerja">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style='width: 20%;'>Nama Perusahaan</th>
												<th style='width: 10%; text-align: center;'>Tahun Masuk</th>
												<th style='width: 10%; text-align: center;'>Tahun Keluar</th>
												<th style='width: 10%; text-align: center;'>Jabatan</th>
												<th style='width: 11%; text-align: right;'>Gaji Terakhir</th>
												<th style='width: 26%;'>Alasan Berhenti</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_pengalaman_kerja"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-pelatihan-seminar">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Pelatihan / Seminar Karyawan</h1>
									<form name="form-pelatihan" id="form-pelatihan" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-3 group-nama-pelatihan">
												<label class="text-success">Nama Pelatihan</label>
												<input type="text" name="txtnama_pelatihan" id="txtnama_pelatihan" class="form-control" placeholder="Nama Pelatihan" />
											</div>
											<div class="col-md-3 group-namainstitusi-pelatihan">
												<label class="text-success">Nama Institusi</label>
												<input type="text" name="txtnamainstitusi_pelatihan" id="txtnamainstitusi_pelatihan" class="form-control" placeholder="Nama Institusi" />
											</div>
											<div class="col-md-2 group-tahun-pelatihan">
												<label class="text-success">Tahun</label>
												<input type="text" name="txttahun_pelatihan" id="txttahun_pelatihan" class="form-control" placeholder="Tahun" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-2 group-filesertifikat-pelatihan">
												<label class="text-success">File Sertifikat</label>
												<input type="file" name="filesertifikat_pelatihan" accept="image/*">
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_pelatihan" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_pelatihan">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style='width: 40%;'>Nama Pelatihan</th>
												<th style='width: 36%;'>Nama Institusi</th>
												<th style='width: 11%; text-align: center;'>Tahun</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_pelatihan"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-sanksi-peringatan">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Sanksi / Peringatan Karyawan</h1>
									<form name="form-sanksi" id="form-sanksi" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-2 group-sanksi-sanksi">
												<label class="text-success">Nama Sanksi</label>
												<select id="cbosanksi_sanksi" name="cbosanksi_sanksi" class="bs-select" data-live-search="true">												
													<option selected hidden value="0">Pilih Nama Sanksi</option>
													<?php 
														foreach($get_sanksi->result() as $row) {
															echo "<option value=".$row->id.">".$row->nama."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-md-2 group-tanggaldiberikan-sanksi">
												<label class="text-success">Tanggal Diberikan</label>
												<input type="text" name="txttanggaldiberikan_sanksi" id="txttanggaldiberikan_sanksi" class="form-control" placeholder="Tanggal Diberikan" />
											</div>
											<div class="col-md-4">
												<label class="text-success">Keterangan</label>
												<input type="text" name="txtketerangan_sanksi" id="txtketerangan_sanksi" class="form-control" placeholder="Keterangan" />
											</div>
											<div class="col-md-2 group-filesanksi-sanksi">
												<label class="text-success">File Sanksi</label>
												<input type="file" name="filesanksi_sanksi" accept="image/*">
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_sanksi" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_sanksi">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style="display: none">ID Sanksi</th>
												<th style='width: 20%;'>Nama Sanksi</th>
												<th style='width: 20%; text-align: center;'>Tanggal Diberikan</th>
												<th style='width: 47%;'>Keterangan</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_sanksi"></tbody>
									</table>
								</div>
							</div>
						</div>
						
						<div class="tab-pane" id="tabs-kewajiban">
							<div class="row">
								<div class="col-md-12">
									<h1 style="text-align: center" class="text-info">Data Kewajiban Karyawan</h1>
									<form name="form-kewajiban" id="form-kewajiban" class="form-horizontal" role="form">
										<div class="form-group">
											<div class="col-md-2 group-kewajiban-kewajiban">
												<label class="text-success">Nama Kewajiban</label>
												<select id="cbokewajiban_kewajiban" name="cbokewajiban_kewajiban" class="bs-select" data-live-search="true">
													<option selected hidden value="0">Pilih Kewajiban</option>
													<?php 
														foreach($get_kewajiban->result() as $row) {
															echo '<option value="'.$row->id.'">'.$row->nama.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-2 group-tahun-kewajiban">
												<label class="text-success">Tahun</label>
												<input type="text" name="txttahun_kewajiban" id="txttahun_kewajiban" class="form-control" placeholder="Tahun" onkeypress="return hanya_angka(event)" />
											</div>											
											<div class="col-md-2 group-jumlah-kewajiban">
												<label class="text-success">Jumlah</label>
												<input type="text" name="txtjumlah_kewajiban" id="txtjumlah_kewajiban" class="form-control" placeholder="0" value="0" onkeypress="return hanya_angka(event)" />
											</div>
											<div class="col-md-4">
												<label class="text-success">Keterangan</label>
												<input type="text" name="txtketerangan_kewajiban" id="txtketerangan_kewajiban" class="form-control" placeholder="Keterangan" />
											</div>
											<div class="col-md-2">
												<label>&nbsp;</label>
												<button id="submit_detail_kewajiban" type="button" class="btn btn-block btn-danger btn-icon-fixed pull-right"><span class="icon-plus"></span> Tambah</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-striped" id="table_kewajiban">
										<thead>
											<tr>
												<th style="display: none">ID</th>
												<th style='width: 4%; text-align: center;'>No.</th>
												<th style="display: none">ID Kewajiban</th>
												<th style='width: 20%;'>Nama Kewajiban</th>
												<th style='width: 15%; text-align: center;'>Tahun</th>
												<th style='width: 15%; text-align: center;'>Jumlah</th>
												<th style='width: 37%;'>Keterangan</th>
												<th style="width: 9%; text-align: center;">Actions</th>
											</tr>
										</thead>
										<tbody id="show_detail_kewajiban"></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>					
				<button type="button" class="btn btn-primary add-edit">Simpan</button>
			</div>
			
		</div>
	</div>            
</div>
<!-- End : Modal Add / Edit -->