<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<div id="Step2" class="wizard-pane" role="tabpanel">

   <div class="form-group form-material has-danger">
      <label class="form-control-label text-uppercase" for="user_classification">Classification</label>
      <select class="text-uppercase form-control" id="user_classification" name="user_classification" autocomplete="signup_off" groupstep="Credential" forminputgroup="signupform" required="required"></select>
   </div>

   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label text-uppercase" for="HealthFacilityCode">Health Facility</label>
      <input type="hidden" class="text-uppercase form-control" id="FacilityName" name="FacilityName" autocomplete="signup_off" groupstep="classification" forminputgroup="signupform" " title="Facility Name" style="height:auto;white-space:pre-wrap">
      <select class="text-uppercase form-control" id="HealthFacilityCode" name="HealthFacilityCode" autocomplete="signup_off" groupstep="classification" forminputgroup="signupform" style="color:#757575" >
      </select>
   </div>

   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label text-uppercase" for="Address">Address</label>
      <input type="text" class="text-uppercase form-control" id="Address" name="Address" autocomplete="signup_off" groupstep="classification" forminputgroup="signupform" " title="Address" style="height:auto;white-space:pre-wrap">
   </div>

   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label text-uppercase" for="Region">Pro-Region</label>
      <input type="text" class="text-uppercase form-control" id="RegionName" name="RegionName" autocomplete="signup_off" groupstep="classification" forminputgroup="signupform" " title="Pro-Region" style="height:auto;white-space:pre-wrap" disabled>
      <input type="hidden" class="text-uppercase form-control" id="Region" name="Region" autocomplete="signup_off" groupstep="classification" forminputgroup="signupform" " title="Pro-Region" style="height:auto;white-space:pre-wrap">
   </div>

   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label text-uppercase" for="Province">Province</label>
      <input type="text" class="text-uppercase form-control" id="ProvinceName" name="ProvinceName" autocomplete="signup_off" groupstep="classification" forminputgroup="signupform" " title="Pro-Region" style="height:auto;white-space:pre-wrap" disabled>
      <input type="hidden" class="text-uppercase form-control" id="Province" name="Province" autocomplete="signup_off" groupstep="classification" forminputgroup="signupform" " title="Pro-Region" style="height:auto;white-space:pre-wrap">
   </div>

   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label text-uppercase" for="City">City / Municipality</label>
      <input type="text" class="text-uppercase form-control" id="CityName" name="CityName" autocomplete="signup_off" groupstep="classification" forminputgroup="signupform" " title="Pro-Region" style="height:auto;white-space:pre-wrap" disabled>
      <input type="hidden" class="text-uppercase form-control" id="City" name="City" autocomplete="signup_off" groupstep="classification" forminputgroup="signupform" " title="Pro-Region" style="height:auto;white-space:pre-wrap">
   </div>

   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label text-uppercase" for="FacilityContactNo">Facility Contact No</label>
      <input type="text" class="text-uppercase form-control" id="FacilityContactNo" name="FacilityContactNo" autocomplete="signup_off" groupstep="classification" forminputgroup="signupform" " title="Facility Contact No" style="height:auto;white-space:pre-wrap">
   </div>
   
   <?php if( @$this->system_settings['RegistrationFileAttachment'] == 1 ) { ?>
   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label text-uppercase" for="proofdocument">Document Attachment</label>
      <input type="text" class="text-uppercase form-control" id="proofdocumentHolder" placeholder="Browse..." readonly="">
      <input type="file" id="proofdocument" name="proofdocument" title="Document Attachment" forminputgroup="signupform" autocomplete="signup_off" groupstep="classification" style="" accept="application/pdf" class="" multiple required="required">
      <div id="filelist" ></div>
   </div>
   <?php } ?>

</div>
