<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


$getSecurityQuestion = $CI->sqlhelper->local->select("ref_securityquestion")->ex_select("","","")->result();
$securityQuestionOptions = '<option value="">Select Security Question</option>';
foreach( $getSecurityQuestion['Data'] as $sqRow => $sqCol)
{
	$securityQuestionOptions .= '<option value="'.$sqCol['ID'].'" '.( ($sqCol['ID'] == @$CI->userprofile['security_question']) ? 'selected' : '').'>'.$sqCol['Question'].'</option>';
}

$passDosDont = $CI->load->view('templates/myaccount/dosdonts', $CI->data, true);
$passDosDont = base64_encode(trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $passDosDont)));

$userDetails = $CI->myutilities->getUserDetails($CI->userid);
$userProfile = $CI->myutilities->getUserProfile($CI->userid);
$userRegistrationInfo = $CI->myutilities->getRegistrationInfo($CI->userid);

if( (int) $userDetails['User_TypeID'] > 3)
{
  $registrationData = $userRegistrationInfo;
  $displayValue = $registrationData;
  $getReference = array(
    'Suffixname'          => 24,
    'Sex'                 => 23,
    'user_classification' => 4,
    'HealthFacilityCode'  => 104,
    'ProCode'             => 204,
    // 'AreaCode'            => 85,
    'OfficeName'          => 153,
    'OfficeDivisionName'  => 153,
    'Region'              => 101,
    'Province'            => 102,
    'City'                => 103,
    'register_status'     => 27,
    'action_by'           => 1,
  );
  foreach($getReference as $rkey => $refid )
  {
    if($rkey == 'ProCode')
    {
      $registrationData[$rkey] = str_pad((int) $registrationData[$rkey],2,0,STR_PAD_LEFT);
    }
    $displayValue[$rkey] = $CI->myutilities->getRef_Desc($refid,@$registrationData[$rkey]);
  }

  foreach($registrationData as $rr => $vv )
  {
      $registrationData[$rr] = $CI->myutilities->formatValueDateTime($rr,$vv);
  }

  $ClassificationArray = array(
    '1' => array(
      'FieldKey'=> 'HealthFacilityCode',
      'Caption' => 'Health Facility Name'
    ),
    '2' => array(
      'FieldKey'=> 'Region',
      'Caption' => 'Region'
    ),
    '3' => array(
      'FieldKey'=> 'Province',
      'Caption' => 'Province'
    ),
    '4' => array(
      'FieldKey'=> 'City',
      'Caption' => 'City / Municipalities'
    ),
    '5' => array(
      'FieldKey'=> 'ProCode',
      'Caption' => 'PhilHealth PRO Office'
    ),
  );

  $classificationShow = array();
  switch( (int) $registrationData['user_classification'] )
  {
    case 1: 
    case 6:
    case 7:
      $classificationShow = array('1','2','3','4');
    break;

    case 2: 
    case 3:
      $classificationShow = array('5');
    break;

    case 4:
    case 5:
      // $classificationShow = array('5');
    break;

    // case 5:
    //   $classificationShow = array('5','6','7');
    // break;

    // case 6:
    //   $classificationShow = array('1','2','3','5','6','7');
    // break;

    // case 7:
    //   $classificationShow = array('1','5','6','7');
    // break;

  }
}

?>

