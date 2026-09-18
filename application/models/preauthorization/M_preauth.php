<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use setasign\Fpdi\Tcpdf\Fpdi;
use PHPCypherFile\PHPCypherFile;
class M_preauth extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);

		$this->load->model('preauthorization/m_preauth_form_flds');
	  
	}
	
	function getSelectionCriteria($illnessType)
	{
		$sc = $this->myutilities->getRef_Desc(200,$illnessType,false,'selectioncriteria');
		return (@$sc == 'Y') ? true : false;
	}

	function vFormData($requiredFld,$submittedFld,$eFlds = false)
	{
		$return = [
			'Status' => 0,
			'Message'=> ''
		];

		$errCnt = 0;
		$errFlds = [];
		$errDesc = [];
		foreach( $requiredFld as $rk => $rv)
		{
			$rr = $this->m_general->validate_formdata($rv,$submittedFld[$rk],false);
			if($rr['Status'] == 1)
			{

				$errCnt = $errCnt + ((is_array($rr['ValidateErrors']) && count($rr['ValidateErrors']['Fields'])) ? count($rr['ValidateErrors']['Fields']) : 0);
				$errFlds = array_merge($errFlds, ((is_array($rr['ValidateErrors']) && count($rr['ValidateErrors']['Fields'])) ? $rr['ValidateErrors']['Fields'] : []) );
				$errDesc = array_merge($errDesc, ((is_array($rr['ValidateErrors']) && count($rr['ValidateErrors']['Description'])) ? $rr['ValidateErrors']['Description'] : []) );
			}
		}

		$return['Message'] = ($errCnt > 0) ? (($eFlds) ? implode(",<br>",$errFlds) : implode(",<br>",$errDesc)) : '';
		if( $errCnt == 0 )
		{
			$return['Status'] = 1;
		}

		return $return;
	}


	function getRequiredData($illnessType = '',$FSCriteria='',$update=false,$submittedFld='')
	{
		try
		{
			// Pre-Auth : Patient and Member Information
			$FormData = [
				'preauth_type'=>['label'=>'Pre-Authorization Form Type','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'case_no'=>['label'=>'Pre-Authorization Case No.','required'=>$update,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'healthfacility_area'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'healthfacility_pro'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'healthfacility_code'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'patient_lastname'=>['label'=>'Patient Last Name','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
				'patient_firstname'=>['label'=>'Patient First Name','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
				'patient_middlename'=>['label'=>'Patient Middle Name','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
				'patient_suffix'=>['label'=>'Patient Suffix','required'=>true,'valuetype'=>'text','referenceid'=>'24','encrypted'=>false],
				'patient_sex'=>['label'=>'Patient Sex','required'=>true,'valuetype'=>'text','referenceid'=>'23','encrypted'=>false],
				'patient_dateofbirth'=>['label'=>'Patient Date of Birth','required'=>true,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
				'patient_age'=>['label'=>'Patient Age','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'patient_philhealthno'=>['label'=>'Patient PhilHealth ID No.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
				'patient_is_member'=>['label'=>'Same as Patient','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'member_lastname'=>['label'=>'Member Last Name','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
				'member_firstname'=>['label'=>'Member First Name','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
				'member_middlename'=>['label'=>'Member Middle Name','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
				'member_suffix'=>['label'=>'Member Suffix','required'=>true,'valuetype'=>'text','referenceid'=>'24','encrypted'=>false],
				'member_sex'=>['label'=>'Member Sex','required'=>true,'valuetype'=>'text','referenceid'=>'23','encrypted'=>false],
				'member_dateofbirth'=>['label'=>'Member Date of Birth','required'=>true,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
				'member_age'=>['label'=>'Member Age','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'member_philhealthno'=>['label'=>'Member PhilHealth ID No.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
				// 'member_validated_pin'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				// 'member_validated_pin_datetime'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'fulfilled_selection_criteria'=>['label'=>'Fulfilled selection Criteria','required'=>$FSCriteria,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'fulfilled_selection_criteria_reason'=>['label'=>'Fulfilled selection Criteria - Reason','required'=>( ($FSCriteria && @$submittedFld['patientinfo']['fulfilled_selection_criteria'] == 'N' ) ? true : false),'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
			];


			$empowerFormData = [
				'a_member_permanent_address_st' => ['label'=>'PERMANENT ADDRESS Street,Blk,lot,subdivision,bldg','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'a_member_permanent_address_region' => ['label'=>'PERMANENT ADDRESS Region','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'a_member_permanent_address_province' => ['label'=>'PERMANENT ADDRESS Province','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'a_member_permanent_address_city' => ['label'=>'PERMANENT ADDRESS City','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'a_member_telephoneno' => ['label'=>'Member Telephone Number','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'a_member_mobileno' => ['label'=>'Mobile Number','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'a_member_emailaddress' => ['label'=>'Email Address','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'b_1' => ['label'=>'Description of condition','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'b_2' => ['label'=>'Applicable Treatment Plan agreed upon with healthcare provider','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'b_3' => ['label'=>'Applicable alternative Treatment Plan agreed upon with health care provider','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'c_1_date' => ['label'=>'Date of initial admission to HF or consult a (mm/dd/yyyy)','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'c_2_date' => ['label'=>'Tentative Date/s of succeeding admission to HF or consult b (mm/dd/yyyy)','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'c_3_date' => ['label'=>'Tentative Date/s of follow-up visit/s c (mm/dd/yyyy)','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_1' => ['label'=>'My health care provider explained the nature of my condition/disability','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_2' => ['label'=>'My health care provider explained the treatment options/intervention','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_3' => ['label'=>'The possible side effects/adverse effects of treatment/intervention were explained to me.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_4' => ['label'=>'My health care provider explained the mandatory services and other services required for the treatment of my condition/intervention.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_5' => ['label'=>'I am satisfied with the explanation given to me by my health care provider','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_6' => ['label'=>'I have been fully informed that I will be cared for by all the pertinent medical and allied specialties, as needed, present in the PhilHealth contracted HF of my choice and that preferring another contracted HF for the said specialized care will not affect my treatment in any way.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_7' => ['label'=>'My health care provider explained the importance of adhering to my treatment plan/intervention. This includes completing the course of treatment/intervention in the contracted HF where my treatment/intervention was initiated.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_8' => ['label'=>'My health care provider gave me the schedule/s of my follow-up visit/s.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_9' => ['label'=>'My health care provider gave me information where to go for financial and other means of support, when needed.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_10' => ['label'=>'I have been furnished by my health care provider with a list of other contracted HFs for the specialized care of my condition.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11' => ['label'=>'I have been fully informed by my health care provider of the PhilHealth membership policies and benefit availment on the Z Benefits:','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11_a' => ['label'=>'a.  I fulfill all selections criteria for my condition/disability.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11_b' => ['label'=>'b.  The “no balance billing” (NBB) policy was explained to me.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11_c' => ['label'=>'c.  I understand that I may choose not to avail of the NBB and may be charged out of pocket expenses','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11_d' => ['label'=>'d.  In case I choose to upgrade my room accommodation or avail of additional services that are not included in the benefit package, I understand that I can no longer demand the hospital to grant me the privilege given to NBB patients (that is, no out of pocket payment upon discharge from the hospital)','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11_e' => ['label'=>'e.  I opt out of the NBB policy of PhilHealth and I am willing to pay on top of my PhilHealth benefits','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11_f' => ['label'=>'f.  I agree to pay as mush as PHP ______* for the following','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11_f_php' => ['label'=>'I agree to pay as mush as PHP','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11_f_following' => ['label'=>'additional services, specify','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11_g' => ['label'=>'g.  I understand that there may be an additional payment on top of my PhilHealth benefits.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11_h' => ['label'=>'h.  I agree to pay as mush as PHP ______* as additional payment on top of my PhilHealth benefits.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_11_h_php' => ['label'=>'I agree to pay as mush as PHP','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'd_12' => ['label'=>'Only five (5) days shall be deducted from the 45 confinement days benefit limit per year for the duration of my treatment/intervention under the Z Benefits.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'e_1' => ['label'=>'I understand that I am responsible for adhering to my treatment schedule.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'e_2' => ['label'=>'I understand that adherence to my treatment schedule is important in terms of clinical outcomes and a pre-requisite to the full entitlement of the Z benefits.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'e_3' => ['label'=>'I understand that it is my responsibility to follow and comply with all the policies and procedures of PhilHealth and the health care provider in order to avail of the full Z benefit package.  In the event that I fail to comply with policies and procedures of PhilHealth and the health care provider, I waive the privilege of availing the Z benefits.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'z_coordinator_lastname' => ['label'=>'Z Coordinator Last Name','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
				'z_coordinator_firstname' => ['label'=>'Z Coordinator First Name','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
				'z_coordinator_middlename' => ['label'=>'Z Coordinator Middle Name','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
				'z_coordinator_suffix' => ['label'=>'Z Coordinator  Suffix','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'z_coordinator_telephoneno' => ['label'=>'Z Coordinator Telephone No.','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'z_coordinator_mobileno' => ['label'=>'Z Coordinator Mobile No.','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'z_coordinator_emailaddress' => ['label'=>'Z Coordinator  Email Address','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
			];

			$illnessType = str_pad( (int) $illnessType, 2, '0',STR_PAD_LEFT);

			if( $FSCriteria == true && $submittedFld['patientinfo']['fulfilled_selection_criteria'] == 'Y' )
			{
				$othFormData = $this->m_preauth_form_flds->{'preauth_'.$illnessType}($submittedFld);
			// $othFormData = $this->{'preauth_'.$illnessType}($submittedFld);
			}
			
			// log_message("error",$othFormData);
			$othFormData = (is_array(@$othFormData) && count(@$othFormData) > 0) ? $othFormData : [];

			$preFormData = [
				'patientinfo' => $FormData,
				'memberempowerment' => $empowerFormData,
			];

			if($FSCriteria && count($othFormData) > 0 && $submittedFld['patientinfo']['fulfilled_selection_criteria'] == 'N')
			{
				foreach($othFormData as $dky => $dkyV)
				{
					foreach($dkyV as $ek => $ev)
					{
						$othFormData[$dky][$ek]['required'] = false;
					}
				}
			}

			$fpreformdata = array_merge($preFormData,@$othFormData);
			// log_message("error",$fpreformdata);
		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}

		return @$fpreformdata;
	}

	function ProcessPreAuth($requiredFld,$submittedFld,$SubmittedFiles,$update = false,$cdata='')
	{
  		$return = ['Status'=>0,'Message'=>'Failed to Process Submitted Pre-Authorization Form'];

  	try
		{

			if($update && $submittedFld['patientinfo']['case_no'])
			{
				$hdata = base64_encode($this->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],json_encode($cdata,JSON_UNESCAPED_SLASHES),'MCrypt','aes-128','ecb'));

				$ihdata = [
					'preauth_rowid'      => @$cdata['patientinfo']['rowid'],
					'preauth_type' 		=> $submittedFld['patientinfo']['preauth_type'],
					'case_no' 				=> $submittedFld['patientinfo']['case_no'],
					'dataarchives' 		=> @$hdata,
					'created_by' 			=> $this->userid,
					'created_datetime' 	=> date('Y-m-d H:i:s'),
				];

				$rihdata = $this->sqlhelper->{$this->m_general->conProCode()}->insert('trans_preauth_form_history')->ex_insert($ihdata)->run();
				$submittedFld['patientinfo']['remarks'] = (@$cdata['patientinfo']['remarks'] <> '' ) ? base64_decode(@$cdata['patientinfo']['remarks']) : '';
				$umfdResult = $this->iPreAuthForm($requiredFld['patientinfo'],$submittedFld['patientinfo'],$update);
				if($umfdResult['Status'] == 1)
				{

					if( @$submittedFld['patientinfo']['fulfilled_selection_criteria'] == 'Y' )
					{
						$chkResult = $this->iPreAuthForm_Checklist($requiredFld['checklist'],$submittedFld['checklist'],$submittedFld['patientinfo']['case_no'],$submittedFld['patientinfo']['preauth_type'],$update);
						$reqResult = $this->iPreAuthForm_Request($requiredFld['request'],$submittedFld['request'],$submittedFld['patientinfo']['case_no'],$submittedFld['patientinfo']['preauth_type'],$update);
					}
					else
					{
						$chkResult['Status'] = 1;
						$reqResult['Status'] = 1;
					}

					$empResult = $this->iPreAuthForm_Empowerment($requiredFld['memberempowerment'],$submittedFld['memberempowerment'],$submittedFld['patientinfo']['case_no'],$submittedFld['patientinfo']['preauth_type'],$update);
					
					if( count($submittedFld['attachment']) > 0 )
					{
						foreach($submittedFld['attachment'] as $fid => $fv)
						{
							$upid = str_replace('Replacement_','',$fid);
							if($fv == 'Y')
							{
								$ufilerecord = [
									'updated_by'			=> $this->userid,
									'updated_datetime'	=> $ihdata['created_datetime'],
									'deleted_by'			=> $this->userid,
									'deleted_datetime'	=> $ihdata['created_datetime'],
								];

								$rufileResult = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_preauth_form_attachments')->ex_update($ufilerecord)->where(" case_no = '".$submittedFld['patientinfo']['case_no']."' and uploadid = '".$upid."' ")->run();
							}
						}
					}

					if(is_array($SubmittedFiles) && count($SubmittedFiles) > 0)
					{
						$attResult = $this->iAttachments($submittedFld['patientinfo']['case_no'],$SubmittedFiles,$update);
						$return['Message'] = $attResult['Message'];
					}

					if($chkResult['Status'] == 0 || $reqResult['Status'] == 0 || $empResult['Status'] == 0 || @$attResult['Status'] == 0)
					{
						goto exit_here;
					}

					$return['Status'] = 1;
					$return['Message'] = '<h3>Pre-Authorization Form Successfully Re-Submitted!</h3><h3 class="text-center mb-0 mt-10 border border-success"><small class="text-success"><b>Pre-Authorization Case No.</b></small><br><b>'.$submittedFld['patientinfo']['case_no'].'</b></h3>';
					$return['CaseNo'] = $submittedFld['patientinfo']['case_no'];

					if($ihdata['created_datetime'] && $cdata['patientinfo']['preauth_status_datetime'])
					{
						$d2 = new DateTime($ihdata['created_datetime']);
						$d1 = new DateTime($cdata['patientinfo']['preauth_status_datetime']);
						$tat = $d2->diff($d1);
						$tat = json_encode($tat);
					}
					
					$this->icasestatus($submittedFld['patientinfo']['case_no'],$submittedFld['patientinfo']['preauth_type'],$this->userid,2,date("Y-m-d H:i:s"),@$submittedFld['patientinfo']['remarks'],@$tat);

					$sendnotif = $this->preauthNotification($submittedFld['patientinfo']['case_no'],$this->myutilities->getRef_Desc(104,$submittedFld['patientinfo']['healthfacility_code']),$submittedFld['patientinfo']['preauth_type'],2,$this->userid);
				}

			}
			else
			{
				$mfdResult = $this->iPreAuthForm($requiredFld['patientinfo'],$submittedFld['patientinfo'],$update);
				if($mfdResult['Status'] == 1)
				{
					$CaseNo = $mfdResult['CaseNo'];
					if( @$submittedFld['patientinfo']['fulfilled_selection_criteria'] == 'Y' )
					{
						$chkResult = $this->iPreAuthForm_Checklist($requiredFld['checklist'],$submittedFld['checklist'],$CaseNo,$submittedFld['patientinfo']['preauth_type']);
						$reqResult = $this->iPreAuthForm_Request($requiredFld['request'],$submittedFld['request'],$CaseNo,$submittedFld['patientinfo']['preauth_type']);
					}
					else
					{
						$chkResult['Status'] = 1;
						$reqResult['Status'] = 1;
					}

					$empResult = $this->iPreAuthForm_Empowerment($requiredFld['memberempowerment'],$submittedFld['memberempowerment'],$CaseNo,$submittedFld['patientinfo']['preauth_type']);
					$attResult = ['Status'=>1];
					if(is_array($SubmittedFiles) && count($SubmittedFiles) > 0)
					{
						$attResult = $this->iAttachments($CaseNo,$SubmittedFiles,@$update);
						$return['Message'] = ($attResult['Status'] == 1) ? $attResult['Message'] : '';
					}
				
					if($chkResult['Status'] == 0 || $reqResult['Status'] == 0 || $empResult['Status'] == 0 || $attResult['Status'] == 0)
					{
						$rmdMain = $this->sqlhelper->{$this->m_general->conProCode()}->delete('trans_preauth_form')->where()->run("case_no='".$CaseNo."'");
						$rmdChk = $this->sqlhelper->{$this->m_general->conProCode()}->delete('trans_preauth_form_'.str_pad($submittedFld['patientinfo']['preauth_type'],2,0,STR_PAD_LEFT).'_checklist')->where("case_no='".$CaseNo."'")->run();
						$rmdReq = $this->sqlhelper->{$this->m_general->conProCode()}->delete('trans_preauth_form_'.str_pad($submittedFld['patientinfo']['preauth_type'],2,0,STR_PAD_LEFT).'_request')->where("case_no='".$CaseNo."'")->run();
						$rmdEmp = $this->sqlhelper->{$this->m_general->conProCode()}->delete('trans_preauth_form_empowerment')->where("case_no='".$CaseNo."'")->run();
						goto exit_here;
					}

					$return['Status'] = 1;
					$return['Message'] = '<h3>Pre-Authorization Form Successfully Submitted!</h3><h3 class="text-center mb-0 mt-10 border border-success"><small class="text-success"><b>Pre-Authorization Case No.</b></small><br><b>'.$CaseNo.'</b></h3>';
					$return['CaseNo'] = $CaseNo;

					$this->icasestatus($CaseNo,$submittedFld['patientinfo']['preauth_type'],$this->userid,1,date("Y-m-d H:i:s"),@$submittedFld['patientinfo']['remarks']);

					$sendnotif = $this->preauthNotification($submittedFld['patientinfo']['case_no'],$this->myutilities->getRef_Desc(104,$submittedFld['patientinfo']['healthfacility_code']),$submittedFld['patientinfo']['preauth_type'],1,$this->userid);
				}
			}
		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}

		exit_here:
		return @$return;

	}

	function iPreAuthForm($requiredFld,$submittedFld,$update = false)
	{
		$return = ['Status'=>0,'Message'=>'','CaseNo' => ''];
		try
		{
			$iData = [];
			foreach($requiredFld as $fld => $cfg)
			{
				$iData[$fld] = (@$submittedFld[$fld] <> '') ? ( ($cfg['encrypted']) ? $this->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],$submittedFld[$fld],'MCrypt','aes-128','ecb') : @$submittedFld[$fld]) : '';
			}

			$CaseNo = '';
			$iData['submitted_by'] = $this->userid;
			$iData['submitted_datetime'] = date("Y-m-d H:i:s");
			$iData['preauth_status_datetime'] = $iData['submitted_datetime'];
			$iData['preauth_status_by'] = $iData['submitted_by'];
			if($update)
			{
				$CaseNo = $iData['case_no'];
				unset($iData['case_no']);
				unset($iData['preauth_type']);
				$iData['preauth_status'] = 2;
				$iData['updated_by'] = $iData['submitted_by'];
				$iData['updated_datetime'] = $iData['preauth_status_datetime'];
				$iData_Result = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_preauth_form')->ex_update($iData)->where("case_no = '".$CaseNo."'")->run();
			}
			else
			{
				$iData['preauth_status'] = 1;
				$iData['created_by'] = $iData['submitted_by'];
				$iData['created_datetime'] = $iData['preauth_status_datetime'];
				$iData_Result = $this->sqlhelper->{$this->m_general->conProCode()}->insert('trans_preauth_form')->ex_insert($iData)->run();
			}
			
			if($iData_Result['ErrorCode'] == '')
			{
				if(!$update)
				{
					// Success Insert Main Form Data
					$dRowID = $iData_Result['Data'];
					$gmaxCase = $this->sqlhelper->{$this->m_general->conProCode()}->sql("select count(*) as cCnt from trans_preauth_form where healthfacility_pro = '".$this->userregistrationinfo['ProCode']."' and DATE_FORMAT(DATE(Created_DateTime),'%Y-%m') = '".date('Y-m')."' ")->row();
					$gmaxCaseNo = $gmaxCase['Data']['cCnt'];
					$CaseNo = date('ymd').'-'.str_pad($this->userregistrationinfo['ProCode'],2,0,STR_PAD_LEFT).'-'.str_pad((($gmaxCaseNo == 0) ? 1 : $gmaxCaseNo),$this->system_settings['caseno_length'],0,STR_PAD_LEFT);
					$ucaseno = [ 'case_no' => $CaseNo]; 
					$uData_Result = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_preauth_form')->ex_update($ucaseno)->where("rowid = ".$dRowID)->run();
				}

				$return['Status'] = 1; 
				$return['CaseNo'] = @$CaseNo;
			}

		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}

		return @$return;
	}

	function iPreAuthForm_Checklist($requiredFld,$submittedFld,$caseno,$formtype,$update = false)
	{
		$return = ['Status'=>0,'Message'=>''];
		try
		{
			$iData = [];
			foreach($requiredFld as $fld => $cfg)
			{
				$iData[$fld] = (@$submittedFld[$fld] <> '') ? ( ($cfg['encrypted']) ? $this->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],$submittedFld[$fld],'MCrypt','aes-128','ecb') : @$submittedFld[$fld]) : '';
			}

			if($update)
			{
				$iData['updated_by'] = $this->userid;
				$iData['updated_datetime'] = date("Y-m-d H:i:s");
				$iData_Result = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_preauth_form_'.str_pad($formtype,2,0,STR_PAD_LEFT).'_checklist')->ex_update($iData)->where(" case_no = '".$caseno."' ")->run();
			}
			else
			{
				$iData['case_no'] = $caseno;
				$iData['created_by'] = $this->userid;
				$iData['created_datetime'] = date("Y-m-d H:i:s");
				$iData_Result = $this->sqlhelper->{$this->m_general->conProCode()}->insert('trans_preauth_form_'.str_pad($formtype,2,0,STR_PAD_LEFT).'_checklist')->ex_insert($iData)->run();
			}
			
			if( $iData_Result['ErrorCode'] == '' )
			{
				$return['Status'] = 1;
			}

		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}

		return @$return;
	}

	function iPreAuthForm_Request($requiredFld,$submittedFld,$caseno,$formtype,$update = false)
	{
		$return = ['Status'=>0,'Message'=>''];
		try
		{
			$iData = [];
			foreach($requiredFld as $fld => $cfg)
			{
				$iData[$fld] = (@$submittedFld[$fld] <> '') ? ( ($cfg['encrypted']) ? $this->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],$submittedFld[$fld],'MCrypt','aes-128','ecb') : @$submittedFld[$fld]) : '';
			}

			if($update)
			{
				$iData['updated_by'] = $this->userid;
				$iData['updated_datetime'] = date("Y-m-d H:i:s");
				$iData_Result = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_preauth_form_'.str_pad($formtype,2,0,STR_PAD_LEFT).'_request')->ex_update($iData)->where(" case_no = '".$caseno."' ")->run();
			}
			else
			{
				$iData['case_no'] = $caseno;
				$iData['created_by'] = $this->userid;
				$iData['created_datetime'] = date("Y-m-d H:i:s");
				$iData_Result = $this->sqlhelper->{$this->m_general->conProCode()}->insert('trans_preauth_form_'.str_pad($formtype,2,0,STR_PAD_LEFT).'_request')->ex_insert($iData)->run();
			}

			if($iData_Result['ErrorCode'] == '')
			{
				$return['Status'] = 1;
			}

		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}

		return @$return;
	}

	function iPreAuthForm_Empowerment($requiredFld,$submittedFld,$caseno,$formtype,$update = false)
	{
		$return = ['Status'=>0,'Message'=>''];
		try
		{
			$iData = [];
			foreach($requiredFld as $fld => $cfg)
			{
				$iData[$fld] = (@$submittedFld[$fld] <> '') ? ( ($cfg['encrypted']) ? $this->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],$submittedFld[$fld],'MCrypt','aes-128','ecb') : @$submittedFld[$fld]) : '';
			}

			if($update)
			{
				$iData['updated_by'] = $this->userid;
				$iData['updated_datetime'] = date("Y-m-d H:i:s");
				$iData_Result = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_preauth_form_empowerment')->ex_update($iData)->where(" case_no = '".$caseno."' ")->run();
			}
			else
			{
				$iData['case_no'] = $caseno;
				$iData['created_by'] = $this->userid;
				$iData['created_datetime'] = date("Y-m-d H:i:s");
				$iData_Result = $this->sqlhelper->{$this->m_general->conProCode()}->insert('trans_preauth_form_empowerment')->ex_insert($iData)->run();
			}

			if($iData_Result['ErrorCode'] == '')
			{
				$return['Status'] = 1;
			}

		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}

		return @$return;
	}

	function iAttachments($caseno,$files,$update = false)
	{
		$return = ['Status'=>0,'Message'=>''];
 		
		try
		{
			$tmpFiles = $files;
			if( count($tmpFiles['attachment']['name']) > 0)
			{
					$upload_config = [];
	        $upload_path = '/attachments/'.$this->userregistrationinfo['ProCode'];
	        if (!file_exists(FCPATH.$upload_path)) 
	        {
	           mkdir (FCPATH.$upload_path."/", 0777); 
	        } 
	       	$upload_path .= '/'.$caseno;
	       	$casedir = $upload_path;
	        if (!file_exists(FCPATH.$upload_path)) 
	        {
	           mkdir (FCPATH.$upload_path."/", 0777); 
	        } 
					$ierrf = 0;
	        $fu = fileUploader('attachment' , $upload_path, 'pdf',FALSE,10240);
	      
	        if( $fu['successCount'] > 0)
	        {
	            $successUploadFIle = [];
	            foreach($fu['files'] as $fi => $fiResult)
	            {
	              $successUploadFIle[] = base64_encode($fiResult['file_name'].'|'.$fiResult['orig_name']);

              	if( @$this->userprofile['oPublicKey'] <> '' )
              	{
              		$publicKey = openssl_pkey_get_public($this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$this->userprofile['oPublicKey'],'MCrypt','aes-128','ecb') );
            			PHPCypherFile::encryptFile($fiResult['full_path'], str_replace(".pdf","_enc.pdf",$fiResult['full_path']), $publicKey);
              	}
	              
	              if( file_exists( str_replace(".pdf","_enc.pdf",$fiResult['full_path']) ) ) 
	              {
              		unlink($fiResult['full_path']);
	              	$fiResult['full_path'] = str_replace(".pdf","_enc.pdf",$fiResult['full_path']);
	              }

	              $ifArray = array(
			            'File_Attachment'   => '',
			            'File_Path'         => str_replace(FCPATH, '', $fiResult['full_path']),
			            'File_FileName'     => $fiResult['orig_name'],
			            'File_FileSize'     => round($fiResult['file_size'] * 1024),
			            'File_FileType'     => $fiResult['file_type'],
			            'Created_By'        => $this->userid,
			            'Created_DateTime'  => date("Y-m-d H:i:s"),
		            );

		            try{
		              $insAttachment = $this->sqlhelper->local->insert('system_fileupload')->ex_insert($ifArray)->run();
		              if($insAttachment['ErrorCode'] == '')
		              {
		              	$aid = @$insAttachment['Data']; 
		              	$iData = [
							  			'case_no'       			=> $caseno,
							  			'uploadid' 	 					=> $aid,
							  			'Created_By' 				 => $this->userid,
											'Created_DateTime' 	 => date("Y-m-d H:i:s"),
							  		];

							  		$iResult = $this->sqlhelper->{$this->m_general->conProCode()}->insert('trans_preauth_form_attachments')->ex_insert($iData)->run();
							  		if($iResult['ErrorCode'] <> '')
							  		{
							  			$rDelete = $this->sqlhelper->remote1->delete('system_fileupload')->where("DataCount = ".$aid)->run();
							  			$ierrf++;
							  			unlink($fiResult['full_path']);
							  		}
		              }
		              else
		              {
		              	$ierrf++;
		              	unlink($fiResult['full_path']);
		              }
		            }catch (PDOException $e) {
		              log_message("error",__METHOD__." | ".$e->getMessage());
		              $ierrf++;
		            } catch (Exception $e) {
		              log_message("error",__METHOD__." | ".$e->getMessage());
		              $ierrf++;
		            }
	            }

	            if($ierrf == 0)
	            {
	            	$return['Status'] 	= 1;
								$return['Message']  = 'Document Attachments Successfully Added!';
	            }
	        } 
	        else
	        {
	        	
	        	$xfiles = glob($casedir."/*"); 
	          foreach($xfiles as $xfile){ // iterate files
	            if(is_file($xfile)) {
	              chown($xfile, 0777);
	              $filetoDelete = glob($xfile."/*");
	              foreach($filetoDelete as $dfile){
	                  chown($dfile, 0777);
	                  unlink($dfile); // delete file
	              }
	              rmdir($file);  // delete folder
	            }
	          }
	          chown($casedir, 0777);
	          rmdir($casedir);  // delete folder
	        	$return['Message'] = 'Failed to Process Submitted Pre-Authorization Form, Please make sure that the Attachment File is not corrupted!';
	        }  
			}
		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}
		
		return @$return;

	}

	function getFormData($rowid,$case_no_filter = false)
	{
		$preFormData = [
			'patientinfo' 				=> 'trans_preauth_form',
			'checklist'	  				=> 'trans_preauth_form_xx_checklist',
			'request'	  					=> 'trans_preauth_form_xx_request',
			'memberempowerment'		=> 'trans_preauth_form_empowerment',
			'attachments' 				=> 'trans_preauth_form_attachments',
		];

		$preauth_type = '';
		if($case_no_filter){
			$gdata = $this->sqlhelper->{$this->m_general->conProCode()}->select('trans_preauth_form')->where(" case_no = '".$rowid."'")->row();
			$rowid = @$gdata['Data']['rowid'];
		}

		foreach($preFormData as $dKeys => $dTbl)
		{
			if($dKeys == 'patientinfo')
			{
				$gd = $this->sqlhelper->{$this->m_general->conProCode()}->select($dTbl)->where(" rowid = '".$rowid."'")->row();
				if($gd['Count'] > 0)
				{
					$preFormData[$dKeys] = $gd['Data']; 
					$preauth_type = str_pad($gd['Data']['preauth_type'],2,0,STR_PAD_LEFT);
				}
				else
				{
					return '';
				}
			}
			else
			{
				if( @$preFormData['patientinfo']['fulfilled_selection_criteria'] == 'N' && in_array($dKeys,['checklist','request']) )
				{
					$preFormData[$dKeys] = [];
				}
				else
				{
					$gd = $this->sqlhelper->{$this->m_general->conProCode()}->select( str_replace("xx",str_pad($preauth_type,2,0,STR_PAD_LEFT),$dTbl) )->where(" case_no = '".$preFormData['patientinfo']['case_no']."' and deleted_datetime is null")->result();
					if($gd['Count'] > 0)
					{
						$preFormData[$dKeys] = ($dKeys <> 'attachments') ? $gd['Data'][0] : $gd['Data'];
					}
					else
					{
						// ZPAMS-FIX (2026-09): previously this branch was missing, so when the
						// subform query returned zero rows, $preFormData[$dKeys] kept the raw
						// table-name string from str_replace() above instead of an array. Defaulting
						// to [] here is the root-cause fix for the foreach() crash below.
						$preFormData[$dKeys] = [];
					}
				}
			}
		}

		$rData = $this->getRequiredData($preauth_type);
		$uprofile = $this->myutilities->getUserProfile($preFormData['patientinfo']['created_by']);

		foreach($preFormData as $fk => $frow)
		{
			// ZPAMS-FIX (2026-09): added `is_array($frow)` guard -- a non-array $frow (see fix
			// above) reached this foreach() and crashed with "foreach() argument must be of type
			// array|object, string given". Kept as a defensive guard even with the root cause fixed.
			if( $fk <> 'attachments' && is_array($frow) )
			{
				foreach($frow as $k => $v)
				{
					$preFormData[$fk][$k] = ( isset($rData[$fk][$k]) && $rData[$fk][$k]['encrypted'] && $v ) ? $this->m_api->encryptdecryptString('decrypt',$uprofile['User_E_Key'],$v,'MCrypt','aes-128','ecb') : $v;
				}
			}
		}

		return $preFormData;
	}

	function preauthAction($preauth_type,$case_no,$astatus,$remarks='',$facilityname='',$facuser='',$subdatetime='',$laststatsdatetime='')
	{
		$return = ['Status'=>0,'Message'=>''];

		try{

			$adatetime = date('Y-m-d H:i:s');
			$d2 = new DateTime($adatetime);
			$d1 = new DateTime($subdatetime);
			$tat = $d2->diff($d1);
			$tat = json_encode($tat);
			if($laststatsdatetime <> '')
			{
				$d3 = new DateTime($laststatsdatetime);
				$tat2 = $d2->diff($d3);
				$tat2 = json_encode($tat2);
			}

      $uD = [
				'remarks'									=> (@$remarks <> '') ? base64_encode($remarks) : '',
				'preauth_status'					=> $astatus,
				'preauth_status_datetime'	=> date('Y-m-d H:i:s'),
				'preauth_status_by'				=> $this->userid,
			];
			
			$RuD = $this->sqlhelper->{$this->m_general->conProCode()}->update("trans_preauth_form")->ex_update($uD)->where("case_no = '".$case_no."'")->run();
			if( $RuD['ErrorCode'] == '' )
			{
				$return['Status'] = 1;
				$this->icasestatus($case_no,@$preauth_type,$uD['preauth_status_by'],$uD['preauth_status'],$uD['preauth_status_datetime'],$uD['remarks'],@$tat2);
				$this->preauthNotification($case_no,$facilityname,$preauth_type,$astatus,$facuser);
			}

    	switch( (int) $astatus )
			{
				case 3: // Received
					$uD2 = [
						'received_datetime'	=> $adatetime ,
						'received_by'				=> $this->userid,
						'received_tat'			=> @$tat,
					];
					$RuD2 = $this->sqlhelper->{$this->m_general->conProCode()}->update("trans_preauth_form")->ex_update($uD2)->where("case_no = '".$case_no."'")->run(); 
				break;
				case 4: // For Compliance
					$return['Message'] = 'Pre-Authorization Case No : '.$case_no.' status successfully changed to [ '.$this->myutilities->getRef_Desc(202,$astatus).' ]';
				break;
				case 5: // Disapproved
				case 6: // Approved
					$uD2 = [
						'ad_datetime'					=> $adatetime ,
						'ad_by'								=> $this->userid,
						'ad_tat'							=> @$tat,
						'preauth_expiry_date' => $this->preauthExpiryDate($preauth_type,$adatetime),
						'preauth_expired'			=> 'N',
					];
					$RuD2 = $this->sqlhelper->{$this->m_general->conProCode()}->update("trans_preauth_form")->ex_update($uD2)->where("case_no = '".$case_no."'")->run(); 
					$return['Message'] = 'Pre-Authorization Case No : '.$case_no.' successfully '.$this->myutilities->getRef_Desc(202,$astatus);
				break;
			}
       
      }catch (PDOException $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
      } catch (Exception $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
      }

      return $return;
		
	}

	function preauthExpiryDate($preauth_type,$adatetime)
	{
		$dte = '';
		try
		{		 
			$gdys = $this->sqlhelper->{$this->m_general->conProCode()}->select("ref_primarycondition_preauthlist")->where("code = '".$preauth_type."'")->row(); 
			$gdys = $gdys['Data'];
			$date = new DateTime($adatetime);
			$interval = new DateInterval('P'.$gdys['validityPeriod'].'D'); // P10D means a period of 10 days
			$date->add($interval);
			$dte =  $date->format('Y-m-d');

		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}

		return $dte;
	}

	function preauthNotification($case_no,$facilityname,$preauth_type,$preauth_status,$facuser,$prevpreauth_status='',$compliance = '')
	{
			$ILLNESS = $this->myutilities->getRef_Desc(200,$preauth_type);

			switch( (int) $preauth_status)
			{
				case 1: // Submitted

					$notifmess = '
						<p>Your Pre-Authorization Request for {ILLNESS} has been successfully submitted.</p><br>
						<p>For future reference, this is the application Case Number: {CASE_NUMBER}</p><br>
						<p>We will update you on its status soon.</p><br>
						<p>Thank you very much.</p><br><br>
					';

					$notifmessbas = '
						<p>New Pre-Authorization Request for {ILLNESS} has been successfully submitted by <b>{FACILITY}</b> with Application Case Number: <b>{CASE_NUMBER}</b>.</p><br>
						<p>Thank you very much.</p><br><br>
					';

					$nSubject = 'New Pre-Authorization Request Case No: {CASE_NUMBER}';

				break;

				case 2: // Re-Submitted
	
					$notifmess = '
						<p>Your Pre-Authorization Request for {ILLNESS} with application Case Number: <b>{CASE_NUMBER}</b> has been successfully re-submitted.</p><br>
						<p>We will update you on its status soon.</p><br>
						<p>Thank you very much.</p><br><br>
					';

					$notifmessbas = '
						<p>Compliance to Pre-Authorization Request for {ILLNESS}  by <b>{FACILITY}</b> with Application Case Number: <b>{CASE_NUMBER}</b> has been successfully submitted</p><br>
						<p>Thank you very much.</p><br><br>
					';

					$nSubject = 'Submitted Pre-Authorization Case No: {CASE_NUMBER}';

				break;

				case 3: // Received
					
					$notifmess = '
						<p>This is to acknowledge receipt of your Pre-Authorization Request for {ILLNESS}.</p><br>
						<p>For future reference, this is the application Case Number: {CASE_NUMBER}</p><br>
						<p>Please expect a response within 3 working days.</p><br>
						<p>Thank you very much.</p><br><br>
					';

					$notifmessbas = '
						<p>Pre-Authorization Request for {ILLNESS} by <b>{FACILITY}</b> with Application Case Number: {CASE_NUMBER}. has been successfully received</p><br>
						<p>Thank you very much.</p><br><br>
					';

					if($prevpreauth_status == 2)
					{
						$notifmess = '
							<p>This is to acknowledge receipt of your Compliance to Pre-Authorization Request for <b>{ILLNESS}</b> with application Case Number: <b>{CASE_NUMBER}</b></p><br>
							<p>Please expect a response within 3 working days.</p><br>
							<p>Thank you very much.</p><br><br>
						';

						$notifmessbas = '
							<p>Compliance to Pre-Authorization Request for {ILLNESS} by <b>{FACILITY}</b> with Application Case Number: {CASE_NUMBER}. has been successfully received</p><br>
							<p>Thank you very much.</p><br><br>
						';
					}
					
					$nSubject = 'Pre-Authorization acknowledgement receipt Case Number: {CASE_NUMBER}';

				break;

				case 4: // For Compliance
					
					$notifmess = '
						<p>Upon careful evaluation of your Pre-Authorization Request for <b>{ILLNESS}</b> with application Case Number: <b>{CASE_NUMBER}</b>, the following deficiencies were noted:</p><br>
						<br>
						<div>'.@$compliance.'</div><br>
						<p>Kindly submit your compliance within 3 working days upon receipt of this email through the Z-PAMS using the above Case Number.</p><br>
						<p>Thank you very much.</p><br><br>
					';

					$notifmessbas = '
						<p>Pre-Authorization Request for {ILLNESS} by <b>{FACILITY}</b> with Application Case Number: <b>{CASE_NUMBER}</b> has been evaulated with the following deficiencies:</p><br>
						<br>
						<div>'.@$compliance.'</div><br>
						<p>Facility are expected to submit the compliance within 3 working days upon receipt of "For Compliance" email notification.</p><br>
						<p>Thank you very much.</p><br><br>
					';

					$nSubject = 'Pre-Authorization For Compliance Case No: {CASE_NUMBER}';
				break;

				case 5: // Disapproved
					$notifmess = 'We regret to inform you that your pre-authorization request (Case No: {CASE_NUMBER}) for {ILLNESS} has been disapproved by Benefits Administration Section (BAS). Please review the reason provided and take the necessary steps.';
					$notifmessbas = 'Pre-authorization request submitted by the {FACILITY} for {ILLNESS}. Case No: {CASE_NUMBER}, successfully disapproved.';
					$nSubject = 'Pre-Authorization Disapproved Case No: {CASE_NUMBER}';
				break;

				case 6: // Approved
					
					$notifmess = '
						<p>This is to inform you that your Pre-Authorization Request for <b>{ILLNESS}</b> with application Case Number: <b>{CASE_NUMBER}</b> has been <b>APPROVED</b></p><br>
						<p>Please note that the pre-authorization validity period is from %STARTDATE% to %ENDDATE%. The facility will need to submit a new pre-authorization request if the approved pre-authorization has expired.</p><br>
						<p>Please expect a response within 3 working days.</p><br>
						<p>Thank you very much.</p><br><br>
					';

					$notifmessbas = 'Pre-authorization request submitted by the {FACILITY} for {ILLNESS}. Case No: {CASE_NUMBER}, successfully approved.';
					$nSubject = 'Pre-Authorization Approved Case No: {CASE_NUMBER}';
				break;
			}

			$notifmess = str_replace(['{ILLNESS}','{CASE_NUMBER}','{FACILITY}'],[$ILLNESS,$case_no,$facilityname],$notifmess);
			$notifmessbas = str_replace(['{ILLNESS}','{CASE_NUMBER}','{FACILITY}'],[$ILLNESS,$case_no,$facilityname],$notifmessbas);
			$nSubject = str_replace(['{ILLNESS}','{CASE_NUMBER}','{FACILITY}'],[$ILLNESS,$case_no,$facilityname],$nSubject);

			$headerMessage = '
			<p>'.strtoupper($this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],@$fuser['Account_Name'],'MCrypt','aes-128','ecb')).'<br>'.strtoupper($facilityname).'</p><br><br>
			';

			$footerMessage = '
			<p>Sincerely yours,</p><br>
			<p>Z-BEN Team</p>
			';

			// Send Notif to Facility
			$fuser = $this->myutilities->getUserDetails($facuser);
			$mail_message = array(
          'header' => $headerMessage.'Dear Sir/ Madam,',
          'footer' => $footerMessage,
          'body' => $notifmess
      );

      $emailparam = array(
          'from'    => $this->config->item('email_sender'),
          'to'      => $fuser['User_EmailAddress'],
          'subject' => $nSubject,
          'message' => $mail_message,
          'cc'      => '',
          'bcc'     => ''
      );

      $this->daemon->execute_background('sendmail/sendmail','send_mail',$emailparam,true,false,false,true); //

      // Send Notif to all BAS
      $BasList = [];
      $gbl = $this->sqlhelper->{$this->m_general->conProCode()}->select("user_register")->where(" ProCode = '".$this->userregistrationinfo['ProCode']."' and user_classification = 2 ")->ex_select('','')->result();

      foreach($gbl['Data'] as $gblR => $gblC)
      {
      	$accname = ucwords(
      							strtolower(
      								$this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$gblC['Lastname'],'MCrypt','aes-128','ecb').
      								( ($gblC['Suffixname'] <> 'NA' && $gblC['Suffixname'] <> '') ? ' '.$this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$gblC['Suffixname'],'MCrypt','aes-128','ecb') : '' ).', '.
      								$this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$gblC['Firstname'],'MCrypt','aes-128','ecb').' '.
      								substr($this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$gblC['Middlename'],'MCrypt','aes-128','ecb'),0,1).'.'
      							)
      						);
      	$BasList[$gblC['emailaddress']] = @$accname;

				$mail_message = array(
	          'header' => 'Hi '.@$accname,
	          'footer' => 'Thank You.',
	          'body' => $notifmessbas
	      );

	      $emailparam = array(
	          'from'    => $this->config->item('email_sender'),
	          'to'      => $gblC['emailaddress'],
	          'subject' => $nSubject,
	          'message' => $mail_message,
	          'cc'      => '',
	          'bcc'     => ''
	      );

	      $this->daemon->execute_background('sendmail/sendmail','send_mail',$emailparam,true,false,false,true);
      }
	}

	function preauthHistory($case_no = '',$rowid = '')
	{
		$return = ['Status'=>0,'Message'=>''];

		try{

			if(!$case_no && !$rowid)
			{
				return $return;
			}

			if($rowid)
			{
				$wf = "rowid = '".((int) $rowid)."'";
			}
			else
			{
				$wf = "case_no = '".$case_no."'";
			}

			$gh = $this->sqlhelper->{$this->m_general->conProCode()}->select("trans_preauth_form_history")->where($wf)->result();
			if($gh['Count'] > 0)
			{
				$return['Status'] = 1;
				$return['Message'] = ($rowid) ? $gh['Data'][0] : $gh['Data'];
			}

		}catch (PDOException $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
      } catch (Exception $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
      }

      return $return;
	}

	function icasestatus($case_no,$preauth_type,$userid,$status,$sdatetime,$remarks='',$tat='')
	{
		try
		{		 
			$iDs = [
				'preauth_type'						=> $preauth_type,
				'case_no'									=> $case_no,
				'remarks'									=> $remarks,
				'preauth_status'					=> $status,
				'preauth_status_datetime'	=> $sdatetime,
				'preauth_status_by'				=> $userid,
				'preauth_status_tat'			=> @$tat,
			];

			$RiDs = $this->sqlhelper->{$this->m_general->conProCode()}->insert("trans_preauth_form_status")->ex_insert($iDs)->run();

		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}
	}

	function preauthstatus($case_no,$row = false)
	{
		$return = ['Status'=>0,'Message'=>''];

		try{

			if(!$case_no)
			{
				return $return;
			}

			$gh = $this->sqlhelper->{$this->m_general->conProCode()}->select("trans_preauth_form")->where("case_no = '".$case_no."'")->row();
			if($gh['Count'] > 0)
			{
				$return['Status'] = 1;
				$return['Message'] = ($row) ? $gh['Data'] : $gh['Data']['preauth_status'];
			}

		}catch (PDOException $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
    } catch (Exception $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    }

    return $return;
	}

	function lastpreauthStatus($case_no)
	{

		$return = ['Status'=>0,'Message'=>''];

		try{

			if(!$case_no)
			{
				return $return;
			}

			$gh = $this->sqlhelper->{$this->m_general->conProCode()}->select("trans_preauth_form_status")->where("case_no = '".$case_no."'")->ex_select('','preauth_status_datetime desc')->result();
			if($gh['Count'] > 0)
			{
				$return['Status'] = 1;
				$return['Message'] = $gh['Data'];
			}

		}catch (PDOException $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
    } catch (Exception $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    }

      return $return;
	}

	function reencryptdata($oldkey = '',$newkey='')
	{
		$result = '';
		try
		{
			$PreauthDataList =  $this->sqlhelper->{$this->m_general->conProCode()}->select("trans_preauth_form",'preauth_type')->where(" healthfacility_code = '".$this->userregistrationinfo['HealthFacilityCode']."'")->ex_select('preauth_type','preauth_type asc','')->result();

			if( $PreauthDataList['Count'] > 0 )
			{
				$PreauthDataList=$PreauthDataList['Data'];
				foreach($PreauthDataList as $row => $col)
				{
					$PreauthType = str_pad($col['preauth_type'],2,0,STR_PAD_LEFT);
					$getRequiredData = $this->getRequiredData($PreauthType,false,false,'');
					$gDataList = [
						'patientinfo' 			=> 'trans_preauth_form',
						'checklist'					=> 'trans_preauth_form_'.$PreauthType.'_checklist',
						'request'						=> 'trans_preauth_form_'.$PreauthType.'_request',
						'memberempowerment'	=> 'trans_preauth_form_empowerment',
					];

					$case_no_list = [];

					foreach($gDataList as $mk => $mt)
					{					
						if( $mk == 'patientinfo' )
						{
							$DataList = $this->sqlhelper->{$this->m_general->conProCode()}->select($mt)->where(" healthfacility_code = '".$this->userregistrationinfo['HealthFacilityCode']."'")->result();
						}
						else
						{
							$DataList = $this->sqlhelper->{$this->m_general->conProCode()}->select($mt)->where(" case_no in ('".implode("','",$case_no_list)."') ")->result();
						}		

						if($DataList['Count'] > 0)
						{
							$poolDataList = $DataList['Data'];
							foreach($poolDataList as $row => $col)
							{
								if( $mk == 'patientinfo' )
								{
									$case_no_list[] = $col['case_no'];
								}

								$upData = [];
								foreach( $col as $key => $val )
								{
									if( @$getRequiredData['patientinfo'][$key]['encrypted'] == true )
									{
										$cval = $val;
										if($val)
										{
											$val = $this->m_api->encryptdecryptString('decrypt',$oldkey,$val,'MCrypt','aes-128','ecb');
											if( $val <> '')
											{
												$val = $this->m_api->encryptdecryptString('encrypt',$newkey,$val,'MCrypt','aes-128','ecb');
											}
											else
											{
												$val = $cval;
											}
										}

										$upData[$key] = $val;
									}
								}

								if( count($upData) > 0 )
								{
									$ru = $this->sqlhelper->{$this->m_general->conProCode()}->update($mt)->ex_update($upData)->where(" case_no = '".$col['case_no']."' ")->run();
								}
							}
						}
					}

					$hDataList = $this->sqlhelper->{$this->m_general->conProCode()}->select('trans_preauth_form_history')->where(" case_no in ('".implode("','",$case_no_list)."') ")->result();
					if($hDataList['Count'] > 0)
					{
						foreach($hDataList['Data'] as $hrow => $hcol)
						{
						  //dataarchives
							$dataarchives = $this->m_api->encryptdecryptString('decrypt',$oldkey,base64_decode($hcol['dataarchives']),'MCrypt','aes-128','ecb');
							if( $dataarchives <> '')
							{
								$dataarchives = $this->m_api->encryptdecryptString('encrypt',$newkey,$dataarchives,'MCrypt','aes-128','ecb');
								$dataarchives = base64_encode($val);
							}
							else
							{
								$dataarchives = $hcol['dataarchives'];
							}

							$uphData = [
								'dataarchives' => $dataarchives
							];

						  $ru = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_preauth_form_history')->ex_update($uphData)->where(" case_no = '".$col['case_no']."' ")->run();

						}
					}
					
				}
			}

		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}

		return @$result;
	}
}
