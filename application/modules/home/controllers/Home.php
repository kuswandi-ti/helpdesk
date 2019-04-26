<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('M_Home');
		if(isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url().'login', 'location');
		}
	}
	
	public function index() {
		foreach($this->M_Home->report_so()->result_array() as $row) {
			$report_so['grafik'][] = (float)$row['Januari'];
			$report_so['grafik'][] = (float)$row['Februari'];
			$report_so['grafik'][] = (float)$row['Maret'];
			$report_so['grafik'][] = (float)$row['April'];
			$report_so['grafik'][] = (float)$row['Mei'];
			$report_so['grafik'][] = (float)$row['Juni'];
			$report_so['grafik'][] = (float)$row['Juli'];
			$report_so['grafik'][] = (float)$row['Agustus'];
			$report_so['grafik'][] = (float)$row['September'];
			$report_so['grafik'][] = (float)$row['Oktober'];
			$report_so['grafik'][] = (float)$row['November'];
			$report_so['grafik'][] = (float)$row['Desember'];
		}
		$data = array(
			'title' => 'Home',
			'breadcrumb_home_active' => '',
			'breadcrumb' => '',
			'page_icon' => 'icon-clipboard-text',
			'page_title' => 'Minimized Open On Hover',
			'page_subtitle' => 'This option makes sublevels hoverable.',
			//'report_so' => $report_so,
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/home.js'></script>"
		);
		$this->template->build('v_home', $data);
	}
	
	function logout() {
		session_destroy();
		redirect(base_url().'login', 'location');
	}
} 