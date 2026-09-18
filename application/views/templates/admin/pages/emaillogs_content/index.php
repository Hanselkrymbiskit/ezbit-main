<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$DataTableConfig =  array(
    'ajaxdatasource'  =>site_url('administrator/emaillogs/emailloglist'),
    'tableid'         =>'system_mailsender_log',
    'dbtable'         =>'',
    'dbtableprimary'  =>'',
    'title'           =>'List',
    'defaultorder'    =>array(array(3,'desc')),
    'dataexport'      =>true,
    'tablecondensed'  =>false,
    'bactionscolumn'  =>array(
                          "enable"        =>true,
                          "add"           =>false,
                          "addmethod"     =>'', // js function call
                          "view"          =>false,
                          "viewmethod"    =>site_url('administrator/registered/details'), // js function call
                          "edit"          =>false,
                          "editmethod"    =>'alert', // js function call
                          "delete"        =>false,
                          "deletemethod"  =>'', // js function call
                          "checkbox"      =>false,
                          "indexnumber"   =>true,
                      ),
    'column'          =>array(
                            // array(
                            //   'fieldid'            => 'DataID',
                            //   'fieldlabel'         => '',
                            //   'fieldtype'          => 'text', // text,date,time,datetime,numeric
                            //   'base64_encrypted'   => false,
                            //   'searchable'         => false,
                            //   'advancesearch'      => false,
                            //   'orderable'          => false,
                            //   'width'              => '5',
                            //   'className'          => '',
                            //   'visible'            => true,
                            //   'exportable'         => true,
                            //   'inputdisplay'       => 'input',
                            //   'inputcustomattr'    => '',
                            //   'inputcustomclass'   => '',
                            //   'referenceid'        => '',
                            //   'referencefilter'    => '',
                            //   'referenceorder'     => '',
                            //   'parentfieldid'      => '',
                            //   'childfieldid'       => '',
                            //   'childfieldfilter'   => '',

                            // ),
                            array(
                              'fieldid'            => 'sToEmail',
                              'fieldlabel'         => 'To [Recipientszsxs]',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '20',
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
                              'fieldid'            => 'sSubject',
                              'fieldlabel'         => 'Subject',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '40',
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
                              'fieldid'            => 'DateTimeCreated',
                              'fieldlabel'         => 'Created Date/Time',
                              'fieldtype'          => 'datetime', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '10',
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
                              'fieldid'            => 'DateTimeSend',
                              'fieldlabel'         => 'Send Date/Time',
                              'fieldtype'          => 'datetime', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => false,
                              'orderable'          => true,
                              'width'              => '10',
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
                              'fieldid'            => 'MailStatus',
                              'fieldlabel'         => 'Status',
                              'fieldtype'          => 'text', // text,date,time,datetime,numeric
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'     => true,
                              'orderable'          => false,
                              'width'              => '5',
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
                          ),
    'scrollx'           =>false,
    'scrollpagination'  =>false,
    'actiobbuttons'     =>'', // html content
    'fixedcolumns'      =>array("leftColumns"=>0,"rightColumns"=>0), 
    'callbackjsfunction'=>'c_emaillogstats',
    'bordercolor'       =>'secondary', // white, success, warning, info,danger,secondary,dark,primary
  );

$emailstat = array(
    "PENDING"=>array(
      'caption' => 'Pending Mail',
      'id'    => 0,
      'class' =>'warning'
    ),
    "SUCCESS"=>array(
      'caption' => 'Successful Mail',
      'id'    => 2,
      'class' =>'success'
    ),
    
    "FAILED"=>array(
      'caption' => 'Failed Mail',
      'id'    => 1,
      'class' =>'danger'
    ),
    "TOTAL"=>array(
      'caption' => 'Total Mail',
      'id'    => 4,
      'class' =>'secondary'
    )
  );

?>

<div class="row mb-10" >
  <?php
  foreach( $emailstat  as $emailstatkeys => $emailstat_config)
  {
    echo '
  
    <div emailStatBox="'.$emailstat_config['id'].'" class="col-sm-2 col-lg-3" style="cursor:pointer"> 
      <div class="card mb-0 border border-'.$emailstat_config['class'].'">
        <div class="card-block">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-uppercase">
                  '.$emailstat_config['caption'].'
                </div>
                <div class="h4 font-weight-bold text-gray-800"><span id="MailLogs_'.strtolower($emailstatkeys).'">0</span></div>
              </div>
              <div class="col-auto">
                <i  title="'.$emailstat_config['caption'].'" class="fa fa-users h3"></i>
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

var c_emaillogstats = function(dtresult){
 if( typeof dtresult == 'object')
  {
    if( dtresult.hasOwnProperty('MailLogs') )
    {
      var rStats = dtresult.MailLogs;
      for(var cField in rStats)
      {
        var v = parseInt(rStats[cField]);
        $("span[id='MailLogs_"+cField.toLowerCase()+"']").html(numberWithCommas(v));
      }
    } 
  }
};

$(document).ready(function(){

  $("div[emailStatBox]").each(function(){
    $(this).click(function(){
      var tableID = "DTTable_<?php echo $DataTableConfig['tableid']; ?>";
      $("table[id='"+tableID+"']").data('filterParam','');

      if($(this).attr('emailStatBox') !== 4 )
      {
        var filterstatus = $(this).attr('emailStatBox');
        if(filterstatus)
        {
          var EmailListFilter = new Object();
          EmailListFilter['addedFilter'] = new Object();
          EmailListFilter['addedFilter']['MailStatus'] = filterstatus;
          $("table[id='"+tableID+"']").data('filterParam',EmailListFilter);
        }
      }
      
      oTable_Obj["<?php echo $DataTableConfig['tableid']; ?>"].search('').draw();  

    });
  });


  $("button[name*='btnEmailLogsButton']").click(function(){
    switch($(this).attr('id'))
    {
      case "btn_TestMail":
        send_testmail();
      break;

      case "btnResendFailedMail":
        resendFailedMail();
      break;
    }
  });

});  


</script>

