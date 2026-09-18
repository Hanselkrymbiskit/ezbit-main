<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#Renamed
class Systemsecurity extends MY_Controller {

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

   
    function bannedip($mode = '')
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

        $Title = 'Banned IP';


        $this->data['title'] =  $Title; 
        $this->data['PageTitle'] = $Title;       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'systemsecurity_content/index',  $this->data,'',1);   
    }

    function getbannedip($internal=false,$formparameter = '')
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
            $BanFileName = 'BanIPList.txt';
            $BanFilePath = FCPATH."BanIPList.txt";
            $BanIPFilePath = FCPATH."BanIP.txt";
            
            if(file_exists($BanFilePath)) 
            {
                $BanIPList = file_get_contents($BanFilePath);
            }

            $BanIPList = ($BanIPList <> '') ? json_decode($BanIPList,TRUE) : [];

            $return['Status'] = 1;
            $return['Message'] = '';
            $return['BanIPList'] = $BanIPList;

        }catch (PDOException $e) {
          log_message("error","Controller : ".__METHOD__." : ".$e->getMessage());
        } catch (Exception $e) {
          log_message("error","Controller : ".__METHOD__." : ".$e->getMessage());
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
                'type'=>'Retrieve Ban IP List',
                'action'=>@$return['Message'],
                'description'=>'',
                'remarks'=>''
              );

        $this->write_useractivitylog($ulog);
    }

    function detectedip($mode = '')
    {
        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
             
                <button type="button" id="btn_MovetoBannedList" name="btn_MovetoBannedList" class="btn btn-danger" style="margin-right:5px"><i class="fas fa-ban fa-fw mr-5"></i>Move All IP to Banned List</button>
                           
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

        $Title = 'Detected Suspicious IP';


        $this->data['title'] =  $Title; 
        $this->data['PageTitle'] = $Title;       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'systemsecurity_content/detected',  $this->data,'',1);   
    }

    function maxloginattempt($mode = '')
    {
        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
             
                <button type="button" id="btn_MovetoBannedList" name="btn_MovetoBannedList" class="btn btn-danger" style="margin-right:5px"><i class="fas fa-ban fa-fw mr-5"></i>Move All IP to Banned List</button>
                           
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

        $Title = 'Max-Login Attempt';

        $this->data['title'] =  $Title; 
        $this->data['PageTitle'] = $Title;       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'systemsecurity_content/maxloginattempt',  $this->data,'',1);   
    }

    function datalist_maxlogin()
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        
        try
        {
            $DataTableConfig = $_SESSION['DataTables'][$_REQUEST['TableID']];

            

            $table = "(
                    select  
                       a.`id` as 'mId',
                       a.ip_address as 'ip_address',
                       a.login as 'login',
                       a.`time` as 'mTime'
                    from system_login_attempts a 
                    ) as ssystem_login_attempts"; // target table = normal table, view table, virtual table
          
            // Table's primary key
            $primaryKey = 'mId'; // Primary Key of the Table for normal table, 

            $proconn = $this->m_general->conProCode();
            if($proconn == 'local')
            {
                $proconn = 'defaultDB';
            }

            $sql_details = array(
                'user' => $this->{$proconn}->username,
                'pass' => $this->{$proconn}->password,
                'db'   => $this->{$proconn}->database,
                'host' => $this->{$proconn}->dsn,
                'encrypt' => @$this->CI->{$proconn}->encrypt
            );

            /* Create Where Clauses for SQL Statement */
            $aliasParam = array(); // Used to replace custom Search Variable to the target column field
            $specialParam = array(); 
            $whereResult = $this->datatables->customWhereStatement($_REQUEST,@$aliasParam,@$specialParam);
            $groupBy = '';

            // Additional Default Filter - Start
            $DefaultFilter = "";
            $StatsFilter = "";
            // if( isset($_POST['pushparameter']['DataSource']) && $_POST['pushparameter']['DataSource'] <> '' )
            // {
            //     if( $_POST['pushparameter']['DataSource'] == 2)
            //     {
            //         $DefaultFilter = " PRORegion = '".$this->userregistrationinfo['ProRegion']."' or Created_By = ".$this->userid;
            //     }
            //     else
            //     {
            //         $DefaultFilter = " DataSource ='1' ";
            //     }
            // }
            // Additional Default Filter - End
            
            $whereResult .= (trim($whereResult) <> '') ? ( (@$DefaultFilter <> '') ? ' and '.$DefaultFilter : '') : @$DefaultFilter;


            /* Remove in Final Post Parameter */
            $unsetArray = array('addedFilter','addedFilterMode','StrictSearchMode');
            foreach($unsetArray as $unsetkeys)
            {
                if( isset($_REQUEST[ $unsetkeys]) ){ unset($_REQUEST[$unsetkeys]); }
            }     

            // use to override datatable return function call
            $overRideFunctionCall = array(
                 
                'mId_crud_view' => function($d, $row, $fieldid, $TargetTableID,$colid){

                    $DataID = base64_encode((int) $row['mId']); //$this->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],$row['rowid']);
                    $d = '<i class="fa fa-search fa-fw text-success" title="View Details" id="'.bin2hex($DataID).'"  name="'.bin2hex($DataID).'" group="'.$TargetTableID.'" role="button"></i>';
                    return $d; 

                },      

                // 'mId_rowclass' => function($d, $row, $fieldid, $TargetTableID,$colid){
                  
                //     return $d; 

                // },    
                // 'DataID_crud_edit' => function($d, $row, $fieldid, $TargetTableID,$colid){

                //     $DataID = base64_encode($this->encryption->encrypt($row['ReferenceNo']));
                //     $d = '<a href="javascript:void(0);" class="" title="Edit Details" id="ed_'.$row['DataID'].'"  name="ed_'.$row['DataID'].'" onclick="window.location.href=\''.(site_url("applicationsystem/index/edit/".$DataID)).'\'"><i class="fa fa-edit fa-fw"></i></a>';

                //     if( $row['Created_By'] <> $this->userid )
                //     {
                //         $d = ( (int) $this->usertype > 3 ) ? '' : $d;
                //     }

                //     return $d; 
                // },  
 
                'mTime' => function($d, $row, $fieldid, $TargetTableID,$colid){
                   
                    return date("m/d/Y h:i:s A",strtotime($d)); 

                },      

            );
            

            // normally used for data return alteration
            $addedColumnProperties = array(
                //  'User_Password' =>  function( $d, $row, $fieldid, $TargetTableID,$colid ) {
                //   return $d;
                // }, 
            );

            $cparam = array(
                'TargetTableID'          => $_REQUEST['TableID'],
                'TablePrimaryKey'        => $primaryKey,
                'FunctionOverride'       => $overRideFunctionCall,
                'AddedColumnProperties'  => $addedColumnProperties
            );

            $ColumnProperties = $this->datatables->columnProperties($cparam);

            // Return Data as Json Format
            $DataReturn = Datatables::complex( $_REQUEST, @$sql_details, $table, $primaryKey, $ColumnProperties, $whereResult, NULL ,@$groupBy,$_REQUEST['TableID'] ) ;


            // Return Data as Json Format
            echo json_encode($DataReturn);
        }
        catch(Exception $err)
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
    }
}