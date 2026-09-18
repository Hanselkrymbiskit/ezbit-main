<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


foreach($registrationData as $rr => $vv )
{
    $registrationData[$rr] = $CI->myutilities->formatValueDateTime($rr,$vv);
}

$displayValue = $registrationData;

$getReference = array(
  'Suffixname'          => 24,
  'Sex'                 => 23,
  'user_classification' => 4,
  'Region'              => 101,
  'Province'            => 102,
  'City'                => 103,
  'register_status'     => 27,
  'action_by'           => 1,
);

foreach($getReference as $rkey => $refid )
{
  $displayValue[$rkey] = (is_array($refid) && @$registrationData[$rkey] <> '') ? $refid[@$registrationData[$rkey]]  : $CI->myutilities->getRef_Desc($refid,@$registrationData[$rkey]);
}

$ProfileArray = array(
  'Lastname' => array(
                  'Caption' => 'Last Name'
                ),
  'Firstname' => array(
                  'Caption' => 'First Name'
                ),
  'Middlename' => array(
                  'Caption' => 'Middle Name'
                ),
  'Suffixname' => array(
                  'Caption' => 'Suffix Name'
                ),
  'DateofBirth' => array(
                  'Caption' => 'Date of Birth'
                ),
  'Sex' => array(
                  'Caption' => 'Sex'
                ),
  'Mobile_no' => array(
                  'Caption' => 'Mobile No'
                ),
);

$ClassificationArray = array(
    '0' => array(
      'FieldKey'=> 'user_classification',
      'Caption' => 'CLASSIFICATION TYPE'
    ),
    '1' => array(
      'FieldKey'=> 'HealthFacilityCode',
      'Caption' => 'FACILITY CODE'
    ),
    '2' => array(
      'FieldKey'=> 'FacilityName',
      'Caption' => 'FACILITY NAME'
    ),
    '3' => array(
      'FieldKey'=> 'Address',
      'Caption' => 'ADDRESS'
    ),
    '4' => array(
      'FieldKey'=> 'Region',
      'Caption' => 'REGION'
    ),
    '5' => array(
      'FieldKey'=> 'Province',
      'Caption' => 'PROVINCE'
    ),
    '6' => array(
      'FieldKey'=> 'City',
      'Caption' => 'CITY / MUNICIPALITY'
    ),
    '7' => array(
      'FieldKey'=> 'FacilityContactNo',
      'Caption' => 'CONTACT NO'
    ),
);

$classificationShow = array();
switch( (int) $registrationData['user_classification'] )
{
  case 1:
    $classificationShow = array(0,1,2,3,4,5,6,7);
  break;
  default:
  $classificationShow = array(0);
}

$ActionMode = ($registrationData['register_status'] == 2 ) ? 'Approved' : 'Disapproved';

?>

