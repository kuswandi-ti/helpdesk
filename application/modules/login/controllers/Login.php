<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
	    $this->load->model('LakModel');
	   	$this->load->helper(array('form', 'url'));
	}

	public function index()
	{	
		if($this->session->userdata('username'))
    	{
    		redirect(base_url().'index.php/Home/','location');
		}
		else
		{
			$data['page_title']='PT. TAUHID';
			$this->load->view('login_view',array('data'=> $data ));
		}
	}
	
  	function auth()
  	{
  		if(isset($_POST))
		{
    		$auth=$this->LakModel->auth();

    		if(sizeof($auth)!=0)
    		{
    			$_SESSION['user_id']= $auth[0]->id;
	         	$_SESSION['user_name']= $auth[0]->user_name;
	         	$_SESSION['user_nik']=$auth[0]->nik;
	         	$_SESSION['user_nama']=$auth[0]->nama;
				
				redirect(base_url().'home','location');
    		}
    		else
    		{
    			$this->session->set_flashdata('notif', 'Username / Password Anda Salah !');
	        	redirect(base_url().'login','location');
    		}
    	}
    	else
	     	redirect(base_url().'login','location');
    
  	}

  	function logout()
	{
		session_destroy();
		redirect(base_url().'index.php/Welcome', 'location');
	}
}
