<?php
    $CI =& get_instance();
?> 
<div> 
   <div nonce="<?php echo $CI->nonceV; ?>" class="brand">
      <img nonce="<?php echo $CI->nonceV; ?>" class="brand-img h-80" src="<?php echo assetPath(); ?>images/brand.png" alt="...">
      <img class="brand-img h-80 ml-20" src="<?php echo assetPath(); ?>images/bagongpilipinas2.png" alt="...">
      <br>
      <img nonce="<?php echo $CI->nonceV; ?>" class="brand-img h-200 mt-30" src="<?php echo assetPath(); ?>images/e-zbits_logo.png" alt="...">
   </div>
   <div class="page-register-bg">
      <div class="panel ">
         <div class="panel-body">
            
            <h2 class="text-success" style="text-align: center">Account Registration Successfully Activated</h2>
            <h5 class="text-center  mt-30">You can now login/signin using your registered username and password.</h5>

            <div  style="width:100%;margin-top:30px;text-align:center;color:#000">
                  <span id="">Redirecting to login page in</span><br>

                  <span id="autoredirect" class="text-success" style="font-size: 100px;font-weight: bold"></span><br>
                  <span>or manually clicking the Login Link below or on the menu</span>
            </div>

            <div class="" style="width:100%;margin-top:20px;">
               <div style="font-size:12px;text-align:center;margin-top:30px;color:#000">
                  Please click <a class="text-dark" href="<?php echo site_url(); ?>">here</a> to redirect to login page. 
               </div>
               <div style="font-size:12px;text-align:center;margin-top:10px">
                  <p></p>
               </div>
            </div>
         </div>
      </div>
   </div>
  
   <footer class="page-copyright page-copyright-inverse text-dark font-weight-bold mt-50">
      <div class="social font-weight-bold" style="">
         <?php if($CI->system_settings['ContactUs'] == 1){ ?>
         <a class="mx-5 text-dark text-uppercase" href="<?php echo site_url('contactus'); ?>">Contact Us</a> |
         <?php } ?>
         <a class="mx-5 text-dark text-uppercase" href="javascript:void(0)" <?php echo $CI->data['pvnfilelinkAction']; ?> >Privacy Notice</a>
      </div>

      <div class="row text-center">

      </div>
      
      <p class="text-uppercase mt-40 text-dark font-weight-bold">© <?php echo $CI->system_settings['ApplicationFooter']; ?></p>
      <a nonce="<?php echo $CI->nonceV; ?>" href="https://www.vecteezy.com/free-vector/background-design" target="_blank" style="position: absolute; bottom:10px; left:10px"><img nonce="<?php echo $CI->nonceV; ?>" src="<?php echo assetPath(); ?>images/vecteezy-logo.png" class="h-30" crossorigin="anonymous"></a>
   </footer>
</div>
<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
   var ct=10;
   var r=setInterval(function()
   {
      $("span[id='autoredirect']").text(ct);

      if(ct < 8)
      {
         $("span[id='autoredirect']").removeClass('text-dark').addClass('text-warning');
      }

      if(ct < 4)
      {
         $("span[id='autoredirect']").removeClass('text-warning').addClass('text-danger');
      }

      if(ct==0)
      {
         clearTimeout(r);
         window.location.href="<?php echo site_url('login'); ?>";
      }
      ct--;
   },1000);
</script>       
