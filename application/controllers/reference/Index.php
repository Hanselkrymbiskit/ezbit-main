<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

	function __construct()
	{
		 // phpinfo();
        parent::__construct();
        $this->data['pagetype'] = "Reference";
        $this->breadcrumbs->push('<i class="fa fa-list-ol"></i>&nbsp;Reference', '/reference');
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

    $this->data['PageTitle'] = "Reference Data"; 
        
    $this->load->template('pages/reference/'.$index,  $this->data,'',1); // this will load the view file    
    // redirect('dashboard');
  } 


}