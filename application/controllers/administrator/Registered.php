<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#Renamed
class Registered extends MY_Controller {

    public $adminpageDir = 'templates/admin/pages/';

    function __construct()
    {
        parent::__construct();
        $this->data['PageName'] = get_class($this);
        $this->data['adminMenu'] = true;
        $this->breadcrumbs->push('<i class="fa fa-users text-danger fa-fw"></i>&nbsp;Registered Account', '/administrator/registered');
        if((int) $this->usertype > 3)
        {
            if( (int) $this->userclassification == 3 )
            {
                $this->data['adminMenu'] = false;

                if( (int) $this->userclassification == 3 && $this->system_module_permission['1'] <> 1)
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

    }

    function index()
    {
        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
             
                <button type="button" id="btn_Submit" name="btnNewCase" class="btn btn-success " style="margin-right:5px"><i class="fas fa-save fa-fw"></i>&nbsp;Submit</button>
                <button type="button" id="btn_Cancel" name="btnNewCase" class="btn btn-dark" style=""<i class="fas fa-times fa-fw"></i>&nbsp;Cancel</button>                
            </div>     
        
        ';

        $pageheaderaction = '';

        $this->data['title'] = "Registered Account"; 
        $this->data['PageTitle'] = "Registered Account".@$buttonL;       


        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'registered_content/index',  $this->data,'',1); 
      
    }

    function registeredlist()
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
            a.*,
            CAST(AES_DECRYPT(FROM_BASE64(a.Lastname),FROM_BASE64('".$this->system_settings['PasswordHashing']."')) AS CHAR ) as aLastname,
            CAST(AES_DECRYPT(FROM_BASE64(a.Firstname),FROM_BASE64('".$this->system_settings['PasswordHashing']."')) AS CHAR ) as aFirstname,
            CAST(AES_DECRYPT(FROM_BASE64(a.Middlename),FROM_BASE64('".$this->system_settings['PasswordHashing']."')) AS CHAR ) as aMiddlename,
            IF(a.activateddatetime IS NULL,'N','Y') as AccountActivated
            from user_register a 
            where a.user_classification is not null
            ) as vTable"; // target table = normal table, view table, virtual table
          
            // Table's primary key
            $primaryKey = 'DataID'; // Primary Key of the Table for normal table, 

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

            // Additional Default Filter - Start
            $DefaultFilter = '';


            // Additional Default Filter - End

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
            $overRideFunctionCall = array(

                'registrationID_crud_view' => function($d, $row, $fieldid, $TargetTableID,$colid){

                        $DTTableSession = $_SESSION['DataTables'][ $TargetTableID ];
                        $de = base64_encode(base64_encode($row['registrationID']));
                       
                        $viewfmethod  = ($DTTableSession['bactionscolumn']['viewmethod'] <> '') ? ( (filter_var($DTTableSession['bactionscolumn']['viewmethod'], FILTER_VALIDATE_URL)) ? 'href="'.$DTTableSession['bactionscolumn']['viewmethod'].'/'.$de.'"' : 'href="javascript:void(0)" fmethod="'.$DTTableSession['bactionscolumn']['viewmethod'].'"')  : 'href="javascript:void(0)"';

                        //'administrator/registered/details'
                        $d = '<a dataid="'.$de.'" grouplink="viewdetails"  title="View Details" datatable="'.$TargetTableID.'" '.$viewfmethod.'><i class="fa fa-search"></i></a>';

                        if( (int) $this->userclassification == 3 && $this->system_module_permission['2'] <> 1)
                        {
                            $d = '';
                        }


                        return $d; //$d;
                },
                'dayscount' => function($d, $row, $fieldid, $TargetTableID,$colid){

                    if( $row['register_status'] == 1)
                    {
                        $d = $this->myutilities->getDaysDifference(date("Y-m-d",strtotime($row['register_datetime'])),date("Y-m-d") );
                    }

                        return $d; //$d;
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
            // log_message("error",$whereResult);
            // Return Data as Json Format
            $DataReturn = Datatables::complex( $_REQUEST, @$sql_details, $table, $primaryKey, $ColumnProperties, $whereResult, NULL ,@$groupBy,$_REQUEST['TableID'] ) ;

            // DO any processing here
            // Get Aggregate User Status
            $this->load->model('register/m_register');
            $DataReturn['RegistrationStat'] = $this->m_register->getStatistics();

            // Return Data as Json Format
            echo json_encode($DataReturn);
        }
        catch(Exception $err)
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        

    }

