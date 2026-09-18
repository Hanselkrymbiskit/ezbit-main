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
    'targetcontent' => 'preauth_checklist',
    'title'         => 'Pre-Authorization Checklist',
    'description'   => '',
  ],

  [
    'targetcontent' => 'preauth_request',
    'title'         => 'Pre-Authorization Request',
    'description'   => '',
  ],

  [
    'targetcontent' => 'preauth_empowerment',
    'title'         => 'Member Empowerment',
    'description'   => '',
  ],

  [
    'targetcontent' => 'preauth_submit',
    'title'         => 'Document Attachment',
    'description'   => '',
  ],
];

if( @$ViewMode == true && $SelectionCriteria && @$preauthFormData['patientinfo']['fulfilled_selection_criteria'] == 'N')
{
  unset($StepsMenu[1]);
  unset($StepsMenu[2]);
}

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
  $phpFilePath = 'views/pages/preauthorization/form/preauthforms/'.$sm_prop['targetcontent'];
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
$CI->data['bborder'] = $bborder;
?>

<?php $CI->load->view('pages/preauthorization/form/preauthforms/preauth_header', $CI->data); ?>
<?php $CI->load->view('pages/preauthorization/form/preauthforms/preauth_info', $CI->data); ?>
<div id="preAuthFormWizard" class="bg-white">
    <div class="progress">
      <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <ul class="nav nav-progress bg-white">
        <?php echo implode("",@$wStepsMenu_Content); ?>
    </ul>
    <form id="preauthFormData" class="" novalidate="novalidate" style="">
     <?php if( $CI->config->item('csrf_protection') ){ ?>
     <input type="hidden" name="<?php echo @$CI->security->get_csrf_token_name(); ?>" value="<?php echo @$CI->security->get_csrf_hash(); ?>" />
     <?php } ?>
     <input type="hidden" name="illnessType" value="<?php echo @$CI->m_api->encryptdecryptString('encrypt',$CI->userprofile['User_E_Key'],(date('Ymd').'-'.$preauthforms)); ?>" />
     <?php if(@$ForCompliance){ ?>
     <input type="hidden" name="case_no" value="<?php echo @$CI->m_api->encryptdecryptString('encrypt',$CI->userprofile['User_E_Key'],$preauthFormData['patientinfo']['case_no']); ?>" />
     <?php } ?>
      <div class="tab-content bg-white mt-20">
          <?php echo implode("",@$wStemsBody_Content); ?>
      </div>
    </form>
</div>
<?php if( (@$ViewMode == true && in_array((int) $preauthFormData['patientinfo']['preauth_status'], [2,3,4,5]) && @$preauthFormData['patientinfo']['remarks'] <> '') || @$ForCompliance == true ){ ?>
<div class="card mt-10" style="">
  <div id="cardbody" class="card-body p-10 bg-white font-size-16" style="">
    <h4 class="text-uppercase text-<?php echo $bborder[(int) $preauthFormData['patientinfo']['preauth_status']]; ?> font-weight-bold">
      <i class="fa fa-exclamation-circle mr-10"></i>
      <?php echo ( (int) $preauthFormData['patientinfo']['preauth_status'] <> 5 ) ? 'FOR COMPLIANCE' : 'REMARKS'; ?>
    </h4>
    <div class="border border-<?php echo $bborder[(int) $preauthFormData['patientinfo']['preauth_status']]; ?> p-10">
      <?php echo base64_decode($preauthFormData['patientinfo']['remarks']); ?>
    </div>
  </div>
</div>
<?php } ?>
<?php $CI->load->view('pages/preauthorization/form/preauthforms/preauth_rside', $CI->data); ?>

