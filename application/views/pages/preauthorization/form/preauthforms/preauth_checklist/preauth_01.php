<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> 'Age 1 to 10 years and 364 days',
		'fieldid' 	=> 'age_1y_10y_364d',
	]
];

$q1list = [];
foreach($qualitifationlist as $q1key => $q1prop)
{
	$q1list[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle" title="'.strip_tags($q1prop['title']).'"><span class=" ml-20 ">'.$q1prop['title'].'</span></td>
			<td class="align-middle">
				<div class="checkbox checkbox-custom custom-control checkbox-primary">
                    <input type="checkbox" id="checklist['.$q1prop['fieldid'].']" name="checklist['.$q1prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="'.strip_tags($q1prop['title']).' : Yes" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q1prop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-12 text-uppercase" for="checklist['.$q1prop['fieldid'].']"></label>
                </div>   
			</td>
		</tr>
	';
}


$qualitifationlist2 = [
	[
		'title' 	=> '1. Bone marrow aspirate morphology ALL FAB L1 or  L2*',
		'fieldid' 	=> 'bone_marrow_aspirate',
	],
	[
		'title' 	=> '2.a No CNS involvement based on CSF cell count and differential count',
		'fieldid' 	=> 'no_cns_csfcell',
	],
	[
		'title' 	=> '2.b No CNS involvement based on Clinical findings',
		'fieldid' 	=> 'no_cns_clinicalfindings',
	],
	[
		'title' 	=> '3. If male, no testicular involvement <i>(If female, put "N/A")</i>',
		'fieldid' 	=> 'notesticular',
	]
];
$q2list = [];
foreach($qualitifationlist2 as $q2key => $q2prop)
{
	$q2list[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle" title="'.strip_tags($q2prop['title']).'"><span class=" ml-20 ">'.$q2prop['title'].'</span></td>
			<td class="align-middle text-center font-size-16 font-weight-bold">
				<div class="checkbox checkbox-custom custom-control checkbox-primary">
                    <input type="checkbox" id="checklist['.$q2prop['fieldid'].']" name="checklist['.$q2prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="'.strip_tags($q2prop['title']).' : Yes" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q2prop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-12 text-uppercase" for="checklist['.$q2prop['fieldid'].']"></label>
                </div>   
			</td>
		</tr>
	';
}


$qualitifationlist3 = [
	[
		'title' 	=> 'BC WBC count <50,000/µL or <50,000 cells/µL or <50 x 10<sup>3</sup>/µL or <50 x 10<sup>9</sup>/L ',
		'fieldid' 	=> 'bc_wbc_count',
	],
	[
		'title' 	=> 'CSF cell count white blood cell (WBC) not more than 5 x 10<sup>6</sup>/L',
		'fieldid' 	=> 'csf_cell_count',
	]
];
$q3list = [];
foreach($qualitifationlist3 as $q3key => $q3prop)
{
	$q3list[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle" title="'.strip_tags($q3prop['title']).'"><span class=" ml-20 ">'.$q3prop['title'].'</span></td>
			<td class="align-middle text-center font-size-16 font-weight-bold">
				<div class="checkbox checkbox-custom custom-control checkbox-primary">
                    <input type="checkbox" id="checklist['.$q3prop['fieldid'].']" name="checklist['.$q3prop['fieldid'].']" class="form-control" title="'.$q3prop['title'].'" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" withdatevalue="true" targetdatefld="checklist['.$q3prop['fieldid'].'_date]" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q3prop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-12 text-uppercase" for="checklist['.$q3prop['fieldid'].']"></label>
                </div>   
			</td>
			<td class="align-middle text-center font-size-16 font-weight-bold">
			 	<div class="form-group form-material mt-10 text-center  w-150" data-plugin="formMaterial">
                    <input type="text" class="form-control text-uppercase text-center" id="checklist['.$q3prop['fieldid'].'_date]" name="checklist['.$q3prop['fieldid'].'_date]" title="'.$q3prop['title'].' - Date Done"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="'.( (@$preauthFormData['checklist'][$q3prop['fieldid'].'_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist'][$q3prop['fieldid'].'_date'])) : '') .'" dfvalue="'.( (@$preauthFormData['checklist'][$q3prop['fieldid'].'_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist'][$q3prop['fieldid'].'_date'])) : '') .'"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" '.( (@$ViewMode <> true && @$ForCompliance == false) ? 'disabled' : ( (@$preauthFormData['checklist'][$q3prop['fieldid'].'_date'] == '') ? '' : @$disabled ) ).'>       
                </div>
			</td>
		</tr>
	';
}

?>

<div class="mb-5 border-bottom">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>)</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">History of Previous Treatment</span></th>
				<th class="text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (count($q1list) > 0 ) ? implode('',$q1list) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5 border-bottom"">
	<h4 class="text-left mt-20 mb-0">ATTESTED BY ATTENDING PHYSICIAN  </h4>  
	<h6 class="text-right mt-0 pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>)</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">QUALIFICATION</span></th>
				<th class="text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (count($q2list) > 0 ) ? implode('',$q2list) : ''; ?>
		</tbody>
	</table>

	<h6 class="text-right mt-0 pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>)</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">DIAGNOSTIC</span></th>
				<th class="text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES</th>
				<th class="text-center font-weight-bold text-dark p-10 font-size-16 align-middle w-150">DATE DONE<br>(mm/dd/yyyy)</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (count($q3list) > 0 ) ? implode('',$q3list) : ''; ?>
		</tbody>
	</table>

</div>

<div class="mb-5">
	<table class="table table-sm table-bordered">
		<tbody>
			<tr>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
				
				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase w-p100"  for="checklist[crtby_attendingphysician]">Certified correct by: </label>
						<input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingphysician]" name="checklist[crtby_attendingphysician]" title="Certified correct by Attending Physician" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingphysician']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingphysician']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Physician</span>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">

				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingphysician_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingphysician_accreno]" name="checklist[crtby_attendingphysician_accreno]" title="Orthopedic Surgeon - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingphysician_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingphysician_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
		                </div>   
	              	</div>  
				</td>
			</tr>

		</tbody>
	</table>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

<?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>
	$("input[id='checklist[crtby_attendingphysician]'][forminputgroup='preauthFormData'],input[id='checklist[crtby_attendingphysician_accreno]'][forminputgroup='preauthFormData']").keyup(function(){
		switch( $(this).attr('id') )
		{
			case 'checklist[crtby_attendingphysician]':
				$("input[id='request[crtby_attendingphysician]'][forminputgroup='preauthFormData']").val($(this).val());
			break;
			case 'checklist[crtby_attendingphysician_accreno]':
				$("input[id='request[crtby_attendingphysician_accreno]'][forminputgroup='preauthFormData']").val($(this).val());
			break;
		}
	});
  

	$("[withdatevalue='true'][forminputgroup='preauthFormData']").click(function(){
		var tgtID = $(this).attr('targetdatefld');
		if( $(this).prop('checked') )
		{
			$("[id='"+tgtID+"'][forminputgroup='preauthFormData']").removeAttr('disabled').attr('required','required');
		}
		else
		{
			$("[id='"+tgtID+"'][forminputgroup='preauthFormData']").attr('disabled','disabled').removeAttr('required');
		}
	});

<?php } ?>

  
});
</script>