    function details($registrationID)
    {
        $this->breadcrumbs->push('<i class="fa fa-vcard text-danger fa-fw"></i>&nbsp;Registration Details', '/administrator/registered/'.$registrationID);

        if( (int) $this->userclassification == 3 && (int)  $this->system_module_permission['2'] <> 1)
        {
            redirect('administrator/registered');
        }

        if( $registrationID == "")
        {
            redirect('administrator/registered');
        }
        else
        {
            $registrationID = base64_decode(base64_decode($registrationID));

            $this->load->model('register/m_register');
            $registrationData = $this->m_register->getRegistrationData($registrationID);
            



            if( $registrationData['accountprofileexists'] == 'Y' )
            {
               $userProfile = $this->myutilities->getUserDetails($registrationData['accountuserid']);
               $registrationData['existing_user_classification'] = $userProfile['User_ClassificationID'];
               $registrationData['existing_username'] = $userProfile['User_Name'];
               $registrationData['existing_accountname'] = $userProfile['Account_Name'];
               $registrationData['existing_email'] = $userProfile['User_EmailAddress'];
               $registrationData['existing_status'] = $userProfile['User_StatusDesc'];
               $registrationData['existing_lastlogin'] = $userProfile['Last_LoginDateTime'];
            }

            $this->data['registrationData'] = $registrationData;            

            if( (int) $this->userclassification == 3)
            {
                if( $registrationData['Region'] <> $this->userregistrationinfo['Region'])
                {
                    redirect('administrator/registered');
                }
            }

            if($registrationData['register_status'] == 1)
            {
                $pageheaderaction = '
                    <div id="PageTitle-Button-Holder" class="float-right">
                     
                        <button type="button" id="btn_Approved" name="btnRegisterAction" class="btn btn-success " style="margin-right:5px"><i class="fas fa-check fa-fw"></i>&nbsp;Approved</button>
                        <button type="button" id="btn_Disapproved" name="btnRegisterAction" class="btn btn-danger " style="margin-right:5px"><i class="fas fa-times fa-fw"></i>&nbsp;Disapproved</button>
                         '.@$RegistrationAllowedBtn.' 
                        <button type="button" id="btn_Cancel" name="btnRegisterAction" class="btn btn-dark" style=""<i class="fas fa-times fa-fw"></i>&nbsp;Back to List</button>     
                                 
                    </div>     
                ';

            }
            else
            {
                $pageheaderaction = '
                    <div id="PageTitle-Button-Holder" class="float-right">        
                        '.@$RegistrationAllowedBtn.'        
                        <button type="button" id="btn_Cancel" name="btnRegisterAction" class="btn btn-dark" style=""<i class="fas fa-times fa-fw"></i>&nbsp;Back to List
                        </button>  
                                    
                    </div>     
                ';
            }

            
            $this->data['title'] = "Registration Details"; 
            $this->data['PageTitle'] = "Registration Details";       


            $this->data['pageheaderaction'] = @$pageheaderaction;
            $this->load->templateAdmin($this->adminpageDir.'registered_content/details',  $this->data,'',1); 

        }
    }


