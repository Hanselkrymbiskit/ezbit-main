<?php defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class MY_Exceptions extends CI_Exceptions {
    public $CI;

    function __construct(){
        parent::__construct();
        // log_message("debug",site_url());
        $this->CI =& get_instance();
    }

    
    
    /**
     * 404 Error Handler
     *
     * @uses    CI_Exceptions::show_error()
     *
     * @param   string  $page       Page URI
     * @param   bool    $log_error  Whether to log the error
     * @return  void
     */
    public function show_404($page = '', $log_error = TRUE)
    {
        $heading = ''; $message = '';
        if (is_cli())
        {
            $heading = 'Not Found';
            $message = 'The controller/method pair you requested was not found.';
        }
        else
        {
            $heading = '404 Page Not Found';
            $message = 'The page you requested was not found.';
        }

        // By default we log this, but allow a dev to skip it
        if ($log_error)
        {
            log_message('error', @$heading.': '.$page);
        }

        if(!file_exists(FCPATH.".htaccessOrig")) 
        {
            $originHtaccess = file_get_contents(FCPATH.".htaccess");
            file_put_contents(FCPATH.".htaccessOrig",$originHtaccess);
        }

        $ClientIP = @$_SERVER['REMOTE_ADDR'];
        $BanFileName = 'BanIPList.txt';
        $BanFilePath = FCPATH."BanIPList.txt";
        $BanIPFilePath = FCPATH."BanIP.txt";

        $LocalIP = config_item('proxy_ips');
        $LocalIPAdded = ['localhost','127.0.0.1'];
        $LocalIP = array_merge($LocalIP,$LocalIPAdded);

        if( !in_array( $ClientIP, $LocalIP) )
        {
           
            $CIP_Array = []; 
            $BanIP = '';
            $BanCount = 0;
            if(!file_exists($BanFilePath)) 
            {
                $CIP_Array[$ClientIP] = ['AccessCnt' => 1, 'LastAccessDT' => date("Y-m-d H:i:s"),'BanIP'=>0];
                file_put_contents($BanFilePath,json_encode($CIP_Array));
            }
            else
            {
                $CIP_Array = file_get_contents($BanFilePath);
                $CIP_Array = json_decode($CIP_Array,TRUE);

                if(!isset($CIP_Array[$ClientIP]))
                {
                    $CIP_Array[$ClientIP] = ['AccessCnt' => 1, 'LastAccessDT' => date("Y-m-d H:i:s"),'BanIP'=>0];
                }
                else
                {
                    $AccessCnt = (!isset($CIP_Array[$ClientIP]['AccessCnt'])) ? $CIP_Array[$ClientIP] : $CIP_Array[$ClientIP]['AccessCnt'];
                    $CIP_Array[$ClientIP] = ( !is_array($CIP_Array[$ClientIP])) ? [] : $CIP_Array[$ClientIP];
                    $CIP_Array[$ClientIP]['AccessCnt'] = (($AccessCnt <> '') ? $AccessCnt : 0 ) + 1;
                    $CIP_Array[$ClientIP]['LastAccessDT'] = date("Y-m-d H:i:s");
                    $CIP_Array[$ClientIP]['BanIP'] = ($CIP_Array[$ClientIP]['AccessCnt'] >= 10) ? 1 : 0;
                }

                file_put_contents($BanFilePath,json_encode($CIP_Array));
            }
            
            if( @$CIP_Array[$ClientIP]['AccessCnt'] >= 10 )
            {
                if(!file_exists($BanIPFilePath))
                {
                    file_put_contents($BanIPFilePath,"Require not ip ".$ClientIP."\n");
                }
                else
                {
                    $BanIP = file_get_contents($BanIPFilePath);
                    
                    if( strpos($BanIP,$ClientIP) == "" || strpos($BanIP,$ClientIP) < 0)
                    {
                        $BanIP .= "Require not ip ".$ClientIP."\n";
                    }
                   
                    file_put_contents($BanIPFilePath, $BanIP);
                } 

                $OrigHTAccess = file_get_contents(FCPATH.".htaccessOrig");
                $OrigHTAccess .= "\n"."<IfModule authz_core_module>"."\n"."<RequireAll>"."\n"."Require all granted"."\n".$BanIP."\n"."</RequireAll>"."\n"."</IfModule>";
                $CurrentHTAccess = file_put_contents(FCPATH.".htaccess", $OrigHTAccess);
            }
        }

        echo $this->show_error(@$heading, $message, 'error_404', 404);
        exit(4); // EXIT_UNKNOWN_FILE
    }

    /**
     * General Error Page - Override
     *
     * Takes an error message as input (either as a string or an array)
     * and displays it using the specified template.
     *
     * @param   string      $heading    Page heading
     * @param   string|string[] $message    Error message
     * @param   string      $template   Template name
     * @param   int     $status_code    (default: 500)
     *
     * @return  string  Error page output
     */
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        ini_set('display_errors', 0);
        $templates_path = '';
        if (empty($templates_path))
        {
            $templates_path = VIEWPATH.'templates/errors'.DIRECTORY_SEPARATOR;
        }

        if (is_cli())
        {
            $message = "\t".(is_array($message) ? implode("\n\t", $message) : $message);
            $template = DIRECTORY_SEPARATOR.$template;
        }
        else
        {
            set_status_header($status_code);
            $message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';
            $template = $template;
        }
        
        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }


        if( $_SERVER['REQUEST_METHOD'] == 'GET')
        {
            ob_start();

            include($templates_path.'/header.php');
            include($templates_path.'errorpage'.DIRECTORY_SEPARATOR.$template.'.php');
            include($templates_path.'/footer.php');
            $buffer = ob_get_contents();
            ob_end_clean();
            
            return $buffer;
        }
        else
        {
            
        }
        
    }

    public function show_exception($exception)
    {
        ini_set('display_errors', 0);

        // ZPAMS-FIX (2026-09): this handler previously discarded $exception entirely --
        // no log_message() call anywhere in this method -- so a 500 error left literally
        // no trace in any log file (CI's error log, Apache's error.log, or php_error_log
        // all stayed silent). Logging it here is required to be able to diagnose any
        // future 500 without re-adding this by hand each time.
        log_message('error', get_class($exception).': '.$exception->getMessage()
            .' in '.$exception->getFile().' on line '.$exception->getLine()
            ."\nStack trace:\n".$exception->getTraceAsString());

        $templates_path = config_item('error_views_path');
        if (empty($templates_path))
        {
            $templates_path = VIEWPATH.'templates/errors'.DIRECTORY_SEPARATOR;
        }

        // $message = $exception->getMessage();
        if (empty($message))
        {
            $message = '(null)';
        }

        if (is_cli())
        {
            $templates_path .= DIRECTORY_SEPARATOR;
        }
        else
        {
            set_status_header(500);
            $templates_path .= DIRECTORY_SEPARATOR;
        }

        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }


        if( $_SERVER['REQUEST_METHOD'] == 'GET')
        {
            ob_start();
            include($templates_path.'header.php');
            include($templates_path.'errorpage'.DIRECTORY_SEPARATOR.( (@$template <> '') ? $template : 'error_general').'.php');
            include($templates_path.'footer.php');
            $buffer = ob_get_contents();
            ob_end_clean();
          
            echo $buffer;
        }
        else
        {

        }
    }

    /**
     * Native PHP error handler - Override
     *
     * @param   int $severity   Error level
     * @param   string  $message    Error message
     * @param   string  $filepath   File path
     * @param   int $line       Line number
     * @return  string  Error page output
     */
    public function show_php_error($severity, $message, $filepath, $line)
    {
        // ZPAMS-FIX (2026-09): this handler (the one actually rendering the branded
        // "Internal Server Error" page seen for the preauth "View Details" 500) also had
        // no log_message() call anywhere -- and immediately below, the real $message is
        // overwritten with the literal string "Internal Server Error" before display, so
        // even display_errors=1 would never have shown the real cause. Log the ORIGINAL
        // values before anything overwrites them.
        $severityLabel = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;
        log_message('error', "PHP {$severityLabel}: {$message} in {$filepath} on line {$line}");

        // ZPAMS-FIX (2026-09): this method used to unconditionally ob_end_clean() the
        // current output buffer and echo a full replacement "500 Internal Server Error"
        // page for ANY reported PHP issue -- including plain E_WARNING/E_NOTICE, which are
        // not fatal and were never meant to stop the page from rendering (PHP itself keeps
        // executing right past them). That meant a single harmless "Undefined array key"
        // warning anywhere on a page -- extremely common across this codebase now running
        // on PHP 8.2 -- silently destroyed the real page content and replaced it with this
        // branded error page, with the true cause visible nowhere. Only genuinely fatal
        // severities (matching the same bitmask CI's own _error_handler in
        // system/core/Common.php uses to decide whether to halt execution) should get the
        // full-page treatment; everything else is just logged above and execution
        // continues normally, exactly as PHP's default (non-overridden) behavior would.
        $isFatal = ((E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR) & $severity) === $severity;
        if ( ! $isFatal)
        {
            return;
        }

        ini_set('display_errors', 0);
        $templates_path = '';
        $phperror_ = true;
        $heading = "Internal Server Error";
        $message =  $heading;
        if (empty($templates_path))
        {
           $templates_path = VIEWPATH.'templates/errors'.DIRECTORY_SEPARATOR;
        }

        $severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;
        $template = 'error_php';
        ob_end_clean();
        if (ob_get_level() > $this->ob_level + 1)
        {
            ob_end_flush();
        }
        
        if( $_SERVER['REQUEST_METHOD'] == 'GET')
        {
            ob_start();
            include($templates_path.'header.php');
            include($templates_path.'errorpage'.DIRECTORY_SEPARATOR.( (@$template <> '') ? $template : 'error_general').'.php');
            include($templates_path.'footer.php');
            $buffer = ob_get_contents();
            ob_end_clean();
          
            echo $buffer;
        }
        else
        {

        }

    }

    

} 

