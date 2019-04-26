<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_account_planning extends CI_Model
{
	function get_customer($idsales)
	{
		$get = $this->db->query("select * from ck_customer where id_sales='$idsales'");
		echo json_encode($get->result());
	}
	
	function set_account_planning()
	{
		
		$id_customer = substr($_POST['id_customer'],1);
		$bulan = $_POST['bulan'];
		$tahun = $_POST['tahun'];
		$total_visit = $_POST['total_visit'];
		$tahun = $_POST['tahun'];
		$target = $_POST['target'];
		$kebutuhan_customer = $_POST['kebutuhan_customer'];
		$deskripsi = $_POST['deskripsi'];
		
		//get if already exist FOR same month & year
		$get = $this->db->query("select * from ck_lembar_kerja
								where id_customer='$id_customer' and bulan='$bulan' and tahun='$tahun' ");
		
		if($get->num_rows() >0)
		{
			echo "Plan For This Date Already Set!";
		}
		else
		{
			$insert = $this->db->query("insert into ck_lembar_kerja (id_customer,bulan,tahun,kebutuhan_customer, total_visit,target,deskripsi)
										VALUES ('$id_customer','$bulan','$tahun','$kebutuhan_customer','$total_visit','$target','".mysql_real_escape_string($deskripsi)."');
										");
			$id_lembar_kerja = $this->db->insert_id();
			if($insert)
			{
				//create default visit 
				for($i=0;$i<$total_visit;$i++)
				{
					$create_visit = $this->db->query("insert into ck_visit(id_lembar_kerja) values('$id_lembar_kerja')");
				}
				echo "done".$id_lembar_kerja;
			}
				
			else echo $this->db->_error_message();
		}
	}
	
	function get_kebutuhan($idcust)
	{
		$idcust=substr($idcust,1);
		$kebutuhan = $this->db->query("SElect kebutuhan from ck_customer where id='$idcust'");
		foreach($kebutuhan->result() as $r)
			echo $r->kebutuhan;
	}
	
	function set_needs()
	{
		$id = substr($_POST['id'],1);
		$kebutuhan =$_POST['kebutuhan'];
		$set = $this->db->query("update ck_customer set kebutuhan='$kebutuhan' where id='$id'");
		if($set)
			echo "done";
		else echo $this->db->_error_message();
	}
	
	function get_ap_detail($id)
	{
		return $this->db->query("SElect lk.* , cust.nama
								FROM ck_lembar_kerja lk
								LEFT OUTER JOIN ck_customer cust
								ON cust.id = lk.id_customer
								WHERE lk.id='$id'
		");
	}
	
	function set_detail()
	{
		$insert = $this->db->insert("ck_visit",$_POST);
		if($insert)
			echo "done".$this->db->insert_id();
		else echo $this->db->_error_message();
	}
	function set_realisasi($idvisit)
	{
		//print_r($_POST);
		$insert = $this->db->insert("ck_visit_realisasi",$_POST);
		if($insert)
			echo "done".$this->db->insert_id();
		else echo $this->db->_error_message();
	}
	
	function count_realisasi($idvisit)
	{
		$get = $this->db->query("select * from ck_visit_realisasi where id_visit='$idvisit'");
		echo $get->num_rows();
	}
	
	function get_realisasi($idvisit)
	{
		$get = $this->db->query("select * from ck_visit_realisasi where id_visit='$idvisit'");
		echo json_encode($get->result());
	}
	
	function get_detail($idap)
	{
		return $this->db->query("select * from ck_visit where id_lembar_kerja='$idap'");
	}
	
	function update_reason($id)
	{
		$upd = $this->db->update("ck_lembar_kerja",$_POST,array('id'=>$id));
		if($upd)
			echo "done";
		else echo $this->db->_error_message();
	}
	
	function show_detail($idap)
	{
		$get = $this->db->query("select * from ck_visit where id_lembar_kerja='$idap'");
		$no = 1;
		if($get->num_rows()==0)
			echo "<tr><td colspan='6'><center>No Data</center></td></tr>";
		foreach($get->result() as $x)
		{
			
			echo 	"<tr>";
			echo 	"<th>".$no."</th>";$no++;
			echo 	"<th><input disabled class=\"vtanggal\" placeholder=\"Select Date\" value='".$x->tanggal."'></th>";
			echo 	"<th><select disabled id=\"vjam_mulai\"  class=\"vjam_mulai\">";
					$y='';
					for($i=0;$i<24;$i++)
									{
										if($i<10)
										$y = '0'.$i;
										else 
										$y = $i;
			echo 	"<option ".($i==$x->jam_mulai?"selected":"") ." value='$i'>$y</option>";
					}
			echo	"</select>:"."
					<select disabled id=\"vmenit_mulai\"   class=\"vmenit_mulai\">";
					for($i=0;$i<60;$i+=5)
									{
										if($i<10)
										$y = '0'.$i;
										else 
										$y = $i;
			echo	 "<option ".($i==$x->menit_mulai?"selected":"") ." value='$i'>$y</option>";
					}					
			echo	"</select></th>";
			echo 	"<th><select disabled id=\"vjam_selesai\"  class=\"vjam_selesai\">";
					for($i=0;$i<24;$i++)
									{
										if($i<10)
										$y = '0'.$i;
										else 
										$y = $i;
			echo 	"<option ".($i==$x->jam_selesai?"selected":"") ." value='$i'>$y</option>";
					}
			echo	"</select>:"."
					<select disabled id=\"vmenit_selesai\"   class=\"vmenit_selesai\">";
					for($i=0;$i<60;$i+=5)
									{
										if($i<10)
										$y= '0'.$i;
										else 
										$y = $i;
			echo	 "<option ".($i==$x->menit_selesai?"selected":"") ." value='$i'>$y</option>";
					}					
			echo	"</select></th>";
			echo	"<th><textarea disabled id='vmateri'  rows='2' style='width:100%;'>".$x->materi."</textarea></th>";
			echo	"<th><textarea disabled id='vstrategi'  rows='2' style='width:100%;'>".$x->strategi."</textarea></th>";
		}
	}
	
	function update_detail($id)
	{
		$upd = $this->db->update('ck_visit',$_POST,array("id"=>$id));
		if($upd)
			echo "done";
		else echo $this->db->_error_message();
		
	}
	
	
	function delete_ap($id)
	{
		$del_detail = $this->db->query("delete from ck_visit where id_lembar_kerja='$id'");
		if($del_detail)
		{
			$del_head = $this->db->query("delete from ck_lembar_kerja where id='$id'");
			if($del_head)
				echo "done";
			else echo $this->db->_error_message();
		}
		else echo $this->db->_error_message();
	}
}
