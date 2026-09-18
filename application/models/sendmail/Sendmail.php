<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Sendmail extends CI_Model
{
	public $CI;
	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
		$this->load->library('email');
	}

	function send_mail($param,$log_mail=true,$return=false,$resendmail=false,$logonly=false)
	{
		$finalResult = 0;
		
		try
		{
			if(is_array($param) && count($param) > 0)
			{
				extract($param);
			}

			$from = ($this->CI->system_settings['UseSendGrid'] == 1) ? @$this->CI->system_settings['SendGridSendFrom'] : '';
			$to = array_filter( ( ( is_array($to) ) ? $to : array($to) ) , function($v) {  return !empty($v); });
			$header = (!is_array($message)) ? '' : @$message['header']; 
	 		$footer = (!is_array($message)) ? '' : @$message['footer']; 
 			$body = (!is_array($message)) ? $message : @$message['body']; 
			$message = (!$resendmail) ? $this->mailcontent_formatter($header,$body,$footer) : $body;
			$subject = (@$subject == '') ? 'Notification Mail from '.@$this->CI->system_settings['ApplicationName'] : $subject;

			$LogMailArray = array(
				'sFrEmail' 				=> $from,
				'sToEmail' 				=> (is_array($to)) ? implode(',',$to) : $to,
				'sCcEmail' 				=> (is_array($cc)) ? implode(',',$cc) : $cc,
				'sBccEmail' 			=> (is_array($bcc)) ? implode(',',$bcc) : $bcc,
				'sSubject' 				=> $subject,
				'sMail' 				=> base64_encode($message),
				'sAttachmentDir' 		=> (@$attach <> "") ? base64_encode(json_encode(@$attach)) : "",
				'DateTimeCreated' 		=> date("Y-m-d H:i:s"),
				'ErrorMessage' 			=> '',
				'sToEmail' 				=> '',
				'MailStatus'			=> 0,
			);

			if( is_array($to) && count($to) > 0 ) 
			{
				foreach($to as $semail)
				{
					$semail = (@$semail <> '') ? trim(strip_tags($semail)) : '';
					$LogMailArray['sToEmail'] = $semail;
					
					if($log_mail && $semail)
					{
						if( trim($LogMailArray['sToEmail']) <> '')
						{
							$InsertMailLogData = $this->CI->sqlhelper->local->insert("system_mailsender_log")->ex_insert($LogMailArray)->run();
							$iMailLogID = $InsertMailLogData['Data'];
						}						
					}

					if(!$semail)
					{
						goto exit_loop;
					}

					if(!$logonly)
					{
						try
						{
							
							if($this->CI->system_settings['UseSendGrid'] == 1)
							{
								$grid_result = $this->send_SendGrid($subject,$to,$message,'text/html',$attach);
								$LogMailArray['MailStatus'] = ( @$grid_result['Status'] == '' ) ? 0 : $grid_result['Status'] ;
								$LogMailArray['DateTimeSend'] = @$grid_result['Message']['DateTimeSend'];
								$LogMailArray['DateTimeFailedSend'] = @$grid_result['Message']['DateTimeFailedSend'];
								$LogMailArray['ErrorMessage'] =  @$grid_result['Message']['ErrorMessage'];

								$finalResult = (@$grid_result['Status'] == 2) ? 1 : 0;
							}
							else
							{
								for( $s = 1; $s <=6; $s++)
								{
									
									$tmpMailMode = ($s > 1) ? $s : '';
									$emailConfig = [];
									$eaConfig = array(
										'SMTPHost'      	=> 'smtp_host',
										'SMTPProtocol'  	=> 'smtp_crypto',
										'SMTPPortNo'    	=> 'smtp_port',
										'SMTPUser'      	=> 'smtp_user',
										'SMTPPassword'  	=> 'smtp_pass',
										'SMTPMailMask'  	=> 'email_sender',
										'SMTPFrom'			=> '',
										'SMTPFromName'  	=> '',
										'SMTPReplyTo'		=> '',
										'SMTPReplyToName' 	=> '',
										'SMTPNoSSLTLS'		=> '',
										'SMTPTextOnly'		=> 'mailtype',
										'SMTPTextSanitize'	=> '',
									);

									foreach( $eaConfig as $emK => $config_key )
									{
										$esA = $emK.$tmpMailMode;
										$econfigVal = @$this->CI->system_settings[$esA];

										switch($emK)
										{
											case "SMTPProtocol":
												$econfigVal = ($econfigVal == 1) ? 'ssl' : 'tls';
											break;

											case "SMTPTextOnly":
												$econfigVal = ($econfigVal == 1) ? 'text' : 'html';
											break;
										}       

										if( $config_key )
										{
											$this->CI->config->set_item($config_key, $econfigVal);
											$emailConfig[$config_key] = $econfigVal;
										}
									}

									if( $emailConfig['smtp_host'] <> '' )
									{
										
										$emailConfig['smtp_crypto'] = (@$this->CI->system_settings['SMTPNoSSLTLS'.$tmpMailMode] == 1) ? '' : $emailConfig['smtp_crypto'];
										$tmpSMTPFrom = 'SMTPFrom'.$tmpMailMode;
										$tmpSMTPUser = 'SMTPUser'.$tmpMailMode;
										$tmpSMTPFromName = 'SMTPFromName'.$tmpMailMode;
										$tmpSMTPReplyTo = 'SMTPReplyTo'.$tmpMailMode;
										$tmpSMTPReplyToName = 'SMTPReplyToName'.$tmpMailMode;
										$fFrom = (@$this->CI->system_settings[$tmpSMTPFrom] <> '') ? $this->CI->system_settings[$tmpSMTPFrom] : $this->CI->system_settings[$tmpSMTPUser];
										$fReplyTo = (@$this->CI->system_settings[$tmpSMTPReplyTo] <> '') ? $this->CI->system_settings[$tmpSMTPReplyTo] : $this->CI->system_settings[$tmpSMTPUser];
										$LogMailArray['sFrEmail'] = $fFrom;

										$emailConfig['protocol'] = 'smtp';
										// $emailConfig['crlf'] = '\r\n';
										// $emailConfig['newline'] = '\r\n';
										$this->email->clear(true);
										$this->email->initialize($emailConfig);
								    	$this->email->from($fFrom,@$this->CI->system_settings[$tmpSMTPFromName]); 
										$this->email->reply_to($fReplyTo,@$this->CI->system_settings[$tmpSMTPReplyToName]);
										$this->email->cc(@$cc);
										$this->email->bcc(@$bcc);
										$this->email->subject($subject);
										if(isset($attach) && $attach <> '')
										{
											if(is_array($attach))
											{
												array_walk($attach, array($this->email, 'attach'));
											}
											else
											{
												$this->email->attach($attach);
											}
										}

										
										if( trim($emailConfig['mailtype']) == 'text' )
										{
											$message = $this->convertToTextMailFormat($message);

											if( @$this->CI->system_settings['SMTPTextSanitize'.$tmpMailMode] == 1)
											{
												$mhtml = new \Html2Text\Html2Text($message);
												$message = $mhtml->getText();
											}
											
										}

										$this->email->to($semail);
										$this->email->message( $message );
										
									    if ($this->email->send())
									    {
								       		$LogMailArray['MailStatus'] =  2 ;
											$LogMailArray['DateTimeSend'] = date("Y-m-d H:i:s");
											$finalResult = 1;
											break;
									    }
									    else
									    {
									       	$LogMailArray['MailStatus'] =  1 ;
									        $LogMailArray['DateTimeFailedSend'] = date("Y-m-d H:i:s");
											$LogMailArray['ErrorMessage'] = ($this->email->print_debugger() <> '') ? json_encode($this->email->print_debugger(array('headers'))) : '';
									        log_message("email", $this->email->print_debugger(array('headers')) );
									    }

									    $this->email->clear(true);

									}

									
								}
							}
						}
						catch(Exception $sendmailError)
						{					
							log_message("error",$sendmailError->getMessage());
						}

						if( isset($iMailLogID) )
						{
							$UpdatetMailLogData = $this->sqlhelper->local->update("system_mailsender_log")->ex_update($LogMailArray)->where("DataID =".$iMailLogID)->run();
						}

					}

					exit_loop:
				}
			}
		}
		catch(Exception $mailError)
		{
			log_message("email","Error Sending Mail : ".$mailError->getMessage());
		}

		if($return)
		{
			return $finalResult;
		}
	}

	function send_SendGrid($subject,$to,$message,$mailtype = 'text/html',$attachment = '')
	{
		$return = ['Status' => '', 'Message' => ''];
		
		$sendgridemail = new \SendGrid\Mail\Mail();
        $sendgridemail->setFrom($this->CI->system_settings['SendGridSendFrom'], $this->CI->system_settings['ApplicationName'] );
        $sendgridemail->setSubject($subject);
        $sendgridemail->addTo($to, "");
        $sendgridemail->addContent(
            $mailtype,@$message);
        if(isset($attachment) && $attachment <> '')
		{
			if(is_array($attachment))
			{
				foreach($attachment as $fileAttachPath)
				{
					$filename = explode("/",$fileAttachPath);
					$filename = ( (count($filename) > 0) ? $filename[count($filename) - 1] : $fileAttachPath);
					$mime_content_type = mime_content_type($fileAttachPath);
					$file_encoded = base64_encode(file_get_contents($fileAttachPath));
					$sendgridemail->addAttachment(
					    $file_encoded,
					    $mime_content_type,
					    $filename,
					    "attachment"
					);
				}	
			}
			else
			{
				$filename = explode("/",$attachment);
				$filename = ( (count($filename) > 0) ? $filename[count($filename) - 1] : $attachment);
				$mime_content_type = mime_content_type($attachment);
				$file_encoded = base64_encode(file_get_contents($attachment));
				$sendgridemail->addAttachment(
				    $file_encoded,
				    $mime_content_type,
				    $filename,
				    "attachment"
				);
			}
		}


        $sendgrid = new \SendGrid(($this->CI->system_settings['SendGridAPIKey']));

        try {
            $sendgridresponse = $sendgrid->send($sendgridemail);
            switch(  $sendgridresponse->statusCode() )
            {
            	case 200:
            	case 201:
            	case 202:
            	case 204:
            		$return['Status'] = true;
            		$return['Message'] = [
            			'MailStatus' 			=> 2,
            			'DateTimeSend' 			=> date("Y-m-d H:i:s"),
            			'DateTimeFailedSend' 	=> ''
            		];
            	break;

            	default:
            		
            		$return['Status'] = false;
            		$return['Message'] = [
            			'MailStatus' 			=> 1,
            			'DateTimeSend' 			=> '',
            			'DateTimeFailedSend' 	=> date("Y-m-d H:i:s")
            		];

            		switch( $sendgridresponse->statusCode() )
            		{
            			case 400:
            				$return['Message']['ErrorMessage'] = 'Bad request';
            			break;
            			case 401:
            				$return['Message']['ErrorMessage'] = 'Requires authentication';
            			break;
            			case 403:
            				$return['Message']['ErrorMessage'] = 'From address doesn\'t match Verified Sender Identity';
            			break;
            			case 406:
            				$return['Message']['ErrorMessage'] = 'Missing Accept header';
            			break;
            			case 429:
            				$return['Message']['ErrorMessage'] = 'Too many requests/Rate limit exceeded';
            			break;
            			case 500:
            				$return['Message']['ErrorMessage'] = 'Internal server error';
            			break;
            		}
            	break;
            }
          	
        } catch (Exception $e) {
            $return['Message']['ErrorMessage'] = $e->getMessage();
            $return['Status'] = false;
        }

		return $return;
	}

	function mailcontent_formatter($cHeader = '',$cBody = '',$cFooter = '',$txtonly = false)
	{
		if(!$txtonly)
		{
			$mHeader = ($cHeader <> '') ? '<h3 id="mailHeader" style="margin-bottom:5px;border-bottom:1px dashed #8b8b8b;padding-bottom:10px">'.$cHeader.'</h3>' : '';
			$mFooter = ($cFooter <> '') ? '<h5 id="mailFooter" style="margin-top:20px;border-top:1px dashed #8b8b8b"><div style="padding-top:15px">'.$cFooter.'</div></h5>' : '';
			$mailHTML = '
				<div style="-webkit-box-shadow: 0px 0px 8px #999;background-image: url('.site_url('').'assets/images/login.jpg);">
					<div style="">
					  	<div style="font-family: sans-serif;padding: 30px;">	    
						    <div style="font-family:sans-serif;padding: 0px 5px;">
						    	<table>
										<tbody>
											<tr>
												<td style="text-align: center;">
													<img class="brand-img h-80 mt-60" src="'.site_url('').'assets/images/brand.png" alt="..." style="height:80px"><img class="brand-img h-80 mt-60 ml-20" src="'.site_url('').'assets/images/bagongpilipinas2.png" alt="..." style="height:80px">
												</td>
												<td style="text-align: center;">
													<img id="PageBrandingImage" src="'.site_url('').'assets/images/e-zbits_logo.png" style="width: 251px;height: 80px;position: absolute;right: 20px;top: 34px;">
												</td>
											</tr>
										</tbody>
									</table>
						    </div>
					  	</div>
					  	<div style="color: #000;font-family: sans-serif;padding: 30px;background-color: #ffffffa1 !important;">
					  		'.$mHeader.'
					  		<div id="mailBody" style="font-size:12px;margin-top:20px;margin-bottom:10px">
					  		'.$cBody.'
					  		</div>
					  		'.$mFooter.'
					  		<span style="font-size:10px">Please do not reply to this message, it was sent from an unmonitored email address.</span>
					  	</div>
				  	</div>
				</div>
			';
		}
		else
		{
			$mHeader = ($cHeader <> '') ? $cHeader." " : '';
			$mFooter = ($cFooter <> '') ? " ".$cFooter : '';
			$mailHTML = $mHeader." ".$cBody." ".$mFooter;
		}

		
		return $mailHTML;
	}

	function convertToTextMailFormat($message)
	{
		
		$dom = new DOMDocument();
		@$dom->loadHTML($message); 

		$divsHeader = $dom->getElementById('mailHeader');
		$divsFooter = $dom->getElementById('mailFooter');
		$divsBody 	= $dom->getElementById('mailBody');

		$mHeader = (@$divsHeader->nodeValue <> '') ? @$divsHeader->nodeValue : '';
		$mFooter = (@$divsFooter->nodeValue <> '') ? " ".@$divsFooter->nodeValue : '';
		$newmessage = $mHeader." ".@$divsBody->nodeValue." ".$mFooter;

		return @$newmessage;

	}

	function resendaccountinfo_all($param = '')
	{
		// $user_info_status = $this->sqlhelper->local->select("v_004_user_info_status")->ex_select("","","")->result();
		$user_info_status = $this->sqlhelper->local->select("v_004_user_info_status")->where("User_Type_Code in (5,6,7,8) and User_Activated = 1")->ex_select("","","")->result();
		if($user_info_status['Count'] > 0 )
		{
			$userInfoData = $user_info_status['Data'];

			foreach($userInfoData as $userInfo)
			{
				
				$emailMessage = $this->load->view('email_format/resend_accountinfo', $userInfo, true);

				$mail_message = array(
				      'header' => 'Hi '.$this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$userInfo['Account_Name'],'MCrypt','aes-128','ecb'),
				      'footer' => 'Thank You.',
				      'body' => $emailMessage
				    );

				$mailparam = array(
				  'from'    => $this->config->item('email_sender'),
				  'to'      => $userInfo['User_EmailAddress'],
				  'subject' => $this->config->item('system_name').' Account Information',
				  'message' => $mail_message,
				  'cc'      => '',
				  'bcc'     => ''
				);

				$this->send_mail($mailparam);

				$return['Status'] = 1;

				$ulog = array(
					'userid'=>$param,
				    'type'=>'User Account',
				    'action'=>'Resend Account Information',
				    'description'=>'Resend User Account Information via Email',
				    'remarks'=>'Username : '.$userInfo['User_Name']
				  );

				$this->CI->write_useractivitylog($ulog);
			}
		}	
	}

	function broadcasemessage($param = '')
	{
		// $user_info_status = $this->sqlhelper->local->select("v_004_user_info_status")->ex_select("","","")->result();
		$user_info_status = $this->sqlhelper->local->select("v_004_user_info_status")->where("User_Type_Code = 1")->ex_select("","","")->result();
		if($user_info_status['Count'] > 0 )
		{
			$userInfoData = $user_info_status['Data'];

			foreach($userInfoData as $userInfo)
			{
				$mail_message = array(
				      'header' => 'Hi '.$this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$userInfo['Account_Name'],'MCrypt','aes-128','ecb'),
				      'footer' => 'Thank You.',
				      'body' => base64_decode($param['MessageContent'])
				    );

				$mailparam = array(
				  'from'    => $this->config->item('email_sender'),
				  'to'      => $userInfo['User_EmailAddress'],
				  'subject' => $param['MessageSubject'],
				  'message' => $mail_message,
				  'cc'      => '',
				  'bcc'     => ''
				);

				$this->send_mail($mailparam);

				$return['Status'] = 1;

				$ulog = array(
					'userid'=>$param['userid'],
				    'type'=>'Broadcast Message',
				    'action'=>'Broadcast Message to All Users',
				    'description'=>'Broadcase Message',
				    'remarks'=>''
				  );

				$this->CI->write_useractivitylog($ulog);
			}
		}	
	}

}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */