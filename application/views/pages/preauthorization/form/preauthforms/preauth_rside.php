<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<?php if( ( is_array(@$preauthHistory) && count($preauthHistory) > 0 ) || ( is_array(@$preauthHStatus) && count($preauthHStatus) > 1 ) ){ ?>
  </div>
  <div class="col-lg-2">
    <?php if(count(@$preauthHistory) > 0){ ?>
    <div class="card mb-0" style="">
      <div id="cardbody" class="card-body p-10 bg-white font-size-16" style="">
        <h6 class="text-uppercase text-center font-weight-bold border-bottom "><i class="fa fa-history mr-10"></i>Pre-Auth Form Version</h6>
        <div>
          <div class="list-group bg-grey-100 bg-inherit mb-0 ">
            <?php 
                foreach($preauthHistory as $phR => $phC)
                { 
                  if( (int) @$currenthistoryid <> (int) $phC['rowid'])
                  {
                    if($phC['dataarchives'])
                    {
                      $rekey = $CI->myutilities->getUserProfile($phC['created_by']);
                      $datahist = json_decode($CI->m_api->encryptdecryptString('decrypt',$rekey['User_E_Key'],base64_decode($phC['dataarchives']),'MCrypt','aes-128','ecb'),true);
                      if(is_array($datahist))
                      {
                        echo '
                          <a class="list-group-item grey-600 mb-5" href="'.site_url('preauthorization/index/view/'.bin2hex(base64_encode('history_'.((int) $phC['preauth_rowid']).':'.((int) $phC['rowid'])))).'" title="Click to View Pre-Authorization Form History">
                            <i class="icon md-calendar float-left font-size-30 mt-5 text-dark" aria-hidden="true"></i> <span class="float-left font-size-12 text-dark font-weight-bold">Status : '.@$CI->myutilities->getRef_Desc(202,$datahist['patientinfo']['preauth_status']).'</span><span  class="float-left font-size-12 text-dark font-weight-bold">Date : '.date("m/d/y h:i:s A",strtotime($phC['created_datetime'])).'</span>
                          </a>
                        ';
                      }
                    }
                  }
                }          
            ?> 
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php if(count($preauthHStatus) > 0){ ?>
    <div class="card mb-0 " style="">
      <div id="cardbody" class="card-body p-10 bg-white font-size-16" style="">
        <h6 class="text-uppercase text-center font-weight-bold border-bottom "><i class="fa fa-history mr-10"></i>Pre-Auth Status History</h6>
        <div class="mh-500 scrollbar scrollbar-dark thin mb-0 ml-0 w-p100">
          <div class="list-group bg-yellow-100 bg-inherit mb-0">
            <?php 
              
                foreach($preauthHStatus as $pshR => $pshC)
                { 
                  $sicon = '';
                  switch($pshC['preauth_status'])
                  {
                    case 1:
                      $sicon = 'fa-send-o text-dark';
                    break;

                    case 2:
                      $sicon = 'fa-send text-info';
                    break;

                    case 3:
                      $sicon = 'fa-file-archive-o text-primary';
                    break;

                    case 4:
                      $sicon = 'fa-exclamation-circle';
                    break;

                    case 5:
                      $sicon = 'fa-thumbs-down text-danger';
                    break;

                    case 6:
                      $sicon = 'fa-thumbs-up text-success';
                    break;
                  }

                  $pTAT = '';
                  if( $pshC['preauth_status_tat'] <> '')
                  {
                    $tat = json_decode($pshC['preauth_status_tat']);
                  }

                  if($tat <> '')
                  {
                    $pTAT = @$tat->s.'s';
                    $rttat = ['y'=>'yr(s)','m'=>'mth(s)','d'=>'day(s)','h'=>'hr(s)','i'=>'min(s)','s'=>'s'];
                    foreach($rttat  as $tk => $tl )
                    {
                      if( $tat->{$tk} <> 0 )
                      {
                          $pTAT = $tat->{$tk}.' '.$tl;
                          break;
                      }
                    }
                  }
                 

                  echo '
                    <a class="list-group-item yellow-600 mb-5" href="javascript:void(0)">
                      <i class="fa '.$sicon.' fa-fw float-left ml--5 mt--5  font-size-30 mr-5" aria-hidden="true"></i> <span class="float-left font-size-12 text-dark font-weight-bold">Status : '.@$CI->myutilities->getRef_Desc(202,$pshC['preauth_status']).( (in_array( (int) $pshC['preauth_status'],[3,4,5,6])) ? ' [BAS]' : '').'</span><span  class="float-left font-size-12 text-dark font-weight-bold">Date : '.date("m/d/y h:i:s A",strtotime($pshC['preauth_status_datetime'])).'</span><span  class="float-left font-size-12 text-dark font-weight-bold">Turn Around Time : '.@$pTAT.'</span>
                    </a>
                  ';
                }
              
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
<?php } ?>