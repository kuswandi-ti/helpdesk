<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Goods_Receive extends CI_Controller {
    
    var $tbl_gr_hdr		= 'ck_tbl_beli_goodsreceive_hdr';
	var $tbl_gr_dtl		= 'ck_tbl_beli_goodsreceive_hdr';
	var $view_gr_hdr	= 'ck_view_beli_goodsreceive_hdr';

	public function __construct() {
		parent::__construct();
		$this->load->model('M_Goods_Receive');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Goods Receive (GR)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/goods_receive">Goods Receive (GR)</a></li>',
			'page_icon' => 'icon-tags',
			'page_title' => 'Goods Receive (GR)',
			'page_subtitle' => 'Penerimaan barang pembelian dari supplier',
			'get_supplier' => $this->M_Goods_Receive->get_supplier(),
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_goods_receive.js'></script>"
		);
		$this->template->build('v_goods_receive', $data);
	}
	
	public function get_data() {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_header', 'no_transaksi', 'tgl_transaksi', 'id_supplier', 'nama_supplier', 'id_po', 'no_po', 'no_sj_supplier', 'tgl_terima', 'keterangan', 'flag_piutang');
        
        // DB table to use
        $sTable = $this->view_gr_hdr;
    
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
		$rResult = $this->db->get_where($sTable);
    
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
			$row[] = $aRow['id_supplier'];
			$row[] = $aRow['nama_supplier'];
			$row[] = $aRow['no_po'];
			$row[] = $aRow['no_sj_supplier'];
			$row[] = date_format(new DateTime($aRow['tgl_terima']), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
			$row[] = $aRow['keterangan'];
			$row[] = '<a title="Edit Detail" href="pembelian/goods_receive/goods_receive_detail/?hid='.$aRow['id_header'].'&sid='.$aRow['id_supplier'].'"><span class="icon-pencil4"></span></a>&nbsp;&nbsp;';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function create_header() {
		$no_transaksi = $this->M_Goods_Receive->create_doc_no(date('n'), date('Y'));		
		$file_name = $_FILES['file_bukti_dokumen']['name'];
		$array_var = explode(".", $file_name);
		$file_ext = end($array_var);
		$file_tmp = $_FILES['file_bukti_dokumen']['tmp_name'];
		$new_file = "assets/img/goods_receive/".$file_name;
		$rename_file = "assets/img/goods_receive/".$no_transaksi.".".$file_ext;		
		move_uploaded_file($file_tmp, $new_file);
		rename($new_file, $rename_file);
		
		$data = array(
			'no_transaksi'          => $no_transaksi,
			'tgl_transaksi'         => $this->config->item('FORMAT_NOW_DATE_TO_INSERT'),
			'id_supplier'           => $this->input->post('cbosupplier'),
			'id_po'           		=> $this->input->post('cbopo'),
			'no_sj_supplier'        => $this->input->post('txtnosjsupplier'),
			'tgl_terima'           	=> date_format(new DateTime($this->input->post('txttglterima')),$this->config->item('FORMAT_DATE_TO_INSERT')),
			'keterangan'           	=> $this->input->post('txtketerangan'),
			'path_file'           	=> $new_file,
			'created_by'            => $this->session->userdata('user_name'),
			'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by'           => $this->session->userdata('user_name'),
			'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
		);
		$create_header = $this->M_Goods_Receive->header_create($data);
		$hid = $this->session->userdata('hid');
		$sid = $this->input->post('cbosupplier');
		if ($create_header) {
			redirect('pembelian/goods_receive/goods_receive_detail/?hid='.$hid.'&sid='.$sid);
		} else {
			echo "<script>alert('Fail')</script>";
		}
	}
	
	function goods_receive_detail() {
		$hid = $_GET['hid'];
		$sid = $_GET['sid'];
				
		$data = array(
			'title' => 'Goods Receive (GR)',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/goods_receive">Goods Receive (GR)</a></li>
							 <li>Goods Receive (GR) Detail</li>',
			'page_icon' => 'icon-tags',
			'page_title' => '',
			'page_subtitle' => '',			
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_goods_receive.js'></script>",
			'get_header' => $this->M_Goods_Receive->get_header($hid),
			'get_detail' => $this->M_Goods_Receive->get_detail($hid),
            'get_po' => $this->M_Goods_Receive->get_po($sid)
		);
		$this->template->build('v_goods_receive_detail', $data);		
	}
	
	function get_lokasi($id_produk) {
		$this->M_Goods_Receive->get_lokasi($id_produk);
	}
	
	function get_po_supplier() {
		$id_supplier = $this->input->post('id_supplier');
		$data = $this->M_Goods_Receive->get_po($id_supplier);
        if ($data->num_rows() > 0) {			
			foreach($data->result() as $r) {				
				echo "<option value='$r->id_po'>".$r->no_po."</option>";
			}
        }
	}
    
	function create_detail() {
        $data = array(
            'id_header'             => $this->input->post('id_header'),
			'id_produk'             => $this->input->post('id_produk'),
			'batch_number'          => $this->input->post('batch_number'),
            'expired_date'        	=> date_format(new DateTime($this->input->post('expired_date')), $this->config->item('FORMAT_DATE_TO_INSERT')),            
            'qty_gr'                => $this->input->post('qty_gr'),
            'created_by'            => $this->session->userdata('user_name'),
            'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           => $this->session->userdata('user_name'),
            'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $create_detail = $this->M_Goods_Receive->detail_create($data);
        if ($create_detail) {
            echo "done";
        } else {
            echo "fail";
		}
    }
	
	function detail_list() {
		$id_header = $this->input->get('hid');
        $data = $this->M_Goods_Receive->detail_list($id_header);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
				echo '<tr>';
				echo    '<td id="id_detail" style="display:none;">'.$row->id_detail.'</td>';
				echo    '<td id="no" style="width: 4%; text-align: center;">'.$i.'</td>';
				echo    '<td id="id_produk" style="display:none;">'.$row->id_produk.'</td>';
				echo    '<td id="nama_produk" style="width: 28%;">'.$row->nama_produk.'</td>';
				echo    '<td id="id_kemasan" style="display:none;">'.$row->id_kemasan.'</td>';
				echo    '<td id="nama_kemasan" style="width: 8%; text-align: center;">'.$row->nama_kemasan.'</td>';
				echo    '<td id="batch_number" style="width: 10%; text-align: center;">'.$row->batch_number.'</td>';
				echo    '<td id="expired_date" style="width: 10%; text-align: center;">'.(($row->expired_date === NULL or is_null($row->expired_date)) ? '' : date_format(new DateTime($row->expired_date), $this->config->item('FORMAT_DATE_TO_DISPLAY'))).'</td>';
				echo    '<td id="qty_gr" style="width: 6%; text-align: right;">'.number_format($row->qty_gr).'</td>';
				echo    '<td id="id_lokasi" style="display:none;">'.$row->id_lokasi.'</td>';
				echo	'<td id="kode_lokasi" style="width: 10%; text-align: center;">'.$row->kode_lokasi.'</td>';
				echo 	'<td style="width: 9%; text-align: center;">';
							if ($row->flag_piutang == 1) {
								echo '-';
							} else {
								echo "<span id='edit_detail' title='Edit' class='icon-register' style='cursor: pointer'></span>&nbsp;
									  <span id='hapus_detail' title='Hapus' class='icon-trash2' style='cursor: pointer'></span>";
							}
						
				echo 	'</td>';
				echo '</tr>';
				
				$i++;
            }
        }		
    }
	
	function delete_item_detail() {        
        $del = $this->M_Goods_Receive->delete_item_detail();        
        if ($del) {
            echo "done";
        } else {
            echo "fail";
		}
    }
    
    function update_item_detail() {
        $del = $this->M_Goods_Receive->update_item_detail();
        if ($del) {
            echo "done";
        } else {
            echo "fail";
		}
    }
	
	function update_header() {		
        $del = $this->M_Goods_Receive->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function get_data_po() {
		$del = $this->M_Goods_Receive->get_data_po();
        if ($del)
            echo "done";
        else
            echo "fail";
	}

} 