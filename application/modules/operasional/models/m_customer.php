<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_customer extends CI_Model
{
	
	function get_kelompok()
	{
		return  $this->db->query('SELECT id, nama FROM ck_customer_kelompok');
	}
	function get_provinsi()
	{
		return  $this->db->query('SELECT id, nama FROM ck_provinsi');
	}
	function get_kota($id)
	{
		$result =  $this->db->query("SELECT id, nama FROM ck_kabupatenkota WHERE provinsi_id='$id'");
		//header('Content-Type: application/json');
		echo json_encode($result->result());
	}
	function get_cp($id)
	{
		$front = substr($id,0);
		if($front == 'a')
			$id = substr($id,1);
		
		$result = $this->db->query("SELECT * from ck_pic WHERE id_customer = '$id'");
		echo "<table class='table datatable'>";
		echo "<thead><tr>";
			echo "<td>No";
			echo "</td>";
			echo "<td>CP";
			echo "</td>";
			echo "<td>Title";
			echo "</td>";
			echo "<td>Email";
			echo "</td>";
			echo "<td>Phone";
			echo "</td>";
			echo "<td>Photo";
			echo "</td>";
		echo "</tr></thead>";
		$no=1;
		foreach($result->result() as $res)
		{
			echo "<tbody>";
			echo "<tr>";
				echo "<td>".$no;
				echo "</td>";
				echo "<td>".$res->pic_nama;
				echo "</td>";
				echo "<td>".$res->pic_jabatan;
				echo "</td>";
				echo "<td>".$res->pic_email;
				echo "</td>";
				echo "<td>".$res->pic_hp;
				echo "</td>";
				echo "<td> <a href='".$res->pic_foto."'> Image </a>";
				echo "</td>";
			echo "</tr>";
			echo "</tbody>";
			$no++;
		}
		echo "</table>";
	}
	function get_region()
	{
		return  $this->db->query('SELECT * FROM ck_region');
	}
	
	function get_branch($id)
	{
		$result =  $this->db->query("SELECT * FROM ck_branch WHERE region_id='$id'");
		//header('Content-Type: application/json');
		echo json_encode($result->result());
	}
	
	function get_area($id)
	{
		$result =  $this->db->query("SELECT * FROM ck_area WHERE branch_id='$id'");
		//header('Content-Type: application/json');
		echo json_encode($result->result());
	}
	function get_sales($id)
	{
		$result =  $this->db->query("SELECT * FROM ck_sales WHERE id_area='$id'");
		//header('Content-Type: application/json');
		echo json_encode($result->result());
	}
	
	
	function input_data($data)
	{
		$insert = $this->db->insert('ck_customer',$data);
		if($insert)
			return $this->db->insert_id();
		else return false;
	}
	
	function input_alamat($data)
	{
		$insert = $this->db->insert('ck_alamat_customer',$data);
		if($insert)
			return $this->db->insert_id();
		else return false;
	}
	
	function input_alamat_tagihan($data)
	{
		$insert = $this->db->insert('ck_alamat_tagihan_customer',$data);
		if($insert)
			return $this->db->insert_id();
		else return false;
	}
	
	function get_kode_branch($id)
	{
		$result = $this->db->query("Select kode from ck_branch where id=$id");
		foreach($result->result() as $r)
			return $r->kode;
	}
	
	function get_customer_detail($id)
	{
		$id = substr($id,1);
		$query = "	select 
						c.nama, c.kode_pos, c.id_provinsi, c.id_kabupatenkota, c.id_customer_kelompok,
						c.id_alamat_tagihan_default,c.id_alamat_default, c.npwp, c.telepon, c.faks,
						c.email,c.website,c.latitude,c.longitude, c.id_region, c.id_branch, c.id_area,
						c.id_sales, c.foto_1, c.foto_2, c.foto_3, c.deskripsi, p.pic_nama,
						p.pic_jabatan, p.pic_hp,p.pic_email, p.pic_foto, ac.alamat_kirim,  atc.alamat_tagihan, ck.nama as nama_kelompok,
						pro.nama as provinsi, kab.nama as kota, reg.nama as region, bran.nama as branch,
						ar.nama as area, sa.nama_sales, kel.id as id_kelas, kel.kelas
					
					from ck_customer c
					left outer join ck_alamat_customer ac
					on c.id_alamat_default = ac.id
					left outer join ck_alamat_tagihan_customer atc
					on c.id_alamat_tagihan_default = atc.id
					left outer join ck_customer_kelompok ck
					on ck.id = c.id_customer_kelompok
					left outer join ck_provinsi pro
					on pro.id = c.id_provinsi
					left outer join ck_kabupatenkota kab
					on kab.id = c.id_kabupatenkota		
					left outer join ck_region reg
					on reg.id = c.id_region
					left outer join ck_branch bran
					on bran.id = c.id_branch
					left outer join ck_area ar
					on ar.id = c.id_area
					left outer join ck_sales sa
					on sa.id = c.id_sales
					left outer join ck_kelas_customer kel
					on kel.id = c.id_kelas_customer
					left outer join ck_pic p
					on p.id = c.id_pic_default
					where c.id = $id;
				";
		$exec = $this->db->query($query);
		echo json_encode($exec->result());
	}
	
	function delete_data($id)
	{
		$id = substr($id,1);
		$delete = $this->db->query("delete from ck_customer where id = '$id'");
		if($delete)
			echo "done";
		else echo $this->db->_error_message();
	}
}