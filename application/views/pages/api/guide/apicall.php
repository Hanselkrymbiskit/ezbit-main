<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


?>

<div class="card mb-5">
	<div class="card-block p-5">
		<h6>HEADER PARAMETER</h6>
		<table class="table table-bordered table-strip mb-0">
			<thead>
				<tr>
					<th>KEY</th>
					<th>VALUE</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>NHDR-API-KEY</td>
					<td>YOUR API KEY</td>
				</tr>
				<tr>
					<td>CONTENT-TYPE</td>
					<td>TEXT/PLAIN</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="card">
	<div class="card-block p-5">
		<h6>SAMPLE CALL : POSTMAN</h6>
		
		<div class="card border-0">
			<div class="card-block p-5">
				<h6>Header Parameter</h6>
				<h7 class="text-danger"> - Set URL to <?php echo site_url('fhir/{ResourceType}'); ?></h7><br>
				<h7 class="text-danger"> - Example URI <?php echo site_url('fhir/Patient'); ?></h7><br>
				<img src="<?php echo assetPath(); ?>images/api/guide/apicall_001.png" style="width:100%">
			</div>
		</div>

		<div class="card border-0">
			<div class="card-block p-5">
				<h6>Body Content</h6>
				<h7 class="text-danger"> - Set Content-Type : text/plain</h7><br>
				<h7 class="text-danger"> - Encrypt the FHIR Profle JSON</br>
				<h7 class="text-danger"> - Paste the Encrypted Payload (FHIR Profle JSON)</h7>
				<img src="<?php echo assetPath(); ?>images/api/guide/apicall_002.png" style="width:100%">
			</div>
		</div>

		<div class="card border-0">
			<div class="card-block p-5">
				<h6>API Call Response Content</h6>
				<h7 class="text-danger"> - Decrypt the Encrypted Response Content using your Encryption KEY</h7>
				<img src="<?php echo assetPath(); ?>images/api/guide/apicall_003.png" style="width:100%">
			</div>
		</div>
	</div>
</div>



<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){ 
	$("i[name*='copytoclipboard']").click(function(){
		toastr.success(($(this).attr('title')+' FHIR JSON PAYLOAD'),'COPY TO CLIPBOARD');
		copyToClipboard($("pre[id='"+$(this).attr('target')+"']").text());
	});
});
</script>