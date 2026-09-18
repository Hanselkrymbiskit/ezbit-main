<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

	function __construct()
	{
		parent::__construct();      
		$this->data['pagetype'] = "API";
		$this->sqlhelper->local->setajaxevent(1);
    	$this->data['sqlhelper']= $this->sqlhelper->local;

    if( $_SERVER['REQUEST_METHOD'] == 'POST')
    {
      if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
      {
         $return = array('Status' => 0, 'Message' => 'Permission Denied!');

         echo json_encode($return);

      }
      else
      {
        unset($_POST['UToken']);
      }
    }

		// $this->data['loadjs'] = array(
		// 		'api',
		// );

	}

	// function index()
	// {
	// 	$this->load->apitemplate('pages/home/index',  $this->data,'',1); // this will load the view file   
	// }

	// function authsecurity()
	// {
	// 	$this->data['title'] = "Authentication"; 
  //   	$this->data['PageTitle'] = 'API Authentication & Data Security'; 
	// 	$this->load->apitemplate('webservice/authsecurity/index',  $this->data,'',1); 
	// }

	// function responsecode()
	// {
	// 	$this->data['title'] = "API Response Code"; 
	// 	$this->data['PageTitle'] = 'API Response Code'; 
	// 	$this->load->apitemplate('webservice/responsecode/index',  $this->data,'',1); 
	// }

	// function responseformat()
	// {
	// 	$this->data['title'] = "Response Format"; 
	// 	$this->data['PageTitle'] = 'API Response Format'; 
	// 	$this->load->apitemplate('webservice/responseformat/index',  $this->data,'',1);
	// }

	// function messageformat()
	// {
	//   	$this->data['title'] = "Message Format"; 
  //   	$this->data['PageTitle'] = 'API Message Format'; 
	// 	$this->load->apitemplate('webservice/messageformat/index',  $this->data,'',1); // this will load the view file 
	// }

	// function methods($apiMethods)
	// {
	// 	$this->data['title'] = "API Methods"; 
	// 	$this->data['MethodTitle'] = @$apiMethods; 
	// 	$this->data['apiMethod'] = @$apiMethods;

	// 	$this->load->model('api/m_api');
	// 	$this->data['methodDetails'] = $this->m_api->methodDetails(trim(@$apiMethods));
	// 	if( is_array($this->data['methodDetails']) )
	// 	{
	// 		$this->data['methodParameter'] = $this->m_api->getMethodParameter($this->data['methodDetails']['FunctionID']);
	// 	}

	// 	$this->load->apitemplate('webservice/apimethods/index',  $this->data,'',1); 
	// }

	// function generatekeys()
	// {
	// 	$this->load->model('api/m_api');
	// 	$codekeys = $this->m_api->generatekeys();

	// 	if( $_SERVER['REQUEST_METHOD'] == 'POST' )
  //   	{
	// 		$return['Status'] = 1;
	// 		$return['Message'] = base64_encode($codekeys);
	// 	}

  //   	echo json_encode($return);
	// }

	function encryptdecryptString($iCall = false,$data = '')
	{
		$return = array('Status' => 0, 'Message' => 'Failed to Encrypt, Invalid Encryption Key');
		if( $_SERVER['REQUEST_METHOD'] == 'POST' )
  	{
			$edata = $_POST;
		}
		else
		{
			if($iCall && is_array($data))
			{
				$edata = $data;				
			}
			else
			{
				goto ExitFunctions;
			}
		}

		$chkE = 0;
		$r = array('generatedkeys','InputString','mode');
		foreach($r as $rkey )
		{
			if( !array_key_exists($rkey, $edata) )
			{
				goto ExitFunctions;
				break;
			}
		}
		
		try
		{
			 
			$mode = ($edata['mode'] == 1) ? 'encrypt' : 'decrypt';
			$outputString = $this->m_api->encryptdecryptString($mode,$edata['generatedkeys'],$edata['InputString'],'openssl','aes-256','cbc');
			$return['Status'] = 1;
			$return['Message'] = $outputString;

		}catch(Exception $err)
		{
			log_message("error",$err->getMessage());
		}

		ExitFunctions:

		if( $_SERVER['REQUEST_METHOD'] == 'POST' )
    	{
			echo json_encode($return);
		}
		else
		{
			return $return;
		}
    
	}

}