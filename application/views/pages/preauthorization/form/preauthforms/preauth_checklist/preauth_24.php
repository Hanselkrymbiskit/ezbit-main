<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> '1. The child’s chronological age is 0 to 17 years and 364 days old',
		'fieldid' 	=> 'q_x_1',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '2. The child presents with functional problems secondary to delays, regressions, or deviations in any one of the following developmental domains:',
		'fieldid' 	=> 'q_x_2',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => true,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-30"> - Cognitive-adaptive',
		'fieldid' 	=> 'q_x_a',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> true,
	],
	[
		'title' 	=> '<b class="ml-30"> - Motor',
		'fieldid' 	=> 'q_x_b',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> true,
	],
	[
		'title' 	=> '<b class="ml-30"> - Social',
		'fieldid' 	=> 'q_x_c',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> true,
	],
	[
		'title' 	=> '<b class="ml-30"> - Emotional',
		'fieldid' 	=> 'q_x_d',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> true,
	],
	[
		'title' 	=> '<b class="ml-30"> - Behavioral',
		'fieldid' 	=> 'q_x_e',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> true,
	],
	[
		'title' 	=> '2. The child presents with functional problems secondary to delays, regressions, or deviations in any one of the following developmental domains:',
		'fieldid' 	=> 'q_x_3',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => true,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-30"> - Physiatrist/ Rehabilitation Medicine Specialist',
		'fieldid' 	=> 'q_x_a',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> true,
	],
	[
		'title' 	=> '<b class="ml-30"> - Behavioral Developmental Pediatrician or Neurodevelopmental Pediatrician',
		'fieldid' 	=> 'q_x_b',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> true,
	],
];

$q1list = [];
foreach($qualitifationlist as $qkey => $qprop)
{
	$bordertop = ($qprop['sub']) ? 'border-bottom-0 border-top-0 ' : '';
	$borderbottom = ($qprop['hassub']) ? ' border-bottom-0 ' : '';

	$qgrp = 'q'.$qprop['group'].'list';

	if( !isset(${$qgrp}) )
	{
		$$qgrp = [];
	}

	$qprop['fieldid'] = str_replace('_x_','_'.$qprop['group'].'_',$qprop['fieldid']);

	$$qgrp[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle '.$borderbottom.' '.$bordertop.'" title="'.strip_tags($qprop['title']).'"><span class=" ml-20 ">'.$qprop['title'].'</span></td>
			<td class="align-middle text-center '.$borderbottom.' '.$bordertop.'">
				'.( ($qprop['noinput']) ? '' : '
				<div class="checkbox checkbox-custom custom-control checkbox-primary">
                    <input type="checkbox" id="checklist['.$qprop['fieldid'].'_Y]" name="checklist['.$qprop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="'.strip_tags($qprop['title']).' : YES" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$qprop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$qprop['fieldid'].'_Y]"></label>
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
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="ml-20">GENERAL QUALIFICATIONS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES</th>
			</tr>
		</thead>
		<tbody>
			<?php echo ( is_array(@$q1list) && count(@$q1list) > 0 ) ? implode('',$q1list) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5">
	<table class="table table-sm table-bordered">
		<tbody>
			<tr>
				<td class="text-dark p-10 font-size-16 font-weight-bold align-middle" title="Place a check on the appropriate box/es for the appropriate assessment/s or evaluation/s that will be given to the child:"><span class=" ml-20 ">Place a (<i class="fa fa-fw fa-check"></i>) on the appropriate box/es for the appropriate assessment/s or evaluation/s that will be given to the child:</span></td>
			</tr>
			<tr>
				<td class="align-middle text-left border-top-0 border-right-0">
					<div class="form-group form-material mb-0 ml-20" data-plugin="formMaterial">
		             	<div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_1_1]" name="checklist[q_2_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Speech Therapy Assessment" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_1'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_1_1]">Speech Therapy Assessment</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_1_2]" name="checklist[q_2_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Occupational Therapy Assessment" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_1'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_1_2]">Occupational Therapy Assessment</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_1_3]" name="checklist[q_2_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="3" title="Physical Therapy Assessment" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_1'] == '3' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_1_3]">Physical Therapy Assessment</label>
			            </div>
			        </div>
				</td>
				
			</tr>
			
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
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingmedspecialist]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingmedspecialist]" name="checklist[crtby_attendingmedspecialist]" title="Certified correct by Attending Rehabilitation Medicine Specialist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingmedspecialist']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingmedspecialist']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
			

			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Patient/Parent/Guardian</span>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Medical Specialist</span>
				</td>
				

			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">  
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingmedspecialist_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingmedspecialist_accreno]" name="checklist[crtby_attendingmedspecialist_accreno]" title="Rehabilitation Medicine Specialist - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingmedspecialist_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingmedspecialist_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
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


	$("input[id='checklist[crtby_attendingmedspecialist]'][forminputgroup='preauthFormData'],input[id='checklist[crtby_attendingmedspecialist_accreno]'][forminputgroup='preauthFormData']").keyup(function(){
		switch( $(this).attr('id') )
		{
			case 'checklist[crtby_attendingmedspecialist]':
				$("input[id='request[crtby_attendingmedspecialist]'][forminputgroup='preauthFormData']").val($(this).val());
			break;
			case 'checklist[crtby_attendingmedspecialist_accreno]':
				$("input[id='request[crtby_attendingmedspecialist_accreno]'][forminputgroup='preauthFormData']").val($(this).val());
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


