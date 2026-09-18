<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$responseFormatMain = array(
		'Code'				=> 'Contains Response Code',
		'Description' => 'Contains Response Description',
		'Content'			=> 'Contains Encrypted String [ String / (XML or JSON) ]'
	);

$responseFormatMain103 = array(
		'Code'				=> '105',
		'Description' => 'Invalid Parameter',
		'Content'			=> array(
											'Required_Content_Count'	=> 2,
											'Submitted_Content_Count'	=> 2,
											'Unknown_Content_Count'		=> 1,
											'Missing_Content_Count'		=> 1,
											'Invalid_Content_Count'		=> 1,
											'RequiredDetails'					=> array(
																										'Field_Name1'=>'Value Format',
																										'Field_Name2'=>'Value Format',
																									),
											'SubmitteddDetails'					=> array(
																										'Field_Name1'=>'Value',
																										'Field_Name3'=>'Value',
																									),
											'UnknownDetails'					=> array(
																										'Field_Name3'=>'Value Format',
																									),
											'MissingDetails'					=> array(
																										'Field_Name2'=>'Value Format',
																									),
											'InvalidDetails'					=> array(
																										'Field_Name1'=>'Invalid Value Format',
																									),
										)
	);

?>

<div class="row" style="width: 100%;font-size:14px !important">
	<div class="card bg-info" style="width: 100%;font-weight: normal !important">
	  <div class="card-body" style="color:#000">
	  	<i class="fas fa-info-circle" style="margin-right: 1rem"></i> <b style="color:#000;">API Standard Response Code for all Methods.</b> Please be reminded that specific API Method have a defined response code.
	  	
	  </div>
	</div>
</div>

<div class="row" style="width: 100%;font-size:14px !important;margin-top: 1rem">
	<div class="card border-info " style="width: 100%;font-weight: normal !important">
	  <div class="card-body">
	  	<table class="table table-sm table-border" style="margin-bottom:0px">
	  		<thead>
	  			<tr>
	  				<th style="font-weight:normal;text-align: center;border:0px solid #ccc;">Code</th>
	  				<th style="font-weight:normal;text-align: left;padding-left:2rem;border:0px solid #ccc;">Description</th>
	  				<th style="font-weight:normal;text-align: center;border:0px solid #ccc;">Specific Method</th>
	  				<th style="font-weight:normal;text-align: center;border:0px solid #ccc;">Return Type</th>
	  			</tr>
	  		</thead>
	  		<tbody>
	  			<?php

	  				$gRL = $CI->sqlhelper->local->select("ws_response_code")->where("Code in ('100','101','102','103','104','105')")->ex_select("","","")->result();
	  				foreach($gRL['Data'] as $r => $c)
	  				{
	  					echo '
							<tr>
			  				<td style="font-weight:normal;text-align: center;">'.$c['Code'].'</td>
			  				<td style="font-weight:normal;text-align: left;padding-left:2rem">'.$c['Description'].'</td>
			  				<td style="font-weight:normal;text-align: center;">'.(($c['FunctionID'] == "") ? '<i class="fas fa-ban"></i>' : '').'</td>
			  				<td style="font-weight:normal;text-align: center;">'.$c['ReturnType'].'</td>
			  			</tr>
	  					';
	  				}
	  			?>
	  		</tbody>
	  	</table>
	  	<br>
	  	<p class="text-info"><a href="<?php echo site_url('api/responsecode'); ?>" style="text-decoration: none">Please refer to the API Response Code for the Complete List.</a></p>
	  </div>
	</div>
</div>

<div class="row" style="width: 100%;font-size:14px !important;margin-top: 1rem">
	<div class="card bg-success" style="width: 100%;font-weight: normal !important">
	  <div class="card-body" style="color:#000">
	  	<i class="fas fa-info-circle" style="margin-right: 1rem"></i> <b style="color:#000;">API Standard Response Format : </b><br>
	  </div>
	</div>
</div>


<div class="row" style="width: 100%;font-size:14px !important;margin-top: 1rem">
	<div class="card border-success float-left" style="width: 49%;font-weight: normal !important">
	  <div class="card-body">
	  	<h6>XML Format</h6>
	  	<div style="border: 1px solid #ccc; padding: 5px;  background: #646464; color: #fff; border-radius: 5px;">
	  			<pre style="color:#fff"><?php echo htmlspecialchars($CI->myutilities->array_to_xmlformat_string($responseFormatMain)); ?></pre>
	  	</div>
	  </div>
	</div>

	<div class="card border-success float-right" style="width: 49%;margin-left:2%;font-weight: normal !important">
	  <div class="card-body">
	  	<h6>JSON Format</h6>
	  	<div style="border: 1px solid #ccc; padding: 5px;  background: #646464; color: #fff; border-radius: 5px;">
	  			<pre style="color:#fff"><?php echo htmlspecialchars(json_encode($responseFormatMain,JSON_PRETTY_PRINT)); ?></pre>
	  	</div>
	  </div>
	</div>
