<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<div id="FormMainPanel" class="panel" style="display:none">
  <div class="panel-header p-0">
    <h3 class="bg-dark text-uppercase text-left mt-0 mb-0 p-10">
      <i class="fa fa-fw fa-question mr-10"></i>
      <span>FREQUENTLY ASKED QUESTIONS</span>
    </h3>
  </div>
  <div class="panel-body p-10">
    <form id="frm_campaignprofile" class="" novalidate="novalidate" autocomplete="off">
       <?php
            $pageleftForm = 'views/templates/faq/new/page/left';
            if ( is_file(APPPATH.$pageleftForm.'.php'))
            {
              $pageleftFormContent = $CI->load->view(str_replace('views/','',$pageleftForm), $CI->data, true);
              $pageleftFormContent = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $pageleftFormContent));
            }
            echo @$pageleftFormContent;
          ?>
    </form>
  </div>
</div>