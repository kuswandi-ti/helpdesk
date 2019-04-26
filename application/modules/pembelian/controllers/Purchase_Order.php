<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_Order extends CI_Controller {
	
	var $tbl_po_hdr				= 'ck_tbl_beli_purchaseorder_hdr';
	var $tbl_po_dtl				= 'ck_tbl_beli_purchaseorder_dtl';
	var $tbl_pr_hdr				= 'ck_tbl_beli_purchaserequest_hdr';
	var $tbl_supplier			= 'ck_supplier';
	var $tbl_tipe_pembayaran	= 'ck_tipe_pembayaran';
	
	var $view_po_hdr 			= 'ck_view_beli_purchaseorder_hdr';

	public function __construct() {
		parent::__construct();
		$this->load->model('M_Purchase_Order');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Purchase Order (PO)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/purchase_order">Purchase Order (PO)</a></li>',
			'page_icon' => 'icon-clipboard-text',
			'page_title' => 'Purchase Order (PO)',
			'page_subtitle' => 'Pemesanan pembelian barang ke supplier',
			'get_supplier' => $this->M_Purchase_Order->get_supplier(),
			'get_supplier_produk' => $this->M_Purchase_Order->get_supplier_produk(),
			'get_tipe_pembayaran' => $this->M_Purchase_Order->get_tipe_pembayaran(),
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_purchase_order.js'></script>"
		);
		$this->template->build('v_purchase_order', $data);
	}
	
	public function get_data($status_po) {		
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_po', 'no_po', 'tgl_po', 'bulan', 'tahun', 
		                  'id_supplier', 'nama_supplier', 'id_tipe_pembayaran', 
						  'nama_tipe_pembayaran', 'keterangan', 
						  'no_pr', 'status_po', 'nama_status_po', 
						  'status_histori', 'grand_total');
        
        // DB table to use
		$sTable = "(SELECT
						a.id AS id_po,
						a.id_pr,
						b.no_transaksi AS no_pr,
						b.tgl_transaksi AS tgl_pr,
						a.no_transaksi AS no_po,
						a.tgl_transaksi AS tgl_po,
						a.bulan,
						a.tahun,
						a.id_supplier,
						c.nama AS nama_supplier,
						c.alamat AS alamat_supplier,
						a.alamat_pengiriman,
						a.id_tipe_pembayaran,
						d.tipe_pembayaran AS nama_tipe_pembayaran,
						a.tgl_pengiriman,
						a.top,
						a.keterangan,
						a.status_po,
						CASE a.status_po
							WHEN 1 THEN 'DRAFT'
							WHEN 2 THEN 'PENDING'
							WHEN 3 THEN 'APPROVE'
							WHEN 4 THEN 'REVISI'
							WHEN 5 THEN 'REJECT'
							WHEN 6 THEN 'KIRIM'
						END AS nama_status_po,
						a.status_histori,
						a.total_barang,
						a.disc_persen,
						a.disc_rupiah,
						a.dpp,
						a.ppn_persen,
						a.ppn_rupiah,
						a.materai,
						a.grand_total,
						a.created_by,
						a.created_date,
						a.modified_by,
						a.modified_date
				   FROM
						".$this->tbl_po_hdr." a
						LEFT OUTER JOIN ".$this->tbl_pr_hdr." b ON a.id_pr = b.id
						LEFT OUTER JOIN ".$this->tbl_supplier." c ON a.id_supplier = c.id
						LEFT OUTER JOIN ".$this->tbl_tipe_pembayaran." d ON a.id_tipe_pembayaran = d.id
				   WHERE
						a.status_po = '".$status_po."'
				   ORDER BY
						a.no_transaksi) list_po";
		if ($status_po == '1') {
			$label = 'label-default';
		} elseif ($status_po == '2') {
			$label = 'label-success';
		} elseif ($status_po == '3') {
			$label = 'label-info';
		} elseif ($status_po == '4') {
			$label = 'label-warning';
		} elseif ($status_po == '5') {
			$label = 'label-danger';
		}
    
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
			$row[] = date_format(new DateTime($aRow['tgl_po']), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
			$row[] = set_month_to_string_ind($aRow['bulan']);
			$row[] = $aRow['tahun'];
			$row[] = $aRow['nama_supplier'];
            $row[] = $aRow['keterangan'];			
            $row[] = $aRow['no_pr'];
			$row[] = "<span class='label $label'>".$aRow['nama_status_po']."</span>";
			$row[] = '<button value="'.$aRow['status_histori'].'" id="btn_'.$aRow['id_po'].'" class="histori btn btn-default btn-sm btn-clean" title="Tampilkan histori"><span class="fa fa-play"></span></button>';
			$row[] = number_format($aRow['grand_total']);
            if ($aRow['status_po'] == 1 || $aRow['status_po'] == 4) {
                $row[] = '<a title="Edit Detail" href="pembelian/purchase_order/purchase_order_detail/?hid='.$aRow['id_po'].'&sid='.$aRow['id_supplier'].'"><span class="icon-pencil5"></span></a>&nbsp;&nbsp;
                          <a title="Siap Approve" href="pembelian/purchase_order/send_to_approve/'.$aRow['id_po'].'" onclick="return confirm(\'Yakin akan melanjutkan ke proses approve ?\')"><span class="icon-paper-plane"></span></a>&nbsp;&nbsp;
					      <a title="Print" href="pembelian/purchase_order/cetak_po/'.$aRow['id_po'].'" target="_blank"><span class="icon-printer"></span></a>';
            } elseif ($aRow['status_po'] == 2 || $aRow['status_po'] == 3 || $aRow['status_po'] == 5) {
                $row[] = '<a title="Lihat Detail" href="pembelian/purchase_order/purchase_order_detail/?hid='.$aRow['id_po'].'&sid='.$aRow['id_supplier'].'"><span class="icon-text-align-justify"></span></a>&nbsp;&nbsp;
					      <a title="Print" href="pembelian/purchase_order/cetak_po/'.$aRow['id_po'].'" target="_blank"><span class="icon-printer"></span></a>';
            }
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function create_header() {		
        $data = array(
            'id_pr'                 => $this->input->post('txtidpr'),
			'no_transaksi'          => $this->M_Purchase_Order->create_doc_no(date('n'), date('Y')),
			'tgl_transaksi'         => date('Y-m-d'),
			'bulan'					=> $this->input->post('cbobulan'),
			'tahun'					=> $this->input->post('cbotahun'),
            'id_supplier'           => $this->input->post('cbosupplier'),
            'alamat_pengiriman'     => $this->input->post('txtalamatpengiriman'),
            'id_tipe_pembayaran'    => $this->input->post('cbotipepembayaran'),
			'top'					=> $this->input->post('txttop'),
            'tgl_pengiriman'       	=> date_format(new DateTime($this->input->post('txttglpengiriman')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'keterangan'           	=> $this->input->post('txtketerangan'),
			'status_histori'		=> '',
			'created_by'            => $this->session->userdata('user_name'),
			'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by'           => $this->session->userdata('user_name'),
			'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
		$create_header = $this->M_Purchase_Order->header_create($data);	
		$hid = $this->session->userdata('hid');
		$sid = $this->input->post('cbosupplier');
		$id_pr = $this->input->post('txtidpr');
		if ($create_header) {
			redirect('pembelian/purchase_order/purchase_order_detail/?hid='.$hid.'&sid='.$sid);
		} else {
			echo "<script>alert('Fail')</script>";
		}		 
	}
	
	function create_header_list() {		
        $data = array(
            'id_pr'                 => $this->input->post('txtidpr'),
			'no_transaksi'          => $this->M_Purchase_Order->create_doc_no(date('n'), date('Y')),
			'tgl_transaksi'         => date('Y-m-d'),
			'bulan'					=> $this->input->post('cbobulan'),
			'tahun'					=> $this->input->post('cbotahun'),
            'id_supplier'           => $this->input->post('cbosupplier'),
            'alamat_pengiriman'     => $this->input->post('txtalamatpengiriman'),
            'id_tipe_pembayaran'    => $this->input->post('cbotipepembayaranlist'),
			'top'					=> $this->input->post('txttoplist'),
            'tgl_pengiriman'       	=> date_format(new DateTime($this->input->post('txttglpengirimanlist')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'keterangan'           	=> $this->input->post('txtketerangan'),
			'status_histori'		=> '',
			'created_by'            => $this->session->userdata('user_name'),
			'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by'           => $this->session->userdata('user_name'),
			'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
		$create_header = $this->M_Purchase_Order->header_create($data);	
		$hid = $this->session->userdata('hid');
		$sid = $this->input->post('cbosupplier');
		$id_pr = $this->input->post('txtidpr');
		if ($create_header) {
			redirect('pembelian/purchase_order/purchase_order_list/?hid='.$hid.'&sid='.$sid);
		} else {
			echo "<script>alert('Fail')</script>";
		}		 
	}
	
	function get_pr_aktif() {
        $data = $this->M_Purchase_Order->get_pr_aktif();
        header('Content-Type: application/json');
        echo json_encode($data);
    }
	
	function supplier_per_pr() {
		$id_pr = $this->input->post('id_pr');
		$data = $this->M_Purchase_Order->supplier_per_pr($id_pr);
        if ($data->num_rows() > 0) {
			foreach($data->result() as $r) {
				echo "<option value='$r->id_supplier'>".$r->nama_supplier."</option>";
			}
        }
	}
	
	function purchase_order_detail() {
		$hid = $this->input->get('hid');
		$sid = $this->input->get('sid');
				
		$data = array(
			'title' => 'Purchase Order (PO)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/purchase_order">Purchase Order (PO)</a></li>
							 <li>Purchase Order (PO) Detail</li>',
			'page_icon' => 'icon-clipboard-text',
			'page_title' => '',
			'page_subtitle' => '',			
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_purchase_order.js'></script>",
			'get_header' => $this->M_Purchase_Order->get_header($hid),
            'get_detail' => $this->M_Purchase_Order->get_detail($hid),
            'get_produk_pr' => $this->M_Purchase_Order->get_produk_pr($hid, $sid),
            'get_produk_supplier' => $this->M_Purchase_Order->get_produk_supplier($sid),
			'get_tipe_pembayaran' => $this->M_Purchase_Order->get_tipe_pembayaran()
		);
		$this->template->build('v_purchase_order_detail', $data);		
	}
	
	function purchase_order_list() {
		$hid = $this->input->get('hid');
		$sid = $this->input->get('sid');
				
		$data = array(
			'title' => 'Purchase Order (PO)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/purchase_order">Purchase Order (PO)</a></li>
							 <li>Purchase Order (PO) Detail</li>',
			'page_icon' => 'icon-clipboard-text',
			'page_title' => '',
			'page_subtitle' => '',			
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_purchase_order.js'></script>",
			'get_header' => $this->M_Purchase_Order->get_header($hid),
            'get_detail' => $this->M_Purchase_Order->get_detail($hid),
            'get_produk_pr' => $this->M_Purchase_Order->get_produk_pr($hid, $sid),
            'get_produk_supplier' => $this->M_Purchase_Order->get_produk_supplier($sid),
			'get_tipe_pembayaran' => $this->M_Purchase_Order->get_tipe_pembayaran()
		);
		$this->template->build('v_purchase_order_list', $data);		
	}
    
    function detail_list() {
		$hid = $this->input->get('hid');
        $data = $this->M_Purchase_Order->detail_list($hid);
		$status_po = $this->M_Purchase_Order->get_status_po($hid);
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
                echo    '<td id="qty_po" style="width: 8%; text-align: right;">'.number_format($row->qty_po).'</td>';
				echo    '<td id="harga_satuan" style="width: 10%; text-align: right;">'.number_format($row->harga_satuan).'</td>';
				echo    '<td id="total" style="width: 10%; text-align: right;">'.number_format($row->total).'</td>';
				echo    '<td id="disc_persen" style="width: 6%; text-align: right;">'.number_format($row->disc_persen).'</td>';
				echo    '<td id="disc_rupiah" style="width: 8%; text-align: right;">'.number_format($row->disc_rupiah).'</td>';
				echo    '<td id="netto" style="width: 10%; text-align: right;">'.number_format($row->netto).'</td>';
                echo    '<td style="width: 9%; text-align: center;">';
				if ($status_po == '2' or $status_po == '3' or $status_po == '5' or $status_po == '6') {
					echo '-';
				} else {
					echo '<span id="edit_detail" title="Edit" class="icon-register text-info text-lg" style="cursor: pointer"></span>&nbsp;
                          <span id="hapus_detail" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
				}       
                echo    '</td>';
                echo '</tr>';
                
                $i++;
            }
        }		
    }  
    
	function create_detail_list() {
		$hid = $this->input->post('id_header');
		$id_produk = $this->input->post('id_produk');
		$qty_po = $this->input->post('qty_po');
		
		$data = array(
            'id_header'             => $hid,
			'id_pr'					=> $this->input->post('id_pr'),
            'id_produk'             => $id_produk,
            'qty_po'             	=> $qty_po,
			'harga_satuan'          => $this->input->post('harga_satuan'),
			'total'             	=> $this->input->post('total'),
			'disc_persen'           => $this->input->post('disc_persen'),
			'disc_rupiah'           => $this->input->post('disc_rupiah'),
			'netto'             	=> $this->input->post('netto'),
            'created_by'            => $this->session->userdata('user_name'),
            'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           => $this->session->userdata('user_name'),
            'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
		$create_detail_list = $this->M_Purchase_Order->detail_create_list($data);
        if ($create_detail_list) {
            $set_header = $this->M_Purchase_Order->get_total_barang_detail(
                                $this->input->post('id_header'),
                                $this->input->post('disc_persen_hdr'),
                                $this->input->post('disc_rp_hdr'),
                                $this->input->post('ppn_persen_hdr'),
                                $this->input->post('materai')
                          );
            $result = array();
            foreach ($set_header->result_array() as $row) {
                $data['result'] = 'done';
				$data['detail_id'] = $create_detail_list;
                $data['total_barang'] = $row['total_barang']; 
                $data['disc_persen'] = $row['disc_persen']; 
                $data['disc_rupiah'] = $row['disc_rupiah']; 
                $data['dpp'] = $row['dpp']; 
                $data['ppn_persen'] = $row['ppn_persen']; 
                $data['ppn_rupiah'] = $row['ppn_rupiah']; 
                $data['materai'] = $row['materai']; 
                $data['grand_total'] = $row['grand_total'];
            }
            echo json_encode($data);
        } else {
            echo "fail";
		}
    }
		
    function delete_item_detail() {        
        $del = $this->M_Purchase_Order->delete_item_detail();        
        if ($del) {
            $set_header = $this->M_Purchase_Order->get_total_barang_detail(
                                $this->input->post('id_header'),
                                $this->input->post('disc_persen_hdr'),
                                $this->input->post('disc_rupiah_hdr'),
                                $this->input->post('ppn_persen_hdr'),
                                $this->input->post('materai')
                          );
            $result = array();
            foreach ($set_header->result_array() as $row) {
                $data['result'] = 'done';
                $data['total_barang'] = $row['total_barang']; 
                $data['disc_persen'] = $row['disc_persen']; 
                $data['disc_rupiah'] = $row['disc_rupiah']; 
                $data['dpp'] = $row['dpp']; 
                $data['ppn_persen'] = $row['ppn_persen']; 
                $data['ppn_rupiah'] = $row['ppn_rupiah']; 
                $data['materai'] = $row['materai']; 
                $data['grand_total'] = $row['grand_total'];
            }
            echo json_encode($data);
        } else {
            echo "fail";
		}
    }
	
	function delete_item_detail_list() {
		$del = $this->M_Purchase_Order->delete_item_detail_list();
        if ($del) {
            $set_header = $this->M_Purchase_Order->get_total_barang_detail(
                                $this->input->post('id_header'),
                                $this->input->post('disc_persen_hdr'),
                                $this->input->post('disc_rupiah_hdr'),
                                $this->input->post('ppn_persen_hdr'),
                                $this->input->post('materai')
                          );
            $result = array();
            foreach ($set_header->result_array() as $row) {
                $data['result'] = 'done';
                $data['total_barang'] = $row['total_barang']; 
                $data['disc_persen'] = $row['disc_persen']; 
                $data['disc_rupiah'] = $row['disc_rupiah']; 
                $data['dpp'] = $row['dpp']; 
                $data['ppn_persen'] = $row['ppn_persen']; 
                $data['ppn_rupiah'] = $row['ppn_rupiah']; 
                $data['materai'] = $row['materai']; 
                $data['grand_total'] = $row['grand_total'];
            }
            echo json_encode($data);
        } else {
            echo "fail";
		}
    }
    
    function update_item_detail() {		
        $del = $this->M_Purchase_Order->update_item_detail();
        if ($del) {
            $set_header = $this->M_Purchase_Order->get_total_barang_detail(
                                $this->input->post('id_header'),
                                $this->input->post('disc_persen_hdr'),
                                $this->input->post('disc_rupiah_hdr'),
                                $this->input->post('ppn_persen_hdr'),
                                $this->input->post('materai')
                          );
            $result = array();
            foreach ($set_header->result_array() as $row) {
                $data['result'] = 'done';
                $data['total_barang'] = $row['total_barang']; 
                $data['disc_persen'] = $row['disc_persen']; 
                $data['disc_rupiah'] = $row['disc_rupiah']; 
                $data['dpp'] = $row['dpp']; 
                $data['ppn_persen'] = $row['ppn_persen']; 
                $data['ppn_rupiah'] = $row['ppn_rupiah']; 
                $data['materai'] = $row['materai']; 
                $data['grand_total'] = $row['grand_total'];
            }
            echo json_encode($data);
        } else {
            echo "fail";
		}
    }
		
	function update_header() {
        $del = $this->M_Purchase_Order->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
    
    function send_to_approve() {
        $id = $this->uri->segment('4');
        $na = $this->M_Purchase_Order->send_to_approve($id);
        
        if ($na)
            redirect('pembelian/purchase_order/');
        else
            redirect('/');
            
    }
	
	function get_info_pr() {
		$id_pr = $this->input->post('id_pr');
		$id_produk = $this->input->post('id_produk');
        $data = $this->M_Purchase_Order->get_info_pr($id_pr, $id_produk);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
	
	function get_data_pr() {
		$del = $this->M_Purchase_Order->get_data_pr();        
        if ($del) {
            $set_header = $this->M_Purchase_Order->get_total_barang_detail(
                                $this->input->post('id_header'),
                                $this->input->post('disc_persen'),
                                $this->input->post('disc_rupiah'),
                                $this->input->post('ppn_persen'),
                                $this->input->post('materai')
                          );
            $result = array();
            foreach ($set_header->result_array() as $row) {
                $data['result'] = 'done';
                $data['total_barang'] = $row['total_barang']; 
                $data['disc_persen'] = $row['disc_persen']; 
                $data['disc_rupiah'] = $row['disc_rupiah']; 
                $data['dpp'] = $row['dpp']; 
                $data['ppn_persen'] = $row['ppn_persen']; 
                $data['ppn_rupiah'] = $row['ppn_rupiah']; 
                $data['materai'] = $row['materai']; 
                $data['grand_total'] = $row['grand_total'];
            }
            echo json_encode($data);
        } else {
            echo "fail";
		}
	}
	
	function Cetak_Po($po_id) {
		$terbilang = '';
		
		$no_po = '';
		$tgl_po = '';
		$bulan = '';
		$tahun = '';
		$nama_supplier = '';
		$alamat_supplier = '';
		$alamat_pengiriman = '';
		$tanggal_pengiriman = '';
		$tipe_pembayaran = '';
		$top = '0';
		$keterangan = '';
		$total_barang = '0';
		$disc_persen = '0';
		$disc_rupiah = '0';
		$dpp = '0';
		$ppn_persen = '0';
		$ppn_rupiah = '0';
		$materai = '0';
		$grand_total = '0';
		
		$data_header = $this->M_Purchase_Order->get_header($po_id);
        foreach($data_header->result() as $r) {
			$no_po = $r->no_po;
			$tgl_po = $r->tgl_po;
			$bulan = $r->bulan;
			$tahun = $r->tahun;
			$nama_supplier = $r->nama_supplier;
			$alamat_supplier = $r->alamat_supplier;
			$alamat_pengiriman = $r->alamat_pengiriman;
			$tanggal_pengiriman = $r->tgl_pengiriman;
			$tipe_pembayaran = $r->nama_tipe_pembayaran;
			$top = $r->top;
			$keterangan = $r->keterangan;
			$total_barang = $r->total_barang;
			$disc_persen = $r->disc_persen;
			$disc_rupiah = $r->disc_rupiah;
			$dpp = $r->dpp;
			$ppn_persen = $r->ppn_persen;
			$ppn_rupiah = $r->ppn_rupiah;
			$materai = $r->materai;
			$grand_total = $r->grand_total;
		}
		
		$fcpath = FCPATH.$no_po.'png';
		$params['data'] = base_url().'pembelian/purchase_order/onlinechecking/'.$po_id;
		$params['savename'] = $fcpath;
		$this->ciqrcode->generate($params);
		$imagedata = file_get_contents($fcpath);
		$qrcode = base64_encode($imagedata);
		unlink($fcpath);
		
        $pdf = new pdf('P','Letter');
        $pdf->SetTitle("$no_po");
        $pdf->SetHeaderMargin(47);
		$pdf->SetTopMargin(45);
		
		$image = base_url().'assets/img/logo-tui.png';
		$imageData = base64_encode(file_get_contents($image));
		$src = 'data:image/jpeg;base64,'.$imageData;
		
		$html_header = '
			<table style="border-bottom: 0.1px solid black; width: 100%">
				<tr>
					<td style="width: 60%;">
						<img height="50" width="200" src="'.$src.'" alt="">
					</td>
					<td style="width: 30%; font-size: 8px;">
						Kepada Yth.<br>
						<b>'.$nama_supplier.'</b><br>
						<span style="font-size: 8px;">'.$alamat_supplier.'</span>
					</td>
					<td style="width: 10%;">
						<img height="45" width="45" src="data:image/png;base64,'.$qrcode.'" alt="">
					</td>
				</tr>
				<tr>
					<td style="width: 100%; font-size: 8px; text-align: center">
						<h3>SURAT PESANAN</h3><br>
						Nomor : '.$no_po.'<br>
						Tanggal : '.date_format(new DateTime($tgl_po), $this->config->item('FORMAT_DATE_TO_DISPLAY')).'
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
			</table>
		';
        $pdf->setHtmlHeader($html_header);
        
        $pdf->SetAutoPageBreak(true, 50);
        $pdf->SetDisplayMode('real', 'default');
		
		$pdf->setFooterMargin(45);
		$html_footer = '
			<table style="text-align: left; width: 100%">
				<tr>
					<td style="width: 70%;">
						Penerima pesanan,<br>
						Penanggung Jawab,			
					</td>
					<td style="width: 30%;">
						Pemesan,<br>
						Penanggung Jawab,			
					</td>
				</tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr><td></td></tr>
				<tr>
					<td style="width: 70%;">
						Nama jelas<br>
						SIK No.			
					</td>
					<td style="width: 30%;">
						<u>Monalisa, S.Si, Apt.</u><br>
						198780119/STRA-UI/2003/225640			
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
		$data_detail = $this->M_Purchase_Order->get_detail($po_id);
		$jml_row = $data_detail->num_rows();
		$no = 1;
		$konten = '<table border="0.7" style="width: 100%; font-size: 8px; border: 0.1px solid black;" cellpadding="4">';
		if($jml_row != 33) {
			$konten .= '<thead>';
		}
		$konten .= '<tr style="font-weight: bold">
						<td style="width: 7%; text-align: center">No.</td>
						<td style="width: 52%; text-align: left">Nama Barang</td>
						<td style="width: 10%; text-align: center">Kemasan</td>
						<td style="width: 10%; text-align: right">Qty PO</td>
						<td style="width: 21%; text-align: left">Keterangan</td>
					</tr>';
		if($jml_row != 33) {				 
			$konten .=' </thead>
						<tbody>';
		}
		foreach($data_detail->result() as $r) {
			$konten .= '<tr ';
			$konten .= ($no % 30 == 0 ? ' style="page-break-after: always;"' : '');
			$konten .= '>
							<td style="width: 7%; text-align: center">'.$no.'</td>
							 <td style="width: 52%; text-align: left">'.$r->nama_produk_ori.'</td>
							 <td style="width: 10%; text-align: center">'.$r->nama_kemasan.'</td>
							 <td style="width: 10%; text-align: right">'.number_format($r->qty_po).'</td>
							 <td style="width: 21%; text-align: left">&nbsp;</td>
						</tr>';
		   $no++;
		}
		if($jml_row != 33) {
			$konten	.= '</tbody>';
		}
        $konten .='</table>';                
        $pdf->writeHTML($konten, true, false, true, false, '');
		
        $pdf->Output($no_po.'.pdf', 'I');
	}
	
	function onlinechecking($id_po) {
		$data['header'] = $this->M_Purchase_Order->get_header($id_po);
		$data['detail'] = $this->M_Purchase_Order->get_detail($id_po);
		$data['source'] = 'PO';
		$this->load->view('v_pub_service', $data);
	}

} 