</div>

<div class="row" style="width: 100%;font-size:14px !important;margin-top: 1rem">
	<div class="card border-success float-left" style="width: 49%;font-weight: normal !important">
	  <div class="card-body">
	  	<?php
	  		$responseFormatMain['Content'] = 'L/mIsitu466gq3iVYLL178y4SiRb1j3nV0waWIdLTB+VzSYBKKg2YD0kJdV/Swdn1Ui9FnuCaqg+Mw0hU+nzy30fGVtzpPS0C0YEMFsRiiPPhuLV+y5IUuGE/LVGqTvmGKm+ZfA7KmD+G2eJhCKNy4Q0QujoYlRzTuEr6BoZ69XDZqLsYYOYb7hKRUCRsCNZMTNzd5Yan3/jJy9AjrgCF/eZ/vI+nzWGpJxKJ8/PNx5H7Q5hzDm3+AByTBxhjo9pBwtsH7Oytksg96A/n3G/2Zn4i2mGYU07K11pGdthnCVrq087FesWqwFVGsA7RZ32neL2ZSrJhum1K/Iz6ymDpxWqr4FU5vnOrv8+m2oBJXRf8+0UjwvTr8/WHVk2TVIE0ti3J8L+kuapUbdND0fVW0QP3KkKkL6ZimBSoR/JwswbRvoK+SMW5iXuiQ9xUipDO9SqJsVcxk9E8QGmXnNbInn0idi4/ig7mwxQcE7ILEowAPJJXJdz8OBS1Q4Vn11C7VMEtxQqfzSazKJmHRSaUQ74dLm6QlVgzwFta8ClD6UpBDPkb8BeJ5pREkv43zc4K7ake6npdgjJF7xDW3UvSVvTDE1axhB4SEWJ/tCaLsd8PKh1WBxF1MeBVs6PvVTpwKALmYsOZdTLKkZsd8TY/x7DZxdO/Lwqys4UAyXbqFxdYgxhJyPDeunGnFSu05KCwhi7UwXynjdELZwBKTZabZJFxffMqPmGvEk1yqkHlKAU9AkhWPaH72tbjlgn68VfG6zC5T+D50OQuSNH21iokAfmDjm+Y5UqcZ2ZRriBd82T/JSbqD5nq3g0ALUs/zGF4IShu66c3e55XjS2p9lcSLhTdzc3VMr3umxjFEARq3Di0BXWOiyz4Ws73Otym4mZYMZYgsYXxA2GUPZjyIbKSA==';
	  	?>
	  	<div style="border: 1px solid #ccc; padding: 5px;  background: #646464; color: #fff; border-radius: 5px;">
	  			<pre style="color:#fff"><?php echo htmlspecialchars($CI->myutilities->array_to_xmlformat_string($responseFormatMain)); ?></pre>
	  	</div>
	  </div>
	</div>

	<div class="card border-success float-right" style="width: 49%;margin-left:2%;font-weight: normal !important">
	  <div class="card-body">
	  	<?php
	  		$responseFormatMain['Content'] = 'ShbhW8h/WDmQQNyAjBVKt/3bwOW3qbfaUlms0VbYDNmB9CsfjWntAFccnyd1UWvgy2TS8xJxLXdxeAE7So5LGtZ6q5zK6SEzXJnQButiGQgdZSJPk15sLL72UjjW884L+p8wGktv9OEjNiovIZcEb6l0xLfX12tzf7+2DJEFpVHZDUVWE+iED4OjfU2agOYFXnvCMY3gh9xfqAZwclXBMQ143tBE0yoQGzBfeAb9gqNnGR5FlF9bKruhXh+07cz5rDaQgHOdu3U7Y1a0jGgf3G5CP9EInS9Zt2cjCY13PbI8MjvbjwBrMl5v4csMJksLKfcOMpkeplgiyYHyP91wsczC2S3K7UQfn8dlAyDw0SJXbtOf8vQJKnPjD4oMG1LXURhgJaayVjLRnduMnX5qUgiqg7fHihubKcJchQXAV8knvLZ2mHxHNkEC3wA0tK3sYL6I4XSHP9bHnfJKs/Euqv2JAPI1jE+Ys+78BSSKLw9+mNoHkkZ2xZJXQhAX9dJ/KA/UlGHx+PYwT91zyNyi1S1BZMeCkI55xI0tdsKuiIRnc/P04uRz0J3TunZId+Fmvg1vg9eO0T19w7pEdunypfhKsoGdnSb4FeOpAFjWR55HGLH6pcKU4gYKSNlsrQOeHrT+snyGDBHcput9iKEd8Pn9htYDTMDBPbNkC+SwZjvgm9/MvKTf9v2xfLw08Z95fiX+I3jQF+fkNp3vRr6O1D8Caa6Rn7cTYUngNz10saXrfOl38t7FOpETQZnMl5PD';
	  	?>
	  	<div style="border: 1px solid #ccc; padding: 5px;  background: #646464; color: #fff; border-radius: 5px;">
	  			<pre style="color:#fff"><?php echo htmlspecialchars(json_encode($responseFormatMain,JSON_PRETTY_PRINT)); ?></pre>
	  	</div>
	  </div>
	</div>
