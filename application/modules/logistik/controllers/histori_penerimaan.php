<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class histori_penerimaan extends CI_Controller {
	
    public function __construct() {
		parent::__construct();
		$this->load->model('m_histori_penerimaan');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
    }
	
	public function index() {
		$data = array(
			'title' => 'Histori Penerimaan Barang',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Inquiry</li>
							 <li>Historikal</li>
							 <li><a href="logistik/histori_penerimaan">Histori Penerimaan Barang</a></li>',
			'page_icon' => 'icon-files',
			'page_title' => 'Histori Penerimaan Barang',
			'page_subtitle' => 'Histori Penerimaan Barang',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_hitoripenerimaan').addClass('active')</script>"
		);
		$this->template->build('v_histori_penerimaan', $data);
	}
	
	public function ajax_list() {
        $list = $this->m_histori_penerimaan->get_datatables();
        $data = array();
        $no = $_POST['start'];
		
        foreach ($list as $r) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $r->no_transaksi;
            $row[] = date_format(new DateTime($r->tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
            $row[] = $r->nama_supplier;
            $row[] = $r->no_po;
            $row[] = $r->no_sj_supplier;
            $row[] = date_format(new DateTime($r->tgl_terima), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
			$row[] = $r->keterangan;
			$row[] = '<button type="button" class="btn btn-default btn-xs btn-detail btn-block" value="'.$r->id_header.'">Detail</button>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->m_histori_penerimaan->count_all(),
                        "recordsFiltered" => $this->m_histori_penerimaan->count_filtered(),
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
		echo 			"<th style='text-align: right'>Qty GR</th>";
		echo 			"</tr>";
		echo 	"</thead>";
				
		$id = $this->input->post('id');
        $data = $this->m_histori_penerimaan->get_detail_produk($id);
		
		if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
				echo "<tbody>";
				echo 	"<tr>";
				echo 		"<td style='text-align: center'>".$i."</td>";
				echo 		"<td>".$row->nama_produk_ori."</td>";
				echo 		"<td style='text-align: center'>".$row->batch_number."</td>";
				echo 		"<td style='text-align: center'>".date_format(new DateTime($row->expired_date), $this->config->item('FORMAT_DATE_TO_DISPLAY'))."</td>";
				echo 		"<td>".$row->nama_kemasan."</td>";
				echo 		"<td style='text-align: right'>".number_format($row->qty_gr)."</td>";
				echo 	"</tr>";
                echo "</tbody>";
                $i++;
            }
        }
		echo "</table>";
    }
	
}