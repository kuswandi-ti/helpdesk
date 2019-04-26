<?php defined('BASEPATH') OR exit('No direct script access allowed');


class back_order extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('pdf');
		$this->load->library('ciqrcode');
		$this->load->model('m_back_order');
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
            'title' => 'Back Order',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Sales</li>
                             <li>Back Order</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Faktur Penjualan',
            'page_subtitle' => '',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
            
        );
        $this->template->build('v_back_order', $data);
    }
	
	function populateSO()
    {
		
		//konten : SO, INV, SJ*/
		// SO: BISA LIHAT Detail
		// INV: UNTUK KEPERLUAN INVOICE
		// SJ:untuk keperluan surat jalan
        $aColumns = array('id','tanggal_po','no_po','nama', 'nama_sales', 'tipe_bayar', 'jangka_waktu', 'back_order_item');
        
        // DB table to use
        $sTable = '(SELECT h.id, h.`no_po`, cust.`nama`, sal.`nama_sales`,h.`tipe_bayar`, h.`jangka_waktu`, h.tanggal_po, COUNT(back_order) AS back_order_item
					FROM  ck_po_customer_detail det
					LEFT OUTER JOIN ck_po_customer_header h
					ON h.id = det.`id_po`
					LEFT OUTER JOIN ck_customer cust
					ON h.`id_customer` = cust.`id`
					LEFT OUTER JOIN ck_sales sal
					ON h.`sales` = sal.`id`
					WHERE back_order IN ("1")
				
					GROUP BY id_po HAVING COUNT(back_order)
					) x '; 
    
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
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
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
            $row[] = $aRow['no_po'];
            $row[] = $aRow['tanggal_po'];
            $row[] = $aRow['nama'];
            $row[] = $aRow['nama_sales'];
            $row[] = $aRow['tipe_bayar'];
            $row[] = $aRow['jangka_waktu'];
            $row[] = $aRow['back_order_item'];
            $row[] = '<button id="bo_detail" class="btn btn-success btn-sm">Create BO</button>
					  <input type="hidden" value="'.$aRow['id'].'" id="id_po">';
			$output['aaData'][] = $row;		
        }	
        
        echo json_encode($output);
    }
	
	
	
	function bo_detail()
    {
       $idheader = $this->input->get('last_id');
       
        $data = array(
            'title' => 'PO Customer',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Sales</li>
                             <li><a href="penjualan/back_order">Back Order</a></li>
                             <li>Back Order Detail</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>',
            'po_header' => $this->m_back_order->populatePO($idheader),
            'idHeader' => $idheader
        );
        $this->template->build('v_back_order_detail', $data);
    }
	
   function getNumDetail($id_po)
   {
		$num = $this->m_back_order->getNumDetail($id_po);
   }
   function loadDetail($id_po)
   {
       $detail = $this->m_back_order->getInputDetail($id_po);
           $no = 1;
           foreach ($detail->result() as $r)
           {
            echo '<tr>';
            echo '<td>'.$no.'</td>';
            echo '<td><input  type="hidden" value="'.$r->id_produk.'">'.$r->nama.' ('.$r->kemasan.')</td>';
            echo '<td>'.$r->batch_number.' - '.$r->kadaluarsa.'</td>';
            echo '<td style="text-align:right;">'.number_format($r->harga_jual).'</td>';
            echo '<td style="text-align:right;"><b>'.$r->jumlah_pesanan.'</b></td>';
            echo '<td style="text-align:right;"><b>'.($r->back_order=='1'?'<label class="label label-warning">'.$r->jumlah_back_order .'</label>':'<label class="label label-default"> No </label>').'</b></td>';
            echo '<td style="text-align:right;"><b>'.$r->qty_akhir.'</b></td>';
            echo '<td style="text-align:right;">'.number_format($r->disc_rp).'&nbsp;&nbsp;&nbsp;('.$r->disc_persen.'%) </td>';
            echo '<td style="text-align:right;">'.number_format($r->sub_total).'</td>';
			//echo '<td><a onclick="del('.$r->id.','.$id_po.')"><span alt="Detail" class="icon-delete">&nbsp; delete</span></a></td>';
			
            echo '</tr>';
            $no++;
           }
   }
   function getBOnominal()
   {
	    $id_po = $this->input->post('id_po');
		$get = $this->m_back_order->getBOnominal($id_po);
		//header('Content-Type: application/json');
		echo json_encode($get->result());
   }
	function delDetail()
   {
       $id = $this->input->post('id');
       $id_po = $this->input->post('id_po');
       $del = $this->m_back_order->delDetail($id,$id_po);
       if($del)
           echo "done";
       else echo "false";
   }
   function processBO()
   {
	   $process = $this->m_back_order->processBO();
   }
} 