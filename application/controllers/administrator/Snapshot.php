<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#Renamed
class Snapshot extends MY_Controller {

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
                    'action'=>'Requesting Client Window Snapshot',
                    'description'=>'',
                    'remarks'=>'Unauthorized Access Detected!'
                  );  
                $this->write_useractivitylog($ulog);
                redirect(site_url());
            }
        }

	}

    function requestsnapshot($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Process - Request Client Window Snapshot!'
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
            $timeoutseconds = 25000; 
            $timestamp = time();
            $timeout = $timestamp-$timeoutseconds;

            $deletePreviousRequest = $this->sqlhelper->local->delete('user_clientsnapshot')->where(" `timestamp` < '".$timeout."' and approved_datetime is null and user_id = '".$SubmittedParam['user_id']."'")->run();

            $insertRequest = [
                'requested_by'          => $this->userid,
                'requested_datetime'    => date("Y-m-d H:i:s"),
                'user_id'               => $SubmittedParam['user_id'],
                'timestamp'             =>$timestamp,
                'requestip'             =>$_SERVER["REMOTE_ADDR"],
            ];

            $insertRequest = $this->sqlhelper->local->insert('user_clientsnapshot')->ex_insert($insertRequest)->run();
            if( $insertRequest['ErrorCode'] == "" )
            {
                $return['Status'] = 1;
                $return['Message'] = 'Request for Client Window Snapshot Successfully Process, Please wait for the Client Response.';
                $return['SnapshotID'] = $insertRequest['Data'];
            }

        }catch (PDOException $e) {
            log_message("error",$e->getMessage());
        }catch (Exception $e) {
            log_message("error",$e->getMessage());
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

    function getsnapshot($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Process - Request Client Window Snapshot!'
        );
        // log_message("error","GET :".json_encode($_POST));
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
            $getSnapshot = $this->sqlhelper->local->select("user_clientsnapshot")->where("user_id = '".$SubmittedParam['user_id']."' and id='".$SubmittedParam['SnapshotID']."' ")->ex_select('','requested_datetime desc','1');
            // log_message("error",$getSnapshot->rtrnSql());
             $getSnapshot =  $getSnapshot->row();
            if( $getSnapshot['Count'] > 0 )
            {
                if($getSnapshot['Data']['snapshotstatus'] == 1)
                {
                    $return['Status'] = 1;
                    $return['Message'] = 'Snapshot Successfully Loaded';
                    $return['SnapshotImage'] = base64_encode($getSnapshot['Data']['snapshot']);
                    $return['SHeight'] = $getSnapshot['Data']['snapshotHeight'];
                    $return['SWidth'] = $getSnapshot['Data']['snapshotWidth'];
                }
                else
                {
                    $return['Message'] = 'Request for Client Snapshot Disapproved!';
                }
                
            }
            else
            {
                $return['Message'] = 'Request for Client Snapshot Disapproved or No Response from the User!';
            }


        }catch (PDOException $e) {
            log_message("error",$e->getMessage());
        }catch (Exception $e) {
            log_message("error",$e->getMessage());
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