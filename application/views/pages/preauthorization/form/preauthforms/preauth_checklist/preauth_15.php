<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> 'Biopsy proven tissue diagnosis of rectal cancer',
		'fieldid' 	=> 'q_1_1',
	],
	[
		'title' 	=> 'No evidence of systemic metastasis from chest x-ray and abdominal ultrasound or CT scan of whole abdomen',
		'fieldid' 	=> 'q_1_2',
	],
	[
		'title' 	=> 'No previous pelvic radiation',
		'fieldid' 	=> 'q_1_3',
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
		'title' 	=> '1. Normal or with mild systemic disease (ASA I or II)',
		'fieldid' 	=> 'q_2_1',
	],
	[
		'title' 	=> '2. Fully active, able to carry on all pre-disease performance without restriction, OR restricted in physically strenuous activity but ambulatory 
and able to carry out work of a light or sedentary nature, e.g. light house work, office work, OR ambulatory and capable of all self-care (ECOG Performances 0-2)',
		'fieldid' 	=> 'q_2_2',
	],
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

?>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>)</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">QUALIFICATIONS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (count($q1list) > 0 ) ? implode('',$q1list) : ''; ?>
		</tbody>
	</table>
</div>
<div class="mb-5">
	<table class="table table-sm table-bordered">
		<tbody>
			<tr>
				<td class="bg-light text-left font-weight-bold text-dark p-10 text-center font-size-16 align-middle">
					<label class="label font-weight-bold text-uppercase font-size-16"  for="checklist[checklist[q_4_1]]">CLINICAL STAGES :</label>
				</td>
				<td class="w-p80 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">
					<div class="form-group form-material mb-0 w-200" data-plugin="formMaterial">
		                <select class="form-control text-uppercase text-primary font-size-16 text-left" id="checklist[q_4_1]" name="checklist[q_4_1]" title="CLINICAL STAGE"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['q_4_1']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['q_4_1']; ?>" <?php echo @$disabled; ?>  required></select> 
	                </div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="mb-5">
	<h6 class="text-right mt-0 pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>)</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">OTHER QUALIFICATION</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (count($q2list) > 0 ) ? implode('',$q2list) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5">
	<table class="table table-sm table-bordered">
		<tbody>
			<tr>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingsurgeon]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingsurgeon]" name="checklist[crtby_attendingsurgeon]" title="Certified correct by Attending Surgeon" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingsurgeon']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingsurgeon']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingmedoncologist]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingmedoncologist]" name="checklist[crtby_attendingmedoncologist]" title="Certified correct by Attending Medical Oncologist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingmedoncologist']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingmedoncologist']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>  
				</td>
				
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Surgeon</span>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Medical Oncologist</span>
				</td>
				
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingsurgeon_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingsurgeon_accreno]" name="checklist[crtby_attendingsurgeon_accreno]" title="Attending Surgeon" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingsurgeon_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingsurgeon_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
		                </div>   
	              	</div>  
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingmedoncologist_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingmedoncologist_accreno]" name="checklist[crtby_attendingmedoncologist_accreno]" title="Attending Medical Oncologist - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingmedoncologist_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingmedoncologist_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
		                </div>   
	              	</div>  
				</td>
			</tr>

			<tr>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingradiationoncologist]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingradiationoncologist]" name="checklist[crtby_attendingradiationoncologist]" title="Certified correct by Attending Radiation Oncologist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingradiationoncologist']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingradiationoncologist']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_patient]">Conforme by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_patient]" name="checklist[crtby_patient]" title="Certified correct by Attending Physician" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Patient Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>
	              	</div>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Radiation Oncologist</span>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Patient</span>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingradiationoncologist_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingradiationoncologist_accreno]" name="checklist[crtby_attendingradiationoncologist_accreno]" title="Attending Radiation Oncologist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingradiationoncologist_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingradiationoncologist_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
		                </div>   
	              	</div>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					
				</td>
			</tr>
		</tbody>
	</table>
</div>


<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){
getreferencevalue({objselect:$("select[id='checklist[q_4_1]'][name='checklist[q_4_1]']"),referenceid:'209',filter:'',order:''},'','SELECT CLINICAL STAGES');

<?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>
	
	$("input[id*='checklist[crtby_'][forminputgroup='preauthFormData']").keyup(function(){
		$("input[id='"+$(this).attr('id').replace('checklist','request')+"'][forminputgroup='preauthFormData']").val($(this).val());
	});


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


