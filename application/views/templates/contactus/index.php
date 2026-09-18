<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


$FormMode = (isset($viewmode) && $viewmode == true) ? 2 : 1; 
$FormDValue = (isset($datavalue)) ? $datavalue : array();

$FormArray = array(
      'Ticket_Category' => array(
                     'type'                => 'select', // select, text, date, time, datetime, radio, checkbox, textbox
                     'title'               =>"Category",
                     'class'               => '',
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
      'Ticket_CompleteName' => array(
                     'type'                => 'text', // select, text, date, time, datetime, radio, checkbox, textbox
                     'title'               =>"Complete Name",
                     'class'               => '',
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
      'Ticket_ContactEmail' => array(
                     'type'                => 'email', // select, text, date, time, datetime, radio, checkbox, textbox
                     'title'               =>"Email Address",
                     'class'               => '',
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
      'Ticket_Subject' => array(
                     'type'                => 'text', // select, text, date, time, datetime, radio, checkbox, textbox
                     'title'               =>"Subject",
                     'class'               => '',
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



?>

<div class="page-brand-info m-0">
  <div class="brand  mb-10" style="border-bottom:1px solid #4f4f4f;margin-top:-1rem">
    <img class="brand-img" src="<?php echo assetPath(); ?>images/Brand.png" alt="..." style="height: 80px">
    <h1 class="brand-text font-size-50 mb-30" style="font-size: 50px !important">
      <?php echo @$CI->system_settings['ApplicationName']; ?>
    </h1>

  </div>

  <div class="text-center mt-5 " class="" data-plugin="scrollable" style="height: 80vh" >
    <div data-role="container">
      <div data-role="content">
        <?php 
          $contactusPage = $CI->load->view('templates/contactus/contactus.php', $CI->data, true);
          $contactusPage = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $contactusPage));
          echo $contactusPage;
        ?>
      </div>
    </div>
  </div>

</div>

<div class="page-login-main pt-0" style="padding:0px 60px 95px !important;">
  <div class="brand hidden-md-up text-center mb-20">
    <img class="brand-img" src="<?php echo assetPath(); ?>images/logo-colored@2x.png" alt="..." style="height:45px;width:45px">
    <h3 class="brand-text font-size-40" style="color: #4CAF4E;"><?php echo @$CI->system_settings['ApplicationAbbre']; ?></h3>
  </div>
  <h3 class="font-size-24">Contact Us</h3>
  <p class="hidden-xs-down"></p>

  <form id="ContactUsForm" method="POST" autocomplete="off" novalidate="novalidate" class="mt-0">
    <?php if( $CI->config->item('csrf_protection') ){ ?>
    <input type="hidden" name="<?php echo @$CI->security->get_csrf_token_name(); ?>" value="<?php echo @$CI->security->get_csrf_hash(); ?>" />
    <?php } ?>
    <div>
      <?php echo $CI->myutilities->FormCreator('contactusform','floating',true,$FormMode, $FormDValue, $FormArray, $FormCustomArray ); ?>    
    </div>
    <button type="button" id="btnSubmitContactUsForm" name="btnAction" class="btn btn-primary btn-block mt-10">Submit</button>
  </form> 
  
  <p></p>
  <footer class="page-copyright mb-10 ml-10 mr-10 text-uppercase">
    <div class="social text-uppercase" style="margin-top:3rem">
      <a class="mx-5" href="<?php echo site_url('login'); ?>">Login</a> |
      <a class="mx-5" href="<?php echo site_url('signup'); ?>">Sign Up</a> |
      <?php if($CI->system_settings['AboutModule'] == 1){ ?>
          <a class="mx-5" href="<?php echo site_url('about'); ?>">About</a> |
          <?php } ?>
      <?php if($CI->system_settings['DownloadModule'] == 1){ ?>
          <a class="mx-5" href="<?php echo site_url('download'); ?>">Downloads</a> |
          <?php } ?>
      <?php if($CI->system_settings['FAQModule'] == 1){ ?>
          <a class="mx-5" href="<?php echo site_url('faq'); ?>">FAQ</a> |
          <?php } ?>
          <a class="mx-5" href="javascript:void(0)" <?php echo $CI->data['pvnfilelinkAction']; ?> >Privacy</a>
    </div>
    <p class="mt-10">Version <?php echo  @$CI->system_settings['ApplicationVersion']; ?></p>
    <p class="mt-10">PHILIPPINE HEALTH INSURANCE CORPORATION</p>
    <p class="text-uppercase">© <?php echo $CI->system_settings['ApplicationFooter']; ?></p>
  </footer>
</div>  

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
function onrecaptchaloaded(){
  try{
    var recpatchetoken  = '';
    grecaptcha.ready(function() {
          try{
              
              grecaptcha.execute("<?php echo $CI->system_settings['ReCaptcha_PublicKey']; ?>", {action: 'login'}).then(function(token) {
                recpatchetoken = token;

                $("button[id='btnSubmitContactUsForm']").unbind('click').click(function(){
                  submitContactUsForm(recpatchetoken);
                });

              }); 
              
            }catch(e) {
               console.log('Failed to retrieve security keys from google recaptcha');
            }
           
        });
  }catch(e){
    onrecaptchaloaded();
  }
}
$(document).ready(function(){
  $("[id='Ticket_Category'][name='Ticket_Category']").closest(".card").addClass('border-0').find(".card-block").addClass('p-0');
});

</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onrecaptchaloaded&render=<?php echo $CI->system_settings['ReCaptcha_PublicKey']; ?>" async defer></script>