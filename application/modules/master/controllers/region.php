<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class region extends CI_Controller {

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
	    $this->load->model('m_region');
	}

	public function index()
	{
		$data["menu_left"] = VIEWPATH . "menu_left_master.php";
	    if($this->session->userdata('user_name'))
	    {
	      	$data['page_title']='Master Data Region';
	      	$data['user_name']=$this->session->userdata('user_name');
			
			$this->load->model('m_region');
    		
	  		$data['region']=$this->m_region->region_list();
	  		
	  		$this->load->view('header', array('data'=> $data ));
	  		$this->load->view('menu_left', array('data'=> $data ));
	  		$this->load->view('topmenu', array('data'=> $data ));
	  		$this->load->view('region', array('data'=> $data ));
	  		$this->load->view('footer', array('data'=> $data ));
	    }
	    else
	    {
	      redirect(base_url().'index.php/Welcome','location');
	    }
  	}
	
  	function region_view($id)
  	{	
		$data['region']=$this->m_region->region_view($id);
    	echo json_encode($data['region'][0],true);
  	}

   function region_create()
   {
    $notifikasi=$this->m_region->region_create();
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

  function region_update()
  {
	$notifikasi=$this->m_region->region_update();
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

  function region_delete($id = '')
  {
    $notifikasi=$this->m_region->region_delete($id);
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
