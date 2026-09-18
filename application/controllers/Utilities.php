<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* User Management - Controller */
#[AllowDynamicProperties]
class Utilities extends MY_Controller {
  
    function __construct()
    {
      parent::__construct();  
      $this->sqlhelper->local->setajaxevent(1);
      $this->data['sqlhelper']= $this->sqlhelper->local;

      if( isset($_REQUEST['ZeQ2c26session']) )
      {
        unset($_REQUEST['ZeQ2c26session']);
        if( isset($_POST['ZeQ2c26session']))
        {
          unset($_POST['ZeQ2c26session']);
        }
        
      }

      // log_message('udebug',$_REQUEST);
      if( $_SERVER['REQUEST_METHOD'] == 'POST')
      {
        if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
        {
           if( !$this->tank_auth->is_logged_in() )
           {
            header("HTTP/1.1 401 Unauthorized");
            exit;
           }
           else
           {
              header("HTTP/1.1 401 Unauthorized");
              exit;
              // $return = array('Status' => 0, 'Message' => 'Permission Denied!');
              // echo json_encode($return);
              // die();
           }
        }
        else
        {
          unset($_POST['UToken']);

          if( @$_SERVER['HTTP_REFERER'] <> '' )
          {
            if (version_compare(PHP_VERSION, '8.0', '>='))
            {
              if( str_contains($_SERVER['HTTP_REFERER'],site_url()) )
              {
                goto chk_caller;
              }
              else
              {
                header("HTTP/1.1 401 Unauthorized");
                exit;
              }
            }
            else
            {
              chk_caller:
              if( strpos($_SERVER['HTTP_REFERER'],site_url()) <> 0 )
              {
                header("HTTP/1.1 401 Unauthorized");
                exit;
              }
            }     
          }

          if( $this->config->item('csrf_protection') )
          {
              if( isset($_POST['authenticity_token']) )
              {
               unset($_POST['authenticity_token']);
              }

              if( isset($_REQUEST['authenticity_cookie']) )
              {
                unset($_REQUEST['authenticity_cookie']);
              }
              
              if( isset($_POST['authenticity_cookie']) )
              {
                unset($_POST['authenticity_cookie']);
              }
          }

          if( isset($_POST) && count($_POST) > 0)
          {
              $exludeSegment = [
                'snapshotrequest',
                'snapshotrequestaction',
                'passwordverification',
              ];

              if( isset($this->uri->rsegment_array()[2]) && ( !in_array($this->uri->rsegment_array()[2], $exludeSegment) ) )
              {
                $xsscleanPost = $this->xssCleaner($_POST);
                $_POST = $xsscleanPost; 
              }          
          }
        }      
      }
      
      if( $_SERVER['REQUEST_METHOD'] == 'GET')
      {
        //show_404();
        header("HTTP/1.1 401 Unauthorized");
        exit;
      }
    }

   
    
    function getTime()
    {
        
        
        $return = array(
            'Status'  => 0,
            'Message' => 'Failed to Send Test Mail.',
        );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
          $return['Message'] = date("h:i:s A", time());
          $return['Status'] = 1;
        }

