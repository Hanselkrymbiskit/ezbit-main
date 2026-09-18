<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>
<div>

  <?php 
    if( @$FAQData['DataID'] <> '' ){ echo '<input type="hidden" class="form-control text-uppercase" id="faqid" name="faqid" forminputgroup="faqformdata" forminputgroupcheck="faqformdata" autocomplete="faqformdata_off" formgroup="faqForm_Data" value="'.base64_encode(base64_encode(@$FAQData['DataID'])).'" dfvalue="'.base64_encode(base64_encode(@$FAQData['DataID'])).'" onKeyPress="return isAlphaNum(event,this)">       
      '; }
  ?>

  <div class="form-group form-material mt-15" data-plugin="formMaterial">
    <label class="label font-weight-bold" for="Title">TITLE/QUESTION</label>
    <input type="text" class="form-control text-uppercase" id="Title" name="Title" forminputgroup="faqformdata" forminputgroupcheck="faqformdata" placeholder="TITLE HERE" autocomplete="faqformdata_off" formgroup="faqForm_Data" value="<?php echo @$FAQData['Title']; ?>" dfvalue="<?php echo @$FAQData['Title']; ?>" onKeyPress="return isAlphaNum(event,this)">       
  </div>

  <div>
    
    <div class="form-group form-material mt-15 mr-5 float-left" data-plugin="formMaterial" style="width:49%">
      <label class="label font-weight-bold" for="Category">CATEGORY</label>
      <select class="form-control text-uppercase" id="Category" name="Category" forminputgroup="faqformdata" forminputgroupcheck="faqformdata" placeholder="" autocomplete="faqformdata_off" formgroup="faqForm_Data" dfvalue="<?php echo @$FAQData['Category']; ?>" ></select>     
    </div>

    <div class="form-group form-material mt-15 ml-5 float-right" data-plugin="formMaterial" style="width:49%">
      <label class="label  font-weight-bold" for="Enable">PUBLISHED</label>
      <select class="form-control text-uppercase" id="Enable" name="Enable" forminputgroup="faqformdata" forminputgroupcheck="faqformdata" placeholder="" autocomplete="faqformdata_off" formgroup="faqForm_Data" dfvalue="<?php echo (@$FAQData['Enable'] == '') ? 'N' : @$FAQData['Enable']; ?>" ></select>        
    </div>
  </div>

  <div>
    <label class="label font-weight-bold w-p100" for="Message_Content">DESCRIPTION / ANSWER</label>
    <div id="Message_Content" groupdiv="summernote">
    <?php echo ((@$FAQData['Message_Content'] <> '') ? base64_decode(@$FAQData['Message_Content']) : ''); ?>
    </div>
    <textarea id="Message_Content" name="Message_Content" class="form-control" forminputgroup="faqformdata" forminputgroupcheck="faqformdata" value="<?php echo @$FAQData['Message_Content']; ?>" style="display:none"><?php echo (@$FAQData['Message_Content'] <> '') ? base64_decode(@$FAQData['Message_Content']) : ''; ?></textarea>
  </div>
</div>