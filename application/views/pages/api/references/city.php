<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$DataTableConfig =  array(
        'ajaxdatasource'  =>site_url('reference/city/datalist'),
        'tableid'         =>'ref_list',
        'dbtable'         =>'',
        'dbtableprimary'  =>'',
        'title'           =>'',
        'defaultorder'    =>array([1,'asc'],[2,'asc'],[3,'asc']),
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
                                  'fieldid'            => 'regcode',
                                  'fieldlabel'         => 'REGION CODE',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => false,
                                  'advancesearch'      => false,
                                  'orderable'          => true,
                                  'width'              => '20%',
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
                                  'fieldid'            => 'provcode',
                                  'fieldlabel'         => 'PROVINCE CODE',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => false,
                                  'advancesearch'      => false,
                                  'orderable'          => true,
                                  'width'              => '20%',
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
                                  'fieldid'            => 'citycode',
                                  'fieldlabel'         => 'CITY CODE',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => false,
                                  'advancesearch'      => false,
                                  'orderable'          => true,
                                  'width'              => '20%',
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
                                  'fieldid'            => 'nscb_city_code',
                                  'fieldlabel'         => 'NSCB CITY CODE',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => false,
                                  'advancesearch'      => false,
                                  'orderable'          => true,
                                  'width'              => '20%',
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
                                  'fieldid'            => 'cityname',
                                  'fieldlabel'         => 'CITY / MUNICIPALITY NAME',
                                  'fieldtype'          => 'text',
                                  'base64_encrypted'   => false,
                                  'searchable'         => true,
                                  'advancesearch'      => true,
                                  'orderable'          => false,
                                  'width'              => '20%',
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

                              
                                
                              ),
        'autoscrollx'       =>false,
        'scrollx'           =>true,
        'scrollpagination'  =>false,
        'actiobbuttons'     =>@$actionButtons, // html content
        'fixedcolumns'      =>array("leftColumns"=>0,"rightColumns"=>0), 
        'callbackjsfunction'=>'',
        'bordercolor'       =>'headercolor',
        'pushparameter'     => []
      );


?>


<div>
<?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
</div>
<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

</script>