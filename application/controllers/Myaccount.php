<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\Providers\Qr\EndroidQrCodeProvider;


class Myaccount extends MY_Controller {
    private $tfa;
	function __construct()
	{
		 // phpinfo();
        parent::__construct();
        $this->data['pagetype'] = "myaccount";
        $this->tfa = new TwoFactorAuth('PhilHealth-eZBits',6,30,'sha1',new EndroidQrCodeProvider());
	}
 
    function profile()
    {
        $this->breadcrumbs->push('Account Profile', '/myaccount/profile');
        $this->data['title'] = "Account Profile "; 
        $this->data['PageTitle'] = "Account Profile";    

        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/jquery-wizard/jquery-wizard',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/jquery-strength/jquery-strength',
            'global/vendor/select2/select2'
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/bootstrap-clockpicker.min',
            'global/vendor/formatter/jquery.formatter',
            'global/vendor/jquery-wizard/jquery-wizard',
            'global/vendor/formvalidation/formValidation.min',
            'global/vendor/formvalidation/framework/bootstrap4.min',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/jquery-strength/password_strength',
            'global/vendor/jquery-strength/jquery-strength',
            'global/vendor/select2/select2.full.min'
            
        );

        $this->data['loadjschild'] = array(
            'global/js/Plugin/bootstrap-datepicker',
            'global/js/Plugin/clockpicker',
            'global/js/Plugin/formatter',
            'global/js/Plugin/jquery-wizard',
            'global/js/Plugin/jquery-labelauty',
            'global/js/Plugin/jquery-strength',
            'global/js/Plugin/select2'
        );
        
