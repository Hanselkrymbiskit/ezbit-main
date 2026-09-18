<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->data['pagetype'] = "Downloads";
        $this->breadcrumbs->push('<i class="fa fa-home"></i>&nbsp;Home', '/home');
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
        if( count(@$_GET) > 0 )
        {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }
        

        $this->breadcrumbs->push('<i class="fa fa-download"></i>&nbsp;Downloads', '/download');

        $this->data['title'] = "Downloads"; 
        $this->data['PageTitle'] = "Downloads"; 
        $indexFile = 'index2';
        $headerfooter = true;

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
        );

        $this->data['loadjschild'] = array(
            'global/js/Plugin/bootstrap-datepicker',
            'global/js/Plugin/clockpicker',
            'global/js/Plugin/formatter',
            'global/js/Plugin/jquery-labelauty',
            'global/js/Plugin/responsive-tabs',
            'global/js/Plugin/closeable-tabs',
            'global/js/Plugin/tabs',
            'global/js/Plugin/summernote',
        ); 

        // if( $this->tank_auth->is_logged_in() )
        // {
        //     $indexFile =  'index2';
        //     $headerfooter = true;

        //     $pageheaderaction = '
        //         <div id="PageTitle-Button-Holder" class="float-right">
        //             <button type="button" id="btn_newDownloadItem" name="btnActionButton" class="btn btn-dark mr-5" title="New Download Item" onclick="window.location.href=\''.site_url('
        //                 download/newdownloaditem').'\'">
        //                 <i class="fa fa-plus mr-10"></i>New Download
        //             </button>
        //             '.@$aaddBack.'
        //         </div>      
        //     ';

        //     if( $this->usertype > 3 )
        //     {
        //         $pageheaderaction = '';
        //     }
             
        //     $pageheaderaction = '';
        //     $this->data['pageheaderaction'] = @$pageheaderaction;  
        // }

        $this->load->template('templates/download/'.$indexFile,$this->data,'',$headerfooter);     
    }

    function download_List()
    {
        if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
        
        try
        {
            $DataTableConfig = @$_SESSION['DataTables_Sessions'][$_REQUEST['TableID']];

            if($this->tank_auth->is_logged_in())
            {
                switch((int) $this->usertype)
                {
                    case (int) $this->usertype > 3:
                        $w = "a.Enable='Y' and a.AllowPublic = 'Y' and (a.PublishedS_DateTime <= '".date('Y-m-d H:i:s')."' and find_in_set(".( (int) $this->userprofile['user_classification']).",a.UserClassification))";
                    break;

                    case 3:
                        $w = "a.Enable='Y' and a.AllowPublic = 'Y' and a.PublishedS_DateTime <= '".date('Y-m-d H:i:s')."'";
                    break;
                }
            }
            else
            {
                $w = " a.Enable='Y' and a.AllowPublic = 'Y' and a.PublishedS_DateTime <= '".date('Y-m-d H:i:s')."' ";
            }
            

            $table = "
                (select
                    a.DataID,
                    a.DownloadFile_Title,
                    a.DownloadFile_Description,
                    a.DownloadFile_FileID,
                    a.DownloadFile_FileExternalURL,
                    a.PublishedS_DateTime,
                    a.PublishedE_DateTime,
                    a.AllowPublic,
                    a.`Enable`,
                    a.UserClassification,
                    a.DownloadCounter,
                    a.LastDownloadDateTime,
                    a.Created_By,
                    a.Created_DateTime,
                    a.Updated_By,
                    a.Updated_DateTime,
                    b.File_Description,
                    b.File_Attachment,
                    b.File_Path,
                    b.File_FileName,
                    b.File_FileSize,
                    b.File_FileType
                from system_download_list a 
                left join system_fileupload b on b.DataCount = a.DownloadFile_FileID 
                ".(( @$w <> '' ) ? " where ".$w : "").") as DownloadTables
            "; // target table = normal table, view table, virtual table
            
            // Table's primary key
            $primaryKey = 'DataID'; // Primary Key of the Table for normal table, 

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

            //$this->load->model('api/m_api');
            // use to override datatable return function call
            $overRideFunctionCall = array(          
                
                'DataID_crud_view'    =>  function( $d, $row, $fieldid, $TargetTableID ) {                           
                                        
                   
                    $genKeys = $this->m_api->generatekeys();
                    $enryptString = $this->m_api->encryptdecryptString('encrypt',$genKeys,$d);
                    $enryptString .= ':'.$genKeys;
                    $enryptString = base64_encode($enryptString);

                    $viewLink = '<i class="fas fa-download" style="cursor:pointer" title="Download" onclick="window.open(\''.site_url('download/downloaditem/'.$enryptString).'\',\'_blank\')"></i>';  
                    
                    // if( $this->tank_auth->is_logged_in() )
                    // {
                    //     $dLInk = urlencode($this->encryption->encrypt($d));
                    //     $viewLink = '<i id="btn_viewDetails" dataid="'.$dLInk.'" class="fas fa-download" style="cursor:pointer" title="View Details" onclick="viewdownloaditem($(this))"></i>';  
                    // }
                    // else
                    // {
                    //     // $dLInk = urlencode($this->encryption->encrypt($d));
                    //     // $did = urlencode(base64_encode(base64_encode($d)));

                    //     $viewLink = '<i class="fas fa-download" style="cursor:pointer" title="Download" onclick="window.open(\''.site_url('download/downloaditem/'.$enryptString).'\',\'_blank\')"></i>';  
                    // }

                   
                    return @$viewLink;
                },                    
                'File_FileType'    =>  function( $d, $row, $fieldid, $TargetTableID ) {                           
                    $ex = explode(".",$row['File_FileName']); 
                    $exCnt = count($ex);                         
                    return (trim($d) <> "" || trim($d) <> 0) ? ((strlen($ex[$exCnt-1]) <=10) ? $ex[$exCnt-1] : '<i class="fas fa-ban"></i>') : $d;
                },
                'File_FileSize'    =>  function( $d, $row, $fieldid, $TargetTableID ) { 
                    return (@$d <> '') ? $this->myutilities->HumanSize($d,true) : '';                            
                   
                }, 
                             
            );
         
       
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

            $DataReturn = Datatables::complex( $_REQUEST, @$sql_details, $table, $primaryKey, $ColumnProperties, $whereResult, NULL ,@$groupBy,$_REQUEST['TableID'] ) ;

            echo json_encode($DataReturn);
        }
        catch(Exception $err)
        {
            echo json_encode(array( "draw"=> 0,"recordsTotal" => 0,"recordsFiltered" => 0,"data"=> array()));
        }
    }
    
    // function newdownloaditem()
    // {
    //     $this->data['loadcss'] = array(
    //         'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
    //         'global/vendor/clockpicker/clockpicker',
    //         'global/vendor/jquery-labelauty/jquery-labelauty',
    //         'global/vendor/formvalidation/formValidation',        
    //         'global/vendor/summernote/summernote-lite',
    //     );

    //     $this->data['loadjsmain'] = array(
    //         'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
    //         'global/vendor/clockpicker/bootstrap-clockpicker.min',
    //         'global/vendor/formatter/jquery.formatter',
    //         'global/vendor/formvalidation/formValidation.min',
    //         'global/vendor/formvalidation/framework/bootstrap4.min',
    //         'global/vendor/jquery-labelauty/jquery-labelauty', 
    //         'global/vendor/summernote/summernote.min',
    //     );

    //     $this->data['loadjschild'] = array(
    //         'global/js/Plugin/bootstrap-datepicker',
    //         'global/js/Plugin/clockpicker',
    //         'global/js/Plugin/formatter',
    //         'global/js/Plugin/jquery-labelauty',
    //         'global/js/Plugin/responsive-tabs',
    //         'global/js/Plugin/closeable-tabs',
    //         'global/js/Plugin/tabs',
    //         'global/js/Plugin/summernote',
    //     ); 

    //     $pageheaderaction = '
    //         <div id="PageTitle-Button-Holder" class="float-right">
    //             <button type="button" id="btn_Save" name="btnActionButton" class="btn btn-dark mr-5" title="Save">
    //                 <i class="fa fa-save mr-10"></i>Save
    //             </button>
    //             <button type="button" id="btn_Cancel" name="btnActionButton" class="btn btn-dark mr-5" title="Cancel">
    //                 <i class="fa fa-times mr-10"></i>Cancel
    //             </button>
    //         </div>      
    //     ';

    //     if( $this->usertype > 3 )
    //     {
    //         $pageheaderaction = '';
    //     }
         
    //     $this->data['title'] = "New Download Item"; 
    //     $this->data['PageTitle'] = "New Download Item";
    //     $this->data['pageheaderaction'] = @$pageheaderaction; 
    //     $this->load->template('templates/download/newdownloaditem',$this->data,'',1);   
    // }    

    // function newdownloaditem()
    // {
    //     $return = array(
    //         'Status' => 0,
    //         'Message' => "",
    //     );

    //     if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
    //     {
    //         goto ExitFunction;
    //     }

    //     if(isset($_POST['DataID']) && $_POST['DataID'] <> "")
    //     {
    //         $DataID = $this->encryption->decrypt(urldecode($_POST['DataID']));
    //         if($DataID <> "" )
    //         {
    //             $getDItem = $this->sqlhelper->local->select("system_download_list")->where("DataID = ".$DataID)->row();
    //             if($getDItem['Count'] > 0 )
    //             {
    //                 $this->data['datavalue'] = $getDItem['Data'];
    //                 // log_message("error", $this->data['datavalue']);
    //             }
    //         }
    //     }

    //     $newdownloaditemHTML = $this->load->view('templates/download/newdownloaditem', $this->data, true);
    //     $newdownloaditemHTML = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $newdownloaditemHTML));

    //     $return = array(
    //         'Status' => 1,
    //         'Message' => base64_encode(base64_encode($newdownloaditemHTML))
    //     );
        
    //     ExitFunction:
    //     echo json_encode($return);
    // }   

    // function submitdownloaddata()
    // {
    //     $return = array('Status' => 0, 'Message' => 'Failed to Process Submitted '.((isset($SubmittedData['DataID'])) ? 'Modify' : 'New').' Download Item'); 
    //     $ulog = array(
    //       'type'=>'DOWNLOAD ITEM',
    //       'action'=>'New Download Item',
    //       'description'=>'',
    //       'remarks'=>''
    //     );

    //     if( $_SERVER['REQUEST_METHOD'] <> 'POST')
    //     {
    //         goto exit_functions;
    //     }

    //     $ulog['remarks'] = $return['Message'];
    //     Process_Start:

    //     try
    //     {
    //         $SubmittedData = $_POST;
    //         $UpdateMode = false;
    //         if(isset($SubmittedData['DataID']) && $SubmittedData['DataID'] <> "")
    //         {
    //             $DataID = $this->encryption->decrypt(urldecode($SubmittedData['DataID']));
    //             if($DataID <> "" )
    //             {
    //                 $getDItem = $this->sqlhelper->local->select("system_download_list")->where("DataID = ".$DataID)->row();
    //                 if($getDItem['Count'] > 0 )
    //                 {
    //                     $UpdateMode = true;
    //                 }
    //             }
    //         }

    //         if($UpdateMode)
    //         {
    //             unset($SubmittedData['DataID']);
    //             $SubmittedData['Updated_By'] = $this->userid;
    //             $SubmittedData['Updated_DateTime'] = date('Y-m-d H:i:s');

    //             $SubmittedData['PublishedS_DateTime'] = (@$SubmittedData['PublishedStart_DateTime'] <> '' )  ? $this->myutilities->dbValueFormatter("PublishedStart_DateTime",@$SubmittedData['PublishedStart_DateTime']) : $SubmittedData['Updated_DateTime']  ;
    //             $SubmittedData['PublishedE_DateTime'] = $this->myutilities->dbValueFormatter("PublishedEnd_DateTime",@$SubmittedData['PublishedEnd_DateTime']) ;
    //             unset($SubmittedData['PublishedStart_DateTime']);
    //             unset($SubmittedData['PublishedEnd_DateTime']);
              
    //             if( $getDItem['Data']['FileLocation'] <> $SubmittedData['FileLocation'] || $getDItem['Data']['FileExternalURL'] <> $SubmittedData['FileExternalURL'])
    //             {
    //                 $SubmittedData['DownloadCounter']  = 0;
    //             }

    //             $runUpdateItem = $this->sqlhelper->local->update("system_download_list")->ex_update($SubmittedData)->where("DataID = ".$DataID);
    //             $runUpdateItem = $runUpdateItem->run();
    //             if($runUpdateItem['ErrorCode'] == "")
    //             {
    //                 $return['Status'] = 1;
    //                 $return['Message'] = "Download Item Successfully Updated";
    //             }
    //         }
    //         else
    //         {   
    //             if(isset($SubmittedData['DataID']))
    //             { 
    //                 unset($SubmittedData['DataID']);
    //             }
    //             // Check if Download Item already Exists
    //             $chkDL = $this->sqlhelper->local->select("system_download_list")->where("FileLocation = '".trim($SubmittedData['FileLocation'])."' or FileExternalURL = '".trim($SubmittedData['FileExternalURL'])."' ")->ex_select("","","1")->row();
    //             if( $chkDL['Count'] > 0 )
    //             {
    //                 $return['Message'] = "File for Download already exists!";
    //                 goto exit_functions;
    //             }

    //             if($SubmittedData['FileTYpe'] == 0)
    //             {

    //             }

                
    //             $SubmittedData['Created_By'] = $this->userid;
    //             $SubmittedData['Created_DateTime'] = date('Y-m-d H:i:s');

    //             $SubmittedData['PublishedS_DateTime'] = (@$SubmittedData['PublishedStart_DateTime'] <> '') ? $this->myutilities->dbValueFormatter("PublishedStart_DateTime",@$SubmittedData['PublishedStart_DateTime']) : $SubmittedData['Created_DateTime'] ;
    //             $SubmittedData['PublishedE_DateTime'] = $this->myutilities->dbValueFormatter("PublishedEnd_DateTime",@$SubmittedData['PublishedEnd_DateTime']) ;
    //             unset($SubmittedData['PublishedStart_DateTime']);
    //             unset($SubmittedData['PublishedEnd_DateTime']);


    //             // Insert New Download Item
    //             $runInsertItem = $this->sqlhelper->local->insert("system_download_list")->ex_insert($SubmittedData)->run();
    //             log_message("error",$runInsertItem);
    //             if($runInsertItem['ErrorCode'] == "")
    //             {
    //                 $return['Status'] = 1;
    //                 $return['Message'] = "New Download Item Successfully Submitted";
    //             }
    //         }
    //     }
    //     catch(Exception $e )
    //     {
    //         log_message("error","Error Processing Submitted Data [ ".$e->getMessage()." ] : ".json_encode($_POST));
    //     }

    //     exit_functions:
    //     $ulog['remarks'] = $return['Message'];
    //     $this->write_useractivitylog($ulog);
    //     echo json_encode($return);

    // }

    // function viewdownloaditem()
    // {
    //     $return = array(
    //         'Status' => 0,
    //         'Message' => "",
    //     );

    //     $ulog = array(
    //       'type'=>'VIEW DOWNLOAD ITEM',
    //       'action'=>'View Download Item Details',
    //       'description'=>'',
    //       'remarks'=>'Failed to View Download Item'
    //     );
        
    //     if( $_SERVER['REQUEST_METHOD'] <> 'POST' )
    //     {
    //         goto ExitFunction;
    //     }

    //     $DataID = $this->encryption->decrypt(urldecode($_POST['DataID']));

    //     if($DataID == "" )
    //     {
    //         goto ExitFunction;
    //     }
        
    //     // Get Download Item
    //     $getDItem = $this->sqlhelper->local->select("system_download_list")->where("DataID = ".$DataID)->row();
    //     if($getDItem['Count'] == 0 )
    //     {
    //         goto ExitFunction;
    //     }

    //     if( $getDItem['Data']['Enable'] == 'N')
    //     {
    //         goto ExitFunction;
    //     }

    //     if($this->tank_auth->is_logged_in())
    //     {
    //         switch((int) $this->usertype)
    //         {
    //             case 3:
    //             case (int) $this->usertype > 3 :

    //                 if( $getDItem['Data']['PublishedS_DateTime'] <> "" && $getDItem['Data']['PublishedS_DateTime'] > date('Y-m-d H:i:s') )
    //                 {
    //                     goto ExitFunction;
    //                 }

    //                 if( $getDItem['Data']['PublishedE_DateTime'] <> "" && $getDItem['Data']['PublishedE_DateTime'] <= date('Y-m-d H:i:s') )
    //                 {
    //                     goto ExitFunction;
    //                 }

    //                 if( (int) $this->usertype > 3)
    //                 {
    //                     $UserPermission = explode(",",$getDItem['Data']['UserClassification']);
    //                     if( $getDItem['Data']['AllowPublic'] == 'N' )
    //                     {
    //                         if(!in_array($this->userprofile['user_classification'],$UserPermission))
    //                         {
    //                             goto ExitFunction;
    //                         }
    //                     }
    //                 }
                    
    //             break;
    //         }
    //     }
    //     else
    //     {
    //         if( $getDItem['Data']['AllowPublic'] == 'N')
    //         {
    //             goto ExitFunction;
    //         }

    //         if( $getDItem['Data']['PublishedS_DateTime'] <> "" && $getDItem['Data']['PublishedS_DateTime'] > date('Y-m-d H:i:s') )
    //         {
    //             goto ExitFunction;
    //         }

    //         if( $getDItem['Data']['PublishedE_DateTime'] <> "" && $getDItem['Data']['PublishedE_DateTime'] <= date('Y-m-d H:i:s') )
    //         {
    //             goto ExitFunction;
    //         }
    //     }

    //     $DownloadDetails = $getDItem['Data'];
    //     $DownloadDetails['PublishedDateTime'] = ($DownloadDetails['PublishedS_DateTime'] <> "") ? $this->myutilities->formatValueDateTime('PublishedS_DateTime',$DownloadDetails['PublishedS_DateTime']) : $this->myutilities->formatValueDateTime('PublishedS_DateTime',$DownloadDetails['Created_DateTime']);

    //     $return = array(
    //         'Status' => 1,
    //         'Message' => $DownloadDetails
    //     );

    //     $ulog['remarks'] = 'Download Item Successfully Loaded';
        
    //     ExitFunction:
    //     echo json_encode($return);
        
    //     $this->write_useractivitylog($ulog);
    // }

    function downloaditem($DataID = '')
    {
        //$this->load->model('api/m_api');
        $decrypt = base64_decode($DataID);
        $decrypt = explode(":",$decrypt);
        $DataID = $this->m_api->encryptdecryptString('decrypt',$decrypt[1],$decrypt[0]);
        if($DataID == "" )
        {
            goto ExitFunction;
        }

        // Get Download Item
        $dQUERY = "
            select
                a.DataID,
                a.DownloadFile_Title,
                a.DownloadFile_Description,
                a.DownloadFile_FileID,
                a.DownloadFile_FileExternalURL,
                a.PublishedS_DateTime,
                a.PublishedE_DateTime,
                a.AllowPublic,
                a.`Enable`,
                a.UserClassification,
                a.DownloadCounter,
                a.LastDownloadDateTime,
                a.Created_By,
                a.Created_DateTime,
                a.Updated_By,
                a.Updated_DateTime,
                b.File_Description,
                b.File_Attachment,
                b.File_Path,
                b.File_FileName,
                b.File_FileSize,
                b.File_FileType
            from system_download_list a 
            left join system_fileupload b on b.DataCount = a.DownloadFile_FileID 
            where DataID = '".$DataID."'
        ";

        $getDItem = $this->sqlhelper->local->sql($dQUERY)->row();
       
        if($getDItem['Count'] == 0 )
        {
            goto ExitFunction;
        }

        if( $getDItem['Data']['PublishedE_DateTime'] <> '' && date('Y-m H:i:s') > $getDItem['Data']['PublishedE_DateTime'] )
        {
            goto ExitFunction;
        }

        $DownloadDetails = $getDItem['Data'];
        $DownloadDetails['DownloadCounter'] = ($DownloadDetails['DownloadCounter'] <> '') ? (int) $DownloadDetails['DownloadCounter'] : 0;
        $path = $DownloadDetails['File_Path'];
        if( $path )
        {
            $tmpPathEx = explode("/downloads/",$path);
            $path = FCPATH.'assets/downloads/'.$tmpPathEx[1];
            $dPath= $path;
            // log_message("error",$path);
            if(is_file($path))
            {
                // Update Counter
                $DCount = $DownloadDetails['DownloadCounter'];
                $updateDItem_array = array(
                    'DownloadCounter' => $DCount + 1,
                    'LastDownloadDateTime' => date("Y-m-d H:i:s")
                );
                $updateDItem = $this->sqlhelper->local->update("system_download_list")->ex_update($updateDItem_array)->where("DataID = ".$DataID)->row();

                $insertHArray = array(
                    'DownloadItemID' => $DataID,
                    'Downloaded_DateTime' => $updateDItem_array['LastDownloadDateTime'],
                    'Downloaded_By' => ($this->tank_auth->is_logged_in()) ? $this->userid : '',
                    'UserIpAddress' => $this->input->ip_address(),
                );

                $insertDHistory = $this->sqlhelper->local->insert('system_download_list_history')->ex_insert($insertHArray)->run();

                // required for IE
                if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off'); }

                // get the file mime type using the file extension
                $this->load->helper('file');
                $name = $DownloadDetails['File_FileName'];
                $mime = get_mime_by_extension($path);

                // Build the headers to push out the file properly.
                header('Pragma: public');     // required
                header('Expires: 0');         // no cache
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($path)).' GMT');
                header('Cache-Control: private',false);
                header('Content-Type: '.$mime);  // Add the mime type from Code igniter.
                header('Content-Disposition: attachment; filename="'.basename($name).'"');  // Add the file name
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: '.filesize($path)); // provide file size
                header('Connection: close');
                $path = str_replace(FCPATH, site_url(), $path);
                readfile($dPath); // push it out
            }
        }
        else
        {
            if( @$DownloadDetails['DownloadFile_FileExternalURL'] <> '')
            {
                header("Location: ".@$DownloadDetails['DownloadFile_FileExternalURL']);
                die();
            }
            else
            {

            }
        } 

        ExitFunction:
        echo "<script nonce=\"myself\" >self.close();</script>";

    }

    // function deletedownloaditem()
    // {
    //     $return = array('Status' => 0, 'Message' => 'Failed to Delete Download Item'); 
    //     $ulog = array(
    //       'type'=>'DOWNLOAD ITEM',
    //       'action'=>'Delete Download Item',
    //       'description'=>'',
    //       'remarks'=>''
    //     );

    //     if( $_SERVER['REQUEST_METHOD'] <> 'POST')
    //     {
    //         goto exit_functions;
    //     }

    //     $ulog['remarks'] = $return['Message'];
    //     Process_Start:

    //     try
    //     {
    //         $SubmittedData = $_POST;
    //         if(isset($SubmittedData['DataID']) && $SubmittedData['DataID'] <> "")
    //         {
    //             $DataID = $this->encryption->decrypt(urldecode($SubmittedData['DataID']));
    //             if($DataID <> "" )
    //             {
    //                 $getDItem = $this->sqlhelper->local->select("system_download_list")->where("DataID = ".$DataID)->row();
    //                 if($getDItem['Count'] > 0 )
    //                 {
    //                     if($getDItem['Data']['Enable'] == 'N')
    //                     {
    //                         $deleteD = $this->sqlhelper->local->delete("system_download_list")->where("DataID = ".$DataID)->run();
    //                         if( $deleteD['ErrorCode'] == "" )
    //                         {
    //                             $return = array('Status' => 1, 'Message' => 'Download Item Successfully Deleted'); 
    //                         }
    //                     }
    //                 }
    //             }
    //         }  
    //     }
    //     catch(Exception $e )
    //     {
    //         log_message("error","Error Processing Submitted Data [ ".$e->getMessage()." ] : ".json_encode($_POST));
    //     }

    //     exit_functions:
    //     $ulog['remarks'] = $return['Message'];
    //     $this->write_useractivitylog($ulog);
    //     echo json_encode($return);

    // }

}
   
   