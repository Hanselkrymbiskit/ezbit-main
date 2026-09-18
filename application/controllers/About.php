<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends MY_Controller {

	function __construct()
	{
		parent::__construct();
        $this->data['pagetype'] = "whatisnhdr";
        $this->breadcrumbs->push('<i class="fa fa-home"></i>&nbsp;Home', '/home');
        $this->breadcrumbs->push('<i class="fa fa-question-circle-o"></i>&nbsp;About NHDR', '/about');
	}

    function index()
    {
        $this->loadpage(__FUNCTION__,'What is NHDR?','What is a National Health Data Repository (NHDR)?');
    }


    function loadpage($pagefile, $title, $pagetitle)
    {
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

        $this->breadcrumbs->push($title, '/about/'.$pagefile);

        $index = $pagefile;

        $this->data['Title'] = $title; //'What is NHDR?';
        $this->data['PageTitle'] = $pagetitle; //"What is a National Health Data Repository (NHDR)?"; 
        $this->load->template('pages/whatisnhdr/'.$index,  $this->data,'',1); // this will load the view file    
    }

    function whatisnhdr()
    {
        $this->loadpage(__FUNCTION__,'What is NHDR?','What is a National Health Data Repository (NHDR)?');
    }

    function architecturalcomponent()
    {
        $this->loadpage(__FUNCTION__,'Architectural Components','Architectural Components');
    }

    function datasetsubmission()
    {
        $this->loadpage(__FUNCTION__,'Dataset Submission','Dataset Submission');
    }

    function businessintelligence()
    {
        $this->loadpage(__FUNCTION__,'Business Intelligence & Analytics','Business Intelligence & Analytics');
    }

    function healthinformationexchange()
    {
        $this->loadpage(__FUNCTION__,'Health Information Exchange','Health Information Exchange');
    }

    function ehealthservices()
    {
        $this->loadpage(__FUNCTION__,'eHealth Services & Applications','eHealth Services & Applications');
    }

    function opendata()
    {
        $this->loadpage(__FUNCTION__,'Open Data','Open Data');
    }

    function expectedbenefits()
    {
        $this->loadpage(__FUNCTION__,'Expected Benefits of the NHDR','Expected Benefits of the NHDR');
    }

}
   
   