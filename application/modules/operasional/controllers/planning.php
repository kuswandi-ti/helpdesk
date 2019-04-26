<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class planning extends CI_Controller
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
            'title' => 'Global Planning',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Operational</li>
                             <li>Sales</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
        );
        $this->template->build('v_planning', $data);
	}
	
	function get_sales()
	{
		$this->m_planning->get_sales();
	}
	
	function get_data($id)
	{
		$this->m_planning->get_data($id);
	}
	
	function insert_data()
	{
		$this->m_planning->insert_data($_POST);
	}
	
	function update_data($id)
	{
		$this->m_planning->update_data($id,$_POST);
	}
	
	function delete_data($id)
	{
		$this->m_planning->delete_data($id);
	}
	function populate_data()
	{
		$aColumns = array('id','nama_sales','nama_area','nama_bulan','tahun','total_visit','total_customer','avg_visit');
		$sTable = "(select gp.*,  s.nama_sales, MONTHNAME(STR_TO_DATE(gp.bulan, '%m')) as nama_bulan, a.nama as nama_area
					from ck_global_planning gp
					left outer join ck_sales s 
					on s.id = gp.id_sales
					left outer join ck_area a
					on a.id = s.id_area
					) x";
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
            $row[] = $aRow['nama_area']."<input type='hidden' id='id' value='".$aRow['id']."'>"; 
            $row[] = $aRow['nama_sales']; 
            $row[] = $aRow['nama_bulan']; 
            $row[] = $aRow['tahun'];
            $row[] = $aRow['total_visit']; 
            $row[] = $aRow['total_customer']; 
            $row[] = $aRow['avg_visit'];
            $row[] = "	<button id=\"button-edit\" style=\"border:none;\" class=\" btn-info  \"> Edit </button> &nbsp; 
						<button id=\"button-delete\" style=\"border:none;\" class=\" btn-danger \"> Delete </button>  ";
            
            $output['aaData'][] = $row;
        }
        
        echo json_encode($output);
	}
}