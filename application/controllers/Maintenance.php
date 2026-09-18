<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends MY_Controller {

	function __construct()
	{
		 // phpinfo();
        parent::__construct();
	}

    public function index()
    {
        // phpinfo();
        // $this->data['title'] = "Home "; 
        // $this->data['PageTitle'] = ""; 
        // $this->data['loadjs'] = array(
        //     // 'namegenerator'
        // );
        $this->load->template('templates/maintenance/index',  $this->data,'',1); // this will load the view file    
       
    }
   
   
}