        $secret = $this->tfa->createSecret(160);
        $this->data['tfa']=$this->tfa;
        $this->data['secret']=$secret;
        $this->load->template('templates/myaccount/profile',  $this->data,'',1); // this will load the view file    
       
    }
    

    function modifyContactDetails()
    {
        $return = array(
                        'Status'  => 0,
                        'Message' => base64_encode("Update Contact Details Failed!,<br>Please try again later"),
                    );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
            {
               $return['Message'] = base64_encode('You do not have Permission to Access this Page!');
               goto endProcess;
            }

            unset($_POST['UToken']);
            goto process_start;
        }
        else
        {
            goto endProcess;
        }

        process_start:
        if($this->form_validation->run() && count($_POST) == 8) 
        {
            try{          
                $UpdateArray = array(); //$_POST;
                $contactDetails_Array = array(
                              'streetno',
                              'region',
                              'province',
                              'city',
                              'barangay',
                              'zipcode',
                              'Landline_no' ,
                              'Mobile_no',
                                    );
                foreach($contactDetails_Array as $keys)
                {
                    $UpdateArray[$keys] = $_POST[$keys];
                }
                
                $UpdateArray['updated_by'] = $this->userid;
                $UpdateArray['updated_datetime'] = date('Y-m-d H:i:s');
                $UpdateSecurityResult = $this->sqlhelper->local->update("user_profiles")->ex_update($UpdateArray)->where("user_id = ".$this->userid)->run();
                if($UpdateSecurityResult['ErrorCode'] == "" )
                {
                    $return['Status'] = 1;
                    $return['Message'] = base64_encode("Contact Details Successfully Updated");
                }
            }
            catch(Exeptions $e)
            {

            }

        }
        else
        {
           $return['Message'] = base64_encode(validation_errors('<span class="text-danger" style="font-size:12px"><i class="fa fa-circle" style="margin-right:10px;font-size:10px"></i>', '</span><br>'));
        }

        endProcess:
        echo json_encode($return);
    }


    function security()
    {
        // phpinfo();
        $this->data['title'] = "Account Security "; 
        $this->data['PageTitle'] = "Account Security";     
        $this->load->template('templates/main/myaccount/security',  $this->data,'',1); // this will load the view file    
       
    }

    function changepassword()
    {
        $return = array(
                        'Status'  => 0,
                        'Message' =>'Change Password Failed!, please try again later',
                    );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
            {
               $return['Message'] = 'You do not have Permission to Access this Page!';
               goto endProcess;
            }

            unset($_POST['UToken']);
            goto process_start;
        }
        else
        {
            goto endProcess;
        }

        process_start:
        if($this->form_validation->run()) 
        {
            
            $oldPassword = $_POST['oldpassword'];
            $newPassword = $_POST['newpassword'];
            $ChangePasswordResult = $this->change_password($oldPassword,$newPassword);
            if( $ChangePasswordResult )
            {
                $return['Status'] = 1;
                $return['Message'] = '';
            }
        }

        endProcess:
        echo json_encode($return);
    }


    function saveFPsettings()
    {
        $return = array(
                        'Status'  => 0,
                        'Message' => 'Failed to save new Settings.',
                    );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
            {
               $return['Message'] = 'You do not have Permission to Access this Page!';
               goto endProcess;
            }

            unset($_POST['UToken']);
            goto process_start;
        }
        else
        {
            goto endProcess;
        }

        process_start:   
            $getTokenCount = $this->myutilities->checkPVToken($_POST['PVToken']);
            if( $getTokenCount )
            {
                unset($_POST['PVToken']);
                $UpdateArray = $_POST;
                $UpdateArray['security_question_custom'] = ($UpdateArray['security_question'] == 20 ) ? $_POST['security_question_custom']  : '';
                $UpdateArray['updated_by'] = $this->userid;
                $UpdateArray['updated_datetime'] = date('Y-m-d H:i:s');

                $UpdateSecurityResult = $this->sqlhelper->local->update("user_profiles")->ex_update($UpdateArray)->where("user_id = ".$this->userid)->run();
                if($UpdateSecurityResult['ErrorCode'] == "" )
                {
                    $return['Status'] = 1;
                    $return['Message'] = "";
                }
            }
            else
            {
                goto endProcess;
            }
           
       
        endProcess:
        echo json_encode($return);
    }

    function viewSecretAnswer()
    {
        $return = array(
                        'Status'  => 0,
                        'Message' => 'Failed to view Security Answer.',
                    );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
            {
               $return['Message'] = 'You do not have Permission to Access this Page!';
               goto endProcess;
            }

            unset($_POST['UToken']);
            goto process_start;
        }
        else
        {
            goto endProcess;
        }

        process_start:   
            
            $getTokenCount = $this->myutilities->checkPVToken($_POST['PVToken']);
            if( $getTokenCount )
            {
                unset($_POST['PVToken']);
                $return['Status'] = 1;
                $return['Message'] = $this->userprofile['security_answer'];

            }
            else
            {
                goto endProcess;
            }
           
       
        endProcess:
        echo json_encode($return);
        log_message("error",$return);
    }

    function myqrid()
    {
        if( (int) $this->userclassification <> 2)
        {
            redirect('');
        }

        $getEmployeeDetails = $this->sqlhelper->local->select("tbl_employee_list")->where("EmployeeID='".$this->userregistrationinfo['EmployeeID']."'")->row();
        if($getEmployeeDetails['Count'] == 0)
        {
            redirect('');
        }

        $EmployeeDetails = $getEmployeeDetails['Data'];
        $this->data['EmployeeDetails'] = $EmployeeDetails;
        $this->load->library('Pdf');
        $this->load->view("pages/employee/qrcodeid",  $this->data,'',1);    
       
    }

    function verifyauthcode($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Verify Authentication Code'
        );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal)
        {
            $return = array('Status' => 0, 'Message' => 'Permission Denied!');
            goto exit_function_here;
        }

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
            {
               $return = array('Status' => 0, 'Message' => 'Permission Denied!');
               goto exit_function_here;
            }
            else
            {
              unset($_POST['UToken']);
              $SubmittedParam = $_POST;
            }
        }
        
        if($internal)
        {
            $SubmittedParam = $formparameter;
        }

        try{
         
            $secret = base64_decode(@$SubmittedParam['secret']);
            $authcode = base64_decode(@$SubmittedParam['confirmauthcode']);

            $verify_result = $this->tfa->verifyCode($secret, $authcode);
            if($verify_result)
            {
                // Update Profile Insert Auth Secret
                $ua = [
                    'TwoFactorKey'          => base64_encode($secret),
                    'TwoFactorKeyDateTime'  => date("Y-m-d H:i:s"),
                ];

                try{
                    $uResult = $this->sqlhelper->local->update("user_profiles")->ex_update($ua)->where("user_id ='".$this->userid."'")->run();
                    if($uResult['ErrorCode'] == "")
                    {
                        $return = array(
                            'Status'    => 1,
                            'Message'   => 'Two-Factor Authentication Successfully Verified'
                        );

                        $this->userprofile['TwoFactorKey'] = $ua['TwoFactorKey'];
                    }
                }catch (PDOException $e) {
                    log_message("error","Controller : ".__METHOD__." : Error Saving Data [ ".$e->getMessage()." ] : ".json_encode($SubmittedParam));
                } catch (Exception $e) {
                    log_message("error","Controller : ".__METHOD__." : Error Saving Data [ ".$e->getMessage()." ] : ".json_encode($SubmittedParam));
                }
            }

        }catch (PDOException $e) {
            log_message("error","Controller : ".__METHOD__." : Error Saving Data [ ".$e->getMessage()." ] : ".json_encode($SubmittedParam));
        } catch (Exception $e) {
            log_message("error","Controller : ".__METHOD__." : Error Saving Data [ ".$e->getMessage()." ] : ".json_encode($SubmittedParam));
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

        $ulog = array(
            'type'=>@$sTitle,
            'action'=>@$return['Message'],
            'description'=>'',
            'remarks'=>''
          );

        $this->write_useractivitylog($ulog);  
    } 
}