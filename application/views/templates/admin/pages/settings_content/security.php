<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>
<form id="formSettings" class="" novalidate="novalidate" autocomplete="off">

   <div class="panel">
    <div class="panel-heading">
      <h4 class="panel-title">Proxy IP Setting</h4>
    </div>
    <div class="panel-body">
      <table class="table table-sm" style="margin-bottom:0px">
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            IP List ( Comma Seperated IP )
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="text" class="form-control" id="ProxyIPList" name="ProxyIPList" forminputgroup="settingsform" title="Proxy IP List" placeholder="x.x.x.x" autocomplete="false" value="<?php echo @$CI->system_settings['ProxyIPList'] ; ?>" >    
              </div>  
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>

  <div class="panel">
    <div class="panel-heading">
      <h4 class="panel-title">Account and Login/SignIn Settings</h4>
    </div>
    <div class="panel-body">
      <table class="table table-sm" style="margin-bottom:0px">

        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            Minimum User Name Length 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:10%;">
                <input type="text" class="form-control" id="UsernameLength_Min" name="UsernameLength_Min" forminputgroup="settingsform" title="Minimum User Name Length" placeholder="0" autocomplete="false" value="<?php echo @$CI->system_settings['UsernameLength_Min'] ; ?>" required="required" onkeypress="return checkIt(event)">    
              </div>  
            </div>
          </td>
        </tr>

        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            Maximum User Name Length 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:10%;">
                <input type="text" class="form-control" id="UsernameLength_Max" name="UsernameLength_Max" forminputgroup="settingsform" title="Maximum User Name Length" placeholder="0" autocomplete="false" value="<?php echo @$CI->system_settings['UsernameLength_Max'] ; ?>" required="required" onkeypress="return checkIt(event)">    
              </div>  
            </div>
          </td>
        </tr>
        
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            Minimum Password Length 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:10%;">
                <input type="text" class="form-control" id="PasswordLength_Min" name="PasswordLength_Min" forminputgroup="settingsform" title="Minimum Password Length" placeholder="0" autocomplete="false" value="<?php echo @$CI->system_settings['PasswordLength_Min'] ; ?>" required="required" onkeypress="return checkIt(event)">    
              </div>  
            </div>
          </td>
        </tr>

        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            Maximum Password Length 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:10%;">
                <input type="text" class="form-control" id="PasswordLength_Max" name="PasswordLength_Max" forminputgroup="settingsform" title="Maximum Password Length" placeholder="0" autocomplete="false" value="<?php echo @$CI->system_settings['PasswordLength_Max'] ; ?>" required="required" onkeypress="return checkIt(event)">    
              </div>  
            </div>
          </td>
        </tr>

        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
          Login Attempt Max Count
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:10%;">
                <input type="text" class="form-control" id="LoginAttempt_Max" name="LoginAttempt_Max" forminputgroup="settingsform" title="Login Attempt Max Count" placeholder="0" autocomplete="false" value="<?php echo @$CI->system_settings['LoginAttempt_Max'] ; ?>" required="required" onkeypress="return checkIt(event)">    
              </div>  
            </div>
          </td>
        </tr>

        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
          Login Attempt - Allowed Login After (No. Hours) 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:10%;">
                <input type="text" class="form-control" id="LoginAttempt_Duration" name="LoginAttempt_Duration" forminputgroup="settingsform" title="Login Attempt - Allowed Login After (No. Hours)" placeholder="0" autocomplete="false" value="<?php echo @$CI->system_settings['LoginAttempt_Duration'] ; ?>" required="required" onkeypress="return checkIt(event)">    
              </div>  
            </div>
          </td>
        </tr>

      </table>
    </div>
  </div>

  <div class="panel">
    <div class="panel-heading">
      <h4 class="panel-title">ReCaptcha Settings v3</h4>
    </div>
    <div class="panel-body">
      <table class="table table-sm" style="margin-bottom:0px">
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            Enable ReCaptcha
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="hidden" class="form-control" id="ReCaptchaModule" name="ReCaptchaModule" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['ReCaptchaModule'] ; ?>">
                <input type="checkbox" class="form-control" id="ReCaptchaModule" name="ReCaptchaModule" title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['ReCaptchaModule'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['ReCaptchaModule'] == 1) ? 'checked' : ''; ?>>    
              </div>  
            </div>
          </td>
        </tr>
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            ReCaptcha Private Key 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="text" class="form-control" id="ReCaptcha_PrivateKey" name="ReCaptcha_PrivateKey" forminputgroup="settingsform" title="ReCaptcha Private Key" placeholder="Private Key" autocomplete="false" value="<?php echo @$CI->system_settings['ReCaptcha_PrivateKey'] ; ?>" required="required">    
              </div>  
            </div>
          </td>
        </tr>
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            ReCaptcha Public Key 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="text" class="form-control" id="ReCaptcha_PublicKey" name="ReCaptcha_PublicKey" forminputgroup="settingsform" title="ReCaptcha Public Key" placeholder="Public Key" autocomplete="false" value="<?php echo @$CI->system_settings['ReCaptcha_PublicKey'] ; ?>" required="required">    
              </div>  
            </div>
          </td>
        </tr>
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            ReCaptcha at Login/Signin 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="hidden" class="form-control" id="ReCaptchaatLogin" name="ReCaptchaatLogin" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['ReCaptchaatLogin'] ; ?>">
                <input type="checkbox" class="form-control" id="ReCaptchaatLogin" name="ReCaptchaatLogin"  title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['ReCaptchaatLogin'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['ReCaptchaatLogin'] == 1) ? 'checked' : ''; ?>>    
              </div>  
            </div>
          </td>
        </tr>
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            ReCaptcha at Registration 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="hidden" class="form-control" id="ReCaptchaatRegistration" name="ReCaptchaatRegistration" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['ReCaptchaatRegistration'] ; ?>">
                <input type="checkbox" class="form-control" id="ReCaptchaatRegistration" name="ReCaptchaatRegistration" title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['ReCaptchaatRegistration'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['ReCaptchaatRegistration'] == 1) ? 'checked' : ''; ?>>    
              </div>  
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>

  <div class="panel">
    <div class="panel-heading">
      <h4 class="panel-title">Buildin IconCaptcha</h4>
    </div>
    <div class="panel-body">
      <table class="table table-sm" style="margin-bottom:0px">
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            Enable IconCaptcha
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="hidden" class="form-control" id="IconCaptchaModule" name="IconCaptchaModule" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['IconCaptchaModule'] ; ?>">
                <input type="checkbox" class="form-control" id="IconCaptchaModule" name="IconCaptchaModule" title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['IconCaptchaModule'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['IconCaptchaModule'] == 1) ? 'checked' : ''; ?>>    
              </div>  
            </div>
          </td>
        </tr>
        
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            IconCaptcha at Login/Signin 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="hidden" class="form-control" id="IconCaptchaatLogin" name="IconCaptchaatLogin" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['IconCaptchaatLogin'] ; ?>">
                <input type="checkbox" class="form-control" id="IconCaptchaatLogin" name="IconCaptchaatLogin"  title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['IconCaptchaatLogin'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['IconCaptchaatLogin'] == 1) ? 'checked' : ''; ?>>    
              </div>  
            </div>
          </td>
        </tr>

        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            IconCaptcha at Registration 
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="hidden" class="form-control" id="IconCaptchaatRegistration" name="IconCaptchaatRegistration" forminputgroup="settingsform" title="Enable / Disable"  value="<?php echo @$CI->system_settings['IconCaptchaatRegistration'] ; ?>">
                <input type="checkbox" class="form-control" id="IconCaptchaatRegistration" name="IconCaptchaatRegistration" title="Enable / Disable" forminputgroup="settingsform" value="<?php echo @$CI->system_settings['IconCaptchaatRegistration'] ; ?>" switchgroup="settingstoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" <?php echo ( @$CI->system_settings['IconCaptchaatRegistration'] == 1) ? 'checked' : ''; ?>>    
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
      
      var settingData = getFormData('settingsform');
      if( Object.keys(settingData['ErrorData']).length > 0 )
      {
        popFormDataError(settingData['ErrorData'],'Security Settings','');      
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
            title: 'Security Settings',
            html: (rp.Status == 1) ? 'Successfully Saved!' : 'Failed to Save Security Settings!',
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
        settingData['FormData'].append('SettingTitle','Security Settings');
        submitFormData('json','',postUrl,settingData['FormData'],savesettingsresults,true);
      }
  });
});
</script>