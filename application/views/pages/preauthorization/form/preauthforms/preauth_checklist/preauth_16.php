<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> 'Age 1 to 10 years and 364 days  ',
		'fieldid' 	=> 'q_1_1',
		'sub'		=> true,
		'hassub'    => true,
	],
];

$q1list = [];
foreach($qualitifationlist as $q1key => $q1prop)
{
	$q1list[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle" title="'.strip_tags($q1prop['title']).'"><span class=" ml-20 ">'.$q1prop['title'].'</span></td>
			<td class="align-middle text-center">
				<div class="checkbox checkbox-custom custom-control checkbox-primary">
                    <input type="checkbox" id="checklist['.$q1prop['fieldid'].']" name="checklist['.$q1prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="'.strip_tags($q1prop['title']).' : Yes" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q1prop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$q1prop['fieldid'].']"></label>
                </div>  
                
			</td>
		</tr>
	';
}

$qualitifationlist2 = [
	[
		'title' 	=> '1. Check past history: ',
		'fieldid' 	=> 'q_2_a',
		'noinput'	=> true,
		'sub'		=> false,
		'hassub'    => true,
	],
	[
		'title' 	=> 'a. No previous cardiac surgery or intervention such as BTS (Blalock Taussig Shunt) ',
		'fieldid' 	=> 'q_2_1',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> 'b. No PDA Stenting or ',
		'fieldid' 	=> 'q_2_2',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> 'c. No residual VSD from previous open heart surgery for total correction ',
		'fieldid' 	=> 'q_2_3',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '2. Check physical examination:',
		'fieldid' 	=> 'q_2_b',
		'noinput'	=> true,
		'sub'		=> false,
		'hassub'    => true,
	],
	[
		'title' 	=> 'No hepatomegaly',
		'fieldid' 	=> 'q_2_4',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> 'No edema lower extremities ',
		'fieldid' 	=> 'q_2_5',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '3. No congenital chromosomal abnormalities  or other congenital defects, except Trisomy 21 (Down’s syndrome) ',
		'fieldid' 	=> 'q_2_6',
		'noinput'	=> false,
		'sub'		=> false,
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
			<td class="text-dark p-10 font-size-16 '.$borderbottom.' '.$bordertop.' align-middle" title="'.strip_tags($q2prop['title']).'"><span class=" ml-20 ">'.$q2prop['title'].'</span></td>
			<td class="align-middle text-center '.$borderbottom.' '.$bordertop.' ">
				'.( (@$q2prop['noinput'] == false) ? '
				<div class="checkbox checkbox-custom custom-control checkbox-primary">
                    <input type="checkbox" id="checklist['.$q2prop['fieldid'].']" name="checklist['.$q2prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" withdatevalue="true" targetdatefld="checklist['.$q2prop['fieldid'].'_date]"  title="'.strip_tags($q2prop['title']).' : Yes" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q2prop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$q2prop['fieldid'].']"></label>
                </div>   
                ' : '').'
			</td>
		</tr>
	';
}

$qualitifationlist3 = [
	[
		'title' 	=> 'Based on the results of 2D Echocardiogram OR, if applicable, cardiac catheterization OR CT angiogram:<sup>2</sup>',
		'fieldid' 	=> 'q_3_a',
		'noinput'	=> true,
		'sub'		=> false,
		'hassub'    => true,
	],
	[
		'title' 	=> '<b class="ml-10">a. Confirmed Tetralogy of Fallot OR Confirmed Ventricular Septal Defect and pulmonic stenosis, severe (This is similar to TOF morphology)<sup>3</sup>',
		'fieldid' 	=> 'q_3_1',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '<b class="ml-10">b. No other associated congenital heart disease (CHD) that includes the following:',
		'fieldid' 	=> 'q_3_b',
		'noinput'	=> true,
		'sub'		=> true,
		'hassub'    => true,
	],
	[
		'title' 	=> '<b class="ml-20">i. absent pulmonic valve',
		'fieldid' 	=> 'q_3_2',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '<b class="ml-20">ii. pulmonary valve atresia',
		'fieldid' 	=> 'q_3_3',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '<b class="ml-20">iii. atrioventricular septal defect (AVSD) ',
		'fieldid' 	=> 'q_3_4',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '<b class="ml-10">c. Confluent and adequate pulmonary artery sizes OR acceptable pulmonary valve annulus',
		'fieldid' 	=> 'q_3_5',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '<b class="ml-10">d. NO major aorto-pulmonary collateral arteries (MAPCA’s) ',
		'fieldid' 	=> 'q_3_6',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
];

$q3list = [];
foreach($qualitifationlist3 as $q3key => $q3prop)
{
	$bordertop = ($q3prop['sub']) ? 'border-bottom-0 border-top-0 ' : '';
	$borderbottom = ($q3prop['hassub']) ? ' border-bottom-0 ' : '';

	$q3list[] = '
		<tr>
			<td class="text-dark p-10 font-size-16 '.$borderbottom.' '.$bordertop.' align-middle" title="'.strip_tags($q3prop['title']).'"><span class=" ml-20 ">'.$q3prop['title'].'</span></td>
			<td class="align-middle text-center '.$borderbottom.' '.$bordertop.' ">
				'.( (@$q3prop['noinput'] == false) ? '
				<div class="checkbox checkbox-custom custom-control checkbox-primary">
                    <input type="checkbox" id="checklist['.$q3prop['fieldid'].']" name="checklist['.$q3prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" withdatevalue="true" targetdatefld="checklist['.$q3prop['fieldid'].'_date]"  title="'.strip_tags($q3prop['title']).' : Yes" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q3prop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$q3prop['fieldid'].']"></label>
                </div>   
                ' : '').'
			</td>
			<td class="align-middle text-center '.$borderbottom.' '.$bordertop.' ">
				'.( (@$q3prop['noinput'] == false) ? '
				<div class="form-group form-material mt-10 mb-0 text-center" data-plugin="formMaterial">
                    <input type="text" class="form-control text-uppercase text-center" id="checklist['.$q3prop['fieldid'].'_date]" name="checklist['.$q3prop['fieldid'].'_date]" title="'.strip_tags($q3prop['title']).' - Date Done"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="'.( (@$preauthFormData['checklist'][$q3prop['fieldid'].'_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist'][$q3prop['fieldid'].'_date'])) : '') .'" dfvalue="'.( (@$preauthFormData['checklist'][$q3prop['fieldid'].'_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist'][$q3prop['fieldid'].'_date'])) : '') .'"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" '.( (@$ViewMode <> true && @$ForCompliance == false) ? 'disabled' : ( (@$preauthFormData['checklist'][$q3prop['fieldid'].'_date'] == '') ? '' : @$disabled ) ).'>       
                </div>   
                ' : '').'
			</td>
		</tr>
	';
}

?>

<div class="mb-5">
	<h4 class="text-left mt-20 mb-0">ATTESTED BY ATTENDING PEDIATRIC CARDIOLOGIST </h4>  
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i></h6>
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
	<h4 class="text-left mt-20 mb-0">ATTESTED BY ATTENDING PEDIATRIC CARDIOLOGIST </h4>  
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i></h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">QUALIFICATIONS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (count($q2list) > 0 ) ? implode('',$q2list) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5">
	<h6 class="text-right mt-0 pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>)</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">DIAGNOSTICS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES</th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">Date Done <br>(mm/dd/yyyy)</th>
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
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingpediatriccardiologist]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingpediatriccardiologist]" name="checklist[crtby_attendingpediatriccardiologist]" title="Certified correct by Attending Pediatric Cardiologist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingpediatriccardiologist']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingpediatriccardiologist']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_patient]">Conforme by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_patient]" name="checklist[crtby_patient]" title="Patient/Parent/Guardian" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>  
				</td>
				
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Pediatric Cardiologist</span>
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
		                <div class="radio radio-custom radio-control radio-primary radio-inline mr-20">
		                    <input type="radio" id="request[crtby_attendingpediatriccardiology_type_CR]" name="request[crtby_attendingpediatriccardiology_type]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="CR" title="Chair, Department of Pediatric Cardiology" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['request']['crtby_attendingpediatriccardiology_type'] == 'CR' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="request[crtby_attendingpediatriccardiology_type_CR]">Chair, Department of Pediatric Cardiology</label>
		                </div>   
		                <div class="radio radio-custom custom-control radio-primary radio-inline mr-20">
		                    <input type="radio" id="request[crtby_attendingpediatriccardiology_type_CF]" name="request[crtby_attendingpediatriccardiology_type]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="CF" title="Chief, Division of Pediatric CV Surgery" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['request']['crtby_attendingpediatriccardiology_type'] == 'CF' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="request[crtby_attendingpediatriccardiology_type_CF]">Chief, Division of Pediatric CV Surgery</label>
		                </div>  
		                <input type="hidden" id="request[crtby_attendingpediatriccardiology_type]" name="request[crtby_attendingpediatriccardiology_type]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_attendingpediatriccardiology_type']; ?>" title="Attending Pediatric Type" autocomplete="eFormData_off" <?php echo @$disabled; ?> required>  
	              	</div>  
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Patient/Parent/Guardian</span>
				</td>
				
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingpediatriccardiologist_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingpediatriccardiologist_accreno]" name="checklist[crtby_attendingpediatriccardiologist_accreno]" title="Pediatric Cardiologist - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingpediatriccardiologist_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingpediatriccardiologist_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
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


	$("input[id='checklist[crtby_attendingpediatriccardiologist]'][forminputgroup='preauthFormData'],input[id='checklist[crtby_attendingpediatriccardiologist_accreno]'][forminputgroup='preauthFormData']").keyup(function(){
		switch( $(this).attr('id') )
		{
			case 'checklist[crtby_attendingpediatriccardiologist]':
				$("input[id='request[crtby_attendingpediatriccardiologist]'][forminputgroup='preauthFormData']").val($(this).val());
			break;
			case 'checklist[crtby_attendingpediatriccardiologist_accreno]':
				$("input[id='request[crtby_attendingpediatriccardiologist_accreno]'][forminputgroup='preauthFormData']").val($(this).val());
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

	/* ZPAMS-FIX (2026-09): the Chair/Chief radios and their paired hidden field all share the
	   same name -- unlike the working copayment wocp/wcp pattern elsewhere, nothing kept the
	   hidden field (the one actually marked required) in sync with the checked radio, so
	   validation always saw it as blank ("Attending Pediatric Type" required-field error) no
	   matter what the user selected.
	   NOTE: this must stay a slash-star block comment, not a double-slash line comment --
	   the view loader strips newlines from this file's rendered output, and a line comment
	   with no line break after it would swallow every statement that follows on this script tag. */
	$("[type='radio'][name='request[crtby_attendingpediatriccardiology_type]'][forminputgroup='preauthFormData']").click(function(){
		$("[type='hidden'][name='request[crtby_attendingpediatriccardiology_type]']").val($(this).val());
	});

<?php } ?>

  
});
</script>


