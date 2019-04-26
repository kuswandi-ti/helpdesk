<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class customer extends CI_Controller
{
    function __construct() {
		parent::__construct();
		$this->load->model('m_customer'); 
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
		$data_kelompok = $this->m_customer->get_kelompok();
		$data_provinsi = $this->m_customer->get_provinsi();
		
		$data_region = $this->m_customer->get_region();
		
		$data = array(
            'title' => 'Customer',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Operational</li>
                             <li>Customer</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>',
            'data_kelompok'=> $data_kelompok,
            'data_provinsi'=> $data_provinsi,
            'data_region'=> $data_region
        );
        $this->template->build('v_customer', $data);
	}
	
	function get_cp($id)
	{
		$this->m_customer->get_cp($id);
	}

	
	function get_kota($id)
	{
		$this->m_customer->get_kota($id);
	}
	function get_branch($id)
	{
		$this->m_customer->get_branch($id);
	}
	function get_area($id)
	{
		$this->m_customer->get_area($id);
	}
	function get_sales($id)
	{
		$this->m_customer->get_sales($id);
	}
	function get_pic_default()
	{
		extract($_POST);
		$get = $this->db->query("SELECT id_pic_default FROM ck_customer WHERE id='$id'");
		foreach($get->result() as $row)
		{
			$get_detail = $this->db->query("SELECT * FROM ck_pic WHERE id = '$row->id_pic_default'");
			echo json_encode($get_detail->result());
		}
	}
	
	function update_pic(){
		$img_cp = $_FILES['pic_foto']['name'];
		if($img_cp=='') // no images, give it empty path
		{
			$data_cp = array(
				'pic_nama' => $_POST['pic_nama'],
				'pic_jabatan' => $_POST['pic_jabatan'],
				'pic_hp' => $_POST['pic_hp'],
				'pic_email' => $_POST['pic_email']
			);
		}
		else {
			$img_ext=(explode(".", $img_cp));
			$tmpcp = $_FILES['pic_foto']['tmp_name'];    
			$PATH_GAMBARCP = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/img/contact_person/'.$_POST['id'].'.'.end($img_ext);
			move_uploaded_file($tmpcp, $PATH_GAMBARCP);
			$pic_foto='/sitauhid/assets/img/contact_person/'.$_POST['id'].'.'.end($img_ext);
			
			$data_cp = array(
				'pic_nama' => $_POST['pic_nama'],
				'pic_jabatan' => $_POST['pic_jabatan'],
				'pic_hp' => $_POST['pic_hp'],
				'pic_email' => $_POST['pic_email'],
				'pic_foto'=>$pic_foto
			);
		}
		
		
		$upd = $this->db->update('ck_pic',$data_cp,array('id'=>$_POST['id']));
		if($upd)
			echo "Done";
		else echo $this->db->_error_message();
	}
	
	function create_pic(){
		// create contact person including foto here 
		$data_cp = array(
			'id_customer' => $_POST['id_customer'],
			'pic_nama' => $_POST['pic_nama'],
			'pic_jabatan' => $_POST['pic_jabatan'],
			'pic_hp' => $_POST['pic_hp'],
			'pic_email' => $_POST['pic_email']
		);
		$insert_cp = $this->db->insert('ck_pic', $data_cp);
		$id_cp = $this->db->insert_id(); //id contact person
		
		// updating cp images.
		$img_cp = $_FILES['pic_foto']['name'];
		if($img_cp=='') // no images, give it empty path
			$pic_foto = '';
		else {
			$img_ext=(explode(".", $img_cp));
			$tmpcp = $_FILES['pic_foto']['tmp_name'];    
			$PATH_GAMBARCP = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/img/contact_person/'.$id_cp.'.'.end($img_ext);
			move_uploaded_file($tmpcp, $PATH_GAMBARCP);
			$pic_foto='/sitauhid/assets/img/contact_person/'.$id_cp.'.'.end($img_ext);
		}
		//pic url saved on $pic_foto
		//now update it
		$updcp = $this->db->query("update ck_pic set pic_foto = '$pic_foto' where id='$id_cp'");
		if($updcp)
			echo "Done";
		else echo $this->db->_error_message();
	}
	function input_data()
	{
		//insert ke master customer//
		$data = array(
			'nama' => $_POST['input-nama'],
			'kode_pos' => $_POST['input-kodepos'],
			'id_provinsi' => $_POST['select-provinsi'],
			'id_kelas_customer' => $_POST['select-kelas'],
			'id_kabupatenkota' => $_POST['select-kota'],
			'id_customer_kelompok' => $_POST['select-kelompok'],
			'npwp' => $_POST['input-npwp'],
			'telepon' => $_POST['input-telpon'],
			'faks' => $_POST['input-fax'],
			'email' => $_POST['input-email'],
			'website' => $_POST['input-web'],
			'latitude' => $_POST['input-lat'],
			'longitude' => $_POST['input-long'],
			'id_region' => $_POST['select-region'],
			'id_branch' => $_POST['select-branch'],
			'id_area' => $_POST['select-area'],
			'id_sales' => $_POST['select-sales'],
			'deskripsi' => $_POST['input-deskripsi']
		);
		
		$id = $this->m_customer->input_data($data); //id customer baru
		
		// create contact person including foto here as default & update to customer id 
		$data_cp = array(
			'id_customer' => $id,
			'pic_nama' => $_POST['input-pic-nama'],
			'pic_nama' => $_POST['input-pic-nama'],
			'pic_jabatan' => $_POST['input-pic-jabatan'],
			'pic_hp' => $_POST['input-pic-hp'],
			'pic_email' => $_POST['input-pic-email']
		);
		$insert_cp = $this->db->insert('ck_pic', $data_cp);
		$id_cp = $this->db->insert_id(); //id contact person
		
		// updating cp images.
		$img_cp = $_FILES['input-pic-foto']['name'];
		if($img_cp=='') // no images, give it empty path
			$pic_foto = '';
		else {
			$img_ext=(explode(".", $img_cp));
			$tmpcp = $_FILES['input-foto1']['tmp_name'];    
			$PATH_GAMBARCP = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/img/contact_person/'.$id_cp.'.'.end($img_ext);
			move_uploaded_file($tmpcp, $PATH_GAMBARCP);
			$pic_foto='/sitauhid/assets/img/contact_person/'.$id_cp.'.'.end($img_ext);
		}
		//pic url saved on $pic_foto
		//now update it
		$updcp = $this->db->query("update ck_pic set pic_foto = '$pic_foto' where id='$id_cp'");
		if($updcp) 
		{
			//update customer table, set new customer default id_pic 
			$updcust = $this->db->query("update ck_customer set id_pic_default = '$id_cp' where id = '$id'");
		}
		
		//create kode customer//
		/**********************/
		$kode_branch = $this->m_customer->get_kode_branch($_POST['select-branch']);
		$kode = $kode_branch.$_POST['select-kelompok'].$id;
		
		
		//insert alamat//
		//*************//
		$data_alamat = array(
			'id_customer'=>$id,
			'alamat_kirim'=>$_POST['input-alamat'],
			'id_area'=>$_POST['select-area']
		);
		$id_alamat_default = $this->m_customer->input_alamat($data_alamat);
		
		
		//alamat tagihan//
		//**************//
		$data_alamat_tagihan = array(
			'id_customer'=>$id,
			'alamat_tagihan'=>$_POST['input-alamat'],
			'id_area'=>$_POST['select-area']
		);
		$id_alamat_tagihan_default = $this->m_customer->input_alamat_tagihan($data_alamat_tagihan);
		
		//foto//
		/******/
		
		$foto1 =  $_FILES['input-foto1']['name'];
		$foto2 =  $_FILES['input-foto2']['name'];
		$foto3 =  $_FILES['input-foto3']['name'];
		
		$path1='';
		$path2='';
		$path3='';
		if($foto1 == '') // if foto ga diisi
		{
			$path1='';
		}
		else
		{
			$extension1=(explode(".", $foto1));
			$tmp1 = $_FILES['input-foto1']['tmp_name'];    
			$PATH_GAMBAR1 = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/img/customer/'.$id.'-foto1.'.end($extension1);
			move_uploaded_file($tmp1, $PATH_GAMBAR1);
			$path1='/sitauhid/assets/img/customer/'.$id.'-foto1.'.end($extension1);
			
		}
		
		if($foto2 == '')
		{
			$path2='';
		}
		else
		{
			$extension2=(explode(".", $foto2));
			$tmp2 = $_FILES['input-foto2']['tmp_name'];    
			$PATH_GAMBAR2 = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/img/customer/'.$id.'-foto2.'.end($extension2);
			move_uploaded_file($tmp2, $PATH_GAMBAR2);
			$path2='/sitauhid/assets/img/customer/'.$id.'-foto2.'.end($extension2);
			
		}
		
		if($foto3 == '')
		{
			$path3='';
		}
		else
		{
			$extension3=(explode(".", $foto3));
			$tmp3 = $_FILES['input-foto3']['tmp_name'];    
			$PATH_GAMBAR3 = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/img/customer/'.$id.'-foto3.'.end($extension3);
			move_uploaded_file($tmp3, $PATH_GAMBAR3);
			$path3='/sitauhid/assets/img/customer/'.$id.'-foto3.'.end($extension3);
		}
		
		
		// update current customer detail //
		/*********************************/
		$update = array (
			'kode'=>$kode,
			'id_alamat_default'=>$id_alamat_default,
			'id_alamat_tagihan_default'=>$id_alamat_tagihan_default,
			'foto_1'=>$path1,
			'foto_2'=>$path2,
			'foto_3'=>$path3
		);
		$select_kelompok=$_POST['select-kelompok'];
		$exec =  $this->db->query("
					update ck_customer 
					set 
						kode=CONCAT('$kode_branch','$select_kelompok',id),
						id_alamat_default='$id_alamat_default',
						id_alamat_tagihan_default='$id_alamat_tagihan_default',
						foto_1='$path1',
						foto_2='$path2',
						foto_3='$path3'
					where id = '$id';
		");
		if($exec)
			echo "done";
		else echo $this->db->_error_message(); 
	}
	
	function update_data($id)
	{
		$id = substr($id,1);
		$data = array(
			'nama' => $_POST['nama'],
			'kode_pos' => $_POST['kode_pos'],
			'id_provinsi' => $_POST['id_provinsi'],
			'id_kabupatenkota' => $_POST['id_kabupatenkota'],
			'id_customer_kelompok' => $_POST['id_customer_kelompok'],
			'id_kelas_customer' => $_POST['id_kelas_customer'],
			'npwp' => $_POST['npwp'],
			'telepon' => $_POST['telepon'],
			'faks' => $_POST['faks'],
			'email' => $_POST['email'],
			'website' => $_POST['website'],
			'latitude' => $_POST['latitude'],
			'longitude' => $_POST['longitude'],
			'id_region' => $_POST['id_region'],
			'id_branch' => $_POST['id_branch'],
			'id_area' => $_POST['id_area'],
			'id_sales' => $_POST['id_sales'],
			'deskripsi' => $_POST['deskripsi'],
			'pic_nama' => $_POST['pic_nama'],
			'pic_jabatan' => $_POST['pic_jabatan'],
			'pic_hp' => $_POST['pic_hp'],
			'pic_email' => $_POST['pic_email']
		);
		//foto//
		/******/
		
		$foto1 =  $_FILES['foto_1']['name'];
		$foto2 =  $_FILES['foto_2']['name'];
		$foto3 =  $_FILES['foto_3']['name'];
		
		if($foto1 !='')
		{
			$extension1=(explode(".", $foto1));
			$tmp1 = $_FILES['foto_1']['tmp_name'];    
			$PATH_GAMBAR1 = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/img/customer/'.$id.'-foto1.'.end($extension1);
			move_uploaded_file($tmp1, $PATH_GAMBAR1);
			$path1='/sitauhid/assets/img/customer/'.$id.'-foto1.'.end($extension1);
			
			//add ke data update
			$data['foto_1'] = $path1;
			
		}
		
		if($foto2 !='')
		{
			$extension2=(explode(".", $foto2));
			$tmp2 = $_FILES['foto_2']['tmp_name'];    
			$PATH_GAMBAR2 = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/img/customer/'.$id.'-foto2.'.end($extension2);
			move_uploaded_file($tmp2, $PATH_GAMBAR2);
			$path2='/sitauhid/assets/img/customer/'.$id.'-foto2.'.end($extension2);
			$data['foto_2'] = $path2;
		}
		
		if($foto3 !='')
		{
			$extension3=(explode(".", $foto3));
			$tmp3 = $_FILES['foto_3']['tmp_name'];    
			$PATH_GAMBAR3 = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/img/customer/'.$id.'-foto3.'.end($extension3);
			move_uploaded_file($tmp3, $PATH_GAMBAR3);
			$path3='/sitauhid/assets/img/customer/'.$id.'-foto3.'.end($extension3);
			$data['foto_3'] = $path3;
		}
		
		$exec = $this->db->update('ck_customer',$data,array('id'=>$id));
		if($exec){
			echo "done";
			//print_r($_POST);
		}
		else echo $this->db->_error_message(); 
	}
	
	function delete_data($id)
	{
		$this->m_customer->delete_data($id);
	}
	
	
	function get_customer_detail($id)
	{
		return $this->m_customer->get_customer_detail($id);
	}
	
	
	
	function populate_customer()
	{
		$aColumns = array('id', 'kelas','kode','nama', 'area', 'alamat_kirim', 'alamat_tagihan','id_sales',  'nama_sales', 'telepon', 'pic_nama', 'credit_limit');
		$sTable = "(SELECT cu.id, kc.kelas,  cu.kode, cu.nama, ar.`nama` AS 'area', a.`alamat_kirim` , tc.`alamat_tagihan`,  s.id AS id_sales ,s.`nama_sales`, cu.telepon,cu.pic_nama, cu.credit_limit
					FROM ck_customer cu
					LEFT OUTER JOIN ck_alamat_customer a
					ON a.id = cu.`id_alamat_default`
					LEFT OUTER JOIN ck_alamat_tagihan_customer tc
					ON tc.`id` = cu.`id_alamat_tagihan_default`
					LEFT OUTER JOIN ck_sales s
					ON s.id = cu.id_sales
					LEFT OUTER JOIN ck_kelas_customer kc
					ON cu.id_kelas_customer = kc.id
					LEFT OUTER JOIN ck_area ar
					ON cu.id_area = ar.id) x";
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
     
        // Paging
        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        
        // Ordering
        if (isset($iSortCol_0)) {
            for ($i=0; $i<intval($iSortingCols); $i++) {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
    
                if ($bSortable == 'true') {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtering
         */
        if (isset($sSearch) && !empty($sSearch)) {
            for ($i=0; $i<count($aColumns); $i++) {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);
                
                // Individual column filtering
                if (isset($bSearchable) && $bSearchable == 'true') {
                    $this->db->or_like($aColumns[$i], $this->db->escape_like_str($sSearch));
                }
            }
        }
        
        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $this->db->get($sTable);
    
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
        
		$no = $iDisplayStart;
		
        foreach ($rResult->result_array() as $aRow) {
            $row = array();
           
            $no++;
            $row[] = $no; 
            $row[] = $aRow['kode']."<input type='hidden' id='idcustomer' value='".$aRow['id']."'>"; 
            $row[] = $aRow['kelas']; 
            $row[] = $aRow['nama']; 
            $row[] = $aRow['area']; 
            $row[] = $aRow['alamat_kirim'];
            $row[] = $aRow['alamat_tagihan']; 
            $row[] = $aRow['telepon']; 
            $row[] = $aRow['pic_nama']; 
            $row[] = $aRow['nama_sales']; 
            $row[] = number_format($aRow['credit_limit']); 
            $row[] = "	<button id=\"button-detail\" style=\"border:none;\" class=\" btn-success  \" title='View Detail'> <span class=\"icon-magnifier\"></span> </button> 
						| <button id=\"button-edit\" style=\"border:none;\" class=\" btn-info  \" title='Edit'> <span class=\"icon-pencil4\"></span> </button> 
						| <button id=\"button-edit-alamat\" style=\"border:none;\" class=\" btn-info  \" title='Edit Alamat'> <span class=\"fa fa-map-marker\"></span> </button> 
						| <button id=\"button-edit-pic\" style=\"border:none;\" class=\" btn-info  \" title='Edit PIC'> <span class=\"fa fa-user\"></span> </button> 
						| <button id=\"button-delete\" style=\"border:none;\" class=\" btn-danger \" title='Delete'> <span class=\"icon-delete\"></span> </button>  ";
            
            $output['aaData'][] = $row;
        }
        
        echo json_encode($output);
	}
	
	function load_default_address($id)
	{
		$load = $this->db->query("	select ac.alamat_kirim, tc.alamat_tagihan, c.id_alamat_default, c.id_alamat_tagihan_default
									from ck_customer c
									left outer join ck_alamat_customer ac on c.id_alamat_default = ac.id
									left outer join ck_alamat_tagihan_customer tc on c.id_alamat_tagihan_default = tc.id
									where c.id='$id' ")->result();
		
		echo json_encode($load);
	}
	
	function update_alamat_default($id){
		$update = $this->db->query("update ck_alamat_customer set alamat_kirim = '".$_POST['alamat']."' where id='$id'");
		if($update)
			echo "done";
		else echo $this->db->_error_message();
	}
	function update_alamat_tagihan_default($id){
		$update = $this->db->query("update ck_alamat_tagihan_customer set alamat_tagihan = '".$_POST['alamat']."' where id='$id'");
		if($update)
			echo "done";
		else echo $this->db->_error_message();
	}
	
	function insert_address($isDefault)
	{
		extract($_POST);
		$insert = $this->db->insert('ck_alamat_customer',$_POST);
		$id_alamat_default = $this->db->insert_id();
		if($insert){
			if($isDefault=='1')
			{
				$update = $this->db->update('ck_customer',array('id_alamat_default'=>$id_alamat_default),array('id'=>$id_customer));
				if($update)
					echo "done";
				else 
				{
					echo $this->db->_error_message();
				}
			}
			else{
				echo "done";
			}
		}
		else echo $this->db->_error_message();
			
	}
	
	function insert_billing_address($isDefault)
	{
		extract($_POST);
		$insert = $this->db->insert('ck_alamat_tagihan_customer',$_POST);
		$id_alamat_default = $this->db->insert_id();
		if($insert){
			if($isDefault=='1')
			{
				$update = $this->db->update('ck_customer',array('id_alamat_tagihan_default'=>$id_alamat_default),array('id'=>$id_customer));
				if($update)
					echo "done";
				else 
				{
					echo $this->db->_error_message();
				}
			}
			else{
				echo "done";
			}
		}
		else echo $this->db->_error_message();
			
	}
	
	function add_pic()
	{	
		$last_id;
		$ins = $this->db->insert('ck_pic',$_POST);
		if($ins){
			$last_id = $this->db->insert_id();
			
		}
	}
}