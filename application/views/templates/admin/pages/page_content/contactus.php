<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


?>
<div class="row">
    <div class="col-lg-12">
      <div class="panel p-15 pb-0">
        <div class="panel-heading p-0">
          <h3 class="panel-title p-0">Display Content<i class="fa fa-save float-right" dataid="<?php echo $CI->encryption->encrypt(@$pagecontent['ContactUSDisplay']);?>" group="savebtn"  target="ContactUSDisplayContent" title="Save" style="cursor:pointer"></i></h3>
        </div>
        <div class="panel-body p-0">
          <p class="text-info font-weight-bold">Viewable in the Contact US Page for Non/Sign-Out Users.</p>
          <div id="ContactUSDisplayContent" groupdiv="summernote">
              <?php echo base64_decode(@$pagecontent['ContactUSDisplay']); ?>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
      <div class="panel p-15 pb-0">
        <div class="panel-heading p-0">
          <h3 class="panel-title p-0">Inclusion List <i class="fa fa-save float-right" dataid="<?php echo $CI->encryption->encrypt(@$settingsvalue['DataCount']);?>" group="savebtn"  target="IncludeEmail" title="Save" style="cursor:pointer"></i></h3>
        </div>
        <div class="panel-body p-0">
          <p class="text-info font-weight-bold">Please enter the Email Address to be included in message notification. seperated by semi-colon ( ; )</p>
          <div id="IncludeEmail" groupdiv="ingmessage">
            <?php echo base64_decode(@$settingsvalue['IncludeEmail']); ?>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="panel p-15 pb-0">
        <div class="panel-heading p-0">
          <h3 class="panel-title p-0">Exclusion List <i class="fa fa-save float-right" dataid="<?php echo $CI->encryption->encrypt(@$settingsvalue['DataCount']);?>" group="savebtn" target="ExcludeEmail" title="Save" style="cursor:pointer"></i></h3>
        </div>
        <div class="panel-body p-0">
          <p class="text-info font-weight-bold">Please enter the Email Address to be excluded in message notification. seperated by semi-colon ( ; )</p>
          <div id="ExcludeEmail" groupdiv="ingmessage">
            <?php echo base64_decode(@$settingsvalue['ExcludeEmail']); ?>
          </div>
        </div>
      </div>
    </div>
</div>
<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){
  

  $("div[groupdiv*='summernote']").summernote({
    placeholder: '',
    tabsize: 2,
    height: 300,
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'underline', 'clear']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
    ]
  });

  $("div[groupdiv*='ingmessage']").summernote({
    placeholder: '',
    tabsize: 2,
    height: 300,
    toolbar: [
      ['style', []],
      ['font', []],
      ['color', []],
      ['para', []],
      ['table', []],
    ]
  });
  
  $("input[id='NotificationStatus']").change(function(){
    var sveFormData  = new FormData();
    sveFormData.append('NotificationStatus',btoa((($(this).prop('checked')) ? 1 : 0)));
    sveFormData.append('DataCount',$(this).attr('dataid'));
    postUrl = site_url+'notification/index/savesettings';
    submitFormData('json','',postUrl,sveFormData,'',false);
  });
  
  $("i[group*='savebtn']").click(function(){
      var sveFormData = new FormData();
      var IconTarget = $(this).attr('target');
      sveFormData.append($(this).attr('target'),btoa($("div[id='"+$(this).attr('target')+"']").summernote('code')));
      sveFormData.append('DataCount',$(this).attr('dataid'));
      if($(this).attr('target') == 'MessageContent1' || $(this).attr('target') == 'MessageContent2')
      {
        sveFormData.append(( ($(this).attr('target') == 'MessageContent1') ? 'SubjectContent1' : 'SubjectContent2'),btoa($("input[id='"+( ($(this).attr('target') == 'MessageContent1') ? 'SubjectContent1' : 'SubjectContent2')+"']").val()) );
      }
      postUrl = site_url+'notification/index/savesettings';
      var sveResult=function(pp,rp){
        $("i[target='"+IconTarget+"']").removeClass("fa-spinner icon-spin").addClass('fa-save').removeAttr('disabled');
      }

      submitFormData('json','',postUrl,sveFormData,sveResult,false);
      $(this).removeClass("fa-save").addClass('fa-spinner icon-spin').attr('disabled','disabled');
  });
});
</script>

