<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->data['pagetype'] = "Signup";

        if( $this->tank_auth->is_logged_in() )
        {
            redirect('home');
        }
        else
        {
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
                      $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                      echo json_encode($return);
                      die();
                   }
                }
                else
                {
                    unset($_POST['UToken']);
                }      
            }
        }
    }

    public function index()
    {
        if( $_SERVER['REQUEST_METHOD'] == 'GET' && count(@$_GET) > 0 )
        {
            header("HTTP/1.1 403 Unauthorized");
            exit();
        }

        $this->data['title'] = "Signup"; 
        $this->data['PageTitle'] = '<p style="text-align:center"><b>Signup Account</b></p>'; 
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


        $this->load->template('templates/signup/index',$this->data,'',false); 
       
    }

    function submit($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Submit Sign-Up Form!, Please try again later!'
        );

        if( $_SERVER['REQUEST_METHOD'] == 'GET')
        {
            //show_404();
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal)
        {
            $return = array('Status' => 0, 'Message' => 'Permission Denied!');
            goto exit_function_here;
        }

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
          $SubmittedParam = $_POST;
          $SubmittedFiles = (count($_FILES) > 0) ? $_FILES : '';
        }
        
        if($internal)
        {
          $SubmittedParam = $formparameter;
          $SubmittedFiles = '';
        }

        try
        {
            if($this->system_settings['ReCaptchaModule'] == 1 && $this->system_settings['ReCaptchaatRegistration']  == 1 )
            {
                if($_SERVER['HTTP_HOST'] <> 'localhost' && $_SERVER['HTTP_HOST'] <> '127.0.0.1')
                {
                    $verifyRecaptchaResult = $this->myutilities->google_recaptcha_validate($_POST['recapctha_response']);
                    unset($_POST['recapctha_response']);
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

            if($verifyRecaptchaResult)
            {
                $this->load->model('register/m_register');
                

                $FormData = [
                    'eaddrs' => ['label'=>'EMAIL ADDRESS','referenceid'=>'','valuetype'=>'text','required'=>true],
                    'unme' => ['label'=>'USER NAME','referenceid'=>'','valuetype'=>'text','required'=>true],
                    'psswrd' => ['label'=>'PASSWORD','referenceid'=>'','valuetype'=>'text','required'=>true],
                    'confirmpsswrd' => ['label'=>'CONFIRM PASSWORD','referenceid'=>'','valuetype'=>'text','required'=>true],
                    'user_classification' => ['label'=>'CLASSIFICATION','referenceid'=>'04','valuetype'=>'text','required'=>true],
                    'security_question' => ['label'=>'SECURITY QUESTION','referenceid'=>'10','valuetype'=>'text','required'=>false],
                    'security_question_custom' => ['label'=>'CUSTOM SECURITY QUESTION','referenceid'=>'','valuetype'=>'text','required'=>false],
                    'security_answer' => ['label'=>'SECURITY ANSWER','referenceid'=>'','valuetype'=>'text','required'=>true],

                    'HealthFacilityCode' => ['label'=>'FACILITY CODE','referenceid'=>'','valuetype'=>'text','required'=>false],
                    'FacilityName' => ['label'=>'FACILITY NAME','referenceid'=>'','valuetype'=>'text','required'=>true],
                    'Region' => ['label'=>'REGION','referenceid'=>'','valuetype'=>'text','required'=>false],
                    'Province' => ['label'=>'PROVINCE','referenceid'=>'','valuetype'=>'text','required'=>false],
                    'City' => ['label'=>'CITY / MUNICIPALITY','referenceid'=>'','valuetype'=>'text','required'=>false],
                    'Address' => ['label'=>'ADDRESS','referenceid'=>'','valuetype'=>'text','required'=>false],
                    'FacilityContactNo' => ['label'=>'FACILITY CONTACT NO','referenceid'=>'','valuetype'=>'text','required'=>false],

                    'Lastname' => ['label'=>'LAST NAME','referenceid'=>'','valuetype'=>'text','required'=>true],
                    'Firstname' => ['label'=>'FIRST NAME','referenceid'=>'','valuetype'=>'text','required'=>true],
                    'Middlename' => ['label'=>'MIDDLE NAME','referenceid'=>'','valuetype'=>'text','required'=>true],
                    'Suffixname' => ['label'=>'SUFFIX','referenceid'=>'24','valuetype'=>'text','required'=>true],
                    'DateofBirth' => ['label'=>'DATE OF BIRTH','referenceid'=>'','valuetype'=>'text','required'=>true],
                    'Sex' => ['label'=>'SEX','referenceid'=>'23','valuetype'=>'text','required'=>true],
                    'Mobile_no' => ['label'=>'MOBILE NO','referenceid'=>'','valuetype'=>'text','required'=>false],  
                ];


                switch(@$SubmittedParam['user_classification'])
                {
                    case 1:
                    case 6:
                    case 7:
                        $FormData['HealthFacilityCode'] =['label'=>$FormData['HealthFacilityCode']['label'],'referenceid'=>'104','valuetype'=>'text','required'=>true];
                        $FormData['Region'] =['label'=>$FormData['Region']['label'],'referenceid'=>'101','valuetype'=>'text','required'=>true];
                        $FormData['Province'] =['label'=>$FormData['Province']['label'],'referenceid'=>'102','valuetype'=>'text','required'=>true];
                        $FormData['City'] =['label'=>$FormData['City']['label'],'referenceid'=>'103','valuetype'=>'text','required'=>true];
                        $FormData['Address'] =['label'=>$FormData['Address']['label'],'referenceid'=>'','valuetype'=>'text','required'=>true];
                        $FormData['FacilityContactNo'] =['label'=>$FormData['FacilityContactNo']['label'],'referenceid'=>'','valuetype'=>'text','required'=>true];
                        $FormData['ProCode'] = ['label'=>'Facility - PRO-GROUP','referenceid'=>'','valuetype'=>'text','required'=>false];
                        $FormData['AreaCode'] =['label'=>'Facility - AREA GROUP','referenceid'=>'','valuetype'=>'text','required'=>false];
                        $zFacilityProfile = $this->m_general->getZBenFacilityProfile(@$SubmittedParam['HealthFacilityCode']);
                        $SubmittedParam['ProCode'] = @$zFacilityProfile['pro_code'];
                        $SubmittedParam['AreaCode'] = @$zFacilityProfile['area_code'];
                        $_POST['ProCode'] = @$SubmittedParam['ProCode'];
                        $_POST['AreaCode'] =  @$SubmittedParam['AreaCode'];
                    break;
                }

                if(@$SubmittedParam['security_question'] <> '' && (int) $SubmittedParam['security_question'] == 20 )
                {
                    $FormData['security_question_custom'] =['label'=>'CUSTOM QUESTION','referenceid'=>'','valuetype'=>'text','required'=>true];
                }

                if( strpos(strtolower($SubmittedParam['unme']),strtolower($this->system_settings['ApplicationAbbre'])) > 0 )
                {
                    $return = array('Status' => 0, 'Message' => 'Invalid User Name, this should not contain [ '.$this->system_settings['ApplicationAbbre'].' ] word!');
                    goto exit_function_here;
                }

                if( strpos(strtolower($SubmittedParam['unme']),strtolower($SubmittedParam['psswrd'])) > 0 )
                {
                    $return = array('Status' => 0, 'Message' => 'Invalid User Password, Password should not contain '.@$SubmittedParam['unme'].'!');
                    goto exit_function_here;
                }

                foreach($FormData as $pk => $pv )
                {
                    if( isset($_POST[$pk]) )
                    {
                        $_POST[$pk] = $this->myutilities->dbValueFormatter($pk, $_POST[$pk]);
                    }
                    else
                    {
                        $_POST[$pk] = '';
                    }
                }

                // log_message("error",$_POST);
                $vResult = $this->m_general->validate_formdata($FormData,$_POST,true);
                // log_message("error",$vResult);
                if( $vResult['Status'] == 1 && $vResult['ValidateStatus'] == 1 )
                {
                    $SumbittedParam = $vResult['ValidateFields'];
                    $InsertRegistrationResult = $this->m_register->insertRegistrationData($vResult['ValidateFields']);
                    if($InsertRegistrationResult['Status'] == 1)
                    {
                        $RegistrationID = $InsertRegistrationResult['RegistrationID'];
                        // Insert File Attachment
                        if( @$this->system_settings['RegistrationFileAttachment'] == 1 )
                        {
                            if(isset($_FILES))
                            {
                                $upload_config = [];
                                $upload_path = '/attachments/registration';
                                if (!file_exists(FCPATH.$upload_path)) 
                                {
                                    mkdir (FCPATH.$upload_path."/", 0777); 
                                } 

                                $upload_path = $upload_path."/".$RegistrationID;
                                
                                if (!file_exists(FCPATH.$upload_path)) 
                                {
                                    mkdir (FCPATH.$upload_path."/", 0777); 
                                } 

                                $fu = fileUploader('proofdocument' , $upload_path, 'pdf|gif|jpg|png|jpeg|pjpeg',FALSE,10240);
                                if( $fu['successCount'] > 0)
                                {
                                    $successUploadFIle = [];
                                    foreach($fu['files'] as $fi => $fiResult)
                                    {
                                        $successUploadFIle[] = base64_encode($fiResult['file_name'].'|'.$fiResult['orig_name']);
                                    }

                                    $updateRegistration_attachment = array(
                                        'proofdocument' => implode(",",$successUploadFIle),
                                    );

                                    $upadteRun = $this->sqlhelper->local->update("user_register")->ex_update($updateRegistration_attachment)->where("registrationID = ".$RegistrationID)->run();
                                }   
                            }
                        }

                        $return['Status'] = 1;
                        if( @$this->system_settings['RegistrationAutoApproved'] == 1 )
                        {
                            $ActivationLink = site_url('activate/index/'.@$InsertRegistrationResult['activationlinkcode']);
                            // get Reg Info
                            $return['Message'] = 'You have Successfully registered to '.$this->system_settings['ApplicationAbbre'].' : '.$this->system_settings['ApplicationName'].'.<h3 class="text-center mb-0 mt-10 border border-success"><small class="text-success"><b>REGISTRATION NO</b></small><br><b>'.$InsertRegistrationResult['RegistrationID'].'</b></h3><br>Account Activation Link Successfully Sent to your registered Email Address.';
                            // Send Registration Info Mail
                             $emailMessage = 'You have Successfully registered to '.$this->system_settings['ApplicationAbbre'].' : '.$this->system_settings['ApplicationName'].' with the account credential of USER NAME : '.$SubmittedParam['unme'].'. <br><br><p>Please activate you account by clicking/navigate to the link provided below.<hr><p>Activation Link :</p><p><a href="'.@$ActivationLink.'" target="_blank">'.@$ActivationLink.'</a></p>
                        <hr><p>Please be reminded that you need to activate your account within 24 hours after receiving this message.</p>';

                             $mSubject   = $this->system_settings['ApplicationAbbre'].' - Account Registration / Activation Link';
                        }
                        else
                        {
                            $return['Message'] = 'New Account is subject for approval and review, Activation Link will be sent via email once it has been approved by the administrator.<h3 class="text-center mb-0 mt-10 border border-success"><small class="text-success"><b>REGISTRATION NO</b></small><br><b>'.$InsertRegistrationResult['RegistrationID'].'</b></h3>'; 
                            // Send Registration Info Mail
                            $emailMessage = 'You have Successfully registered to '.$this->system_settings['ApplicationAbbre'].' : '.$this->system_settings['ApplicationName'].' with the Registration ID : '.$InsertRegistrationResult['RegistrationID'].', please be reminded that your registration is subjected for approval and review. You will received user account activation email once it has been approved by the administrator. ';
                            $mSubject   = $this->system_settings['ApplicationAbbre'].' - Account Registration';
                        }

                        // Send Email Notification
                        try
                        {
                            $regName = ucwords(strtolower($SubmittedParam['Lastname'].( ($SubmittedParam['Suffixname'] <> 'NA' && $SubmittedParam['Suffixname'] <> '') ? ' '.$SubmittedParam['Suffixname'] : '' ).', '.$SubmittedParam['Firstname'].' '.substr($SubmittedParam['Middlename'],0,1).'.'));

                            $mail_message = array(
                                'header' => 'Hi '.$regName,
                                'footer' => 'Thank You.',
                                'body' => $emailMessage
                            );

                            $emailparam = array(
                                'from'    => $this->config->item('email_sender'),
                                'to'      => $SubmittedParam['eaddrs'],
                                'subject' => $mSubject,
                                'message' => $mail_message,
                                'cc'      => '',
                                'bcc'     => ''
                            );

                            $this->daemon->execute_background('sendmail/sendmail','send_mail',$emailparam);

                        }catch(Exception $er)
                        {
                            log_message("error","Error Processing Registration Data [ ".$e->getMessage()." ] : ".json_encode($_POST));
                        }
                        
                    }   
                    else
                    {
                        $return['Message'] = ($InsertRegistrationResult['Message'] == "Invalid Data") ? $return['Message']  :  $InsertRegistrationResult['Message'];
                    }
                }
                else
                {
                    $return['Message'] = 'Please fill-up all required fields!';
                }
                
            }
        }
        catch (PDOException $e) {
          log_message("error",__METHOD__." | ".$e->getMessage());
        } catch (Exception $e) {
          log_message("error",__METHOD__." | ".$e->getMessage());
        }

        exit_function_here:

        if($return['Status'] == 0)
        {
            // Log Failed Registration
            $SubmittedParam['remarks'] = $return['Message'];
            $SubmittedParam['register_datetime'] = date('Y-m-d H:i:s');
            $SubmittedParam['DateofBirth'] = (@$SubmittedParam['DateofBirth'] <> '') ?  date('Y-m-d',strtotime($SubmittedParam['DateofBirth'])) : '';
            unset($SubmittedParam['confirmpsswrd']);
            unset($SubmittedParam['psswrd']);
            unset($SubmittedParam['recapctha_response']);
            $this->sqlhelper->local->insert('user_register_attempt')->ex_insert($SubmittedParam)->run();
        }

        if($internal)
        {
            return $return;
        }
        else
        {
            echo json_encode($return);
        }

    }

    function searchHealthFacilityList($internal=false,$formparameter = '')
    {
        $return = [
            "results" => [],
            "pagination" => ["more"=>true]
        ];

     

        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal)
        {
            goto exit_function_here;
        }
        
        $SubmittedParam = $_POST;

        if($internal)
        {
            $SubmittedParam = $formparameter;
        }

        try
        {

            $sQuery = "
                select 
                    a.*,
                    b.regname,
                    b.nscb_reg_name,
                    c.provname,
                    d.cityname
                from ref_zben_facility a 
                left join ref_zben_region b on b.regcode=a.region_code 
                left join ref_zben_province c on c.provcode=a.province_code 
                left join ref_zben_city d on d.citycode=a.municipality_code
                where 
                a.contract_status <> 'TERMINATED' and (a.inst_name like '%".$SubmittedParam['q']."%' or a.inst_code like '%".$SubmittedParam['q']."%') 
                order by a.inst_name asc
            ";


            $eList = $this->sqlhelper->local->sql($sQuery);
            // log_message("error",$eList->rtrnSql());
            $eList = $eList->result();
            $earray = [];

            foreach($eList['Data'] as $row => $val)
            {
                $earray[] = [
                    "id"            => $val['inst_code'],
                    "text"          => strtoupper($val['inst_name']),
                    "facilityname"  => strtoupper($val['inst_name']),
                    "facilitycode"  => $val['inst_code'],
                    "address"       => (!is_null($val['inst_address_street']) && @$val['inst_address_street'] <> '') ? strtoupper($val['inst_address_street']) : '',
                    "region"        => (!is_null($val['nscb_reg_name']) && @$val['nscb_reg_name'] <> '') ? strtoupper($val['nscb_reg_name']) : '',
                    "province"      => (!is_null($val['provname']) && @$val['provname'] <> '') ? strtoupper($val['provname']) : '',
                    "city"          => (!is_null($val['cityname']) && @$val['cityname'] <> '') ? strtoupper($val['cityname']) : '',     
                    "regioncode"    => (!is_null($val['region_code']) && @$val['region_code'] <> '') ? strtoupper($val['region_code']) : '',
                    "provincecode"  => (!is_null($val['province_code']) && @$val['province_code'] <> '') ? strtoupper($val['province_code']) : '',
                    "citycode"      => (!is_null($val['municipality_code']) && @$val['municipality_code'] <> '') ? strtoupper($val['municipality_code']) : '',
                    "fcontact"      => (!is_null($val['inst_contactno']) && @$val['inst_contactno'] <> '') ? $val['inst_contactno'] : ''
                ];
            }

            $return = [
                "results" => $earray,
                "pagination" => ["more"=>true]
            ];

        }
        catch (PDOException $e) {
          log_message("error",__METHOD__ ." |  ".$e->getMessage());
        } catch (Exception $e) {
          log_message("error",__METHOD__ ." |  ".$e->getMessage());
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
}
   
   