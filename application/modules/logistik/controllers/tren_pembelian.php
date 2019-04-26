<?php defined('BASEPATH') OR exit('No direct script access allowed');

class tren_pembelian extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('m_tren_pembelian');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Tren Pembelian',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
			                 <li>Inquiry</li>
			                 <li>Analisa / Tren (Grafik)</li>
                             <li><a href="logistik/tren_pembelian">Tren Pembelian</a></li>',
			'page_icon' => 'icon-files',
			'page_title' => 'Tren Pembelian',
			'page_subtitle' => 'Analisa Tren Pembelian',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_trenpembelian').addClass('active');</script>"
		);
		$this->template->build('v_tren_pembelian', $data);
	}
	
	public function get_data_pembelian() {
		print json_encode($this->m_tren_pembelian->get_data_pembelian());
	}
	
	function get_data_produk($tahun) {
		$this->m_tren_pembelian->get_data_produk($tahun);
	}

}
