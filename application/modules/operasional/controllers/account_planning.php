<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class account_planning extends CI_Controller
{
    function __construct() {
		parent::__construct();
		$this->load->model('m_account_planning'); 
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
            'title' => 'Account Planning',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Operational</li>
                             <li>Sales</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
        );
        $this->template->build('v_account_planning', $data);
	}
	
	
	function get_customer ($idsales)
	{
		$this->m_account_planning->get_customer($idsales);
	}
	
	function set_account_planning()
	{
		$this->m_account_planning->set_account_planning();
	}
	
	function populate_account_planning($id_karyawan)
	{
		
	}
	function get_kebutuhan($idcust)
	{
		$this->m_account_planning->get_kebutuhan($idcust);
	}
	
	function set_needs()
	{
		$this->m_account_planning->set_needs();
	}
	function insert_detail($id)
	{
		$detail_ap = $this->m_account_planning->get_ap_detail($id);
		$data = array(
            'title' => 'Account Planning',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Operational</li>
                             <li>Sales</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => ' ',
			'id'=>$id,
			'detail'=>$detail_ap
        );
        $this->template->build('v_account_planning_detail', $data);
	}
	function edit_detail($id)
	{
		$detail_ap = $this->m_account_planning->get_ap_detail($id);
		$konten = $this->m_account_planning->get_detail($id);
		$data = array(
            'title' => 'Account Planning',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Operational</li>
                             <li>Sales</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => ' ',
			'id'=>$id,
			'detail'=>$detail_ap,
			'konten'=>$konten
        );
		if($konten->num_rows() > 0)
			$this->template->build('v_account_planning_edit', $data);
		else $this->template->build('v_account_planning_detail', $data);
	}
	function realization($id)
	{
		$detail_ap = $this->m_account_planning->get_ap_detail($id);
		$konten = $this->m_account_planning->get_detail($id);
		
		$data = array(
            'title' => 'Account Planning',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Operational</li>
                             <li>Sales</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => ' ',
			'id'=>$id,
			'detail'=>$detail_ap,
			'konten'=>$konten,
			'jumlah_konten'=>$konten->num_rows()
        );
		$this->template->build('v_account_planning_realization', $data);
	}
	function count_realisasi($idvisit)
	{
		$this->m_account_planning->count_realisasi($idvisit);
	}
	function set_realisasi($idvisit)
	{
		$this->m_account_planning->set_realisasi($idvisit);
	}
	function get_realisasi($idvisit)
	{
		$this->m_account_planning->get_realisasi($idvisit);
	}
	function set_detail()
	{
		$this->m_account_planning->set_detail();
	}
	
	function update_detail($id)
	{
		$this->m_account_planning->update_detail($id);
	}
	
	function show_detail($idap)
	{
		$this->m_account_planning->show_detail($idap);
	}
	
	function delete_ap($id)
	{
		$this->m_account_planning->delete_ap($id);
	}
	
	function get_evaluasi($id)
	{
		$real = $this->db->query("select * from ck_lembar_kerja where id='$id'");
		echo json_encode($real->result());
	}
	function update_reason($id)
	{
		$this->m_account_planning->update_reason($id);
		
	}
	
	function get_list($idsales,$bulan ='' ,$tahun='')
	{
		if($bulan.$tahun == '')
		{
			$bulan = date('n');
			$tahun = date('Y');
		}
		$aColumns = array('idc','id_customer','id','nama_bulan', 'nama','bulan','tahun','kebutuhan_customer','target','realisasi','deskripsi','total_visit');
		
		$sTable = "(SELECT c.nama, c.id as idc, lk.*,  MONTHNAME(STR_TO_DATE(lk.bulan, '%m')) as nama_bulan
					FROM ck_customer c
					LEFT JOIN ck_lembar_kerja lk 
					ON c.id = lk.id_customer  AND lk.`bulan` IN ('$bulan') AND lk.tahun IN ('$tahun')
					WHERE id_sales='$idsales') x"; 
		
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
		if(strlen($bulan)<2)
			$bulan = '0'.$bulan;
		
        foreach ($rResult->result_array() as $aRow) {
			
            $row = array(); 
           
            $no++;
            $row[] = $no; 
            $row[] = 	"<span id='nama'>".$aRow['nama']."</span>
						<input type='hidden' id='id_ap' value='".$aRow['id']."'>
						<input type='hidden' id='bulan' value='".$aRow['bulan']."'>
						<input type='hidden' id='nama_bulan' value='".$aRow['nama_bulan']."'>
						<input type='hidden' id='tahun' value='".$aRow['tahun']."'>
						<input type='hidden' id='id_cust' value='a".$aRow['idc']."'>
						<span style='display:none;' id='namabulan'>".$aRow['nama_bulan']."</span>
						<span style='display:none;' id='tahun'>".$aRow['tahun']."</span>
						"
						; 
            // $row[] = "<span style='display:none;' id='namabulan'>".$aRow['nama_bulan']."</span>"; 
            // $row[] = "<span style='display:none;' id='tahun'>".$aRow['idc']."</span>"; 
            $row[] = "<span id='total_visit'>".$aRow['total_visit']."</span>"; 
			//Last 3 month
			$get3 = $this->db->query("
						SELECT SUM(h.total) as total
						FROM ck_po_customer_header h
						LEFT OUTER JOIN ck_lembar_kerja lk
						ON lk.`id_customer` = h.id_customer

						WHERE  h.id_customer='".$aRow['idc']."' and
						DATE_FORMAT(h.`approved_date`, '%Y-%m') = DATE_FORMAT((CONCAT($tahun,'-',$bulan,'-01')) - INTERVAL 3 MONTH, '%Y-%m')");
			foreach($get3->result() as $y)
				$total3 = number_format($y->total);
			$row[] = $total3;
			
			//Last 2 month
			$get2 = $this->db->query("
						SELECT SUM(h.total) as total
						FROM ck_po_customer_header h
						LEFT OUTER JOIN ck_lembar_kerja lk
						ON lk.`id_customer` = h.id_customer

						WHERE  h.id_customer='".$aRow['idc']."' and
						DATE_FORMAT(h.`approved_date`, '%Y-%m') = DATE_FORMAT((CONCAT($tahun,'-',$bulan,'-01')) - INTERVAL 2 MONTH, '%Y-%m')");
			foreach($get2->result() as $y)
				$total2 = number_format($y->total);
			$row[] = $total2;
			
			//Last month
			$get1 = $this->db->query("
						SELECT SUM(h.total) as total
						FROM ck_po_customer_header h
						LEFT OUTER JOIN ck_lembar_kerja lk
						ON lk.`id_customer` = h.id_customer

						WHERE  h.id_customer='".$aRow['idc']."' and
						DATE_FORMAT(h.`approved_date`, '%Y-%m') = DATE_FORMAT((CONCAT($tahun,'-',$bulan,'-01')) - INTERVAL 1 MONTH, '%Y-%m')");
			foreach($get1->result() as $y)
				$totalnow = number_format($y->total);
			$row[] = $totalnow;
			
			//if kebutuhan null, get from query;
			if($aRow['kebutuhan_customer']!='')
				$row[] = number_format($aRow['kebutuhan_customer']);
			else 
			{
				$gets = $this->db->query("SELECT kebutuhan FROM ck_customer WHERE id ='".$aRow['idc']."'");
				foreach($gets->result() as $y)
					$row[] = number_format($y->kebutuhan);
			}
			
            //if target null, make it not set.
			$targets;
			if($aRow['target']<1)
				$row[] = '<span id="vtarget">Not Set</span>';  
			else 
			{
				$row[] = '<span id="vtarget">'.number_format($aRow['target']).'</span>'; 
				$targets=$aRow['target'];
			}
			
			
			$get =$this->db->query("
						SELECT SUM(h.total) as total
						FROM ck_po_customer_header h
						LEFT OUTER JOIN ck_lembar_kerja lk
						ON lk.`id_customer` = h.id_customer

						WHERE  h.id_customer='".$aRow['idc']."' and
						DATE_FORMAT(h.`approved_date`, '%Y-%m') = DATE_FORMAT((CONCAT($tahun,'-',$bulan,'-01')) - INTERVAL 0 MONTH, '%Y-%m')");
			$actuals;
			foreach($get->result() as $y)
			{
				$actuals = $y->total;
				$total4 = '<span id="vrealiz">'.number_format($y->total).'</span>'; 
			}
				 
			$row[] = $total4; 
			
			if($aRow['target']<1 && $total4<1)
				$row[] ='-'; 
			else $row[]= ($actuals / $targets *100). '%';
			
           // $row[] = "<span id='realisasi'>".$aRow['realisasi']."</span>"; 
            $row[] = "<textarea style='width:228px;height:87px;' id='deskripsi' readonly>".$aRow['deskripsi']."</textarea>";
			
            $row[] = "	<button ".($tahun.$bulan<date('Ym')?"disabled class=\"btn-default\"":'class="btn-info"')."  id=\"btn-add-data\" style=\"border:none;\"   title='Add Data' ><span class=\"icon-user-plus\"></span> </button>
					
						<button ".($aRow['target']<1?"disabled class=\"btn-default\"":'class="btn-success"')."id=\"button-detail\" style=\"border:none;\" title='View Detail'> <span class=\"icon-magnifier\"></span> </button>  
						<button ".($aRow['target']<1?"disabled class=\"btn-default\"":'class="btn-warning"')." id=\"button-edit\" style=\"border:none;\" title='Edit'> <span class=\"icon-pencil4\"></span> </button>
						<button ".($aRow['target']<1?"disabled class=\"btn-default\"":'class="btn-primary"')." id=\"button-realization\" style=\"border:none;\"  title='Realization'> <span class=\"icon-list3\"></span> </button>
						<button ".($aRow['target']<1?"disabled class=\"btn-default\"":'class="btn-danger"')." onclick=\"del(".$aRow['id'].")\" id=\"button-delete\" style=\"border:none;\" title='Delete'> <span class=\"icon-delete\"></span> </button>  "
						.($tahun.$bulan<=date('Ym')?"<button id=\"button-evaluate\" title='Evaluation'  class='btn-info' style='border:none;'><span class='icon-clipboard-alert'></span></button>":"");
						
            $output['aaData'][] = $row;
        }
        
        echo json_encode($output);
	}
	function get_header_cal()
	{
		$bulan = date('n');
		$tahun = date('Y');
		echo "<table id='cal' class='table-head-custom table-bordered table-hover no-footer font11'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>".substr(date("F", mktime(0, 0, 0, $bulan, 10)),0,3)."<br>".$tahun."</th>";
		
		
		for($j=6;$j<=19;$j++)
			{
				for($k=0;$k<60;$k+=30){
					echo "<th>";
					echo str_pad($j,2,"0",STR_PAD_LEFT).'.'.str_pad($k,2,"0",STR_PAD_LEFT);
					echo "</th>";
				}
			}
		echo "</tr>";
		echo "</thead>";
		echo "<tbody id='calbody'>";
	
		echo "</tbody>";
		echo "</table>";
	}
	function get_calendar($default='yes', $filter_sales='')
	{
		if($default=='yes')
		{
			$bulan = date('n');
			$tahun = date('Y');
		}
		else 
		{
			$bulan = $_POST['bulan'];
			$tahun = $_POST['tahun'];
		}
		
		$total_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun); // 31
		//echo $total_hari;
		
		for($i=1;$i<=$total_hari;$i++)
		{
			echo "<tr>";
			echo "<td><span id='tgl'>";
			echo $i;
			echo "</tgl></td>";
			
			for($j=6;$j<=19;$j++)
			{
				for($k=0;$k<60;$k+=30){
					
					//Query Here
					if($filter_sales=='')
						$get = $this->db->query(
						"
						SELECT GROUP_CONCAT(s.nama_sales) as nama_sales,GROUP_CONCAT(v.`id_lembar_kerja`) as id_lembar_kerja,
							TIME(STR_TO_DATE(CONCAT(jam_mulai,':',menit_mulai,':00'),'%T')) AS jam_mulai_, 
							TIME(STR_TO_DATE(CONCAT(jam_selesai,':',menit_selesai,':00'),'%T')) AS jam_selesai_
							
						FROM ck_visit  v
						LEFT OUTER JOIN ck_lembar_kerja lk
							ON v.id_lembar_kerja = lk.id
						LEFT OUTER JOIN ck_customer c
							ON lk.id_customer = c.id
						LEFT OUTER JOIN ck_sales s
							ON s.id = c.id_sales 
						WHERE v.`tanggal`='$tahun-$bulan-$i'
							AND TIME('$j:$k:00') BETWEEN TIME(STR_TO_DATE(CONCAT(jam_mulai,':',menit_mulai,':00'),'%T')) 
							AND TIME(STR_TO_DATE(CONCAT(jam_selesai,':',menit_selesai,':00'),'%T')) 
						GROUP BY v.`tanggal`
						");
					else {
						$id_sales = $this->input->post('id_sales');
						$get = $this->db->query(
						"
						SELECT GROUP_CONCAT(s.nama_sales) as nama_sales,GROUP_CONCAT(v.`id_lembar_kerja`) as id_lembar_kerja,
							TIME(STR_TO_DATE(CONCAT(jam_mulai,':',menit_mulai,':00'),'%T')) AS jam_mulai_, 
							TIME(STR_TO_DATE(CONCAT(jam_selesai,':',menit_selesai,':00'),'%T')) AS jam_selesai_
							
						FROM ck_visit  v
						LEFT OUTER JOIN ck_lembar_kerja lk
							ON v.id_lembar_kerja = lk.id
						LEFT OUTER JOIN ck_customer c
							ON lk.id_customer = c.id
						LEFT OUTER JOIN ck_sales s
							ON s.id = c.id_sales 
						WHERE v.`tanggal`='$tahun-$bulan-$i'
							AND TIME('$j:$k:00') BETWEEN TIME(STR_TO_DATE(CONCAT(jam_mulai,':',menit_mulai,':00'),'%T')) 
							AND TIME(STR_TO_DATE(CONCAT(jam_selesai,':',menit_selesai,':00'),'%T')) 
							AND s.id = '$id_sales'
						GROUP BY v.`tanggal`
						");
					}
					if($get->num_rows()>0)
					{ 
						echo "<td class='tooltips' style='width:3.44%;background:red;color:white;'>";
						foreach($get->result() as $x)
						{
							$nama = explode(",",$x->nama_sales);
							$id_lembar_kerja = explode(",",$x->id_lembar_kerja);
							$res = array_combine($nama,$id_lembar_kerja);
							$no = 1;
							echo '<span class="ttext">';
							foreach($res as $namax => $id) // $y = id_lembar_kerja
							{
								if(sizeOf($res)>1)
								{
									echo $no;
								}
								
								echo " ".$namax."-> id LK ".$id;
								echo "<br>";
								$no++;
							}
							echo '</span>';
							foreach($res as $namax => $id) // $y = id_lembar_kerja
							{
								$id_sales = $this->input->post('id_sales');
								//$i = tanggal, 
								if($id_sales!=''){
									$get_visit_by_lk = $this->db->query("
										select v.*, c.nama , lk.deskripsi as strategi, materi, 
										concat(jam_mulai,':',menit_mulai,'-',jam_selesai,':',menit_selesai) as jam
										from ck_visit  v
										left outer join ck_lembar_kerja lk 
										on v.id_lembar_kerja = lk.id
										left outer join ck_customer c
										on c.id = lk.id_customer
										LEFT OUTER JOIN ck_sales s
											ON s.id = c.id_sales
										WHERE v.`tanggal`='$tahun-$bulan-$i'
										AND TIME('$j:$k:00') BETWEEN TIME(STR_TO_DATE(CONCAT(jam_mulai,':',menit_mulai,':00'),'%T')) 
										AND TIME(STR_TO_DATE(CONCAT(jam_selesai,':',menit_selesai,':00'),'%T'))
										AND s.id = '$id_sales'
									")->result();
									foreach($get_visit_by_lk as $row)
									{
										echo "
										<input type='hidden' id='id_visit' value='$row->id'>
										<input type='hidden' id='id_lk' value='$id'>
										<input type='hidden' id='cust_nama' value='$row->nama'>
										<input type='hidden' id='str' value='$row->strategi'>
										<input type='hidden' id='mat' value='$row->materi'>
										<input type='hidden' id='jam' value='$row->jam'>
										<button id='popuper' style='background:red;color:white;'> ".strtoupper(substr($namax,0,3)).'</button>';//"-> id LK ".$id;
										echo "<br>";
										
									}
								}
								else {
									echo "<span style='background:red;color:white;'> ".strtoupper(substr($namax,0,3)).'</span>';//"-> id LK ".$id;
									echo "<br>";
								} 
							}
							// foreach($nama as $z)
							// {
								
								// echo strtoupper(substr($z,0,3))."<br>";
								
							// }
							
						}
					}
					else
						echo '<td>';
					
					echo "</td>";
				}
			}
			
			
			echo "<tr>";
		}
	}
	
	function set_komen(){
		extract($_POST);
		$set = $this->db->query("UPDATE ck_visit SET komentar = '".$komen."' where id='$id' ");
		if($set)
			echo "done";
		else echo $this->db->_error_message();
	}
}