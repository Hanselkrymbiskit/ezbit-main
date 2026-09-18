<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$DataTableConfig =  array(
        'ajaxdatasource'  =>site_url('api/logs/datalist'),
        'tableid'         =>'organization_List',
        'dbtable'         =>'',
        'dbtableprimary'  =>'',
        'title'           =>'',
        'defaultorder'    =>array([4,'desc'],[5,'desc']),
        'dataexport'      =>true,
        'tablecondensed'  =>false,
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
                                  'fieldid'            => 'DataCount',
                                  'fieldlabel'         => 'LOG ID',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => false,
                                  'advancesearch'      => false,
                                  'orderable'          => false,
                                  'width'              => '5',
                                  'className'          => 'font-size-10 text-uppercase align-middle',
                                  'visible'            => false,
                                  'exportable'         => false,
                                  'inputdisplay'       => 'input',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),

                                array(
                                  'fieldid'            => 'method',
                                  'fieldlabel'         => 'METHOD',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => true,
                                  'advancesearch'      => true,
                                  'orderable'          => false,
                                  'width'              => '40px',
                                  'className'          => 'font-size-10 text-uppercase align-middle  text-center',
                                  'visible'            => true,
                                  'exportable'         => true,
                                  'inputdisplay'       => 'text',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),
                                array(
                                  'fieldid'            => 'uri',
                                  'fieldlabel'         => 'URI',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => true,
                                  'advancesearch'      => true,
                                  'orderable'          => false,
                                  'width'              => '40px',
                                  'className'          => 'font-size-10 text-uppercase align-middle  text-center',
                                  'visible'            => true,
                                  'exportable'         => true,
                                  'inputdisplay'       => 'text',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),
                                array(
                                  'fieldid'            => 'logDate',
                                  'fieldlabel'         => 'LOG DATE',
                                  'fieldtype'          => 'date',
                                  'base64_encrypted'   => false,
                                  'searchable'         => true,
                                  'advancesearch'      => true,
                                  'orderable'          => true,
                                  'width'              => '40px',
                                  'className'          => 'font-size-10 text-uppercase align-middle  text-center',
                                  'visible'            => true,
                                  'exportable'         => true,
                                  'inputdisplay'       => 'daterange',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),
                                array(
                                  'fieldid'            => 'logTime',
                                  'fieldlabel'         => 'LOG TIME',
                                  'fieldtype'          => 'time',
                                  'base64_encrypted'   => false,
                                  'searchable'         => true,
                                  'advancesearch'      => true,
                                  'orderable'          => true,
                                  'width'              => '40px',
                                  'className'          => 'font-size-10 text-uppercase align-middle  text-center',
                                  'visible'            => true,
                                  'exportable'         => true,
                                  'inputdisplay'       => 'timerange',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),

                                array(
                                  'fieldid'            => 'rtime',
                                  'fieldlabel'         => 'RESPONSE TIME',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => true,
                                  'advancesearch'      => true,
                                  'orderable'          => false,
                                  'width'              => '40px',
                                  'className'          => 'font-size-10 text-uppercase align-middle  text-center',
                                  'visible'            => true,
                                  'exportable'         => true,
                                  'inputdisplay'       => 'input',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),

                                array(
                                  'fieldid'            => 'response_code',
                                  'fieldlabel'         => 'RESPONSE CODE',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => true,
                                  'advancesearch'      => true,
                                  'orderable'          => false,
                                  'width'              => '40px',
                                  'className'          => 'font-size-10 text-uppercase align-middle  text-center',
                                  'visible'            => true,
                                  'exportable'         => true,
                                  'inputdisplay'       => 'input',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
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
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => true,
                                  'advancesearch'      => true,
                                  'orderable'          => false,
                                  'width'              => '200px',
                                  'className'          => 'font-size-10 text-uppercase align-middle',
                                  'visible'            => true,
                                  'exportable'         => true,
                                  'inputdisplay'       => 'text',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),

                                array(
                                  'fieldid'            => 'FacilityCode',
                                  'fieldlabel'         => 'FACILITY CODE',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => true,
                                  'advancesearch'      => true,
                                  'orderable'          => false,
                                  'width'              => '200px',
                                  'className'          => 'font-size-10 text-uppercase align-middle',
                                  'visible'            => true,
                                  'exportable'         => true,
                                  'inputdisplay'       => 'text',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),


                                array(
                                  'fieldid'            => 'organizationid',
                                  'fieldlabel'         => 'ORGANIZATION RESOURCE ID',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => true,
                                  'advancesearch'      => true,
                                  'orderable'          => false,
                                  'width'              => '40px',
                                  'className'          => 'font-size-10 text-uppercase align-middle  text-center',
                                  'visible'            => true,
                                  'exportable'         => true,
                                  'inputdisplay'       => 'text',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),

                                array(
                                  'fieldid'            => 'params',
                                  'fieldlabel'         => 'REQUEST PAYLOAD',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => false,
                                  'advancesearch'      => false,
                                  'orderable'          => false,
                                  'width'              => '40px',
                                  'className'          => 'font-size-10 text-uppercase align-middle  text-center',
                                  'visible'            => false,
                                  'exportable'         => false,
                                  'inputdisplay'       => 'text',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),
                                array(
                                  'fieldid'            => 'responseparams',
                                  'fieldlabel'         => 'RESPONSE PAYLOAD',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => false,
                                  'advancesearch'      => false,
                                  'orderable'          => false,
                                  'width'              => '40px',
                                  'className'          => 'font-size-10 text-uppercase align-middle  text-center',
                                  'visible'            => false,
                                  'exportable'         => false,
                                  'inputdisplay'       => 'text',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),
                                array(
                                  'fieldid'            => 'ip_address',
                                  'fieldlabel'         => 'IP ADDRESS',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => false,
                                  'advancesearch'      => false,
                                  'orderable'          => false,
                                  'width'              => '40px',
                                  'className'          => 'font-size-10 text-uppercase align-middle  text-center',
                                  'visible'            => false,
                                  'exportable'         => false,
                                  'inputdisplay'       => 'text',
                                  'inputcustomattr'    => '',
                                  'inputcustomclass'   => 'text-uppercase',
                                  'referenceid'        => '',
                                  'referencefilter'    => '',
                                  'referenceorder'     => '',
                                  'parentfieldid'      => '',
                                  'childfieldid'       => '',
                                  'childfieldfilter'   => '',
                                ),

                              ),
        'autoscrollx'       =>false,
        'scrollx'           =>true,
        'scrollpagination'  =>false,
        'actiobbuttons'     =>@$actionButtons, // html content
        'fixedcolumns'      =>array("leftColumns"=>0,"rightColumns"=>0), 
        'callbackjsfunction'=>'',
        'bordercolor'       =>'headercolor',
        'pushparameter'     =>['Mode' => 1]
      );

