<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#Renamed
class Index extends MY_Controller {

    public $adminpageDir = 'templates/admin/pages/';

	function __construct()
	{
		parent::__construct();
        $this->data['PageName'] = get_class($this);
        $this->data['adminMenu'] = true;
        if((int) $this->usertype > 3)
        {
            if( $_SERVER['REQUEST_METHOD'] == 'POST' )
            {
                 $return = array(
                    'Status'  => 0,
                    'Message' => "Unauthorized Access Detected!",
                );
                // ZPAMS-FIX (2026-09): was `return json_encode($return);`, which does NOT stop
                // execution here -- CodeIgniter invokes the requested method regardless of what a
                // constructor returns, so this unauthorized-access check was silently bypassed.
                // Changed to echo + exit so the block actually halts the request.
                echo json_encode($return);
                exit;
            }
            else
            {            
                $ulog = array(
                    'type'=>'Administrator',
                    'action'=>'Accessing Administrator Panel',
                    'description'=>'',
                    'remarks'=>'Unauthorized Access Detected!'
                  );  
                $this->write_useractivitylog($ulog);
                redirect(site_url());
            }
        }

	}

    function index()
    {
        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
             
                       
            </div>     
        ';

        $this->data['title'] = "Dashboard"; 
        $this->data['PageTitle'] = "Dashboard";       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'dashboard_content/index',  $this->data,'',1);     
    } 

    function getstorageinfo($internal = false, $formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => ''
        );
       
        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal)
        {
          $return = array('Status' => 0, 'Message' => 'Permission Denied!');
          goto exit_function_here;
        }

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
          if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
          {
            $return = array('Status' => 0, 'Message' => 'Permission Denied!');
            goto exit_function_here;
          }
          else
          {
            unset($_POST['UToken']);
            $SubmittedParam = $_POST;
            $SubmittedFiles = (count($_FILES) > 0) ? $_FILES : '';
          }
        }
        
        if($internal)
        {
          $SubmittedParam = $formparameter;
          $SubmittedFiles = '';
        }

        try
        {
            $appDrive = FCPATH;
            $appDrive = explode(":\\",FCPATH);
            $appDrive = $appDrive[0];

            $return['Status'] = 1;
            $return['Message'] = [
                'appsSize' => $this->myutilities->HumanSize($this->myutilities->dirSize(FCPATH),true,true),
                'appsdrive_freespace' => '', //$this->myutilities->HumanSize(disk_free_space(@$appDrive.":"),true,true)
            ];
        }
        catch(Exception $err)
        {
            log_message("error",'error loading storage info');
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

    function getdatabaseinfo($internal = false, $formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => ''
        );
       
        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal)
        {
          $return = array('Status' => 0, 'Message' => 'Permission Denied!');
          goto exit_function_here;
        }

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
          if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
          {
            $return = array('Status' => 0, 'Message' => 'Permission Denied!');
            goto exit_function_here;
          }
          else
          {
            unset($_POST['UToken']);
            $SubmittedParam = $_POST;
            $SubmittedFiles = (count($_FILES) > 0) ? $_FILES : '';
          }
        }
        
        if($internal)
        {
          $SubmittedParam = $formparameter;
          $SubmittedFiles = '';
        }

        try
        {
            $DBSizeQ = "SELECT  SUM(data_length + index_length) as 'DBSize' FROM information_schema.tables 
            where table_schema = '".$this->defaultDB->database."' GROUP BY table_schema";
            $getDBSize = $this->sqlhelper->local->sql($DBSizeQ)->row();
            $DBSize = $getDBSize['Data']['DBSize'];

            $getD = $this->sqlhelper->local->sql("SHOW VARIABLES WHERE Variable_Name = 'datadir'")->row();
            $dbDataDrive = $getD['Data']['Value'];
            $dbDataDrive = explode(":\\",$dbDataDrive);
            $dbDataDrive = $dbDataDrive[0];

            $return['Status'] = 1;
            $return['Message'] = [
                'dbSize' => $this->myutilities->HumanSize($DBSize,true,true),
                'dbdrive_freespace' => '', //$this->myutilities->HumanSize(disk_free_space(@$dbDataDrive.":"),true,true)
            ];
        }
        catch(Exception $err)
        {
            log_message("error",'error loading database info');
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

    function getusersinfo($internal = false, $formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => ''
        );
       
        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal)
        {
          $return = array('Status' => 0, 'Message' => 'Permission Denied!');
          goto exit_function_here;
        }

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
          if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
          {
            $return = array('Status' => 0, 'Message' => 'Permission Denied!');
            goto exit_function_here;
          }
          else
          {
            unset($_POST['UToken']);
            $SubmittedParam = $_POST;
            $SubmittedFiles = (count($_FILES) > 0) ? $_FILES : '';
          }
        }
        
        if($internal)
        {
          $SubmittedParam = $formparameter;
          $SubmittedFiles = '';
        }

        try
        {
            $this->load->model('administrator/m_useraccount');
            $UserStats = $this->m_useraccount->getUserStatusStatistics();

            $getUsersOnlineQ = "Select count(*) as UsersOnlineCount from (select 
a.userid from users_online a where a.userid <> '".$this->userid."' group by a.userid) as vtbl";
            $UserOnline = $this->sqlhelper->local->sql($getUsersOnlineQ)->row();
            $UserStats[] = ['Status' => 5,'Total'=>$UserOnline['Data']['UsersOnlineCount']];

            $getRegisterCount = "Select count(*) as RegisteredCount from user_register where register_status = '2'";
            $RegisterUser = $this->sqlhelper->local->sql($getRegisterCount)->row();
            $UserStats[] = ['Status' => 6,'Total'=>$RegisterUser['Data']['RegisteredCount']];


            $return['Status'] = 1;
            $return['Message'] = $UserStats;
        }
        catch(Exception $err)
        {
            log_message("error",'error loading database info');
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