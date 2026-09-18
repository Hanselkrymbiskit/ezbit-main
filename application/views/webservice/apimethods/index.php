<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();


$payloadParameter = array(
	'APIKey'			=> 'Client API Key',
	'ResponseFormat'	=> 'XML or JSON',
	'Content'			=> 'Encrypted JSON String',
);


$ParamArrayNoValue = [];
?>

<div class="card border border-info p-0  col-sm-12 col-lg-12 mb-10" style="width: 100%;font-weight: normal !important">
	<div class="card-body">
		<h6 class="mt-0">Description</h6>
		<p><?php echo @$methodDetails['WS_Function_Description']; ?></p>
		
	</div>
</div>

<div class="card border border-info p-0  col-sm-12 col-lg-12 mb-10" style="width: 100%;font-weight: normal !important">
	<div class="card-body">
		<h6 class="mt-0">Payload/Message Parameter <i class="fa fa-info-circle float-right text-info mr-5"> Key Value is required if Parent Field Value = Parent Expected Value.</i></h6>
		<div>
			<table class="table table-sm table-striped">
				<thead>
					<tr >
						<th class="font-weight-bold">#</th>
						<th class="font-weight-bold">Key Name</th>
						<th class="font-weight-bold">Description</th>
						<th class="font-weight-bold">Required</th>
						<th class="font-weight-bold">Data Type</th>
						<th class="font-weight-bold">Length</th>
						<th class="font-weight-bold">Format</th>
						<th class="font-weight-bold">Value Type</th>
						<th class="font-weight-bold">Value Mode</th>
						<th class="font-weight-bold">Parent Field</th>
						<th class="font-weight-bold">Parent Expected Value</th>
						<th class="font-weight-bold">Reference Table ID</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if( @$methodParameter <> '' && count($methodParameter) > 0)
					{
						$pCnt = 0;
						foreach($methodParameter as $prow => $pcol )
						{
							$pCnt++;

							$ParamArrayNoValue[@$pcol['Parameter_Name']] = 'Value Here';

							$Format = ( strtolower($pcol['Data_Type']) == 'date') ? 'Y-m-d' : ( ( strtolower($pcol['Data_Type']) == 'time') ? 'H:i:s [24 hour time format]' : '' );

							$ParentParameter = '';
							if( $pcol['Parent_Parameter_ID'] <> '' )
							{
								$ParentParameter = $CI->sqlhelper->local->select("ws_method_param_key_child")->where("DataID = ".$pcol['Parent_Parameter_ID'])->ex_select('','','')->row();
								$ParentParameter = $ParentParameter['Data']['Parameter_Name'];
							}

							//
							echo '
							<tr>
								<td>'.$pCnt.'</td>
								<td>'.$pcol['Parameter_Name'].'</td>
								<td>'.$pcol['Description'].'</td>
								<td>'.$pcol['Required'].'</td>
								<td>'.$pcol['Data_Type'].'</td>
								<td>'.$pcol['Char_Length'].'</td>
								<td>'.$Format.'</td>	
								<td>'.(($pcol['ValueType'] == 3) ? 'Array Value' : $CI->myutilities->getRef_Desc(5,$pcol['ValueType'])).'</td>
								<td>'.(($pcol['ValueMode'] == '') ? '' : $CI->myutilities->getRef_Desc(7,$pcol['ValueMode'])).'</td>
								<td>'.@$ParentParameter.'</td>
								<td>'.$pcol['Parent_Expected_Value'].'</td>
								<td>'.$pcol['Reference_ID'].'</td>
							</tr>
							';
						}
					}

					?>
				</tbody>
			</table>
		</div>
		
		<div>
			<div class="">
				<div class="card border-info float-left" style="width: 49%;font-weight: normal !important">
					<div class="card-body">
					  	<h6>Array Format Parameter</h6>
					  	<div style="background: #646464; color: #fff; border-radius: 5px;">
					  			<pre style="color:#fff"><?php  echo (count($ParamArrayNoValue) > 0 ) ? str_replace(array('[',']','Value Here',')'),array('"','"','"Value Here",',');'),print_r($ParamArrayNoValue,true)) : ''; ?></pre>
					  	</div>
				  	</div>
				</div>

				<div class="card border-info float-right" style="width: 49%;margin-left:2%;font-weight: normal !important">
				  	<div class="card-body">
					  	<h6>JSON Format Parameter</h6>
					  	<div style="background: #646464; color: #fff; border-radius: 5px;">
					  			<pre style="color:#fff">
					  				<?php  echo (count($ParamArrayNoValue) > 0 ) ? str_replace(array('Array','[',']','=>','Value Here','(',')'),array('','"','"',':','"Value Here",','{','};'),print_r($ParamArrayNoValue,true)) : ''; ?></pre>
					  	</div>
				  	</div>
				</div>

			</div>
		</div>

		<div>
			<div class="">
				<div class="card border-info float-left" style="width: 49%;font-weight: normal !important">
					<div class="card-body">
					  	<h6>SOAP Parameter Format</h6>
					  	<div style="background: #646464; color: #fff; border-radius: 5px;">
					  			<pre style="color:#fff"><?php echo (count($payloadParameter) > 0 ) ? htmlspecialchars(str_replace('DOH', 'SOAP-ENV:Body', $CI->myutilities->array_to_xmlformat_string($payloadParameter))) : ''; ?>

					  			</pre>
					  	</div>
				  	</div>
				</div>

				<div class="card border-info float-right" style="width: 49%;margin-left:2%;font-weight: normal !important">
				  	<div class="card-body">
					  	<h6>REST [POST] Parameter Format</h6>
					  	<div style="background: #646464; color: #fff; border-radius: 5px;">
					  			<pre style="color:#fff"><?php  echo (count($payloadParameter) > 0 ) ? str_replace(array('[',']','Value Here',')'),array('"','"','"Value Here",',');'),print_r($payloadParameter,true)) : ''; ?></pre>
					  	</div>
				  	</div>
				</div>

			</div>
		</div>
	</div>
</div>

<div class="card border border-info p-0  col-sm-12 col-lg-12 mb-10" style="width: 100%;font-weight: normal !important">
	<div class="card-body">
		<h6 class="mt-0">Response Parameter</h6>
		<p>API Client is required to submit/transmit the required parameter for a successfull API Call.</p>
		
	</div>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
</script>