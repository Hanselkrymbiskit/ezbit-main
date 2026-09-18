<?php
    $CI =& get_instance();



// Data Display Convertion Here -- End
    // log_message("error",PDF_PAGE_FORMAT);
$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('PHILHEALTH');
$pdf->SetTitle('QR Code ID');
$pdf->SetSubject('');
$pdf->SetKeywords('QRCODEID');

// set header data
// $PageNo = $pdf->getAliasNumPage().' of '.$pdf->getAliasNbPages();

$headerHtml = '
    ';
// $pdf->setHeaderData('', '50', '',$headerHtml, array(0,64,0), array(0,64,128));

$pdf->setFooterData('PHILHEALTH | Covid-19 Electronic Immunization Registry',array(0,0,0), array(0,0,0));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setPrintFooter(false);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// echo PDF_MARGIN_HEADER;
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT-13, PDF_MARGIN_TOP-24, PDF_MARGIN_RIGHT-14);
// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER+10);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER); //PDF_MARGIN_FOOTER
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 0);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('helvetica', '', 12, '', true); // dejavusans
// $this->pdf->SetFont('dejavusans', 'B', 20); 
// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage('L', 'A5');

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>false, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
// log_message("error",base64_encode($getVaccineeMasterListData['QRCodeBlob']));
// $QRCODESDisplay = '<img src="data:image/png;base64, '.base64_encode($getVaccineeMasterListData['QRCodeBlob']).'" height="100px" width="100px" alt="Registration No" border="1" style="border:1px solid #000" />';


// $pdf->Image('@'.$getVaccineeMasterListData['QRCodeBlob'], 115, 15, 23, 23, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);

// log_message("error",assetPath().'images/vcsidecontact.jpg');
// $pdf->Image(assetPath().'images/vcsidecontact.jpg', 138, 3.5, 12, 100, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);

$pdf->Image('@'.$EmployeeDetails['QRCodeBlob'], 60, 17, 35, 35, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);


$CompleteName = $EmployeeDetails['Firstname'].' '.substr($EmployeeDetails['Middlename'], 0,1).'. '.$EmployeeDetails['Lastname'].( ($EmployeeDetails['Suffixname'] <> '') ? ' '.$EmployeeDetails['Suffixname'] : '');
$CompleteName = strtoupper($CompleteName);
$EmployeeID = $EmployeeDetails['EmployeeID'];
$OfficeName = $CI->myutilities->getRef_Desc(123,$EmployeeDetails['OfficeID']);
$bgColor = "#eb6d99";



