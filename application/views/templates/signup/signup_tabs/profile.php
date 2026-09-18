<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<div id="Step4" class="wizard-pane" role="tabpanel">
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="Lastname">Last Name</label>
      <input type="text" class="text-uppercase form-control" id="Lastname" name="Lastname" autocomplete="signup_off" groupstep="Profile" forminputgroup="signupform"  required="required" onKeyPress="return isAllowedChar(event)">
   </div>
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="Firstname">First Name</label>
      <input type="text" class="text-uppercase form-control" id="Firstname" name="Firstname" autocomplete="signup_off" groupstep="Profile" forminputgroup="signupform"  required="required" onKeyPress="return isAllowedChar(event)">
   </div>
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="Middlename">Middle Name</label>
      <input type="text" class="text-uppercase form-control" id="Middlename" name="Middlename" placeholder="Put N/A for Not Applicable" autocomplete="signup_off" groupstep="Profile" forminputgroup="signupform"  required="required" onKeyPress="return isAllowedChar(event)">
   </div>
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="Suffixname">Suffix Name</label>
      <select class="text-uppercase form-control" id="Suffixname" name="Suffixname" autocomplete="signup_off" groupstep="Profile" forminputgroup="signupform"  required="required"></select>
   </div>
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="DateofBirth">Date of Birth</label>
      <input type="text" class="text-uppercase form-control" id="DateofBirth" name="DateofBirth" autocomplete="signup_off" data-plugin="formatter,datepicker" data-pattern="[[99]]/[[99]]/[[9999]]" groupstep="Profile" forminputgroup="signupform"  required="required">
   </div>
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="Sex">Sex</label>
      <select class="text-uppercase form-control" id="Sex" name="Sex" autocomplete="signup_off" groupstep="Profile" forminputgroup="signupform"  required="required"></select>
   </div>
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="Mobile_no">Mobile No</label>
      <input type="text" class="text-uppercase form-control" id="Mobile_no" name="Mobile_no" autocomplete="signup_off" groupstep="Profile" placeholder="0917xxxxxxx" forminputgroup="signupform" data-plugin="formatter" data-pattern="09[[99]][[9999999]]">
   </div>
</div>
