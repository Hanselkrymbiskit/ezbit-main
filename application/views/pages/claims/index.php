<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$DataTableConfig =  array(
    'ajaxdatasource'  =>site_url('claims/index/datalist'),
    'tableid'         =>'claimsTblList',
    'dbtable'         =>'',
    'dbtableprimary'  =>'',
    'title'           =>'',
    'defaultorder'    =>array(array(1,'asc')),
    'dataexport'      =>false,
    'tablecondensed'  =>true,
    'bactionscolumn'  =>array(
                          "enable"        =>true,
                          "add"           =>false,
                          "addmethod"     =>'', // js function call
                          "view"          =>true,
                          "viewmethod"    =>'', // js function call
                          "edit"          =>false,
                          "editmethod"    =>'', // js function call
                          "delete"        =>false,
                          "deletemethod"  =>'', // js function call
                          "checkbox"      =>false,
                          "indexnumber"   =>true,
                      ),
    'column'          =>array(
                                                        
                            array(
                              'fieldid'            => 'rowid',
                              'fieldlabel'         => 'PreAuth-Form ID',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '120px',
                              'className'          => 'font-size-16 text-uppercase',
                              'visible'            => false,
                              'exportable'         => false,
                              'inputdisplay'       => 'input',
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
                              'fieldid'            => 'case_no',
                              'fieldlabel'         => 'Pre-Auth Case No',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '100px',
                              'className'          => 'font-size-16 text-uppercase',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'input',
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
                              'fieldid'            => 'preauth_type',
                              'fieldlabel'         => 'Pre-Authorization Type',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '240px',
                              'className'          => 'font-size-16 text-uppercase',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '200',
                              'referencefilter'    => '',
                              'referenceorder'     => '',
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                            ),

                            array(
                              'fieldid'            => 'healthfacility_code',
                              'fieldlabel'         => 'Health Facility',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '240px',
                              'className'          => 'font-size-16 text-uppercase',
                              'visible'            => (!in_array((int) $CI->userclassification,$CI->m_general->HFUsers)) ? true : false,
                              'exportable'         => (!in_array((int) $CI->userclassification,$CI->m_general->HFUsers)) ? true : false,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '104',
                              'referencefilter'    => '',
                              'referenceorder'     => '',
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                            ),

                            array(
                              'fieldid'            => 'submitted_datetime',
                              'fieldlabel'         => 'Submitted Date/Time',
                              'fieldtype'          => 'datetime',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '100px',
                              'className'          => 'font-size-16 text-uppercase text-center',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'date',
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
                              'fieldid'            => 'claims_status',
                              'fieldlabel'         => 'Status',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '100px',
                              'className'          => 'font-size-16 text-uppercase text-center',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '202',
                              'referencefilter'    => '',
                              'referenceorder'     => '',
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                            ),
                            
                            array(
                              'fieldid'            => 'claims_status_datetime',
                              'fieldlabel'         => 'Status Date/Time',
                              'fieldtype'          => 'datetime',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '150px',
                              'className'          => 'font-size-16 text-uppercase text-center',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'date',
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
                              'fieldid'            => 'TAT',
                              'fieldlabel'         => 'T.A.T',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '100px',
                              'className'          => 'font-size-16 text-uppercase text-center',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'input',
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
                              'fieldid'            => 'submitted_datetime',
                              'fieldlabel'         => 'Submitted Date/Time',
                              'fieldtype'          => 'datetime',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '150px',
                              'className'          => 'font-size-16 text-uppercase text-center',
                              'visible'            => false,
                              'exportable'         => false,
                              'inputdisplay'       => 'date',
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
                              'fieldid'            => 'received_datetime',
                              'fieldlabel'         => 'Received Date/Time',
                              'fieldtype'          => 'datetime',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '150px',
                              'className'          => 'font-size-16 text-uppercase text-center',
                              'visible'            => false,
                              'exportable'         => false,
                              'inputdisplay'       => 'date',
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
                              'fieldid'            => 'ad_datetime',
                              'fieldlabel'         => 'Approved/Disapproved Date/Time',
                              'fieldtype'          => 'datetime',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '150px',
                              'className'          => 'font-size-16 text-uppercase text-center',
                              'visible'            => false,
                              'exportable'         => false,
                              'inputdisplay'       => 'date',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '',
                              'referencefilter'    => '',
                              'referenceorder'     => '',
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                            ),

                          ),
    'scrollx'           =>false,
    'scrollpagination'  =>false,
    'actiobbuttons'     =>@$actiobbuttons,
    'fixedcolumns'      =>array("leftColumns"=>1,"rightColumns"=>0), 
    'callbackjsfunction'=>'claimsStats',
    'bordercolor'       =>'template',
    'headerclass'     =>'text-success font-weight-bold',

  );

?>

