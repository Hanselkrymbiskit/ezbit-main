<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$zbenServices = $CI->m_general->getFacilityZBenServices($coProfile['HealthFacilityCode']);
$myzbenServices = $coProfile['primarycondition_access'];
$myzbenServices = ($myzbenServices <> '') ? explode(",",$myzbenServices) : [];
?>

<div class="row mt--20">
    
  <div class="col-md-12 col-lg-12">
    <div class="card mb-5">
        <div class="card-header card-header-transparent card-header-bordered font-weight-bold">
          <?php echo str_replace(['Health Facility -'],'',$CI->myutilities->getRef_Desc(4,$coProfile['user_classification'])); ?> Profile
        </div>
        <div class="card-block p-5">
          <table class="table table-sm mb-0">
            <tbody>
              <tr>
                <td colspan="2" class="border-top-0 border-bottom">
                  <label class="font-weight-bold mb-0">Health Facility :</label>
                  <h6 class="font-weight-bold text-primary text-uppercase mt-5 mb-5"><?php echo $CI->myutilities->getRef_Desc(104,@$coProfile['HealthFacilityCode']); ?></h6>
                </td>
                <td colspan="2" class="border-top-0 border-bottom">
                  <label class="font-weight-bold mb-0">Address :</label>
                  <h6 class="font-weight-bold text-primary text-uppercase mt-5 mb-5"><?php echo $CI->myutilities->getRef_Desc(104,@$coProfile['HealthFacilityCode'],false,'inst_address_street'); ?></h6>
                </td>
                <td class="w-p20 border-top-0 border-bottom">
                  <label class="font-weight-bold mb-0">Contact No :</label>
                  <h6 class="font-weight-bold text-primary text-uppercase mt-5 mb-5"><?php echo $CI->myutilities->getRef_Desc(104,@$coProfile['HealthFacilityCode'],false,'inst_contactno'); ?></h6>
                </td>
              </tr>
              <tr>
                <td class="w-p20 border-top-0">
                  <label class="font-weight-bold mb-0">Last Name :</label>
                  <h6 class="font-weight-bold text-primary text-uppercase mt-5 mb-5"><?php echo @$coProfile['pLastname']; ?></h6>
                </td>
                <td class="w-p20 border-top-0">
                  <label class="font-weight-bold mb-0">First Name :</label>
                  <h6 class="font-weight-bold text-primary text-uppercase mt-5 mb-5"><?php echo @$coProfile['pFirstname']; ?></h6>
                </td>
                <td class="w-p20 border-top-0">
                  <label class="font-weight-bold mb-0">Middle Name :</label>
                  <h6 class="font-weight-bold text-primary text-uppercase mt-5 mb-5"><?php echo @$coProfile['pMiddlename']; ?></h6>
                </td>
                <td class="w-p20 border-top-0">
                  <label class="font-weight-bold mb-0">Suffix :</label>
                  <h6 class="font-weight-bold text-primary text-uppercase mt-5 mb-5"><?php echo @$coProfile['Suffix']; ?></h6>
                </td>
                <td class="w-p20 border-top-0">
                  <label class="font-weight-bold mb-0">Sex :</label>
                  <h6 class="font-weight-bold text-primary mt-5 mb-5"><?php echo @$coProfile['Sex']; ?></h6>
                </td>
              </tr>
              <tr>
                <td>
                  <label class="font-weight-bold mb-0">Date of Birth :</label>
                  <h6 class="font-weight-bold text-primary mt-5 mb-5"><?php echo date("m/d/Y",strtotime($coProfile['DateofBirth'])); ?></h6>
                </td>
                <td>
                  <label class="font-weight-bold mb-0">Email Address :</label>
                  <h6 class="font-weight-bold text-primary mt-5 mb-5"><?php echo $coProfile['emailaddress']; ?></h6>
                </td>
                <td>
                  <label class="font-weight-bold mb-0">Mobile No :</label>
                  <h6 class="font-weight-bold text-primary mt-5 mb-5"><?php echo $coProfile['Mobile_no']; ?></h6>
                </td>
                <td>
                  <label class="font-weight-bold mb-0">Registration Date/Time :</label>
                  <h6 class="font-weight-bold text-primary mt-5 mb-5"><?php echo date("m/d/Y h:i:s A",strtotime($coProfile['register_datetime'])); ?></h6>
                </td>
                <td>
                  <label class="font-weight-bold mb-0">Activated Date/Time :</label>
                  <h6 class="font-weight-bold text-primary mt-5 mb-5"><?php echo date("m/d/Y h:i:s A",strtotime($coProfile['activateddatetime'])); ?></h6>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  </div>

  <?php if( $coProfile['user_classification'] == 6 ){ ?>
  <div class="col-md-12 col-lg-12">
    <div class="card mb-5">
        <div class="card-header card-header-transparent card-header-bordered font-weight-bold">
           <h4>Z-Benefits Services
            <?php if($CI->userclassification == 1){ ?>
              <span class="float-right" ><input type="checkbox" id="ZBenServices_All" name="ZBenServices_All" zbentoggle="all" value="<?php echo $CI->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],(date("Ymd").'_all'.'_'.$coProfile['user_id']),'MCrypt','aes-128','ecb'); ?>"  data-plugin="switchery" data-color="#757575" data-switchery="true" data-size="medium"  <?php echo ( count($myzbenServices) == count($zbenServices) ) ? 'checked' : ''; ?>  style="display: none;"></span>
            <?php } ?>
            </h4>
        </div>
        <div class="card-block p-5">
            <div class="row">

              <?php

                foreach($zbenServices as $zrow => $zcol)
                {
                  $services_details = $CI->myutilities->getRef_Desc(210,$zcol['zben_services'],false,'',true);
                  $services_name = $services_details['display'];
                  $services_code = str_pad((int) $services_details['code'],2,0,STR_PAD_LEFT);
                  $services_code_val = $CI->m_api->encryptdecryptString('encrypt',$this->userprofile['User_E_Key'],(date("Ymd").'_'.$services_code.'_'.$coProfile['user_id']),'MCrypt','aes-128','ecb');

                  $actions = '';
                  
                  if( $CI->userclassification == 1 )
                  {
                    $actions = '<input type="checkbox" id="ZBenServices_'.$services_code.'" name="ZBenServices_'.$services_code.'" title="'.@$services_name.'" zbentoggle="'.$services_code.'" value="'.$services_code_val.'" dvalue="'.$services_code_val.'" data-plugin="switchery" data-color="#757575" '.((in_array($services_code,$myzbenServices)) ? 'checked' : '').' data-switchery="true" data-size="small" style="display: none;">';
                    $bg = 'bg-yellow-50';
                  }
                  else
                  {
                    $actions = ( in_array($services_code,$myzbenServices) ) ? '<i class="fa fa-fw fa-check-circle-o text-success font-size-30 mt--10"></i>' : '';
                    $bg = ((in_array($services_code,$myzbenServices)) ? 'bg-yellow-50' : 'bg-light');
                  }

                  echo '
                    <div class="col-md-4 col-lg-4">
                      <div class="card '.$bg.' mb-5" data-mh="zbenServices">
                        <div class="card-block p-5 pl-10 pr-0">
                          <div class="float-left w-p85" >
                            <h5>'.@$services_name.'</h5>
                          </div>
                          <div class="float-right w-p10 mt-10">
                            '.$actions.'
                          </div>
                        </div>
                      </div>
                    </div>
                  ';
                }

              ?>


            </div>
        </div>
      </div>
   
  </div>
  <?php } ?>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