$HTTPMethod = [
  'POST'    => 'POST',
  'GET'     => 'GET',
  'OPTIONS' => 'OPTIONS',
  'PUT'     => 'PUT',
  'PATCH'   => 'PATCH',
  'DELETE'  => 'DELETE',
];

$httpmethod_tblhead = ''; $httpmethod_tblbody = '';
foreach($HTTPMethod as $httpmethodcode => $httpmethoddesc)
{
  $httpmethod_tblhead .= '<th class="text-center font-weight-bold" style="width:'.(100/count($HTTPMethod)).'%;">'.$httpmethoddesc.'</label></th>';
  $httpmethod_tblbody .= '<td class="text-center font-weight-bold"><span class="font-size-14" id="method_'.$httpmethodcode.'">0</span></td>';
}

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

$httpcode_tblhead = ''; $httpcode_tblbody = '';
foreach($HTTPCode as $httpcode => $httpdesc)
{
  $httpcode_tblhead .= '<th class="text-center font-weight-bold w-p'.(100/count($HTTPCode)).'">'.$httpcode.'<br><label class="small">'.$httpdesc.'</label></th>';
  $httpcode_tblbody .= '<td class="text-center font-weight-bold"><span class="font-size-14" id="http_'.$httpcode.'">0</span></td>';
}

?>

<div class="card border-headercolor mb-10" style="">
  <div class="card-body">
    <h6 class="mt-0">API : METHOD</h6>
    <table class="table table-sm table-bordered mb-0">
      <thead>
        <tr>
          <?php echo $httpmethod_tblhead; ?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php echo $httpmethod_tblbody; ?>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="card border-headercolor mb-10" style="">
  <div class="card-body">
    <h6 class="mt-0">API : HTTP RESPONSE CODE</h6>
    <table class="table table-sm table-bordered mb-0">
      <thead>
        <tr>
          <?php echo $httpcode_tblhead; ?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php echo $httpcode_tblbody; ?>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div>
