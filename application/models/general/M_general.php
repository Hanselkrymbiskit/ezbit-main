<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\Providers\Qr\EndroidQrCodeProvider;
class M_general extends CI_Model
{
  public $CI;
  public $HFUsers = [1,6,7];
	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	}

	function daemontest()
	{
		return true;
	}

	function ReferenceCheckID_List($referenceid,$val,$label)
	{
			$return = [];
			if( !isset($_SESSION['ReferenceCheckID_List']) )
			{
				$_SESSION['ReferenceCheckID_List'] = [];
			}

			if( !isset($_SESSION['ReferenceCheckID_List'][$referenceid]) )
			{
				$_SESSION['ReferenceCheckID_List'][$referenceid] = [];
			}

			if( !in_array($val, $_SESSION['ReferenceCheckID_List'][$referenceid]) )
			{	
				$refDesc = $this->CI->myutilities->getRef_Desc($referenceid,$val);
				if( $refDesc == '')
				{
					$return['errFlds'] = 'Invalid Field Type Value';
					$return['errFldsMess'] = 'Invalid Value for '.$label.", Value doesn't exist in the reference! "; 
				}
				else
				{
					$_SESSION['ReferenceCheckID_List'][$referenceid][] = $val;
				}
			}

			return $return;
	}

	// Validate Form Fields and Value
	/*
	 valuetype = [text,commaseperated,array,date,time,datetime,numeric]

	$FormDataConfig = [
			'Field' => ['label'=>'',referenceid'=>'','valuetype'=>'text','required'=>true],
	];
	*/
	function validate_formdata($FormDataConfig='',$SubmittedParam = '',$FullFldsCheck=false)
	{
		$return = array(
            'Status'    	 => 0,
            'Message'   	 => 'Failed to Perform Validation Process',
            'ValidateStatus' => 0,
            'ValidateMessage'=> '',
            'ValidateErrors' => '',
        );

		if( !is_array($FormDataConfig) || !is_array($SubmittedParam) )
		{
			goto exit_validation;
		}

		$errFldsCnt = 0;
		$errFlds = [];
		$errFldsMess = [];
		$sParam = [];

		foreach( $FormDataConfig as $fld => $param )
		{
			if(!$FullFldsCheck && $errFldsCnt > 0)
			{
				goto exitLoops;
			}

			if( array_key_exists($fld,$SubmittedParam) )
			{
				$sParam[$fld] = @$SubmittedParam[$fld];
				$sParam[$fld] = ( is_array($sParam[$fld]) ) ? $sParam[$fld] : (($sParam[$fld]) ? trim($sParam[$fld]) : $sParam[$fld]) ;
				$invalidFldType = false;
				if($param['required'] && $sParam[$fld] == '' )
				{
					$errFldsCnt++;
					$errFlds[$fld] = 'Invalid Value';
					$errFldsMess[] = 'Invalid Value for '.$param['label'].', this is a required field!'; 

					goto Exit_Validation;
				}

				Proceed_Validation:

				if( @$param['referenceid'] <> '' )
				{
					if( strtolower($param['valuetype']) == 'array' || strtolower($param['valuetype']) == 'commaseperated')
					{
						
						$tmpVal = ( !is_array($sParam[$fld]) ) ? explode(",",$sParam[$fld]) : $sParam[$fld];

						if( count($tmpVal) > 0 )
						{
							$invalidCheckResultCnt = 0;
							$invalidCheckResultVal = [];
							foreach( $sParam[$fld] as $pval )
							{
								$chkReference_Result = $this->ReferenceCheckID_List($param['referenceid'],$pval,$param['label']);
								if(count($chkReference_Result) > 0)
								{
									$invalidCheckResultCnt++;
									$invalidCheckResultVal[] = $pval;
								}
							}

							if($invalidCheckResultCnt > 0)
							{
								$errFldsCnt++;
								$errFlds[$fld] = 'Invalid Field Type Value';
								$errFldsMess[] = 'Invalid Value for ['.implode(", ",$invalidCheckResultVal)."], Value doesn't exist in the reference! "; 
								goto Exit_Validation;
							}
						}
					}
					else
					{
						if( trim($sParam[$fld]) <> '' )
						{

							$chkReference_Result = $this->ReferenceCheckID_List($param['referenceid'],$sParam[$fld],$param['label']);
							if(count($chkReference_Result) > 0)
							{
								$errFldsCnt++;
								$errFlds[$fld] = $chkReference_Result['errFlds']; //'Invalid Field Type Value';
								$errFldsMess[] = $chkReference_Result['errFldsMess']; // 'Invalid Value for '.$param['label'].", Value doesn't exist in the reference! "; 
								goto Exit_Validation;
							}

						}
					}
				}

				if( $sParam[$fld] <> '')
				{	
					$sParam[$fld] = $this->CI->myutilities->xssCleaner($sParam[$fld]);
					switch( strtolower($param['valuetype']) )
					{
						case "date":    
              $sParam[$fld] = (trim($SubmittedParam[$fld]) <> '') ? date('Y-m-d',strtotime($SubmittedParam[$fld])) : '';
              if(!$this->CI->myutilities->isDate($sParam[$fld]))
              {
                $sParam[$fld] = '';
                $invalidFldType = true;
                $invalidFldTypeMess = 'Invalid Value for '.$param['label'].', this field require date type value!';
						  }
            break;
            case "time":
            	$sParam[$fld] = ($SubmittedParam[$fld] <> '') ? date('H:i:s',strtotime($SubmittedParam[$fld])) : '';
            	if( $sParam[$fld] == "" )
	            {
	            	$invalidFldType = true;
	                $invalidFldTypeMess = 'Invalid Value for '.$param['label'].', this field require time value!';
	            }
            break;
            case "datetime":
              $sParam[$fld]= ($SubmittedParam[$fld] <> '') ? date('Y-m-d H:i:s',strtotime($SubmittedParam[$fld])) : '';
              if(!$this->CI->myutilities->isDate($sParam[$fld]))
              {
                $sParam[$fld] = '';
                $invalidFldType = true;
                $invalidFldTypeMess = 'Invalid Value for '.$param['label'].', this field require date/time type value!';
              }
            break;
            case "array":
	            if( !is_array($sParam[$fld]) )
	            {
	            	$sParam[$fld] = '';
                $invalidFldType = true;
                $invalidFldTypeMess = 'Invalid Value for '.$param['label'].', this field require array value!';
	            }
            break;
						case "commaseperated":
	            if( is_array($sParam[$fld]) && count($sParam[$fld]) == 0 )
	            {
	            	$sParam[$fld] = '';
	                $invalidFldType = true;
	                $invalidFldTypeMess = 'Invalid Value for '.$param['label'].', this field require array or comma seperated string value!';
	            }
							else
							{
								$sParam[$fld] = (is_array($sParam[$fld])) ? implode(",",$sParam[$fld]) : $sParam[$fld] ;
							}
            break;
            case "numeric":
              	if( !is_numeric($sParam[$fld]) )
	            {
	            	$sParam[$fld] = '';
	                $invalidFldType = true;
	                $invalidFldTypeMess = 'Invalid Value for '.$param['label'].', this field require numeric value!';
	            }
            break;
					}
				}

				if($invalidFldType)
				{
					$errFldsCnt++;
					$errFlds[$fld] = 'Invalid Field Type Value';
					$errFldsMess[] = $invalidFldTypeMess;
				}

				Exit_Validation:
				
			}
			else
			{
				$errFldsCnt++;
				$errFlds[$fld]=((array_key_exists($fld,$errFlds)) ? $errFlds[$fld].',' : '').'Invalid Field';
				$errFldsMess[] = 'Invalid Field '.$param['label'].'!';
			}
		}

		exitLoops:
		if($errFldsCnt == 0)
		{
			$return = array(
	            'Status'    	 => 1,
	            'Message'   	 => 'Data Validation Successfully Completed',
	            'ValidateStatus' => 1,
	            'ValidateMessage' => 'Form Data Passed the Required Conditions',
	            'ValidateFields' => $sParam,
	            'ValidateErrors' => '',
	        );
		}
		else
		{
			$return = array(
	            'Status'    	 => 1,
	            'Message'   	 => 'Data Validation Successfully Completed',
	            'ValidateStatus' => 0,
	            'ValidateMessage' => 'Form Data Failed the Required Conditions',
	            'ValidateErrors' => ['Fields' => $errFlds, 'Description' => $errFldsMess ]
	        );
		}

		exit_validation:
    return $return;
	}


	function verifyTOTP($secret,$authcode = '')
	{
		$tfa = new TwoFactorAuth('PhilHealth-eZBits',6,30,'sha1',new EndroidQrCodeProvider());
		// log_message("error",$secret." = ".$authcode);
		$secret = (base64_decode($secret)) ? base64_decode($secret) : $secret;
		// log_message("error",$secret." = ".$authcode);
    $verify_result = $tfa->verifyCode($secret, $authcode);
    return $verify_result;
	}

	function getZBenFacilityProfile($instCode)
	{
		$return = $this->sqlhelper->{$this->conProCode()}->select("ref_zben_facility")->where("inst_code = '".$instCode."'")->row();
		return @$return['Data'];
	}

	function conProCode()
	{
		$procode = ( @$this->userregistrationinfo['ProCode'] <> '' ) ? (int) $this->userregistrationinfo['ProCode'] : 0;
		switch ($procode) 
		{
			case 1: //NCR
			case 2: //NCR
			case 3: //NCR
				$concode = 'remote1';
			break;
			case 4: //CAR
				$concode = 'remote17';
			break;
			case 5: //PRO-1
				$concode = 'remote2';
			break;
			case 6: //PRO-2
				$concode = 'remote3';
			break;
			case 7: //PRO-3A
			case 21: //PRO-3B
				$concode = 'remote4';
			break;
			case 8: //PRO-4A
				$concode = 'remote5';
			break;
			case 9: //PRO-4B
				$concode = 'remote6';
			break;
			case 10: //PRO-05
				$concode = 'remote7';
			break;
			case 11: //PRO-06
				$concode = 'remote8';
			break;
			case 12: //PRO-07
				$concode = 'remote9';
			break;
			case 13: //PRO-08
				$concode = 'remote10';
			break;
			case 14: //PRO-09
				$concode = 'remote11';
			break;
			case 15: //PRO-10
				$concode = 'remote12';
			break;
			case 16: //PRO-11
				$concode = 'remote13';
			break;
			case 17: //PRO-12
				$concode = 'remote14';
			break;
			case 18: //PRO-CARAGA
				$concode = 'remote15';
			break;
			case 20: //PRO-BARFMM
				$concode = 'remote16';
			break;
				
			default:
				$concode = 'local';
			break;
		}

		return $concode;
	}

	function getFacilityZBenServices($instCode,$preauthType = false)
	{

		if($preauthType)
		{
			$sql = "
				select 
					a.*,
					b.code
				from ref_zben_facility_services a 
				left join ref_primarycondition b on b.mapcode = a.zben_services 
				where a.inst_code = '".$instCode."'
			";

			$return = $this->sqlhelper->{$this->conProCode()}->sql($sql)->result();
			if($return['Count'] > 0)
			{
				$primaryC = [];
				foreach($return['Data'] as $dRow => $dCol)
				{
					if( !in_array($dCol['code'],$primaryC) ){ $primaryC[] = $dCol['code']; } 
				}

				$preauthlist = $this->sqlhelper->{$this->m_general->conProCode()}->select('ref_primarycondition_preauthlist')->where("primarycondition_code in ('".(implode("','",$primaryC))."')")->result();
				$preAuth = [];
				foreach($preauthlist['Data'] as $pr => $pc)
				{
					$preAuth[] = $pc['code'];
				}

				$rtn = $preAuth;
			}
		}
		else
		{
			$return = $this->sqlhelper->{$this->conProCode()}->select("ref_zben_facility_services")->where("inst_code = '".$instCode."'")->result();
			$rtn = @$return['Data'];
		}


		return @$rtn;
	}
}
