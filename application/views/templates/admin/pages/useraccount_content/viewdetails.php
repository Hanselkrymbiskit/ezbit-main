<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$userDetails = $CI->myutilities->getUserDetails($currentuserid);
$userProfile = $CI->myutilities->getUserProfile($currentuserid);
$userRegistrationInfo = $CI->myutilities->getRegistrationInfo($currentuserid);

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

$userActionButton = [
    // 'changeuserlevel'   => [
    //   'icon'    => 'fa-user-circle-o',
    //   'title'   => 'Change User Level',
    //   'fmethod' => 'changeuserlevel',
    // ],
    'resendAccountInfo'   => [
      'icon'    => 'fa-solid fa-address-card',
      'title'   => 'Resend Account Info',
      'fmethod' => 'resendaccountinfo',
    ],
    'changeemailaddress'   => [
      'icon' => 'fa-solid fa-envelope',
      'title'=> 'Change Email Address',
      'fmethod' => 'changeuseremailaddress',
    ],
    'resetpassword'   => [
      'icon' => 'fa-solid fa-key',
      'title'=> 'Reset Password',
      'fmethod' => 'resetpassword',
    ],
    'resendactivation'   => [
      'icon' => 'fa-solid fa-link',
      'title'=> 'Resend Activation Link',
      'fmethod' => 'resendactivation',
    ],
    'changeaccountstatus'   => [
      'icon' => ($userDetails['User_Status'] == 1) ? 'fa-solid fa-lock' : 'fa-solid fa-unlock-alt',
      'title'=> ($userDetails['User_Status'] == 1) ? 'Deactivate Account' : 'Activate Account',
      'fmethod' => 'accountstatusupdate',
    ],
    'bannedaccount'   => [
      'icon' => 'fa-solid fa-ban '.($userDetails['User_Status'] == 4) ? 'text-danger' : 'text-success',
      'title'=> ($userDetails['User_Status'] == 4) ? 'Unbanned Account' : 'Banned Account',
      'fmethod' => 'bannedaccount',
    ],
    'expiredaccount'   => [
      'icon' =>  'fa-solid fa-warning text-danger',
      'title'=> 'Renew Account?',
      'fmethod' => 'renewaccount',
    ],
];

if( (int) $CI->userclassification == 3 )
{
  if( $CI->system_module_permission['6'] <> 1 )
  {
    unset($userActionButton['resendAccountInfo']);
  }

  if( $CI->system_module_permission['7'] <> 1 )
  {
    unset($userActionButton['changeemailaddress']);
  }

  if( $CI->system_module_permission['8'] <> 1 )
  {
    unset($userActionButton['resetpassword']);
  }

  if( $CI->system_module_permission['9'] <> 1 )
  {
    unset($userActionButton['resendactivation']);
  }
}

if( $userDetails['User_Activated'] <> 1 )
{
  unset($userActionButton['bannedaccount']);
  unset($userActionButton['changeaccountstatus']);
  unset($userActionButton['expiredaccount']);
}
else
{
  unset($userActionButton['resendactivation']);
  if( $userDetails['User_Status'] <> 1 && $userDetails['User_Status'] <> 4 )
  {
    unset($userActionButton['bannedaccount']);
  }

  if( $userDetails['User_Status'] <> 3 )
  {
    unset($userActionButton['expiredaccount']);
  }

}
?>

