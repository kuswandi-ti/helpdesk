<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class analisa_penjualan_tahunan extends CI_Controller {
	
    public function __construct() {
		parent::__construct();
		$this->load->model('m_analisa_penjualan_tahunan');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
    }
	
	public function index() {
		$data = array(
			'title' => 'Analisa Penjualan Tahunan',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Manajemen Data</li>
							 <li>Perencanaan Stok</li>
							 <li><a href="logistik/analisa_penjualan_tahunan">Analisa Penjualan Tahunan</a></li>',
			'page_icon' => 'fa fa-area-chart',
			'page_title' => 'Analisa Penjualan Tahunan',
			'page_subtitle' => 'Analisa Penjualan Tahunan',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_analisapenjualantahunan').addClass('active')</script>"
		);
		$this->template->build('v_analisa_penjualan_tahunan', $data);
	}
	
	public function ajax_list() {
        $list = $this->m_analisa_penjualan_tahunan->get_datatables();
        $data = array();
        $no = $_POST['start'];
		
        foreach ($list as $stocks) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $stocks->tahun;
            $row[] = number_format($stocks->jan);
			$row[] = number_format($stocks->feb);
			$row[] = number_format($stocks->mar);
			$row[] = number_format($stocks->apr);
			$row[] = number_format($stocks->mei);
			$row[] = number_format($stocks->jun);
			$row[] = number_format($stocks->jul);
			$row[] = number_format($stocks->agu);
			$row[] = number_format($stocks->sep);
			$row[] = number_format($stocks->okt);
			$row[] = number_format($stocks->nov);
			$row[] = number_format($stocks->des);
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->m_analisa_penjualan_tahunan->count_all(),
                        "recordsFiltered" => $this->m_analisa_penjualan_tahunan->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
	
}