</div>

<div class="row" style="width: 100%;font-size:14px !important;margin-top: 1rem">
	<div class="card bg-warning" style="width: 100%;font-weight: normal !important">
	  <div class="card-body" style="color:#000">
	  	<i class="fas fa-info-circle" style="margin-right: 1rem"></i> <b style="color:#000;">Content : </b>Contains Encrypted String (Normal String/XML String/JSON String)<br><br>

					<ol>
						<li>Each API Methods have a different response contents. kindly refer to specific API Methods</li>
						<li>Response Content is NULL/EMPTY for Response Code [ 100,101,102,103,104 ]</li>
					</ol>
		
	  </div>
	</div>
</div>

<div class="row" style="width: 100%;font-size:14px !important;margin-top: 1rem">
	<div class="card border-warning" style="width: 100%;font-weight: normal !important">
	  <div class="card-body">
	  	<h4>Response Code : 105</h4>
	  	<div class="border-warning" style="border:1px solid #ccc;padding:5px;border-radius: 5px;margin-bottom:1rem">
	  		<table class="table table-sm" style="margin:0px !important">
	  			<thead>
	  				<tr>
	  					<td colspan="2" class="bg-dark text-white">Content Parameter</td>
	  				</tr>
	  			</thead>
	  			<tbody>
	  				<tr>
	  					<td style="text-align: center">Required_Content_Count</td>
	  					<td>: Total Number of Required Parameter Content</td>
	  				</tr>
	  				<tr>
	  					<td style="text-align: center">Submitted_Content_Count</td>
	  					<td>: Total Number of Submitted Parameter Content</td>
	  				</tr>
	  				<tr>
	  					<td style="text-align: center">Unknown_Content_Count</td>
	  					<td>: Total Number of Unknown Parameter Content</td>
	  				</tr>
	  				<tr>
	  					<td style="text-align: center">Missing_Content_Count</td>
	  					<td>: Total Number of Missing from Required Parameter Content</td>
	  				</tr>
	  				<tr>
	  					<td style="text-align: center">Invalid_Content_Count</td>
	  					<td>: Total Number of Invalid Parameter Content</td>
	  				</tr>
	  				<tr>
	  					<td style="text-align: center">RequiredDetails</td>
	  					<td>: Contains Detailed Information of Required Parameter. [ Parameter Key = Value Format ]</td>
	  				</tr>
	  				<tr>
	  					<td style="text-align: center">SubmitteddDetails</td>
	  					<td>: Contains Detailed Information of Submitted Parameter. [ Parameter Key = Format ]</td>
	  				</tr>
	  				<tr>
	  					<td style="text-align: center">UnknownDetails</td>
	  					<td>: Contains Detailed Information of Unknown Parameter. [ Parameter Key = Value ]</td>
	  				</tr>
	  				<tr>
	  					<td style="text-align: center">MissingDetails</td>
	  					<td>: Contains Detailed Information of Missing Parameter. [ Parameter Key = Value Format ]</td>
	  				</tr>
	  				<tr>
	  					<td style="text-align: center">InvalidDetails</td>
	  					<td>: Contains Detailed Information of Invalid Parameter. [ Parameter Key = Value Format ]</td>
	  				</tr>
	  			</tbody>
	  		</table>
	  	</div>
	  	<div style="">
		  		<div class="card float-left" style="width: 49%;font-weight: normal !important;border:0px  !important">
					  <div class="card-body" style="padding:2px;border:0px !important">
					  	<h6>XML Format</h6>
					  	<div style="padding: 5px;  background: #646464; color: #fff; border-radius: 5px;">
						  		
					  			<pre style="color:#fff"><?php echo htmlspecialchars($CI->myutilities->array_to_xmlformat_string($responseFormatMain103)); ?></pre>
					  	</div>
					  </div>
					</div>

					<div class="card float-right" style="width: 49%;margin-left:2%;font-weight: normal !important;border:0px  !important">
					  <div class="card-body" style="padding:2px;border:0px  !important">
					  	<h6>JSON Format</h6>
					  	<div style="padding: 5px;  background: #646464; color: #fff; border-radius: 5px;">
					  			<pre style="color:#fff"><?php echo htmlspecialchars(json_encode($responseFormatMain103,JSON_PRETTY_PRINT)); ?></pre>
					  	</div>
					  </div>
					</div>
	  	</div>
	  </div>
	</div>
</div>


<script type="text/javascript">

</script>