<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#Renamed
class Settings extends MY_Controller {

    public $adminpageDir = 'templates/admin/pages/';

	function __construct()
	{
		parent::__construct();
        $this->data['PageName'] = get_class($this);
        $this->data['adminMenu'] = true;
        if((int) $this->usertype > 2)
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

    function general()
    {
        $this->breadcrumbs->push('<i class="fa fa-gear fa-fw"></i>&nbsp;Settings - General', '/administrator/settings/general');

        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
                <button type="button" id="btnSaveSettings" name="btnSaveSettings" class="btn btn-dark" title="Save Settings"><i class="fa fa-fw fa-save mr-10"></i>Save</button>
                       
            </div>     
        ';

        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/summernote/summernote-lite'
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/bootstrap-clockpicker.min',
            'global/vendor/formatter/jquery.formatter',
            'global/vendor/formvalidation/formValidation.min',
            'global/vendor/formvalidation/framework/bootstrap4.min',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/summernote/summernote.min'
        );

        $this->data['loadjschild'] = array(
             'global/js/Plugin/bootstrap-datepicker',
             'global/js/Plugin/clockpicker',
             'global/js/Plugin/formatter',
             'global/js/Plugin/jquery-labelauty',
             'global/js/Plugin/summernote'

        );

        $this->data['title'] = "General Settings"; 
        $this->data['PageTitle'] = "General Settings";       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'settings_content/general',  $this->data,'',1);     
    }

    function security()
    {
        $this->breadcrumbs->push('<i class="fa fa-gear fa-fw"></i>&nbsp;Settings - General', '/administrator/settings/security');

        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
                <button type="button" id="btnSaveSettings" name="btnSaveSettings" class="btn btn-dark" title="Save Settings"><i class="fa fa-fw fa-save mr-10"></i>Save</button>
                       
            </div>     
        ';

        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/summernote/summernote-lite'
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/bootstrap-clockpicker.min',
            'global/vendor/formatter/jquery.formatter',
            'global/vendor/formvalidation/formValidation.min',
            'global/vendor/formvalidation/framework/bootstrap4.min',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/summernote/summernote.min'
        );

        $this->data['loadjschild'] = array(
             'global/js/Plugin/bootstrap-datepicker',
             'global/js/Plugin/clockpicker',
             'global/js/Plugin/formatter',
             'global/js/Plugin/jquery-labelauty',
             'global/js/Plugin/summernote'

        );

        $this->data['title'] = "Security Settings"; 
        $this->data['PageTitle'] = "Security Settings";       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'settings_content/security',  $this->data,'',1);     
    }

    function email()
    {
        $this->breadcrumbs->push('<i class="fa fa-gear fa-fw"></i>&nbsp;Settings - General', '/administrator/settings/email');

        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
                <button type="button" id="btnSaveSettings" name="btnSaveSettings" class="btn btn-dark" title="Save Settings"><i class="fa fa-fw fa-save mr-10"></i>Save</button>
                       
            </div>     
        ';

        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/summernote/summernote-lite'
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/bootstrap-clockpicker.min',
            'global/vendor/formatter/jquery.formatter',
            'global/vendor/formvalidation/formValidation.min',
            'global/vendor/formvalidation/framework/bootstrap4.min',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/summernote/summernote.min'
        );

        $this->data['loadjschild'] = array(
             'global/js/Plugin/bootstrap-datepicker',
             'global/js/Plugin/clockpicker',
             'global/js/Plugin/formatter',
             'global/js/Plugin/jquery-labelauty',
             'global/js/Plugin/summernote'

        );

        $this->data['title'] = "Email Settings"; 
        $this->data['PageTitle'] = "Email Settings";       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'settings_content/email',  $this->data,'',1);     
    }

    function sms()
    {
        $this->breadcrumbs->push('<i class="fa fa-gear fa-fw"></i>&nbsp;Settings - General', '/administrator/settings/sms');

        $pageheaderaction = '
            <div id="PageTitle-Button-Holder" class="float-right">
                <button type="button" id="btnSaveSettings" name="btnSaveSettings" class="btn btn-dark" title="Save Settings"><i class="fa fa-fw fa-save mr-10"></i>Save</button>
                       
            </div>     
        ';

        $this->data['loadcss'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/clockpicker',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/formvalidation/formValidation',
            'global/vendor/summernote/summernote-lite'
        );

        $this->data['loadjsmain'] = array(
            'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
            'global/vendor/clockpicker/bootstrap-clockpicker.min',
            'global/vendor/formatter/jquery.formatter',
            'global/vendor/formvalidation/formValidation.min',
            'global/vendor/formvalidation/framework/bootstrap4.min',
            'global/vendor/jquery-labelauty/jquery-labelauty',
            'global/vendor/summernote/summernote.min'
        );

        $this->data['loadjschild'] = array(
             'global/js/Plugin/bootstrap-datepicker',
             'global/js/Plugin/clockpicker',
             'global/js/Plugin/formatter',
             'global/js/Plugin/jquery-labelauty',
             'global/js/Plugin/summernote'

        );

        $this->data['title'] = "SMS Settings"; 
        $this->data['PageTitle'] = "SMS Settings";       
        $this->data['pageheaderaction'] = @$pageheaderaction;
        $this->load->templateAdmin($this->adminpageDir.'settings_content/sms',  $this->data,'',1);     
    }

    function savesettings($internal=false,$formparameter = '')
    {
      $return = array(
            'Status'    => 0,
            'Message'   => 'Failed to Update User Permission'
        );

        if( @$this->usertype > 3 )
        {
          $return = array('Status' => 0, 'Message' => 'Permission Denied!');
          goto exit_function_here;
        }

        if( $_SERVER['REQUEST_METHOD'] == 'POST' && $internal)
        {
            $return = array('Status' => 0, 'Message' => 'Permission Denied!');
            goto exit_function_here;
        }

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
            {
               $return = array('Status' => 0, 'Message' => 'Permission Denied!');
               goto exit_function_here;
            }
            else
            {
              unset($_POST['UToken']);
              $SubmittedParam = $_POST;
            }
        }
        
        if($internal)
        {
            $SubmittedParam = $formparameter;
        }

        try{
         
            $sTitle = @$SubmittedParam['SettingTitle'];
            unset($SubmittedParam['SettingTitle']);
            // log_message("error",$SubmittedParam);
            $updateError = 0;
            $settingsCnt = 0;
            foreach($SubmittedParam as $key => $val)
            {
                try{
                    // ZPAMS-FIX (2026-09): this whole check-then-act block replaces an UPDATE-only
                    // save that silently discarded any setting with no matching row in
                    // system_settings (e.g. one newly added to a form) -- the UPDATE matched zero
                    // rows and reported success anyway. Now falls back to INSERT when missing.
                    $chkExisting = $this->sqlhelper->local->select("system_settings")->where("SettingName ='".$key."'")->row();

                    if( $chkExisting['Count'] > 0 )
                    {
                        $updateArray = [
                            'SettingValue' => $val
                        ];
                        $updateResult = $this->sqlhelper->local->update("system_settings")->ex_update($updateArray)->where("SettingName ='".$key."'")->run();
                    }
                    else
                    {
                        // Setting has no existing row yet (e.g. newly added to a form) - create it instead of silently doing nothing
                        $insertArray = [
                            'SettingName'  => $key,
                            'SettingValue' => $val,
                            'ValueType'    => ( in_array($val, ['0','1'], true) ) ? 9 : 3
                        ];
                        $updateResult = $this->sqlhelper->local->insert("system_settings")->ex_insert($insertArray)->run();
                    }

                    if($updateResult['ErrorCode'] <> "")
                    {
                        $updateError++;
                    }
                    else
                    {
                        $settingsCnt++;
                    }
                }catch (PDOException $e) {
                    $updateError++;
                } catch (Exception $e) {
                    $updateError++;
                }
            }

            if( count($SubmittedParam) == $settingsCnt )
            {
                $return['Status'] = 1;
                $return['Message'] = 'Successfully Saved!';
            }
            else if( count($SubmittedParam) > 0 && $settingsCnt == 0)
            {
                $return['Status'] = 0;
                $return['Message'] = 'Failed to Save New Settings!'; 
            }
            else
            {
                $return['Status'] = 1;
                $return['Message'] = 'Some Settings Successfully Saved!'; 
            }

        }catch (PDOException $e) {
            log_message("error","Controller : administrator/settings : Error Saving Data [ ".$e->getMessage()." ] : ".json_encode($SubmittedParam));
        } catch (Exception $e) {
            log_message("error","Controller : administrator/settings : Error Saving Data [ ".$e->getMessage()." ] : ".json_encode($SubmittedParam));
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

        $ulog = array(
            'type'=>@$sTitle,
            'action'=>@$return['Message'],
            'description'=>'',
            'remarks'=>''
          );

        $this->write_useractivitylog($ulog);  
    } 
}