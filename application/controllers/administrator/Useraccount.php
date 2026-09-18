<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#Renamed
use Ramsey\Uuid\Uuid;
class Useraccount extends MY_Controller {

    public $adminpageDir = 'templates/admin/pages/';

	function __construct()
	{
		parent::__construct();
        $this->data['PageName'] = get_class($this);
        $this->data['adminMenu'] = true;
        if((int) $this->usertype > 3)
        {
            if( (int) $this->userclassification == 3 )
            {
                $this->data['adminMenu'] = false;

                if( (int) $this->userclassification == 3 && $this->system_module_permission['4'] <> 1)
                {
                    redirect('home');
                }
            }
            else
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
        else
        {
            if( (int) $this->usertype == 3 )
            {
                $this->data['adminMenu'] = false;
            }
        }

        if( $this->config->item('csrf_protection') )
        {
            if( isset($_POST[$this->config->item('csrf_token_name')]) )
            {
             unset($_POST[$this->config->item('csrf_token_name')]);
            }

            if( isset($_POST[$this->config->item('csrf_cookie_name')]) )
            {
              unset($_POST[$this->config->item('csrf_cookie_name')]);
            }
        }

        if( isset($_REQUEST['ZeQ2c26session']) )
        {
            unset($_REQUEST['ZeQ2c26session']);
            if( isset($_POST['ZeQ2c26session']))
            {
              unset($_POST['ZeQ2c26session']);
            }
        }

	}

    // User Account Page - Start
    // Load User Account Index Page
    function index()
    { 
        $this->data['loadcss'] = array(
          'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
          'global/vendor/clockpicker/clockpicker',
          'global/vendor/jquery-labelauty/jquery-labelauty',
          'global/vendor/formvalidation/formValidation',
          'global/vendor/summernote/summernote-lite',
          'global/vendor/multi-select/multi-select'
        );

        $this->data['loadjsmain'] = array(
          'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
          'global/vendor/clockpicker/bootstrap-clockpicker.min',
          'global/vendor/formatter/jquery.formatter',
          'global/vendor/formvalidation/formValidation.min',
          'global/vendor/formvalidation/framework/bootstrap4.min',
          'global/vendor/jquery-labelauty/jquery-labelauty',
          'global/vendor/summernote/summernote.min',
          'global/vendor/multi-select/jquery.multi-select',
          'js/userfunction/jquery.quicksearch',
          'js/admin/useraccount',
        );

        $this->data['loadjschild'] = array(
           'global/js/Plugin/bootstrap-datepicker',
           'global/js/Plugin/clockpicker',
           'global/js/Plugin/formatter',
           'global/js/Plugin/jquery-labelauty',
           'global/js/Plugin/summernote',
           'global/js/Plugin/responsive-tabs',
           'global/js/Plugin/closeable-tabs',
           'global/js/Plugin/tabs',
           'global/js/Plugin/multi-select'
        );

        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
                <button type="button" id="btn_New" name="btnuseraccountAction" class="btn btn-dark" style="" onclick="newaccount(1)"><i class="fa fa-user-plus fa-fw"></i>&nbsp;New Account</button>                
            </div>     
        ';

        if((int) $this->usertype > 2)
        {
            $pageheaderaction = '';
        }
        

        $this->data['title'] = "User Account"; 
        $this->data['PageTitle'] = "User Account".@$buttonL;       


        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'useraccount_content/index',  $this->data,'',1); 
    }

