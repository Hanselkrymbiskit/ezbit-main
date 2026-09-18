<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#Renamed
class Pagecontent extends MY_Controller {

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

    function contactus()
    {
        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">           
            </div>     
        
        ';

        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/bootstrap-treeview/bootstrap-treeview',
            'global/vendor/jstree/jstree.min',
            'global/vendor/summernote/summernote-lite'
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/bootstrap-clockpicker.min',
            'global/vendor/formatter/jquery.formatter',
            'global/vendor/formvalidation/formValidation.min',
            'global/vendor/formvalidation/framework/bootstrap4.min',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/bootstrap-treeview/bootstrap-treeview.min',         
            'global/vendor/summernote/summernote.min'
            // 'global/vendor/jstree/jstree.min.js',
        );

        $this->data['loadjschild'] = array(
             'global/js/Plugin/bootstrap-datepicker',
             'global/js/Plugin/clockpicker',
             'global/js/Plugin/formatter',
             'global/js/Plugin/jquery-labelauty',
             'global/js/Plugin/bootstrap-treeview',
             'global/js/Plugin/summernote'
             // 'global/js/Plugin/jstree',

        );


        $pageheaderaction = '';

        $this->data['title'] =  "Contact US : Settings"; 
        $this->data['PageTitle'] = "Contact US : Settings";       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'page_content/contactus',  $this->data,'',1);   
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
                    $files = glob($LogDir."/*"); 
                    foreach($files as $file){ // iterate files
                      if(is_file($file)) {
                        unlink($file); // delete file
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
    // function emailloglist()
    // {
    //     if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
    //     {
    //         echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
    //     }
        
    //     try
    //     {
    //         $DataTableConfig = $_SESSION['DataTables'][$_REQUEST['TableID']];

    //         $table = 'system_mailsender_log'; // target table = normal table, view table, virtual table
          
    //         // Table's primary key
    //         $primaryKey = 'DataID'; // Primary Key of the Table for normal table, 

    //         // $sql_details = array(
    //         //     'user' =>$this->db->username,
    //         //     'pass' => $this->db->password,
    //         //     'db'   => $this->db->database,
    //         //     'host' => $this->db->hostname
    //         // );

    //         /* Create Where Clauses for SQL Statement */
    //         $aliasParam = array(); // Used to replace custom Search Variable to the target column field
    //         $specialParam = array(); 
    //         $whereResult = $this->datatables->customWhereStatement($_REQUEST,@$aliasParam,@$specialParam);

    //         $groupBy = '';

    //         /* Remove in Final Post Parameter */
    //         $unsetArray = array('addedFilter','addedFilterMode','StrictSearchMode');
    //         foreach($unsetArray as $unsetkeys)
    //         {
    //             if( isset($_REQUEST[ $unsetkeys]) ){ unset($_REQUEST[$unsetkeys]); }
    //         }     

    //         // use to override datatable return function call
    //         // _crud_view, _crud_edit, _crud_delete
    //         $overRideFunctionCall = array(
    //             'MailStatus' => function($d, $row, $fieldid, $TargetTableID,$colid){
    //                 switch($d)
    //                 {
    //                     case 2:
    //                             $d = '<i title="Successfully Sent" class="fa fa-fw fa-2x fa-envelope text-success"></i>';
    //                         break;
    //                     case 1:
    //                             $d = '<i title="Failed to Send Email" class="fa fa-fw fa-2x fa-envelope text-danger"></i>';
    //                         break;
    //                     default:
    //                             $d = '<i title="For Sending" class="fa fa-fw fa-2x fa-envelope text-warning"></i>';
    //                         break;
    //                 }
    //                 return $d; //$d;
    //             }                         
    //         );
            

    //         // normally used for data return alteration
    //         $addedColumnProperties = array(
    //             //  'User_Password' =>  function( $d, $row, $fieldid, $TargetTableID,$colid ) {
    //             //   return $d;
    //             // }, 
    //         );

    //         $cparam = array(
    //             'TargetTableID'          => $_REQUEST['TableID'],
    //             'TablePrimaryKey'        => $primaryKey,
    //             'FunctionOverride'       => $overRideFunctionCall,
    //             'AddedColumnProperties'  => $addedColumnProperties
    //         );

    //         $ColumnProperties = $this->datatables->columnProperties($cparam);

    //         // Return Data as Json Format
    //         $DataReturn = Datatables::complex( $_REQUEST, @$sql_details, $table, $primaryKey, $ColumnProperties, $whereResult, NULL ,@$groupBy,$_REQUEST['TableID'] ) ;

    //         // DO any processing here
    //         // Get Aggregate User Status
    //         $this->load->model('maillogs/m_maillogs');
    //         $DataReturn['MailLogs'] = $this->m_maillogs->getMailLogStat();

    //         // Return Data as Json Format
    //         echo json_encode($DataReturn);
    //     }
    //     catch(Exception $err)
    //     {
    //         echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
    //     }
        

    // }

    // function getFailedMail()
    // {
    //     $return = array('Status' => 0, 'Message' => 'Permission Denied!');   
    //     // log_message("error",$_REQUEST);
    //     if( $_SERVER['REQUEST_METHOD'] == 'POST')
    //     {
            
    //         if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
    //         {
    //            $return = array('Status' => 0, 'Message' => 'Permission Denied!');   
    //            goto exit_function_here;
    //         }

    //         unset($_POST['UToken']);
        
    //         try
    //         {

    //             $this->load->model('maillogs/m_maillogs');
    //             $mlogs = $this->m_maillogs->getFailedMail();
    //             $return['Status'] = 1;
    //             $return['Message'] = $mlogs;

    //         }catch(Exception $e)
    //         {
    //             log_message("error",$_POST);
    //         }

    //     }

    //     exit_function_here:
    //     echo json_encode($return);
    // }

    // function resendmail()
    // {
    //     $return = array('Status' => 0, 'Message' => 'Permission Denied!');   
    //     // log_message("error",$_REQUEST);
    //     if( $_SERVER['REQUEST_METHOD'] == 'POST')
    //     {
            
    //         if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
    //         {
    //            $return = array('Status' => 0, 'Message' => 'Permission Denied!');   
    //            goto exit_function_here;
    //         }

    //         unset($_POST['UToken']);
        
    //         try
    //         {
    //             $Param = $_POST;

    //             $this->load->model('maillogs/m_maillogs');
    //             $maildata = $this->m_maillogs->getMailData($Param['DataID']);
    //             if(is_array($maildata))
    //             {
    //                 $param = array(
    //                   'from'    => $this->config->item('email_sender'),
    //                   'to'      => $maildata['sToEmail'],
    //                   'subject' => $maildata['sSubject'],
    //                   'message' => base64_decode($maildata['sMail']),
    //                   'cc'      => $maildata['sCcEmail'],
    //                   'bcc'     => $maildata['sBccEmail'],
    //                   'attach'  => ($maildata['sAttachmentDir'] <> '') ? base64_decode(jseon_decode($maildata['sAttachmentDir'])) : '',
    //                 );
   
    //                 $this->load->model('sendmail/sendmail');
    //                 $resendResult = $this->sendmail->send_mail($param,false,true,true);

    //                 if( $resendResult == 1 )
    //                 {
    //                     $updateMail = array(
    //                             'MailStatus' => 2,
    //                             'DateTimeSend' => date('Y-m-d H:i:s'),
    //                             'DateTimeFailedSend' => '',
    //                             'ErrorMessage' => '',
    //                         );

    //                     $UpdateLog = $this->sqlhelper->local->update("system_mailsender_log")->ex_update($updateMail)->where("DataID = ".(int) $Param['DataID'])->run();

    //                     $return['Status'] = 1;
    //                 }
    //                 else
    //                 {
    //                     $updateMail = array(
    //                             'MailStatus' => 1,
    //                             'DateTimeFailedSend' => date('Y-m-d H:i:s'),
    //                         );

    //                     $UpdateLog = $this->sqlhelper->local->update("system_mailsender_log")->ex_update($updateMail)->where("DataID = ".(int) $Param['DataID'])->run();
    //                 }
    //             }

    //         }catch(Exception $e)
    //         {
    //             log_message("error",$_POST);
    //         }

    //         exit_function_here:
    //         echo json_encode($return);
    //     }
    //     else
    //     {
    //         redirect('');
    //     }
        
    // }
   
}