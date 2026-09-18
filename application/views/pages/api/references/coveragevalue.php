<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$DataTableConfig =  array(
        'ajaxdatasource'  =>site_url('reference/coveragevalue/datalist'),
        'tableid'         =>'ref_list',
        'dbtable'         =>'',
        'dbtableprimary'  =>'',
        'title'           =>'',
        'defaultorder'    =>array([1,'asc']),
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
                                  'fieldid'            => 'code',
                                  'fieldlabel'         => 'CODE',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => false,
                                  'advancesearch'      => false,
                                  'orderable'          => true,
                                  'width'              => '50%',
                                  'className'          => 'font-size-10 text-uppercase align-middle',
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
                                  'fieldid'            => 'display',
                                  'fieldlabel'         => 'DISPLAY',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => true,
                                  'advancesearch'      => true,
                                  'orderable'          => false,
                                  'width'              => '50',
                                  'className'          => 'font-size-10 text-uppercase align-middle  text-left',
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

                                
                              ),
        'autoscrollx'       =>false,
        'scrollx'           =>true,
        'scrollpagination'  =>false,
        'actiobbuttons'     =>@$actionButtons, // html content
        'fixedcolumns'      =>array("leftColumns"=>0,"rightColumns"=>0), 
        'callbackjsfunction'=>'',
        'bordercolor'       =>'headercolor',
        'pushparameter'     =>[]
      );


?>


<div>
<?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
</div>
<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

</script>