<?php if( (@$ViewMode <> true || @$ForCompliance == true ) && in_array($CI->userclassification,[1,6,7]) ){ ?>
<script nonce="<?php echo $CI->nonceV; ?>" src="<?php echo getjsPath().'views/pages/preauthorization/forms/index.js'; ?>"></script>
<?php } ?>
<?php if( @$ViewMode == true && !in_array($CI->userclassification,[1,6,7]) ){ ?>
<script nonce="<?php echo $CI->nonceV; ?>" src="<?php echo getjsPath().'views/pages/preauthorization/forms/index_vaction.js'; ?>"></script>
<?php } ?>
<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

    let hasBeenReset = false;
    let extbtn = '';
    function WizardapplyColors(colorObj) {
      colorObj = JSON.parse(window.atob(colorObj));
      $.each(colorObj, function(key, val) {
          document.documentElement.style.setProperty(key, val);
      });
    }

    $("#preAuthFormWizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
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
        let stepInfo = $('#preAuthFormWizard').smartWizard("getStepInfo");
        $("#sw-current-step").text(stepInfo.currentStep + 1);
        $("#sw-total-step").text(stepInfo.totalSteps);
        $("html, body").animate({scrollTop: 0}, 100);
    });

    $("#preAuthFormWizard").on("initialized", function(e) {
        
    });

    $("#preAuthFormWizard").on("loaded", function(e) {
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
        $('#preAuthFormWizard').smartWizard("setState", [0,1,2,3,4], 'done');
        <?php } ?>
        
    });

    $("#preAuthFormWizard").on("leaveStep", function(e, anchorObject, currentStepIdx, nextStepIdx, stepDirection) { 
        var paneID = anchorObject.attr('href').replace('#','');
        var reqerror = 0;
        var errordata = {};
        
        <?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>
        if (stepDirection == 'forward') {
            $("div[id='"+paneID+"'] input[forminputgroup='preauthFormData'], div[id='"+paneID+"'] select[forminputgroup='preauthFormData']").each(function(){
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
              $('#preAuthFormWizard').smartWizard("setState", [currentStepIdx], 'error');
              popFormDataError(errordata,$("div[id='"+paneID+"']").attr('title'));
              // return false;
            }

            $('#preAuthFormWizard').smartWizard("unsetState", [currentStepIdx], 'error');
            switch(paneID)
            {
              case 'preauth_personalinformation':
                
                if( $("input[id='patientinfo[fulfilled_selection_criteria]'][forminputgroup='preauthFormData']").val() == 'N' )
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

    $('#preAuthFormWizard').smartWizard({
        selected: 0,
        autoAdjustHeight: false,
        justified: true,
        theme: 'basic',
        transition: {
          animation:'fade' 
        },
        toolbar: {
          showNextButton: true,
          showPreviousButton: true,
          position: 'bottom', 
          extraHtml: extbtn,
        },
        anchor: {
            enableNavigation: true, 
            enableNavigationAlways: false,
            enableDoneState: 'steps',
            markPreviousStepsAsDone: true, 
            unDoneOnBackNavigation: false, 
            enableDoneStateNavigation: true 
        },
        disabledSteps: [],
        errorSteps: [],
        hiddenSteps: [],
    });
    
    $("[name='btn_preauthaction']").click(function(){
        switch($(this).attr('id'))
        { 
          case 'backPreAuth':
            window.location.href=site_url+'preauthorization';
          break;

          case 'printPreAuth':
          break;
 
          <?php 
          if( @$ViewMode == true && !in_array($CI->userclassification,[1,6,7]) )
          {
            switch((int) $preauthFormData['patientinfo']['preauth_status'])
            {
              case 1: // Submitted
              case 2:
               echo '
                case "receivedPreAuth":
                  receivedPreAuth(\''.$CI->myutilities->getRef_Desc(200,$preauthFormData['patientinfo']['preauth_type']).'\',\''.@$preauthFormData['patientinfo']['case_no'].'\',\''.$CI->m_api->encryptdecryptString('encrypt',$CI->system_settings['PasswordHashing'],@$preauthFormData['patientinfo']['case_no'],'MCrypt','aes-128','ecb').'\');
                break;
               ';
              break;

              case 3: // Received
               echo '
                case "forcompliancePreAuth":
                case "approvePreAuth":
                case "disapprovePreAuth":


                  statusPreAuth(\''.($CI->myutilities->getRef_Desc(200,$preauthFormData['patientinfo']['preauth_type'])).'\',\''.@$preauthFormData['patientinfo']['case_no'].'\',\''.$CI->m_api->encryptdecryptString('encrypt',$CI->system_settings['PasswordHashing'],@$preauthFormData['patientinfo']['case_no'],'MCrypt','aes-128','ecb').'\',$(this).attr(\'astatus\'));
                break;
               ';
              break;
            }
          }
          ?>

        }
    });

    <?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>

    <?php if($CI->system_settings['eSignature']){ ?>
    $("label[for*='[crtby_']").each(function(){
      if( !$(this).attr('for').includes("_accreno") )
      {
        $(this).addClass('w-p100').append('<i grp="signature" target="'+$(this).attr('for')+'" class="float-right fa fa-fw fa-pencil-square-o" title="Click to write Signature"></i>');
      }
    });

    $("i[grp='signature']").click(function(){
      var imgID = $(this).attr('target').replace(']','_signature]');
      var rPreAuth = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success text-white mr-5 w-p45',
            cancelButton: 'btn btn-success text-white  w-p45',
            input: 'form-control'
          },
          buttonsStyling: false,
          willOpen: (fnRun) => {
            $(".swal2-container").css('z-index',$.topZIndex());
            
            $("[id='"+imgID+"']").jqSignature({
              'width'       : '400',
              'height'      : '150',
              'background'  : '#0004B100',
              'linecolor'   :'#0004B1DD'
            });
          }
      }).fire({
          title: 'Signature of '+ ($("[id='"+$(this).attr('target')+"']").attr('title')).replace('Certified correct by ',''),
          // icon:'question',
          html: '<div id="'+imgID+'" class="js-signature"></div>',
          showCancelButton: true,
          confirmButtonText: 'ATTACH',
          cancelButtonText:'CANCEL',
          showLoaderOnConfirm: true,
          reverseButtons: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
      }).then((result) => {
          if(result)
          {
            
            if($("img[id='"+imgID+"']").length == 0)
            {
              $(this).closest("label").after('<div class="text-center"><img class="w-200" id="'+imgID+'" src="'+$("[id='"+imgID+"']").jqSignature('getDataURL')+'"></div>');
            }
            else
            {
              $("img[id='"+imgID+"']").attr('src',$("[id='"+imgID+"']").jqSignature('getDataURL'));
            }
          }
      });
    });
    <?php } ?>
    
    $("[forminputgroup='preauthFormData']").each(function(){
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

    $("input[data-plugin='formatter,datepicker'][forminputgroup='preauthFormData']").blur(function(){
      if($(this).val() && moment(new Date($(this).val())).format('MM/DD/YYYY') == 'Invalid date')
      {
        $(this).val('');
      }
    });
  <?php } ?>
});



</script>