    function submitregisteredaction()
    {
        $return = array('Status' => 0, 'Message' => 'Permission Denied!');   

        if( (int) $this->userclassification == 3 && $this->system_module_permission['3'] <> 1)
        {
            $return = array('Status' => 0, 'Message' => 'Unauthorized Access Detected!');   
            goto exit_function_here;
        }


        if( $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
            {
               $return = array('Status' => 0, 'Message' => 'Permission Denied!');   
               goto exit_function_here;
            }

            unset($_POST['UToken']);
            $return = array('Status' => 0, 'Message' => 'Failed to Submit Registration Action!, Please try again later');

            try{
              
                $FormData = $_POST;
                if( $FormData['register_status'] == 2 || $FormData['register_status'] == 3 )
                {
                    $registrationID = base64_decode( base64_decode($FormData['registrationID']) );

                    $this->load->model('register/m_register');
                    $registrationData = $this->m_register->getRegistrationData($registrationID);
                  
                    if( !is_array($registrationData) )
                    {
                        // log_message("error","Cannot Retrieve Registration Data");
                        goto exit_function_here;
                    }

                    if( $registrationData['register_status'] > 1)
                    {
                        // log_message("error","Registration is already been Approved/Disapproved");
                        goto exit_function_here;
                    }

                    unset($FormData['registrationID']);
                    $FormData['action_datetime'] = date("Y-m-d H:i:s");
                    $FormData['action_by'] = $this->userid;
                    //log_message("error",$registrationID);

                    if( (int) $this->userclassification == 3 && $this->system_module_permission['3'] == 1)
                    {
                        if( $registrationData['Region'] <> $this->userregistrationinfo['Region'] )
                        {
                            $return = array('Status' => 0, 'Message' => 'Permission Denied!');   
                            goto exit_function_here;
                        }
                    }

                    try
                    {
                        $UpdateRegistrationResult = $this->sqlhelper->local->update("user_register")->ex_update($FormData)->where("registrationID ='".$registrationID."'")->run();

                        if( $UpdateRegistrationResult['ErrorCode'] == "" )
                        {
                            $MoveResult = false;
                            if($FormData['register_status'] == 2)
                            {
                                $MoveResult = $this->m_register->movetouseraccount($registrationData['registrationID']);

                                if(!$MoveResult)
                                {
                                    $revertUpdate = $this->sqlhelper->local->update("user_register")->ex_update($registrationData)->where("registrationID ='".$registrationID."'")->run();
                                    goto exit_function_here;
                                }

                                // ZPAMS-FIX (2026-09): movetouseraccount() creates the real row in
                                // `users` and updates user_register.user_id in the database, but only
                                // returns a boolean -- $registrationData here is still the copy fetched
                                // BEFORE approval, when the registration had no linked account yet, so
                                // its user_id is NULL. Passing that stale NULL into
                                // resendactivationlink() below crashed with "Cannot access offset of
                                // type string on string" (getRegistrationInfo(NULL) returns "" instead
                                // of a row). Re-fetching here picks up the user_id movetouseraccount()
                                // just assigned.
                                $registrationData = $this->m_register->getRegistrationData($registrationID);
                            }
                            
                            try
                            {
                                $return['Status']   = 1;
                                $return['rstat']  = $FormData['register_status'];

                                $toDEncrypt = ['Lastname','Firstname','Middlename'];
                                foreach( $registrationData as $ek => $ev)
                                {
                                    if( in_array($ek, $toDEncrypt) )
                                    {
                                        if( $ev <> '' )
                                        {
                                            $registrationData[$ek] = $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$ev,'MCrypt','aes-128','ecb');

                                            $registrationData[$ek] = ($registrationData[$ek] <> '') ? $registrationData[$ek] : $this->m_api->encryptdecryptString('decrypt',$this->system_settings['PasswordHashing'],$ev,'MCrypt','aes-128','cbc');
                                        }
                                    }
                                }

                                $RegName = ucwords(strtolower($registrationData['Lastname'].( ($registrationData['Suffixname'] <> 'NA' && $registrationData['Suffixname'] <> '') ? ' '.$registrationData['Suffixname'] : '' ).', '.$registrationData['Firstname'].' '.substr($registrationData['Middlename'],0,1).'.'));

                                if( $FormData['register_status'] == 2 )
                                {
                                   
                                    $return['Message']  = 'Email Notification successfully sent to the registered email address containing the account activation link.';
                                    $SendActivationLink = $this->myutilities->resendactivationlink($registrationData['user_id']);

                                }
                                else if( $FormData['register_status'] == 3 )
                                {
                                    $return['Message']  = 'Email Notification successfully sent to the registered email address.';

$emailMessage = '
Your Account Registration to '.$this->system_settings['ApplicationAbbre'].' : '.$this->system_settings['ApplicationName'].' has been Disapproved by the Administrator.

<br>Reason for Disapproval:<br>
<p>'.@$FormData['remarks'].'</p>

<br><br><p>If you have any concern or inquiries regarding the Disapproval of your registration, Kindly contact us in our technical support emai address or directly send us message using the application contact us page.</p><br>';

                                    // Send Email Notification
                                    $mail_message = array(
                                          'header' => 'Hi '.$RegName,
                                          'footer' => 'Thank You.',
                                          'body' => $emailMessage
                                        );

                                    $param = array(
                                      'from'    => $this->config->item('email_sender'),
                                      'to'      => $registrationData['emailaddress'],
                                      'subject' => $this->system_settings['ApplicationAbbre'].' - Account Registration',
                                      'message' => $mail_message,
                                      'cc'      => '',
                                      'bcc'     => ''
                                    );

                                    // Load Send Mail Model for Sending Email
                                    // $this->load->model('sendmail/sendmail'); 
                                    $this->daemon->execute_background('sendmail/sendmail','send_mail',$param);
                                }
                                
                                
                            }
                            catch(Exception $err )
                            {
                                log_message("error",$err->getMessage());
                            }
   
                        }

                    }catch (PDOException $e) {
                        log_message("error",$e->getMessage());
                    } catch (Exception $e) {
                        log_message("error",$e->getMessage());
                    }

                }

            }
            catch(Exception $e)
            {
                log_message("error",$e->getMessage());
            }
        }

        exit_function_here:
        echo json_encode($return);
    }

}