$html = <<<EOD
    <style>
        
        .table-css td{
            border:.1px solid #fff;
            padding:0px;
            font-size:13px;
        }

    </style>

    <table class="table-css" cellspacing="0" cellpadding="5"  >
      <tr>
        <td style="width:336px;height:456px;border:.1px solid $bgColor;background-color:$bgColor;">

          <table class="table-css" cellspacing="0" cellpadding="0" style="width:313px;" >
            <tr>
              <td style="width:313px;font-size:14px;text-align: center;border:.1px solid $bgColor;color:#fff;background-color:$bgColor;">
              <br>
              Republic of the Philippines
              </td>
            </tr>
            <tr>
              <td style="width:313px;font-size:16px;font-weight:bold;text-align: center;border:.1px solid #fff;color:#000;background-color:#fff;">
              CITY GOVERNMENT OF MALABON&nbsp;
              </td>
            </tr>
              
            <tr>
              <td rowspan="2" style="height:200;width:181px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

               
              </td>

              <td style="height:140px;width:135px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

               
              </td>
            </tr>
            <tr>
              <td style="height:100px;width:135px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

                  <table class="table-css" cellspacing="0" cellpadding="5" style="width:313px;" >
                    <tr>
                      <td style="font-size:12px !important; text-align:left !important;height:70px;width:126px;border:.1px solid $bgColor;color:#000;background-color:#fff;">
                     CITY HUMAN RESOURCE MGT. DEV'T DEPT.
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size:14px;text-align:left !important;height:30px;width:126px;border:.1px solid $bgColor;color:#000;background-color:#fff;">
                        <b>ID NO.: </b>
                      </td>
                    </tr>
                  </table>
               
              </td>
            </tr>
            <tr>
              <td style="width:313px;font-size:14px;font-weight:bold;text-align: center;border:.1px solid $bgColor;color:#fff;background-color:$bgColor;">
              </td>
            </tr>
            <tr>
              <td style="background-color:#fff;">
                <table class="table-css" cellspacing="0" cellpadding="10">
                  <tr>
                    <td style="width:298px;font-size:20px;font-weight:bold;text-align:center !important;border:.1px solid $bgColor;border-right:.1px solid #fff;">$CompleteName</td>
                  </tr>
                </table>
              </td>
            </tr>

            <tr>
              <td valign="middle" style="width:313px;font-size:16px;font-weight:bold;text-align: center;border:.1px solid #000;color:#fff;background-color:#000;padding:15px !important">
              ADMINISTRATIVE OFFICER IV
              </td>
            </tr>

          </table>


        </td>
        <td style="width:336px;height:456px;border:.1px solid $bgColor;background-color:$bgColor;">

          <table class="table-css" cellspacing="0" cellpadding="0" style="width:313px;" >
            <tr>
              <td style="width:313px;font-size:14px;text-align: center;border:.1px solid $bgColor;color:#fff;background-color:$bgColor;">
              <br>
              Republic of the Philippines
              </td>
            </tr>
            <tr>
              <td style="width:313px;font-size:16px;font-weight:bold;text-align: center;border:.1px solid #fff;color:#000;background-color:#fff;">
              CITY GOVERNMENT OF MALABON&nbsp;
              </td>
            </tr>
              
            <tr>
              <td rowspan="2" style="height:200;width:181px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

               
              </td>

              <td style="height:140px;width:135px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

               
              </td>
            </tr>
            <tr>
              <td style="height:100px;width:135px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

                  <table class="table-css" cellspacing="0" cellpadding="5" style="width:313px;" >
                    <tr>
                      <td style="font-size:12px !important; text-align:left !important;height:70px;width:126px;border:.1px solid $bgColor;color:#000;background-color:#fff;">
                     CITY HUMAN RESOURCE MGT. DEV'T DEPT.
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size:14px;text-align:left !important;height:30px;width:126px;border:.1px solid $bgColor;color:#000;background-color:#fff;">
                        <b>ID NO.: </b>
                      </td>
                    </tr>
                  </table>
               
              </td>
            </tr>
            <tr>
              <td style="width:313px;font-size:14px;font-weight:bold;text-align: center;border:.1px solid $bgColor;color:#fff;background-color:$bgColor;">
              </td>
            </tr>
            <tr>
              <td style="background-color:#fff;">
                <table class="table-css" cellspacing="0" cellpadding="10">
                  <tr>
                    <td style="width:298px;font-size:20px;font-weight:bold;text-align:center !important;border:.1px solid $bgColor;border-right:.1px solid #fff;">$CompleteName</td>
                  </tr>
                </table>
              </td>
            </tr>

            <tr>
              <td valign="middle" style="width:313px;font-size:16px;font-weight:bold;text-align: center;border:.1px solid #000;color:#fff;background-color:#000;padding:15px !important">
              ADMINISTRATIVE OFFICER IV
              </td>
            </tr>

          </table>


        </td>
      </tr>

    </table>


    
   
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

$pdf->AddPage('L', 'A5');

