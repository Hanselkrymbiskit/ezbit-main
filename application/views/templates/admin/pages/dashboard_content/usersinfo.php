<?php defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

  $userstatslist = array(
    "OnlineUser"=>array(
      'caption' => 'Users Online',
      'id'    => 5,
      'class' =>'success'
    ),
    "Registered"=>array(
      'caption' => 'Registered Account',
      'id'    => 6,
      'class' =>'success'
    ),
    "Active"=>array(
      'caption' => 'Active Account',
      'id'    => 1,
      'class' =>'success'
    ),
    "Inactive"=>array(
      'caption' => 'Inactive Account',
      'id'    => 2,
      'class' =>'info'
    ),
    "Expired"=>array(
      'caption' => 'Expired Account',
      'id'    => 3,
      'class' =>'warning'
    ),
    "Banned"=>array(
      'caption' => 'Banned Account',
      'id'    => 4,
      'class' =>'danger'
    )
  );

  foreach( $userstatslist  as $status_name => $status_config)
  {
    echo '
    <div class="col-lg-2 col-md-2" >
      <div class="card card-shadow">
        <div class="card-block p-20 pt-10">
          <div class="clearfix">
            <div class="grey-800 float-left py-10 font-size-20 ">
              <i class="fa-solid fa-user fa-fw grey-600 font-size-20 vertical-align-bottom mr-5"></i>
              <span class="text-uppercase">'.$status_config['caption'].'</span>
            </div>
          </div>
          <div class="mb-20 grey-500  font-size-30">
            <span id="UserStatus_'.$status_config['id'].'">0</span>
          </div>
        </div>
      </div>
    </div>


  ';
  }

$userstatslist = array(
  "Active"=>array(
    'caption' => 'Active Account',
    'id'    => 1,
    'class' =>'success'
  ),
  "Inactive"=>array(
    'caption' => 'Inactive Account',
    'id'    => 2,
    'class' =>'info'
  ),
  "Expired"=>array(
    'caption' => 'Expired Account',
    'id'    => 3,
    'class' =>'warning'
  ),
  "Banned"=>array(
    'caption' => 'Banned Account',
    'id'    => 4,
    'class' =>'danger'
  )
);

$CI->load->model('administrator/m_useraccount');
$usertypestat = $CI->m_useraccount->getusertypelist();
$userclassificationstat = $CI->m_useraccount->getuserclassificationlist();


