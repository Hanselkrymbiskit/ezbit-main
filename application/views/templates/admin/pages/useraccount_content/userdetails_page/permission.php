<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();
$CI->load->model('administrator/m_useraccount');

$userpermissionList = $CI->myutilities->usermodulepermission(@$currentuserid);
                       
?>

<div class="panel border border-secondary mb-10">
  <div class="panel-body p-10">
    <h4>MODULE PERMISSION</h4>
    <form id="modulepermissionForm" class="" novalidate="novalidate" autocomplete="off">

    <div class="panel mb-10" style="border-bottom: 1px dashed #ddd;border-radius: 0px">
      <div class="panel-body p-10">
        <h6 class="text-uppercase">Registered Page</h6>
        <?php 
          $RegistrationPagePermission = [
            'View_Registration_List'        =>  ['Caption'=>'View Registration List', 'Value'=>@$userpermissionList['1']],
            'View_Registration_Details'     =>  ['Caption'=>'View Registration Details', 'Value'=>@$userpermissionList['2']],
            'Approved_Registration_Details' =>  ['Caption'=>'Approved / Disapproved Registration', 'Value'=>@$userpermissionList['3']],
          ];

        ?>
        <table class="table mb-0">
          <tr>
            <?php 
              foreach($RegistrationPagePermission as $rk => $rc)
              {
            echo '
            <td>    
              <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                <label class="text-uppercase">'.$rc['Caption'].'</label>
                <div class="" style="width:90%;">
                  <input type="hidden" class="form-control" id="'.$rk.'" name="'.$rk.'" forminputgroup="modulepermissionForm" title="Enable / Disable"  value="'.$rc['Value'].'">
                  <input type="checkbox" class="form-control" id="'.$rk.'" name="'.$rk.'"  title="Enable / Disable" forminputgroup="modulepermissionForm" value="'.$rc['Value'].'" switchgroup="permissiontoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" '.(( $rc['Value'] == 1) ? 'checked' : '').'>
                </div>  
              </div>   
            </td>
            ';
              }
            ?>
            
          </tr>
          
        </table>
      </div>
    </div>

    
    <div class="panel mb-10" style="border-bottom: 1px dashed #ddd;border-radius: 0px">
      <div class="panel-body p-10">
        <h6 class="text-uppercase">User Account Page</h6>
        <?php 
          $UserAccountPagePermission = [
            'View_UserAccount_List'        =>  ['Caption'=>'View User Account List', 'Value'=>@$userpermissionList['4']],
            'View_UserAccount_Details'     =>  ['Caption'=>'View User Account Details', 'Value'=>@$userpermissionList['5']],
            'ResendInfo_Action' =>  ['Caption'=>'Resend Account Information', 'Value'=>@$userpermissionList['6']],
            'ChangeEmail_Action' =>  ['Caption'=>'Change Account Email Address', 'Value'=>@$userpermissionList['7']],
            'ResetPassword_Action' =>  ['Caption'=>'Account Password Reset', 'Value'=>@$userpermissionList['8']],
            'ResendActivation_Action' =>  ['Caption'=>'Resend Activation Link', 'Value'=>@$userpermissionList['9']],
          ];

        ?>
        <table class="table mb-0">
          <tr>
            <?php 
              foreach($UserAccountPagePermission as $uk => $uc)
              {
            echo '
            <td>    
              <div class="form-group form-material mb-0 row" data-plugin="formMaterial" style="">
                <label class="text-uppercase">'.$uc['Caption'].'</label>
                <div class="" style="width:90%;">
                  <input type="hidden" class="form-control" id="'.$uk.'" name="'.$uk.'" forminputgroup="modulepermissionForm" title="Enable / Disable"  value="'.$uc['Value'].'">
                  <input type="checkbox" class="form-control" id="'.$uk.'" name="'.$uk.'"  title="Enable / Disable" forminputgroup="modulepermissionForm" value="'.$uc['Value'].'" switchgroup="permissiontoggle" data-plugin="switchery" data-switchery="true" data-color="#11c26d" data-size="medium" '.(( $uc['Value'] == 1) ? 'checked' : '').'>
                </div>  
              </div>   
            </td>
            ';
              }
            ?>
            
          </tr>
          
        </table>
      </div>
    </div>

    </form>
    <div class="panel mb-10" style="border-bottom: 1px dashed #ddd;border-radius: 0px">
      <div class="panel-body p-10 text-right">
        <button type="button" id="btnSavePermission" name="btnSavePermission" class="btn btn-dark" title="Save Permission Settings"><i class="fa fa-fw fa-save mr-10"></i>SAVE PERMISSION</button>
      </div>
    </div>
  </div>
</div>





<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){
  $("input[switchgroup*='permissiontoggle']").change(function(){
    $("input[type='hidden'][name='"+$(this).attr('name')+"'").val( ($(this).prop('checked')) ? 1 : 0 );
    $(this).val(($(this).prop('checked')) ? 1 : 0);
  });

  $("button[id='btnSavePermission']").click(function(){
      
      var permissionData = getFormData('modulepermissionForm');
      if( Object.keys(permissionData['ErrorData']).length > 0 )
      {
        popFormDataError(permissionData['ErrorData'],'Permission Settings','');      
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
            title: 'Permission Settings',
            html: (rp.Status == 1) ? 'Successfully Saved!' : 'Failed to Save Permission Settings!',
            icon: (rp.Status == 1) ? 'success' : 'error',
            showConfirmButton:true,
            confirmButtonText: 'Close',
            showCancelButton: false,
            cancelButtonText: "No",  
            reverseButtons: false,
          }).then((result) => {


       
          });
        };
        postUrl = site_url+'administrator/useraccount/savepermission';
        permissionData['FormData'].append('userid',"<?php echo @$currentuserid; ?>");
        submitFormData('json','',postUrl,permissionData['FormData'],savesettingsresults,true);
      }
  });
});  
</script>
