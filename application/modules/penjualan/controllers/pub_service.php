<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pub_service extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('m_so');
	}
	
	function onlinechecking($jenis,$id)
	{
		//jenis 1=DN, 2=Faktur
		switch($jenis)
		{
			case 'dn':
				$data['tipe'] = 'dn';
				$data['header'] = $this->m_so->populatedetail($id);
				$data['detail'] = $this->m_so->isiDetail($id);
				$this->load->view('v_pub_service',$data);
				break;
			default: 
			break;
		}
		
		
	}
}