<div class="row">

  <div class="col-sm-12 col-lg-5">
    
    <div class="card" data-mh="usersGrouping">
      <div class="card-block">
        <h4 class="card-title">Registration ID : <?php echo @$registrationData['registrationID']; ?></h4>
        <h4 class="card-title">Account Profile</h4>
        <div>
          <?php foreach($ProfileArray as $pk => $pset ) { ?>
          <?php  
            $toDEncrypt = ['Lastname','Firstname','Middlename'];
            if( in_array($pk, $toDEncrypt) )
            {
              $displayValue[$pk] = $CI->m_api->encryptdecryptString('decrypt',$CI->system_settings['PasswordHashing'],$displayValue[$pk],'MCrypt','aes-128','ecb');
              $displayValue[$pk] = ($displayValue[$pk] <> '') ? $displayValue[$pk] : $CI->m_api->encryptdecryptString('decrypt',$CI->system_settings['PasswordHashing'],$displayValue[$pk],'MCrypt','aes-128','cbc');
            }

          ?>
          <div class="form-group form-material row">
            <label class="col-md-3 col-form-label"><?php echo @$pset['Caption'] ?>: </label>
            <div class="col-md-9">
              <input type="text" class="form-control text-uppercase" value="<?php echo @$displayValue[$pk]; ?>" autocomplete="off" disabled="disabled">
            </div>
          </div>
          <?php } ?>
        </div>
        <div>
          <?php
              if( @$registrationData['accountuserid'] <> '')
              {
                $rUserList = explode(",",$registrationData['accountuserid']);
                if( is_array($rUserList) )
                {
                    $uRowsHTML = '';
                    foreach($rUserList as $dUserID)
                    {
                        $ttuserinfo = $CI->myutilities->getRegistrationInfo($dUserID);
                        $uRowsHTML .= '
                        <tr>
                              <td class="text-center">'.$ttuserinfo['username'].'</td>
                              <td class="text-center">'.$ttuserinfo['emailaddress'].'</td>
                              <td class="text-center">'.$CI->myutilities->getRef_Desc(4,$ttuserinfo['user_classification']).'</td>
                          </tr>
                        ';
                    }

                    echo '
                    <hr><h4>Detected Other Account Profile</h4>
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">User Name</th>
                                <th class="text-center">Email Address</th>
                                <th class="text-center">Classification</th>
                            </tr>
                        </thead>
                        <tbody>'.@$uRowsHTML.'</tbody></table>
                    ';
                    
                }
              }
          ?>
        </div>
      </div>  
    </div>

  </div>

  <div class="col-sm-12 col-lg-7" >
    <div data-mh="usersGrouping">
      <div class="card mb-10">
        <div class="card-block">
          <h5 class="card-title mb-0">Registered Date/Time: <span class="text-info"><?php echo @$displayValue['register_datetime']; ?></span><span class="float-right">Status: <span class="text-info"><?php echo @$displayValue['register_status']; ?></span></span></h5>  
        </div>  
      </div>

      <?php if((int) $registrationData['register_status'] <> 1 ){ ?>
      <div class="card mb-10">
        <div class="card-block">
          <h5 class="card-title mb-0"><?php echo @$ActionMode; ?> Date/Time: <span class="text-info"><?php echo @$displayValue['action_datetime']; ?></span><span class="float-right"><?php echo @$ActionMode; ?> By: <span class="text-info"><?php echo @$displayValue['action_by']; ?></span></span></h5>  
        </div>  
      </div>
      <?php } ?>

      <div class="card mb-10">
        <div class="card-block">
          <div class="form-inline">
             <div class="ml-5 w-p50 form-group form-material row">
              <label class="col-form-label">Email Address: </label>
              <div class="w-p70 ml-5" style="">
                <input type="text" class="form-control w-p100" value="<?php echo @$displayValue['emailaddress']; ?>" autocomplete="off" disabled="disabled">
              </div>
            </div>
            <div class="ml-10 w-p45 form-group form-material row">
              <label class="ol-form-label">User Name: </label>
              <div class="w-p75 ml-5">
                <input type="text" class="form-control w-p100" value="<?php echo @$displayValue['username']; ?>" autocomplete="off" disabled="disabled">
              </div>
            </div>
          </div>
        </div>  
      </div>

      <div class="card mb-10">
        <div class="card-block">
          <div>
            <?php foreach($classificationShow as $classfield ) {   $cset = $ClassificationArray[$classfield]; ?>
            <div class="form-group form-material row">
              <label class="col-md-4 col-form-label"><?php echo @$cset['Caption'] ?>: </label>
              <div class="col-md-8">
                <input type="text" class="form-control text-uppercase" value="<?php echo @$displayValue[$cset['FieldKey']]; ?>" autocomplete="off" disabled="disabled">
              </div>
            </div>
            <?php } ?>
          </div>
        </div>  
      </div>

      <?php if( $registrationData['proofdocument'] <> ''){ ?>
      <div class="card mb-10">
        <div class="card-block">
          <div>
            <span>File Attachment</span>
            <div>
                <?php 
                  $documentArray = explode(",",$registrationData['proofdocument']); 

                  foreach($documentArray as $fileinfo)
                  {
                    $fileinfo = explode("|",base64_decode($fileinfo));
                    $encrypFileName = $fileinfo[0];
                    $origFileName = $fileinfo[1];

                    $getFileType = explode(".",$origFileName);
                  
                    $getFileType = strtolower($getFileType[count($getFileType) -1]);

                    $fileTypes = [
                     'jpeg' => 'image/jpeg',
                     'pjpeg'=> 'image/pjpeg',
                     'png'  => 'image/png',
                     'gif'  => 'image/gif',
                     'jpg'  => 'image/jpg',
                     'pdf'  => 'application/pdf'
                   ];

                    $FilePaths = FCPATH.'/assets/attachment/registration/'.$registrationData['registrationID'].'/'.$encrypFileName;
     
                      // log_message("error",$BlobContent);
                    $fla = [
                        'FileName' => $origFileName,
                        'FileType' => $fileTypes[$getFileType],
                        'FileData' => '',
                        'FilePath' => assetPath().'attachment/registration/'.$registrationData['registrationID'].'/'.$encrypFileName
                    ];


                    $filelinkAction = 'onclick="javascript:ObjectFileViewer(\''.base64_encode(json_encode($fla)).'\')" ';

                    echo '
                    <input id="FileHolderx_'.str_replace($getFileType,'',$encrypFileName).'" type="text" class="form-control float-left" placeholder="'.$origFileName.'" readonly="" style="background:none;border: 0px !important;width: 96%;margin-top: 5px;" disabled="disabled" ><i id="iFileHolderx_'.str_replace($getFileType,'',$encrypFileName).'" class="fa fa-search float-right" style="cursor:pointer;margin-top: 13px;" '.@$filelinkAction.'></i>
                    ';
                  }

                ?>
            </div>
          </div>
        </div>  
      </div>
      <?php } ?>

      <div class="card ">
        <div class="card-block">
            <div class="form-group form-material">
              <label class="col-form-label">Remarks:</label>
              <div class="col-md-12">
                <textarea class="form-control" autocomplete="off" rows="2" disabled="disabled"><?php echo @$displayValue['remarks']; ?></textarea>
              </div>
            </div>
        </div>  
      </div>
    </div>
  </div>
  
 
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

  $("button[name*='btnRegisterAction']").click(function(){
    
    var remarkshtml='<div class="mt-10 border border-warning p-5 text-left form-group form-material" data-plugin="formMaterial"><label class="form-control-label" for="textarea">Remarks</label><textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea></div>';

    switch( $(this).attr('id') )
    {
      <?php if( (int) $registrationData['register_status'] == 1){ ?>
      case 'btn_Approved':
      case 'btn_Disapproved':
          var regstatus = ( $(this).attr('id') == 'btn_Approved' ? 2 : 3);
          Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-warning mr-10 w-100',
              cancelButton: 'btn btn-secondary  w-100',
            },
            buttonsStyling: false,
            willOpen: (fnRun) => {
              $(".swal2-container").css('z-index',$.topZIndex());
            }
          }).fire({
            title:  ( $(this).attr('id') == 'btn_Approved' ? 'Approved' : 'Disapproved')+" Registration?",
            html: remarkshtml,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: "No",
            showConfirmButton:true,
            confirmButtonText: 'Yes',
            reverseButtons: false,
          }).then((result) => {
            if (result.value) 
            {
              var registerActionData = new FormData();
              registerActionData.append('register_status',regstatus);
              registerActionData.append('registrationID',"<?php echo base64_encode(base64_encode($registrationData['registrationID'])); ?>");
              registerActionData.append('remarks',$("textarea[id='remarks']").val());

              var registeractionResult = function(rapp,rapr){
        
                    Swal.mixin({
                      customClass: {
                        confirmButton: 'btn btn-'+(rapr.Status == 1 ? 'success' : 'danger'),
                      },
                      buttonsStyling: false,
                      willOpen: (fnRun) => {
                        $(".swal2-container").css('z-index',$.topZIndex());
                      }
                    }).fire({
                      title: (rapr.Status == 1 ? ( (rapr.rstat == 2 ) ? 'Registration Approved' : 'Registration Disapproved') : 'Registration Action'),
                      text: rapr.Message,
                      icon: (rapr.Status == 1 ? 'success' : 'error'),
                      confirmButtonText: 'Close',
                    }).then((result) => {
                        if (result.value) 
                        {
                          window.location.reload();
                        }
                    });
              };

              submitFormData('json','',site_url+'administrator/registered/submitregisteredaction',registerActionData,registeractionResult,true);
            }
          });

      break;
      <?php } ?>

      <?php if( (int) $CI->userclassification == 02 || (int) $CI->usertype <= 3){ ?>
        <?php if($registrationData['register_status'] == 2 && (int) $registrationData['user_classification'] == 02){ ?>
      case 'btn_EnableRegistrationAcces':
      case 'btn_DisableRegistrationAcces':
          var registrationpageaccess = ( $(this).attr('id') == 'btn_EnableRegistrationAcces' ? 'Y' : 'N');
          Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-warning mr-10 w-100',
              cancelButton: 'btn btn-secondary  w-100',
            },
            buttonsStyling: false,
            willOpen: (fnRun) => {
              $(".swal2-container").css('z-index',$.topZIndex());
            }
          }).fire({
            title:  ( $(this).attr('id') == 'btn_EnableRegistrationAcces' ? 'Enable' : 'Disable')+" Registration Access?",
            html: remarkshtml,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: "No",
            showConfirmButton:true,
            confirmButtonText: 'Yes',
            reverseButtons: false,
          }).then((result) => {
            if (result.value) 
            {
              var registerActionData = new FormData();
              registerActionData.append('registrationpageaccess',registrationpageaccess);
              registerActionData.append('registrationID',"<?php echo base64_encode(base64_encode($registrationData['registrationID'])); ?>");

              var registeractionResult = function(rapp,rapr){
        
                    Swal.mixin({
                      customClass: {
                        confirmButton: 'btn btn-'+(rapr.Status == 1 ? 'success' : 'danger'),
                      },
                      buttonsStyling: false,
                      willOpen: (fnRun) => {
                        $(".swal2-container").css('z-index',$.topZIndex());
                      }
                    }).fire({
                      title: (rapr.Status == 1 ? ( (rapr.rstat == 2 ) ? 'Registration Access Enabled' : 'Registration Access Disabled') : 'Registration Action'),
                      text: rapr.Message,
                      icon: (rapr.Status == 1 ? 'success' : 'error'),
                      confirmButtonText: 'Close',
                    }).then((result) => {
                        if (result.value) 
                        {
                          window.location.reload();
                        }
                    });
              };

              submitFormData('json','',site_url+'administrator/registered/submitregistrationaccess',registerActionData,registeractionResult,true);
            }
          });

      break;
         <?php } ?>
      <?php } ?>
      case 'btn_Cancel':
        window.location.href = site_url+'administrator/registered';
      break;
    }
  });

});
</script>

