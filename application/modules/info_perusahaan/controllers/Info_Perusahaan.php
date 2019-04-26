<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Info_Perusahaan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->view('theme_default/setting');
	}
	
	public function index() {
		$data = array(
			'title' => 'Info Perusahaan',
			'breadcrumb_home_active' => '',
			'breadcrumb' => '<li>Pengaturan</li>
                             <li>Info Perusahaan</li>',
			'page_icon' => 'icon-home4',
			'custom_scripts' => ''
		);
		$this->template->build('v_info_perusahaan', $data);
	}

} 