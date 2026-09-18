<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();
$DataTableConfig =  array(
    'ajaxdatasource'  =>site_url('administrator/systemsecurity/datalist_maxlogin'),
    'tableid'         =>'maxloginTblList',
    'dbtable'         =>'',
    'dbtableprimary'  =>'',
    'title'           =>'',
    'defaultorder'    =>array(array(3,'desc')),
    'dataexport'      =>false,
    'tablecondensed'  =>true,
    'bactionscolumn'  =>array(
                          "enable"        =>true,
                          "add"           =>false,
                          "addmethod"     =>'', // js function call
                          "view"          =>false,
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
                              'fieldid'            => 'mId',
                              'fieldlabel'         => 'Log ID',
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
                              'fieldid'            => 'ip_address',
                              'fieldlabel'         => 'IP Address',
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
                              'fieldid'            => 'login',
                              'fieldlabel'         => 'User Name',
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
                              'fieldid'            => 'mTime',
                              'fieldlabel'         => 'Date / Time',
                              'fieldtype'          => 'date',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '100px',
                              'className'          => 'font-size-16 text-uppercase',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'daterange',
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
    'callbackjsfunction'=>'',
    'bordercolor'       =>'template',
    'headerclass'       =>'text-success font-weight-bold',

  );


?>

<div class="row">
  <div class="col-sm-12  col-lg-12">
    <?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
  </div>
</div>


<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

$(document).ready(function(){


});  


</script>

