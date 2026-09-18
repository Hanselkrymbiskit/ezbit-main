<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

 <div id="Step3" class="wizard-pane" role="tabpanel">
                     
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="security_question">Security Question</label>
      <select class="text-uppercase form-control" id="security_question" name="security_question" autocomplete="signup_off" groupstep="Security" forminputgroup="signupform"  required="required"></select>
   </div>
   <div class="form-group form-material d-none">
      <label class="form-control-label text-uppercase" for="security_question_custom">Custom Security Question</label>
      <input type="text" class="text-uppercase form-control" id="security_question_custom" name="security_question_custom" groupstep="Security" autocomplete="signup_off" forminputgroup="signupform">
   </div>
   <div class="form-group form-material">
      <label class="form-control-label text-uppercase" for="security_answer">Security Answer</label>
      <input type="password" class="form-control" id="security_answer" name="security_answer" autocomplete="signup_off" groupstep="Security" forminputgroup="signupform"  required="required">
   </div>
</div>
