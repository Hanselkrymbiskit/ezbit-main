<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<div class="page-brand-info m-0">
  <div class="brand  mb-10" style="border-bottom:1px solid #4f4f4f;margin-top:-3rem">
    <img class="brand-img" src="<?php echo assetPath(); ?>images/Brand.png" alt="..." style="height: 80px">
    <h1 class="brand-text font-size-60 mb-0" style="font-size: 60px !important">
      <?php echo @$CI->system_settings['ApplicationAbbre']; ?>
      <p class="font-size-10"><?php echo @$CI->system_settings['ApplicationName']; ?></p>
    </h1>
    <h1 class="text-right float-right mr-20" style="font-size: 70px;margin-top: 12px; color: #fbea08;">FAQs</h1>
  </div>

  <div class="text-center mt-15 " class=""  style="height: 80vh" >
    <?php 
      $downloadlist = $CI->load->view('templates/faq/faqlist.php', $CI->data, true);
      $downloadlist = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $downloadlist));
      echo str_replace(array('text-white'), 'text-dark', $downloadlist);
    ?>
  </div>

  <footer class="page-copyright page-copyright-inverse text-uppercase" style="color: #fbea08 !important; position:relative;">

    <div class="social" style="margin-top:3rem;">
      <a class="mx-5 " href="<?php echo site_url('login'); ?>" style="color: #fbea08 !important; ">Login</a> |
      <a class="mx-5 " href="<?php echo site_url('seirguide'); ?>" style="color: #fbea08 !important; ">SEIR Guide</a> |
    <?php if($CI->system_settings['AboutModule'] == 1){ ?>
          <a class="mx-5" href="<?php echo site_url('about'); ?>"  style="color: #fbea08 !important; ">ABOUT</a> |
          <?php } ?>
    <?php if($CI->system_settings['DownloadModule'] == 1){ ?>
    <a class="mx-5" href="<?php echo site_url('download'); ?>" style="color: #fbea08 !important; ">DOWNLOADS</a> |
    <?php } ?>
      <a class="mx-5 " href="<?php echo site_url('contactus'); ?>" style="color: #fbea08 !important; ">Contact Us</a> |
      <a class="mx-5 " href="javascript:void(0)" title="View Privacy Notice" <?php echo $CI->data['pvnfilelinkAction']; ?>  style="color: #fbea08 !important; ">Privacy Notice</a>
    
    </div>
    
    <div class="mt-20">Version <?php echo  @$CI->system_settings['ApplicationVersion']; ?></div>

    <div class="mt-30">PHILIPPINE HEALTH INSURANCE CORPORATION</div>
    <div>© 2020. All RIGHT RESERVED.</div>
    <div class="social"  style="color: #fbea08 !important; ">
      <a class="btn btn-icon btn-pure" href="https://twitter.com/DOHgovph" target="_blank"  style="color: #fbea08 !important; ">
       <i class="icon bd-twitter" aria-hidden="true"  style="color: #fbea08 !important; "></i>
      </a>
         <a class="btn btn-icon btn-pure" href="https://www.facebook.com/OfficialDOHgov/" target="_blank">
       <i class="icon bd-facebook" aria-hidden="true" style="color: #fbea08 !important; "></i>
      </a>
         <a class="btn btn-icon btn-pure" href="javascript:void(0)">
       <i class="icon bd-google-plus" aria-hidden="true" style="color: #fbea08 !important; "></i>
      </a>
    </div>
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
  $(".page-content").addClass('pr-30');
  $("[id='Ticket_Category'][name='Ticket_Category']").closest(".card").addClass('border-0').find(".card-block").addClass('p-0');
  $("..page-login-v2 footer").addClass('pr-30');
  


});

</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onrecaptchaloaded&render=<?php echo $CI->system_settings['ReCaptcha_PublicKey']; ?>" async defer></script>