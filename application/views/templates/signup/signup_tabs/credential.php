<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>


<div id="Step1" class="wizard-pane active" role="tabpanel">
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="eaddrs">Email Address</label>
      <input type="text" class="form-control" id="eaddrs" name="eaddrs"  title="Email Address"  autocomplete="signup_off" groupstep="Credential" forminputgroup="signupform" required="required">
      <div class="hint text-uppercase w-p100 mt-0" ><span id="exists_eaddrs" class="float-right" ></span></div>
   </div>
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="unme">USERNAME</label>
      <input type="text" class="form-control" id="unme" name="unme" title="User Name" autocomplete="signup_off" groupstep="Credential" forminputgroup="signupform" minlength="<?php echo @$CI->system_settings['unmeLength_Min']; ?>" maxlength="<?php echo @$CI->system_settings['unmeLength_Max']; ?>" required="required" onKeyPress="return isAllowedChar(event)">
      <div class="hint text-uppercase w-p100 mt-0" ><span id="exists_unme" class="float-right" ></span></div>
   </div> 
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="psswrd">Password</label>
      <input type="password" class="form-control" id="psswrd" name="psswrd" title="psswrd"  autocomplete="signup_off" groupstep="Security" forminputgroup="signupform" minlength="<?php echo @$CI->system_settings['PasswordLength_Min']; ?>" maxlength="<?php echo @$CI->system_settings['PasswordLength_Max']; ?>" data-plugin="strength" required="required" onKeyPress="return isPasswordAllowed(event,this)">
      <div class="hint text-uppercase w-p100 mt-0" ><span id="match_psswrd" class="float-right" >Password and Confirm Password Doesn't Match</span></div>
   </div> 
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="confirmpsswrd">Confirm Password</label>
      <input type="password" class="form-control" id="confirmpsswrd" name="confirmpsswrd"  title="Confirm Password"  autocomplete="signup_off" groupstep="Security" forminputgroup="signupform" minlength="<?php echo @$CI->system_settings['PasswordLength_Min']; ?>" maxlength="<?php echo @$CI->system_settings['PasswordLength_Max']; ?>" data-plugin="strength" required="required" onKeyPress="return isPasswordAllowed(event,this)">
      <div class="hint text-uppercase w-p100 mt-0" ><span id="match_confirmpsswrd" class="float-right" >Password and Confirm Password Doesn't Match</span></div>
   </div>   

   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="security_question">Security Question</label>
      <select class="text-uppercase form-control" id="security_question" name="security_question" autocomplete="signup_off" groupstep="Credential" forminputgroup="signupform"  required="required"></select>
   </div>
   <div class="form-group form-material d-none">
      <label class="form-control-label text-uppercase" for="security_question_custom">Custom Security Question</label>
      <input type="text" class="text-uppercase form-control" id="security_question_custom" name="security_question_custom" groupstep="Credential" autocomplete="signup_off" forminputgroup="signupform">
   </div>
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="security_answer">Security Answer</label>
      <input type="password" class="form-control" id="security_answer" name="security_answer" autocomplete="signup_off" groupstep="Credential" forminputgroup="signupform"  required="required">
   </div>   
</div>
