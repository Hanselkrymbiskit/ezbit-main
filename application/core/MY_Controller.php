<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Ramsey\Uuid\Uuid;
use PHPCypherFile\PHPCypherFile;
use setasign\Fpdi\Tcpdf\Fpdi;
#[AllowDynamicProperties]
class MY_Controller extends CI_Controller {

    public $exempt_auth = FALSE;
    public $DBConnected = TRUE;
    public $userlevel;
    public $data = array();
    public $userid;
    public $username;
    public $userdetails; 
    public $usertype;
    public $userprofile;
    public $userregistrationinfo;
    public $userclassification = 0;
    public $defaultDB;
    public $remote1;
    public $remote2;
    public $remote3;
    public $system_settings;
    public $cc ;
    public $menupageaccess;
    public $currentpage;
    public $UserProfilePic;
    public $maintenancenotif = '';
    public $system_module_permission = [];
    public $nonceV = '';
    public $cspHeader = '';
    public $csrfToken = '';
    public $openssl_config = [
                "config"            => APPPATH.'keystorage/openssl.cnf',
                "private_key_bits"  => 4096,
                "private_key_type"  => OPENSSL_KEYTYPE_RSA,
            ];
    // protected $sqlhelper;
   
    function __construct() {
       
        parent::__construct();
        $this->data['pageHeader'] = true;
        if( $_SERVER['REQUEST_METHOD'] == 'GET' )
        {
            $bin2hex = bin2hex(openssl_random_pseudo_bytes(32));
            $this->nonceV = base64_encode($_SERVER['UNIQUE_ID'] ?? ''); //$bin2hex;
            unset($_SERVER['UNIQUE_ID']);
            unset($_SERVER['REDIRECT_UNIQUE_ID']);
            if( isset($_GET[$this->config->item('csrf_cookie_name')]) || isset($_GET[$this->config->item('csrf_token_name')]) )
            {
                header("HTTP/1.1 403 Unauthorized");
                exit;
            }
        }

        // Load Database Active Group  
        // $this->defaultDB = $this->load->database('default', TRUE);
        if(file_exists($file_path = APPPATH.'config/database.php'))
        {
            include($file_path);
            if ( isset($db) && count($db) > 0 )
            {   
                foreach($db as $dbk => $dbv)
                {
                    $this->{$dbk} = $this->load->database($dbk, TRUE);
                }
            }
        }

        if(!$this->defaultDB->conn_id) {
           $this->DBConnected = FALSE;
        }

        else
        {
            $this->DBConnected = TRUE;
            // Default Load Upon Success Connection to the Database 
            // Load datatables Class 
            $this->load->library('Datatables',array('sqlhelper' => $this->sqlhelper)); 
            // Load System Settings
            $this->system_settings = $this->myutilities->getSystemSettings();
            $this->config->set_item('apitag', $this->system_settings['ApplicationAbbre']);
            $this->config->set_item('encryption_key',base64_decode($this->system_settings['PasswordHashing']));
            $this->csrfToken = $this->config->item('csrf_token_name');

            $SetNewConfig = array(
                'ApplicationName',
                'ApplicationAbbre',
                'ApplicationFooter',
                'ApplicationVersion',
            );

            foreach( $SetNewConfig as $newCKey  )
            {
                $this->config->set_item(strtolower($newCKey), $this->system_settings[$newCKey]);
            }
    

            if( @$this->system_settings['ProxyIPList'] <> '')
            {
                $sProxyIPList = explode(",",$this->system_settings['ProxyIPList']);
                $nproxy_ips = array_merge($this->config->item('proxy_ips'),$sProxyIPList);
                $this->config->set_item('proxy_ips', $nproxy_ips);
            }

            $TankAuthArray = array(
                'ApplicationName'           => 'website_name',
                'UsernameLength_Min'        => 'username_min_length',
                'UsernameLength_Max'        => 'username_max_length',
                'PasswordLength_Min'        => 'password_min_length',
                'PasswordLength_Max'        => 'password_max_length',
                'LoginAttempt_Max'          => 'login_max_attempts',
                'LoginAttempt_Duration'     => 'login_attempt_expire',
                'ReCaptcha_PrivateKey'      => 'recaptcha_private_key',
                'ReCaptcha_PublicKey'       => 'recaptcha_public_key',
                'ReCaptchaModule'           => 'use_recaptcha',
            );

            $tankAuthConfig = $this->config->item('tank_auth');
            foreach( $TankAuthArray as $tankauthKey => $tankauthConfigKey )
            {
                $tankAuthConfig[$tankauthConfigKey] = ($tankauthKey == 'ReCaptchaModule' ) ? ( ($this->system_settings[$tankauthKey] == 1) ? true : false ) : $this->system_settings[$tankauthKey];
            }
            
            $this->config->set_item('tank_auth', $tankAuthConfig);
            $exempt_auth = array(
                'signup',
                'activate',
                'daemons',
                'utilities',
                'resetpassword',
                'createpassword',
                'cronjob',
                // 'rest'
            );   

            $this->sqlhelper->local->setajaxevent(1);
           
            $urisegment1 =(is_null($this->uri->segment(1))) ? '' : $this->uri->segment(1);
            $crequest = strtolower($urisegment1);


            switch( $crequest )
            {
                case 'webservice':
                break;
                default:
                    $this->DefaultBreadcrumbs();
            }

            $this->currentpage = $crequest ;

            $chkAdminAccount = $this->sqlhelper->local->select("users")->where("usertype=1")->result();
            $_SESSION['maintenanceModeNow'] = false;
            if( $this->system_settings['MaintenanceMode'] == 1)
            {
                if(  @$this->system_settings['MaintenanceFromDateTime'] <> '' &&  @$this->system_settings['MaintenanceToDateTime'] <> '')
                {
                    $mdatecover = ( date("m/d/Y",strtotime($this->system_settings['MaintenanceFromDateTime'])) == date("m/d/Y",strtotime($this->system_settings['MaintenanceToDateTime'])) ) ? ' from '.date("m/d/Y h:i: A",strtotime($this->system_settings['MaintenanceFromDateTime'])).' - '.date("h:i A",strtotime($this->system_settings['MaintenanceToDateTime'])) : ' from '.date("m/d/Y h:i:s A",strtotime($this->system_settings['MaintenanceFromDateTime'])).' to '.date("m/d/Y h:i:s A",strtotime($this->system_settings['MaintenanceToDateTime']));

                    $_SESSION['maintenanceModeMessage'] = '
                    <div style="border-top:1px solid #bebebe;">
                        <div style="color: #bebebe;font-size: 20px;">
                        We will be performing a Scheduled Maintenance and Upgrade for improved experience<br>'.@$mdatecover.'.
                        </div>
                        <div style="color: #bebebe;font-size: 20px;margin-top:10px">
                        Kindly Note. All services will be unavailable during this maintenance.
                        </div>
                    </div>';

                    $this->maintenancenotif = strip_tags('Scheduled Maintenance'.@$mdatecover.', Please signout 15min before the scheduled date/time.');

                    if( date("Y-m-d H:i:s") >= @$this->system_settings['MaintenanceFromDateTime'] && date("Y-m-d H:i:s") <= @$this->system_settings['MaintenanceToDateTime'] )
                    {
                        $_SESSION['maintenanceModeMessage'] = '
                        <div style="border-top:1px solid #bebebe;">
                            <div style="color: #bebebe;font-size: 20px;">
                            Ongoing Scheduled Maintenance and Upgrade for improved experience<br>'.@$mdatecover.'.
                            </div>
                            <div style="color: #bebebe;font-size: 20px;margin-top:10px">
                            Kindly Note. All services will be unavailable during this maintenance.
                            </div>
                        </div>';

                        $_SESSION['maintenanceModeNow'] = true;
                        $this->maintenancenotif = '';
                    }

                    if( date("Y-m-d H:i:s") > @$this->system_settings['MaintenanceToDateTime'] )
                    {
                        $_SESSION['maintenanceModeNow'] = false;
                        unset($_SESSION['maintenanceModeMessage']);
                        $this->maintenancenotif = '';
                    }
            
                }
                else
                {
                    if( @$this->system_settings['MaintenanceFromDateTime'] == '' && @$this->system_settings['MaintenanceToDateTime'] == "")
                    {
                        $_SESSION['maintenanceModeMessage'] = '
                        <div style="border-top:1px solid #bebebe;">
                            <div style="color: #bebebe;font-size: 20px;">
                            Under Maintenance and Upgrade for improved experience.
                            </div>
                            <div style="color: #bebebe;font-size: 20px;margin-top:10px">
                            Kindly Note. All services will be unavailable during this maintenance.
                            </div>
                        </div>';

                        $_SESSION['maintenanceModeNow'] = true;
                    }
                    else
                    {
                        if( @$this->system_settings['MaintenanceFromDateTime'] <> '' && @$this->system_settings['MaintenanceToDateTime'] == "" )
                        {

                            $_SESSION['maintenanceModeMessage'] = '
                            <div style="border-top:1px solid #bebebe;">
                                <div style="color: #bebebe;font-size: 20px;">
                                We will be performing a Scheduled Maintenance and Upgrade for improved experience<br>from '.date("m/d/Y h:i: A",strtotime($this->system_settings['MaintenanceFromDateTime'])).' to indefinite date/time.
                                </div>
                                <div style="color: #bebebe;font-size: 20px;margin-top:10px">
                                Kindly Note. All services will be unavailable during this maintenance.
                                </div>
                            </div>';

                            $this->maintenancenotif = strip_tags('Scheduled Maintenance will start at '.date("m/d/Y h:i: A",strtotime($this->system_settings['MaintenanceFromDateTime'])).', Please signout 15min before the scheduled date/time.');

                            if( date("Y-m-d H:i:s") >= @$this->system_settings['MaintenanceFromDateTime']  )
                            {
                                 // unset($_SESSION['maintenanceModeMessage']);
                                $_SESSION['maintenanceModeMessage'] = '
                                <div style="border-top:1px solid #bebebe;">
                                    <div style="color: #bebebe;font-size: 20px;">
                                    Ongoing Scheduled Maintenance and Upgrade for improved experience<br>from '.date("m/d/Y h:i: A",strtotime($this->system_settings['MaintenanceFromDateTime'])).' to indefinite date/time.
                                    </div>
                                    <div style="color: #bebebe;font-size: 20px;margin-top:10px">
                                    Kindly Note. All services will be unavailable during this maintenance.
                                    </div>
                                </div>';

                                $_SESSION['maintenanceModeNow'] = true;
                            }
                        }

                        if( @$this->system_settings['MaintenanceFromDateTime'] == '' && @$this->system_settings['MaintenanceToDateTime'] <> "" )
                        {

                            $_SESSION['maintenanceModeMessage'] = '
                            <div style="border-top:1px solid #bebebe;">
                                <div style="color: #bebebe;font-size: 20px;">
                                Under Maintenance and Upgrade for improved experience<br>until '.date("m/d/Y h:i: A",strtotime($this->system_settings['MaintenanceToDateTime'])).'.
                                </div>
                                <div style="color: #bebebe;font-size: 20px;margin-top:10px">
                                Kindly Note. All services will be unavailable during this maintenance.
                                </div>
                            </div>';

                            $_SESSION['maintenanceModeNow'] = true;

                            if( date("Y-m-d H:i:s") >= @$this->system_settings['MaintenanceToDateTime']  )
                            {
                                unset($_SESSION['maintenanceModeMessage']);
                            }
                        }
                    }
                }
            }

            if($chkAdminAccount['Count'] == 0)
            {
                if( !isset($_SERVER['REQUEST_METHOD']) && trim($this->currentpage) <> 'setup')
                {
                    redirect('setup');
                }
            }
            else
            {
                if ( !$this->tank_auth->is_logged_in() ) 
                {
                    if( trim($this->currentpage) <> 'login' && !in_array($this->currentpage, $exempt_auth) )
                    {
                        redirect('login');
                    } 
                    
                    if ($_SERVER['REQUEST_METHOD'] == 'POST')
                    {
                        
                        if (isset($_SERVER['HTTP_ORIGIN'])) {
                            $address = (( isset($_SERVER['HTTPS']) ) ?  "https://" : "http://").$_SERVER['HTTP_HOST'];
                            if (strpos($address, $_SERVER['HTTP_ORIGIN']) !== 0) { 
                                // log_message("error","here");
                                $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                                echo json_encode($return);
                                die();
                            }
                        }
                    }
                   
                } 
                else
                {
                        if(!isset($_SESSION['DataTables']))
                        {
                            $_SESSION['DataTables'] = array();
                        }

                        if( !isset($_SESSION['sys_userdetails'])  )
                        {
                            $_SESSION['sys_userdetails'] = $this->sqlhelper->local->select("users")->where("id='".$this->tank_auth->get_user_id()."'")->row();
                        }

                       $userdetails = $_SESSION['sys_userdetails'];
                       $this->userdetails = $userdetails['Data'];
                       $this->userid = $this->userdetails['id'];
                       $this->username = $this->userdetails['username'];
                       $this->usertype = $this->userdetails['usertype'];

                        if( !isset($_SESSION['sys_userprofile'])  )
                        {
                            $_SESSION['sys_userprofile'] = $this->sqlhelper->local->select("user_profiles")->where("user_id='".$this->userid."'")->row();
                        }
                       
                      $userprofile = $_SESSION['sys_userprofile'];

if(!$userprofile || !is_array($userprofile) || !isset($userprofile['Data'])) {
    $this->tank_auth->logout();
    redirect('login');
}

if(!isset($_SESSION['CompleteName']))
{
    $sCname = $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],@$userprofile['Data']['CompleteName'],'MCrypt','aes-128','ecb');
                            $sCname = ($sCname <> '') ? $sCname : $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],@$userprofile['Data']['CompleteName'],'MCrypt','aes-128','cbc');
                            $_SESSION['CompleteName'] = $sCname;
                       }
                       
