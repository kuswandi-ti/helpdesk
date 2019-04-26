<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aset extends CI_Controller {

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
	    $this->load->model('AsetModel');
	    $this->load->model('AsetKelompokModel');
	}

	public function index()
	{
		$data["menu_left"] = VIEWPATH . "menu_left_master.php";
	    if($this->session->userdata('user_name'))
	    {
	      	$data['page_title']='Master Data Aset';
	      	$data['user_name']=$this->session->userdata('user_name');
			
	  		$data['cara_perolehan']=array('1'=>'Beli', '2'=>'Hadiah', '9'=>'Lain-lain');
	  		$data['metode_bayar']=array('1'=>'Tunai', '2'=>'Kredit');
	  		
			$data['aset_kelompok']=$this->AsetKelompokModel->aset_kelompok_list();
	  		$data['aset']=$this->AsetModel->aset_list();
	  		
	  		$this->load->view('header', array('data'=> $data ));
	  		$this->load->view('menu_left', array('data'=> $data ));
	  		$this->load->view('topmenu', array('data'=> $data ));
	  		$this->load->view('aset', array('data'=> $data ));
	  		$this->load->view('footer', array('data'=> $data ));
	    }
	    else
	    {
	      redirect(base_url().'index.php/Welcome','location');
	    }
  	}
	
  	function aset_view($id)
  	{	
		$data['aset']=$this->AsetModel->aset_view($id);
    	echo json_encode($data['aset'][0],true);
  	}

   function aset_create()
   {
    $notifikasi=$this->AsetModel->aset_create();
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

  function aset_update()
  {
	$notifikasi=$this->AsetModel->aset_update();
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

  function aset_delete($id = '')
  {
    $notifikasi=$this->AsetModel->aset_delete($id);
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

	public function qr_code($id = "")
	{
	 	$this->load->library('ciqrcode');
	 	header("Content-Type: image/png");
		
      	$query = $this->db->select('*')->where('id', $id)->get('ck_aset')->result_array();
  			if(count($query) > 0){
  				foreach($query as $row){
  					$kode = $row['kode'];
  					$nama = $row['nama'];
  					$nama_pengguna = $row['nama_pengguna'];
  					$lokasi = $row['lokasi'];
  				}
  			}
		
		$qr['data'] = 'No. Inv		:	'.$kode.'
Nama Barang		:	'.$nama.'
Nama Pengguna	:	'.$nama_pengguna.'
Lokasi		:	'.$lokasi;

	 	$this->ciqrcode->generate($qr);
	}
}
