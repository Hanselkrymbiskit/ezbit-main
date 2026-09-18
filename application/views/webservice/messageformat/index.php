<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$responseFormatMain = array(
	'APIKey'				=> 'Client API Key',
	'ResponseFormat'	=> 'XML or JSON',
	'Content'				=> 'Base64 Encoded - Encrypted String'
);

$responseFormatMain2 = array(
	'APIKey'				=> 'zNyeUJ1ITFrMWwQJNh59GQ==',
	'ResponseFormat'	=> 'XML',
	'Content'				=> 'ShbhW8h/WDmQQNyAjBVKt/3bwOW3qbfaUlms0VbYDNmB9CsfjWntAFccnyd1UWvgy2TS8xJxLXdxeAE7So5LGtZ6q5zK6SEzXJnQButiGQgdZSJPk15sLL72UjjW884L+p8wGktv9OEjNiovIZcEb6l0xLfX12tzf7+2DJEFpVHZDUVWE+iED4OjfU2agOYFXnvCMY3gh9xfqAZwclXBMQ143tBE0yoQGzBfeAb9gqNnGR5FlF9bKruhXh+07cz5rDaQgHOdu3U7Y1a0jGgf3G5CP9EInS9Zt2cjCY13PbI8MjvbjwBrMl5v4csMJksLKfcOMpkeplgiyYHyP91wsczC2S3K7UQfn8dlAyDw0SJXbtOf8vQJKnPjD4oMG1LXURhgJaayVjLRnduMnX5qUgiqg7fHihubKcJchQXAV8knvLZ2mHxHNkEC3wA0tK3sYL6I4XSHP9bHnfJKs/Euqv2JAPI1jE+Ys+78BSSKLw9+mNoHkkZ2xZJXQhAX9dJ/KA/UlGHx+PYwT91zyNyi1S1BZMeCkI55xI0tdsKuiIRnc/P04uRz0J3TunZId+Fmvg1vg9eO0T19w7pEdunypfhKsoGdnSb4FeOpAFjWR55HGLH6pcKU4gYKSNlsrQOeHrT+snyGDBHcput9iKEd8Pn9htYDTMDBPbNkC+SwZjvgm9/MvKTf9v2xfLw08Z95fiX+I3jQF+fkNp3vRr6O1D8Caa6Rn7cTYUngNz10saXrfOl38t7FOpETQZnMl5PD'
);
?>

<div class="card border border-info p-0  col-sm-12 col-lg-12" style="width: 100%;font-weight: normal !important">
	<div class="card-body">
		<h4>Standard Message Format</h4>
		<p>API Client is required to submit/transmit the required parameter for a successfull API Call.</p>
		<div class="">
			<table class="table table-sm" style="margin:0px !important">
	  			<thead>
	  				<tr>
	  					<td colspan="2" class="font-weight-bold border-top-0">Message Parameter</td>
	  				</tr>
	  			</thead>
	  			<tbody>
	  				<tr>
	  					<td style="text-align: center">APIKey</td>
	  					<td>: Contains Client API Authentication Key</td>
	  				</tr>
	  				<tr>
	  					<td style="text-align: center">ResponseFormat</td>
	  					<td>: Type of API Response Format [ XML or JSON ]</td>
	  				</tr>
	  				<tr>
	  					<td style="text-align: center">Content</td>
	  					<td>: Contains BASE64 Enocded String of AES Encrypted JSON API Method Parameter</td>
	  				</tr>
	  			</tbody>
	  		</table>
		</div>
	</div>
</div>

<div class="">
	<div class="card border-info float-left" style="width: 49%;font-weight: normal !important">
		<div class="card-body">
		  	<h6>SOAP Parameter Format</h6>
		  	<div style="background: #646464; color: #fff; border-radius: 5px;">
		  			<pre style="color:#fff"><?php echo htmlspecialchars(str_replace('DOH', 'SOAP-ENV:Body', $CI->myutilities->array_to_xmlformat_string($responseFormatMain))); ?>
		  				
		  			</pre>
		  	</div>
	  	</div>
	</div>

	<div class="card border-info float-right" style="width: 49%;margin-left:2%;font-weight: normal !important">
	  	<div class="card-body">
		  	<h6>REST [POST] Parameter Format</h6>
		  	<div style="background: #646464; color: #fff; border-radius: 5px;">
		  			<pre style="color:#fff"><?php  print_r($responseFormatMain); ?></pre>
		  	</div>
	  	</div>
	</div>

</div>

<div class="card border-warning p-0 col-sm-12 col-lg-12" style="width: 100%;font-weight: normal !important">
	<div class="card-body" style="color:#000">
		<h6>Please refer to <a class="text-primary" href="<?php echo site_url('api/responseformat'); ?>" style="text-decoration: none;color:#000">API Response Format</a> for detailed information of API Response</h6>
	</div>
</div>

<div class="">
	<div class="card border-info float-left" style="width: 49%;font-weight: normal !important">
		<div class="card-body">
		  	<h6>SOAP Parameter Format  <span class="float-right">Sample Value</span></h6>
		  	<div style="background: #646464; color: #fff; border-radius: 5px;">
	  			<pre style="color:#fff"><?php echo htmlspecialchars(str_replace('DOH', 'SOAP-ENV:Body', $CI->myutilities->array_to_xmlformat_string($responseFormatMain2))); ?>
	
	  			</pre>
		  	</div>
	  	</div>
	</div>

	<div class="card border-info float-right" style="width: 49%;margin-left:2%;font-weight: normal !important">
	  	<div class="card-body">
		  	<h6>REST [POST] Parameter Format <span class="float-right">Sample Value</span></h6>
		  	<div style="background: #646464; color: #fff; border-radius: 5px;">
	  			<pre style="color:#fff"><?php  print_r($responseFormatMain2); ?></pre>
		  	</div>
	  	</div>
	</div>
</div>

<div class="card border-warning p-0 col-sm-12 col-lg-12" style="width: 100%;font-weight: normal !important">
	<div class="card-body" style="color:#000">
		<h6>ResponseFormat</h6>
		<p>You can specify XML or JSON for both API Protocol [ SOAP / REST ]. API will use the specified ResponseFormat whether you call via SOAP or REST.</p>
	</div>
</div>

<script type="text/javascript">

</script>