<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#Renamed
class Debugginglogs extends MY_Controller {

    public $adminpageDir = 'templates/admin/pages/';

	function __construct()
	{
		parent::__construct();
        $this->data['PageName'] = get_class($this);
        $this->data['adminMenu'] = true;
        if((int) $this->usertype > 2)
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

    function codes()
    {
        $this->index('code');
    }

    function query()
    {
        $this->index('query');
    }

    function email()
    {
        $this->index('email');
    }
    function index($mode = '')
    {
        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
             
                <button type="button" id="btn_TestMail" name="btnEmailLogsButton" class="btn btn-dark" style="margin-right:5px"><i class="fas fa-envelope fa-fw"></i>&nbsp;Test Send Mail</button>
                <button type="button" id="btnResendFailedMail" name="btnEmailLogsButton" class="btn btn-dark" style=""><i class="fas fa-send fa-fw"></i>&nbsp;Resend Failed Mail</button>                
            </div>     
        
        ';

        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/bootstrap-treeview/bootstrap-treeview',
            'global/vendor/jstree/jstree.min',
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/bootstrap-clockpicker.min',
            'global/vendor/formatter/jquery.formatter',
            'global/vendor/formvalidation/formValidation.min',
            'global/vendor/formvalidation/framework/bootstrap4.min',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/bootstrap-treeview/bootstrap-treeview.min',
            // 'global/vendor/jstree/jstree.min.js',
        );

        $this->data['loadjschild'] = array(
             'global/js/Plugin/bootstrap-datepicker',
             'global/js/Plugin/clockpicker',
             'global/js/Plugin/formatter',
             'global/js/Plugin/jquery-labelauty',
             'global/js/Plugin/bootstrap-treeview',
             // 'global/js/Plugin/jstree',

        );


        $pageheaderaction = '';

        switch($mode)
        {
           case "code":
            $Title =  'Code Error Log';
            $this->data['dir'] = FCPATH.'application/logs/error_log'; 
           break; 

           case "query":
            $Title =  'SQL Statemet Error Log';
            $this->data['dir'] = FCPATH.'application/logs/db_log'; 
           break; 

           case "email":
            $Title =  'Sending Mail Error Log';
            $this->data['dir'] = FCPATH.'application/logs/email_log'; 
           break;

           default: 
            $Title =  'Code Error Log';
            $this->data['dir'] = FCPATH.'application/logs/error_log'; 
            break;
        }


        $this->data['title'] =  $Title; 
        $this->data['PageTitle'] = $Title;       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'debugginglogs_content/index',  $this->data,'',1);   
    }

    function getlogs($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to retrieved Log Data!'
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
            if(isset($SubmittedParam['LogPath']))
            {
                $getC = file_get_contents($SubmittedParam['LogPath']);
                if($getC <> '')
                {
                    $return['Status'] = 1;
                    $return['Message'] = $getC;
                }
            } 
  
        }catch (PDOException $e) {
          log_message("error","Controller : admin/debugginglogs/getlogs : get Log Data : ".$e->getMessage());
        } catch (Exception $e) {
          log_message("error","Controller : admin/debugginglogs/getlogs : get Log Data : ".$e->getMessage());
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

        $ulog = array(
                'type'=>'Retrieve Log Data',
                'action'=>@$return['Message'],
                'description'=>'',
                'remarks'=>''
              );

        $this->write_useractivitylog($ulog);
    }

    function deletealllogs($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Delete Log File Data!'
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
            if(isset($SubmittedParam['DirPath']))
            {
                $LogDir = $this->encryption->decrypt($SubmittedParam['DirPath']);
                if($LogDir <> '')
                {
                    $LogDir = str_replace("\\","/",$LogDir);
                    $files = glob($LogDir."/*"); 
                    
                    foreach($files as $file){ // iterate files
                        if(is_dir($file))
                        {
                            $filetoDelete = glob($file."/*");
                            foreach($filetoDelete as $dfile){
                                chown($dfile, 0777);
                                unlink($dfile); // delete file
                            }
                        }
                        else
                        {
                            chown($file, 0777);
                            unlink($file); // delete file
                        }

                        if(file_exists($file))
                        {
                            rmdir($file);  // delete folder
                        }
                    }

                    $return['Status'] = 1;
                    $return['Message'] = "Log File Successfully Deleted!";
                }
            } 
  
        }catch (PDOException $e) {
          log_message("error","Controller : admin/debugginglogs/getlogs : get Log Data : ".$e->getMessage());
        } catch (Exception $e) {
          log_message("error","Controller : admin/debugginglogs/getlogs : get Log Data : ".$e->getMessage());
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

        $ulog = array(
                'type'=>'Retrieve Log Data',
                'action'=>@$return['Message'],
                'description'=>'',
                'remarks'=>''
              );

        $this->write_useractivitylog($ulog);
    }
   
}