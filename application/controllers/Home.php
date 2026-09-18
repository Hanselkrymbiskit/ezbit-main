<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use setasign\FpdiPdfParser\PdfParser\PdfParser;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\FpdiProtection\FpdiProtection;
class Home extends MY_Controller {

	function __construct()
	{
		 // phpinfo();
        parent::__construct();
        $this->data['pagetype'] = "Home";
        $this->breadcrumbs->push('<i class="fa fa-home"></i>&nbsp;Home', '/home');
        redirect('preauthorization');
	}

  function index()
  {
    $index = 'index';

    $this->data['loadcss'] = array(
        'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
        'global/vendor/clockpicker/clockpicker',
        'global/vendor/jquery-labelauty/jquery-labelauty',
        'global/vendor/formvalidation/formValidation',
        'global/vendor/multi-select/multi-select',
      
    );

    $this->data['loadjsmain'] = array(
        'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
        'global/vendor/clockpicker/bootstrap-clockpicker.min',
        'global/vendor/formatter/jquery.formatter',
        'global/vendor/formvalidation/formValidation.min',
        'global/vendor/formvalidation/framework/bootstrap4.min',
        'global/vendor/jquery-labelauty/jquery-labelauty',
        'global/vendor/multi-select/jquery.multi-select',
        'js/userfunction/jquery.quicksearch',
        // 'global/vendor/jq-signature/jq-signature.min',
    );


    $this->data['loadjschild'] = array(
         'global/js/Plugin/bootstrap-datepicker',
         'global/js/Plugin/clockpicker',
         'global/js/Plugin/formatter',
         'global/js/Plugin/jquery-labelauty',
         'global/js/Plugin/responsive-tabs',
         'global/js/Plugin/closeable-tabs',
         'global/js/Plugin/tabs',
         'global/js/Plugin/multi-select',
          'js/userfunction/xlsx.full.min',
          'js/userfunction/table2excel.min',
    );

    $this->data['PageTitle'] = "Home"; 
        
    $this->load->template('pages/home/'.$index,  $this->data,'',1); // this will load the view file    
    // redirect('dashboard');
  } 

  
}