                       $userprofile['Data']['CompleteName'] = (isset($_SESSION['CompleteName'])) ? @$_SESSION['CompleteName'] : $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],@$userprofile['Data']['CompleteName'],'MCrypt','aes-128','ecb');
      
                        $this->userprofile = $userprofile['Data'];

                        if( !is_array($this->userdetails) || $this->userdetails == '' || !is_array($this->userprofile) || $this->userprofile == '')
                        {
                            redirect('logout');
                        }

                        if( (int) $this->usertype > 3 )
                        {
                            $userregisteredData = $this->sqlhelper->local->select("user_register")->where("user_id='".$this->userid."'")->row();
                            $this->userregistrationinfo = ($userregisteredData['Count']) ? $userregisteredData['Data'] : [];
                            if( array_key_exists('oPrivateKey', $this->userprofile) && $this->userprofile['oPrivateKey'] == '' )
                            {
                                $this->generateRSAKeys($this->userprofile);
                            }
                        }

                        $this->userclassification = ($this->usertype > 3) ? $this->userprofile['user_classification'] : 0;

                        // Profile Pic
                        $this->UserProfilePic = ($this->userprofile['ProfilePhoto'] == '' ) ? assetPath().'images/profilepic/guestuser2.png' : 'data:image/png;base64,'.$this->userprofile['ProfilePhoto'] ;
                        $this->logactive_user();


                        // Check for Two-Factor Authentication Setup
                        if($this->tank_auth->is_logged_in())
                        {
                            $totppageExempt = [
                                'logout','signout','twofactorsetup'
                            ];

                           if( @$this->userprofile['TwoFactorKey'] == "" && $this->usertype > 3 && !in_array($this->currentpage,$totppageExempt) && $_SERVER['HTTP_HOST'] != 'localhost' )
{
    redirect('twofactorsetup');
}
                        }
                       
                        // Permission Module
                        $this->system_module_permission = $this->myutilities->usermodulepermission($this->userid);
                       
                        if( isset($_SESSION['maintenanceModeMessage'])  && (int) $this->usertype > 3 && @$_SESSION['maintenanceModeNow'] == true )
                        {
                            $result = $this->sqlhelper->local->delete('users_online')->where(" `userid` ='".$this->tank_auth->get_user_id()."'")->run();
                            try{
                                if( isset($_SESSION['userloginlogdatetime']) && isset($_SESSION['userloginlogid']) )
                                {
                                    $logoutdatetime = date("Y-m-d H:i:s");
                                    $Date1 = date("Y-m-d H:i:s",strtotime($logoutdatetime));
                                    $Date2 = date("Y-m-d H:i:s",strtotime($_SESSION['userloginlogdatetime']));
                                    $duration = date_diff(new DateTime($Date1),new DateTime($Date2));
                                    $duration = $duration->format('%h Hours %i Minute %s Seconds');
                                    $uloglogin = [
                                        'logoutdatetime'   => $logoutdatetime,
                                        'duration'         => @$duration,
                                    ];

                                    $insertLogLogin = $this->sqlhelper->local->update('users_online_log')->ex_update($uloglogin)->where("userid='".$this->tank_auth->get_user_id()."' and `id` = '".@$_SESSION['userloginlogid']."'")->run();
                                    
                                    unset($_SESSION['userloginlogid']);
                                    unset($_SESSION['userloginlogdatetime']);
                                }   
                            }
                            catch(Exception $ee)
                            {
                                log_message("error","Logout Error ".$ee->getMessage());
                            }
                            

                            $this->tank_auth->logout();

                            if($this->session->iniCounterNo)
                            {
                                $cno = $this->session->iniCounterNo;
                                unset($this->session->iniCounterNo);
                                $deleteSession = $this->sqlhelper->local->delete('system_sessions')->where(" `id` ='".session_id()."'")->run();
                                redirect('login');      
                            }
                            else
                            {
                                $deleteSession = $this->sqlhelper->local->delete('system_sessions')->where(" `id` ='".session_id()."'")->run();
                                redirect('');
                            }
                        }

                        if( $this->config->item('csrf_protection') )
                        {
                            $checkUnset = [
                                'authenticity_token',
                                'authenticity_cookie',
                            ];
                            
                            if( isset($_REQUEST[$this->csrfToken]) )
                            {
                              unset($_REQUEST[$this->csrfToken]);
                            }

                            if( isset($_REQUEST[$this->config->item('csrf_cookie_name')]) )
                            {
                              unset($_REQUEST[$this->config->item('csrf_cookie_name')]);
                            }
                            
                            if( isset($_REQUEST['ci_csrf_token']) )
                            {
                              unset($_REQUEST['ci_csrf_token']);
                              unset($_POST['ci_csrf_token']);
                            }
                        }

                        // Added for Zben Only
                        $_SESSION['HFZBenServices'] = [];
                        $_SESSION['HFPreAuthServices'] = [];
                        if( in_array((int) $this->userclassification,$this->m_general->HFUsers) )
                        {
                          $_SESSION['HFZBenServices'] = $this->m_general->getFacilityZBenServices($this->userregistrationinfo['HealthFacilityCode']);
                          $_SESSION['HFPreAuthServices'] = $this->m_general->getFacilityZBenServices($this->userregistrationinfo['HealthFacilityCode'],true);
                        }

                }    
            }

            
        }
    }

    // Remap URL Access to Specific Controller/Functions 
    function _remap($method, $params = array())
    {    
        // Check if the requested method within our controller exists
        if (method_exists($this, $method)) 
        {
            $params = array_slice($this->uri->rsegment_array(), 2);
            return call_user_func_array(array($this, $method), $params);
        }
        else
        {
            show_404();
            //log_message("error",$params);
            $params = array_slice($this->uri->rsegment_array(), 1);
            $this->index($params);   
        }       
    }

    function xssCleaner($data)
    {
        return $this->myutilities->xssCleaner($data);
    }

    function DefaultBreadcrumbs($AdminPage = false)
    {
        $AdminControlPanelPage = array(
                'administrator',
                // 'registered',
                // 'useraccount',
                // 'broadcastmessage',
                // 'faq',
                // 'reference',
                // 'email',
                // 'settings',
                // 'editor',
            );

        if ( $this->tank_auth->is_logged_in()) 
        {
            // 
            // $this->breadcrumbs->push($messnotice, '/home');
            $urisegment1 = strtolower((is_null($this->uri->segment(1))) ? '' : $this->uri->segment(1));

            if( in_array($urisegment1, $AdminControlPanelPage) )
            {
                if( $urisegment1 == 'faq' && $this->uri->segment(2) == "" )
                {
                    $this->breadcrumbs->push('<i class="fa fa-home"></i>&nbsp;Home', '/home');
                }
                else
                {
                    $this->breadcrumbs->push('<i class="fa fa-gear"></i>&nbsp;Admin Panel', '/administrator');
                }
                
            }
            else
            {
                if( $urisegment1 <> 'home' && trim($urisegment1) <> '' && $urisegment1 <> 'faq'  )
                {
                    $this->breadcrumbs->push('<i class="fa fa-home"></i>&nbsp;Home', '/home');
                } 
            }        
        }
    }

    function logactive_user($returnactive = 'N')
    {
        $timeoutseconds = 600; 
        $timestamp = time();
        $timeout = $timestamp-$timeoutseconds;
        $table = "users_online";
        $ex_insert = array(
                'sessionid' => session_id(),
                'timestamp'=>$timestamp,
                'ip'=>$_SERVER["REMOTE_ADDR"],
                'file'=>$_SERVER["PHP_SELF"],
                'userid'=>$this->tank_auth->get_user_id(),
                'logdatetime'=>date('Y-m-d H:i:s')
            );
 
        try{
            $insert_ou = $this->sqlhelper->local->insert($table)->ex_insert($ex_insert)->run();
            $delete_uo = $this->sqlhelper->local->delete($table)->where(" users_online.`timestamp` < '".$timeout."'")->run();
        }catch(Exceptions $e)
        {
            log_message("error",$e);
        }

        if($returnactive == "Y")
        {     
            $gAuser = $this->sqlhelper->local->select($table,"`userid`")->ex_select('`userid`','','')->result();
            $auser = array(
                    'Count'=>$gAuser['Count'],
                    'UserOnline'=>''
                );
            $us = array();
            foreach($gAuser['Data'] as $row=>$col)
            {
                $us[] = $col['userid'];
            }

             $auser['UserOnline'] = $us;
            return $auser;
        }
        
        return "";
    }

    function storePassword_gen($encrypted = '',$decrypted = '')
    {   
        if($encrypted <> '' && $decrypted <> '')
        {
            $idata = array(
                    'Password_Decrypted' => $decrypted,
                    'Password_Encrypted' => $encrypted

                );
            $insert = $this->sqlhelper->local->insert("system_password_storage")->ex_insert($idata)->run();
        }
    }

    function change_password($old_pass,$new_pass,$userid = '',$activated = TRUE)
    {
      
        $user_id = ($userid == "") ? $this->session->userdata('user_id') : $userid;

        if (!is_null($user = $this->users->get_user_by_id($user_id, $activated))) {
            // Check if old password correct
            $hasher = new PasswordHash(
                    $this->config->item('phpass_hash_strength', 'tank_auth'),
                    $this->config->item('phpass_hash_portable', 'tank_auth'));
            
            // if(@$_SESSION['PasswordChanged'] == 'N' && (int) $this->userclassification == 2)
            // {
            //     $upData = [
            //         'PasswordChanged' => 'Y'
            //     ];

            //     $upDataRun = $this->sqlhelper->local->update("tbl_employee_list")->ex_update($upData)->where("EmployeeID = '".$this->userregistrationinfo['EmployeeID']."'")->run();

            //     unset($_SESSION['FirstQRLogin']);
            //     goto passhere;
            // }
 
            if ($hasher->CheckPassword($old_pass, $user->password)) {           // success
                // Hash new password using phpass
                passhere:
                $hashed_password = $hasher->HashPassword($new_pass);
                // Replace old password with new one
                $this->users->change_password($user_id, $hashed_password);
                $this->storePassword_gen($hashed_password,$new_pass);

                    // Change Web Key and E Key
                if ( $this->tank_auth->is_logged_in() && (int) $this->usertype <= 3) 
                {
                }
                else
                {
                    $oldEKey = $this->userprofile['User_E_Key'];
                    repeat_keypair:
                    $ws_uuid = Uuid::uuid4();
                    $ws_uuid = $ws_uuid->toString();
                    $e_uuid = Uuid::uuid4();
                    $e_uuid = $e_uuid->toString();

                    $User_WS_Key = $ws_uuid;
                    $User_E_Key =  base64_encode( $this->encryption->create_key(16) ); //$e_uuid;
                    $chkKey = $this->sqlhelper->local->select("user_profiles")->where(" User_WS_Key = '".$User_WS_Key."' ")->result();
                    if($chkKey['Count'] > 0)
                    {
                        goto repeat_keypair;
                    }


                    $UpdateProfile = array(
                        'User_WS_Key'  => $User_WS_Key,
                        'User_E_Key'   => $User_E_Key
                    );

                    if( @$this->userprofile['oPrivateKey'] <> '' )
                    {
                        try
                        {
                            $config = [
                                "config"            => APPPATH.'keystorage\openssl.cnf',
                                "private_key_bits"  => 4096,
                                "private_key_type"  => OPENSSL_KEYTYPE_RSA,
                            ];

                            // Update Private Key Passphrase
                            $privateKey = openssl_pkey_get_private($this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$this->userprofile['oPrivateKey'],'MCrypt','aes-128','ecb'), $oldEKey); 
                            if ($privateKey === false) {
                                log_message("error","Failed to load Private Key");
                            }
                            else
                            {
                                $exportSuccess = openssl_pkey_export($privateKey, $nprivateKey, $User_E_Key,$config);
                                if ($exportSuccess === false) {
                                    log_message("error","Failed to export private key with new passphrase");
                                }
                                else
                                {
                                    $UpdateProfile['oPrivateKey'] = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$nprivateKey,'MCrypt','aes-128','ecb');
                                }
                            }

                        }catch (PDOException $e) {
                            return false;
                        } catch (Exception $e) {
                            return false;
                        }
                    }

                    $runProfileUpdate = $this->sqlhelper->local->update("user_profiles")->ex_update($UpdateProfile)->where("user_id = ".$user_id)->run();
                    $getaccesskeyinfo = $this->sqlhelper->local->select('rest_api_keys')->where("user_id = ".$user_id)->row();
                    $uaiinfo = [
                        'api_key' => $User_WS_Key
                    ];
                    $updateAccessKeyInfo = $this->sqlhelper->local->update("rest_api_keys")->ex_update($uaiinfo)->where("user_id = ".$user_id)->run();
                    $uiinfo = [
                        'user_id'       => $user_id,
                        'api_key'       => $getaccesskeyinfo['Data']['api_key'],
                        'date_created'  => date("Y-m-d H:i:s"),
                    ];
                    $insertArchiveKey = $this->sqlhelper->local->insert('rest_api_keys_archive')->ex_insert($uiinfo)->run();
                    //Update Previous Key Access
                    $uuapiaccesssql = "update rest_api_access a set a.`key` = '".$User_WS_Key."' where `key` = '".$getaccesskeyinfo['Data']['api_key']."'";
                    $updateAccessAPI = $this->sqlhelper->local->sql($uuapiaccesssql)->run();

                    // Re-Encrypt Data
                    if( $this->userprofile['user_classification'] == 1 )
                    {
                        $this->load->model('preauthorization/m_preauth');
                        $this->load->model('claims/m_claims');
                        $ReEncryptData_PreAuth = $this->m_preauth->reencryptdata($oldEKey,$User_E_Key);
                        $ReEncryptData_claims = $this->m_preauth->reencryptdata($oldEKey,$User_E_Key);
                    }
                }
               
                return TRUE;
            } else {                                                            
                return FALSE;
            }
        }
        
        return FALSE;
    }

    // User Managemt -- End

    // Validate JSON String
    function _json_validate($string,$return_errod_desc = FALSE)
    {
        // decode the JSON data
        $result = json_decode($string);

        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            // PHP >= 5.3.3
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            // PHP >= 5.5.0
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }

        if ($error !== '') {
            // throw the Exception or exit // or whatever :)
            exit($error);
        }

        
        return $result;
    }


    // Use for Captcha
    function _create_captcha()
    {
        $this->load->helper('captcha');

        $cap = create_captcha(array(
            'img_path'      => './'.$this->config->item('captcha_path', 'tank_auth'),
            'img_url'       => base_url().$this->config->item('captcha_path', 'tank_auth'),
            'font_path'     => './'.$this->config->item('captcha_fonts_path', 'tank_auth'),
            'font_size'     => $this->config->item('captcha_font_size', 'tank_auth'),
            'img_width'     => $this->config->item('captcha_width', 'tank_auth'),
            'img_height'    => $this->config->item('captcha_height', 'tank_auth'),
            'show_grid'     => $this->config->item('captcha_grid', 'tank_auth'),
            'expiration'    => $this->config->item('captcha_expire', 'tank_auth'),
        ));

        // Save captcha params in session
        $this->session->set_flashdata(array(
                'captcha_word' => $cap['word'],
                'captcha_time' => $cap['time'],
        ));

        return $cap['image'];
    }

    function _create_recaptcha()
    {
        $this->load->helper('recaptcha');

        // Add custom theme so we can get only image
        $options = "<script nonce=\"myself\" >var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

        // Get reCAPTCHA JS and non-JS HTML
        $html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'));

        return $options.$html;
    }

    function _check_captcha($code)
    {
        $time = $this->session->flashdata('captcha_time');
        $word = $this->session->flashdata('captcha_word');

        list($usec, $sec) = explode(" ", microtime());
        $now = ((float)$usec + (float)$sec);

        if ($now - $time > $this->config->item('captcha_expire', 'tank_auth')) {
            $this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
            return FALSE;

        } elseif (($this->config->item('captcha_case_sensitive', 'tank_auth') AND
                $code != $word) OR
                strtolower($code) != strtolower($word)) {
            $this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
            return FALSE;
        }
        return TRUE;
    }
   
    function isUserNameExists($username = '')
    {
        if($username <> '')
        {
            $usertable = $this->config->item('usertable','tank_auth');
            $username_field = $this->config->item('username_field','tank_auth');
            $check = $this->sqlhelper->local->select($usertable)->where($username_field." = '".$username."'")->count();
            if( $check > 0)
            {
                return TRUE;
            }

            return FALSE;
        }
    }

    function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= substr($chars, $index, 1);
        }

        return $result;
    }

    function getEncryptedPassword($password = '')
    {
        $epass = '';
        if($password <> '')
        {
            $get = $this->sqlhelper->local->select("system_password_storage","Password_Encrypted")->where("Password_Decrypted = '".$password."'")->ex_select('','','1');
            if($get->count() > 0)
            {
                $get = $get->row();
                $epass = $get['Data']['Password_Encrypted'];
            }
        }

        return $epass;
    }

    function write_useractivitylog($data)
    {
        if(is_array($data))
        {
            $idata = array(
                'userid'=> (isset($data['userid']) && $data['userid'] <> '') ? $data['userid'] : $this->tank_auth->get_user_id(),
                'datetime'=>date("Y-m-d H:i:s"),
                'type'=>@$data['type'],
                'action'=>@$data['action'],
                'description'=>@$data['description'],
                'remarks'=>@$data['remarks']
            );
            
            $insert = $this->sqlhelper->local->insert("user_activity")->ex_insert($idata)->run();
        }
    }
    
    function generateRSAKeys($uprof)
    {
        try
        {
            $res = openssl_pkey_new($this->openssl_config);
            if( openssl_pkey_export($res, $privkey,$uprof['User_E_Key'],$this->openssl_config) );  
            { 
                // Get details of public key 
                $pubkey = openssl_pkey_get_details($res); 
                $pubkey = $pubkey["key"]; 
                $privkey = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$privkey,'MCrypt','aes-128','ecb');
                $pubkey = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$pubkey,'MCrypt','aes-128','ecb');

                $uD = [
                    'oPublicKey'    => @$pubkey,
                    'oPrivateKey'   => @$privkey
                ];

                $ruD = $this->sqlhelper->local->update("user_profiles")->ex_update($uD)->where(" user_id = '".$uprof['user_id']."'")->run();
                if(@$ruD['ErrorCode'] <> '')
                {
                    log_message("error",__METHOD__ .' | '.@$ruD['Description']);
                }
            } 

        }catch (PDOException $e) {
            log_message("error",__METHOD__ .' | '.$e->getMessage());
        } catch (Exception $e) {
            log_message("error",__METHOD__ .' | '.$e->getMessage());
        }
    }

    // ZPAMS-FIX (2026-09): extracted from preauthorization/Index.php::view() so both the
    // Pre-Authorization and Claims modules can generate the same Annex A PDF from a
    // pre-authorization record, instead of duplicating this illness-specific FPDI/TCPDF
    // overlay logic in each controller. A claim has no medical form of its own -- it
    // references its originating pre-authorization case_no -- so "Print/Download" on a
    // claim regenerates that same case's Annex A form via this shared method.
    //
    // $getPreAuthData  -- full pre-authorization record (patientinfo/checklist/request),
    //                     as returned by M_preauth::getFormData().
    // $fileNameTitle   -- clean filename to give the generated PDF (illness name only,
    //                     no "Pre-Auth : " prefix -- callers strip that before passing in).
    // $viewMode        -- mirrors the caller's $this->data['ViewMode']; when false, no PDF
    //                     is built and this returns ''.
    //
    // Returns the onclick="..." attribute string to wire onto a print/download button, or
    // '' if no PDF could be built (caller should show a "not available" state in that case).
    public function generatePreAuthPDF($getPreAuthData, $fileNameTitle, $viewMode = true)
    {
        $fileClick = '';
        try
        {
            if($viewMode == true)
            {
                $ppdf = new Fpdi();
                // ZPAMS-FIX (2026-09): TCPDF defaults to a ~10mm right margin even on an FPDI
                // overlay canvas where every Write() is already explicitly positioned via
                // SetXY(). A Write() whose text would cross that margin boundary silently wraps
                // the overflow to a NEW LINE starting at the LEFT margin instead of clipping --
                // confirmed as the cause of Prostate CA's diagnostic dates rendering in the far
                // left margin instead of their intended column (x=192 on a 215.9mm-wide page
                // left only ~4mm before the 205.9mm right-margin boundary, too little room for a
                // date string). Zeroing all margins removes this wrap behavior entirely; nothing
                // here relies on TCPDF's automatic text flow since every field is hand-positioned.
                $ppdf->SetMargins(0, 0, 0);
                $ppdf->SetAutoPageBreak(false, 0);
                $ppdfFile = str_replace("\\","//",FCPATH."assets\zben-forms\PreAuth_".str_pad($getPreAuthData['patientinfo']['preauth_type'],2,0,STR_PAD_LEFT).".pdf");
                if (file_exists($ppdfFile)) 
                {
                    $tmpBlobContent = file_get_contents($ppdfFile);
                    preg_match_all('!\d+!', $tmpBlobContent, $matches);
                    $ppdfversion = implode('.', $matches[0]);
                    $ppdfversion = substr($ppdfversion,0,3);
                    if($ppdfversion > "1.4")
                    {
                        if (file_exists(str_replace(".pdf","_.pdf",$ppdfFile))) 
                        {
                            unlink(str_replace(".pdf","_.pdf",$ppdfFile));
                        }

                        if( strtoupper(substr(PHP_OS, 0, 3)) == "WIN" )
                        {
                            exec('gswin64 -dBATCH -dNOPAUSE -dQUIET -sDEVICE=pdfwrite -dCompatibilityLevel="1.4" -sOutputFile="'.str_replace(".pdf","_.pdf",$ppdfFile).'" "'.$ppdfFile.'" 2>&1',$execoutput);
                        }
                        else
                        {
                            exec('gs -dBATCH -dNOPAUSE -dQUIET -sDEVICE=pdfwrite -dCompatibilityLevel="1.4" -sOutputFile="'.str_replace(".pdf","_.pdf",$ppdfFile).'" "'.$ppdfFile.'" 2>&1',$execoutput);
                        }
                    }

                    if (file_exists(str_replace(".pdf","_.pdf",$ppdfFile))) 
                    {
                        $ppageCount = $ppdf->setSourceFile(str_replace(".pdf","_.pdf",$ppdfFile));
                    }
                    else
                    {
                        $ppageCount = $ppdf->setSourceFile($ppdfFile);
                    }


                    // ZPAMS-FIX (2026-09): the PDF export previously wrote only the 3 fields below
                    // (Case No, Health Facility, Address) onto the template -- everything the user
                    // actually fills in the wizard (patient/member identity, PhilHealth ID, the
                    // checklist Yes/No selections, diagnostics, signatures) was silently dropped from
                    // the exported PDF even though it renders correctly on-screen and is saved in the
                    // database. Added a full field mapping for preauth_type 14 (Prostate Cancer) as a
                    // first pass -- coordinates were measured empirically per-template (FPDI+Ghostscript
                    // render with an mm gridline overlay, same method used for the original 3 fields),
                    // so this does not yet cover the other 25 Z-Ben templates.
                    $pFullName = function($row, $prefix) {
                        $parts = array_filter([
                            @$row["{$prefix}_lastname"], @$row["{$prefix}_firstname"], @$row["{$prefix}_middlename"]
                        ]);
                        $name = implode(', ', $parts);
                        return trim($name.' '.@$row["{$prefix}_suffix"]);
                    };

                    // ZPAMS-FIX (2026-09): M_preauth::getFormData() calls getRequiredData($preauth_type)
                    // with only one argument, so $FSCriteria stays falsy and the illness-specific field
                    // definitions (which carry the 'encrypted' flag for checklist/request columns like
                    // crtby_patient, crtby_attendingphysician, crtby_attendingphysician_accreno,
                    // crtby_medicaldirectory, crtby_medicaldirectory_accreno) never get merged into
                    // $rData. getFormData()'s decrypt loop only decrypts a field when
                    // isset($rData[$fk][$k]) is true, so every 'checklist'/'request' field -- not just
                    // the ones used here -- is returned as raw ciphertext straight from the database.
                    // This is a pre-existing app-wide gap, not something introduced by this PDF fix;
                    // decrypting locally here (scoped to the 5 fields this PDF actually prints) avoids
                    // changing getRequiredData()'s behavior for every other caller.
                    $pDecrypt = function($ciphertext) use (&$getPreAuthData) {
                        if (@$ciphertext == '') { return ''; }
                        static $eKey = null;
                        if ($eKey === null) {
                            $uprofile = $this->myutilities->getUserProfile(@$getPreAuthData['patientinfo']['created_by']);
                            $eKey = @$uprofile['User_E_Key'];
                        }
                        if (!$eKey) { return ''; }
                        return @$this->m_api->encryptdecryptString('decrypt', $eKey, $ciphertext, 'MCrypt', 'aes-128', 'ecb');
                    };

                    // Writes one character per pre-printed accreditation-number box instead of one
                    // continuous string -- box pitch measured empirically off the template (uniform
                    // ~3.9mm per box) via a pixel scan of a rendered gridline overlay, same method used
                    // for every other coordinate in this template. Dashes in the stored value are
                    // formatting only (the boxes hold digits one-per-cell), so they're stripped first.
                    $pBoxedNumber = function($value, $x, $y, $pitch = 3.9, $fontsize = 7) use ($ppdf) {
                        $digits = preg_replace('/[^A-Za-z0-9]/', '', (string) $value);
                        if ($digits === '') { return; }
                        $ppdf->SetFont('Helvetica','',$fontsize);
                        foreach (str_split($digits) as $i => $ch) {
                            $ppdf->SetXY($x + ($i * $pitch), $y);
                            $ppdf->Write(0, $ch);
                        }
                    };

                    // Centers text within a given horizontal span (start x, width) instead of always
                    // left-aligning from x -- used for printed names on signature lines so they sit
                    // centered under the line the way a filled-in form normally looks.
                    $pCentered = function($text, $x, $y, $width, $fontsize = 8) use ($ppdf) {
                        $ppdf->SetFont('Helvetica','',$fontsize);
                        $ppdf->SetXY($x, $y);
                        $ppdf->Cell($width, 0, (string) $text, 0, 0, 'C');
                    };

                    // $ppageCount = $ppdf->setSourceFile($ppdfFile);
                    for ($pageNo = 1; $pageNo <= $ppageCount; $pageNo++) {
                        $templateId = $ppdf->importPage($pageNo);
                        $size = $ppdf->getTemplateSize($templateId);
                        $ppdf->AddPage($size['orientation'], array($size['width'], $size['height']));
                        $ppdf->setPrintHeader(false);
                        $ppdf->setPrintFooter(false);
                        $ppdf->SetFont('Helvetica');
                        // use the imported page
                        $ppdf->useTemplate($templateId);
                        if($pageNo == 1)
                        {
                           // ZPAMS-FIX (2026-09): overlay coordinates below were off-template and
                           // rendered text on top of the form's pre-printed labels (Case No, Health
                           // Facility, Address). Re-measured empirically against PreAuth_06.pdf and
                           // PreAuth_02.pdf via an FPDI+Ghostscript render with a gridline overlay.
                           //
                           // ZPAMS-FIX (2026-09): PreAuth_14.pdf (Prostate CA) is US Letter size
                           // (215.9x279.4mm) while every other mapped template is A4 (210x297mm) --
                           // the "Case No." line's actual printed position differs enough between the
                           // two page sizes that the shared y=42.5 (calibrated against the A4
                           // templates) left the case number floating ~6mm below the underline instead
                           // of on it. Confirmed via word-level PDF text extraction (PyMuPDF) against
                           // PreAuth_14.pdf directly rather than assumed from the other templates.
                           // HCP/Address on this same page use the shared y=52.5/59 correctly, so only
                           // Case No needed its own override here.
                            $isProstate = ((int) @$getPreAuthData['patientinfo']['preauth_type'] == 14);
                           // Case No
                            $ppdf->SetXY($isProstate ? 50 : 43, $isProstate ? 36.4 : 42.5);
                            $ppdf->Write(0,@$getPreAuthData['patientinfo']['case_no']);
                            // ZPAMS-FIX (2026-09): PreAuth_03.pdf (CABG) has the HCP/Address rows
                            // positioned lower than every other template checked so far, despite
                            // looking identical at a glance -- confirmed empirically (numbered-marker
                            // diagnostic render) rather than assumed from visual similarity, after the
                            // shared coordinates below produced text sitting above the HCP/Address box
                            // borders for this template specifically.
                            $isCabg = ((int) @$getPreAuthData['patientinfo']['preauth_type'] == 3);
                            // Health Facility
                            $ppdf->SetXY(70, $isCabg ? 63 : 52.5);
                            $ppdf->Write(0,$this->myutilities->getRef_Desc(104,@$getPreAuthData['patientinfo']['healthfacility_code']));
                            // Address
                            $ppdf->SetXY(70, $isCabg ? 74 : 59);
                            $ppdf->Write(0,$this->myutilities->getRef_Desc(104,@$getPreAuthData['patientinfo']['healthfacility_code'],false,'inst_address_street'));

                            if ((int) @$getPreAuthData['patientinfo']['preauth_type'] == 14) {
                                $pi = $getPreAuthData['patientinfo'];
                                $cl = @$getPreAuthData['checklist'];

                                $pCentered($pFullName($pi,'patient'), 97, 71.5, 53, 7);
                                $ppdf->SetFont('Helvetica','',9);
                                // ZPAMS-FIX (2026-09): x=153/169 (copied from the A4 templates) landed
                                // right at this Letter-size template's own checkbox left edge instead of
                                // centered inside it -- re-measured against PreAuth_14.pdf directly.
                                if (@$pi['patient_sex'] == 'M') { $ppdf->SetXY(153.1, 72.1); $ppdf->Write(0,'X'); }
                                else { $ppdf->SetXY(168.7, 72.1); $ppdf->Write(0,'X'); }
                                // ZPAMS-FIX (2026-09): PhilHealth ID was a single Write() of the whole
                                // string instead of the per-box digit placement every other mapped
                                // template uses -- confirmed via render that digits ran straight through
                                // the pre-printed boxes rather than one-per-box. This template's box row
                                // is also non-uniform (2 boxes, a printed dash, 9 boxes, a printed dash,
                                // 1 box), so it needs three separate boxed segments rather than one
                                // pBoxedNumber() call spanning all 12 digits.
                                $pphic = preg_replace('/[^0-9]/', '', (string) @$pi['patient_philhealthno']);
                                $pBoxedNumber(substr($pphic,0,2), 110.2, 78.1, 5.3, 8);
                                $pBoxedNumber(substr($pphic,2,9), 125.1, 78.1, 5.31, 8);
                                $pBoxedNumber(substr($pphic,11,1), 177.3, 78.1, 5.3, 8);

                                if (@$pi['patient_is_member'] == 'Y') {
                                    // ZPAMS-FIX (2026-09): x=60.3 sat past the checkbox's right edge;
                                    // re-measured to 59.0 against this template's own glyph position.
                                    $ppdf->SetXY(59.0, 84.9); $ppdf->Write(0,'X');
                                } else {
                                    $pCentered($pFullName($pi,'member'), 97, 93, 53, 7);
                                    $ppdf->SetFont('Helvetica','',9);
                                    $mphic = preg_replace('/[^0-9]/', '', (string) @$pi['member_philhealthno']);
                                    $pBoxedNumber(substr($mphic,0,2), 110.2, 99.3, 5.3, 8);
                                    $pBoxedNumber(substr($mphic,2,9), 125.1, 99.3, 5.31, 8);
                                    $pBoxedNumber(substr($mphic,11,1), 177.3, 99.3, 5.3, 8);
                                }

                                // ZPAMS-FIX (2026-09): x=89.5 landed inside the printed word "Yes"
                                // itself -- the actual Yes/No checkboxes on this template sit to the
                                // LEFT of their labels (x=83.5 measured directly from this page's own
                                // text), not after them like the shared pattern assumed.
                                if (@$pi['fulfilled_selection_criteria'] == 'Y') { $ppdf->SetXY(83.5,124.3); $ppdf->Write(0,'X'); }
                                else {
                                    $ppdf->SetXY(83.5,129.0); $ppdf->Write(0,'X');
                                    $ppdf->SetXY(100,132.3); $ppdf->Write(0, @$pi['fulfilled_selection_criteria_reason']);
                                }

                                if (@$cl['q_1_1'] == 'Y') { $ppdf->SetXY(174,157.5); $ppdf->Write(0,'X'); }
                                if (@$cl['q_1_2'] == 'Y') { $ppdf->SetXY(174,163.5); $ppdf->Write(0,'X'); }

                                // ZPAMS-FIX (2026-09): row Y values below were re-measured against
                                // PreAuth_14.pdf's own text layer -- the originals (178/192/199/206)
                                // didn't match this template's actual DIAGNOSTICS row positions
                                // (185.9/195.2/199.5/203.7 in the source PDF), so X marks landed on the
                                // wrong rows (e.g. overlapping the "DATE DONE" header). The date column
                                // x=192 also sat close enough to TCPDF's default ~10mm right margin
                                // that Write() silently wrapped the date text to the far-left margin
                                // instead of clipping -- fixed at the source by zeroing all margins on
                                // this $ppdf instance (see SetMargins/SetAutoPageBreak above), and the
                                // column moved to x=161 for clear room regardless.
                                $diagRows = [
                                    ['q_2_1','q_2_1_date', 186.3],
                                    ['q_2_2','q_2_2_date', 195.6],
                                    ['q_2_3','q_2_3_date', 199.9],
                                    ['q_2_4','q_2_4_date', 204.1],
                                ];
                                foreach ($diagRows as $r) {
                                    [$yk,$dk,$ry] = $r;
                                    if (@$cl[$yk] == 'Y') {
                                        $ppdf->SetXY(149, $ry); $ppdf->Write(0,'X');
                                        if (!empty(@$cl[$dk])) { $ppdf->SetXY(161, $ry); $ppdf->Write(0, date('m/d/Y', strtotime($cl[$dk]))); }
                                    }
                                }

                                $pCentered($pDecrypt(@$cl['crtby_patient']), 15, 228, 85, 8);
                                // ZPAMS-FIX (2026-09): width=73 starting at x=140 spanned well past the
                                // actual signature line (which ends at x=177.8 per the source PDF),
                                // pushing the centered name off the line entirely. Re-measured to the
                                // line's real bounds (x=117.6 to 177.8).
                                $pCentered($pDecrypt(@$cl['crtby_attendingphysician']), 118, 228, 59, 8);
                                // ZPAMS-FIX (2026-09): this box row is 14 uniform slots (pitch measured
                                // via pixel scan at ~3.90mm) for a 4-dash-7-dash-1 accreditation number
                                // format ("XXXX-XXXXXXX-X") -- the dashes occupy their own box slots
                                // rather than sitting in extra gaps between groups. pBoxedNumber()'s
                                // digit-only stripping doesn't apply here since the dash characters need
                                // to print in their own boxes too, so this writes the raw value
                                // (including dashes) one character per box directly instead.
                                $accreno = $pDecrypt(@$cl['crtby_attendingphysician_accreno']);
                                $ppdf->SetFont('Helvetica','',8);
                                foreach (str_split((string) $accreno) as $i => $ch) {
                                    $ppdf->SetXY(128.35 + ($i * 3.90), 239.8);
                                    $ppdf->Write(0, $ch);
                                }
                            }

                            // ZPAMS-FIX (2026-09): field mapping for preauth_type 4 (Cervical Cancer),
                            // added as part of the same rollout as Prostate CA (14). Coordinates
                            // measured empirically against PreAuth_04.pdf (A4, distinct from Prostate
                            // CA's Letter-size template) via the same FPDI+Ghostscript gridline method.
                            if ((int) @$getPreAuthData['patientinfo']['preauth_type'] == 4) {
                                $pi = $getPreAuthData['patientinfo'];
                                $cl = @$getPreAuthData['checklist'];

                                $pCentered($pFullName($pi,'patient'), 50, 73, 98, 7);
                                $ppdf->SetFont('Helvetica','',9);
                                if (@$pi['patient_sex'] == 'M') { $ppdf->SetXY(153, 75); $ppdf->Write(0,'X'); }
                                else { $ppdf->SetXY(167, 75); $ppdf->Write(0,'X'); }
                                $pBoxedNumber(@$pi['patient_philhealthno'], 107.6, 80, 5.55);

                                if (@$pi['patient_is_member'] == 'Y') {
                                    $ppdf->SetXY(53, 89); $ppdf->Write(0,'X');
                                } else {
                                    $pCentered($pFullName($pi,'member'), 50, 96, 98, 7);
                                    $pBoxedNumber(@$pi['member_philhealthno'], 107.6, 102, 5.55);
                                }

                                if (@$pi['fulfilled_selection_criteria'] == 'Y') { $ppdf->SetXY(82,111.5); $ppdf->Write(0,'X'); }
                                else {
                                    $ppdf->SetXY(82,119.5); $ppdf->Write(0,'X');
                                    $ppdf->SetXY(95,126); $ppdf->Write(0, @$pi['fulfilled_selection_criteria_reason']);
                                }

                                // 5 QUALIFICATIONS items, YES-only column
                                $qualY = ['q_1_1'=>156,'q_1_2'=>161,'q_1_3'=>166,'q_1_4'=>171,'q_1_5'=>176];
                                foreach ($qualY as $fld => $ry) {
                                    if (@$cl[$fld] == 'Y') { $ppdf->SetXY(163, $ry); $ppdf->Write(0,'X'); }
                                }

                                // FIGO Clinical Staging -- single-select radio (q_2_1 holds the chosen
                                // stage's code 1-9, matching ref_zben_cc_stages) rather than one Y/N
                                // field per row, so only the matching row gets marked.
                                $figoY = [1=>197,2=>202,3=>207,4=>211.5,5=>216.5,6=>221.5,7=>226.5,8=>231.5,9=>236.5];
                                $stageCode = (int) @$cl['q_2_1'];
                                if (isset($figoY[$stageCode])) {
                                    $ppdf->SetXY(103, $figoY[$stageCode]); $ppdf->Write(0,'X');
                                    if (!empty(@$cl['q_2_1_date'])) {
                                        $ppdf->SetXY(140, $figoY[$stageCode]);
                                        $ppdf->Write(0, date('m/d/Y', strtotime($cl['q_2_1_date'])));
                                    }
                                }

                                $pCentered($pDecrypt(@$cl['crtby_attendingqynecologiconcologist']), 116, 248, 68, 7);
                                $pBoxedNumber($pDecrypt(@$cl['crtby_attendingqynecologiconcologist_accreno']), 126.9, 260, 4.15);
                            }

                            // ZPAMS-FIX (2026-09): field mapping for preauth_type 3 (CABG), added as
                            // part of the same rollout. Unlike Prostate CA / Cervical CA, this
                            // template's checklist spans TWO physical pages (1 and 2) -- page 1 holds
                            // only the standalone age qualification and checklist items 1-2; items 3-4,
                            // diagnostics, and the physician/patient signatures live on page 2.
                            if ((int) @$getPreAuthData['patientinfo']['preauth_type'] == 3) {
                                $pi = $getPreAuthData['patientinfo'];
                                $cl = @$getPreAuthData['checklist'];

                                $pCentered($pFullName($pi,'patient'), 50, 83.5, 98, 7);
                                $ppdf->SetFont('Helvetica','',9);
                                if (@$pi['patient_sex'] == 'M') { $ppdf->SetXY(153, 83.5); $ppdf->Write(0,'X'); }
                                else { $ppdf->SetXY(169, 83.5); $ppdf->Write(0,'X'); }
                                $pBoxedNumber(@$pi['patient_philhealthno'], 107.6, 90, 5.55);

                                if (@$pi['patient_is_member'] == 'Y') {
                                    $ppdf->SetXY(52.5, 97.5); $ppdf->Write(0,'X');
                                } else {
                                    $pCentered($pFullName($pi,'member'), 50, 107, 98, 7);
                                    $pBoxedNumber(@$pi['member_philhealthno'], 107.6, 114, 5.55);
                                }

                                if (@$pi['fulfilled_selection_criteria'] == 'Y') { $ppdf->SetXY(82,128.5); $ppdf->Write(0,'X'); }
                                else {
                                    $ppdf->SetXY(82,133.5); $ppdf->Write(0,'X');
                                    $ppdf->SetXY(95,140); $ppdf->Write(0, @$pi['fulfilled_selection_criteria_reason']);
                                }

                                if (@$cl['q_1_age'] == 'Y') { $ppdf->SetXY(167,173); $ppdf->Write(0,'X'); }
                                if (@$cl['q_2_1'] == 'Y')   { $ppdf->SetXY(167,205); $ppdf->Write(0,'X'); }
                                $qualY2 = ['q_2_2_a'=>226,'q_2_2_b'=>236,'q_2_2_c'=>246,'q_2_2_d'=>256];
                                foreach ($qualY2 as $fld => $ry) {
                                    if (@$cl[$fld] == 'Y') { $ppdf->SetXY(167, $ry); $ppdf->Write(0,'X'); }
                                }
                            }
                        }

                        if ($pageNo == 2 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 3) {
                            $cl = @$getPreAuthData['checklist'];

                            $qualY3 = ['q_2_3_a'=>45.6,'q_2_3_b'=>52.5,'q_2_4'=>62.5];
                            foreach ($qualY3 as $fld => $ry) {
                                if (@$cl[$fld] == 'Y') { $ppdf->SetXY(167, $ry); $ppdf->Write(0,'X'); }
                            }

                            $diagRows2 = [['q_3_1','q_3_1_date', 97], ['q_3_2','q_3_2_date', 109.5]];
                            foreach ($diagRows2 as $r) {
                                [$yk,$dk,$ry] = $r;
                                if (@$cl[$yk] == 'Y') {
                                    $ppdf->SetXY(150, $ry); $ppdf->Write(0,'X');
                                    if (!empty(@$cl[$dk])) { $ppdf->SetXY(172, $ry); $ppdf->Write(0, date('m/d/Y', strtotime($cl[$dk]))); }
                                }
                            }

                            $pCentered($pDecrypt(@$cl['crtby_attendingcardiologist']), 8, 146.5, 100, 6);
                            $pCentered($pDecrypt(@$cl['crtby_attendingcardiovascularsurgeon']), 115, 146.5, 90, 6);
                            $pBoxedNumber($pDecrypt(@$cl['crtby_attendingcardiologist_accreno']), 47.5, 162.7, 4.15);
                            $pBoxedNumber($pDecrypt(@$cl['crtby_attendingcardiovascularsurgeon_accreno']), 134.0, 162.7, 4.15);

                            // ZPAMS-FIX (2026-09): the "Conforme by: Patient" box on this page sits
                            // in the RIGHT column (matching the medical-director-style box seen on
                            // every other template's request page), not the left -- an earlier
                            // diagnostic pass only validated the Y coordinate here (it probed a single
                            // fixed x for every candidate row), so the x=8 left-column guess went
                            // unverified and landed outside any box.
                            $pCentered($pDecrypt(@$cl['crtby_patient']), 115, 191.5, 90, 7);
                            if (!empty(@$cl['crtby_patient_signeddate'])) {
                                $ppdf->SetFont('Helvetica','',8);
                                $ppdf->SetXY(160, 202.5); $ppdf->Write(0, date('m/d/Y', strtotime($cl['crtby_patient_signeddate'])));
                            }
                        }

                        if ($pageNo == 3 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 3) {
                            $pi = $getPreAuthData['patientinfo'];
                            $rq = @$getPreAuthData['request'];

                            $ppdf->SetFont('Helvetica','',9);
                            $ppdf->SetXY(135, 52);
                            $ppdf->Write(0, !empty(@$pi['submitted_datetime']) ? date('m/d/Y', strtotime($pi['submitted_datetime'])) : '');

                            $pCentered($pFullName($pi,'patient'), 20, 68, 45, 9);
                            $pCentered($this->myutilities->getRef_Desc(104,@$pi['healthfacility_code']), 75, 68, 110, 9);

                            // ZPAMS-FIX (2026-09): was `else`, which marked "With co-payment" for ANY
                            // non-'wocp' value including an empty/unset field -- a case with no
                            // copayment answer at all got a false "With co-payment" mark. The wizard's
                            // own radio only ever stores 'wocp' or 'wcp' (preauth_request/preauth_NN.php);
                            // gating on 'wcp' explicitly leaves both boxes blank when truly unanswered.
                            if (@$rq['copayment'] == 'wocp') { $ppdf->SetXY(27.6, 97); $ppdf->Write(0,'X'); }
                            elseif (@$rq['copayment'] == 'wcp') {
                                $ppdf->SetXY(27.6, 103); $ppdf->Write(0,'X');
                                $ppdf->SetXY(95, 103); $ppdf->Write(0, $pDecrypt(@$rq['with_copayment_purpose']));
                            }

                            $pCentered($pDecrypt(@$rq['crtby_attendingcardiologist']), 8, 119, 100, 6);
                            $pCentered($pDecrypt(@$rq['crtby_attendingcardiovascularsurgeon']), 115, 119, 90, 6);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_attendingcardiologist_accreno']), 47.5, 135.5, 4.15);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_attendingcardiovascularsurgeon_accreno']), 134.0, 135.5, 4.15);

                            $pCentered($pDecrypt(@$rq['crtby_patient']), 8, 150, 100, 7);
                            $pCentered($pDecrypt(@$rq['crtby_medicaldirectory']), 115, 150, 90, 6);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_medicaldirectory_accreno']), 134.0, 168, 4.15);
                        }

                        // ZPAMS-FIX (2026-09): field mapping for preauth_type 2 (Breast Cancer).
                        // This template uses the "new style" base header (HEALTH FACILITY (HF), no
                        // "Same as patient" checkbox -- the form just instructs writing "same as
                        // above" by hand) and spans 4 physical pages: 1) patient info + treatment
                        // history + menstrual/HER2/laterality-staging, 2) applicable treatment
                        // protocol, 3) patient info repeated + 3 physician signatures + conforme,
                        // 4) the request page with 4 signature blocks. Coordinates measured
                        // empirically per page; the deep chemotherapy-protocol regimen sub-choice on
                        // page 2 is left unmapped (not enough time to verify its DB value encoding
                        // against the 3 named regimens) -- everything else is covered.
                        if ($pageNo == 1 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 2) {
                            $pi = $getPreAuthData['patientinfo'];
                            $cl = @$getPreAuthData['checklist'];

                            $ppdf->SetXY(70, 52.2); $ppdf->Write(0,$this->myutilities->getRef_Desc(104,@$pi['healthfacility_code']));
                            $ppdf->SetXY(70, 59.3); $ppdf->Write(0,$this->myutilities->getRef_Desc(104,@$pi['healthfacility_code'],false,'inst_address_street'));

                            $pCentered($pFullName($pi,'patient'), 50, 70, 98, 7);
                            $ppdf->SetFont('Helvetica','',9);
                            if (@$pi['patient_sex'] == 'M') { $ppdf->SetXY(153, 72.5); $ppdf->Write(0,'X'); }
                            else { $ppdf->SetXY(169, 72.5); $ppdf->Write(0,'X'); }
                            // ZPAMS-FIX (2026-09): this template's PhilHealth ID boxes start at
                            // x=113.9 (measured via pixel scan) -- noticeably further right than every
                            // other template's ID row, confirmed after the shared x=107.6 assumption
                            // rendered digits drifting outside the box outlines.
                            $pBoxedNumber(@$pi['patient_philhealthno'], 113.9, 79.5, 4.2);

                            if (@$pi['patient_is_member'] == 'Y') {
                                $ppdf->SetFont('Helvetica','',7);
                                $ppdf->SetXY(50, 99); $ppdf->Write(0, 'SAME AS ABOVE');
                            } else {
                                $pCentered($pFullName($pi,'member'), 50, 99, 98, 7);
                                $pBoxedNumber(@$pi['member_philhealthno'], 113.9, 106.6, 4.2);
                            }

                            $hptRows = [
                                'hpt_1' => ['y'=>136.1, 'specify'=>['hpt_1_specify', 71.2], 'date'=>['hpt_1_date', 164.4]],
                                'hpt_2' => ['y'=>142.4, 'date'=>['hpt_2_date', 164.4]],
                                'hpt_3' => ['y'=>148.6, 'date'=>['hpt_3_date', 164.4]],
                                'hpt_4' => ['y'=>154.9, 'specify'=>['hpt_4_specify', 86.4], 'date'=>['hpt_4_date', 164.4]],
                            ];
                            $ppdf->SetFont('Helvetica','',8);
                            foreach ($hptRows as $fld => $cfg) {
                                if (@$cl[$fld] == 'Y') {
                                    $ppdf->SetXY(25.5, $cfg['y']); $ppdf->Write(0,'X');
                                    if (isset($cfg['specify']) && !empty(@$cl[$cfg['specify'][0]])) {
                                        $ppdf->SetXY($cfg['specify'][1], $cfg['y']+3); $ppdf->Write(0, @$cl[$cfg['specify'][0]]);
                                    }
                                    if (!empty(@$cl[$cfg['date'][0]])) {
                                        $ppdf->SetXY($cfg['date'][1], $cfg['y']); $ppdf->Write(0, date('m/d/Y', strtotime($cl[$cfg['date'][0]])));
                                    }
                                }
                            }

                            if (@$cl['menstrual'] == 'pre') { $ppdf->SetXY(25.5, 181.5); $ppdf->Write(0,'X'); }
                            elseif (@$cl['menstrual'] == 'post') { $ppdf->SetXY(105.8, 181.5); $ppdf->Write(0,'X'); }

                            $her2Y = ['0'=>25.5, '2'=>83.8, '3'=>134.5];
                            $her2Key = (string) @$cl['HER2'];
                            if (isset($her2Y[$her2Key])) { $ppdf->SetXY($her2Y[$her2Key], 193.9); $ppdf->Write(0,'X'); }

                            if (@$cl['laterality_r'] == 'Y') { $ppdf->SetXY(25.5, 210.2); $ppdf->Write(0,'X'); }
                            if (@$cl['laterality_l'] == 'Y') { $ppdf->SetXY(109.3, 210.2); $ppdf->Write(0,'X'); }
                            $stageY = [1=>215.3,2=>220.2,3=>225.1,4=>230,5=>234.9,6=>239.8,7=>244.7,8=>249.6,9=>254.5];
                            $rStage = (int) @$cl['clinical_staging_r'];
                            $lStage = (int) @$cl['clinical_staging_l'];
                            if (isset($stageY[$rStage])) { $ppdf->SetXY(25.5, $stageY[$rStage]); $ppdf->Write(0,'X'); }
                            if (isset($stageY[$lStage])) { $ppdf->SetXY(109.3, $stageY[$lStage]); $ppdf->Write(0,'X'); }
                        }

                        if ($pageNo == 2 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 2) {
                            $cl = @$getPreAuthData['checklist'];
                            $ppdf->SetFont('Helvetica','',8);

                            // ZPAMS-FIX (2026-09): atp_surgery and atp_cchemotherapy both store 'A'
                            // (Adjuvant) / 'N' (Neoadjuvant) -- confirmed against the wizard's own
                            // checklist view (preauth_checklist/preauth_02.php), which radios both
                            // fields to value="A"/value="N". This code was checking for 'adj'/'neoadj',
                            // which those fields never actually contain, so neither Surgery's nor
                            // Chemotherapy's Adjuvant/Neoadjuvant selection was ever being marked on
                            // the generated PDF.
                            if (@$cl['atp_surgery'] == 'A') { $ppdf->SetXY(115.9, 39.4); $ppdf->Write(0,'X'); }
                            if (@$cl['atp_surgery'] == 'N') { $ppdf->SetXY(142.6, 39.4); $ppdf->Write(0,'X'); }
                            if (@$cl['atp_hormonaltherapy'] == 'Y') { $ppdf->SetXY(27.6, 48.9); $ppdf->Write(0,'X'); }
                            if (@$cl['atp_cchemotherapy'] == 'A') { $ppdf->SetXY(115.9, 56.7); $ppdf->Write(0,'X'); }
                            if (@$cl['atp_cchemotherapy'] == 'N') { $ppdf->SetXY(142.6, 56.7); $ppdf->Write(0,'X'); }

                            // Cytotoxic Chemotherapy -- Protocol sub-choice (previously unmapped).
                            // Field values confirmed against the wizard view: ACT/ACP/TCB. Coordinates
                            // verified via rendered marker overlay against PreAuth_02.pdf page 2.
                            if (@$cl['atp_cchemotherapy_protocol'] == 'ACT') { $ppdf->SetXY(115.9, 76.2); $ppdf->Write(0,'X'); }
                            if (@$cl['atp_cchemotherapy_protocol'] == 'ACP') { $ppdf->SetXY(115.9, 95.7); $ppdf->Write(0,'X'); }
                            if (@$cl['atp_cchemotherapy_protocol'] == 'TCB') { $ppdf->SetXY(115.9, 115.2); $ppdf->Write(0,'X'); }

                            if (@$cl['atp_targettherapy'] == 'Y') { $ppdf->SetXY(27.6, 127.6); $ppdf->Write(0,'X'); }
                            if (!empty(@$cl['atp_targettherapy_specify'])) { $ppdf->SetXY(150, 134.5); $ppdf->Write(0, @$cl['atp_targettherapy_specify']); }
                            if (@$cl['atp_surveillance'] == 'Y') { $ppdf->SetXY(27.6, 147.7); $ppdf->Write(0,'X'); }
                        }

                        if ($pageNo == 3 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 2) {
                            $pi = $getPreAuthData['patientinfo'];
                            $cl = @$getPreAuthData['checklist'];

                            $ppdf->SetXY(70, 28.8); $ppdf->Write(0,$this->myutilities->getRef_Desc(104,@$pi['healthfacility_code']));
                            $ppdf->SetXY(70, 38.1); $ppdf->Write(0,$this->myutilities->getRef_Desc(104,@$pi['healthfacility_code'],false,'inst_address_street'));

                            $pCentered($pFullName($pi,'patient'), 50, 46.5, 98, 7);
                            $ppdf->SetFont('Helvetica','',9);
                            if (@$pi['patient_sex'] == 'M') { $ppdf->SetXY(153, 53.4); $ppdf->Write(0,'X'); }
                            else { $ppdf->SetXY(169, 53.4); $ppdf->Write(0,'X'); }
                            $pBoxedNumber(@$pi['patient_philhealthno'], 113.9, 60.3, 4.2);

                            if (@$pi['patient_is_member'] == 'Y') {
                                $ppdf->SetFont('Helvetica','',7);
                                $ppdf->SetXY(50, 80); $ppdf->Write(0, 'SAME AS ABOVE');
                            } else {
                                $pCentered($pFullName($pi,'member'), 50, 80, 98, 7);
                                $pBoxedNumber(@$pi['member_philhealthno'], 113.9, 87.1, 4.2);
                            }

                            $pCentered($pDecrypt(@$cl['crtby_attendingmedoncologist']), 5, 124.5, 105, 6);
                            $pCentered($pDecrypt(@$cl['crtby_attendingsurgeon']), 112, 124.5, 105, 6);
                            $pBoxedNumber($pDecrypt(@$cl['crtby_attendingmedoncologist_accreno']), 38, 137, 4.5);
                            $pBoxedNumber($pDecrypt(@$cl['crtby_attendingsurgeon_accreno']), 134.5, 137, 4.3);

                            $pCentered($pDecrypt(@$cl['crtby_attendingradoncologist']), 5, 176.5, 105, 6);
                            $pBoxedNumber($pDecrypt(@$cl['crtby_attendingradoncologist_accreno']), 38, 189, 4.5);

                            $pCentered($pDecrypt(@$cl['crtby_patient']), 112, 176.5, 105, 7);
                            if (!empty(@$cl['crtby_patient_signeddate'])) {
                                $ppdf->SetFont('Helvetica','',7);
                                $ppdf->SetXY(163, 192.5); $ppdf->Write(0, date('m/d/Y', strtotime($cl['crtby_patient_signeddate'])));
                            }
                        }

                        if ($pageNo == 4 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 2) {
                            $pi = $getPreAuthData['patientinfo'];
                            $rq = @$getPreAuthData['request'];

                            $ppdf->SetFont('Helvetica','',9);
                            $ppdf->SetXY(90, 27.5);
                            $ppdf->Write(0, !empty(@$pi['submitted_datetime']) ? date('m/d/Y', strtotime($pi['submitted_datetime'])) : '');

                            $pCentered($pFullName($pi,'patient'), 15, 37, 90, 8);
                            $pCentered($this->myutilities->getRef_Desc(104,@$pi['healthfacility_code']), 100, 37, 90, 8);

                            // ZPAMS-FIX (2026-09): see the same fix's note on the CABG request page --
                            // gate on 'wcp' explicitly rather than "anything not 'wocp'".
                            if (@$rq['copayment'] == 'wocp') { $ppdf->SetXY(18.6, 69); $ppdf->Write(0,'X'); }
                            elseif (@$rq['copayment'] == 'wcp') {
                                $ppdf->SetXY(18.6, 74); $ppdf->Write(0,'X');
                                $ppdf->SetXY(30, 80); $ppdf->Write(0, $pDecrypt(@$rq['with_copayment_purpose']));
                            }

                            $pCentered($pDecrypt(@$rq['crtby_attendingmedoncologist']), 5, 93, 105, 6);
                            $pCentered($pDecrypt(@$rq['crtby_attendingsurgeon']), 112, 93, 95, 6);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_attendingmedoncologist_accreno']), 37.8, 108, 4.5);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_attendingsurgeon_accreno']), 134.5, 108, 4.3);

                            $pCentered($pDecrypt(@$rq['crtby_attendingradoncologist']), 5, 127, 105, 6);
                            $pCentered($pDecrypt(@$rq['crtby_medicaldirectory']), 112, 127, 95, 6);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_attendingradoncologist_accreno']), 37.8, 149, 4.5);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_medicaldirectory_accreno']), 134.5, 149, 4.3);

                            $pCentered($pDecrypt(@$rq['crtby_patient']), 112, 168, 95, 7);
                        }

                        // ZPAMS-FIX (2026-09): field mapping for preauth_type 6 (Kidney
                        // Transplantation). This template is the largest in the whole Z-Ben form set
                        // -- 6 physical pages. The Selection Criteria questionnaire (q_0_x / q_1_x,
                        // pages 1-4) is mapped below using the field order from the wizard's own
                        // checklist view (application/views/.../preauth_checklist/preauth_06.php,
                        // $qualitifationlist array) as the source of truth for which DB field
                        // corresponds to which printed question -- NOT the DB column declaration
                        // order, which does not reliably indicate page position. Coordinates were
                        // verified via word-level PDF text extraction (exact for every Yes/No/NA
                        // checkbox in the numbered 1.1-11 list) plus a visual marker overlay for the
                        // vector-drawn checkboxes in the top-of-page1 grid (History of Previous
                        // Kidney Transplantation / Type of Transplantation Procedure).
                        //
                        // Deliberately NOT mapped: the oncologist/gastroenterologist medical-clearance
                        // blocks (name, affiliation, date, accreditation) that appear inline after
                        // items 7.2, 8.2 and 10, and the three separate "Informed Consent / Conforme
                        // by" blocks tied to those same conditional items. The checklist table has no
                        // DB fields for any of these at all (confirmed against
                        // M_preauth_form_flds::preauth_06) -- they are distinct, conditionally-
                        // applicable clinical attestations that the wizard never collects, so there is
                        // no data to print and nothing to guess at.
                        if ($pageNo == 1 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6) {
                            $pi = $getPreAuthData['patientinfo'];

                            $ppdf->SetXY(70, 52.2); $ppdf->Write(0,$this->myutilities->getRef_Desc(104,@$pi['healthfacility_code']));
                            $ppdf->SetXY(70, 59.3); $ppdf->Write(0,$this->myutilities->getRef_Desc(104,@$pi['healthfacility_code'],false,'inst_address_street'));

                            $pCentered($pFullName($pi,'patient'), 50, 70, 98, 7);
                            $ppdf->SetFont('Helvetica','',9);
                            if (@$pi['patient_sex'] == 'M') { $ppdf->SetXY(153, 72.5); $ppdf->Write(0,'X'); }
                            else { $ppdf->SetXY(169, 72.5); $ppdf->Write(0,'X'); }
                            $pBoxedNumber(@$pi['patient_philhealthno'], 113.9, 79.5, 4.2);

                            if (@$pi['patient_is_member'] == 'Y') {
                                $ppdf->SetXY(25.5, 89.3); $ppdf->Write(0,'X');
                            } else {
                                $pCentered($pFullName($pi,'member'), 50, 93, 98, 7);
                                if (@$pi['member_sex'] == 'M') { $ppdf->SetXY(153, 93); $ppdf->Write(0,'X'); }
                                else { $ppdf->SetXY(169, 93); $ppdf->Write(0,'X'); }
                                $pBoxedNumber(@$pi['member_philhealthno'], 113.9, 103, 4.2);
                            }

                            $cl = @$getPreAuthData['checklist'];

                            // History of Previous Kidney Transplantation
                            if (@$cl['q_0_1'] == 'NA') { $ppdf->SetXY(141.5, 113.5); $ppdf->Write(0,'X'); }
                            if (@$cl['q_0_2'] == '1') { $ppdf->SetXY(24.5, 119.4); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_0_2'] == '2') { $ppdf->SetXY(112.3, 119.8); $ppdf->Write(0,'X'); }
                            if (@$cl['q_0_2_1'] == '1') { $ppdf->SetXY(29.5, 123.7); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_0_2_1'] == '2') { $ppdf->SetXY(61.5, 123.7); $ppdf->Write(0,'X'); }
                            if (@$cl['q_0_3'] == 'Y') { $ppdf->SetXY(24.5, 130.8); $ppdf->Write(0,'X'); }
                            if (!empty(@$cl['q_0_3_date'])) {
                                $ppdf->SetFont('Helvetica','',8);
                                $ppdf->SetXY(113.1, 130.3); $ppdf->Write(0, date('m/d/Y', strtotime($cl['q_0_3_date'])));
                                $ppdf->SetFont('Helvetica','',9);
                            }
                            if (@$cl['q_0_3_1'] == '1') { $ppdf->SetXY(30.5, 135.6); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_0_3_1'] == '2') { $ppdf->SetXY(47.3, 135.6); $ppdf->Write(0,'X'); }

                            // Kidney Transplantation Procedure grid (Type of Transplantation /
                            // Immunosuppression / Donor Nephrectomy / Organ Preservation)
                            if (@$cl['q_0_4'] == '1') { $ppdf->SetXY(25.5, 173.3); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_0_4'] == '2') { $ppdf->SetXY(23.5, 204.5); $ppdf->Write(0,'X'); }
                            if (@$cl['q_0_4_1'] == '1') { $ppdf->SetXY(30, 176.1); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_0_4_1'] == '2') { $ppdf->SetXY(30, 180.9); $ppdf->Write(0,'X'); }
                            if (@$cl['q_0_5'] == '1') { $ppdf->SetXY(72.5, 171.6); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_0_5'] == '2') { $ppdf->SetXY(72.5, 181.3); $ppdf->Write(0,'X'); }
                            if (@$cl['q_0_6'] == '1') { $ppdf->SetXY(127.5, 170.5); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_0_6'] == '2') { $ppdf->SetXY(127.5, 189.7); $ppdf->Write(0,'X'); }
                            if (@$cl['q_0_7'] == '1') { $ppdf->SetXY(127.5, 212.1); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_0_7'] == '2') { $ppdf->SetXY(127.5, 216.9); $ppdf->Write(0,'X'); }

                            // Selection Criteria 1.1 - 1.2 (continues on pages 2 and 4)
                            if (@$cl['q_1_1'] == 'Y') { $ppdf->SetXY(163.0, 246.6); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_2'] == 'Y') { $ppdf->SetXY(163.0, 259.6); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_2'] == 'NA') { $ppdf->SetXY(175.5, 259.6); $ppdf->Write(0,'X'); }
                        }

                        if ($pageNo == 2 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6) {
                            $cl = @$getPreAuthData['checklist'];

                            if (@$cl['q_1_3'] == 'Y') { $ppdf->SetXY(163.0, 47.6); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_3'] == 'NA') { $ppdf->SetXY(175.5, 47.6); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_4'] == 'Y') { $ppdf->SetXY(163.0, 61.1); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_5'] == 'Y') { $ppdf->SetXY(163.0, 69.2); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_6'] == 'N') { $ppdf->SetXY(29.5, 82.1); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_6'] == 'Y') { $ppdf->SetXY(29.5, 86.9); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_6_1'] == 'Y') { $ppdf->SetXY(163.0, 96.9); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_6_1'] == 'NA') { $ppdf->SetXY(175.0, 96.9); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_6_2'] == 'Y') { $ppdf->SetXY(163.0, 105.0); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_6_2'] == 'NA') { $ppdf->SetXY(175.0, 105.0); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_6_2_1'] == 'Y') { $ppdf->SetXY(163.0, 114.8); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_6_2_2'] == 'Y') { $ppdf->SetXY(163.0, 119.9); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_6_3'] == 'Y') { $ppdf->SetXY(163.0, 129.4); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_6_4'] == 'Y') { $ppdf->SetXY(163.0, 134.4); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_7'] == 'Y') { $ppdf->SetXY(163.0, 148.8); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_8'] == 'Y') { $ppdf->SetXY(163.0, 177.8); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_9'] == 'N') { $ppdf->SetXY(29.5, 204.1); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_9'] == 'Y') { $ppdf->SetXY(29.5, 208.9); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_10'] == 'Y') { $ppdf->SetXY(163.0, 215.7); $ppdf->Write(0,'X'); }
                            elseif (in_array(@$cl['q_1_10'], ['N','NA'])) { $ppdf->SetXY(175.0, 215.7); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_10_1'] == 'Y') { $ppdf->SetXY(32.3, 225.2); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_10_2'] == 'Y') { $ppdf->SetXY(32.3, 234.8); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_10_3'] == 'Y') { $ppdf->SetXY(32.3, 239.7); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_10_4'] == 'Y') { $ppdf->SetXY(32.3, 244.5); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_10_5'] == 'Y') { $ppdf->SetXY(32.3, 254.1); $ppdf->Write(0,'X'); }
                        }

                        // Page 3 for preauth_type 6: only Selection Criteria items 8.1/8.2 are
                        // mapped. The oncologist-equivalent gastroenterologist medical-clearance
                        // block and the "Informed Consent / Conforme by" block on this page have no
                        // corresponding DB fields (see scope note above) and are left blank.
                        if ($pageNo == 3 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6) {
                            $cl = @$getPreAuthData['checklist'];

                            if (@$cl['q_1_11'] == 'Y') { $ppdf->SetXY(163.0, 96.9); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_11'] == 'N') { $ppdf->SetXY(175.0, 96.9); $ppdf->Write(0,'X'); }
                            // Note: the printed template has no "No" box for row 8.2 (only "Yes"
                            // is printed) -- a 'N' value here has nowhere correct to be marked.
                            if (@$cl['q_1_12'] == 'Y') { $ppdf->SetXY(163.0, 105.1); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_12_1'] == 'Y') { $ppdf->SetXY(30.3, 109.7); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_12_2'] == 'Y') { $ppdf->SetXY(30.3, 114.5); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_12_3'] == 'Y') { $ppdf->SetXY(30.3, 124.1); $ppdf->Write(0,'X'); }
                        }

                        // Page 4 for preauth_type 6: same scope note as page 3 -- the
                        // gastroenterologist clearance block for item 10 and the two Informed
                        // Consent / Conforme blocks (items 10 and 11) have no DB fields and are
                        // left blank.
                        if ($pageNo == 4 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6) {
                            $cl = @$getPreAuthData['checklist'];

                            if (@$cl['q_1_13'] == 'Y') { $ppdf->SetXY(163.0, 33.2); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_13'] == 'N') { $ppdf->SetXY(175.0, 33.2); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_14'] == 'Y') { $ppdf->SetXY(163.0, 41.4); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_14'] == 'NA') { $ppdf->SetXY(175.0, 41.4); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_15'] == 'Y') { $ppdf->SetXY(163.0, 60.8); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_15'] == 'NA') { $ppdf->SetXY(175.0, 60.8); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_16_1'] == 'Y') { $ppdf->SetXY(163.0, 224.9); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_16_1'] == 'NA') { $ppdf->SetXY(175.0, 224.9); $ppdf->Write(0,'X'); }
                            if (@$cl['q_1_16_2'] == 'Y') { $ppdf->SetXY(163.0, 230.1); $ppdf->Write(0,'X'); }
                            elseif (@$cl['q_1_16_2'] == 'NA') { $ppdf->SetXY(175.0, 230.1); $ppdf->Write(0,'X'); }
                        }

                        if ($pageNo == 5 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6) {
                            $cl = @$getPreAuthData['checklist'];

                            $pCentered($pDecrypt(@$cl['crtby_attendingnephrologist']), 5, 116, 100, 6);
                            $pCentered($pDecrypt(@$cl['crtby_attendingtransplantsurgeon']), 112, 116, 100, 6);
                            $pBoxedNumber($pDecrypt(@$cl['crtby_attendingnephrologist_accreno']), 23.8, 129, 4.16);
                            $pBoxedNumber($pDecrypt(@$cl['crtby_attendingtransplantsurgeon_accreno']), 119, 129, 4.2);

                            $pCentered($pDecrypt(@$cl['crtby_patient']), 112, 163, 100, 7);
                            if (!empty(@$cl['crtby_patient_signeddate'])) {
                                $ppdf->SetFont('Helvetica','',8);
                                $ppdf->SetXY(150, 182.7); $ppdf->Write(0, date('m/d/Y', strtotime($cl['crtby_patient_signeddate'])));
                            }
                        }

                        if ($pageNo == 6 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6) {
                            $pi = $getPreAuthData['patientinfo'];
                            $rq = @$getPreAuthData['request'];

                            $ppdf->SetFont('Helvetica','',9);
                            $ppdf->SetXY(140, 33.9);
                            $ppdf->Write(0, !empty(@$pi['submitted_datetime']) ? date('m/d/Y', strtotime($pi['submitted_datetime'])) : '');

                            $pCentered($pFullName($pi,'patient'), 15, 45.5, 90, 8);
                            $pCentered($this->myutilities->getRef_Desc(104,@$pi['healthfacility_code']), 100, 45.5, 90, 8);

                            // ZPAMS-FIX (2026-09): see the same fix's note on the CABG request page --
                            // gate on 'wcp' explicitly rather than "anything not 'wocp'".
                            if (@$rq['copayment'] == 'wocp') { $ppdf->SetXY(24, 78); $ppdf->Write(0,'X'); }
                            elseif (@$rq['copayment'] == 'wcp') {
                                $ppdf->SetXY(24, 83); $ppdf->Write(0,'X');
                                $ppdf->SetXY(30, 89); $ppdf->Write(0, $pDecrypt(@$rq['copayment_amount']));
                            }

                            $pCentered($pDecrypt(@$rq['crtby_attendingnephrologist']), 5, 100, 105, 6);
                            $pCentered($pDecrypt(@$rq['crtby_attendingtransplantsurgeon']), 112, 100, 95, 6);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_attendingnephrologist_accreno']), 23.8, 117, 4.16);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_attendingtransplantsurgeon_accreno']), 119, 117, 4.2);

                            $pCentered($pDecrypt(@$rq['crtby_patient']), 5, 137, 105, 6);
                            $pCentered($pDecrypt(@$rq['crtby_medicaldirectory']), 112, 137, 95, 6);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_medicaldirectory_accreno']), 119, 157.3, 4.2);
                        }

                        if ($pageNo == 3 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 14) {
                            $pi = $getPreAuthData['patientinfo'];
                            $rq = @$getPreAuthData['request'];

                            $ppdf->SetXY(140, 49.5);
                            $ppdf->Write(0, !empty(@$pi['submitted_datetime']) ? date('m/d/Y', strtotime($pi['submitted_datetime'])) : '');

                            $pCentered($pFullName($pi,'patient'), 25, 61.5, 85, 9);
                            $pCentered($this->myutilities->getRef_Desc(104,@$pi['healthfacility_code']), 118, 61.5, 75, 9);

                            // ZPAMS-FIX (2026-09): see the same fix's note on the CABG request page --
                            // gate on 'wcp' explicitly rather than "anything not 'wocp'".
                            if (@$rq['copayment'] == 'wocp') { $ppdf->SetXY(31, 88.8); $ppdf->Write(0,'X'); }
                            elseif (@$rq['copayment'] == 'wcp') {
                                $ppdf->SetXY(31, 95.8); $ppdf->Write(0,'X');
                                $ppdf->SetXY(100, 95.5); $ppdf->Write(0, $pDecrypt(@$rq['with_copayment_purpose']));
                            }

                            // ZPAMS-FIX (2026-09): y was 108.5, landing above the "Certified correct
                            // by:" row's own header instead of on the blank signature line below it.
                            // Re-measured against a gridline overlay: the gap between that row's
                            // bottom divider (~110.4) and the "(Printed name and signature)" caption
                            // (~112.9) is only ~2.5mm, so this also needs a smaller font to avoid
                            // touching the caption underneath.
                            $pCentered($pDecrypt(@$rq['crtby_attendingphysician']), 10, 110.6, 90, 6);
                            $pCentered($pDecrypt(@$rq['crtby_medicaldirectory']), 113, 110.6, 92, 6);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_attendingphysician_accreno']), 51.3, 129.5);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_medicaldirectory_accreno']), 133.1, 129.5);
                            $pCentered($pDecrypt(@$rq['crtby_patient']), 115, 142.5, 90, 9);
                        }

                        if ($pageNo == 3 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 4) {
                            $pi = $getPreAuthData['patientinfo'];
                            $rq = @$getPreAuthData['request'];

                            $ppdf->SetFont('Helvetica','',9);
                            $ppdf->SetXY(135, 44);
                            $ppdf->Write(0, !empty(@$pi['submitted_datetime']) ? date('m/d/Y', strtotime($pi['submitted_datetime'])) : '');

                            $pCentered($pFullName($pi,'patient'), 20, 58, 45, 9);
                            $pCentered($this->myutilities->getRef_Desc(104,@$pi['healthfacility_code']), 75, 58, 110, 9);

                            // ZPAMS-FIX (2026-09): confirmed live on a seeded case with no copayment
                            // answer set at all -- the old `else` marked "With co-payment" regardless.
                            // Gate on 'wcp' explicitly rather than "anything not 'wocp'".
                            if (@$rq['copayment'] == 'wocp') { $ppdf->SetXY(27.6, 95); $ppdf->Write(0,'X'); }
                            elseif (@$rq['copayment'] == 'wcp') {
                                $ppdf->SetXY(27.6, 99); $ppdf->Write(0,'X');
                                $ppdf->SetXY(30, 112.5); $ppdf->Write(0, $pDecrypt(@$rq['with_copayment_purpose']));
                            }
                            // Treatment modality -- single-select radio (code 1 or 2, per
                            // ref_zben_cc_treatmentmodality), same pattern as FIGO staging above.
                            $tmY = [1=>87.5, 2=>101.5];
                            $tmCode = (int) @$rq['treatmentmodality'];
                            if (isset($tmY[$tmCode])) { $ppdf->SetXY(110.7, $tmY[$tmCode]); $ppdf->Write(0,'X'); }

                            // Signature-line gap here is tight (~5.6mm from divider to caption),
                            // matching the same issue found and fixed for Prostate CA's page 3 --
                            // smaller font, positioned right after the divider.
                            $pCentered($pDecrypt(@$rq['crtby_attendingqynecologiconcologist']), 8, 118.5, 100, 6);
                            $pCentered($pDecrypt(@$rq['crtby_medicaldirectory']), 115, 118.5, 90, 6);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_attendingqynecologiconcologist_accreno']), 47.5, 143, 4.15);
                            $pBoxedNumber($pDecrypt(@$rq['crtby_medicaldirectory_accreno']), 134.0, 143, 4.15);
                            $pCentered($pDecrypt(@$rq['crtby_patient']), 118, 155.5, 88, 7);
                        }
                    }

                    $pBlobContent = $ppdf->Output($fileNameTitle,'S');
                    $ppdf->Close();

                    $fla = [
                        'FileName' => $fileNameTitle,
                        'FileType' => 'application/pdf',
                        'FileData' => base64_encode($pBlobContent),
                        'nonce'    => $this->nonceV
                    ];

                    $fileClick = 'onclick="javascript:ObjectFileViewer(\''.base64_encode(json_encode($fla)).'\')" ';
                }
            }
        }
        catch (PDOException $e) {
          log_message("error",__METHOD__." | ".$e->getMessage());
        } catch (Throwable $e) {
          // ZPAMS-FIX (2026-09): was `catch (Exception $e)`, which does not catch a PHP Error/
          // TypeError (e.g. a fatal thrown inside the PDF library) -- widened to Throwable so a
          // failed PDF build is actually logged instead of surfacing as a blank response.
          log_message("error",__METHOD__." | ".$e->getMessage());
        }

        return $fileClick;
    }
}
