<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class inquiry extends CI_Controller
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
            'title' => 'Inquiry',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Operational</li>
                             <li>Sales</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
        );
        $this->template->build('v_inquiry', $data);
	}
	
	function get_penjualan_by_area()
	{
		$get = $this->db->query("
			SELECT reg.`nama`, SUM(total) as total
			FROM ck_region reg
			LEFT OUTER JOIN ck_branch br
			ON reg.id = br.`region_id`
			LEFT OUTER JOIN ck_area ar
			ON br.id = ar.`branch_id`
			LEFT OUTER JOIN ck_customer cust
			ON ar.id = cust.`id_area`
			LEFT OUTER JOIN ck_po_customer_header po
			ON po.`id_customer` = cust.`id`
			GROUP BY reg.id
		");
		$no = 1;
		echo "<table class='table datatable'>";
		echo "<tr>";
		echo "<th> No ";
		echo "</th>";
		echo "<th> Region Name"; 
		echo "</th>";
		echo "<th> Total";
		echo "</th>";
		echo "</tr>";
		echo "<tr>";
		foreach($get->result() as $row)
		{
			echo "<td>".$no;
			echo "</td>";
			echo "<td>".$row->nama;
			echo "</td>"; 
			echo "<td>".number_format($row->total);
			echo "</td>";
			echo "</tr>";
			$no++;
		}
		
			echo "</table>";
	}
	function get_penjualan_by_sales()  
	{
		$year=$_POST['year'];
		// $get = $this->db->query("
			// SELECT s.id as id_sales,s.`nama_sales`, SUM(h.total) as total
			// FROM ck_po_customer_header h
			// LEFT OUTER JOIN ck_customer c
			// ON h.`id_customer` = c.id
			// LEFT OUTER JOIN ck_sales s
			// ON s.id = c.`id_sales`
			// WHERE 
			 // YEAR(h.`approved_date`) = '2018'
			 // AND MONTH(h.`approved_date`)='$i'
			// GROUP BY nama_sales 
		// ");
		$get = $this->db->query("
						SELECT  s.id as id_sales,s.`nama_sales`
						FROM ck_po_customer_header h
						LEFT OUTER JOIN ck_customer c
						ON c.id = h.`id_customer`
						LEFT OUTER JOIN ck_sales s
						ON c.`id_sales` = s.id
						 group by nama_sales");
		$no = 1;
				
		echo "<table class='table datatable'>";
		echo "<tr>";
		echo "<th> No ";
		echo "</th>";
		echo "<th> Sales Name";
		echo "</th>";
		for($i=1;$i<13;$i++)
		{
			echo "<th>".date('F', strtotime("2000-$i-01")).	"</th>";
		}
		echo "</tr>";
		foreach($get->result() as $row)
		{
			echo "<tr>";
			echo "<td>".$no;
			echo "</td>";
			echo "<td>".strtoupper($row->nama_sales);
			echo "</td>";
			for($i=1;$i<13;$i++)
			{
				
				$q = $this->db->query("
						SELECT s.`nama_sales`, c.nama,no_so, h.`approved_date`, SUM(h.total) as total
						FROM ck_po_customer_header h
						LEFT OUTER JOIN ck_customer c
						ON c.id = h.`id_customer`
						LEFT OUTER JOIN ck_sales s
						ON c.`id_sales` = s.id
						WHERE 
						 YEAR(h.`approved_date`) = '$year'
						 AND MONTH(h.`approved_date`)='$i'
						 and  s.id='$row->id_sales'");
				foreach($q->result() as $r)
				{
					echo "<td>".number_format($r->total)."</td>";
				}	
				
			}
			echo "</tr>";
			$no++;
		}
		
			echo "</table>";
	}
	function get_penjualan_by_cust($year)  
	{
		$get = $this->db->query("
			SELECT c.`nama`, SUM(total) as total
			FROM ck_po_customer_header h
			LEFT OUTER JOIN ck_customer c
			ON h.`id_customer` = c.id
			LEFT OUTER JOIN ck_sales s
			ON s.id = c.`id_sales`
			where year(h.approved_date)='$year'
			GROUP BY c.nama
		");
		$no = 1;
		echo "<table class='table datatable'>";
		echo "<tr>";
		echo "<th> No ";
		echo "</th>";
		echo "<th> Customer Name";
		echo "</th>";
		echo "<th> Total";
		echo "</th>";
		echo "</tr>";
		echo "<tr>";
		foreach($get->result() as $row)
		{
			echo "<td>".$no;
			echo "</td>";
			echo "<td>".$row->nama;
			echo "</td>";
			echo "<td>".number_format($row->total);
			echo "</td>";
			echo "</tr>";
			$no++;
		}
		
			echo "</table>";
	}
	function get_penjualan_by_product($year='')  
	{
		if($year==''){
			$year = date('Y');
			$get= $this->db->query("
			SELECT pro.id,pro.`nama`, COUNT(pro.`nama`),sum(d.jumlah_acc) as qty_item, d.harga_jual, SUM(d.`sub_total`) as total
			FROM ck_produk pro
			LEFT OUTER JOIN ck_po_customer_detail d
			ON  pro.id = d.`id_produk`
			LEFT OUTER JOIN ck_po_customer_header h
			ON d.`id_po` = h.`id`
			LEFT OUTER JOIN ck_customer c
			ON h.`id_customer` = c.id
			LEFT OUTER JOIN ck_sales s
			ON s.id = c.`id_sales`
			WHERE year(h.approved_date)='$year'
			GROUP BY pro.`id` HAVING total>0
			ORDER BY total desc
		");
		}
		else
		$get = $this->db->query("
			SELECT pro.id,pro.`nama`, COUNT(pro.`nama`),sum(d.jumlah_acc) as qty_item, d.harga_jual, SUM(d.`sub_total`) as total
			FROM ck_produk pro
			LEFT OUTER JOIN ck_po_customer_detail d
			ON  pro.id = d.`id_produk`
			LEFT OUTER JOIN ck_po_customer_header h
			ON d.`id_po` = h.`id`
			LEFT OUTER JOIN ck_customer c
			ON h.`id_customer` = c.id
			LEFT OUTER JOIN ck_sales s
			ON s.id = c.`id_sales`
			WHERE year(h.approved_date)='$year'
			GROUP BY pro.`id`
			
			ORDER BY total desc
		");
		$no = 1;
		echo "<table class='table datatable'>";
		echo "<tr>";
		echo "<th> No ";
		echo "</th>";
		echo "<th> Product ID";
		echo "</th>";
		echo "<th> Product Name";
		echo "</th>";
		echo "<th> Sold Qty";
		echo "</th>";
		echo "<th> Price";
		echo "</th>";
		echo "<th> Total";
		echo "</th>";
		echo "</tr>";
		echo "<tr>";
		foreach($get->result() as $row)
		{
			echo "<td>".$no;
			echo "</td>";
			echo "<td>".$row->id;
			echo "</td>";
			echo "<td>".$row->nama;
			echo "</td>";
			echo "<td>".$row->qty_item;
			echo "</td>";
			echo "<td>".number_format($row->harga_jual);
			echo "</td>";
			echo "<td>".number_format($row->total);
			echo "</td>";
			echo "</tr>";
			$no++;
		}
		
			echo "</table>";
	}
	 
	function get_visit_by_period(){
		extract($_POST);
		$get = $this->db->query("
			SELECT b.id , cust.nama as nama_c, b.nama, COALESCE(SUM(lk.`total_visit`) ,'0') AS total_visit, sum(lk.target) as target, SUM(h.`total`) AS actual,  TRUNCATE((SUM(h.`total`)/SUM(lk.`target`)*100),2) AS percent
			FROM ck_bulan b
			LEFT OUTER JOIN ck_lembar_kerja lk
			ON lk.`bulan` = b.id
			AND lk.tahun = '$tahun'
			AND lk.id_customer = '$id_customer'
			left outer join ck_customer cust
			on cust.id = lk.id_customer
			left outer join ck_po_customer_header h
			on h.id_customer = cust.id
			GROUP BY b.id");
			
		$no = 1;
		echo "<table class='table datatable'>";
		echo "<tr>";
		echo "<th> No ";
		echo "</th>";
		echo "<th> Month";
		echo "</th>";
		echo "<th> Total Visit";
		echo "</th>";
		echo "<th> Target (IDR)";
		echo "</th>";
		echo "<th> Actual (IDR)";
		echo "</th>";
		echo "<th> %";
		echo "</th>";
		echo "</tr>";
		echo "<tr>";
		foreach($get->result() as $row)
		{
			echo "<td>".$no;
			echo "</td>";
			echo "<td>".$row->nama;
			echo "</td>";
			echo "<td>".$row->total_visit;
			echo "</td>";
			echo "<td>".number_format($row->target);
			echo "</td>";
			echo "<td>".number_format($row->actual);
			echo "</td>";
			echo "<td>".$row->percent;
			echo "</td>";
			echo "</tr>";
			$no++;
		}
		
			echo "</table>";
	}
	
	function get_visit_by_cust(){
		extract($_POST);
		$get = $this->db->query("
			SELECT cust.id,cust.nama, sal.nama_sales ,lk.total_visit, SUM(lk.`target`) AS target, SUM(h.`total`) AS actual,  TRUNCATE((SUM(h.`total`)/SUM(lk.`target`)*100),2) AS percent
			FROM ck_customer cust
			LEFT OUTER JOIN ck_po_customer_header h
			ON h.`id_customer` = cust.id
			LEFT OUTER JOIN ck_sales sal
			on sal.id = cust.id_sales
			LEFT OUTER JOIN ck_lembar_kerja lk
			ON lk.id_customer = cust.`id`
			AND bulan = '$bulan'
			AND tahun = '$tahun'
			GROUP BY cust.nama HAVING total_visit > 0");
			
		$no = 1;
		echo "<table class='table datatable'>";
		echo "<tr>";
		echo "<th> No ";
		echo "</th>";
		echo "<th> Id Cust";
		echo "</th>";
		echo "<th> Customer";
		echo "</th>";
		echo "<th> Sales Executive";
		echo "</th>";
		echo "<th> Total Visit";
		echo "</th>";
		echo "<th> Target (IDR)";
		echo "</th>";
		echo "<th> Actual (IDR)";
		echo "</th>";
		echo "<th> %";
		echo "</th>";
		echo "</tr>";
		echo "<tr>";
		foreach($get->result() as $row)
		{
			echo "<td>".$no;
			echo "</td>";
			echo "<td>".$row->id;
			echo "</td>";
			echo "<td>".$row->nama;
			echo "</td>";
			echo "<td>".$row->nama_sales;
			echo "</td>";
			echo "<td>".$row->total_visit;
			echo "</td>";
			echo "<td>".number_format($row->target);
			echo "</td>";
			echo "<td>".number_format($row->actual);
			echo "</td>";
			echo "<td>".$row->percent;
			echo "</td>";
			echo "</tr>";
			$no++;
		}
		
			echo "</table>";
	}
	
	function get_visit_by_area()
	{
		$get = $this->db->query("SELECT reg.nama, SUM(lk.target) AS target, SUM(h.`total`) AS realisasi
		FROM ck_region reg
		LEFT OUTER JOIN ck_branch br
		ON br.`region_id` = reg.`id`
		LEFT OUTER JOIN ck_area ar
		ON ar.`branch_id` = br.`id`
		LEFT OUTER JOIN ck_customer c
		ON c.`id_area` = ar.`id`
		LEFT OUTER JOIN ck_lembar_kerja lk
		ON lk.`id_customer` = c.`id`
		LEFT OUTER JOIN ck_po_customer_header h
		ON h.`id_customer` = c.`id`
		GROUP BY reg.id");
		$no = 1;
		echo "<table class='table datatable'>";
		echo "<tr>";
		echo "<th> No ";
		echo "</th>";
		echo "<th> Region";
		echo "</th>";
		echo "<th> Target";
		echo "</th>";
		echo "<th> Total";
		echo "</th>";
		echo "</tr>";
		echo "<tr>";
		foreach($get->result() as $row)
		{
			echo "<td>".$no;
			echo "</td>";
			echo "<td>".$row->nama;
			echo "</td>";
			echo "<td>".number_format($row->target);
			echo "</td>";
			echo "<td>".number_format($row->realisasi);
			echo "</td>";
			echo "</tr>";
			$no++;
		}
		
			echo "</table>";
	}
	
	function get_sales_by_year($year) // grafik purpose
	{
		$get = $this->db->query("
			SELECT COALESCE(SUM(total),0) AS total
			FROM ck_bulan b
			LEFT OUTER JOIN ck_po_customer_header h
			ON b.id = MONTH(h.`approved_date`)
			AND YEAR(h.`approved_date`) = '$year'
			GROUP BY b.id");
		
		echo json_encode($get->result());
	}
	
	function get_sales_by_cust($idcust,$year) // grafik purpose
	{
		$get = $this->db->query("
			SELECT COALESCE(SUM(total),0) AS total
			FROM ck_bulan b
			LEFT OUTER JOIN ck_po_customer_header h
			ON b.id = MONTH(h.`approved_date`)
			AND YEAR(h.`approved_date`) = '$year'
			AND h.`id_customer` = '$idcust'
			GROUP BY b.id");
		
		echo json_encode($get->result());
	}
	function get_sales_by_product($idprod,$year) // grafik purpose
	{
		$get = $this->db->query("
			SELECT COALESCE(SUM(jumlah_acc),0) AS total
			FROM ck_bulan b
			LEFT OUTER JOIN ck_po_customer_header h
			ON b.id = MONTH(h.`approved_date`)
			AND YEAR(h.approved_date) = '$year'
			LEFT OUTER JOIN ck_po_customer_detail d
			ON d.id_po = h.id
			AND id_produk = '$idprod'

			GROUP BY b.id");
		
		echo json_encode($get->result());
	}
	
	function get_top_ten($year)
	{
		$get = $this->db->query("
			SELECT pro.`nama` AS nama,SUM(d.jumlah_acc) AS total_qty, SUM(d.`sub_total`) AS total_sales
			FROM ck_produk pro
			LEFT OUTER JOIN ck_po_customer_detail d
			ON  pro.id = d.`id_produk`
			LEFT OUTER JOIN ck_po_customer_header h
			ON d.`id_po` = h.`id`
			LEFT OUTER JOIN ck_customer c
			ON h.`id_customer` = c.id
			LEFT OUTER JOIN ck_sales s
			ON s.id = c.`id_sales`
			WHERE YEAR(h.approved_date)='$year'
			GROUP BY pro.`id` HAVING total_qty>0
			ORDER BY total_qty DESC
			LIMIT 10");
		
		echo json_encode($get->result());
	}
}