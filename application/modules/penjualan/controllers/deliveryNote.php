<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class deliveryNote extends CI_Controller
{
    function __construct() {
    parent::__construct();
    $this->load->model('m_delivery_note'); 
    $this->load->library('pdf');
    if (isset($_SESSION['user_name']))
        $this->load->view('theme_default/setting');
    else {
        session_destroy();
        redirect(base_url() . 'login', 'location');
        }
    }

	
    function index()
    {
        $data = array(
            'title' => 'Delivery Note',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Penjualan</li>
                             <li>Delivery Note</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
            
            
        );
        $this->template->build('v_delivery_note', $data);
    }
}