<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class produk_min_over extends CI_Controller {
	
	var $view_stok_akhir		= 'ck_view_logistik_stok_akhir_by_produk';
	
    public function __construct() {
		parent::__construct();
		$this->load->model('m_produk_min_over');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
    }
	
	public function index() {
		$data = array(
			'title' => 'Monitoring Produk Minimum / Over Stok',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Inquiry</li>
							 <li>Monitoring Stok</li>
							 <li><a href="logistik/produk_min_over">Monitoring Produk Minimum / Over Stok</a></li>',
			'page_icon' => 'icon-files',
			'page_title' => 'Monitoring Produk Minimum / Over Stok',
			'page_subtitle' => 'Monitoring Produk Minimum / Over Stok',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_minover').addClass('active')</script>"
		);
		$this->template->build('v_produk_min_over', $data);
	}
	
	public function get_data() {
		/* 
		 * Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id_produk', 'nama_produk', 'nama_kemasan', 'nama_satuan', 
						  'nama_principal', 'stok', 'min_stok', 'max_stok');
        
        // DB table to use
		$sTable = "(SELECT
						*
					FROM "
						.$this->view_stok_akhir." 
					WHERE
						stok > 0) ck_view_logistik_stok_akhir_2";
						
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
			$row[] = $aRow['nama_produk'];
			$row[] = $aRow['nama_kemasan'];
			$row[] = $aRow['nama_satuan'];
			$row[] = $aRow['nama_principal'];
			$row[] = $aRow['stok'];
			$row[] = $aRow['min_stok'];
			$row[] = $aRow['max_stok'];
			
			$status_stok = '';
			if ($aRow['stok'] < $aRow['min_stok']) {
				$status_stok = 'MIN';
			} elseif ($aRow['stok'] > $aRow['max_stok']) {
				$status_stok = 'OVER';
			} else {
				$status_stok = 'OK';
			}
			$row[] = $status_stok;
			
			$row[] = '<button type="button" class="btn btn-default btn-xs btn-detail btn-block" value="'.$aRow['id_produk'].'">Detail</button>';
			
			$output['aaData'][] = $row;
        }
        echo json_encode($output);
    }
	
	function tampil_stok_produk() {		
		echo "<table id='table' class='table table-expandable'>";
		echo 	"<thead>";
		echo 		"<tr>";
		echo 			"<th style='text-align: center'>No.</th>";
		echo 			"<th>Nama Produk</th>";
		echo 			"<th style='text-align: center'>Batch Number</th>";
		echo 			"<th style='text-align: center'>Expired Date</th>";
		echo 			"<th>Kemasan</th>";
		echo 			"<th style='text-align: right'>Stok Akhir</th>";
		echo 			"</tr>";
		echo 	"</thead>";
				
		$id_produk = $this->input->post('id_produk');
        $data = $this->m_produk_min_over->get_stok_produk($id_produk);
		
		if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
				echo "<tbody>";
				echo 	"<tr bgcolor='#cce0ff' style='font-weight: bold'>";
				echo 		"<td style='text-align: center'>".$i."</td>";
				echo 		"<td>".$row->nama_produk."</td>";
				echo 		"<td style='text-align: center'>".$row->batch_number."</td>";
				echo 		"<td style='text-align: center'>".date_format(new DateTime($row->expired_date), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";
				echo 		"<td>".$row->nama_kemasan."</td>";
				echo 		"<td style='text-align: right'>".number_format($row->stok)."</td>";
				echo 	"</tr>";
				echo 	"<tr>";
				echo 		"<td colspan='7'>";
				echo 			"<table class='table'>";
				echo 				"<thead>";
				echo 					"<tr>";		
				echo						"<th style='text-align: right; width: 15%'>Tgl. Input</th>";
				echo 						"<th style='text-align: right; width: 12%'>Tgl. Faktur</th>";
				echo 						"<th style='text-align: right; width: 10%'>Qty Awal</th>";
				echo 						"<th style='text-align: right; width: 10%'>Qty Masuk</th>";
				echo 						"<th style='text-align: right; width: 10%'>Qty Keluar</th>";
				echo 						"<th style='text-align: right; width: 10%'>Qty Akhir</th>";
				echo 						"<th style='width: 18%'>Keterangan</th>";
				echo 						"<th style='text-align: center; width: 15%'>No. Transaksi</th>";
				echo					"</tr>";
				echo 				"</thead>";
				echo				"<tbody>";
										$this->detail_stok($row->id_produk,
														   $row->batch_number,
													       $row->expired_date);
				echo				"</tbody>";
				echo			"</table>";
				echo		"</td>";
				echo	"</tr>";
                echo "</tbody>";
                $i++;
            }
        }
		echo "</table>";
    }
	
	function detail_stok($id_produk,
						 $batch_number,
						 $expired_date) {
        $data = $this->m_produk_min_over->detail_stok($id_produk,
												      $batch_number,
												      $expired_date);
        if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $r) {
				$tgl_transaksi = $r->tgl_transaksi === NULL ? '' : date_format(new DateTime($r->tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
				if ($r->status === '99') {
					echo '<tr bgcolor="#ffe6e6">';
				} else {
					echo '<tr>';
				}				
				echo 	'<td style="text-align: right; width: 15%">'.date_format(new DateTime($r->date_time), $this->config->item('FORMAT_DATETIME_TO_DISPLAY')).'</td>';
				echo 	'<td style="text-align: right; width: 12%">'.$tgl_transaksi.'</td>';
				echo 	'<td style="text-align: right; width: 10%">'.number_format($r->qty_awal).'</td>';
				echo 	'<td style="text-align: right; width: 10%">'.number_format($r->qty_masuk).'</td>';
				echo 	'<td style="text-align: right; width: 10%">'.number_format($r->qty_keluar).'</td>';
				echo 	'<td style="text-align: right; width: 10%">'.number_format($r->qty_akhir).'</td>';
				echo 	'<td style="width: 18%">'.$r->status_keterangan.'</td>';
				echo 	'<td style="text-align: center; width: 15%">'.$r->no_transaksi.'</td>';
				echo '<tr>';
            }
        }
    }
	
}