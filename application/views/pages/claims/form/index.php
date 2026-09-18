<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$StepsMenu = [
  [
    'targetcontent' => 'preauth_personalinformation',
    'title'         => 'Patient and Member Information',
    'description'   => '',
  ],

  [
    'targetcontent' => 'preauth_submit',
    'title'         => 'Claims Document Attachment',
    'description'   => '',
  ],
];


$StepsMenu_Content = [];
$StemsBody_Content = [];

$wStepsMenu_Content = [];
$wStemsBody_Content = [];


$sm_cnt = 0;
$stepw = 12 / count($StepsMenu);
foreach($StepsMenu as $sm_index => $sm_prop)
{
  $sm_cnt++;
  $wStepsMenu_Content_Display= '
    <li class="nav-item">
      <a class="nav-link pt-20 pb-20 '.( ($sm_cnt == 1) ? ' active' : '' ).'" href="#'.@$sm_prop['targetcontent'].'">
        <div class="num">'.$sm_cnt.'</div>
        <span class="font-size-18">'.@$sm_prop['title'].'</span>
      </a>
    </li>
      ';

  $wStepsMenu_Content[] = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $wStepsMenu_Content_Display));


  $StemsBodyContent = '';
  $phpFilePath = 'views/pages/claims/form/preauthforms/'.$sm_prop['targetcontent'];
  if( is_file(APPPATH.$phpFilePath.'.php'))
  {
    $StemsBodyContent = $CI->load->view(str_replace('views/','',$phpFilePath), $CI->data, true);
    $StemsBodyContent = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $StemsBodyContent));
  }

  $wStemsBody_Content_Display = '
    <div id="'.$sm_prop['targetcontent'].'" title="'.$sm_prop['title'].'" class="tab-pane" role="tabpanel" aria-expanded="true" aria-labelledby="'.$sm_prop['targetcontent'].'">
      <div class="card  border-0 mb-0" style="">
          <div id="cardbody_'.$sm_prop['targetcontent'].'" class="card-body p-10 bg-white" style="">
            '.@$StemsBodyContent.'
          </div>
      </div>
    </div>
  ';
  $wStemsBody_Content[] = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $wStemsBody_Content_Display));

} 

$bborder = [1=>'secondary',2=>'info',3=>'primary',4=>'warning',5=>'danger',6=>'success'];

$hideee = false;
if( @$ViewMode == true || @$ForCompliance == true ){ 
  $hideee = true;
}


?>

