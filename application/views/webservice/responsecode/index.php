<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$APIMethods = $CI->sqlhelper->local->select("ws_method")->where("WS_Enable = 'Y'")->ex_select('','WS_Order asc','')->result();
$APIMethods = $APIMethods['Data'];

$SpecificMethodResponseCode = $CI->sqlhelper->local->select("ws_response_code")->where("DataID > 6")->result();
log_message("error",$SpecificMethodResponseCode);
$apiMethodID = array();
foreach($SpecificMethodResponseCode['Data'] as $k => $l)
{
	$apiMethodID[ (int) $l['FunctionID'] ][] = $l;
}
log_message("error",$apiMethodID);
?>

<div class="col-lg-12 p-0" style="width: 100%;font-size:14px !important;">
	<div class="card bg-info" style="width: 100%;font-weight: normal !important">
		  <div class="card-body" style="color:#000">
		  	<i class="fas fa-info-circle" style="margin-right: 1rem"></i> <b style="color:#000;">API Standard Response Code for all Methods.</b>.
		  	
		  </div>
		</div>
			
		<div class="border-info" style="border:1px solid #ccc;border-radius: 5px;padding:2px;margin-top:1rem">
		  	<table class="table table-sm table-border" style="">
		  		<thead>
		  			<tr class="bg-info text-black">
		  				<th style="color:#000;font-weight:normal;text-align: center;border:0px solid #ccc;">Code</th>
		  				<th style="color:#000;font-weight:normal;text-align: left;padding-left:2rem;border:0px solid #ccc;">Description</th>
		  				<th style="color:#000;font-weight:normal;text-align: center;border:0px solid #ccc;">Specific Method</th>
		  				<th style="color:#000;font-weight:normal;text-align: center;border:0px solid #ccc;">Return Type</th>
		  			</tr>
		  		</thead>
		  		<tbody>
		  			<?php

		  				$gRL = $CI->sqlhelper->local->select("ws_response_code")->where("DataID <= 6")->ex_select("","","")->result();
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
	  	</div>

	  	<div class="card bg-success" style="width: 100%;font-weight: normal !important;margin-top:1rem;margin-bottom:0">
			  <div class="card-body" style="color:#000">
			  	<i class="fas fa-info-circle" style="margin-right: 1rem"></i> <b style="color:#000;">Response Code for Specific API Method.</b>.
			  	
			  </div>
			</div>
			
		<?php

		foreach($APIMethods as $apiRows => $apiCols)
		{
		?>
			
			<div class="border-success" style="border:1px solid #ccc;border-radius: 5px;padding:2px;margin-top:.5rem">
				<h7 style="color:#000;padding:5px">
					<span>Method Name : <b><?php echo $apiCols['WS_Function_Name']; ?></b></span>
					<i title="Method Description & Usage" class="fa fa-search float-right text-info mr-5" onclick="location.href='<?php echo site_url("api/index/methods/".$apiCols['WS_Function_Name']); ?>'" style="cursor: pointer"></i>
				</h7>
		  	<table class="table table-sm table-border" style="">
		  		<thead>
		  			<tr class="bg-success text-black">
		  				<th style="color:#000;font-weight:normal;text-align: center;border:0px solid #ccc;">Code</th>
		  				<th style="color:#000;font-weight:normal;text-align: left;padding-left:2rem;border:0px solid #ccc;">Description</th>
		  				<th style="color:#000;font-weight:normal;text-align: center;border:0px solid #ccc;">Return Type</th>
		  			</tr>
		  		</thead>
		  		<tbody>
		  				<?php
		  					if( isset($apiMethodID[ (int) $apiCols['FunctionID'] ]) )
		  					{
		  						$rCodeList = $apiMethodID[ (int) $apiCols['FunctionID'] ];
		  						foreach($rCodeList as $rx => $cx)
				  				{
				  					echo '
										<tr>
						  				<td style="font-weight:normal;text-align: center;">'.@$cx['Code'].'</td>
						  				<td style="font-weight:normal;text-align: left;padding-left:2rem">'.$cx['Description'].'</td>
						  				<td style="font-weight:normal;text-align: center;">'.@$cx['ReturnType'].'</td>
						  			</tr>
				  					';
				  				}
		  					}		  				
			  			?>
		  		</tbody>
		  	</table>
	  	</div>

		<?php
		}

		?>
</div>




<script type="text/javascript">

</script>