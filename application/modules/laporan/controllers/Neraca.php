<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Neraca extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->view('theme_default/setting');
	}
	
	public function index() {
		$data = array(
			'title' => 'Laporan Keuangan - Neraca',
			'breadcrumb_home_active' => '',
			'breadcrumb' => '<li>Laporan</li>
                             <li>Laporan Keuangan</li>
                             <li>Neraca</li>',
			'page_icon' => 'icon-printer',
			'page_title' => 'Laporan Keuangan - Neraca',
			'page_subtitle' => 'Laporan Keuangan - Neraca',			
			'custom_scripts' => ''
		);
		$this->template->build('v_neraca', $data);
	}

} 