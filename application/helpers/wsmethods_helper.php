<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// Use to Send Information of the Webservice Date / Time
function WS_Check($param = '',$loadGUI = false) 
{
    $CI = & get_instance();

    if($loadGUI)
    {
        $param_return = array(
                'methodname' =>  strtolower(__FUNCTION__),
                'FunctionID' => 0,
            );

        $display_html = array(
                'page_title' => 'WS_Check',
                'param'     => $param_return,
            );
        return $display_html;
    }
    else
    {
        $rdatetime=date("F d, Y g:i A");
        $Return=$CI->Response_Result($rdatetime,100);
        
        return $Return; 
    }
}

function push_PHIEData($param = '',$loadGUI = false) 
{
    
    $CI = & get_instance();
    $CI->sqlhelper->local->setajaxEvent(1);
    $CI->response_code_list = $CI->response_code(true);
  
    $Response_Code = "";
    $Response_Message = "";
    $Response_Remarks = "";
     
   
    if($loadGUI)
    {
        
        $param_return = array(
                'methodname' =>  strtolower(__FUNCTION__),
                'FunctionID' => $CI->get_FunctionID(__FUNCTION__),
            );

        $display_html = array(
                'page_title' => 'push_PHIEData',
                'param'     => $param_return,
            );
        return $display_html;
    }
    else
    {
        try
        {
            // WS Log Event - Start
            $WS_Log_Param = array(
                    'FunctionID' => $CI->get_FunctionID(__FUNCTION__)
                );

            $CI->WS_Log_Event($WS_Log_Param,'I');

            // WS Log Event - End
            
            $Authticate = $CI->WS_Authenticate_Param(__FUNCTION__,$param);

            if( $Authticate['Code'] <> 105 )
            {
                $WS_Log_Param = array(
                    'Parameter'     => $param,
                    'ReturnCode'    => $Authticate['Code'],
                    'ReturnMessage' => $Authticate['Message'],
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );

                $CI->WS_Log_Event($WS_Log_Param,'U');
                return $CI->WS_Response_Result($Authticate['Message'],$Authticate['Code']);
            }
            
            // Passed Authentication Validation -> Checked Parameter Content
            $chk_Parameter_Content = $CI->WS_Validate_Param(__FUNCTION__, $Authticate['Param']['Type'], $Authticate['Param']['Data']);
           
            if( $chk_Parameter_Content['Status'] <> 2 )
            {
                $Response_Code = ($chk_Parameter_Content['Status'] == 0) ? 104 : 103;
                $Response_Message = ($Response_Code == 104) ? 'Web Service Method is not available' : $chk_Parameter_Content['Message'];
                goto exit_push_phiedata;
            }
          
            // Passed Content Validation
            $Type = $Authticate['Param']['Type'];
            $Type_Details = $CI->get_Method_Param_Key_Details($Authticate['Param']['Type']);

            $Submitted_Param = $chk_Parameter_Content['Message'];

            $User_Profile = $CI->WS_User_Profile;
            $UserType = (int) $CI->WS_User_Type;
            
            if( $UserType == 7 )
            {
                $EMR_System_ID = $User_Profile['emr_system'];
                $check_emr = $CI->sqlhelper->local->select("user_profiles")->where("hfhudcode = '".trim($Submitted_Param['HealthFacilityCode'])."' and emr_system = '".$EMR_System_ID."' ")->ex_select("","","1")->row();
                if( $check_emr['Count'] > 0 )
                {
                     goto continue_here;
                }
                else
                {
                    $Response_Code = 107;
                    $Response_Message = "You cannot upload this Health Facility Data, this Health Facility is register to other EMR System.";
                    goto exit_push_phiedata;
                }
            }


            continue_here:

            $Submitted_Param['Log_Created_DateTime'] = date("Y-m-d H:i:s");
            $Submitted_Param['Log_Uploaded_DateTime'] = date("Y-m-d H:i:s");
            
            if( trim($Type) == "PatientData" )
            {
                // Add to Master Patient if Patient Information doesn't Exists
                $where_Master_Patient = " LName = '".trim($Submitted_Param['LName'])."' and FName = '".trim($Submitted_Param['FName'])."' and MName = '".trim($Submitted_Param['MName'])."' and Sex = '".trim($Submitted_Param['Sex'])."'  and DOB = '".date('Y-m-d',strtotime(trim($Submitted_Param['DOB'])))."' ";

                if( $Submitted_Param['SName'] <> '' && strtoupper(trim($Submitted_Param['SName'])) <> "NA"  )
                {
                    $where_Master_Patient .= " and SName = '".$Submitted_Param['SName']."'";
                }

                $chk_Master_Patient = $CI->sqlhelper->local->select("hie_clientregistry_master")->where($where_Master_Patient)->ex_select("","","1")->row();
    
                if( $chk_Master_Patient['Count'] == 0 )
                {
                    // Second Validate by $Submitted_Param['HOSPITALNO'] and $Submitted_Param['HealthFacilityCode']
                    $DOHID = $CI->get_DOHID($Submitted_Param['HealthFacilityCode'],$Submitted_Param['HOSPITALNO']);
                    if( $DOHID == '')
                    {
                        // Insert to Master Patient
                        $insert_Master_Patient = array(
                                'PHILHEALTHNO'          => $Submitted_Param['PHILHEALTHNO'],
                                'LName'                 => $Submitted_Param['LName'],
                                'FName'                 => $Submitted_Param['FName'],
                                'MName'                 => $Submitted_Param['MName'],
                                'SName'                 => $Submitted_Param['SName'],
                                'DOB'                   => $Submitted_Param['DOB'],
                                'POB'                   => $Submitted_Param['POB'],
                                'Sex'                   => $Submitted_Param['Sex'],
                                'Civil_Status'          => $Submitted_Param['Civil_Status'],
                                'Nationality'           => $Submitted_Param['Nationality'],
                                'Log_Created_DateTime'  => $Submitted_Param['Log_Created_DateTime'],
                                'Log_Uploaded_DateTime' => $Submitted_Param['Log_Uploaded_DateTime']
                            );

                        $run_Insert_Master_Patient = $CI->sqlhelper->local->insert("hie_clientregistry_master")->ex_insert($insert_Master_Patient);
                        $run_Insert_Master_Patient = $run_Insert_Master_Patient->run();
                        if( $run_Insert_Master_Patient['ErrorCode'] == "" )
                        {
                            // Success Insert -> get DataID -> Generate DOHID -> update hie_clientregistry_master
                            $masterInsert_ID =  $run_Insert_Master_Patient['Data'];
                            $DOHID = $CI->myutilities->createDOHID($masterInsert_ID);
                            $set_update = array(
                                    'DOHID' => $DOHID,
                                    'Log_Updated_DateTime' => date('Y-m-d H:i:s')
                                );

                            $run_Update = $CI->sqlhelper->local->update("hie_clientregistry_master")->ex_update($set_update)->run();
                        }
                    }
                }
                else
                {
                    $DOHID = $chk_Master_Patient['Data']['DOHID'];
                }

                $Submitted_Param['DOHID'] = $DOHID;
              
            }
            else
            {
                // Not Patient Data -> Get DOHID use HOSPITALNO and HFHUDCODE to get DOHID for this PATIENT
                
                $DOHID = $CI->get_DOHID($Submitted_Param['HealthFacilityCode'],$Submitted_Param['HOSPITALNO']);
                $Submitted_Param['DOHID'] = $DOHID;
                
                if($DOHID == "")
                {
                    $Response_Code = 107;
                    $Response_Message = "This ".$Authticate['Param']['Type']." cannot be uploaded, please upload Patient Data first for the Pat_Facility_No = ".$Submitted_Param['HOSPITALNO'];
                    $Response_Remarks = "";
                    goto exit_push_phiedata;
                }
                
                if( $Authticate['Param']['Type'] <> "EncounterData" )
                {
                    $Encounter_Record_Cnt = $CI->sqlhelper->local->select("hie_encounters")->where("encounter_code = '".$Submitted_Param['encounter_code']."' and HOSPITALNO = '".$Submitted_Param['HOSPITALNO']."' and HealthFacilityCode = '".$Submitted_Param['HealthFacilityCode']."' ")->count();
                    if( $Encounter_Record_Cnt == 0 )
                    {
                        $Response_Code = 107;
                        $Response_Message = "This ".$Authticate['Param']['Type']." cannot be uploaded, please upload Encounter Data first for the Pat_Facility_No = ".$Submitted_Param['HOSPITALNO'];
                        $Response_Remarks = "";
                        goto exit_push_phiedata;
                    }
                }

            }
            
            // Insert Data - to target Table
            $Remarks = '';
            $insert_local_patient = $CI->sqlhelper->local->insert($Type_Details['TargetTable'])->ex_insert($Submitted_Param);
            $insert_local_patient = $insert_local_patient->run();
            if( $insert_local_patient['ErrorCode'] == '' )
            {
                $Response_Code = 106;

                $Response_Message = array(
                        'DataType'=>$Authticate['Param']['Type'],
                        'TransactionID'=>$insert_local_patient['Data'],
                        'Client_DOHID'=>$DOHID,
                        'Pat_Facility_No'=>$Submitted_Param['HOSPITALNO']
                    );

                // Trigger Replication - Process ( Asynchronous Task )
                $get_Param_Replication = $CI->sqlhelper->local->select($Type_Details['TargetTable'])->where("DataCount = '".$insert_local_patient['Data']."'")->ex_select('','','1')->row();
                if( $get_Param_Replication['Count'] > 0)
                {
                    $rep_param = $get_Param_Replication['Data'];
                    
                    $rep_param['caller_id'] = "webservice";

                    try
                    {
                        $CI->daemon->execute_background('replicationphic',$Authticate['Param']['Type'],$rep_param);
                    }
                    catch(Exception $er)
                    {
                        log_message("error","Web_Service, push_PHIEData, ".$er->getMessage());   
                    }
                }                                
            }
            else
            {
                $Response_Code = 107;
                $Response_Message = "Please contact PHIE Lite Helpdesk Support.";
                $Response_Remarks = json_encode($insert_local_patient);
            }

            exit_push_phiedata:

            $WS_Log_Param = array(
                    'DataType'      => $Authticate['Param']['Type'],
                    'Parameter'     => json_encode($Authticate['Param']),
                    'ReturnCode'    => $Response_Code,
                    'ReturnMessage' => ($Response_Code == 107 ) ? $Response_Message : json_encode($Response_Message),
                    'Remarks'       => $Response_Remarks,
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );
            $CI->WS_Log_Event($WS_Log_Param,'U');
            return $CI->WS_Response_Result($Response_Message,$Response_Code);
        }
        catch(Exception $Exception )
        {
            log_message("error","Web_Service, push_PHIEData, ".$Exception->getMessage());   
           
            $WS_Log_Param = array(
                        'Parameter'     => $param,
                        'ReturnCode'    => 107,
                        'ReturnMessage' => "Please Contact PHIE Lite Helpdesk Support",
                        'Remarks'       => $Exception->getMessage(),
                        'ResponseDateTime' => date('Y-m-d H:i:s')
                    );
            $CI->WS_Log_Event($WS_Log_Param,'U');

            return $CI->WS_Response_Result("Please Contact PHIE Lite Helpdesk Support",107);
        }
    }


    
}

