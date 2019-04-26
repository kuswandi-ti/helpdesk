<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends CI_Controller
{
        function __construct() {
        parent::__construct();
        $this->load->model('m_karyawan');
        $this->load->library('pdf');
        if (isset($_SESSION['user_name']))
            $this->load->view('theme_default/setting');
        else {
            session_destroy();
            redirect(base_url() . 'login', 'location');
        }
    }

    function index() {

        $data = array(
            'title' => 'Karyawan',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Karyawan</li>
                             <li>Data Pokok</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>',
            'headerList' => $this->karyawanList()
        );
        $this->template->build('v_karyawan', $data);
    }

    function karyawanList() {
        $headerList = $this->m_karyawan->karyawanList();
        return $headerList;
    }


}