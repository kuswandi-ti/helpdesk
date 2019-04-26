<?php defined('BASEPATH') OR exit('No direct script access allowed');

class perencanaan_pembelian extends CI_Controller {
	
	var $tbl_fb_hdr				= 'ck_tbl_logistik_rencanabeli_hdr';
	var $tbl_fb_dtl				= 'ck_tbl_logistik_rencanabeli_dtl';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_perencanaan_pembelian');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Perencanaan Pembelian Produk',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
			                 <li>Manajemen Data</li>
			                 <li>Perencanaan Stok</li>
                             <li><a href="logistik/perencanaan_pembelian">Perencanaan Pembelian Produk</a></li>',
			'page_icon' => 'fa fa-bar-chart',
			'page_title' => 'Perencanaan Pembelian Produk',
			'page_subtitle' => 'Perencanaan pembelian produk ke supplier',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_perencanaanpembelian').addClass('active');</script>"
		);
		$this->template->build('v_perencanaan_pembelian', $data);
	}
	
	public function get_data($status) {		
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'no_transaksi', 'tgl_transaksi', 'tahun', 'keterangan', 
		                  'status', 'approved_by', 'approved_date');
        
        // DB table to use
		$sTable = "(SELECT
						*
				   FROM
						".$this->tbl_fb_hdr."
				   WHERE
						status = '".$status."'
				   ORDER BY
						no_transaksi) list";
		if ($status == '0') {
			$label = 'label-default';
			$status = 'BELUM APPROVE';
		} elseif ($status == '1') {
			$label = 'label-success';
			$status = 'SUDAH APPROVE';
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
			$row[] = $aRow['tahun'];
            $row[] = $aRow['keterangan'];
			$row[] = "<span class='label $label'>".$status."</span>";
            if ($aRow['status'] == 0) {
                $row[] = '<a title="Edit Detail" href="logistik/perencanaan_pembelian/perencanaan_pembelian_detail/?hid='.$aRow['id'].'"><span class="icon-pencil5"></span></a>';
            } elseif ($aRow['status'] == 1) {
                $row[] = '<a title="Lihat Detail" href="logistik/perencanaan_pembelian/perencanaan_pembelian_detail/?hid='.$aRow['id'].'"><span class="icon-text-align-justify"></span></a>';
            }
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function create_header() {		
        $data = array(
			'no_transaksi'          => $this->m_perencanaan_pembelian->create_doc_no(date('n'), date('Y')),
			'tgl_transaksi'         => $this->config->item('FORMAT_NOW_DATE_TO_INSERT'),
			'tahun'					=> $this->input->post('cbotahun'),
			'keterangan'           	=> $this->input->post('txtketerangan'),
			'created_by'            => $this->session->userdata('user_name'),
			'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by'           => $this->session->userdata('user_name'),
			'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
		$create_header = $this->m_perencanaan_pembelian->create_header($data);	
		$hid = $this->session->userdata('hid');
		if ($create_header) {
			redirect('logistik/perencanaan_pembelian/perencanaan_pembelian_detail/?hid='.$hid);
		} else {
			echo "<script>alert('Fail')</script>";
		}		 
	}
	
	function perencanaan_pembelian_detail() {
		$hid = $this->input->get('hid');
				
		$data = array(
			'title' => 'Perencanaan Pembelian Produk',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
			                 <li>Manajemen Data</li>
			                 <li>Perencanaan Stok</li>
                             <li><a href="logistik/perencanaan_pembelian">Perencanaan Pembelian Produk</a></li>
							 <li>Perencanaan Pembelian Produk Detail</li>',
			'page_icon' => 'fa fa-bar-chart',
			'page_title' => 'Perencanaan Pembelian Produk',
			'page_subtitle' => 'Perencanaan pembelian produk ke supplier',
			'get_header' => $this->m_perencanaan_pembelian->get_header($hid),
			'get_produk_aktif' => $this->m_perencanaan_pembelian->get_produk_aktif(),
			'get_status' => $this->m_perencanaan_pembelian->get_status($hid),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_perencanaanpembelian').addClass('active');</script>"
		);
		$this->template->build('v_perencanaan_pembelian_detail', $data);		
	}
    
    function detail_list() {
		$hid = $this->input->get('hid');
        $data = $this->m_perencanaan_pembelian->detail_list($hid);
		$status = $this->m_perencanaan_pembelian->get_status($hid);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
                echo '<tr>';
                echo    '<td id="id_detail" style="display:none;">'.$row->id_detail.'</td>';
                echo    '<td id="no" style="width: 4%; text-align: center;">'.$i.'</td>';
                echo    '<td id="id_produk" style="display:none;">'.$row->id_produk.'</td>';
                echo    '<td id="nama_produk" style="width: 20%;">'.$row->nama_produk.'</td>';
                echo    '<td id="bulan_01" style="width: 5%; text-align: right;">'.number_format($row->bulan_01).'</td>';
				echo    '<td id="bulan_02" style="width: 5%; text-align: right;">'.number_format($row->bulan_02).'</td>';
				echo    '<td id="bulan_03" style="width: 5%; text-align: right;">'.number_format($row->bulan_03).'</td>';
				echo    '<td id="bulan_04" style="width: 5%; text-align: right;">'.number_format($row->bulan_04).'</td>';
				echo    '<td id="bulan_05" style="width: 5%; text-align: right;">'.number_format($row->bulan_05).'</td>';
				echo    '<td id="bulan_06" style="width: 5%; text-align: right;">'.number_format($row->bulan_06).'</td>';
				echo    '<td id="bulan_07" style="width: 5%; text-align: right;">'.number_format($row->bulan_07).'</td>';
				echo    '<td id="bulan_08" style="width: 5%; text-align: right;">'.number_format($row->bulan_08).'</td>';
				echo    '<td id="bulan_09" style="width: 5%; text-align: right;">'.number_format($row->bulan_09).'</td>';
				echo    '<td id="bulan_10" style="width: 5%; text-align: right;">'.number_format($row->bulan_10).'</td>';
				echo    '<td id="bulan_11" style="width: 5%; text-align: right;">'.number_format($row->bulan_11).'</td>';
				echo    '<td id="bulan_12" style="width: 5%; text-align: right;">'.number_format($row->bulan_12).'</td>';
				echo    '<td id="keterangan" style="width: 10%;">'.$row->keterangan.'</td>';
                echo    '<td style="width: 6%; text-align: center;">';
				if ($status == '1') {
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
    
	function create_detail() {
		$id_header = $this->input->post('id_header');
		$id_produk = $this->input->post('id_produk');
		$bulan_01 = $this->input->post('bulan_01');
		$bulan_02 = $this->input->post('bulan_02');
		$bulan_03 = $this->input->post('bulan_03');
		$bulan_04 = $this->input->post('bulan_04');
		$bulan_05 = $this->input->post('bulan_05');
		$bulan_06 = $this->input->post('bulan_06');
		$bulan_07 = $this->input->post('bulan_07');
		$bulan_08 = $this->input->post('bulan_08');
		$bulan_09 = $this->input->post('bulan_09');
		$bulan_10 = $this->input->post('bulan_10');
		$bulan_11 = $this->input->post('bulan_11');
		$bulan_12 = $this->input->post('bulan_12');
		$keterangan = $this->input->post('keterangan');
		
		$data = array(
            'id_header'             => $id_header,
			'id_produk'				=> $id_produk,
            'bulan_01'             	=> $bulan_01,
			'bulan_02'             	=> $bulan_02,
			'bulan_03'             	=> $bulan_03,
			'bulan_04'             	=> $bulan_04,
			'bulan_05'             	=> $bulan_05,
			'bulan_06'             	=> $bulan_06,
			'bulan_07'             	=> $bulan_07,
			'bulan_08'             	=> $bulan_08,
			'bulan_09'             	=> $bulan_09,
			'bulan_10'             	=> $bulan_10,
			'bulan_11'             	=> $bulan_11,
			'bulan_12'             	=> $bulan_12,
			'keterangan'            => $keterangan,
            'created_by'            => $this->session->userdata('user_name'),
            'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
            'modified_by'           => $this->session->userdata('user_name'),
            'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
		$create_detail = $this->m_perencanaan_pembelian->create_detail($data);
        if ($create_detail) {
            echo "done";
        } else {
            echo "fail";
		}
    }
		
    function delete_item_detail() {        
        $del = $this->m_perencanaan_pembelian->delete_item_detail();        
        if ($del) {
            echo "done";
        } else {
            echo "fail";
		}
    }
    
    function update_item_detail() {		
        $del = $this->m_perencanaan_pembelian->update_item_detail();
        if ($del) {
            echo "done";
        } else {
            echo "fail";
		}
    }
		
	function update_header() {
        $del = $this->m_perencanaan_pembelian->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }

} 