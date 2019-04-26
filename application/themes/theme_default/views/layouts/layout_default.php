<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
	<head>                        
        <title>Si Tauhid | <?php echo $title; ?></title>
        
        <!-- META SECTION -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo $this->config->item('PATH_ASSET_APPS') ?>favicon.ico" type="image/x-icon">
        <!-- END META SECTION -->
		
		<base href="<?php echo base_url(); ?>" />
			
        <!-- CSS INCLUDE -->
        <?php echo $template['partials']['script_css']; ?>
        <!-- EOF CSS INCLUDE -->
		
		<!-- jquery css autocomplete -->
		<link href="assets/apps/css/jquery-ui.css" rel="stylesheet">
		<script src="assets/apps/js/jquery-1.9.1.min.js"></script>
    </head>
	
	<body>		
		<!-- APP WRAPPER -->
        <div class="app">
		
			<!-- START APP CONTAINER -->
            <div class="app-container">
			
				<!-- START SIDEBAR -->
				<div class="app-sidebar app-navigation app-navigation-fixed scroll app-navigation-style-default app-navigation-open-hover dir-left" data-type="close-other" data-minimized="minimized">
					<a href="<?php echo base_url(); ?>" class="app-navigation-logo">Project</a>
					
					<nav>
						<ul>
						
                            <li><a id="menu_home" href="<?php echo base_url(); ?>" class="popover-hover" data-placement="top" data-container="body" data-trigger="click" data-content="Home"><span class="icon-home"></span> Home</a></li>
							
							<li class="title">PERSIAPAN</li>
                            <li>
                                <a href="#" class="popover-hover" data-placement="top" data-container="body" data-trigger="click" data-content="Master Data"><span class="icon-files"></span> Master Data</a>
                                <ul>
                                    <!--<li>
                                        <a href="#"><span class="icon-folder"></span> Produk</a>
                                        <ul>
											<li><a id="menu_produk_produk" href="master/produk"><span class="icon-icons"></span> Produk Utama</a></li>
											<li><a id="menu_produk_perbekalan_farmasi" href="master/perbekalan_farmasi"><span class="icon-layers"></span> Perbekalan Farmasi</a></li>
                                            <li><a id="menu_produk_kemasan_produk" href="master/kemasan_produk"><span class="icon-tag"></span> Kemasan Produk</a></li>
											<li><a id="menu_produk_kelompok_produk" href="master/kelompok_produk"><span class="icon-equalizer"></span> Kelompok Produk</a></li>
											<li><a id="menu_produk_satuan_produk" href="master/satuan_produk"><span class="icon-bookmark2"></span> Satuan Produk</a></li>
											<li><a id="menu_produk_bentuk_sediaan" href="master/bentuk_sediaan"><span class="icon-pills"></span> Bentuk Sediaan</a></li>
											<li><a id="menu_produk_klasifikasi_produk" href="master/klasifikasi_produk"><span class="icon-wall2"></span> Klasifikasi Produk</a></li>                                            
                                        </ul>
                                    </li>-->
									<li>
										<a href="#"><span class="icon-folder"></span> Wilayah Kerja</a>
										<ul>
											<li><a href="master/region"><span class="icon-color-sampler"></span> Region</a></li>
                                            <li><a href="master/branch"><span class="icon-color-sampler"></span> Branch</a></li>
                                            <li><a href="master/area"><span class="icon-color-sampler"></span> Area</a></li>
                                            <li><a href="master/office"><span class="icon-color-sampler"></span> Office</a></li>
										</ul>
									</li>
									<li>
										<a href="#"><span class="icon-folder"></span> Wilayah Administrasi</a>
										<ul>
											<li><a href="master/provinsi"><span class="icon-disc"></span> Provinsi</a></li>
											<li><a href="master/kabupaten"><span class="icon-disc"></span> Kabupaten / Kota</a></li>
											<li><a href="master/kecamatan"><span class="icon-disc"></span> Kecamatan</a></li>
											<li><a href="master/kelurahan"><span class="icon-disc"></span> Kelurahan</a></li>
										</ul>
									</li>
									<li>
										<a href="#"><span class="icon-folder"></span> Struktur Organisasi</a>
										<ul>
											<li><a id="menu_master_jabatan" href="master/jabatan"><span class="icon-users"></span> Jabatan</a></li>
                                            <li><a id="menu_master_kelompok_kerja" href="master/kelompok_kerja"><span class="icon-users"></span> Kelompok Kerja</a></li>
                                            <li><a id="menu_master_unit_kerja" href="master/unit_kerja"><span class="icon-users"></span> Unit Kerja</a></li>
										</ul>
									</li>
									<li>
										<a href="#"><span class="icon-folder"></span> Mitra Usaha</a>
										<ul>
											<!--<li><a id="menu_mitrausaha_principal" href="master/principal"><span class="icon-group-work"></span> Principal</a></li>
											<li><a id="menu_mitrausaha_supplier" href="master/supplier"><span class="icon-group-work"></span> Supplier</a></li>-->
											<li><a href="#"><span class="icon-group-work"></span> Customer</a></li>
										</ul>
									</li>
									<li>
										<a href="#"><span class="icon-screen"></span> Sarana & Prasarana</a>
										<ul>
											<li><a id="" href="master/asset_data"><span class="icon-clipboard-pencil"></span> Asset</a></li>
											<li><a id="" href="master/asset_kelompok"><span class="icon-drawers3"></span> Kelompok Asset</a></li>
										</ul>
									</li>
                                </ul>
                            </li>
							
							<li>
                                <a href="#" class="popover-hover" data-placement="top" data-container="body" data-trigger="click" data-content="Pengaturan"><span class="icon-cog"></span> Pengaturan</a>
                                <ul>
                                    <li><a href="#"><span class="icon-home4"></span> Info Perusahaan</a></li>
                                    <li><a href="#"><span class="icon-hammer-wrench"></span> Preferensi</a></li>
                                    <li>
                                        <a href="#"><span class="icon-user"></span> Pengguna</a>
                                        <ul>      
                                            <li><a href="#"><span class="icon-calendar-user"></span> Profil Pengguna</a></li> 
                                            <li><a href="#"><span class="icon-user-lock"></span> Ubah Password</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
							
							<li class="title">HR & GA</li>
							<li>
								<a href="#" class="popover-hover" data-placement="top" data-container="body" data-trigger="click" data-content="HR"><span class="icon-man-woman"></span> Human Resource (HR)</a>
								<ul>
									<li><a id="menu_hrga_karyawan" href="hr/karyawan"><span class="icon-man-woman"></span> Karyawan</a></li>
									<li><a href="#"><span class="icon-files"></span> Master Data</a>
										<ul>
											<li><a id="menu-hrga-master-data-tunjangan" href="hr/data_tunjangan"><span class="icon-files"></span> Data Tunjangan</a></li>
											<li><a id="menu-hrga-master-data-bank" href="hr/data_bank"><span class="icon-files"></span> Data Bank</a></li>
											<li><a id="menu-hrga-master-data-pajak" href="hr/data_pajak"><span class="icon-files"></span> Data Pajak</a></li>
											<li><a id="menu-hrga-master-data-fasilitas" href="hr/data_fasilitas"><span class="icon-files"></span> Data Fasilitas</a></li>
											<li><a id="menu-hrga-master-data-pekerjaan" href="hr/data_pekerjaan"><span class="icon-files"></span> Data Pekerjaan</a></li>
											<li><a id="menu-hrga-master-data-pendidikan" href="hr/data_pendidikan"><span class="icon-files"></span> Data Pendidikan</a></li>
											<li><a id="menu-hrga-master-data-sanksi" href="hr/data_sanksi"><span class="icon-files"></span> Data Sanksi / Peringatan</a></li>
											<li><a id="menu-hrga-master-data-kewajiban" href="hr/data_kewajiban"><span class="icon-files"></span> Data Kewajiban</a></li>											
										</ul>
									</li>
									<li><a href="#"><span class="fa fa-calendar"></span> Presensi</a>
                                        <ul>
                                            <li><a id="menu-hrga-presensi-kalender-kerja" href="hr/kalender_kerja"><span class="icon-calendar-user"></span> Kalender kerja</a></li> 
                                            <li><a href="#"><span class="icon-user-lock"></span> Kehadiran</a></li>
											<li><a id="menu-hrga-presensi-jenis-cuti" href="hr/data_cuti"><span class="icon-files"></span> Jenis Cuti</a></li>
                                            <li><a id="menu-hrga-presensi-cuti" href="hr/cuti"><span class="icon-user-lock"></span> Cuti</a></li>
                                        </ul>
                                    </li>
									<li><a href="#"><span class="icon-man-woman"></span> Recruitment</a>
                                        <ul>      
                                            <li><a href="#"><span class="icon-user-lock"></span> Request Resource </a></li> 
                                            <li><a href="#"><span class="icon-user-lock"></span> Procurement</a>
												<ul>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Penjadwalan </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Proses Seleksi</a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Kontrak Kerja</a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Assignment </a></li>
												</ul>
											</li>
                                            <li><a href="#"><span class="icon-user-lock"></span> Exit Resource</a>
												<ul>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Exit Interview </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Exit Clearance</a></li>
												</ul>
											</li>
                                        </ul>
                                    </li>
									<li><a href="#"><span class="icon-man-woman"></span> Pelatihan / Seminar</a>
                                        <ul>      
                                            <li><a href="#"><span class="icon-user-lock"></span> Request Resource</a></li> 
                                            <li><a href="#"><span class="icon-user-lock"></span> Pengelolaan Pelatihan </a>
												<ul>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Penjadwalan </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Pendaftaran Peserta </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Pelaksanaan </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Sertifikasi </a></li>
												</ul>
											</li>
                                        </ul>
                                    </li>
									<li><a href="#"><span class="icon-man-woman"></span> Balance Score Card (BSC)</a></li>
									<li><a href="#"><span class="icon-man-woman"></span> Peraturan & Pedoman Kerja</a></li>
								</ul>
							</li>
							<li>
								<a href="#" class="popover-hover" data-placement="top" data-container="body" data-trigger="click" data-content="GA"><span class="icon-socks"></span> General Affair (GA)</a>
								<ul>
									<li><a href="#"><span class="icon-socks"></span> Sarana & Prasana</a>
                                        <ul>      
                                            <li><a href="#"><span class="icon-user-lock"></span> Sarana </a>
												<ul>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Histori Perawatan </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Histori Perbaikan </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Kelengkapan Surat  </a></li>
												</ul>
											</li> 
                                            <li><a href="#"><span class="icon-user-lock"></span> Procurement </a>
												<ul>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Histori Perawatan </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Histori Perbaikan </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Alokasi Pengunaan </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Alokasi Sarana Terhapus </a></li>
												</ul>
											</li> 
                                            <li><a href="#"><span class="icon-user-lock"></span> Prasarana  </a>
												<ul>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Histori Perawatan </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Histori Perbaikan </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Kelengkapan Surat  </a></li>
												</ul>
											</li>
										</ul>
									<li><a href="#"><span class="icon-socks"></span> Layanan Kantor</a>
                                        <ul>      
                                            <li><a href="#"><span class="icon-user-lock"></span> Cleaning Service / Office Boy </a>
												<ul>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Schedule</a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span>Reward & Complain</a></li>
												</ul>
											</li> 
                                            <li><a href="#"><span class="icon-user-lock"></span> Kurir</a>
												<ul>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Ticketing</a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Delivery by Schedule</a></li>
												</ul>
											</li>
										</ul>
									</li>
									<li><a href="#"><span class="icon-socks"></span> Pengadaan</a>
												<ul>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Request Sarana</a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Penjadwalan </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Pelaksanaan  </a></li>
													<li><a id="" href="#"><span class="icon-user-lock"></span> Monitoring Status Request </a></li>
												</ul>
											</li>
								</ul>
							</li>
                            
                            <li class="title">SUPPLY CHAIN MANAGEMENT</li>
                            <li id="menu_pembelian">
								<a href="javascript:void();" class="popover-hover" data-placement="top" data-container="body" data-trigger="click" data-content="Pembelian"><span class="icon-cart-full"></span> Pembelian</a>
								<ul>
									<li>
										<a href="javascript:void();"><span class="icon-folder"></span> Purchase Request</a>
										<ul>
											<li><a id="menu_pembelian_pr" href="pembelian/purchase_request"><span class="icon-cart-plus2"></span> Purchase Request (PR)</a></li>
											<li><a id="menu_pembelian_approve_pr" href="pembelian/approve_pr"><span class="icon-link2"></span> Approve PR</a></li>
										</ul>
									</li>
									<li>
										<a href="javascript:void();"><span class="icon-folder"></span> Purchase Order</a>
										<ul>
											<li><a id="menu_pembelian_po" href="pembelian/purchase_order"><span class="icon-clipboard-text"></span> Purchase Order (PO)</a></li>
											<li><a id="menu_pembelian_approve_po" href="pembelian/approve_po"><span class="icon-link2"></span> Approve PO</a></li>
											<li><a id="menu_pembelian_send_po" href="pembelian/send_po"><span class="icon-bag"></span> Send PO</a></li>
										</ul>
									</li>
									<li>
										<a href="javascript:void();"><span class="icon-folder"></span> Goods Receive</a>
										<ul>
											<li><a id="menu_pembelian_gr" href="pembelian/goods_receive"><span class="icon-tags"></span> Goods Receive (GR)</a></li>
										</ul>
									</li>
									<li>
										<a href="javascript:void();"><span class="icon-folder"></span> Finance</a>
										<ul>
											<li><a id="menu_pembelian_ap" href="pembelian/account_payable"><span class="icon-cash-dollar"></span> Account Payable (AP)</a></li>
											<li>
												<a href="javascript:void();"><span class="icon-folder"></span> Tagihan</a>
												<ul>
													<li><a id="menu_pembelian_tagihan" href="pembelian/penerimaan_tagihan"><span class="icon-new-tab"></span> Penerimaan Tagihan (PT)</a></li>
													<li><a id="menu_pembelian_validasi_tagihan" href="pembelian/validasi_tagihan"><span class="icon-list3"></span> Validasi Tagihan</a></li>
												</ul>
											</li>
										</ul>
									</li>
									<li>
										<a href="javascript:void();"><span class="icon-folder"></span> Pembayaran</a>
										<ul>
											<li><a id="menu_pembelian_pb" href="pembelian/pengajuan_pembayaran"><span class="icon-receipt"></span> Pengajuan Pembayaran (PB)</a></li>
											<li><a id="menu_pembelian_approve_pb" href="pembelian/approve_pb"><span class="icon-link2"></span> Approve PB</a></li>
											<li><a id="menu_pembelian_payment_ap" href="pembelian/payment_ap"><span class="icon-bag-dollar"></span> Payment AP</a></li>													
										</ul>
									</li>
									<li><a id="menu_pembelian_ap_settlement" href="pembelian/ap_settlement"><span class="icon-briefcase"></span> AP Settlement</a></li>
								</ul>
							</li>
							<li id="menu_penjualan">
                                <a href="#" class="popover-hover" data-placement="top" data-container="body" data-trigger="click" data-content="Penjualan"><span class="icon-bag2"></span> Penjualan</a>
                                <ul>
                                    <li><a id="menu_penjualan_quotation" href="penjualan/quotation"><span class="icon-clipboard-pencil"></span>Quotation</a></li>
                                    
                                    <li>
										<a href="javascript:void();"><span class="icon-folder"></span> Purchase Order</a>
										<ul>
											<li><a id="menu_penjualan_po" href="penjualan/po_customer"><span class="icon-cart-add"></span> Create Purchase Order (PO)</a></li> 
											<li><a id="menu_approval" href="penjualan/approvalpo"><span class="icon-cart-add"></span> Approval PO</a></li> 
										</ul>
									</li>
                                    <li>
										<a href="javascript:void();"><span class="icon-folder"></span> Sales Order</a>
										<ul>
											<li><a id="menu_so" href="penjualan/sales_order"><span class="icon-clipboard-text"></span> Sales Order (SO)</a></li>
										</ul>
									</li>
                                    <li>
										<a href="javascript:void();"><span class="icon-folder"></span> Logistic</a>
										<ul>
											<li><a id="menu_picking" href="penjualan/pickinglist"><span class="icon-check"></span> Picking List</a></li> 
											<li><a id="menu_dn" href="penjualan/deliveryNote"><span class="icon-luggage-weight"></span> Delivery Note</a></li>
										</ul>
									</li>
                                    <li>
										<a href="javascript:void();"><span class="icon-folder"></span> Finance</a>
										<ul>
											<li><a id="menu_inv" href="penjualan/faktur_penjualan"><span class="icon-luggage-weight"></span> Faktur Penjualan</a></li> 
											<li><a id="menu_ar" href="penjualan/account_receivable"><span class="icon-calendar-check"></span> Account Receivable</a></li>
											<li><a id="menu_tagihan_penjualan" href="penjualan/tagihan_penjualan"><span class="icon-calendar-check"></span> Tagihan Penjualan</a></li>
											<li><a id="menu_penerimaan_pembayaran" href="penjualan/penerimaan_pembayaran"><span class="icon-calendar-check"></span> Penerimaan Pembayaran</a></li>
											<li><a id="menu_ars" href="penjualan/ar_settlement"><span class="icon-calendar-check"></span> AR Settlement</a></li>
										</ul>
									</li>
									<li><a id="menu_realisasi" href="penjualan/pencatatan_realisasi"><span class="icon-calendar-check"></span> Pencatatan Realisasi</a></li>
                                    <li><a id="menu_bo" href="penjualan/back_order"><span class="icon-calendar-check"></span> Back Order</a></li>
                                    <!--li><a href="#"><span class="icon-calendar-cross"></span> Retur Penjualan</a></li-->
                                </ul>
                            </li>
							<li id="menu_logistik">
								<a href="javascript:void();" class="popover-hover" data-placement="top" data-container="body" data-trigger="click" data-content="Logistik"><span class="icon-truck"></span> Logistik</a>
								<ul>
									<li>
                                        <a href="javascript:void();"><span class="icon-folder"></span> Manajemen Data</a>
                                        <ul>
											<li>
												<a href="javascript:void();"><span class="icon-folder"></span> Mitra Usaha</a>
												<ul>
													<li><a id="menu_logistik_principal" href="logistik/principal"><span class="icon-group-work"></span> Principal</a></li>
													<!--<li><a id="menu_logistik_principal_alamat" href="logistik/principal_alamat"><span class="icon-road-sign"></span> Alamat Principal</a></li>-->
													<li><a id="menu_logistik_supplier" href="logistik/supplier"><span class="icon-group-work"></span> Supplier</a></li>
													<!--<li><a id="menu_logistik_supplier_alamat" href="logistik/supplier_alamat"><span class="icon-road-sign"></span> Alamat Supplier</a></li>-->
												</ul>
											</li>
											<li>
												<a href="javascript:void();"><span class="icon-folder"></span> Produk</a>
												<ul>
													<li><a id="menu_logistik_produk" href="logistik/produk"><span class="icon-icons"></span> Produk Utama</a></li>
													<!--<li><a id="menu_logistik_produk_per_supplier" href="logistik/produk_per_supplier"><span class="icon-icons"></span> Produk Per Supplier</a></li>-->
													<li><a id="menu_logistik_lokasiproduk" href="logistik/lokasi_produk"><span class="fa fa-anchor"></span>Lokasi Penyimpanan</a></li>
													<!--<li><a id="menu_logistik_perbekalan_farmasi" href="logistik/perbekalan_farmasi"><span class="icon-layers"></span> Perbekalan Farmasi</a></li>-->
													<li><a id="menu_logistik_kemasan_produk" href="logistik/kemasan_produk"><span class="icon-tag"></span> Kemasan Produk</a></li>
													<!--<li><a id="menu_logistik_kelompok_produk" href="logistik/kelompok_produk"><span class="icon-equalizer"></span> Kelompok Produk</a></li>-->
													<li><a id="menu_logistik_satuan_produk" href="logistik/satuan_produk"><span class="icon-bookmark2"></span> Satuan Produk</a></li>
													<li><a id="menu_logistik_bentuk_sediaan" href="logistik/bentuk_sediaan"><span class="icon-pills"></span> Bentuk Sediaan</a></li>
													<!--<li><a id="menu_logistik_klasifikasi_produk" href="logistik/klasifikasi_produk"><span class="icon-wall2"></span> Klasifikasi Produk</a></li>-->
												</ul>
											</li>
											<li>
												<a href="#"><span class="icon-folder"></span> Stock Opname</a>
												<ul>
													<li><a id="menu_logistik_cetakstockopname" href="logistik/cetak_stok_opname"><span class="icon-printer"></span>Cetak (By Lokasi Produk)</a></li>
													<li><a id="menu_logistik_penyesuaiandata" href="logistik/stock_opname"><span class="icon-cloud-sync"></span>Penyesuaian Data</a></li>
												</ul>
											</li>
											<li>
												<a href="#"><span class="icon-folder"></span> Perencanaan Stok</a>
												<ul>
													<li><a id="menu_logistik_forecastpembelian" href="logistik/forecast_pembelian"><span class="fa fa-signal"></span>Daftar Forecasting Produk</a></li>
													<li><a id="menu_logistik_analisapenjualantahunan" href="logistik/analisa_penjualan_tahunan"><span class="fa fa-area-chart"></span>Analisa Penjualan Tahunan</a></li>
													<li><a id="menu_logistik_perencanaanpembelian" href="logistik/perencanaan_pembelian"><span class="fa fa-bar-chart"></span>Input Data Perencanaan</a></li>
												</ul>
											</li>
											<li>
												<a href="#"><span class="icon-folder"></span> Mutasi Stok</a>
												<ul>
													<li><a id="menu_logistik_quarantineproduk" href="logistik/quarantine"><span class="icon-shield-alert"></span>Quarantine & Remove Produk</a></li>
													<li><a id="menu_logistik_returpembelian" href="logistik/retur_pembelian"><span class="icon-repeat"></span>Retur Pembelian</a></li>
												</ul>
											</li>
                                        </ul>										
                                    </li>
									
									<li>
                                        <a href="javascript:void();"><span class="icon-folder"></span> Inquiry</a>
                                        <ul>
											<li>
												<a href="javascript:void();"><span class="icon-folder"></span> Stok</a>
												<ul>
													<li><a id="menu_logistik_stokbywilayah" href="logistik/stok_by_wilayah"><span class="icon-files"></span> By Wilayah</a></li>
													<li><a id="menu_logistik_stokbymitrausaha" href="logistik/stok_by_mitra_usaha"><span class="icon-files"></span> By Mitra Usaha</a></li>
													<li><a id="menu_logistik_stokbyproduk" href="logistik/stok_by_produk"><span class="icon-files"></span> By Produk</a></li>
												</ul>
											</li>
											<li>
												<a href="javascript:void();"><span class="icon-folder"></span> Monitoring Stok</a>
												<ul>
													<li><a id="menu_logistik_stokproduk" href="logistik/stok_produk"><span class="icon-files"></span> Stok Produk</a></li>
													<li><a id="menu_logistik_produkexpired" href="logistik/produk_expired"><span class="icon-files"></span> Produk Expired</a></li>
													<li><a id="menu_logistik_minover" href="logistik/produk_min_over"><span class="icon-files"></span> Min. & Over Stok</a></li>
												</ul>
											</li>
											<li>
												<a href="javascript:void();"><span class="icon-folder"></span> Historikal</a>
												<ul>
													<li><a id="menu_logistik_hitoripenerimaan" href="logistik/histori_penerimaan"><span class="icon-files"></span> Penerimaan</a></li>
													<li><a id="menu_logistik_historipengeluaran" href="logistik/histori_pengeluaran"><span class="icon-files"></span> Pengeluaran</a></li>
													<li><a id="menu_logistik_historipengawasanpemeliharaan" href="logistik/histori_pengawasan_pemeliharaan"><span class="icon-files"></span> Pengawasan & Pemeliharaan</a></li>
												</ul>
											</li>
											<li>
												<a href="javascript:void();"><span class="icon-folder"></span> Analisa / Tren (Grafik)</a>
												<ul>
													<li><a id="menu_logistik_trenpembelian" href="logistik/tren_pembelian"><span class="icon-files"></span> Tren Pembelian</a></li>
													<li><a id="menu_logistik_trenpenjualan" href="logistik/tren_penjualan"><span class="icon-files"></span> Tren Penjualan</a></li>
												</ul>
											</li>
										</ul>
									</li>
									
									<li>
                                        <a href="javascript:void();"><span class="icon-folder"></span> Laporan</a>
                                        <ul>
											<li><a id="menu_logistik_lapstokgudang" href="logistik/laporan_stok_gudang"><span class="icon-files"></span>Laporan Stok Gudang</a></li>
											<li><a id="menu_logistik_lappenerimaan" href="logistik/laporan_penerimaan"><span class="icon-files"></span>Laporan Penerimaan</a></li>
											<li><a id="menu_logistik_lappengiriman" href="logistik/laporan_pengiriman"><span class="icon-files"></span>Laporan Pengiriman</a></li>
											<li><a id="menu_logistik_lapstokopname" href="logistik/laporan_stock_opname"><span class="icon-files"></span>Laporan Stock Opname</a></li>
										</ul>
									</li>
									
									<li>
                                        <a href="javascript:void();"><span class="icon-folder"></span> Maintenance</a>
                                        <ul>
											<li><a id="menu_logistik_exportimportproduk" href="logistik/export_import_produk"><span class="fa fa-download"></span>Export / Import Produk</a></li>
											<li><a id="menu_logistik_pengawasanpemeliharaan" href="logistik/pengawasan_pemeliharaan"><span class="fa fa-hourglass-half"></span>Pengawasan & Pemeliharaan</a></li>
										</ul>
									</li>
								</ul>
							</li>
							
							<li class="title">OPERASIONAL</li>								 
                            <li>
                                <a href="#" class="popover-hover" data-placement="top" data-container="body" data-trigger="click" data-content="Operasional"><span class="icon-graph"></span>OPERASIONAL</a> 
								<ul> 
                                    <li><a id="menu_customer"  href="operasional/customer"><span class="icon-user"></span> Customer Management</a></li>
                                    <!--li><a id="menu_planning"  href="operasional/planning"><span class="icon-pencil5"></span> Global Planning</a></li-->
                                    <li><a id="menu_account_planning"  href="operasional/account_planning"><span class="icon-chart-bars"></span> Account Planning</a></li>
                                    <li><a id="menu_selling_data"  href="operasional/selling_data"><span class="icon-chart-bars"></span> Selling Data</a></li>
                                    <li><a id="menu_marketing_tools"  href="operasional/marketing_tools"><span class="icon-chart-bars"></span> Marketing Tools</a></li>
                                    <li><a id="menu_inquiry"  href="operasional/inquiry"><span class="icon-chart-bars"></span> Inquiry</a></li>
                                    <li><a id="menu_laporan_operasional"  href="operasional/report"><span class="icon-chart-bars"></span> Report</a></li>
								</ul>
                            </li>
							<li class="title">LAPORAN</li>
                            <li>
                                <a href="#" class="popover-hover" data-placement="top" data-container="body" data-trigger="click" data-content="Laporan Keuangan"><span class="icon-screen"></span> Laporan Keuangan</a>                                
								<ul>
                                    <li><a href="#"><span class="icon-printer"></span> Buku Besar</a></li>
									<li><a href="#"><span class="icon-printer"></span> Laba Rugi</a></li>
								</ul>
                            </li>
                        </ul>					
					</nav>
				
				</div>
				<!-- END SIDEBAR -->
				
				<!-- START APP CONTENT -->
                <div class="app-content">
					<!-- START APP HEADER -->
                    <div class="app-header">
                        <ul class="app-header-buttons">
                            <li class="visible-mobile"><a href="#" class="btn btn-link btn-icon" data-sidebar-toggle=".app-sidebar.dir-left"><span class="icon-icons2"></span></a></li>
                            <li class="hidden-mobile"><a href="#" class="btn btn-link btn-icon" data-sidebar-minimize=".app-sidebar.dir-left"><span class="icon-icons2"></span></a></li>
                        </ul>
                        <!--form class="app-header-search" action="" method="post">        
                            <input type="text" name="keyword" placeholder="Search">
                        </form-->    
                    <ul class="breadcrumb" style="margin-top:9px;margin-left:9px;">
                            <li  class="<?php echo $breadcrumb_home_active; ?>"><a href="<?php echo base_url(); ?>"><span class="fa fa-home"></span> Home</a></li>
							<?php echo $breadcrumb; ?>
                        </ul>
                        <ul class="app-header-buttons pull-right">
                            <li>
                                <div class="contact contact-rounded contact-bordered contact-lg contact-ps-controls">
                                    <img src="<?php echo $this->config->item('PATH_ASSET_DESIGN') ?>images/users/user_1.jpg" alt="John Doe">
                                    <div class="contact-container">
                                        <a href="#"><?php echo $_SESSION['user_name'];  ?></a>
                                        <span><?php echo $_SESSION['user_nama']; ?></span>
                                    </div>
                                    <div class="contact-controls">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-default btn-icon" data-toggle="dropdown"><span class="icon-cog"></span></button>                        
                                            <ul class="dropdown-menu dropdown-left">
                                                <li><a href="#"><span class="icon-cog"></span> Settings</a></li> 
                                                <li><a href="#"><span class="icon-envelope"></span> Messages <span class="label label-danger pull-right">+24</span></a></li>
                                                <li><a href="#"><span class="icon-users"></span> Contacts <span class="label label-default pull-right">76</span></a></li>
                                                <li class="divider"></li>
                                                <li><a href="<?php echo 'home/logout'; ?>"><span class="icon-exit-right"></span> Log Out</a></li> 
                                            </ul>
                                        </div>                    
                                    </div>
                                </div>
                            </li>        
                        </ul>
                    </div>
                    <!-- END APP HEADER  -->
					
					<!-- START PAGE HEADING -->
                    <div class="app-heading-container app-heading-bordered bottom" style="display:none;">
                        <ul class="breadcrumb">
                            <li class="<?php echo $breadcrumb_home_active; ?>"><a href="<?php echo base_url(); ?>"><span class="fa fa-home"></span> Home</a></li>
							<?php echo $breadcrumb; ?>
                        </ul>
                    </div>
                    <!-- END PAGE HEADING -->
					
					<!-- START PAGE CONTAINER -->
                    <div class="container">                        
                        <!-- START BLOCk -->
                        <!--<div class="block">-->                      
                            <?php echo $template['body']; ?>
                        <!--</div>-->
                        <!-- END BLOCk -->                        
                    </div>
                    <!-- END PAGE CONTAINER -->
				</div>
				<!-- END APP CONTENT -->
			
			</div>
			<!-- END APP CONTAINER -->
			
			<!-- START APP FOOTER -->
            <!--<div class="app-footer app-footer-default">
            
                <div class="app-footer-line extended">
                    <div class="row">
                        <div class="col-md-3 col-sm-4">
                            <h3 class="title"><img src="/img/logo-footer.png" alt="boooyah"> Boooya</h3>                            
                            <p>The innovation in admin template design. You will save hundred hours while working with our template. That is based on latest technologies and understandable for all.</p>
                            <p><strong>How?</strong><br>This template included with thousand of best components, that really help you to build awesome design.</p>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <h3 class="title"><span class="icon-clipboard-text"></span> About Us</h3>
                            <ul class="list-unstyled">
                                <li><a href="#">About</a></li>                                                                
                                <li><a href="#">Team</a></li>
                                <li><a href="#">Why use us?</a></li>
                                <li><a href="#">Careers</a></li>
                            </ul>
                        </div>
                        <div class="col-md-2 col-sm-4">                            
                            <h3 class="title"><span class="icon-lifebuoy"></span> Need Help?</h3>
                            <ul class="list-unstyled">
                                <li><a href="#">FAQ</a></li>                                                                
                                <li><a href="#">Community</a></li>
                                <li><a href="#">Contacts</a></li>
                                <li><a href="#">Terms & Conditions</a></li>
                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-6 clear-mobile">
                            <h3 class="title"><span class="icon-reading"></span> Latest News</h3>
            
                            <div class="row app-footer-articles">
                                <div class="col-md-3 col-sm-4">
                                    <img src="/assets/images/preview/img-1.jpg" alt="" class="img-responsive">
                                </div>
                                <div class="col-md-9 col-sm-8">
                                    <a href="#">Best way to increase vocabulary</a>
                                    <p>Quod quam magnum sit fictae veterum fabulae declarant, in quibus tam multis.</p>
                                </div>
                            </div>
            
                            <div class="row app-footer-articles">
                                <div class="col-md-3 col-sm-4">
                                    <img src="/assets/images/preview/img-2.jpg" alt="" class="img-responsive">
                                </div>
                                <div class="col-md-9 col-sm-8">
                                    <a href="#">Best way to increase vocabulary</a>
                                    <p>In quibus tam multis tamque variis ab ultima antiquitate repetitis tria.</p>
                                </div>
                            </div>
            
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <h3 class="title"><span class="icon-thumbs-up"></span> Social Media</h3>
            
                            <a href="#" class="label-icon label-icon-footer label-icon-bordered label-icon-rounded label-icon-lg">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a href="#" class="label-icon label-icon-footer label-icon-bordered label-icon-rounded label-icon-lg">
                                <i class="fa fa-twitter"></i>
                            </a>
                            <a href="#" class="label-icon label-icon-footer label-icon-bordered label-icon-rounded label-icon-lg">
                                <i class="fa fa-youtube"></i>
                            </a>
                            <a href="#" class="label-icon label-icon-footer label-icon-bordered label-icon-rounded label-icon-lg">
                                <i class="fa fa-google-plus"></i>
                            </a>
            
                            <h3 class="title"><span class="icon-paper-plane"></span> Subscribe</h3>
            
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="E-mail...">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary">GO</button>
                                </div>
                            </div> 
                        </div>                        
                    </div>                   
                </div>
                <div class="app-footer-line darken">                
                    <div class="copyright wide text-center">&copy; 2017 PT TATA USAHA INDONESIA. Template By Boooya</div>                
                </div>
            </div>-->
            <!-- END APP FOOTER -->
		<!-- END APP WRAPPER -->
		
		<?php echo $template['partials']['script_js']; ?>
		<?php echo $custom_scripts ?>
		
	</body>

</html>