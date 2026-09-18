<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>
<div class="row">
	<div class="col-lg-4 col-md-4">
		<div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Time Based One-Time Password (TOTP)</h3>
          </div>
          <div class="panel-body">
            <p>In order to properly setup your Two-Factor Authentication you need to have Authenticator/TOTP Apps installed on your mobile device.</p>
            <p>How to Install:</p>
            <ul>
				<li>For <i class="fa fa-apple fa-fw"></i> iOS Devices -> Open Apps Store</li>
				<li>For <i class="fa fa-android fa-fw"></i> Android Devices -> Open Play Store</li>
				<li>Search for Authenticator Apps</li>
				<li>Select your preferred Authenticator Apps</li>
				<li>Press -> GET for iOS Device or Install for Android Device</li>
			</ul>
			<p>Recommended Authenticator APPS : </p>
			<a nonce="<?php echo @$CI->nonceV; ?>" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en-US&pli=1" target="_blank"><img class="w-p100" src="<?php echo assetPath(); ?>images/pages/twofactor/google_auth.png"></a>
			<a nonce="<?php echo @$CI->nonceV; ?>" href="https://play.google.com/store/search?q=microsoft+authenticator&c=apps&hl=en-US" target="_blank"><img class="w-p100 mt-5" src="<?php echo assetPath(); ?>images/pages/twofactor/microsoft_auth.png"></a>
          </div>
        </div>
	</div>
	
	<div class="col-lg-4 col-md-4">
		<div class="panel mb-10">
          <div class="panel-body">
            <h5 class="text-center">For manual setup please follow the instruction below.</h5>
            <div class="">
				<ul>
					<li>Open your Authentication app or TOTP app.</li>
					<li>Click Add/New then Enter a Setup Key.</li>
					<li>Enter the Following details.</li>
					<li> - Code Name = <span class="font-weight-bold text-primary">PhilHealth-eZBits: <?php echo @$CI->userprofile['emailaddress']; ?></span></li>
					<li> - Your Key  = <span class="font-weight-bold  text-primary"><?php echo @$secret; ?></span></li>
					<li> - Type of Key default to <span class="text-primary">Time Based</span> .</li>
				</ul>
			</div>

          </div>
        </div>
        <div class="panel">
          <div class="panel-body">
            <h5 class="text-center">For quick setup scan the QR code with your Authentication app.</h5>
            <div class="text-center">
							<img class="w-200 mt-25" src="<?php echo $tfa->getQRCodeImageAsDataUri($CI->userprofile['emailaddress'], $secret); ?>">
						</div>
          </div>
        </div>
	</div>

	<div class="col-lg-4 col-md-4">
		<div class="panel">
      <div class="panel-body">
      	<h3 class="panel-title text-success text-center">Confirm Authentication Code</h3>
        <div class="form-group form-material  w-p50" data-plugin="formMaterial" style="margin:0 auto;">
          
          <div class="input-group">
            <div class="form-control-wrap">
              <input type="text" id="ConfirmAuthCode" class="form-control text-center" name="ConfirmAuthCode" forminputgroup="twofactorform" title="Authentication Code" autocomplete="false" value="" required="required" placeholder="Authentication Code Here" data-plugin="formatter" data-pattern="[[999999]]">
            </div>
            <span class="input-group-btn">
              <button type="button" id="btnConfirmAuthCode" name="btnTwoFactor" class="btn btn-dark waves-effect waves-classic" forminputgroup="twofactorform">Submit</button>
            </span>
          </div>
     	</div>
      </div>
    </div>
	</div>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

	$("button[id='btnConfirmAuthCode']").click(function(){

			if( !$("input[id='ConfirmAuthCode'][forminputgroup='twofactorform']").val() )
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
          title: "Invalid Authentication Code",
          text: 'Please enter Authentication Code',
          icon: "error",
          confirmButtonText: 'Close',
        });
      }
      else
      {

			 	var formdata = new FormData();
		      	formdata.append('confirmauthcode',btoa($("input[id='ConfirmAuthCode'][forminputgroup='twofactorform']").val()));
		      	formdata.append('secret',btoa('<?php echo $secret; ?>'));
		      	var confirmAuthResult = function(pp,rp){
		            Swal.mixin({
		              customClass: {
		                confirmButton: 'btn btn-dark',
		              },
		              buttonsStyling: false,
		              willOpen: (fnRun) => {
		                $(".swal2-container").css('z-index',$.topZIndex());
		              }
		            }).fire({
		              title: "",
		              html: ( (rp.Status == 1) ? 'Two-Factor Authentication Successfully Configured' : 'Failed to Verify Authencication Code!'),
		              icon: ( (rp.Status == 1) ? 'success' : 'error'),
		              confirmButtonText: 'Close',
		            }).then((result) => {
		            	if( rp.Status == 1)
		            	{
		            		window.location.href=site_url+'logout';
		            	}
		            	else
		            	{
		            		$("input[id='ConfirmAuthCode'][forminputgroup='twofactorform']").val('');
		            	}
		            });
		        };

		        target_url = site_url+'twofactorsetup/verifyauthcode'
		        submitFormData('json','',target_url,formdata,confirmAuthResult,true);
      }
	});

});
</script>

