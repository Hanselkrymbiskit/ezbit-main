<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resetpassword extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->data['pagetype'] = "Resetpassword";
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
    }

    function index($param = '')
    {
        if( $this->tank_auth->is_logged_in() )
        {
            redirect('home');
        }

        if( $param == '' )
        {
            redirect('login');
        }

        $passwordEkey = (is_array($param)) ? $param[0] : $param;
        $passwordkey = base64_decode(base64_decode( $passwordEkey ));

        $curentDateTime = strtotime(date("Y-m-d H:i:s"));

        $userDetails = $this->sqlhelper->local->select("users")->where("new_password_key ='".$passwordkey."'")->ex_select("","","1")->row();

        if($userDetails['Count'] == 0 )
        {
            redirect('login');
        }

        $userDetails = $userDetails['Data'];

        if($userDetails['new_password_key'] == '' || $userDetails['new_password_requested'] == '' )
        {
            redirect('login');
        }

        $minuteslapse = round(abs($curentDateTime - strtotime($userDetails['new_password_requested'])) / 60);
        $hourslapse = round($minuteslapse / 60);

        if($hourslapse > 24)
        {
            $indexPage = "expired";
        }
        else
        {
            $indexPage = "index";

        $this->data['loadcss'] = array(
            'global/vendor/jquery-strength/jquery-strength',
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/jquery-strength/password_strength',
            'global/vendor/jquery-strength/jquery-strength',
            
        );

        $this->data['loadjschild'] = array(
             'global/js/Plugin/jquery-strength',
        );

            $this->data['passwordkey'] = $passwordEkey;
            $this->data['registeredemailaddress'] = $userDetails['email'];
            $this->data['username'] = $userDetails['username'];
            $this->data['accountname'] = $this->m_api->encryptdecryptString("decrypt",$this->system_settings['PasswordHashing'],$this->myutilities->getRef_Desc(1,$userDetails['id'],False,'Account_Name'),'MCrypt','aes-128','ecb');
        }

        $this->data['title'] = "Reset Password"; 
        $this->data['PageTitle'] = '<p style="text-align:center;margin:0px">Create New Account Password</p>'; 
        $this->load->template('templates/resetpassword/'.$indexPage,  $this->data,'',FALSE); // this will load the view file    
       
    }
    
    function submitnewpassword()
    {
        $return = array('Status' => 0, 'Message' => 'Permission Denied!');

        if( $_SERVER['REQUEST_METHOD'] <> 'POST')
        {
            redirect('login');
        }

        $Param = $_POST;

        if( !isset($Param['passwordkey']) || !isset($Param['recaptchatoken']) )
        {
            $return['Message'] = 'Failed to process new Account Password!';
            goto exitSubmitNewPassword;
        }

        if($this->system_settings['ReCaptchaModule'] == 1 && $this->system_settings['ReCaptchaatLogin']  == 1 ){
            if($_SERVER['HTTP_HOST'] <> 'localhost' && $_SERVER['HTTP_HOST'] <> '127.0.0.1')
            {
                $verifyRecaptchaResult = $this->myutilities->google_recaptcha_validate($Param['recaptchatoken']);
            }
            else
            {
                $verifyRecaptchaResult = true; 
            }
        }
        else
        {
            $verifyRecaptchaResult = true; 
        }

        // $verifyRecaptchaResult = $this->myutilities->google_recaptcha_validate($Param['recaptchatoken']);
        if(!$verifyRecaptchaResult)
        {
            $return['Message'] = 'System Authentication Failed!, Invalid ReCaptcha Security';
            goto exitSubmitNewPassword;
        }


        $passwordEkey = $Param['passwordkey'];
        $passwordkey = base64_decode(base64_decode( $passwordEkey ));

        $curentDateTime = strtotime(date("Y-m-d H:i:s"));

        $userDetails = $this->sqlhelper->local->select("users")->where("new_password_key ='".$passwordkey."'")->ex_select("","","1")->row();

        if($userDetails['Count'] == 0 )
        {
           $return['Message'] = 'Creating New Account Password Denied, You do not have a Password Create/Reset Request!';
           goto exitSubmitNewPassword;
        }

        $userDetails = $userDetails['Data'];
        $ulog = array(
            'userid' => @$userDetails['id'],
            'type'=>'User Account',
            'action'=>'Create New Account Password',
            'description'=>'Create New Account Password',
            'remarks'=>''
          );

        if($userDetails['new_password_key'] == '' || $userDetails['new_password_requested'] == '' )
        {
            $return['Message'] = 'Creating New Account Password Denied, You do not have a Password Create/Reset Request!';
            goto exitSubmitNewPassword;
        }

        $minuteslapse = round(abs($curentDateTime - strtotime($userDetails['new_password_requested'])) / 60);
        $hourslapse = round($minuteslapse / 60);

        if($hourslapse > 24)
        {
            $return['Message'] = 'Creating New Account Password Denied, Password Key already Expired!';
            goto exitSubmitNewPassword;
        }
        else
        {
            try
            {
                $genPassword = $this->myutilities->genpasswordhash($Param['newpassword']);

                $updateArray = array(
                    'password'                  => $genPassword,
                    'passwordlegacy'            => $genPassword,
                    'new_password_key'          => '',
                    'new_password_requested'    => '',
                    'modified_by'               => $userDetails['id'],
                    'modified_datetime'         => date("Y-m-d H:i:s",$curentDateTime)
                );

                $updateUsers = $this->sqlhelper->local->update("users")->ex_update($updateArray)->where(" id = ".$userDetails['id'])->run();
                if($updateUsers['ErrorCode'] == "")
                {
                    $return['Status'] = 1;
                    $return['Message'] = 'New Account Password Successfully Created!';

                    // Update Web Service WSKey and EKey
                    $User_WS_Key = base64_encode( $this->encryption->create_key(16) );
                    $User_E_Key = base64_encode( $this->encryption->create_key(16) );

                    $updateProfile = array(
                        'User_WS_Key' => $User_WS_Key,
                        'User_E_Key'  => $User_E_Key,
                    );

                    $updateProfileResult = $this->sqlhelper->local->update("user_profiles")->ex_update($updateProfile)->where("user_id = ".$userDetails['id'])->run();

                   // Send Email
                    $userInfoDetails = $this->myutilities->getUserDetails($userDetails['id']);
                    $userInfoDetails['Account_Name'] = $this->m_api->encryptdecryptString("decrypt",$this->system_settings['PasswordHashing'],$userInfoDetails['Account_Name'],'MCrypt','aes-128','ecb');
                    $emailMessage = 'Your New Account Password is successfully Created on '.date("F d, Y h:i:s A",$curentDateTime).'.<br>';

                    $mail_message = array(
                          'header' => 'Hi '.ucwords(strtolower($userInfoDetails['Account_Name'])),
                          'footer' => 'Thank You.',
                          'body' => $emailMessage
                        );
                    
                    $param = array(
                      'from'    => $this->config->item('email_sender'),
                      'to'      => $userInfoDetails['User_EmailAddress'],
                      'subject' => $this->system_settings['ApplicationAbbre'].' Account Password Successfully Created',
                      'message' => $mail_message,
                      'cc'      => '',
                      'bcc'     => ''
                    );

                    // // Send by Asynchronouse Call
                    $this->daemon->execute_background('sendmail/sendmail','send_mail',$param);                         
                }
            }
            catch(Exception $e)
            {
                $return['Message'] = 'Creating New Account Password Failed!, Please try again later.';
            }
        }

        exitSubmitNewPassword:
        $ulog['remarks'] =  $return['Message'];
        $this->write_useractivitylog($ulog); 
        echo json_encode($return);
    }

    function newrequest()
    {
        if( $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $Param = $_POST;

            if( isset($Param['useridemail']) && $Param['useridemail'] <> '' )
            {
                $Param['useridemail'] = base64_decode($Param['useridemail']);
                $where = (( strpos($Param['useridemail'],"@") > -1 ) ? ' email = ' : ' username = ')."'".$Param['useridemail']."'";
                $userid = $this->sqlhelper->local->select("users")->where($where)->row();
                if( $userid['Count'] > 0)
                {
                    $userid = @$userid['Data']['id'];
                    $getUserDetails = $this->myutilities->getUserDetails(@$userid);
                    $getUserProfile = $this->myutilities->getUserProfile(@$userid);

                    if($userid)
                    {
                        $question = ($getUserProfile['security_question'] <> 99 ) ? $this->myutilities->getRef_Desc(10,$getUserProfile['security_question']) : $getUserProfile['security_question_custom'];    

                        $return = array(
                            'Status'            => 1, 
                            'Message'           => 'User Information Retrieved',
                            'secretquestion'    => $question,
                            'userid'            => base64_encode(base64_encode($userid)),
                        );
                    }
                    else
                    {
                        $return = array('Status' => 0, 'Message' => 'Invalid Username / Email Address');
                    }
                }
                else
                {
                    $return = array('Status' => 0, 'Message' => 'Invalid Username / Email Address');
                }
            }
            else
            {
                $return = array('Status' => 0, 'Message' => 'Forgot Password Failed!');
            }

            echo json_encode($return);
        }
        else
        {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }
    }

    function confirmrequest()
    {
        $ulog = array(
                        'type'=>'Forgot Password',
                        'action'=>'Reset Password',
                        'description'=>'',
                        'remarks'=>'Failed to Reset Account Password'
                      );
        
        $return = array('Status' => 0, 'Message' => 'Forgot Password Failed!');

        if( $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $Param = $_POST;
            if( isset($Param['securityanswer']) && $Param['securityanswer'] <> '' && isset($Param['userid']) && $Param['userid'] <> '' )
            {
                $Param['securityanswer'] = base64_decode($Param['securityanswer']);
                $Param['userid']         = base64_decode(base64_decode($Param['userid']));

                if( $Param['userid']  == "" || $Param['securityanswer'] == "")
                {
                    goto exitNewPassword;
                }

                $UserProfile = $this->myutilities->getUserProfile($Param['userid']);
                if($UserProfile == "")
                {
                    goto exitNewPassword;
                }

                if( trim($UserProfile['security_answer']) <> trim($Param['securityanswer']) )
                {
                    $return['Message'] = "Invalid Security Answer!";
                    goto exitNewPassword;
                }

                // Passed Confirmation
                $return = $this->myutilities->resetpassword($Param['userid']);
                $ulog['remarks'] = $return['Message'];
            }
            else
            {
                $return = array('Status' => 0, 'Message' => 'Forgot Password Failed!');
            }
        }
        else
        {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }


        exitNewPassword:
        $ulog['remarks'] = $return['Message'];
        $ulog['userid'] = @$userid;
        $this->write_useractivitylog($ulog);
        echo json_encode($return);
    }
}