if( (int) $CI->usertype <= 3 ){ 
?>

<div class="col-xxl-5 col-lg-6">
  <div class="panel" id="projects">
    <div class="panel-heading">
      <div class="grey-800 ml-20 py-10 font-size-20 ">
        <span class="text-uppercase">User Type</span>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-striped" style="font-size: 12px !important">
            <thead>
              <tr class="font-weight-bold">
                <th class="text-uppercase" style="width:40%">Type</th>
                <th class="text-uppercase" style="width:15%;text-align: center;">Active</th>
                <th class="text-uppercase" style="width:15%;text-align: center;">Inactive</th>
                <th class="text-uppercase" style="width:15%;text-align: center;">Expired</th>
                <th class="text-uppercase" style="width:15%;text-align: center;">Banned</th>
                <th class="text-uppercase" style="width:15%;text-align: center;">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $statuslistArray = array('ActiveCnt'=>1,'InactiveCnt'=>2,'ExpiredCnt'=>3,'BannedCnt'=>4);
              foreach($usertypestat as $utrow => $utcol)
              {
                $utLabel = ucwords(strtolower($CI->myutilities->getRef_Desc(2,$utcol['UserType'])));
                echo '
                <tr>
                  <td class="text-uppercase" style="width:40%">'.$utLabel.'</td>
                  ';

                  foreach( $statuslistArray as $ustatus => $ucode )
                  {
                    echo '
                      
                      <td style="width:15%;text-align: center">
                      <a href="javascript:void(0)" onclick="filteruserlist('.$ucode.','.intval($utcol['UserType']).')" style="cursor:pointer;text-decoration:none;">
                        <span utype="'.$utcol['UserType'].'" id="UserType_'.$ustatus.'">'.@$utcol[$ustatus].'</span>
                        </a>
                      </td>
                      ';
                  }

            
                echo '
                  <td style="width:15%;text-align: center">
                    <span utype="'.$utcol['UserType'].'" id="UserType_TotalCount">'.@$utcol['TotalCount'].'</span>
                  </td>
                </tr> 
                ';

                $UserType_TotalActiveCnt =  @$UserType_TotalActiveCnt + $utcol['ActiveCnt'];
                $UserType_TotalInactiveCnt = @$UserType_TotalInactiveCnt + $utcol['InactiveCnt'];
                $UserType_TotalExpiredCnt =  @$UserType_TotalExpiredCnt + $utcol['ExpiredCnt'];
                $UserType_TotalBannedCnt =  @$UserType_TotalBannedCnt + $utcol['BannedCnt'];
                $UserType_TotalTotalCount =  @$UserType_TotalTotalCount + $utcol['TotalCount'];
              }
              ?>       
            </tbody>
            <tfoot>
              <tr class="table-active font-weight-bold">
                <td class="text-uppercase" style="width:40%">Total</td>
                <td style="width:15%;text-align: center"><span id="UserType_Total_ActiveCnt"><?php echo @$UserType_TotalActiveCnt; ?></span></td>
                <td style="width:15%;text-align: center"><span id="UserType_Total_InactiveCnt"><?php echo @$UserType_TotalInactiveCnt; ?></span></td>
                <td style="width:15%;text-align: center"><span id="UserType_Total_ExpiredCnt"><?php echo @$UserType_TotalExpiredCnt; ?></span></td>
                <td style="width:15%;text-align: center"><span id="UserType_Total_BannedCnt"><?php echo @$UserType_TotalBannedCnt; ?></span></td>
                <td style="width:15%;text-align: center"><span id="UserType_Total_TotalCount"><?php echo @$UserType_TotalTotalCount; ?></span></td>
              </tr> 
            </tfoot>
          </table>
    </div>
  </div>
</div>
<?php } ?>
<div class="col-xxl-5 col-lg-6">
  <div class="panel" id="projects">
    <div class="panel-heading">
      <div class="grey-800 ml-20 py-10 font-size-20 ">
        <span class="text-uppercase">User Classification</span>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table" style="font-size: 12px !important">
        <thead>
          <tr class="font-weight-bold">
            <th class="text-uppercase" style="width:40%">Classification</th>
            <th class="text-uppercase" style="width:15%;text-align: center">Active</th>
            <th class="text-uppercase" style="width:15%;text-align: center">Inactive</th>
            <th class="text-uppercase" style="width:15%;text-align: center">Expired</th>
            <th class="text-uppercase" style="width:15%;text-align: center">Banned</th>
            <th class="text-uppercase" style="width:15%;text-align: center">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach($userclassificationstat as $ucrow => $uccol)
          {
            $ucLabel = ucwords(strtolower($CI->myutilities->getRef_Desc(4,$uccol['UserClassification'])));
            echo '
            <tr>
              <td class="text-uppercase" style="width:40%">'.$ucLabel.'</td>
              <td style="width:15%;text-align: center"><span uclassfication="'.$uccol['UserClassification'].'" id="UserClassification_ActiveCnt">'.@$uccol['ActiveCnt'].'</span></td>
              <td style="width:15%;text-align: center"><span uclassfication="'.$uccol['UserClassification'].'" id="UserClassification_InactiveCnt">'.@$uccol['InactiveCnt'].'</span></td>
              <td style="width:15%;text-align: center"><span uclassfication="'.$uccol['UserClassification'].'" id="UserClassification_ExpiredCnt">'.@$uccol['ExpiredCnt'].'</span></td>
              <td style="width:15%;text-align: center"><span uclassfication="'.$uccol['UserClassification'].'" id="UserClassification_BannedCnt">'.@$uccol['BannedCnt'].'</span></td>
              <td style="width:15%;text-align: center"><span uclassfication="'.$uccol['UserClassification'].'" id="UserClassification_TotalCount">'.@$uccol['TotalCount'].'</span></td>
            </tr> 
            ';

            $UserClassification_TotalActiveCnt =  @$UserClassification_TotalActiveCnt + $uccol['ActiveCnt'];
            $UserClassification_TotalInactiveCnt = @$UserClassification_TotalInactiveCnt + $uccol['InactiveCnt'];
            $UserClassification_TotalExpiredCnt =  @$UserClassification_TotalExpiredCnt + $uccol['ExpiredCnt'];
            $UserClassification_TotalBannedCnt =  @$UserClassification_TotalBannedCnt + $uccol['BannedCnt'];
            $UserClassification_TotalTotalCount =  @$UserClassification_TotalTotalCount + $uccol['TotalCount'];

          }
          ?>  

        </tbody>
        <tfoot>
          <tr class="table-active font-weight-bold">
            <td  class="text-uppercase" style="width:40%">Total</td>
            <td style="width:15%;text-align: center"><span id="UserClassification_Total_ActiveCnt"><?php echo @$UserClassification_TotalActiveCnt; ?></span></td>
            <td style="width:15%;text-align: center"><span id="UserClassification_Total_InactiveCnt"><?php echo @$UserClassification_TotalInactiveCnt; ?></span></td>
            <td style="width:15%;text-align: center"><span id="UserClassification_Total_ExpiredCnt"><?php echo @$UserClassification_TotalExpiredCnt; ?></span></td>
            <td style="width:15%;text-align: center"><span id="UserClassification_Total_BannedCnt"><?php echo @$UserClassification_TotalBannedCnt; ?></span></td>
            <td style="width:15%;text-align: center"><span id="UserClassification_Total_TotalCount"><?php echo @$UserClassification_TotalTotalCount; ?></span></td>
          </tr> 
        </tfoot>
      </table>
    </div>
  </div>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
function getUserInfo()
{
  var target_url = site_url+'administrator/index/getusersinfo';
  var PostParam = new FormData();
  var rpResult = function(pp,rp){
    if(rp.Status == 1 )
    {
      rStats = rp.Message;
      for(var cField in rStats)
      {
        var v = parseInt(rStats[cField]['Total']);
        $("span[id='UserStatus_"+parseInt(rStats[cField]['Status'])+"']").html(numberWithCommas(v));
      }
    }
    setTimeout(function(){
      getUserInfo();
    },5000);
  };

  submitFormData('json','',target_url,PostParam,rpResult,false);
}

$(document).ready(function(){
  getUserInfo();
});

</script>
