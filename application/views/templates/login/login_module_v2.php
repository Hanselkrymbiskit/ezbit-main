<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


?>

<div nonce="<?php echo @$CI->nonceV; ?>" class="page-login-main" style="background:rgb(255 255 255 / 30%)">

	<div class="hidden-xs-down mb-20 text-center">
		<img class="navbar-brand-logo h-90 mt--45" src="<?php echo assetPath(); ?>images/logo-masthead.png">
	</div>

	<div  style="height: 280px;
    margin-bottom: 10px;
    width: 350px;">
		
	</div>	

	<div class="brand hidden-md-up text-center mb-20">
		<img class="brand-img h-45 w-45" src="<?php echo assetPath(); ?>images/logo-colored@2x.png" alt="..." >
		<h3 class="brand-text font-size-40" style="color: #4CAF4E;"><?php echo @$CI->system_settings['ApplicationAbbre']; ?></h3>
		<h6 class="text-success mt-0"><?php echo @$CI->system_settings['ApplicationName']; ?></h6>
	</div>
	<h3 class="font-size-24 text-white text-uppercase">Sign In</h3>
	<p class="hidden-xs-down"></p>

	<form id="LoginForm" method="POST" autocomplete="LoginForm_false">
		<?php if( $CI->config->item('csrf_protection') ){ ?>
		<input type="hidden" name="<?php echo @$CI->security->get_csrf_token_name(); ?>" value="<?php echo base64_encode(@$CI->security->get_csrf_hash()); ?>" />
		<?php } ?>
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<input type="text" class="form-control empty text-white font-weight-bold" id="inputEmail" name="email" formgroup="LoginForm_Data" autocomplete="email_false">
			<label class="floating-label text-white mb-15" for="inputEmail">USERNAME / EMAIL</label>
		</div>
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<input type="password" class="form-control empty text-white font-weight-bold" id="inputPassword" name="pswrd" formgroup="LoginForm_Data" autocomplete="password_false">
			<label class="floating-label text-white  mb-15" for="inputPassword">PASSWORD</label>
		</div>
		<div class="form-group mb-10 clearfix">
			<p class="float-left text-white">No account? <a class="ml-0 text-uppercase text-white font-weight-bold" href="<?php echo site_url('signup'); ?>">SIGN UP</a></p>
			<a class="float-right  text-white" href="javascript:void(0)" onclick="forgotpassword()">Forgot password?</a>
		</div>

		<button type="button" id="btn_SignIn" name="btnAction" class="btn btn-primary btn-block mt-10">SIGN IN</button>
		<div class="form-group mt-20 clearfix">
			
		</div>
	</form>	
	

	<footer class="page-copyright mb-10 text-white text-uppercase ml-30 mr-30">
		<div class="social" style="margin-top:3rem">
	     		<a class="mx-5 text-white " href="<?php echo site_url('home'); ?>">HOME</a> |
	      	<?php if($CI->system_settings['ContactUs'] == 1){ ?>
	      	<a class="mx-5 text-white " href="<?php echo site_url('contactus'); ?>">Contact Us</a> |
	      	<?php } ?>
	      	<a class="mx-5 text-white " href="javascript:void(0)" <?php echo @$CI->data['pvnfilelinkAction']; ?> >Privacy Notice</a>
		</div>

		<p class="mt-10"></p>
		<p class="text-uppercase">© <?php echo $CI->system_settings['ApplicationFooter']; ?></p>
	</footer>
</div>	

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
<?php if($CI->system_settings['ReCaptchaModule'] == 1 && $CI->system_settings['ReCaptchaatLogin']  == 1 ){ ?>
<?php if($_SERVER['HTTP_HOST'] <> 'localhost' && $_SERVER['HTTP_HOST'] <> '127.0.0.1'){ ?>	
function onrecaptchaloaded(){
	try{
		var recpatchetoken  = '';
		grecaptcha.ready(function() {
            try{
           		
           		grecaptcha.execute("<?php echo $CI->system_settings['ReCaptcha_PublicKey']; ?>", {action: 'login'}).then(function(token) {
           			recpatchetoken = token;
           			$("button[id='btn_SignIn']").unbind('click').removeAttr('disabled').click(function(){
   	   	  			   system_login($("button[id='btn_SignIn']"),recpatchetoken);
					});
       			}); 
              
            }catch(e)
            {
				console.log('Failed to retrieve security keys from google recaptcha');
            }
           
        });
	}catch(e){
		$("button[id='btn_SignIn']").attr('disabled','disabled');
		onrecaptchaloaded();
	}
}
<?php } else{ ?>
$("button[id='btn_SignIn']").unbind('click').removeAttr('disabled').click(function(){
   system_login($("button[id='btn_SignIn']"));
});	
<?php } ?>
<?php } else{ ?>
$("button[id='btn_SignIn']").unbind('click').removeAttr('disabled').click(function(){
   system_login($("button[id='btn_SignIn']"));
});	
<?php } ?>

$(document).ready(function(){
	
	$(".page-login-main").css('padding','55px 60px 180px');

	$(window).keypress(function(e){
		if( e.keyCode == 13 )
		{
			if( $("input[id='inputEmail'][formgroup='LoginForm_Data'], input[id='inputPassword'][formgroup='LoginForm_Data']").is(":focus") )
			{
				$("button[id='btn_SignIn']").click();
			}		
		}
	});	

	$("page-login-main").css('padding','50px 60px 180px');

});

</script>
<?php if($CI->system_settings['ReCaptchaModule'] == 1 && $CI->system_settings['ReCaptchaatLogin']  == 1 ){ ?>
<?php if($_SERVER['HTTP_HOST'] <> 'localhost' && $_SERVER['HTTP_HOST'] <> '127.0.0.1'){ ?>
<script src="https://www.google.com/recaptcha/api.js?onload=onrecaptchaloaded&render=<?php echo $CI->system_settings['ReCaptcha_PublicKey']; ?>" nonce="<?php echo $CI->nonceV; ?>" async defer></script>
<?php } ?>
<?php } ?>