function push_PMRFData($param = '',$loadGUI = false) 
{
    
    $CI = & get_instance();
    $CI->sqlhelper->local->setajaxEvent(1);
    $CI->response_code_list = $CI->response_code(true);
  
    $Response_Code = "";
    $Response_Message = "";
    $Response_Remarks = "";
    
   
    if($loadGUI)
    {
        $param_return = array(
                'methodname' =>  strtolower(__FUNCTION__),
                'FunctionID' => $CI->get_FunctionID(__FUNCTION__),
            );

        $display_html = array(
                'page_title' => 'push_PMRFData',
                'param'     => $param_return,
            );
        return $display_html;
    }
    else
    {
        try
        {
            // WS Log Event - Start
            $WS_Log_Param = array(
                    'FunctionID' => $CI->get_FunctionID(__FUNCTION__)
                );

            $CI->WS_Log_Event($WS_Log_Param,'I');

            // WS Log Event - End
            
            $Authticate = $CI->WS_Authenticate_Param(__FUNCTION__,$param);

            if( $Authticate['Code'] <> 105 )
            {
                $WS_Log_Param = array(
                    'Parameter'     => $param,
                    'ReturnCode'    => $Authticate['Code'],
                    'ReturnMessage' => $Authticate['Message'],
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );

                $CI->WS_Log_Event($WS_Log_Param,'U');
                return $CI->WS_Response_Result($Authticate['Message'],$Authticate['Code']);
            }
            
            // Passed Authentication Validation -> Checked Parameter Content
            $chk_Parameter_Content = $CI->WS_Validate_Param(__FUNCTION__, 'PMRFData', $Authticate['Param']['PMRFData']);
           
            if( $chk_Parameter_Content['Status'] <> 2 )
            {
                $Response_Code = ($chk_Parameter_Content['Status'] == 0) ? 104 : 103;
                $Response_Message = ($Response_Code == 104) ? 'Web Service Method is not available' : $chk_Parameter_Content['Message'];
                goto exit_function;
            }
          
            // Passed Content Validation
            $Type = "PMRFData";
            $Type_Details = $CI->get_Method_Param_Key_Details($Type);

            $Submitted_Param = $chk_Parameter_Content['Message'];

            $User_Profile = $CI->WS_User_Profile;
            $UserType = (int) $CI->WS_User_Type;
            
            if( $UserType == 7 )
            {
                $EMR_System_ID = $User_Profile['emr_system'];
                $check_emr = $CI->sqlhelper->local->select("user_profiles")->where("hfhudcode = '".trim($Submitted_Param['HealthFacilityCode'])."' and emr_system = '".$EMR_System_ID."' ")->ex_select("","","1")->row();
                if( $check_emr['Count'] > 0 )
                {
                     goto continue_here;
                }
                else
                {
                    $Response_Code = 111;
                    $Response_Message = "You cannot upload this Health Facility Data, this Health Facility is register to other EMR System.";
                    goto exit_function;
                }
            }


            continue_here:

            $Submitted_Param['Log_Created_DateTime'] = date("Y-m-d H:i:s");
            $Submitted_Param['Log_Uploaded_DateTime'] = date("Y-m-d H:i:s");
            
            
            $where_Master_Patient = " LName = '".trim($Submitted_Param['LName'])."' and FName = '".trim($Submitted_Param['FName'])."' and MName = '".trim($Submitted_Param['MName'])."' and Sex = '".trim($Submitted_Param['Sex'])."'  and DOB = '".date('Y-m-d',strtotime(trim($Submitted_Param['DOB'])))."' ";

            if( $Submitted_Param['SName'] <> '' && strtoupper(trim($Submitted_Param['SName'])) <> "NA"  )
            {
                $where_Master_Patient .= " and SName = '".$Submitted_Param['SName']."'";
            }

            $chk_Master_Patient = $CI->sqlhelper->local->select("hie_clientregistry_master")->where($where_Master_Patient)->ex_select("","","1")->row();
            if( $chk_Master_Patient['Count'] > 0 )
            {
                $Submitted_Param['DOHID'] = $chk_Master_Patient['Data']['DOHID'];
            }     

            // Check if data is already a philhealth member
            $PhilHealthMember = "N";
            
            $SNAMEFilter = ( trim($Submitted_Param['SName']) <> '' && strtoupper(trim($Submitted_Param['SName'])) <> "NA" ) ? " and SUFFIXNAME = ".$CI->db->escape(trim($Submitted_Param['SName'])) : "" ;
            $chk = $CI->sqlhelper->replicate->select("registry_member")->where(" LASTNAME = ".$CI->db->escape(trim($Submitted_Param['LName']))." and FIRSTNAME = ".$CI->db->escape(trim($Submitted_Param['FName']))." and MIDDLENAME = ".$CI->db->escape(trim($Submitted_Param['MName']))." ".$SNAMEFilter." and SEX=".$CI->db->escape(trim($Submitted_Param['Sex']))." and BIRTHDAY=".$CI->db->escape(date("y-m-d",strtotime($Submitted_Param['DOB'])))." ")->ex_select("","","1")->row();
            if( $chk['Count'] > 0 )
            {
                // PhilHealth Member
                if( $Submitted_Param['PMRF_Purpose'] == 'E')
                {
                    // Retrieve Client Phil Health Information
                    $clientDetails = $chk['Data'];
                    $Client_PIN = $clientDetails['MEMID_NO'];

                    // GET PIN
                    $Response_Code = 108;
                    $Client_PIN = substr($Client_PIN,0,2)."-".substr($Client_PIN,2,-1)."-".substr($Client_PIN,11);
                    $Response_Message = array(
                            'Client_PhilHealth_No'=>$Client_PIN,
                            'Client_LastName' => strtoupper($clientDetails['LASTNAME']),
                            'Client_FirstName' => strtoupper($clientDetails['FIRSTNAME']),
                            'Client_MiddleName' => strtoupper($clientDetails['MIDDLENAME']),
                            'Client_SuffixName' => strtoupper($clientDetails['SUFFIXNAME']),
                            'Client_Sex' => $clientDetails['SEX'],
                            'Client_DateofBirth' => date("Y-m-d",strtotime($clientDetails['BIRTHDAY'])),
                        );

                    goto exit_function;
                }
            }   
            
            $SNAMEFilter = ( trim($Submitted_Param['SName']) <> '' ) ? " and SName = ".$CI->db->escape(trim($Submitted_Param['SName'])) : "";
            $chk2 = $CI->sqlhelper->local->select("replication_pmrf_registry")->where(" LName = ".$CI->db->escape(trim($Submitted_Param['LName']))." and FName = ".$CI->db->escape(trim($Submitted_Param['FName']))." and MName = ".$CI->db->escape(trim($Submitted_Param['MName']))." ".$SNAMEFilter." and Sex=".$CI->db->escape(trim($Submitted_Param['Sex']))." and DOB=".$CI->db->escape(date("y-m-d",strtotime($Submitted_Param['DOB'])))." ")->ex_select("","Log_Created_DateTime desc","1");
            $chk2 = $chk2->row();
            if( $chk2['Count'] > 0 )
            {
                $PMRFDetails = $chk2['Data'];
                
                $Client_PIN = (trim($PMRFDetails['PHILHEALTHNO']) <> '') ? substr($PMRFDetails['PHILHEALTHNO'],0,2)."-".substr($PMRFDetails['PHILHEALTHNO'],2,-1)."-".substr($PMRFDetails['PHILHEALTHNO'],11) : "";
                
                $Response_Code = 112;
                $Response_Message = array(
                    'PMRF_Submitted_DateTime'=>date("F d, Y g:m:s A",strtotime($PMRFDetails['Log_Created_DateTime'])),
                    'PMRF_Purpose'=>$CI->myutilities->getRef_Desc(28,$PMRFDetails['PMRF_Purpose']),
                    'PMRF_Status'=>($PMRFDetails['PMRF_Status'] == 1) ? "Processing" : ( ( $PMRFDetails['PMRF_Status'] == 2 ) ? "Processed" : "Submitted" ),
                    'PMRF_REGNO'=>$PMRFDetails['PMRF_REGNO'],
                    'Client_Details'=>array(
                                'Client_PhilHealth_No'=>$Client_PIN,
                                'Client_LastName' => strtoupper($PMRFDetails['LName']),
                                'Client_FirstName' => strtoupper($PMRFDetails['FName']),
                                'Client_MiddleName' => strtoupper($PMRFDetails['MName']),
                                'Client_SuffixName' => strtoupper($PMRFDetails['SName']),
                                'Client_Sex' => $PMRFDetails['Sex'],
                                'Client_DateofBirth' => date("Y-m-d",strtotime($PMRFDetails['DOB'])),
                            )
                );

                if( strtoupper($Submitted_Param['PMRF_Purpose']) == 'E' && $PMRFDetails['PMRF_Status'] == "" )
                {
                    goto exit_function;
                }     
            
            }
            // Insert Data - PMRF Data
            $runInsert = $CI->sqlhelper->local->insert("replication_pmrf_registry")->ex_insert($Submitted_Param)->run();
            if( $runInsert['ErrorCode'] == '' )
            {
                $PMRF_REGNO = date('Ymd').str_pad($runInsert['Data'],10,"0",STR_PAD_LEFT);
                $update_array = array('PMRF_REGNO'=>$PMRF_REGNO);
                $Update_PMRF = $CI->sqlhelper->local->update("replication_pmrf_registry")->ex_update($update_array)->where("DataCount = ".$runInsert['Data']."")->run();

                $Client_PIN = (trim($Submitted_Param['PHILHEALTHNO']) <> '') ? substr($Submitted_Param['PHILHEALTHNO'],0,2)."-".substr($Submitted_Param['PHILHEALTHNO'],2,-1)."-".substr($Submitted_Param['PHILHEALTHNO'],11) : "";
                
                $Response_Code = 110;
                $Response_Message = array(
                    'PMRF_Submitted_DateTime'=>date("F d, Y g:m:s A",strtotime($Submitted_Param['Log_Created_DateTime'])),
                    'PMRF_Purpose'=>$CI->myutilities->getRef_Desc(28,$Submitted_Param['PMRF_Purpose']),
                    'PMRF_Status'=>"Submitted",
                    'PMRF_REGNO'=>$PMRF_REGNO,
                    'Client_Details'=>array(
                                'Client_PhilHealth_No'=>$Client_PIN,
                                'Client_LastName' => strtoupper($Submitted_Param['LName']),
                                'Client_FirstName' => strtoupper($Submitted_Param['FName']),
                                'Client_MiddleName' => strtoupper($Submitted_Param['MName']),
                                'Client_SuffixName' => strtoupper($Submitted_Param['SName']),
                                'Client_Sex' => $Submitted_Param['Sex'],
                                'Client_DateofBirth' => date("Y-m-d",strtotime($Submitted_Param['DOB'])),
                            )
                );

                $get_Param_Replication = $CI->sqlhelper->local->select("replication_pmrf_registry")->where("DataCount = '".$runInsert['Data']."'")->ex_select('','','1')->row();
                if( $get_Param_Replication['Count'] > 0)
                {
                    try{
                        $rep_param = $get_Param_Replication['Data'];
                        $rep_param['caller_id'] = "webservice";
                        $CI->daemon->execute_background('replicationphic',"PMRFData",$rep_param);
                    }
                    catch(Exception $er)
                    {
                        log_message("error","Web_Service, push_PHIEData, ".$er->getMessage());   
                    }
                }   
                
            }
            else
            {
                $Response_Code = 111;
                $Response_Message = 'Please Contact PHIE Lite Helpdesk Support';
                $Response_Remarks = $runInsert['Description'];
            }
           
            exit_function:

            $WS_Log_Param = array(
                    'DataType'      => "PMRFData",
                    'Parameter'     => json_encode($Authticate['Param']),
                    'ReturnCode'    => $Response_Code,
                    'ReturnMessage' => ($Response_Code == 111 ) ? $Response_Message : json_encode($Response_Message),
                    'Remarks'       => $Response_Remarks,
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );
            $CI->WS_Log_Event($WS_Log_Param,'U');

            return $CI->WS_Response_Result($Response_Message,$Response_Code);

        }
        catch(Exception $Exception )
        {
            log_message("error","Web_Service, push_PMRFData, ".$Exception->getMessage());   
           
            $WS_Log_Param = array(
                        'DataType'      => "PMRFData",
                        'Parameter'     => $param,
                        'ReturnCode'    => 111,
                        'ReturnMessage' => "Please Contact PHIE Lite Helpdesk Support",
                        'Remarks'       => $Exception->getMessage(),
                        'ResponseDateTime' => date('Y-m-d H:i:s')
                    );
            $CI->WS_Log_Event($WS_Log_Param,'U');

            return $CI->WS_Response_Result("Please Contact PHIE Lite Helpdesk Support",111);
        }
    }
}

