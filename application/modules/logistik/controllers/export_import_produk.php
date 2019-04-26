<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class export_import_produk extends CI_Controller {
	
	var $tbl_produk			= 'ck_produk_import';
	var $view_produk_all	= 'ck_view_logistik_produk_all';
	
    public function __construct() {
		parent::__construct();
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		if (isset($_SESSION['user_name'])) {
			$this->load->view('theme_default/setting');
		} else {
			session_destroy();
			redirect(base_url() . 'login', 'location');
		}
    }
	
	public function index() {
		$data = array(
			'title' => 'Export / Import Produk',
			'breadcrumb_home_active' => '',
            'breadcrumb' => '<li>Logistik</li>
							 <li>Maintenance</li>
							 <li><a href="logistik/export_import_produk">Export / Import Produk</a></li>',
			'page_icon' => 'fa fa-download',
			'page_title' => 'Export / Import Produk',
			'page_subtitle' => 'Export / Import Produk',
			'custom_scripts' => "<script type='text/javascript'>$('#menu_logistik_exportimportproduk').addClass('active')</script>"
		);
		$this->template->build('v_export_import_produk', $data);
	}
	
	public function export_produk() {		
	 	/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Asia/Jakarta');
		
		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();		
		// Here your first sheet
		$objWorkSheet = $objPHPExcel->getActiveSheet();
		
		// Set all setting
		$objWorkSheet->getDefaultStyle()->getFont()->setSize(12);
		$objWorkSheet->getDefaultRowDimension()->setRowHeight(15);
		$objWorkSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		// Array style
		$styleArrayTitle = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			),
			'font' => array(
				'bold'  => true,
				'size'  => 12
			)
		);
		$styleArrayHeaderTable = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
			),
			'font' => array(
				'bold'  => true,
				'size'  => 10
			)
		);
		
		// Title
		$objWorkSheet->getStyle('A1:H3')->applyFromArray($styleArrayTitle);
		$objWorkSheet->mergeCells('A1:H1'); 
			$objWorkSheet->setCellValue('A1', 'PT. TATA USAHA INDONESIA');
		$objWorkSheet->mergeCells('A2:H2'); 
			$objWorkSheet->setCellValue('A2', 'DATA PRODUK');
		$objWorkSheet->mergeCells('A3:H3'); 
			$objWorkSheet->setCellValue('A3', 'PER TANGGAL '.date("d/m/Y H:i:s"));
			
		// Table Header		
		$objWorkSheet->getStyle('A5:H5')->applyFromArray($styleArrayHeaderTable);
		$objWorkSheet->setCellValue('A5', 'NO');
		$objWorkSheet->setCellValue('B5', 'NAMA PRODUK');
		$objWorkSheet->setCellValue('C5', 'KEMASAN');
		$objWorkSheet->setCellValue('D5', 'PERBEKELAN');
		$objWorkSheet->setCellValue('E5', 'KELOMPOK');
		$objWorkSheet->setCellValue('F5', 'GOLONGAN');
		$objWorkSheet->setCellValue('G5', 'JENIS');
		$objWorkSheet->setCellValue('H5', 'STATUS');
		
		/* Begin - Detail Data */
		$sql = "SELECT
					*
				FROM
					".$this->view_produk_all."
				ORDER BY
					nama_perbekalan_produk,
					nama_kelompok_produk,
					nama_golongan_produk,
					nama_jenis_produk,
					nama_produk";
        $r = $this->db->query($sql);
		if ($r->num_rows() > 0) {
			$baris = 6;
			$no = 1;			
			foreach ($r->result() as $r) {
				$objWorkSheet->setCellValue('A'.$baris, $no.".");
				$objPHPExcel->getActiveSheet()->setCellValueExplicit(
					'B'.$baris, 
					$r->nama_produk,
					PHPExcel_Cell_DataType::TYPE_STRING
				);
				$objPHPExcel->getActiveSheet()->setCellValueExplicit(
					'C'.$baris, 
					$r->nama_kemasan,
					PHPExcel_Cell_DataType::TYPE_STRING
				);
				$objWorkSheet->setCellValue('D'.$baris, $r->nama_perbekalan_produk);
				$objWorkSheet->setCellValue('E'.$baris, $r->nama_kelompok_produk);
				$objWorkSheet->setCellValue('F'.$baris, $r->nama_golongan_produk);
				$objWorkSheet->setCellValue('G'.$baris, $r->nama_jenis_produk);
				$objWorkSheet->setCellValue('H'.$baris, $r->status_produk);
				
				$baris++;
				$no++;
			}
		}
		/* End - Detail Data */
		
		// Set Orientation, size and scaling
		$objWorkSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$objWorkSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
		$objWorkSheet->getPageMargins()->setTop(0.2); 
		$objWorkSheet->getPageMargins()->setRight(0.2); 
		$objWorkSheet->getPageMargins()->setLeft(0.2); 
		$objWorkSheet->getPageMargins()->setBottom(0.2);
		
		// Redirect output to a client’s web browser (Excel2007)
		header("Content-Type: application/vnd.openxmlformteats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment;filename=Produk.xls");
		header("Cache-Control: max-age=0");
		
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
	
	public function import_produk() {
		$fileName = time().$_FILES['file']['name'];
		 
		$config['upload_path'] = './assets/import_excel/'; // buat folder dengan nama assets di root folder
		$config['file_name'] = $fileName;
		$config['allowed_types'] = 'xls|xlsx|csv';
		$config['max_size'] = 10000;
		 
		$this->load->library('upload');
		$this->upload->initialize($config);
		 
		if(!$this->upload->do_upload('file') )
		$this->upload->display_errors();
			 
		//$media = $this->upload->data('file');
		$inputFileName = './assets/import_excel/'.$fileName; // $media['file_name'];
		 
		try {
			$inputFileType = IOFactory::identify($inputFileName);
			$objReader = IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		 
		for ($row = 6; $row <= $highestRow; $row++){                  //  Read a row of data into an array                 
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
											NULL,
											TRUE,
											FALSE);
											 
			// Sesuaikan sama nama kolom tabel di database                                
			$data = array(
				'nama'=> $rowData[0][1]
			);
			 
			// sesuaikan nama dengan nama tabel
			$insert = $this->db->insert($this->tbl_produk,$data);
			//delete_files($media['file_path']);
			//unlink($inputFileName);
			delete_files($inputFileName);
				 
		}
		redirect('logistik/export_import_produk', 'refresh');
    }
	
}