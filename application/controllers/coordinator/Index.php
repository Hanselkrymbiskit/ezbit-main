<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

	function __construct()
	{
		 // phpinfo();
        parent::__construct();
        $this->data['pagetype'] = "Z-Ben Coordinator";
        $this->breadcrumbs->push('<i class="fa fa-users"></i>&nbsp;Z-Benefit Coordinator', '/coordinator');
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
        
        // if( !in_array((int) $this->userclassification,$this->m_general->HFUsers) )
        // {
        //     redirect('home');
        // }

        $this->load->model('coordinator/m_coordinator');
        
	}

    function index($HCICode = '')
    {
        $index = 'index';

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

        $this->data['PageTitle'] = "Z-Benefit Coordinator"; 
        $this->data['title'] = "Z-Benefit Coordinator"; 
        
        $pageheaderaction = '
                <div id="PageTitle-Button-Holder" class="float-right">
                    <button type="button" name="btn_claimsaction" id="claims_new" class="float-right btn btn-primary btn-block btn-round waves-effect waves-classic text-left font-size-16"><i class="fa fa-fw fa-plus mr-10 ml-10 pr-10" aria-hidden="true" style="border-right: 1px solid #787878;"></i>New Z-Benefit Claims</button>             
                </div>     
            ';

        $pageheaderaction = '';

        if( in_array((int) $this->userclassification,$this->m_general->HFUsers) )
        {
            $_SESSION['HFZBenServices'] = $this->m_general->getFacilityZBenServices($this->userregistrationinfo['HealthFacilityCode']);
        }
        else
        {
            if($HCICode <> '')
            {
                $HCICode = $this->m_api->encryptdecryptString('decrypt',$this->userprofile['User_E_Key'],$HCICode,'MCrypt','aes-128','ecb');
            }

            $_SESSION['HFZBenServices'] = $this->m_general->getFacilityZBenServices($HCICode);
        }

        $this->data['pageheaderaction'] = @$pageheaderaction;

        $this->load->template('pages/coordinator/'.$index,  $this->data,'',1); // this will load the view file    
        // redirect('dashboard');
    } 

    function datalist()
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        
        try
        {
            $DataTableConfig = $_SESSION['DataTables'][$_REQUEST['TableID']];
            switch( (int) $this->userclassification )
            {
                case 1: // Facility
                    $wtble = "a.HealthFacilityCode = '".$this->userregistrationinfo['HealthFacilityCode']."'";
                break;
                case 2: // BAS - PRO
                case 3: // PRO 
                    $wtble = "a.ProCode = '".$this->userregistrationinfo['ProCode']."'";
                break;
            }


            $table = "
                (
                    select  
                        a.*,
                        CAST(AES_DECRYPT(FROM_BASE64(b.CompleteName),FROM_BASE64('".$this->system_settings['PasswordHashing']."')) AS CHAR ) as CompleteName,
                        c.primarycondition_access
                    from user_register a 
                    left join user_profiles b on b.user_id = a.user_id and b.registration_id = a.DataID 
                    left join trans_zben_coordinator_access c on c.user_id = a.user_id
                    where a.user_classification in (6,7) and a.register_status = 2 ".( (@$wtble) ? ' and '.@$wtble : '')."
                ) as CoordinatorList
            "; // target table = normal table, view table, virtual table
          
            // Table's primary key
            $primaryKey = 'DataID'; // Primary Key of the Table for normal table, 

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
                 
                'DataID_crud_view' => function($d, $row, $fieldid, $TargetTableID,$colid){

                    $DataID = base64_encode((int) $row['DataID']); 
                    $d = '<i class="fa fa-search fa-fw text-success" title="View Details" id="'.bin2hex($DataID).'"  name="'.bin2hex($DataID).'" group="'.$TargetTableID.'" role="button"></i>';
                    return $d; 

                },      

                // 'DataID_crud_edit' => function($d, $row, $fieldid, $TargetTableID,$colid){

                //     $DataID = base64_encode($this->encryption->encrypt($row['ReferenceNo']));
                //     $d = '<a href="javascript:void(0);" class="" title="Edit Details" id="ed_'.$row['DataID'].'"  name="ed_'.$row['DataID'].'" onclick="window.location.href=\''.(site_url("applicationsystem/index/edit/".$DataID)).'\'"><i class="fa fa-edit fa-fw"></i></a>';

                //     if( $row['Created_By'] <> $this->userid )
                //     {
                //         $d = ( (int) $this->usertype > 3 ) ? '' : $d;
                //     }

                //     return $d; 
                // },  
                'user_classification' => function($d, $row, $fieldid, $TargetTableID,$colid){

                    $d = $this->myutilities->getRef_Desc(4,$d);
                    $d = str_replace(['Health Facility - ','Z Benefits'],'',$d);
                    return $d; 

                },      
                'primarycondition_access' => function($d, $row, $fieldid, $TargetTableID,$colid){
                    if( $d <> '' )
                    {
                        $serv = explode(",",$d);
                        $HFZBen = ( isset($_SESSION['HFZBenServices']) ) ? @$_SESSION['HFZBenServices'] : [];
                        if(count($serv) == count($HFZBen))
                        {
                            $d = 'ALL Z-BEN SERVICES OF THE FACILITY';
                        }
                        else
                        {
                            $zdesc = [];
                            foreach($serv as $zcode)
                            {
                                $zdesc[] = $this->myutilities->getRef_Desc(203,$zcode,'false','short_display');
                            }

                            $d = implode(" | ",$zdesc);
                        }
                    }
                    else
                    {
                        $d = '<i class="fa fa-fw fa-ban mr-10"></i>NO Z-BEN PERMISSION';
                    }

                    return $d; 

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
            // Get Aggregate Status
            // $DataReturn['ClaimsStatus'] = $this->m_claims_reports->claims_status_stats(@$wtble);
            // $DataReturn['ClaimsPrimaryStats'] = $this->m_claims_reports->claims_primary_stats(@$wtble);

            // log_message("error",$DataReturn['PreAuthStatus']);
            // $DataReturn['LocationStatus'] = $this->m_dashboard->stats_appstatus_location(@$DefaultFilter);
            // $DataReturn['SystemGroup'] = $this->m_dashboard->stats_appstatus_systemgroup(@$DefaultFilter);


            // Return Data as Json Format
            echo json_encode($DataReturn);
        }
        catch(Exception $err)
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        

    }

    function view($rowid)
    {
        if( $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }
        
        $rowid = base64_decode(hex2bin($rowid));

        if(!$rowid)
        {
            redirect('coordinator');
        }

        $rowid = (int) $rowid;
        $index = 'details';

        $coProfile = $this->m_coordinator->getProfile($rowid);
        if($coProfile['Status'] == 0)
        {
             redirect('coordinator');
        }

        $this->data['coProfile'] = $coProfile['Message'];

        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/jquery-wizard/jquery-wizard',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/multi-select/multi-select',
            'css/jquery-smartwizard/smart_wizard_all.min',
            'global/vendor/summernote/summernote-lite',
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/bootstrap-clockpicker.min',
            'global/vendor/formatter/jquery.formatter',
            'global/vendor/jquery-wizard/jquery-wizard',
            'global/vendor/formvalidation/formValidation.min',
            'global/vendor/formvalidation/framework/bootstrap4.min',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/multi-select/jquery.multi-select',
            'js/userfunction/jquery.quicksearch',
            'js/jquery-smartwizard/jquery.smartWizard.min',
            'global/vendor/summernote/summernote.min',
            // 'js/userfunction/pdf-lib.min'
        );

        $this->data['loadjschild'] = array(
             'global/js/Plugin/bootstrap-datepicker',
             'global/js/Plugin/clockpicker',
             'global/js/Plugin/formatter',
             'global/js/Plugin/jquery-wizard',
             'global/js/Plugin/jquery-labelauty',
             'global/js/Plugin/responsive-tabs',
             'global/js/Plugin/closeable-tabs',
             'global/js/Plugin/tabs',
             'global/js/Plugin/multi-select',
              'js/userfunction/xlsx.full.min',
              'js/userfunction/table2excel.min',
        );

        $this->data['PageTitle'] = "Z-Ben Coordinator Profile"; 
        $this->data['title'] = "Z-Ben Coordinator Profile"; 

        $pageheaderaction = '
        <div id="PageTitle-Button-Holder" class="float-right">
            <button type="button" name="btn_claimaction" id="backClaim" class="btn btn-dark btn-round waves-effect waves-classic text-left font-size-16" title="Back to Z-Benefit Claims List" onclick="window.location.href=\''.site_url('coordinator').'\'"><i class="fa fa-fw fa-arrow-left" aria-hidden="true"></i></button>      
        </div> 
        ';

        // $this->data['pdf'] = new FpdiProtection('P', 'mm','A4', true);
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->template('pages/coordinator/'.$index,  $this->data,'',1); // this will load the view file    
     
    } 

    function zbenpermission($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => ''
        );
    
        if( ($_SERVER['REQUEST_METHOD'] == 'POST' && $internal) || $this->userclassification <> 1)
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
        
            $state = ($SubmittedParam['state'] == 'true') ? 'add' : 'remove';
            $zserv = $this->m_api->encryptdecryptString('decrypt',$this->userprofile['User_E_Key'],$SubmittedParam['zbenservices'],'MCrypt','aes-128','ecb');
            $zserv = ($zserv <> '') ? explode("_",$zserv) : [];
            if(count($zserv) > 0)
            {
                $ZBenCode = $zserv[1];
                $tUser = $zserv[2];
                $zDate = $zserv[0];
                if( $zDate <> date("Ymd") )
                {
                    $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                    goto exit_function_here;
                }

                $return = $this->m_coordinator->{$state.'ZBenServices'}($ZBenCode,$tUser);
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
    }

}