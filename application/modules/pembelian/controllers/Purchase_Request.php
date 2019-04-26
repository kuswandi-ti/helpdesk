<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_Request extends CI_Controller {
	
	var $tbl_pr_hdr				= 'ck_tbl_beli_purchaserequest_hdr';

	public function __construct() {
		parent::__construct();
		$this->load->model('M_Purchase_Request');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Purchase Request (PR)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/purchase_request">Purchase Request (PR)</a></li>',
			'page_icon' => 'icon-cart-plus2',
			'page_title' => 'Purchase Request (PR)',
			'page_subtitle' => 'Pengajuan pembelian barang yang dilakukan oleh user kepada bagian pembelian',			
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_purchase_request.js'></script>"
		);
		$this->template->build('v_purchase_request', $data);
	}
	
	public function get_data($status_pr) {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'no_transaksi', 'tgl_transaksi', 'bulan', 'tahun', 'deskripsi', 'status_pr', 'nama_status_pr', 'jenis_pr', 'nama_jenis_pr', 'status_histori');
        
        // DB table to use
		$sTable = "(SELECT
						id,
						no_transaksi,
						tgl_transaksi,
						bulan,
						tahun,
						deskripsi,
						jenis_pr,
						CASE
							WHEN jenis_pr = '1' THEN 'RUTIN'
							WHEN jenis_pr = '2' THEN 'NON RUTIN'
						END as nama_jenis_pr,
						status_pr,
						CASE
							WHEN status_pr = '1' THEN 'DRAFT'
							WHEN status_pr = '2' THEN 'PENDING'
							WHEN status_pr = '3' THEN 'APPROVE'
							WHEN status_pr = '4' THEN 'REVISI'
							WHEN status_pr = '5' THEN 'REJECT'		
							WHEN status_pr = '6' THEN 'PROSES PO'
						END AS nama_status_pr,
						status_histori
				    FROM
						".$this->tbl_pr_hdr."
				    WHERE
						status_pr = '".$status_pr."') ck_tbl_beli_purchaserequest_hdr";
		if ($status_pr == '1') {
			$label = 'label-default';
		} elseif ($status_pr == '2') {
			$label = 'label-success';
		} elseif ($status_pr == '3') {
			$label = 'label-info';
		} elseif ($status_pr == '4') {
			$label = 'label-warning';
		} elseif ($status_pr == '5') {
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
			$row[] = $aRow['no_transaksi'];
			$row[] = date_format(new DateTime($aRow['tgl_transaksi']), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
			$row[] = set_month_to_string_ind($aRow['bulan']);
			$row[] = $aRow['tahun'];
			$row[] = $aRow['deskripsi'];
			if ($aRow['jenis_pr'] == '1') { // Rutin
				$row[] = '<span class="label label-danger">'.$aRow['nama_jenis_pr'].'</span>';
			} elseif ($aRow['jenis_pr'] == '2') { // Non Rutin
				$row[] = '<span class="label label-success">'.$aRow['nama_jenis_pr'].'</span>';
			}
			$row[] = "<span class='label $label'>".$aRow['nama_status_pr']."</span>";
			$row[] = '<button value="'.$aRow['status_histori'].'" id="btn_'.$aRow['id'].'" class="histori btn btn-default btn-sm btn-clean" title="Tampilkan histori"><span class="fa fa-play"></span></button>';
			
			if ($aRow['status_pr'] == '1' || $aRow['status_pr'] == '4') { // Draft / Revisi
                $row[] = '<a title="Edit Detail" href="pembelian/purchase_request/purchase_request_detail/?hid='.$aRow['id'].'"><span class="icon-pencil5"></span></a>&nbsp;&nbsp;
                          <a title="Siap Approve" href="pembelian/purchase_request/approve_pr/'.$aRow['id'].'" onclick="return confirm(\'Yakin akan melanjutkan ke proses approve ?\')"><span class="icon-paper-plane"></span></a>&nbsp;&nbsp;';            
			} elseif ($aRow['status_pr'] == '2' || $aRow['status_pr'] == '3' || $aRow['status_pr'] == '5') { // Pending / Approve / Reject
                $row[] = '<a title="Lihat Detail" href="pembelian/purchase_request/purchase_request_detail/?hid='.$aRow['id'].'"><span class="icon-text-align-justify"></span></a>&nbsp;&nbsp;';
            }
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function create_header() {
        $data = array(
			'no_transaksi'          => $this->M_Purchase_Request->create_doc_no(date('n'), date('Y')),
			'tgl_transaksi'         => date('Y-m-d'),
			'bulan'					=> $this->input->post('cbobulan'),
			'tahun'					=> $this->input->post('cbotahun'),
			'jenis_pr'           	=> $this->input->post('rdojenispr'),
			'deskripsi'           	=> $this->input->post('txtdeskripsi'),
			'status_histori'		=> '',
			'created_by'            => $this->session->userdata('user_name'),
			'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by'           => $this->session->userdata('user_name'),
			'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
		$create_header = $this->M_Purchase_Request->header_create($data);
		$hid = $this->session->userdata('hid');
		if ($create_header) {
			redirect('pembelian/purchase_request/purchase_request_detail/?hid='.$hid);
		} else {
			echo "<script>alert('Fail')</script>";
		}		 
	}
	
	function detail_list() {
		$hid = $this->input->get('hid');
        $data = $this->M_Purchase_Request->detail_list($hid);
		$status_pr = $this->M_Purchase_Request->get_status_pr($hid);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {				
				echo "<tr>";
					echo "<td id='id_detail' style='display:none;'>".$r->id_detail."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";								
					echo "<td id='id_produk' style='display:none;'>".$r->id_produk."</td>";
					echo "<td id='nama_produk' style='width: 38%;'>".$r->nama_produk."</td>";
					echo "<td id='id_kemasan' style='display:none;'>".$r->id_kemasan."</td>";
					echo "<td id='nama_kemasan' style='width: 8%; text-align: center;'>".$r->nama_kemasan."</td>";
					echo "<td id='isi_kemasan' style='width: 13%; text-align: center;'>".$r->info_kemasan."</td>";
					echo "<td id='tgl_diperlukan' style='width: 12%; text-align: center;'>".date_format(new DateTime($r->tgl_diperlukan), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";
					echo "<td id='qty_pr' style='width: 8%; text-align: right;'>".number_format($r->qty_pr)."</td>";					
					if ($status_pr == '3') {
						echo "<td id='jumlah_disetujui' style='width: 8%; text-align: right;'>".number_format($r->qty_approve)."</td>";
					} else {
						echo "<td id='jumlah_disetujui' style='width: 8%; text-align: right;'>???</td>";
					}
					echo "<td style='width: 9%; text-align: center;'>";
						if ($status_pr == '2' or $status_pr == '3' or $status_pr == '5' or $status_pr == '6') {
							echo '-';
						} else {
							echo '<span id="edit_detail" title="Edit" class="icon-register text-info text-lg" style="cursor: pointer"></span>&nbsp;
								  <span id="hapus_detail" title="Hapus" class="icon-trash2 text-danger text-lg" style="cursor: pointer"></span>';
						}
					echo "</td>";
				echo "</tr>";                
                $i++;
            }
        }
    }
	
	function purchase_request_detail() {
		$hid = $this->input->get('hid');
				
		$data = array(
			'title' => 'Purchase Request (PR)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/purchase_request">Purchase Request (PR)</a></li>
							 <li>Purchase Request (PR) Detail</li>',
			'page_icon' => 'icon-cart-plus2',
			'page_title' => '',
			'page_subtitle' => '',			
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_purchase_request.js'></script>",
			'get_header' => $this->M_Purchase_Request->get_header($hid)
		);
		$this->template->build('v_purchase_request_detail', $data);		
	}
    
	function create_detail() {
        $data = array(
            'id_header'             => $this->input->post('id_header'),
            'tgl_diperlukan'        => date_format(new DateTime($this->input->post('tgl_diperlukan')), $this->config->item('FORMAT_DATE_TO_INSERT')),
            'id_produk'             => $this->input->post('id_produk'),
            'qty_pr'             	=> $this->input->post('qty_pr'),
			'qty_approve'      		=> $this->input->post('qty_pr'),
            'created_by'            => $this->session->userdata('user_name'),
            'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           => $this->session->userdata('user_name'),
            'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $create_detail = $this->M_Purchase_Request->detail_create($data);
        if ($create_detail)
            echo "done";
        else
            echo "fail";
    }
    
    function delete_item_detail() {        
        $del = $this->M_Purchase_Request->delete_item_detail();        
        if ($del)
            echo "done";
        else
            echo "fail";
    }
    
    function update_item_detail() {
        $del = $this->M_Purchase_Request->update_item_detail();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function update_header() {		
        $del = $this->M_Purchase_Request->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function get_produk_aktif() {
		$data = $this->M_Purchase_Request->get_produk_aktif();
        header('Content-Type: application/json');
        echo json_encode($data);
	}
	
	function get_qty_stok_akhir() {		
		$data = $this->M_Purchase_Request->get_qty_stok_akhir();
		return $data;
	}
	
	function approve_pr() {
        $id = $this->uri->segment('4');
        $na = $this->M_Purchase_Request->approve_pr($id);        
        if ($na)
            redirect('pembelian/purchase_request/');
        else
            redirect('/');
    }

} 