<div class="row">
  <div class="col-lg-3">
    <!-- Page Widget -->
    
    <div class="card border border-secondary text-center mb-5">
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
              <div class="form-group form-material m-5 font-size-13" data-plugin="formMaterial">
                <label class="form-control-label mb-0 font-size-13 text-uppercase" for="inputText">Status</label>
                <div class="mb-0 text-primary font-size-16"><?php echo ($userDetails['User_Activated_DateTime'] <> '') ? $userDetails['User_StatusDesc'] : 'Pending for Activation'; ?></div>
              </div>
            </div>  
          </div>
        </div>
      </div>
      
    </div>

    <!-- End Page Widget -->
  </div>

  <div class="col-lg-9">
    <!-- Panel -->
    <div class="panel border border-secondary mb-10">
      <div class="panel-body nav-tabs-animate nav-tabs-horizontal p-10" data-plugin="tabs">
        <div class="h-50">
          <ul id="userDetailsTab" class="nav nav-tabs nav-tabs-line float-left" role="tablist">
            <li class="nav-item" role="presentation"><a class="active nav-link" data-toggle="tab" href="#activityhistory"
                aria-controls="activityhistory" role="tab"><i class="f fa-list mr-10"></i>Activity History</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#loginhistory" aria-controls="loginhistory"
                role="tab"><i class="f fa-list mr-10"></i>Login History</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#statushistory" aria-controls="statushistory"
                role="tab"><i class="f fa-list mr-10"></i>Status History</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#clientwindow" aria-controls="clientwindow"
                role="tab"><i class="f fa-desktop mr-10"></i>Client Window</a></li>
            <?php if( (int) $userDetails['User_ClassificationID'] == 3 && (int) $this->usertype < 3 ) { ?>
            <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#permission" aria-controls="permission"
                role="tab"><i class="f fa-universal-access mr-10"></i>Module Permission</a></li>
            <?php } ?>
          </ul>
          <ul class="nav float-right">
            <?php 
              foreach( $userActionButton as $abmenu => $abmenuConfig)
              {
                echo '
                  <li class="nav-item  text-center">
                    <button type="button" id="'.$abmenu.'" name="'.$abmenu.'" btnGroup="btnUserDetailsActionButton" class="btn btn-pure btn-default icon '.$abmenuConfig['icon'].' waves-effect waves-classic font-size-14" title="'.$abmenuConfig['title'].'" data-placement="top" data-toggle="tooltip" data-original-title="'.$abmenuConfig['title'].'" fmethod="'.@$abmenuConfig['fmethod'].'"></button>
                  </li>
                ';
              }
            ?>
          </ul>
        </div>
        <div class="tab-content">
        <?php
          $udPage = ['activityhistory','loginhistory','statushistory','clientwindow','permission'];

          if( (int) $userDetails['User_ClassificationID'] <> 3 ||  (int) $CI->usertype < 3 )
          {
            unset($udPage['permission']);
          }

          foreach( $udPage as $uPageiFle)
          {
            $phpFilePath = 'views/templates/admin/pages/useraccount_content/userdetails_page/'.$uPageiFle;
            $filePath =  APPPATH.$phpFilePath.'.php';
            if ( is_file($filePath))
            {
              $formcontent = $CI->load->view(str_replace('views/','',$phpFilePath), $CI->data, true);
              $formcontent = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $formcontent));
            } 

            echo '
            <div class="tab-pane '.(($uPageiFle == 'activityhistory') ? 'active' : '').' animation-slide-left" id="'.$uPageiFle.'" role="tabpanel">'.@$formcontent.'</div>
            ';

          }
        ?>
        </div>
      </div>
    </div>
    <!-- End Panel -->

  </div>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

  $("ul[id='userDetailsTab'] a").on('shown.bs.tab', function(event){
    var x = $(event.target).text();         // active tab
    var y = $(event.relatedTarget).text();  // previous tab
    $("div[id='cardbody_"+$(event.target).attr('href').replace("#tabpane_","")+"']").scrollTop(0);

    if( oTable_Obj.hasOwnProperty('user_'+$(event.target).attr('href').replace("#","")+'list') )
    {
       oTable_Obj['user_'+$(event.target).attr('href').replace("#","")+'list'].draw();  
    }
  }).on('hide.bs.tab', function(event){
    var x = $(event.target).text();         // active tab
    var y = $(event.relatedTarget).text();  // previous tab
  });


  $("body").on("click","#btn_LogoutAccount",function(){
    var target_url = site_url+'Utilities/logoutcurrentuser';
    var PostParam = new FormData();
    PostParam.append('userid',<?php echo $currentuserid; ?>);
    var rpResult = function(pp,rp){
      if(rp.Status == 1 )
      {

      }else{

      }
    };
    submitFormData('json','',target_url,PostParam,rpResult,false);
  });

  $("button[btnGroup*='btnUserDetailsActionButton']").click(function(){

    switch($(this).attr('id'))
    {
      case "btn_BacktoList":
        window.location.href=site_url+"/administrator/useraccount";
      break;
      case "btn_LogoutAccount":
       
      break;
      default:
        var param = new FormData();
        switch( $(this).attr('id') )
        {
          <?php 
          if( (int) $CI->userclassification == 3 )
          {
            $btnActionCount = 0;
            if( $CI->system_module_permission['6'] == 1 )
            {
              echo 'case "resendAccountInfo":';
              $btnActionCount++;
            }

            if( $CI->system_module_permission['7'] == 1 )
            {
              echo 'case "changeemailaddress":';
              $btnActionCount++;
            }

            if( $CI->system_module_permission['8'] == 1 )
            {
              echo 'case "resetpassword":';
              $btnActionCount++;
            }

            if( $CI->system_module_permission['9'] == 1 )
            {
              echo 'case "resendactivation":';
              $btnActionCount++;
            }

            if($btnActionCount > 0)
            {
              echo "param.append('user_id','".$currentuserid."');";
              echo "break;";
            }
          }
          else
          {
          ?>
          case "resendactivation":
          case "resendAccountInfo":
          case "changeemailaddress":
          case "resetpassword":
          case "expiredaccount":
            param.append('user_id',<?php echo $currentuserid; ?>);
          break;
          case "changeaccountstatus":
            param.append('user_id',<?php echo $currentuserid; ?>);
            param.append('status',<?php echo (@$userDetails['User_Status'] <> 1) ? 1 : 0; ?>);
          break;
          case "bannedaccount":
            param.append('user_id',<?php echo $currentuserid; ?>);
            param.append('status',<?php echo (@$userDetails['User_Status'] <> 4) ? 1 : 0; ?>);
          break;
          <?php  } ?>
        }

        passwordverification(param,$(this).attr('fmethod'));
      break;
    }
  });

  var signoutbtn = '<?php echo base64_encode(@$signoutbtn); ?>';
  function checkUserOnlineStatus(userid)
  {
    var target_url = site_url+'Utilities/onlineuser';
    var PostParam = new FormData();
    PostParam.append('userid',userid);
    var rpResult = function(pp,rp){
      if(rp.Status == 1 && rp.Message )
      {
        if (!$("button[id='btn_LogoutAccount'][name='btn_LogoutAccount']").length) {
          $("button[id='btn_BacktoList'][name='btn_BacktoList']").before(atob('<?php echo base64_encode(@$signoutbtn); ?>'));
        }
      }else{
        $("button[id='btn_LogoutAccount'][name='btn_LogoutAccount']").remove();
      }
      setTimeout(function(userid){
        checkUserOnlineStatus(userid);
      },5000,userid)
    };

    submitFormData('json','',target_url,PostParam,rpResult,false);
  }

  checkUserOnlineStatus(<?php echo $currentuserid; ?>);

});

</script>

