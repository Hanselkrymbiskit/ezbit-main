<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();




?>

<form id="newdownloaditemForm" class="mb-0 mt-20" novalidate="novalidate">

<div class="panel mb-10" style="">
  <div class="panel-body p-10">
    <table class="table table-sm mb-0">
      <tr>
        <td class="" style="border-top:0px">    
          <div class="form-group form-material" data-plugin="formMaterial">
            <label class="label">File Title</label>
            <input type="text" title="Year" class="form-control text-center font-size-20" id="CrecYear" name="CrecYear" forminputgroup="newdownloaditemForm" forminputgroupcheck="newdownloaditemForm" value="" dfvalue="" placeholder="" autocomplete="newdownloaditemForm_off" data-plugin="formatter" data-pattern="[[9999]]" required="required">          
          </div>
        </td>
      </tr>
      <tr>
        <td class="" style="border-top:0px">     
          <div class="form-group form-material" data-plugin="formMaterial">
            <label class="label">File Description</label>
            <textarea title="Year" class="form-control text-center font-size-20" id="CrecYear" name="CrecYear" forminputgroup="newdownloaditemForm" forminputgroupcheck="newdownloaditemForm" value="" dfvalue="" placeholder="" autocomplete="newdownloaditemForm_off" required="required"></textarea>         
          </div>
        </td>
      </tr>
    </table>
  </div>
</div>



</form>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

$(document).ready(function(){


});
</script>