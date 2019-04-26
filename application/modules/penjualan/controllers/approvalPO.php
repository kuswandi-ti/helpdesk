<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class approvalPO extends CI_Controller
{
    function __construct() {
    parent::__construct();
    $this->load->model('m_approval_po'); 
    $this->load->model('m_po_customer'); 
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
            'title' => 'Approval PO',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Penjualan</li>
                             <li>Approval PO</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
            
            
        );
        $this->template->build('v_approval_po', $data);
    }
     function populatePO()
    {
        /*$q = "SELECT h.id, h.no_po, h.id_customer, cust.nama, cust.alamat,h.sales,h.bukti_gambar, 
                                    h.tanggal_po, h.created_by FROM ck_po_customer_header h LEFT OUTER JOIN ck_customer cust
                                    ON cust.id  = h.`id_customer`";*/
         $aColumns = array('tanggal_po','id','no_po', 'nama', 'sales', 'bukti_gambar',  'status', 'keterangan');
        
        // DB table to use
        $sTable = 'v_po_customer_header_pending';
    
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
        $rResult = $this->db->get_where($sTable, array('status'=>'pending'));
    
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
            $gambar = base_url().$aRow['bukti_gambar'];	
            $label;
            if($aRow['status']=='draft')
                $label='default';
            elseif ($aRow['status']=='pending')
                $label = 'warning';
            elseif ($aRow['status']=='reject')
                $label = 'danger';
            else 
                $label = 'success';
            $no++;
            $row[] = $no;
            $row[] = $aRow['no_po'];
            $row[] = $aRow['nama'];
            $row[] = $aRow['sales'];
            $row[] = $aRow['tanggal_po'];
            $row[] = "<a onclick=\"var mywin=window.open('".$gambar."', '','left=450,top=450,width=550,height=550,toolbar=0,resizable=0');mywin.focus();\">Gambar</a>";
            $row[] = "<span class=\"label label-".$label."\">".$aRow['status']."</span>";
            $row[] = "<button id=\"showhis\" type=\"button\" class=\"btn btn-info btn-sm\">Lihat histori</button><input type='hidden' id=\"idpo\" 	value=\"".$aRow['id']."\">";
            $row[] = " <a title='Lihat Detail' href='/sitauhid/penjualan/approvalpo/po_detail/?last_id=".$aRow['id']."'><span alt='Detail' class='icon-launch'></span></a>&nbsp;";
            	
            $output['aaData'][] = $row;
        }
        
        echo json_encode($output);
    }
	
	function po_detail()
    {
       $idheader = $this->input->get('last_id');
       
        $data = array(
            'title' => 'Approval PO',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Sales</li>
                             <li><a href="penjualan/approvalpo">Approval PO</a></li>
                             <li>PO Detail</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>',
            'po_header' => $this->m_po_customer->populatePO($idheader),
			'total' => $this->getTotal($idheader),
            'idHeader' => $idheader
        );
        $this->template->build('v_approval_po_detail', $data);
    }
	
	function getTotalRow($id)
	{
		$this->m_approval_po->getTotalRow($id);
	}
	function loadDetail($id_po)
   {
       $detail = $this->m_approval_po->getInputDetail($id_po);
           $no = 1;
           foreach ($detail->result() as $r)
           {
			  $x =($r->jumlah_pesanan)-($r->jumlah_back_order);
            echo '<tr>';
            echo '<td>'.$no.'</td>';
            echo '<td><input id="id_po" type="hidden" value="'.$id_po.'">'.'<input id="id_detail" type="hidden" value="'.$r->id.'">'.$r->nama.' ('.$r->kemasan.')</td>';
            echo '<td>'.$r->batch_number.' - '.$r->kadaluarsa.'</td>';
            echo '<td style="text-align:right;">'.number_format($r->harga_jual).'</td>';
            echo '<td style="text-align:right;"><b>'.$r->jumlah_pesanan.'</b></td>';
            echo '<td style="text-align:right;"><b>'.$r->jumlah_back_order.'</b></td>';
            echo '<td style="text-align:right; font-weight:bold;" ><input type="hidden" value="'.$r->qty_akhir.'" id="numstok" >'.$r->qty_akhir.'</td>'; // stok
            echo '<td style="text-align:right;"><input id="numqtyacc" type="number" min="1" value="'.($r->Qacc==$r->jumlah_pesanan?$x:$r->Qacc).'" max="'.$r->qty_akhir.'" style="width:35%; text-align:center;"><button id="setqtyacc">Set</button></td>';
            echo '<td style="text-align:right;">'.number_format($r->disc_rp).'&nbsp;&nbsp;&nbsp;('.$r->disc_persen.'%) </td>';
            echo '<td style="text-align:right;">'.number_format($r->sub_total).'</td>';
			if($r->acc=="1"):
				echo '<td style="text-align:right;"><input id="checker" type="checkbox" checked></td>';
				echo '<td style="text-align:right;"><input id="reason" class="input input-sm pull-left" placeholder="Catatan" type="text" style="width:65%;" ><button id="setter" style="width:35%;padding:0;margin:0;" class="btn btn-sm btn-warning">Set</button></td>';
			else:
				echo '<td style="text-align:right;"><input id="checker" type="checkbox" ></td>';
				echo '<td style="text-align:right;"><input id="reason" class="input input-sm pull-left" placeholder="Catatan" type="text" style="width:65%;" value="'.$r->keterangan.'" ><button id="setter"  style="width:35%;padding:0;margin:0;" class="btn btn-sm btn-warning alreadyset">Unset</button></td>';
			endif; 
			
            echo '</tr>';
            $no++;
           }
   }
   
   function getHistori($id_po)
   {
		$his = $this->m_approval_po->getHistori($id_po);
		foreach($his->result() as $r) echo $r->keterangan;
   }
   function getTotal($id_po)
   {
		$total = $this->m_approval_po->getTotal($id_po);
		foreach($total->result() as $r)
			return $r->total;
   }

   function approval()
   {
	   $res = $this->input->post('respon');
	   $msg = $this->input->post('message');
	   $id_po = $this->input->post('id_po');
	   $approvedby = $this->input->post('approved_by');
	   $tanggal = $this->input->post('tanggal');
	   $jam = $this->input->post('jam');
	   
	   $data = array(
			'res'=>$res,
			'msg'=>$msg,
			'id_po'=>$id_po,
			'approvedby'=>$approvedby,
			'tanggal'=>$tanggal,
			'jam'=>$jam
		); 
		if($res == 'approved')
			$updateStok = $this->m_approval_po->updateStok($id_po);
		
		$insert = $this->m_approval_po->insertKeterangan($data);
		if($insert) echo "done";
		else echo "false";
	   }
   
   function setQtyAcc()
   {
		$set = $this->m_approval_po->setQtyAcc();
   }
   
   function setAcc()
   {
	   $set = $this->m_approval_po->setAcc();
   }
}

