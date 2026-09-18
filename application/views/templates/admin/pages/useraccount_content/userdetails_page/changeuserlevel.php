<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();
$CI->load->model('administrator/m_useraccount');

?>

<form id="changeuserlevelForm" novalidate="novalidate">
  <div class="form-group form-material">
      <label class="form-control-label" for="user_classification">Classification</label>
      <select class="form-control" id="user_classification" name="user_classification" autocomplete="off" groupstep="Credential" forminputgroup="signupform" required="required"></select>
   </div>
   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label" for="OfficeName">Office Name</label>
      <select class="form-control" id="OfficeName" name="OfficeName" autocomplete="off" groupstep="classification" forminputgroup="signupform"></select>
   </div>
   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label" for="OfficeNameRegion">Office Name</label>
      <input type="text" class="form-control" id="OfficeNameRegion" name="OfficeNameRegion" autocomplete="off" groupstep="classification" forminputgroup="signupform"  >
   </div>
   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label" for="OfficeDivisionName">Division Name</label>
      <select class="form-control" id="OfficeDivisionName" name="OfficeDivisionName" autocomplete="off" groupstep="classification" forminputgroup="signupform"  >
      </select>
   </div>

   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label" for="RU_Type">DRU Type</label>
      <select class="form-control" id="RU_Type" name="RU_Type" autocomplete="off" groupstep="classification" forminputgroup="signupform" >
        <option value="1">Health Facility</option>
        <option value="2">Other</option>
      </select>
   </div>


   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label" for="RU_HealthFacilityCode">Name of Disease Reporting Unit</label>
      <div>
         <div class="input-group">
            <div class="form-control-wrap">
                <input type="hidden" class="form-control" id="RU_HealthFacilityCode" title="Health Facility" name="RU_HealthFacilityCode" groupstep="Classification"  forminputgroup="signupform" placeholder="Health Facility Name" autocomplete="off" style="" value="">
                <input type="text" class="form-control " id="RU_FacilityName" name="RU_FacilityName" placeholder="Health Facility" autocomplete="off" style="" forminputgroup="signupform" >
            </div>
            <span class="input-group-btn">
                <button id="btnShowHealthFacilityList" class="btn btn-dark waves-effect waves-classic" type="button" title="Search Health Facility"><i class="fa fa-fw fa-search"></i></button>
            </span>
         </div>
      </div>
   </div>

   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label" for="RU_Address">Address</label>
      <input type="text" class="form-control" id="RU_Address" name="RU_Address" autocomplete="off" groupstep="classification" forminputgroup="signupform"  >
   </div>


   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label" for="RU_Region">Region</label>
      <select class="form-control" id="RU_Region" name="RU_Region" autocomplete="off" groupstep="classification" forminputgroup="signupform" ></select>
   </div>

   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label" for="RU_Province">Province</label>
      <select class="form-control" id="RU_Province" name="RU_Province" autocomplete="off" groupstep="classification" forminputgroup="signupform"></select>
   </div>

   <div parentelement="classification" class="form-group form-material">
      <label class="form-control-label" for="RU_City">City / Municipality</label>
      <select class="form-control" id="RU_City" name="RU_City" autocomplete="off" groupstep="classification" forminputgroup="signupform" ></select>
   </div>
</form>



<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

});  


</script>
