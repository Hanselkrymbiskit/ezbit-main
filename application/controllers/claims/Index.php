<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
        $this->load->model('claims/m_claims');
        $this->load->model('claims/m_claims_reports');
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

        $this->data['PageTitle'] = "Z-Benefit Claim Submission"; 
        $this->data['title'] = "Z-Benefit Claim Submission"; 
        //
        $pageheaderaction = '
                <div id="PageTitle-Button-Holder" class="float-right">
                    <button type="button" name="btn_claimsaction" id="claims_new" class="float-right btn btn-primary btn-block btn-round waves-effect waves-classic text-left font-size-16"><i class="fa fa-fw fa-plus mr-10 ml-10 pr-10" aria-hidden="true" style="border-right: 1px solid #787878;"></i>New Z-Benefit Claims</button>             
                </div>     
            ';


        if(!in_array((int) $this->userclassification,$this->m_general->HFUsers))
        {
            $pageheaderaction = '';
        }

        $this->data['pageheaderaction'] = @$pageheaderaction;

        $this->load->template('pages/claims/'.$index,  $this->data,'',1); // this will load the view file    
        // redirect('dashboard');
    } 

    function requestnewform($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => '<h5>Failed to Retrieve Form</h5>'
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
                // Verify Pre-Auth Case No if Approved
                $verify = $this->m_preauth->preauthstatus($SubmittedParam['CaseNo'],true);
                if($verify['Status'] == 1)
                {
                    $verify = $verify['Message'];
                    if( $verify['healthfacility_code'] <> $this->userregistrationinfo['HealthFacilityCode'] )
                    {
                       $return['Message'] = '<h5>Unauthorized User Detected!, You do not have access permission to file claims for this Pre-Authorization Case No. '.$SubmittedParam['CaseNo'].'</h5>';
                       goto exit_function_here;
                    }

                    if( (int) $verify['preauth_status'] <> 6 )
                    {
                        $return['Message'] = '<h5>Pre-Authorization Case No. '.$SubmittedParam['CaseNo'].' is not qualified to file Z Benefit Claims!</h5><h6 class="pt-15 pb-10 mb-0 border-top border-info">Pre-Authorization Status</h6><h4 class="border border-info p-10">'.$this->myutilities->getRef_Desc(202,$verify['preauth_status']).'</h4>';
                        goto exit_function_here;
                    }

                    if( (int) $this->userclassification == 6 )
                    {
                        
                        
                        if( !in_array(str_pad($verify['preauth_type'],2,0,STR_PAD_LEFT),$this->data['myPreAuthAccess']) )
                        {
                            $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                            goto exit_function_here;
                        }
                    }

                    $return = array(
                        'Status'    => 1,
                        'Message'   => $this->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],date("Ymd").":".$SubmittedParam['CaseNo'],'Mcrypt','aes-128','ecb')
                    );
                }
                else
                {
                    $return['Message'] = '<h5>Invalid Pre-Authorization Case No. '.$SubmittedParam['CaseNo'].'!</h5>';
                }
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

    function newclaims($param)
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
            'css/jquery-smartwizard/smart_wizard_all.min'
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

        $param = $this->m_api->encryptdecryptString('decrypt',$this->userprofile['User_E_Key'],base64_decode($param),'MCrypt','aes-128','ecb');
        $param = explode(":",$param);
        if($param[0] <> date("Ymd"))
        {
            redirect('claims');
        }
        
        $case_no = $param[1];
        $getPreAuthData = $this->m_preauth->getFormData($case_no,true);

        if( (int) $this->userclassification == 6 )
        {
            
            
            if( !in_array(str_pad($getPreAuthData['patientinfo']['preauth_type'],2,0,STR_PAD_LEFT),$this->data['myPreAuthAccess']) )
            {
                 redirect('claims');
            }
        }

        $PreAuthFormTitle = $this->myutilities->getRef_Desc(200,$getPreAuthData['patientinfo']['preauth_type']);
        $this->data['preauthFormData'] = $getPreAuthData;
        $this->data['SelectionCriteria'] = $this->m_preauth->getSelectionCriteria($getPreAuthData['patientinfo']['preauth_type']);
        $this->data['preauthforms'] = str_pad($getPreAuthData['patientinfo']['preauth_type'], 2,0,STR_PAD_LEFT);
        $this->data['PageTitle'] = "Z - Benefit Claims : ".$PreAuthFormTitle; 
        $this->data['title'] = "Z - Benefit Claims"; 
        $pageheaderaction = '';
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->template('pages/claims/form/'.$index,  $this->data,'',1); // this will load the view file    
        // redirect('dashboard');
    } 

    function submitform($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Process Submitted Z-Benefit Claim Form'
        );
    
        if( ($_SERVER['REQUEST_METHOD'] == 'POST' && $internal) || !in_array((int) $this->userclassification,$this->m_general->HFUsers))
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
                $return = array('Status' => 0, 'Message' => 'Failed to Process Submitted Z-Benefit Claim Form, Unauthorized Request Detected!');
                goto exit_function_here;
            }

            $claims_status = 1;
            $illnessType = @$illnessType[1];

            if( (int) $this->userclassification == 6 )
            {
                
                
                if( !in_array(str_pad($illnessType,2,0,STR_PAD_LEFT),$this->data['myPreAuthAccess']) )
                {
                    $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                    goto exit_function_here;
                }
            }

            $case_no = $this->m_api->encryptdecryptString('decrypt',$this->userprofile['User_E_Key'],$SubmittedParam['case_no']);

            if(!$case_no)
            {
                goto exit_function_here;
            }

            unset($SubmittedParam['illnessType']);
            unset($SubmittedParam['case_no']);

            $verify = $this->m_preauth->preauthstatus($case_no,true);


            if($verify['Status'] == 1)
            {
                $verify = $verify['Message'];
                if( $verify['healthfacility_code'] <> $this->userregistrationinfo['HealthFacilityCode'] )
                {
                   $return['Message'] = '<h5>Unauthorized User Detected!, You do not have access permission to file claims for this Pre-Authorization Case No. '.$case_no.'</h5>';
                   goto exit_function_here;
                }

                if( (int) $verify['preauth_status'] <> 6 )
                {
                    $return['Message'] = '<h5>Pre-Authorization Case No. '.$case_no.' is not qualified to file Z Benefit Claims!</h5><h6 class="pt-15 pb-10 mb-0 border-top border-info">Pre-Authorization Status</h6><h4 class="border border-info p-10">'.$this->myutilities->getRef_Desc(202,$verify['preauth_status']).'</h4>';
                    goto exit_function_here;
                }
            }
            else
            {
                $return['Message'] = '<h5>Invalid Pre-Authorization Case No. '.$case_no.'!</h5>';
            }


            $ClaimData = $this->m_claims->getFormData($case_no,true);
    
            if(is_array($ClaimData))
            {
                if( $ClaimData['claiminfo']['claims_status'] <> 4 )
                {
                    $return['Message'] = 'Failed to Update Z-Benefit Claim Status, Pre-Authorization Case No : '.$case_no.' already '.$this->myutilities->getRef_Desc(206,(int) $ClaimData['claiminfo']['claims_status']);
                    goto exit_function_here;
                }

                if( $ClaimData['claiminfo']['claims_status'] == 1 )
                {
                    $return['Message'] = 'Failed to Submit Z-Benefit Claim, Pre-Authorization Case No : '.$case_no.' already exists!';
                    goto exit_function_here;
                }

                $dupdates = true;
                $claims_status = 2;
            }

            if( !$dupdates && !is_array($SubmittedFiles) )
            {
                $return['Message'] = 'Failed to Submit Z-Benefit Claim, please attach required claim document attachments';
                goto exit_function_here;
            }

            $SubmittedParam['claiminfo']['preauth_type'] = $illnessType;
            $SubmittedParam['claiminfo']['case_no'] = $case_no;
            $SubmittedParam['claiminfo']['healthfacility_area']   = @$this->userregistrationinfo['AreaCode'];
            $SubmittedParam['claiminfo']['healthfacility_pro']    = @$this->userregistrationinfo['ProCode'];
            $SubmittedParam['claiminfo']['healthfacility_code']   = @$this->userregistrationinfo['HealthFacilityCode'];


            $required = $this->m_claims->getRequiredData($dupdates,$SubmittedParam);

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

            $validate = $this->m_claims->vFormData($required,$SubmittedParam);
            if( $validate['Status'] == 1 )
            {
                // Proceed Form Processing
                $return = $this->m_claims->ProcessClaim($required,$SubmittedParam,$SubmittedFiles,$dupdates,@$ClaimData);
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

                    $ttble = 'trans_claims_form';

                   
 
                break;

                case 2: // BAS - PRO
                case 3: // PRO 
                    $wtble = "a.healthfacility_pro = '".((int) $this->userregistrationinfo['ProCode'])."'";
                    $ttble = 'trans_claims_form';
                break;

                case 4:
                case 5:
                    $ttble = 'trans_claims_form';
                break;

                // ZPAMS-FIX (2026-09): this default case was missing -- any classification not
                // explicitly listed above (1, 2, 3, 4, 5) left $ttble undefined, breaking the
                // query built below for that user.
                default:
                    $ttble = 'trans_claims_form';
                break;
            }


            $table = "
                (
                    select  
                        a.*,
                        a.claims_status as 'TAT'
                    from ".$ttble." a 
                    left join user_profiles b on b.user_id = a.created_by
                    ".( (@$wtble) ? ' where '.@$wtble : '')."

                ) as PreAuth_Forms
            "; // target table = normal table, view table, virtual table
          
            // Table's primary key
            $primaryKey = 'rowid'; // Primary Key of the Table for normal table, 

            // ZPAMS-FIX (2026-09): 'local' is not a real DB connection group in this app's config
            // -- conProCode() could return it, and $this->{'local'} would fail. Mirrors the same
            // fallback already used by the Pre-Authorization module.
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
                    switch( (int) $row['claims_status'])
                    {
                        case 3:
                            $d2 = new DateTime($row['received_datetime']);
                        break;
                        case 4:
                            $d2 = new DateTime($row['claims_status_datetime']);
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
            $DataReturn['ClaimsStatus'] = $this->m_claims_reports->claims_status_stats(@$wtble);
            $DataReturn['ClaimsPrimaryStats'] = $this->m_claims_reports->claims_primary_stats(@$wtble);

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
            redirect('claims');
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
            $getClaimDataHistory = $this->m_claims->claimHistory('',$rowid);
            if( $getClaimDataHistory['Status'] == 0 )
            {
                redirect('claims');
            }

            $getClaimDataHistory = $getClaimDataHistory['Message'];
            $rekey = $this->myutilities->getUserProfile($getClaimDataHistory['created_by']);
            $datahist = json_decode($this->m_api->encryptdecryptString('decrypt',$rekey['User_E_Key'],base64_decode($getClaimDataHistory['dataarchives']),'MCrypt','aes-128','ecb'),true);
            if(is_array($datahist))
            {
                $getClaimData = $datahist;
                $this->data['currenthistoryid'] = $rowid;
                $this->data['currenthistorydate'] = $getClaimDataHistory['created_datetime'];
            }
        }
        else
        {
            $getClaimData = $this->m_claims->getFormData($rowid);
        }
        
        if( !is_array(@$getClaimData) )
        {
            redirect('claims');
        }

        switch($this->userclassification)
        {
            case 1:
            case 6:
            case 7:

                if( $getClaimData['claiminfo']['healthfacility_code'] <> $this->userregistrationinfo['HealthFacilityCode'] )
                {
                    redirect('claims');
                }
                
                if(!$this->data['historymode'])
                {
                    switch( (int) $getClaimData['claiminfo']['claims_status']  )
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

                if( $getClaimData['claiminfo']['healthfacility_pro'] <> $this->userregistrationinfo['ProCode'] )
                {
                    redirect('claims');
                }

            break;
        }

        $gclaimHistory = $this->m_claims->claimHistory($getClaimData['claiminfo']['case_no']);
        $this->data['claimsHistory'] = (is_array($gclaimHistory['Message'])) ? $gclaimHistory['Message'] : [];
        $gclaimHStatus = $this->m_claims->lastclaimStatus($getClaimData['claiminfo']['case_no']);
        $this->data['claimsHStatus'] = (is_array($gclaimHStatus['Message'])) ? $gclaimHStatus['Message'] : [];
        $illnessType = (int) $getClaimData['claiminfo']['preauth_type'];

        if( (int) $this->userclassification == 6 )
        {
            
            
            if( !in_array(str_pad($illnessType,2,0,STR_PAD_LEFT),$this->data['myPreAuthAccess']) )
            {
                 redirect('claims');
            }
        }

        $PreAuthFormTitle = $this->myutilities->getRef_Desc(200,$illnessType);
        $this->data['preauthforms'] = str_pad($illnessType, 2,0,STR_PAD_LEFT);
        $this->data['PageTitle'] = "Z - Benefit Claims : ".$PreAuthFormTitle; 
        $this->data['title'] = "Z - Benefit Claims"; 
        $this->data['claimsFormData'] = $getClaimData;
        $getPreAuthData = $this->m_preauth->getFormData($getClaimData['claiminfo']['case_no'],true);
        if(!is_array($getPreAuthData))
        {
            redirect('claims');
        }
        $this->data['preauthFormData'] = $getPreAuthData;
        if(!$this->data['historymode'])
        {
            switch($getClaimData['claiminfo']['claims_status'])
            {
                case 1: // Submitted
                case 2: // Re-Submitted
                    $actionBtn = '<button type="button" name="btn_claimaction" id="receivedClaim" class="btn btn-warning waves-effect waves-classic mr-20 font-size-16" title="Received Z - Benefit Claims Request" ><i class="fa fa-fw fa-file mr-10" aria-hidden="true"></i>Receive Z - Benefit Claims</button>';
                break;

                case 3: // Received
                    $actionBtn = '
                    <div class="btn-group mr-20 font-size-16" role="group">
                        <button type="button" class="btn btn-dark text-white font-weight-bold dropdown-toggle waves-effect waves-classic" id="actionBtnGroup" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-fw fa-wpforms mr-10"></i>Z-Benefit Claim Status
                        </button>
                        <div class="dropdown-menu" aria-labelledby="actionBtnGroup" role="menu" style="will-change: transform;">
                            <a class="dropdown-item font-size-16 font-weight-bold text-dark" id="forcomplianceClaim" name="btn_claimaction" href="javascript:void(0)" astatus="4" role="menuitem">For Compliance</a>
                            <a class="dropdown-item font-size-16 font-weight-bold text-success" id="approveClaim" name="btn_claimaction" href="javascript:void(0)"  astatus="6" role="menuitem">Approve</a>
                            <a class="dropdown-item font-size-16 font-weight-bold text-danger" id="disapproveClaim" name="btn_claimaction" href="javascript:void(0)"  astatus="5" role="menuitem">Disapprove</a>
                        </div>
                    </div>
                    ';
                break;
            }
        }

        // ZPAMS-FIX (2026-09): "Print/Download Z-Benefit Claim" previously had no onclick
        // handler at all -- the button did nothing, and the JS side ('printClaim': break;)
        // never implemented it either. A claim has no form of its own: it references its
        // originating pre-authorization case_no, so printing a claim regenerates that same
        // case's Annex A PDF via the method both this controller and Pre-Authorization's
        // view() now share (MY_Controller::generatePreAuthPDF()).
        $fileClick = $this->generatePreAuthPDF($getPreAuthData, $PreAuthFormTitle, $this->data['ViewMode']);
        if( @$this->data['ViewMode'] == true && !@$fileClick )
        {
            $fileClick = 'onclick="javascript:toastr.error(\'Unable to Generate PDF\',\'Print/Download\');"';
        }

        $pageheaderaction = '
        <div id="PageTitle-Button-Holder" class="float-right">
            '.(($this->userclassification > 1) ? @$actionBtn : '').'
            <button type="button" name="btn_claimaction" id="backClaim" class="btn btn-dark btn-round waves-effect waves-classic text-left font-size-16" title="Back to Z-Benefit Claims List" onclick="window.location.href=\''.site_url('claims').'\'"><i class="fa fa-fw fa-arrow-left" aria-hidden="true"></i></button>
            <button type="button" name="btn_claimaction" id="printClaim" class="btn btn-dark btn-round waves-effect waves-classic text-left font-size-16" title="Print/Download Z-Benefit Claim" '.@$fileClick.'><i class="fa fa-fw fa-print" aria-hidden="true"></i></button>
        </div>
        ';

        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->template('pages/claims/form/'.$index,  $this->data,'',1); // this will load the view file    
     
    } 

    function receivedClaim($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => ''
        );
    
        if( ($_SERVER['REQUEST_METHOD'] == 'POST' && $internal) || in_array((int) $this->userclassification,$this->m_general->HFUsers) )
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

            $ClaimData = $this->m_claims->getFormData($case_no,true);
            if(!$ClaimData)
            {
                $return['Message'] = 'Failed to Receive Z-Benefit Claim Form, Invalid Pre-Authorization Case No!';
                goto exit_function_here;
            }

            if((int) $ClaimData['claiminfo']['healthfacility_pro'] <> (int) $this->userregistrationinfo['ProCode'])
            {
                $return['Message'] = 'Failed to Receive Z-Benefit Claim Form, You are trying to receive Z-Benefit Claim with Pre-Authorization Case No that is not within your jurisdiction!';
                goto exit_function_here;
            }

            if( !in_array((int) $ClaimData['claiminfo']['claims_status'],[1,2]) )
            {
                $return['Message'] = 'Failed to Receive Z-Benefit Claim Form, Pre-Authorization Case No : '.$ClaimData['claiminfo']['case_no'].' current status ['.$this->myutilities->getRef_Desc(202,$ClaimData['claiminfo']['claims_status']).']';
                goto exit_function_here;
            }

            // Process Receive Pre-Authorization
            $actnResult = $this->m_claims->claimAction((int) $ClaimData['claiminfo']['preauth_type'],$case_no,3,$ClaimData['claiminfo']['remarks'],$this->myutilities->getRef_Desc(104,$ClaimData['claiminfo']['healthfacility_code']),$ClaimData['claiminfo']['created_by']);
            if($actnResult['Status'] == 1)
            {
                $return['Message'] = 'Z-Benefit Claim with Pre-Authorization Case No : '.$ClaimData['claiminfo']['case_no'].' Successfully Received!';
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

    function astatusClaim($internal=false,$formparameter = '')
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

            $ClaimData = $this->m_claims->getFormData($case_no,true);
            if(!$ClaimData)
            {
                $return['Message'] = 'Failed to Receive Z-Benefit Claim Form, Invalid Pre-Authorization Case No!';
                goto exit_function_here;
            }

            if((int) $ClaimData['claiminfo']['healthfacility_pro'] <> (int) $this->userregistrationinfo['ProCode'])
            {
                $return['Message'] = 'Failed to Receive Pre-Authorization Form, You are trying to receive Pre-Authorization Case No that is not within your jurisdiction!';
                goto exit_function_here;
            }

            if((int) $ClaimData['claiminfo']['claims_status'] > 4)
            {
                $return['Message'] = 'Failed to Update Pre-Authorization Status, Pre-Authorization Case No : '.$case_no.' already '.$this->myutilities->getRef_Desc(202,(int) $ClaimData['claiminfo']['claims_status']);
                goto exit_function_here;
            }

            $actnResult = $this->m_claims->claimAction((int) $ClaimData['claiminfo']['preauth_type'],$case_no,$SubmittedParam['claims_status'],@$SubmittedParam['remarks'],$this->myutilities->getRef_Desc(104,$ClaimData['claiminfo']['healthfacility_code']),$ClaimData['claiminfo']['created_by']);
        
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
}