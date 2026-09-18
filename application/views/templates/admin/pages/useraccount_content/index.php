<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
$CI =& get_instance();
$CI->load->model('administrator/m_useraccount');
//
$DataTableConfig =  array(
    'ajaxdatasource'  =>site_url('administrator/useraccount/userlist'),
    'tableid'         =>'useraccountList',
    'dbtable'         =>'',
    'dbtableprimary'  =>'',
    'title'           =>'',
    'defaultorder'    =>array(array(1,'asc')),
    'dataexport'      =>true,
    'tablecondensed'  =>false,
    'bactionscolumn'  =>array(
                          "enable"        =>true,
                          "add"           =>true,
                          "addmethod"     =>site_url('administrator/useraccount/newaccount'), // js function call
                          "view"          =>( (int) $CI->usertype < 4) ? true : ( ( (int) $CI->userclassification == 3 && $CI->system_module_permission['5'] == 1) ? true : false  ),
                          "viewmethod"    =>site_url('administrator/useraccount/viewdetails'), // js function call
                          "edit"          =>false,
                          "editmethod"    =>'alert', // js function call
                          "delete"        =>false,
                          "deletemethod"  =>'', // js function call
                          "checkbox"      =>false,
                          "indexnumber"   =>true,
                      ),
    'column'          =>array(
                            
                            array(
                              'fieldid'            => 'User_Name',
                              'fieldlabel'         => 'User Name',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '15',
                              'className'  => 'text-uppercase',
                              'visible'            => true,
                              'exportable'         => true,
                              'focusable'          => true,
                              'inputdisplay'       => 'text', 
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '', 
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '', 
                              

                            ),
                            array(
                              'fieldid'    => 'aAccount_Name',
                              'fieldlabel' => 'Account Name',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable' => true,
                              'advancesearch'     => true,
                              'orderable'  => false,
                              'width'    => '23',
                              'className'  => 'text-uppercase',
                              'visible'  => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'text',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                            array(
                              'fieldid'            => 'User_TypeID',
                              'fieldlabel'         => 'User Type',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => ( (int) $CI->usertype < 4) ? true : false,
                              'advancesearch'      => ( (int) $CI->usertype < 4) ? true : false,
                              'orderable'          => false,
                              'width'              => '15',
                              'className'  => 'text-uppercase',
                              'visible'            => ( (int) $CI->usertype < 4) ? true : false,
                              'exportable'         => ( (int) $CI->usertype < 4) ? true : false,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '2',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                            array(
                              'fieldid'    => 'User_EmailAddress',
                              'fieldlabel' => 'EMAIL ADDRESS',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable' => true,
                              'advancesearch'     => true,
                              'orderable'  => false,
                              'width'    => '',
                              'className'  => '',
                              'visible'  => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'text',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                            array(
                              'fieldid'            => 'User_ClassificationID',
                              'fieldlabel'         => 'Classification',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'     => true,
                              'orderable'          => false,
                              'width'              => '15',
                              'className'  => 'text-uppercase',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '4',
                              'referencefilter'    => ( (int) $CI->usertype < 4) ? "" : "Code in (4,5,6,7)", 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                          
                            array(
                              'fieldid'    => 'User_Expiration',
                              'fieldlabel' => 'Expiration Date',
                              'fieldtype'          => 'date',
                              'base64_encrypted'   => false,
                              'searchable' => false,
                              'advancesearch'     => false,
                              'orderable'  => false,
                              'width'    => '',
                              'className'  => 'text-uppercase',
                              'visible'  => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'text',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                            array(
                              'fieldid'            => 'User_Status',
                              'fieldlabel'         => 'Status',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '5',
                              'className'  => 'text-uppercase',
                              'visible'            => true,
                              'exportable'         => true,
                              'focusable'          => false,
                              'inputdisplay'       => 'text',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                            
                          ),
    'scrollx'           =>false,
    'scrollpagination'  =>false,
    'actiobbuttons'     =>'', // html content
    'fixedcolumns'      =>array("leftColumns"=>0,"rightColumns"=>0), 
    'callbackjsfunction'=>'c_uastats',
    'bordercolor'       =>'secondary', // white, success, warning, info,danger,secondary,dark,primary
  );

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
?>
<div class="row mb-10" >
  <?php
  foreach( $userstatslist  as $status_name => $status_config)
  {
    echo '
  <div class="col-sm-2 col-lg-3">
    <div class="card mb-0 border border-'.$status_config['class'].'">
      <div class="card-block">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-uppercase">
                '.$status_config['caption'].'
              </div>
              <div class="h4 font-weight-bold text-gray-800"><span id="UserStatus_'.$status_config['id'].'">0</span></div>
            </div>
            <div class="col-auto">
              <i  title="'.$status_name.' Account" class="fa fa-users h3"></i>
            </div>
          </div>            
      </div>
    </div>
  </div>  
  ';
  }
  ?>
</div>
<!-- DataTables Start -->
<div class="row mb-10">
  <div class="col-lg ">  
    <?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
  </div>
</div>
<!-- DataTables End -->
<div class="row">
  <?php
    
    $usertypestat = $CI->m_useraccount->getusertypelist();
    $userclassificationstat = $CI->m_useraccount->getuserclassificationlist();
   
  ?>

  <?php if( (int) $CI->usertype <= 3 ){ ?>
  <div class="col-sm-6">
    <div class="card card-block border border-secondary">
      <h5 class="card-title">User Type</h5>
      <div class="table-responsive"  data-mh="usersGrouping">
        
        <table class="table table-sm" style="font-size: 12px !important">
          <thead>
            <tr class="font-weight-bold">
              <th style="width:40%">Type</th>
              <th style="width:15%;text-align: center">Active</th>
              <th style="width:15%;text-align: center">Inactive</th>
              <th style="width:15%;text-align: center">Expired</th>
              <th style="width:15%;text-align: center">Banned</th>
              <th style="width:15%;text-align: center">Total</th>
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
                <td style="width:40%">'.$utLabel.'</td>
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
              <td style="width:40%">Total</td>
              <td style="width:15%;text-align: center"><span id="UserType_Total_ActiveCnt"><?php echo @$UserType_TotalActiveCnt; ?></span></td>
              <td style="width:15%;text-align: center"><span id="UserType_Total_InactiveCnt"><?php echo @$UserType_TotalInactiveCnt; ?></span></td>
              <td style="width:15%;text-align: center"><span id="UserType_Total_ExpiredCnt"><?php echo @$UserType_TotalExpiredCnt; ?></span></td>
              <td style="width:15%;text-align: center"><span id="UserType_Total_BannedCnt"><?php echo @$UserType_TotalBannedCnt; ?></span></td>
              <td style="width:15%;text-align: center"><span id="UserType_Total_TotalCount"><?php echo @$UserType_TotalTotalCount; ?></span></td>
            </tr> 
          </tfoot>
        </table>
      </div>
      <p class="card-text">
        <small class="text-muted"><!-- Last updated 3 mins ago --></small>
      </p>
    </div>
  </div>
  <?php } ?>
  <div class="col-sm-<?php echo ((int) $CI->userclassification == 3) ? 12 : 6; ?>">
    <div class="card card-block border border-secondary">
      <h5 class="card-title">User Classification</h5>
      <div class="table-responsive" data-mh="usersGrouping">
        <table class="table table-sm" style="font-size: 12px !important">
            <thead>
              <tr class="font-weight-bold">
                <th style="width:40%">Classification</th>
                <th style="width:15%;text-align: center">Active</th>
                <th style="width:15%;text-align: center">Inactive</th>
                <th style="width:15%;text-align: center">Expired</th>
                <th style="width:15%;text-align: center">Banned</th>
                <th style="width:15%;text-align: center">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($userclassificationstat as $ucrow => $uccol)
              {
                $ucLabel = ucwords(strtolower($CI->myutilities->getRef_Desc(4,$uccol['UserClassification'])));
                echo '
                <tr>
                  <td style="width:40%">'.$ucLabel.'</td>
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
                <td style="width:40%">Total</td>
                <td style="width:15%;text-align: center"><span id="UserClassification_Total_ActiveCnt"><?php echo @$UserClassification_TotalActiveCnt; ?></span></td>
                <td style="width:15%;text-align: center"><span id="UserClassification_Total_InactiveCnt"><?php echo @$UserClassification_TotalInactiveCnt; ?></span></td>
                <td style="width:15%;text-align: center"><span id="UserClassification_Total_ExpiredCnt"><?php echo @$UserClassification_TotalExpiredCnt; ?></span></td>
                <td style="width:15%;text-align: center"><span id="UserClassification_Total_BannedCnt"><?php echo @$UserClassification_TotalBannedCnt; ?></span></td>
                <td style="width:15%;text-align: center"><span id="UserClassification_Total_TotalCount"><?php echo @$UserClassification_TotalTotalCount; ?></span></td>
              </tr> 
            </tfoot>
          </table>
      </div>
      <p class="card-text">
        <small class="text-muted"><!-- Last updated 3 mins ago --></small>
      </p>
    </div>
  </div>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

var unmin = '<?php echo $CI->system_settings['UsernameLength_Min']; ?>';
var unmax = '<?php echo $CI->system_settings['UsernameLength_Max']; ?>';
var passmin = '<?php echo $CI->system_settings['PasswordLength_Min']; ?>';
var passmax = '<?php echo $CI->system_settings['PasswordLength_Max']; ?>';


var c_uastats = function(dtresult){

  if( typeof dtresult == 'object')
  {
    if( dtresult.hasOwnProperty('UserStats') )
    {    
      var rStats = dtresult.UserStats;
      for(var cField in rStats)
      {
        var v = parseInt(rStats[cField]['Total']);
        $("span[id='UserStatus_"+parseInt(rStats[cField]['Status'])+"']").html(numberWithCommas(v));
      }
    } 
  }

};


$(document).ready(function(){

});  


</script>
