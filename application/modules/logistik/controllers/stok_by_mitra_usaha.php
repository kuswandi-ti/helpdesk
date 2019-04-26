<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class stok_by_mitra_usaha extends CI_Controller {
	
    public function __construct() {
		parent::__construct();
		$this->load->model('m_stok_by_mitra_usaha');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
    }
	
	public function index() {
		$data = array(
			'title' => 'Stok Berdasarkan Mitra Usaha',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Inquiry</li>
							 <li>Stok</li>
							 <li><a href="logistik/stok_by_mitra_usaha">Stok Berdasarkan Mitra Usaha</a></li>',
			'page_icon' => 'icon-files',
			'page_title' => 'Stok Berdasarkan Mitra Usaha',
			'page_subtitle' => 'Stok Berdasarkan Mitra Usaha',
			'get_supplier' => $this->m_stok_by_mitra_usaha->get_supplier(),
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_stokbymitrausaha').addClass('active')</script>"
		);
		$this->template->build('v_stok_by_mitra_usaha', $data);
	}
	
	public function ajax_list() {
        $list = $this->m_stok_by_mitra_usaha->get_datatables();
        $data = array();
        $no = $_POST['start'];
		
        foreach ($list as $stocks) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $stocks->nama_produk;
            $row[] = $stocks->nama_kemasan;
            $row[] = $stocks->nama_satuan;
			$row[] = $stocks->batch_number;
			$row[] = date_format(new DateTime($stocks->expired_date), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
            $row[] = $stocks->nama_supplier;
            $row[] = $stocks->stok;
            $row[] = $stocks->min_stok;
			$row[] = $stocks->max_stok;
			$row[] = '<button type="button" class="btn btn-default btn-xs btn-detail btn-block" value="'.$stocks->id_produk.'">Detail</button>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->m_stok_by_mitra_usaha->count_all(),
                        "recordsFiltered" => $this->m_stok_by_mitra_usaha->count_filtered(),
                        "data" => $data,
                );
        //output to json format
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
		$batch_number = $this->input->post('batch_number');
		$expired_date = date_format(new DateTime($this->input->post('expired_date')), $this->config->item('FORMAT_DATE_TO_INSERT'));
        $data = $this->m_stok_by_mitra_usaha->get_stok_produk($id_produk, $batch_number, $expired_date);
		
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
        $data = $this->m_stok_by_mitra_usaha->detail_stok($id_produk,
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