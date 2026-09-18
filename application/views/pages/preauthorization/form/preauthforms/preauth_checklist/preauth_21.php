<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> '1. Age ≥ 18 years old',
		'fieldid' 	=> 'q_x_1',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
		[
		'title' 	=> '2. At least 3 months post-onset',
		'fieldid' 	=> 'q_x_2',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '3. Upper limbs ≥ 4 with fair trunk control and full range of motion, if bilateral',
		'fieldid' 	=> 'q_x_3',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '4. Unaffected limbs ≥ 3 with fair trunk control and full range of motion, if unilateral',
		'fieldid' 	=> 'q_x_4',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '5. Ambulatory with assistive device',
		'fieldid' 	=> 'q_x_5',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '6. No fresh or non-healing wound',
		'fieldid' 	=> 'q_x_6',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '1. Weakness or absence of dorsiflexors and/or plantarflexors, +/- grade 1-2 spasticity with full range of motion achieved passively',
		'fieldid' 	=> 'q_x_1',
		'group'		=> '2',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '2. Equinovarus +/- foot rotation and +/- grade 1-2 spasticity with full range of motion achieved passively',
		'fieldid' 	=> 'q_x_2',
		'group'		=> '2',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '3. Pain & Instability secondary to sensory or structural deficit in a Charcot Arthropathy',
		'fieldid' 	=> 'q_x_3',
		'group'		=> '2',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> 'Quadriceps MMT of <3 +/- sensory loss ,+/- instability (genu recurvatum) with hip/knee flexion contracture <20 degrees',
		'fieldid' 	=> 'q_x_1',
		'group'		=> '3',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> 'Hip, knee, ankle & foot muscles MMT <3 +/- sensory loss, +/- instability, with hip /knee flexion contracture <20 degrees',
		'fieldid' 	=> 'q_x_1',
		'group'		=> '4',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
];

$q1list = [];
foreach($qualitifationlist as $qkey => $qprop)
{

	$qgrp = 'q'.$qprop['group'].'list';

	if( !isset(${$qgrp}) )
	{
		$$qgrp = [];
	}

	$qprop['fieldid'] = str_replace('_x_','_'.$qprop['group'].'_',$qprop['fieldid']);

	$$qgrp[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle" title="'.strip_tags($qprop['title']).'"><span class=" ml-20 ">'.$qprop['title'].'</span></td>
			<td class="align-middle text-center">
				'.( ($qprop['noinput']) ? '' : '
				<div class="checkbox radio-custom custom-control radio-primary radio-inline">
                    <input type="radio" id="checklist['.$qprop['fieldid'].'_Y]" name="checklist['.$qprop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="'.strip_tags($qprop['title']).' : YES" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$qprop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$qprop['fieldid'].'_Y]">YES</label>
                </div>   
                <div class="checkbox radio-custom custom-control radio-primary radio-inline">
                    <input type="radio" id="checklist['.$qprop['fieldid'].'_NA]" name="checklist['.$qprop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="NA" title="'.strip_tags($qprop['title']).' : Not Applicable" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$qprop['fieldid']] == 'NA' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$qprop['fieldid'].'_NA]">N/A</label>
                </div>   
                ').'
			</td>
		</tr>
	';
}

$qualitifationlist = [
	[
		'title' 	=> 'Ankle Foot Orthosis',
		'fieldid' 	=> 'q_5_1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> 'Knee Ankle Foot Orthosis',
		'fieldid' 	=> 'q_5_2',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> 'Hip Knee Ankle Foot Orthosis',
		'fieldid' 	=> 'q_5_3',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
];

$q5list = [];
foreach($qualitifationlist as $q2key => $q5prop)
{

	$q5list[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle" title="'.strip_tags($q5prop['title']).'"><span class=" ml-20 ">'.$q5prop['title'].'</span></td>
			<td class="align-middle text-center">
				'.( ($q5prop['noinput']) ? '' : '
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
	             	<div class="radio radio-custom custom-control radio-primary radio-inline">
		                <input type="radio" id="checklist['.$q5prop['fieldid'].'_R]" name="checklist['.$q5prop['fieldid'].']" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="R" title="Right limb" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q5prop['fieldid']] == 'R' ) ? 'checked' : '').' '.@$disabled.' >
		                <label class="font-size-16 text-uppercase" for="checklist['.$q5prop['fieldid'].'_R]">Right limb</label>
		            </div>  
		            <div class="radio radio-custom custom-control radio-primary radio-inline">
		                <input type="radio" id="checklist['.$q5prop['fieldid'].'_L]" name="checklist['.$q5prop['fieldid'].']" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="L" title="Left limb" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q5prop['fieldid']] == 'L' ) ? 'checked' : '').' '.@$disabled.' >
		                <label class="font-size-16 text-uppercase" for="checklist['.$q5prop['fieldid'].'_L]">Left limb</label>
		            </div>  
		            <div class="radio radio-custom custom-control radio-primary radio-inline">
		                <input type="radio" id="checklist['.$q5prop['fieldid'].'_B]" name="checklist['.$q5prop['fieldid'].']" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="B" title="Both limb" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q5prop['fieldid']] == 'B' ) ? 'checked' : '').' '.@$disabled.' >
		                <label class="font-size-16 text-uppercase" for="checklist['.$q5prop['fieldid'].'_B]">Both limb</label>
		            </div>
		        </div>
		        ').'
			</td>
		</tr>
	';
}

