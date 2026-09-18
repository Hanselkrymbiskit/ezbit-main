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
    'ajaxdatasource'  =>site_url('administrator/useractivitylogs/list_activityhistory'),
    'tableid'         =>'user_activityhistorylist',
    'dbtable'         =>'',
    'dbtableprimary'  =>'',
    'title'           =>'Activity History',
    'defaultorder'    =>array(array(3,'desc')),
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
                              'fieldid'          => 'userid',
                              'fieldlabel'       => 'User ID',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'       => false,
                              'advancesearch'      => false,
                              'orderable'      => true,
                              'width'          => '',
                              'className'        => '',
                              'visible'        => false,
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
                              'fieldid'          => 'username',
                              'fieldlabel'       => 'User Name',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'       => false,
                              'advancesearch'      => false,
                              'orderable'      => true,
                              'width'          => '',
                              'className'        => '',
                              'visible'        => true,
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
                              'fieldid'    		   => 'datetime',
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
                              'fieldid'    		   => 'type',
                              'fieldlabel' 		   => 'Page',
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
                            array(
                              'fieldid'    		   => 'action',
                              'fieldlabel' 		   => 'Action',
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
                            // array(
                            //   'fieldid'    		   => 'remarks',
                            //   'fieldlabel' 		   => 'Remarks',
                            //   'fieldtype'          => 'text',
                            //   'base64_encrypted'   => false,
                            //   'searchable'		   => false,
                            //   'advancesearch'      => false,
                            //   'orderable'		   => false,
                            //   'width'    		   => '',
                            //   'className' 		   => '',
                            //   'visible' 		   => true,
                            //   'exportable'         => true,
                            //   'inputdisplay'       => 'text',
                            //   'inputcustomattr'    => '',
                            //   'inputcustomclass'   => '',
                            //   'referenceid'        => '',
                            //   'referencefilter'    => '', 
                            //   'referenceorder'     => '', 
                            //   'parentfieldid'      => '',
                            //   'childfieldid'       => '',
                            //   'childfieldfilter'   => '',
                            //   'parentfieldid'      => '',
                            // ),
                         
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
