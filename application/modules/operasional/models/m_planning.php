<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_planning extends CI_Model
{
	function get_sales()
	{
		$get = $this->db->query('select * from ck_sales');
		echo json_encode($get->result());
	}
	
	function insert_data($data)
	{
		$insert=$this->db->insert('ck_global_planning',$data);
		if($insert)
			echo "done";
		else echo $this->db->_error_message();
		
	}
	
	function get_data($id)
	{
		$get = $this->db->query("
					SELECT gp.*,  s.nama_sales, MONTHNAME(STR_TO_DATE(gp.bulan, '%m')) AS nama_bulan, a.nama AS nama_area
					FROM ck_global_planning gp
					LEFT OUTER JOIN ck_sales s 
					ON s.id = gp.id_sales
					LEFT OUTER JOIN ck_area a
					ON a.id = s.id_area
					WHERE gp.id='$id'
					");
		echo json_encode($get->result());
	}
	
	function update_data($id,$data)
	{
		$update = $this->db->update('ck_global_planning',$data,array("id"=>$id));
		if($update)
			echo "done";
		else echo $this->db->_error_message();
	}
	
	function delete_data($id)
	{
		$del = $this->db->query("delete from ck_global_planning where id='$id'");
		if($del)
			echo "done";
		else echo $this->db->_error_message();
	}
}