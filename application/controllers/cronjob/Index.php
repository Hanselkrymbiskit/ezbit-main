<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
set_time_limit(600);
ini_set("default_socket_timeout", 6000);
class Index extends MY_Controller {

	function __construct()
	{
		parent::__construct();
        
        $return = array('Status' => 0, 'Message' => 'Permission Denied!');
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            // ob_end_clean();   
            header("HTTP/1.1 401 Unauthorized");
            header("Pragma: no-cache");
            header("Cache-Control: no-store, no-cache");
            header("Content-type: application/json");
            echo json_encode($return);  
            exit();

        }
        
        if ( !in_array( $this->input->ip_address(), $this->config->item( 'proxy_ips' ) ) )
        {
            $return['Message'] = 'called from untrusted IP-address';
            // ob_end_clean();  
            header("HTTP/1.1 401 Unauthorized"); 
            header("Pragma: no-cache");
            header("Cache-Control: no-store, no-cache");
            header("Content-type: application/json");
            echo json_encode($return);
            exit();
        }
	}
    
    function sendmail()
    {
        if(ob_get_level() > 0) {
            ob_end_clean();
        }

        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache");
        header("Content-type: application/json");

        try
        {
            $returnIDS = (@$_REQUEST['returnIDS'] <> '') ? @$_REQUEST['returnIDS'] : false;
            $mailid = @$_REQUEST['mailid'];
            if( $mailid == '')
            {
                $getAllMailtoSend = $this->sqlhelper->local->select("system_mailsender_log")->where("MailStatus <> 2 and sToEmail is not null")->ex_select("","DateTimeCreated asc","50")->result();

                // echo $getAllMailtoSend['Count'];
                if($getAllMailtoSend['Count'] > 0)
                {
                    if($returnIDS)
                    {
                        $EmailLogIDs = [];
                        foreach($getAllMailtoSend['Data'] as $ERows => $ECols)
                        {
                            $EmailLogIDs[] = base64_encode( (int) $ECols['DataID'] );
                        }

                        echo (count($EmailLogIDs) > 0 ) ? json_encode(['EmailLOGID'=>$EmailLogIDs]) : json_encode(['EmailLOGID'=>'']);
                    }
                    else
                    {
                        $this->load->model('sendmail/sendmail');
                        foreach($getAllMailtoSend['Data'] as $mailRow => $mailCol)
                        {
                            
                            // Recheck if for sending
                            $getCurrentStatus = $this->sqlhelper->local->select("system_mailsender_log")->where("DataID = '".$mailCol['DataID']."'")->ex_select("","DateTimeCreated asc","")->row();

                            if($getCurrentStatus['Count'] > 0 && $getCurrentStatus['Data']['MailStatus'] <> 2)
                            {
                                $maildata = $mailCol;
                                if(is_array($maildata))
                                {
                                    $param = array(
                                      'from'    => $this->config->item('email_sender'),
                                      'to'      => $maildata['sToEmail'],
                                      'subject' => $maildata['sSubject'],
                                      'message' => base64_decode($maildata['sMail']),
                                      'cc'      => $maildata['sCcEmail'],
                                      'bcc'     => $maildata['sBccEmail'],
                                      'attach'  => ($maildata['sAttachmentDir'] <> '') ? base64_decode(jseon_decode($maildata['sAttachmentDir'])) : '',
                                    );

                                    
                                    $resendResult = $this->sendmail->send_mail($param,false,true,true);

                                    if( $resendResult == 1 )
                                    {
                                        $updateMail = array(
                                                'MailStatus' => 2,
                                                'DateTimeSend' => date('Y-m-d H:i:s'),
                                                'DateTimeFailedSend' => '',
                                                'ErrorMessage' => '',
                                            );

                                        $UpdateLog = $this->sqlhelper->local->update("system_mailsender_log")->ex_update($updateMail)->where("DataID = ".(int) $mailCol['DataID'])->run();
                                    }
                                    else
                                    {
                                        $updateMail = array(
                                                'MailStatus' => 1,
                                                'DateTimeFailedSend' => date('Y-m-d H:i:s'),
                                            );

                                        $UpdateLog = $this->sqlhelper->local->update("system_mailsender_log")->ex_update($updateMail)->where("DataID = ".(int) $mailCol['DataID'])->run();
                                    }
                                }
                            } 
                        } 

                        echo json_encode(['Successfully Resend Email']);
                    }
                }
            }
            else
            {
                if( $mailid <> '')
                {
                    $emailid = base64_decode( $mailid );
                    if( $emailid )
                    {
                       
                        $this->load->model('sendmail/sendmail');
                        $getCurrentStatus = $this->sqlhelper->local->select("system_mailsender_log")->where("DataID = '".(int) $emailid."'")->ex_select("","DateTimeCreated asc","")->row();

                        if($getCurrentStatus['Count'] > 0 && $getCurrentStatus['Data']['MailStatus'] <> 2)
                        {
                            $maildata = $getCurrentStatus['Data'];
                            if(is_array($maildata))
                            {
                                $param = array(
                                  'from'    => $this->config->item('email_sender'),
                                  'to'      => $maildata['sToEmail'],
                                  'subject' => $maildata['sSubject'],
                                  'message' => base64_decode($maildata['sMail']),
                                  'cc'      => $maildata['sCcEmail'],
                                  'bcc'     => $maildata['sBccEmail'],
                                  'attach'  => ($maildata['sAttachmentDir'] <> '') ? base64_decode(jseon_decode($maildata['sAttachmentDir'])) : '',
                                );

                                
                                $resendResult = $this->sendmail->send_mail($param,false,true,true);

                                if( $resendResult == 1 )
                                {
                                    $updateMail = array(
                                            'MailStatus' => 2,
                                            'DateTimeSend' => date('Y-m-d H:i:s'),
                                            'DateTimeFailedSend' => '',
                                            'ErrorMessage' => '',
                                        );

                                    $UpdateLog = $this->sqlhelper->local->update("system_mailsender_log")->ex_update($updateMail)->where("DataID = ".$emailid)->run();
                                }
                                else
                                {
                                    $updateMail = array(
                                            'MailStatus' => 1,
                                            'DateTimeFailedSend' => date('Y-m-d H:i:s'),
                                        );

                                    $UpdateLog = $this->sqlhelper->local->update("system_mailsender_log")->ex_update($updateMail)->where("DataID = ".$emailid)->run();
                                }
                            }
                        } 

                        echo json_encode(['Successfully Resend Email']);
                    }
                    else
                    {
                       echo json_encode(['Invalid Email Log ID']);
                    }
                }
                else
                {
                   echo json_encode(['Invalid Email Log ID']);
                }
               
            } 
        }
        catch(Exception $e)
        {
            log_message("error","Error running Send Mail CRON JOB");

            echo json_encode(['Failed to Resend Email']);
        }
    }

}