<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$appDrive = FCPATH;
$appDrive = explode(":\\",FCPATH);
$appDrive = $appDrive[0];
?>


<div class="col-sm-4">
  <div class="card card-block border border-secondary  mb-10">
    <h5 class="card-title">
      <i class="fa fa-hdd-o fa-fw mr-10"></i>Storage Information
      <span id="storageinfo_loader" class="float-right" style="display: none"><i class="fa fa-fw fa-circle-o-notch icon-spin"></i></span>
    </h5>
    <div class="table-responsive" data-mh="serverGrouping">
      <table class="table" style="border:0px !important;margin-bottom:0px !important">
        <tr>
          <td class="text-success text-center" style="border:0px !important;font-weight:bold;vertical-align:middle">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Application Size</div>
            <div class="h1 mb-0 font-weight-bold text-gray-800 font-size-80">
              <span id="appsSize"><?php echo $CI->myutilities->HumanSize($CI->myutilities->dirSize(FCPATH),true,true); ?></span>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <p class="card-text">
      <small class="text-muted"><!-- Last updated 3 mins ago --></small>
    </p>
  </div>
</div>

<script type="text/javascript">
function getStorageInfo()
{
  $("span[id='storageinfo_loader']").show();
  var target_url = site_url+'administrator/index/getstorageinfo';
  var PostParam = new FormData();
  var rpResult = function(pp,rp){
    if(rp.Status == 1 )
    {
      $("span[id='appsSize']").text(rp.Message['appsSize']);
      $("span[id='appsdrive_freespace']").text(rp.Message['appsdrive_freespace']);
    }
    $("span[id='storageinfo_loader']").hide();
    setTimeout(function(){
      getStorageInfo();
    },5000);
  };

  submitFormData('json','',target_url,PostParam,rpResult,false);
}
$(document).ready(function(){
  getStorageInfo();
});

</script>