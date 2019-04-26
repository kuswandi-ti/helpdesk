<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MasterOffice extends CI_Controller {

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
	    $this->load->model('MasterOfficeModel');
	    $this->load->model('MasterRegionModel');
	    $this->load->model('MasterProvinsiModel');
	    $this->load->model('MasterKabupatenkotaModel');
	}

	public function index()
	{
		$data["menu_left"] = VIEWPATH . "menu_left_master.php";
	    if($this->session->userdata('user_name'))
	    {
	      	$data['page_title']='Master Data Office';
	      	$data['user_name']=$this->session->userdata('user_name');
    		
	  		$data['office']=$this->MasterOfficeModel->office_list();
			$data['region']=$this->MasterRegionModel->region_list();
	  		$data['provinsi']=$this->MasterProvinsiModel->provinsi_list();
	  		$data['kabupaten_kota']=$this->MasterKabupatenkotaModel->kabupatenkota_list();
	  		
	  		$this->load->view('header', array('data'=> $data ));
	  		$this->load->view('menu_left', array('data'=> $data ));
	  		$this->load->view('topmenu', array('data'=> $data ));
	  		$this->load->view('office', array('data'=> $data ));
	  		$this->load->view('footer', array('data'=> $data ));
	    }
	    else
	    {
	      redirect(base_url().'index.php/Welcome','location');
	    }
  	}
	
  	function office_view($id)
  	{	
		$data['office']=$this->MasterOfficeModel->office_view($id);
    	echo json_encode($data['office'][0],true);
  	}

   function office_create()
   {
    $notifikasi=$this->MasterOfficeModel->office_create();
    if($notifikasi == 1)
    {
      $notifikasi=" Selamat ! Anda berhasil";
    }
    else
    {
      $notifikasi=" Maaf ! Anda gagal";
    }
    $notifikasi=array('notifikasi'=>$notifikasi);
    echo json_encode($notifikasi,true);
  }

  function office_update()
  {
	$notifikasi=$this->MasterOfficeModel->office_update();
    if($notifikasi == 1)
    {
      $notifikasi=" Selamat ! Anda berhasil";
    }
    else
    {
      $notifikasi=" Maaf ! Anda gagal";
    }
    $notifikasi=array('notifikasi'=>$notifikasi);
    echo json_encode($notifikasi,true);
  }

  function office_delete($id = '')
  {
    $notifikasi=$this->MasterOfficeModel->office_delete($id);
    if($notifikasi == 1)
    {
      $notifikasi=" Selamat ! Anda berhasil";
    }
    else
    {
      $notifikasi=" Maaf ! Anda gagal";
    }
    $notifikasi=array('notifikasi'=>$notifikasi);
    echo json_encode($notifikasi,true);
  }

}
