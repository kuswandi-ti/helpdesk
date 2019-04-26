<?php

class marketing_tools extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('m_planning'); 
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
            'title' => 'Marketing Tools',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Operational</li>
							 <li>Sales</li>
                             <li>Marketing Tools</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
        );
        $this->template->build('v_marketing_tools', $data);
	}
	
	function populate_drugs()
	{
		$select = $this->db->query("
		SELECT * 
		FROM ck_produk
		LIMIT 20
		");
		
       header('Content-Type: application/json');
		echo json_encode($select->result());
	}
	
	function get_equivalent_product($id)
	{
		$query=$this->db->query("
		SELECT eq.id as id_eq, p.*
		FROM ck_produk_equivalent eq
		left outer join ck_produk p
		on eq.id_produk_equivalent = p.id 
		where eq.id_produk = '$id' 
		")->result();
		$no = 1;
		foreach($query as $x):
			echo "<tr>";
				echo "<td>";
				echo  $no;
				echo "</td>";
				echo "<td><input id='id_eq' type='hidden' value='$x->id_eq'>";
				echo  $x->id;
				echo "</td>";
				echo "<td>";
				echo  $x->nama;
				echo "</td>";
				echo "<td>";
				echo  $x->indikasi;
				echo "</td>";
				if($_POST['type']=='insert'){
				echo "<td><button id='eq-btn-del' class='btn btn-sm'>Delete</button></td>";
				}
			echo "</tr>";
		$no++;
		endforeach;
		
	}
	
	function insert_eq($a,$b)
	{
		$ins = $this->db->query("insert into ck_produk_equivalent(id_produk,id_produk_equivalent) values ('$a','$b')");
		if($ins)
			echo "done";
		else echo $this->db->_error_message(); 
	}
	function del_eq($a)
	{
		$del = $this->db->query("delete from ck_produk_equivalent where id='$a'");
		if($del)
			echo "done";
		else echo $this->db->_error_message(); 
	}
	
	function populate_manual()
	{
		$query=$this->db->query("
		SELECT *
		FROM ck_manual_book
		")->result();
		$no = 1;
		foreach($query as $x):
			echo "<tr>";
				echo "<td>";
				echo  $no;
				echo "</td>";
				echo "<td><input id='id_manual' type='hidden' value='$x->id'>";
				echo  "<span class='manual-judul'>$x->judul</span>";
				echo "</td>";
				echo "<td>";
				echo "<a href='$x->url' target='_blank'><span class='fa fa-file-o'></span> Downloads</a>";
				echo "</td>";
				echo "<td>";
				echo  $x->uploader;
				echo "</td>";
				echo "<td>";
				echo  $x->upload_date;
				echo "</td>";
				echo "<td>";
				echo "	<button id=\"manual-btn-edit\" style=\"border:none;\" class=\" btn-info  \" title='Edit'> <span class=\"icon-pencil4\"></span> </button>
						<button id=\"manual-btn-del\" style=\"border:none;\" class=\" btn-danger \" title='Delete'> <span class=\"icon-delete\"></span> </button>  ";
				echo "</td>";
			echo "</tr>";
		$no++;
		endforeach;
	}
	
	function insert_manual()
	{
		extract($_POST);
		$fileName = $_FILES['file']['name'];
		$extension=(explode(".", $fileName));
		$tmp = $_FILES['file']['tmp_name'];    
		$date = date_create();
		
		$PATH_GAMBAR = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/docs/manual/'.$uploader.date_timestamp_get($date).'_'.str_replace(' ','_',$fileName);
		move_uploaded_file($tmp, $PATH_GAMBAR);
		
		$path=base_url().'assets/docs/manual/'.$uploader.date_timestamp_get($date).'_'.str_replace(' ','_',$fileName);
		
		$q = "insert into ck_manual_book(judul,url,uploader) values('$judul','$path','$uploader')";
		$ex = $this->db->query($q);
		if($ex) echo "done";
		else echo $this->db->_error_message(); 
	}
	function update_manual()
	{
		extract($_POST);
		$fileName = $_FILES['file']['name'];
		if($fileName=='') // hanya ganti judul
		{
			$q = "update ck_manual_book set judul='$judul' where id='$id'";
		}
		else{
			$extension=(explode(".", $fileName));
			$tmp = $_FILES['file']['tmp_name'];    
			$date = date_create();
			
			$PATH_GAMBAR = $_SERVER['DOCUMENT_ROOT'].'/sitauhid/assets/docs/manual/'.$uploader.date_timestamp_get($date).'_'.str_replace(' ','_',$fileName);
			move_uploaded_file($tmp, $PATH_GAMBAR);
			
			$path=base_url().'assets/docs/manual/'.$uploader.date_timestamp_get($date).'_'.str_replace(' ','_',$fileName);
			$q = "update ck_manual_book set judul='$judul',url='$path' where id='$id'";
		}
		
		
		$ex = $this->db->query($q);
		if($ex) echo "done";
		else echo $this->db->_error_message(); 
	}
	function del_manual($a)
	{
		$del = $this->db->query("delete from ck_manual_book where id='$a'");
		if($del)
			echo "done";
		else echo $this->db->_error_message(); 
	}
	
	function get_competitor_drug($id_produk)
	{
		$get = $this->db->query("	SELECT k.*,kom.`nama_perusahaan`,kom.lokasi, p.nama, DATE_FORMAT(k.`created_Date`, '%d-%m-%Y') AS tanggal
									FROM ck_market_intel k
									LEFT OUTER JOIN ck_kompetitor kom
									ON kom.id = k.`id_kompetitor`
									LEFT OUTER JOIN ck_produk p
									ON p.id = k.id_produk
									where k.id_produk = '$id_produk'
								");
		$no = 1;
		if($get->num_rows()>0){
			$no = 1;
		foreach($get->result() as $row)
			{
				echo "<tr>";
				echo "<td>";
				echo $no;
				echo "</td>";
				echo "<td>";
				echo $row->nama_perusahaan;
				echo "</td>";
				echo "<td>";
				echo $row->lokasi;
				echo "</td>";
				echo "<td>";
				echo number_format($row->harga);
				echo "</td>";
				echo "<td>";
				echo $row->tanggal;
				echo "</td>";
				echo "<.tr>";
				$no++;
			}		
		}
		else echo "<tr><td colspan='4'><center>No Data</center></td></tr>";
	}
	
	function ins_data_market_intel()
	{
		$ins=$this->db->insert('ck_market_intel',$_POST);
		if($ins)
			echo "done";
		else echo $this->db->_error_message(); 
	}
	
	function add_competitor()
	{
		$ins=$this->db->insert('ck_kompetitor',$_POST);
		if($ins)
			echo "done";
		else echo $this->db->_error_message(); 
	}
	
	function list_competitor()
	{
		$get = $this->db->query("SELECT * FROM ck_kompetitor");
		if($get->num_rows()>0){
			$no = 1;
		foreach($get->result() as $row)
			{
				echo "<tr>";
				echo "<td>";
				echo $no;
				echo "</td>";
				echo "<td>";
				echo $row->nama_perusahaan;
				echo "</td>";
				echo "<td>";
				echo $row->lokasi;
				echo "</td>";
				echo "<td>";
				echo "<input id='id_kom' type='hidden' value='$row->id'><button id='btn_del_kompetitor' class='btn-info'>Delete</button>";
				echo "</td>";
				echo "<.tr>";
				$no++;
			}		
		}
		else echo "<tr><td colspan='4'><center>No Data</center></td></tr>";
	}
	
	function del_kom($id)
	{
		$delkom = $this->db->query("delete from ck_kompetitor where id='$id'");
		if($delkom)
		{
			$delmarketintel = $this->db->query("delete from ck_market_intel where id_kompetitor ='$id'");
			if($delmarketintel)
				echo "done";
			else echo $this->db->_error_message();
		}
		else echo $this->db->_error_message();
	}
	
	function stock_det($string)
	{
		$list = $this->db->query("	SELECT p.`nama`, s.id, s.`batch_number`, s.`expired_date`, s.`qty_akhir`, sum(qty_akhir) as total
									FROM ck_produk p
									LEFT OUTER JOIN ck_stok s
									ON p.id = s.`id_produk`
									WHERE p.nama LIKE '%$string%'
									GROUP BY p.nama
									ORDER BY p.id DESC
									LIMIT 20");
		$no = 1;
		if($list->num_rows()>0){
			foreach($list->result() as $row)
				{
					echo "<tr>";
					echo "<td>";
					echo $no;
					echo "</td>";
					echo "<td>";
					echo $row->nama;
					echo "</td>";
					echo "<td>";
					echo $row->total;
					echo "</td>";
					echo "<.tr>";
					$no++;
				}		
		}
	
		else echo "<tr><td colspan='4'><center>No Data</center></td></tr>";
	}
	
}