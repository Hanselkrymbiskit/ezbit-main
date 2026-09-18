<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use Ramsey\Uuid\Uuid;
use PHPCypherFile\PHPCypherFile;
class M_register extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->load->model('api/m_api');
	}
  	
	function insertRegistrationData($Data)
	{
		$return = array('Status' => 0,'Message' => 'Invalid Form Data');
		if( is_array($Data) )
		{
			// log_message("error",$Data);
			try{
				// Validation Option
				// 1 : Check for Email Duplicate
				// 2 : Check for User Name Duplicate
				// 3 : Check for Duplicate Account Profile
				//  ------------------------------------
				if( count($Data) > 0)
				{
				  $xsscleanPost = $this->CI->xssCleaner($Data); 
				  $Data = $xsscleanPost;
				}

				$emailDup =$this->CI->myutilities->EmailExists($Data['eaddrs']);
				if($emailDup)
				{
					$return['Message'] = 'Email Address already exists!';
					goto exit_function_here;
				}

				$usernameDup = $this->CI->users->is_username_available($Data['unme']);
				if( $this->CI->myutilities->isUserNameExists($Data['unme']) || !$usernameDup )
				{
					$return['Message'] = 'User Name already exists!';
					goto exit_function_here;
				}

				$previousUserID = $this->CI->myutilities->userProfileExists($Data);
                if($previousUserID)
                {
                    $Data['accountprofileexists'] = 'Y';
                    $Data['accountuserid'] = $previousUserID;
                }

                $Data['register_datetime'] = date("Y-m-d H:i:s");

                try{
                	unset($Data['confirmpsswrd']);

					$SIGNUP_AutoApproved = $this->system_settings['RegistrationAutoApproved'];
					if( $SIGNUP_AutoApproved == 1 )
					{
						$Data['action_by']         = 0;
						$Data['action_datetime']   = $Data['register_datetime'];
						$Data['register_status']   = 2;
					}

					$Data['emailaddress'] = $Data['eaddrs'];
					unset($Data['eaddrs']);
					$Data['username'] = $Data['unme'];
					unset($Data['unme']);
                	$Data['password'] = $this->CI->encryption->encrypt($Data['psswrd']);
                	unset($Data['psswrd']);
					$Data['user_type'] = (@$Data['user_type'] == '' ) ? 4 : @$Data['user_type'];

					if( (int) $Data['user_type'] < 4 )
					{
						$Data['user_classification'] = '';
					}
 

					$toEncrypt = ['Lastname','Firstname','Middlename','security_answer'];
					foreach( $Data as $ek => $ev)
					{
						if( in_array($ek, $toEncrypt) )
						{
							if( $ev <> '' )
							{
								$Data[$ek] = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$ev,'MCrypt','aes-128','ecb');
							}
						}
					}

                	$insertResult = $this->CI->sqlhelper->local->insert('user_register')->ex_insert($Data)->run();  	

                	if($insertResult['ErrorCode'] == "")
                	{
						$tmpDateTime = (@$Data['action_datetime'] == '') ? $Data['register_datetime'] : $Data['action_datetime'];

						$registrationID = date("Ymdhi").str_pad($insertResult['Data'],'10',0,STR_PAD_LEFT);
						$activationlinkcode = base64_encode( base64_encode($registrationID).':'.base64_encode(@$tmpDateTime) ); 
						$uData = [
							'registrationID' => @$registrationID,
							'activationlink' => ($SIGNUP_AutoApproved) ? $activationlinkcode : '',
							'activationlinkdatetime' => ($SIGNUP_AutoApproved) ? $Data['action_datetime'] : @$tmpDateTime
						];
						
						$updateResult = $this->CI->sqlhelper->local->update('user_register')->ex_update($uData)->where("DataID = '".$insertResult['Data']."'")->run();  	
                		$return = array('Status' => 1,'Message' => 'Success','RegistrationID'=>$registrationID, 'activationlinkcode' => $uData['activationlink'] );

						// Check if Auto Approved is ON
						if( $SIGNUP_AutoApproved == 1 )
						{
							$MoveResult = $this->movetouseraccount($registrationID);
							if(!$MoveResult)
							{
								// Delete this Registration
								$delRegistration = $this->CI->sqlhelper->delete('user_register')->where("DataID = '".$insertResult['Data']."'")->run();
								$return = array('Status' => 0,'Message' => 'Failed to Process Submitted Form Data');
								goto exit_function_here;
							}
						}
                	}
                	else
                	{
                		$return['Message'] = 'Failed to Process Submitted Form!, Please try again later';
                		goto exit_function_here;
                	}
                }catch (PDOException $e) {
					log_message("error","Model : m_register : Error Inserting Registration Data [ ".$e->getMessage()." ] : ".json_encode($Data));
					$return = array('Status' => 0,'Message' => 'Failed to Process Request, Please try again!');
				} catch (Exception $e) {
					log_message("error","MModel : m_register : Error Inserting Registration Data [ ".$e->getMessage()." ] : ".json_encode($Data));
					$return = array('Status' => 0,'Message' => 'Failed to Process Request, Please try again!');
				}

			}catch(Exeption $e)
			{
				log_message("error","MModel : m_register : Error Inserting Registration Data [ ".$e->getMessage()." ] : ".json_encode($Data));
				$return = array('Status' => 0,'Message' => 'Failed to Process Request, Please try again!');
			}
			
		}
	
		exit_function_here:
		return $return;
	}

	function getRegistrationDataviaDataID($DataID)
	{
		$regData = $this->CI->sqlhelper->local->select("user_register")->where("DataID = ".$DataID)->ex_select('','','1')->row();
		return ($regData['Count'] > 0) ? $regData['Data'] : '';
	}

	function getRegistrationData($registrationID,$activationlinkcode = false)
	{
		if($activationlinkcode)
		{
			$regData = $this->CI->sqlhelper->local->select("user_register")->where("activationlink = '".$registrationID."'")->ex_select('','','1')->row();
		}
		else
		{
			$regData = $this->CI->sqlhelper->local->select("user_register")->where("registrationID = '".$registrationID."'")->ex_select('','','1')->row();
		}
		
		return ($regData['Count'] > 0) ? $regData['Data'] : '';
	}

 	function getStatistics()
 	{
		if( (int) $this->CI->userclassification == 10)
        {
            $DefaultFilter = " user_classification < ".((int) $this->userclassification);
        }

        if( (int) $this->CI->userclassification == 3)
        {
            $DefaultFilter = " user_classification in (4,5,6) and Region = '".$this->CI->userregistrationinfo['Region']."'";
        }


 		$getDataSQL = "
			select 
			COUNT(CASE WHEN a.register_status = 1 THEN 1 END) as '1',
			COUNT(CASE WHEN a.register_status = 2 THEN 1 END) as '2',
			COUNT(CASE WHEN a.register_status = 3 THEN 1 END) as '3',
			count(*) as '4'
			from user_register a ".((@$DefaultFilter <> '') ? " where ".$DefaultFilter : "")."
 		";


 		$Stats = $this->CI->sqlhelper->local->sql($getDataSQL)->row();
 		// log_message("error",$Stats);
 		return ($Stats['Count'] > 0 ) ? $Stats['Data'] : array() ;
 	} 

 	function movetouseraccount($registrationID)
 	{
 		$getRegDetails = $this->getRegistrationData($registrationID);
 		if( is_array($getRegDetails) )
 		{
 			if( $getRegDetails['register_status'] == 2 )
 			{
 				// Prepare Insert to User Table
 				$encryptPassword = '';
 				$hasher = new PasswordHash($this->CI->config->item('phpass_hash_strength', 'tank_auth'),$this->CI->config->item('phpass_hash_portable', 'tank_auth'));
 				$hashed_password = $hasher->HashPassword($this->CI->encryption->decrypt($getRegDetails['password']));
 
 				$user_table = array(
	 				'usertype'			=> (@$getRegDetails['user_type'] == '') ? 4 : $getRegDetails['user_type'],
					'username'			=> $getRegDetails['username'],
					'password'			=> $hashed_password,
					'passwordlegacy'	=> '',
					'email'				=> $getRegDetails['emailaddress'],
					'activated'			=> 0,
					'expirydate'		=> (date('Y')+1).'-12-31',
					'created_datetime'  => date('Y-m-d H:i:s'),
					'created_by'  		=> (@$this->CI->userid == "") ? 0 : @$this->CI->userid
	 			);

	 			$insertUsers = $this->CI->sqlhelper->local->insert("users")->ex_insert($user_table)->run();
	 			
	 			if($insertUsers['ErrorCode'] == '' )
	 			{
	 				$userid = $insertUsers['Data'];
	 				try
		 			{
	 					$updateRegistration = $this->CI->sqlhelper->local->update("user_register")->ex_update("user_id = ".$userid)->where("DataID = ".$getRegDetails['DataID'])->run();

	 					repeat_keypair:

	 					$ws_uuid = Uuid::uuid4();
	 					$ws_uuid = $ws_uuid->toString();
	 					$e_uuid = Uuid::uuid4();
	 					$e_uuid = $e_uuid->toString();

	 					$User_WS_Key = $ws_uuid;
	 					$User_E_Key = base64_encode( $this->CI->encryption->create_key(16) ); //$e_uuid;

	 					$chkKey = $this->CI->sqlhelper->local->select("user_profiles")->where(" User_WS_Key = '".$User_WS_Key."' ")->result();
	 					if($chkKey['Count'] > 0)
	 					{
	 						goto repeat_keypair;
	 					}

	 					$toDEncrypt = ['Lastname','Firstname','Middlename'];
	 					$rData = [];
						foreach( $getRegDetails as $ek => $ev)
		 				{
							if( in_array($ek, $toDEncrypt) )
							{
								if( $ev <> '' )
								{
									$rData[$ek] = $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$ev,'MCrypt','aes-128','ecb');
								}
							}
						}

						$CompleteName = ucwords(strtolower($rData['Firstname'])).' '.ucwords(strtolower($rData['Lastname'])).' '.( (@$getRegDetails['Suffixname'] =='NA') ? '' : ucfirst(strtolower($getRegDetails['Suffixname'])) );
						$CompleteName = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$CompleteName,'MCrypt','aes-128','ecb');

						$security_answer = (@$getRegDetails['security_answer'] == '') ? $getRegDetails['emailaddress'] : $getRegDetails['security_answer'];

			 			$userprofile_table = array(
				 			'user_id' 					=> $userid,
							'registration_id' 			=> $getRegDetails['DataID'],
							'user_classification' 		=> @$getRegDetails['user_classification'],
							'emailaddress' 				=> $getRegDetails['emailaddress'],
							'CompleteName' 				=> $CompleteName,
							'Lastname' 					=> $getRegDetails['Lastname'],
							'Firstname' 				=> $getRegDetails['Firstname'],
							'Middlename' 				=> $getRegDetails['Middlename'],
							'Suffixname' 				=> $getRegDetails['Suffixname'],
							'DateofBirth' 				=> $getRegDetails['DateofBirth'],
							'Sex' 						=> $getRegDetails['Sex'],
							'Mobile_no' 				=> $getRegDetails['Mobile_no'],
							'security_question' 		=> (@$getRegDetails['security_question'] == '') ? 20 : $getRegDetails['security_question'],

							'security_question_custom' 	=> (@$getRegDetails['security_question'] == '' && @$getRegDetails['security_question_custom'] == '') ? 'Please enter your registered email address!' : @$getRegDetails['security_question_custom'],

							'security_answer' 			=> @$security_answer,
							'User_WS_Key' 				=> $User_WS_Key,
							'User_E_Key' 				=> $User_E_Key,
							'created_by' 				=> $user_table['created_by'],
							'created_datetime' 			=> $user_table['created_datetime'],
						);

			 			try
			 			{
			 				try
			 				{
								$res = openssl_pkey_new($this->openssl_config);
								if( openssl_pkey_export($res, $privkey,$User_E_Key,$this->openssl_config) );  
								{ 
								    // Get details of public key 
								    $pubkey = openssl_pkey_get_details($res); 
								    $pubkey = $pubkey["key"]; 
								    $privkey = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$privkey,'MCrypt','aes-128','ecb');
								    $pubkey = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$pubkey,'MCrypt','aes-128','ecb');
								    $userprofile_table['oPublicKey'] = @$pubkey;
								    $userprofile_table['oPrivateKey'] = @$privkey;
								} 

							}catch (PDOException $e) {
								return false;
							} catch (Exception $e) {
								return false;
							}

			 				$insertUserProfile = $this->CI->sqlhelper->local->insert("user_profiles")->ex_insert($userprofile_table)->run();
				 			if($insertUserProfile['ErrorCode'] <> '')
				 			{
				 				// Delete User Data
				 				$this->CI->sqlhelper->local->delete("users")->where("id = ".$userid)->run();
				 				return false;
				 			}

				 			// Insert to REST Keys
				 			$restData = [
				 				'user_id'		=> $userid,
								'api_key'		=> $User_WS_Key,
								'level'			=> 1,
								'ignore_limits'	=> 0,
								'is_private_key'=> 0
				 			];

				 			$insertREST = $this->CI->sqlhelper->local->insert("rest_api_keys")->ex_insert($restData)->run();
				 			if($insertREST['ErrorCode'] == '')
				 			{

								if(@$user_table['usertype'] < 3)
								{
									// $isql = "insert into rest_api_access (`key`, `all_access`, `controller`) VALUES ('".$User_WS_Key."', 1, 'fhir/StructureDefinition')";
									// $isql = "insert into rest_api_access (`key`, `all_access`, `controller`) VALUES ('".$User_WS_Key."', 1, 'fhir/ValueSet')";
									// $isql = "insert into rest_api_access (`key`, `all_access`, `controller`) VALUES ('".$User_WS_Key."', 1, 'fhir/CodeSystem')";
								  	// $rsql = $this->CI->sqlhelper->local->sql($isql)->run();
								}
								else
								{
									// Add Access
									$excludeAPIController = [];
					 				$fhrR = $this->CI->sqlhelper->local->select("rest_api_controller")->result();
					 				if( $fhrR['Count'] > 0 )
					 				{
					 					foreach($fhrR['Data'] as $rrr=>$ccc)
										{
										  if( !in_array($ccc['id'],$excludeAPIController) )
										  {
										  	$isql = "insert into rest_api_access (`key`, `all_access`, `controller`) VALUES ('".$User_WS_Key."', 1, '".$ccc['controller']."')";
										  	$rsql = $this->CI->sqlhelper->local->sql($isql)->run();
										  }
										  
										}
					 				}
									
								}
				 			}

				 			return true;

						}catch (PDOException $e) {
							return false;
						} catch (Exception $e) {
							return false;
						}
	 				}catch (PDOException $e) {
						return false;
					} catch (Exception $e) {
						return false;
					}
	 			}
	 			else
	 			{
	 				return false;
	 			}

	 			return true;
 			}
 			
 		}

 		return false;
 	}

	
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */