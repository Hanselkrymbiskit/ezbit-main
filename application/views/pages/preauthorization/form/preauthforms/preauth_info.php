<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<?php if(@$ViewMode == true || @$ForCompliance == true ){ ?>
<div id="preauthStatus" class="card mb-10" style="">
  <div id="cardbody" class="card-body p-10 bg-white font-size-16" style="">
      <div class="row">
        <?php if( $CI->userclassification > 1){ ?>
        <div class="col-lg-12 mb-5 ">
          <div class="border-bottom border-success">
            <h4 class="mt-0 font-weight-bold">Health Facility : <span class="text-primary"><?php echo $CI->myutilities->getRef_Desc(104,@$preauthFormData['patientinfo']['healthfacility_code']); ?></span><span class="float-right">Code : <span class="text-primary"><?php echo $CI->myutilities->getRef_Desc(104,@$preauthFormData['patientinfo']['healthfacility_code'],false,'inst_code'); ?></span></span></h4>
            <h5 class="mt-0 font-weight-bold">Facility Address : <span class="text-primary"><?php echo $CI->myutilities->getRef_Desc(104,@$preauthFormData['patientinfo']['healthfacility_code'],false,'inst_address_street'); ?></span><span class="float-right">Contact No : <span class="text-primary"><?php echo $CI->myutilities->getRef_Desc(104,@$preauthFormData['patientinfo']['healthfacility_code'],false,'inst_contactno'); ?></span></span></h5>
          </div>
        </div>
        <?php } ?>
        <div class="col-lg-6 col-md-6">
          <div>
            <table class="table table-sm mb-0 ">
              <tr>
                <td class="w-p30 font-weight-bold">Case No</td>
                <td class="w-20">:</td>
                <td class="text-primary"><?php echo @$preauthFormData['patientinfo']['case_no']; ?></td>
              </tr>
              <tr>
                <td class="font-weight-bold">Submitted By</td>
                <td class="w-20">:</td>
                <td class="text-primary">
                  <?php
                    // ZPAMS-FIX (2026-09): getUserDetails() deliberately returns "" (not an
                    // array) when there's no submitted_by yet -- a normal state for any
                    // draft record that hasn't been submitted. Indexing that string with
                    // ['Account_Name'] is a fatal TypeError on PHP 8 ("Cannot access offset
                    // of type string on string"), where PHP 7 only warned. Guard with
                    // is_array() so drafts render instead of 500ing.
                    $submitted_by = $CI->myutilities->getUserDetails(@$preauthFormData['patientinfo']['submitted_by']);
                    echo (is_array($submitted_by) && isset($submitted_by['Account_Name']))
                        ? $CI->m_api->encryptdecryptString('decrypt',$CI->system_settings['PasswordHashing'],$submitted_by['Account_Name'],'MCrypt','aes-128','ecb')
                        : '';
                  ?>
                </td>
              </tr>
              <tr>
                <td class="font-weight-bold">Submitted Date / Time</td>
                <td class="w-20">:</td>
                <td class="text-primary"><?php echo (@$preauthFormData['patientinfo']['submitted_datetime'] <> '') ? date("M d, Y h:i:s",strtotime(@$preauthFormData['patientinfo']['submitted_datetime'])) : ''; ?></td>
              </tr>
              <?php if( @$preauthFormData['patientinfo']['ad_datetime'] <> '' ){ ?>
              <tr>
                <td class="w-p30 font-weight-bold">Approved Date / Time</td>
                <td class="w-20">:</td>
                <td class="text-primary"><?php echo (@$preauthFormData['patientinfo']['ad_datetime'] <> '') ? date("M d, Y h:i:s",strtotime(@$preauthFormData['patientinfo']['ad_datetime'])) : ''; ?></td>
              </tr>
              <tr>
                <td class="w-p30 font-weight-bold">Valid Until</td>
                <td class="w-20">:</td>
                <td class="text-primary"><?php echo (@$preauthFormData['patientinfo']['preauth_expiry_date'] <> '') ? date("M d, Y",strtotime(@$preauthFormData['patientinfo']['preauth_expiry_date'])) : ''; ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="border border-<?php echo $bborder[(int) $preauthFormData['patientinfo']['preauth_status']]; ?> p-10">
            <table class="table table-sm mb-0">
              <tr>
                <td class="w-p30 font-weight-bold border-top-0">Status</td>
                <td class="w-20  border-top-0">:</td>
                <td class="text-primary  border-top-0"><?php echo @$CI->myutilities->getRef_Desc(202,@$preauthFormData['patientinfo']['preauth_status']); ?></td>
              </tr>
              <tr>
                <td class="font-weight-bold">Status Date/Time</td>
                <td class="w-20">:</td>
                <td class="text-primary"><?php echo (@$preauthFormData['patientinfo']['preauth_status_datetime'] <> '') ? date("M d, Y h:i:s",strtotime(@$preauthFormData['patientinfo']['preauth_status_datetime'])) : ''; ?></td>
              </tr>
              <tr>
                <td class="font-weight-bold">Status By</td>
                <td class="w-20">:</td>
                <td class="text-primary">
                  <?php
                    // ZPAMS-FIX (2026-09): same getUserDetails()-returns-"" issue as the
                    // Submitted By block above -- preauth_status_by is legitimately NULL
                    // until the record is actually acted on. Guard the same way.
                    $preauth_status_by = $CI->myutilities->getUserDetails(@$preauthFormData['patientinfo']['preauth_status_by']);
                    if (is_array($preauth_status_by) && isset($preauth_status_by['Account_Name']))
                    {
                      echo $CI->m_api->encryptdecryptString('decrypt',$CI->system_settings['PasswordHashing'],$preauth_status_by['Account_Name'],'MCrypt','aes-128','ecb');
                      if(@$preauth_status_by['User_ClassificationID'] > 1)
                      {
                        echo '<span class="font-size-12"><br>'.@$preauth_status_by['User_Classification'];
                        $sby = $CI->myutilities->getRegistrationInfo(@$preauthFormData['patientinfo']['preauth_status_by']);
                        echo '<br>'.$CI->myutilities->getRef_Desc(204,str_pad(@$sby['ProCode'], 2,0,STR_PAD_LEFT)).'</span>';
                      }
                      else
                      {
                        echo '<span class="font-size-12"><br>Health Facility</span>';
                      }
                    }
                  ?>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
  </div>
</div> 
<?php } ?>
