<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Changepassword extends MY_Controller {

	function __construct()
	{
		 // phpinfo();
        parent::__construct();
        $this->data['pagetype'] = "Change Password";
	}

    public function index()
    {
        // phpinfo();
        $this->data['title'] = "Change Password "; 
        $this->data['PageTitle'] = ""; 
        
        $this->load->template('pages/home/index',  $this->data,'',1); // this will load the view file    
       
    }
   
   
}