        echo json_encode($return);
    }

    function send_test_mail($internalCall = false)
    {
        $return = array(
            'Status'  => 0,
            'Message' => 'Failed to Send Test Mail.',
        );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
          try{
            // Get Email Format
             $emailMessage = ( (@$_POST['emailcontent'] <> '') ? '
              '.base64_decode($_POST['emailcontent']).'
             ' : '' ).'
             This is a Test Email, Kindly Ignore this email.
             ';

             $mail_message = array(
                  'header' => 'Hi There!',
                  'footer' => 'Thank You.',
                  'body' => $emailMessage
                );

            $param = array(
              'from'    => $this->config->item('email_sender'),
              'to'      => base64_decode($_POST['emailaddress']),
              'subject' => 'Test Mail'.( (@$_POST['emailsubject'] <> '') ? ' : '.base64_decode($_POST['emailsubject']) : '' ),
              'message' => $mail_message,
              'cc'      => '',
              'bcc'     => ''
            );
            
            // Load Send Mail Model for Sending Email
            $this->load->model('sendmail/sendmail'); 
            $SendMail_Result = $this->sendmail->send_mail($param,false,true);    
            if( $SendMail_Result == 1 )
            {
              $return['Status'] = 1;
            }
          }
          catch(Exception $e)
          {

          }
        }

        if( $internalCall )
        {
          return $return;
        }
        else
        {
           echo json_encode($return);
        }
    }


    function UploadProfilePic($internalCall = false)
    {
      $return = array('Status' => 0, 'Message' => '');

      if( $_SERVER['REQUEST_METHOD'] == 'POST')
      {           
        $ImageStrings = str_replace("data:image/png;base64,", "", $_POST['ImageStrings']);
        
        try{
          // Insert Image Strint to Profile [ Photo ]
          $UpdateArray = array(
                          'photo' => $ImageStrings,
                          'updated_by' => $this->userid,
                          'updated_datetime' => date('Y-m-d H:i:s'),
                        );
          $tryUpdate = $this->sqlhelper->local->update("user_profiles")->ex_update($UpdateArray)->where("user_id = ".$this->userid)->run();
          if($tryUpdate['ErrorCode'] == '')
          {
              $return['Status'] = 1;
          }
        }
        catch(Exception $e)
        {

        }

        $return['Status'] = 1;
      }

      if( $internalCall )
      {
        return $return;
      }
      else
      {
         echo json_encode($return);
      }
    }

    // This Function is called by ajax call and will return a json string with following structure
    // array( 0=>array('ref_description' => '', 'ref_value' => '') )
    function getReferenceValue($internalCall = false)
    {
       // log_message("error",$_REQUEST);   
      $return = array('Status' => 0, 'Message' => '');
      if( $_SERVER['REQUEST_METHOD'] == 'POST')
      {           
        $return['Message'] = $this->myutilities->getReferenceValue($_REQUEST);
        if( $return['Message'] <> "" || !is_null($return['Message']) )
        {
          $return['Status'] = 1;
        }
      }

      if( $internalCall )
      {
        return $return;
      }
      else
      {
         echo json_encode($return);
         // log_message ("error",$return);
      }
    }

    // This Function is called to Verify the Current User by Using Password Verification
    function passwordverification()
    {
      $return = array(
                  'Status'  => 0,
                  'Message' => 'Invalid Account Password',
                );

      if( $_SERVER['REQUEST_METHOD'] == 'POST' )
      {
        if(isset($_POST['password_verification']))
        {
          $_POST['password_verification'] = base64_decode($_POST['password_verification']);
          if ($this->form_validation->run()) 
          {
              $submitted_vpassword =  $_POST['password_verification'];
              $user_id = $this->session->userdata('user_id');
              if (!is_null($user = $this->users->get_user_by_id($user_id, TRUE))) 
              {
                $hasher = new PasswordHash(
                    $this->config->item('phpass_hash_strength', 'tank_auth'),
                    $this->config->item('phpass_hash_portable', 'tank_auth')
                  );

                if($hasher->CheckPassword($submitted_vpassword, $user->password))
                {
                  $return['Status'] = 1;
                  $return['Message'] = $this->myutilities->generatePVToken();
                }
              }
          }
        }
      }
      echo json_encode($return);
    }

    function forgotpassword()
    {
        $return = array(
            'Status'  => 0,
            'Message' => '',
        );

        $UserDetails = "";
        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
 
            if( isset($_POST['mode']) )
            {
              if( !isset($_POST['login_credential'] ))
              {
                goto ghere;
              }

              if( $_POST['mode'] == 1)
              {
                  if(filter_var($_POST['login_credential'], FILTER_VALIDATE_EMAIL)) 
                  {
                    if(!$this->users->is_email_available($_POST['login_credential']))
                    {
                      $getUserDetails = $this->sqlhelper->local->select("users")->where("email='".trim($_POST['login_credential'])."'")->ex_select("","","1")->row();
                      $UserDetails = ( $getUserDetails['Count'] > 0 ) ? $getUserDetails['Data'] : "";
                    }
                  }
                  else {
                    $getUserDetails = $this->sqlhelper->local->select("users")->where("username='".trim($_POST['login_credential'])."'")->ex_select("","","1")->row();
                    $UserDetails = ( $getUserDetails['Count'] > 0 ) ? $getUserDetails['Data'] : "";
                  }

                  if(is_array($UserDetails))
                  {
                    $getProfile = $this->sqlhelper->local->select("user_profiles")->where("user_id = '".$UserDetails['id']."'")->ex_select("","","1")->row();
                    $UserProfile = $getProfile['Data'];

                    if( !is_null($UserProfile['security_question']) || $UserProfile['security_question'] <> "" )
                    {
                      $this->session->fp_token = base64_encode(base64_encode($UserDetails['password']."+phielite+".$UserDetails['id']));
                      $this->session->fp_created = time();

                      $security_question = '
                        <table id="myaccount" class="table table-bordered" style="margin-bottom:0px">
                            <tr>
                                <td class="myaccount_caption" style="border-right:0px !important;vertical-align:middle;width:10%" >Security Question</td>
                                <td class="myaccount_value" style="border-left:0px !important">
                                  <input title="Security Question" type="text" id="sq_question" name="" readonly="readonly" class="form-control" style="vertical-align:middle;Background:#fff" value="'.( ($UserProfile['security_question'] == 20) ? $UserProfile['security_question_custom'] : $this->myutilities->getRef_Desc(10,$UserProfile['security_question'],false) ).'">
                                </td>
                            </tr>
                            <tr>
                                <td class="myaccount_caption" style="border-right:0px !important;vertical-align:middle" >Security Answer</td>
                                <td class="myaccount_value" style="border-left:0px !important"><input title="Security Answer" type="password" id="security_answer" name="form_data" class="form-control" style="vertical-align:middle" value=""></td>
                            </tr>
                        </table>
                      ';

                      $security_question = trim(preg_replace('/^\s+|\n|\r|\s+$/m', "", $security_question));

                      $return['Status'] = 1;
                      $return['Message'] = base64_encode($security_question);
                      $return['Token'] = $this->session->fp_token;

                    }
                    else
                    {
                      $return['Message'] = "Account Security is not set to your account, Please contact ".$this->config->config['system_short_name']." Helpdesk Support for Password Retrieval.";
                    }
                  }
                  else
                  {
                    $return['Message'] = "Invalid User Name or Email Address!";
                  }
              }
              else if( $_POST['mode'] == 2 && isset($_SESSION['fp_token']) )
              {
                if(time() - $this->session->fp_created > 300) {
                  // Expired Sessiong Forgot Password Cancel
                  $return['Message'] = "Your Forgot Password Session has expired, Please try again later.";
                  unset($_SESSION['fp_token']);
                }
                else
                {
                  if( !isset($_POST['token'] ))
                  {
                    goto ghere;
                  }

                  if( $_POST['token'] == $this->session->fp_token)
                  {
                    $parseToken = explode("+phielite+", base64_decode(base64_decode($_POST['token'])) );
                    if(count($parseToken) == 2)
                    {
                      $userid = $parseToken[1];
                      $getUserDetails = $this->sqlhelper->local->select("users")->where("id='".trim($userid)."'")->ex_select("","","1")->row();
                      $UserDetails = ( $getUserDetails['Count'] > 0 ) ? $getUserDetails['Data'] : "";

                      if( is_array($UserDetails) )
                      {
                        $getProfile = $this->sqlhelper->local->select("user_profiles")->where("user_id = '".$UserDetails['id']."'")->ex_select("","","1")->row();
                        $UserProfile = $getProfile['Data'];

                        if(trim($_POST['login_credential']) == trim($UserProfile['security_answer']) )
                        {
                          $return['Status'] = 1;
                          $return['Message'] = "Account Password Successfully Retrieved, please check your email for account information.";

                          $this->resendaccountinfo($UserDetails['id'],'Y');
                        }
                        else
                        {
                          $return['Message'] = "Incorrect Secret Answer!";
                        }
                      }
                      else
                      {
                        $return['Message'] = "Invalid User Name or Email Address!";
                      }
                      
                    }
                    else
                    {
                      $return['Message'] = "Incorrect Secret Answer!";
                    }
                  }
                  else
                  {
                    $return['Message'] = "Incorrect Secret Answer!";
                  }
                }
              } 
            }

             
        }
        ghere:
        echo json_encode($return);
    }

    function checkemailexists()
    {
      $return = array(
          'Status'  => 0,
          'Message' => 'Failed to Verify Email Address!',
        );

      if(  $_SERVER['REQUEST_METHOD'] == 'POST' )
      {
        if( !isset($_POST['eaddrs']) )
        {
          goto ghere;
        }

        $return['Status'] = 1;
        $return['Message'] = 'Email Address do not exists';
        if( $this->myutilities->chk_registered_email($_POST['eaddrs']) )
        {
          $return['Status'] = 0;
          $return['Message'] = 'Email Address is already Exists';
        }

        if( $this->myutilities->chk_user_email($_POST['eaddrs']) )
        {
          $return['Status'] = 0;
          $return['Message'] = 'Email Address is already Exists';
        }
      }
      ghere:
      echo json_encode($return);
    }

    // This Function is used to change user account email address
    function changeemail()
    {
        $return = array(
            'Status'  => 0,
            'Message' => 'Failed to Change Account Email Address, Please try again later!',
          );

        if(  $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            // if(count($_REQUEST) <> 4)
            // {
            //   goto goHere;
            // }
            
            if( !isset($_POST['PVToken']) )
            {
              goto goHere;
            }


            $PVToken = @$_REQUEST['PVToken'];
            if($PVToken <> '')
            {
              if( !$this->myutilities->checkPVToken($PVToken) )
              {
                goto goHere;
              }
            }
            else
            {
              goto goHere;
            }
        }

        ContinueProcess:

        if( !isset($_POST['new_email']) || !isset($_POST['user_id']) )
        {
          goto goHere;
        }



        $ulog = array(
          'type'=>'User Account',
          'action'=>'Change Email Address',
          'description'=>'Change User Account Email Address',
          'remarks'=>''
        );



        if( $this->myutilities->chk_registered_email($_POST['new_email']) )
        {
          $regInfo = $this->myutilities->getRegistrationInfo($_POST['user_id']);
          if( trim($_POST['new_email']) == trim($regInfo['emailaddress']) )
          {
            goto ContinueNow;
          }

          $return['Message'] = 'New Email Address is already Exists!';
          goto goHere;
        }

        if( $this->myutilities->chk_user_email($_POST['new_email']) )
        {
          $return['Message'] = 'New Email Address is already Exists!';
          goto goHere;
        }

        ContinueNow:
        $userDetails = $this->myutilities->getUserDetails($_POST['user_id']);
        $userProfile = $this->myutilities->getUserProfile($_POST['user_id']);
        if($userDetails == "")
        {
          goto goHere;
        }

        try
        {
          // Change User Email Address
          $setUpdate = array(
                'email' => $_POST['new_email'],
                'modified_datetime' => date('Y-m-d H:i:s'),
                'modified_by' => $this->userid
            );
          $updateEmail = $this->sqlhelper->local->update('users')->ex_update($setUpdate)->where("id = ".$userDetails['User_ID'])->run();
          if( ($updateEmail['ErrorCode'] == '') )
          {
            // Update User Profile Email
            $setUpdateProfile = array(
                'emailaddress' => $_POST['new_email'],
                'updated_datetime' => $setUpdate['modified_datetime'],
                'updated_by' => $this->userid
            );
            $updateEmail = $this->sqlhelper->local->update('user_profiles')->ex_update($setUpdateProfile)->where("user_id = ".$userDetails['User_ID'])->run();
            $return['Status']=1;
            $return['Message'] = 'Account Email Address Successfully Changed to <b>'.$_POST['new_email'].'</b>';


          }
        }
        catch(Exception $e)
        {
          
        }

        goHere:
        $ulog['remarks'] = $return['Message'].(($return['Status'] == 1) ? ' for the Account User Name : '.$userDetails['User_Name'] : '');
        $this->write_useractivitylog($ulog);
        echo json_encode($return);
    }
    
    function resendallaccountinfo()
    {
        if( !$this->tank_auth->is_logged_in() || $this->data['usertype'] > 2)
        {
          redirect('login');
        }

        $return = array(
            'Status'  => 0,
            'Message' => '',
          );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            $this->daemon->execute_background('sendmail/sendmail','resendaccountinfo_all',$this->tank_auth->get_user_id());

            $return['Status'] = 1;

            $ulog = array(
                'type'=>'User Account',
                'action'=>'Resend Account Information',
                'description'=>'Resend User Account Information via Email',
                'remarks'=>'Resend All Account Information'
              );

            $this->write_useractivitylog($ulog);

          
        }

        if($userid <> '')
        {
          return $return;
        }
        else
        {
          echo json_encode($return);
        }
        
    }

    function checkusernameexists()
    {
      $return = array(
          'Status'  => 0,
          'Message' => 'Failed to Verify User Name!',
      );

      if( !isset($_POST['unme']) )
      {
        goto goHere;
      }

      if(  $_SERVER['REQUEST_METHOD'] == 'POST' )
      {
        $return['Status'] = 1;
        if( !$this->users->is_username_available($_POST['unme']) )
        {
          $return['Status'] = 0;
        }

        $chku = $this->sqlhelper->local->select('users')->where("username='".$this->db->escape_str($_POST['unme'])."'")->result();
      }
      goHere:
      echo json_encode($return);
    }

    // This Function is used to change User Account User Name
    function changeusername()
    {
        $return = array(
            'Status'  => 0,
            'Message' => '',
          );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {  
          if(count($_POST) == 4)
          {
            $token = 'vp_token_'.$this->session->view_userid;
            if( isset($_POST['vptoken']) && base64_decode($_POST['vptoken']) == $this->session->$token )
            {
              if ($this->form_validation->run()) 
              {
                if(!$this->users->is_username_available($_POST['new_username']))
                {
                 $return['Message']=base64_encode('<span class="text-danger"><i class="fa fa-warning"></i>&nbsp;New User Name already exists.</span>');
                }
                else
                {
                  // Change User Email Address
                  $setUpdate = array(
                        '`username`' => $_POST['new_username'],
                        '`modified`' => date('Y-m-d H:i:s'),
                        'modified_by' => $this->tank_auth->get_user_id()
                    );
                  $updateUserName = $this->sqlhelper->local->update("`".$this->config->item('usertable','tank_auth')."`")->ex_update($setUpdate)->where("`id` = '".$_POST['user_id']."'")->run();
                  if( ($updateUserName['ErrorCode'] == '' ) )
                  {
                    $this->session->view_username = $_POST['new_username'];
                    $return['Status']=1;
                    $return['Message']=base64_encode('<div class="text-success" style="text-align:center">Email Notification Successfully Sent.</div>');

                    $getUemail = $this->sqlhelper->local->select("`".$this->config->item('usertable','tank_auth')."`","`email`")->where("`id`='".$_POST['user_id']."'")->row();

                    $mailhtml = '
                       <p>You\'re Account User Name was successfully changed by the Administrator.</p>
                       <h3>New User Name : '.$_POST['new_username'].'</h3>
                       <p>Please use the new username the next time you login to the '.$this->config->item('system_name').'.</p>
                    ';

                    $mail_message = array(
                      'header' => 'Hi '.$_POST['current_username'].".",
                      'footer' => 'Thank You.',
                      'body' => $mailhtml,
                    );

                    // Send Email Notification in Background
                    $param = array(
                                    'from'    => $this->config->item('email_sender'),
                                    'to'      => $getUemail['Data']['email'],
                                    'subject' => $this->config->item('system_name').' Account Information Update',
                                    'message' => $mail_message,
                                    'cc'      => '',
                                    'bcc'     => ''
                                  );

                   // $this->daemon->execute_background('sendmail','send_mail',$param);
                   
                    $ulog = array(
                        'type'=>'User Account',
                        'counterno'=>'',
                        'action'=>'Change User Name',
                        'description'=>'Change User Account Username',
                        'remarks'=>'Current User Name : '.$_POST['current_username'].' to New User Name : '.$_POST['new_username']
                      );

                    $this->write_useractivitylog($ulog);
                  }
                  else
                  {
                      $return['Message']=base64_encode('<span class="text-danger"><i class="fa fa-warning"></i>&nbsp;Failed to change User Name.</span>');
                  }
                }            
                
              }
              
            }
            else
            {
              $return['Message']=base64_encode(validation_errors('<span class="text-danger"><i class="fa fa-warning"></i>&nbsp;', '</span>'));
            }
          }
        }

        echo json_encode($return);
    }

    // This Function is used to resend account information
    function resendaccountinfo($userid = '',$forgotpassword = 'N')
    {
        $return = array(
            'Status'  => 0,
            'Message' => '',
          );

        if(  $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            // if(count($_REQUEST) <> 3)
            // {
            //   goto goHere;
            // }

            if( !isset($_POST['PVToken']) )
            {
              goto goHere;
            }

            $PVToken = @$_POST['PVToken'];
            if($PVToken <> '')
            {
              if( !$this->myutilities->checkPVToken($PVToken) )
              {
                goto goHere;
              }
            }
            else
            {
              goto goHere;
            }
        }

        ContinueProcess:

        if( !isset($_POST['user_id']) )
        {
          goto goHere;
        }


        $user_id = (@$userid <> '') ? $userid : ((@$_POST['user_id'] <> '') ? trim($_POST['user_id']) : '');

        if(@$user_id == '')
        {
          goto goHere;
        }

        // Get Info
        $user_info_status = $this->sqlhelper->local->select("v_001_user_infostatus")->where("User_ID = '".$user_id."'")->ex_select("","","1")->row();
        $userInfo = $user_info_status['Data'];

        $userInfo['Account_Name'] = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$userInfo['Account_Name'],'MCrypt','aes-128','ecb');

        $emailMessage = $this->load->view('email_format/resend_accountinfo', $userInfo, true);

        $mail_message = array(
              'header' => 'Hi '.ucwords(strtolower(@$userInfo['Account_Name'])),
              'footer' => 'Thank You.',
              'body' => $emailMessage
            );
        
        $param = array(
          'from'    => $this->config->item('email_sender'),
          'to'      => $userInfo['User_EmailAddress'],
          'subject' => $this->system_settings['ApplicationAbbre'].' Account Information',
          'message' => $mail_message,
          'cc'      => '',
          'bcc'     => ''
        );

        // // Send by Asynchronouse Call
        $this->daemon->execute_background('sendmail/sendmail','send_mail',$param);

        $return['Status'] = 1;

        $ulog = array(
            'type'=>'User Account',
            'action'=>'Resend Account Information',
            'description'=>'Resend User Account Information via Email',
            'remarks'=>'Account Information Successfully Resend to Username : '.$userInfo['User_Name']
          );

        $this->write_useractivitylog($ulog);

 
        goHere:
        if($userid <> '')
        {
          return $return;
        }
        else
        {
          echo json_encode($return);
        }
        
    }

     // This Function is used to Reset Account Password
    function resendactivationlink()
    {
        $return = array(
            'Status'  => 0,
            'Message' => 'Failed to Resend Activation Link',
          );

        $ulog = array(
                    'type'=>'User Account',
                    'action'=>'Resend User Account Activation Link',
                    'description'=>'Generate New Account Activation Link.',
                    'remarks'=>'Failed to Resend Activation Link'
                  );
        if(  $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            // if(count($_REQUEST) <> 3)
            // {
            //   goto goHere;
            // }

            if( !isset($_POST['PVToken']) )
            {
              goto goHere;
            }

            $PVToken = @$_REQUEST['PVToken'];
            if($PVToken <> '')
            {
              if( !$this->myutilities->checkPVToken($PVToken) )
              {
                goto goHere;
              }
            }
            else
            {
              goto goHere;
            }
        }

        ContinueProcess:
        if( !isset($_POST['user_id']) )
        {
          goto goHere;
        }
        $userDetails = $this->myutilities->getUserDetails($_POST['user_id']);
        if($userDetails == "")
        {
          $ulog['remarks'] = "User Account Details Doesn't Exists for User ID : ".$_POST['user_id'];
          goto goHere;
        }
     
        $return = $this->myutilities->resendactivationlink($_POST['user_id'],$userDetails);

        goHere:
        $ulog['remarks'] = $return['Message'];
        $this->write_useractivitylog($ulog);
        echo json_encode($return);
    }
    
    // This Function is used to Reset Account Password
    function resetpassword()
    {
        $return = array(
            'Status'  => 0,
            'Message' => 'Failed to Reset Account Password',
          );
        // log_message("error", $_REQUEST );
        $ulog = array(
                    'type'=>'User Account',
                    'action'=>'Reset User Account Password',
                    'description'=>'Generate New Password Key for Creating New Password.',
                    'remarks'=>'Failed to Reset Account Password'
                  );
        if(  $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            // if(count($_REQUEST) <> 3)
            // {
            //   goto goHere;
            // }
            if( !isset($_POST['PVToken']) )
            {
              goto goHere;
            }
            $PVToken = @$_REQUEST['PVToken'];
            if($PVToken <> '')
            {
              if( !$this->myutilities->checkPVToken($PVToken) )
              {
                goto goHere;
              }
            }
            else
            {
              goto goHere;
            }
        }

        ContinueProcess:
        if( !isset($_POST['user_id']) )
            {
              goto goHere;
            }
        $userDetails = $this->myutilities->getUserDetails($_POST['user_id']);
        if($userDetails == "")
        {
          $ulog['remarks'] = "User Account Details Doesn't Exists for User ID : ".$_POST['user_id'];
          goto goHere;
        }
     
        $return = $this->myutilities->resetpassword($_POST['user_id']);

        goHere:
        $ulog['remarks'] = $return['Message'];
        $this->write_useractivitylog($ulog);
        echo json_encode($return);
    }

    // This Function is used to Change Account Status
    function changeaccountstatus()
    {
        $return = array(
            'Status'  => 0,
            'Message' => 'Failed to Change User Account Status, Please try again later!',
          );

        $ulog = array(
                    'type'=>'User Account',
                    'action'=>'Change Account Status',
                    'description'=>'Change Account Status [ Activate / Deactivate ]',
                    'remarks'=>'Failed to Change Account Status'
                  );

        if(  $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            // if(count($_REQUEST) <> 4)
            // {
              
            //   goto goHere;
            // }

            if( !isset($_POST['PVToken']) )
            {
              $return['Message'] = 'Unauthorize Access Detected!';
              goto goHere;
            }


            $PVToken = @$_REQUEST['PVToken'];
            if($PVToken <> '')
            {
              if( !$this->myutilities->checkPVToken($PVToken) )
              {
                goto goHere;
              }
            }
            else
            {
              goto goHere;
            }
        }

        ContinueProcess:

          if( !isset($_POST['user_id']) || !isset($_POST['status'])  )
            {
              goto goHere;
            }


          $userID = $_POST['user_id'];
          $newStatus = $_POST['status'];

          $updateArray = array(
            'active'              =>$newStatus ,
            'modified_by'         =>$this->userid,
            'modified_datetime'   =>date('Y-m-d H:i:s'),
          );

          $userDetails = $this->myutilities->getUserDetails($userID);
          if( $userDetails == "")
          {
            $return['Message'] = "User Account Details Doesn't Exists for User ID : ".$_POST['user_id'];
            goto goHere;
          }

          if( $userDetails['User_Activated_DateTime'] == "" )
          {
            $return['Message'] = "User Account Details is not yet Activated by the User - Manual Activation/Deactivation is not allowed";
            goto goHere;
          }

          try
          {
            $updateUser = $this->sqlhelper->local->update("users")->ex_update($updateArray)->where("id = ".$userID)->run();
            if($updateUser['ErrorCode'] == "")
            {
                $insertStatusLog = array(
                    'userid' => $userID,
                    'userstatus' => $newStatus,
                    'created_datetime' => $updateArray['modified_datetime'],
                    'created_by' => $updateArray['modified_by'],
                );
                $runInsert = $this->sqlhelper->local->insert("users_status_log")->ex_insert($insertStatusLog)->run();

                $return['Status'] = 1;
                $return['Message'] = 'User Account Successfully '.(($newStatus == 1) ? 'Activated' : 'Deactivated');

                // SEND MAIL
                $emailMessage = 'Your '.$this->system_settings['ApplicationAbbre'].' Account has been '.(($newStatus == 1) ? 'Activated' : 'Deactivated').'.<br>';

                $emailMessage .= 'If you have any concern/inquiry please contact our Helpdesk Support.';

                $mail_message = array(
                      'header' => 'Hi '.ucwords(strtolower($userDetails['Account_Name'])),
                      'footer' => 'Thank You.',
                      'body' => $emailMessage
                    );
                
                $param = array(
                  'from'    => $this->config->item('email_sender'),
                  'to'      => $userDetails['User_EmailAddress'],
                  'subject' => $this->system_settings['ApplicationAbbre'].' Account '.(($newStatus == 1) ? 'Activated' : 'Deactivated'),
                  'message' => $mail_message,
                  'cc'      => '',
                  'bcc'     => ''
                );

                // // Send by Asynchronouse Call
                $this->daemon->execute_background('sendmail/sendmail','send_mail',$param);
            }
            else
            {
              log_message("debug",$updateUser);
            }
          }
          catch(Exception $e)
          {

          }


        goHere:
        $ulog['remarks'] = $return['Message'];
        $this->write_useractivitylog($ulog);

        echo json_encode($return);
    }

    // This Function is used to mark user account as deleted
    function removeaccount()
    {
        $return = array(
            'Status'  => 0,
            'Message' => '',
          );

        $ulog = array(
                    'type'=>'User Account',
                    'action'=>'Remove User Account',
                    'description'=>'Remove Selected User Account',
                    'remarks'=>'Failed to Remove User Account'
                  );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) == 2)
        {
          $token = 'vp_token_'.$this->session->view_userid;
          if( $this->form_validation->run()&& (isset($_POST['vptoken']) && base64_decode($_POST['vptoken']) == $this->session->$token ) )
          { 
              $user_id = $_POST['user_id'];
              $u_row = $this->sqlhelper->local->select("`users`")->where("`id` = '".$_POST['user_id']."'")->ex_select("","","1");
              if($u_row->count() > 0)
              {
                $user_details = $u_row->row();
                $udata = array(
                    'activated'=>'0',
                    'deleted'=>'Y',
                    'deleted_by'=>$this->tank_auth->get_user_id(),
                    'modified'=>date('Y-m-d H:i:s'),
                    'modified_by' => $this->tank_auth->get_user_id(),
                    'deleteddatetime'=>date('Y-m-d H:i:s')
                  );

                $update_password = $this->sqlhelper->local->update("`users`")->ex_update($udata)->where("`id` = '".$_POST['user_id']."'")->run();
                // log_message("error",json_encode($update_password));
                if( ($update_password['ErrorCode'] == '') )
                {
                  $return['Status']=1;
                  $ulog['remarks'] = 'Account Successfully Deleted : User ID =  '.$_POST['user_id'];
                  // Send Mail
                  $mailhtml = '
                     <p>You\'re '.$this->config->item('system_name').' Account was successfully deleted by the Administrator.</p>
                     <p>Please contact the '.$this->config->item('system_name').' Administrator for any inquiry.</p>
                  ';
                  $mail_message = array(
                    'header' => 'Hi '.$user_details['Data']['username'].".",
                    'footer' => 'Thank You.',
                    'body' => $mailhtml,
                  );

                  $param = array(
                                  'from'    => $this->config->item('email_sender'),
                                  'to'      => $user_details['Data']['email'],
                                  'subject' => $this->config->item('system_name').' Account Deleted',
                                  'message' => $mail_message,
                                  'cc'      => '',
                                  'bcc'     => ''
                                );

                 $this->daemon->execute_background('sendmail','send_mail',$param);
                }
              } 
          }     
        }

        $this->write_useractivitylog($ulog);

        echo json_encode($return);
    }

    // This Function is used to Renew User Account
    function renewaccount()
    {
        $return = array(
            'Status'  => 0,
            'Message' => 'Failed to Renew Account Expiration',
          );

        $ulog = array(
                    'type'=>'User Account',
                    'action'=>'Renew User Account Expiration',
                    'description'=>'Change Account Status [ Activate / Deactivate ]',
                    'remarks'=>'Failed to Renew User Account Expiration'
                  );

        if(  $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            // if(count($_REQUEST) <> 3)
            // {
            //   $return['Message'] = 'Unauthorize Access Detected!';
            //   goto goHere;
            // }

            if( !isset($_POST['PVToken'])   )
            {
               $return['Message'] = 'Unauthorize Access Detected!';
              goto goHere;
            }

            $PVToken = @$_REQUEST['PVToken'];
            if($PVToken <> '')
            {
              if( !$this->myutilities->checkPVToken($PVToken) )
              {
                goto goHere;
              }
            }
            else
            {
              goto goHere;
            }
        }

        ContinueProcess:
        if( !isset($_POST['user_id'])   )
            {
              goto goHere;
            }
        $userID = $_POST['user_id'];
        $userDetails = $this->myutilities->getUserDetails($userID);
        if( $userDetails == "")
        {
          $return['Message'] = "User Account Details Doesn't Exists for User ID : ".$_POST['user_id'];
          goto goHere;
        }

        $DtNow = date("Y-m-d");
        if( $DtNow > $userDetails['User_Expiration'] )
        {
          $newExpiryDate = (date("Y")+1).'-12-31';
          $updateUsersArray = array(
                      'expirydate'        => $newExpiryDate,
                      'modified_by'       => $this->userid,
                      'modified_datetime' => date('Y-m-d H:i:s'),
                    );
          try
          {
            $updateUsers = $this->sqlhelper->local->update("users")->ex_update($updateUsersArray)->where("id = ".$userID)->run();
            if($updateUsers['ErrorCode'] == "")
            {
                $return['Status'] = 1;
                $return['Message'] = "User Account Expiration Successfully Renew.";

                $insertStatusLog = array(
                    'userid' => $userID,
                    'userstatus' => ($userDetails['User_Activated'] == 3) ? (($userDetails['User_Activated'] == 1) ? 1 : 2) : $userDetails['User_Activated'],
                    'created_datetime' => $updateUsersArray['modified_datetime'],
                    'created_by' => $updateUsersArray['modified_by'],
                );
                $runInsert = $this->sqlhelper->local->insert("users_status_log")->ex_insert($insertStatusLog)->run();

                // SEND MAIL
                $emailMessage = 'Your '.$this->system_settings['ApplicationAbbre'].' User Account Information was successfully renewed by the administrator.<br>';

                $mail_message = array(
                      'header' => 'Hi '.ucwords(strtolower($userDetails['Account_Name'])),
                      'footer' => 'Thank You.',
                      'body' => $emailMessage
                    );
                
                $param = array(
                  'from'    => $this->config->item('email_sender'),
                  'to'      => $userDetails['User_EmailAddress'],
                  'subject' => $this->system_settings['ApplicationAbbre'].' Account Expiration Renew',
                  'message' => $mail_message,
                  'cc'      => '',
                  'bcc'     => ''
                );

                // // Send by Asynchronouse Call
                $this->daemon->execute_background('sendmail/sendmail','send_mail',$param);
            }
            else
            {

            }
          }
          catch(Exception $e)
          {

          }
        }
        else
        {
          $return['Message'] = 'User Account is not Expired!';
          goto goHere;
        }


        goHere:
        $ulog['remarks'] = $return['Message'];
        $this->write_useractivitylog($ulog);

        echo json_encode($return);
    }

    // This Function is used to Unbanned User Account
    function changebannedstatus()
    {
        $return = array(
            'Status'  => 0,
            'Message' => 'Failed to Change User Account Status, Please try again later!',
          );

        $ulog = array(
                    'type'=>'User Account',
                    'action'=>'Change Account Status',
                    'description'=>'Change Account Status [ Activate / Deactivate ]',
                    'remarks'=>'Failed to Change Account Status'
                  );

        if(  $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
           
            // if(count($_REQUEST) <> 5 )
            // {
            //   $return['Message'] = 'Unauthorize Access Detected!';
            //   goto goHere;
            // }
            if( !isset($_POST['PVToken'])   )
            {
               $return['Message'] = 'Unauthorize Access Detected!';
              goto goHere;
            }

            $PVToken = @$_POST['PVToken'];
            if($PVToken <> '')
            {
              if( !$this->myutilities->checkPVToken($PVToken) )
              {
                goto goHere;
              }
            }
            else
            {
              goto goHere;
            }
        }

        ContinueProcess:

           if( !isset($_POST['user_id']) || !isset($_POST['status'])  )
            {
              goto goHere;
            }

          $userID = $_POST['user_id'];
          $newStatus = $_POST['status'];
          $reason = @$_POST['reason'];
          $updateArray = array(
            'banned'              =>$newStatus,
            'ban_reason'       =>(($newStatus == 0) ?  "" : $reason),
            'ban_datetime'        =>(($newStatus == 0) ?  "" : date('Y-m-d H:i:s')),
            'modified_by'         =>$this->userid,
            'modified_datetime'   =>date('Y-m-d H:i:s'),
          );

          $userDetails = $this->myutilities->getUserDetails($userID);
          if( $userDetails == "")
          {
            $return['Message'] = "User Account Details Doesn't Exists for User ID : ".$_POST['user_id'];
            goto goHere;
          }

          try
          {
            $updateUser = $this->sqlhelper->local->update("users")->ex_update($updateArray)->where("id = ".$userID)->run();
            if($updateUser['ErrorCode'] == "")
            {
                $insertStatusLog = array(
                    'userid' => $userID,
                    'userstatus' => ($newStatus == 1) ? 4 : (($userDetails['User_Status'] == 4) ? (($userDetails['User_Activated'] == 1) ? 1 : 2) : $userDetails['User_Status']),
                    'created_datetime' => $updateArray['modified_datetime'],
                    'created_by' => $updateArray['modified_by'],
                );
                $runInsert = $this->sqlhelper->local->insert("users_status_log")->ex_insert($insertStatusLog)->run();

                $return['Status'] = 1;
                $return['Message'] = 'User Account Successfully '.(($newStatus == 1) ? 'Banned' : 'Unbanned');

                // SEND MAIL
                $emailMessage = 'Your '.$this->system_settings['ApplicationAbbre'].' Account has been '.(($newStatus == 1) ? 'Banned' : 'Unbanned').'.<br>';

                $emailMessage .= 'If you have any concern/inquiry please contact our Helpdesk Support.';

                $mail_message = array(
                      'header' => 'Hi '.ucwords(strtolower($userDetails['Account_Name'])),
                      'footer' => 'Thank You.',
                      'body' => $emailMessage
                    );
                
                $param = array(
                  'from'    => $this->config->item('email_sender'),
                  'to'      => $userDetails['User_EmailAddress'],
                  'subject' => $this->system_settings['ApplicationAbbre'].' Account '.(($newStatus == 1) ? 'Banned' : 'Unbanned'),
                  'message' => $mail_message,
                  'cc'      => '',
                  'bcc'     => ''
                );

                // // Send by Asynchronouse Call
                $this->daemon->execute_background('sendmail/sendmail','send_mail',$param);
            }
            else
            {
              log_message("debug",$updateUser);
            }
          }
          catch(Exception $e)
          {

          }


        goHere:
        $ulog['remarks'] = $return['Message'];
        $this->write_useractivitylog($ulog);

        echo json_encode($return);
    }

    // This Function is used to check online user
    function onlineuser($callInside = false)
    {
      if( $_SERVER['REQUEST_METHOD'] == 'POST')
      {
        echo $this->myutilities->onlineuser(@$_POST['userid']); 
      }
      else
      {
        echo json_encode( array( 
        "Status"=>0,
        "Message"=>""
        ));
      }
    }
    
    function logoutcurrentuser()
    {
      if( $_SERVER['REQUEST_METHOD'] == 'POST')
      {
        if( is_array($_POST['userid']) )
        {
          foreach($_POST['userid'] as $u => $userid)
          {
            $r = $this->myutilities->logoutonlineuser($userid); 
            // log_message("error",$r);
          }

          echo json_encode( array( 
          "Status"=>1,
          "Message"=>""
          ));
        }
        else
        {
          echo $this->myutilities->logoutonlineuser(@$_POST['userid']); 
        }
      }
      else
      {
        echo json_encode( array( 
        "Status"=>0,
        "Message"=>""
        ));
      }
    }

    function get_icd10_form()
    {
      $return = array( 
        "Status"=>0,
        "Message"=>""
      );

      if( $_SERVER['REQUEST_METHOD'] == 'POST')
      {
        // $this->data['MultiSelect'] = (isset($_POST['MultiSelect']) ?  $_POST['MultiSelect']:false;
        // $this->data['Target_Object'] = (isset($_POST['Target_Object']) ?  $_POST['Target_Object']:'';
        $html_content = $this->load->view('Utilities/icd10_form', $_POST, true);
        $html_content = trim(preg_replace('/^\s+|\n|\r|\s+$/m', "", $html_content));
        $return['Status'] = 1;
        // $return['Message'] = base64_encode(json_encode($_POST)); //$html_content;
        $return['Message'] = base64_encode($html_content);
      }
      
      echo json_encode($return);
    }

    function get_icd_list()
    {
      $return = array( 
        "Status"=>0,
        "Message"=>""
      );

      if( $_SERVER['REQUEST_METHOD'] == 'POST')
      {
          $table = 'ref_icd10_code';

          // Table's primary key
          $primaryKey = 'ICD10_CODE';
          
          $columns = array(
              array(
                  'db' => 'ICD10_CODE',
                  'dt' => 'DT_RowId',
                  'field' =>'ICD10_CODE',
                  'formatter' => function( $d, $row ) {
                      // Technically a DOM id cannot start with an integer, so we prefix
                      // a string. This can also be useful if you have multiple tables
                      // to ensure that the id is unique with a different prefix
                      return 'row_'.$d;
                  }
              ),
              array(
                  'db' => 'ICD10_CODE',
                  'dt' => 'DT_RowClass',
                  'field' =>'ICD10_CODE',
                  'formatter' => function( $d, $row ) {
                      // Technically a DOM id cannot start with an integer, so we prefix
                      // a string. This can also be useful if you have multiple tables
                      // to ensure that the id is unique with a different prefix
                      return 'icd_data_row';
                  }
              ),
              // array( 'db' => 'ICD10_CODE', 'dt' => 0 ,'field' =>'ICD10_CODE','formatter' => function( $d, $row ) {
              //         return base64_encode('<button type="button" id="btn_select_icd" icd_code="'.$d.'" class="btn"><i class="fa fa-reply"></i></button>');
              //     }),
              array( 'db' => 'ICD10_CODE', 'dt' => 0 ,'field' =>'ICD10_CODE','formatter' => function( $d, $row ) {
                      return $d;
                    } ),
              array( 'db' => 'ICD10_DESC',  'dt' => 1 ,'field' =>'ICD10_DESC','formatter' => function( $d, $row ) {
                      return $d;
                    } ),
             
          );
          
          $sql_details = array(
            'user' =>$this->db->username,
            'pass' => $this->db->password,
            'db'   => $this->db->database,
            'host' => $this->db->hostname
          );

          $joinQuery = "";
          $extraWhere = (isset($_REQUEST['DefaultFilter'])) ? base64_decode($_REQUEST['DefaultFilter']) : "" ;           
          $groupBy = "";        

          if( isset($_REQUEST['addedFilter']) )
          {
            $aliasFilter_Field = array(
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

            unset($_REQUEST['addedFilter']);
          }       


          echo json_encode(
                  Datatables::simple( $_REQUEST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy )
              );
      }
    }

    function get_healthfacility_form()
    {
      $return = array( 
        "Status"=>0,
        "Message"=>""
      );

      if( $_SERVER['REQUEST_METHOD'] == 'POST')
      {
        $html_content = $this->load->view('Utilities/healthfacilitylist_form', $_POST, true);
        $html_content = trim(preg_replace('/^\s+|\n|\r|\s+$/m', "", $html_content));
        $return['Status'] = 1;
        $return['Message'] = base64_encode($html_content);
      }
      
      echo json_encode($return);
    }
    

    function get_healthfacility_list()
    {
      if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
      {
          echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
      }
      
      try
      {
          $DataTableConfig = @$_SESSION['DataTables'][$_REQUEST['TableID']];

          $table = 'ref_facilities'; // target table = normal table, view table, virtual table
        
          // Table's primary key
          $primaryKey = 'hfhudcode'; // Primary Key of the Table for normal table, 
          /* Create Where Clauses for SQL Statement */
          $aliasParam = array(); // Used to replace custom Search Variable to the target column field
          $specialParam = array(); 
          $whereResult = $this->datatables->customWhereStatement($_REQUEST,@$aliasParam,@$specialParam);
          $groupBy = '';

          /* Remove in Final Post Parameter */
          $unsetArray = array('addedFilter','addedFilterMode','StrictSearchMode');
          foreach($unsetArray as $unsetkeys)
          {
              if( isset($_REQUEST[ $unsetkeys]) ){ unset($_REQUEST[$unsetkeys]); }
          }     

          // use to override datatable return function call
          $overRideFunctionCall = array(
              'fhud_seq_crud_view' =>  function( $d, $row, $fieldid, $TargetTableID,$colid ) {
                                    return '<a linkgroup="selectfacility" href="javascript:void(0)" hfhudcode="'.$row['hfhudcode'].'" hfhudname="'.base64_encode($row['hfhudname']).'" fhudaddress="'.base64_encode($row['fhudaddress']).'" title="Select this Facility"><i class="icon md-long-arrow-return btn btn-dark" style="padding: 2px 5px;"></i></a>';
                                  },
          );
          

          // normally used for data return alteration
          $addedColumnProperties = array(
              //  'User_Password' =>  function( $d, $row, $fieldid, $TargetTableID,$colid ) {
              //   return $d;
              // }, 
          );

          $cparam = array(
              'TargetTableID'          => $_REQUEST['TableID'],
              'TablePrimaryKey'        => $primaryKey,
              'FunctionOverride'       => $overRideFunctionCall,
              'AddedColumnProperties'  => $addedColumnProperties
          );

          $ColumnProperties = $this->datatables->columnProperties($cparam);

          // Return Data as Json Format
          $DataReturn = Datatables::complex( $_REQUEST, @$sql_details, $table, $primaryKey, $ColumnProperties, $whereResult, NULL ,@$groupBy,$_REQUEST['TableID'] ) ;

          // DO any processing here
          // Return Data as Json Format
          echo json_encode($DataReturn);
      }
      catch(Exception $err)
      {
          echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
      }
    }

    function get_usersonline_form()
    {
      $return = array( 
        "Status"=>0,
        "Message"=>""
      );

      if( $_SERVER['REQUEST_METHOD'] == 'POST')
      {
        $html_content = $this->load->view('Utilities/usersonlinelist_form', $_POST, true);
        $html_content = trim(preg_replace('/^\s+|\n|\r|\s+$/m', "", $html_content));
        $return['Status'] = 1;
        $return['Message'] = base64_encode($html_content);
      }
      
      echo json_encode($return);
    }

    function get_usersonline_list()
    {
      $return = array( 
        "Status"=>0,
        "Message"=>""
      );

      $loggedIn_User = $this->logactive_user('Y');
      
      if( $_SERVER['REQUEST_METHOD'] == 'POST')
      {
          $table = 'v_002_users_online';

          // Table's primary key
          $primaryKey = 'User_ID';
          
          $columns = array(
              array(
                  'db' => 'User_ID',
                  'dt' => 'DT_RowId',
                  'field' =>'User_ID',
                  'formatter' => function( $d, $row ) {
                      // Technically a DOM id cannot start with an integer, so we prefix
                      // a string. This can also be useful if you have multiple tables
                      // to ensure that the id is unique with a different prefix
                      return 'row_'.$d;
                  }
              ),
              array(
                  'db' => 'User_ID',
                  'dt' => 'DT_RowClass',
                  'field' =>'User_ID',
                  'formatter' => function( $d, $row ) {
                      // Technically a DOM id cannot start with an integer, so we prefix
                      // a string. This can also be useful if you have multiple tables
                      // to ensure that the id is unique with a different prefix
                      return 'userid_row';
                  }
              ),

              array( 'db' => 'User_ID', 'dt' => 0 ,'field' =>'User_ID','formatter' => function( $d, $row ) {
                       return base64_encode('<i class="fa fa-search" style="cursor:pointer" onclick="javascript:closeModal(\'users_online_list\'); location.href=\''.site_url('useraccount/va/'.base64_encode($d)).'\'"></i>');
                    } ),
              
              array( 'db' => 'User_Name',  'dt' => 1,'field' =>'User_Name','formatter' => function( $d, $row ) {
                      return $d;
                    } ),
              array( 'db' => 'Account_Name',  'dt' => 2 ,'field' =>'Account_Name','formatter' => function( $d, $row ) {
                      return $d;
                    } ),
              array( 'db' => 'User_Type',  'dt' => 3 ,'field' =>'User_Type','formatter' => function( $d, $row ) {
                      return $d;
                    } )       
          );
          
          $sql_details = array(
            'user' =>$this->db->username,
            'pass' => $this->db->password,
            'db'   => $this->db->database,
            'host' => $this->db->hostname
          );

          $joinQuery = "";
          $extraWhere = " User_Type_Code > 1 ";        
          $groupBy = "";        

          if( isset($_REQUEST['addedFilter']) )
          {
            $aliasFilter_Field = array(
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

            unset($_REQUEST['addedFilter']);
          }       


          echo json_encode(
                  Datatables::simple( $_REQUEST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy )
              );
      }
    }

    function snapshotrequest($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'  => 0,
            'Message' => '',
          );

        $ulog = array(
                    'type'=>'Check for Client Window Snapshot Request',
                    'action'=>'Check for pending client window snapshot request',
                    'description'=>'',
                    'remarks'=>''
                  );


        if( !$this->tank_auth->is_logged_in() )
        {
          $return['Message'] = 'Unauthorize Access Detected!';
          goto exit_function_here;
        }


        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal)
        {
          $return = array('Status' => 0, 'Message' => 'Permission Denied!');
          goto exit_function_here;
        }
        
        if($internal)
        {
          $SubmittedParam = $formparameter;
          $SubmittedFiles = '';
        }
        else
        {
          $SubmittedParam = $_POST;
          $SubmittedFiles = (count($_FILES) > 0) ? $_FILES : '';
        }

        try
        {
          $timeoutseconds = 30000; 
          $timestamp = time();
          $timeout = $timestamp-$timeoutseconds;

          $reqWhere = "(`timestamp` between '".$timeout."' and '".$timestamp."') and approved_datetime is null and user_id = '".$SubmittedParam['user_id']."' and snapshotstatus is null";

          $getRequest = $this->sqlhelper->local->select('user_clientsnapshot')->where($reqWhere)->ex_select("","","1");
         
          $getRequest = $getRequest->row();
          
          if($getRequest['Count'] > 0)
          { 
            $return['Status'] = 1;
            $return['Message'] = 'Snapshot Request Successfully check - New Request Detected';
            $return['RequestData'] = $getRequest['Data'];
            $ulog['remarks'] = $return['Message'];
            $this->write_useractivitylog($ulog);
          }
          else
          {
            $return['Message'] = 'Snapshot Request Successfully check - No Request Detected';
          }

        }catch (PDOException $e) {
            log_message("error",$e->getMessage());
        }catch (Exception $e) {
            log_message("error",$e->getMessage());
        }

        exit_function_here:
        
        if($internal)
        {
            return $return;
        }
        else
        {
            echo json_encode($return);
        }
    }

    function snapshotrequestaction($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'  => 0,
            'Message' => '',
          );

        $ulog = array(
                    'type'=>'Client Window Snapshot Request Action',
                    'action'=>'Submit Snapshot Request Action',
                    'description'=>'',
                    'remarks'=>''
                  );
        // log_message("error",$_POST);
        if( !$this->tank_auth->is_logged_in() )
        {
          $return['Message'] = 'Unauthorize Access Detected!';
          goto exit_function_here;
        }


        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal)
        {
          $return = array('Status' => 0, 'Message' => 'Permission Denied!');
          goto exit_function_here;
        }

        if($internal)
        {
          $SubmittedParam = $formparameter;
          $SubmittedFiles = '';
        }
        else
        {
          $SubmittedParam = $_POST;
          $SubmittedFiles = (count($_FILES) > 0) ? $_FILES : '';
        }

        try
        {
          if( isset($SubmittedParam['snapshot']) )
          {
            $SubmittedParam['snapshot'] = base64_decode(str_replace('data:image/png;base64,','', $SubmittedParam['snapshot']));
          }

          $uaSnapshot = [
              'clientip' => $_SERVER["REMOTE_ADDR"],
              'approved_datetime' => date("Y-m-d H:i:s"),
              'snapshot' => @$SubmittedParam['snapshot'],
              'snapshotstatus' => @$SubmittedParam['snapshotstatus'],
              'snapshotHeight' => @$SubmittedParam['snapshotHeight'],
              'snapshotWidth' => @$SubmittedParam['snapshotWidth'],
          ];

          $uRequest = $this->sqlhelper->local->update('user_clientsnapshot')->ex_update($uaSnapshot)->where("id = '".$SubmittedParam['SnapshotID']."' and user_id = '".$SubmittedParam['user_id']."'")->run();
          // log_message("error",)
          $return['Status'] = 1;
          $return['Message'] = ($SubmittedParam['snapshotstatus'] == 1) ? 'Snapshot Request Approved' : 'Snapshot Request Disapproved';

        }catch (PDOException $e) {
            log_message("error",$e->getMessage());
        }catch (Exception $e) {
            log_message("error",$e->getMessage());
        }

        exit_function_here:
        $ulog['remarks'] = $return['Message'];
        $this->write_useractivitylog($ulog);
        if($internal)
        {
            return $return;
        }
        else
        {
            echo json_encode($return);
        }
    }

    function get_StorageSpace()
    {
      $return = array( 
        "Status"=>0,
        "Message"=>[]
      );

      if( $_SERVER['REQUEST_METHOD'] == 'POST')
      {
          try
          {
            if($this->tank_auth->is_logged_in() && $this->usertype < 3)
            {
              $return['Status'] = 1;
              $return['Message']['storagesize_apps'] = $this->myutilities->HumanSize($this->myutilities->dirSize(FCPATH),true);
              $return['Message']['storagesize_freespace'] = $this->myutilities->HumanSize(disk_free_space(FCPATH),true);
            }
            
          } catch (Exception $e) {
            log_message("error",__METHOD__ .' | '.$e->getMessage());
          }
      }
      
      echo json_encode($return);
    }
}