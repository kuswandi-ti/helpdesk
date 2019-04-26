<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KaryawanDetail extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct()
	{
		parent::__construct();
	    $this->load->database();
	    $this->load->model('KaryawanModel');
	    $this->load->model('MasterProvinsiModel');
	    $this->load->model('MasterKabupatenkotaModel');
	    $this->load->model('MasterJabatanModel');
	    $this->load->model('MasterUnitKerjaModel');
	}

	public function detail($id)
	{
		$data["menu_left"] = VIEWPATH . "menu_left_master.php";
	    if($this->session->userdata('user_name'))
	    {
	      	$data['page_title']='Data Karyawan';
	      	$data['user_name']=$this->session->userdata('user_name');
			
			$data['karyawan']=$this->KaryawanModel->karyawan_view($id);
	  		
	  		$this->load->view('header', array('data'=> $data ));
	  		$this->load->view('menu_left', array('data'=> $data ));
	  		$this->load->view('topmenu', array('data'=> $data ));
	  		$this->load->view('karyawan_detail', array('data'=> $data ));
	  		$this->load->view('footer', array('data'=> $data ));
	    }
	    else
	    {
	      redirect(base_url().'index.php/Welcome','location');
	    }
  	}
}
