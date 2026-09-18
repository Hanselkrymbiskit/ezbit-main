<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>
<form id="formSettings" class="" novalidate="novalidate" autocomplete="off">

  <div class="panel">
    <div class="panel-heading">
      <h4 class="panel-title">Twilio SMS</h4>
    </div>
    <div class="panel-body">
      <table class="table table-sm" style="margin-bottom:0px">

        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            Account SID
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="text" class="form-control" id="TwilioSID" name="TwilioSID" forminputgroup="settingsform" title="Twilio - Account SID" placeholder="" autocomplete="false" value="<?php echo @$CI->system_settings['TwilioSID'] ; ?>" required="required">    
              </div>  
            </div>
          </td>
        </tr>
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            Auth Token
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="email" class="form-control" id="TwilioAuth" name="TwilioAuth" forminputgroup="settingsform" title="Twilio - Account AUTH Token" placeholder="" autocomplete="false" value="<?php echo @$CI->system_settings['TwilioAuth'] ; ?>" required="required">    
              </div>  
            </div>
          </td>
        </tr>
        <tr>
          <td style="border-top: 0px;width:35%;vertical-align: middle">
            Twilio Valid Number
          </td>
          <td style="border-top: 0px;vertical-align: middle">   
            <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
              <div class="" style="width:90%;">
                <input type="email" class="form-control" id="TwilioVnumber" name="TwilioVnumber" forminputgroup="settingsform" title="Twilio - Valid Number" placeholder="" autocomplete="false" value="<?php echo @$CI->system_settings['TwilioVnumber'] ; ?>" required="required">    
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
            title: 'SMS Settings',
            html: (rp.Status == 1) ? 'Successfully Saved!' : 'Failed to Save SMS Settings!',
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
        settingData['FormData'].append('SettingTitle','SMS Settings');
        submitFormData('json','',postUrl,settingData['FormData'],savesettingsresults,true);
      }
  });
});
</script>