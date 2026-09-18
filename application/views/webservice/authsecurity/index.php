<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$responseFormatMain = array(
	'APIKey'				=> 'Client API Key',
	'ResponseFormat'	=> 'XML or JSON',
	'Content'				=> 'Encrypted String'
);

$responseFormatMain2 = array(
	'APIKey'				=> 'zNyeUJ1ITFrMWwQJNh59GQ==',
	'ResponseFormat'	=> 'XML',
	'Content'				=> 'ShbhW8h/WDmQQNyAjBVKt/3bwOW3qbfaUlms0VbYDNmB9CsfjWntAFccnyd1UWvgy2TS8xJxLXdxeAE7So5LGtZ6q5zK6SEzXJnQButiGQgdZSJPk15sLL72UjjW884L+p8wGktv9OEjNiovIZcEb6l0xLfX12tzf7+2DJEFpVHZDUVWE+iED4OjfU2agOYFXnvCMY3gh9xfqAZwclXBMQ143tBE0yoQGzBfeAb9gqNnGR5FlF9bKruhXh+07cz5rDaQgHOdu3U7Y1a0jGgf3G5CP9EInS9Zt2cjCY13PbI8MjvbjwBrMl5v4csMJksLKfcOMpkeplgiyYHyP91wsczC2S3K7UQfn8dlAyDw0SJXbtOf8vQJKnPjD4oMG1LXURhgJaayVjLRnduMnX5qUgiqg7fHihubKcJchQXAV8knvLZ2mHxHNkEC3wA0tK3sYL6I4XSHP9bHnfJKs/Euqv2JAPI1jE+Ys+78BSSKLw9+mNoHkkZ2xZJXQhAX9dJ/KA/UlGHx+PYwT91zyNyi1S1BZMeCkI55xI0tdsKuiIRnc/P04uRz0J3TunZId+Fmvg1vg9eO0T19w7pEdunypfhKsoGdnSb4FeOpAFjWR55HGLH6pcKU4gYKSNlsrQOeHrT+snyGDBHcput9iKEd8Pn9htYDTMDBPbNkC+SwZjvgm9/MvKTf9v2xfLw08Z95fiX+I3jQF+fkNp3vRr6O1D8Caa6Rn7cTYUngNz10saXrfOl38t7FOpETQZnMl5PD'
);
?>

<div class="card border border-info p-0  col-sm-12 col-lg-12">
    <div class="card-block">
      <h4 class="card-title">Authentication Key Set</h4>
      <p class="card-text">Pair of Keys used in API Call for Authentication and Encryption/Decryption. This Key Sets is derived from the User Login Credential, any changes on the User Credential will automatically change the API Authentication Key Sets.</p>

      <table class="table table-sm" style="margin:0px !important">
  			<tbody>
  				<tr>
  					<td class="font-weight-bold" style="text-align: center">APIKey</td>
  					<td>: Used for API Authentication</td>
  				</tr>
  				<tr>
  					<td class="font-weight-bold" style="text-align: center">EncryptionKey</td>
  					<td>: Used as Encryption/Decryption salt for Sending Paramater or Receiving API Response</td>
  				</tr>
  			</tbody>
  		</table>

    </div>
</div>

<div class="card border border-info p-0  col-sm-12 col-lg-12" style="width: 100%;font-weight: normal !important">
  <div class="card-body">
  	<h6>Getting API KEY & ENCRYPTION KEY</h6>
  	<table class="table" style="margin-bottom: 0px">
		<tbody>
			<tr>
				<td class="border-info" style="width:33.33%;height:350px;border-top:0px;border-right: 1px solid;">
					<h6 class="text-info" style="text-align: center"><span class="badge badge-info" style="width:20px;margin-right: 5px">1</span>Login to System</h6>
					<img src="<?php echo getimagePath(); ?>api/authsecurity/Step_01.png" style="width: 100%;height: 94%">
				</td>
				<td class="border-info" style="width:33.33%;height:350px;border-top:0px;border-right: 1px solid;">
					<h6 class="text-info" style="text-align: center"><span class="badge badge-info" style="width:20px;margin-right: 5px">2</span>Navigate to User Menu. Click API Auth</h6>
					<img src="<?php echo getimagePath(); ?>api/authsecurity/Step_02.png"  style="width: 100%;height: 94%">
				</td>
				<td style="width:33.33%;height:350px;border-top:0px">
					<h6 class="text-info" style="text-align: center"><span class="badge badge-info" style="width:20px;margin-right: 5px">3</span>View & Copy API Authentication </h6>
					<img src="<?php echo getimagePath(); ?>api/authsecurity/Step_03.png"  style="width: 100%;height: 94%">
				</td>
			</tr>
		</tbody>
	</table>
  </div>
