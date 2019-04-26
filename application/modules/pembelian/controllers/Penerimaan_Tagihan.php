<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan_Tagihan extends CI_Controller {
    	
	var $view_pt_hdr		= 'ck_view_beli_penerimaantagihan_hdr';

	public function __construct() {
		parent::__construct();
		$this->load->model('M_Penerimaan_Tagihan');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Penerimaan Tagihan',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/penerimaan_tagihan">Penerimaan Tagihan</a></li>',
			'page_icon' => 'icon-new-tab',
			'page_title' => 'Penerimaan Tagihan',
			'page_subtitle' => 'Penerimaan invoice pembelian dari supplier',
			'get_supplier' => $this->M_Penerimaan_Tagihan->get_supplier(),
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_penerimaan_tagihan.js'></script>"
		);
		$this->template->build('v_penerimaan_tagihan', $data);
	}
	
	public function get_data() {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_header', 'no_transaksi', 'tgl_transaksi', 'id_supplier', 'nama_supplier', 'no_invoice_supplier', 'tgl_invoice_supplier', 'tgl_jatuh_tempo', 'keterangan', 'validasi', 'grand_total');
        
        // DB table to use
        $sTable = $this->view_pt_hdr;
    
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
			$row[] = $aRow['id_supplier'];
			$row[] = $aRow['nama_supplier'];
			$row[] = $aRow['no_invoice_supplier'];
			$row[] = date_format(new DateTime($aRow['tgl_invoice_supplier']), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
			$row[] = date_format(new DateTime($aRow['tgl_jatuh_tempo']), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
			$row[] = $aRow['keterangan'];
			if ($aRow['validasi'] == '0') {
				$row[] = "<span class='icon-cross2'></span>";	
			} else {
				$row[] = "<span class='icon-check'></span>";
			}
			$row[] = number_format($aRow['grand_total']);
			$row[] = '<a title="Edit Detail" href="pembelian/penerimaan_tagihan/penerimaan_tagihan_detail/?hid='.$aRow['id_header'].'&sid='.$aRow['id_supplier'].'"><span class="icon-pencil4"></span></a>&nbsp;&nbsp;
					  <a title="Print" href="#"><span class="icon-printer"></span></a>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function create_header() {
		$data = array(
			'no_transaksi'          => $this->M_Penerimaan_Tagihan->create_doc_no(date('n'), date('Y')),
			'tgl_transaksi'         => $this->config->item('FORMAT_NOW_DATE_TO_INSERT'),
			'id_supplier'           => $this->input->post('cbosupplier'),
			'no_invoice_supplier'   => $this->input->post('txtnoinvoicesupplier'),
			'tgl_invoice_supplier'	=> date_format(new DateTime($this->input->post('txttglinvoicesupplier')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'tgl_jatuh_tempo'       => date_format(new DateTime($this->input->post('txttgljatuhtempo')),$this->config->item('FORMAT_DATE_TO_INSERT')),
			'keterangan'           	=> $this->input->post('txtketerangan'),
			'created_by'            => $this->session->userdata('user_name'),
			'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by'           => $this->session->userdata('user_name'),
			'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
		);
		$create_header = $this->M_Penerimaan_Tagihan->header_create($data);
		$hid = $this->session->userdata('hid');
		$sid = $this->input->post('cbosupplier');
		if ($create_header) {
			redirect('pembelian/penerimaan_tagihan/penerimaan_tagihan_detail/?hid='.$hid.'&sid='.$sid);
		} else {
			echo "<script>alert('Fail')</script>";
		}
	}
	
	function penerimaan_tagihan_detail() {
		$hid = $this->input->get('hid');
		$sid = $this->input->get('sid');
				
		$data = array(
			'title' => 'Penerimaan Tagihan',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Pembelian</li>
                             <li><a href="pembelian/penerimaan_tagihan">Penerimaan Tagihan</a></li>
							 <li>Penerimaan Tagihan Detail</li>',
			'page_icon' => 'icon-new-tab',
			'page_title' => '',
			'page_subtitle' => '',			
			'custom_scripts' => "<script type='text/javascript' src='assets/custom_script/beli_penerimaan_tagihan.js'></script>",
			'get_header' => $this->M_Penerimaan_Tagihan->get_header($hid),
			'get_detail' => $this->M_Penerimaan_Tagihan->get_detail($hid),
			'get_gr' => $this->M_Penerimaan_Tagihan->get_gr($sid)
		);
		$this->template->build('v_penerimaan_tagihan_detail', $data);		
	}
    
    function create_detail() {
        $data = array(
            'id_header'             => $this->input->post('id_header'),
            'id_gr'                 => $this->input->post('id_gr'),
            'total_tagihan'         => $this->input->post('total_tagihan'),
            'created_by'            => $this->session->userdata('user_name'),
            'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           => $this->session->userdata('user_name'),
            'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
        $create_detail = $this->M_Penerimaan_Tagihan->detail_create($data);
        if ($create_detail) {
            $set_header = $this->M_Penerimaan_Tagihan->get_sub_total_detail(
                                $this->input->post('id_header'),
                                $this->input->post('disc_persen_hdr'),
                                $this->input->post('disc_rp_hdr'),
                                $this->input->post('ppn_persen_hdr'),
                                $this->input->post('materai')
                          );
            $result = array();
            foreach ($set_header->result_array() as $row) {
                $data['result'] = 'done';
                $data['sub_total'] = $row['sub_total']; 
                $data['disc_persen'] = $row['disc_persen']; 
                $data['disc_rp'] = $row['disc_rupiah']; 
                $data['dpp'] = $row['dpp']; 
                $data['ppn_persen'] = $row['ppn_persen']; 
                $data['ppn_rp'] = $row['ppn_rupiah']; 
                $data['materai'] = $row['materai']; 
                $data['grand_total'] = $row['grand_total'];
            }
            echo json_encode($data);
        } else {
            echo "fail";
		}
	}
	
	function detail_list() {
        $data = $this->M_Penerimaan_Tagihan->detail_list($this->input->get('hid'));
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
				echo '<tr>';
				echo    '<td id="id_detail" style="display:none;">'.$row->id_detail.'</td>';
				echo    '<td id="no" style="text-align: center;">'.$i.'</td>';
				echo    '<td id="id_gr" style="display:none;">'.$row->id_gr.'</td>';
				echo    '<td id="no_gr" style="text-align: center;">'.$row->no_gr.'</td>';
				echo    '<td id="tgl_gr" style="text-align: center;">'.date_format(new DateTime($row->tgl_gr), $this->config->item('FORMAT_DATE_TO_DISPLAY')).'</td>';
				echo    '<td id="no_po" style="text-align: center;">'.$row->no_po.'</td>';
				echo    '<td id="no_sj_supplier" style="text-align: center;">'.$row->no_sj_supplier.'</td>';
				echo    '<td id="tgl_terima" style="text-align: center;">'.date_format(new DateTime($row->tgl_terima), $this->config->item('FORMAT_DATE_TO_DISPLAY')).'</td>';
				echo    '<td id="total_tagihan" style="text-align: right;">'.number_format($row->total_tagihan).'</td>';		
				echo 	'<td style="width: 9%; text-align: center;">';
						if ($row->validasi == 0) {
							echo "<span id='hapus_detail' title='Hapus' class='icon-trash2' style='cursor: pointer'></span>";
						} else {
							echo "-";
						}						
				echo 	'</td>';
				echo '</tr>';
				
				$i++;
            }
        }		
    }
	
	function delete_item_detail() {
        $id_detail = $this->input->post('id_detail');
        $del = $this->M_Penerimaan_Tagihan->delete_item_detail($id_detail);
        if ($del) {
            $set_header = $this->M_Penerimaan_Tagihan->get_sub_total_detail(
                                $this->input->post('id_header'),
                                $this->input->post('disc_persen_hdr'),
                                $this->input->post('disc_rupiah_hdr'),
                                $this->input->post('ppn_persen_hdr'),
                                $this->input->post('materai')
                          );
            $result = array();
            foreach ($set_header->result_array() as $row) {
                $data['result'] = 'done';
                $data['sub_total'] = $row['sub_total']; 
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
        $del = $this->M_Penerimaan_Tagihan->update_item_detail();
        if ($del) {
            $set_header = $this->M_Penerimaan_Tagihan->get_sub_total_detail(
                                $this->input->post('id_header'),
                                $this->input->post('disc_persen_hdr'),
                                $this->input->post('disc_rupiah_hdr'),
                                $this->input->post('ppn_persen_hdr'),
                                $this->input->post('materai')
                          );
            $result = array();
            foreach ($set_header->result_array() as $row) {
                $data['result'] = 'done';
                $data['sub_total'] = $row['sub_total']; 
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
        $del = $this->M_Penerimaan_Tagihan->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }
	
	function get_info_gr() {
		$id_gr = $this->input->post('id_gr');
		$res = $this->M_Penerimaan_Tagihan->get_info_gr($id_gr);
		foreach ($res->result_array() as $row) {
			$data['result'] = 'done';
			$data['tgl_gr'] = date_format(new DateTime($row['tgl_gr']), $this->config->item('FORMAT_DATE_TO_DISPLAY')); 
			$data['no_po'] = $row['no_po']; 
			$data['jumlah_tagihan'] = number_format($row['total']); 
		}
		echo json_encode($data);
    }

} 