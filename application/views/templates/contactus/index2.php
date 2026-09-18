<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


$FormMode = (isset($viewmode) && $viewmode == true) ? 2 : 1; 
$FormDValue = (isset($datavalue)) ? $datavalue : array();

$FormArray = array(
      'Ticket_Category' => array(
                     'type'                => 'select', // select, text, date, time, datetime, radio, checkbox, textbox
                     'title'               =>"Category",
                     'class'               => 'text-uppercase',
                     'style'               =>'', 
                     'required'            => true, // true or  false
                     'extraattr'           => '', // custom attributes here
                     'placeholder'         => '',
                     'hint'                => '',
                     'GroupLabel'          => '',
                     'SubGroupLabel'       => '',
                     'parentfieldid'       => '',
                     'childfieldid'        => '',
                     'childfieldfilter'    => "",     
                     'referenceid'         => '59',  
                     'referencefilter'     => '',
                     'referenceorder'      => '',
                   ),
      'Ticket_Subject' => array(
                     'type'                => 'text', // select, text, date, time, datetime, radio, checkbox, textbox
                     'title'               =>"Subject",
                     'class'               => 'text-uppercase',
                     'style'               =>'', 
                     'required'            => true, // true or  false
                     'extraattr'           => '', // custom attributes here
                     'placeholder'         => '',
                     'hint'                => '',
                     'GroupLabel'          => '',
                     'SubGroupLabel'       => '',
                     'parentfieldid'       => '',
                     'childfieldid'        => '',
                     'childfieldfilter'    => "",     
                     'referenceid'         => '',  
                     'referencefilter'     => '',
                     'referenceorder'      => '',
                   ),
      'Ticket_Message' => array(
                     'type'                => 'textarea', // select, text, date, time, datetime, radio, checkbox, textbox
                     'title'               =>"Message",
                     'class'               => '',
                     'style'               =>'', 
                     'required'            => true, // true or  false
                     'extraattr'           => 'rows="7"', // custom attributes here
                     'placeholder'         => '',
                     'hint'                => '',
                     'GroupLabel'          => '',
                     'SubGroupLabel'       => '',
                     'parentfieldid'       => '',
                     'childfieldid'        => '',
                     'childfieldfilter'    => "",     
                     'referenceid'         => '',  
                     'referencefilter'     => '',
                     'referenceorder'      => '',
                   ),
     
     

);

$FormCustomArray = array();


$DataTableConfig =  array(
        'ajaxdatasource'  =>site_url('contactus/index/ticketlist'),
        'tableid'         =>'cticketlist',
        'dbtable'         =>'',
        'dbtableprimary'  =>'',
        'title'           =>'TICKET LIST',
        'defaultorder'    =>array(array(1,'desc')),
        'dataexport'      =>true,
        'tablecondensed'  =>false,
        'bactionscolumn'  =>array(
                              "enable"        =>true,
                              "add"           =>false,
                              "addmethod"     =>'', // js function call
                              "view"          =>true,
                              "viewmethod"    =>site_url('contactus/index/details'), // js function call
                              "edit"          =>false,
                              "editmethod"    =>'', // js function call
                              "delete"        =>false,
                              "deletemethod"  =>'', // js function call
                              "checkbox"      =>false,
                              "indexnumber"   =>true,
                          ),
        'column'          =>array(
                            
                            array(
                              'fieldid'            => 'Ticket_Status',
                              'fieldlabel'         => 'Status',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '50px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
                              'exportable'         => false,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '60',
                              'referencefilter'    => '',
                              'referenceorder'     => '',
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                            ), 

                            array(
                              'fieldid'            => 'Ticket_No',
                              'fieldlabel'         => 'Ticket No',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => false,
                              'width'              => '100px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
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
                            ),

                            array(
                              'fieldid'            => 'Ticket_DateTime',
                              'fieldlabel'         => 'Date / Time',
                              'fieldtype'          => 'datetime',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '150px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
                              'exportable'         => false,
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
                            
                             array(
                              'fieldid'            => 'Ticket_Category',
                              'fieldlabel'         => 'Category',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => false,
                              'orderable'          => true,
                              'width'              => '200px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
                              'exportable'         => false,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '59',
                              'referencefilter'    => '',
                              'referenceorder'     => '',
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                            ),
                             
                            array(
                              'fieldid'            => 'Ticket_Subject',
                              'fieldlabel'         => 'Subject',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => false,
                              'orderable'          => true,
                              'width'              => '200px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
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
                            ),
                           
                            array(
                              'fieldid'            => 'Ticket_ClosedDateTime',
                              'fieldlabel'         => 'Closed Date / Time',
                              'fieldtype'          => 'datetime',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '150px',
                              'className'          => 'text-uppercase',
                              'visible'            => true,
                              'exportable'         => false,
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
        'autoscrollx'       =>false,
        'scrollx'           =>false,
        'scrollpagination'  =>false,
        'actiobbuttons'     =>@$actionButtons, // html content
        'fixedcolumns'      =>array("leftColumns"=>1,"rightColumns"=>0), 
        'callbackjsfunction'=>'',
        'bordercolor'       =>'',
        // 'pushparameter'     =>['CrecControlNo' => $CI->encryption->encrypt($CrecData['CrecControlNo'])]

      );
?>

<?php if( (int) $CI->usertype > 3 || (int) $CI->userclassification > 3 ){ ?>
<div class="row  mb-10">
  <div class="col-sm-9 col-l-9 col-md-9" >
    <div class="panel border border-secondary bg-white mb-0"  >
      <div class="panel-body p-5" data-mh="cContainer">
        <div class="" style="font-size:12px !important"   >
          <?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-3 col-l-3  col-md-3 mb-10"  data-mh="cContainer">
    <div class="panel border border-secondary bg-dark mb-0"  >
      <div class="panel-body p-5">
        <div data-mh="cContainer">
          <h5 class="text-white mb-0"><i class="fa fa-fw fa-ticket mr-10"></i>NEW TICKET :</h5>
          <form id="ContactUsForm" method="POST" autocomplete="off" novalidate="novalidate" class="mt-0 mb-0">
            <?php if( $CI->config->item('csrf_protection') ){ ?>
            <input type="hidden" name="<?php echo @$CI->security->get_csrf_token_name(); ?>" value="<?php echo @$CI->security->get_csrf_hash(); ?>" />
            <?php } ?>
            <div>
              <?php echo $CI->myutilities->FormCreator('contactusform','floating',true,$FormMode, $FormDValue, $FormArray, $FormCustomArray ); ?>    
            </div>
            
          </form> 
          <button type="button" id="btnSubmitContactUsForm" name="btnAction" class="btn btn-primary btn-block ">SUBMIT</button>
        </div>
      </div>
    </div>
  </div> 
</div>
<?php } ?>
<div class="row">
  <div class="col-sm-12 col-l-12 col-md-12">
    <div class="panel border border-secondary bg-white mb-0"  >
      <div class="panel-body" data-mh="cContainer">
        <div class="text-dark">
          <?php 
            $contactusPage = $CI->load->view('templates/contactus/contactus.php', $CI->data, true);
            $contactusPage = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $contactusPage));
            echo str_replace(array('text-white'), 'text-dark', $contactusPage);
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

$(document).ready(function(){
  
  // $("[id='Ticket_Category'][name='Ticket_Category']").closest(".card").addClass('border-0').find(".card-block").addClass('p-0');


  $("button[id='btnSubmitContactUsForm']").unbind('click').click(function(){
    submitContactUsForm('0');
  });

});

</script>
