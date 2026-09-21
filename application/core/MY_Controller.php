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

                            // ZPAMS-FIX (2026-09): accounts created outside the normal registration flow
                            // (M_register.php / M_useraccount.php are the only places that generate this key)
                            // can end up with a NULL User_E_Key. Every PHP-level encrypt/decrypt call in this
                            // app -- Claims case-no URL tokens, CompleteName, the oPrivateKey passphrase just
                            // below -- silently returns false on a NULL key, which surfaces downstream as an
                            // empty URL param or a 500. Self-heal here so no account can stay in this state,
                            // the same way oPrivateKey is lazily generated right after this block.
                            if( array_key_exists('User_E_Key', $this->userprofile) && ($this->userprofile['User_E_Key'] == '' || is_null($this->userprofile['User_E_Key'])) )
                            {
                                $User_E_Key = base64_encode( $this->encryption->create_key(16) );
                                $this->sqlhelper->local->update("user_profiles")->ex_update(array('User_E_Key' => $User_E_Key))->where("user_id = '".$this->userid."'")->run();
                                $this->userprofile['User_E_Key'] = $User_E_Key;
                                $_SESSION['sys_userprofile']['Data']['User_E_Key'] = $User_E_Key;
                            }

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

                // ZPAMS-FIX (2026-09): config-driven field-map loader. If a JSON map exists for
                // this illness under assets/zben-forms/field-maps/preauth_NN.json, it's decoded
                // once here and consumed via $pRenderFields() (defined below) inside the page
                // loop, instead of that illness's coordinates being hardcoded PHP. Illnesses
                // without a JSON file simply fall through to their existing hardcoded block
                // unchanged -- this is additive/opt-in per illness, not a behavior change for
                // any illness that hasn't been migrated.
                $fieldMapFile = FCPATH."assets/zben-forms/field-maps/preauth_".str_pad($getPreAuthData['patientinfo']['preauth_type'],2,0,STR_PAD_LEFT).".json";
                $fieldMap = (file_exists($fieldMapFile)) ? json_decode(file_get_contents($fieldMapFile), true) : null;

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

                    // ZPAMS-FIX (2026-09): config-driven field renderer. Reads a field-mapping
                    // array (normally loaded from a JSON file, one per illness template) and
                    // draws each field using the exact same $pDecrypt/$pBoxedNumber/$pCentered
                    // helpers defined above, instead of one hardcoded SetXY()/Write() block per
                    // field written directly in this method. Purely additive -- not called from
                    // any existing illness block below. The 5 already-verified illnesses
                    // (preauth_type 02/03/04/06/14) are untouched; this is for mapping new
                    // illness templates going forward, where a coordinate fix becomes an edit to
                    // a JSON file instead of a PHP redeploy.
                    //
                    // $fields is an array of entries shaped like:
                    //   { "type": "checkbox", "source": "checklist", "field": "q_1_1",
                    //     "x": 174, "y": 157.5 }
                    // $data is $getPreAuthData itself (patientinfo/checklist/request).
                    //
                    // Supported "type" values:
                    //   text      -- plain Write() of the raw value
                    //   date      -- Write() of the value formatted as m/d/Y
                    //   centered  -- $pCentered()
                    //   boxed     -- $pBoxedNumber() (digit-only, strips non-alphanumeric;
                    //                needs "pitch")
                    //   boxed_raw -- one character per box INCLUDING separators, e.g.
                    //                accreditation numbers where a dash occupies its own
                    //                printed box (needs "pitch")
                    //   checkbox  -- writes 'X' at (x,y) only if the field's stored value
                    //                equals "match" (default 'Y')
                    //   radio     -- looks up (x,y) from options[<stored value>] and writes
                    //                'X' there -- for staging grids / coded single-select
                    //                fields (e.g. FIGO stage, chemo protocol)
                    // Every type accepts optional "decrypt": true (routes the value through
                    // $pDecrypt first) and "fontsize" (default 9).
                    $pRenderFields = function(array $fields, array $data) use ($ppdf, $pDecrypt, $pBoxedNumber, $pCentered, $pFullName, &$pRenderFields) {
                        foreach ($fields as $f) {
                            $source = $data[$f['source']] ?? [];
                            // "fullname" and "facility_name" are computed values, not a single
                            // stored field -- resolved before the generic $source[$f['field']]
                            // lookup below (which would find nothing for these).
                            if ($f['type'] === 'fullname') {
                                $raw = $pFullName($source, $f['prefix']);
                            } elseif ($f['type'] === 'facility_name') {
                                $raw = $this->myutilities->getRef_Desc(104, @$source['healthfacility_code'], false, $f['column'] ?? '');
                            } elseif ($f['type'] === 'member_toggle') {
                                $raw = null; // resolved entirely inside its own case below
                            } else {
                                $raw = $source[$f['field']] ?? null;
                            }
                            if (!empty($f['decrypt'])) { $raw = $pDecrypt($raw); }
                            $fontsize = $f['fontsize'] ?? 9;

                            switch ($f['type']) {
                                case 'text':
                                    if ($raw === null || $raw === '') break;
                                    $ppdf->SetFont('Helvetica','',$fontsize);
                                    $ppdf->SetXY($f['x'], $f['y']);
                                    $ppdf->Write(0, (string) $raw);
                                    break;

                                case 'date':
                                    if (empty($raw)) break;
                                    $ppdf->SetFont('Helvetica','',$fontsize);
                                    $ppdf->SetXY($f['x'], $f['y']);
                                    $ppdf->Write(0, date('m/d/Y', strtotime($raw)));
                                    break;

                                case 'centered':
                                case 'fullname':
                                case 'facility_name':
                                    if ($raw === null || $raw === '') break;
                                    // ZPAMS-FIX (2026-09): added migrating CABG/Breast Cancer/Kidney.
                                    // Breast Cancer and Kidney's page-1 blocks duplicate the shared
                                    // page-1 header's Health Facility/Address write with their own
                                    // slightly-offset coordinates (a pre-existing quirk in the original
                                    // code, not introduced here) -- those writes are plain left-aligned
                                    // Write() calls, not $pCentered()'s boxed/centered layout. "align":
                                    // "left" reproduces that exact original behavior; omitted/"center"
                                    // keeps every existing mapped field (Cervical, Prostate demo, CABG's
                                    // request-page facility_name) unchanged.
                                    if (($f['align'] ?? 'center') === 'left') {
                                        $ppdf->SetFont('Helvetica','',$fontsize);
                                        $ppdf->SetXY($f['x'], $f['y']);
                                        $ppdf->Write(0, (string) $raw);
                                    } else {
                                        $pCentered($raw, $f['x'], $f['y'], $f['w'], $fontsize);
                                    }
                                    break;

                                case 'boxed':
                                    if (empty($raw)) break;
                                    $pBoxedNumber($raw, $f['x'], $f['y'], $f['pitch'] ?? 3.9, $fontsize);
                                    break;

                                case 'boxed_raw':
                                    if (empty($raw)) break;
                                    $ppdf->SetFont('Helvetica','',$fontsize);
                                    foreach (str_split((string) $raw) as $i => $ch) {
                                        $ppdf->SetXY($f['x'] + ($i * $f['pitch']), $f['y']);
                                        $ppdf->Write(0, $ch);
                                    }
                                    break;

                                // "mark" -- unconditionally writes fixed text (default 'X') at
                                // (x,y). For slots like the "Same as Patient" checkbox where the
                                // surrounding member_toggle branch has already decided this slot
                                // applies -- no per-field value check needed.
                                case 'mark':
                                    $ppdf->SetFont('Helvetica','',$fontsize);
                                    $ppdf->SetXY($f['x'], $f['y']);
                                    $ppdf->Write(0, $f['text'] ?? 'X');
                                    break;

                                case 'checkbox':
                                    // ZPAMS-FIX (2026-09): added migrating Kidney Transplantation.
                                    // "match" can be a single value (all prior illnesses) or an array
                                    // of acceptable values -- Kidney's q_1_10 row marks the same box for
                                    // either 'N' or 'NA' (original: `elseif (in_array($cl['q_1_10'],
                                    // ['N','NA']))`). (array) wraps a scalar match transparently so
                                    // every existing single-value mapping is unaffected.
                                    $matches = array_map('strval', (array) ($f['match'] ?? 'Y'));
                                    if (!in_array((string) $raw, $matches, true)) break;
                                    $ppdf->SetFont('Helvetica','',$fontsize);
                                    $ppdf->SetXY($f['x'], $f['y']);
                                    $ppdf->Write(0, 'X');
                                    break;

                                case 'radio':
                                    $key = (string) $raw;
                                    if (!isset($f['options'][$key])) break;
                                    [$rx, $ry] = $f['options'][$key];
                                    $ppdf->SetFont('Helvetica','',$fontsize);
                                    $ppdf->SetXY($rx, $ry);
                                    $ppdf->Write(0, 'X');
                                    break;

                                // ZPAMS-FIX (2026-09): added migrating the 5 already-mapped illnesses.
                                // "boxed_segments" -- for PhilHealth ID / accreditation rows where the
                                // digit run is split by printed dashes into groups with their own start
                                // x and (usually matching) pitch, e.g. Prostate's 2-9-1 digit ID boxes.
                                // "segments": [{ "start":0, "length":2, "x":110.2, "pitch":5.3 }, ...]
                                // (start/length index into the digit-only string, same stripping rule
                                // as "boxed").
                                case 'boxed_segments':
                                    $digits = preg_replace('/[^A-Za-z0-9]/', '', (string) $raw);
                                    if ($digits === '') break;
                                    $ppdf->SetFont('Helvetica','',$fontsize);
                                    foreach ($f['segments'] as $seg) {
                                        $part = substr($digits, $seg['start'], $seg['length']);
                                        foreach (str_split($part) as $i => $ch) {
                                            $ppdf->SetXY($seg['x'] + ($i * ($seg['pitch'] ?? $f['pitch'] ?? 3.9)), $seg['y'] ?? $f['y']);
                                            $ppdf->Write(0, $ch);
                                        }
                                    }
                                    break;

                                // "member_toggle" -- the "Same as Patient" pattern common to all 5
                                // mapped illnesses: if the condition field equals "match" (default
                                // 'Y'), just mark/write the "same" slot; otherwise render the member's
                                // own name + PhilHealth ID using the same field types as everywhere
                                // else. "same" and "different.member_name"/"different.member_id" each
                                // take a normal field-entry shape (any type above).
                                case 'member_toggle':
                                    $condVal = (string) ($data[$f['source']][$f['conditionField']] ?? '');
                                    $match = $f['match'] ?? 'Y';
                                    if ($condVal === (string) $match) {
                                        $pRenderFields([$f['same']], $data);
                                    } else {
                                        if (isset($f['different']['member_name'])) { $pRenderFields([$f['different']['member_name']], $data); }
                                        if (isset($f['different']['member_id'])) { $pRenderFields([$f['different']['member_id']], $data); }
                                        // ZPAMS-FIX (2026-09): added migrating Kidney Transplantation.
                                        // Kidney's "different" branch also prints the member's OWN sex
                                        // checkbox (M/F) -- a third field the original two named slots
                                        // don't cover. "extra" takes a plain list of field entries,
                                        // rendered only in the non-same branch, same as member_name/id.
                                        if (isset($f['different']['extra'])) { $pRenderFields($f['different']['extra'], $data); }
                                    }
                                    break;
                            }
                        }
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
                            // ZPAMS-FIX (2026-09): Tetralogy of Fallot (16) and Ventricular Septal
                            // Defect (17, 18) are mapped entirely through the JSON field-map engine
                            // from the start (no pre-existing hardcoded block to migrate) -- their
                            // Case No./HCP/Address positions differ enough from every other template
                            // (measured directly against each PDF) that reusing the shared defaults
                            // below would print this header data on top of the label text instead of
                            // beside it. Suppressed here; each illness's own JSON "text"/"facility_name"
                            // entries cover all three fields instead.
                            $isTOF = in_array((int) @$getPreAuthData['patientinfo']['preauth_type'], [16, 17, 18], true);
                            if (!$isTOF) {
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
                            }

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

                            // ZPAMS-FIX (2026-09): Cervical Cancer (preauth_type 4) field mapping
                            // driven by the config engine + assets/zben-forms/field-maps/preauth_04.json.
                            if ((int) @$getPreAuthData['patientinfo']['preauth_type'] == 4 && $fieldMap) {
                                $pRenderFields($fieldMap['pages']['1'], $getPreAuthData);

                                // FIGO staging's date needs the SAME row Y as whichever stage q_2_1
                                // selected -- a lookup tied to another field's value, not expressible
                                // as a flat JSON field entry, so it stays as a small coupled snippet.
                                $cl = @$getPreAuthData['checklist'];
                                $figoY = [1=>197,2=>202,3=>207,4=>211.5,5=>216.5,6=>221.5,7=>226.5,8=>231.5,9=>236.5];
                                $stageCode = (int) @$cl['q_2_1'];
                                if (isset($figoY[$stageCode]) && !empty(@$cl['q_2_1_date'])) {
                                    $ppdf->SetFont('Helvetica','',9);
                                    $ppdf->SetXY(140, $figoY[$stageCode]);
                                    $ppdf->Write(0, date('m/d/Y', strtotime($cl['q_2_1_date'])));
                                }
                            }

                            // ZPAMS-FIX (2026-09): CABG (preauth_type 3) field mapping driven by the
                            // config engine + assets/zben-forms/field-maps/preauth_03.json.
                            if ((int) @$getPreAuthData['patientinfo']['preauth_type'] == 3 && $fieldMap) {
                                $pRenderFields($fieldMap['pages']['1'], $getPreAuthData);
                            }

                            // ZPAMS-FIX (2026-09): Tetralogy of Fallot (preauth_type 16) field mapping
                            // driven by the config engine + assets/zben-forms/field-maps/preauth_16.json
                            // (all 3 pages). First illness mapped without ever having a hardcoded
                            // block -- built and verified directly against PreAuth_16.pdf's own
                            // measured coordinates.
                            if ((int) @$getPreAuthData['patientinfo']['preauth_type'] == 16 && $fieldMap) {
                                $pRenderFields($fieldMap['pages']['1'], $getPreAuthData);
                            }

                            // ZPAMS-FIX (2026-09): Ventricular Septal Defect (preauth_type 17 Surgery,
                            // 18 Closure w/ Associated Special Conditions) field mapping driven by the
                            // config engine + assets/zben-forms/field-maps/preauth_17.json /
                            // preauth_18.json (all 3 pages each). Same "no hardcoded block" approach
                            // as TOF; page 1 holds header+patient/member+qualifications+diagnostics
                            // together for this template family, unlike TOF's page1/2 split.
                            if (in_array((int) @$getPreAuthData['patientinfo']['preauth_type'], [17, 18], true) && $fieldMap) {
                                $pRenderFields($fieldMap['pages']['1'], $getPreAuthData);
                            }
                        }

                        if ($pageNo == 2 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 3 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['2'], $getPreAuthData);
                        }

                        if ($pageNo == 3 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 3 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['3'], $getPreAuthData);
                        }

                        if ($pageNo == 2 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 16 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['2'], $getPreAuthData);
                        }

                        if ($pageNo == 3 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 16 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['3'], $getPreAuthData);
                        }

                        if ($pageNo == 2 && in_array((int) @$getPreAuthData['patientinfo']['preauth_type'], [17, 18], true) && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['2'], $getPreAuthData);
                        }

                        if ($pageNo == 3 && in_array((int) @$getPreAuthData['patientinfo']['preauth_type'], [17, 18], true) && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['3'], $getPreAuthData);
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
                        // ZPAMS-FIX (2026-09): Breast Cancer (preauth_type 2) field mapping driven by
                        // the config engine + assets/zben-forms/field-maps/preauth_02.json (all 4
                        // pages, including the Cytotoxic Chemotherapy Protocol sub-choice).
                        if ($pageNo == 1 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 2 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['1'], $getPreAuthData);
                        }
                        if ($pageNo == 2 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 2 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['2'], $getPreAuthData);
                        }
                        if ($pageNo == 3 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 2 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['3'], $getPreAuthData);
                        }
                        if ($pageNo == 4 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 2 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['4'], $getPreAuthData);
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
                        // ZPAMS-FIX (2026-09): Kidney Transplantation (preauth_type 6) field mapping
                        // driven by the config engine + assets/zben-forms/field-maps/preauth_06.json
                        // (all 6 pages).
                        if ($pageNo == 1 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['1'], $getPreAuthData);
                        }
                        if ($pageNo == 2 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['2'], $getPreAuthData);
                        }
                        if ($pageNo == 3 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['3'], $getPreAuthData);
                        }
                        if ($pageNo == 4 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['4'], $getPreAuthData);
                        }
                        if ($pageNo == 5 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['5'], $getPreAuthData);
                        }
                        if ($pageNo == 6 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 6 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['6'], $getPreAuthData);
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
                            //
                            // ZPAMS-FIX (2026-09): re-measured against the checkbox glyphs' own bbox
                            // (x=33.02-36.57) via word-level PDF text extraction -- x=31 sat left of
                            // both boxes entirely. The purpose text at y=95.5 also sat low enough that
                            // the printed underline cut through the middle of the letters instead of
                            // sitting below them; raised to y=92.6 so it rests on the line.
                            if (@$rq['copayment'] == 'wocp') { $ppdf->SetXY(33.3, 90.0); $ppdf->Write(0,'X'); }
                            elseif (@$rq['copayment'] == 'wcp') {
                                $ppdf->SetXY(33.3, 94.6); $ppdf->Write(0,'X');
                                $ppdf->SetXY(100, 92.6); $ppdf->Write(0, $pDecrypt(@$rq['with_copayment_purpose']));
                            }

                            // ZPAMS-FIX (2026-09): re-measured against the "Certified correct by:"
                            // cell's own vertical divider (x=106.21-106.38, found via vector drawing
                            // extraction) -- the previous x=10/w=90 and x=113/w=92 didn't match either
                            // column's actual bounds (left cell: x=31.16-106.21; right cell:
                            // x=106.38-186.94), so names centered against the wrong box and drifted.
                            $pCentered($pDecrypt(@$rq['crtby_attendingphysician']), 31.16, 109.3, 75, 6);
                            $pCentered($pDecrypt(@$rq['crtby_medicaldirectory']), 106.38, 109.3, 80.5, 6);
                            // ZPAMS-FIX (2026-09): this row's PhilHealth Accreditation No. boxes are 14
                            // uniform slots (pitch measured via vector drawing extraction at ~3.85mm)
                            // for the same "XXXX-XXXXXXX-X" format used on page 1 -- pBoxedNumber()'s
                            // digit-only stripping doesn't apply since the dash characters need their
                            // own boxes too, so this writes the raw value (including dashes) one
                            // character per box directly instead, same technique as page 1.
                            $ppdf->SetFont('Helvetica','',8);
                            foreach (str_split((string) $pDecrypt(@$rq['crtby_attendingphysician_accreno'])) as $i => $ch) {
                                $ppdf->SetXY(51.39 + ($i * 3.85), 129.5); $ppdf->Write(0, $ch);
                            }
                            foreach (str_split((string) $pDecrypt(@$rq['crtby_medicaldirectory_accreno'])) as $i => $ch) {
                                $ppdf->SetXY(133.10 + ($i * 3.85), 129.5); $ppdf->Write(0, $ch);
                            }
                            $pCentered($pDecrypt(@$rq['crtby_patient']), 115, 142.5, 90, 9);
                        }

                        // ZPAMS-FIX (2026-09): Cervical Cancer page 3 migrated to $pRenderFields +
                        // preauth_04.json, same verification/rollback approach as page 1 above.
                        if ($pageNo == 3 && (int) @$getPreAuthData['patientinfo']['preauth_type'] == 4 && $fieldMap) {
                            $pRenderFields($fieldMap['pages']['3'], $getPreAuthData);
                        }
                    }

                    $pBlobContent = $ppdf->Output($fileNameTitle,'S');
                    $ppdf->Close();

                    $fla = [
                        'FileName' => $fileNameTitle,
                        'FileType' => 'application/pdf',
                        'FileData' => base64_encode($pBlobContent),
                        // ZPAMS-FIX (2026-09): ObjectFileViewer() (assets/js/coreutilities.js) hides
                        // the embedded PDF viewer's native toolbar (#toolbar=0) unless this flag is
                        // explicitly set -- that toolbar is what carries the browser's print and
                        // download/save controls. Without it, the "Print/Download Pre-Authorization
                        // Checklist & Request" button opened a PDF with no way to actually print or
                        // download it. Matches the working pattern already used for attachment
                        // previews in claims/form/preauthforms/preauth_submit.php.
                        'ToolBar'  => 'true',
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
