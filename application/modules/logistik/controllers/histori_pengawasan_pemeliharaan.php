<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class histori_pengawasan_pemeliharaan extends CI_Controller {
	
    public function __construct() {
		parent::__construct();
		$this->load->model('m_histori_pengawasan_pemeliharaan');
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
    }
	
	public function index() {
		$data = array(
			'title' => 'Histori Pengawasan & Pemeliharaan',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Inquiry</li>
							 <li>Historikal</li>
							 <li><a href="logistik/histori_pengawasan_pemeliharaan">Histori Pengawasan & Pemeliharaan</a></li>',
			'page_icon' => 'icon-files',
			'page_title' => 'Histori Pengawasan & Pemeliharaan',
			'page_subtitle' => 'Histori Pengawasan & Pemeliharaan',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_historipengawasanpemeliharaan').addClass('active')</script>"
		);
		$this->template->build('v_histori_pengawasan_pemeliharaan', $data);
	}
	
	public function ajax_list() {
        $list = $this->m_histori_pengawasan_pemeliharaan->get_datatables();
        $data = array();
        $no = $_POST['start'];
		
        foreach ($list as $r) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $r->no_transaksi;
            $row[] = date_format(new DateTime($r->tgl_transaksi), $this->config->item('FORMAT_DATE_TO_DISPLAY'));
            $row[] = $r->pic;
            $row[] = $r->kategori == '1' ? 'Rutin' : 'Non Rutin';
			$row[] = $r->keterangan;
			$row[] = '<button type="button" class="btn btn-default btn-xs btn-detail btn-block" value="'.$r->id.'">Detail</button>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->m_histori_pengawasan_pemeliharaan->count_all(),
                        "recordsFiltered" => $this->m_histori_pengawasan_pemeliharaan->count_filtered(),
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
		echo 			"<th>Uraian</th>";
		echo 			"<th>Tindak Lanjut</th>";
		echo 			"<th style='text-align: center'>Status</th>";
		echo 			"</tr>";
		echo 	"</thead>";
				
		$id = $this->input->post('id');
        $data = $this->m_histori_pengawasan_pemeliharaan->get_detail($id);
		
		if ($data->num_rows() > 0) {
            $i = 1;
            foreach ($data->result() as $row) {
				echo "<tbody>";
				echo 	"<tr>";
				echo 		"<td style='text-align: center'>".$i."</td>";
				echo 		"<td>".$row->uraian."</td>";
				echo 		"<td>".$row->tindak_lanjut."</td>";
				echo 		"<td style='text-align: center'>".$row->status."</td>";
				echo 	"</tr>";
                echo "</tbody>";
                $i++;
            }
        }
		echo "</table>";
    }
	
}