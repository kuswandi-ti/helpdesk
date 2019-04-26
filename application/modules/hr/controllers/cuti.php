<?php defined('BASEPATH') OR exit('No direct script access allowed');

class cuti extends CI_Controller {
	
	var $tbl_cuti_hdr	= 'ck_tbl_hr_cuti_hdr';
	var $tbl_karyawan	= 'ck_karyawan';

	public function __construct() {
		parent::__construct();
		$this->load->model('m_cuti');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
	}
	
	public function index() {
		$data = array(
			'title' => 'Cuti',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Human Resource (HR)</li>
							 <li>Presensi</li>
                             <li><a href="hr/cuti">Cuti</a></li>',
			'page_icon' => 'icon-user-lock',
			'page_title' => 'Cuti',
			'page_subtitle' => 'Pengajuan Cuti Karyawan',
			'get_karyawan' => $this->m_cuti->get_karyawan(),
			'custom_scripts' => "<script type='text/javascript'>$('#menu-hrga-presensi-cuti').addClass('active');</script>"
		);
		$this->template->build('v_cuti', $data);
	}
	
	public function get_data() {		
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'no_transaksi', 'tgl_transaksi', 'nik', 'nama_karyawan', 'keterangan');
        
        // DB table to use
		$sTable = "(SELECT
						a.id,
						a.no_transaksi,
						a.tgl_transaksi,
						a.id_karyawan,
						b.nik,
						b.nama AS nama_karyawan,
						a.keterangan
					FROM
						".$this->tbl_cuti_hdr." a
						LEFT OUTER JOIN ".$this->tbl_karyawan." b ON a.id_karyawan = b.id) ck_tbl_hr_cuti_hdr";
    
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
			$row[] = $aRow['nik'];
			$row[] = $aRow['nama_karyawan'];
			$row[] = $aRow['keterangan'];
			$row[] = '<a title="Edit Detail" href="hr/cuti/cuti_detail/?hid='.$aRow['id'].'"><span class="icon-pencil5"></span></a>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function create_header() {		
        $data = array(
			'no_transaksi'          => $this->m_cuti->create_doc_no(date('n'), date('Y')),
			'tgl_transaksi'         => $this->config->item('FORMAT_NOW_DATE_TO_INSERT'),
			'id_karyawan'           => $this->input->post('cbokaryawan'),
			'keterangan'           	=> $this->input->post('txtketerangan'),
			'created_by'            => $this->session->userdata('user_name'),
			'created_date'          => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT'),
			'modified_by'           => $this->session->userdata('user_name'),
			'modified_date'         => $this->config->item('FORMAT_NOW_DATETIME_TO_INSERT')
        );
		$create_header = $this->m_cuti->create_header($data);	
		$hid = $this->session->userdata('hid');
		if ($create_header) {
			redirect('hr/cuti/cuti_detail/?hid='.$hid);
		} else {
			echo "<script>alert('Fail')</script>";
		}		 
	}
	
	function create_detail() {
        $data = array(
            'id_header'             => $this->input->post('id_header'),
			'id_cuti'				=> $this->input->post('id_cuti'),
            'tanggal_awal'          => date_format(new DateTime($this->input->post('tanggal_awal')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'tanggal_akhir'         => date_format(new DateTime($this->input->post('tanggal_akhir')), $this->config->item('FORMAT_DATE_TO_INSERT')),
			'keterangan'         	=> $this->input->post('keterangan'),
            'created_by'            => $this->session->userdata('user_name'),
            'created_date'          => date($this->config->item('FORMAT_DATETIME_TO_INSERT')),
            'modified_by'           => $this->session->userdata('user_name'),
            'modified_date'         => date($this->config->item('FORMAT_DATETIME_TO_INSERT'))
        );
        $create_detail = $this->m_cuti->create_detail($data);
        if ($create_detail)
            echo "done";
        else
            echo "fail";
    }
		
	function cuti_detail() {
		$hid = $this->input->get('hid');
		
		$data = array(
			'title' => 'Cuti',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Human Resource (HR)</li>
							 <li>Presensi</li>
                             <li><a href="hr/cuti">Cuti</a></li>
							 <li>Cuti Detail</li>',
			'page_icon' => 'icon-user-lock',
			'page_title' => '',
			'page_subtitle' => '',
			'get_header' => $this->m_cuti->get_header($hid),
            'get_detail' => $this->m_cuti->get_detail($hid),
			'get_cuti' => $this->m_cuti->get_cuti(),
			'custom_scripts' => "<script type='text/javascript'>$('#menu-hrga-cuti').addClass('active');</script>"
		);
		$this->template->build('v_cuti_detail', $data);		
	}
    
    function detail_list() {
		$hid = $this->input->get('hid');
        $data = $this->m_cuti->detail_list($hid);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
                echo '<tr>';
                echo    '<td id="id" style="display: none;">'.$row->id.'</td>';
                echo    '<td id="no" style="width: 4%; text-align: center;">'.$i.'</td>';
				echo    '<td id="id_cuti" style="display: none;">'.$row->id_cuti.'</td>';
                echo    '<td id="nama_cuti" style="width: 25%; text-align: center;">'.$row->nama_cuti.'</td>';
				echo    '<td id="tanggal_awal" style="width: 12%; text-align: center;">'.date_format(new DateTime($row->tanggal_awal), $this->config->item('FORMAT_DATE_TO_DISPLAY')).'</td>';
				echo    '<td id="tanggal_akhir" style="width: 12%; text-align: center;">'.date_format(new DateTime($row->tanggal_akhir), $this->config->item('FORMAT_DATE_TO_DISPLAY')).'</td>';
				echo    '<td id="keterangan" style="width: 38%;">'.$row->keterangan.'</td>';
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
        $del = $this->m_cuti->delete_item_detail();        
        if ($del) {
			echo "done";
        } else {
            echo "fail";
		}
    }
    
    function update_item_detail() {		
        $del = $this->m_cuti->update_item_detail();
        if ($del) {
            echo "done";
        } else {
            echo "fail";
		}
    }
		
	function update_header() {
        $del = $this->m_cuti->update_header();
        if ($del)
            echo "done";
        else
            echo "fail";
    }

} 