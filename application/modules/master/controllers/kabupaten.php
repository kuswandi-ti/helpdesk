<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MasterKabupatenkota extends CI_Controller {

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
	    $this->load->model('MasterKabupatenkotaModel');
	}

	public function index()
	{
		$data["menu_left"] = VIEWPATH . "menu_left_master.php";
	    if($this->session->userdata('user_name'))
	    {
	      	$data['page_title']='Master Data Kabupaten / Kota';
	      	$data['user_name']=$this->session->userdata('user_name');
			
			$this->load->model('MasterProvinsiModel');
    		
	  		$data['kabupatenkota']=$this->MasterKabupatenkotaModel->kabupatenkota_list();
			$data['provinsi']=$this->MasterProvinsiModel->provinsi_list();
	  		
	  		$this->load->view('header', array('data'=> $data ));
	  		$this->load->view('menu_left', array('data'=> $data ));
	  		$this->load->view('topmenu', array('data'=> $data ));
	  		$this->load->view('kabupatenkota', array('data'=> $data ));
	  		$this->load->view('footer', array('data'=> $data ));
	    }
	    else
	    {
	      redirect(base_url().'index.php/Welcome','location');
	    }
  	}
	
  	function kabupatenkota_list()
  	{	
	    $options_kabupatenkota = array();
	    $provinsi_id = $this->input->post('provinsi');
	    $selected = $this->db->select('id,nama')->where('provinsi_id', $provinsi_id)->get('ck_kabupatenkota');
	    foreach($selected->result() as $sel){
	      array_push($options_kabupatenkota, array('id'=>$sel->id, 'nama'=>$sel->nama));
	    }
	    echo json_encode($options_kabupatenkota);
  	}
	
  	function kabupatenkota_view($id)
  	{	
		$data['kabupatenkota']=$this->MasterKabupatenkotaModel->kabupatenkota_view($id);
    	echo json_encode($data['kabupatenkota'][0],true);
  	}

   function kabupatenkota_create()
   {
    $notifikasi=$this->MasterKabupatenkotaModel->kabupatenkota_create();
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

  function kabupatenkota_update()
  {
	$notifikasi=$this->MasterKabupatenkotaModel->kabupatenkota_update();
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

  function kabupatenkota_delete($id = '')
  {
    $notifikasi=$this->MasterKabupatenkotaModel->kabupatenkota_delete($id);
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
