<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


$gProfile = $CI->sqlhelper->local->select("rest_api_controller")->where(" id not in ('11','12','13','14')")->result();
$Profile = $gProfile['Data'];

$li_menu = [];
$tab_content = [];
$urnresource = [];
$bundlecontent = [
	'resourceType' => 'Bundle',
	'type'				 => 'transaction',
	'entry'				 => [],
];
$pCnt = 0;
foreach($Profile as $pRow => $pContent)
{
	$pCnt++;

	$li_menu[] = '<li class="nav-item" role="presentation"><a class="nav-link '.(( $pCnt == 1) ? 'active' : '').'" data-toggle="tab" href="#ProfileResource_'.$pContent['display'].'" aria-controls="ProfileResource_'.$pContent['display'].'" role="tab" aria-selected="'.(( $pCnt == 1) ? 'true' : 'false').'">'.str_pad($pCnt, 2,0,STR_PAD_LEFT).' : '.$pContent['display'].'</a></li>';

	$getProfileDataSets = $CI->sqlhelper->local->select("tbl_resource_fhirprofile_datasets")->where("fhirprofile = '".$pContent['id']."'")->result();
	$profileRows = [];
	$ppcnt = 0;
	foreach($getProfileDataSets['Data'] as $pprows => $ppcols)
	{
		$ppcnt++;
		$profileRows[] = '
			<tr class="text-center">
				<td class="font-weight-bold text-dark font-size-10">'.str_pad($ppcnt, 2,0,STR_PAD_LEFT).'</td>
				<td class="font-weight-bold text-dark font-size-10 text-left">'.@$ppcols['dataindicator'].'</td>
				<td class="font-weight-bold text-dark font-size-10">'.@$ppcols['resourceelement'].'</td>
				<td class="font-weight-bold text-dark font-size-10 text-left">'.@$ppcols['definition'].'</td>
				<td class="font-weight-bold text-dark font-size-10">'.@$ppcols['mandatory'].'</td>
				<td class="font-weight-bold text-dark font-size-10">'.@$ppcols['extension'].'</td>
				<td class="font-weight-bold text-dark font-size-10">'.@$ppcols['datatype'].'</td>
				<td class="font-weight-bold text-dark font-size-10">'.@$ppcols['terminologybindings'].'</td>
				<td class="font-weight-bold text-dark font-size-10">'.@$ppcols['dataformat'].'</td>
				<td class="font-weight-bold text-dark font-size-10">'.@$ppcols['datacondition'].'</td>
			</tr>
		';
	}

	if( strtolower($pContent['display']) <> 'bundle' )
	{
		$urntmp = 'urn:uuid:'.$pCnt;
		$urnresource[strtolower($pContent['display'])] = $urntmp;
		$bundleData = [
			'fullUrl' => $urntmp,
			'resource' => (@$pContent['jsonprofile'] <> '') ? json_decode(@$pContent['jsonprofile'],TRUE) : ''
		];
		$bundlecontent['entry'][] = $bundleData;
	}
	else
	{
		$pContent['jsonprofile'] = json_encode($bundlecontent);
	}

	$jsonContent = ( strtolower($pContent['display']) <> 'bundle' ) ? ((@$pContent['jsonprofile'] <> '') ? json_decode(@$pContent['jsonprofile'],TRUE) : '') : $bundlecontent;
	$tab_content[] = '
	<div class="tab-pane '.(( $pCnt == 1) ? 'active' : '').'" id="ProfileResource_'.$pContent['display'].'" role="tabpanel">
		<div class="card mb-5">
			<div class="card-header">
				<table class="table mb-0">
					<thead>
						<tr>
							<th class="font-weight-bold text-dark">FHIR Resource</th>
							<th class="font-weight-bold text-dark">FHIR End-Point URI</th>
							<th class="font-weight-bold text-dark">METHOD</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="font-weight-bold text-success">'.$pContent['display'].'</td>
							<td class="font-weight-bold text-success">'.site_url($pContent['controller']).'</td>
							<td class="font-weight-bold text-success">'.( (@$pContent['method'] <> '') ? implode(" / ",$cmethod = explode(",",@$pContent['method'])) : '').'</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		'.( (count($profileRows) > 0) ? '
		<div class="card mb-5">
      <div class="card-block p-5">
        	<h6>DATA SETS</h6>
        	<table class="table table-bordered table-striped mb-0">
						<thead>
							<tr class="bg-success text-white text-center">
								<th class="font-weight-bold text-white font-size-10" style="width:1%;">#</th>
								<th class="font-weight-bold text-white font-size-10">Data Indicator</th>
								<th class="font-weight-bold text-white font-size-10">Resource / Element Name</th>
								<th class="font-weight-bold text-white font-size-10">Definition</th>
								<th class="font-weight-bold text-white font-size-10">Required</th>
								<th class="font-weight-bold text-white font-size-10">Extension</th>
								<th class="font-weight-bold text-white font-size-10">Data Type</th>
								<th class="font-weight-bold text-white font-size-10">Terminology Binding</th>
								<th class="font-weight-bold text-white font-size-10">Format</th>
								<th class="font-weight-bold text-white font-size-10">Condition</th>
							</tr>
						</thead>
						<tbody>
							'.implode('',$profileRows).'
						</tbody>
					</table>
    	</div>
 		</div>
 		' : '').'
 		'.( (in_array('GET', $cmethod) || in_array('PUT', $cmethod) ) ? '
 		<div class="card mb-5">	
      <div class="card-block p-5">
      	<h6>METHOD : GET '.( (in_array('PUT', $cmethod)) ? '/ PUT' : '' ).'</h6>
      	<h6 ><span class="text-success">URI : '.site_url($pContent['controller']).'/{Resource ID}</span></h6>
      	<h6 ><span class="text-info">EXAMPLE FOR '.strtoupper($pContent['display']).' RESOURCE WITH ID = 999 :</h6>
      	<h6>'.site_url($pContent['controller']).'/999 '.( (strtolower($pContent['display']) == 'organization') ? ' | '.site_url($pContent['controller']).'?identifier={value_here}' : '').'</span></h6> 
    	</div>
  	</div>
  	' : '' ).'
  	'.( (in_array('POST', $cmethod) ) ? '
		<div class="card">	
      <div class="card-block p-5">
      	<h6>METHOD : POST '.( (in_array('PUT', $cmethod)) ? '/ PUT' : '' ).'</h6>
      	<h6>FHIR JSON : PAYLOAD <i name="copytoclipboard" target="fhirjsonpayload_'.$pContent['id'].'" title="'.strtoupper($pContent['display']).'" class="btn btn-dark fa fa-fw fa-copy float-right mt--10 mr-10" style="cursor:pointer;"> Copy to Clipboard</i></h6>
      	<div class="scrollbar-dark thin" style="background: #646464; border-radius: 5px;overflow-y:auto;max-height:70vh;">
          <pre class="mb-0 text-white" id="fhirjsonpayload_'.$pContent['id'].'">'.( (@$pContent['jsonprofile'] <> '') ? htmlspecialchars(json_encode($jsonContent,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)) : '').'</pre>
    		</div>
  		</div>
		</div>
		' : '').'
	</div>';
}

?>

<div class="mb-20">
  <div class="nav-tabs-vertical" data-plugin="tabs">
    <ul class="nav nav-tabs mr-20" role="tablist">
      <?php echo implode("",$li_menu); ?>
    </ul>
    <div class="tab-content ">
      <?php echo implode("",$tab_content); ?>
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