</div>

<div class="card border border-info p-0  col-sm-12 col-lg-12" style="width: 100%;font-weight: normal !important">
  <div class="card-body">
  	<h4>Encryption / Decryption</h4>
  	<p class="card-text">Advanced Encryption Standard(AES) is a symmetric encryption algorithm. AES is the industry standard as of now. AES allows 128, 192 and  256 bit encryption. Symmetric encryption is very fast as compared to asymmetric encryption and are used in systems such as database system.</p>
  	<p>AES is used by this API as a standard Data Security for all data transmission.</p>
  	<div>
  		<table class="table table-sm" style="">
  			<thead>
  				<tr>
  					<td colspan="2" class="bg-dark text-white" style="text-align:center">AES Configuration</td>
  				</tr>
  			</thead>
  			<tbody>
  				<tr>
  					<td style="width:50%;text-align: center">CIPHER NAME</td>
  					<td>: <b style="margin-left:10%">AES-128</b></td>
  				</tr>
  				<tr>
  					<td style="text-align: center">KEY LENGTHS [ bits / bytes ]</td>
  					<td>: <b style="margin-left:10%">128 / 16</b></td>
  				</tr>
  				<tr>
  					<td style="text-align: center">MODES</td>
  					<td>: <b style="margin-left:10%">CBC</b></td>
  				</tr>
  			</tbody>
  		</table>
  	</div>

  	<div class="card p-0 border-0" style="width: 100%;font-weight: normal !important">
	  <div class="card-body p-0">
	  	<h4>HOW IT WORKS</h4>
	  	<div>
	  		<div>
				<div style="margin-top: 1rem">
					<h7 class="text-secondary"><b>Encryption</b></h7>
					<ol style="margin-top: 1rem">
	  				<li>Generate a random initialization vector (IV).</li>
	  				<li>Encrypt the data via AES-128 in CBC mode, using the above-mentioned derived encryption key and IV.</li>
	  				<li>Prepend said IV to the resulting cipher-text.</li>
	  				<li>Base64-encode the resulting string, so that it can be safely stored or transferred without worrying about character sets.</li>
	  			</ol>
				</div>
				<div style="margin-top: 1rem">
					<h7 class="text-secondary"><b>Decryption</b></h7>
					<ol style="margin-top: 1rem">
	  				<li>Base64-decode the string.</li>
	  				<li>Retrieve the IV from the Decoded String.</li>
	  				<li>Separate the IV out of the cipher-text and decrypt the said cipher-text using that IV and the derived encryption key.</li>
	  			</ol>
				</div>
			</div>
			
	  	</div>

	  	<div>
			<h6 class="text-secondary">Try it Now</h6>
			<div>
				<form>
					<div>
					  	<div class="form-row align-items-center">
							<div class="col-auto">
								<label class="sr-only" for="inlineFormInputGroup"></label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text">Encryption Key</div>
									</div>
									<input type="text" id="generatedkeys" name="Form_Data" class="form-control" id="inlineFormInputGroup" placeholder="" autocomplete="off" style="width:300px;text-align: center">
								</div>
							</div>
							<div class="col-auto">
								<button type="button" class="btn btn-primary mb-2" id="btn_generateKey" name="btn_authButton">Generate Key</button>
							</div>
							<div class="col-auto">
								<button type="button" class="btn btn-primary mb-2" id="btn_EncryptString" name="btn_authButton">Encrypt</button>
							</div>
							<div class="col-auto">
								<button type="button" class="btn btn-primary mb-2" id="btn_DecryptString" name="btn_authButton">Decrypt</button>
							</div>
						</div>
				  	</div>
				  	<div class="card border border-secondary bg-dark">
		                <div class="card-block p-5">
		                  	<h4 class="card-title text-white">Input</h4>
		                  	<textarea class="form-control border-light" style="width:100%;height:200px;resize:none" id="InputString" name="Form_Data"></textarea>	
		                </div>
		            </div>
		            <div class="card border border-secondary bg-success">
		                <div class="card-block p-5">
		                  	<h4 class="card-title text-black">Output</h4>
		                  	<textarea class="form-control border-light" style="width:100%;height:200px;resize:none"  id="OutputString" name="Form_DataOutput"></textarea>	
		                </div>
		            </div>
		  			
				</form>
			</div>
		</div>
	  </div>
	</div>

  </div>
</div>


<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

$("button[name*='btn_authButton']").click(function(){
	switch( $(this).attr('id') )
	{
		case "btn_generateKey":
			getEncryptionKey();
		break;

		case "btn_EncryptString":
			TryEncryptDecrypt(1,$(this));
		break;

		case "btn_DecryptString":
			TryEncryptDecrypt(2,$(this));
		break;
	}
});

</script>