    function userlist()
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        
        try
        {
            $DataTableConfig = $_SESSION['DataTables'][$_REQUEST['TableID']];

            $table = "
            (select 
                a.*,
                CAST(AES_DECRYPT(FROM_BASE64(a.Account_Name),'".$this->system_settings['PasswordHashing']."') AS CHAR ) as aAccount_Name
            from v_001_user_infostatus a) as vTble"; // target table = normal table, view table, virtual table
          
            // Table's primary key
            $primaryKey = 'User_ID'; // Primary Key of the Table for normal table, 


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
            $DefaultFilter = " User_TypeID > ".((int) $this->usertype);

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
                'User_Name' =>  function( $d, $row, $fieldid, $TargetTableID,$colid ) {
                                      // $d = 'Create Where Clauses for SQL StatementCreate Where Clauses for SQL StatementCreate Where Clauses for SQL StatementCreate Where Clauses for SQL StatementCreate Where Clauses for SQL StatementCreate Where Clauses for SQL StatementCreate Where Clauses for SQL StatementCreate Where Clauses for SQL StatementCreate Where Clauses for SQL StatementCreate Where Clauses for SQL StatementCreate Where Clauses for SQL Statement'; // ucwords(strtolower($d)); //   $d='1'; //
                                      return $d;
                                    },
                'Account_Name' =>  function( $d, $row, $fieldid, $TargetTableID,$colid ) {
                                        $d = ucwords(strtolower($d));
                                      return $d;
                                    },
                'User_Status' =>  function( $d, $row, $fieldid, $TargetTableID,$colid ) {
                                            
                                       switch($d)
                                       {
                                        case 1:
                                            $d = 'Active';
                                            $class = 'success';
                                            break;
                                        case 2:
                                            $d = 'Inactive';
                                            $class = 'info';
                                            break;
                                        case 3:
                                            $d = 'Expired';
                                            $class = 'warning';
                                            break;
                                        case 4:
                                            $d = 'Banned';
                                            $class = 'danger';
                                            break;

                                       } 

                                      return '<span class="badge-outline badge-'.$class.'" style="'.@$style.';padding:5px '.( ($d=='Active') ? '14' : '10').'px;border-radius:5px">'.$d.'</span>';
                                    },     
                'User_Expiration'  =>  function( $d, $row, $fieldid, $TargetTableID,$colid ) {
                                        $d = ($d<>'') ? date('m/d/Y',strtotime($d)) : '';
                                        $d = ($d=='01/01/1970') ? '<i class="fa fa-ban"></i>' : $d;
                                        return $d;
                                    },              
            );
            

            // normally used for data return alteration
            $addedColumnProperties = array(
                 'User_Password' =>  function( $d, $row, $fieldid, $TargetTableID,$colid ) {
                  return $d;
                }, 
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
            // Get Aggregate User Status
            $this->load->model('administrator/m_useraccount');
            $UserStats = $this->m_useraccount->getUserStatusStatistics();
            $DataReturn['UserStats'] = $UserStats;

            // Return Data as Json Format
            echo json_encode($DataReturn);
        }
        catch(Exception $err)
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        

    }

    function viewdetails($userid)
    {
        if( (int) $this->userclassification == 3 && (int)  $this->system_module_permission['5'] <> 1)
        {
            redirect('home');
        }

        $this->data['currentuserid'] = base64_decode(base64_decode($userid));

        $SignOutBtn = '
            <button type="button" id="btn_LogoutAccount" name="btn_LogoutAccount" btnGroup="btnUserDetailsActionButton" class="btn btn-success" style=""><i class="fas fa-sign-out fa-fw"></i>&nbsp;Sign-Out</button>
        ';

        $this->data['signoutbtn'] = $SignOutBtn;

        $userOnlineData = json_decode($this->myutilities->onlineuser($this->data['currentuserid']),TRUE);

        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">

                '.( ( is_array($userOnlineData['Message']) ) ? $SignOutBtn : '').'

                <button type="button" id="btn_BacktoList" name="btn_BacktoList" btnGroup="btnUserDetailsActionButton" class="btn btn-dark ml-5" style=""><i class="fas fa-list fa-fw"></i>&nbsp;User List</button>            
            </div>     
        
        ';

        // $pageheaderaction = '';

        
        $this->data['title'] = "User Account"; 
        
        $this->data['PageTitle'] = "User Account".@$buttonL;       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'useraccount_content/viewdetails',  $this->data,'',1); 
    }

    function getUserStatusStatistics($pageCall = false)
    {
        $return = array(
            'Status'  => 0,
            'Message' => "Unable to Load User Statistics Information",
        );

        if( !$pageCall )
        {
            if( $_SERVER['REQUEST_METHOD'] == 'POST' )
            {
                goto proceed;
            }
            else
            {
                goto labas;
            }
        }

        proceed:
        $this->load->model('administrator/useraccount');
        $stat = $this->useraccount->getUserStatusStatistics();
        if(is_array($stat) && count($stat) > 0)
        {
            $return['Status'] = 1;
            $return['Message'] = $stat;
        }
        
        labas:
        if( $pageCall )
        {
            return $return;
        }
        else
        {
            echo json_encode($return);
        }
        
    }

    function useronline_list()
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        
        try
        {
            $DataTableConfig = $_SESSION['DataTables_Sessions'][$_REQUEST['TableID']];

            $table = 'v_002_user_online'; // target table = normal table, view table, virtual table
          
            // Table's primary key
            $primaryKey = 'LogID'; // Primary Key of the Table for normal table, 

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

            /* Remove in Final Post Parameter */
            $unsetArray = array('addedFilter','addedFilterMode','StrictSearchMode');
            foreach($unsetArray as $unsetkeys)
            {
                if( isset($_REQUEST[ $unsetkeys]) ){ unset($_REQUEST[$unsetkeys]); }
            }     

            // use to override datatable return function call
            $overRideFunctionCall = array(
                'LogID' =>  function( $d, $row, $fieldid, $TargetTableID ) {
                                        
                                      return str_pad(intval($d),5,0,STR_PAD_LEFT);
                                    },
                'Account_Name' =>  function( $d, $row, $fieldid, $TargetTableID ) {
                                        $d = ucwords(strtolower($d));
                                      return $d;
                                    },
                'LogDateTime' =>  function( $d, $row, $fieldid, $TargetTableID ) {
                                        $d = date("m/d/Y h:i:s A",strtotime($d));
                                      return $d;
                                    },
                'UpdatedDateTime' =>  function( $d, $row, $fieldid, $TargetTableID ) {
                                        $d = $this->myutilities->getDuration(strtotime($row['LogDateTime']),strtotime($d));
                                      return $d;
                                    },
                 'IP_Address' =>  function( $d, $row, $fieldid, $TargetTableID ) {
                                        
                                      return ($d == '::1') ? 'localhost' : $d;
                                    },
                       
            );
         
            switch(strtolower($DataTableConfig['extracolumn']))
            {
                case 'first':
                case 'last':
                    $overRideFunctionCall[ucwords(strtolower(trim($DataTableConfig['extracolumn']))).'Column'] = function( $d, $row, $fieldid, $TargetTableID ) {

                        // Put Your Return Value Here
                        // Reminder -> this is a DataTable Array Column Properties
                        // $d = Return Value from the Database
                        // $row = Define Column Properties for the DataTabe --> ex : $row['UserID']
                        $d = '<button type="button" class="btn btn-success btn-sm" id="btnLogoutUser" uid="'.$row['LogID'].'" title="Logout this User ['.$row['User_Name'].']" style="font-size:12px"><i class="fa fa-fw fa-sign-out-alt"></i>&nbsp;Logout</button>';
                      return  base64_encode($d);
                    };

                    break;
                case 'firstlast';
                    $overRideFunctionCall['FirstColumn'] = function( $d, $row, $fieldid, $TargetTableID ) {
                        // Put Your Return Value Here
                        $d = '<i id="btn_viewDetails" dataid="'.$d.'" class="fas fa-search" style="cursor:pointer" title="View [ '.ucwords(strtolower($row['Account_Name'])).' ] Account Details"></i>';
                      return  base64_encode($d);
                    };
                     $overRideFunctionCall['LastColumn'] = function( $d, $row, $fieldid, $TargetTableID ) {
                        // Put Your Return Value Here
                        $d = '';
                          return  base64_encode($d);
                        };
                    break;
            }        

            // normally used for data return alteration
            $addedColumnProperties = array(
                //  'User_Password' =>  function( $d, $row, $fieldid, $TargetTableID ) {
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
            // Get Aggregate User Status
            // Load Model -> administrator/useraccount
            

            // Return Data as Json Format
            echo json_encode($DataReturn);
        }
        catch(Exception $err)
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        

    }

    function list_activityhistory($userid = '')
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        
        try
        {
            $DataTableConfig = $_SESSION['DataTables'][$_REQUEST['TableID']];

            $table = 'user_activity'; // target table = normal table, view table, virtual table
          
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
            $DefaultFilter =" userid = ".((int) @$userid);


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

    function list_loginhistory($userid = '')
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        
        try
        {
            $DataTableConfig = $_SESSION['DataTables'][$_REQUEST['TableID']];

            $table = 'users_online_log'; // target table = normal table, view table, virtual table
          
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
            $DefaultFilter =" userid = ".((int) @$userid);


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
    
    function list_statushistory($userid = '')
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        
        try
        {
            $DataTableConfig = $_SESSION['DataTables'][$_REQUEST['TableID']];

            $table = 'users_status_log'; // target table = normal table, view table, virtual table
          
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
            $DefaultFilter =" userid = ".((int) @$userid);


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

    function generateusername($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Generate New User Name'
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
            $returnR = $this->myutilities->generateusername();
            if( $returnR <> '')
            {
                $return = array(
                    'Status'    => 1,
                    'Message'   => $returnR
                );
            }
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

        $ulog = array(
          'type'        =>'Create New Account',
          'action'      =>'Generate User Name',
          'description' =>'',
          'remarks'     =>@$return['Message']
        );

        $this->write_useractivitylog($ulog);
    }

    function submitaccount($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Create New User Account'
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
          unset($SubmittedParam['UToken']);
        }
        
        if($internal)
        {
          $SubmittedParam = $formparameter;
          $SubmittedFiles = '';
        }

        try
        { 
            $activaterequired = $SubmittedParam['activationrequired'];
            unset($SubmittedParam['activationrequired']);

            if( (int) $this->usertype > 2 )
            {
                $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                goto exit_function_here;
            }
            // Process Create User Account
            if( !isset($SubmittedParam['psswrd']) )
            {
                $SubmittedParam['psswrd'] = $this->myutilities->generatePassword($this->system_settings['PasswordLength_Max']);
            }
            
            $SubmittedParam['psswrd'] = $this->db->escape_str($SubmittedParam['psswrd']);
            $SubmittedParam['user_type'] =  $SubmittedParam['usertype'];
            $SubmittedParam['DateofBirth'] = date("Y-m-d",strtotime($SubmittedParam['DateofBirth']));
            unset($SubmittedParam['usertype']);
            // Validate
            // Check Duplicate in User Name and Email Address
            $this->load->model('register/m_register');
            $InsertRegistrationResult = $this->m_register->insertRegistrationData($SubmittedParam);
            if($InsertRegistrationResult['Status'] == 1)
            {
                $registrationID = $InsertRegistrationResult['RegistrationID'];
                $ActivationLink = @$InsertRegistrationResult['ActivationLink'];
                // Success Insert Registration
                if( @$this->system_settings['RegistrationAutoApprove'] <> 1 )
                {
                   
                    // Auto Move for Admin Created Account
                    $approvedRegData = [
                        'action_by'         => 0,
                        'action_datetime'   => date("Y-m-d H:i:s"),
                        'register_status'   => 2,
                    ];

                    $UpdateRegistrationResult = $this->sqlhelper->local->update("user_register")->ex_update($approvedRegData)->where("registrationID ='".$registrationID."'")->run();
                    $MoveResult = $this->m_register->movetouseraccount($registrationID);

                    $activationlink = $this->myutilities->stringToUuid($approvedRegData['action_datetime'].'|'.$registrationID);
                    $uuidV5 = Uuid::uuid5($activationlink, $registrationID);
                    $activationlink = $uuidV5->toString();

                    $activationlinkurl = site_url('activate/index/'.$activationlink);

                    $activationinfo = array(
                        'activationlink' => $activationlink,
                        'activationlinkdatetime' => $approvedRegData['action_datetime']
                    );

                    $UpdateRegistrationResult = $this->sqlhelper->local->update("user_register")->ex_update($activationinfo)->where("registrationID ='".$registrationID."'")->run();

                    $ActivationLink = $activationlinkurl;
                }

                // get Reg Info
                $regInfo = $this->m_register->getRegistrationData($registrationID); 

                $usp = [
                    'manualcreate' => 'Y'
                ];
                $this->sqlhelper->local->update("user_profiles")->ex_update($usp)->where("user_id='".$regInfo['user_id']."'")->run();

                $uar = [
                    'usertype' => $SubmittedParam['user_type']
                ];
                
                if(@$activaterequired == 'N')
                {
                    $uar['activated'] = 1;
                    $uar['activated_datetime'] = date("Y-m-d H:i:s");
                    $uar['active'] = 1;
                }

                $this->sqlhelper->local->update("users")->ex_update($uar)->where("id='".$regInfo['user_id']."'")->run();
                $uarr = [
                    'user_type' => $SubmittedParam['user_type']
                ];
                if(@$activaterequired == 'N')
                {
                    $uarr['activateddatetime'] = date("Y-m-d H:i:s");
                }
                $this->sqlhelper->local->update("user_register")->ex_update($uarr)->where("user_id='".$regInfo['user_id']."'")->run();

                if(@$activaterequired <> 'N')
                {
                    $return['Status'] = 1;
                    $return['Message'] = 'New Account successfully created, Activation Link will be sent via email to activate and start using the created account.';

                    $regInfo['Lastname'] = $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$regInfo['Lastname'],'MCrypt','aes-128','ecb');
                    $regInfo['Firstname'] = $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$regInfo['Firstname'],'MCrypt','aes-128','ecb');
                    $regInfo['Middlename'] = $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$regInfo['Middlename'],'MCrypt','aes-128','ecb');

                    $RegName = ucwords(strtolower($regInfo['Lastname'].( ($regInfo['Suffixname'] <> 'NA' && $regInfo['Suffixname'] <> '') ? ' '.$regInfo['Suffixname'] : '' ).', '.$regInfo['Firstname'].' '.substr($regInfo['Middlename'],0,1).'.'));

$emailMessage = '
You have Successfully registered to '.$this->system_settings['ApplicationAbbre'].' : '.$this->system_settings['ApplicationName'].'. 
Please see account credential below:<br><br>
<p>USER NAME : '.$regInfo['username'].'</p>
<p>PASSWORD : '.$SubmittedParam['psswrd'].'</p>
<br><br>
<p>Please activate your account by clicking/navigate to the link provided below.<hr>
<p>Activation Link :</p><p><a href="'.@$ActivationLink.'" target="_blank">'.@$ActivationLink.'</a></p>
<hr>
<p>Please be reminded that you need to activate your account within 24 hours after receiving this message.</p>';

                    $mail_message = array(
                          'header' => 'Hi '.strtoupper(@$RegName),
                          'footer' => 'Thank You.',
                          'body' => $emailMessage
                        );

                    $emailparam = array(
                      'from'    => $this->config->item('email_sender'),
                      'to'      => $SubmittedParam['eaddrs'],
                      'subject' => $this->system_settings['ApplicationAbbre'].' - Account Registration',
                      'message' => $mail_message,
                      'cc'      => '',
                      'bcc'     => ''
                    );

                    $this->daemon->execute_background('sendmail/sendmail','send_mail',$emailparam);
                }
                else
                {
                    // Update User Profile
                    $inserActivationLog = [
                        'activation_datetime' => date("Y-m-d H:i:s"),
                        'user_id'             => $regInfo['user_id']
                    ];
                    $iual = $this->sqlhelper->local->insert('user_activation_log')->ex_insert($inserActivationLog)->run();
                    
                    $return['Status'] = 1;
                    $return['Message'] = 'New Account successfully created';
                }
                
            }
            else
            {
                return $InsertRegistrationResult;
            }

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

        $ulog = array(
          'type'        =>'Create New User Account',
          'action'      =>'Create New User Account',
          'description' =>'',
          'remarks'     =>$return['Message']
        );

        $this->write_useractivitylog($ulog);
    }

    function savepermission($internal=false,$formparameter = '')
    {
      $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Save User Module Permission'
        );

        if( @$this->usertype > 3 )
        {
          $return = array('Status' => 0, 'Message' => 'Permission Denied!');
          goto exit_function_here;
        }

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
            }
        }
        
        if($internal)
        {
            $SubmittedParam = $formparameter;
        }

        try{
         
            $userid = @$SubmittedParam['userid'];
            unset($SubmittedParam['userid']);
            $PermissionListItem = $this->myutilities->systemmodulepermissionlist();
            $UserPermissionListItem = $this->myutilities->usermodulepermission($userid);
            foreach($SubmittedParam as $key => $val)
            {
                $ipData = [
                    'user_id'           => $userid,
                    'PermissionNameID'  => $PermissionListItem[$key],
                    'PermissionValue'   => (int) (@$val <> '') ? $val : '0',
                    'Created_By'        => $this->userid,
                    'Created_DateTime'  => date('Y-m-d H:i:s'),
                ];
                if( array_key_exists((int) $ipData['PermissionNameID'], $UserPermissionListItem))
                {
                    $ipData['Updated_By'] = $ipData['Created_By'];
                    $ipData['Updated_DateTime']  = $ipData['Created_DateTime'];
                    
                    unset($ipData['user_id']);
                    unset($ipData['user_id']);
                    unset($ipData['Created_By']);
                    unset($ipData['Created_DateTime']);
                    $updateResult = $this->sqlhelper->local->update("user_permission")->ex_update($ipData)->where("user_id ='".$userid."' and PermissionNameID = '".(int) $PermissionListItem[$key]."'")->run();
                }
                else
                {
                    $insertResult = $this->sqlhelper->local->insert("user_permission")->ex_insert($ipData)->run();
                }
            }

            $return['Status'] = 1;
            $return['Message'] = 'User Module Permission Successfully Saved!';

        }catch (PDOException $e) {
            log_message("error","Controller : useraccount/savepermission : Error Saving Data [ ".$e->getMessage()." ] : ".json_encode($SubmittedParam));
        } catch (Exception $e) {
            log_message("error","Controller : useraccount/savepermission : Error Saving Data [ ".$e->getMessage()." ] : ".json_encode($SubmittedParam));
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
            'type'=>'User Module Permission',
            'action'=>@$return['Message'],
            'description'=>'',
            'remarks'=>''
          );

        $this->write_useractivitylog($ulog);  
    } 
}