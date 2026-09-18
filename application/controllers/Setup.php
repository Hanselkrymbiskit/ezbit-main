<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends MY_Controller {

	function __construct()
	{
		parent::__construct();
        $this->data['pagetype'] = "Login";

        // if( $this->tank_auth->is_logged_in() )
        // {
        //     redirect('home');
        // }
	}

    public function index()
    {

        $this->data['title'] = "Setup"; 
        $this->data['PageTitle'] = '<p style="text-align:center"><b>Setup Account</b></p>'; 
        
        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/jquery-wizard/jquery-wizard',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/jquery-strength/jquery-strength',
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
            
        );

        $this->data['loadjschild'] = array(
             'global/js/Plugin/bootstrap-datepicker',
             'global/js/Plugin/clockpicker',
             'global/js/Plugin/formatter',
             'global/js/Plugin/jquery-wizard',
             'global/js/Plugin/jquery-labelauty',
             'global/js/Plugin/jquery-strength',
        );


        $this->load->template('templates/setup/index',$this->data,'',false); 
       
    }

    function submit($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to setup Administrator Account!'
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
            $SubmittedFiles = (count($_FILES) > 0) ? $_FILES : '';
          }
        }
        
        if($internal)
        {
          $SubmittedParam = $formparameter;
          $SubmittedFiles = '';
        }

        try
        {
            $systemsettingsArray = [
                'PasswordHashing' => base64_encode($SubmittedParam['PasswordHashing'])
            ];

            foreach($systemsettingsArray as $settings_Key => $settings_Val )
            {
                $updateSystemSettings = $this->sqlhelper->local->update("system_settings")->ex_update("SettingValue='".trim($settings_Val)."'")->where("SettingName = '".trim($settings_Key)."'")->run();
            }
            
            $this->config->set_item('encryption_key',$SubmittedParam['PasswordHashing']);
            // Generate Password Hash

            $userArray = [
                'usertype'          => 1,
                'username'          => trim($SubmittedParam['username']),
                'email'             => trim($SubmittedParam['email']),
                'activated'         => 1,
                'expirydate'        => '2999-01-23',
                'password'          => $this->myutilities->genpasswordhash($SubmittedParam['password']),
                'created_datetime'    =>date('Y-m-d H:i:s'),
            ];

            $insertUser = $this->sqlhelper->local->insert("users")->ex_insert($userArray)->run();
            log_message("error",$insertUser);
            if($insertUser['ErrorCode'] == "")
            {
                $UserID = $insertUser['Data'];

                $userProfileArray = [
                    'user_id'             =>$UserID,
                    'emailaddress'        =>trim($SubmittedParam['email']),
                    'lastname'            =>trim(strtolower($SubmittedParam['Lastname'])),
                    'firstname'           =>trim(strtolower($SubmittedParam['Firstname'])),
                    'middlename'          =>trim(strtolower($SubmittedParam['Middlename'])),
                    'suffixname'          =>$SubmittedParam['Suffixname'],
                    'DateofBirth'         =>date('Y-m-d',strtotime($SubmittedParam['dateofbirth'])),
                    'sex'                 =>$SubmittedParam['Sex'],
                    'created_datetime'    =>date('Y-m-d H:i:s'),
                ];  

                $insertProfile =  $this->sqlhelper->local->insert("user_profiles")->ex_insert($userProfileArray)->run();
                log_message("error",$insertProfile);
                if($insertProfile['ErrorCode'] == "")
                {
                    $return['Status'] = 1;
                    $return['Message'] = 'Administrator Account Successfully Configured';
                }

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

}
   
   