<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Ramsey\Uuid\Uuid;
class Activate extends MY_Controller {

	function __construct()
	{
		parent::__construct();
        $this->data['pagetype'] = "Activate";
	}

    function index($activationid = '')
    {
        
        if( $this->tank_auth->is_logged_in() )
        {
            redirect('home');
        }
        else
        {
            
            $aID = (is_array($activationid)) ? @$activationid[0] : $activationid;

            if( $aID == '')
            {
                redirect('login');
            }

            try
            {
                
                $activationLink = $aID;         
                
                $this->load->model('register/m_register');
                $registrationData = $this->m_register->getRegistrationData($activationLink,true);
                if( !is_array($registrationData) )
                {
                    redirect('login');
                }

                if( $registrationData['activateddatetime'] <> "" || !is_null($registrationData['activateddatetime']) )
                {
                    redirect('login');
                }


                $ai_uuid = Uuid::fromString($activationLink);
                if(  $ai_uuid->getFields()->getVersion() == 5 && $ai_uuid->getFields()->getVariant() == 2 )
                {
                    
                    // Test UUID

                    $tmp_al = $this->myutilities->stringToUuid($registrationData['activationlinkdatetime'].'|'.$registrationData['registrationID']);
                    $tmp_uuidV5 = Uuid::uuid5($tmp_al, $registrationData['registrationID']);
                    $tmp_uuidV5 = $tmp_uuidV5->toString();
                    if( $tmp_uuidV5 <> $activationLink)
                    {
                        redirect('login');
                    }

                    $userProfile = $this->myutilities->getUserProfile($registrationData['user_id']);
                    $accountname = $userProfile['CompleteName'];

                    // Check Activation Link if Expired
                    $dt = date('Y-m-d H:i:s');
                    $datetimeNow = strtotime($dt);
                    $minuteslapse = round(abs($datetimeNow - strtotime($registrationData['activationlinkdatetime'])) / 60);
                    $hourslapse = round($minuteslapse / 60);

                    if( $hourslapse > 24)
                    {
                        $pageLink = 'expired';
                        $ResendActivationLink = $this->myutilities->resendactivationlink($registrationData['user_id']);
                    }
                    else
                    {
                        $pageLink = 'index';

                        // Activate Account
                        $updateReg = $this->sqlhelper->local->update("user_register")->ex_update("activateddatetime = '".$dt."'")->where("registrationID = '".$registrationData['registrationID']."'")->run();    

                        $this->load->model('administrator/m_useraccount');
                        $this->m_useraccount->activateUserAccount($userProfile['user_id'],$dt,true);

                        $emailMessage = 'Your '.$this->system_settings['ApplicationAbbre'].' : '.$this->system_settings['ApplicationName'].' Account is Successfully Activated!.<br>You can now login, using your registered username/email and password';

                        $mail_message = array(
                          'header' => 'Hi '.$accountname,
                          'footer' => 'Thank You.',
                          'body' => $emailMessage
                        );

                        $mailparam = array(
                          'from'    => $this->config->item('email_sender'),
                          'to'      => $registrationData['emailaddress'],
                          'subject' => $this->system_settings['ApplicationAbbre'].' - Account Activation',
                          'message' => $mail_message,
                          'cc'      => '',
                          'bcc'     => ''
                        );

                        // Load Send Mail Model for Sending Email
                        $this->daemon->execute_background('sendmail/sendmail','send_mail',$mailparam);
                    }

                    $this->data['title'] = "Activate Account"; 
                    $this->data['PageTitle'] = '<p style="text-align:center"><b>Account Activation</b></p>'; 
                    $this->load->template('templates/activate/'.$pageLink,$this->data,'',false);
                }
                else
                {
                    redirect('login');
                }

            }
            catch(Exception $e)
            {
                redirect('login');
            }
            
        }
    }

}
   
   