function push_PMRFDependentData($param = '',$loadGUI = false) 
{
    
    $CI = & get_instance();
    $CI->sqlhelper->local->setajaxEvent(1);
    $CI->response_code_list = $CI->response_code(true);
  
    $Response_Code = "";
    $Response_Message = "";
    $Response_Remarks = "";
    
   
    if($loadGUI)
    {
        $param_return = array(
                'methodname' =>  strtolower(__FUNCTION__),
                'FunctionID' => $CI->get_FunctionID(__FUNCTION__),
            );

        $display_html = array(
                'page_title' => 'push_PMRFDependentData',
                'param'     => $param_return,
            );
        return $display_html;
    }
    else
    {
        try
        {
            // WS Log Event - Start
            $WS_Log_Param = array(
                    'FunctionID' => $CI->get_FunctionID(__FUNCTION__)
                );

            $CI->WS_Log_Event($WS_Log_Param,'I');

            // WS Log Event - End
            
            $Authticate = $CI->WS_Authenticate_Param(__FUNCTION__,$param);

            if( $Authticate['Code'] <> 105 )
            {
                $WS_Log_Param = array(
                    'Parameter'     => $param,
                    'ReturnCode'    => $Authticate['Code'],
                    'ReturnMessage' => $Authticate['Message'],
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );

                $CI->WS_Log_Event($WS_Log_Param,'U');
                return $CI->WS_Response_Result($Authticate['Message'],$Authticate['Code']);
            }
            
            // Passed Authentication Validation -> Checked Parameter Content
            $chk_Parameter_Content = $CI->WS_Validate_Param(__FUNCTION__, 'PMRFDependentData', $Authticate['Param']['PMRFDependentData']);
           
            if( $chk_Parameter_Content['Status'] <> 2 )
            {
                $Response_Code = ($chk_Parameter_Content['Status'] == 0) ? 104 : 103;
                $Response_Message = ($Response_Code == 104) ? 'Web Service Method is not available' : $chk_Parameter_Content['Message'];
                goto exit_function;
            }
          
            // Passed Content Validation
            $Type = "PMRFDependentData";
            $Type_Details = $CI->get_Method_Param_Key_Details($Type);
            $Submitted_Param = $chk_Parameter_Content['Message'];

            $User_Profile = $CI->WS_User_Profile;
            $UserType = (int) $CI->WS_User_Type;
            
            if( $UserType == 7 )
            {
                $EMR_System_ID = $User_Profile['emr_system'];
                $check_emr = $CI->sqlhelper->local->select("user_profiles")->where("hfhudcode = '".trim($Submitted_Param['HealthFacilityCode'])."' and emr_system = '".$EMR_System_ID."' ")->ex_select("","","1")->row();
                if( $check_emr['Count'] > 0 )
                {
                     goto continue_here;
                }
                else
                {
                    $Response_Code = 116;
                    $Response_Message = "You cannot upload this Health Facility Data, this Health Facility is register to other EMR System.";
                    goto exit_function;
                }
            }


            continue_here:

            $Submitted_Param['Log_Created_DateTime'] = date("Y-m-d H:i:s");
            $Submitted_Param['Log_Uploaded_DateTime'] = date("Y-m-d H:i:s");
            $chk = $CI->sqlhelper->local->select("replication_pmrf_registry")->where("PMRF_REGNO = '".$Submitted_Param['PMRF_REGNO']."' ")->ex_select("","","1");
            $chk = $chk->row();
            if( $chk['Count'] == 0 )
            {
                $Response_Code = 118;
                $Response_Message = "Please submit the PMRF Data for this PMRF Dependent.";
                goto exit_function;
            }
            else
            {
                $pmrf_details = $chk['Data'];
                if($pmrf_details['PMRF_Status'] <> "")
                {
                    $Response_Code = 118;
                    $Response_Message = "PMRF Data is already been process, you cannot upload any dependent data for this PMRF_REGNO = ".$Submitted_Param['PMRF_REGNO']."";
                    goto exit_function;
                }
            }   

            $chk2_where = "
            PMRF_REGNO = '".trim(@$Submitted_Param['PMRF_REGNO'])."' AND 
            Dependent_LName = '".trim(@$Submitted_Param['Dependent_LName'])."' AND 
            Dependent_FName = '".trim(@$Submitted_Param['Dependent_FName'])."' AND 
            Dependent_MName = '".trim(@$Submitted_Param['Dependent_MName'])."' AND 
            Dependent_DOB = '".date('Y-m-d',strtotime(trim(@$Submitted_Param['Dependent_DOB'])))."' AND 
            Dependent_Sex = '".strtoupper(trim(@$Submitted_Param['Dependent_Sex']))."' 
            ";

            if(!is_null(trim(@$Submitted_Param['Dependent_SName'])) && trim(@$Submitted_Param['Dependent_SName']) <>'' )
            {
               $where .= " AND Dependent_SName = '".trim(@$Submitted_Param['Dependent_SName'])."'";
            }

            $chk2 = $CI->sqlhelper->local->select("replication_pmrf_dependent_registry")->where($chk2_where)->ex_select("","","1")->row();
            if( $chk2['Count'] == 0 )
            {
                // Insert
                $runInsert = $CI->sqlhelper->local->insert("replication_pmrf_dependent_registry")->ex_insert($Submitted_Param)->run();
                if($runInsert['ErrorCode'] == '')
                {
                    $Response_Code = 115;
                    $Response_Message = array(
                            'PMRFDependent_Submitted_DateTime'=>date('F d, Y g:i:s A',strtotime($Submitted_Param['Log_Created_DateTime'])),
                            'PMRFDependent_Type'=>$CI->myutilities->getRef_Desc(42,$Submitted_Param['Dependent_Type']),
                            'PMRFDependent_Details'=>array(
                                    'Dependent_PHILHEALTHNO'=>$Submitted_Param['Dependent_PHILHEALTHNO'],
                                    'Dependent_LastName' => strtoupper($Submitted_Param['Dependent_LName']),
                                    'Dependent_FirstName' => strtoupper($Submitted_Param['Dependent_FName']),
                                    'Dependent_MiddleName' => strtoupper($Submitted_Param['Dependent_MName']),
                                    'Dependent_SuffixName' => strtoupper($Submitted_Param['Dependent_SName']),
                                    'Dependent_DateofBirth' => date("Y-m-d",strtotime($Submitted_Param['Dependent_DOB'])),
                                    'Dependent_Sex' => $CI->myutilities->getRef_Desc(51,$Submitted_Param['Dependent_Sex']),
                                    'Dependent_PermanentlyDisabled'=>$Submitted_Param['Dependent_PermDisabled']
                                )
                        );

                    $get_Param_Replication = $CI->sqlhelper->local->select("replication_pmrf_dependent_registry")->where("DataCount = '".$runInsert['Data']."'")->ex_select('','','1')->row();
                    if( $get_Param_Replication['Count'] > 0)
                    {
                        try{
                            $rep_param = $get_Param_Replication['Data'];
                        	$rep_param['caller_id'] = "webservice";
                            $CI->daemon->execute_background('replicationphic',"PMRFDependentData",$rep_param);
                        }
                        catch(Exception $er)
                        {
                            log_message("error","Web_Service, push_PMRFDependentData, ".$er->getMessage());   
                        }
                    }   
                }
                else
                {
                    $Response_Code = 116;
                    $Response_Message = 'Please Contact PHIE Lite Helpdesk Support';
                }
            }
            else
            {
                $Response_Code = 117;
                $Response_Message = array(
                        'PMRFDependent_Submitted_DateTime'=>date('F d, Y g:i:s A',strtotime($chk2['Data']['Log_Created_DateTime'])),
                        'PMRFDependent_Type'=>$CI->myutilities->getRef_Desc(42,$chk2['Data']['Dependent_Type']),
                        'PMRFDependent_Details'=>array(
                                'Dependent_PHILHEALTHNO'=>$chk2['Data']['Dependent_PHILHEALTHNO'],
                                'Dependent_LastName' => strtoupper($chk2['Data']['Dependent_LName']),
                                'Dependent_FirstName' => strtoupper($chk2['Data']['Dependent_FName']),
                                'Dependent_MiddleName' => strtoupper($chk2['Data']['Dependent_MName']),
                                'Dependent_SuffixName' => strtoupper($chk2['Data']['Dependent_SName']),
                                'Dependent_DateofBirth' => date("Y-m-d",strtotime($chk2['Data']['Dependent_DOB'])),
                                'Dependent_Sex' => $CI->myutilities->getRef_Desc(51,$chk2['Data']['Dependent_Sex']),
                                'Dependent_PermanentlyDisabled'=>$chk2['Data']['Dependent_PermDisabled']
                            )
                    );
            }

            exit_function:

            $WS_Log_Param = array(
                    'DataType'      => "PMRFDependentData",
                    'Parameter'     => json_encode($Authticate['Param']),
                    'ReturnCode'    => $Response_Code,
                    'ReturnMessage' => ($Response_Code == 116 ) ? $Response_Message : json_encode($Response_Message),
                    'Remarks'       => $Response_Remarks,
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );
            $CI->WS_Log_Event($WS_Log_Param,'U');

            return $CI->WS_Response_Result($Response_Message,$Response_Code);

        }
        catch(Exception $Exception )
        {
            log_message("error","Web_Service, push_PMRFDependentData, ".$Exception->getMessage());   
           
            $WS_Log_Param = array(
                        'DataType'      => "PMRFDependentData",
                        'Parameter'     => $param,
                        'ReturnCode'    => 111,
                        'ReturnMessage' => "Please Contact PHIE Lite Helpdesk Support",
                        'Remarks'       => $Exception->getMessage(),
                        'ResponseDateTime' => date('Y-m-d H:i:s')
                    );
            $CI->WS_Log_Event($WS_Log_Param,'U');

            return $CI->WS_Response_Result("Please Contact PHIE Lite Helpdesk Support",111);
        }
    }
}

