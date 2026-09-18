<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

// id
// userid
// datetime
// type
// action
// description
// remarks


$DataTableConfig =  array(
    'ajaxdatasource'  =>site_url('administrator/useraccount/list_statushistory/'.$currentuserid),
    'tableid'         =>'user_statushistorylist',
    'dbtable'         =>'',
    'dbtableprimary'  =>'',
    'title'           =>'Status History',
    'defaultorder'    =>array(array(1,'desc')),
    'dataexport'      =>true,
    'tablecondensed'  =>false,
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
                              'fieldid'    		   => 'created_datetime',
                              'fieldlabel' 		   => 'Date / Time',
                              'fieldtype'          => 'datetime',
                              'base64_encrypted'   => false,
                              'searchable'		   => false,
                              'advancesearch'      => false,
                              'orderable'		   => true,
                              'width'    		   => '',
                              'className' 		   => '',
                              'visible' 		   => true,
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
                              'fieldid'    		   => 'userstatus',
                              'fieldlabel' 		   => 'Status',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'		   => true,
                              'advancesearch'      => true,
                              'orderable'		   => false,
                              'width'    		   => '',
                              'className' 		   => '',
                              'visible' 		   => true,
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
                            
                          ),
    'scrollx'           =>false,
    'scrollpagination'  =>false,
    'actiobbuttons'     =>'', // html content
    'fixedcolumns'      =>array("leftColumns"=>0,"rightColumns"=>0), 
    'callbackjsfunction'=>'',
    'bordercolor'       =>'secondary', 
  );





?>

<div class="">  
    <?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){


});  
</script>
