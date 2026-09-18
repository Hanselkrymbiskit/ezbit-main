<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> 'a. Age ≥ 18 years old',
		'fieldid' 	=> 'q_1_1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
		[
		'title' 	=> 'b. At least three months post-amputation, if acquired',
		'fieldid' 	=> 'q_1_2',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> 'c. Wheelchair independent, community-ambulator with or without crutches, cane or walker',
		'fieldid' 	=> 'q_1_3',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> 'd. On physical examination: no fresh or non-healing wound,neuroma or painful residual limb, no motor strength of <4/5 and limitation of motion of upper and/or lower limbs, no incoordination or poor balance',
		'fieldid' 	=> 'q_1_4',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
];

$q1list = [];
foreach($qualitifationlist as $q1key => $q1prop)
{
	
	$q_1_7 = '
		<div class="form-group form-material mb-0" data-plugin="formMaterial">
            <div class="radio radio-custom custom-control radio-primary radio-inline">
                <input type="radio" id="checklist['.$q1prop['fieldid'].'_L]" name="checklist['.$q1prop['fieldid'].']" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="L" title="Left limb" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q1prop['fieldid']] == 'L' ) ? 'checked' : '').' '.@$disabled.' >
                <label class="font-size-16 text-uppercase" for="checklist['.$q1prop['fieldid'].'_L]">Left limb</label>
            </div>  
            <div class="radio radio-custom custom-control radio-primary radio-inline">
                <input type="radio" id="checklist['.$q1prop['fieldid'].'_R]" name="checklist['.$q1prop['fieldid'].']" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="R" title="Right limb" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q1prop['fieldid']] == 'R' ) ? 'checked' : '').' '.@$disabled.' >
                <label class="font-size-16 text-uppercase" for="checklist['.$q1prop['fieldid'].'_R]">Right limb</label>
            </div>  
            <div class="radio radio-custom custom-control radio-primary radio-inline">
                <input type="radio" id="checklist['.$q1prop['fieldid'].'_B]" name="checklist['.$q1prop['fieldid'].']" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="B" title="Both limb" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q1prop['fieldid']] == 'B' ) ? 'checked' : '').' '.@$disabled.' >
                <label class="font-size-16 text-uppercase" for="checklist['.$q1prop['fieldid'].'_B]">Both limb</label>
            </div>
        </div>
	';



	$q1list[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle" title="'.strip_tags($q1prop['title']).'"><span class=" ml-20 ">'.$q1prop['title'].'</span></td>
			<td class="align-middle text-center">
				'.( ($q1prop['fieldid'] == 'q_1_7') ? @$q_1_7 : '
				<div class="checkbox radio-custom custom-control radio-primary radio-inline">
                    <input type="radio" id="checklist['.$q1prop['fieldid'].'_Y]" name="checklist['.$q1prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="'.strip_tags($q1prop['title']).' : YES" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q1prop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$q1prop['fieldid'].'_Y]">YES</label>
                </div>   
                <div class="checkbox radio-custom custom-control radio-primary radio-inline">
                    <input type="radio" id="checklist['.$q1prop['fieldid'].'_NA]" name="checklist['.$q1prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="NA" title="'.strip_tags($q1prop['title']).' : Not Applicable" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q1prop['fieldid']] == 'NA' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$q1prop['fieldid'].'_NA]">N/A</label>
                </div>   
                ').'
			</td>
		</tr>
	';
}

$qualitifationlist = [
	[
		'title' 	=> 'I. Lower limb',
		'fieldid' 	=> 'q_2_I',
		'sub'		=> false,
		'hassub'    => true,
		'noinput'	=> true,
	],
	[
		'title' 	=> '<b class="ml-20"></b>A. Above knee/ knee disarticulation',
		'fieldid' 	=> 'q_2_1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-20"></b>B. Hip disarticulation',
		'fieldid' 	=> 'q_2_2',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-20"></b>C. Van Ness Rotationplasty',
		'fieldid' 	=> 'q_2_3',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> 'II. Upper limb',
		'fieldid' 	=> 'q_2_II',
		'sub'		=> false,
		'hassub'    => true,
		'noinput'	=> true,
	],
	[
		'title' 	=> '<b class="ml-20"></b>A. Below elbow',
		'fieldid' 	=> 'q_2_4',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-20"></b>B. Above elbow',
		'fieldid' 	=> 'q_2_5',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
	],
];

$q2list = [];
foreach($qualitifationlist as $q2key => $q2prop)
{

	$q2list[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle" title="'.strip_tags($q2prop['title']).'"><span class=" ml-20 ">'.$q2prop['title'].'</span></td>
			<td class="align-middle text-center">
				'.( ($q2prop['noinput']) ? '' : '
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
	             	<div class="radio radio-custom custom-control radio-primary radio-inline">
		                <input type="radio" id="checklist['.$q2prop['fieldid'].'_R]" name="checklist['.$q2prop['fieldid'].']" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="R" title="Right limb" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q2prop['fieldid']] == 'R' ) ? 'checked' : '').' '.@$disabled.' >
		                <label class="font-size-16 text-uppercase" for="checklist['.$q2prop['fieldid'].'_R]">Right limb</label>
		            </div>  
		            <div class="radio radio-custom custom-control radio-primary radio-inline">
		                <input type="radio" id="checklist['.$q2prop['fieldid'].'_L]" name="checklist['.$q2prop['fieldid'].']" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="L" title="Left limb" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q2prop['fieldid']] == 'L' ) ? 'checked' : '').' '.@$disabled.' >
		                <label class="font-size-16 text-uppercase" for="checklist['.$q2prop['fieldid'].'_L]">Left limb</label>
		            </div>  
		            <div class="radio radio-custom custom-control radio-primary radio-inline">
		                <input type="radio" id="checklist['.$q2prop['fieldid'].'_B]" name="checklist['.$q2prop['fieldid'].']" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="B" title="Both limb" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q2prop['fieldid']] == 'B' ) ? 'checked' : '').' '.@$disabled.' >
		                <label class="font-size-16 text-uppercase" for="checklist['.$q2prop['fieldid'].'_B]">Both limb</label>
		            </div>
		        </div>
		        ').'
			</td>
		</tr>
	';
}

?>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) if YES</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">QUALIFICATIONS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES | N/A</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (count($q1list) > 0 ) ? implode('',$q1list) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) on the type of prostheses to be given to the patient</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">Z Benefits*</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">RIGHT | LEFT | BOTH</th>
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


