<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


?>
<form id="formSettings" class="" novalidate="novalidate" autocomplete="off">

<div class="panel">
  <div class="panel-heading">
    <h4 class="panel-title">General Information</h4>
  </div>
  <div class="panel-body">
    <table class="table table-sm" style="margin-bottom:0px">
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Application Name 
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="text" class="form-control" id="ApplicationName" name="ApplicationName" forminputgroup="settingsform" title="Application Name" placeholder="Application Name" autocomplete="false" value="<?php echo @$CI->system_settings['ApplicationName'] ; ?>" required="required">    
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Application Abbreviation 
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:10%;">
              <input type="text" class="form-control" id="ApplicationAbbre" name="ApplicationAbbre" forminputgroup="settingsform" title="Application Name" placeholder="Application Name" autocomplete="false" value="<?php echo @$CI->system_settings['ApplicationAbbre'] ; ?>" required="required">    
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Application Footer 
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="text" class="form-control" id="ApplicationFooter" name="ApplicationFooter" forminputgroup="settingsform" title="Application Name" placeholder="Application Footer" autocomplete="false" value="<?php echo @$CI->system_settings['ApplicationFooter'] ; ?>" required="required">
            </div>

          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Application Version 
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:10%;">
              <input type="text" class="form-control" id="ApplicationVersion" name="ApplicationVersion" forminputgroup="settingsform" title="Application Version" placeholder="Application Name" autocomplete="false" value="<?php echo @$CI->system_settings['ApplicationVersion'] ; ?>" required="required">    
            </div>  
          </div>
        </td>
      </tr>

    </table>
  </div>
</div>

<div class="panel">
  <div class="panel-heading">
    <h4 class="panel-title">Maintenance Mode</h4>
  </div>
  <div class="panel-body">
    <table class="table table-sm" style="margin-bottom:0px">
      
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Enable 
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="hidden" class="form-control" id="MaintenanceMode" name="MaintenanceMode" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['MaintenanceMode'] ; ?>">
              <input type="checkbox" class="form-control" id="MaintenanceMode" name="MaintenanceMode"  title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['MaintenanceMode'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['MaintenanceMode'] == 1) ? 'checked' : ''; ?>>

            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Maintenance From - Date / Time
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="text" class="form-control text-center float-left" id="MaintenanceFrom_Date" name="MaintenanceFrom_Date" title="Date" placeholder="mm/dd/yyyy" autocomplete="false" value="<?php echo (@$CI->system_settings['MaintenanceFromDateTime'] <> '') ? date('m/d/Y',strtotime(@$CI->system_settings['MaintenanceFromDateTime'])) : '' ; ?>"  data-plugin="formatter,datepicker" data-pattern="[[99]]/[[99]]/[[9999]]" style="width: 120px"> 
              <input type="text" class="form-control float-left text-center" id="MaintenanceFrom_Time" name="MaintenanceFrom_Time" title="Time" placeholder="hh:mm" autocomplete="false" value="<?php echo (@$CI->system_settings['MaintenanceFromDateTime'] <> '') ? date('H:m',strtotime(@$CI->system_settings['MaintenanceFromDateTime'])) : '' ; ?>" data-plugin="formatter,clockpicker" data-placement="top" data-autoclose="true" data-pattern="[[99]]:[[99]]"  style="width: 120px">
              <input type="hidden" class="form-control" id="MaintenanceFromDateTime" name="MaintenanceFromDateTime" forminputgroup="settingsform" autocomplete="false" value="<?php echo @$CI->system_settings['MaintenanceFromDateTime'] ; ?>">   
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Maintenance To - Date / Time
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="text" class="form-control text-center float-left" id="MaintenanceTo_Date" name="MaintenanceTo_Date" title="Date" placeholder="mm/dd/yyyy" autocomplete="false" value="<?php echo (@$CI->system_settings['MaintenanceToDateTime'] <> '') ? date('m/d/Y',strtotime(@$CI->system_settings['MaintenanceToDateTime'])) : '' ; ?>"  data-plugin="formatter,datepicker" data-pattern="[[99]]/[[99]]/[[9999]]" style="width: 120px"> 
              <input type="text" class="form-control float-left text-center" id="MaintenanceTo_Time" name="MaintenanceTo_Time" title="Time" placeholder="hh:mm" autocomplete="false" value="<?php echo (@$CI->system_settings['MaintenanceToDateTime'] <> '') ? date('H:m',strtotime(@$CI->system_settings['MaintenanceToDateTime'])) : '' ; ?>" data-plugin="formatter,clockpicker" data-placement="top" data-autoclose="true" data-pattern="[[99]]:[[99]]"  style="width: 120px">
              <input type="hidden" class="form-control" id="MaintenanceToDateTime" name="MaintenanceToDateTime" forminputgroup="settingsform" autocomplete="false" value="<?php echo @$CI->system_settings['MaintenanceToDateTime'] ; ?>">   
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Notify Users 
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="hidden" class="form-control" id="MaintenanceNotify" name="MaintenanceNotify" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['MaintenanceNotify'] ; ?>">
              <input type="checkbox" class="form-control" id="MaintenanceNotify" name="MaintenanceNotify"  title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['MaintenanceNotify'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['MaintenanceNotify'] == 1) ? 'checked' : ''; ?>>
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Notification Message
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <textarea class="form-control" id="MaintenanceMessage" name="MaintenanceMessage" forminputgroup="settingsform" title=""  value="<?php echo @$CI->system_settings['MaintenanceMessage'] ; ?>"><?php echo @$CI->system_settings['MaintenanceMessage'] ; ?></textarea>     
            </div>  
          </div>
        </td>
      </tr>
    </table>
  </div>
