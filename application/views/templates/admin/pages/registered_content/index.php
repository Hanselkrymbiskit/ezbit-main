<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


$DataTableConfig =  array(
    'ajaxdatasource'  =>site_url('administrator/registered/registeredlist'),
    'tableid'         =>'registered_accountlist',
    'dbtable'         =>'',
    'dbtableprimary'  =>'',
    'title'           =>'List',
    'defaultorder'    =>array(array(2,'desc')),
    'dataexport'      =>true,
    'tablecondensed'  =>false,
    'bactionscolumn'  =>array(
                          "enable"        =>true,
                          "add"           =>false,
                          "addmethod"     =>'', // js function call
                          "view"          =>true,
                          "viewmethod"    =>site_url('administrator/registered/details'), // js function call
                          "edit"          =>false,
                          "editmethod"    =>'alert', // js function call
                          "delete"        =>false,
                          "deletemethod"  =>'', // js function call
                          "checkbox"      =>false,
                          "indexnumber"   =>true,
                      ),
    'column'          =>array(
                            array(
                              'fieldid'            => 'registrationID',
                              'fieldlabel'         => 'REGISTRATION ID',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '100px',
                              'className'          => '',
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
                              'fieldid'            => 'register_status',
                              'fieldlabel'         => 'STATUS',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '100px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '27',
                              'referencefilter'    => '',
                              'referenceorder'     => '',
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                            ),
                            array(
                              'fieldid'            => 'register_datetime',
                              'fieldlabel'         => 'REGISTRATION DATE',
                              'fieldtype'          => 'date', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'     => true,
                              'orderable'          => true,
                              'width'              => '120px',
                              'className'          => '',
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
                              'fieldid'            => 'user_classification',
                              'fieldlabel'         => 'CLASSIFICATION',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '300px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '4',
                              'referencefilter'    => '',
                              'referenceorder'     => '',
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                            ),
                          
                            
                            array(
                              'fieldid'            => 'emailaddress',
                              'fieldlabel'         => 'EMAIL ADDRESS',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '100px',
                              'className'          => '',
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
                              'fieldid'            => 'HealthFacilityCode',
                              'fieldlabel'         => 'FACILITY CODE',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '50px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
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
                            ),

                            array(
                              'fieldid'            => 'FacilityName',
                              'fieldlabel'         => 'FACILITY NAME',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '350px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
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
                            ),

                            array(
                              'fieldid'            => 'Address',
                              'fieldlabel'         => 'ADDRESS',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '350px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
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
                            ),

                            array(
                              'fieldid'            => 'Province',
                              'fieldlabel'         => 'PROVINCE',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '250px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '102',
                              'referencefilter'    => '',
                              'referenceorder'     => '',
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                            ),

                            array(
                              'fieldid'            => 'City',
                              'fieldlabel'         => 'CITY / MUNICIPALITY',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '250px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '103',
                              'referencefilter'    => '',
                              'referenceorder'     => '',
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                            ),

                            array(
                              'fieldid'            => 'aLastname',
                              'fieldlabel'         => 'LAST NAME',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '100px',
                              'className'          => 'text-uppercase',
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
                              'fieldid'            => 'aFirstname',
                              'fieldlabel'         => 'FIRST NAME',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '100px',
                              'className'          => 'text-uppercase',
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
                              'fieldid'            => 'Middlename',
                              'fieldlabel'         => 'MIDDLE NAME',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '100px',
                              'className'          => 'text-uppercase',
                              'visible'            => false,
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
                              'fieldid'            => 'dayscount',
                              'fieldlabel'         => 'DAYS COUNT',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => true,
                              'width'              => '50px',
                              'className'          => 'text-center',
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
                              'fieldid'            => 'AccountActivated',
                              'fieldlabel'         => 'ACCOUNT ACTIVATED',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => true,
                              'width'              => '50px',
                              'className'          => 'text-uppercase text-center',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'input',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '22',
                              'referencefilter'    => '',
                              'referenceorder'     => '',
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                            ),
                          ),
    'scrollx'           =>true,
    'scrollpagination'  =>false,
    'actiobbuttons'     =>'', // html content
    'fixedcolumns'      =>array("leftColumns"=>1,"rightColumns"=>0), 
    'callbackjsfunction'=>'c_registeredstats',
    'bordercolor'       =>'secondary', // white, success, warning, info,danger,secondary,dark,primary
  );

$userstatslist = array(
    "Pending"=>array(
      'caption' => 'Pending Registration',
      'id'    => 1,
      'class' =>'info'
    ),
    "Approved"=>array(
      'caption' => 'Approved Registration',
      'id'    => 2,
      'class' =>'success'
    ),
    "Disapproved"=>array(
      'caption' => 'Disapproved Registration',
      'id'    => 3,
      'class' =>'danger'
    ),
    "Total"=>array(
      'caption' => 'Total Registered',
      'id'    => 4,
      'class' =>'secondary'
    )
  );
?>
<div class="row mb-10" >
  <?php
  foreach( $userstatslist  as $status_name => $status_config)
  {
    echo '
  
    <div registartStatBox="'.$status_config['id'].'" class="col-sm-2 col-lg-3" style="cursor:pointer"> 
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
  
<div class="row">
  <div class="col-lg ">  
    <?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
  </div>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

var c_registeredstats = function(dtresult){

  if( typeof dtresult == 'object')
  {
    if( dtresult.hasOwnProperty('RegistrationStat') )
    {
      var rStats = dtresult.RegistrationStat;
      for(var cField in rStats)
      {
        var v = parseInt(rStats[cField]);
        $("span[id='UserStatus_"+parseInt(cField)+"']").html(numberWithCommas(v));
      }
    } 
  }

};

$(document).ready(function(){

  $("div[registartStatBox]").each(function(){
    $(this).click(function(){
      var tableID = "DTTable_<?php echo $DataTableConfig['tableid']; ?>";
      $("table[id='"+tableID+"']").data('filterParam','');
      var filterstatus = ($(this).attr('registartStatBox') !== 4 ) ? $(this).attr('registartStatBox') : '';
      if(filterstatus)
      {
        var RegListFilter = new Object();
        RegListFilter['addedFilter'] = new Object();
        RegListFilter['addedFilter']['register_status'] = filterstatus;
        $("table[id='"+tableID+"']").data('filterParam',RegListFilter);
      }

      oTable_Obj["<?php echo $DataTableConfig['tableid']; ?>"].search('').draw();  
    });
  });

});

</script>

