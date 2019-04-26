<?php defined('BASEPATH') OR exit('No direct script access allowed');

class stock_opname extends CI_Controller {
	
	var $tbl_stockopname_hdr	= 'ck_tbl_logistik_stockopname_hdr';	
	var $view_stockopname_hdr	= 'ck_view_logistik_stockopname_hdr';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_stock_opname');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Penyesuaian Data Stok',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Manajemen Data</li>
							 <li>Stock Opname</li>
                             <li><a href="logistik/stock_opname">Penyesuaian Data Stok</a></li>',
			'page_icon' => 'icon-cloud-sync',
			'page_title' => 'Penyesuaian Data Stok',
			'page_subtitle' => 'Penyesuaian data stok antara di sistem dan aktual fisik',
			'get_lokasi' => $this->m_stock_opname->get_lokasi(),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_penyesuaiandata').addClass('active');</script>"
		);
		$this->template->build('v_stock_opname', $data);
	}
	
	public function get_data($status) {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_header', 'no_transaksi', 'tgl_transaksi', 'kode_lokasi', 'bulan', 'tahun', 'deskripsi', 
						  'status', 'nama_status', 'status_histori');
        
        // DB table to use
		$sTable = $this->view_stockopname_hdr;
		if ($status == '0') {
			$label = 'label-default';
		} elseif ($status == '1') {
			$label = 'label-success';
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
        $rResult = $this->db->get_where($sTable, array('status' => $status));
    
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
			$row[] = $aRow['kode_lokasi'];
			$row[] = set_month_to_string_ind($aRow['bulan']);
			$row[] = $aRow['tahun'];
			$row[] = $aRow['deskripsi'];
			$row[] = "<span class='label $label'>".$aRow['nama_status']."</span>";
			if ($aRow['status'] == '0') {
                $row[] = '<a title="Edit Detail" href="logistik/stock_opname/stock_opname_detail/?hid='.$aRow['id_header'].'"><span class="icon-pencil5"></span></a>';
			} elseif ($aRow['status'] == '1') {
                $row[] = '<a title="Lihat Detail" href="logistik/stock_opname/stock_opname_detail/?hid='.$aRow['id_header'].'"><span class="icon-text-align-justify"></span></a>&nbsp;&nbsp;';
            }
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function create_header() {
		$no_transaksi = $this->m_stock_opname->create_doc_no(date('n'), date('Y'));
		$file_name = $_FILES['file_bukti_dokumen']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['file_bukti_dokumen']['tmp_name'];
		$new_file = "assets/img/stock_opname/".$file_name;
		$rename_file = "assets/img/stock_opname/".$no_transaksi.".".$file_ext;		
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
        $data = array(
			'no_transaksi'          => $this->m_stock_opname->create_doc_no(date('n'), date('Y')),
			'tgl_transaksi'         => date('Y-m-d'),
			'id_lokasi'				=> $this->input->post('cbolokasi'),
			'bulan'					=> $this->input->post('cbobulan'),
			'tahun'					=> $this->input->post('cbotahun'),
			'deskripsi'           	=> $this->input->post('txtdeskripsi'),
			'file_image'           	=> $new_file,
			'created_by'            => $this->session->userdata('user_name'),
			'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by'           => $this->session->userdata('user_name'),
			'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
		$create_header = $this->m_stock_opname->header_create($data);
		$hid = $this->session->userdata('hid');
		if ($create_header) {
			redirect('logistik/stock_opname/stock_opname_detail/?hid='.$hid);
		} else {
			echo "<script>alert('Fail')</script>";
		}		 
	}
	
	function get_batch_number() {
		$id_lokasi = $this->input->post('id_lokasi');
		$id_produk = $this->input->post('id_produk');
		$this->m_stock_opname->get_batch_number($id_lokasi, $id_produk);
	}
	
	function get_produk_lokasi() {
		$data = $this->m_stock_opname->get_produk_lokasi();
        header('Content-Type: application/json');
        echo json_encode($data);
	}
	
	function detail_list() {
		$hid = $this->input->get('hid');
        $data = $this->m_stock_opname->detail_list($hid);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {				
				echo "<tr>";
					echo "<td id='id_detail' style='display: none;'>".$r->id_detail."</td>";
					echo "<td style='width: 4%; text-align: center;'>".$i."</td>";								
					echo "<td id='id_produk' style='display: none;'>".$r->id_produk."</td>";
					echo "<td id='nama_produk' style='width: 16%;'>".$r->nama_produk."</td>";
					echo "<td id='id_kemasan' style='display: none;'>".$r->id_kemasan."</td>";
					echo "<td id='nama_kemasan' style='width: 8%; text-align: center;'>".$r->nama_kemasan."</td>";
					echo "<td id='batch_number' style='width: 9%; text-align: center;'>".$r->batch_number."</td>";
					echo "<td id='expired_date' style='width: 8%; text-align: center;'>".date_format(new DateTime($r->expired_date), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";
					echo "<td id='qty_data' style='width: 8%; text-align: right;'>".number_format($r->qty_data)."</td>";
					echo "<td id='qty_fisik' style='width: 8%; text-align: right;'>".number_format($r->qty_fisik)."</td>";
					echo "<td id='qty_selisih' style='width: 8%; text-align: right;'>".number_format($r->qty_selisih)."</td>";
					echo "<td id='keterangan' style='width: 11%;'>".$r->keterangan."</td>";
					echo "<td id='rekomendasi' style='width: 11%;'>".$r->rekomendasi."</td>";
					echo "<td style='width: 9%; text-align: center;'>";
						if ($r->status == '1') {
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
	
	function stock_opname_detail() {
		$hid = $this->input->get('hid');
				
		$data = array(
			'title' => 'Penyesuaian Data Stok',
			'breadcrumb_home_active' => '',
			'breadcrumb' => '<li>Logistik</li>
							 <li>Manajemen Data</li>
							 <li>Stock Opname</li>
                             <li><a href="logistik/stock_opname">Penyesuaian Data Stok</a></li>',
			'page_icon' => 'icon-cloud-sync',
			'page_title' => '',
			'page_subtitle' => '',
			'get_header' => $this->m_stock_opname->get_header($hid),
			'get_lokasi' => $this->m_stock_opname->get_lokasi(),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_penyesuaiandata').addClass('active');</script>"			
		);
		$this->template->build('v_stock_opname_detail', $data);		
	}
    
	function create_detail() {
        $data = array(
            'id_header'             => $this->input->post('id_header'),
			'id_produk'             => $this->input->post('id_produk'),
			'batch_number'          => $this->input->post('batch_number'),
            'expired_date'        	=> date_format(new DateTime($this->input->post('expired_date')), $this->config->item('FORMAT_DATE_TO_INSERT')),
            'qty_data'             	=> $this->input->post('qty_data'),
			'qty_fisik'      		=> $this->input->post('qty_aktual'),
			'qty_selisih'      		=> $this->input->post('qty_selisih'),
			'keterangan'      		=> $this->input->post('keterangan'),
			'rekomendasi'      		=> $this->input->post('rekomendasi'),
            'created_by'            => $this->session->userdata('user_name'),
            'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           => $this->session->userdata('user_name'),
            'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $create_detail = $this->m_stock_opname->create_detail($data);
        if ($create_detail)
            echo "done";
        else
            echo "fail";
    }
    
    function delete_item_detail() {        
        $del = $this->m_stock_opname->delete_item_detail();        
        if ($del)
            echo "done";
        else
            echo "fail";
    }
    
    function update_item_detail() {
        $del = $this->m_stock_opname->update_item_detail();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function update_header() {		
        $del = $this->m_stock_opname->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }

}