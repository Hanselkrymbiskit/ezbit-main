<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#Renamed
class Useractivitylogs extends MY_Controller {

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

    function index()
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
            'global/vendor/formvalidation/formValidation'
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/bootstrap-clockpicker.min',
            'global/vendor/formatter/jquery.formatter',
            'global/vendor/formvalidation/formValidation.min',
            'global/vendor/formvalidation/framework/bootstrap4.min',
            'global/vendor/jquery-labelauty/jquery-labelauty',
        );

        $this->data['loadjschild'] = array(
             'global/js/Plugin/bootstrap-datepicker',
             'global/js/Plugin/clockpicker',
             'global/js/Plugin/formatter',
             'global/js/Plugin/jquery-labelauty',

        );

        $pageheaderaction = '';
        $this->data['title'] = "User Activity Logs"; 
        $this->data['PageTitle'] = "User Activity Logs";       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'useractivitylogs_content/index',  $this->data,'',1); 
      
    }

    function list_activityhistory()
    {
        ob_end_clean();   
        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache");
        header("Content-type: application/json");

        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        
        try
        {
            $DataTableConfig = $_SESSION['DataTables'][$_REQUEST['TableID']];

            $table = '
            (select 
            a.id,
            a.userid,
            a.datetime,
            a.type,
            a.action,
            a.description,
            a.remarks,
            vu.User_Name as username
            from user_activity a 
            join v_001_user_infostatus vu on vu.User_ID = a.userid) as vtable'; // target table = normal table, view table, virtual table
          
            // Table's primary key
            $primaryKey = 'id'; // Primary Key of the Table for normal table, 


            // $sql_details = array(
            //     'user' =>$this->db->username,
            //     'pass' => $this->db->password,
            //     'db'   => $this->db->database,
            //     'host' => $this->db->hostname
            // );

            /* Create Where Clauses for SQL Statement */
            $aliasParam = array(); // Used to replace custom Search Variable to the target column field
            $specialParam = array(); 
            $whereResult = $this->datatables->customWhereStatement($_REQUEST,@$aliasParam,@$specialParam);
            $groupBy = '';

            // Additional Default Filter - Start
            $DefaultFilter = ""; //" userid = ".((int) @$userid);


            // Additional Default Filter - End
            if( $whereResult <> '' )
            {
                $whereResult = " (".$whereResult.") ";
            }

            $whereResult .= (trim($whereResult) <> '') ? ( (@$DefaultFilter <> '') ? ' and '.$DefaultFilter : '') : @$DefaultFilter;


            /* Remove in Final Post Parameter */
            $unsetArray = array('addedFilter','addedFilterMode','StrictSearchMode');
            foreach($unsetArray as $unsetkeys)
            {
                if( isset($_REQUEST[ $unsetkeys]) ){ unset($_REQUEST[$unsetkeys]); }
            }     

            // use to override datatable return function call
            $overRideFunctionCall = array(
                'action' =>  function( $d, $row, $fieldid, $TargetTableID,$colid ) {
                  return strip_tags($d);
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

            // DO any processing here

            // Return Data as Json Format
            echo json_encode($DataReturn);
        }
        catch(Exception $err)
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        

    }
   
}