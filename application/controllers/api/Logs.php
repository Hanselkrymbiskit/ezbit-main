<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logs extends MY_Controller {

	function __construct()
	{
		 // phpinfo();
        parent::__construct();
        $this->data['pagetype'] = "API Logs";
        $this->breadcrumbs->push('<i class="fa fa-file-text-o"></i>&nbsp;Logs', '/api/logs');
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

        $this->data['PageTitle'] = "API LOGS";     
        $this->load->template('pages/api/logs/'.$index,  $this->data,'',1); // this will load the view file  
    } 

    function datalist()
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }

        try
        {
            $DataTableConfig = @$_SESSION['DataTables'][$_REQUEST['TableID']];
            $table = "
             (
                select 
                    vTbl1.*,
                    r.HealthFacilityCode as 'FacilityCode',
                    r.FacilityName as 'FacilityName',
                    o.organizationid

                from   
                (
                        select 
                           
                            a.id,
                            a.uri,
                            a.method,
                            a.uriargs,
                            a.api_key,
                            a.ip_address,
                            a.time,
                            a.rtime,
                            a.authorized,
                            a.response_code,
                            a.resourcedataid,
                            a.id as DataCount,
                            DATE_FORMAT(FROM_UNIXTIME(a.`time`),'%Y-%m-%d') as 'logDate',
                            DATE_FORMAT(FROM_UNIXTIME(a.`time`),'%H:%i:%s') as 'logTime',
                            CASE 
                                WHEN b.user_id IS NOT NULL THEN b.user_id 
                                WHEN c.user_id IS NOT NULL THEN c.user_id 
                                WHEN b.user_id IS NULL AND c.user_id IS NULL THEN NULL 
                            END as 'user_id'
                        from rest_api_logs a 
                        left join rest_api_keys b on b.api_key = a.api_key 
                        left join rest_api_keys_archive c on c.api_key = a.api_key 
                ) as vTbl1

                left join user_register r on r.user_id = vTbl1.user_id 
                left join tbl_resource_organization o on o.hfhudcode = r.HealthFacilityCode

             ) as vTbl0
            "; // target table = normal table, view table, virtual table
          
            // Table's primary key
            $primaryKey = 'id'; // Primary Key of the Table for normal table, 

            /* Create Where Clauses for SQL Statement */
            $aliasParam = array(); // Used to replace custom Search Variable to the target column field
            $specialParam = array(); 
            $whereResult = $this->datatables->customWhereStatement($_REQUEST,@$aliasParam,@$specialParam);

            // Additional Filter
            $DefaultFilter = "";
            $StatFilter = '';
            
            $api_key_filter = (@$_POST['pushparameter']['api_key_filter'] <> '') ? $_POST['pushparameter']['api_key_filter'] : '';

            if( $api_key_filter )
            {
                $apki = implode("','",$api_key_filter);

                $DefaultFilter = "api_key in ('".$apki."')";
                $StatFilter = $DefaultFilter;
            }
          
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
                    
                    $logid = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$d);

                    // $x = $row;
                    // $x['logDate'] = date("m/d/Y",strtotime($x['logDate']));
                    // $x['logTime'] = date("h:i:s A",strtotime($x['logTime']));
                    // $x['FacilityName'] = (!is_null($x['FacilityName']) && $x['FacilityName'] <> '') ? strtoupper($x['FacilityName']) : '';
                    // $x['organizationid'] = (int) $x['organizationid'];
                    // $x['aresponseparams'] = ($x['aresponseparams'] == '') ? '' : $x['aresponseparams'];


                    // $x = json_encode($x);

                    // $d = '<a href="javascript:void(0);" onclick="vlD(\''.base64_encode($x).'\')" class="" title="View Details" id="vd_'.$logid.'"  name="vd_'.$logid.'" buttongroup="viewbtnAction"><i class="fa fa-search"></i></a>';

                    $d = '<a href="javascript:void(0);" onclick="vlD(\''.$logid.'\')" class="" title="View Details" id="vd_'.$logid.'"  name="vd_'.$logid.'" buttongroup="viewbtnAction"><i class="fa fa-search"></i></a>';

                    return $d; 
                },  

                'organizationid' => function($d, $row, $fieldid, $TargetTableID,$colid){
                
                    return ($d == "") ? '' : (int) $d; 
                },    
                
                'uri' => function($d, $row, $fieldid, $TargetTableID,$colid){
                
                    return ($row['uriargs'] == "") ? $d : $row['uriargs']; 
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

            $this->load->model('api/m_logs');
            $DataReturn['MiniStats'] = [];
            $getmethodstats = $this->m_logs->stats_method($StatFilter);
            $gethttpstats = $this->m_logs->stats_httpresponse($StatFilter);
            $DataReturn['MiniStats']['METHOD'] = $getmethodstats;
            $DataReturn['MiniStats']['HTTP'] = $gethttpstats;
            if( $api_key_filter <> '')
            {
                $gethttpstats = $this->m_logs->stats_fhirhttpresponse($StatFilter);
                $DataReturn['MiniStats']['FHIRRESOURCE'] = $gethttpstats;
            }
            // Return Data as Json Format
            echo json_encode($DataReturn);
        }
        catch(Exception $err)
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
    }

    function datadetails($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Retrieve Form'
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
            $SubmittedParam['logid'] = ( @$SubmittedParam['logid'] <> '') ? $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$SubmittedParam['logid']) : '';
            if($SubmittedParam['logid'] == '')
            {
                $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                goto exit_function_here;
            }

            $qry = "
                select 
                    vTbl1.*,
                    r.HealthFacilityCode as 'FacilityCode',
                    r.FacilityName as 'FacilityName',
                    o.organizationid

                from   
                (
                        select 
                            a.*,
                            a.id as DataCount,
                            DATE_FORMAT(FROM_UNIXTIME(a.`time`),'%Y-%m-%d') as 'logDate',
                            DATE_FORMAT(FROM_UNIXTIME(a.`time`),'%H:%i:%s') as 'logTime',
                            TO_BASE64(CAST(aes_decrypt(from_base64(a.params),a.api_key) AS CHAR CHARSET utf8mb4))  as 'aparams',
                            TO_BASE64(CAST(aes_decrypt(from_base64(a.responseparams),a.api_key) AS CHAR CHARSET utf8mb4))  as 'aresponseparams',
                            CASE 
                                WHEN b.user_id IS NOT NULL THEN b.user_id 
                                WHEN c.user_id IS NOT NULL THEN c.user_id 
                                WHEN b.user_id IS NULL AND c.user_id IS NULL THEN NULL 
                            END as 'user_id'
                        from rest_api_logs a 
                        left join rest_api_keys b on b.api_key = a.api_key 
                        left join rest_api_keys_archive c on c.api_key = a.api_key 

                        where a.id = '".$SubmittedParam['logid']."'
                ) as vTbl1

                left join user_register r on r.user_id = vTbl1.user_id 
                left join tbl_resource_organization o on o.hfhudcode = r.HealthFacilityCode
            ";

            $res = $this->sqlhelper->local->sql($qry)->row();
            $return['Status'] = 1;

            $x = $res['Data'];
            if(count($x) > 0)
            {
                $x['logDate'] = date("m/d/Y",strtotime($x['logDate']));
                $x['logTime'] = date("h:i:s A",strtotime($x['logTime']));
                $x['FacilityName'] = (!is_null($x['FacilityName']) && $x['FacilityName'] <> '') ? strtoupper($x['FacilityName']) : '';
                $x['organizationid'] = (int) $x['organizationid'];
                $x['params'] = base64_encode($x['params']);
                $x['aresponseparams'] = ($x['aresponseparams'] == '') ? '' : $x['aresponseparams'];
            }

            $x = base64_encode(json_encode($x));
            $return['Message'] = $x;


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