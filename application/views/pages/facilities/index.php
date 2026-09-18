<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$DataTableConfig =  array(
    'ajaxdatasource'  =>site_url('facilities/index/datalist'),
    'tableid'         =>'FacilitiesTblList',
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
                              'fieldid'            => 'inst_code',
                              'fieldlabel'         => 'Facility Code',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
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
                              'fieldid'            => 'inst_name',
                              'fieldlabel'         => 'Health Facility Name',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '200px',
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
                              'fieldid'            => 'sector',
                              'fieldlabel'         => 'Sector',
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
                              'fieldid'            => 'inst_address_street',
                              'fieldlabel'         => 'Address',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '200px',
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
                              'fieldid'            => 'inst_contactno',
                              'fieldlabel'         => 'Contact Number',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '150px',
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
                              'fieldid'            => 'inst_email',
                              'fieldlabel'         => 'Email Address',
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

  // var DTTableID = '<?php echo $DataTableConfig['tableid']; ?>';
  // oTable_Obj[DTTableID].on( 'draw.dt', function (e,settings){ 
  //   $("i[group='"+DTTableID+"']").click(function(){
  //       window.location.href=site_url+'facilities/index/view/'+$(this).attr('id');
  //   });
  // });

});
</script>
