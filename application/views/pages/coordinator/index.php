<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$DataTableConfig =  array(
    'ajaxdatasource'  =>site_url('coordinator/index/datalist'),
    'tableid'         =>'coordinatorTblList',
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
                              'fieldid'            => 'DataID',
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
                              'fieldid'            => 'HealthFacilityCode',
                              'fieldlabel'         => 'Health Facility',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => (!in_array((int) $CI->userclassification,$CI->m_general->HFUsers)) ? true : false,
                              'advancesearch'      => (!in_array((int) $CI->userclassification,$CI->m_general->HFUsers)) ? true : false,
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
                              'fieldid'            => 'CompleteName',
                              'fieldlabel'         => 'Complete Name',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '70px',
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
                              'fieldid'            => 'emailaddress',
                              'fieldlabel'         => 'Email',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '70px',
                              'className'          => 'font-size-16',
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
                              'fieldid'            => 'Mobile_no',
                              'fieldlabel'         => 'Mobile No',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '50px',
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
                              'fieldid'            => 'user_classification',
                              'fieldlabel'         => 'Classification',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '100px',
                              'className'          => 'font-size-16 text-uppercase',
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
                              'fieldid'            => 'primarycondition_access',
                              'fieldlabel'         => 'Z-Ben Services',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => (in_array((int) $CI->userclassification,$CI->m_general->HFUsers)) ? true : false,
                              'advancesearch'      => (in_array((int) $CI->userclassification,$CI->m_general->HFUsers)) ? true : false,
                              'orderable'          => (in_array((int) $CI->userclassification,$CI->m_general->HFUsers)) ? true : false,
                              'width'              => '240px',
                              'className'          => 'font-size-16 text-uppercase',
                              'visible'            => (in_array((int) $CI->userclassification,$CI->m_general->HFUsers)) ? true : false,
                              'exportable'         => (in_array((int) $CI->userclassification,$CI->m_general->HFUsers)) ? true : false,
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

                            


                          ),
    'scrollx'           =>false,
    'scrollpagination'  =>false,
    'actiobbuttons'     =>@$actiobbuttons,
    'fixedcolumns'      =>array("leftColumns"=>1,"rightColumns"=>0), 
    'callbackjsfunction'=>'claimsStats',
    'bordercolor'       =>'template',
    'headerclass'       =>'text-success font-weight-bold',

  );

?>

<div id="" class="row">
  <div class="col-sm-12 col-lg-12 ">
    <?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
  </div>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

$(document).ready(function(){

  var DTTableID = '<?php echo $DataTableConfig['tableid']; ?>';
  oTable_Obj[DTTableID].on( 'draw.dt', function (e,settings){ 
    $("i[group='"+DTTableID+"']").click(function(){
        window.location.href=site_url+'coordinator/index/view/'+$(this).attr('id');
    });
  });

});
</script>
