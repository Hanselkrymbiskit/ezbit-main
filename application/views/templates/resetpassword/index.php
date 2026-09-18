<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();



?>

<div class="signup-page">
	<div class="brand">
    <img class="brand-img h-150" src="<?php echo assetPath(); ?>images/e-zbits_logo.png" alt="...">
  </div>
   
  <div class="page-register-bg p-10 bg-white">
		<div class="row">
				<div class="col-lg-8 col-md-8 p-10">
					<div class="text-dark" style="">

						<div class="card bg-transparent border-0 mb-10">
							<div class="card-block p-0">
							  <h4 class="card-title ">Creating Password Guide</h4>
							  <div>
							  	To ensure the integrity and security of this site, all authorized end-users are strongly required to <b>CREATE STRONG PASSWORDS</b>:
							  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="card bg-transparent border-0 mb-10">
									<div class="card-block p-0">
									  <h4 class="card-title ">DO's</h4>
									  <div>
									  	<div class="text-left font-size-14 p-5">
							              - Are at least eight (8) to sixteen (16) alphanumeric characters long.&nbsp;<br>
							              - Contain both upper and lower case characters<strong> (e.g., a-z, A-Z)</strong>&nbsp;<br>
							              - Have digits and punctuation characters as well as letters e.g.,&nbsp;<strong>(0-9),( . )&nbsp;</strong>
							                - Are not a word in any language, slang, dialect, jargon, etc.&nbsp;<br>
							                - Are not based on personal information, names of family, ATM account numbers, license numbers, etc.
							                
					            </div>  
									  </div>
									</div>
								</div>
							</div>

							<div class="col-lg-6 col-md-6">
								<div class="card bg-transparent border-0 mb-10">
									<div class="card-block p-0">
									  <h4 class="card-title ">DONT's</h4>
									  <div>
									  	<div class="text-left font-size-14 p-5">
							            - The password is a word found in a dictionary (English or foreign)&nbsp;<br>
						                - The password is a common usage word such as: ( Names of family, pets, friends, co-workers, fantasy characters,&nbsp;etc.&nbsp;)<br>
						                - Computer terms and names, commands, sites, companies, hardware,&nbsp;software.&nbsp;<br>
						                - Birthdays and other personal information such as addresses and&nbsp;phone numbers.&nbsp;<br>
						                - Word or number patterns like aaabbb, qwerty, zyxwvuts, 123321,&nbsp; etc.&nbsp;<br>
						                - Any of the above spelled backwards.&nbsp;<br>
						                - Any of the above preceded or followed by a digit (e.g., secret1,&nbsp;1secret)&nbsp;
							                
					            </div>  
									  </div>
									</div>
								</div>
							</div>

						</div>
						<div class="card bg-transparent border-0 mb-10">
							<div class="card-block p-0">
							  <h4 class="card-title ">ALL PASSWORDS ARE TO BE TREATED AS SENSITIVE, CONFIDENTIAL&nbsp;INFORMATION.</h4>
							  <div>
							  	<div class="float-left text-left  w-p40 font-size-14">
					              - Don't reveal a password over the phone to ANYONE&nbsp;<br>
					              - Don't reveal a password in an email message&nbsp;<br>
					              - Don't talk about a password in front of others&nbsp;<br>
					              - Don't hint at the format of a password (e.g., "my family name")&nbsp;<br>
					              - Don't reveal a password on questionnaires or security forms&nbsp;<br>
					              - Don't share a password with family members&nbsp;<br>
					              
		              </div>
		              <div class="float-right text-left  w-p60 font-size-14">
					                - Don't reveal a password to co-workers while on vacation&nbsp;<br>
					              - Don’t use the "Remember Password" feature of applications (e.g.,&nbsp;Firefox, IE, Chrome, Opera, etc.).&nbsp;<br>
					              - Don’t use the same password on several computers and/or services as&nbsp;once revealed, it would compromise the security within all the others&nbsp;in one go&nbsp;<br>
					              &nbsp;-Before entering your User ID and password, make sure no one is&nbsp;watching you, to avoid the so-called "shoulder surfing" technique.&nbsp;<br>
					              - Before using your User ID and password on a third-party computer,&nbsp;make sure it is well protected, and free of trojans and key loggers.&nbsp;<p></p>
		              </div> 
							  </div>
							</div>
						</div>

						<p class="muted ml-10 float-left"> Source: &nbsp;<a target="_blank" rel="nofollow" class="" href="http://www.google.com/url?sa=D&amp;q=http://oit.osu.edu/networking/osunet/Password_Best_Practices.pdf&amp;usg=AFQjCNHKxtwOkhbGxCakT25qRc1gNGWA_g">http://oit.osu.edu/networking/osunet/Password_Best_Practices.pdf</a>&nbsp; </p>
					</div>
				</div>

				<div class="col-lg-4 col-md-4 p-10 border-left">
					<div class="card border-0 mb-10">
							<div class="card-block p-0">
								<h3 class="font-size-24 text-uppercase text-center">Create New<br>Account Password</h3>
								<p class="hidden-xs-down"></p>

								<form id="ForgotPasswordForm" class="" method="POST" autocomplete="off">
									<?php if( $CI->config->item('csrf_protection') ){ ?>
		               <input type="hidden" name="<?php echo @$CI->security->get_csrf_token_name(); ?>" value="<?php echo @$CI->security->get_csrf_hash(); ?>" />
		              <?php } ?>
									<label class=" font-size-16 floating-label " for="newpassword">Account Name</label>
									<div class="form-group form-material floating mt-0 mb-5" data-plugin="formMaterial">
										<input type="text" class="form-control empty font-size-16 text-primary  text-center"  autocomplete="off"  value="<?php echo $accountname; ?>" disabled="disabled">
										
									</div>
									<label class="floating-label" for="newpassword">User Name</label>
									<div class="form-group form-material floating mt-0  mb-5" data-plugin="formMaterial">
										<input type="text" class="form-control empty font-size-16  text-primary text-center"  autocomplete="off"  value="<?php echo $username; ?>" disabled="disabled">
										
									</div>
									<label class="floating-label" for="newpassword">Registered Email Address</label>
									<div class="form-group form-material floating mt-0 mb-5" data-plugin="formMaterial">
										<input type="text" class="form-control empty font-size-16  text-primary text-center" autocomplete="off"  value="<?php echo $registeredemailaddress; ?>" disabled="disabled">
										
										
									</div>
									<label class="floating-label" for="newpassword">New Password</label>
									<div class="form-group form-material floating mt-0  mb-5" data-plugin="formMaterial">

										<input type="password" class="form-control empty font-size-16  text-primary text-center" id="newpassword" name="newpassword" forminputgroup="NewPasswordForm_Data" autocomplete="off" minlength="<?php echo @$CI->system_settings['PasswordLength_Min']; ?>" maxlength="<?php echo @$CI->system_settings['PasswordLength_Max']; ?>" data-plugin="strength">
										
										<div class="font-size-10 text-danger" style="width:100%"><span id="match_newpassword" class="float-right" style="display:none">Password and Confirm Password Doesn't Match</span></div>
									</div>
									<label class="floating-label" for="confirmpassword">Confirm New Password</label>
									<div class="form-group form-material floating mt-0  mb-5" data-plugin="formMaterial">
										
										<input type="password" class="form-control empty  font-size-16 text-primary text-center" id="confirmpassword" name="confirmpassword" forminputgroup="NewPasswordForm_Data" autocomplete="off" minlength="<?php echo @$CI->system_settings['PasswordLength_Min']; ?>" maxlength="<?php echo @$CI->system_settings['PasswordLength_Max']; ?>" data-plugin="strength">
										<div class="font-size-10 text-danger" style="width:100%"><span id="match_confirmpassword" class="float-right" style="display:none">Password and Confirm Password Doesn't Match</span></div>
									</div>
									
									<button type="button" id="btnSubmitNewPassword" name="btnAction" class="btn btn-primary btn-block mt-10">Submit</button>
								</form>	
							</div>
						</div>
				</div>
		</div>


  </div>
  <footer class="page-copyright page-copyright-inverse  text-dark">
	      <div class="social font-weight-bold" style="margin-top:2rem;margin-bottom:2rem">
	         <a class="mx-5 text-dark text-uppercase" href="<?php echo site_url('login'); ?>">Login</a>  |
	         <?php if($CI->system_settings['ContactUs'] == 1){ ?>
	         <a class="mx-5 text-dark text-uppercase" href="<?php echo site_url('contactus'); ?>">Contact Us</a> |
	         <?php } ?>
	         <a class="mx-5 text-dark text-uppercase" href="javascript:void(0)" <?php echo $CI->data['pvnfilelinkAction']; ?> >Privacy Notice</a>
	      </div>
	      <div class="row">
	         <div class="col-lg-12"><img class="brand-img mr-20" src="<?php echo assetPath(); ?>images/PHICBrand_Greyscale.png" alt="..." style="height:80px"><img class="brand-img" src="<?php echo assetPath(); ?>images/bagongpilipinas2_greyscale.png" alt="..." style="height:80px">
	         </div>
	      </div> 
	      <p class="text-uppercase mt-40 text-dark font-weight-bold">© <?php echo $CI->system_settings['ApplicationFooter']; ?></p>
	      <a nonce="<?php echo $CI->nonceV; ?>" href="https://www.vecteezy.com/free-vector/background-design" target="_blank" style="position: absolute; bottom:-125px; left:10px"><img nonce="<?php echo $CI->nonceV; ?>" src="<?php echo assetPath(); ?>images/vecteezy-logo.png" class="h-30" crossorigin="anonymous"></a>
	 	</footer>
</div>



<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

let recpatchetoken = '<?php echo @$passwordkey; ?>';
<?php if($CI->system_settings['ReCaptchaModule'] == 1 && (!in_array($_SERVER['HTTP_HOST'], ['localhost','127.0.0.1'])) ){ ?>
function onrecaptchaloaded(){
	try{
		grecaptcha.ready(function() {
      try{
   			grecaptcha.execute("<?php echo $CI->system_settings['ReCaptcha_PublicKey']; ?>", {action: 'resetpassword'}).then(function(token) {
     			recpatchetoken = token;
   			});  
      }
      catch(e)
      {
				console.log('Failed to retrieve security keys from google recaptcha');
      }   
    });
	}catch(e){
		onrecaptchaloaded();
	}
}
<?php } ?>

$(document).ready(function(){
	
	$("button[id='btnSubmitNewPassword']").unbind('click').click(function(){
			if( ( $("input[id='newpassword']").val() && $("input[id='confirmpassword']").val() ) && $("input[id='newpassword']").val() == $("input[id='confirmpassword']").val() )
			{
				submitCreatePassword('<?php echo @$passwordkey; ?>',recpatchetoken);
			}
			else
			{
				Swal.mixin({
	          customClass: {
	            confirmButton: 'btn btn-danger',
	          },
	          buttonsStyling: false,
	          willOpen: (fnRun) => {
	            $(".swal2-container").css('z-index',$.topZIndex());
	          }
	        }).fire({
	          title: "Create New Account Password",
	          text: 'Invalid New Password & Confirm Password!',
	          icon: 'error',
	          confirmButtonText: 'Close',
	        }).then((result) => {
	        	if(typeof onrecaptchaloaded == 'function')
	        	{
	        		onrecaptchaloaded();
	        	}
	        });
			}
	});

	$("input[id='newpassword'],input[id='confirmpassword']").blur(function(){

		var otherP = ( $(this).attr('id') == 'newpassword' ) ? 'confirmpassword' : 'newpassword';
		var DefaultPMess = "Password and Confirm Password Doesn't Match";
		
		$("span[id='match_"+$(this).attr('id')+"']").text(DefaultPMess);
		if( parseInt( ($(this).val()).length ) < parseInt($(this).attr('minlength')) )
		{
			$("span[id='match_"+$(this).attr('id')+"']").text('Password Minimum Length '+$(this).attr('minlength')+'!');
			$("span[id='match_"+$(this).attr('id')+"']").show();
		}

		if( parseInt( ($(this).val()).length ) > parseInt($(this).attr('maxlength')) )
		{
			$("span[id='match_"+$(this).attr('id')+"']").text('Password Maximum Length '+$(this).attr('maxlength')+'!');
			$("span[id='match_"+$(this).attr('id')+"']").show();
		}


		if( $(this).val() && $("input[id='"+otherP+"']").val() )
		{

			if( $(this).val() !== $("input[id='"+otherP+"']").val() )
			{
				
          		$("span[id='match_newpassword'],span[id='match_confirmpassword']").show();
          		$("button[id='btnSubmitNewPassword']").attr('disabled','disabled');
			}
			else
			{
				$("span[id='match_newpassword'],span[id='match_confirmpassword']").hide();
          		$("button[id='btnSubmitNewPassword']").removeAttr('disabled');
			}
		}

		

   });

	$(window).keypress(function(e){
		if( e.keyCode == 13 )
		{
			if( $("input[id='newpassword'][formgroup='NewPasswordForm_Data'], input[id='confirmpassword'][formgroup='NewPasswordForm_Data']").is(":focus") )
			{
				$("button[id='btnSubmitNewPassword']").click();
			}		
		}
	});	


});

</script>
<?php if($CI->system_settings['ReCaptchaModule'] == 1 && (!in_array($_SERVER['HTTP_HOST'], ['localhost','127.0.0.1'])) ){ ?>
<script src="https://www.google.com/recaptcha/api.js?onload=onrecaptchaloaded&render=<?php echo $CI->system_settings['ReCaptcha_PublicKey']; ?>" nonce="<?php echo $CI->nonceV; ?>" async defer></script>
<?php } ?>