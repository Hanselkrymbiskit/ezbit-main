<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#Renamed
use PHPCypherFile\PHPCypherFile;
class Diagnose extends MY_Controller {

    public $adminpageDir = 'templates/admin/pages/';

	function __construct()
	{
		parent::__construct();
        $this->data['PageName'] = get_class($this);
        $this->data['adminMenu'] = true;
        if((int) $this->usertype > 3)
        {
            if( $_SERVER['REQUEST_METHOD'] == 'POST' )
            {
                 $return = array(
                    'Status'  => 0,
                    'Message' => "Unauthorized Access Detected!",
                );
                // ZPAMS-FIX (2026-09): was `return json_encode($return);`, which does NOT stop
                // execution here -- CodeIgniter invokes the requested method regardless of what a
                // constructor returns, so this unauthorized-access check was silently bypassed.
                // Changed to echo + exit so the block actually halts the request.
                echo json_encode($return);
                exit;
            }
            else
            {            
                $ulog = array(
                    'type'=>'Administrator',
                    'action'=>'Accessing Administrator Panel',
                    'description'=>'',
                    'remarks'=>'Unauthorized Access Detected!'
                  );  
                $this->write_useractivitylog($ulog);
                redirect(site_url());
            }
        }
        else
        {
            if( (int) $this->usertype == 3 )
            {
                $this->data['adminMenu'] = false;
            }
        }
	}

    function index()
    {
        
        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/multi-select/multi-select',
          
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/bootstrap-clockpicker.min',
            'global/vendor/formatter/jquery.formatter',
            'global/vendor/formvalidation/formValidation.min',
            'global/vendor/formvalidation/framework/bootstrap4.min',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/multi-select/jquery.multi-select',
            'js/userfunction/jquery.quicksearch',
        );


        $this->data['loadjschild'] = array(
             'global/js/Plugin/bootstrap-datepicker',
             'global/js/Plugin/clockpicker',
             'global/js/Plugin/formatter',
             'global/js/Plugin/jquery-labelauty',
             'global/js/Plugin/responsive-tabs',
             'global/js/Plugin/closeable-tabs',
             'global/js/Plugin/tabs',
             'global/js/Plugin/multi-select',
              'js/userfunction/xlsx.full.min',
              'js/userfunction/table2excel.min',
        );

        $this->data['PageTitle'] = "System Diagnose"; 
        $this->data['title'] = "System Diagnose"; 
        
        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
                <button type="button" id="btnRunDiagnose" name="btnRunDiagnose" class="btn btn-dark" style=""><i class="fa fa-fw fa-stethoscope"></i>&nbsp;Run System Diagnostic</button>                
            </div>     
        ';
  
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'systemdiagnose_content/index',  $this->data,'',1); 
      
    }

    function filescheck($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => ''
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
            
            $p = json_decode($SubmittedParam['params'],true);
            if($p['path'] <> '')
            {
              $checkAccess = $this->myutilities->checkDirectoryAccess(FCPATH.@$p['path']);
              $return['Status'] = 1;
              $return['Message'] = $checkAccess;
              $return['Message']['id'] = $p['id'];
              $return['Message']['write'] = $p['write'];
              $return['Message']['delete'] = $p['delete'];
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


    } 

    function diag_email($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Send Test Email!, make sure to properly configure Email Settings [ <b>System Settings -> Email Settings</b> ]'
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
            
            $emailMessage = 'This is a Test Email, Kindly Ignore this email.';

            $mail_message = array(
                  'header' => 'Hi There!',
                  'footer' => 'Thank You.',
                  'body' => $emailMessage
                );

            $param = array(
              'from'    => $this->config->item('email_sender'),
              'to'      => $this->userprofile['emailaddress'],
              'subject' => 'Test Mail',
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
              $em = explode("@",$this->userprofile['emailaddress']);
              $ee = ($em[1] == 'mailinator.com') ? '<a href="https://www.mailinator.com/v4/public/inboxes.jsp?to='.$em[0].'" target="_blank" title="Click To View Sent Test Mail">'.$this->userprofile['emailaddress'].'</a>' : $this->userprofile['emailaddress'];
              $return['Message'] = 'Email Successfully Sent to this email address [ '.$ee.' ]';
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


    } 
    
    function diag_daemon($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => ''
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
           $cd = $this->daemon->execute_background('general/m_general','daemontest',[]);
           if( @$cd == 1 )
           {
                $return['Status'] = 1;
                $return['Message'] = 'Daemon Asynchronous Function Successfully Called';
           }
           else
           {
                $return['Message'] = '<span class="text-danger">Failed!, make sure to include the host/server ip to [ <b>System Settings -> Security Settings -> Proxy IP Setting</b> ]</span>';
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


    } 

    function diag_cipherkey($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Generate Cipher Key'
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
            
            $res = openssl_pkey_new($this->openssl_config);
            // ZPAMS-FIX (2026-09): stray trailing semicolon terminated the if-condition with an
            // empty body, so the key-export success/failure result was discarded and the block
            // below (building $pubkey/$privkey) ran unconditionally -- the diagnostic would
            // report success even when openssl_pkey_export() actually failed.
            if( openssl_pkey_export($res, $privkey,$this->system_settings['PasswordHashing'],$this->openssl_config) )
            {
                // Get details of public key 
                $pubkey = openssl_pkey_get_details($res); 
                $pubkey = $pubkey["key"]; 
                $privkey = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$privkey,'MCrypt','aes-128','ecb');
                $pubkey = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$pubkey,'MCrypt','aes-128','ecb');
                $userprofile_table['oPublicKey'] = @$pubkey;
                $userprofile_table['oPrivateKey'] = @$privkey;
            } 

            $return['Message'] = 'Failed to Generated Cipher Key, make sure to properly configure openssl and openssl cnf file exists.';
            if( @$pubkey <> '' && @$privkey <> '')
            {
                $return['Status'] = 1;
                $return['Message'] = 'Cipher Key Successfully Generated';
            }

        }catch (PDOException $e) {
            log_message("error","Controller : ".__METHOD__." : Error Saving Data [ ".$e->getMessage()." ] : ".json_encode($SubmittedParam));
            $return['Message'] = $e->getMessage();
        } catch (Exception $e) {
            log_message("error","Controller : ".__METHOD__." : Error Saving Data [ ".$e->getMessage()." ] : ".json_encode($SubmittedParam));
            $return['Message'] = $e->getMessage();
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