<div id="" class="row">
  <div class="col-sm-12  col-lg-12">
    <div class="row">
        <div class="col-sm-2  col-lg-2">
          <div class="card mb-0">
            <div class="card-block">
              <h6 class="text-left text-uppercase mt-0 mb-0"><span class="float-left">Submitted : </span><span id="claims_stats_1" class="ml-20 float-right font-weight-bold text-primary">0</span></h6>
            </div>
          </div>
        </div>

        <div class="col-sm-2  col-lg-2">
          <div class="card mb-0">
            <div class="card-block">
              <h6 class="text-left text-uppercase mt-0 mb-0"><span class="float-left">Received : </span><span id="claims_stats_3" class="ml-20 float-right font-weight-bold text-primary">0</span></h6>
            </div>
          </div>
        </div>

        <div class="col-sm-2  col-lg-2">
          <div class="card mb-0">
            <div class="card-block">
              <h6 class="text-left text-uppercase mt-0 mb-0"><span class="float-left">For Compliance : </span><span id="claims_stats_4" class="ml-20 float-right font-weight-bold text-primary">0</span></h6>
            </div>
          </div>
        </div>

        <div class="col-sm-2  col-lg-2">
          <div class="card mb-0">
            <div class="card-block">
              <h6 class="text-left text-uppercase mt-0 mb-0"><span class="float-left">Disapproved : </span><span id="claims_stats_5" class="ml-20 float-right font-weight-bold text-primary">0</span></h6>
            </div>
          </div>
        </div>

        <div class="col-sm-2  col-lg-2">
          <div class="card mb-0">
            <div class="card-block">
              <h6 class="text-left text-uppercase mt-0 mb-0"><span class="float-left">Approved : </span><span id="claims_stats_6" class="ml-20 float-right font-weight-bold text-primary">0</span></h6>
            </div>
          </div>
        </div>

        <div class="col-sm-2  col-lg-2">
          <div class="card mb-0">
            <div class="card-block">
              <h6 class="text-left text-uppercase mt-0 mb-0"><span class="float-left">Total : </span><span id="claims_stats_total" class="ml-20 float-right font-weight-bold text-primary">0</span></h6>
            </div>
          </div>
        </div>

        <div class="col-sm-12  col-lg-12 mt-10">
          <?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
        </div>
      </div>
  </div>
  <div class="col-sm-12  col-lg-12">
    <div class="row">
    <?php
      $getConditionList = $CI->sqlhelper->local->select("ref_primarycondition")->ex_select()->result();
      $utmp = [];
      $ucnt = 0;
      foreach($getConditionList['Data'] as $cl_r => $cl_c)
      {
        $ucnt++;
        $utmp[] = '<li class="refbtn-list list-group-item bg-transparent pt-0 pb-0"><a href="#" class="font-size-14">- '.$cl_c['display'].'<span id="claims_stats_'.$cl_c['code'].'" class="text-primary float-right mr-10">0</span></a></li>';

        if(count($utmp) == 5 || count($getConditionList['Data']) == $ucnt)
        {
          echo '
            <div class="col-lg-3 col-sm-3 col-md-3 mt-10">
              <div class="card">
                <div class="card-block" data-mh="preauthorization_type">
                  <ul class="list-group list-group-full mb-0 bg-transparent">
                    <li class="refbtn-list list-group-item bg-transparent pt-0 pb-0 border-bottom font-weight-bold font-size-16">Primary Illness/Condition<span class="text-primary float-right mr-5">#</span></li>
                    '.implode("",$utmp).'
                  </ul>
                </div>
              </div>
            </div>
          ';

          $utmp = [];
        }
        
      }
    ?>
    </div>
  </div>
</div>
<?php if(in_array((int) $CI->userclassification,$CI->m_general->HFUsers)){ ?>
<script nonce="<?php echo $CI->nonceV; ?>" src="<?php echo getjsPath().'views/pages/claims/index.js'; ?>"></script>
<?php } ?>
<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

var claimsStats = function(result){
  if( typeof result == 'object')
  {
    if( result.hasOwnProperty('ClaimsStatus') )
    {
      var PreAuthStatus = result.PreAuthStatus;
      for(var pstat in PreAuthStatus)
      {
        var v = parseInt(PreAuthStatus[pstat]);
        $("span[id='claims_stats_"+pstat+"']").html(numberWithCommas(v));
      }
    } 

    if( result.hasOwnProperty('ClaimsPrimaryStats') )
    {
      var PrimaryStats = result.PreAuthPrimaryStats;
      for(var pstat in PrimaryStats)
      {
        var v = parseInt(PrimaryStats[pstat]);
        $("span[id='claims_stats_"+pstat+"']").html(numberWithCommas(v));
      }
    } 

  }
}

$(document).ready(function(){

  var DTTableID = '<?php echo $DataTableConfig['tableid']; ?>';
  oTable_Obj[DTTableID].on( 'draw.dt', function (e,settings){ 
    $("i[group='"+DTTableID+"']").click(function(){
        window.location.href=site_url+'claims/index/view/'+$(this).attr('id');
    });
  });

  <?php
  // ZPAMS-FIX (2026-09): this hardcoded == 1 excluded classifications 6 (Z Benefits
  // Coordinator) and 7 (Executive Director/Chief of Hospital/Medical Director/Medical
  // Center Chief) from the click handler that opens "New Z-Benefit" -- even though the
  // backend (requestnewform()/newclaims() in claims/Index.php) already authorizes all
  // three via $CI->m_general->HFUsers = [1,6,7]. Matching that list here is what fixes
  // "New Z-Benefit does nothing" for those roles.
  if( in_array((int) $CI->userclassification, $CI->m_general->HFUsers) ){ ?>
  $("button[name='btn_claimsaction']").click(function(){
      switch($(this).attr('id'))
      { 
        case 'claims_new':
          new_claims();
        break;
      }
  });
  <?php } ?>
  
  
});
</script>