<?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
</div>
<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
console.log('here');
var vlD = function(logid)
{
  console.log(logid);
  var PostResult = function(pp,rp){
    if(rp.Status == 1)
    {
      x = typeof rp.Message !== 'undefined' ? rp.Message : '';

      if(x !== '')
      {
        x = JSON.parse(atob(x));
      }

      var PBox = '';
      PBox += '<div class="card border-success mb-0" style="wdith:90vw;">';
         PBox += '<div class="card-header">';
          PBox += '<table class="table table-sm mb-0">';
            PBox += '<tr>';
              PBox += '<td class="border-0"><label class="font-weight-bold">Method :</label><div class="text-success text-uppercase"><span>'+x.method+'</span></div></td>';
              PBox += '<td colspan="2" class="border-0"><label class="font-weight-bold">URI :</label><div class="text-success"><span>'+x.uri+'</span></div></td>';
              PBox += '<td class="border-0"><label class="font-weight-bold">IP Address :</label><i class="fa fa-remove bootbox-close-button float-right" style="cursor:pointer"></i><div class="text-success text-uppercase"><span>'+x.ip_address+'</span></div></td>';
            PBox += '</tr>';
            PBox += '<tr>';
              PBox += '<td class=""><label class="font-weight-bold">Request Date :</label><div class="text-success"><span>'+x.logDate+'</span></div></td>';
              PBox += '<td class=""><label class="font-weight-bold">Request Time :</label><div class="text-success"><span>'+x.logTime+'</span></div></td>';
              PBox += '<td class=""><label class="font-weight-bold">Response Code :</label><div class="text-success"><span>'+x.rtime+'</span></div></td>';
              PBox += '<td class=""><label class="font-weight-bold">Response Time :</label><div class="text-success"><span>'+x.response_code+'</span></div></td>';
            PBox += '</tr>';
            PBox += '<tr>';
              PBox += '<td colspan="2" class=""><label class="font-weight-bold">Facility Name :</label><div class="text-success  text-uppercase"><span>'+x.FacilityName+'</span></div></td>';
              PBox += '<td class=""><label class="font-weight-bold">Facility Code :</label><div class="text-success"><span>'+x.FacilityCode+'</span></div></td>';
              PBox += '<td class=""><label class="font-weight-bold">Organization Resource ID :</label><div class="text-success"><span>'+x.organizationid+'</span></div></td>';
            PBox += '</tr>';
          PBox += '</table>';
        PBox += '</div>';
        PBox += '<div class="card-body">';
          PBox += '<div class="nav-tabs-horizontal nav-tabs-inverse" data-plugin="tabs">';
            PBox += '<ul class="nav nav-tabs " role="tablist">';
              PBox += '<li class="nav-item" role="presentation">';
                PBox += '<a class="nav-link font-weight-bold text-dark active" data-toggle="tab" href="#payload_Request" aria-controls="payload_Request" role="tab" aria-selected="true">Request Payload</a>';
              PBox += '</li>';
              PBox += '<li class="nav-item" role="presentation">';
              PBox += '  <a class="nav-link font-weight-bold text-dark" data-toggle="tab" href="#payload_Response" aria-controls="payload_Response" role="tab" aria-selected="false">Response Payload</a>';
              PBox += '</li>';
            PBox += '</ul>';
            PBox += '<div class="tab-content p-0">';
              PBox += '<div class="tab-pane active" id="payload_Request" role="tabpanel">';  
                PBox += '<div style="background: #646464; border-radius: 5px;overflow-y:auto;max-height:70vh">';
                  PBox += '<pre class="mb-0 text-white">'+ JSON.stringify(JSON.parse(x.params),null,2)+'</pre>';
                PBox += '</div>';
              PBox += '</div>';
              PBox += '<div class="tab-pane" id="payload_Response" role="tabpanel">';
                PBox += '<div style="background: #646464; border-radius: 5px;overflow-y:auto;max-height:70vh">';
                  PBox += '<pre class="mb-0 text-white">'+ JSON.stringify(JSON.parse(x.responseparams),null,2)+'</pre>';
                PBox += '</div>';
              PBox += '</div>';
            PBox += '</div>';
          PBox += '</div>';
        PBox += '</div>';
      PBox += '</div>';
     
      bootbox.dialog({
        size: 'xl',
        message: PBox,
        closeButton:false,
        onShow: function(e) {
        },
        onShown:function(e){
        },
        onHidden:function(e){
        },  
      });

    }
    else
    {
      Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-danger',
        },
        buttonsStyling: false,
        willOpen: (fnRun) => {
          $(".swal2-container").css('z-index',$.topZIndex());
        }
      }).fire({
        title: "Failed to Process Request!",
        text: rp.Message,
        icon: "error",
        confirmButtonText: 'Close',
      });
    }
  };

  pFormData = new FormData();
  pFormData['logid'] = logid;
  postUrl = site_url+'api/logs/datadetails';
  submitFormData('json','',postUrl,pFormData,PostResult,false,true,0);
}

$(document).ready(function(){ 
  var DTTableID = '<?php echo $DataTableConfig['tableid']; ?>';
  oTable_Obj[DTTableID].on( 'draw.dt', function (e,settings){
    if( typeof oTable_Obj_Return[DTTableID] == 'object')
    {
      if( oTable_Obj_Return[DTTableID].hasOwnProperty('MiniStats') )
      {
        var xms = oTable_Obj_Return[DTTableID].MiniStats;
        for(var st in xms)
        {
          var v = xms[st];
          for(var t in v)
          {
            $("span[id='"+st.toLocaleLowerCase()+"_"+t+"']").html(numberWithCommas(parseInt(v[t])));
          }
        }
      } 
    }
  }).draw();
});
</script>