<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$llA = ['127.0.0.1','localhost'];

$IconCaptchaS = ( (($CI->system_settings['IconCaptchaModule'] ?? 0) == 1 && ($CI->system_settings['IconCaptchaatLogin'] ?? 0) == 1 ) && !in_array($_SERVER['HTTP_HOST'], $llA) ) ? true : false;?>
<div class="bg-white-clear-50 pb-5 rounded-rm-1" style="">
	<div nonce="<?php echo $CI->nonceV; ?>" class="brand mb-0">
		<div class="bg-white pb-5 pt-5 rounded-rm-tpo-1" style="">
		<img nonce="<?php echo $CI->nonceV; ?>" class="brand-img h-80 mt-0 mr-70" src="<?php echo assetPath(); ?>images/brand.png" alt="..."><img class="brand-img h-80 mt-0 ml-20" src="<?php echo assetPath(); ?>images/bagongpilipinas2.png" alt="...">
		</div>
		<br>
		<img nonce="<?php echo $CI->nonceV; ?>" class="brand-img h-200 mt-30" src="<?php echo assetPath(); ?>images/e-zbits_logo.png" alt="...">
		<h2 nonce="<?php echo $CI->nonceV; ?>" class="mt-0 mb-0"></h2>
		<h2 nonce="<?php echo $CI->nonceV; ?>" class="mt-0 mb-0">&nbsp;</h2>
	</div>
	<div class="vertical-align-middle" >
		<form  nonce="<?php echo $CI->nonceV; ?>" id="LoginForm" method="POST" autocomplete="off" class="align-middle mt-0 mb-0">
			<?php if( $CI->config->item('csrf_protection') ){ ?>
			<input type="hidden" name="<?php echo @$CI->security->get_csrf_token_name(); ?>" value="<?php echo base64_encode(@$CI->security->get_csrf_hash()); ?>" />
			<?php } ?>
			<div id="lgnGroup" class="<?php echo ($IconCaptchaS ) ? 'd-none' : ''; ?>">
				<div class="form-group form-material floating" data-plugin="formMaterial">
					<input type="text" class="form-control empty text-dark font-weight-bold" id="ieml" name="email" formgroup="LoginForm_Data">
					<label class="floating-label text-dark font-size-16 mt--10" for="ieml">USERNAME / EMAIL</label>
				</div>
				<div class="form-group form-material floating" data-plugin="formMaterial">
					<input type="password" class="form-control empty text-dark font-weight-bold" id="ipswrd" name="pswrd" formgroup="LoginForm_Data">
					<label class="floating-label text-dark font-size-16 mt--10" for="ipswrd" >PASSWORD</label>
				</div>
				<div class="form-group mb-10 clearfix">
					<p class="float-left text-dark">No account? <a class="ml-0 text-uppercase text-dark font-weight-bold" href="<?php echo site_url('signup'); ?>">SIGN UP</a></p>
					<a class="float-right  text-dark" href="javascript:void(0)" onclick="forgotpassword()">Forgot password?</a>
				</div>

				<button type="button" id="btn_SignIn" name="btnAction" class="btn btn-dark btn-block mt-10">SIGN IN</button>
				<div class="form-group mt-20 clearfix"></div>
			</div>
			<?php if( $IconCaptchaS ){ ?>
			<div class="iconcaptcha-widget mb-10" data-theme="light"></div>
			<?php } ?>
		</form>	
			
	</div>
	<footer class="page-copyright page-copyright-inverse text-dark font-weight-bold mt-50">
	   <div class="social font-weight-bold">
		   <?php if($CI->system_settings['ContactUs'] == 1){ ?>
		   <a class="mx-5 text-dark text-uppercase" href="<?php echo site_url('contactus'); ?>">Contact Us</a> |
		   <?php } ?>
		   <a class="mx-5 text-dark text-uppercase" href="javascript:void(0)" <?php echo $CI->data['pvnfilelinkAction']; ?> >Privacy Notice</a>
		</div>

		<div class="row text-center">

		</div>
		
		<p class="text-uppercase mt-40 text-dark font-weight-bold">© <?php echo $CI->system_settings['ApplicationFooter']; ?></p>
		<a nonce="<?php echo $CI->nonceV; ?>" href="https://www.vecteezy.com/free-vector/background-design" target="_blank" class="position-left-absolute-10"><img nonce="<?php echo $CI->nonceV; ?>" src="<?php echo assetPath(); ?>images/vecteezy-logo.png" class="h-30" crossorigin="anonymous"></a>
	</footer>
