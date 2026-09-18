<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$DataTableConfig =  array(
    'ajaxdatasource'  =>site_url('faq/faq_List'),
    'tableid'         =>'FAQsList',
    'dbtable'         =>'',
    'dbtableprimary'  =>'',
    'title'           =>'',
    'defaultorder'    =>array(array(1,'asc')),
    'dataexport'      =>($CI->tank_auth->is_logged_in()) ? true : false,
    'tablecondensed'  =>false,
    'bactionscolumn'  =>array(
                          "enable"        =>true,
                          "add"           =>false,
                          "addmethod"     =>'', // js function call
                          "view"          =>true,
                          "viewmethod"    =>'', // js function call
                          "edit"          =>($CI->tank_auth->is_logged_in() && $CI->usertype < 4 ) ? true : false,
                          "editmethod"    =>'', // js function call
                          "delete"        =>($CI->tank_auth->is_logged_in() && $CI->usertype < 4 ) ? true : false,
                          "deletemethod"  =>'', // js function call
                          "checkbox"      =>false,
                          "indexnumber"   =>false,
                      ),
    'column'          =>array(
                            
                            array(
                              'fieldid'            => 'DataID',
                              'fieldlabel'         => '',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '5%',
                              'className'          => '',
                              'visible'            => false,
                              'exportable'         => false,
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
                              'fieldid'            => 'Title',
                              'fieldlabel'         => 'QUESTIONS',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '',
                              'className'          => 'text-uppercase text-left',
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
                              'parentfieldid'      => '',
                            ),

                            array(
                              'fieldid'            => 'Message_Content',
                              'fieldlabel'         => 'ANSWER',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '',
                              'className'          => 'text-uppercase text-center',
                              'visible'            => false,
                              'exportable'         => false,
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
                              'fieldid'            => 'Category',
                              'fieldlabel'         => 'CATEGORY',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '',
                              'className'          => 'text-uppercase text-center',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '9',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),

                            array(
                              'fieldid'            => 'Enable',
                              'fieldlabel'         => 'PUBLISHED',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '10',
                              'className'          => 'text-uppercase text-center',
                              'visible'            => ($CI->tank_auth->is_logged_in() && $CI->usertype < 4 ) ? true : false,
                              'exportable'         => true,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '36',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),


                            array(
                              'fieldid'            => 'Created_By',
                              'fieldlabel'         => 'CREATED BY',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '10',
                              'className'          => 'text-uppercase text-center',
                              'visible'            => ($CI->tank_auth->is_logged_in() && $CI->usertype < 4 ) ? true : false,
                              'exportable'         => false,
                              'inputdisplay'       => 'text',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '7',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                            array(
                              'fieldid'            => 'Created_DateTime',
                              'fieldlabel'         => 'CREATED DATE/TIME',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '10',
                              'className'          => 'text-uppercase text-center',
                              'visible'            => ($CI->tank_auth->is_logged_in() && $CI->usertype < 4 ) ? true : false,
                              'exportable'         => false,
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
    'lengthChange'      =>false,

  );
?>

<div class="text-dark">  
    <?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
var faqanswer = function($p){

 var faqc = JSON.parse(atob($p));

  var faqHTML  = '<div class="card border mb-0">';
        faqHTML += '<div class="card-block">';
        faqHTML += '<h6 class="card-title text-success" style="border-bottom:1px dashed #eee">'+faqc.Category+'</h6>';
        faqHTML += '<h3 class="card-title text-uppercase">'+faqc.Questions+'</h3>';
        faqHTML += '<div class="mt-14 border-top border-success p-10 font-size-18 text-left" >';
        faqHTML += atob(faqc.Answer);
        faqHTML += '</div>';
        faqHTML += '</div>';
      faqHTML += '</div>';


 Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
    },
    buttonsStyling: false,
    willOpen: (fnRun) => {
      $(".swal2-container").css('z-index',$.topZIndex());
      $(".swal2-content").css('padding','0px');
    }
  }).fire({
    html: faqHTML,
    width: 800,
    confirmButtonText: 'CLOSE',
  }).then((result) => {

  });
};

$(document).ready(function(){



});  
</script>