<?php if( ( is_array(@$claimsHistory) && count($claimsHistory) > 0 ) || ( is_array(@$claimsHStatus) && count($claimsHStatus) > 1 ) ){ ?>
 
<div class="row">
  <?php if(@$currenthistoryid <> ''){ ?>
  <div class="col-lg-12 mb-5 ">
    <h3 class="mt-0 bg-white border p-10  font-weight-bold">
      <i class="fa fa-calendar mr-10"></i>Z-Benefit Claim Form Version Date : <?php echo date("M d, Y h:i:s A",strtotime($currenthistorydate)); ?>
      <?php 
      if( @$currenthistoryid <> '')
      {
        echo '
            <button type="button" class="btn btn-success float-right " onclick="window.location.href=\''.site_url('claims/index/view/'.bin2hex(base64_encode( ((int) $recentid) )) ).'\'" title="Click to View Recent Z - Benefit Claim Form"><i class="icon md-calendar float-left mr-10 text-white font-size-16" aria-hidden="true"></i>View Recent Form</button>
          ';
      }
      ?>
    </h3>
  </div>
  <?php } ?>
  <div class="col-lg-10">

<?php } ?>


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
        <div class="<?php echo (!$hideee) ? 'col-lg-12 col-md-12' : 'col-lg-6 col-md-6'; ?>">
          <div class="border border-<?php echo $bborder[(int) $preauthFormData['patientinfo']['preauth_status']]; ?> p-10">
            <h3 class="mt-0  font-weight-bold ">Pre-Authorization Information <i title="View Pre-Authorization Details" class="fa fa-fw fa-search float-right" role="button" onclick="window.open('<?php echo site_url('preauthorization/index/view/'.bin2hex(base64_encode((int) $preauthFormData['patientinfo']['rowid']))); ?>','_blank')"></i></h3>
            <table class="table table-sm mb-0 ">
              <tr>
                <td class="w-p40 font-weight-bold ">Case No</td>
                <td class="w-20">:</td>
                <td class="text-primary"><?php echo @$preauthFormData['patientinfo']['case_no']; ?></td>
              </tr>
              <tr>
                <td class="font-weight-bold">Submitted By</td>
                <td class="w-20">:</td>
                <td class="text-primary">
                  <?php 
                    $submitted_by = $CI->myutilities->getUserDetails(@$preauthFormData['patientinfo']['submitted_by']); 
                    echo $CI->m_api->encryptdecryptString('decrypt',$CI->system_settings['PasswordHashing'],$submitted_by['Account_Name'],'MCrypt','aes-128','ecb');
                  ?>
                </td>
              </tr>
              <tr>
                <td class="font-weight-bold">Submitted Date / Time</td>
                <td class="w-20">:</td>
                <td class="text-primary"><?php echo (@$preauthFormData['patientinfo']['submitted_datetime'] <> '') ? date("M d, Y h:i:s",strtotime(@$preauthFormData['patientinfo']['submitted_datetime'])) : ''; ?></td>
              </tr>
              <tr>
                <td class="w-p40 font-weight-bold">Status</td>
                <td class="w-20  ">:</td>
                <td class="text-primary "><?php echo @$CI->myutilities->getRef_Desc(202,@$preauthFormData['patientinfo']['preauth_status']); ?></td>
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
                    $preauth_status_by = $CI->myutilities->getUserDetails(@$preauthFormData['patientinfo']['preauth_status_by']); 
                    echo $CI->m_api->encryptdecryptString('decrypt',$CI->system_settings['PasswordHashing'],$preauth_status_by['Account_Name'],'MCrypt','aes-128','ecb');
                    if($preauth_status_by['User_ClassificationID'] > 1)
                    {
                      echo '<span class="font-size-12"><br>'.@$preauth_status_by['User_Classification']; 
                      $sby = $CI->myutilities->getRegistrationInfo(@$preauthFormData['patientinfo']['preauth_status_by']);
                      echo '<br>'.$CI->myutilities->getRef_Desc(204,str_pad($sby['ProCode'], 2,0,STR_PAD_LEFT)).'</span>';
                    }
                    else
                    {
                      echo '<span class="font-size-12"><br>Health Facility</span>'; 
                    }
                  ?>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <?php if($hideee){ ?>
        <div class="col-lg-6 col-md-6">
          <div class="border border-<?php echo $bborder[(int) $claimsFormData['claiminfo']['claims_status']]; ?> p-10">
            <h3 class="mt-0  font-weight-bold ">Z-Benefit Claim Information</h3>
            <table class="table table-sm mb-0">
              <tr>
                <td class="font-weight-bold">Submitted By</td>
                <td class="w-20">:</td>
                <td class="text-primary">
                  <?php 
                    $claims_submitted_by = $CI->myutilities->getUserDetails(@$claimsFormData['claiminfo']['submitted_by']); 
                    echo $CI->m_api->encryptdecryptString('decrypt',$CI->system_settings['PasswordHashing'],$claims_submitted_by['Account_Name'],'MCrypt','aes-128','ecb');
                    if($claims_submitted_by['User_ClassificationID'] > 1)
                    {
                      echo '<span class="font-size-12"><br>'.@$claims_submitted_by['User_Classification']; 
                      $sby = $CI->myutilities->getRegistrationInfo(@$claimsFormData['claiminfo']['submitted_by']);
                      echo '<br>'.$CI->myutilities->getRef_Desc(204,str_pad($sby['ProCode'], 2,0,STR_PAD_LEFT)).'</span>';
                    }
                  ?>
                </td>
              </tr>
              <tr>
                <td class="font-weight-bold">Submitted Date/Time</td>
                <td class="w-20">:</td>
                <td class="text-primary"><?php echo (@$claimsFormData['claiminfo']['claims_status_datetime'] <> '') ? date("M d, Y h:i:s",strtotime(@$claimsFormData['claiminfo']['claims_status_datetime'])) : ''; ?></td>
              </tr>
              <tr>
                <td class="w-p30 font-weight-bold">Status</td>
                <td class="w-20  ">:</td>
                <td class="text-primary "><?php echo @$CI->myutilities->getRef_Desc(206,@$claimsFormData['claiminfo']['claims_status']); ?></td>
              </tr>
              <tr>
                <td class="font-weight-bold">Status Date/Time</td>
                <td class="w-20">:</td>
                <td class="text-primary"><?php echo (@$claimsFormData['claiminfo']['claims_status_datetime'] <> '') ? date("M d, Y h:i:s",strtotime(@$claimsFormData['claiminfo']['claims_status_datetime'])) : ''; ?></td>
              </tr>
              <tr>
                <td class="font-weight-bold">Status By</td>
                <td class="w-20">:</td>
                <td class="text-primary">
                  <?php 
                    $claims_status_by = $CI->myutilities->getUserDetails(@$claimsFormData['claiminfo']['claims_status_by']); 
                    echo $CI->m_api->encryptdecryptString('decrypt',$CI->system_settings['PasswordHashing'],$claims_status_by['Account_Name'],'MCrypt','aes-128','ecb');
                    if($claims_status_by['User_ClassificationID'] > 1)
                    {
                      echo '<span class="font-size-12"><br>'.@$claims_status_by['User_Classification']; 
                      $sby = $CI->myutilities->getRegistrationInfo(@$claimsFormData['claiminfo']['claims_status_by']);
                      echo '<br>'.$CI->myutilities->getRef_Desc(204,str_pad($sby['ProCode'], 2,0,STR_PAD_LEFT)).'</span>';
                    }
                    else
                    {
                      echo '<span class="font-size-12"><br>Health Facility</span>'; 
                    }
                  ?>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <?php } ?>
      </div>
  </div>