<div class="row">
  <div class="col-lg-3">
    <!-- Page Widget -->
    <div class="card text-center mb-5">
      <div class="card-block">
        <a class="avatar avatar-lg" href="javascript:void(0)" style="height:100px;width:100px">
          <img src="<?php echo @$CI->UserProfilePic; ?>" alt="..." style="height:100px;width:100px;border:1px solid #f1f4f5 ">
        </a>
        <h5 class="profile-user text-uppercase"><?php echo $CI->m_api->encryptdecryptString("decrypt",$this->system_settings['PasswordHashing'],$userDetails['Account_Name'],'MCrypt','aes-128','ecb'); ?></h5>
        <p class="profile-job text-uppercase text-primary" title="Classification"><?php echo ($userDetails['User_ClassificationID'] <> '') ? $CI->myutilities->getRef_Desc(4,(int) $userDetails['User_ClassificationID']) : $CI->myutilities->getRef_Desc(2,(int) $userDetails['User_TypeID']) ; ?></p>
        <div>
          <div class="card border-0 mb-0">
            <div class="card-block p-0">
              <div class="form-group form-material m-5 font-size-13" data-plugin="formMaterial" style="border-bottom: 1px dashed #ddd">
                <label class="form-control-label mb-0 font-size-13 text-uppercase" for="inputText">User Name</label>
                <div class="mb-0 text-primary font-size-16"><?php echo $userDetails['User_Name']; ?></div>
              </div>
              <div class="form-group form-material m-5 font-size-13" data-plugin="formMaterial" style="border-bottom: 1px dashed #ddd">
                <label class="form-control-label  mb-0 font-size-13 text-uppercase" for="inputText">Email Address</label>
                <div class="mb-0 text-primary font-size-16"><?php echo $userProfile['emailaddress']; ?></div>
              </div>
              <div class="form-group form-material m-5 font-size-13" data-plugin="formMaterial" style="border-bottom: 1px dashed #ddd">
                <label class="form-control-label  mb-0 font-size-13 text-uppercase" for="inputText">Date of Birth</label>
                <div class="mb-0 text-primary font-size-16"><?php echo date("m/d/Y",strtotime($userProfile['DateofBirth'])); ?></div>
              </div>
              <div class="form-group form-material m-5 font-size-13" data-plugin="formMaterial" style="border-bottom: 1px dashed #ddd">
                <label class="form-control-label  mb-0 font-size-13 text-uppercase" for="inputText">Sex</label>
                <div class="mb-0 text-primary font-size-16"><?php echo $userProfile['Sex']; ?></div>
              </div>
              <div class="form-group form-material m-5 font-size-13" data-plugin="formMaterial" style="border-bottom: 1px dashed #ddd">
                <label class="form-control-label  mb-0 font-size-13 text-uppercase" for="inputText">Contact #</label>
                <div class="mb-0 text-primary font-size-16"><?php echo $userProfile['Mobile_no']; ?></div>
              </div>
              <?php if( (int) $userDetails['User_TypeID'] > 3){ ?>
              <div>
                <?php foreach($classificationShow as $classfield ) {   $cset = $ClassificationArray[$classfield]; ?>
                <div class="form-group form-material m-5 font-size-13" data-plugin="formMaterial" style="border-bottom: 1px dashed #ddd">
                  <label class="form-control-label  mb-0 font-size-13 text-uppercase text-left" for="inputText"><?php echo @$cset['Caption'] ?></label>
                  <div class="mb-0 text-primary font-size-16 text-uppercase "><?php echo @$displayValue[$cset['FieldKey']]; ?></div>
                </div>
                <?php } ?>
              </div>
              <div class="form-group form-material m-5 font-size-13" data-plugin="formMaterial" style="border-bottom: 1px dashed #ddd">
                <label class="form-control-label mb-0 font-size-13 text-uppercase" for="inputText">Registered Date/Time</label>
                <div class="mb-0 text-primary font-size-16"><?php echo date('m/d/Y h:i:s A',strtotime($userRegistrationInfo['register_datetime'])); ?></div>
              </div>
              <?php if($userDetails['User_Activated_DateTime'] <> ''){ ?>
              <div class="form-group form-material m-5 font-size-13" data-plugin="formMaterial" style="border-bottom: 1px dashed #ddd">
                <label class="form-control-label mb-0 font-size-13 text-uppercase" for="inputText">Activated Date/Time</label>
                <div class="mb-0 text-primary font-size-16"><?php echo date('m/d/Y h:i:s A',strtotime($userDetails['User_Activated_DateTime'])); ?></div>
              </div>
              <div class="form-group form-material m-5 font-size-13" data-plugin="formMaterial" style="border-bottom: 1px dashed #ddd">
                <label class="form-control-label mb-0 font-size-13 text-uppercase" for="inputText">Expiration Date</label>
                <div class="mb-0 text-primary font-size-16"><?php echo date('m/d/Y',strtotime($userDetails['User_Expiration'])); ?></div>
              </div>
              <?php } ?>
              <?php } ?>

            </div>  
          </div>
        </div>
      </div>
      
    </div>

    
    <!-- End Page Widget -->
  </div>
	
	<div class="col-lg-5">
    <div class="panel mb-5">
      <div class="panel-body p-20">
        <h4>Change Password <a class="float-right" href="javascript:void(0)" onclick="passwordguide()"><i class="fa fa-info-circle text-secondary" title="Creating Password Guide"></i></a></h4>
        <div>
          <form autocomplete="off">
            <div class="form-group form-material">
              <label class="form-control-label" for="oldpassword">Old Password</label>
              <input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Password" autocomplete="off" required="required" formInputgroup="ChangePassword" />
            </div>
            <div class="form-group form-material">
              <label class="form-control-label" for="newpassword">New Password</label>
              <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="Password" autocomplete="off" required="required" data-plugin="strength" formInputgroup="ChangePassword" />
            </div>
            <div class="form-group form-material">
              <label class="form-control-label" for="confirmnewpassword">Confirm Password</label>
              <input type="password" class="form-control" id="confirmnewpassword" name="confirmnewpassword" placeholder="Password" autocomplete="off" required="required" data-plugin="strength" formInputgroup="ChangePassword" />
            </div>
           
            <div id="" class="form-group form-material mb-0">
              <button type="button" class="btn btn-primary" id="btnChangePassword" name="btnProfileActionButton" onclick="changepassword()"  >Change Password</button>
              <div class="float-right checkbox-custom checkbox-primary">
                <input type="checkbox" class="strength-toggle" title="Show/Hide Password" id="show_password" name="show_password">
                <label for="show_password">Show Password</label>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="panel mb-0">
      <div class="panel-body p-20">
        <h4>Forgot Password Settings</h4>
        <div>
          <form autocomplete="off" novalidate="novalidate" class="was-validated">
            <div class="form-group form-material">
              <label class="form-control-label" for="security_question">Security Question</label>
              <select class="form-control" id="security_question" name="security_question" formInputgroup="FPSettings" required="required" disabled="disabled"  />
                <?php echo $securityQuestionOptions; ?>
              </select>
            </div>
            <div class="form-group form-material">
              <label class="form-control-label" for="security_question_custom">Custom Question</label>
              <input type="text" class="form-control" id="security_question_custom" name="security_question_custom" formInputgroup="FPSettings"  placeholder="Custom Security Question" autocomplete="off" value="<?php echo @$CI->userprofile['security_question_custom']; ?>" disabled="disabled" />
            </div>      
            <div class="form-group form-material">
              <label class="form-control-label" for="security_answer">Security Answer </label><a class="float-right" href="javascript:void(0)" onclick="passwordverification('','viewsecurityanswer');"><i class="fa fa-eye-slash "></i></a>
              <input type="password" class="form-control" id="security_answer" name="security_answer" formInputgroup="FPSettings" placeholder="Your Security Answer" autocomplete="off" required="required" disabled="disabled" value="<?php echo substr($CI->encryption->encrypt(@$CI->userprofile['security_answer']),0,32); ?>" />
            </div>
   
            <div id="cfqButtonHolder" class="form-group form-material mb-0">
              <button type="button" class="btn btn-primary" id="btnChangeFPSettings" name="btnProfileActionButton" group="cfqButtonHolder">Change Settings</button>
              <button type="button" class="btn btn-primary d-none" id="btnSaveFPSettings" name="btnProfileActionButton" group="cfqButtonHolder"><i  class="icon fa-save"></i>&nbsp;Save</button>
              <button type="button" class="btn btn-primary d-none ml-10" id="btnCancelFPSettings" name="btnProfileActionButton" group="cfqButtonHolder"><i  class="icon fa-times"></i>&nbsp;Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

	<div class="col-lg-4">
    <div class="panel mb-0">
      <div class="panel-body p-20">

        <h4>Re-configure Time Based One-Time Password (TOTP)</h4>
        <div>

            <div class="panel">
              <div class="panel-body">
                <h5 class="text-center">Scan the QR code with your Authentication app.</h5>
                <div class="text-center">
                  <img class="w-200 mt-25" src="<?php echo $tfa->getQRCodeImageAsDataUri($CI->userprofile['emailaddress'], $secret); ?>">
                </div>
              </div>
            </div>

            <div class="panel">
              <div class="panel-body">
                <h3 class="panel-title text-success text-center">Confirm Authentication Code</h3>
                <div class="form-group form-material  w-p50" data-plugin="formMaterial" style="margin:0 auto;">
                  <div class="input-group">
                    <div class="form-control-wrap">
                      <input type="text" id="ConfirmAuthCode" class="form-control text-center" name="ConfirmAuthCode" forminputgroup="twofactorform" title="Authentication Code" autocomplete="false" value="" required="required" placeholder="Authentication Code Here" data-plugin="formatter" data-pattern="[[999999]]">
                    </div>
                    <span class="input-group-btn">
                      <button type="button" id="btnConfirmAuthCode" name="btnTwoFactor" class="btn btn-dark waves-effect waves-classic" forminputgroup="twofactorform">Submit</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <p>Recommended Authenticator APPS : </p>
              <a class="float-left w-p45 mr-5" nonce="<?php echo @$CI->nonceV; ?>" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en-US&pli=1" target="_blank"><img class="w-p100" src="<?php echo assetPath(); ?>images/pages/twofactor/google_auth.png"></a>
              <a class="float-right w-p45 " nonce="<?php echo @$CI->nonceV; ?>" href="https://play.google.com/store/search?q=microsoft+authenticator&c=apps&hl=en-US" target="_blank"><img class="w-p100" src="<?php echo assetPath(); ?>images/pages/twofactor/microsoft_auth.png"></a>
            </div>
            

        </div>
      </div>
    </div>
  </div>
  
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

