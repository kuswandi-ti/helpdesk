<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po_customer extends CI_Controller
{
    var $db_po     = 'ck_po_customer_header';
    function __construct() {
    parent::__construct();
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
            'title' => 'PO Customer',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Penjualan</li>
                             <li>Surat Pesanan</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>'
            
            
        );
        $this->template->build('v_po_customer', $data);
    }
	function popQuotation($idcust)
	{
		$return = $this->m_po_customer->popQuotation($idcust);
		echo json_encode($return->result());
	}
    function populatecust()
	{
		$return = $this->m_po_customer->populatecust();
		header('Content-Type: application/json');
		echo json_encode($return->result());
	}
    function populatePO($status)
    {
        /*$q = "SELECT h.id, h.no_po, h.id_customer, cust.nama, cust.alamat,h.sales,h.bukti_gambar, 
                                    h.tanggal_po, h.created_by FROM ck_po_customer_header h LEFT OUTER JOIN ck_customer cust
                                    ON cust.id  = h.`id_customer`";*/
         $aColumns = array('id','no_po', 'nama', 'sales', 'bukti_gambar','tanggal_po',  'status', 'keterangan', 'approved_by', 'approved_date');
        $sTable;
        // DB table to use
		switch($status):
			case 'draft':
				$sTable = 'v_po_customer_header_draft';
				break;
			case 'pending':
				$sTable = 'v_po_customer_header_pending';
				break;
			case 'revisi':
				$sTable = 'v_po_customer_header_revisi';
				break;
			case 'approved':
				$sTable = 'v_po_customer_header_approved';
				break;
			case 'reject':
				$sTable = 'v_po_customer_header_reject';
				break;
			default:
				$sTable = 'v_po_customer_header';
		endswitch;
       
    
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
            $gambar = base_url().$aRow['bukti_gambar'];	
            $label;
			$deleterow ="<a id='hapus' title='Hapus' onclick='delHeader(".'"'.$aRow['id'].'",'.'"'.$aRow['no_po'].'"'.")'><span class='icon-trash2'></span></a>&nbsp;";
            if($aRow['status']=='draft')
                $label='default';
            elseif ($aRow['status']=='pending')
			{
				$deleterow ='';
                $label = 'warning';
			}
            elseif ($aRow['status']=='approved')
			{
				$deleterow ='';
                $label = 'success';
			}
            elseif ($aRow['status']=='revisi')
			{
				$deleterow ='';
                $label = 'primary';
			}
            elseif ($aRow['status']=='reject')
			{
				$deleterow ='';	
                $label = 'danger';
			}
            else 
                $label = 'success';
			
			
            $no++;
            $row[] = $no; //1
            $row[] = $aRow['no_po']; //2
            $row[] = $aRow['nama']; //3 
            $row[] = $aRow['sales']; //4
            $row[] = $aRow['tanggal_po']; //5			
            $row[] = "<a onclick=\"var mywin=window.open('".$gambar."', '','left=450,top=450,width=550,height=550,toolbar=0,resizable=0');mywin.focus();\"><span class=\"fa fa-file-picture-o\"></span> img</a>"; //6
            //$row[] = $aRow['status']; //7
            $row[] = "<span class=\"label label-".$label."\">".$aRow['status']."</span>"; //7
			if($aRow['status']=='draft') //8
			{
				$row [] = "";
			}
			else
				$row[] = "<button id=\"showhis\" type=\"button\" class=\"btn btn-info btn-sm\">History</button><input type='hidden' id=\"idpo\" 	value=\"".$aRow['id']."\">";
			
			if($status == 'approved')
			{
				$row[] = $aRow['approved_by'];
				$row[] = $aRow['approved_date'];
			}
			
			$row[] = " <a title='Lihat Detail' href='/sitauhid/penjualan/po_customer/po_detail/?last_id=".$aRow['id']."'><span alt='Detail' class='icon-launch'></span></a>&nbsp;".$deleterow;
            
            $output['aaData'][] = $row;
        }
        
        echo json_encode($output);
    }
    
    function createHeader()
    {
        
        $fileName = $_FILES['buktiPO']['name'];
        $extension=(explode(".", $fileName));
        $tmp = $_FILES['buktiPO']['tmp_name'];    
        
        $PATH_GAMBAR = $_SERVER['DOCUMENT_ROOT'].'sitauhid/assets/img/po_customer/'.$this->input->post('no_po').'.'.end($extension);
        move_uploaded_file($tmp, $PATH_GAMBAR);
		$diskon_persen = "0";
		$tipe_bayar = "credit";
		if($this->input->post('tipebayar')=='cod'):
			$tipe_bayar = "cash";
			$diskon_persen = "1";
		endif;
        $data = array(
            "no_po" => $this->input->post('no_po'),
            "sales" => $this->input->post('sales'),
            "idquotation" => $this->input->post('idquotation'),
            "customer" => $this->input->post('customer'),
            "tanggal" => $this->input->post('tanggal'),
            "tipe_bayar" => $tipe_bayar,
            "jangka_waktu" => $this->input->post('jangkawaktu'),
            "bukti_gambar" => 'assets/img/po_customer/'.$this->input->post('no_po').'.'.end($extension),
			"diskon_persen" => $diskon_persen
        );
        
		
        //Create Header ke Database
        $createHeader = $this->m_po_customer->createHeader($data);
        $last_id = $this->session->userdata('last_id');
        
        if ($createHeader) {
            
           // echo $PATH_GAMBAR;
           redirect('penjualan/po_customer/po_detail/?last_id=' . $last_id);
        } else {
            echo "<script>alert('Fail')</script>";
        }
    }
    
	
    function delHeader()
    {
        $id = $this->input->post('id');
        return $this->m_po_customer->delHeader($id);
    }
    
    function po_detail()
    {
       $idheader = $this->input->get('last_id');
       
        $data = array(
            'title' => 'PO Customer',
            'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Penjualan</li>
                             <li><a href="penjualan/po_customer">Surat Pesanan</a></li>
                             <li>Surat Pesanan Detail</li>',
            'page_icon' => 'icon-clipboard-text',
            'page_title' => 'Minimized Open On Hover',
            'page_subtitle' => 'This option makes sublevels hoverable.',
            'custom_scripts' => '<script>$("#menu_penjualan").addClass("active");</script>',
            'po_header' => $this->m_po_customer->populatePO($idheader),
            'idHeader' => $idheader
        );
        $this->template->build('v_po_customer_detail', $data);
    }
    
   function populateInputBarang()
   {
       $ret = $this->m_po_customer->populateInputBarang();
       header('Content-Type: application/json');
       echo json_encode($ret->result());
   }
   function populateSales()
   {
	   $ret = $this->m_po_customer->populateSales();
	//  header('Content-Type: application/json');
		echo json_encode($ret->result());
   }
   function insertDetail()
   {
       $this->session->set_userdata('id_po',$this->input->post('id_po'));
       $data = array(
           "id_po" => $this->input->post('id_po'),
           "id_produk" => $this->input->post('id_produk'),
           "id_kemasan" => $this->input->post('id_kemasan'),
           "batch_number" => $this->input->post('batch_number'),
           "expired_date" => $this->input->post('expired_date'),
           "harga_jual" => $this->input->post('harga_jual'),
           "back_order" => $this->input->post('back_order'),
           "jumlah_back_order" => $this->input->post('jumlah_back_order'),
           "jumlah_pesanan" => $this->input->post('jumlah_pesanan'),
           "jumlah_acc" => $this->input->post('jumlah_pesanan')-$this->input->post('jumlah_back_order'),
           "disc_rp" => $this->input->post('disc_rp'),
           "disc_persen" => $this->input->post('disc_persen'),
           "sub_total" => $this->input->post('sub_total')
       );
//       print_r($data);
       $insert = $this->m_po_customer->poInputDetail($data);
       if($insert)
       {
           $this->loadDetail($data['id_po']);
       }
       else{
           echo "Failed";
       }
   }
   
   function loadDetail($id_po)
   {
       $detail = $this->m_po_customer->getInputDetail($id_po);
           $no = 1;
           foreach ($detail->result() as $r)
           {
			 $stok = ($r->jumlah_pesanan)-($r->jumlah_back_order);
            echo '<tr>';
            echo '<td>'.$no.'</td>';
            echo '<td>
			<input type="hidden" id="cek_id_produk" value="'.$r->id_produk.'">
			<input type="hidden" id="cek_id" value="'.$r->id.'">
			<input type="hidden" id="cek_batch_number" value="'.$r->batch_number.'">
			<input type="hidden" id="cek_kadaluarsa" value="'.$r->kadaluarsa.'">
			<input type="hidden" id="detail_jumlah_backorder" value="'.$r->jumlah_back_order.'">
			<input type="hidden" id="detail_stok" value="'.$r->qty_akhir.'"><span  id="detail_product">'
			.$r->nama.' ('.$r->kemasan.')</span></td>';
            echo '<td id="detail_expbatch">'.$r->batch_number.' - '.$r->kadaluarsa.'</td>';
            echo '<td id="detail_sellingprice" style="text-align:right;">'.number_format($r->harga_jual).'</td>';
            echo '<td id="detail_qty" style="text-align:right;"><b>'.$r->jumlah_pesanan.'</b></td>';
            echo '<td style="text-align:right;"><b>'.($r->back_order=='1'?'<label class="label label-warning">'.$r->jumlah_back_order .'</label>':'<label class="label label-default"> No </label>').'</b></td>';
            echo '<td style="text-align:right;"><span id="detail_discrp">'.number_format($r->disc_rp).'</span>&nbsp;&nbsp;&nbsp;(<span id="detail_discpersen">'.$r->disc_persen.'%</span> )</td>';
            echo '<td style="text-align:right;"><span id="detail_subtotal">'.number_format($r->sub_total).'</span></td>';
			if(($r->status == 'draft') || ($r->status == 'revisi')){
				echo '<td><button id="edit"><span alt="Detail" class="icon-pencil"> Edit</span></button>&nbsp;&nbsp;|&nbsp;';
				echo '<button onclick="del('.$r->id.','.$id_po.')"><span alt="Detail" class="icon-delete"> Delete</button></a></td>';
			}
			else echo '<td></td>';
            echo '</tr>';
			$stok='';
            $no++;
           } 
   }
   
   function getNumDetail($id_po)
   {
		$num = $this->m_po_customer->getNumDetail($id_po);
   }
   function getStatus($id_po)
   {
		$stat = $this->m_po_customer->getStatus($id_po);
		foreach($stat->result() as $r)
		echo $r->status;
   }
   
   function edit_detail()
   {
	   $this->m_po_customer->edit_detail();
   }
   
   function delDetail()
   {
       $id = $this->input->post('id');
       $id_po = $this->input->post('id_po');
       $del = $this->m_po_customer->delDetail($id,$id_po);
       if($del)
           echo "done";
       else echo "false";
   }
   
   function getheadernominal()
   {
	    $id_po = $this->input->post('id_po');
		$get = $this->m_po_customer->getheadernominal($id_po);
		//header('Content-Type: application/json');
		echo json_encode($get->result());
   }
   function updatenominal()
   {
	   $data = array(
			'id'=>$this->input->post('id'),
			'subtotal'=>$this->input->post('subtotal'),
			'diskon_persen'=>$this->input->post('diskon_persen'),
			'diskon_rupiah'=>$this->input->post('diskon_rupiah'),
			'ppn'=>$this->input->post('ppn'),
			'total'=>$this->input->post('total')
	   );
	   $upd = $this->m_po_customer->updatenominal($data);
	   
   }
   
   function getAging($id_cust)
   {
	   $id_cust = ltrim($id_cust,'c'); //get id cust
	   $get = $this->db->query("
			SELECT h.id AS header,c.id AS c_id,h.no_so, c.`nama` AS customer,
			DATEDIFF(DATE(NOW()),DATE_ADD(tanggal_faktur, INTERVAL jangka_waktu DAY)) AS aging_piutang

			FROM ck_po_customer_header h
			LEFT OUTER JOIN ck_customer c
			ON c.id = h.`id_customer`
			WHERE h.`status` = 'approved'  AND c.id = '$id_cust'
			HAVING aging_piutang > 0
		");

		if($get->num_rows() > 0) // kalo ada tagihan;
		{
			$tu = "Tunggakan : ";
			foreach($get->result() as $x)
			{
				$tu.=$x->no_so.', ';
			}
			$tu = substr($tu,0,-2);
			echo $tu;
		}
		else echo 'clear';
   }
   
   function simpanPO()
   {
		$id_po = $this->input->post('id');
		$param = $this->input->post('param');
		$keterangan = $this->input->post('reason');
		if($param=='0'):
			$simpan = $this->m_po_customer->simpanPO($id_po,$keterangan);
			if($simpan)
			  echo "done";
			else echo "failed";
		endif;
   }
   

}