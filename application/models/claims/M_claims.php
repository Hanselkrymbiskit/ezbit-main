<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use setasign\Fpdi\Tcpdf\Fpdi;
class M_claims extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	  
	}

	function vFormData($requiredFld,$submittedFld)
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

		$return['Message'] = ($errCnt > 0) ? implode(",<br>",$errDesc) : '';
		if( $errCnt == 0 )
		{
			$return['Status'] = 1;
		}

		return $return;
	}


	function getRequiredData($update=false,$submittedFld='')
	{
		try
		{
			$FormData = [
				'preauth_type'=>['label'=>'Pre-Authorization Form Type','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'case_no'=>['label'=>'Pre-Authorization Case No.','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'healthfacility_area'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'healthfacility_pro'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
				'healthfacility_code'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
			];

			$preFormData = [
				'claiminfo' => $FormData,
			];
		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}

		return @$preFormData;
	}

	function ProcessClaim($requiredFld,$submittedFld,$SubmittedFiles,$update = false,$cdata='')
	{
  		$return = ['Status'=>0,'Message'=>'Failed to Process Submitted Z-Benefit Claim Form'];

  	try
		{

			if($update && $submittedFld['claiminfo']['case_no'])
			{
				$hdata = base64_encode($this->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],json_encode($cdata,JSON_UNESCAPED_SLASHES),'MCrypt','aes-128','ecb'));

				$ihdata = [
					'claim_rowid'      => @$cdata['claiminfo']['rowid'],
					'preauth_type' 		=> $submittedFld['claiminfo']['preauth_type'],
					'case_no' 				=> $submittedFld['claiminfo']['case_no'],
					'dataarchives' 		=> @$hdata,
					'created_by' 			=> $this->userid,
					'created_datetime' 	=> date('Y-m-d H:i:s'),
				];

				$rihdata = $this->sqlhelper->{$this->m_general->conProCode()}->insert('trans_claims_form_history')->ex_insert($ihdata)->run();
				$submittedFld['claiminfo']['remarks'] = (@$cdata['claiminfo']['remarks'] <> '' ) ? base64_decode(@$cdata['claiminfo']['remarks']) : '';
				$umfdResult = $this->iClaimForm($requiredFld['claiminfo'],$submittedFld['claiminfo'],$update);
				if($umfdResult['Status'] == 1)
				{
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

								$rufileResult = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_claims_form_attachments')->ex_update($ufilerecord)->where(" case_no = '".$submittedFld['claiminfo']['case_no']."' and uploadid = '".$upid."' ")->run();
							}
						}
					}

					if(is_array($SubmittedFiles) && count($SubmittedFiles) > 0)
					{
						$attResult = $this->iAttachments($submittedFld['claiminfo']['case_no'],$SubmittedFiles,$update);
						$return['Message'] = $attResult['Message'];
						if($attResult['Status'] == 0)
						{
							goto exit_here;
						}
					}

					$return['Status'] = 1;
					$return['Message'] = '<h3>Z-Benefit Claim Form Successfully Re-Submitted!</h3><h3 class="text-center mb-0 mt-10 border border-success"><small class="text-success"><b>Pre-Authorization Case No.</b></small><br><b>'.$submittedFld['claiminfo']['case_no'].'</b></h3>';
					$return['CaseNo'] = $submittedFld['claiminfo']['case_no'];

					$this->icasestatus($submittedFld['claiminfo']['case_no'],$submittedFld['claiminfo']['preauth_type'],$this->userid,2,date("Y-m-d H:i:s"),@$submittedFld['claiminfo']['remarks']);

					$sendnotif = $this->claimNotification($submittedFld['claiminfo']['case_no'],$this->myutilities->getRef_Desc(104,$submittedFld['claiminfo']['healthfacility_code']),$submittedFld['claiminfo']['preauth_type'],2,$this->userid);
				}

			}
			else
			{
				$mfdResult = $this->iClaimForm($requiredFld['claiminfo'],$submittedFld['claiminfo'],$update);
				if($mfdResult['Status'] == 1)
				{
					$CaseNo = $submittedFld['claiminfo']['case_no'];
					$attResult = ['Status'=>1];
					if(is_array($SubmittedFiles) && count($SubmittedFiles) > 0)
					{
						$attResult = $this->iAttachments($CaseNo,$SubmittedFiles,@$update);
						$return['Message'] = ($attResult['Status'] == 1) ? $attResult['Message'] : '';
					}
				
					if( $attResult['Status'] == 0 )
					{
						$rmdMain = $this->sqlhelper->{$this->m_general->conProCode()}->delete('trans_claims_form')->where()->run("case_no='".$CaseNo."'");
						goto exit_here;
					}

					$return['Status'] = 1;
					$return['Message'] = '<h3>Z-Benefit Claim Form Successfully Submitted!</h3><h3 class="text-center mb-0 mt-10 border border-success"><small class="text-success"><b>Pre-Authorization Case No.</b></small><br><b>'.$CaseNo.'</b></h3>';
					$return['CaseNo'] = $CaseNo;

					$this->icasestatus($CaseNo,$submittedFld['claiminfo']['preauth_type'],$this->userid,1,date("Y-m-d H:i:s"),@$submittedFld['claiminfo']['remarks']);

					$sendnotif = $this->claimNotification($submittedFld['claiminfo']['case_no'],$this->myutilities->getRef_Desc(104,$submittedFld['claiminfo']['healthfacility_code']),$submittedFld['claiminfo']['preauth_type'],1,$this->userid);
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

	function iClaimForm($requiredFld,$submittedFld,$update = false)
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
			$iData['claims_status_datetime'] = $iData['submitted_datetime'];
			$iData['claims_status_by'] = $iData['submitted_by'];
			if($update)
			{
				$CaseNo = $iData['case_no'];
				unset($iData['case_no']);
				unset($iData['preauth_type']);
				$iData['claims_status'] = 2;
				$iData['updated_by'] = $iData['submitted_by'];
				$iData['updated_datetime'] = $iData['claims_status_datetime'];
				$iData_Result = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_claims_form')->ex_update($iData)->where("case_no = '".$CaseNo."'")->run();
			}
			else
			{
				$iData['claims_status'] = 1;
				$iData['created_by'] = $iData['submitted_by'];
				$iData['created_datetime'] = $iData['claims_status_datetime'];
				$iData_Result = $this->sqlhelper->{$this->m_general->conProCode()}->insert('trans_claims_form')->ex_insert($iData)->run();
			}
			
			if($iData_Result['ErrorCode'] == '')
			{
				$return['Status'] = 1; 
				$return['CaseNo'] = @$submittedFld[''];
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

        $upload_path .= '/claims';
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

						  		$iResult = $this->sqlhelper->{$this->m_general->conProCode()}->insert('trans_claims_form_attachments')->ex_insert($iData)->run();
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
        	$return['Message'] = 'Failed to Process Submitted Z-Benefit Claim Form, Please make sure that the Attachment File is not corrupted!';
        }  
		}

		return @$return;

	}

	function getFormData($rowid,$case_no_filter = false)
	{
		$preFormData = [
			'claiminfo' 				=> 'trans_claims_form',
			'attachments' 			=> 'trans_claims_form_attachments',
		];
		$preauth_type = '';

		if($case_no_filter){
			$gdata = $this->sqlhelper->{$this->m_general->conProCode()}->select('trans_claims_form')->where(" case_no = '".$rowid."'")->row();
			$rowid = @$gdata['Data']['rowid'];
		}

		foreach($preFormData as $dKeys => $dTbl)
		{
			if($dKeys == 'claiminfo')
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
				$gd = $this->sqlhelper->{$this->m_general->conProCode()}->select( str_replace("xx",str_pad($preauth_type,2,0,STR_PAD_LEFT),$dTbl) )->where(" case_no = '".$preFormData['claiminfo']['case_no']."' and deleted_datetime is null")->result();
				if($gd['Count'] > 0)
				{
					$preFormData[$dKeys] = ($dKeys <> 'attachments') ? $gd['Data'][0] : $gd['Data'];
				}
			}
		}

		$rData = $this->getRequiredData($preauth_type);
		$uprofile = $this->myutilities->getUserProfile($preFormData['claiminfo']['created_by']);
		foreach($preFormData as $fk => $frow)
		{
			if( $fk <> 'attachments' )
			{
				foreach($frow as $k => $v)
				{
					$preFormData[$fk][$k] = ( isset($rData[$fk][$k]) && $rData[$fk][$k]['encrypted'] && $v ) ? $this->m_api->encryptdecryptString('decrypt',$uprofile['User_E_Key'],$v,'MCrypt','aes-128','ecb') : $v;
				}
			}
		}

		return $preFormData;
	}

	function claimAction($preauth_type,$case_no,$astatus,$remarks='',$facilityname='',$facuser='')
	{
		$return = ['Status'=>0,'Message'=>''];

		try{
      	
      	$uD = [
				'remarks'									=> (@$remarks <> '') ? base64_encode($remarks) : '',
				'claims_status'					=> $astatus,
				'claims_status_datetime'	=> date('Y-m-d H:i:s'),
				'claims_status_by'				=> $this->userid,
			];
			
			$RuD = $this->sqlhelper->{$this->m_general->conProCode()}->update("trans_claims_form")->ex_update($uD)->where("case_no = '".$case_no."'")->run();
			if( $RuD['ErrorCode'] == '' )
			{
				$return['Status'] = 1;
				$this->icasestatus($case_no,@$preauth_type,$uD['claims_status_by'],$uD['claims_status'],$uD['claims_status_datetime'],$uD['remarks']);
				$this->claimNotification($case_no,$facilityname,$preauth_type,$astatus,$facuser);
			}

    	switch( (int) $astatus )
			{
				case 3: // Received
					$uD2 = [
						'received_datetime'	=> date('Y-m-d H:i:s'),
						'received_by'			=> $this->userid,
					];
					$RuD2 = $this->sqlhelper->{$this->m_general->conProCode()}->update("trans_claims_form")->ex_update($uD2)->where("case_no = '".$case_no."'")->run(); 
				break;
				case 4: // For Compliance
					$return['Message'] = 'Z-Benefit Claims with Pre-Authorization Case No : '.$case_no.' status successfully changed to [ '.$this->myutilities->getRef_Desc(202,$astatus).' ]';
				break;
				case 5: // Disapproved
				case 6: // Approved
					$uD2 = [
						'ad_datetime'	=> date('Y-m-d H:i:s'),
						'ad_by'			=> $this->userid,
					];
					$RuD2 = $this->sqlhelper->{$this->m_general->conProCode()}->update("trans_claims_form")->ex_update($uD2)->where("case_no = '".$case_no."'")->run(); 
					$return['Message'] = 'Z-Benefit Claims with Pre-Authorization Case No : '.$case_no.' successfully '.$this->myutilities->getRef_Desc(202,$astatus);
				break;
			}
       
      }catch (PDOException $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
      } catch (Exception $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
      }

      return $return;
		
	}

	function claimNotification($case_no,$facilityname,$preauth_type,$claims_status,$facuser)
	{
			$ILLNESS = $this->myutilities->getRef_Desc(200,$preauth_type);

			switch( (int) $claims_status)
			{
				case 1: // Submitted
				case 2: // Re-Submitted
					$notifmess = 'Your Z-Benefit Claim request has been successfully submitted for %ILLNESS%. Case No: %CASE_NUMBER%, We will update you on its status soon.';
					$notifmessbas = ( ((int) $claims_status == 2) ? 'Z-Benefit Claim request re-submitted' : 'New Z-Benefit Claim request submitted' ).'  by the %FACILITY% for %ILLNESS%. Case No: %CASE_NUMBER%, Please review and process accordingly.';
					$nSubject = 'Z-Benefit Claim Submitted Case No: %CASE_NUMBER%';
				break;

				case 3: // Received
					$notifmess = 'Your Z-Benefit Claim (Case No: %CASE_NUMBER%) for %ILLNESS% has been received. If additional documents are required, we will contact you. Thank you for your patience.';
					$notifmessbas = ( ((int) $claims_status == 2) ? 'Z-Benefit Claim request re-submitted' : 'New Z-Benefit Claim request submitted' ).' by the %FACILITY% for %ILLNESS%. Case No: %CASE_NUMBER%, has been successfully received.';
					$nSubject = 'Z-Benefit Claim Received Case No: %CASE_NUMBER%';
				break;

				case 4: // For Compliance
					$notifmess = 'Your Z-Benefit Claim request (Case No: %CASE_NUMBER%) for %ILLNESS% has been reviewed by Benefits Administration Section (BAS). Additional compliance is required. Kindly login and review necessary documents or information for submission.';
					$notifmessbas = 'Z-Benefit Claim request submitted by the %FACILITY% for %ILLNESS%. Case No: %CASE_NUMBER%, successfully changed status to "For Compliance".';
					$nSubject = 'Z-Benefit Claim For Compliance Case No: %CASE_NUMBER%';
				break;

				case 5: // Disapproved
					$notifmess = 'We regret to inform you that your Z-Benefit Claim request (Case No: %CASE_NUMBER%) for %ILLNESS% has been disapproved by Benefits Administration Section (BAS). Please review the reason provided and take the necessary steps.';
					$notifmessbas = 'Z-Benefit Claim request submitted by the %FACILITY% for %ILLNESS%. Case No: %CASE_NUMBER%, successfully disapproved.';
					$nSubject = 'Z-Benefit Claim Disapproved Case No: %CASE_NUMBER%';
				break;

				case 6: // Approved
					$notifmess = 'Your Z-Benefit Claim request (Case No: %CASE_NUMBER%) for %ILLNESS% has been successfully approved by Benefits Administration Section (BAS). Please check for details.';
					$notifmessbas = 'Z-Benefit Claim request submitted by the %FACILITY% for %ILLNESS%. Case No: %CASE_NUMBER%, successfully approved.';
					$nSubject = 'Z-Benefit Claim Approved Case No: %CASE_NUMBER%';
				break;
			}

			$notifmess = str_replace(['%ILLNESS%','%CASE_NUMBER%','%FACILITY%'],[$ILLNESS,$case_no,$facilityname],$notifmess);
			$notifmessbas = str_replace(['%ILLNESS%','%CASE_NUMBER%','%FACILITY%'],[$ILLNESS,$case_no,$facilityname],$notifmessbas);
			$nSubject = str_replace(['%ILLNESS%','%CASE_NUMBER%','%FACILITY%'],[$ILLNESS,$case_no,$facilityname],$nSubject);

			// Send Notif to Facility
			$fuser = $this->myutilities->getUserDetails($facuser);
			$mail_message = array(
          'header' => 'Hi '.$this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],@$fuser['Account_Name'],'MCrypt','aes-128','ecb'),
          'footer' => 'Thank You.',
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

      $this->daemon->execute_background('sendmail/sendmail','send_mail',$emailparam,true,false,false,true);

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

	function claimHistory($case_no = '',$rowid = '')
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

			$gh = $this->sqlhelper->{$this->m_general->conProCode()}->select("trans_claims_form_history")->where($wf)->result();
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

	function icasestatus($case_no,$preauth_type,$userid,$status,$sdatetime,$remarks='')
	{
		try
		{		 
			$iDs = [
				'preauth_type'						=> $preauth_type,
				'case_no'									=> $case_no,
				'remarks'									=> $remarks,
				'claims_status'					=> $status,
				'claims_status_datetime'	=> $sdatetime,
				'claims_status_by'				=> $userid,
			];

			$RiDs = $this->sqlhelper->{$this->m_general->conProCode()}->insert("trans_claims_form_status")->ex_insert($iDs)->run();

		}catch (PDOException $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		} catch (Exception $e) {
		  log_message("error",__METHOD__ .' | '.$e->getMessage());
		}
	}

	function claimstatus($case_no,$row = false)
	{
		$return = ['Status'=>0,'Message'=>''];

		try{

			if(!$case_no)
			{
				return $return;
			}

			$gh = $this->sqlhelper->{$this->m_general->conProCode()}->select("trans_claims_form")->where("case_no = '".$case_no."'")->row();
			if($gh['Count'] > 0)
			{
				$return['Status'] = 1;
				$return['Message'] = ($row) ? $gh['Data'] : $gh['Data']['claims_status'];
			}

		}catch (PDOException $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
    } catch (Exception $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    }

    return $return;
	}

	function lastclaimStatus($case_no)
	{

		$return = ['Status'=>0,'Message'=>''];

		try{

			if(!$case_no)
			{
				return $return;
			}

			$gh = $this->sqlhelper->{$this->m_general->conProCode()}->select("trans_claims_form_status")->where("case_no = '".$case_no."'")->ex_select('','claims_status_datetime desc')->result();
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
			$ClaimDataList =  $this->sqlhelper->{$this->m_general->conProCode()}->select("trans_claims_form")->where(" healthfacility_code = '".$this->userregistrationinfo['HealthFacilityCode']."'")->result();
			$case_no_list = [];
			if( $ClaimDataList['Count'] > 0 )
			{
				foreach($ClaimDataList['Data'] as $crow => $ccol)
				{
					$case_no_list[] = $ccol['case_no'];
				}

				$hDataList = $this->sqlhelper->{$this->m_general->conProCode()}->select('trans_claims_form_history')->where(" case_no in ('".implode("','",$case_no_list)."') ")->result();
				if($hDataList['Count'] > 0)
				{
					foreach($hDataList['Data'] as $hrow => $hcol)
					{
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

					  $ru = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_claims_form_history')->ex_update($uphData)->where(" case_no = '".$col['case_no']."' ")->run();

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
