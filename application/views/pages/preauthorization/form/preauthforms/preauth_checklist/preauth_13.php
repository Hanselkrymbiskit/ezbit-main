<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> 'Ambulatory prior to injury',
		'fieldid' 	=> 'q_1_1',
		'sub'		=> true,
		'hassub'    => true,
	],
	[
		'title' 	=> 'Normal or with mild systemic disease or no functional limitation (ASA I & II)',
		'fieldid' 	=> 'q_1_2',
		'sub'		=> true,
		'hassub'    => true,
	],
	[
		'title' 	=> 'With no more than two to three (2-3) co-morbid illnesses based on physical status classification based on ASA (low to moderate risk) ',
		'fieldid' 	=> 'q_1_3',
		'sub'		=> true,
		'hassub'    => true,
	]
];

$q1list = [];
foreach($qualitifationlist as $q1key => $q1prop)
{
	$q1list[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle" title="'.strip_tags($q1prop['title']).'"><span class=" ml-20 ">'.$q1prop['title'].'</span></td>
			<td class="align-middle text-center">
				<div class="radio radio-custom custom-control radio-primary radio-inline">
                    <input type="radio" id="checklist['.$q1prop['fieldid'].'_Y]" name="checklist['.$q1prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="'.strip_tags($q1prop['title']).' : Yes" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q1prop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$q1prop['fieldid'].'_Y]">YES</label>
                </div>  
                <div class="radio radio-custom custom-control radio-primary radio-inline">
                    <input type="radio" id="checklist['.$q1prop['fieldid'].'_NA]" name="checklist['.$q1prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="NA" title="'.strip_tags($q1prop['title']).' : Not Applicable" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q1prop['fieldid']] == 'NA' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$q1prop['fieldid'].'_NA]">N/A</label>
                </div>   
			</td>
		</tr>
	';
}


$qualitifationlist2 = [
	[
		'title' 	=> 'Arm and Forearm: The choice between plating and pinning depends on the fracture location, degree of comminution, displacement and age of the patient. ',
		'fieldid' 	=> 'q_2_0',
		'noinput'	=> true,
		'sub'		=> false,
		'hassub'    => true,
	],
	[
		'title' 	=> '- Humerus fractures (proximal and/or; distal and/or; distal) ',
		'fieldid' 	=> 'q_2_1',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '- Forearm diaphyseal fractures (radius only or; Ulna only or; both radius and ulna)',
		'fieldid' 	=> 'q_2_2',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '- Wrist (distal radius)',
		'fieldid' 	=> 'q_2_3',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '- Without malignant/metastatic pathologic fracture;',
		'fieldid' 	=> 'q_2_4',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
];

$q2list = [];
foreach($qualitifationlist2 as $q2key => $q2prop)
{
	$bordertop = ($q2prop['sub']) ? 'border-bottom-0 border-top-0 ' : '';
	$borderbottom = ($q2prop['hassub']) ? ' border-bottom-0 ' : '';

	$q2list[] = '
		<tr>
			<td class="text-dark p-10 font-size-16 '.$borderbottom.' '.$bordertop.' align-middle" title="'.strip_tags($q1prop['title']).'"><span class=" ml-20 ">'.$q2prop['title'].'</span></td>
			<td class="align-middle text-center '.$borderbottom.' '.$bordertop.' ">
				'.( (@$q2prop['noinput'] == false) ? '
				<div class="radio radio-custom custom-control radio-primary radio-inline">
                    <input type="radio" id="checklist['.$q2prop['fieldid'].'_Y]" name="checklist['.$q2prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="'.strip_tags($q2prop['title']).' : Yes" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q2prop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$q2prop['fieldid'].']">YES</label>
                </div>  
                <div class="radio radio-custom custom-control radio-primary radio-inline">
                    <input type="radio" id="checklist['.$q2prop['fieldid'].'_NA]" name="checklist['.$q2prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="NA" title="'.strip_tags($q2prop['title']).' : Not Applicable" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q2prop['fieldid']] == 'NA' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$q2prop['fieldid'].']">N/A</label>
                </div>   
                ' : '').'
			</td>
		</tr>
	';
}

?>

<div class="mb-5">
	<h6 class="text-right pr-20">choose appropriate answer</h6>
	<table class="table table-sm table-bordered">
		<tbody>
			<tr>
				<td class="bg-light text-left font-weight-bold text-dark p-10 text-center font-size-16 align-middle">
					<label class="label font-weight-bold text-uppercase font-size-16"  for="checklist[checklist[q_0_1]]">SITE OF INJURY </label>
				</td>
				<td class="w-p80 text-left font-weight-bold text-dark p-10 font-size-16 align-middle">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
		                <div class="radio radio-custom custom-control radio-primary radio-inline">
		                    <input type="radio" id="checklist[q_0_1_L]" name="checklist[q_0_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="L" title="Left side" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_1'] == 'L' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
		                    <label class="font-size-16 text-uppercase" for="checklist[q_0_1_L]">Left side</label>
		                </div>  
		                <div class="radio radio-custom custom-control radio-primary radio-inline">
		                    <input type="radio" id="checklist[q_0_1_R]" name="checklist[q_0_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="R" title="Right side" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_1'] == 'R' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
		                    <label class="font-size-16 text-uppercase" for="checklist[q_0_1_R]">Right side</label>
		                </div>  
		                <div class="radio radio-custom custom-control radio-primary radio-inline">
		                    <input type="radio" id="checklist[q_0_1_B]" name="checklist[q_0_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="B" title="Both side" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_1'] == 'B' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
		                    <label class="font-size-16 text-uppercase" for="checklist[q_0_1_R]">Both side</label>
		                </div>
	                </div>
				</td>
			</tr>
			<tr>
				<td class="bg-light text-left font-weight-bold text-dark p-10 text-center font-size-16 align-middle">
					<label class="label font-weight-bold text-uppercase font-size-16"  for="checklist[checklist[q_0_2]]">SURGICAL URGENCY</label>
				</td>
				<td class="w-p80 text-left font-weight-bold text-dark p-10 font-size-16 align-middle">
					<div class="form-group form-material row mb-0 ml-2" data-plugin="formMaterial">
		                <div class="radio radio-custom custom-control radio-primary mr-30"">
		                    <input type="radio" id="checklist[q_0_2_er]" name="checklist[q_0_2]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="ER" title="Emergency" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_2'] == 'ER' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
		                    <label class="font-size-16 text-uppercase" for="checklist[q_0_2]">Emergency</label>
		                </div>  
		                <div class="row mt-10 mr-20">
			                <label class="col-md-6 font-size-1 text-uppercase mb-0" for="checklist[q_0_2_date]">, Date of Surgery : </label>
			                <div class="col-md-4">
			                	<input type="text" class="form-control text-uppercase text-primary text-left mt--5" id="checklist[q_0_2_date]" name="checklist[q_0_2_date]" title="for synchronous tumor, specify sites" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo ( (@$preauthFormData['checklist']['q_0_2_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist']['q_0_2_date'])) : ''); ?>"  dfvalue="<?php echo ( (@$preauthFormData['checklist']['q_0_2_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist']['q_0_2_date'])) : ''); ?>" data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" <?php echo @$disabled; ?> required> 
		                	</div>
			            </div>
		                 
		                <div class="radio radio-custom custom-control radio-primary ">
		                    <input type="radio" id="checklist[q_0_2_EL]" name="checklist[q_0_2]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="EL" title="Elective" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_2'] == 'EL' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
		                    <label class="font-size-16 text-uppercase" for="checklist[q_0_2_EL]">Elective</label>
		                </div> 
	                </div>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div class="mb-5">
	<h4 class="text-left mt-20 mb-0">ATTESTED BY ATTENDING PHYSICIAN  </h4>  
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) if YES or NA if not applicable</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">QUALIFICATIONS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES | NA</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (count($q1list) > 0 ) ? implode('',$q1list) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5">
	<h6 class="text-right mt-0 pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>)</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">CLINICAL FEATURES</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES | NA</th>
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
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_patient]">Conforme by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_patient]" name="checklist[crtby_patient]" title="Patient/Parent/Guardian" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>  
				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingortopedicsurgeon]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingortopedicsurgeon]" name="checklist[crtby_attendingortopedicsurgeon]" title="Certified correct by Attending Orthopedic Surgeon" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingortopedicsurgeon']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingortopedicsurgeon']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Patient/Parent/Guardian</span>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Orthopedic Surgeon</span>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_patient_signeddate]">Date Signed :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_patient_signeddate]" name="checklist[crtby_patient_signeddate]" title="Date Signed" frmgrp="patientinformation"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo (@$preauthFormData['checklist']['crtby_patient_signeddate'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['checklist']['crtby_patient_signeddate'])) : ''; ?>" dfvalue="<?php echo (@$preauthFormData['checklist']['crtby_patient_signeddate'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['checklist']['crtby_patient_signeddate'])) : ''; ?>"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" <?php echo @$disabled; ?>  required>       
		                </div>   
	              	</div>  
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingortopedicsurgeon_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingortopedicsurgeon_accreno]" name="checklist[crtby_attendingortopedicsurgeon_accreno]" title="Orthopedic Surgeon - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingortopedicsurgeon_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingortopedicsurgeon_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
		                </div>   
	              	</div>  
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


