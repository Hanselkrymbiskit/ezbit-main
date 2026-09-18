<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Access extends MY_Controller {

	function __construct()
	{
		 // phpinfo();
        parent::__construct();
        $this->data['pagetype'] = "ACCESS";
        $this->breadcrumbs->push('<i class="fa fa-key"></i>&nbsp;Access', '/api/access');
	}

    function index()
    {
        if($this->usertype > 3)
        {
            $userlink = base64_encode($this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$this->userid));
            redirect('api/access/details/'.$userlink);
        }

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

        $index = 'index';

        $this->data['PageTitle'] = "API ACCESS";     
        $this->load->template('pages/api/access/'.$index,  $this->data,'',1); // this will load the view file  
    } 

    function datalist()
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }

        try
        {
            //select 

                    //     a.*,
                    //     a.id as DataCount,
                    //     d.FacilityName,
                    //     d.HealthFacilityCode as FacilityCode,
                    //     d.FacilityContactNo,
                    //     e.organizationid,
                    //     f.User_E_Key,
                    //     cast(aes_decrypt(from_base64(`f`.`CompleteName`),(select `system_settings`.`SettingValue` from `system_settings` where (`system_settings`.`DataID` = 1))) as char charset utf8mb4) as 'Account_Name',
                    //     d.emailaddress,
                    //     JSON_ARRAYAGG(b.controller) as controller,
                    //     count(a.*) as KeyCount
                    // from rest_api_keys a 
                    // left join rest_api_access b  on b.`key` = a.api_key 
                    // left join user_register d on d.user_id = a.user_id 
                    // left join tbl_resource_organization e on e.hfhudcode = d.HealthFacilityCode
                    // left join user_profiles f on f.user_id = a.user_id 
                    // where d.user_type > 3
                    // group by d.HealthFacilityCode 

            $DataTableConfig = @$_SESSION['DataTables'][$_REQUEST['TableID']];
            $table = "
             (
                select 
                    
                    vTbl1.*

                from   
                (
                    select 
                        a.id as DataCount,
                        d.FacilityName,
                        d.HealthFacilityCode as FacilityCode,
                        d.FacilityContactNo,
                        e.organizationid,
                        count(*) as KeyCount
                    from rest_api_keys a 
                    left join user_register d on d.user_id = a.user_id 
                    left join tbl_resource_organization e on e.hfhudcode = d.HealthFacilityCode
                    left join user_profiles f on f.user_id = a.user_id 
                    where d.user_type > 3
                    group by d.HealthFacilityCode 

                ) as vTbl1


             ) as vTblAccess
            "; // target table = normal table, view table, virtual table
          
            // Table's primary key
            $primaryKey = 'DataCount'; // Primary Key of the Table for normal table, 

            /* Create Where Clauses for SQL Statement */
            $aliasParam = array(); // Used to replace custom Search Variable to the target column field
            $specialParam = array(); 
            $whereResult = $this->datatables->customWhereStatement($_REQUEST,@$aliasParam,@$specialParam);

            // Additional Filter
            $DefaultFilter = "";
            $StatFilter = '';
            
            // if( (int) $this->usertype > 3 )
            // {
            //     switch( (int) $this->userclassification )
            //     {
            //         // case 2: // Regional Office
            //         //     $DefaultFilter = "Region = '".$this->userregistrationinfo['Region']."' ";
            //         //     $StatFilter = $DefaultFilter;
            //         // break;
            //         // case 3: // Provincial Office
            //         //     $DefaultFilter = "Province = '".$this->userregistrationinfo['Province']."' ";
            //         //     $StatFilter = $DefaultFilter;
            //         // break;
            //         // case 4: // City / Municipal Office
            //         //     $DefaultFilter = "City = '".$this->userregistrationinfo['City']."' ";
            //         //     $StatFilter = $DefaultFilter;
            //         // break;
            //         // case 5:
            //         //     $DefaultFilter = "LaboratoryID = '".$this->userregistrationinfo['LaboratoryID']."' "; 
            //         //     $StatFilter = "LaboratoryID = '".$this->userregistrationinfo['LaboratoryID']."'";
            //         // break;
            //     }
            // }
            
            $DefaultFilter .= (@$DefaultFilter <> '') ? '' : ''; 
            
            if( $whereResult <> '' )
            {
                $whereResult = " (".$whereResult.") ";
            }

            $whereResult .= (trim($whereResult) <> '') ? ( (@$DefaultFilter <> '') ? ' and '.$DefaultFilter : '') : @$DefaultFilter;
          
            $groupBy = '';

            /* Remove in Final Post Parameter */
            $unsetArray = array('addedFilter','addedFilterMode','StrictSearchMode');
            foreach($unsetArray as $unsetkeys)
            {
                if( isset($_REQUEST[ $unsetkeys]) ){ unset($_REQUEST[$unsetkeys]); }
            }     

            // use to override datatable return function call
            // _crud_view, _crud_edit, _crud_delete
            $overRideFunctionCall = array(
                
                'DataCount_crud_view' => function($d, $row, $fieldid, $TargetTableID,$colid){
                    
                    $user_id = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$row['FacilityCode']);

                    $d = '<a href="'.site_url('api/access/details/'.base64_encode($user_id)).'"  class="" title="View Details" id="vd_'.$row['DataCount'].'"  name="vd_'.$row['DataCount'].'" buttongroup="viewbtnAction"><i class="fa fa-search"></i></a>';

                    return $d; 
                },  

                'organizationid' => function($d, $row, $fieldid, $TargetTableID,$colid){
                
                    return ($d == "") ? '' : (int) $d; 
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

            // $this->load->model('api/m_logs');
            // $DataReturn['MiniStats'] = [];
            // $getmethodstats = $this->m_logs->stats_method($StatFilter);
            // $gethttpstats = $this->m_logs->stats_httpresponse($StatFilter);
            // $DataReturn['MiniStats']['METHOD'] = $getmethodstats;
            // $DataReturn['MiniStats']['HTTP'] = $gethttpstats;
            
            // Return Data as Json Format
            echo json_encode($DataReturn);
        }
        catch(Exception $err)
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
    }

    function details($userid = '')
    {
        $this->load->model('api/m_access');
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

        $index = 'index';
        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
                <button type="button" id="btn_Back" name="btnActionButton" class="btn btn-dark mr-5 text-uppercase" title="Back to List" onclick="location.href=\''.site_url('api/access').'\'">
                    <i class="fa fa-list mr-10"></i> Back to List
                </button>
            </div>      
        ';

        if($this->usertype > 3)
        {
            $pageheaderaction = '';

            if( @$userid <> '' && base64_decode($userid) )
            {
                $userid = $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],base64_decode($userid));

                $accessinfo = $this->m_access->getaccess_info($userid);
               
                $archivekey = $this->m_access->getarchivekey($userid);
                $archivekey[] = $accessinfo['api_key'];

            }

            $this->data['accessinfo'] = $accessinfo;
            $this->data['api_key_filter'] = $archivekey;

        }
        else
        {
            $healthfacilitycode = $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],base64_decode($userid));
            $qry = "
                select 

                     a.*,
                     a.id as DataCount,
                     d.FacilityName,
                     d.HealthFacilityCode as FacilityCode,
                     d.FacilityContactNo,
                     e.organizationid,
                     f.User_E_Key,
                     cast(aes_decrypt(from_base64(`f`.`CompleteName`),(select `system_settings`.`SettingValue` from `system_settings` where (`system_settings`.`DataID` = 1))) as char charset utf8mb4) as 'Account_Name',
                     d.emailaddress,
                     JSON_ARRAYAGG(b.controller) as controller
                 from rest_api_keys a 
                 left join rest_api_access b  on b.`key` = a.api_key 
                 left join user_register d on d.user_id = a.user_id 
                 left join tbl_resource_organization e on e.hfhudcode = d.HealthFacilityCode
                 left join user_profiles f on f.user_id = a.user_id 
                 where d.HealthFacilityCode = '".@$healthfacilitycode."'
                 group by a.api_key 
            ";

            $rsql = $this->sqlhelper->local->sql($qry)->result();
            $archivekey = [];
            foreach($rsql['Data'] as $rr => $cc)
            {
                $akeys = $this->m_access->getarchivekey($cc['user_id']);
                $archivekey[] = $cc['api_key'];
                if(count($akeys) > 0)
                {
                    foreach($akeys as $apikey_row => $apikey_val)
                    {
                        $archivekey[] = $apikey_val;
                    }

                }
            }
            
            $this->data['accessinfo'] = $rsql['Data'];
            $this->data['api_key_filter'] = $archivekey;
        }

        $this->data['PageTitle'] = "API ACCESS DETAILS".@$pageheaderaction;     
        $this->load->template('pages/api/access/details/'.$index,  $this->data,'',1); // this will load the view file  
    }
}