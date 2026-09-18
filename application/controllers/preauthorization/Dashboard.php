<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	function __construct()
	{
		// phpinfo();
        parent::__construct();
        $this->data['pagetype'] = "Pre-Authorization-Dashboard";
        $this->breadcrumbs->push('<i class="fa fa-wpforms"></i>&nbsp;Pre-Authorization Dashboard', '/preauthorization/dashboard');

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

        $this->load->model('preauthorization/m_preauth');
        $this->load->model('preauthorization/m_preauth_reports');
        $this->load->model('dashboard/m_dashboard');

	}

    function rpt_monthlyrequest($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => ''
        );

        if( ($_SERVER['REQUEST_METHOD'] == 'POST' && $internal) || in_array((int) $this->userclassification,$this->m_general->HFUsers))
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
            $rpt_data = $this->m_dashboard->rpt_monthlyrequest();
            $return['Status'] = 1;
            $return['Message'] = $rpt_data;
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