function passwordguide()
{
  bootbox.dialog({
      size: 'xl',
      // title: "Password DO's and DONT's",
      message: atob('<?php echo $passDosDont; ?>'),
      closeButton:false,
      onShow: function(e) {

      }
  })
}

function forgotpasswordsetting(obj)
{
  switch(obj.attr('id'))
  {
    case 'btnChangeFPSettings': 
        $("button[name='btnProfileActionButton'][id='btnSaveFPSettings'],button[name='btnProfileActionButton'][id='btnCancelFPSettings']").removeClass('d-none').show().unbind('click').click(function(){
          forgotpasswordsetting($(this))
        });

        $("[formInputgroup*='FPSettings']").each(function(){
          if( $(this).attr('id') == 'security_question_custom' )
          {
            if( parseInt($("select[id='security_question'][formInputgroup='FPSettings']").val()) == 20 )
            {
              $(this).removeAttr('disabled');
            } 
          }
          else
          {
            $(this).removeAttr('disabled');
          }
        });

        $("[formInputgroup='FPSettings'][id='security_answer']").attr("type","text").val('');
        $("button[name='btnProfileActionButton'][id='btnChangeFPSettings']").addClass('d-none').hide().unbind('click');
    break;

    case 'btnSaveFPSettings':
    case 'btnCancelFPSettings':

      var cfpFunction = function(){
        $("[formInputgroup*='FPSettings']").each(function(){
          $(this).val( fpDefault[$(this).attr('id')] );
        });

        $("[formInputgroup*='FPSettings']").attr('disabled','disabled');
        $("[formInputgroup*='FPSettings']").closest("div[class*='form-group form-material']").removeClass('has-danger')
        $("[formInputgroup='FPSettings'][id='security_answer']").attr("type","password");

        $("button[name='btnProfileActionButton'][id='btnSaveFPSettings'],button[name='btnProfileActionButton'][id='btnCancelFPSettings']").hide().unbind('click');
        $("button[name='btnProfileActionButton'][id='btnChangeFPSettings']").removeClass('d-none').show().unbind('click').click(function(){
          forgotpasswordsetting($(this))
        });
      };

      var cM = (obj.attr('id') == 'btnSaveFPSettings') ? true : false;
      
      if(cM)
      {
        var gFormData = getFormData('FPSettings');
        if( Object.keys(gFormData['ErrorData']).length > 0 )
        {
          popFormDataError(gFormData['ErrorData'],'Forgot Password Settings','');
        }
        else
        {           
          var savefpsettings = function(FormParams)
          {
            postUrl = site_url+'myaccount/saveFPsettings'; 
            var SaveFPSettings_Result = function(FormData,PostResult){   
                if(PostResult.Status == 1)
                {
                  sfpMessage = "Successfully Saved!";
                  sfpType = "success";
                }
                else
                {
                  sfpMessage = PostResult.Message;
                  sfpType = "error";
                }

                Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-'+( (sfpType=='success') ? 'success' : 'danger'),
                  },
                  buttonsStyling: false,
                  willOpen: (fnRun) => {
                    $(".swal2-container").css('z-index',$.topZIndex());
                  }
                }).fire({
                  title: "Forgot Password Settings",
                  text: sfpMessage,
                  icon: sfpType,
                  confirmButtonText: 'Close',
                });

                if(PostResult.Status == 1)
                {
                  toastr.success("New Settings Successfully Saved","Forgot Password Settings");
                }
            };
            submitFormData('json','',postUrl,FormParams,SaveFPSettings_Result,true);
          }

          passwordverification(gFormData['FormData'],savefpsettings,cfpFunction);
        }
      }
      else
      {
        cfpFunction();
      }
    break;
  }
}

