<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class pdf extends TCPDF
{
    public function __construct($orientasi='',$ukuran='')
    {
		//parent::__construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false); 
		parent::__construct($orientation=$orientasi, $unit='mm', $format=$ukuran, $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false); 
    }
     //Page header
   // $htmlHeader;
 
    public function setHtmlHeader($htmlHeader) {
		
        $this->htmlHeader = $htmlHeader;
    }
    public function setFooters($footer) {
		
        $this->footer = $footer;
    }

    public function Header() {
        $this->writeHTMLCell(
            $w = 0, $h = 0, $x = '10', $y = '6',
            $this->htmlHeader, $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'top', $autopadding = true);
    }
	// Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        //$this->SetY(-10);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
       // $this->Cell(0, 10, $this->footer.'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
         $this->writeHTMLCell(
            $w = 0, $h = 50, $x = '', $y = '',
            //$this->footer.'<font align="right" style="text-align:right;" > Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages().'</font>', $border = 0, $ln = 1, $fill = 1,
            $this->footer, $border = 0, $ln = 0, $fill = 0,
            $reseth = true, $align = 'top', $autopadding = true);
    }
}

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */