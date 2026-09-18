<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\FpdiProtection\FpdiProtection;
class Index extends MY_Controller {

	function __construct()
	{
		 // phpinfo();
        parent::__construct();
        $this->data['pagetype'] = "Pre-Authorization";
        $this->breadcrumbs->push('<i class="fa fa-wpforms"></i>&nbsp;Pre-Authorization', '/preauthorization');

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
        $this->load->model('coordinator/m_coordinator');

        if( (int) $this->userclassification == 6)
        {
            $myPreAuthAccess = $this->m_coordinator->getCoorZBenServices($this->userid,true);
            $myPreAuthAccess = ($myPreAuthAccess['Status'] == 1) ? $myPreAuthAccess['Message'] : [];
            $this->data['myPreAuthAccess'] = $myPreAuthAccess;
        }
	}

    function index()
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

        $this->data['PageTitle'] = "Pre-Authorization - Checklist and Request"; 
        $this->data['title'] = "Pre-Authorization"; 
        
        $pageheaderaction = '
                <div id="PageTitle-Button-Holder" class="float-right">
                    <button type="button" name="btn_preauthaction" id="preauth_new" class="float-right btn btn-dark btn-block btn-round waves-effect waves-classic text-left font-size-16"><i class="fa fa-fw fa-plus mr-10 ml-10 pr-10" aria-hidden="true" ></i>New Pre-Authorization</button>             
                </div>     
            ';

        if(!in_array((int) $this->userclassification,$this->m_general->HFUsers))
        {
            $pageheaderaction = '';
        }  
        // $this === ci controller
        $this->data['pageheaderaction'] = @$pageheaderaction;

        $this->load->template('pages/preauthorization/'.$index,  $this->data,'',1); // this will load the view file   

        // redirect('dashboard');
    } 

    function requestnewform($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Retrieve Form'
        );

        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal || !in_array((int) $this->userclassification,$this->m_general->HFUsers) )
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

            if( $this->usertype > 3 && in_array((int) $this->userclassification,$this->m_general->HFUsers) )
            {
                if( (int) $this->userclassification == 6 )
                {
                    if( !in_array(str_pad($SubmittedParam['IllnessType'],2,0,STR_PAD_LEFT),$this->data['myPreAuthAccess']) )
                    {
                        $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                        goto exit_function_here;
                    }
                }

                $return = array(
                    'Status'    => 1,
                    'Message'   => $this->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],date("Ymd")."-".$SubmittedParam['IllnessType'],'Mcrypt','aes-128','ecb')
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
    }

    function newpreauth($param)
    {
        if( $_SERVER['REQUEST_METHOD'] == 'POST' || !in_array((int) $this->userclassification,$this->m_general->HFUsers) )
        {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        $index = 'index';

        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/jquery-wizard/jquery-wizard',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/multi-select/multi-select',
            'css/jquery-smartwizard/smart_wizard_all.min',
            'global/vendor/select2/select2'
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
            'global/vendor/jq-signature/jq-signature.min',
            'global/vendor/select2/select2.full.min'
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
            'global/js/Plugin/select2'
        );

        $param = $this->m_api->encryptdecryptString('decrypt',$this->userprofile['User_E_Key'],base64_decode($param),'MCrypt','aes-128','ecb');
        $param = explode("-",$param);

        if($param[0] <> date("Ymd"))
        {
            redirect('preauthorization');
        }
        
        $illnessType = (int) $param[1];

        if( (int) $this->userclassification == 6 )
        {

            if( !in_array(str_pad($illnessType,2,0,STR_PAD_LEFT),$this->data['myPreAuthAccess']) )
            {
                redirect('preauthorization');
            }
        }

        $PreAuthFormTitle = $this->myutilities->getRef_Desc(200,$illnessType);
        $this->data['SelectionCriteria'] = $this->m_preauth->getSelectionCriteria($illnessType);
        $this->data['preauthforms'] = str_pad($illnessType, 2,0,STR_PAD_LEFT);
        $this->data['PageTitle'] = "Pre-Auth : ".$PreAuthFormTitle; 
        $this->data['title'] = "Pre-Authorization"; 
        $pageheaderaction = '';
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->template('pages/preauthorization/form/'.$index,  $this->data,'',1); // this will load the view file    

    } 

    function submitform($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Process Submitted Pre-Authorization Form'
        );

        if( ($_SERVER['REQUEST_METHOD'] == 'POST' && $internal) || !in_array((int) $this->userclassification,$this->m_general->HFUsers) )
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
            
            $illnessType = @$this->m_api->encryptdecryptString('decrypt',$this->userprofile['User_E_Key'],$SubmittedParam['illnessType']);
            $dupdates = false;
            if(!$illnessType)
            {
                goto exit_function_here;
            }

            $illnessType = explode("-",$illnessType);
            if($illnessType[0] <> date('Ymd') || !is_array($illnessType))
            {
                $return = array('Status' => 0, 'Message' => 'Failed to Process Submitted Pre-Authorization Form, Unauthorized Request Detected!');
                goto exit_function_here;
            }
            $preauth_status = 1;
            $illnessType = @$illnessType[1];

            if( (int) $this->userclassification == 6 )
            {

                if( !in_array(str_pad($illnessType,2,0,STR_PAD_LEFT),$this->data['myPreAuthAccess']) )
                {
                    $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                    goto exit_function_here;
                }
            }

            unset($SubmittedParam['illnessType']);
            $SubmittedParam['patientinfo']['preauth_type'] = $illnessType;
            $SubmittedParam['patientinfo']['patient_is_member'] = (isset($SubmittedParam['patientinfo']['patient_is_member'])) ? $SubmittedParam['patientinfo']['patient_is_member'] : 'N';
            
            if( $SubmittedParam['patientinfo']['patient_is_member'] == 'Y' )
            {
                $SubmittedParam['patientinfo']['member_lastname']       = $SubmittedParam['patientinfo']['patient_lastname'];
                $SubmittedParam['patientinfo']['member_firstname']      = $SubmittedParam['patientinfo']['patient_firstname'];
                $SubmittedParam['patientinfo']['member_middlename']     = $SubmittedParam['patientinfo']['patient_middlename'];
                $SubmittedParam['patientinfo']['member_suffix']         = $SubmittedParam['patientinfo']['patient_suffix'];
                $SubmittedParam['patientinfo']['member_sex']            = $SubmittedParam['patientinfo']['patient_sex'];
                $SubmittedParam['patientinfo']['member_dateofbirth']    = $SubmittedParam['patientinfo']['patient_dateofbirth'];
                $SubmittedParam['patientinfo']['member_philhealthno']   = $SubmittedParam['patientinfo']['patient_philhealthno'];
            }

            // Add-on Parameter
            $SubmittedParam['patientinfo']['patient_age'] = $this->myutilities->ComputeForAge( @$SubmittedParam['patientinfo']['patient_dateofbirth'] ,date('Y-m-d'));
            $SubmittedParam['patientinfo']['member_age'] = $this->myutilities->ComputeForAge( @$SubmittedParam['patientinfo']['member_dateofbirth'] ,date('Y-m-d'));
            $SubmittedParam['patientinfo']['fulfilled_selection_criteria_reason'] = @$SubmittedParam['patientinfo']['fulfilled_selection_criteria_reason'];
            $SubmittedParam['patientinfo']['healthfacility_area']   = @$this->userregistrationinfo['AreaCode'];
            $SubmittedParam['patientinfo']['healthfacility_pro'] = @$this->userregistrationinfo['ProCode'];
            $SubmittedParam['patientinfo']['healthfacility_code']    = @$this->userregistrationinfo['HealthFacilityCode'];

            if( @$SubmittedParam['memberempowerment']['a_member_permanent_address_city'] <> '' )
            {
                $aCtyRow = $this->myutilities->getRef_Desc(13,$SubmittedParam['memberempowerment']['a_member_permanent_address_city'],false,'',true);
                $SubmittedParam['memberempowerment']['a_member_permanent_address_region'] = @$aCtyRow['regcode'];
                $SubmittedParam['memberempowerment']['a_member_permanent_address_province'] = @$aCtyRow['provcode'];
            }

            if( isset($SubmittedParam['case_no']) )
            {
                $SubmittedParam['case_no'] = $this->m_api->encryptdecryptString('decrypt',$this->userprofile['User_E_Key'],$SubmittedParam['case_no']);
                $case_no = $SubmittedParam['case_no'];
                unset($SubmittedParam['case_no']);
                $SubmittedParam['patientinfo']['case_no'] = $case_no;
                $PreAuthData = $this->m_preauth->getFormData($case_no,true);
                if(!$PreAuthData)
                {
                    $return['Message'] = 'Failed to Process Submitted Pre-Authorization Form, Invalid Pre-Authorization Case No!';
                    goto exit_function_here;
                }

                if($PreAuthData['patientinfo']['preauth_status'] <> 4)
                {
                    $return['Message'] = 'Failed to Update Pre-Authorization Status, Pre-Authorization Case No : '.$case_no.' already '.$this->myutilities->getRef_Desc(202,(int) $PreAuthData['patientinfo']['preauth_status']);
                    goto exit_function_here;
                }

                $dupdates = true;
                $preauth_status = 2;
            }

            $fulfilled_selection_criteria = (isset($SubmittedParam['patientinfo']['fulfilled_selection_criteria'])) ? true : false;
            if( $fulfilled_selection_criteria && $SubmittedParam['patientinfo']['fulfilled_selection_criteria'] == 'N')
            {
                unset($SubmittedParam['checklist']);
                unset($SubmittedParam['request']);
            }

            $required = $this->m_preauth->getRequiredData($illnessType,$fulfilled_selection_criteria,$dupdates,$SubmittedParam);
     
            foreach( $required as $rK => $rV )
            {
                foreach( $rV as $rVk => $rVv )
                {
                    if( !array_key_exists($rVk, $SubmittedParam[$rK]))
                    {
                        $SubmittedParam[$rK][$rVk] = '';
                    }
                    else
                    {
                        $SubmittedParam[$rK][$rVk] = ($rVv) ? $this->myutilities->dbValueFormatter($rVk, $SubmittedParam[$rK][$rVk]) : @$SubmittedParam[$rK][$rVk];
                    }
                }
            }

            $validate = $this->m_preauth->vFormData($required,$SubmittedParam,true);
            if( $validate['Status'] == 1 )
            {
                $return = $this->m_preauth->ProcessPreAuth($required,$SubmittedParam,$SubmittedFiles,$dupdates,@$PreAuthData);
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
                case 1:
                case 6:
                case 7:
                    if((int) $this->userclassification == 6 )
                    {
                        $wtble = "a.healthfacility_code = '".$this->userregistrationinfo['HealthFacilityCode']."' and a.preauth_type in ('".( ( is_array($this->data['myPreAuthAccess']) ) ? implode("','",$this->data['myPreAuthAccess']) : 'none' )."')";
                    }
                    else
                    {
                        $wtble = "a.healthfacility_code = '".$this->userregistrationinfo['HealthFacilityCode']."' ";
                    }

                    $ttble = 'trans_preauth_form';
                break;

                case 2: // BAS - PRO
                case 3: // PRO 
                    $wtble = "a.healthfacility_pro = '".((int) $this->userregistrationinfo['ProCode'])."'";
                    $ttble = 'trans_preauth_form';
                break;

                case 4:
                case 5:
                    $ttble = 'trans_preauth_form';
                break;

                default:
                     $ttble = 'trans_preauth_form';
                 break;
            }


            // ZPAMS-FIX (2026-09): user_profiles.User_E_Key is a UUID-style string (e.g.
            // "632955c3-207f-49eb-b67f-d11e25e933ea") containing hyphens, which are not
            // valid base64 characters. FROM_BASE64() on that string returns NULL, so
            // AES_DECRYPT(..., NULL) always returned NULL here -- Patient Name and Member's
            // PhilHealth No. were blank in this grid for every record, for every user, on
            // every login, regardless of the underlying data. The rest of the app (e.g.
            // M_preauth::getFormData(), used by "View Details") decrypts these same columns
            // in PHP via M_api::encryptdecryptString(), which -- because the PHP openssl
            // driver silently truncates an oversized key rather than base64-decoding it --
            // effectively uses the first 16 bytes of the raw E_Key string as the AES-128 key.
            // LEFT(b.User_E_Key,16) reproduces that same effective key at the SQL level
            // (verified to decrypt identically to the PHP path) instead of the broken
            // FROM_BASE64() call.
            $table = "
                (
                    select
                        a.*,
                        a.preauth_status as 'TAT',
                        CAST(AES_DECRYPT(FROM_BASE64(a.patient_lastname),LEFT(b.User_E_Key,16)) AS CHAR ) as pLastname,
                        CAST(AES_DECRYPT(FROM_BASE64(a.patient_firstname),LEFT(b.User_E_Key,16)) AS CHAR ) as pFirstname,
                        CAST(AES_DECRYPT(FROM_BASE64(a.patient_middlename),LEFT(b.User_E_Key,16)) AS CHAR ) as pMiddlename,
                        CAST(AES_DECRYPT(FROM_BASE64(a.member_philhealthno),LEFT(b.User_E_Key,16)) AS CHAR ) as pmember_philhealthno,
                        CONCAT(CAST(AES_DECRYPT(FROM_BASE64(a.patient_lastname),LEFT(b.User_E_Key,16)) AS CHAR ),', ',CAST(AES_DECRYPT(FROM_BASE64(a.patient_firstname),LEFT(b.User_E_Key,16)) AS CHAR ),IF(a.patient_suffix is not null,CONCAT(' ',a.patient_suffix),''),', ',CAST(AES_DECRYPT(FROM_BASE64(a.patient_middlename),LEFT(b.User_E_Key,16)) AS CHAR )) as pPatientName


                    from ".$ttble." a
                    left join user_profiles b on b.user_id = a.created_by
                    ".( (@$wtble) ? ' where '.@$wtble : '')."

                ) as PreAuth_Forms
            "; // target table = normal table, view table, virtual table
          
            // Table's primary key
            $primaryKey = 'rowid'; // Primary Key of the Table for normal table, 

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
                 
                'rowid_crud_view' => function($d, $row, $fieldid, $TargetTableID,$colid){

                    $DataID = base64_encode((int) $row['rowid']); //$this->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],$row['rowid']);
                    $d = '<i class="fa fa-search fa-fw text-success" title="View Details" id="'.bin2hex($DataID).'"  name="'.bin2hex($DataID).'" group="'.$TargetTableID.'" role="button"></i>';
                    return $d; 

                },      

                'rowid_rowclass' => function($d, $row, $fieldid, $TargetTableID,$colid){
                  
                    switch((int) $row['preauth_status'] )
                    {
                        case 4:
                            $d .= ' bg-info-50';
                        break;
                        case 5:
                            $d .= ' bg-red-50';
                        break;

                        case 6:
                            $d .= ' bg-green-50';
                        break;
                    }
                    
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
 
                'TAT' => function($d, $row, $fieldid, $TargetTableID,$colid){
                    $tatd = '<i class="fa fa-fw fa-ban"></i>';
                    $d1 = new DateTime($row['submitted_datetime']);
                    $d2 = '';
                    switch( (int) $row['preauth_status'])
                    {
                        case 3:
                            $d2 = new DateTime($row['received_datetime']);
                        break;
                        case 4:
                            $d2 = new DateTime($row['preauth_status_datetime']);
                        break;
                        case 5:
                        case 6:
                            $d2 = new DateTime($row['ad_datetime']);
                           
                        break;

                    }

                    if($d2)
                    {
                        $tat = $d2->diff($d1);
                        $tatd = $tat->s.'s';
                        $rt = ['y'=>'yr(s)','m'=>'mth(s)','d'=>'day(s)','h'=>'hr(s)','i'=>'min(s)','s'=>'s'];
                        foreach($rt  as $tk => $tl )
                        {
                            if( $tat->{$tk} <> 0 )
                            {
                                $tatd = $tat->{$tk}.' '.$tl;
                                break;
                            }
                        }

                    }
                   
                    return $tatd; 

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
            $DataReturn['PreAuthStatus'] = $this->m_preauth_reports->preauth_status_stats(@$wtble);
            $DataReturn['PreAuthPrimaryStats'] = $this->m_preauth_reports->preauth_primary_stats(@$wtble);

            // log_message("error",$DataReturn['PreAuthStatus']);
            // $DataReturn['LocationStatus'] = $this->m_dashboard->stats_appstatus_location(@$DefaultFilter);
            // $DataReturn['SystemGroup'] = $this->m_dashboard->stats_appstatus_systemgroup(@$DefaultFilter);


            // Return Data as Json Format
            echo json_encode($DataReturn);
        }
        // ZPAMS-FIX (2026-09): was `catch(Exception $err)`, which does not catch a PHP Error/
        // TypeError -- widened to Throwable so a fatal here logs instead of failing silently.
        catch(Throwable $err)
        {
            log_message("error","datalist EXCEPTION | ".$err->getMessage()." | ".$err->getFile().":".$err->getLine());
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
            redirect('preauthorization');
        }

        $this->data['historymode'] = false;
        if( strpos($rowid, 'history_') > -1 )
        {
            $rowid = str_replace('history_', '', $rowid);

            $rowid = explode(":", $rowid);
            $this->data['recentid'] = $rowid[0];
            $rowid = $rowid[1];
            $this->data['historymode'] = true;
        }

        $rowid = (int) $rowid;
        $index = 'index';

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

        $this->data['ViewMode'] = true;
        $this->data['disabled'] = 'disabled';
        $this->data['ForCompliance'] = false;

        if($this->data['historymode'])
        {
            $getPreAuthDataHistory = $this->m_preauth->preauthHistory('',$rowid);
            if( $getPreAuthDataHistory['Status'] == 0 )
            {
                redirect('preauthorization');
            }

            $getPreAuthDataHistory = $getPreAuthDataHistory['Message'];
            $rekey = $this->myutilities->getUserProfile($getPreAuthDataHistory['created_by']);
            $datahist = json_decode($this->m_api->encryptdecryptString('decrypt',$rekey['User_E_Key'],base64_decode($getPreAuthDataHistory['dataarchives']),'MCrypt','aes-128','ecb'),true);
            if(is_array($datahist))
            {
                $getPreAuthData = $datahist;
                $this->data['currenthistoryid'] = $rowid;
                $this->data['currenthistorydate'] = $getPreAuthDataHistory['created_datetime'];
            }
        }
        else
        {
            $getPreAuthData = $this->m_preauth->getFormData($rowid);
        }
        
        if( !is_array(@$getPreAuthData) )
        {
            redirect('preauthorization');
        }

        switch($this->userclassification)
        {
            case 1:
            case 6:
            case 7:
                if( $getPreAuthData['patientinfo']['healthfacility_code'] <> $this->userregistrationinfo['HealthFacilityCode'] )
                {
                    redirect('preauthorization');
                }
                
                if(!$this->data['historymode'])
                {
                    switch( (int) $getPreAuthData['patientinfo']['preauth_status']  )
                    {
                        case 4:
                            $this->data['ViewMode'] = false; 
                            $this->data['disabled'] = '';
                            $this->data['ForCompliance'] = true;
                        break;
                    }
                }

            break;

            case 2: // BAS Pro
            case 3: // BAS Pro

                if( $getPreAuthData['patientinfo']['healthfacility_pro'] <> $this->userregistrationinfo['ProCode'] )
                {
                    redirect('preauthorization');
                }

            break;
        }

        $gpreauthHistory = $this->m_preauth->preauthHistory($getPreAuthData['patientinfo']['case_no']);
        $this->data['preauthHistory'] = (is_array($gpreauthHistory['Message'])) ? $gpreauthHistory['Message'] : [];
        $gpreauthHStatus = $this->m_preauth->lastpreauthStatus($getPreAuthData['patientinfo']['case_no']);
        $this->data['preauthHStatus'] = (is_array($gpreauthHStatus['Message'])) ? $gpreauthHStatus['Message'] : [];
        $illnessType = (int) $getPreAuthData['patientinfo']['preauth_type'];

        if( (int) $this->userclassification == 6 )
        {
            if( !in_array(str_pad($illnessType,2,0,STR_PAD_LEFT),$this->data['myPreAuthAccess']) )
            {
                redirect('preauthorization');
            }
        }
        
        $PreAuthFormTitle = $this->myutilities->getRef_Desc(200,$illnessType);
        $this->data['SelectionCriteria'] = $this->m_preauth->getSelectionCriteria($illnessType);
        $this->data['preauthforms'] = str_pad($illnessType, 2,0,STR_PAD_LEFT);
        $this->data['PageTitle'] = "Pre-Auth : ".$PreAuthFormTitle; 
        $this->data['title'] = "Pre-Authorization"; 
        $this->data['preauthFormData'] = $getPreAuthData;
        
        if(!$this->data['historymode'])
        {
            switch($getPreAuthData['patientinfo']['preauth_status'])
            {
                case 1: // Submitted
                case 2: // Re-Submitted
                    $actionBtn = '<button type="button" name="btn_preauthaction" id="receivedPreAuth" class="btn btn-warning waves-effect waves-classic mr-20 font-size-16" title="Received Pre-Authorization Checklist & Request" ><i class="fa fa-fw fa-file mr-10" aria-hidden="true"></i>Receive Pre-Authorization</button>';
                break;

                case 3: // Received
                    $actionBtn = '
                    <div class="btn-group mr-20 font-size-16" role="group">
                        <button type="button" class="btn btn-dark text-white font-weight-bold dropdown-toggle waves-effect waves-classic" id="actionBtnGroup" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-fw fa-wpforms mr-10"></i>Pre-Authorization Status
                        </button>
                        <div class="dropdown-menu" aria-labelledby="actionBtnGroup" role="menu" style="will-change: transform;">
                            <a class="dropdown-item font-size-16 font-weight-bold text-dark" id="forcompliancePreAuth" name="btn_preauthaction" href="javascript:void(0)" astatus="4" role="menuitem">For Compliance</a>
                            <a class="dropdown-item font-size-16 font-weight-bold text-success" id="approvePreAuth" name="btn_preauthaction" href="javascript:void(0)"  astatus="6" role="menuitem">Approve</a>
                            <a class="dropdown-item font-size-16 font-weight-bold text-danger" id="disapprovePreAuth" name="btn_preauthaction" href="javascript:void(0)"  astatus="5" role="menuitem">Disapprove</a>
                        </div>
                    </div>
                    ';
                break;
            }
        }

            // ZPAMS-FIX (2026-09): PDF generation extracted to MY_Controller::generatePreAuthPDF()
            // so Claims can reuse it for its own Print/Download (a claim has no form of its own --
            // it prints its originating pre-authorization case's Annex A form).
            $fileClick = $this->generatePreAuthPDF($getPreAuthData, str_replace("Pre-Auth : ","",$this->data['PageTitle']), $this->data['ViewMode']);

        // ZPAMS-FIX (2026-09): previously a failed PDF build left $fileClick unset with no
        // feedback to the user (blank preview, no error). Now shows a toast instead.
        if( @$this->data['ViewMode'] == true && !@$fileClick )
        {
            $fileClick = 'onclick="javascript:toastr.error(\'Unable to Generate PDF\',\'Print/Download\');"';
        }

        $pageheaderaction = '
        <div id="PageTitle-Button-Holder" class="float-right">
            '.(($this->userclassification > 1) ? @$actionBtn : '').'
            <button type="button" name="btn_preauthaction" id="backPreAuth" class="btn btn-dark btn-round waves-effect waves-classic text-left font-size-16" title="Back to Pre-Authorization List" onclick="window.location.href=\''.site_url('preauthorization').'\'"><i class="fa fa-fw fa-arrow-left" aria-hidden="true"></i></button>
            <button type="button" name="btn_preauthaction" id="printPreAuth" class="btn btn-dark btn-round waves-effect waves-classic text-left font-size-16" title="Print/Download Pre-Authorization Checklist & Request" '.@$fileClick.'><i class="fa fa-fw fa-print" aria-hidden="true"></i></button>
        </div>
        ';

        // $this->data['pdf'] = new FpdiProtection('P', 'mm','A4', true);
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->template('pages/preauthorization/form/'.$index,  $this->data,'',1); // this will load the view file    
     
    } 

    function receivedPreAuth($internal=false,$formparameter = '')
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
            $case_no = @$this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$SubmittedParam['case_no'],'Mcrypt','aes-128','ecb');
            if(!$case_no)
            {
                goto exit_function_here;
            }

            $PreAuthData = $this->m_preauth->getFormData($case_no,true);
            if(!$PreAuthData)
            {
                $return['Message'] = 'Failed to Receive Pre-Authorization Form, Invalid Pre-Authorization Case No!';
                goto exit_function_here;
            }

            if((int) $PreAuthData['patientinfo']['healthfacility_pro'] <> (int) $this->userregistrationinfo['ProCode'])
            {
                $return['Message'] = 'Failed to Receive Pre-Authorization Form, You are trying to receive Pre-Authorization Case No that is not within your jurisdiction!';
                goto exit_function_here;
            }

            if( !in_array((int) $PreAuthData['patientinfo']['preauth_status'],[1,2]) )
            {
                $return['Message'] = 'Failed to Receive Pre-Authorization Form, Pre-Authorization Case No : '.$PreAuthData['patientinfo']['case_no'].' current status ['.$this->myutilities->getRef_Desc(202,$PreAuthData['patientinfo']['preauth_status']).']';
                goto exit_function_here;
            }

            // Process Receive Pre-Authorization
            $actnResult = $this->m_preauth->preauthAction((int) $PreAuthData['patientinfo']['preauth_type'],$case_no,3,$PreAuthData['patientinfo']['remarks'],$this->myutilities->getRef_Desc(104,$PreAuthData['patientinfo']['healthfacility_code']),$PreAuthData['patientinfo']['created_by'],@$PreAuthData['patientinfo']['submitted_datetime'],@$PreAuthData['patientinfo']['preauth_status_datetime']);
            if($actnResult['Status'] == 1)
            {
                $return['Message'] = 'Pre-Authorization Case No : '.$PreAuthData['patientinfo']['case_no'].' Successfully Received!';
                $return['Status'] = 1;
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

    function astatusPreAuth($internal=false,$formparameter = '')
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
           
            $case_no = @$this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$SubmittedParam['case_no'],'Mcrypt','aes-128','ecb');
            if(!$case_no)
            {
                goto exit_function_here;
            }

            $PreAuthData = $this->m_preauth->getFormData($case_no,true);
            if(!$PreAuthData)
            {
                $return['Message'] = 'Failed to Receive Pre-Authorization Form, Invalid Pre-Authorization Case No!';
                goto exit_function_here;
            }

            if((int) $PreAuthData['patientinfo']['healthfacility_pro'] <> (int) $this->userregistrationinfo['ProCode'])
            {
                $return['Message'] = 'Failed to Receive Pre-Authorization Form, You are trying to receive Pre-Authorization Case No that is not within your jurisdiction!';
                goto exit_function_here;
            }

            if((int) $PreAuthData['patientinfo']['preauth_status'] > 4)
            {
                $return['Message'] = 'Failed to Update Pre-Authorization Status, Pre-Authorization Case No : '.$case_no.' already '.$this->myutilities->getRef_Desc(202,(int) $PreAuthData['patientinfo']['preauth_status']);
                goto exit_function_here;
            }

            // Process Receive Pre-Authorization
            $actnResult = $this->m_preauth->preauthAction((int) $PreAuthData['patientinfo']['preauth_type'],$case_no,$SubmittedParam['preauth_status'],@$SubmittedParam['remarks'],$this->myutilities->getRef_Desc(104,$PreAuthData['patientinfo']['healthfacility_code']),$PreAuthData['patientinfo']['created_by'],$PreAuthData['patientinfo']['submitted_datetime'],@$PreAuthData['patientinfo']['preauth_status_datetime']);
        
            if($actnResult['Status'] == 1)
            {
                $return['Message'] = $actnResult['Message'];
                $return['Status'] = 1;
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

    function searchCityList($internal=false,$formparameter = '')
    {
        $return = [
            "results" => [],
            "pagination" => ["more"=>true]
        ];

        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal)
        {
            goto exit_function_here;
        }
        
        $SubmittedParam = $_POST;

        if($internal)
        {
            $SubmittedParam = $formparameter;
        }

        try
        {

            $sQuery = "
                select 
                    a.*,
                    c.provname,
                    b.nscb_reg_name
                from ref_city a 
                left join ref_region b on b.regcode=a.regcode 
                left join ref_province c on c.provcode=a.provcode 
                where 
                (a.cityname like '%".$SubmittedParam['q']."%' or a.citycode like '%".$SubmittedParam['q']."%') 
                order by a.cityname asc
            ";


            $eList = $this->sqlhelper->local->sql($sQuery);
            // log_message("error",$eList->rtrnSql());
            $eList = $eList->result();
            $earray = [];

            foreach($eList['Data'] as $row => $val)
            {
                $earray[] = [
                    "id"            => $val['citycode'],
                    "text"          => strtoupper($val['cityname']),
                    "cityname"      => strtoupper($val['cityname']),
                    "region"        => (!is_null($val['nscb_reg_name']) && @$val['nscb_reg_name'] <> '') ? strtoupper($val['nscb_reg_name']) : '',
                    "province"      => (!is_null($val['provname']) && @$val['provname'] <> '') ? strtoupper($val['provname']) : '',
                    "city"          => (!is_null($val['cityname']) && @$val['cityname'] <> '') ? strtoupper($val['cityname']) : '',     
                    "regioncode"    => (!is_null($val['regcode']) && @$val['region_code'] <> '') ? strtoupper($val['regcode']) : '',
                    "provincecode"  => (!is_null($val['provcode']) && @$val['province_code'] <> '') ? strtoupper($val['provcode']) : '',
                    "citycode"      => (!is_null($val['citycode']) && @$val['municipality_code'] <> '') ? strtoupper($val['citycode']) : '',
                ];
            }

            $return = [
                "results" => $earray,
                "pagination" => ["more"=>true]
            ];

        }
        catch (PDOException $e) {
          log_message("error",__METHOD__ ." |  ".$e->getMessage());
        } catch (Exception $e) {
          log_message("error",__METHOD__ ." |  ".$e->getMessage());
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