function viewsecurityanswer(FormData)
{
    postUrl = site_url+'myaccount/viewSecretAnswer'; 
    var vsa_result = function(FormData,PostResult){   
        
        if(PostResult.Status == 1)
        {
          $("[formInputgroup='FPSettings'][id='security_answer']").attr("type","text").val(PostResult.Message);
          setTimeout(function(){
            $("[formInputgroup='FPSettings'][id='security_answer']").attr("type","password").val(fpDefault['security_answer']);
          },5000);
        }

    };

    submitFormData('json','',postUrl,{PVToken:FormData},vsa_result,false);
} 

function changepassword()
{
  var gFormData = getFormData('ChangePassword');
  if( Object.keys(gFormData['ErrorData']).length > 0 )
  {
    popFormDataError(gFormData['ErrorData'],'Change Password','');
  }
  else
  {   
    postUrl = site_url+'myaccount/changepassword';
    var chp_result = function(FormData,PostResult){   
        
        if(PostResult.Status == 1)
        {
          sfpMessage = "New Account Password Successfully Set";
          sfpType = "success";
          toastr.success(sfpMessage,"Change Password");
        }
        else
        {
          sfpMessage = "Failed to Change Account Password";
          sfpType = "error";
          toastr.error(sfpMessage,"Change Password");
        }

          Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-'+( (sfpType=='success') ? 'success' : 'danger'),
            },
            buttonsStyling: false,
            willOpen: (fnRun) => {
              $(".swal2-container").css('z-index',$.topZIndex());
            }
          }).fire({
            title: "Change Password",
            text: sfpMessage,
            icon: sfpType,
            confirmButtonText: 'Close',
          });

    }

        submitFormData('json','',postUrl,gFormData['FormData'],chp_result,true); 
  }

  setTimeout(function(){
    $("[formInputgroup='ChangePassword']").each(function(){
      $(this).val('').keyup(); 
      $("label[class='form-control-label'][for='"+$(this).attr('id')+"']").closest("div.has-danger").removeClass('has-danger'); 
    });     
  },5000);
  
}

