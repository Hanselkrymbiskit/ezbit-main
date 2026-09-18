<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }

    public function Header() {
        $headerData = $this->getHeaderData();
        $this->SetFont('helvetica', 'B', 10);
        $this->writeHTML($headerData['string']);
    }




    public function Footer() { 
        // $footerData = $this->getFooterData();
        // $this->SetFont('helvetica', 'B', 10);
        // $this->writeHTML($footerData['string']);
        // // Position at 15 mm from bottom
        $this->SetY(-15);
        // // Set font
        $this->SetFont('helvetica', 'B', 8);
        // // Page number
        $this->Cell(0, 0, 'PHILHEALTH', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

        // $this->writeHTML('test', false, true, false, true);   
    }
}

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */