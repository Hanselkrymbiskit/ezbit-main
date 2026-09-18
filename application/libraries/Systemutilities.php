<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use Ramsey\Uuid\Uuid;
#[AllowDynamicProperties]
class Systemutilities {

    public $CI;

    function __construct($params = '')
    {
        $this->CI = & get_instance();
    }

    function stringToUuid($string) {
        // Generate a hash from the string
        $hash = md5($string);

        // Format the hash as a UUID (8-4-4-4-12)
        $uuid = sprintf(
            '%08s-%04s-%04s-%04s-%12s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            substr($hash, 12, 4),
            substr($hash, 16, 4),
            substr($hash, 20, 12)
        );

        return $uuid;
    }

    function xssCleaner($data)
    {
        if( is_array($data) )
        {
            foreach($data as $pk => $pv)
            {
                $data[$pk] = (is_array($pv)) ? $this->xssCleaner($pv) : $this->CI->security->xss_clean($pv);
            }
        }
        else
        {
            $data = $this->CI->security->xss_clean($data);
        }

        return $data;
    }


    function namemasking($input = '',$mask = '*')
    {
        $return = '';
        $tmpX = explode(" ",$input);
        $tmpY = [];
        foreach($tmpX as $xmark)
        {
          $cl = strlen($xmark);
          $sx = substr($xmark, 1,$cl - 2);
          $sy = str_pad('', $cl - 2,$mask,STR_PAD_LEFT);
          $tmpY[] = str_replace($sx, $sy, $xmark);
        }

        $return = implode(" ",$tmpY);

        return $return;
    }

    function siteMenu($menuItem,$subMenu = false,$subMenuItem = false)
    {
        $menuHTML = '<ul class="site-menu'.( ($subMenu) ? '-sub' : '').'" data-plugin="menu">';
        foreach( $menuItem as $menuRow => $menuConfig )
        {
            $hasSub = ( count($menuConfig['submenuitem']) > 0 ) ? true : false;
            $hrefLink = ( (trim($menuConfig['targetlink']) <> '') ? trim($menuConfig['targetlink']) : 'javascript:void(0)');
            $iconImage = (trim($menuConfig['iconClass']) <> '') ? '<i class="site-menu-icon '.trim($menuConfig['iconClass']).' '.( (!$subMenuItem) ? 'fa-2x mb-5' : '').'" aria-hidden="true" ></i>' : '<img  src="'.trim($menuConfig['iconImage']).'" class="h-15">';
            $SubMenuItemHTML = '';
            $SubMenuArrow = '';
            $hasSubClass = '';
            
            if( $hasSub )
            {
                $hrefLink = 'javascript:void(0)';
                $hasSubClass = 'has-sub';
                $SubMenuItemHTML = $this->siteMenu($menuConfig['submenuitem'],true,true);
            }

            if( @$menuConfig['categorymode'] == true)
            {
                $menuHTML .='
                <li class="site-menu-category">
                    <span class="ml-10">'.trim($menuConfig['menucaption']).'</span>
                </li>
                ';
            }
            else
            {
              $menuHTML .='
                <li class="site-menu-item '.@$hasSubClass.'">
                    <a class="'.((!$hasSub) ? 'animsition-link ' : '' ).' '.( ($subMenuItem) ? 'border-0' : '').' border-bottom border-white" href="'.$hrefLink.'">
                        '.( (!$subMenu) ? @$iconImage : '').'
                        <span class="site-menu-title">'.( ($subMenu) ? str_replace('site-menu-icon','mr-10 fa',@$iconImage)  : '').''.trim($menuConfig['menucaption']).'</span>
                        '.@$SubMenuArrow.'
                    </a>
                    '.@$SubMenuItemHTML.'
                </li>
                ';  
            }
            
        }


        $menuHTML .= '</ul>';

        return $menuHTML;
    }

    function randomString($length = 32)
    {
        return $this->generateRandomString($length);
    }

    function generateRandomString($length = 32) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function randomInt($length = 32) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomInt = '';
        for ($i = 0; $i < $length; $i++) {
            $randomInt .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomInt;
    }

    // Display Bytes into Description
    /* Input
        $Bytes
        $Desc = true or false [ To Return Description ]
    */
        
    function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '' , trim($num));
        $gdecimal = explode(".",$num);
        $decimalV = (isset($gdecimal[1])) ? $gdecimal[1] : '';
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words).( ($decimalV <> '' && $decimalV <> '00') ? ' & '.$decimalV.'/100' : '' );
    }


    function validateToken($Token)
    {
        $Token = $this->CI->m_api->encryptdecryptString('decrypt',$this->CI->system_settings['PasswordHashing'],base64_decode($Token),'MCrypt','aes-128','ecb');
        return ( $Token == date('Ymd') ) ? true : false;
    }

    function getInitials($string = null) {
        return array_reduce(
            explode(' ', $string),
            function ($initials, $word) {
                return sprintf('%s%s', $initials, substr($word, 0, 1));
            },
            ''
        );
    }

    function generatePVToken()
    {
        $i = array(
            'Created_By' => $this->CI->userid,
            'Created_DateTime' => date('Y-m-d H:i:s'),
            'Token' => base64_encode($this->CI->encryption->create_key(16)),
        );

        $c = $this->CI->sqlhelper->local->insert("system_passwordverification")->ex_insert($i)->run();   
        return $i['Token'];
    }

    function checkPVToken($PVToken = '')
    {
        $c = $this->CI->sqlhelper->remote1->select("system_passwordverification")->where("Created_By = ".$this->CI->userid." and Token = '".@$PVToken."' and DATE(Created_DateTime) = '".date('Y-m-d')."'")->result();   
        return ($c['Count'] > 0) ? true : false;
    }

    function getSystemSettings()
    {
        $getSettings = $this->CI->sqlhelper->remote1->select("system_settings")->result();
        $settings=array();
        foreach($getSettings['Data'] as $rows => $cols )
        {
            $settings[$cols['SettingName']] = $cols['SettingValue'];
        }
        return $settings;
    }

    

    function EmailExists($emailaddress = '')
    {   
        $return = '';
        if($emailaddress <> '')
        {
            $chkRegisterTable = $this->CI->sqlhelper->remote1->select("user_register")->where("emailaddress = '".trim($emailaddress)."'")->ex_select('','','1')->row();

            if($chkRegisterTable['Count'] > 0)
            {
                return true;
            }

            $chkUsersTable = $this->CI->sqlhelper->remote1->select("users")->where("email = '".trim($emailaddress)."'")->ex_select('','','1')->row();
            if( $chkUsersTable['Count'] > 0)
            {
                return true;
            }

            return false;
        }

        return $return;
    }

    function userProfileExists($Demographics = '')
    {
        if( !is_array($Demographics) )
        {
            $toCheck = array('Lastname' => 'required','Firstname' => 'required','Middlename' => '','Suffixname' => '','DateofBirth' => 'required','Sex' => 'required');
            $DemographicsInfo = array();
            $DemoError = 0;
            foreach($toCheck as $FieldID => $FieldValue)
            {
              if( array_key_exists($FieldID, $Demographics))
              {
                if($FieldValue == 'required' && (trim($Demographics[$FieldID]) == "" || is_null($Demographics[$FieldID]) ) )
                {
                  $DemoError++;
                }
                $DemographicsInfo[$FieldID] = $this->CI->myutilities->dbValueFormatter($FieldID, $DemoError[$FieldID]);
              }
            }

            if($DemoError == 0)
            {
              $SuffixFilter = ($DemographicsInfo['Suffixname'] <> 'NA' && $DemographicsInfo['Suffixname'] <> '') ? " AND Suffixname = '".$DemographicsInfo['Suffixname']."'" : "";

              $chk = $this->CI->sqlhelper->remote1->select("user_profiles")->where(" LOWER(REPLACE(Lastname, ' ', '')) = '".strtolower(str_replace(" ", '', trim($DemographicsInfo['Lastname'])))."' and LOWER(REPLACE(Firstname, ' ', '')) = '".strtolower(str_replace(" ", '', trim($DemographicsInfo['Lastname'])))."' and LOWER(REPLACE(Middlename, ' ', '')) = '".strtolower(str_replace(" ", '', trim($DemographicsInfo['Lastname'])))."' ".$SuffixFilter." AND DateofBirth = '".$DemographicsInfo['DateofBirth']."' and Sex='".$DemographicsInfo['Sex']."' ")->ex_select("","","1")->result();

              if( $chk['Count'] > 0)
              {
                $user_ids = [];
                foreach( $chk['Data'] as $chkRow => $chkCol )
                {
                    $user_ids[] = $chkCol['user_id'];
                }

                return implode(",",$user_ids);
              }
            } 

        }

        return "";
    }


    function HumanSize($Bytes = '',$Desc = false,$nohtml = false)
    {
        if($Bytes <> '')
        {
            $Type= array(
                    "0" => array('Abbrev' => 'B','Description' => 'Bytes'),
                    "1" => array('Abbrev' => 'KB','Description' => 'Kilobytes'),
                    "2" => array('Abbrev' => 'MB','Description' => 'Megabytes'),
                    "3" => array('Abbrev' => 'GB','Description' => 'Gigabytes'),
                    "4" => array('Abbrev' => 'TB','Description' => 'Terabytes'),
                    "5" => array('Abbrev' => 'PB','Description' => 'Petabytes'),
                    "6" => array('Abbrev' => 'EB','Description' => 'Exabytes'),
                    "7" => array('Abbrev' => 'ZB','Description' => 'Zettabytes'),
                    "8" => array('Abbrev' => 'YB','Description' => 'Yottabytes')
                );

            $Index=0;

            while($Bytes>=1024)
            {
                $Bytes/=1024;
                $Index++;
            }

            if(!$Desc)
            {
                return round($Bytes,2); 
            }

            if($nohtml)
            {
                return round($Bytes,2).' '.$Type[$Index]['Abbrev'];
            }

            return round($Bytes,2).' <span title="'.$Type[$Index]['Description'].'">'.$Type[$Index]['Abbrev'].'</span>';

        }

        return '';  
    }

    function romanic_number($integer, $upcase = true) 
    { 
        $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
        $return = ''; 
        while($integer > 0) 
        { 
            foreach($table as $rom=>$arb) 
            { 
                if($integer >= $arb) 
                { 
                    $integer -= $arb; 
                    $return .= $rom; 
                    break; 
                } 
            } 
        } 

        return $return; 
    } 

    function array_insertBefore($input, $index, $newKey, $element) {
        if (!array_key_exists($index, $input)) {
            throw new Exception("Index not found");
        }
        $tmpArray = array();
        foreach ($input as $key => $value) {
            if ($key === $index) {
                $tmpArray[$newKey] = $element;
            }
            $tmpArray[$key] = $value;
        }
        return $input;
    }

    function array_insertAfter($input, $index, $newKey, $element) {
        if (!array_key_exists($index, $input)) {
            throw new Exception("Index not found");
        }
        $tmpArray = array();
        foreach ($input as $key => $value) {
            $tmpArray[$key] = $value;
            if ($key === $index) {
                $tmpArray[$newKey] = $element;
            }
        }
        return $tmpArray;
    }


    function is_base64($str)
    {
        if(is_null($str))
        {
            return false;
        }

        return  base64_decode($str, true) !== false;

    }
    function is_base64_encoded($data)
    {
        // if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
        //    return TRUE;
        // } else {
        //    return FALSE;
        // }

        return $this->is_base64($data);
    }

    function escape_string($input)
    {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        else
        {
            $input = addslashes($input);
        }
        
        return $input;
    }

    function getDirContents($dir, &$results = array()){
        $files = scandir($dir);
        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $pathinfo = pathinfo($path);
                $results[] = [
                    'filename'=>$value,
                    'path'=>$path,
                    'size'=>filesize($path),
                    'sizemb'=>$this->HumanSize(filesize($path),true,true),
                    'extension' => $pathinfo['extension']
                ];
            } else if($value != "." && $value != "..") {
                $this->getDirContents($path, $results);
                // $results[] = [
                //     'filename'=>$value,
                //     'path'=>$path,
                //     'size'=>filesize($path),
                //     'sizemb'=>$this->HumanSize(filesize($path),true,true)
                // ];
            }
        }
        return $results;
    }

    /**
    * Get the directory size
    * @param directory $directory
    * @return integer
    */
   
    function dirSize($directory) {
        $size = 0;
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
            $size+=$file->getSize();
        }
        return $size;
    } 

    function dbValueFormatter($key ='', $val='')
    {
        $newVal = $val;
        
        if($val <> "'")
        {
            if( strpos(strtolower($key),'updated') > -1 )
            {
                return $newVal;
            }

            if($val <> '')
            {
                $dTime = '';
                if( strpos(strtolower($key),"date") > -1 )
                {
                    if( strpos(strtolower($key),"time") > -1 )
                    {
                        $dTime = ' H:i:s';
                    }

                    $newVal = date("Y-m-d".$dTime,strtotime($val));
                }
                else
                {
                    if( strpos(strtolower($key),"time") > -1 )
                    {
                        $newVal = date("H:i:s",strtotime($val));
                    }
                } 
            }  
        }
       
        return $newVal;
    }

    function formatValueDateTime($key ='', $val='')
    {
        $val = (is_null($val) || $val == '' ) ? '' : trim($val);
        $newvaldisplay = $val;
        if( !is_null(@$val) )
        {
            $key=strtolower($key);
            if(strpos($key,"date") > -1)
            {
                if(strpos($key,"time") > -1)
                {
                    $newvaldisplay = ($val <> "") ? date("m/d/Y g:i:s A",strtotime($val)) : "";
                }
                else
                {
                    $newvaldisplay = ($val <> "") ? date("m/d/Y",strtotime($val)) : "";
                }       
            }

            if(strpos($key,"time") > -1)
            {
                if(strpos($key,"date") > -1)
                {
                    $newvaldisplay = ($val <> "") ? date("m/d/Y g:i:s A",strtotime($val)) : "";
                }
                else
                {
                    $newvaldisplay = ($val <> "") ? date("g:i:s A",strtotime($val)) : "";
                }       
            }
        }
        

        return $newvaldisplay;
    }

    function google_recaptcha_validate($response = '')
    {
        $params = array();
        $params['secret'] = $this->CI->system_settings['ReCaptcha_PrivateKey']; // Secret key
        $params['response'] = urlencode($response); 
        $params['remoteip'] = $_SERVER['REMOTE_ADDR'];
        
        try
        {
            $params_string = http_build_query($params);
            $requestURL = 'https://www.google.com/recaptcha/api/siteverify';

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded", //\r\n
                    'method'  => 'POST',
                    'content' => $params_string
                ),
                'ssl'=>array(
                    //'cafile'            => 'D:\WebServer/Apache24/conf/domain.pem',
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                ),
            );
            
            $context  = stream_context_create($options);

            try{
                $result = file_get_contents($requestURL, false, $context);
            }
            catch(Exception $caller )
            {
                log_message("error", $caller->getMessage());
                $result = FALSE;
            }

            if ($result === FALSE) { return false; }

            $result = @json_decode($result, true);
        }
        catch(Exception $e)
        {
            log_message("error", $e->getMessage());
            return false;
        }

        return ( isset($result["success"]) && $result["success"] && $result['score'] > 0.4 ) ? true : false;
    }
    
    function resendactivationlink($userid,$userDetails = '')
    {
        // Generate New Password Keys
        $return = [];
        $return['Status'] = 0;
        $return['Message'] = "Resend Activation Link Failed!";

        try
        {
            $registrationInfo =$this->getRegistrationInfo($userid);
            $profileInfo =$this->getUserProfile($userid);

            // ZPAMS-FIX (2026-09): getRegistrationInfo()/getUserProfile() deliberately return
            // "" (not an array) when $userid doesn't resolve to a row -- indexing that string
            // as an array is a fatal TypeError on PHP 8. Guard here so a bad/missing $userid
            // fails cleanly through $return above instead of a 500.
            if ( ! is_array($registrationInfo) || ! is_array($profileInfo))
            {
                $return['Message'] = "Resend Activation Link Failed! Invalid or missing user.";
                return $return;
            }

            $registrationID = $registrationInfo['registrationID'];
            $aDateTime = date("Y-m-d H:i:s");

            $registrationInfo['Lastname'] = $this->CI->m_api->encryptdecryptString('decrypt',$this->CI->system_settings['PasswordHashing'],$registrationInfo['Lastname'],'MCrypt','aes-128','ecb');
            $registrationInfo['Firstname'] = $this->CI->m_api->encryptdecryptString('decrypt',$this->CI->system_settings['PasswordHashing'],$registrationInfo['Firstname'],'MCrypt','aes-128','ecb');
            $registrationInfo['Middlename'] = $this->CI->m_api->encryptdecryptString('decrypt',$this->CI->system_settings['PasswordHashing'],$registrationInfo['Middlename'],'MCrypt','aes-128','ecb');

            $RegName = ucwords(strtolower($registrationInfo['Lastname'].( ($registrationInfo['Suffixname'] <> 'NA' && $registrationInfo['Suffixname'] <> '') ? ' '.$registrationInfo['Suffixname'] : '' ).', '.$registrationInfo['Firstname'].' '.substr($registrationInfo['Middlename'],0,1).'.'));
            
            $activationlink = $this->stringToUuid($aDateTime.'|'.$registrationID);
            $uuidV5 = Uuid::uuid5($activationlink, $registrationID);
            $activationlink = $uuidV5->toString();

            $activationlinkurl = site_url('activate/index/'.$activationlink);

            $activationinfo = array(
                'activationlink' => $activationlink,
                'activationlinkdatetime' => $aDateTime
            );

            $UpdateRegistrationResult = $this->CI->sqlhelper->local->update("user_register")->ex_update($activationinfo)->where("registrationID ='".$registrationID."'")->run();
 
$emailMessage = 'Your Account Registration to '.$this->CI->system_settings['ApplicationAbbre'].' : '.$this->CI->system_settings['ApplicationName'].' has been Approved by the Administrator.'.PHP_EOL;
$emailMessage .= 'Please activate you account by clicking/navigate to the link provided below.'.PHP_EOL;
$emailMessage .= '<hr><p>Activation Link :</p>'.PHP_EOL;
$emailMessage .= '<p><a href="'.$activationlinkurl.'" target="_blank">'.$activationlinkurl.'</a></p>'.PHP_EOL;
$emailMessage .= '<hr><p>Please be reminded that you need to activate your account within 24 hours after receiving this message.</p>'.PHP_EOL;

            $return['Status']   = 1;
            $return['Message']  = 'Email Notification successfully sent to the registered email address containing the account activation link.';

            // Send Email Notification
            $mail_message = array(
                  'header' => 'Hi '.$RegName,
                  'footer' => 'Thank You.',
                  'body' => $emailMessage
                );

            $param = array(
              'from'    => $this->CI->config->item('email_sender'),
              'to'      => $profileInfo['emailaddress'],
              'subject' => $this->CI->system_settings['ApplicationAbbre'].' - Account Activation',
              'message' => $mail_message,
              'cc'      => '',
              'bcc'     => ''
            );

            // Load Send Mail Model for Sending Email
            // $this->load->model('sendmail/sendmail'); 
            $this->CI->daemon->execute_background('sendmail/sendmail','send_mail',$param);
        }
        catch(Throwable $e)
        {
            // ZPAMS-FIX (2026-09): was catch(Exception $e), which does not catch a PHP
            // Error/TypeError (e.g. the offset-on-string fatal this function used to throw
            // before the is_array() guard above) -- widened to Throwable so a future failure
            // here is logged instead of surfacing as an uncaught 500.
            log_message("error",$e->getMessage());
        }

        return $return;
    }

    function resetpassword($userid)
    {
        // Generate New Password Keys
        $return = [];
        $return['Status'] = 0;
        $return['Message'] = "Password Reset Failed!";

        $genNewPassword = $this->generatePassword(8);
        $password_key = $this->genpasswordhash($genNewPassword);
        $password_key_request = date('Y-m-d H:i:s');

        $updateUsersTable = array(
          'new_password_key'        => $password_key,
          'new_password_requested'  => $password_key_request,
          'modified_by'             => $userid,
          'modified_datetime'       => $password_key_request,
        );

        try
        {
          if( @$userid == '' )
          {
            return $return;
          }

          $userDetails = $this->getUserDetails($userid);

          $updateUsers = $this->CI->sqlhelper->local->update("users")->ex_update($updateUsersTable)->where("id = ".$userid)->run();
          if($updateUsers['ErrorCode'] == '')
          {
            $return['Status'] = 1;
            $return['Message'] = 'Account Password Successfully Reset!, please check you email address for password reset link';

            $codedlink = base64_encode(base64_encode($password_key));
            $passwordresetlink = site_url('resetpassword/index/'.$codedlink);

            $emailMessage = 'Your Account Password is successfully reset, You may now create your new account password by clicking the provided linke below.<br>';

            $emailMessage .= '<hr>Password Reset Link<br><a href="'.$passwordresetlink.'" target="">'.$passwordresetlink.'</a><hr>Please be reminded that password reset link will expired within 24Hrs.';

            $mail_message = array(
                  'header' => 'Hi '.ucwords(strtolower($this->CI->m_api->encryptdecryptString('decrypt',$this->CI->system_settings['PasswordHashing'],$userDetails['Account_Name'],'MCrypt','aes-128','ecb'))),
                  'footer' => 'Thank You.',
                  'body' => $emailMessage
                );
            
            $param = array(
              'from'    => $this->CI->config->item('email_sender'),
              'to'      => $userDetails['User_EmailAddress'],
              'subject' => $this->CI->system_settings['ApplicationAbbre'].' Account Password Reset',
              'message' => $mail_message,
              'cc'      => '',
              'bcc'     => ''
            );

            // // Send by Asynchronouse Call
            $this->CI->daemon->execute_background('sendmail/sendmail','send_mail',$param);
          }
          else
          {
            log_message("debug",$updateUsers);
          }
          
        }
        catch(Exception $e)
        {

        }

        return $return;
    }

    function genpasswordhash($stringPassword)
    {
        $hasher = new PasswordHash(
        $this->CI->config->item('phpass_hash_strength', 'tank_auth'),
        $this->CI->config->item('phpass_hash_portable', 'tank_auth'));
        return $hasher->HashPassword($stringPassword);
    }


    // Check Registered Email address
    // Input : (string) email address
    // Output : (boolean) true / false
    function chk_registered_email($email = '')
    {
        $chk = $this->CI->sqlhelper->remote1->select("user_register")->where("emailaddress = '".trim($email)."'")->row();
        return ( $chk['Count'] > 0) ? true : false;
    }

    function chk_user_email($email = '')
    {
        $chk = $this->CI->sqlhelper->remote1->select("users")->where("email = '".trim($email)."'")->row();
        return ( $chk['Count'] > 0) ? true : false;
    }
    // Check Health Facility Code if Exists in Registered Table [registered_user]
    // Input : (string) hfhudcode
    // Output : (boolean) true/false
    function chk_healthfacility($healthfacilitycode = '')
    {
        $chk = $this->CI->sqlhelper->remote1->select("user_register")->where("HealthFacilityCode = '".$healthfacilitycode."'")->row();
        return ( $chk['Count'] > 0) ? true : false;
    }

    // Get Health Facility Name 
    // Input : (string) hfhudcode
    // Output : (string) 
    function getFacilityName($hfhudcode = '')
    {
        $return = '';
        $chk1 = $this->CI->sqlhelper->remote1->select("information_schema.tables")->where("table_schema = '".$this->CI->defaultDB->database."' and table_name = 'ref_facilities'")->ex_select("","","1")->row();
        if( $chk1['Count'] > 0 )
        {
            $getFName = $this->CI->sqlhelper->remote1->select('ref_facilities')->where("hfhudcode = '".$hfhudcode."'")->ex_select('','','1')->row();
            $return = ($getFName['Count'] > 0) ? $getFName['Data']['hfhudname'] : "";
        }
        return $return;
    } 

    // Get Usertype Description
    // Input : (int) usertype
    // Output : (string) description (if exists)
    function get_usertype_desc($type = '')
    {
        $desc = '';
        if(is_numeric($type))
        {
            $getDescription = $this->CI->sqlhelper->remote1->select("usertype")->where("ID = ".$type)->ex_select('','','1')->row();
            $desc = ($getUserDesc['Count'] > 0) ? $getUserDesc['Data']['Name'] : "";
        }
        return $desc;
    }

    function isDate($value) 
    {
        if (!$value) {
            return false;
        }

        try {
            if(trim($value) == "00/00/0000" || trim($value) == "-0001-11-30")
            {
                return false;   
            }
            
            new \DateTime($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    function is_Date($str){ 
        
        $str = str_replace('/', '-', $str);     
        $stamp = strtotime($str);
        if (is_numeric($stamp)){  
            
            $month = date( 'm', $stamp ); 
            $day   = date( 'd', $stamp ); 
            $year  = date( 'Y', $stamp ); 
            
            return checkdate($month, $day, $year); 
                
        }  
        return false; 
    }

    function array_insert(&$array, $position, $insert)
    {
        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos   = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }
    
    /**
     * @param $startPoint
     * @param null $endPoint
     * @return mixed
     */
    function getDuration($startPoint, $endPoint = null)
    {
        if (!$endPoint) {
            $endPoint = time();
        }

        $secs = $endPoint - $startPoint;

        $bit = array(
            ' year' => $secs / 31556926 % 12,
            ' month' => $secs / 2592000 % 12,
            ' day' => $secs / 86400 % 7,
            ' hour' => $secs / 3600 % 24,
            ' minute' => $secs / 60 % 60,
            ' second' => $secs % 60
        );

        $ret = array();
        
        foreach ($bit as $k => $v) {
            if ($v > 1) $ret[] = $v . $k . 's';
            if ($v == 1) $ret[] = $v . $k;
        }

        return implode(" ",$ret);
    }


    // Generate PHPMaker Encrypted String
    // Input : (string) to encrypt
    // Output : (string) encrypted
    function phpmaker_encrypt_pass($input = '')
    {
        $salt = '';
        return ($input <> '') ? ((strval($salt) <> "") ? md5($input . $salt) . ":" . $salt : md5($input) ) : "";
    }
    
    function validateDateTime($date, $format)
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
        // date_default_timezone_set('UTC');
        // $d = DateTime::createFromFormat($format, $date);
        // if($d && $d->format($format) === $date) {
        //     return true;
        // } else {

        //     return false;
        // }
    }

    // Get Reference Description
    // Input : 
    //      reference ID = (int) from tbl_reference table
    //      referenve Value = (strint) value of the reference you want to get description [ ex : m which have a description of Male ]
    //      return Details = if you want to get the Reference Settings in tbl_reference_table 
    //      customField = if you want to get Description based on Other Field(Column) of the reference table defined in tbl_reference_table
    function getRef_Desc($refID = 0,$refValue = '',$returnDetails = false,$customField = '',$getrow = false)
    {
        $this->CI = & get_instance();

        if( $returnDetails == 'false')
        {
            $returnDetails = false;
        }

        if( $returnDetails == 'true')
        {
            $returnDetails = true;
        }

        $refDesc = '';
        $chkRefID = '';
        $inSession = false;
        if($refID <> '')
        {
            try{

                $refID = (int) $refID;

                if( !isset($_SESSION['ReferenceID_List']) )
                {
                    $_SESSION['ReferenceID_List'] = [];
                    $_SESSION['ReferenceID_Table'] = [];
                    $_SESSION['ReferenceID_CustomFldList'] = [];
                }

                if( isset($_SESSION['ReferenceID_Table'][$refID]) )
                {
                    $chkRefID = @$_SESSION['ReferenceID_Table'][$refID]; 
                }

                if( $chkRefID == '' || !isset($chkRefID['Count']))
                {
                    $RefIDConfig = $this->getReferenceConfig($refID);  
                    $chkRefID =  ['Count'=> ((is_array($RefIDConfig)) ? 1 : 0),'Data'=>$RefIDConfig];
                    //  $this->CI->sqlhelper->remote1->select("system_reference")->where("DataID = ".$refID."")->ex_select("","","1")->row();
                    $_SESSION['ReferenceID_Table'][$refID] = $chkRefID;
                }
                
                if( $chkRefID['Count'] > 0 )
                {
                    
                    if( !isset($_SESSION['ReferenceID_List'][$refID]) )
                    {
                        $_SESSION['ReferenceID_List'][$refID] = [];
                        $_SESSION['ReferenceID_CustomFldList'][$refID] = [];
                    }
                    
                    $reference = $chkRefID['Data'];
                    
                    if( $returnDetails )
                    {
                        $refDesc = $reference;
                    }
                    else
                    {
                        if(  $refValue <> '' && $reference['Reference_Table'] <> '' && $reference['Reference_Value_Field'] <> '' && $reference['Reference_Description_Field'] <> '' )
                        {
                            if( $customField == '' )
                            {
                               $refDesc = ( array_key_exists($refValue , $_SESSION['ReferenceID_List'][$refID]) && !$getrow ) ? @$_SESSION['ReferenceID_List'][$refID][$refValue] : ''; 
                            }
                            else
                            {
                                if( !isset($_SESSION['ReferenceID_CustomFldList'][$refID][$refValue]) )
                                {
                                    $_SESSION['ReferenceID_CustomFldList'][$refID][$refValue] = []; 
                                    $refDesc = '';
                                }
                                else
                                {
                                    $refDesc = (array_key_exists($customField , $_SESSION['ReferenceID_CustomFldList'][$refID][$refValue]) && !$getrow ) ? @$_SESSION['ReferenceID_CustomFldList'][$refID][$refValue][$customField] : ''; 
                                }
                            }

                            if(trim($refDesc) == "")
                            {
                                $Reference_Value_Field = $this->CI->sqlhelper->remote1->select("information_schema.columns")->where("table_schema = '".$this->CI->defaultDB->database."' AND table_name = '".$reference['Reference_Table']."' and column_name = '".$reference['Reference_Value_Field']."'")->row();
                                $Reference_Description_Field = $this->CI->sqlhelper->remote1->select("information_schema.columns")->where("table_schema = '".$this->CI->defaultDB->database."' AND table_name = '".$reference['Reference_Table']."' and column_name = '".$reference['Reference_Description_Field']."'")->row();
                                if($Reference_Value_Field['Count'] > 0 && $Reference_Description_Field['Count'] > 0  )
                                {
                                    $getDesc = $this->CI->sqlhelper->remote1->select($reference['Reference_Table'])->where($reference['Reference_Value_Field']." = '".$refValue."'")->ex_select("","","1")->row();
                                    if( $getDesc['Count'] > 0 )
                                    {
                                        if($getrow)
                                        {
                                            $refDesc = @$getDesc['Data'];
                                        }
                                        else
                                        {
                                            $refDesc = @$getDesc['Data'][$reference['Reference_Description_Field']];
                                        
                                            $_SESSION['ReferenceID_List'][$refID][$refValue] = trim($refDesc);
                                            if( $customField <> '')
                                            {
                                                $refDesc = @$getDesc['Data'][$customField];
                                                $_SESSION['ReferenceID_CustomFldList'][$refID][$refValue][$customField] = trim($refDesc);
                                            }
                                        }
                                      

                                    }
                                    else
                                    {
                                        $refDesc = '';
                                        $_SESSION['ReferenceID_List'][$refID][$refValue] = trim($refDesc);
                                        if( $customField <> '')
                                        {
                                            $_SESSION['ReferenceID_CustomFldList'][$refID][$refValue][$customField] = trim($refDesc);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            catch(Exeption $e)
            {
                log_message("error","Error in Getting Reference Description : ".json_encode(func_get_args()));
            }        
        }

        return $refDesc;
    }

    // Get Registration Information
    // Input : (string) Registration ID/No
    // Output : (array) Registration Infor
    function getRegistrationInfo($userid = '')
    {
        $RegistrationInfo = '';
        $getInfo = $this->CI->sqlhelper->remote1->select("user_register")->where("user_id = ".$userid)->row();
        if($getInfo['Count'] > 0)
        {
            $RegistrationInfo = $getInfo['Data'];            
        }
        return $RegistrationInfo;
    }

    function isUserNameExists($username = '')
    {
        if($username <> '')
        {
            $chkTable = [
                'users'         => 'username',
                'user_register' => 'username'
            ];

            foreach( $chkTable as $uTable => $uField)
            {
                $check = $this->CI->sqlhelper->remote1->select($uTable)->where($uField." = '".$username."'")->count();
                if( $check > 0)
                {
                    return TRUE;
                }
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
            $get = $this->sqlhelper->local->select("password_storage","Password_Encrypted")->where("Password_Decrypted = '".$password."'")->ex_select('','','1');
            if($get->count() > 0)
            {
                $get = $get->row();
                $epass = $get['Data']['Password_Encrypted'];
            }
        }

        return $epass;
    }


    // Generate User Name 
    // Input : None
    // Output : (string) Generated User Name
    // function generateUserName()
    // {
    //     $WSTag = $this->CI->config->config['WSTag'];
    //     $username = '';
    //     $queryLastUserName = $this->CI->sqlhelper->local->select("users")->where("username like '".$WSTag.date('Y')."%' ")->ex_select("","username desc","1")->row();
    //     if($queryLastUserName['Count'] > 0)
    //     {
    //         $username = $queryLastUserName['Data']['username'];
    //         $getUsername = str_replace($WSTag, "", $username);
    //         $getUsername = (int) $getUsername;
    //         $username = $WSTag.str_pad(($getUsername+1),5,'0',STR_PAD_LEFT);
    //     }
    //     else
    //     {
    //         $username = $WSTag.date('Y').'00001';
    //     }
    //     return $username;
    // }

    function parseXml_Response_Result($result)
    {
        $xml = "";

        if( count($result) > 0)
        {
            foreach( $result as $key=>$value )
            {
                if(is_array($value)) 
                {
                    
                    if(!is_numeric($key))
                    {
                        $xml .= "<" . str_replace(" ","",trim($key)) .">";
                        $xml .= $this->parseXml_Response_Result($value);
                        $xml .= "</" . str_replace(" ","",trim($key)) .">";
                    }
                    else
                    {
                        $xml .= "<num_" . str_replace(" ","",trim($key)) .">";
                        $xml .= $this->parseXml_Response_Result($value);
                        $xml .= "</num_" . str_replace(" ","",trim($key)) .">";
                    }
                    
                }
                else
                {
                    if(!is_numeric($key))
                    {
                        $xml .= "<" . str_replace(" ","",trim($key)) .">";
                        $xml .= $value;
                        $xml .= "</" . str_replace(" ","",trim($key)) .">";
                    }
                    else
                    {
                        $xml .= "<num_" . str_replace(" ","",trim($key)) .">";
                        $xml .= $value;
                        $xml .= "</num_" . str_replace(" ","",trim($key)) .">";
                    }
                    
                    
                }
            }
        } 
        else
        {
            $xml .= "";
        }
        
        return $xml;
    }

    function dataxml_to_array($xml) 
    {        
        $deXml = simplexml_load_string($xml);
        $deJson = json_encode($deXml);
        $xml_array = json_decode($deJson,TRUE);
        if(count($xml_array) == 1)
        {
            $xml_array = $xml_array[0];
        }
        
        $xml_array = array($this->CI->config->item('WSTag')=>$xml_array);
        
        return $xml_array;
    }

    function aes256_cbc_encrypt($key = '', $data = '')
    {
      $iv = $key;

      if($data == '' || $key == '' ){ return ''; }

      if(32 !== strlen($key)) $key = hash('SHA256', $key, true);
      if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);

      if(is_array($data))
      {
        $data = json_encode($data);
      }

      $padding = 16 - (strlen($data) % 16);
      $data .= str_repeat(chr($padding), $padding);
      return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
    }

    function aes256_cbc_decrypt($key = '', $data = '')
    {
       $iv = $key;

      if($data == '' || $key == ''){ return ''; }

      if(32 !== strlen($key)) $key = hash('SHA256', $key, true);
      if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
      $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
      $padding = ord($data[strlen($data) - 1]);
      return substr($data, 0, -$padding);
    }

    function get_value_format_desc($Format = '')
    {
        $return = "none";
        switch($Format)
        {
            case "date":
                    $return = "Y-m-d";
                break;

            case "datetime":
                    $return = "Y-m-d H:i:s [ Time format : 24Hours ]";
                break;

            case "time":
                    $return = "H:i:s [ Time format : 24Hours ]";
                break;

            case "int":
                    $return = "numeric";
                break;

            case "bigint":
                    $return = "numeric";
                break;

            case "decimal":
                    $return = "numeric with decimal place";
                break;
        }
        return $return;
    }

    // Convert Array to XML Formatted String
    function array_to_xmlformat_string($array,$XMLHeader = false)
    {
        $doc = new DomDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;

        // create root node
        $root = $doc->createElement('DOH');
        $root = $doc->appendChild($root);
        
        $signed_values = $array;
        
        // process one row at a time
        if( count($array) > 1)
        {
            $this->array_to_xml( $signed_values, $doc, $root );
        }
        else  if( count($array) == 1)
        {
            $value = $doc->createTextNode($array[0]);
            $value = $root->appendChild($value);       
        }

        // get completed xml document
        if($XMLHeader)
        {
            $xml_string = $doc->saveXML() ;
        }
        else
        {
            $xml_string = $doc->saveXML($root) ;
        }
        
        return $xml_string;
    }

    function array_to_xml( $data, &$xml_data, &$child_data ) {
        foreach ($data as $key => $val)
        {
            if( is_numeric($key) ){
                $key = 'num_'.$key; //dealing with <0/>..<n/> issues
            }
   
            if( is_array($val) ) 
            {
                // // Param Key
                $occ = $xml_data->createElement($key);
                $occ = $child_data->appendChild($occ);
                $this->array_to_xml($val, $xml_data, $occ);

            } else {
                 // Param Key
                $occ = $xml_data->createElement($key);
                $occ = $child_data->appendChild($occ);

                // Param Value
                $value = $xml_data->createTextNode($val);
                $value = $occ->appendChild($value);
            }
        }
    }

    function xml_htmlFormatter($Array = '',$paddingsize = 0)
    {
        $returnHtml = "";

        if(is_array($Array))
        {
            foreach($Array as $akey=>$avalue)
            {
                if( is_array($avalue) )
                {
                    $oPaddingSize = $paddingsize;
                    $returnHtml .= '<span style="margin-left:'.$oPaddingSize.'px">&lt;'.$akey.'&gt;</span><br>';
                    
                    if($paddingsize == 0 || $paddingsize > 0)
                    {
                        $paddingsize = $paddingsize + 20;
                    }
                        $returnHtml .= $this->xml_htmlFormatter($avalue,$paddingsize);
                    $returnHtml .= '<span style="margin-left:'.$oPaddingSize.'px">&lt;/'.$akey.'&gt<br>';
                }
                else
                {
                    
                    $returnHtml .= '<span style="margin-left:'.$paddingsize.'px">&lt;'.$akey.'&gt;<span style="color:#000">'.$avalue.'</span>&lt;/'.$akey.'&gt;</span><br>';
                }
            }

        }
        
        return $returnHtml;
    }

    function menu_dropdown($parentid = '')
    {
        
        $menuFilter = $this->menupageaccess();
        $html = ' <div id="menu_'.$parentid.'" class="collapse" aria-expanded="false">
                    <ul class="sitesubmenu2">';

        $getMenuItem = $this->CI->sqlhelper->local->select("tbl_menu")->where("mEnable = 'Y' and mPosition = '1' and mParentMenuID = ".$parentid)->ex_select("","mOrder asc","")->result();
        
        if( is_array($getMenuItem) && $getMenuItem['Count'] > 0 )
        {
           foreach( $getMenuItem['Data'] as $mRow => $mCol)
            {
                if( is_array($menuFilter))
                {
                    if( in_array($mCol['MenuID'],$menuFilter) )
                    {
                        $html .= '<li class="">
                                    <a id="menu_'.$mCol['MenuID'].'" href=" '.( ($mCol['mCustomHref'] == 'Y') ? $mCol['mHref'] : site_url($mCol['mHref']) )  .' " '. ( (strpos( $mCol['mHref'], "javascript:" ) !== false && $mCol['mCustomHref'] == 'Y' ) ? 'target="_blank"' : '' ).' style="" class="" >
                                    '.$mCol['mMenuImage'].'&nbsp;'.$mCol['mTitle'].'
                                    </a>
                                    
                                </li>';
                    }
                }
                
                
            }
        }
                    
        $html .= '      </ul>
                    </div>';
        return $html;
    }
    function getDaysDifference($date1,$date2)
    {
        $Diff = date_diff( date_create($date1) , date_create($date2) )  ;   
        return $Diff->format("%a");
    }
    // Compute for Age
    // $return = all = year
    function ComputeForAge($birthday, $now,$return='y')
    {
        $bday = new DateTime(date("Y-m-d",strtotime($birthday))); // Your date of birth
        $today = new Datetime(date("Y-m-d",strtotime($now)));
        $diff = $today->diff($bday);

        if($return == 'all')
        {
            return $diff ;
        }
        else
        {
           return $diff->y; 
        }
        
    } 
    
    
    function getPRINTHeader()
    {
        $printHeader = '
            <div style="margin-bottom:10px;border-bottom: 1px double #999;height:80px">
                <div class="pull-left" style="margin-right:30px">
                    <img alt="Brand" src="'.site_url('/assets/images/Brand.png').'" height="30" width="30">
                </div>
                <div class="pull-left">
                    <h4 style="margin:0px">'.@$this->CI->system_settings['ApplicationOwner'].'</h4>
                    <h3 style="margin-top: 3px;">'.@$this->CI->system_settings['ApplicationName'].'</h3>
                </div>
            </div>
            <br>
        ';

        return $printHeader;
    }

    function onlineuser($userid = '')
    {
        $return = array(
          "Status"=>0,
          "Message"=>""
        );
      
        $return['Status'] = 1;

        $w = ( @$userid <> '') ? 'User_ID = '.((int) $userid) : '';

        $getOnlineUsers = $this->CI->sqlhelper->remote1->select("v_002_users_online")->where($w)->ex_select('User_ID','LogDateTime desc','');


        $userOnline =  ( @$userid <> '')  ? $getOnlineUsers->row() : $getOnlineUsers->result();
        $return['Message'] = $userOnline['Count'] > 0 ? $userOnline['Data'] : '';

        // if(isset($POSTVALUE['getlist']))
        // {

        //     $i=0;
        //     foreach($loggedIn_User['UserOnline'] as $uid)
        //     {
        //         if( $uid == $this->CI->tank_auth->get_user_id() )
        //         {
        //          $loggedIn_User['Count'] = $loggedIn_User['Count'] - 1;
        //          unset($loggedIn_User['UserOnline'][$i]);
        //         }
        //         else
        //         {
        //         $user_details = $this->CI->sqlhelper->local->select("v_002_users_online","User_Name,Account_Name,User_Type")->where("User_ID='".$uid."'")->row();
        //         $loggedIn_User['UserOnline'][$i] = $user_details['Data'];
        //         }
        //         $i++;
        //     }
        //     $return['Message'] = $loggedIn_User;
        // }
        // else
        // {
        //     if( in_array($this->session->view_userid,$loggedIn_User['UserOnline']) )
        //     {
        //     $return['Message'] = "Y";
        //     }
        // }


        return json_encode($return);
    }
    
    function logoutonlineuser($userid = '')
    {
        $return = array(
          "Status"=>0,
          "Message"=>""
        );

        if($userid <> '')
        {
            $getUsersOnlineData = json_decode($this->onlineuser($userid),TRUE);
            if( is_array($getUsersOnlineData['Message']) )
            {
                $OnlineData = $getUsersOnlineData['Message'];
                // log_message("error",$OnlineData);
                $o_sessionid = $OnlineData['sessionid'];

                $uo = $this->CI->sqlhelper->local->delete('users_online')->where(" `userid` = ".$userid." ")->run();
                $ds = $this->CI->sqlhelper->local->delete('system_sessions')->where(" `id` ='".$o_sessionid."'")->run();

                $return['Status'] = 1;
            }
        }

        return json_encode($return);
    }

    function validateDataContent($Type_Name = "",$FormData = '')
    {
      $return = array(
                'Status' => 1,
                'Message'=> ""
            );

      if($DataMode == 1)
      {
        $addFieldFilter = " and Parameter_Name in ('".implode("','",array_keys($FormData))."')";
      }
      
      // Step 1 : get Data Properties
      $getParamKeys = $this->CI->sqlhelper->remote1->select("ws_method_param_key")->where("Type_Name = '".$Type_Name."'")->ex_select('','','1')->row();
      if($getParamKeys['Count'] > 0)
      {
            $getParamKeys = $getParamKeys['Data'];
            $getParamProperties = $this->CI->sqlhelper->remote1->select("ws_method_param_key_child")->where("Type_ID = '".$getParamKeys['Type_ID']."' ".$addFieldFilter)->ex_select('','','')->result();

          $ParamProperties = ($getParamProperties['Count'] > 0) ? $getParamProperties['Data'] : '';

          // Loop Through ParamProperties -> Validate Compliant Content
          if(is_array($ParamProperties))
          {
            $errorCnt = 0;
            $errMessage = array();
              foreach($ParamProperties as $PRows => $PCols)
              { // START LOOP

                $Parameter_Key = $PCols['Parameter_Name'];
                $SubmittedValue = $FormData[$Parameter_Key];

                // Check Mandatory 
                if($PCols['Required'] == 'Y' && $SubmittedValue == "")
                {
                  $errorCnt++;
                  $errMessage[] = array('FKey'=>$Parameter_Key,'Message' => $Parameter_Key. 'Invalid Value, This is a required field.');
                  goto moveNextItem;
                }

                // Check if have Parent Field
                if($PCols['Required'] == 'N' && $PCols['Parent_Parameter_ID'] <> '')
                {
                  $ParentFieldKey = $this->CI->sqlhelper->remote1->select('ws_method_param_key_child')->where("DataID = '".$PCols['Parent_Parameter_ID']."'")->ex_select('','','1')->row();
                  $ParentFieldKey = $ParentFieldKey['Data']['Parameter_Name'];
                  if( $FormData[$ParentFieldKey] == $PCols['Parent_Expected_Value'] && $SubmittedValue == "" )
                  {
                    $errorCnt++;
                    $errMessage[] = array('FKey'=>$Parameter_Key,'Message' => 'Invalid Value, This is a required field.');
                    goto moveNextItem;
                  }
                }

                // Check Reference if used
                if( $PCols['Reference_ID'] <> '')
                {
                  if($SubmittedValue <> '')
                  {
                    // Check If Exists in Reference
                    $returnDesc = $this->getRef_Desc($PCols['Reference_ID'],$SubmittedValue);
                    if( $returnDesc == "" )
                    {
                      $errorCnt++;
                      $errMessage[] = array('FKey'=>$Parameter_Key,'Message' => "Invalid Value, Submitted Value doesn't exist in the reference");
                      goto moveNextItem;
                    }
                  }
                }

                // Check if JSON STRING
                if( $PCols['ValueType'] == 3 && $SubmittedValue <> '')
                {
                  // log_message("debug","SValue : ".$SubmittedValue);
                  if(!json_decode($SubmittedValue))
                  {
                    $errMessage[] = array('FKey'=>$Parameter_Key,'Message' => "Invalid Value, Submitted Value is not a valid json string");
                    $errorCnt++;
                    goto moveNextItem;
                  }
                }

              

                switch ($PCols['Data_Type']) 
                {
                    case 'date':
                         
                        $FormData[$Parameter_Key] = ($SubmittedValue <> '') ? date('Y-m-d',strtotime($SubmittedValue)) : ''; 
                         
                        break;
                    case 'datetime':    
                      
                        $FormData[$Parameter_Key] = ($SubmittedValue <> '') ? date('Y-m-d H:i:s',strtotime($SubmittedValue)) : ''; 
                          
                        break;
                    case 'time':    
                    
                        $FormData[$Parameter_Key] = ($SubmittedValue <> '') ? date('H:i:s',strtotime($SubmittedValue)) : ''; 
                        break;
                    default:

                    $FormData[$Parameter_Key] = trim($this->CI->security->xss_clean($SubmittedValue));

                    break;
                }

               
                moveNextItem:
               
              } // END LOOP

              if($errorCnt > 0)
              {
                $return = array(
                    'Status' => 0,
                    'Message'=> $errMessage
                );
              }
              
          }

          if($return['Status'] == 1)
          {
            $return['Message'] = $FormData;
          }
      }
      

      return $return;
    }

    function getUserDetails($userid = '')
    {
        $return = "";
        
        if($userid == '')
        {
            return $return; 
        }

        $getData = $this->CI->sqlhelper->remote1->select("v_001_user_infostatus")->where("User_ID = ".$userid)->ex_select("","","1");
        $getData = $getData->row();
        $return = ($getData['Count'] > 0) ? $getData['Data'] : "";
        return $return; 
    }

    function getUserProfile($userid)
    {
        $return = "";
        $getProfile = $this->CI->sqlhelper->remote1->select("user_profiles")->where("user_id = ".$userid)->ex_select("","","1")->row();
        $return = ($getProfile['Count'] > 0) ? $getProfile['Data'] : "";
        return $return; 
    }

    function getFacilityDetails($hfhudcode)
    {
        $return = "";
        $getProfile = $this->CI->sqlhelper->remote1->select("ref_facilities")->where("hfhudcode = '".$hfhudcode."'")->ex_select("","","1")->row();
        $return = ($getProfile['Count'] > 0) ? $getProfile['Data'] : "";
        return $return; 
    }

function getReferenceConfig($referenceid = '')
{
    if(!isset($_SESSION['ReferenceConfigData']))
    {
        $_SESSION['ReferenceConfigData'] = [];
    }
    
    if( isset($_SESSION['ReferenceConfigData'][$referenceid]) )
    {
        return $_SESSION['ReferenceConfigData'][$referenceid];
    }
    else
    {
        $getReference = $this->CI->sqlhelper->remote1->select("system_reference")->where("DataID = ".trim($referenceid)."")->ex_select('','','1')->row();
        $_SESSION['ReferenceConfigData'][$referenceid] = $getReference['Data'];
        return ($getReference['Count'] > 0) ? $getReference['Data'] : "";
    }
}

function getReferenceValue($param,$otherColumn='')
{   
    if(isset($param['Filter']) && $param['Filter'] <> '')
    {
      $forSearch = array('select','update','delete');
      foreach( $forSearch as $sqlInject )
      {
        if( strpos( strtolower($param['Filter']), $sqlInject) > -1 )
        {
          return "";
        }
      }
    }

    $Field_Description = ''; // It can be a concat query or specific column name
    $Field_Value = ''; // It can be a concat query or specific column name
    $Reference_Table = ''; // It can be a join table [ if for join table please use alias]
    // log_message("error",$param);
    // log_message("error",base64_decode($param['Filter']));
    $getReference = $this->getReferenceConfig(@$param['ReferenceID']);

    // log_message("debug",trim($param['ReferenceID'])." TEST : ". json_encode($getReference));
    if(is_array($getReference))
    {
      $Field_Description = $getReference['Reference_Description_Field']; 
      $Field_Value = $getReference['Reference_Value_Field'];
      $Reference_Table = $getReference['Reference_Table'];
    } 
    else
    {
        return '';
    }
 
    // Override Here
    switch($param['ReferenceID'])
    {
      case 49 : // Region
          $Field_Description = 'concat(regname," ,",nscb_reg_name)';
          $Field_Value = 'regcode';
          $Reference_Table = 'ref_region';
        break;
      default:
          break;
    }

    $RefFilter = (@$param['Filter'] <> '') ? $param['Filter'] : "NOFILTER";
    
    if($Reference_Table <> '')
    {
        if( !isset($_SESSION['ReferenceValue_List']) )
        {
            $_SESSION['ReferenceValue_List'] = [];
        }

        if( isset($_SESSION['ReferenceValue_List'][$param['ReferenceID']][$RefFilter]) )
        {
            // log_message("udebug","REFERENCE SESSION LOADED");
            return $_SESSION['ReferenceValue_List'][$param['ReferenceID']][$RefFilter];
        }
        else
        {
            // log_message("udebug","REFERENCE DATA LOADED");
            // Run SQL Query
            $queryReference = $this->CI->sqlhelper->remote1->select($Reference_Table,$Field_Description.' as ref_description, '.$Field_Value.' as ref_value');
            if( isset($param['Filter']) && $param['Filter'] <> '')
            {
                if( base64_decode($param['Filter'],TRUE) )
                {
                    $queryReference = $queryReference->where(base64_decode($param['Filter']));
                }    
            }
            
            $queryReference = $queryReference->ex_select(@$param['Group'],@$param['Order'],'');
            $queryReference = $queryReference->result();
            $_SESSION['ReferenceValue_List'][$param['ReferenceID']][$RefFilter]=$queryReference['Data'];
            if($queryReference['ErrorCode'] == '' && $queryReference['Count'] > 0)
            {
                return $queryReference['Data'];
            }
            else
            {
                return "";
            }
        }
    }

}

function mysql_aes_encrypt($val,$key)
{
    $key = mysql_aes_key($key);
    $pad_value = 16-(strlen($val) % 16);
    $val = str_pad($val, (16*(floor(strlen($val) / 16)+1)), chr($pad_value));
    return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $val, MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_DEV_URANDOM));
}

function mysql_aes_decrypt($val,$key)
{
    $key = mysql_aes_key($key);
    $val = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $val, MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_DEV_URANDOM));
    return rtrim($val, "..16");
}

/* 
Form Creator - Properties - Start
Description : Create Table Form Type based on the given Multidimensional Array properties
FormMode = int, [ 1 = Input Form. 2 = View Form ]
FormDValue = array() [ array( key=>value ) ] ex : array( 'username'=>'gt', )
DisplayArray = array() [ Multidimensional Array properties : used as the main html properties for the form ]
DisplayArray = array(
    'BloodType' => array(
                  'type'                =>'select', // select, multiselect, text, date, time, datetime, radio, checkbox, textbox,file,image,password,email
                  'title'               =>'Blood Type',
                  'class'               =>'',
                  'style'               =>'', 
                  'required'            =>'',
                  'placeholder'         =>'',
                  'extraattr'           =>'',
                  'hint'                =>'',
                  'grouplabel'          =>'',
                  'subgrouplabel'       => '',

                  'parentfielid'         =>'',
                  'childfieldid'          =>'',
                  'childfieldfilter'    =>'',
                             
                  'referenceid'         => '',
                  'referencefilter'     => '',
                  'referenceorder'      => '', 
                  'referencevalue'      => '', //array(arraY('value'=>'description'));
                  'referenceajax'       => true, // true or false
                ),
);
CustomDisplayArray = array() [ Custom Display for Array : overwride auto form if key match ]
Form Creator  Properties - End  

$FormType = static, floating, horizontal
$FormLayout = horizontal,
*/
    function FormCreator($FormID='forminputgroup',$FormType='static',$FormLabel=true,$FormMode = 1,$FormDValue = array(), $DisplayArray = array(), $CustomDisplayArray = array(),$EditOnNullValueOnly = false )
    {
        $FormHTML = '';
        $ElementItem = array();
        $GroupItemLbl = [];
        $SubGroupItemLbl = [];
        $GroupItem = array();
        $NoGroupCount = 0;
        $NoSubGroupCount = 0;
        $CurrentGroup = '';
        $CurrentSubGroup = '';
        $eleJSScript = '';
        foreach( $DisplayArray as $elementid => $elementconfig )
        {     
            $formmaterialclass = '';
            $groupname    = strtolower(preg_replace("/[^A-Za-z0-9]/", "", @$elementconfig['GroupLabel']));
            $subgroupname = strtolower(preg_replace("/[^A-Za-z0-9]/", "", @$elementconfig['SubGroupLabel']));

            if($CurrentGroup == $groupname)
            {
                if( $groupname=="" && $CurrentGroup=="")
                { 
                    $groupname = 'nogroup_'.$NoGroupCount;
                    if( !isset($GroupItemLbl[$groupname]) )
                    {
                        $GroupItemLbl[$groupname] = ''; //@$elementconfig['title'].' & Other Info';
                    }
                   
                }

                if( $subgroupname == "")
                {
                    $subgroupname = 'nosubgroup_'.$NoSubGroupCount;
                }
                else
                {
                    if( $CurrentSubGroup <> $subgroupname)
                    {
                        $NoSubGroupCount++;
                    }
                    $CurrentSubGroup = $subgroupname;   
                }

            }
            else
            {
                $NoSubGroupCount = 0;

                $CurrentGroup = $groupname;

                if($groupname=="")
                {
                    $NoGroupCount++;
                    $groupname = 'nogroup_'.$NoGroupCount;
                }

                if( $subgroupname == "")
                {
                    $subgroupname = 'nosubgroup_'.$NoSubGroupCount;
                }

                $GroupItemLbl[$groupname] = @$elementconfig['GroupLabel'];
            }

            $CurrentSubGroup = $subgroupname;

            $GroupItem[$groupname][$subgroupname][] = $elementid;

            $SubGroupItemLbl[$groupname][$subgroupname] = @$elementconfig['SubGroupLabel'];


            $label = @$elementconfig['title'];

            $ehtml = '&nbsp;';

            $placeholderlabel = (@$elementconfig['placeholder'] <> '') ? $elementconfig['placeholder'] : $label;
            $placeholderlabel = strip_tags($placeholderlabel);

            $placeholder =  ( strtolower($FormType) <> 'floating' ) ? ((@$elementconfig['placeholder'] <> '') ? 'placeholder="'.$placeholderlabel.'"' : 'placeholder="'.$placeholderlabel.'"') : '';


            if($FormMode == 2)
            {
                $elementconfig['required'] = false;
                switch( strtolower($elementconfig['type']) )
                {
                    case "select":
                    case "checkbox":
                    case "radio":
       
                        $tryExplode = explode(",",@$FormDValue[$elementid]);
                        $dValues = [];
                        foreach($tryExplode as $exValue)
                        {
                            $eDvalue = @$FormDValue[$elementid];
                            if(@$elementconfig['referenceid'] <> '')
                            {
                                $dValues[] = $this->getRef_Desc(@$elementconfig['referenceid'],$exValue);
                            }

                            
                        }

                        $ehtml = '<input type="text" class="form-control" id="'.$elementid.'" title="'.$label.'"  disabled="disabled" value="'.implode(", ",$dValues).'" >';

                    break;

                    case "file":

                        $fValue = $FormDValue[$elementid];
                       
                        $fileD = [];
                        if( is_array($fValue) )
                        {
                            $fileD[] = $fValue;
                        }
                        else
                        {
                            $fileD = json_decode($fValue,TRUE);
                        }
                       
                        if( is_array($fileD) )
                        {
                            $fValue = $fileD;
                            $fileTypes = [
                               'image/jpeg',
                               'image/pjpeg',
                               'image/png',
                               'image/gif',
                               'image/jpg',
                               'application/pdf'
                             ];

                            foreach( $fValue as $fsI => $FileSets )
                            {
                                $fla = [
                                    'FileName' => @$FileSets['File_FileName'],
                                    'FileType' => @$FileSets['File_FileType'],
                                    'FileData' => '',
                                ];

                                $blobY = false;

                                if( @$FileSets['File_Path'] <> '' )
                                {
                                    $fla['FileData'] = @$FileSets['File_Path'];

                                    if( in_array($FileSets['File_FileType'], $fileTypes))
                                    {
                                        $BlobContent = file_get_contents($FileSets['File_Path']);
                                        $fla['FileData'] = base64_encode(@$BlobContent);
                                        $blobY = true;
                                    }          
                                }
                               
                                if( @$FileSets['File_Attachment'] <> '' )
                                {
                                    if( in_array($FileSets['File_FileType'], $fileTypes))
                                    {
                                        $fla['FileData'] = @$FileSets['File_Attachment'];
                                        $blobY = true;
                                    }
                                    else
                                    {
                                        $fla['FileData'] = @$FileSets['File_Path'];
                                    }

                                }

                                if($blobY)
                                {
                                    $filelinkAction = 'onclick="javascript:ObjectFileViewer(\''.base64_encode(json_encode($fla)).'\')" ';
                                }
                                else
                                {
                                    $surl = site_url();
                                    $fPath = str_replace('\\','/',FCPATH);

                                    $fpp = str_replace($fPath, $surl, $fla['FileData']);
                                    $filelinkAction = 'onclick="javascript:window.open(\''.$fpp.'\',\'_blank\')" ';
                                }
                                

                                $ehtml .= '
                                <input id="FileHolderx_'.$elementid.'" type="text" class="form-control float-left" placeholder="'.@$FileSets['File_FileName'].'" readonly="" style="border: 0px !important;width: 96%;margin-top: 5px;" disabled="disabled" ><i id="iFileHolderx_'.$elementid.'" class="fa '.( (in_array(@$FileSets['File_FileType'], $fileTypes)) ? 'fa-search' : 'fa-download').' float-right" style="cursor:pointer;margin-top: 13px;" '.@$filelinkAction.'></i>';
                            }
  
                        }



                    break;

                    default:
                   
                        $ehtml = '<input type="text" class="form-control '.@$elementconfig['class'].'" title="'.$label.'"  id="'.$elementid.'" disabled="disabled" value="'.((!is_array(@$FormDValue[$elementid])) ? $this->formatValueDateTime($elementid,@$FormDValue[$elementid]) : json_encode(@$FormDValue[$elementid]) ).'" style="'.@$elementconfig['style'].'">';
                    break;
                }
            }
            else
            {
                $fValue = (@$FormDValue[$elementid] <> '') ? ((!is_array(@$FormDValue[$elementid])) ? $this->dbValueFormatter($elementid,@$FormDValue[$elementid]) : json_encode(@$FormDValue[$elementid]) ) : '';

                switch( strtolower($elementconfig['type']) )
                {
                    case "multiselect":
                    case "select":
                        $ehtml = '<select class="form-control '.@$elementconfig['class'].'" id="'.$elementid.'" name="'.$elementid.'" title="'.$label.'" forminputgroup="'.@$FormID.'" forminputgroupcheck="'.@$FormID.'" autocomplete="'.$elementid.'_false" '.@$elementconfig['extraattr'].' style="'.@$elementconfig['style'].'" '.(($elementconfig['required']) ? 'required="required"' : '').' '.( (strtolower($elementconfig['type']) == 'multiselect') ? 'multiple' : '').' dfvalue="'.$fValue.'"></select>';

                        if( is_numeric($elementconfig['referenceid']) )
                        {
                            $selectrefArray = array(
                                'referenceid'        => @$elementconfig['referenceid'], 
                                'referencefilter'    => @$elementconfig['referencefilter'], 
                                'referenceorder'     => @$elementconfig['referenceorder'], 
                                'parentfieldid'      => @$elementconfig['parentfieldid'], 
                                'childfieldid'       => @$elementconfig['childfieldid'],
                                'childfieldfilter'   => @$elementconfig['childfieldfilter'],
                            );

                            $eleJSScript .= 'addSelectReferenceObjectList(\''.$elementid.'\','.json_encode($selectrefArray).');';
                        }
                    break;

                    case "textarea":
                        $ehtml = '<textarea class="form-control '.@$elementconfig['class'].'" id="'.$elementid.'" name="'.$elementid.'" title="'.$label.'" forminputgroup="'.@$FormID.'" forminputgroupcheck="'.@$FormID.'" '.$placeholder.' autocomplete="'.$elementid.'_false" '.@$elementconfig['extraattr'].' style="'.@$elementconfig['style'].'" row="2" '.(($elementconfig['required']) ? 'required="required"' : '').'>'.$fValue.'</textarea>';
                    break;

                    case "radio":
                    case "checkbox":

                        $etype = strtolower($elementconfig['type']);
                        $inlinemode = (@$elementconfig['radiocheckboxinline'] <> '' && $elementconfig['radiocheckboxinline'] == 'N' ) ? '' : '-inline';

                        $tfValue = $fValue;
                        if( $etype == 'checkbox' )
                        {
                            $tfValue = explode(",",$tfValue);
                            $tfValue = (count($tfValue) > 0) ? $tfValue : [];
                        }

                        if( $elementconfig['referenceid'] <> "" && is_numeric($elementconfig['referenceid']) )
                        {
                            $aitemTmp = '';
                            $referenceConfig = $this->CI->myutilities->getReferenceConfig(@$elementconfig['referenceid']);

                            $refItem = $this->CI->sqlhelper->remote1->select($referenceConfig['Reference_Table'],$referenceConfig['Reference_Value_Field']." as rval, ".$referenceConfig['Reference_Description_Field']." as rdesc")->where(@$elementconfig['referencefilter'])->ex_select('',@$elementconfig['referenceorder'],'')->result();
                            if($refItem['Count'] > 0)
                            {
                                foreach($refItem['Data'] as $rRow => $rCols)
                                {
                                    $aitemTmp .= '
                                        <div class="'.$etype.$inlinemode.' '.$etype.'-custom custom-control '.$etype.'-primary">
                                            <input type="'.$etype.'" id="'.$elementid.'_'.$rCols['rval'].'" name="'.$elementid.''.(($etype == 'radio') ? '' : '[]').'" class="form-control" forminputgroup="'.@$FormID.'" forminputgroupcheck="'.@$FormID.'" '.@$elementconfig['extraattr'].' value="'.$rCols['rval'].'"  title="'.$rCols['rdesc'].'" parenttitle="'.$label.'" '.( ($etype == 'checkbox') ? ( (in_array($rCols['rval'], $tfValue)) ? 'checked' : '' ) : ( ($rCols['rval'] == $fValue) ? 'checked' : '' )).'>
                                            <label class="font-size-13" for="'.$elementid.'_'.$rCols['rval'].'">'.$rCols['rdesc'].'</label>
                                        </div>
                                    ';
                                }  
                            }
                            
                            $ehtml = '<div><input type="hidden" class="form-control" id="'.$elementid.(($etype == 'checkbox') ? '' : '').'" name="'.$elementid.(($etype == 'checkbox') ? '[]' : '').'" title="'.$label.'" forminputgroupcheck="'.@$FormID.'" dfvalue="'.$fValue.'"  '.(($elementconfig['required']) ? 'required="required"' : '').' style="display:none;">'.$aitemTmp.'</div>';
                            
                        }
                        else
                        {
                            // goto emoveHere;
                        }
                    break;

                    case "file":

                        $formmaterialclass = 'form-material-file ';
                        if( strtolower($FormType) <> 'floating'  )
                        {
                            $placeholder = 'placeholder="Browse..."'; 
                        }

                        $ehtml = '
                        <input type="text" class="form-control '.@$elementconfig['class'].'"  '.$placeholder.'  readonly="">
                        <input type="file" id="'.$elementid.'" name="'.$elementid.'" title="'.$label.'" forminputgroup="'.@$FormID.'" forminputgroupcheck="'.@$FormID.'" '.@$elementconfig['extraattr'].' autocomplete="'.$elementid.'_false"  style="width: 100%;height: 35px;'.@$elementconfig['class'].'" '.(($elementconfig['required']) ? 'required="required"' : '').'>';
                        
                        $fileD = [];
                        if( is_array($fValue) )
                        {
                            $fileD[] = $fValue;
                        }
                        else
                        {
                            $fileD = json_decode($fValue,TRUE);
                        }
                       
                        if( is_array($fileD) )
                        {
                            $fValue = $fileD;

                            if( count($fValue) > 0 )
                            {
                                if( array_keys($fValue) == 0 ) 
                                {
                                    
                                }
                                else
                                {
                                    $fValue = [$fValue];
                                }
                            }

                            $fileTypes = [
                               'image/jpeg',
                               'image/pjpeg',
                               'image/png',
                               'image/gif',
                               'image/jpg',
                               'application/pdf'
                             ];

                            foreach( $fValue as $fsI => $FileSets )
                            {
                                $fla = [
                                    'FileName' => @$FileSets['File_FileName'],
                                    'FileType' => @$FileSets['File_FileType'],
                                    'FileData' => '',
                                ];

                                $blobY = false;

                                if( @$FileSets['File_Path'] <> '' )
                                {
                                    $fla['FileData'] = @$FileSets['File_Path'];

                                    if( in_array($FileSets['File_FileType'], $fileTypes))
                                    {
                                        $BlobContent = file_get_contents($FileSets['File_Path']);
                                        $fla['FileData'] = base64_encode(@$BlobContent);
                                        $blobY = true;
                                    }          
                                }
                               
                                if( @$FileSets['File_Attachment'] <> '' )
                                {
                                    if( in_array($FileSets['File_FileType'], $fileTypes))
                                    {
                                        $fla['FileData'] = @$FileSets['File_Attachment'];
                                        $blobY = true;
                                    }
                                    else
                                    {
                                        $fla['FileData'] = @$FileSets['File_Path'];
                                    }

                                }

                                if($blobY)
                                {
                                    $filelinkAction = 'onclick="javascript:ObjectFileViewer(\''.base64_encode(json_encode($fla)).'\')" ';
                                }
                                else
                                {
                                    $surl = site_url();
                                    $fPath = str_replace('\\','/',FCPATH);

                                    $fpp = str_replace($fPath, $surl, $fla['FileData']);
                                    $filelinkAction = 'onclick="javascript:window.open(\''.$fpp.'\',\'_blank\')" ';
                                }
                                

                                $ehtml .= '
                            <input id="FileHolderx_'.$elementid.'" type="text" class="form-control float-left" placeholder="'.@$FileSets['File_FileName'].'" readonly="" style="border: 0px !important;width: 96%;margin-top: 5px;" disabled="disabled" ><i id="iFileHolderx_'.$elementid.'" class="fa '.( (in_array($FileSets['File_FileType'], $fileTypes)) ? 'fa-search' : 'fa-download').' float-right" style="cursor:pointer;margin-top: 13px;" '.@$filelinkAction.'></i>';
                            }
  
                        }


                    break;

                    case "image":
                    break;

                    case "date":

                        if( strtolower($FormType) == 'floating'  )
                        {
                            $label = $label.'<i class="ml-10">(mm/dd/yyyy)</i>';
                        }
                        else
                        {
                            $placeholder = 'placeholder="mm/dd/yyyy"'; 
                        }

                        $elementconfig['hint'] = 'Date Format = mm/dd/yyyy [ex: 01/23/1987] '.@$elementconfig['hint'];
                        $ehtml = '<input type="text" class="form-control '.@$elementconfig['class'].'" id="'.$elementid.'" name="'.$elementid.'" title="'.$label.'" forminputgroup="'.@$FormID.'" forminputgroupcheck="'.@$FormID.'" '.$placeholder.' autocomplete="'.$elementid.'_false" '.@$elementconfig['extraattr'].' dfvalue="'.$fValue.'" value="'.$this->formatValueDateTime($elementid,$fValue).'" style="'.@$elementconfig['style'].'" '.(($elementconfig['required']) ? 'required="required"' : '').' data-plugin="formatter,datepicker" data-pattern="[[99]]/[[99]]/[[9999]]">';
                    break;
                    case "time":

                        if( strtolower($FormType) == 'floating'  )
                        {
                            $label = $label.'<i class="ml-10">(hh:mm)</i>';
                        }
                        else
                        {
                            $placeholder = 'placeholder="hh:mm"'; 
                        }

                        $elementconfig['hint'] = '24Hour Time Format = hh:mm '.@$elementconfig['hint'];
                        $ehtml = '<input type="text" class="form-control '.@$elementconfig['class'].'" id="'.$elementid.'" name="'.$elementid.'" title="'.$label.'" forminputgroup="'.@$FormID.'" forminputgroupcheck="'.@$FormID.'" '.$placeholder.' autocomplete="'.$elementid.'_false" '.@$elementconfig['extraattr'].' dfvalue="'.$fValue.'" value="'.( (@$fValue <> '') ? date('H:i',strtotime($fValue)) : '').'" style="'.@$elementconfig['style'].'" '.(($elementconfig['required']) ? 'required="required"' : '').' data-plugin="formatter,clockpicker" data-placement="top" data-autoclose="true" data-pattern="[[99]]:[[99]]">';
                    break;

                    case "datetime":
                    break;
                    case "hidden":
                        $ehtml = '<input type="'.strtolower($elementconfig['type']).'" class="form-control '.@$elementconfig['class'].'" id="'.$elementid.'" name="'.$elementid.'" title="'.$label.'" forminputgroup="'.@$FormID.'" forminputgroupcheck="'.@$FormID.'" '.$placeholder.' autocomplete="'.$elementid.'_false" '.@$elementconfig['extraattr'].' style="'.@$elementconfig['style'].'" '.(($elementconfig['required']) ? 'required="required"' : '').' dfvalue="'.$fValue.'" value="'.$fValue.'">';
                    break;
                    case "text":
                    case "email":
                    case "password":
                    default:
                        $ehtml = '<input type="'.strtolower($elementconfig['type']).'" class="form-control '.@$elementconfig['class'].'" id="'.$elementid.'" name="'.$elementid.'" title="'.$label.'" forminputgroup="'.@$FormID.'" forminputgroupcheck="'.@$FormID.'" '.$placeholder.' autocomplete="'.$elementid.'_false" '.@$elementconfig['extraattr'].' style="'.@$elementconfig['style'].'" '.(($elementconfig['required']) ? 'required="required"' : '').' dfvalue="'.$fValue.'" value="'.$fValue.'">';
                    break;
                } 
            }

               

            $chkradio = (  strtolower($elementconfig['type']) == 'checkbox' ||  strtolower($elementconfig['type']) == 'radio' ) ? true : false;

            $lblclass = ($FormType == 'floating') ? 'floating-label' : '';
            $tmpFormType = $FormType;
            switch( strtolower($FormType) )
            {
                case 'static':
                    $lblclass = 'form-control-label';
                break;

                case 'floating':
                    if(strtolower($elementconfig['type']) <> 'checkbox' && strtolower($elementconfig['type']) <> 'radio')
                    {
                        $lblclass = 'floating-label';
                        $formmaterialclass = $formmaterialclass.' floating';  
                    }
                    else
                    {
                        $FormType = 'static';
                        $lblclass = '';
                    }
                    
                break;

                case 'horizontal':
                    $lblclass = 'col-form-label ml-20 font-weight-bold pt-'.( ($chkradio) ? '0' : '10');
                    $formmaterialclass = $formmaterialclass.' row';
                break;

                default:
                    $lblclass = 'form-control-label';
                break;
            }


            $requirdmark = '<span id="RequiredMark_'.$elementid.'" class="require mr-2 text-danger" style="'.(($elementconfig['required']) ? '' : 'display:none').'">*</span>';
            $colonmark   = ($FormType == 'horizontal') ? '<span class="mr-5 ml-20 pt-'.( ($chkradio) ? '0' : '10').'" style="width:1%;">:</span>' : '';

            $lbldisplay  = ($FormLabel) ?  '<label class="'.$lblclass.'" for="'.$elementid.'" style="'.(($FormType == 'horizontal') ? 'width:30%' : '').'">'.$requirdmark.$label.'</label>'.$colonmark : '';

            if(@$elementconfig['inputonly'] == 'Y' )
            {
                $d_ehtml= '
                <div id="formelementdiv_'.$elementid.'" group="'.$groupname.'" subgroup="'.$subgroupname.'" class="form-group form-material '.$formmaterialclass.' mb-0" data-plugin="formMaterial" style="'.( ( strtolower($elementconfig['type']) == 'hidden' ) ? 'display:none' : '').'">
                    '.( ($FormType <> 'floating' ) ? '' : '' ).'
                    '.(($FormType == 'horizontal') ? '<div class="w-p100 p-15" style="">' : '').'
                    '.$ehtml.'
                    '.( ($FormType == 'floating' ) ? '' : '' ).'
                    <div class="hint" style="">'.@$elementconfig['hint'].'<span id="RequiredMark_'.$elementid.'" class="text-danger '.((@$elementconfig['hint'] <> '') ? 'ml-20' : '').' float-right" style="'.(($elementconfig['required']) ? '' :'display:none').'">Required Field!.</span></div>
                    '.(($FormType == 'horizontal') ? '</div>' : '').'
                </div>
                ';
            }
            else
            {
                $d_ehtml= '
                <div id="formelementdiv_'.$elementid.'" group="'.$groupname.'" subgroup="'.$subgroupname.'" class="form-group form-material '.$formmaterialclass.'" data-plugin="formMaterial" style="'.( ( strtolower($elementconfig['type']) == 'hidden' ) ? 'display:none' : '').'">
                    '.( ($FormType <> 'floating' ) ? $lbldisplay : '' ).'
                    '.(($FormType == 'horizontal') ? '<div class="mr-20" style="width:60%;">' : '').'
                    '.$ehtml.'
                    '.( ($FormType == 'floating' ) ? $lbldisplay : '' ).'
                    <div class="hint" style="">'.@$elementconfig['hint'].'<span id="RequiredMark_'.$elementid.'" class="text-danger '.((@$elementconfig['hint'] <> '') ? 'ml-20' : '').' float-right" style="'.(($elementconfig['required']) ? '' :'display:none').'">Required Field!.</span></div>
                    '.(($FormType == 'horizontal') ? '</div>' : '').'
                </div>
                ';
            }

           

            if( $chkradio )
            {
                $FormType = $tmpFormType;
            }

            if( isset($CustomDisplayArray[$elementid]) && $CustomDisplayArray[$elementid] <> '' )
            {
                $ElementItem[$elementid] = base64_encode($CustomDisplayArray[$elementid]);
            }
            else
            {
                $ElementItem[$elementid] = base64_encode($d_ehtml);
            }
            

        }

        $FormHTML .= '<div class="panel-group panel-group-simple mb-0" id="formAccord_'.$FormID.'" aria-multiselectable="true" role="tablist" >';

        $PrevGroupItemLabel = '';
        foreach($GroupItem as $g => $sg )
        {
            $accordhead = '<div class="panel-heading" id="group_'.$g.'" role="tab" style="'.((@$GroupItemLbl[$g] == '') ? 'display:none' :'').'"><a class="panel-title collapsed" data-parent="#formAccord_'.$FormID.'_'.$g.'" data-toggle="collapse" href="#collapse_group_'.$g.'" aria-controls="collapse_group_'.$g.'" aria-expanded="true"><h4 class=" m-0">'.@$GroupItemLbl[$g].'</h4></a></div>';

            
            $subaccordpanel = '';

            foreach($sg as $sgname => $sgitem)
            {
                $subaccordpanel .= '';
                $si = '';
                foreach($sgitem as $ed )
                {
                    $si .= base64_decode($ElementItem[$ed]);
                }
                $subaccordpanel .= '
                <div class="card mb-5">
                    <div class="card-block">
                        <h5 class="card-title" '.( (@$SubGroupItemLbl[$g][$sgname] <> '') ? 'id="SubGroup_'.str_replace(" ","",$SubGroupItemLbl[$g][$sgname]).'"' : '').' >'.$SubGroupItemLbl[$g][$sgname].'</h5>
                        <div>
                            '.$si.'
                        </div>
                    </div>
                </div>';
            }



            $accordheadbody = '<div class="panel-collapse collapse show" id="collapse_group_'.$g.'" aria-labelledby="group_'.$g.'" role="tabpanel" style=""><div class="panel-body" style="'.((@$GroupItemLbl[$g] == '' && $PrevGroupItemLabel == '') ? 'border-top:0px' :'').'">'.$subaccordpanel.'</div></div>';
            $accordpanel = '<div class="panel">'.$accordhead.$accordheadbody.'</div>';
            $FormHTML .= $accordpanel;

            $PrevGroupItemLabel = @$GroupItemLbl[$g];

        }
        $FormHTML .= '</div>

        <script id="removethis" nonce="'.$this->CI->nonceV.'" type="text/javascript">
            $(document).ready(function(){
                $("div[id=\'formAccord_'.$FormID.'\']").on("shown.bs.collapse", function () {
                   
                });

                '.$eleJSScript.'

            });  
        </script> 
        ';


        return $FormHTML;
    }




    function dateDifference($startDate, $endDate) 
    { 
        $startDate = strtotime($startDate); 
        $endDate = strtotime($endDate); 
        if ($startDate === false || $startDate < 0 || $endDate === false || $endDate < 0 || $startDate > $endDate) 
            return false; 
            
        $years = date('Y', $endDate) - date('Y', $startDate); 
        
        $endMonth = date('m', $endDate); 
        $startMonth = date('m', $startDate); 
        
        // Calculate months 
        $months = $endMonth - $startMonth; 
        if ($months <= 0)  { 
            $months += 12; 
            if($years > 0)
            {
                $years--; 
            }
            
        } 
        if ($years < 0) 
            return false; 
        
        // Calculate the days 
        $offsets = array(); 
        if ($years > 0) 
            $offsets[] = $years . (($years == 1) ? ' year' : ' years'); 
        if ($months > 0) 
            $offsets[] = $months . (($months == 1) ? ' month' : ' months'); 
        $offsets = count($offsets) > 0 ? '+' . implode(' ', $offsets) : 'now'; 

        $days = $endDate - strtotime($offsets, $startDate); 
        $days = date('z', $days);    
                    
        return array($years, $months, $days); 
    } 

    function generateusername()
    {
        try
        {
            $g = "select CAST(REPLACE(username,'".$this->CI->system_settings['ApplicationAbbre']."','') as UNSIGNED)  as username from users where username like '".$this->CI->system_settings['ApplicationAbbre']."%' and REPLACE(username,'".$this->CI->system_settings['ApplicationAbbre']."','') REGEXP '^[0-9]+$' order by username desc limit 1";
            $getCMPS = $this->CI->sqlhelper->local->sql($g)->row();
           
            $uCNT = ($getCMPS['Count'] == 0) ? 1 : (int) (str_replace($this->CI->system_settings['ApplicationAbbre'],'',$getCMPS['Data']['username'])) + 1;
            $uCNT = (int) $uCNT;
            $newUserName = strtoupper($this->CI->system_settings['ApplicationAbbre']).str_pad($uCNT,7,0,STR_PAD_LEFT);
             // log_message("error",$newUserName);
            return $newUserName;
        }
        catch (PDOException $e) {
          log_message("error",__METHOD__." | ".$e->getMessage());
        } catch (Exception $e) {
          log_message("error",__METHOD__." | ".$e->getMessage());
        }

        exit_function_here:
        $ulog = array(
          'type'        =>'Create New Account',
          'action'      =>'Generate User Name',
          'description' =>'',
          'remarks'     =>$return['Message']
        );

        $this->CI->write_useractivitylog($ulog);
    }

    function systemmodulepermissionlist()
    {
        $getPermissionList = $this->CI->sqlhelper->remote1->select("ref_permission_module")->result();
        $PermissionListItem = [];
        foreach($getPermissionList['Data'] as $pmRow => $pmCol)
        {
            $PermissionListItem[$pmCol['PermissionName']] = $pmCol['Code'];
        }

        return $PermissionListItem;
    }

    function usermodulepermission($userid = '')
    {
        $permissionlist = [];
        $getUserPermissionList = $this->CI->sqlhelper->remote1->select("user_permission")->where("user_id ='".$userid."'")->result();
        $UserPermissionListItem = [];
        foreach($getUserPermissionList['Data'] as $upmRow => $upmCol)
        {
            $permissionlist[$upmCol['PermissionNameID']] = $upmCol['PermissionValue'];
        }

        return $permissionlist;
    }

    function checkDirectoryAccess($dir) {
        $return = ["writeable"=> @$writeable, "deleteable" => @$deleteable ];

        try
        {
            // Check write access
            $writeable = is_writable($dir) ? true : false;
            if( is_dir($dir) )
            {
                // Check delete access by attempting to create and delete a temporary file
                $tempFile = $dir . '/tempfile.tmp';
                $deleteable = false;
                try
                {
                    if (file_put_contents($tempFile, 'test') !== false) {
                        if (unlink($tempFile)) {
                            $deleteable = true;
                        }
                    }
                 } catch (Exception $e) {
                  log_message("error",__METHOD__." | ".$e->getMessage());
                }

            }

        } catch (Exception $e) {
          log_message("error",__METHOD__." | ".$e->getMessage());
        }

        $return = ["writeable"=> @$writeable, "deleteable" => @$deleteable ];

        return $return;
    }
}

