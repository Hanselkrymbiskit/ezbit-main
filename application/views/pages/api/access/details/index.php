<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$HTTPCode = [
  '200' => 'HTTP_OK',
  '201' => 'HTTP_CREATED',
  '304' => 'HTTP_NOT_MODIFIED',
  '400' => 'HTTP_BAD_REQUEST',
  '401' => 'HTTP_UNAUTHORIZED',
  '403' => 'HTTP_FORBIDDEN',
  '404' => 'HTTP_NOT_FOUND',
  '405' => 'HTTP_METHOD_NOT_ALLOWED',
  '406' => 'HTTP_NOT_ACCEPTABLE',
  '500' => 'HTTP_INTERNAL_ERROR',
];

$exludeFHIRR = ['12','13','14'];
$fhrR = $CI->sqlhelper->local->select("rest_api_controller")->where(" `id` not in ('".implode("','",$exludeFHIRR)."') ")->result();
$fR = [];
foreach($fhrR['Data'] as $rrr=>$ccc)
{
  $fR[$ccc['controller']] = $ccc;
}


?>

<div class="card border-headercolor mb-10" style="">
  <div class="card-body">
    <h6 class="text-uppercase"><?php echo ($CI->usertype < 4) ? 'FACILITY INFO' : 'CREDENTIALS'; ?> : </h6>
    <table class="table table-sm mb-0">
       <tbody>
        <tr>
          <td colspan="3" class=""><label class="font-weight-bold">Facility Name :</label><div class="text-success  text-uppercase"><span><?php echo (@$accessinfo['FacilityName'] <> '') ?  @$accessinfo['FacilityName'] : @$accessinfo[0]['FacilityName']; ?></span></div></td>
          <td class="w-p20"><label class="font-weight-bold">Facility Code :</label><div class="text-success"><span><?php echo (@$accessinfo['FacilityCode'] <> '') ?  @$accessinfo['FacilityCode'] : @$accessinfo[0]['FacilityCode']; ?></span></div></td>
          <td class="w-p20"><label class="font-weight-bold">Organization Resource ID :</label><div class="text-success"><span><?php echo (@$accessinfo['organizationid'] <> '') ? (int)  @$accessinfo['organizationid'] : (int) @$accessinfo[0]['organizationid']; ?></span></div></td>
        </tr>
      <?php if($CI->usertype > 3) { ?>
        <tr>
          <td class="w-p20"><label class="font-weight-bold">Account Name :</label><div class="text-success"><span><?php echo @$accessinfo['Account_Name']; ?></span></div></td>
          <td class="w-p20"><label class="font-weight-bold">Contact No :</label><div class="text-success"><span><?php echo @$accessinfo['FacilityContactNo']; ?></span></div></td>
          <td class="w-p20"><label class="font-weight-bold">Email Address :</label><div class="text-success"><span><?php echo @$accessinfo['emailaddress']; ?></span></div></td>
          <td class="w-p20"><label class="font-weight-bold">Access KEY :</label><div class="text-success"><span><?php echo  @$accessinfo['api_key']; ?></span></div></td>
          <td class="w-p20"><label class="font-weight-bold">Encryption KEY :</label><div class="text-success"><span><?php echo  @$accessinfo['User_E_Key']; ?></span></div></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<?php if($CI->usertype < 4) { ?>
<div class="card border-headercolor mb-10" style="">
  <div class="card-body">
    <h6 class="text-uppercase">API KEY CREDENTIALS : </h6>
    <table class="table table-striped table-sm mb-0">
      <thead>
        <tr>
          <th class="align-middle text-center " style="width:2%">#</th>
          <th class="align-middle text-left w-p20"><label class="font-weight-bold">NAME</label></th>
          <th class="align-middle text-center w-p20"><label class="font-weight-bold ">EMAIL</label></th>
          <th class="align-middle text-center w-p20"><label class="font-weight-bold ">API KEY</label></th>
          <th class="align-middle text-center w-p20"><label class="font-weight-bold ">ENCRYPTION KEY</label></th>
          <th class="align-middle text-center w-p10"><label class="font-weight-bold ">STATUS</label></th>
        </tr>
      <tbody>
        <?php
          $keyCNT = 0;
          foreach( $accessinfo as $accessRow => $accessCol)
          {
              $keyCNT++;
              echo '
        <tr>
          <td class="align-middle text-center ">'.$keyCNT.'</td>
          <td class="align-middle text-left">'.$accessCol['Account_Name'].'</td>
          <td class="align-middle text-center">'.$accessCol['emailaddress'].'</td>
          <td class="align-middle text-center">'.$accessCol['api_key'].'</td>
          <td class="align-middle text-center">'.$accessCol['User_E_Key'].'</td>
          <td class="align-middle"></td>
        </tr>
            ';
          }

        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>

<div class="card border-headercolor mb-10" style="">
  <div class="card-body">
    <h6 class="text-uppercase">FHIR Resource Access : </h6>
    <table class="table table-sm table-bordered mb-0">
      <thead>
        <tr>
          <th rowspan="2" class="align-middle text-center " style="width:2%">#</th>
          <th rowspan="2" class="align-middle text-center w-p20"><label class="font-weight-bold">NAME</label></th>
          <th rowspan="2" class="align-middle text-center"><label class="font-weight-bold ">URI</label></th>
          <th rowspan="2" class="align-middle text-center"><label class="font-weight-bold ">RECORD COUNT</label></th>
          <th colspan="10" class="align-middle"><label class="font-weight-bold ">RESPONSE CODE</label></th>
        </tr>
        <tr>
          <?php 
          foreach( $HTTPCode as $hk => $hv)
          {
            echo '<th class="text-center w-p5" title="'.$hv.'"><label class="font-weight-bold font-size-12">'.$hk.'</label></th>';
          }

          ?>
        </tr>
      </thead>
      <tbody>
        <?php 
          
          $exludeFHIRR[] = 10; $exludeFHIRR[] = 11; $exludeFHIRR[] = 15;
          
          if($CI->usertype > 3)
          {
            $fhirr = array_reverse(json_decode(@$accessinfo['controller'],TRUE));
            $fhrcnt = 0;
            foreach( $fhirr as $fhirhk => $fhirhv)
            {
              $fhrcnt++;
              echo '
              <tr>
                <td class="align-middle text-center " style="width:2%">'.$fhrcnt.'</td>
                <td class="align-middle text-center w-p20"><label class="font-weight-bold">'.((@$fR[$fhirhv]['display'] == 'Index') ? 'Fhir' : @$fR[$fhirhv]['display']).'</label></td>
                <td class="align-middle text-left"><label class="font-weight-bold ml-10">'.((@$fR[$fhirhv]['display'] == 'Index') ? 'fhir' : $fhirhv).'</label></td>
                <td class="align-middle text-center">'.( (!in_array((int) $fR[$fhirhv]['id'], $exludeFHIRR)) ? '<span id="fhirresource_'.( ($fhirhv=='') ? '' : strtolower($fhirhv)).'_totalrecord'.'" class="font-weight-bold">0</span>' : '').'</td>
              ';
              foreach( $HTTPCode as $hk => $hv)
              {
                echo '<td class="text-center w-p5" title="'.$hv.'"><span class="font-weight-bold font-size-12" id="fhirresource_'.( ($fhirhv=='') ? '' : strtolower($fhirhv)).'_'.$hk.'">0</span></td>';
              }
   
              echo '</tr>';
            }
          }
          else
          {

            $fhrcnt = 0;
            foreach( $fR as $frController => $frConfig )
            {
              $fhrcnt++;
              echo '
              <tr>
                <td class="align-middle text-center " style="width:2%">'.$fhrcnt.'</td>
                <td class="align-middle text-center w-p20"><label class="font-weight-bold">'.((@$frConfig['display'] == 'Index') ? 'Fhir' : @$frConfig['display']).'</label></td>
                <td class="align-middle text-left"><label class="font-weight-bold ml-10">'.((@$frConfig['display'] == 'Index') ? 'fhir' : $frConfig['controller']).'</label></td>
                <td class="align-middle text-center">'.( (!in_array((int) $frConfig['id'], $exludeFHIRR)) ? '<span id="fhirresource_'.( ($frController=='') ? '' : strtolower($frController)).'_totalrecord'.'" class="font-weight-bold">0</span>' : '<i class="fa fa-fw fa-ban"></i>').'</td>
              ';
              foreach( $HTTPCode as $hk => $hv)
              {
                echo '<td class="text-center w-p5" title="'.$hv.'"><span class="font-weight-bold font-size-12" id="fhirresource_'.( ($frController=='') ? '' : strtolower($frController)).'_'.$hk.'">0</span></td>';
              }
   
              echo '</tr>';
            }
          }
        ?>
      </tbody>
    </table>
  </div>
</div>

<div>
<?php
$phpFilePath = 'views/pages/api/logs/index';
if ( is_file(APPPATH.$phpFilePath.'.php'))
{
  $APILogsContent = $CI->load->view(str_replace('views/','',$phpFilePath), $CI->data, true);
  $APILogsContent = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $APILogsContent));
  echo $APILogsContent;
}
?>
</div>
<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

</script>