</div> 

<div id="claimFormWizard" class="bg-white">
    <div class="progress">
      <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <ul class="nav nav-progress bg-white">
        <?php echo implode("",@$wStepsMenu_Content); ?>
    </ul>
    <form id="claimFormData" class="" novalidate="novalidate" style="">
     <?php if( $CI->config->item('csrf_protection') ){ ?>
     <input type="hidden" name="<?php echo @$CI->security->get_csrf_token_name(); ?>" value="<?php echo @$CI->security->get_csrf_hash(); ?>" />
     <?php } ?>
     <input type="hidden" name="illnessType" value="<?php echo @$CI->m_api->encryptdecryptString('encrypt',$CI->userprofile['User_E_Key'],(date('Ymd').'-'.$preauthforms)); ?>" />
     <input type="hidden" name="case_no" value="<?php echo @$CI->m_api->encryptdecryptString('encrypt',$CI->userprofile['User_E_Key'],$preauthFormData['patientinfo']['case_no']); ?>" />
      <div class="tab-content bg-white mt-20">
          <?php echo implode("",@$wStemsBody_Content); ?>
      </div>
    </form>
</div>
<?php if($hideee && @$claimsFormData['claiminfo']['remarks'] <> ''){ ?>
<div class="card mt-10" style="">
  <div id="cardbody" class="card-body p-10 bg-white font-size-16" style="">
    <h4 class="text-uppercase text-<?php echo $bborder[(int) $claimsFormData['claiminfo']['claims_status']]; ?> font-weight-bold">
      <i class="fa fa-exclamation-circle mr-10"></i>
      <?php 
        switch((int) $claimsFormData['claiminfo']['claims_status'])
        {
          case 1:
          case 2: // Re-Submitted
            echo "FOR COMPLIANCE";
          break;

          case 3: // Received
          case 4:
            echo "FOR COMPLIANCE";
          break;

          case 5:
            echo "REMARKS";
          break;
        }
      ?>
    </h4>
    <?php if(@$claimsFormData['claiminfo']['remarks'] <> ''){ ?>
    <div class="border border-<?php echo $bborder[(int) $claimsFormData['claiminfo']['claims_status']]; ?> p-10">
      <?php echo base64_decode($claimsFormData['claiminfo']['remarks']); ?>
    </div>
    <?php } ?>
  </div>
</div>
<?php } ?>

