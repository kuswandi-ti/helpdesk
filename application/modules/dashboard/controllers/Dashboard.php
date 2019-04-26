<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->view('theme_default/setting');
	}
	
	public function index() {
		$data = array(
			'title' => 'Dashboard',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Layouts</li>
                             <li>Open On Hover</li>',
			'page_icon' => 'icon-home',
			'page_title' => 'Dashboard',
			'page_subtitle' => 'The revolution in admin template build',			
			'custom_scripts' => "<script type='text/javascript' src='assets/apps/js/app_demo_dashboard.js'></script>"
		);
		$this->template->build('v_dashboard', $data);
	}

} 