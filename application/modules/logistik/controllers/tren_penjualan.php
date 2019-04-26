<?php defined('BASEPATH') OR exit('No direct script access allowed');

class tren_penjualan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('m_tren_penjualan');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Tren Penjualan',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
			                 <li>Inquiry</li>
			                 <li>Analisa / Tren (Grafik)</li>
                             <li><a href="logistik/tren_penjualan">Tren Penjualan</a></li>',
			'page_icon' => 'icon-files',
			'page_title' => 'Tren Penjualan',
			'page_subtitle' => 'Analisa Tren Penjualan',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_trenpenjualan').addClass('active');</script>"
		);
		$this->template->build('v_tren_penjualan', $data);
	}
	
	public function get_data_penjualan() {
		print json_encode($this->m_tren_penjualan->get_data_penjualan());
	}
	
	function get_data_produk($tahun) {
		$this->m_tren_penjualan->get_data_produk($tahun);
	}

}
