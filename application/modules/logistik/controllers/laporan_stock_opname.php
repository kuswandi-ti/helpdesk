<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class laporan_stock_opname extends CI_Controller {
	
    public function __construct() {
		parent::__construct();
		$this->load->model('m_laporan_stock_opname');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
    }
	
	public function index() {
		$data = array(
			'title' => 'Laporan Stock Opname',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Laporan</li>
							 <li><a href="logistik/laporan_stock_opname">Laporan Stock Opname</a></li>',
			'page_icon' => 'icon-files',
			'page_title' => 'Laporan Stock Opname',
			'page_subtitle' => 'Laporan Stock Opname',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_lapstokopname').addClass('active')</script>"
		);
		$this->template->build('v_laporan_stock_opname', $data);
	}
	
	public function ajax_list() {
        $list = $this->m_laporan_stock_opname->get_datatables();
        $data = array();
        $no = $_POST['start'];
		
        foreach ($list as $stocks) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $stocks->no_transaksi;
            $row[] = date_format(new DateTime($stocks->tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
            $row[] = $stocks->kode_lokasi;
            $row[] = $stocks->deskripsi;
			$row[] = '<button type="button" class="btn btn-default btn-xs btn-detail btn-block" value="'.$stocks->id_header.'">Detail</button>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->m_laporan_stock_opname->count_all(),
                        "recordsFiltered" => $this->m_laporan_stock_opname->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
	
	function tampil_data_transaksi() {		
		echo "<table id='table' class='table table-expandable'>";
		echo 	"<thead>";
		echo 		"<tr>";
		echo 			"<th style='text-align: center'>No.</th>";
		echo 			"<th>Nama Produk</th>";
		echo 			"<th style='text-align: center'>Batch Number</th>";
		echo 			"<th style='text-align: center'>Expired Date</th>";
		echo 			"<th>Kemasan</th>";
		echo 			"<th style='text-align: right'>Qty Data</th>";
		echo 			"<th style='text-align: right'>Qty Fisik</th>";
		echo 			"<th style='text-align: right'>Qty Selisih</th>";
		echo 			"</tr>";
		echo 	"</thead>";
				
		$id_header = $this->input->post('id_header');
        $data = $this->m_laporan_stock_opname->get_data_transaksi($id_header);
		
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
				echo 		"<td style='text-align: right'>".number_format($row->qty_data)."</td>";
				echo 		"<td style='text-align: right'>".number_format($row->qty_fisik)."</td>";
				echo 		"<td style='text-align: right'>".number_format($row->qty_selisih)."</td>";
				echo 	"</tr>";
                echo "</tbody>";
                $i++;
            }
        }
		echo "</table>";
    }
	
}