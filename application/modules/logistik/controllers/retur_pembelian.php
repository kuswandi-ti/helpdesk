<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_Pembelian extends CI_Controller {
	
	var $tbl_retur_hdr			= 'ck_tbl_logistik_returbeli_hdr';
	var $tbl_po_hdr				= 'ck_tbl_beli_purchaseorder_hdr';
	
	var $view_retur_hdr			= 'ck_view_logistik_returbeli_hdr';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_retur_pembelian');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Retur Pembelian',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Manajemen Data</li>
							 <li>Mutasi Stok</li>
                             <li><a href="logistik/retur_pembelian">Retur Pembelian</a></li>',
			'page_icon' => 'icon-repeat',
			'page_title' => 'Retur Pembelian',
			'page_subtitle' => 'Retur Barang Pembelian Ke Supplier / Principal',
			'get_supplier' => $this->m_retur_pembelian->get_supplier(),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_returpembelian').addClass('active');</script>"
		);
		$this->template->build('v_retur_pembelian', $data);
	}
	
	public function get_data() {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_header', 'no_transaksi', 'tgl_transaksi', 'bulan', 'tahun', 
						  'id_supplier', 'nama_supplier', 'id_gr', 'no_gr', 
						  'alasan_retur', 'keterangan');
        
        // DB table to use
		$sTable = $this->view_retur_hdr;
    
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
			$row[] = $aRow['no_transaksi'];
			$row[] = date_format(new DateTime($aRow['tgl_transaksi']), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
			$row[] = set_month_to_string_ind($aRow['bulan']);
			$row[] = $aRow['tahun'];
			$row[] = $aRow['nama_supplier'];
			$row[] = $aRow['no_gr'];
			$row[] = $aRow['alasan_retur'];
            $row[] = $aRow['keterangan'];
			$row[] = '<a title="Edit Detail" href="logistik/retur_pembelian/retur_pembelian_detail/?hid='.$aRow['id_header'].'&sid='.$aRow['id_supplier'].'&grid='.$aRow['id_gr'].'"><span class="icon-pencil5"></span></a>&nbsp;&nbsp;
					  <a title="Print" href="logistik/retur_pembelian/cetak_retur/'.$aRow['id_header'].'" target="_blank"><span class="icon-printer"></span></a>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function get_gr($id_supplier) {
		$this->m_retur_pembelian->get_gr($id_supplier);
	}
	
	function create_header() {		
        $data = array(
			'no_transaksi'          => $this->m_retur_pembelian->create_doc_no(date('n'), date('Y')),
			'tgl_transaksi'         => $this->config->item('FORMAT_NOW_DATE_TO_INSERT'),
			'bulan'					=> $this->input->post('cbobulan'),
			'tahun'					=> $this->input->post('cbotahun'),
            'id_supplier'           => $this->input->post('cbosupplier'),
			'id_gr'           		=> $this->input->post('cbonomorgr'),
			'alasan_retur'          => $this->input->post('txtalasanretur'),
			'keterangan'           	=> $this->input->post('txtketerangan'),
			'created_by'            => $this->session->userdata('user_name'),
			'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by'           => $this->session->userdata('user_name'),
			'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
		$create_header = $this->m_retur_pembelian->header_create($data);	
		$hid = $this->session->userdata('hid');
		$sid = $this->input->post('cbosupplier');
		$grid = $this->input->post('cbonomorgr');
		if ($create_header) {
			redirect('logistik/retur_pembelian/retur_pembelian_detail/?hid='.$hid.'&sid='.$sid.'&grid='.$grid);
		} else {
			echo "<script>alert('Fail')</script>";
		}		 
	}
	
	function create_detail() {
        $data = array(
            'id_header'             => $this->input->post('id_header'),
            'id_produk'             => $this->input->post('id_produk'),
            'batch_number'          => $this->input->post('batch_number'),
			'expired_date'			=> date_format(new DateTime($this->input->post('expired_date')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'qty_retur'      		=> $this->input->post('qty_retur'),
			'keterangan'          	=> $this->input->post('keterangan'),
            'created_by'            => $this->session->userdata('user_name'),
            'created_date'          => date($this->config->item('FORMAT_DATETIME_TO_INSERT')),
            'modified_by'           => $this->session->userdata('user_name'),
            'modified_date'         => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
        );
        $create_detail = $this->m_retur_pembelian->detail_create($data);
        if ($create_detail)
            echo "done";
        else
            echo "fail";
    }
	
	function get_info_produk_gr() {
		$data = array();
		$id_gr = $this->input->post('id_gr');
		$id_produk = $this->input->post('id_produk');
		$res = $this->m_retur_pembelian->get_info_produk_gr($id_gr, $id_produk);
		
		if ($res->num_rows() > 0) {
			foreach ($res->result_array() as $row) {
				$data['result'] = 'done';
				$data['qty_gr'] = number_format($row['qty_gr']);
				$data['batch_number'] = $row['batch_number'];
				$data['expired_date'] = $row['expired_date'];
			}
		}
		echo json_encode($data);
    }
	
	function retur_pembelian_detail() {
		$hid = $this->input->get('hid');
		$sid = $this->input->get('sid');
		$grid = $this->input->get('grid');
		
		$data = array(
			'title' => 'Retur Pembelian',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Manajemen Data</li>
							 <li>Mutasi Stok</li>
                             <li><a href="logistik/retur_pembelian">Retur Pembelian</a></li>
							 <li>Retur Pembelian Detail</li>',
			'page_icon' => 'icon-repeat',
			'page_title' => '',
			'page_subtitle' => '',
			'get_header' => $this->m_retur_pembelian->get_header($hid),
            'get_detail' => $this->m_retur_pembelian->get_detail($hid),
			'get_produk_gr' => $this->m_retur_pembelian->get_produk_gr($grid),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_returpembelian').addClass('active');</script>"
		);
		$this->template->build('v_retur_pembelian_detail', $data);		
	}
    
    function detail_list() {
		$hid = $this->input->get('hid');
        $data = $this->m_retur_pembelian->detail_list($hid);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
                echo '<tr>';
                echo    '<td id="id_detail" style="display:none;">'.$row->id_detail.'</td>';
                echo    '<td id="no" style="width: 4%; text-align: center;">'.$i.'</td>';
                echo    '<td id="id_produk" style="display:none;">'.$row->id_produk.'</td>';
                echo    '<td id="nama_produk" style="width: 29%;">'.$row->nama_produk.'</td>';
                echo    '<td id="id_kemasan" style="display:none;">'.$row->id_kemasan.'</td>';
				echo    '<td id="nama_kemasan" style="width: 6%; text-align: center;">'.$row->nama_kemasan.'</td>';
				echo    '<td id="batch_number" style="width: 12%; text-align: center;">'.$row->batch_number.'</td>';
				echo    '<td id="expired_date" style="width: 10%; text-align: center;">'.date_format(new DateTime($row->expired_date), $this->config->item('FORMAT_DATE_TO_DISPLAY')).'</td>';
                echo    '<td id="qty_retur" style="width: 8%; text-align: right;">'.number_format($row->qty_retur).'</td>';
				echo    '<td id="keterangan">'.$row->keterangan.'</td>';
                echo    '<td style="width: 9%; text-align: center;">';
				echo 		'<span id="edit_detail" title="Edit" class="icon-register text-info text-lg" style="cursor: pointer"></span>&nbsp;
							 <span id="hapus_detail" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
                echo    '</td>';
                echo '</tr>';
                
                $i++;
            }
        }		
    }
		
    function delete_item_detail() {        
        $del = $this->m_retur_pembelian->delete_item_detail();        
        if ($del) {
			echo "done";
        } else {
            echo "fail";
		}
    }
    
    function update_item_detail() {		
        $del = $this->m_retur_pembelian->update_item_detail();
        if ($del) {
            echo "done";
        } else {
            echo "fail";
		}
    }
		
	function update_header() {
        $del = $this->m_retur_pembelian->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function Cetak_Retur($id) {
		$terbilang = '';
		
		$no_retur = '';
		$tgl_retur = '';
		$bulan = '';
		$tahun = '';
		$nama_supplier = '';
		$alasan_retur = '';
		$keterangan = '';
		
		$data_header = $this->m_retur_pembelian->get_header($id);
        foreach($data_header->result() as $row) {
			$no_retur = $row->no_transaksi;
			$tgl_retur = $row->tgl_transaksi;
			$bulan = $row->bulan;
			$tahun = $row->tahun;
			$nama_supplier = $row->nama_supplier;
			$alasan_retur = $row->alasan_retur;
			$keterangan = $row->keterangan;
		}
		
		$fcpath = FCPATH.$no_retur.'png';
		$params['data'] = base_url().'logistik/retur_pembelian/onlinechecking/'.$id;
		$params['savename'] = $fcpath;
		$this->ciqrcode->generate($params);
		$imagedata = file_get_contents($fcpath);
		$qrcode = base64_encode($imagedata);
		unlink($fcpath);
		
        $pdf = new pdf('L','A5');
        $pdf->SetTitle("$no_retur");
        $pdf->SetHeaderMargin(47);
		$pdf->SetTopMargin(30);
		
		
		$image = base_url().'assets/img/logo-tui.png';
		$imageData = base64_encode(file_get_contents($image));
		$src = 'data:image/jpeg;base64,'.$imageData;
		
		$html_header = '
			<table cellpadding="0" cellspacing="0" width="95%" style="font-size: x-small;">
				<tr>
					<td width="45%">
						<img height="50" width="200" src="'.$src.'" alt="" />
					</td>
					<td width="35%" style="font-size: medium; text-align: center;">
						<b><u><br />NOTA RETUR PEMBELIAN</u></b>
						<br />
						No : '.$no_retur.'
					</td>
					<td width="20%" style="text-align: right;">
						<img height="45" width="45" src="data:image/png;base64,'.$qrcode.'" alt="">
					</td>
				</tr>
			</table>
		';
        $pdf->setHtmlHeader($html_header);
        
        $pdf->SetAutoPageBreak(true, 50);
        $pdf->SetDisplayMode('real', 'default');
		
		$pdf->setFooterMargin(45);
		$html_footer = '
			<table width="100%" border="0">
				<tr>
					<td width="60%">
						<p style="font-size: large;">
							Cileungsi, <?php echo date("d/m/Y"); ?>
							<br>
							Yang Menerima                
							<br>
							<br>
							<br>
							<br>(...............................)
						</p>
					</td>
					<td width="40%" align="center">
						<p style="font-size: large;">
							<br>
							Yang Menyerahkan                
							<br>
							<br>              
							<br>
							<br>(...............................)
							<br>PT. TATA USAHA INDONESIA
						</p>
					</td>
				</tr>
			</table>
			<br><br><br>
			<table style="width: 100%;">
				<tr>
					<td style="text-align: center;"><i>Page '.$pdf->getAliasNumPage().' of '.$pdf->getAliasNbPages().'</i></td>
				</tr>
			</table>
		';		
		$pdf->setFooters($html_footer);

        $pdf->AddPage();
		
		$pdf->SetFont('', '', '', '', 'false');
		$data_detail = $this->m_retur_pembelian->get_detail($id);
		$jml_row = $data_detail->num_rows();
		$no = 1;
		$konten = '<table border="0.7" style="width: 100%; font-size: 7px; border: 0.1px solid black;" cellpadding="4">';
		if($jml_row != 33) {
			$konten .= '<thead>';
		}
		$konten .= '<tr style="font-weight: bold">
						<td style="width: 5%; text-align: center">No.</td>
						<td style="width: 41%; text-align: left">Nama Barang</td>
						<td style="width: 8%; text-align: center">Kemasan</td>
						<td style="width: 12%; text-align: center">Batch Number</td>
						<td style="width: 10%; text-align: center">Expired Date</td>
						<td style="width: 8%; text-align: right">Qty Retur</td>
						<td style="width: 16%; text-align: left">Keterangan</td>
					</tr>';
		if($jml_row != 33) {				 
			$konten .=' </thead>
						<tbody>';
		}
		foreach($data_detail->result() as $r) {
			$konten .= '<tr ';
			$konten .= ($no % 30 == 0 ? ' style="page-break-after: always;"' : '');
			$konten .= '>
							 <td style="width: 5%; text-align: center">'.$no.'</td>
							 <td style="width: 41%; text-align: left">'.$r->nama_produk_ori.'</td>
							 <td style="width: 8%; text-align: center">'.$r->nama_kemasan.'</td>
							 <td style="width: 12%; text-align: center">'.$r->batch_number.'</td>
							 <td style="width: 10%; text-align: center">'.date_format(new DateTime($r->expired_date), $this->config->item('FORMAT_DATE_TO_DISPLAY')).'</td>
							 <td style="width: 8%; text-align: right">'.number_format($r->qty_retur).'</td>
							 <td style="width: 16%; text-align: left">'.$r->keterangan.'</td>
						</tr>';
		   $no++;
		}
		if($jml_row != 33) {
			$konten	.= '</tbody>';
		}
        $konten .='</table>';                
        $pdf->writeHTML($konten, true, false, true, false, '');
		
        $pdf->Output($no_retur.'.pdf', 'I');
	}
	
	function onlinechecking($id) {
		$data['header'] = $this->m_retur_pembelian->get_header($id);
		$data['detail'] = $this->m_retur_pembelian->get_detail($id);
		$data['source'] = 'RB';
		$this->load->view('v_pub_service', $data);
	}

} 