$(document).ready(function(){
  $("button[name='btnProfileActionButton'][id='btnChangeFPSettings']").unbind('click').click(function(){
        forgotpasswordsetting($(this))
      });
  fpDefault = getFormData('FPSettings');
  fpDefault = fpDefault['FormData'];
  $("select[id='security_question'][formInputgroup='FPSettings']").change(function(){
    if( $(this).val() == 20 )
    {
      $("input[id='security_question_custom'][formInputgroup='FPSettings']").attr('required','required').removeAttr('disabled').closest("div[class*='form-group form-material']").addClass('has-danger');
    }
    else
    {
      $("input[id='security_question_custom'][formInputgroup='FPSettings']").removeAttr('required').attr('disabled','disabled').val('').closest("div[class*='form-group form-material']").removeClass('has-danger');
    }
  });

  $("input[type='checkbox'][id='show_password']").click(function(){
    $("[formInputgroup='ChangePassword'][id='confirmnewpassword'],[formInputgroup='ChangePassword'][id='newpassword']").attr("type","password");
    if( $(this).prop("checked") )
    {
      $("[formInputgroup='ChangePassword'][id='confirmnewpassword'],[formInputgroup='ChangePassword'][id='newpassword']").attr("type","text");
    }
  })

  $("[formInputgroup='ChangePassword'][id='confirmnewpassword'],[formInputgroup='ChangePassword'][id='newpassword']").blur(function(){
    if( $(this).val() && $("[formInputgroup='ChangePassword'][id='"+(($(this).attr('id') !== 'confirmnewpassword' ) ? 'confirmn' : '')+"newpassword']").val() )
    {
      if( $(this).val() !== $("[formInputgroup='ChangePassword'][id='"+(($(this).attr('id') !== 'confirmnewpassword' ) ? 'confirmn' : '')+"newpassword']").val() )
      {
          $("button[id='btnChangePassword']").attr('disabled','disabled');
      }
      else
      {
          $("button[id='btnChangePassword']").removeAttr('disabled');
      } 
    }
  });

  $("button[id='btnConfirmAuthCode']").click(function(){
      if( !$("input[id='ConfirmAuthCode'][forminputgroup='twofactorform']").val() )
      {
        Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-danger',
          },
          buttonsStyling: false,
          willOpen: (fnRun) => {
            $(".swal2-container").css('z-index',$.topZIndex());
          }
        }).fire({
          title: "Invalid Authentication Code",
          text: 'Please enter Authentication Code',
          icon: "error",
          confirmButtonText: 'Close',
        });
      }
      else
      {
        var formdata = new FormData();
        formdata.append('confirmauthcode',btoa($("input[id='ConfirmAuthCode'][forminputgroup='twofactorform']").val()));
        formdata.append('secret',btoa('<?php echo $secret; ?>'));
        var confirmAuthResult = function(pp,rp){
            Swal.mixin({
              customClass: {
                confirmButton: 'btn btn-dark',
              },
              buttonsStyling: false,
              willOpen: (fnRun) => {
                $(".swal2-container").css('z-index',$.topZIndex());
              }
            }).fire({
              title: "",
              html: ( (rp.Status == 1) ? 'Two-Factor Authentication Successfully Configured' : 'Failed to Verify Authencication Code!'),
              icon: ( (rp.Status == 1) ? 'success' : 'error'),
              confirmButtonText: 'Close',
            }).then((result) => {
              if( rp.Status == 1)
              {
                window.location.href=site_url+'logout';
              }
              else
              {
                $("input[id='ConfirmAuthCode'][forminputgroup='twofactorform']").val('');
              }
            });
        };

        target_url = site_url+'myaccount/verifyauthcode'
        submitFormData('json','',target_url,formdata,confirmAuthResult,true);
      }
      
  });

});

</script>