</div>
<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

<?php 

if( (($CI->system_settings['ReCaptchaModule'] ?? 0) <> 1 && ($CI->system_settings['IconCaptchaModule'] ?? 0) <> 1) || in_array($_SERVER['HTTP_HOST'], $llA) )	{
		echo '
		$("button[id=\'btn_SignIn\']").unbind("click").removeAttr("disabled").click(function(){
		   system_login($("button[id=\'btn_SignIn\']"));
		});	
		';
	}
	else
	{
		// For ReCaptcha
		if($CI->system_settings['ReCaptchaModule'] == 1 && $CI->system_settings['ReCaptchaatLogin']  == 1 )
		{
			echo '
				function onrecaptchaloaded(){
					try{
						var recpatchetoken  = "";
						grecaptcha.ready(function() {
				         try{
				        		
				        		grecaptcha.execute("'.$CI->system_settings['ReCaptcha_PublicKey'].'", {action: "login"}).then(function(token) {
				        			recpatchetoken = token;
				        			$("button[id=\'btn_SignIn\']").unbind("click").removeAttr("disabled").click(function(){
				   	  			   system_login($("button[id=\'btn_SignIn\']"),recpatchetoken);
									});
				    			}); 
				           
				         }catch(e)
				         {
								console.log(\'Failed to retrieve security keys from google recaptcha\');
				         }
				     });
					}catch(e){
						$("button[id=\'btn_SignIn\']").attr("disabled","disabled");
						onrecaptchaloaded();
					}
				}
		';
		}

		// For IconCaptcha
if(($CI->system_settings['IconCaptchaModule'] ?? 0) == 1 && ($CI->system_settings['IconCaptchaatLogin'] ?? 0) == 1 )		{
			echo '
			$("button[id=\'btn_SignIn\']").unbind("click").removeAttr("disabled").click(function(){
			   system_login($("button[id=\'btn_SignIn\']"),"",true);
			});	
			';
		}
	}
?>

$(document).ready(function(){
	
	$(".page-login-main").css('padding','55px 60px 180px');

	$(window).keypress(function(e){
		if( e.keyCode == 13 )
		{
			if( $("input[id='ieml'][formgroup='LoginForm_Data'], input[id='ipswrd'][formgroup='LoginForm_Data']").is(":focus") )
			{
				$("button[id='btn_SignIn']").click();
			}		
		}
	});	

	$("page-login-main").css('padding','50px 60px 180px');

	<?php if(($CI->system_settings['IconCaptchaModule'] ?? 0) == 1 && ($CI->system_settings['IconCaptchaatLogin'] ?? 0) == 1 ){ ?>
	$('.iconcaptcha-widget').iconCaptcha({
		general: {
          endpoint: site_url+'login/iconcaptcha', 
          fontFamily: 'inherit',
          showCredits: true,
      },
      security: {
          interactionDelay: 1500,
          hoverProtection: true,
          displayInitialMessage: true,
          initializationDelay: 500,
          incorrectSelectionResetDelay: 3000,
          loadingAnimationDuration: 1000,
      },
      locale: {
          initialization: {
              verify: 'Verify that you are human.',
              loading: 'Loading challenge...',
          },
          header: 'Select the image displayed the <u>least</u> amount of times',
          correct: 'Verification complete.',
          incorrect: {
              title: 'Uh oh.',
              subtitle: "You've selected the wrong image.",
          },
          timeout: {
              title: 'Please wait.',
              subtitle: 'You made too many incorrect selections.'
          }
      }
	}).bind('success', function(e) {
     $("div[id='lgnGroup']").removeClass('d-none');
 	});
	<?php } ?>
});

</script>
<?php if($CI->system_settings['ReCaptchaModule'] == 1 && $CI->system_settings['ReCaptchaatLogin']  == 1 ){ ?>
<?php if( !in_array($_SERVER['HTTP_HOST'], $llA) ){ ?>
<script src="https://www.google.com/recaptcha/api.js?onload=onrecaptchaloaded&render=<?php echo $CI->system_settings['ReCaptcha_PublicKey']; ?>" nonce="<?php echo $CI->nonceV; ?>" async defer></script>
<?php } ?>
<?php } ?>

