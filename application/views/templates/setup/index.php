<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();



?>

<div class="page-brand-info">
	<div class="brand mb-5">
		<img class="brand-img" src="<?php echo assetPath(); ?>images/Brand.png" alt="..." style="height: 140px">
		<h1 class="brand-text font-size-60" style="font-size: 100px !important;margin-top: -50px">
			<?php echo @$CI->system_settings['ApplicationAbbre']; ?>
		</h1>
		
	</div>
	<h5 class="brand-text font-size-60" style="font-size: 20px !important;margin-left: 168px;margin-top: -90px;">
	<?php echo @$CI->system_settings['ApplicationName']; ?>
	</h5>		
	<p class="font-size-20"><?php echo @$CI->system_settings['ApplicationDescription']; ?></p>
</div>

<div class="page-login-main" style="padding:40px 60px 40px !important">
	<div class="brand hidden-md-up text-center mb-20">
		<img class="brand-img" src="<?php echo assetPath(); ?>images/logo-colored@2x.png" alt="..." style="height:45px;width:45px">
		<h3 class="brand-text font-size-40" style="color: #4CAF4E;"><?php echo @$CI->system_settings['ApplicationAbbre']; ?></h3>
		<h6 class="text-success mt-0"><?php echo @$CI->system_settings['ApplicationName']; ?></h6>
	</div>
	<h3 class="font-size-24">Admin Account Setup</h3>
	<p class="hidden-xs-down"></p>

	<form id="SetupForm" method="POST" autocomplete="off">
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<input type="password" class="form-control empty" id="PasswordHashing" name="PasswordHashing" forminputgroup="AdminForm_Data" required="required" maxlength="32" minlength="8">
			<label class="floating-label text-danger" for="PasswordHashing">Encryption Keys</label>
		</div>
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<input type="text" class="form-control empty" id="username" name="username" forminputgroup="AdminForm_Data" required="required">
			<label class="floating-label text-danger" for="username">Username</label>
		</div>
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<input type="text" class="form-control empty" id="email" name="email" forminputgroup="AdminForm_Data" required="required">
			<label class="floating-label  text-danger" for="email">Email Address</label>
		</div>
		<div class="form-group form-material" data-plugin="formMaterial">
			<label class="label text-danger" for="password">Password</label><input type="password" class="form-control empty" id="password" name="password" forminputgroup="AdminForm_Data" minlength="<?php echo @$CI->system_settings['PasswordLength_Min']; ?>" maxlength="<?php echo @$CI->system_settings['PasswordLength_Max']; ?>" data-plugin="strength" required="required">
			<div class="hint" style="width:100%"><span id="match_password" class="float-right" style="display:none">Password and Confirm Password Doesn't Match</span></div>
		</div>
		<div class="form-group form-material" data-plugin="formMaterial">
			<label class="label text-danger" for="confirmpassword">Confirm Password</label><input type="password" class="form-control empty" id="confirmpassword" name="confirmpassword" forminputgroup="AdminForm_Data" minlength="<?php echo @$CI->system_settings['PasswordLength_Min']; ?>" maxlength="<?php echo @$CI->system_settings['PasswordLength_Max']; ?>" data-plugin="strength" required="required">
			<div class="hint" style="width:100%"><span id="match_confirmpassword" class="float-right" style="display:none">Password and Confirm Password Doesn't Match</span></div>
			
		</div>
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<input type="text" class="form-control empty" id="Lastname" name="Lastname" forminputgroup="AdminForm_Data" required="required">
			<label class="floating-label text-danger" for="Lastname">Last Name</label>
		</div>
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<input type="text" class="form-control empty" id="Firstname" name="Firstname" forminputgroup="AdminForm_Data" required="required">
			<label class="floating-label text-danger" for="Firstname">First Name</label>
		</div>
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<input type="text" class="form-control empty" id="Middlename" name="Middlename" forminputgroup="AdminForm_Data" required="required">
			<label class="floating-label  text-danger" for="Middlename">Middle Name</label>
		</div>
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<select class="form-control empty" id="Suffixname" name="Suffixname" forminputgroup="AdminForm_Data" required="required"></select>
			<label class="floating-label" for="Suffixname">Suffix Name</label>
		</div>
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<input type="text" class="form-control empty" id="dateofbirth" name="dateofbirth" forminputgroup="AdminForm_Data" required="required" data-plugin="formatter,datepicker" data-pattern="[[99]]/[[99]]/[[9999]]">
			<label class="floating-label text-danger" for="Lastname">Date of Birth</label>
		</div>
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<select class="form-control empty" id="Sex" name="Sex" forminputgroup="AdminForm_Data" required="required"></select>
			<label class="floating-label" for="Sex">Sex</label>
		</div>
		<button type="button" id="btn_Configure" name="btnAction" class="btn btn-primary btn-block">Configure</button>
		
	</form>	

</div>	

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

$(document).ready(function(){
	
	getreferencevalue({objselect:$("select[id='Sex'][name='Sex']"),referenceid:'51',filter:'',order:''});
   	getreferencevalue({objselect:$("select[id='Suffixname'][name='Suffixname']"),referenceid:'55',filter:'',order:''});	
	

   	$("input[id='password'],input[id='confirmpassword']").blur(function(){
      if( $(this).val() && $("input[id='"+( ($(this).attr('id') == 'password') ? 'confirm' : '')+"password']").val() )
      {
        if( $(this).val() !== $("input[id='"+( ($(this).attr('id') == 'password') ? 'confirm' : '')+"password']").val() )
        {
          $(this).val('');
          $("span[id='match_password'],span[id='match_confirmpassword']").parent().show();
          $("span[id='match_password'],span[id='match_confirmpassword']").show();
        }
        else
        {
          $("span[id='match_password'],span[id='match_confirmpassword']").parent().hide();
          $("span[id='match_password'],span[id='match_confirmpassword']").hide();
        }
      }
   	});

	$("button[id='btn_Configure']").click(function(){
		var AdminData = getFormData('AdminForm_Data');
		if( Object.keys(AdminData['ErrorData']).length > 0 )
        {
            popFormDataError(AdminData['ErrorData'],'Admin Account Setup','');      
        } 
		else
		{
			var posturl = site_url+'setup/submit';
			var postresult = function(pp,rp){
				Swal.mixin({
				customClass: {
					confirmButton: 'btn btn-dark',
				},
				buttonsStyling: false,
				willOpen: (fnRun) => {
					$(".swal2-container").css('z-index',$.topZIndex());
				}
				}).fire({
					title: "Admin Account Setup",
					html: rp.Message,
					icon: ((rp.Status == 0) ? 'error' : 'success'),
					confirmButtonText: 'Close',
				}).then((result) => {
					if( rp.Status == 1 )
					{ 
						window.location.href=site_url+'login';
					}        
              	});
			};
			submitFormData('json','',posturl,AdminData['FormData'],postresult,true); 
		}
	});
});

</script>
