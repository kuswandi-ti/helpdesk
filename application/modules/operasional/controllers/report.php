<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class report extends CI_Controller
{
	function __construct() {
		parent::__construct();
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
            'title' => 'Report',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Operational</li>
                             <li>Sales</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
        );
        $this->template->build('v_report', $data);
	}
	
	function get_by_perbekalan($id,$year){
		$q = $this->db->query("SELECT prod.*
			FROM ck_produk prod
			WHERE produk_perbekalan_id = '$id'");
		$no = 1;
		$finalTotal=0;
		foreach($q->result() as $row){
			
			$grandTotal=0;
			echo "<tr>";
			echo "<td>".$no."</td>";
			echo "<td>".$row->nama."</td>";
			for($i=1;$i<=12;$i++){
				$getSales = $this->db->query("
					SELECT sum(det.sub_total) as total 
					from ck_po_customer_detail det
					left outer join ck_po_customer_header h 
					on det.id_po = h.id
					where id_produk='$row->id' and month(h.approved_date)='$i' and year(h.approved_date)='$year'");
				foreach($getSales->result() as $res)
				{
					echo "<td>".number_format($res->total)."</td>";
					$grandTotal=$grandTotal+$res->total;
				}
			}
			echo "<td>".number_format($grandTotal)."</td>";
			echo "</tr>";
			$finalTotal=$finalTotal+$grandTotal;
			$no++;
		}
			echo "<tr><td colspan='14'><span class='pull-right'><b>Grand Total</b></span></td><td>".number_format($finalTotal)."</td></tr>";
	}
	
	function pop_kelompok($id_p)
	{
		$perb = $this->db->query("SELECT * FROM ck_produk_perbekalan where id_parent='$id_p'");
		echo "<option disabled selected hidden>Kelompok</option>";
		foreach($perb->result() as $res)
		{
			echo "<option value='$res->id'>$res->nama($res->kode)</option>";
		}
	}
	function get_by_kelompok($id,$year){
		$q = $this->db->query("SELECT prod.*
			FROM ck_produk prod
			WHERE produk_kelompok_id = '$id'");
		$no = 1;
		$finalTotal=0;
		foreach($q->result() as $row){
			
			$grandTotal=0;
			echo "<tr>";
			echo "<td>".$no."</td>";
			echo "<td>".$row->nama."</td>";
			for($i=1;$i<=12;$i++){
				$getSales = $this->db->query("
					SELECT sum(det.sub_total) as total 
					from ck_po_customer_detail det
					left outer join ck_po_customer_header h 
					on det.id_po = h.id
					where id_produk='$row->id' and month(h.approved_date)='$i' and year(h.approved_date)='$year'");
				foreach($getSales->result() as $res)
				{
					echo "<td>".number_format($res->total)."</td>";
					$grandTotal=$grandTotal+$res->total;
				}
			}
			echo "<td>".number_format($grandTotal)."</td>";
			echo "</tr>";
			$finalTotal=$finalTotal+$grandTotal;
			$no++;
		}
			echo "<tr><td colspan='14'><span class='pull-right'><b>Grand Total</b></span></td><td>".number_format($finalTotal)."</td></tr>";
	}
	
	
	function pop_golongan($id_p)
	{
		$perb = $this->db->query("SELECT * FROM ck_produk_perbekalan where id_parent='$id_p'");
		echo "<option disabled selected hidden>Golongan</option>";
		foreach($perb->result() as $res)
		{
			echo "<option value='$res->id'>$res->nama($res->kode)</option>";
		}
	}
	function get_by_golongan($id,$year){
		$q = $this->db->query("SELECT prod.*
			FROM ck_produk prod
			WHERE produk_golongan_id = '$id'");
		$no = 1;
		$finalTotal=0;
		foreach($q->result() as $row){
			
			$grandTotal=0;
			echo "<tr>";
			echo "<td>".$no."</td>";
			echo "<td>".$row->nama."</td>";
			for($i=1;$i<=12;$i++){
				$getSales = $this->db->query("
					SELECT sum(det.sub_total) as total 
					from ck_po_customer_detail det
					left outer join ck_po_customer_header h 
					on det.id_po = h.id
					where id_produk='$row->id' and month(h.approved_date)='$i' and year(h.approved_date)='$year'");
				foreach($getSales->result() as $res)
				{
					echo "<td>".number_format($res->total)."</td>";
					$grandTotal=$grandTotal+$res->total;
				}
			}
			echo "<td>".number_format($grandTotal)."</td>";
			echo "</tr>";
			$finalTotal=$finalTotal+$grandTotal;
			$no++;
		}
			echo "<tr><td colspan='14'><span class='pull-right'><b>Grand Total</b></span></td><td>".number_format($finalTotal)."</td></tr>";
	}
	
	function pop_jenis($id_p)
	{
		$perb = $this->db->query("SELECT * FROM ck_produk_perbekalan where id_parent='$id_p'");
		echo "<option disabled selected hidden>Jenis</option>";
		foreach($perb->result() as $res)
		{
			echo "<option value='$res->id'>$res->nama($res->kode)</option>";
		}
	}
	
	function get_by_jenis($id,$year){
		$q = $this->db->query("SELECT prod.*
			FROM ck_produk prod
			WHERE produk_jenis_id = '$id'");
		$no = 1;
		$finalTotal=0;
		foreach($q->result() as $row){
			
			$grandTotal=0;
			echo "<tr>";
			echo "<td>".$no."</td>";
			echo "<td>".$row->nama."</td>";
			for($i=1;$i<=12;$i++){
				$getSales = $this->db->query("
					SELECT sum(det.sub_total) as total 
					from ck_po_customer_detail det
					left outer join ck_po_customer_header h 
					on det.id_po = h.id
					where id_produk='$row->id' and month(h.approved_date)='$i' and year(h.approved_date)='$year'");
				foreach($getSales->result() as $res)
				{
					echo "<td>".number_format($res->total)."</td>";
					$grandTotal=$grandTotal+$res->total;
				}
			}
			echo "<td>".number_format($grandTotal)."</td>";
			echo "</tr>";
			$finalTotal=$finalTotal+$grandTotal;
			$no++;
		}
			echo "<tr><td colspan='14'><span class='pull-right'><b>Grand Total</b></span></td><td>".number_format($finalTotal)."</td></tr>";
	}
	
	
	function pop_produk($id_p)
	{
		$perb = $this->db->query("SELECT * FROM ck_produk_perbekalan where id_parent='$id_p'");
		echo "<option disabled selected hidden>Produk</option>";
		foreach($perb->result() as $res)
		{
			echo "<option value='$res->id'>$res->nama($res->kode)</option>";
		}
	}
	
	function get_by_produk($id,$year){
		$q = $this->db->query("SELECT prod.*
			FROM ck_produk prod
			WHERE id = '$id'");
		$no = 1;
		$finalTotal=0;
		foreach($q->result() as $row){
			
			$grandTotal=0;
			echo "<tr>";
			echo "<td>".$no."</td>";
			echo "<td>".$row->nama."</td>";
			for($i=1;$i<=12;$i++){
				$getSales = $this->db->query("
					SELECT sum(det.sub_total) as total 
					from ck_po_customer_detail det
					left outer join ck_po_customer_header h 
					on det.id_po = h.id
					where id_produk='$row->id' and month(h.approved_date)='$i' and year(h.approved_date)='$year'");
				foreach($getSales->result() as $res)
				{
					echo "<td>".number_format($res->total)."</td>";
					$grandTotal=$grandTotal+$res->total;
				}
			}
			echo "<td>".number_format($grandTotal)."</td>";
			echo "</tr>";
			$finalTotal=$finalTotal+$grandTotal;
			$no++;
		}
			echo "<tr><td colspan='14'><span class='pull-right'><b>Grand Total</b></span></td><td>".number_format($finalTotal)."</td></tr>";
	}
	
	function pop_branch($id)
	{
		$perb = $this->db->query("SELECT * FROM ck_branch where region_id='$id'");
		echo "<option disabled selected hidden>Branch</option>";
		foreach($perb->result() as $res)
		{
			echo "<option value='$res->id'>$res->nama($res->kode)</option>";
		}
	}
	function pop_area($id)
	{
		$perb = $this->db->query("SELECT * FROM ck_area where branch_id='$id'");
		echo "<option disabled selected hidden>Area</option>";
		foreach($perb->result() as $res)
		{
			echo "<option value='$res->id'>$res->nama</option>";
		}
	}
	function pop_sales($id)
	{
		$perb = $this->db->query("SELECT * FROM ck_sales where id_area='$id'");
		echo "<option disabled selected hidden>SE</option>";
		foreach($perb->result() as $res)
		{
			echo "<option value='$res->id'>$res->nama_sales</option>";
		}
	}
	
	function get_by_region($year)
	{
		$get = $this->db->query("
		SELECT *
		FROM ck_region
		");
		$no = 1; $finalTotal=0;
		foreach($get->result() as $row){
			$grandTotal=0;
			echo "<tr>";
			echo "<td>".$no."</td>";
			echo "<td>".$row->nama."</td>";
			for($i=1;$i<=12;$i++){
				$getSales = $this->db->query("
					SELECT COALESCE(SUM(h.total),0) AS total
					FROM ck_region reg
					LEFT OUTER JOIN ck_branch br
					ON br.`region_id` = reg.`id`
					LEFT OUTER JOIN ck_area ar
					ON ar.`branch_id` = br.`id`
					LEFT OUTER JOIN ck_sales sa
					ON sa.`id_area` = ar.`id`	
					LEFT OUTER JOIN ck_customer cu
					ON cu.id_Sales = sa.`id`
					LEFT OUTER JOIN ck_po_customer_header h
					ON h.`id_customer` = cu.id
					AND MONTH(h.`approved_date`)='$i'
					AND YEAR(h.`approved_date`)='$year'
					WHERE reg.id=$row->id
					GROUP BY reg.id");
				foreach($getSales->result() as $res)
				{
					echo "<td>".number_format($res->total)."</td>";
					$grandTotal=$grandTotal+$res->total;
				}
			}
			echo "<td>".number_format($grandTotal)."</td>";
			echo "</tr>";
			$finalTotal=$finalTotal+$grandTotal;
			$no++;
		}
			echo "<tr><td colspan='14'><span class='pull-right'><b>Grand Total</b></span></td><td>".number_format($finalTotal)."</td></tr>";
	}
	function get_by_branch($year,$id_reg)
	{
		$get = $this->db->query("
		SELECT *
		FROM ck_branch
		where region_id='$id_reg'
		");
		$no = 1; $finalTotal=0;
		foreach($get->result() as $row){
			$grandTotal=0;
			echo "<tr>";
			echo "<td>".$no."</td>";
			echo "<td>".$row->nama."</td>";
			for($i=1;$i<=12;$i++){
				$getSales = $this->db->query("
					SELECT COALESCE(SUM(h.total),0) AS total
					FROM ck_region reg
					LEFT OUTER JOIN ck_branch br
					ON br.`region_id` = reg.`id`
					LEFT OUTER JOIN ck_area ar
					ON ar.`branch_id` = br.`id`
					LEFT OUTER JOIN ck_sales sa
					ON sa.`id_area` = ar.`id`
					LEFT OUTER JOIN ck_customer cu
					ON cu.id_Sales = sa.`id`
					LEFT OUTER JOIN ck_po_customer_header h
					ON h.`id_customer` = cu.id
					AND MONTH(h.`approved_date`)='$i'
					AND YEAR(h.`approved_date`)='$year'
					WHERE br.id = '$row->id'
					GROUP BY br.id");
				foreach($getSales->result() as $res)
				{
					echo "<td>".number_format($res->total)."</td>";
					$grandTotal=$grandTotal+$res->total;
				}
			}
			echo "<td>".number_format($grandTotal)."</td>";
			echo "</tr>";
			$finalTotal=$finalTotal+$grandTotal;
			$no++;
		}
			echo "<tr><td colspan='14'><span class='pull-right'><b>Grand Total</b></span></td><td>".number_format($finalTotal)."</td></tr>";
	}
	function get_by_area($year,$id_branch)
	{
		$get = $this->db->query("
		SELECT *
		FROM ck_area
		where branch_id='$id_branch'
		");
		$no = 1; $finalTotal=0;
		foreach($get->result() as $row){
			$grandTotal=0;
			echo "<tr>";
			echo "<td>".$no."</td>";
			echo "<td>".$row->nama."</td>";
			for($i=1;$i<=12;$i++){
				$getSales = $this->db->query("
					SELECT COALESCE(SUM(h.total),0) AS total
					FROM ck_region reg
					LEFT OUTER JOIN ck_branch br
					ON br.`region_id` = reg.`id`
					LEFT OUTER JOIN ck_area ar
					ON ar.`branch_id` = br.`id`
					LEFT OUTER JOIN ck_sales sa
					ON sa.`id_area` = ar.`id`
					LEFT OUTER JOIN ck_customer cu
					ON cu.id_Sales = sa.`id`
					LEFT OUTER JOIN ck_po_customer_header h
					ON h.`id_customer` = cu.id
					AND MONTH(h.`approved_date`)='$i'
					AND YEAR(h.`approved_date`)='$year'
					WHERE ar.id = '$row->id'
					GROUP BY br.id");
				foreach($getSales->result() as $res)
				{
					echo "<td>".number_format($res->total)."</td>";
					$grandTotal=$grandTotal+$res->total;
				}
			}
			echo "<td>".number_format($grandTotal)."</td>";
			echo "</tr>";
			$finalTotal=$finalTotal+$grandTotal;
			$no++;
		}
			echo "<tr><td colspan='14'><span class='pull-right'><b>Grand Total</b></span></td><td>".number_format($finalTotal)."</td></tr>";
	}
	function get_by_sales($year,$id_area)
	{
		$get = $this->db->query("
		SELECT *
		FROM ck_sales
		where id_area='$id_area'
		");
		$no = 1; $finalTotal=0;
		foreach($get->result() as $row){
			$grandTotal=0;
			echo "<tr>";
			echo "<td>".$no."</td>";
			echo "<td>".$row->nama_sales."</td>";
			for($i=1;$i<=12;$i++){
				$getSales = $this->db->query("
					SELECT COALESCE(SUM(h.total),0) AS total
					FROM ck_region reg
					LEFT OUTER JOIN ck_branch br
					ON br.`region_id` = reg.`id`
					LEFT OUTER JOIN ck_area ar
					ON ar.`branch_id` = br.`id`
					LEFT OUTER JOIN ck_sales sa
					ON sa.`id_area` = ar.`id`
					LEFT OUTER JOIN ck_customer cu
					ON cu.id_Sales = sa.`id`
					LEFT OUTER JOIN ck_po_customer_header h
					ON h.`id_customer` = cu.id
					AND MONTH(h.`approved_date`)='$i'
					AND YEAR(h.`approved_date`)='$year'
					WHERE sa.id = '$row->id'
					GROUP BY br.id");
				foreach($getSales->result() as $res)
				{
					echo "<td>".number_format($res->total)."</td>";
					$grandTotal=$grandTotal+$res->total;
				}
			}
			echo "<td>".number_format($grandTotal)."</td>";
			echo "</tr>";
			$finalTotal=$finalTotal+$grandTotal;
			$no++;
		}
			echo "<tr><td colspan='14'><span class='pull-right'><b>Grand Total</b></span></td><td>".number_format($finalTotal)."</td></tr>";
	}
	function get_per_sales($year,$id)
	{
		$get = $this->db->query("
		SELECT *
		FROM ck_sales
		where id='$id'
		");
		$no = 1; $finalTotal=0;
		foreach($get->result() as $row){
			$grandTotal=0;
			echo "<tr>";
			echo "<td>".$no."</td>";
			echo "<td>".$row->nama_sales."</td>";
			for($i=1;$i<=12;$i++){
				$getSales = $this->db->query("
					SELECT COALESCE(SUM(h.total),0) AS total
					FROM ck_region reg
					LEFT OUTER JOIN ck_branch br
					ON br.`region_id` = reg.`id`
					LEFT OUTER JOIN ck_area ar
					ON ar.`branch_id` = br.`id`
					LEFT OUTER JOIN ck_sales sa
					ON sa.`id_area` = ar.`id`
					LEFT OUTER JOIN ck_customer cu
					ON cu.id_Sales = sa.`id`
					LEFT OUTER JOIN ck_po_customer_header h
					ON h.`id_customer` = cu.id
					AND MONTH(h.`approved_date`)='$i'
					AND YEAR(h.`approved_date`)='$year'
					WHERE sa.id = '$row->id'
					GROUP BY br.id");
				foreach($getSales->result() as $res)
				{
					echo "<td>".number_format($res->total)."</td>";
					$grandTotal=$grandTotal+$res->total;
				}
			}
			echo "<td>".number_format($grandTotal)."</td>";
			echo "</tr>";
			$finalTotal=$finalTotal+$grandTotal;
			$no++;
		}
			echo "<tr><td colspan='14'><span class='pull-right'><b>Grand Total</b></span></td><td>".number_format($finalTotal)."</td></tr>";
	}
}