</div>

<div class="panel">
  <div class="panel-heading">
    <h4 class="panel-title">System Modules</h4>
  </div>
  <div class="panel-body">
    <table class="table table-sm" style="margin-bottom:0px">
      
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Account Registration 
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="hidden" class="form-control" id="RegistrationMode" name="RegistrationMode" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['RegistrationMode'] ; ?>">
              <input type="checkbox" class="form-control" id="RegistrationMode" name="RegistrationMode"  title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['RegistrationMode'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['RegistrationMode'] == 1) ? 'checked' : ''; ?>>    
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Account Registration - Auto Approved
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="hidden" class="form-control" id="RegistrationAutoApproved" name="RegistrationAutoApproved" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['RegistrationAutoApproved'] ; ?>">
              <input type="checkbox" class="form-control" id="RegistrationAutoApproved" name="RegistrationAutoApproved"  title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['RegistrationAutoApproved'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['RegistrationAutoApproved'] == 1) ? 'checked' : ''; ?>>    
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Account Registration - Require File Attachment
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="hidden" class="form-control" id="RegistrationFileAttachment" name="RegistrationFileAttachment" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['RegistrationFileAttachment'] ; ?>">
              <input type="checkbox" class="form-control" id="RegistrationFileAttachment" name="RegistrationFileAttachment"  title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['RegistrationFileAttachment'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['RegistrationFileAttachment'] == 1) ? 'checked' : ''; ?>>    
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Forgot Password 
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="hidden" class="form-control" id="ForgotPasswordMode" name="ForgotPasswordMode" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['ForgotPasswordMode'] ; ?>">
              <input type="checkbox" class="form-control" id="ForgotPasswordMode" name="ForgotPasswordMode" title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['ForgotPasswordMode'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['ForgotPasswordMode'] == 1) ? 'checked' : ''; ?>>    
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Frequently Ask Question(s)
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="hidden" class="form-control" id="FAQModule" name="FAQModule" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['FAQModule'] ; ?>">
              <input type="checkbox" class="form-control" id="FAQModule" name="FAQModule" title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['FAQModule'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['FAQModule'] == 1) ? 'checked' : ''; ?>>    
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Download
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="hidden" class="form-control" id="DownloadModule" name="DownloadModule" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['DownloadModule'] ; ?>">
              <input type="checkbox" class="form-control" id="DownloadModule" name="DownloadModule" title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['DownloadModule'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['DownloadModule'] == 1) ? 'checked' : ''; ?>>    
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          Contact Us
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="hidden" class="form-control" id="ContactUs" name="ContactUs" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['ContactUs'] ; ?>">
              <input type="checkbox" class="form-control" id="ContactUs" name="ContactUs" title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['ContactUs'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['ContactUs'] == 1) ? 'checked' : ''; ?>>    
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          About US
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="hidden" class="form-control" id="AboutModule" name="AboutModule" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['AboutModule'] ; ?>">
              <input type="checkbox" class="form-control" id="AboutModule" name="AboutModule" title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['AboutModule'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['AboutModule'] == 1) ? 'checked' : ''; ?>>    
            </div>  
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top: 0px;width:35%;vertical-align: middle">
          API (Application Programming Interface) 
        </td>
        <td style="border-top: 0px;vertical-align: middle">   
          <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
            <div class="" style="width:90%;">
              <input type="hidden" class="form-control" id="APIMode" name="APIMode" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['APIMode'] ; ?>">
              <input type="checkbox" class="form-control" id="APIMode" name="APIMode" forminputgroup="settingsform"  title="Enable / Disable"  value="<?php echo @$CI->system_settings['APIMode'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['APIMode'] == 1) ? 'checked' : ''; ?>>    
            </div>  
          </div>
        </td>
      </tr>
     
    </table>
  </div>