?>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) if YES or N/A if not applicable</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">GENERAL QUALIFICATIONS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES | N/A</th>
			</tr>
		</thead>
		<tbody>
			<?php echo ( is_array(@$q1list) && count(@$q1list) > 0 ) ? implode('',$q1list) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) if YES or N/A if not applicable</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">QUALIFICATIONS SPECIFIC TO ANKLE FOOT ORTHOSIS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES | N/A</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (is_array(@$q2list) && count(@$q2list) > 0 ) ? implode('',$q2list) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) if YES or N/A if not applicable</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">QUALIFICATIONS SPECIFIC TO KNEE ANKLE FOOT ORTHOSIS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES | N/A</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (is_array(@$q3list) && count(@$q3list) > 0 ) ? implode('',$q3list) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) if YES or N/A if not applicable</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">QUALIFICATIONS SPECIFIC TO HIP KNEE ANKLE FOOT ORTHOSIS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES | N/A</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (is_array(@$q4list) && count(@$q4list) > 0 ) ? implode('',$q4list) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) on the type of orthoses to be given to the patient</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">Z Benefits*</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">RIGHT | LEFT | BOTH</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (is_array(@$q5list) && count(@$q5list) > 0 ) ? implode('',$q5list) : ''; ?>
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
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingrms]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingrms]" name="checklist[crtby_attendingrms]" title="Certified correct by Attending Rehabilitation Medicine Specialist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingrms']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingrms']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
			

			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Patient/Parent/Guardian</span>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Rehabilitation Medicine Specialist</span>
				</td>
				

			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">  
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingrms_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingrms_accreno]" name="checklist[crtby_attendingrms_accreno]" title="Rehabilitation Medicine Specialist - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingrms_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingrms_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
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
	
	$("input[id*='checklist[crtby_'][forminputgroup='preauthFormData']").keyup(function(){
		$("input[id='"+$(this).attr('id').replace('checklist','request')+"'][forminputgroup='preauthFormData']").val($(this).val());
	});


	$("input[id='checklist[crtby_attendingrms]'][forminputgroup='preauthFormData'],input[id='checklist[crtby_attendingrms_accreno]'][forminputgroup='preauthFormData']").keyup(function(){
		switch( $(this).attr('id') )
		{
			case 'checklist[crtby_attendingrms]':
				$("input[id='request[crtby_attendingrms]'][forminputgroup='preauthFormData']").val($(this).val());
			break;
			case 'checklist[crtby_attendingrms_accreno]':
				$("input[id='request[crtby_attendingrms_accreno]'][forminputgroup='preauthFormData']").val($(this).val());
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


