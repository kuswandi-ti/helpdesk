<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class selling_data extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('m_selling_data'); 
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
            'title' => 'Selling Data',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Operational</li>
                             <li>Sales</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
        );
        $this->template->build('v_selling_data', $data);
	}
	
	function chart_penjualan()
	{
		$id_sales = $_GET['id_sales'];
		$month=$_GET['month'];
		$year=$_GET['year'];
		$his_selling = $this->m_selling_data->get_his_selling($id_sales,$month,$year);
		$avg_selling = $this->m_selling_data->get_avg_selling($month,$year);
		$highest_selling = $this->m_selling_data->get_highest_selling($month,$year);
		echo $his_selling.','.$avg_selling.','.$highest_selling	;
		
	}
	
	function selling_target($bln, $thn)
	{
		$list_sales = $this->m_selling_data->list_sales();
		$nom_target=0;
		$nom_real=0;
		$dev;
		$no = 1;
		foreach($list_sales->result() as $x)
		{
			echo "<tr>";
				echo "<td>".$no."</td>";
				echo "<td>".$x->nama_sales."</td>";
				
				$target = $this->db->query("
				SELECT * 
				FROM ck_target_sales
				WHERE id_sales = '$x->id'
				AND bulan='$bln' 
				AND tahun='$thn'");
				if($target->num_rows()>0){
					foreach($target->result() as $trgt){
						echo "<td>".number_format($trgt->target)."</td>";
						$nom_target = $trgt->target;
					}
				}
				else {
					echo "<td>0</td>";
					$nom_target = 0;
				}
				$realisasi = $this->db->query("
								SELECT SUM(h.total) as total
								FROM ck_po_customer_header h
								LEFT OUTER JOIN ck_customer c
								ON c.id = h.`id_customer`
								LEFT OUTER JOIN ck_sales s
								ON s.id = c.`id_sales`
								WHERE id_sales =  '$x->id'
								AND MONTH(h.`approved_date`) = '$bln'
								AND YEAR (h.`approved_date`) = '$thn'
				");
				foreach($realisasi->result() as $real){
					echo "<td>".number_format($real->total)."</td>";
					$nom_real = $real->total;
				}
				$at = ($nom_real)/($nom_target)*100;
				echo "<td>".number_format($at,2)."%</td>";
				
				$dev = ($nom_real)-($nom_target);
				$no++;
				echo "<td>".number_format($dev)."</td>";
			echo "</tr>";
		}
	}
	 
	function load_monthly_target($id_sales)
	{
		extract($_POST);
		$i = 1;
		for($i=1;$i<=12;$i++){
			echo "<tr>";
				echo "<td>";
					echo $i;
				echo "</td>";
				echo "<td>";
					echo date("F", strtotime(date("Y")."-".$i."-01"));
				echo "</td>";
				echo "<td>";
					echo $tahun;
				echo "</td>";
				
			$get = $this->db->query("SELECT * FROM ck_target_sales WHERE id_sales = '$id_sales' AND bulan='$i' AND tahun = '$tahun'");
			foreach($get->result() as $row)
			{
				echo "<td>";
					echo "<input disabled id='input_target_edit' value='".number_format($row->target)."'>";
				echo "</td>";
				echo "<td>";
					echo "	<input type='hidden' id='id_target' value='$row->id'>
							<button id='btn_edit_target' class='btn btn-warning btn-sm'> Edit </button>&nbsp;
							<button disabled id='btn_update_target' class='btn btn-info btn-sm'> Save </button>
						";
				echo "</td>";
			}
			echo "</tr>";
		}
	}
	
	
	function insert_target(){
		extract ($_POST);
		
		//cek udah ada target apa belom dari sales ini di bulan,tahun
		$cek = $this->db->query("SELECT * FROM ck_target_sales WHERE id_sales='$id_sales' and bulan='$bulan' and tahun ='$tahun'");
		if($cek->num_rows() > 0)
		{ // kalau ada datanya, update nominal targetnya aja
			foreach($cek->result() as $r)
			{
				$upd = $this->db->query("update ck_target_sales set target = '$target' where id = '$r->id'");
				if($upd)
					echo "done";
				else echo $this->db->_error_message();
			}
		}
		else
		{ // kalau belum ada datanya. insert
			$ins = $this->db->insert('ck_target_sales',$_POST);
			if($ins)
				echo "done";
			else echo $this->db->_error_message();
		}
	}
	
	function update_target_by_id($id)
	{
		$upd=$this->db->update('ck_target_sales',$_POST,array('id'=>$id));
		if($upd)
			echo "done";
		else echo $this->db->_error_message();
	}

}