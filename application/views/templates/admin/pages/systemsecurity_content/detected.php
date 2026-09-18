<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();
$IPLookupAPIKey = $CI->system_settings['IPLookupAPIKey'];
$BannedIPList = $CI->getbannedip(true);
$BannedIPList = $BannedIPList['BanIPList'];
$IPLookupinfoList = FCPATH.'IPLookupList.txt';

$cardsList = [];
  
if(!isset($_SESSION['IPLookupinfo']))
{
  $_SESSION['IPLookupinfo'] = [];

  if(file_exists($IPLookupinfoList)) 
  {
    $IPLookupinfoData = file_get_contents($IPLookupinfoList);
    $IPLookupinfoData = json_decode($IPLookupinfoData,TRUE);
    $_SESSION['IPLookupinfo'] = $IPLookupinfoData;
  }

}

foreach($BannedIPList as $ip => $cnt)
{
 
  if(!array_key_exists($ip, $_SESSION['IPLookupinfo']))
  {
    $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );
    $res = file_get_contents('https://www.iplocate.io/api/lookup/'.$ip.'?apikey='.$IPLookupAPIKey,false, stream_context_create($arrContextOptions));
    $_SESSION['IPLookupinfo'][$ip] = json_decode($res,true);
  }

  $iplookupinfo = $_SESSION['IPLookupinfo'][$ip];
  
  $cnter = is_array($cnt) ? $cnt['AccessCnt'] : $cnt;
  if($cnt['BanIP'] == 0 || $cnter <= 10)
  {
    $ipcnt = ((@$ipcnt == '') ? 1 : ($ipcnt+1));
    $cardsList[] = '
      <tr>
        <td  class="text-left">'.$ipcnt.'</td>
        <td dcontent="'.strtolower($ip).'"  class="text-left">'.$ip .'</td>
        <td dcontent="'.( (@$iplookupinfo['country'] <> '') ? strtolower(@$iplookupinfo['country']) : "").' [ '.@$iplookupinfo['country_code'].'"  class="text-left">'.@$iplookupinfo['country'].' [ '.@$iplookupinfo['country_code'].' ]</td>
        <td dcontent="'.@$iplookupinfo['latitude'].'/'.@$iplookupinfo['longitude'].'"  class="text-left">'.@$iplookupinfo['latitude'].'/'.@$iplookupinfo['longitude'].'</td>
        <td dcontent="'.( (@$iplookupinfo['time_zone'] <> '') ? strtolower(@$iplookupinfo['time_zone']) : "").'"  class="text-left">'.@$iplookupinfo['time_zone'].'</td>
        <td dcontent="'.( (@$iplookupinfo['network'] <> '') ? strtolower(@$iplookupinfo['network']) : "").'"  class="text-left">'.@$iplookupinfo['network'].'</td>
        <td dcontent="'.( (@$iplookupinfo['org'] <> '') ? strtolower(@$iplookupinfo['org']) : "").'"  class="text-left">'.@$iplookupinfo['org'].'</td>
        <td class="text-left">'.@$cnt['LastAccessDT'].'</td>
      </tr>
  ';
  }
}

if(file_exists($IPLookupinfoList)) 
{
  file_put_contents($IPLookupinfoList,json_encode($_SESSION['IPLookupinfo']));
}

?>

<div class="row mb-10">
  <div class="col-sm-12 col-lg-12 mb-5" style="cursor:pointer"> 
    <div class="card mb-10 border border-headercolor" data-mh="3nfTables">
        <div class="card-block">
        <div class="form-group form-material mb-0" data-plugin="formMaterial">
            <div class="input-group">
              <div class="form-control-wrap">
                <input type="text" class="form-control" id="search_resource" name="search_resource" placeholder="Quick Search..." autocomplete="off">
              </div>    
            </div>
          </div>
        </div>
      </div>
  </div>

  <div class="col-sm-12 col-lg-12 mb-10" > 
    <div class="card mb-10 border border-headercolor" data-mh="3nfTables">
      <div class="card-block scrollbar-dark thin" style="height:58vh;max-height:58vh;overflow-y: scroll;">
        <table class="table table-sm table-striped border-0 mb-0">
          <thead>
            <tr>
              <td class="text-left w-20"><label class="font-weight-bold text-danger">#</label></td>
              <td class="text-left"><label class="font-weight-bold text-danger">IP Address</label></td>
              <td class="text-left"><label class="font-weight-bold">Country</label></td>
              <td class="text-left"><label class="font-weight-bold">Lat / Long</label></td>
              <td class="text-left"><label class="font-weight-bold">Timezone</label></td>
              <td class="text-left"><label class="font-weight-bold">Network</label></td>
              <td class="text-left"><label class="font-weight-bold">Network Org</label></td>
              <td class="text-left"><label class="font-weight-bold">Last Access Attempt Date/Time</label></td>
            </tr>
          </thead>
          <tbody id="ipbodylist">
            <?php echo implode('',$cardsList); ?>
          </tbody>
        </table>  
      </div>
    </div>
  </div>
</div>


<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

$(document).ready(function(){


$("input[id='search_resource']").keyup(function(){
  if($(this).val())
  {
    $("tbody[id='ipbodylist'] tr").hide();
    $("td[dcontent*='"+($(this).val()).toLowerCase()+"']").closest('tr').show();
  }
  else
  {
     $("tbody[id='ipbodylist'] tr").show();
  }
});



});  


</script>