</div>

</form>



<script id="removethis" nonce="<?php echo $CI->nonceV; ?>"  type="text/javascript">  
$(document).ready(function(){

  $("input[switchgroup*='settingstoggle']").change(function(){
    $("input[type='hidden'][name='"+$(this).attr('name')+"'").val( ($(this).prop('checked')) ? 1 : 0 );
    $(this).val(($(this).prop('checked')) ? 1 : 0);
  });

  $("button[id='btnSaveSettings']").click(function(){
      
      if( $("input[id='MaintenanceFrom_Date']").val() )
      {
          var mdf = moment($("input[id='MaintenanceFrom_Date']").val()).format('YYYY-MM-DD');
          if( $("input[id='MaintenanceFrom_Time']").val() )
          {
            mdf += ' '+moment($("input[id='MaintenanceFrom_Date']").val()+' '+$("input[id='MaintenanceFrom_Time']").val()+':00').format('HH:mm:ss');
          }

          $("input[id='MaintenanceFromDateTime'][name='MaintenanceFromDateTime']").val(mdf);
      }
      else
      {
        $("input[id='MaintenanceFromDateTime'][name='MaintenanceFromDateTime']").val('');
      }

      if( $("input[id='MaintenanceTo_Date']").val() )
      {
          var mdf = moment($("input[id='MaintenanceTo_Date']").val()).format('YYYY-MM-DD');
          if( $("input[id='MaintenanceTo_Time']").val() )
          {
            mdf += ' '+moment($("input[id='MaintenanceTo_Date']").val()+' '+$("input[id='MaintenanceTo_Time']").val()+':00').format('HH:mm:ss');
          }

          $("input[id='MaintenanceToDateTime'][name='MaintenanceToDateTime']").val(mdf);
      }
      else
      {
        $("input[id='MaintenanceToDateTime'][name='MaintenanceToDateTime']").val('');
      }

      var settingData = getFormData('settingsform');
      if( Object.keys(settingData['ErrorData']).length > 0 )
      {
        popFormDataError(settingData['ErrorData'],'General Settings','');      
      } 
      else
      {
        var savesettingsresults = function(pp,rp){
          Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success mr-5 w-p45',
              cancelButton: 'btn btn-secondary w-p45',
            },
            buttonsStyling: false,
            willOpen: (fnRun) => {
              $(".swal2-container").css('z-index',$.topZIndex());
            }
          }).fire({
            title: 'General Settings',
            html: (rp.Status == 1) ? 'Successfully Saved!' : 'Failed to Save General Settings!',
            icon: (rp.Status == 1) ? 'success' : 'error',
            showConfirmButton:true,
            confirmButtonText: 'Close',
            showCancelButton: false,
            cancelButtonText: "No",  
            reverseButtons: false,
          }).then((result) => {

            if(rp.Status == 1)
            {
              window.location.reload(); 
            }
       
          });
        }
        postUrl = site_url+'administrator/settings/savesettings';
        settingData['FormData'].append('SettingTitle','General Settings');
        submitFormData('json','',postUrl,settingData['FormData'],savesettingsresults,true);
      }
  });
});
</script>