<?php if( ( is_array(@$claimsHistory) && count($claimsHistory) > 0 ) || ( is_array(@$claimsHStatus) && count($claimsHStatus) > 1 ) ){ ?>
  
  </div>
  <div class="col-lg-2">
    <?php if(count(@$claimsHistory) > 0){ ?>
    <div class="card mb-0" style="">
      <div id="cardbody" class="card-body p-10 bg-white font-size-16" style="">
        <h6 class="text-uppercase text-center font-weight-bold border-bottom "><i class="fa fa-history mr-10"></i>Z-Ben Claims Form Version</h6>
        <div>
          <div class="list-group bg-grey-100 bg-inherit mb-0 ">
            <?php 

                foreach($claimsHistory as $phR => $phC)
                { 
                  if( (int) @$currenthistoryid <> (int) $phC['rowid'])
                  {
                    if($phC['dataarchives'])
                    {
                      $rekey = $CI->myutilities->getUserProfile($phC['created_by']);
                      $datahist = json_decode($CI->m_api->encryptdecryptString('decrypt',$rekey['User_E_Key'],base64_decode($phC['dataarchives']),'MCrypt','aes-128','ecb'),true);
                      if(is_array($datahist))
                      {
                        echo '
                          <a class="list-group-item grey-600 mb-5" href="'.site_url('claims/index/view/'.bin2hex(base64_encode('history_'.((int) $phC['claim_rowid']).':'.((int) $phC['rowid'])))).'" title="Click to View Pre-Authorization Form History">
                            <i class="icon md-calendar float-left font-size-30 mt-5 text-dark" aria-hidden="true"></i> <span class="float-left font-size-12 text-dark font-weight-bold">Status : '.@$CI->myutilities->getRef_Desc(202,$datahist['claiminfo']['claims_status']).'</span><span  class="float-left font-size-12 text-dark font-weight-bold">Date : '.date("m/d/y h:i:s A",strtotime($phC['created_datetime'])).'</span>
                          </a>
                        ';
                      }
                    }
                  }
                }          
            ?> 
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php if(count($claimsHStatus) > 0){ ?>
    <div class="card mb-0 mt-10" style="">
      <div id="cardbody" class="card-body p-10 bg-white font-size-16" style="">
        <h6 class="text-uppercase text-center font-weight-bold border-bottom "><i class="fa fa-history mr-10"></i>Pre-Auth Status History</h6>
        <div class="h-500 scrollbar scrollbar-dark thin mb-0 ml-0 w-p100">
          <div class="list-group bg-yellow-100 bg-inherit mb-0">
            <?php 
              
                foreach($claimsHStatus as $pshR => $pshC)
                { 
              
                  $sicon = '';
                  switch($pshC['claims_status'])
                  {
                    case 1:
                      $sicon = 'fa-send-o text-dark';
                    break;

                    case 2:
                      $sicon = 'fa-send text-info';
                    break;

                    case 3:
                      $sicon = 'fa-file-archive-o text-primary';
                    break;

                    case 4:
                      $sicon = 'fa-exclamation-circle';
                    break;

                    case 5:
                      $sicon = 'fa-thumbs-down text-danger';
                    break;

                    case 6:
                      $sicon = 'fa-thumbs-up text-success';
                    break;
                  }

                  echo '
                    <a class="list-group-item yellow-600 mb-5" href="javascript:void(0)">
                      <i class="fa '.$sicon.' fa-fw float-left ml--5 mt--5  font-size-30 mr-5" aria-hidden="true"></i> <span class="float-left font-size-12 text-dark font-weight-bold">Status : '.@$CI->myutilities->getRef_Desc(202,$pshC['claims_status']).( (in_array( (int) $pshC['claims_status'],[3,4,5,6])) ? ' [BAS]' : '').'</span><span  class="float-left font-size-12 text-dark font-weight-bold">Date : '.date("m/d/y h:i:s A",strtotime($pshC['claims_status_datetime'])).'</span>
                    </a>
                  ';
                }
              
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
<?php } ?>

<?php if( (@$ViewMode <> true || @$ForCompliance == true ) && $CI->userclassification == 1 ){ ?>
<script nonce="<?php echo $CI->nonceV; ?>" src="<?php echo getjsPath().'views/pages/claims/forms/index.js'; ?>"></script>
<?php } ?>
<?php if(@$ViewMode == true && $CI->userclassification > 1){ ?>
<script nonce="<?php echo $CI->nonceV; ?>" src="<?php echo getjsPath().'views/pages/claims/forms/index_vaction.js'; ?>"></script>
<?php } ?>
<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

    // $("[forminputgroup][title]").tooltip({'placement':'top','fallbackPlacement':'flip'});

    let hasBeenReset = false;
    let extbtn = '';
    function WizardapplyColors(colorObj) {
      colorObj = JSON.parse(window.atob(colorObj));
      $.each(colorObj, function(key, val) {
          document.documentElement.style.setProperty(key, val);
      });
    }

    $("#claimFormWizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
        $(".sw-btn-prev").removeClass('disabled').prop('disabled', false).show();
        $(".sw-btn-next").removeClass('disabled').prop('disabled', false).show();
        $("#btnFinish").addClass('disabled').prop('disabled', true).hide();
        if(stepPosition === 'first') {
            $(".sw-btn-prev").addClass('disabled').prop('disabled', true).hide();
        } else if(stepPosition === 'last') {
            $(".sw-btn-next").addClass('disabled').prop('disabled', true).hide();
            <?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>
            $("#btnFinish").removeClass('disabled').prop('disabled', false).show();
            <?php } ?>
        } else {
            $(".sw-btn-prev").removeClass('disabled').prop('disabled', false).show();
            $(".sw-btn-next").removeClass('disabled').prop('disabled', false).show();
        }

        // Get step info from Smart Wizard
        let stepInfo = $('#claimFormWizard').smartWizard("getStepInfo");
        $("#sw-current-step").text(stepInfo.currentStep + 1);
        $("#sw-total-step").text(stepInfo.totalSteps);
        $("html, body").animate({scrollTop: 0}, 100);
    });

    $("#claimFormWizard").on("initialized", function(e) {
        
    });

    $("#claimFormWizard").on("loaded", function(e) {
        WizardapplyColors('eyItLXN3LWJvcmRlci1jb2xvciI6IiNlZWVlZWUiLCItLXN3LXRvb2xiYXItYnRuLWNvbG9yIjoiI2ZmZmZmZiIsIi0tc3ctdG9vbGJhci1idG4tYmFja2dyb3VuZC1jb2xvciI6IiMwMDg5MzEiLCItLXN3LWFuY2hvci1kZWZhdWx0LXByaW1hcnktY29sb3IiOiIjZjhmOWZhIiwiLS1zdy1hbmNob3ItZGVmYXVsdC1zZWNvbmRhcnktY29sb3IiOiIjYjBiMGIxIiwiLS1zdy1hbmNob3ItYWN0aXZlLXByaW1hcnktY29sb3IiOiIjNzhjMDQzIiwiLS1zdy1hbmNob3ItYWN0aXZlLXNlY29uZGFyeS1jb2xvciI6IiNmZmZmZmYiLCItLXN3LWFuY2hvci1kb25lLXByaW1hcnktY29sb3IiOiIjNTg4ODM1IiwiLS1zdy1hbmNob3ItZG9uZS1zZWNvbmRhcnktY29sb3IiOiIjYzJjMmMyIiwiLS1zdy1hbmNob3ItZGlzYWJsZWQtcHJpbWFyeS1jb2xvciI6IiNmOGY5ZmEiLCItLXN3LWFuY2hvci1kaXNhYmxlZC1zZWNvbmRhcnktY29sb3IiOiIjZGJlMGU1IiwiLS1zdy1hbmNob3ItZXJyb3ItcHJpbWFyeS1jb2xvciI6IiNkYzM1NDUiLCItLXN3LWFuY2hvci1lcnJvci1zZWNvbmRhcnktY29sb3IiOiIjZmZmZmZmIiwiLS1zdy1hbmNob3Itd2FybmluZy1wcmltYXJ5LWNvbG9yIjoiI2ZmYzEwNyIsIi0tc3ctYW5jaG9yLXdhcm5pbmctc2Vjb25kYXJ5LWNvbG9yIjoiI2ZmZmZmZiIsIi0tc3ctcHJvZ3Jlc3MtY29sb3IiOiIjNzhjMDQzIiwiLS1zdy1wcm9ncmVzcy1iYWNrZ3JvdW5kLWNvbG9yIjoiI2Y4ZjlmYSIsIi0tc3ctbG9hZGVyLWNvbG9yIjoiIzc4YzA0MyIsIi0tc3ctbG9hZGVyLWJhY2tncm91bmQtY29sb3IiOiIjZjhmOWZhIiwiLS1zdy1sb2FkZXItYmFja2dyb3VuZC13cmFwcGVyLWNvbG9yIjoicmdiYSgyNTUsIDI1NSwgMjU1LCAwLjcpIn0=');

        if (!hasBeenReset) {
          hasBeenReset = true;
          $(this).smartWizard('reset');
        }

        if( $("i[id='sw-btn-prev']").length == 0)
        {
          $(".sw-btn-prev").addClass("w-80").prepend('<i class="fa fa-fw fa-angle-left mr-5" id="sw-btn-prev"></i>');
          $(".sw-btn-next").addClass("w-80").prepend('<i class="fa fa-fw fa-angle-right mr-5" id="sw-btn-next"></i>');
        }

        <?php if(@$ViewMode == true){ ?>
        $('#claimFormWizard').smartWizard("setState", [0,1,2,3,4], 'done');
        <?php } ?>
        
    });

    $("#claimFormWizard").on("leaveStep", function(e, anchorObject, currentStepIdx, nextStepIdx, stepDirection) { 
        var paneID = anchorObject.attr('href').replace('#','');
        var reqerror = 0;
        var errordata = {};
        
        <?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>
        if (stepDirection == 'forward') {
            $("div[id='"+paneID+"'] input[forminputgroup='claimFormData'], div[id='"+paneID+"'] select[forminputgroup='claimFormData']").each(function(){
              var divparent = $(this).closest(".form-group.form-material");
              var eleDisabled = ($(this).hasAttr('disabled')) ? true : false;
              var mn = minlth = ($(this).hasAttr('minlength')) ? parseInt($(this).attr('minlength')) : '';
              var mx = maxlth = ($(this).hasAttr('maxlength')) ? parseInt($(this).attr('maxlength')) : '';

              if(minlth && maxlth)
              {
                minlth = (mn > mx) ? mx : mn;
                maxlth = (mn > mx) ? mn : mx;
              }

              if( $(this).hasAttr('required') && !$(this).val() )
              {
                reqerror++;
                if(!eleDisabled)
                {
                  $(divparent).removeClass('has-success').addClass('has-danger');
                  errordata[$(this).attr('id')] = ($(this).prop('title') && $(this).attr('title') !== '' ) ? $(this).attr('title') : $("label[class='form-control-label'][for='"+$(this).attr('id')+"']").text();
                }
              }
              else
              {
                var vv = ($(this).val()) ? ($(this).val()).length : 0;
                var divhint = $(divparent).find("div.hint");

                if(!eleDisabled)
                {
                  $(divparent).removeClass('has-danger').addClass('has-success');
                }
               
                $(divhint).hide().find("span").text('').hide();

                if( minlth || maxlth )
                {
                  if( parseInt(vv) < parseInt(minlth) || parseInt(vv) > parseInt(maxlth))
                  {
                    reqerror++;
                    $(divparent).removeClass('has-success').addClass('has-danger');
                    $(divhint).show().find("span").text('Invalid minimum('+minlth+') or maximum('+maxlth+') character length').show();
                  }
                }
              }

            });

            if(reqerror > 0)
            {
              $('#claimFormWizard').smartWizard("setState", [currentStepIdx], 'error');
              popFormDataError(errordata,$("div[id='"+paneID+"']").attr('title'));
              // return false;
            }

            $('#claimFormWizard').smartWizard("unsetState", [currentStepIdx], 'error');
            switch(paneID)
            {
              case 'preauth_personalinformation':
                
                if( $("input[id='patientinfo[fulfilled_selection_criteria]'][forminputgroup='claimFormData']").val() == 'N' )
                { 
                
                }

              break;
            }

        }
        <?php } ?>
        
    });

    <?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>
    extbtn = `<button type="button" id="btnFinish" name="btnWizard" class="btn btn-warning text-dark w-100" onclick="WizardonFinish(<?php echo (@$ForCompliance) ? 'true' : ''; ?>)"><i class="fa fa-fw fa-file-text-o mr-10"></i>Submit</button>
                      <button type="button" id="btnCancel" name="btnWizard" class="btn btn-secondary w-100" onclick="WizardonCancel()"><i class="fa fa-fw fa-remove mr-10"></i>Cancel</button>`;
    <?php } ?>

    $('#claimFormWizard').smartWizard({
        selected: 0,
        autoAdjustHeight: false,
        justified: true,
        theme: 'basic',
        transition: {
          animation:'fade' 
        },
        toolbar: {
          showNextButton: true, // show/hide a Next button
          showPreviousButton: true, // show/hide a Previous button
          position: 'bottom', // none/ top/ both bottom
          extraHtml: extbtn,
        },
        anchor: {
            enableNavigation: true, // Enable/Disable anchor navigation 
            enableNavigationAlways: false, // Activates all anchors clickable always
            enableDoneState: 'steps', // Add done state on visited steps
            markPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
            unDoneOnBackNavigation: false, // While navigate back, done state will be cleared
            enableDoneStateNavigation: true // Enable/Disable the done state navigation
        },
        disabledSteps: [], // Array Steps disabled
        errorSteps: [], // Highlight step with errors
        hiddenSteps: [], // Hidden steps
    });
    
    $("[name='btn_claimaction']").click(function(){
        switch($(this).attr('id'))
        { 
          case 'backClaim':
            window.location.href=site_url+'claims';
          break;

          case 'printClaim':
          break;
 
          <?php 
          if( $CI->userclassification > 1)
          {
            switch((int) $claimsFormData['claiminfo']['claims_status'])
            {
              case 1: // Submitted
              case 2:
               echo '
                case "receivedClaim":
                  receivedClaim(\''.$CI->myutilities->getRef_Desc(200,$preauthFormData['patientinfo']['preauth_type']).'\',\''.@$claimsFormData['claiminfo']['case_no'].'\',\''.$CI->m_api->encryptdecryptString('encrypt',$CI->system_settings['PasswordHashing'],@$claimsFormData['claiminfo']['case_no'],'MCrypt','aes-128','ecb').'\');
                break;
               ';
              break;

              case 3: // Received
               echo '
                case "forcomplianceClaim":
                case "approveClaim":
                case "disapproveClaim":
                  statusClaim(\''.($CI->myutilities->getRef_Desc(200,$preauthFormData['patientinfo']['preauth_type'])).'\',\''.@$claimsFormData['claiminfo']['case_no'].'\',\''.$CI->m_api->encryptdecryptString('encrypt',$CI->system_settings['PasswordHashing'],@$claimsFormData['claiminfo']['case_no'],'MCrypt','aes-128','ecb').'\',$(this).attr(\'astatus\'));
                break;
               ';
              break;
            }
          }
          


          ?>

        }
    });

    <?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>
    $("[forminputgroup='claimFormData']").each(function(){
      switch( $(this).prop('nodeName') )
      {
        case 'INPUT':
          switch($(this).attr('type'))
          {
            case 'text':
              $(this).blur();
            break;

            case 'checkbox':

              if( $(this).prop('checked') )
              {
                $(this).click().click();
              }

            break;

            case 'radio':
              if( $(this).prop('checked') )
              {
                $(this).attr('id');
                $(this).click();
              }
            break;
          }
          
        break;
        case 'SELECT':
          $(this).change();
        break;
      }
    });

    $("input[data-plugin='formatter,datepicker'][forminputgroup='claimFormData']").blur(function(){
      if($(this).val() && moment(new Date($(this).val())).format('MM/DD/YYYY') == 'Invalid date')
      {
        $(this).val('');
      }
    });
  <?php } ?>


});



</script>
