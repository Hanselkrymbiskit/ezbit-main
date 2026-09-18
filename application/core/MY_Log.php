<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * custom loader file extends CI_Loader
 */
#[AllowDynamicProperties]
class MY_Log extends CI_Log {
    
    protected $_threshold = 1;
    protected $_threshold_array = array();
    protected $_levels = array('ERROR' => 1, 'DEBUG' => 2, 'INFO' => 3, 'EMAIL' => 4,'DB' => 5, 'ALL' => 6, 'UDEBUG'=>7);
    public $CI;
    function __construct() {
        parent::__construct();

        //$this->CI = & get_instance();
        // $this->write_log("info", $_SERVER['REQUEST_URI']);
       //$x = $this->CI->defaultDB->hostname;
    }
    

    /**
     * Write Log File
     *
     * Generally this function will be called using the global log_message() function
     *
     * @param   string  $level  The error level: 'error', 'debug' or 'info'
     * @param   string  $msg    The error message
     * @return  bool
     */
    public function write_log($level, $msg)
    {
        
        if( str_replace(str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']),"",$_SERVER['REQUEST_URI']) == "utilities/getTime" )
        {
            return FALSE;
        }
        else
        {
            if ($this->_enabled === FALSE)
            {
                return FALSE;
            }
            
            $level = strtoupper($level);

            if( !array_key_exists(6, $this->_threshold_array)  )
            {
                if (    ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
                && ! isset($this->_threshold_array[$this->_levels[$level]])            )
                {
                   
                    return FALSE;
                }
            }

            $directoryLog = strtolower($level).'_log/';
            if ( !file_exists($this->_log_path.$directoryLog) ) 
            {
                mkdir ($this->_log_path.$directoryLog."/", 0777); 
            } 

            $daydirectory = date('Y-m-d');
            if ( !file_exists($this->_log_path.$directoryLog.$daydirectory) ) 
            {
                mkdir ($this->_log_path.$directoryLog.$daydirectory."/", 0777); 
            } 
      
            $filepath = $this->_log_path.$directoryLog.$daydirectory.'/log-'.date('Y-m-d-H').'.'.$this->_file_ext;
            $message = '';

            if ( ! file_exists($filepath))
            {
                $newfile = TRUE;
                // Only add protection to php files
                if ($this->_file_ext === 'php')
                {
                    $message .= "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>\n\n";
                }
            }

            if ( ! $fp = @fopen($filepath, 'ab'))
            {
                return FALSE;
            }

            flock($fp, LOCK_EX);

            // Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
            if (strpos($this->_date_fmt, 'u') !== FALSE)
            {
                $microtime_full = microtime(TRUE);
                $microtime_short = sprintf("%06d", ($microtime_full - floor($microtime_full)) * 1000000);
                $date = new DateTime(date('Y-m-d H:i:s.'.$microtime_short, $microtime_full));
                $date = $date->format($this->_date_fmt);
            }
            else
            {
                $date = date($this->_date_fmt);
            }

            $message .= $this->_format_line($level, $date, ( (is_array($msg)) ? print_r($msg,TRUE) : $msg ));

            for ($written = 0, $length = self::strlen($message); $written < $length; $written += $result)
            {
                if (($result = fwrite($fp, self::substr($message, $written))) === FALSE)
                {
                    break;
                }
            }

            flock($fp, LOCK_UN);
            fclose($fp);

            if (isset($newfile) && $newfile === TRUE)
            {
                chmod($filepath, $this->_file_permissions);
            }

            return is_int($result);
        }

        
    }

}