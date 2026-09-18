<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

	function __construct()
	{
		 // phpinfo();
        parent::__construct();

        $this->data['pagetype'] = 'ticketinquiry';

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

	}

    function index()
    {     
        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/multi-select/multi-select'
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


        $this->data['title'] = "Ticket Inquiry"; 
        $this->data['PageTitle'] = "Ticket Inquiry"; 
        $indexFile = 'index';
        // $headerfooter = false;
        // if( $this->tank_auth->is_logged_in() )
        // {
        //     $indexFile =  'index2';
        //     $headerfooter = true;
        // }
        
        $this->load->template('pages/ticketinquiry/'.$indexFile,$this->data,'',true); 
       
    }
  
    function submitform()
    {
        $return = array('Status' => 0, 'Message' => 'Permission Denied!');
        $Param = $_POST;

        if( $_SERVER['REQUEST_METHOD'] <> 'POST')
        {
            redirect('login');
        }
        
        if( !$this->tank_auth->is_logged_in() )
        {      
            $verifyRecaptchaResult = $this->myutilities->google_recaptcha_validate($Param['recaptchatoken']);

            if($verifyRecaptchaResult)
            {
                unset($Param['recaptchatoken']);
                goto ContinueHere;
            }
            else
            {
               goto exitHere; 
            }
        }

        ContinueHere:
        try {
            unset($Param['recaptchatoken']);  
            unset($_POST['recaptchatoken']);  
            unset($_REQUEST['recaptchatoken']);  
            if ( $this->form_validation->run() ) 
            {   
                if( $this->tank_auth->is_logged_in() )
                {
                    $Param['Ticket_CompleteName'] = $this->userprofile['CompleteName'];
                    $Param['Ticket_ContactEmail'] = $this->userprofile['emailaddress'];
                }  
                $Param['Ticket_Message'] = base64_encode($Param['Ticket_Message']);
                $Param['Ticket_DateTime'] = date('Y-m-d H:i:s');
                $Param['Ticket_CreatedBy'] = (!$this->tank_auth->is_logged_in()) ? '0' : $this->userid;

                $InsertTicket = $this->sqlhelper->local->insert("system_ticket_list")->ex_insert($Param);
                // log_message("error",$InsertTicket->rtrnSql());
                $InsertTicket = $InsertTicket->run();
                if($InsertTicket['ErrorCode'] == "")
                {
                    $DataID = $InsertTicket['Data'];
                    $TicketNo = $this->sqlhelper->local->select("system_ticket_list")->where("DataID = ".$DataID)->row();
                    $TicketNo = $TicketNo['Data']['Ticket_No'];

                    $return['Status'] = 1;
                    $return['Message'] = 'New Inquiry Successfully Submitted';

                    // Send Email Notification
                    $emailMessageU = '
                    <p>This is to acknowledge your Inquiry with ticket no. '.$TicketNo.' dated '.date('F d, Y').' submitted through '.$this->system_settings['ApplicationAbbre'].' Portal.

                    Please expect a '.$this->system_settings['ApplicationAbbre'].' representative to contact you within 24hours.
                                     ';

                    $mail_message = array(
                          'header' => 'Hi '.ucwords(strtolower($Param['Ticket_CompleteName'])),
                          'footer' => 'Thank You.',
                          'body' => $emailMessageU
                        );

                    $param = array(
                      'from'    => $this->config->item('email_sender'),
                      'to'      => $Param['Ticket_ContactEmail'],
                      'subject' => $this->system_settings['ApplicationAbbre'].' Inquiry [Ticket No : '.$TicketNo.'] ',
                      'message' => $mail_message,
                      'cc'      => '',
                      'bcc'     => ''
                    );

                    // Load Send Mail Model for Sending Email
                    // $this->load->model('sendmail/sendmail'); 
                    $this->daemon->execute_background('sendmail/sendmail','send_mail',$param);

                    // Send Email to Administrator/Helpdesl or All user < usertype 4

                    // Get All User Email Address
                    $getUserEmail =  $this->sqlhelper->local->select("v_001_user_infostatus")->where("User_typeID < 4")->result();

                    foreach( $getUserEmail['Data'] as $urow => $ucol )
                    {
                        $emailMessage = '
                        <p>System received a new Inquiry, Kindly respond and see details below.</p><br>
                        <p>Ticket No       : '.$TicketNo.'</p>
                        <p>Subject         : '.$Param['Ticket_Subject'].'</p>
                        <p>Name            : '.$Param['Ticket_CompleteName'].'</p>
                        <p>Email Address   : '.$Param['Ticket_ContactEmail'].'</p>
                        <p>Message         : </p><br>
                        <p>'.base64_decode($Param['Ticket_Message']).'</p>
                        
                        ';

                        // Send Email Notification
                        $mail_message = array(
                              'header' => 'Hi '.$ucol['Account_Name'],
                              'footer' => 'Thank You.',
                              'body' => $emailMessage
                            );

                        $param = array(
                          'from'    => $this->config->item('email_sender'),
                          'to'      => $ucol['User_EmailAddress'],
                          'subject' => $this->system_settings['ApplicationAbbre'].' Inquiry [Ticket No : '.$TicketNo.'] ',
                          'message' => $mail_message,
                          'cc'      => '',
                          'bcc'     => ''
                        );

                        // Load Send Mail Model for Sending Email
                        // $this->load->model('sendmail/sendmail'); 
                        $this->daemon->execute_background('sendmail/sendmail','send_mail',$param);

                    }

                }
                else
                {
                    // log_message("error",$InsertTicket);
                    $return['Message'] = "Failed to Submit New Inquiry.";
                }

            }
            else
            {
               $return['Message']= "Invalid Form Data!";
            }

            

        } catch (Exception $e) {
            $return['Message'] = "Failed to Submit New Inquiry";
           
            // log_message("error",$e->getMessage());
        }        

        exitHere:
        echo json_encode($return);
    }

    function ticketlist()
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }

        try
        {
            $DataTableConfig = @$_SESSION['DataTables'][$_REQUEST['TableID']];
            $table = "system_ticket_list"; // target table = normal table, view table, virtual table
          
            // Table's primary key
            $primaryKey = 'DataID'; // Primary Key of the Table for normal table, 

            /* Create Where Clauses for SQL Statement */
            $aliasParam = array(); // Used to replace custom Search Variable to the target column field
            $specialParam = array(); 
            $whereResult = $this->datatables->customWhereStatement($_REQUEST,@$aliasParam,@$specialParam);

            // Additional Filter
            $DefaultFilter = "";
            $StatFilter = '';
            
            if( (int) $this->usertype > 3 )
            {
                switch((int) $this->userclassification)
                {
                    case 3:
                        // $DefaultFilter = "Region = '".$this->userregistrationinfo['Region']."'  and uClassification = 3";
                    break;
                   
                    default:
                         $DefaultFilter = 'Ticket_CreatedBy = '.$this->userid;
                    break;
                }
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
                
                // 'DataCount_crud_view' => function($d, $row, $fieldid, $TargetTableID,$colid){
                    
                //     $LinelistMasterRecordID = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$d);

                //     $d = '<a href="'.site_url('linelist/index/details/'.base64_encode($LinelistMasterRecordID)).'" class="" title="View Details" id="vd_'.$LinelistMasterRecordID.'"  name="vd_'.$LinelistMasterRecordID.'" buttongroup="viewbtnAction"><i class="fa fa-search"></i></a>';

                //     return $d; 
                // },    
                
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

    function details($ticketno = '')
    {
        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/multi-select/multi-select'
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

        $dticketno = $ticketno;
        $ticketno = $this->encryption->decrypt(base64_decode($ticketno));

        if( $ticketno == "" )
        {
            redirect('contactus');
        }
        else
        {
                  $this->data['title'] = "TICKET NO : ".@$dticketno; 
        $this->data['PageTitle'] = "TICKET DETAILS"; 
        $this->load->template('templates/contactus/ticket/details/index',$this->data,'',$headerfooter);
        }




   
    }
   
}