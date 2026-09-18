<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#[AllowDynamicProperties]
class My_Router extends CI_Router {
    
    public function __construct($routing = NULL) {
        parent::__construct($routing);
    }

    protected function _set_default_controller() 
    {
        if (empty($this->default_controller)) {
            show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
        }

        // Is the method being specified?
        if (sscanf($this->default_controller, '%[^/]/%[^/]/%s', $directory, $class, $method) !== 3) {
            $method = 'index';
        }
        if (is_dir(APPPATH . 'controllers' . DIRECTORY_SEPARATOR . $directory) === true) {

            if (!file_exists(APPPATH . 'controllers' . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . ucfirst($class) . '.php')) {
                // This will trigger 404 later
                return;
            }
            $this->set_directory($directory);
            $this->set_class($class);
            $this->set_method($method);
        } else {
            if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2) {
                $method = 'index';
            }
            if (!file_exists(APPPATH . 'controllers' . DIRECTORY_SEPARATOR . ucfirst($class) . '.php')) {
                // This will trigger 404 later
                return;
            }
            $this->set_class($class);
            $this->set_method($method);
        }
        // Assign routed segments, index starting from 1
        $this->uri->rsegments = array(
            1 => $class,
            2 => $method
        );

        log_message('udebug', 'No URI present. Default controller set.');
   }

    protected function _validate_request($segments)
    {
        $c = count($segments);
        $directory_override = isset($this->directory);
        
        // Loop through our segments and return as soon as a controller
        // is found or when such a directory doesn't exist
        while ($c-- > 0)
        {
            $test = $this->directory
            .ucfirst($this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[0]) : $segments[0]);

            if (
            ! file_exists(APPPATH.'controllers/'.$test.'.php')
            && $directory_override === FALSE
            && is_dir(APPPATH.'controllers/'.$this->directory.$segments[0])
            )
            {
                $this->set_directory(array_shift($segments), TRUE);
                // Addedd - Start
                while(count($segments) > 0 && is_dir(APPPATH.'controllers/'.$this->directory.$segments[0]))
                {
                    $this->set_directory($this->directory . $segments[0]);
                    $segments = array_slice($segments, 1);
                }
                // Added - End
                if(count($segments) == 0 )
                {
                    $segments = array('index');
                }
                continue;
            }

            return $segments;
        }
        // This means that all segments were actually directories
        return $segments;
    }


}