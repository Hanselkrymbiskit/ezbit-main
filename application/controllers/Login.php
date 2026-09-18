<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use IconCaptcha\IconCaptcha;

#[AllowDynamicProperties]

class Login extends MY_Controller {

	function __construct()
	{
		parent::__construct();
        $this->data['pagetype'] = "Login";
        if( $this->tank_auth->is_logged_in() )
        {
            redirect('home');
        }

        //$this->load->model('api/m_api');
	}

    function index()
    {
        if( $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            header("HTTP/1.1 403 Unauthorized");
            exit;
        }

        if( count(@$_GET) > 0 )
        {
            header("HTTP/1.1 403 Unauthorized");
            exit;
        }

        if( $this->tank_auth->is_logged_in() )
        {
            redirect('home');
        }
        elseif ($this->tank_auth->is_logged_in(FALSE))
        {                       // logged in, not activated
            redirect('');
        } 
        else 
        {
            if( ($this->system_settings['IconCaptchaModule'] ?? 0) == 1 && ($this->system_settings['IconCaptchaatLogin'] ?? 0)  == 1  )
            {
                $this->data['loadcss'] = array(
                    'css/fabianwennink/iconcaptcha/iconcaptcha.min',
                );

                $this->data['loadjsmain'] = array(
                    'js/fabianwennink/iconcaptcha/iconcaptcha.min',
                );
            }
           
            $this->load->template('templates/login/index',  $this->data,'',false);  
        }
    }
   
