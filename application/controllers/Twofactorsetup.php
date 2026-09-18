<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\Providers\Qr\EndroidQrCodeProvider;
class Twofactorsetup extends MY_Controller {

    private $tfa;

	function __construct()
	{
		 // phpinfo();
        parent::__construct();
        $this->data['pagetype'] = "twofactorsetup";
        $this->breadcrumbs->push('<i class="fa fa-home"></i>&nbsp;Two-Factor Authentication Setup', '/twofactorsetup');

        if( @$this->userprofile['TwoFactorKey'] <> '')
        {
            redirect('');
        }

        $this->tfa = new TwoFactorAuth('PhilHealth-eZBits',6,30,'sha1',new EndroidQrCodeProvider());
	}

    function index()
    {
        $index = 'index';

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

        $this->data['PageTitle'] = "Two-Factor Authentication Setup"; 
        
        $secret = $this->tfa->createSecret(160);
        $this->data['tfa']=$this->tfa;
        $this->data['secret']=$secret;
        $this->load->template('templates/twofactor/'.$index,  $this->data,'',1); // this will load the view file    
        // redirect('dashboard');
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

            log_message("error",$SubmittedParam);
            
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