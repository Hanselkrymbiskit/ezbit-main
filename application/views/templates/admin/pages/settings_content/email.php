<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>
<form id="formSettings" class="" novalidate="novalidate" autocomplete="off">

  <div class="panel mb-10">
    <div class="panel-heading">
      <h4 class="panel-title">Email Sender <small class="float-right text-info">By Default System will use SMTP for Email Sending</small></h4>

    </div>
    <div class="panel-body">
      <table class="table table-sm" style="margin-bottom:0px">
       
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            Use SendGrid for Sending Email 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="hidden" class="form-control" id="UseSendGrid" name="UseSendGrid" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['UseSendGrid'] ; ?>">
                <input type="checkbox" class="form-control" id="UseSendGrid" name="UseSendGrid"  title="Use SendGrid / Use SMTP" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['UseSendGrid'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['UseSendGrid'] == 1) ? 'checked' : ''; ?>>    
              </div>  
            </div>
          </td>
        </tr>
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            SendGrid API Key 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="text" class="form-control" id="SendGridAPIKey" name="SendGridAPIKey" forminputgroup="settingsform" title="SendGrid API Key" placeholder="" autocomplete="false" value="<?php echo @$CI->system_settings['SendGridAPIKey'] ; ?>" required="required">    
              </div>  
            </div>
          </td>
        </tr>
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            SendGrid Single Sender From 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="email" class="form-control" id="SendGridSendFrom" name="SendGridSendFrom" forminputgroup="settingsform" title="SendGrid Sender From" placeholder="" autocomplete="false" value="<?php echo @$CI->system_settings['SendGridSendFrom'] ; ?>" required="required">    
              </div>  
            </div>
          </td>
        </tr>
        
      </table>
    </div>
  </div>

  <?php
    $EmailListConfig = 6;
    $EMAILSettingsDisplay = '';
    for($e=1;$e <=6; $e++)
    {
      $ecnt = (($e > 1) ? $e : '');
      $EMAILSettingsDisplay .= '
      <div class="panel mb-10">
        <div class="panel-heading">
          <h4 class="panel-title">SMTP Setting '.$e.' <span class="text-danger float-right">'.( ($ecnt == '') ? 'Primary' : 'Failover').'</span></h4>
        </div>
        <div class="panel-body">
          <table class="table table-sm" style="margin-bottom:0px">
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                SMTP Host 
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="text" class="form-control" id="SMTPHost'.$ecnt.'" name="SMTPHost'.$ecnt.'" forminputgroup="settingsform" title="SMTP Host" placeholder="smtp.google.com" autocomplete="false" value="'.@$CI->system_settings['SMTPHost'.@$ecnt].'" required="required">    
                  </div>  
                </div>
              </td>
            </tr>
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                SMTP Port No.
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="text" class="form-control" id="SMTPPortNo'.$ecnt.'" name="SMTPPortNo'.$ecnt.'" forminputgroup="settingsform" title="SMTP Port No." placeholder="587" autocomplete="false" value="'.@$CI->system_settings['SMTPPortNo'.@$ecnt].'" required="required">    
                  </div>  
                </div>
              </td>
            </tr>
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                SMTP SSL Protocol 
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="hidden" class="form-control" id="SMTPProtocol'.$ecnt.'" name="SMTPProtocol'.$ecnt.'" forminputgroup="settingsform" title="Enable / Disable"  value="'.@$CI->system_settings['SMTPProtocol'.@$ecnt].'">
                    <input type="checkbox" class="form-control" id="SMTPProtocol'.$ecnt.'" name="SMTPProtocol'.$ecnt.'"  title="Enable / Disable" forminputgroup="settingsform" value="'.@$CI->system_settings['SMTPProtocol'.@$ecnt].'" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" '.( ( @$CI->system_settings['SMTPProtocol'.@$ecnt] == 1) ? 'checked' : '' ).'>    
                  </div>  
                </div>
              </td>
            </tr>
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                SMTP No SSL / TLS Protocol 
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="hidden" class="form-control" id="SMTPNoSSLTLS'.$ecnt.'" name="SMTPNoSSLTLS'.$ecnt.'" forminputgroup="settingsform" title="Enable / Disable"  value="'.@$CI->system_settings['SMTPNoSSLTLS'.@$ecnt].'">
                    <input type="checkbox" class="form-control" id="SMTPNoSSLTLS'.$ecnt.'" name="SMTPNoSSLTLS'.$ecnt.'"  title="Enable / Disable" forminputgroup="settingsform" value="'.@$CI->system_settings['SMTPNoSSLTLS'.@$ecnt].'" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" '.( ( @$CI->system_settings['SMTPNoSSLTLS'.@$ecnt] == 1) ? 'checked' : '' ).'>    
                  </div>  
                </div>
              </td>
            </tr>
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                SMTP Mail Type (Text Only)
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="hidden" class="form-control" id="SMTPTextOnly'.$ecnt.'" name="SMTPTextOnly'.$ecnt.'" forminputgroup="settingsform" title="Enable / Disable"  value="'.@$CI->system_settings['SMTPTextOnly'.@$ecnt].'">
                    <input type="checkbox" class="form-control" id="SMTPTextOnly'.$ecnt.'" name="SMTPTextOnly'.$ecnt.'"  title="Enable / Disable" forminputgroup="settingsform" value="'.@$CI->system_settings['SMTPTextOnly'.@$ecnt].'" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" '.( ( @$CI->system_settings['SMTPTextOnly'.@$ecnt] == 1) ? 'checked' : '' ).'>    
                  </div>  
                </div>
              </td>
            </tr>
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                SMTP Sanitize Text Content (For Text-Only Mail Type Content)
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="hidden" class="form-control" id="SMTPTextSanitize'.$ecnt.'" name="SMTPTextSanitize'.$ecnt.'" forminputgroup="settingsform" title="Enable / Disable"  value="'.@$CI->system_settings['SMTPTextSanitize'.@$ecnt].'">
                    <input type="checkbox" class="form-control" id="SMTPTextSanitize'.$ecnt.'" name="SMTPTextSanitize'.$ecnt.'"  title="Enable / Disable" forminputgroup="settingsform" value="'.@$CI->system_settings['SMTPTextSanitize'.@$ecnt].'" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" '.( ( @$CI->system_settings['SMTPTextSanitize'.@$ecnt] == 1) ? 'checked' : '' ).'>    
                  </div>  
                </div>
              </td>
            </tr>
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                SMTP User Name 
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="text" class="form-control" id="SMTPUser'.$ecnt.'" name="SMTPUser'.$ecnt.'" forminputgroup="settingsform" title="SMTP User Name" placeholder="youremail@gmail.com" autocomplete="false" value="'.@$CI->system_settings['SMTPUser'.@$ecnt].'" required="required">    
                  </div>  
                </div>
              </td>
            </tr>
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                SMTP Password 
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="password" class="form-control" id="SMTPPassword'.$ecnt.'" name="SMTPPassword'.$ecnt.'" forminputgroup="settingsform" title="SMTP Password" placeholder="" autocomplete="false" value="'.@$CI->system_settings['SMTPPassword'.@$ecnt].'" required="required">    
                  </div>  
                </div>
              </td>
            </tr>
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                Send Email From
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="text" class="form-control" id="SMTPFrom'.$ecnt.'" name="SMTPFrom'.$ecnt.'" forminputgroup="settingsform" title="Send Email From" placeholder="youremail@gmail.com" autocomplete="false" value="'.@$CI->system_settings['SMTPFrom'.@$ecnt].'" required="required">    
                  </div>  
                </div>
              </td>
            </tr>
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                Send Email From : Name
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="text" class="form-control" id="SMTPFromName'.$ecnt.'" name="SMTPFromName'.$ecnt.'" forminputgroup="settingsform" title="Send Email From : Name" placeholder="sender profile name" autocomplete="false" value="'.@$CI->system_settings['SMTPFromName'.@$ecnt].'" required="required">    
                  </div>  
                </div>
              </td>
            </tr>
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                Reply Email To
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="text" class="form-control" id="SMTPReplyTo'.$ecnt.'" name="SMTPReplyTo'.$ecnt.'" forminputgroup="settingsform" title="Reply Email To" placeholder="youremail@gmail.com" autocomplete="false" value="'.@$CI->system_settings['SMTPReplyTo'.@$ecnt].'" required="required">    
                  </div>  
                </div>
              </td>
            </tr>
            <tr>
              <td style="border-top: 0px;width:35%;vertical-align: middle">
                Reply Email To : Name
              </td>
              <td style="border-top: 0px;vertical-align: middle">   
                <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                  <div class="" style="width:90%;">
                    <input type="text" class="form-control" id="SMTPReplyToName'.$ecnt.'" name="SMTPReplyToName'.$ecnt.'" forminputgroup="settingsform" title="Reply Email To : Name" placeholder="reply to profile name" autocomplete="false" value="'.@$CI->system_settings['SMTPReplyToName'.@$ecnt].'" required="required">    
                  </div>  
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>

      ';
    }

    echo @$EMAILSettingsDisplay;
  ?>

</form>



<script id="removethis" nonce="<?php echo $CI->nonceV; ?>"  type="text/javascript">  
$(document).ready(function(){

  $("input[switchgroup*='settingstoggle']").change(function(){
    $("input[type='hidden'][name='"+$(this).attr('name')+"'").val( ($(this).prop('checked')) ? 1 : 0 );
    $(this).val(($(this).prop('checked')) ? 1 : 0);
  });

  $("button[id='btnSaveSettings']").click(function(){
      
      var settingData = getFormData('settingsform');
      if( Object.keys(settingData['ErrorData']).length > 0 )
      {
        popFormDataError(settingData['ErrorData'],'Email Settings','');      
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
            title: 'Email Settings',
            html: (rp.Status == 1) ? 'Successfully Saved!' : 'Failed to Save Email Settings!',
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
        settingData['FormData'].append('SettingTitle','Email Settings');
        submitFormData('json','',postUrl,settingData['FormData'],savesettingsresults,true);
      }
  });
});
</script>