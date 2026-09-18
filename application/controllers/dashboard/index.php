<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

	function __construct()
	{
		 // phpinfo();
        parent::__construct();
        $this->data['pagetype'] = "dashboard";
        $this->breadcrumbs->push('<i class="fa fa-chart-bar"></i>&nbsp;Dashboard', '/dashboard');
        
        if( $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
            {
               if( !$this->tank_auth->is_logged_in() )
               {
                header("HTTP/1.1 401 Unauthorized");
                exit;
               }
               else
               {
                  $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                  echo json_encode($return);
                  die();
               }
            }
            else
            {
                unset($_POST['UToken']);



                
            }      
        }

        $this->load->model('dashboard/m_dashboard');

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
            'global/vendor/chart-js/package/dist/chart.umd',
            'global/vendor/chart-js/chartjs-plugin-datalabels.min',
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

        $this->data['pageHeader'] = false;
        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
                <button type="button" id="btn_New" name="btnActionBtn" class="btn btn-dark" style="" onclick="newaccount(1)"><i class="fa fa-plus fa-fw"></i>&nbsp;NEW APPLICATION</button>                
            </div>     
        ';
        $pageheaderaction = '';


        switch( (int) $this->userclassification )
        {
            case 1:
            case 6:
            case 7:
                $flderindex = 'healthfacility';
            break;

            case 2:
            case 3:
                $flderindex = 'pro';
            break;

            default:
                 $flderindex = 'central';
            break;
        }



        $this->data['title'] = "Dashboard"; 
        $this->data['PageTitle'] = "";   
        $this->data['pageheaderaction'] = @$pageheaderaction;



        $this->load->template('pages/dashboard/'.$flderindex.'/index',  $this->data,'',1); // this will load the view file    
    } 

    function getStats($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Process Request'
        );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal)
        {
            $return = array('Status' => 0, 'Message' => 'Permission Denied!');
            goto exit_function_here;
        }

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
          $SubmittedParam = $_POST;
          $SubmittedFiles = (count($_FILES) > 0) ? $_FILES : '';
        }
        
        if($internal)
        {
          $SubmittedParam = $formparameter;
          $SubmittedFiles = '';
        }

        try
        {
            $return['Status'] = 1;
            $return['Message'] = "";

            $return['Data']['AppsStatus'] = $this->m_dashboard->stats_appstatus();
            $return['Data']['LocationStatus'] = $this->m_dashboard->stats_appstatus_location();
            $return['Data']['SystemGroup'] = $this->m_dashboard->stats_appstatus_systemgroup();
            $return['Data']['OfficeOrigin'] = $this->m_dashboard->stats_appstatus_officeorigin();
            
        }
        catch (PDOException $e) {
          log_message("error",__METHOD__." | ".$e->getMessage());
        } catch (Exception $e) {
          log_message("error",__METHOD__." | ".$e->getMessage());
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