$html = <<<EOD
    <style>
        
        .table-css td{
            border:.1px solid #fff;
            padding:0px;
            font-size:13px;
        }

    </style>

    <table class="table-css" cellspacing="0" cellpadding="5"  >
      <tr>
        <td style="width:336px;height:456px;border:.1px solid $bgColor;background-color:$bgColor;">

          <table class="table-css" cellspacing="0" cellpadding="0" style="width:313px;" >
            <tr>
              <td style="width:313px;font-size:14px;text-align: center;border:.1px solid $bgColor;color:#fff;background-color:$bgColor;">
              <br>
              Republic of the Philippines
              </td>
            </tr>
            <tr>
              <td style="width:313px;font-size:16px;font-weight:bold;text-align: center;border:.1px solid #fff;color:#000;background-color:#fff;">
              CITY GOVERNMENT OF MALABON&nbsp;
              </td>
            </tr>
              
            <tr>
              <td rowspan="2" style="height:200;width:181px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

               
              </td>

              <td style="height:140px;width:135px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

               
              </td>
            </tr>
            <tr>
              <td style="height:100px;width:135px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

                  <table class="table-css" cellspacing="0" cellpadding="5" style="width:313px;" >
                    <tr>
                      <td style="font-size:12px !important; text-align:left !important;height:70px;width:126px;border:.1px solid $bgColor;color:#000;background-color:#fff;">
                     CITY HUMAN RESOURCE MGT. DEV'T DEPT.
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size:14px;text-align:left !important;height:30px;width:126px;border:.1px solid $bgColor;color:#000;background-color:#fff;">
                        <b>ID NO.: </b>
                      </td>
                    </tr>
                  </table>
               
              </td>
            </tr>
            <tr>
              <td style="width:313px;font-size:14px;font-weight:bold;text-align: center;border:.1px solid $bgColor;color:#fff;background-color:$bgColor;">
              </td>
            </tr>
            <tr>
              <td style="background-color:#fff;">
                <table class="table-css" cellspacing="0" cellpadding="10">
                  <tr>
                    <td style="width:298px;font-size:20px;font-weight:bold;text-align:center !important;border:.1px solid $bgColor;border-right:.1px solid #fff;">$CompleteName</td>
                  </tr>
                </table>
              </td>
            </tr>

            <tr>
              <td valign="middle" style="width:313px;font-size:16px;font-weight:bold;text-align: center;border:.1px solid #000;color:#fff;background-color:#000;padding:15px !important">
              ADMINISTRATIVE OFFICER IV
              </td>
            </tr>

          </table>


        </td>
        <td style="width:336px;height:456px;border:.1px solid $bgColor;background-color:$bgColor;">

          <table class="table-css" cellspacing="0" cellpadding="0" style="width:313px;" >
            <tr>
              <td style="width:313px;font-size:14px;text-align: center;border:.1px solid $bgColor;color:#fff;background-color:$bgColor;">
              <br>
              Republic of the Philippines
              </td>
            </tr>
            <tr>
              <td style="width:313px;font-size:16px;font-weight:bold;text-align: center;border:.1px solid #fff;color:#000;background-color:#fff;">
              CITY GOVERNMENT OF MALABON&nbsp;
              </td>
            </tr>
              
            <tr>
              <td rowspan="2" style="height:200;width:181px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

               
              </td>

              <td style="height:140px;width:135px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

               
              </td>
            </tr>
            <tr>
              <td style="height:100px;width:135px;border:.1px solid $bgColor;border-top:.1px solid #fff;color:#000;background-color:$bgColor;padding:0px !important">

                  <table class="table-css" cellspacing="0" cellpadding="5" style="width:313px;" >
                    <tr>
                      <td style="font-size:12px !important; text-align:left !important;height:70px;width:126px;border:.1px solid $bgColor;color:#000;background-color:#fff;">
                     CITY HUMAN RESOURCE MGT. DEV'T DEPT.
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size:14px;text-align:left !important;height:30px;width:126px;border:.1px solid $bgColor;color:#000;background-color:#fff;">
                        <b>ID NO.: </b>
                      </td>
                    </tr>
                  </table>
               
              </td>
            </tr>
            <tr>
              <td style="width:313px;font-size:14px;font-weight:bold;text-align: center;border:.1px solid $bgColor;color:#fff;background-color:$bgColor;">
              </td>
            </tr>
            <tr>
              <td style="background-color:#fff;">
                <table class="table-css" cellspacing="0" cellpadding="10">
                  <tr>
                    <td style="width:298px;font-size:20px;font-weight:bold;text-align:center !important;border:.1px solid $bgColor;border-right:.1px solid #fff;">$CompleteName</td>
                  </tr>
                </table>
              </td>
            </tr>

            <tr>
              <td valign="middle" style="width:313px;font-size:16px;font-weight:bold;text-align: center;border:.1px solid #000;color:#fff;background-color:#000;padding:15px !important">
              ADMINISTRATIVE OFFICER IV
              </td>
            </tr>

          </table>


        </td>
      </tr>

    </table>


    
   
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);


$pdf->deletePage(2);
$pdf->Output('DOH-QRCODE-ID-'.$EmployeeID.'.pdf', "I");

?>
