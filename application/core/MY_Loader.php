<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * custom loader file extends CI_Loader
 */
#[AllowDynamicProperties]
class MY_Loader extends CI_Loader {
    
    function __construct() {
        parent::__construct();
    }

    /* Template = default, admin, api */
    function template($viewfile, $vars = array(), $return = FALSE,$HeaderFooter = TRUE,$templateMode = 'default')
    {
        $HeaderFooter = ( is_bool($HeaderFooter) ) ? $HeaderFooter : ( ($HeaderFooter == 1) ? TRUE : FALSE );
        $return = (!is_bool($return)) ? FALSE : $return;
        $paramCount = func_num_args(); 
        $viewFileList = [];
        $templates = 'templates';
        switch($templateMode)
        {
            case 'admin':
                $templates = 'templates';
                break;

            case 'api':
                $templates = 'webservice/template';
                break;
        }

        if( !is_array($viewfile) )
        {
            $viewFileList[] = $viewfile;
        }

        $vars = (!is_array($vars)) ? array() : $vars;
        $vars['title'] = @$vars['title'];
        $vars['headerfooter'] = $HeaderFooter;
        $content = '';

        ($return) ? $content .= $this->view($templates.'/header', $vars, $return) :   $this->view($templates.'/header', $vars, $return) ;
        
        foreach($viewFileList as $pathViewFile )
        {
            if( !file_exists(APPPATH.'views'.DIRECTORY_SEPARATOR.$pathViewFile.".php") )
            { 
                show_404();
                break;
            } 
            else
            {
                ($return) ? $content .= $this->view($pathViewFile, $vars, $return) :   $this->view($pathViewFile, $vars, $return) ;
            }
        }   

        ($return) ? $content .= $this->view($templates.'/footer', $vars, $return) :   $this->view($templates.'/footer', $vars, $return) ;


        if($return)
        {
            
            return $content;
        }

    }


    function templateAdmin($viewfile, $vars = array(), $return = FALSE,$HeaderFooter = TRUE)
    {
        $this->template($viewfile,$vars,$return,$HeaderFooter);
    }

    function apitemplate($viewfile, $vars = array(), $return = FALSE,$HeaderFooter = TRUE)
    {
        $this->template($viewfile,$vars,$return,$HeaderFooter,'api');
    }
}