function get_phicPatientID($param = '',$loadGUI = false) 
{
    
    $CI = & get_instance();
    $CI->sqlhelper->local->setajaxEvent(1);
    $CI->response_code_list = $CI->response_code(true);
  
    $Response_Code = "";
    $Response_Message = "";
    $Response_Remarks = "";
    
   
    if($loadGUI)
    {
        $param_return = array(
                'methodname' =>  strtolower(__FUNCTION__),
                'FunctionID' => $CI->get_FunctionID(__FUNCTION__),
            );

        $display_html = array(
                'page_title' => 'get_phicPatientID',
                'param'     => $param_return,
                
            );
        return $display_html;
    }
    else
    {
        try
        {
            // WS Log Event - Start
            $WS_Log_Param = array(
                    'FunctionID' => $CI->get_FunctionID(__FUNCTION__)
                );

            $CI->WS_Log_Event($WS_Log_Param,'I');

            // WS Log Event - End
            
            $Authticate = $CI->WS_Authenticate_Param(__FUNCTION__,$param);

            if( $Authticate['Code'] <> 105 )
            {
                $WS_Log_Param = array(
                    'Parameter'     => $param,
                    'ReturnCode'    => $Authticate['Code'],
                    'ReturnMessage' => $Authticate['Message'],
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );

                $CI->WS_Log_Event($WS_Log_Param,'U');
                return $CI->WS_Response_Result($Authticate['Message'],$Authticate['Code']);
            }
            
            // Passed Authentication Validation -> Checked Parameter Content
            $chk_Parameter_Content = $CI->WS_Validate_Param(__FUNCTION__, 'GetPHICPatientID', $Authticate['Param']);
           
            if( $chk_Parameter_Content['Status'] <> 2 )
            {
                $Response_Code = ($chk_Parameter_Content['Status'] == 0) ? 104 : 103;
                $Response_Message = ($Response_Code == 104) ? 'Web Service Method is not available' : $chk_Parameter_Content['Message'];
                goto exit_function;
            }
          
            // Passed Content Validation
            $Type = "GetPHICPatientID";
            $Type_Details = $CI->get_Method_Param_Key_Details($Type);
            $Submitted_Param = $chk_Parameter_Content['Message'];

            $User_Profile = $CI->WS_User_Profile;
            $UserType = (int) $CI->WS_User_Type;
            
            continue_here:
            $Response_Code = 109;
            $Response_Message = array(
                        'Client_Type'=>'NON-MEMBER',
                        'Client_PhilHealth_No'=>"",
                        'Client_LastName' => strtoupper($Submitted_Param['Client_LastName']),
                        'Client_FirstName' => strtoupper($Submitted_Param['Client_FirstName']),
                        'Client_MiddleName' => strtoupper($Submitted_Param['Client_MiddleName']),
                        'Client_SuffixName' => strtoupper($Submitted_Param['Client_SuffixName']),
                        'Client_Sex' => $Submitted_Param['Client_Sex'],
                        'Client_DateofBirth' => date("Y-m-d",strtotime($Submitted_Param['Client_DateofBirth'])),
                   );

            $addWhere = ($Submitted_Param['Client_SuffixName'] <> '' &&  $Client_SuffixName <> 'NA' &&  $Client_SuffixName <> 'N/A' ) ? " and SUFFIXNAME = '".strtoupper($Submitted_Param['Client_SuffixName'])."' " : "";
            $chk = $CI->sqlhelper->replicate->select("registry_member")->where(" LASTNAME = '".strtoupper($Submitted_Param['Client_LastName'])."' and FIRSTNAME = '".strtoupper($Submitted_Param['Client_FirstName'])."' and MIDDLENAME = '".strtoupper($Submitted_Param['Client_MiddleName'])."' ".$addWhere." and SEX = '".strtoupper($Submitted_Param['Client_Sex'])."' and BIRTHDAY = '".date("Y-m-d",strtotime($Submitted_Param['Client_DateofBirth']))."' ")->ex_select('','','1')->row();
            if( $chk['Count'] > 0 )
            {
                $Client_PIN = $chk['Data']['MEMID_NO'];
                $Client_PIN = substr($Client_PIN,0,2)."-".substr($Client_PIN,2,-1)."-".substr($Client_PIN,11);

                $Response_Code = 108;
                $Response_Message = array(
                        'Client_Type'=>'MEMBER',
                        'Client_PhilHealth_No'=>$Client_PIN,
                        'Client_LastName' => strtoupper($Submitted_Param['Client_LastName']),
                        'Client_FirstName' => strtoupper($Submitted_Param['Client_FirstName']),
                        'Client_MiddleName' => strtoupper($Submitted_Param['Client_MiddleName']),
                        'Client_SuffixName' => strtoupper($Submitted_Param['Client_SuffixName']),
                        'Client_Sex' => $Submitted_Param['Client_Sex'],
                        'Client_DateofBirth' => date("Y-m-d",strtotime($Submitted_Param['Client_DateofBirth'])),
                    );
            }
            else
            {
                // Check to Dependent Table
                 $chk = $CI->sqlhelper->replicate->select("registry_dependent")->where(" LASTNAME = '".strtoupper($Submitted_Param['Client_LastName'])."' and FIRSTNAME = '".strtoupper($Submitted_Param['Client_FirstName'])."' and MIDDLENAME = '".strtoupper($Submitted_Param['Client_MiddleName'])."' ".$addWhere." and SEX = '".strtoupper($Submitted_Param['Client_Sex'])."' and BIRTHDAY = '".date("Y-m-d",strtotime($Submitted_Param['Client_DateofBirth']))."' ")->ex_select('','','1')->row();
                if( $chk['Count'] > 0 )
                {
                    $Client_PIN = $chk['Data']['MEMID_NO'];
                    $Client_PIN = substr($Client_PIN,0,2)."-".substr($Client_PIN,2,-1)."-".substr($Client_PIN,11);

                    $Response_Code = 108;
                    $Response_Message = array(
                            'Client_Type'=>'MEMBER',
                            'Client_PhilHealth_No'=>$DEPENDENT,
                            'Client_LastName' => strtoupper($Submitted_Param['Client_LastName']),
                            'Client_FirstName' => strtoupper($Submitted_Param['Client_FirstName']),
                            'Client_MiddleName' => strtoupper($Submitted_Param['Client_MiddleName']),
                            'Client_SuffixName' => strtoupper($Submitted_Param['Client_SuffixName']),
                            'Client_Sex' => $Submitted_Param['Client_Sex'],
                            'Client_DateofBirth' => date("Y-m-d",strtotime($Submitted_Param['Client_DateofBirth'])),
                        );
                }
                else
                {
                    // Call PhilHealth Web Service
                    try
                    {
                        $getSettings = $CI->sqlhelper->local->select("settings")->where("SettingName = 'PHICWS' ")->ex_select("","","1")->row();
                        
                        $wsdl = ( trim($getSettings['Data']['SettingValue']) <> "" ) ? trim($getSettings['Data']['SettingValue']) : "";
                        
                        if( $wsdl <> "" )
                        {
                            $ws_server = new SoapClient($wsdl);

                            $Submitted_Param['Client_MiddleName'] = ( strtolower($Submitted_Param['Client_MiddleName']) == "na" || strtolower($Submitted_Param['Client_MiddleName']) == "n/a" ) ? "" : $Submitted_Param['Client_MiddleName'];
                            $Submitted_Param['Client_SuffixName'] = ( strtolower($Submitted_Param['Client_SuffixName']) == "na" || strtolower($Submitted_Param['Client_SuffixName']) == "n/a" ) ? "" : $Submitted_Param['Client_SuffixName'];
                            
                            $param = array(
                                "Lastname"=>strtoupper($Submitted_Param['Client_LastName']),
                                "Extname"=>strtoupper($Submitted_Param['Client_SuffixName']),
                                "Firstname"=>strtoupper($Submitted_Param['Client_FirstName']),
                                "Middlename"=>strtoupper($Submitted_Param['Client_MiddleName']),
                                "Sex"=>strtoupper($Submitted_Param['Client_Sex']),
                                "DOB"=>date("m/d/Y",strtotime($Submitted_Param['Client_DateofBirth'])),
                            );

                            $result =  $ws_server->__soapCall("ValidateMemberAndDependent", $param, NULL);
                            if(simplexml_load_string($result))
                            {
                                $response = simplexml_load_string($result);
                                $atts_object = $response->CLIENTDETAILS->attributes(); 
                                $atts_array = (array) $atts_object; 

                                if(isset($atts_array['@attributes']))
                                {
                                    $CLIENTDETAILS = $atts_array['@attributes'];

                                    // GET PIN
                                    $Response_Code = 108;
                                    $Client_PIN = $CLIENTDETAILS['MEMIDNO'];
                                    $Client_PIN = substr($Client_PIN,0,2)."-".substr($Client_PIN,2,-1)."-".substr($Client_PIN,11);
                                    
                                    if($CLIENTDETAILS['CLIENTTYPE'] == 'DEPENDENT')
                                    {
                                        $Client_PIN .= "-".$CLIENTDETAILS['SEQNO'];
                                    }

                                    $Response_Message = array(
                                            'Client_Type'=>$CLIENTDETAILS['CLIENTTYPE'],
                                            'Client_PhilHealth_No'=>$Client_PIN,
                                            'Client_LastName' => strtoupper($CLIENTDETAILS['LASTNAME']),
                                            'Client_FirstName' => strtoupper($CLIENTDETAILS['FIRSTNAME']),
                                            'Client_MiddleName' => strtoupper($CLIENTDETAILS['MIDINITIAL']),
                                            'Client_SuffixName' => strtoupper($CLIENTDETAILS['EXTNAME']),
                                            'Client_Sex' => $CLIENTDETAILS['SEX'],
                                            'Client_DateofBirth' => date("Y-m-d",strtotime($CLIENTDETAILS['BIRTHDATE'])),
                                        );                                    
                                }

                            }
                        }

                    }
                    catch(SoapFault $fault)
                    {
                        log_message("error","Web_Service, PhilHealth WS Call ValidateMemberAndDependent , ".json_encode($fault));   
                        $Response_Remarks = json_encode($fault);
                    }   
                }
            }
            
            exit_function:

            $WS_Log_Param = array(
                    'DataType'      => "GetPHICPatientID",
                    'Parameter'     => json_encode($Authticate['Param']),
                    'ReturnCode'    => $Response_Code,
                    'ReturnMessage' => ($Response_Code == 108 ) ? $Response_Message : json_encode($Response_Message),
                    'Remarks'       => $Response_Remarks,
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );
            $CI->WS_Log_Event($WS_Log_Param,'U');

            return $CI->WS_Response_Result($Response_Message,$Response_Code);

        }
        catch(Exception $Exception )
        {
            log_message("error","Web_Service, GetPHICPatientID, ".$Exception->getMessage());   
           
            $WS_Log_Param = array(
                        'DataType'      => "GetPHICPatientID",
                        'Parameter'     => $param,
                        'ReturnCode'    => 109,
                        'ReturnMessage' => "Cannot connect to PhilHealth Web Service",
                        'Remarks'       => $Exception->getMessage(),
                        'ResponseDateTime' => date('Y-m-d H:i:s')
                    );
            $CI->WS_Log_Event($WS_Log_Param,'U');

            return $CI->WS_Response_Result("Cannot connect to PhilHealth Web Service",109);
        }
    }
}