    function authenticate()
    {
         // log_message("error",$_REQUEST);
        if( $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']))
            {
               $return = array('Status' => 0, 'Message' => 'Permission Denied!');
               goto exitFunction;
            }
            
            unset($_POST['UToken']);
        }
        else
        {
            redirect('login');
        }

 
        $return = array(
          'Status'      =>  0,
          'Message'     =>  "Incorrect Username/Email or Password!"
        );

        try
        {
            $verifyRecaptchaResult = true; 

            if($this->system_settings['ReCaptchaModule'] == 1 && $this->system_settings['ReCaptchaatLogin']  == 1 )
            {
                if($_SERVER['HTTP_HOST'] <> 'localhost' && $_SERVER['HTTP_HOST'] <> '127.0.0.1' && !isset($_POST['authcode']))
                {
                    // log_message("error","ReCaptcha Validation Line");
                    $verifyRecaptchaResult = $this->myutilities->google_recaptcha_validate($_POST['recapctha_response']);
                }
            }

            if( ($this->system_settings['IconCaptchaModule'] ?? 0) == 1 && ($this->system_settings['IconCaptchaatLogin'] ?? 0)  == 1  )
            {
                if($_SERVER['HTTP_HOST'] <> 'localhost' && $_SERVER['HTTP_HOST'] <> '127.0.0.1' && !isset($_POST['authcode']))
                {
                    // log_message("error","IconCaptcha Validation Line");

                    $options = $this->iconcaptchaconfig();
                    $iconcaptcha = new IconCaptcha($options);

                    // log_message("error",$_POST);
                    
                    $icvalidation = $iconcaptcha->validate($_POST);



                    $verifyRecaptchaResult = $icvalidation->success();
                }
            }

            // log_message("error",$verifyRecaptchaResult);

            if($verifyRecaptchaResult)
            {
                $login = '';
                $LoginAttempt_Expire = $this->system_settings['LoginAttempt_Duration']; 

                if ( $this->config->item('login_count_attempts', 'tank_auth') && ( $login = $_POST['login'] ) ) 
                {
                    $login = $this->security->xss_clean($login);
                }

                if ( $this->tank_auth->is_max_login_attempts_exceeded($login) ) 
                {
                    $return['Message'] = 'You have reached the maximum number of signin attempt, please try to again after '.$LoginAttempt_Expire.' Hours.';        
                    goto exitFunction;
                }

                // Step 1 : Check if form value passed the standard form validation
                if ( $this->form_validation->run() ) 
                {   
                    // Step 2 : Try to Login the Credential
                    $submitUser = base64_decode($this->form_validation->set_value('login'));
                    $submitPass = base64_decode($this->form_validation->set_value('password'));

                    // Check for TwoFactor Credential
                    $preLogin = $this->tank_auth->login( $submitUser , $submitPass,'',true,true,true);
                    if($preLogin)
                    {
                        if( strpos($submitUser,'@') > -1 )
                        {
                            $preuser = $this->users->get_user_by_email($submitUser);
                        }
                        else
                        {
                            $preuser = $this->users->get_user_by_username($submitUser);
                        }
  
                        $preuserid = $preuser->id;
                        $preuserprofile = $this->sqlhelper->local->select("user_profiles")->where("user_id='".$preuserid."'")->row();
                        $preuserprofile = $preuserprofile['Data'];
                        $twofactorsecret = is_array($preuserprofile) ? $preuserprofile['TwoFactorKey'] : '';
                    }
                    // log_message("error",$_POST);
                    // log_message("error",@$preuserprofile);
                    if(@$twofactorsecret == '' || (@$preuserprofile['user_classification'] == '' || is_null(@$preuserprofile['user_classification']) ) )
                    {
                        // Let it passthrough to configure TwoFactor
                        $SuccessLogin = $this->tank_auth->login( $submitUser , $submitPass );
                    }
                    else
                    {
                        if(isset($_POST['authcode']) && @$_POST['authcode'] <> '')
                        {
                            // Run TOTP Verify
                            
                            $totpverify = $this->m_general->verifyTOTP($twofactorsecret,base64_decode($_POST['authcode']));
                            if($totpverify)
                            {
                                $SuccessLogin = $this->tank_auth->login( $submitUser , $submitPass );
                            }
                            else
                            {
                                $return = array(
                                  'Status'          =>  0,
                                  'Message'         =>  'Failed to Login, Invalid Authentication Code!',
                                  'RequireTwoFactorVerify' => 1
                                );

                                goto exitFunction;
                            }
                        }
                        else
                        {
                            $return = array(
                              'Status'          =>  1,
                              'Message'         =>  'Two-Factor Verification Required!',
                              'RequireTwoFactorVerify' => 1
                            );

                            goto exitFunction;
                        }
                    }
                    

                    // $SuccessLogin = $this->tank_auth->login( $submitUser , $submitPass,'',true,true,true);

                    if( $SuccessLogin ) 
                    {
                        $return['Status'] = 1;
                        $userprofile = $this->sqlhelper->local->select("user_profiles")->where("user_id='".$this->tank_auth->get_user_id()."'")->row();
                        $return['Message'] = 'Welcome';
                        $this->session->pdfpassword = $submitPass;
                        $dAname = $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],@$userprofile['Data']['CompleteName'],'MCrypt','aes-128','ecb');
                        $dAname = ($dAname <> '') ? $dAname : $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],@$userprofile['Data']['CompleteName'],'MCrypt','aes-128','cbc');
                        $return['AccountName'] = ucwords( strtolower($dAname ) );
                        $usd = $this->sqlhelper->local->select("users")->where("id='".$this->tank_auth->get_user_id()."'")->row();
                        
                        if( isset($_SESSION['maintenanceModeMessage']) && (int) $usd['Data']['usertype'] > 3 && @$_SESSION['maintenanceModeNow'] == true)
                        {
                            $return = array(
                              'Status'      =>  0,
                              'Message'     =>  strip_tags($_SESSION['maintenanceModeMessage'])
                            );

                            $this->tank_auth->logout();

                            if($this->session->iniCounterNo)
                            {
                                $cno = $this->session->iniCounterNo;
                                unset($this->session->iniCounterNo);    
                            }
       
                            $deleteSession = $this->sqlhelper->local->delete('system_sessions')->where(" `id` ='".session_id()."'")->run();
                            goto exitFunction;
                        }

                        // Log Login History
                        $loglogin = [
                            'ip'            => @$_SERVER['REMOTE_ADDR'],
                            'userid'        => $this->tank_auth->get_user_id(),
                            'logdatetime'   => date("Y-m-d H:i:s"),
                        ];

                        $insertLogLogin = $this->sqlhelper->local->insert('users_online_log')->ex_insert($loglogin)->run();
                        $_SESSION['userloginlogdatetime'] = $loglogin['logdatetime'];
                        $_SESSION['userloginlogid'] = $insertLogLogin['Data'];
                    }
                    else
                    {
                        $emess='';
                        $errors = $this->tank_auth->get_error_message();
                        if ( array_key_exists( 'banned' , $errors )  )
                        {                           
                            $return['Message'] = 'This User Account is Banned!';
                        }
                        else if ( array_key_exists('not_activated',$errors) ) 
                        {               
                            $return['Message'] = 'Please Activate your account!';
                        } 
                        else 
                        {
                            $akeys = array_keys($errors);
                            $return['Message'] = $this->lang->line($errors[$akeys[0]]);
                        }
                    }
                }
                else
                {
                   $return['Message']= "Invalid Username/Email or Password!";
                }
            }
            else
            {
               $return['Message'] = 'System Authentication Failed!, Invalid ReCaptcha/IconCaptcha Security';
            }  
        }catch (PDOException $e) {
          log_message("error","Controller : ".__METHOD__." : Error [ ".$e->getMessage()." ]");
        } catch (Exception $e) {
          log_message("error","Controller : ".__METHOD__." : Error [ ".$e->getMessage()." ]");
        }

        exitFunction:
        $return['Message'] = base64_encode($return['Message']);
        echo json_encode($return);
    }

    function iconcaptcha()
    {
        try {
            
            // Start a session.
            // * Only required when using any 'session' driver in the configuration. See the documentation for more information.
         
            // Load the IconCaptcha options.
            $options = $this->iconcaptchaconfig();
            // log_message("error",$options);
            // Create an instance of IconCaptcha.
            $captcha = new IconCaptcha($options);

            // Handle the CORS preflight request.
            // * If you have disabled CORS in the configuration, you may remove this line.
            // $captcha->handleCors();

            // Process the request.
            $captcha->request()->process();

            // Request was not supported/recognized.
            http_response_code(400);

        } catch (Throwable $exception) {

            http_response_code(500);

            // Add your custom error logging handling here.

        }
    }

    function iconcaptchaconfig()
    {
       return [

            // Specifies a function that must return the IP address of the visitor.
            // Using Cloudflare? Ensure to return the visitor's original IP (HTTP_CF_CONNECTING_IP) and not the proxy IP.
            // For more information, see https://github.com/fabianwennink/IconCaptcha-PHP/wiki/Configuration#ipaddress
            'ipAddress' => static fn() => $_SERVER['REMOTE_ADDR'],

            // Specifies the token class to use for challenge CSRF tokens. Set to null to disable.
            // The default token class is \IconCaptcha\Token\IconCaptchaToken::class.
            // For more information, see https://github.com/fabianwennink/IconCaptcha-PHP/wiki/Token
            'token' => null,

            // 'token' => \IconCaptcha\Token\IconCaptchaToken::class,

            // Configurations for additional custom themes.
            // For more information, see https://github.com/fabianwennink/IconCaptcha-PHP/wiki/Themes
            'themes' => [
                'black' => [
                    // Specifies which icon type should be used: light or dark.
                    'iconStyle' => 'light',
                    // Specifies the RGB color of the icon separator.
                    'separatorColor' => [20, 20, 20],
                ]
            ],

            // Configuration for database storage.
            'storage' => [
                // Specifies the driver to use for data storage.
                // Default available drivers: 'session', 'mysql', 'sqlsrv', 'pgsql' and 'sqlite'.
                'driver' => 'session',
                // Specifies the connection details for database session driver.
                // Alternatively, you can use an existing PDO object for the 'connection' key.
                // For more information, see https://github.com/fabianwennink/IconCaptcha-PHP/wiki/Database-Storage
                'connection' => [
                    //'url' => 'mysql:host=127.0.0.1;port=3306;dbname=db', // You can use a DSN URL if your database requires a more complex connection.
                    'host' => '127.0.0.1',
                    'port' => 3306,
                    'database' => 'db',
                    'username' => 'root',
                    'password' => '',
                ],
                // Specifies the format used to create formatted datetime values to use in database queries.
                // Use a different format if needed for your database. See https://www.php.net/manual/en/datetime.format.php.
                'datetimeFormat' => 'Y-m-d H:i:s',
            ],

            // Configuration for the challenge generation.
            'challenge' => [
                // Specifies the maximum number of unique icons available. By default, IconCaptcha ships with 250 icons.
                'availableIcons' => 250,
                // Specifies the minimum and maximum number of icons to use in each challenge image.
                'iconAmount' => [
                    'min' => 5, // The lowest possible is 5 icons per challenge.
                    'max' => 8 // The highest possible is 8 icons per challenge.
                ],
                // Specifies whether to randomly rotate the icons in each challenge image.
                'rotate' => true,
                // Specifies whether to randomly flip the icons in each challenge image, horizontally and/or vertically.
                'flip' => [
                    'horizontally' => true,
                    'vertically' => true,
                ],
                // Specifies whether to render a border between the icons in each challenge image.
                'border' => true,
                // Specifies the generator class to use for creating challenge images. Generators for 'GD' and 'Imagick' extensions are available.
                // For more information, see https://github.com/fabianwennink/IconCaptcha-PHP/wiki/Challenge-Generator
                'generator' => \IconCaptcha\Challenge\Generators\GD::class,
            ],

            // Configuration for challenge validation.
            'validation' => [
                // Specifies the duration (in seconds) of inactivity before a challenge is invalidated. Set to 0 to disable.
                'inactivityExpiration' => 120,
                // Specifies the duration (in seconds) after a successful challenge before it's invalidated. Set to 0 to disable.
                'completionExpiration' => 300,
                // Specifies the options for challenge solving attempts.
                // For more information, see https://github.com/fabianwennink/IconCaptcha-PHP/wiki/Attempts-&-Timeouts
                'attempts' => [
                    // Specifies whether to enable the attempts and timeout feature.
                    'enabled' => true,
                    // Specifies the maximum number of attempts before the visitor will receive a timeout.
                    'amount' => 3,
                    // Specifies the time (in seconds) which the visitor has to wait after making too many incorrect attempts.
                    'timeout' => 60,
                    // Specifies the time (in seconds) after which an attempt will automatically be forgotten and removed from the attempts counter.
                    'valid' => 30,
                    // Specifies the options for storing attempts and timeout data.
                    'storage' => [
                        // Specifies the custom driver class to use for storing and retrieving attempts and timeout data.
                        // An internal driver compatible with the configured storage driver will be used when set to NULL.
                        // For more information, see https://github.com/fabianwennink/IconCaptcha-PHP/wiki/Attempts-&-Timeouts#custom-driver
                        // Required:
                        // - Your custom driver must extend the '\IconCaptcha\Attempts\Attempts' class.
                        // - Your custom driver must be compatible with the configured storage driver.
                        'driver' => null,
                        // Specifies the options passed on to the driver.
                        'options' => [
                            // Specifies the table name used by the database storage drivers to keep track of attempts and timeouts.
                            'table' => 'iconcaptcha_attempts',
                            // Specifies whether the expired attempts/timeout records should automatically be deleted from storage.
                            'purging' => true,
                        ],
                    ]
                ],
            ],

            // Configuration for the session driver.
            'session' => [
                // Specifies the custom driver class to use for managing challenge data.
                // An internal driver compatible with the configured storage driver will be used when set to NULL.
                // For more information, see https://github.com/fabianwennink/IconCaptcha-PHP/wiki/Session#custom-driver
                // Required:
                // - Your custom driver must extend the '\IconCaptcha\Session\Session' class.
                // - Your custom driver must be compatible with the configured storage driver.
                'driver' => null,
                // Specifies the options passed on to the driver.
                'options' => [
                    // Specifies the table name used by the database storage drivers to keep track of challenges.
                    'table' => 'iconcaptcha_challenges',
                    // Specifies whether the expired challenges should automatically be deleted from storage.
                    'purging' => true,
                    // Specifies the maximum amount of attempts that will be made to generate a captcha identifier before failing.
                    'identifierTries' => 100,
                ],
            ],

            // Configuration for Cross-Origin Resource Sharing (CORS).
            // For more information, see https://github.com/fabianwennink/IconCaptcha-PHP/wiki/Configuration#cors
            'cors' => [
                // Specifies whether CORS is enabled.
                'enabled' => false,
                // Specifies the list of allowed origins for CORS requests.
                // Wildcards, such as *.example.com, are supported. Use '*' to allow all origins, but be aware of the potential security implications.
                'origins' => [],
                // Specifies whether to include credentials (cookies, headers, etc.) in CORS requests.
                'credentials' => true,
                // Specifies the maximum age (in seconds) to cache CORS preflight requests.
                'cache' => 86400,
            ],

            // Configuration for hooks.
            // For more information, see https://github.com/fabianwennink/IconCaptcha-PHP/wiki/Hooks-&-Events#hooks
            'hooks' => [
                // Initialization hook, called when the challenge is requested.
                // Use case: To determine whether to serve a challenge, or complete immediately, e.g. based on IP or previously completed challenges.
                // Required: The hook must implement the 'InitHookInterface' interface.
                'init' => null,
                // Image generation hook, called after the challenge image was generated.
                // Example use case: Modify the image by applying filters or adding random noise to increase the difficulty.
                // Required: The hook must implement the 'GenerationHookInterface' interface.
                'generation' => null,
                // User image interaction hook, called after the user clicked on an icon.
                // Example use case: Perform a custom action based on whether the user made a correct or incorrect choice.
                // Required: The hook must implement the 'SelectionHookInterface' interface.
                'selection' => null,
            ],
        ];
    }
   
}