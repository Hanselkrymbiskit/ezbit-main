<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webservice extends MY_Controller {

	public $ws_templateType = 1; // Page Column Layout - 1, 2
	public $ws_Page_LeftColumnContent = ''; // Holds Page Left Column Content for 2 column layout
	public $ws_doc_content; // Holds the View Page Content for Document Display
	public $ws_page_title; // Holds Page Title
	public $server;
	public $WSKey;
	public $EKey = '';
	public $WS_User_Type;
	public $WS_User_Profile;
	public $WS_User_Name;
	public $WS_Log_ID;
	public $ReturnFormat = 'xml';
	public $DOHID;
	public $mainDB;
	public $response_code_list;
	public $loadjs;
	public $loadcss;

	function __construct()
	{
		parent::__construct();      

			// if( $this->system_settings['WebServiceModule']['Value'] <> 'Y' ) 
			// {
			// 	redirect('login');
			// }

			$this->response_code_list = $this->response_code(TRUE);
			$this->load->library('nusoap/nusoap');
     	$this->load->helper(array('wsmethods'));
      
     	$this->server = new soap_server();
     	$this->namespace =  str_replace(array('http://www','http://','https://www','https://'),'',base_url()).'webservice/wsdl';

	    /** configuration of nusoap server **/
			$this->server->configureWSDL(str_replace(" ","_",strtolower($this->system_settings['ApplicationName'])),$this->namespace);
			
			/* Get Web Service Methods */
			$wsmethods = $this->soap_register_methods();

			if(is_array($wsmethods))
			{
				foreach($wsmethods as $method_name => $method_properties)
				{
					if( function_exists($method_name) )
					{
						$this->server->register("".$method_name."", array(),array('return' => 'xsd:string'),$this->namespace);
					}
				}
			}

			if( $this->uri->rsegment(2) == "wsdl" )
			{
				$_SERVER['QUERY_STRING'] = "wsdl";
			}
			else if( ( $this->uri->rsegment(2) == "" || $this->uri->rsegment(2) == 'index.php' ) && isset($_REQUEST['wsdl']) )
			{
				$_SERVER['QUERY_STRING'] = "wsdl";
			}
			else
			{
				$_SERVER['QUERY_STRING'] = "";
			}


	}

	function _remap($method, $params = array())
	{
		if (function_exists($method)) 
		{
			$params = array_slice($this->uri->rsegment_array(), 2);
			$return = call_user_func_array($method,array($params,TRUE));
			$this->ws_page_title = $return['page_title'].'<span class="pull-right" style="font-size:14px">'.$this->webservice_ui_menu(false).'</span>';
			$this->loadjs = array(
			    'zeroclipboard/dist/ZeroClipboard.min',
			);
			$this->ws_doc_content = $this->load->view('webservice/method_ui_display',$return['param'],TRUE);
			$this->server->service(file_get_contents("php://input"));
		}
		else
		{
			if (method_exists($this, $method)) 
			{
			  $params = array_slice($this->uri->rsegment_array(), 2);
			  return call_user_func_array(array($this, $method), $params);
			}
			else
			{
			  $this->index();   
			}       
		}       
	}

    function webservice_ui_menu($array = false)
    {
    	$Menu_List = array(
   				'desc' => 'Description',
   				'parameter' => 'Method Parameter',
   				'responsevalue' => 'Method Response Value',
   				
   			);
    	
    	if( $array )
    	{
    		return $Menu_List;
    	}

    	$menu = '<ul class="nav nav-pills">';
    	foreach($Menu_List as $id => $caption)
    	{
    		$menu .= '<li role="presentation" class="'.( ( (array_search($id, array_keys($Menu_List)) == 0 ) ) ? "active" : "" ).'"><a data-toggle="tab" href="#'.$id.'" style="border:1px solid #4cae4c">'.$caption.'</a></li>';
    	}
    	$menu .= '</ul>';
			
    	return $menu;
    }

    function index()
    {
		$this->ws_page_title = 'Response Code';
		$vars['response_code_list'] = $this->response_code(TRUE);
		$this->loadjs = array(
                        'zeroclipboard/dist/ZeroClipboard.min',
                    );
		$this->ws_doc_content = $this->load->view('webservice/response_code',$vars,TRUE);
		$this->server->service(file_get_contents("php://input"));
    }

    function clientcall()
    {
    	$this->ws_page_title = 'SOAP Sample Client Call';
		$vars['Test'] = "";
		$this->loadjs = array(
                        'zeroclipboard/dist/ZeroClipboard.min',
                    );
		$this->ws_doc_content = $this->load->view('webservice/clientcall',$vars,TRUE);
		$this->server->service(file_get_contents("php://input"));
    }

    function headercontent()
    {
    	$this->ws_page_title = 'SOAP Header Content';
		$vars['Test'] = "";
		$this->loadjs = array(
                        'zeroclipboard/dist/ZeroClipboard.min',
                    );
		$this->ws_doc_content = $this->load->view('webservice/headercontent',$vars,TRUE);
		$this->server->service(file_get_contents("php://input"));
    }

    function defaultresponse()
    {
    	$this->ws_page_title = 'Authentication Response';
		$vars['Test'] = "";
		$this->loadjs = array(
                        'zeroclipboard/dist/ZeroClipboard.min',
                    );
		$this->ws_doc_content = $this->load->view('webservice/defaultresponse',$vars,TRUE);
		$this->server->service(file_get_contents("php://input"));
    }

    function encrypt_decrypt()
    {
    	$this->ws_page_title = 'Data Encryption / Decryption';
		$vars['Test'] = "";
		$this->loadjs = array(
                        'zeroclipboard/dist/ZeroClipboard.min',
                    );
		$this->ws_doc_content = $this->load->view('webservice/encrypt_decrypt',$vars,TRUE);
		$this->server->service(file_get_contents("php://input"));
    }


    function response_code($getArray = false)
    {
    	$ResponseCode = $this->sqlhelper->local->select("ws_response_code"," DISTINCT `Code` as 'R_Code', `Description`")->ex_select("","Code asc","")->result();
    	if($ResponseCode['Count'] > 0)
    	{
    		$ResponseCode = $ResponseCode['Data'];
    	}

    	$RList = array();
		foreach($ResponseCode  as $Rows => $Cols)
		{
			$RList[ $Cols['R_Code'] ] = $Cols['Description'];
		}

    	if($getArray == true)
    	{
    		return $RList;
    	}
    	else
    	{
			$this->ws_page_title = 'Response Code';
    		$vars['response_code_list'] = $RList;
			$this->ws_doc_content = $this->load->view('webservice/response_code',$vars,TRUE);
			$this->server->service(file_get_contents("php://input"));
    	}	
    }

    // Function to Register Web Service Methods
    function soap_register_methods()
    {
        $methods = array(
                'WS_Check'=>array('group_name'=>'','order'=>''),
            );

        return $methods;
    }

    function Response_Result($value = '',$Response_Code=100,$XMLHeader = TRUE)
    {
    	$xml ="";

		$Response_Desc="";
		$Response_Code_List = $this->response_code_list;
		
		if ( array_key_exists($Response_Code, $Response_Code_List) ) 
		{
			$Response_Desc = $Response_Code_List[$Response_Code];
		}
		else
		{
			$Response_Desc = "Unknown Response Code";
		}

		$xml = $this->myutilities->array_to_xmlformat_string($value,$XMLHeader);
		
		// if($XMLHeader == TRUE)
		// {
		// 	$xml .= "<?xml version=\"1.0\"? >";
		// }

		// $xml .= "<".$this->config->item('WSTag').">";

		// if(is_array($value))
		// {
		// 	if($this->myutilities->parseXml_Response_Result($value) <> '')
		// 	{
		// 		$xml .= $this->myutilities->parseXml_Response_Result($value);
		// 	}			
		// }
		// else
		// {
		// 	if($value <> "")
		// 	{
		// 		$xml .= $value;
		// 	}
		// }

		// $xml .= "</".$this->config->item('WSTag').">";

		$this->server->responseHeaders = array(
			'response_code'=>$Response_Code,
			'response_desc'=>$Response_Desc,
			'response_datetime'=>date("F d, Y g:i A")
		);

		return $xml;
    }

    // This Function will validate if methods is registered and enabled in table ws_method
    function get_FunctionID($WS_Methods = '')
    {
    	$WS_Function_ID = '';
    	if( trim($WS_Methods) <> '')
    	{
    		$functionParameter = $this->sqlhelper->local->select("ws_method")->where("WS_Function_Name = '".$WS_Methods."'")->ex_select('','','1')->row();
			
			if( $functionParameter['Count'] > 0 && $functionParameter['Data']['WS_Enable'] == 'Y' )
			{
				$WS_Function_ID = $functionParameter['Data']['FunctionID'];
			}
    	}
		return $WS_Function_ID; 
    }

    // This Function will validate all Client Submittted Param [Authentication, and Data Parameter]
    function WS_Authenticate_Param($WS_Methods = '',$WS_Param = '')
	{
		$SOAP_Client_Header = $this->server->requestHeader;

		$ReturnMessage = array(
			'Code'			=>	102,
			'Message'		=>	'', 
			'ReturnFormat'  =>	$this->ReturnFormat
		);

		// 1st Check : Validate Client Soap Header Parameter if array and contains required Array Keys
		if( is_array($SOAP_Client_Header) && isset($SOAP_Client_Header['Authentication']) )
		{
			$Authentication = $SOAP_Client_Header['Authentication'];
			if( count($Authentication) == 2 )
			{
				if( isset( $Authentication['WSKey'] ) && isset( $Authentication['ReturnFormat'] ))
				{
					$this->ReturnFormat = ( $Authentication['ReturnFormat'] == "json" ) ? $Authentication['ReturnFormat'] : "xml";
					$ReturnMessage['ReturnFormat'] =$this->ReturnFormat;
					// Validate if WSKey Value Exists and Activated
					$getUserProfiles = $this->sqlhelper->local->select("user_profiles")->where(" User_WS_Key = '".trim($Authentication['WSKey'])."' ")->ex_select('','','1')->row();
					
					if( $getUserProfiles['Count'] > 0 )
					{
						$UserProfile = $getUserProfiles['Data'];
						$this->WSKey = $UserProfile['User_WS_Key'];
						$this->EKey  = $UserProfile['User_E_Key'];
						$this->WS_User_Profile = $UserProfile;

						// Check if Activated 
						$chkActivationTable = $this->sqlhelper->local->select("user_activation_log a inner join v_userinfo b on (a.user_id = b.id)",'a.user_id as UserID, b.accountStatus as AccountStatus, b.usertype as UserType, b.username as UserName')->where("a.user_id = '".trim($UserProfile['user_id'])."'")->ex_select()->row();
						
						if( $chkActivationTable['Count'] > 0 && $chkActivationTable['Data']['AccountStatus'] == 1 )
						{
							$this->WS_User_Type = $chkActivationTable['Data']['UserType'];
							$this->WS_User_Name = $chkActivationTable['Data']['UserName'];
							goto continue_validation_1;
						}
						else
						{
							$ReturnMessage['Message'] = 'Your PHIE Lite Account is '.$this->myutilities->getRef_Desc(3,$chkActivationTable['Data']['AccountStatus']);
						}
					}
					else
					{
						$ReturnMessage['Message'] = 'Invalid Web Service Key [ '.$Authentication['WSKey'].' ].';
					}
				}
				else
				{
					$ReturnMessage['Message'] = 'Missing Soap Header Parameter [ WSKey , ReturnFormat ]';
				}
			}
			else
			{
				$ReturnMessage['Message'] = 'Missing Soap Header Parameter [ WSKey , ReturnFormat ]';
			}
		}
		else
		{
			$ReturnMessage['Message'] = 'Missing Soap Header Parameter [ Authentication ]';
		}
		goto exit_authentication;

		// 2nd Check : Check Parameter per Method
		continue_validation_1:

		$ReturnMessage['Code'] = 103;
		$ReturnMessage['Message'] = 'Invalid Parameter, Unrecognized Encrypted String';

		// Decrypt Parameter
		if(!is_array($WS_Param) && $WS_Param <> '')
		{
			$Decrypter_WS_Param = $this->myutilities->aes256_cbc_decrypt($this->EKey,base64_decode($WS_Param));
			$Decrypter_WS_Param = json_decode($Decrypter_WS_Param,TRUE);
			if( !is_array($Decrypter_WS_Param) )
			{
				$ReturnMessage['Message'] = 'Invalid Parameter Format';
				goto exit_authentication;
			}			
		}

		// Get Function  Required Parameter
		// 
		$WS_Function_ID = $this->get_FunctionID($WS_Methods);
		if( trim($WS_Function_ID) <> '' )
		{
			$WS_Param = $Decrypter_WS_Param;

			$chkMethod_Access = $this->sqlhelper->local->select('ws_method_access')->where("FunctionID = ".trim($WS_Function_ID))->ex_select("","","1")->row();
			
			if( $chkMethod_Access['Count'] > 0 )
			{
				// Get Function Parameter
				$WS_Function_Param = array();
				$getFunction_Param = $this->sqlhelper->local->select("ws_method_param")->where("FunctionID = ".trim($WS_Function_ID))->result();
				
				if( $getFunction_Param['Count'] > 0 )
				{
					foreach($getFunction_Param['Data'] as $fparam_rows =>  $fparam_cols)
					{
						$WS_Function_Param[$fparam_cols['Parameter_Name']] = $fparam_cols['Parameter_Type'];
					}
					
					goto continue_validation_2;
				}
				else
				{
					$ReturnMessage['Code'] = 102;
					$ReturnMessage['Message'] = 'Your Account do not have permission to use this service';
					goto exit_authentication;
				}
			}
			else
			{
				$ReturnMessage['Code'] = 102;
				$ReturnMessage['Message'] = 'Your Account do not have permission to use this service';
				goto exit_authentication;
			}
		}
		else
		{
			$ReturnMessage['Code'] = 104;
			$ReturnMessage['Message'] = 'Web Service Method is not available';
			goto exit_authentication;
		}
		
		continue_validation_2:

		$ReturnMessage['Code'] = 103;
		// Check Submitted Parameter if = to required Function Parameter 
		if( count($WS_Param) == count($WS_Function_Param) )
		{
			foreach($WS_Param as $ws_param_keys => $ws_param_value)
			{
				if( !array_key_exists($ws_param_keys, $WS_Function_Param) )
				{
					$ReturnMessage['Message'] = 'Invalid Parameter Key [ '.$ws_param_keys.' ].';
					goto exit_authentication;
				}
				else
				{
					// Check Submitted Parameter Value Type match the Required Parameter Value Type
					if( $WS_Function_Param[$ws_param_keys] == 1 && is_array($ws_param_value) )
					{
						$ReturnMessage['Message'] = 'Invalid Parameter Value Type for Parameter [ '.$ws_param_keys.' ].';
						goto exit_authentication;
					}
					else if( $WS_Function_Param[$ws_param_keys] == 2 && !is_array($ws_param_value) ) 
					{
						$ReturnMessage['Message'] = 'Invalid Parameter Value Type for Parameter [ '.$ws_param_keys.' ].';
						goto exit_authentication;
					}

				}
			}

			$ReturnMessage['Code'] = 105;
			$ReturnMessage['Param'] = $WS_Param;
			$ReturnMessage['Message'] = "";
				
		}
		else
		{
			$ReturnMessage['Message'] = 'Please verify if the Parameter Keys match the following required Parameter [ '.implode(", ",array_keys($WS_Function_Param)).' ]';
		}

		exit_authentication:

		$WS_Log_Param = array(
				'Parameter' 	=> ( $ReturnMessage['Code'] == 105 ) ? json_encode($WS_Param) : $WS_Param,
				'ReturnCode' 	=> $ReturnMessage['Code'],
				'ReturnMessage' => $ReturnMessage['Message'],
				'LogDateTime' 	=> date('Y-m-d H:i:s')
			);

		$this->WS_Log_Event($WS_Log_Param,'U');

		return $ReturnMessage;
		
	}

	// This Function is used to Validate Parameter if meets the required Condition [ required, valid value ]
	// Return Status = [ 0 = Invalid Parameter, 1 = Validation Failed, 2 = Validation Success ]
	// Return Message = [ 0 = "" , 1 = array details, 2 = ""]
	function WS_Validate_Param($WS_Methods = '', $DataType = '',$Submitted_Param = '' )
	{
		$this->sqlhelper->local->setajaxEvent(1);
		$Error_Counter = 0;

		$ValidFields = array();

		$ReturnMessage = array(
				'Status'  => 0,
				'Message' => ''
			);
		
		$WS_Function_ID = $this->get_FunctionID(trim($WS_Methods));

		if( trim($WS_Function_ID) <> '' && trim($DataType) <> '' && is_array($Submitted_Param) )
		{
			// Get Type_ID
			$Param_Type_ID = $this->sqlhelper->local->select("ws_method_param_key")->where("Type_Name = '".trim(str_replace("'","",$DataType))."'")->ex_select("","","1")->row();
			if( $Param_Type_ID['Count'] > 0 )
			{
				$Param_Type_ID = $Param_Type_ID['Data'];
				$Type_ID = $Param_Type_ID['Type_ID'];
				$Target_Table = $Param_Type_ID['TargetTable'];
			}
			else
			{
				goto exit_parameter_validation;
			}

			$ReturnMessage['Status'] = 1;
			$ReturnMessage['Message'] = array(
				"Description"=>"Invalid Data Content",
				"Required_Content_Count"=>0,
				"Submitted_Content_Count"=>count($Submitted_Param),
				"Unknown_Content_Count"=>0,
				"Missing_Content_Count"=>0,
				"Invalid_Content_Count"=>0,
				"RequiredDetails"=>array(),
				"SubmittedDetails"=>$Submitted_Param,
				"UnknownDetails"=>array(),
				"MissingDetails"=>array(),
				"InvalidDetails"=>array()
			);
			
			// Get Required Parameter
			$required_Param = $this->sqlhelper->local->select('ws_method_param_key_child')->where(" Type_ID = '".$Type_ID."' ")->result();
			if( $required_Param['Count'] > 0 )
			{
				$User_Profile = $this->WS_User_Profile;
	            $UserType = (int) ltrim($this->WS_User_Type,"0");

	            if( $UserType == 4 )
	            {
					$ReturnMessage['Message']['Required_Content_Count'] = $required_Param['Count'];
				}
				else if( $UserType == 7 )
	            {
	            	$ReturnMessage['Message']['Required_Content_Count'] = ($required_Param['Count']+1);

	            	$ReturnMessage['Message']['RequiredDetails']['HealthFacilityCode'] = array(
	                        "Required"=>'Y',
	                        "Parent_Field" => "none",
	                        "Parent_Field_Expected_Value" => "none",
	                        "Format"=>'varchar',
	                        "Max_Character"=>20,
	                        "Value_Type"=>$this->myutilities->getRef_Desc(5,1),
	                        "Value_Format"=>$this->myutilities->get_value_format_desc('varchar'),
	                        "ReferenceTable"=>"ref_facilities",
	                        "ReferenceField"=>"hfhudcode" 
	                    );
	            }

				foreach ($required_Param['Data'] as $rows => $cols)
				{
					$Target_Field = $cols['Target_Field'];
					$ValidFields[$Target_Field] = "";
					$ReferenceTableDetails = (!is_null($cols['Reference_ID']) && trim($cols['Reference_ID']) <> '' ) ? $this->myutilities->getRef_Desc($cols['Reference_ID'],"",true) : "none";
					$ReferenceTable = (is_array($ReferenceTableDetails)) ? $ReferenceTableDetails['Reference_Table'] : "none";
					$ReferenceField = (is_array($ReferenceTableDetails)) ? $ReferenceTableDetails['Reference_Value_Field'] : "none";
					
					$getColumnDetails = array(
							'DATA_TYPE' => $cols['Data_Type'],
							'CHARACTER_MAXIMUM_LENGTH' => $cols['Char_Length'],
							'Numeric_Precision' => $cols['Numeric_Precision'],
							'Numeric_Scale' => $cols['Numeric_Scale']
						);

					$ReturnMessage['Message']['RequiredDetails'][$cols['Parameter_Name']] = array(
						"Required"=>$cols['Required'],
						"Parent_Field" => "none",
						"Parent_Field_Expected_Value" => "none",
						"Format"=>$getColumnDetails['DATA_TYPE'],
						"Max_Character"=>$getColumnDetails['CHARACTER_MAXIMUM_LENGTH'],
						"Value_Type"=>$this->myutilities->getRef_Desc(5,$cols['ValueType']),
						"Value_Format"=>$this->myutilities->get_value_format_desc($getColumnDetails['DATA_TYPE']),
						"ReferenceTable"=>$ReferenceTable,
						"ReferenceField"=>$ReferenceField 
					);
					
					// Check if Required Param exist in Submitted Param
					if(!array_key_exists($cols['Parameter_Name'], $Submitted_Param))
					{
						$ReturnMessage['Message']['MissingDetails'][] = $cols['Parameter_Name'];
						$ReturnMessage['Message']['Missing_Content_Count']++; 
						$Error_Counter++;
					}
					else
					{
						if( $cols['Required'] == "Y" )
						{
							if( $Submitted_Param[$cols['Parameter_Name']] == "")
							{
								$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Value, this Parameter cannot be null/empty';
								$ReturnMessage['Message']['Invalid_Content_Count']++; 
								$Error_Counter++;
							}
						}
						
						if( $cols['Required'] == "N" && !is_null(trim($cols['Parent_Parameter_ID'])) && $cols['Parent_Parameter_ID'] <> "" )
						{
							$Parent_Parameter_Name = $this->myutilities->getRef_Desc(6,$cols['Parent_Parameter_ID']);
							$ReturnMessage['Message']['RequiredDetails'][$cols['Parameter_Name']]['Parent_Field'] = $Parent_Parameter_Name;
							$ReturnMessage['Message']['RequiredDetails'][$cols['Parameter_Name']]['Parent_Field_Expected_Value'] = $cols['Parent_Expected_Value'];
							
							$ReturnMessage['Message']['RequiredDetails'][$cols['Parameter_Name']]['Required'] = ( $Submitted_Param[$Parent_Parameter_Name] == $cols['Parent_Expected_Value'] ) ? "Y" : "N";
							
							if( $Submitted_Param[$Parent_Parameter_Name] == $cols['Parent_Expected_Value'] && $Submitted_Param[$cols['Parameter_Name']] == "" )
							{
								$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Value, this Parameter cannot be null/empty because the Parent Field Key [ '.$Parent_Parameter_Name.' = '.$cols['Parent_Expected_Value'].' ].';
								$ReturnMessage['Message']['Invalid_Content_Count']++; 
								$Error_Counter++;
							}
						}

						if( $Submitted_Param[$cols['Parameter_Name']] <> '' )
						{
							if( $ReferenceTable == "none" ) 
							{
									switch( strtolower($getColumnDetails['DATA_TYPE']) )
									{
										case "varchar":
												
												if( strlen($Submitted_Param[$cols['Parameter_Name']]) > $getColumnDetails['CHARACTER_MAXIMUM_LENGTH']  )
												{
													$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Value Length, submitted value exceed the allowed length';
													$ReturnMessage['Message']['Invalid_Content_Count']++; 
													$Error_Counter++;

													break;
												}

												if( $cols['ValueType'] == 3 )
												{
													// Check if Valid JSON String
													if( is_null(json_decode($Submitted_Param[$cols['Parameter_Name']])) )
													{
														$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Format Value, submitted value is not a valid json formatted string';
														$ReturnMessage['Message']['Invalid_Content_Count']++; 
														$Error_Counter++;
													}
												}	

											break;
										case "text":
												
												if( strlen($Submitted_Param[$cols['Parameter_Name']]) > $getColumnDetails['CHARACTER_MAXIMUM_LENGTH']  )
												{
													$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Value Length, submitted value exceed the allowed length';
													$ReturnMessage['Message']['Invalid_Content_Count']++; 
													$Error_Counter++;

													break;
												}

												if( $cols['ValueType'] == 3 )
												{
													// Check if Valid JSON String
													if( is_null(json_decode($Submitted_Param[$cols['Parameter_Name']])) )
													{
														$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Format Value, submitted value is not a valid json formatted string';
														$ReturnMessage['Message']['Invalid_Content_Count']++; 
														$Error_Counter++;
													}
												}	

											break;
										case "date":

												if( $this->myutilities->validateDateTime($Submitted_Param[$cols['Parameter_Name']],'Y-m-d') == true)
												{
													$ValidFields[$Target_Field] = date("Y-m-d",strtotime($Submitted_Param[$cols['Parameter_Name']]));
												}
												else
												{
													$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Format Value, submitted value is not a valid date';
													$ReturnMessage['Message']['Invalid_Content_Count']++; 
													$Error_Counter++;
												}

											break;

										case "datetime":
												if( $this->myutilities->validateDateTime($Submitted_Param[$cols['Parameter_Name']],'Y-m-d H:i:s') == true)
												{
													$ValidFields[$Target_Field] = date("Y-m-d H:i:s",strtotime($Submitted_Param[$cols['Parameter_Name']]));
												}
												else
												{
													$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Format Value, submitted value is not a valid datetime';
													$ReturnMessage['Message']['Invalid_Content_Count']++; 
													$Error_Counter++;
												}

											break;

										case "time":
												
												if( strtotime($Submitted_Param[$cols['Parameter_Name']]) === false)
												{
													$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Format Value, submitted value is not a valid time';
													$ReturnMessage['Message']['Invalid_Content_Count']++; 
													$Error_Counter++;
												}
												else
												{
													$ValidFields[$Target_Field] = date("H:i:s",strtotime($Submitted_Param[$cols['Parameter_Name']]));
												}

											break;

										case "int":
												
												$ReturnMessage['Message']['RequiredDetails'][$cols['Parameter_Name']]['Max_Character'] = $getColumnDetails['Numeric_Precision'];

												if( !is_numeric($Submitted_Param[$cols['Parameter_Name']]) && !is_int($Submitted_Param[$cols['Parameter_Name']]) )
												{
													$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Format Value, submitted value is not a valid integer';
													$ReturnMessage['Message']['Invalid_Content_Count']++; 
													$Error_Counter++;
												}
												else
												{
													// $Submitted_Param[$cols['Parameter_Name']] = (int) $Submitted_Param[$cols['Parameter_Name']];
													$testbingint_length = (string) $Submitted_Param[$cols['Parameter_Name']];
													if(strlen($testbingint_length) > $getColumnDetails['Numeric_Precision'])
													{
														$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Format Value, submitted value is not a valid big integer';
														$ReturnMessage['Message']['Invalid_Content_Count']++; 
														$Error_Counter++;

														break;
													}	

													// $Submitted_Param[$cols['Parameter_Name']] = (int) $Submitted_Param[$cols['Parameter_Name']];
												}
											break;

										case "bigint":
												log_message("debug",$Submitted_Param[$cols['Parameter_Name']]);
												$ReturnMessage['Message']['RequiredDetails'][$cols['Parameter_Name']]['Max_Character'] = $getColumnDetails['Numeric_Precision'];

												if( !is_numeric($Submitted_Param[$cols['Parameter_Name']]) && !is_int($Submitted_Param[$cols['Parameter_Name']]) )
												{
													$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Format Value, submitted value is not a valid integer';
													$ReturnMessage['Message']['Invalid_Content_Count']++; 
													$Error_Counter++;
												}
												else
												{
													$testbingint_length = (string) $Submitted_Param[$cols['Parameter_Name']];
													if(strlen($testbingint_length) > $getColumnDetails['Numeric_Precision'])
													{
														$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Format Value, submitted value is not a valid big integer';
														$ReturnMessage['Message']['Invalid_Content_Count']++; 
														$Error_Counter++;
														break;
													}	
													// $Submitted_Param[$cols['Parameter_Name']] = (int) $Submitted_Param[$cols['Parameter_Name']];
												}
											break;

										case "decimal":
												$ReturnMessage['Message']['RequiredDetails'][$cols['Parameter_Name']]['Max_Character'] = "(".$getColumnDetails['Numeric_Precision'].",".$getColumnDetails['Numeric_Scale'].")";

												if( !is_numeric($Submitted_Param[$cols['Parameter_Name']]) && !is_float($Submitted_Param[$cols['Parameter_Name']]) )
												{
													$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Format Value, submitted value is not a valid decimal';
													$ReturnMessage['Message']['Invalid_Content_Count']++; 
													$Error_Counter++;
												}
												else
												{
													$split_float = explode(".",$Submitted_Param[$cols['Parameter_Name']]);
													if( count($split_float) == 2)
													{
														if( strlen($split_float[0]) > $getColumnDetails['Numeric_Precision'])
														{
															$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Value, submitted value is larger than the allowed decimal';
															$ReturnMessage['Message']['Invalid_Content_Count']++; 
															$Error_Counter++;

															break;
														}
													}

													$Submitted_Param[$cols['Parameter_Name']] = round($Submitted_Param[$cols['Parameter_Name']],$getColumnDetails['Numeric_Scale']);
												}
											break;

										case "enum":
												if( strlen($Submitted_Param[$cols['Parameter_Name']]) > $getColumnDetails['CHARACTER_MAXIMUM_LENGTH']  )
												{
													$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Invalid Value Length, submitted value exceed the allowed length';
													$ReturnMessage['Message']['Invalid_Content_Count']++; 
													$Error_Counter++;

													break;
												}
											break;
									}
								
							}
							else
							{
								// Check if Value Exists in Reference Table
								if( $Submitted_Param[$cols['Parameter_Name']] <> "" || !is_null($Submitted_Param[$cols['Parameter_Name']]))
								{
									if( $this->myutilities->getRef_Desc($cols['Reference_ID'],$Submitted_Param[$cols['Parameter_Name']]) == "" )
									{
										$refDetails = $this->myutilities->getRef_Desc($cols['Reference_ID'],"",true);
										$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Submitted value doesn\'t exists in the reference table [ '.$refDetails['Reference_Table'].' ] ';
										$ReturnMessage['Message']['Invalid_Content_Count']++; 
										$Error_Counter++;
									}
								}
							}
							
						}

						$ValidFields[$Target_Field] = $Submitted_Param[$cols['Parameter_Name']];
					}
				} // For Each End
				if( $UserType == 4 )
	            {
					$ValidFields['HealthFacilityCode'] = $User_Profile['hfhudcode'];
				}
				else if( $UserType == 7 )
	            {
	            	if( isset($Submitted_Param['HealthFacilityCode']) && trim($Submitted_Param['HealthFacilityCode']) <> '')
	                {
	                    $chkDesc = $this->myutilities->getRef_Desc(4,$Submitted_Param['HealthFacilityCode']);
	                    if( $chkDesc == "" )
	                    {
	                    	$ReturnMessage['Message']['InvalidDetails'][$cols['Parameter_Name']] = ' Submitted value doesn\'t exists in the reference table [ ref_facilities ] ';
							$ReturnMessage['Message']['Invalid_Content_Count']++; 
							$Error_Counter++;
	                    }
	                    else
	                    {
	                    	$ValidFields['HealthFacilityCode'] = $Submitted_Param['HealthFacilityCode'];
	                    }
	                }
	                else
	            	{
	            		$ReturnMessage['Message']['MissingDetails'][] = 'HealthFacilityCode';
						$ReturnMessage['Message']['Missing_Content_Count']++; 
						$Error_Counter++;
	            	}
            	}

				// Check for Unknown Content
				foreach($Submitted_Param as $S_Keys => $S_Value)
				{
					if(!array_key_exists($S_Keys, $ReturnMessage['Message']['RequiredDetails'] ) )
					{
						$ReturnMessage['Message']['UnknownDetails'][] = $S_Keys;
						$ReturnMessage['Message']['Unknown_Content_Count']++; 
						$Error_Counter++;
					}
				}

				if( $UserType == 4 )
	            {
					if( $required_Param['Count'] <> count($Submitted_Param) )
					{
						$Error_Counter++;
					}
				}
				else if( $UserType == 7 )
	            {
	            	if( ($required_Param['Count']+1) <> count($Submitted_Param) )
					{
						$Error_Counter++;
					}
            	}
				
			}
		}
	
		if( $Error_Counter == 0)
		{	
			$ReturnMessage['Status'] = 2;
			$ReturnMessage['Validation_Content'] = $ReturnMessage['Message'];
			$ReturnMessage['Message'] = $ValidFields;
		}
		
		exit_parameter_validation:
		return $ReturnMessage;
	}

	// This Function is used to log webservice event
	//  Mode [ I = Insert, U = Update ]
	//  Param [ Contains Parameter ]
	function WS_Log_Event($Param = '',$Mode = 'I')
	{
		if( is_array($Param) )
		{
			// LogType, Function_Name, User_IP, LogDateTime, LogID, WSKey, ReturnCode, ReturnMessage
			if( strtoupper($Mode) == 'I')
			{
				$Insert_array = array(
						'User_IP' 		=> ( isset($_SERVER['HTTP_CLIENT_IP'] ) ) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'],
						'User_Name' 	=> $this->WS_User_Name ,
						'WSKey' 		=> $this->WSKey,
						// 'FunctionID' 	=> ( isset($Param['FunctionID']) ) ? $Param['FunctionID'] : '',
						// 'DataType' 		=> ( isset($Param['DataType']) ) ? $Param['DataType'] : '',
						// 'Parameter' 	=> ( isset($Param['Parameter']) ) ? $Param['Parameter'] : '',
						// 'ReturnCode' 	=> ( isset($Param['ReturnCode']) ) ? $Param['ReturnCode'] : '',
						// 'ReturnMessage' => ( isset($Param['ReturnMessage']) ) ? $Param['ReturnMessage'] : '',
						// 'Remarks' 		=> ( isset($Param['Remarks']) ) ? $Param['Remarks'] : '',
						'LogDateTime' 	=> date('Y-m-d H:i:s')
					);

				$list = array('FunctionID','DataType','Parameter','ReturnCode','ReturnMessage','Remarks','ResponseDateTime');

				foreach( $list as $keys)
				{
					if( array_key_exists($keys, $Param) )
					{
						$Insert_array[$keys] = $Param[$keys];
					}
				}

				$runInsert = $this->sqlhelper->local->insert("ws_logs")->ex_insert($Insert_array)->run();
				if( $runInsert['ErrorCode'] == '' )
				{
					$this->WS_Log_ID = $runInsert['Data'];
				}

			}
			else if( strtoupper($Mode) == 'U' && trim($this->WS_Log_ID) <> '' )
			{
				$Update_array = array(
						'User_Name' 	=> $this->WS_User_Name,
						'WSKey' 		=> $this->WSKey,
						// 'DataType' 		=> ( isset($Param['DataType']) ) ? $Param['DataType'] : '',
						// 'Parameter' 	=> ( isset($Param['Parameter']) ) ? $Param['Parameter'] : '',
						// 'ReturnCode' 	=> ( isset($Param['ReturnCode']) ) ? $Param['ReturnCode'] : '',
						// 'ReturnMessage' => ( isset($Param['ReturnMessage']) ) ? $Param['ReturnMessage'] : '',
						// 'Remarks' 		=> ( isset($Param['Remarks']) ) ? $Param['Remarks'] : '',
						// 'ResponseDateTime' => ( isset($Param['ResponseDateTime']) ) ? $Param['ResponseDateTime'] : '',
					);

				$list = array('DataType','Parameter','ReturnCode','ReturnMessage','Remarks','ResponseDateTime');

				foreach( $list as $keys)
				{
					if( array_key_exists($keys, $Param) )
					{
						$Update_array[$keys] = $Param[$keys];
					}
				}

				$runUpdate = $this->sqlhelper->local->update("ws_logs")->ex_update($Update_array)->where(" LogID = '".$this->WS_Log_ID."'")->run();
			}
		}

	}

	function get_Method_Param_Key_Details($Type = '')
	{
		$Method_Param_Detail = '';
		if( trim( $Type ) <> '' )
		{
			$Param_Type_ID = $this->sqlhelper->local->select("ws_method_param_key")->where("Type_Name = '".trim(str_replace("'","",$Type))."'")->ex_select("","","1")->row();
			if( $Param_Type_ID['Count'] > 0 )
			{
				$Method_Param_Detail = $Param_Type_ID['Data'];
			}

		}	
		return $Method_Param_Detail;
	}

	function get_DOHID($hfhudcode = '',$hospitalno = '')
	{
		$DOHID = '';
		if($hfhudcode <> '' && $hospitalno <> '')
		{
			$get_DOHID = $this->sqlhelper->local->select("hie_clientregistry_local")->where("HealthFacilityCode = '".trim($hfhudcode)."' and HOSPITALNO = '".trim($hospitalno)."'")->ex_select("","Log_Created_DateTime desc","1")->row();
			if( $get_DOHID['Count'] > 0 )
			{
				$DOHID = $get_DOHID['Data']['DOHID'];
			}
		}
		return $DOHID;
	}

	function WS_Response_Result($Response_Message,$Response_Code)
	{
		$final = $this->Response_Result($Response_Message,$Response_Code);
		if($this->ReturnFormat == "json")
		{
			$final = json_encode($this->myutilities->dataxml_to_array($final));		
		}
		
		if( $this->EKey <> '' )
		{
			$final = base64_encode($this->myutilities->aes256_cbc_encrypt($this->EKey,$final));
		}
		
		return $final;
	}

	function datatable_content()
	{
		$return = array( 
		    "Status"=>0,
		    "Message"=>""
		  );

		if( $_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$table = 'v_ws_data_content_param';

			if( isset($_REQUEST['getArrayCode']) )
			{
				$ParamList = array();
				$getParamList = $this->sqlhelper->local->select($table)->where("Type_ID = ".$_REQUEST['getArrayCode'])->result();
				if( $getParamList['Count'] > 0)
				{
					foreach($getParamList['Data'] as $row=>$col)
					{
						$ParamList[$col['Parameter_Name']] = $col['Parameter_Name']." Value ".( ($col['Required'] == 'Y') ? "[Required Field]" : "" ) ;
					}
				}

				echo json_encode($ParamList);
			}
			else
			{
				// Table's primary key
				  $primaryKey = 'DataID';
				  $i=0;
				  $columns = array(
				      array(
				          'db' => 'DataID',
				          'dt' => 'DT_RowId',
				          'field' =>'DataID',
				          'formatter' => function( $d, $row ) {
				              return 'row_'.$d;
				          }
				      ),
				      array(
				          'db' => 'DataID',
				          'dt' => 'DT_RowClass',
				          'field' =>'DataID',
				          'formatter' => function( $d, $row ) {
				              return 'parameter_row';
				          }
				      ),
			     	array( 'db' => 'DataID', 'dt' => 0 ,'field' =>'Parameter_Name','formatter' => function( $d, $row ) {
					   return $d;
					} ),
					array( 'db' => 'Parameter_Name', 'dt' => 1 ,'field' =>'Parameter_Name','formatter' => function( $d, $row ) {
					   return $d;
					} ),
					array( 'db' => 'Description',  'dt' => 2,'field' =>'Description','formatter' => function( $d, $row ) {
					  return $d;
					} ),
					array( 'db' => 'Data_Type',  'dt' => 3,'field' =>'Data_Type','formatter' => function( $d, $row ) {
					  return strtoupper($d).( ($d == "varchar" || $d=="text" || $d == "enum") ? "(".$row['Char_Length'].")" : ( ( $d == "date" || $d == "datetime") ? "" : (($d=="decimal") ? "(".$row['Numeric_Precision'].",".$row['Numeric_Scale'].")" : "(".$row['Numeric_Precision'].")" )  ) );
					} ),
					array( 'db' => 'Required',  'dt' => 4 ,'field' =>'Required','formatter' => function( $d, $row ) {
					  return $d;
					} ),
					array( 'db' => 'Parent_Parameter_ID',  'dt' => 5 ,'field' =>'Parent_Parameter_ID','formatter' => function( $d, $row ) {
					  return $d;
					} ),
					array( 'db' => 'Parent_Expected_Value',  'dt' => 6 ,'field' =>'Parent_Expected_Value','formatter' => function( $d, $row ) {
					  return $d;
					} ),
					array( 'db' => 'Reference_Table',  'dt' => 7 ,'field' =>'Reference_Table','formatter' => function( $d, $row ) {
					  return $d;
					} ),
					array( 'db' => 'Reference_Value_Field',  'dt' => 8 ,'field' =>'Reference_Value_Field','formatter' => function( $d, $row ) {
					  return $d;
					} ),  
					array( 'db' => 'Char_Length',  'dt' => 9 ,'field' =>'Char_Length','formatter' => function( $d, $row ) {
					  return $d;
					} ),      
					array( 'db' => 'Numeric_Precision',  'dt' => 10,'field' =>'Numeric_Precision','formatter' => function( $d, $row ) {
					  return $d;
					} ),  
					array( 'db' => 'Numeric_Scale',  'dt' => 11 ,'field' =>'Numeric_Scale','formatter' => function( $d, $row ) {
					  return $d;
					} )
				  );
				  
				  $sql_details = array(
				    'user' =>$this->defaultDB->username,
				    'pass' => $this->defaultDB->password,
				    'db'   => $this->defaultDB->database,
				    'host' => $this->defaultDB->hostname
				  );

				  $joinQuery = "";
				  $extraWhere = (isset($_REQUEST['DefaultFilter'])) ? base64_decode($_REQUEST['DefaultFilter']) : "" ;        
				  $groupBy = "";        

				  if( isset($_REQUEST['addedFilter']) )
				  {
				    $aliasFilter_Field = array(
				      );
				     $specialFilter = array(
				          
				        );
				    foreach($_REQUEST['addedFilter'] as $keys=>$values)
				    {
				      $f_fields = "";
				      $f_values = "";
				      $where_operations = "=";
				      if(array_key_exists($keys, $aliasFilter_Field))
				      {
				        $f_fields = $aliasFilter_Field[$keys];
				      }
				      else if( in_array($keys,$specialFilter) )
				      {
				        $f_fields = '';
				      }
				      else
				      {
				        $f_fields = $keys;
				      }

				      if(!is_array($values))
				      {
				        $f_values = "'".$values."'";
				      }
				      else
				      {
				        if( count($values) == count($values, COUNT_RECURSIVE) )
				        { 
				          if( count($values) > 0)
				          {
				            $where_operations = 'in';
				            $f_values = "('".implode("','",$values)."')";
				          }
				        }
				      }

				      $padd_operations = '';
				      if($extraWhere <> '')
				      {
				        $padd_operations = ' AND ';
				       
				      }
				      
				      if( $f_fields <> '' && $f_values <> '')
				      {
				        $extraWhere.= $padd_operations.$f_fields." ".$where_operations." ".$f_values;
				      }
				      
				    }
				    	$dateFilter = "";
				      // Date Filter
				      // if( isset($_POST['addedFilter']['Reference_Created_DateTime_from']) && isset($_POST['addedFilter']['Reference_Created_DateTime_to']) )
				      // {
				      //   $dateFilter = "DATE(`Registration_DateTime`) between '".date("Y-m-d",strtotime($_POST['addedFilter']['Reference_Created_DateTime_from']))."' AND '".date("Y-m-d",strtotime($_POST['addedFilter']['Reference_Created_DateTime_to']))."'";
				      // }
				      // else
				      // {
				      //   if( isset($_POST['addedFilter']['Reference_Created_DateTime_from']) || isset($_POST['addedFilter']['Reference_Created_DateTime_to']) )
				      //   {
				      //       $dateFilter = "DATE(`Registration_DateTime`) = '".date("Y-m-d",strtotime(@$_POST['addedFilter']['Reference_Created_DateTime_from'].@$_POST['addedFilter']['Reference_Created_DateTime_to']))."'";
				      //   }
				      // }

				      if(@$dateFilter <> '')
				      {
				        if($extraWhere <> '')
				        {
				          $extraWhere .= ' AND '.$dateFilter;
				        }
				        else
				        {
				          $extraWhere = $dateFilter;
				        }
				      }
				    unset($_REQUEST['addedFilter']);
				  }       

				  	// Call DataTable Data
				  	echo json_encode(
				          Datatables::simple( $_REQUEST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy )
				      );
			}
		  
		}
	}

	function test_encrypt_decrypt()
	{
		$return = array( 
		    "Status"=>0,
		    "Message"=>""
		  );

		if(  $_SERVER['REQUEST_METHOD'] == "POST" && count($_POST) == 3)
		{
			$return['Status'] = 1;
			$data = '';
			if( $_REQUEST['mode'] == 'E')
			{
				$data = $this->myutilities->aes256_cbc_encrypt($_REQUEST['key'],base64_decode(trim($_REQUEST['data'])));
			}
			else if( $_REQUEST['mode'] == 'D')
			{
				$data = $this->myutilities->aes256_cbc_decrypt( $_REQUEST['key'], base64_decode( base64_decode( trim($_REQUEST['data']) ) ) );
			}

			$return['Message'] = ($data <> "" ) ? base64_encode($data) : "";
		}

		echo json_encode($return);
	}
}