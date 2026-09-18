<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$DBSizeQ = "SELECT  SUM(data_length + index_length) as 'DBSize' FROM information_schema.tables 
where table_schema = '".$CI->defaultDB->database."' GROUP BY table_schema";
$getDBSize = $CI->sqlhelper->local->sql($DBSizeQ)->row();
$DBSize = $getDBSize['Data']['DBSize'];

$getD = $CI->sqlhelper->local->sql("SHOW VARIABLES WHERE Variable_Name = 'datadir'")->row();
$dbDataDrive = $getD['Data']['Value'];
$dbDataDrive = explode(":\\",$dbDataDrive);
$dbDataDrive = $dbDataDrive[0];

?>

<div class="col-sm-4">
  <div class="card card-block border border-secondary  mb-10">
    <h5 class="card-title">
      <i class="fa fa-database fa-fw mr-10"></i>Database Information
      <span id="dbinfo_loader" class="float-right" style="display: none"><i class="fa fa-fw fa-circle-o-notch icon-spin"></i></span>
    </h5>
    <div class="table-responsive" data-mh="serverGrouping">
      <table class="table" style="border:0px !important;margin-bottom:0px !important">
        <tr>
          <td class="text-success text-center" style="border:0px !important;font-weight:bold;vertical-align:middle">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Database Size</div>
            <div class="h1 mb-0 font-weight-bold text-gray-800 font-size-80">
              <span id="dbSize"><?php echo $CI->myutilities->HumanSize($DBSize,true,true); ?></span>
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
function getDBInfo()
{
  $("span[id='dbinfo_loader']").show();
  var target_url = site_url+'administrator/index/getdatabaseinfo';
  var PostParam = new FormData();
  var rpResult = function(pp,rp){
    if(rp.Status == 1 )
    {
      $("span[id='dbSize']").text(rp.Message['appsSize']);
      $("span[id='dbdrive_freespace']").text(rp.Message['appsdrive_freespace']);
    }
    $("span[id='dbinfo_loader']").hide();
    setTimeout(function(){
      getDBInfo();
    },5000);
  };

  submitFormData('json','',target_url,PostParam,rpResult,false);
}
$(document).ready(function(){
  getDBInfo();
});
</script>