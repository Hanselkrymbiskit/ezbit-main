<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->data['pagetype'] = "faq";

        if( $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
            {
               $return = array('Status' => 0, 'Message' => 'Permission Denied!');

               echo json_encode($return);

            }
            else
            {
              unset($_POST['UToken']);
            }
        }

        if($this->system_settings['DownloadModule'] <> 1)
        {
            if( $_SERVER['REQUEST_METHOD'] == 'POST')
            {
               $return = array('Status' => 0, 'Message' => 'Download Page Doesn\'t Exists!');

               echo json_encode($return);
            }
            else
            {
                redirect(($this->tank_auth->is_logged_in()) ? 'home' : "login");
            }
        }
    }

    function index()
    {
        $this->data['title'] = "FAQs"; 
        $this->data['PageTitle'] = "FAQs"; 
        $indexFile = 'index';
        $headerfooter = false;

         $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/formvalidation/formValidation',        
            'global/vendor/summernote/summernote-lite',
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/bootstrap-clockpicker.min',
            'global/vendor/formatter/jquery.formatter',
            'global/vendor/formvalidation/formValidation.min',
            'global/vendor/formvalidation/framework/bootstrap4.min',
            'global/vendor/jquery-labelauty/jquery-labelauty', 
            'global/vendor/summernote/summernote.min',
            'js/admin/faq',
        );

        $this->data['loadjschild'] = array(
            'global/js/Plugin/bootstrap-datepicker',
            'global/js/Plugin/clockpicker',
            'global/js/Plugin/formatter',
            'global/js/Plugin/jquery-labelauty',
            'global/js/Plugin/responsive-tabs',
            'global/js/Plugin/closeable-tabs',
            'global/js/Plugin/summernote',

        ); 

        if( $this->tank_auth->is_logged_in() )
        {
            $indexFile =  'index2';
            $headerfooter = true;
            $this->data['title'] = "FAQs"; 
            $this->data['PageTitle'] = "Frequently Asked Questions"; 
            $pageheaderaction = '
                <div id="PageTitle-Button-Holder" class="float-right">
                    <button type="button" id="btn_newFAQs" name="btnActionButton" class="btn btn-dark mr-5" title="New Download Item">
                        <i class="fa fa-plus mr-10"></i>New FAQs
                    </button>
                </div>      
            ';

            if( $this->usertype > 3 )
            {
                $pageheaderaction = '';
            }
             
           
            $this->data['pageheaderaction'] = @$pageheaderaction;  
        }

        $this->load->template('templates/faq/'.$indexFile,$this->data,'',$headerfooter);     
    }

    function faq_List()
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }

        try
        {
            $DataTableConfig = @$_SESSION['DataTables'][$_REQUEST['TableID']];
            
            $table = "system_faq_list"; // target table = normal table, view table, virtual table
            //a.QRCodeImage,
            // Table's primary key
            $primaryKey = 'DataID'; // Primary Key of the Table for normal table, 

            /* Create Where Clauses for SQL Statement */
            $aliasParam = array(); // Used to replace custom Search Variable to the target column field
            $specialParam = array(); 
            $whereResult = $this->datatables->customWhereStatement($_REQUEST,@$aliasParam,@$specialParam);

            // Additional Filter
            $DefaultFilter = "Enable = 'Y'";
            if( $this->usertype < 4)
            {
                $DefaultFilter = '';
            }
            $StatFilter = '';
            
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

            //$this->load->model('api/m_api');

            // use to override datatable return function call
            // _crud_view, _crud_edit, _crud_delete
            $overRideFunctionCall = array(
                
                'DataID_crud_view' => function($d, $row, $fieldid, $TargetTableID,$colid){
                    
                    $FAQDetails = [
                        'Category' => strtoupper($this->myutilities->getRef_Desc(9,$row['Category'])),
                        'Questions'=> strtoupper($row['Title']),
                        'Answer'   => $row['Message_Content'],
                    ];


                    $d = '<a href="javascript:void(0)" class="" title="VIEW ANSWER" onclick="faqanswer(\''.base64_encode(json_encode($FAQDetails)).'\')" buttongroup="viewbtnAction"><i class="fa fa-search"></i></a>';

                    return $d; 
                },    

                'DataID_crud_edit' => function($d, $row, $fieldid, $TargetTableID,$colid){
                    
                    $FaqDataID = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$d);

                    $d = '<a href="javascript:void(0);" onclick="newfaq(\''.base64_encode($FaqDataID).'\');" class="" title="Edit Details" id="edf_'.$FaqDataID.'"  name="edf_'.$FaqDataID.'" buttongroup="viewbtnAction"><i class="fa fa-edit"></i></a>';

                    if( $this->usertype > 3 )
                    {
                        $d='';
                    }

                    return $d; 
                }, 

                'DataID_crud_delete' => function($d, $row, $fieldid, $TargetTableID,$colid){
                    
                    $FaqDataID = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$d);

                    $d = '<a href="javascript:void(0);" onclick="delfaq(\''.base64_encode($FaqDataID).'\',\''.strtoupper($row['Title']).'\');" class="" title="Delete Details" id="edf_'.$FaqDataID.'"  name="edf_'.$FaqDataID.'" buttongroup="viewbtnAction"><i class="fa fa-trash"></i></a>';

                    if( $this->usertype > 3 )
                    {
                        $d='';
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

            // $this->load->model('masterlist/m_masterlist');

            // $getMasterListStat = $this->m_masterlist->masterliststat('healthfacility',$StatFilter);
            // $DataReturn['MiniStats'] = $getMasterListStat;
            
            // Return Data as Json Format
            echo json_encode($DataReturn);
        }
        catch(Exception $err)
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
    }
    
    function getform($internal=false,$formparameter = '')
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
            if( @$SubmittedParam['DataID'] <> '' )
            {
               
                //$this->load->model('api/m_api');
                $this->data['DataID'] =  $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],base64_decode(@$SubmittedParam['DataID']));

                $getFAQData = $this->sqlhelper->local->select("system_faq_list")->where("DataID = '".$this->data['DataID']."'")->row();
                if($getFAQData['Count'] == 0)
                {
                    $return = array('Status' => 0, 'Message' => "FAQ Data doesn't exist!");
                    goto exit_function_here;
                }

                $this->data['FAQData'] = $getFAQData['Data'];
                // $VDResult = $this->m_vaccination->getVaccinationData($this->data['CitizenDataID'],$this->data['DataCount']);
                // $this->data['VaccinationData'] = $VDResult['VaccinationData'];
                // $this->data['CitizenData'] = $VDResult['CitizenData'];
                // $this->data['CitizenData']['DateofBirth'] = date("m/d/Y",strtotime($this->data['CitizenData']['DateofBirth']));
                // $this->data['vaccinetypelist'] = [];
                // $this->data['VDData'] = ['VaccineDetails'=>[]];
                // foreach($this->data['VaccinationData'] as $vdRow => $VdCol)
                // {
                //     $this->data['vaccinetypelist'][] = $VdCol['Vaccine_Type'];
                //     $this->data['VDData']['VaccineDetails'][$VdCol['Vaccine_Type']] = $VdCol;
                // }

            }
        
            // log_message("error", $this->data['VPData']);
            $faqForm = $this->load->view('templates/faq/new/index',  $this->data,true); 
            $return['Status'] = 1;
            $return['Message'] = 'FAQ Form Successfully Retrieved';
            $return['FormHTML'] = base64_encode($faqForm);
            // $return['FormJS'] = '';
            $return['FormJS'] = base64_encode(str_replace(array('<script id="removethis" nonce="'.@$this->nonceV.'" type="text/javascript">','</script>'),'',$this->load->view('templates/faq/new/indexjs',  $this->data,true)));
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
    
    function submitform($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Process Submitted Form!'
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
            if( $this->usertype > 3 )
            {
                $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                goto exit_function_here;
            }
            
            // Validation
            if( isset($SubmittedParam['faqid']) )
            {
                if( base64_decode(base64_decode($SubmittedParam['faqid'])) <> '' )
                {
                    $getFaqData = $this->sqlhelper->local->select("system_faq_list",)->where("DataID = '".base64_decode(base64_decode($SubmittedParam['faqid']))."'")->row();
                    if( $getFaqData['Count'] == 0 )
                    {
                        $return['Message'] = "FAQ Data doesn't Exists!";
                    }
                    else
                    {
                        $DuplicateTitle = false;
                        if( preg_replace("/[^A-Za-z0-9\\\\xF1\\\\xD1]/", "", @$SubmittedParam['Title'] ) <> preg_replace("/[^A-Za-z0-9\\\\xF1\\\\xD1]/", "", $getFaqData['Data']['Title']  ) )
                        {
                            $getFAQTitle = $this->sqlhelper->local->select('system_faq_list')->where("LOWER(REGEXP_REPLACE(`Title`, '[^A-Za-z0-9\\\\xF1\\\\xD1]', '')) = LOWER(REGEXP_REPLACE('".$this->db->escape_str($SubmittedParam['Title'])."','[^A-Za-z0-9\\\\xF1\\\\xD1]', '')) and DataID <> '".base64_decode(base64_decode($SubmittedParam['faqid']))."'")->row();
                            if( $getFAQTitle['Count'] > 0 )
                            {
                                $DuplicateTitle = true;
                            }
                        }

                        if( !$DuplicateTitle )
                        {
                            // Update
                            $UpdateFAQ = [
                                'Category'          => @$SubmittedParam['Category'],
                                'Title'             => $this->db->escape_str(@$SubmittedParam['Title']),
                                'Message_Content'   => base64_encode(@$SubmittedParam['Message_Content']),
                                'Enable'            => (@$SubmittedParam['Enable'] == 'Y' || @$SubmittedParam['Enable'] == 'N') ? $SubmittedParam['Enable'] : 'N',
                                'Updated_DateTime'  => date("Y-m-d H:i:s"),
                                'Updated_By'        => $this->userid,
                            ];

                            $uFAQData = $this->sqlhelper->remote1->update('system_faq_list')->ex_update($UpdateFAQ)->where("DataID = '".base64_decode(base64_decode($SubmittedParam['faqid']))."'")->run();
                            if($uFAQData['ErrorCode'] == '')
                            {
                                $return['Status']  = 1;
                                $return['Message'] = 'FAQ Successfully Updated!';
                            }

                        }
                        else
                        {
                            $return['Message'] = 'Invalid FAQ Form Data, Duplicate FAQ Title!';
                        }
                        
                    }
                    
                }
            }
            else
            {
                $getFAQTitle = $this->sqlhelper->local->select('system_faq_list')->where("LOWER(REGEXP_REPLACE(`Title`, '[^A-Za-z0-9\\\\xF1\\\\xD1]', '')) = LOWER(REGEXP_REPLACE('".$this->db->escape_str($SubmittedParam['Title'])."','[^A-Za-z0-9\\\\xF1\\\\xD1]', ''))")->row();
                if( $getFAQTitle['Count'] > 0 )
                {
                    $return['Message'] = 'Invalid FAQ Form Data, Duplicate FAQ Title!';
                }
                else
                {
                    // Insert
                    $InsertFAQ = [
                        'Category'          => @$SubmittedParam['Category'],
                        'Title'             => $this->db->escape_str(@$SubmittedParam['Title']),
                        'Message_Content'   => base64_encode(@$SubmittedParam['Message_Content']),
                        'Enable'            => (@$SubmittedParam['Enable'] == 'Y' || @$SubmittedParam['Enable'] == 'N') ? $SubmittedParam['Enable'] : 'N',
                        'Created_DateTime'  => date("Y-m-d H:i:s"),
                        'Created_By'        => $this->userid,
                    ];

                    $iFAQData = $this->sqlhelper->remote1->insert('system_faq_list')->ex_insert($InsertFAQ)->run();
                    if($iFAQData['ErrorCode'] == '')
                    {
                        $return['Status']  = 1;
                        $return['Message'] = 'New FAQ Successfully Submitted!';
                    }
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

    function deleteFAQ($internal=false,$formparameter = '')
    {
        $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Process Submitted Form!'
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
            if( $this->usertype > 3 )
            {
                $return = array('Status' => 0, 'Message' => 'Permission Denied!');
                goto exit_function_here;
            }
           
            // Validation
            if( isset($SubmittedParam['faqid']) )
            {
                //$this->load->model('api/m_api');
                $SubmittedParam['faqid'] =  $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],base64_decode(@$SubmittedParam['faqid']));

                
                if( $SubmittedParam['faqid'] <> '' )
                {
                    $getFaqData = $this->sqlhelper->local->select("system_faq_list",)->where("DataID = '".$SubmittedParam['faqid']."'")->row();
                    if( $getFaqData['Count'] == 0 )
                    {
                        $return['Message'] = "FAQ Data doesn't Exists!";
                    }
                    else
                    {
                       // Delete
                       $delFAQData = $this->sqlhelper->remote1->delete("system_faq_list",)->where("DataID = '".$SubmittedParam['faqid']."'")->run();
                        if( $delFAQData['ErrorCode'] == '')
                        {
                            $return = array('Status' => 1, 'Message' => 'FAQ Successfully Deleted!');
                        }
                    }
                    
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
}
   
   