function get_upcmBilling($param = '',$loadGUI = false) 
{
    
    $CI = & get_instance();
    $CI->sqlhelper->local->setajaxEvent(1);
    $CI->response_code_list = $CI->response_code(true);
  
    $Response_Code = "";
    $Response_Message = "";
    $Response_Remarks = "";
    
   
    if($loadGUI)
    {
        $param_return = array(
                'methodname' =>  strtolower(__FUNCTION__),
                'FunctionID' => $CI->get_FunctionID(__FUNCTION__),
            );

        $display_html = array(
                'page_title' => 'get_upcmBilling',
                'param'     => $param_return
            );
        return $display_html;
    }
    else
    {
        try
        {
            // WS Log Event - Start
            $WS_Log_Param = array(
                    'FunctionID' => $CI->get_FunctionID(__FUNCTION__)
                );

            $CI->WS_Log_Event($WS_Log_Param,'I');

            // WS Log Event - End
            
            $Authticate = $CI->WS_Authenticate_Param(__FUNCTION__,$param);

            if( $Authticate['Code'] <> 105 )
            {
                $WS_Log_Param = array(
                    'Parameter'     => $param,
                    'ReturnCode'    => $Authticate['Code'],
                    'ReturnMessage' => $Authticate['Message'],
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );

                $CI->WS_Log_Event($WS_Log_Param,'U');
                return $CI->WS_Response_Result($Authticate['Message'],$Authticate['Code']);
            }
            
            // Passed Authentication Validation -> Checked Parameter Content
            $chk_Parameter_Content = $CI->WS_Validate_Param(__FUNCTION__, 'GetUPCMBilling', $Authticate['Param']);
           
            if( $chk_Parameter_Content['Status'] <> 2 )
            {
                $Response_Code = ($chk_Parameter_Content['Status'] == 0) ? 104 : 103;
                $Response_Message = ($Response_Code == 104) ? 'Web Service Method is not available' : $chk_Parameter_Content['Message'];
                goto exit_function;
            }
          
            // Passed Content Validation
            $Type = "GetUPCMBilling";
            $Type_Details = $CI->get_Method_Param_Key_Details($Type);
            $Submitted_Param = $chk_Parameter_Content['Message'];

            $User_Profile = $CI->WS_User_Profile;
            $UserType = (int) $CI->WS_User_Type;
            
            continue_here:
            
            // Check for Billing
            $chk = $CI->sqlhelper->replicate->select("tsekap_tbl_billsummary")->where(" HealthFacilityCode = '".trim($Submitted_Param['HealthFacilityCode'])."' AND EFF_QTR = '".trim($Submitted_Param['Billing_Quarter'])."' and EFF_YEAR = ".trim($Submitted_Param['Billing_Year'])." ")->ex_select("","","1")->row();
            if( $chk['Count'] > 0 )
            {
                $Response_Code = 113;
                $Response_Message = array(
                    'ACCRE_NO'=>$chk['Data']['ACCRE_NO'],
                    'GEN_DATE'=>$chk['Data']['GEN_DATE'],
                    'BILL_NO'=>$chk['Data']['BILL_NO'],
                    'BILL_TYPE'=>$chk['Data']['BILL_TYPE'],
                    'EFF_QTR'=>$chk['Data']['EFF_QTR'],
                    'EFF_YEAR'=>$chk['Data']['EFF_YEAR'],
                    'ENLIST_TOTAL_AMT'=>$chk['Data']['ENLIST_TOTAL_AMT'],
                    'PROFILE_TOTAL_AMT'=>$chk['Data']['PROFILE_TOTAL_AMT'],
                    'HASH'=>$chk['Data']['HASH'],
                    'ENLIST_TOTAL_CNT'=>$chk['Data']['ENLIST_TOTAL_CNT'],
                    'PROFILE_TOTAL_CNT'=>$chk['Data']['PROFILE_TOTAL_CNT'],
                    'BILL_GTOTAL'=>$chk['Data']['BILL_GTOTAL'],
                    'ISAPPROVED_DATE'=>$chk['Data']['ISAPPROVED_DATE'],
                    'BILL_DETAILS'=>array()
                );

                // Get Billing Details
                $chk2 = $CI->sqlhelper->replicate->select("tsekap_tbl_billdetails","CATEGORY_CODE,ENLIST_CNT,PROFILE_CNT,ENLIST_AMT,PROFILE_AMT,TOT_ENLISTED_MEMCNT,TOT_ENLISTED_DEPCNT,TOT_PROFILED_MEMCNT,TOT_PROFILED_DEPCNT,TOT_ASSIGNED_MEM")->where("BILL_NO = '".trim($chk['Data']['BILL_NO'])."'")->result();
                if( $chk2['Count'] > 0 )
                {
                    foreach( $chk2['Count']['Data'] as $row => $col)
                    {
                        $CI->myutilities->array_insert($col,'CATEGORY_DESC',array('CATEGORY_DESC'=>$CI->myutilities->getRef_Desc(57,$col['CATEGORY_CODE'])));
                        $Response_Message['BILL_DETAILS'][$col['CATEGORY_CODE']] = $col;
                    }
                }
            }
            else
            {
                $Response_Code = 114;
                $Response_Message = "UPCM Billing doesn't exists!";
            }

            exit_function:

            $WS_Log_Param = array(
                    'DataType'      => "GetUPCMBilling",
                    'Parameter'     => json_encode($Authticate['Param']),
                    'ReturnCode'    => $Response_Code,
                    'ReturnMessage' => ($Response_Code == 113 ) ? $Response_Message : json_encode($Response_Message),
                    'Remarks'       => $Response_Remarks,
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );
            $CI->WS_Log_Event($WS_Log_Param,'U');

            return $CI->WS_Response_Result($Response_Message,$Response_Code);

        }
        catch(Exception $Exception )
        {
            log_message("error","Web_Service, GetUPCMBilling, ".$Exception->getMessage());   
           
            $WS_Log_Param = array(
                        'DataType'      => "GetUPCMBilling",
                        'Parameter'     => $param,
                        'ReturnCode'    => 114,
                        'ReturnMessage' => "Cannot connect to PhilHealth Replicated Database",
                        'Remarks'       => $Exception->getMessage(),
                        'ResponseDateTime' => date('Y-m-d H:i:s')
                    );
            $CI->WS_Log_Event($WS_Log_Param,'U');

            return $CI->WS_Response_Result("UPCM Billing doesn't exists!",114);
        }
    }
}