$(document).ready(function(){
  <?php if($CI->userclassification == 1){ ?>
  var upza = function(t,z,s){
    var sact = (s) ? 'Permission Successfully Added' : 'Permission Successfully Removed';
    var eact = (s) ? 'Failed to add pemission' : 'Failed to remove permission';
    try{
      pData = new FormData();
      pData.append('zbenservices', z);
      pData.append('state', s);
      var apiPath = site_url+'coordinator/index/zbenpermission';
      var apiResult = function(pp,rp){
        if(rp.Status == 1)
        {
          toastr.success(t,sact);
        }
        else
        {
          toastr.error(t,eact);
        }
      }
      submitFormData('json','',apiPath,pData,apiResult,false,true,0);
    }catch(e){
       toastr.error(t,"Failed to Add/Remove Pemission");
    }
  };

  $("[zbentoggle]").change(function(){
    var cs = ($(this).prop('checked')) ? false : true;
    switch($(this).attr('id'))
    {
      case 'ZBenServices_All':
        $("[zbentoggle][id!='ZBenServices_All']").each(function(){
          if( $(this).prop('checked') == cs )
          {
            $(this).click();
          }
        });
      break;

      default:

      upza($(this).attr('title'),$(this).val(),$(this).prop('checked'));
      
      break;
    }
  })
  <?php } ?>
});

</script>
