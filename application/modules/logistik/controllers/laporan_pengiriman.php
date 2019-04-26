<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class laporan_pengiriman extends CI_Controller {
	
    public function __construct() {
		parent::__construct();
		$this->load->model('m_laporan_pengiriman');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
    }
	
	public function index() {
		$data = array(
			'title' => 'Laporan Pengiriman Barang',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Laporan</li>
							 <li><a href="logistik/laporan_pengiriman">Laporan Pengiriman Barang</a></li>',
			'page_icon' => 'icon-files',
			'page_title' => 'Laporan Pengiriman Barang',
			'page_subtitle' => 'Laporan Pengiriman Barang',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_lappenerimaan').addClass('active')</script>"
		);
		$this->template->build('v_laporan_pengiriman', $data);
	}
	
	public function ajax_list() {
        $list = $this->m_laporan_pengiriman->get_datatables();
        $data = array();
        $no = $_POST['start'];
		
        foreach ($list as $r) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $r->no_sj;
            $row[] = date_format(new DateTime($r->tanggal_sj), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
            $row[] = $r->nama_customer;
            $row[] = $r->no_so;
			$row[] = '<button type="button" class="btn btn-default btn-xs btn-detail btn-block" value="'.$r->no_sj.'">Detail</button>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->m_laporan_pengiriman->count_all(),
                        "recordsFiltered" => $this->m_laporan_pengiriman->count_filtered(),
                        "data" => $data,
                );
        // output to json format
        echo json_encode($output);
    }
	
	function tampil_detail() {		
		echo "<table id='table' class='table'>";
		echo 	"<thead>";
		echo 		"<tr>";
		echo 			"<th style='text-align: center'>No.</th>";
		echo 			"<th>Nama Produk</th>";
		echo 			"<th style='text-align: center'>Batch Number</th>";
		echo 			"<th style='text-align: center'>Expired Date</th>";
		echo 			"<th>Kemasan</th>";
		echo 			"<th style='text-align: right'>Qty SJ</th>";
		echo 			"</tr>";
		echo 	"</thead>";
				
		$no_sj = $this->input->post('no_sj');
        $data = $this->m_laporan_pengiriman->get_detail_produk($no_sj);
		
		if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
				echo "<tbody>";
				echo 	"<tr>";
				echo 		"<td style='text-align: center'>".$i."</td>";
				echo 		"<td>".$row->nama_produk."</td>";
				echo 		"<td style='text-align: center'>".$row->batch_number."</td>";
				echo 		"<td style='text-align: center'>".date_format(new DateTime($row->expired_date), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";
				echo 		"<td>".$row->nama_kemasan."</td>";
				echo 		"<td style='text-align: right'>".number_format($row->qty_sj)."</td>";
				echo 	"</tr>";
                echo "</tbody>";
                $i++;
            }
        }
		echo "</table>";
    }
	
}