function get_upcmAssignmentList($param = '',$loadGUI = false) 
{
    
    $CI = & get_instance();
    $CI->sqlhelper->local->setajaxEvent(1);
    $CI->response_code_list = $CI->response_code(true);
  
    $Response_Code = "";
    $Response_Message = "";
    $Response_Remarks = "";
    
   
    if($loadGUI)
    {
        $param_return = array(
                'methodname' =>  strtolower(__FUNCTION__),
                'FunctionID' => $CI->get_FunctionID(__FUNCTION__),
            );

        $display_html = array(
                'page_title' => 'get_upcmAssignmentList',
                'param'     => $param_return
            );
        return $display_html;
    }
    else
    {
        try
        {
            // WS Log Event - Start
            $WS_Log_Param = array(
                    'FunctionID' => $CI->get_FunctionID(__FUNCTION__)
                );

            $CI->WS_Log_Event($WS_Log_Param,'I');

            // WS Log Event - End
            
            $Authticate = $CI->WS_Authenticate_Param(__FUNCTION__,$param);

            if( $Authticate['Code'] <> 105 )
            {
                $WS_Log_Param = array(
                    'Parameter'     => $param,
                    'ReturnCode'    => $Authticate['Code'],
                    'ReturnMessage' => $Authticate['Message'],
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );

                $CI->WS_Log_Event($WS_Log_Param,'U');
                return $CI->WS_Response_Result($Authticate['Message'],$Authticate['Code']);
            }
            
            // Passed Authentication Validation -> Checked Parameter Content
            $chk_Parameter_Content = $CI->WS_Validate_Param(__FUNCTION__, 'GetAssignmentList', $Authticate['Param']);
           
            if( $chk_Parameter_Content['Status'] <> 2 )
            {
                $Response_Code = ($chk_Parameter_Content['Status'] == 0) ? 104 : 103;
                $Response_Message = ($Response_Code == 104) ? 'Web Service Method is not available' : $chk_Parameter_Content['Message'];
                goto exit_function;
            }
          
            // Passed Content Validation
            $Type = "GetAssignmentList";
            $Type_Details = $CI->get_Method_Param_Key_Details($Type);
            $Submitted_Param = $chk_Parameter_Content['Message'];

            $User_Profile = $CI->WS_User_Profile;
            $UserType = (int) $CI->WS_User_Type;
            
            continue_here:

            $Response_Code = 120;
            $Response_Message = array(
                'Effectivity_Year' => @$Submitted_Param['Effectivity_Year'],
                'Accredication_No' => "",
                'Assignment_List_Count' => 0,
                'Assignment_List_Data' => array()
            );

            // Check and Get Accre No
            $chk = $CI->sqlhelper->replicate->select("phie_lib_accreditation")->where(" NHFR_LONG_CODE = '".$Submitted_Param['HealthFacilityCode']."'")->ex_select("","","1")->row();
            if( $chk['Count'] > 0 )
            {
                $AccreNo = $chk['Data']['ACCRE_NO'];
                $Response_Message['Accredication_No'] = $AccreNo;

                $get_assign_list = $CI->sqlhelper->replicate->select("tsekap_tbl_assign","ACCRE_NO as 'Accreditation_No', 'Not Available' as 'Category_ID',MEM_PIN as 'Members_Pin', 'Not Available' as 'Last_Name', 'Not Available' as 'First_Name', 'Not Available' as 'Middle_Name','Not Available' as 'Maiden_Name','Not Available' as 'Suffix_Name', 'Not Available' as 'Sex','Not Available' as 'Birthday',EFF_YEAR as 'Effectivity_Year',ASSIGN_DATE as 'Assign_Date'")->where(" ACCRE_NO = '".trim(@$AccreNo)."' AND EFF_YEAR ='".@$Submitted_Param['Effectivity_Year']."' ")->result();
                if($get_assign_list['Count'] > 0)
                {
                    $Response_Code = 119;

                    $Response_Message['Assignment_List_Count'] = $get_assign_list['Count'];

                    $Assignment_Details = $get_assign_list['Data'];

                    foreach( $Assignment_Details as $row => $col)
                    {
                        $Assignment_Details[$row]['Assign_Date'] = date("Y-m-d",strtotime($col['Assign_Date']));
                        $member_detail = $CI->sqlhelper->replicate->select("registry_member")->where(" MEMID_NO = '".$col['Members_Pin']."' ")->ex_select("","","1")->row();
                        
                        if( $member_detail['Count'] > 0)
                        {
                            $columnDetails = array(
                                    'NEW_CATEGORY_ID' => 'Category_ID',
                                    'LASTNAME' => 'Last_Name', 
                                    'FIRSTNAME' => 'First_Name', 
                                    'MIDDLENAME' => 'Middle_Name',
                                    'MAIDENNAME' => 'Maiden_Name',
                                    'SUFFIXNAME' => 'Suffix_Name', 
                                    'SEX' => 'Sex',
                                    'birthday' => 'Birthday'
                                );

                            foreach($columnDetails as $member_detail_col_name => $Assignment_Details_Col_Name)
                            {
                                if( array_keys($member_detail_col_name,$member_detail['Count']['Data']) )
                                {
                                    $Assignment_Details[$row][$Assignment_Details_Col_Name] = ($member_detail_col_name == "birthday") ? date("Y-m-d",strtotime($member_detail['Data'][$member_detail_col_name])) : $member_detail['Data'][$member_detail_col_name];
                                }
                            }
                        }

                    }

                    $Response_Message['Assignment_List_Data'] = $Assignment_Details;
                }
            }
           
            exit_function:

            $WS_Log_Param = array(
                    'DataType'      => "GetAssignmentList",
                    'Parameter'     => json_encode($Authticate['Param']),
                    'ReturnCode'    => $Response_Code,
                    'ReturnMessage' => json_encode($Response_Message) ,
                    'Remarks'       => $Response_Remarks,
                    'ResponseDateTime' => date('Y-m-d H:i:s')
                );

            $CI->WS_Log_Event($WS_Log_Param,'U');

            return $CI->WS_Response_Result($Response_Message,$Response_Code);

        }
        catch(Exception $Exception )
        {
            log_message("error","Web_Service, GetAssignmentList, ".$Exception->getMessage());   
           
            $WS_Log_Param = array(
                        'DataType'      => "GetAssignmentList",
                        'Parameter'     => $param,
                        'ReturnCode'    => 120,
                        'ReturnMessage' => "Cannot connect to PhilHealth Replicated Database",
                        'Remarks'       => $Exception->getMessage(),
                        'ResponseDateTime' => date('Y-m-d H:i:s')
                    );
            $CI->WS_Log_Event($WS_Log_Param,'U');

            return $CI->WS_Response_Result("",120);
        }
    }
}