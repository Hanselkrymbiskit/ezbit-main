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
		'title' 	=> '2. Upon diagnosis and/or post-operative clearance',
		'fieldid' 	=> 'q_x_2',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '3. No sensory deficit over body segment of application',
		'fieldid' 	=> 'q_x_3',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '4. Upper and lower limb manual muscle strength of ≥ 3',
		'fieldid' 	=> 'q_x_4',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '1. Thoracolumbar (T12-L2) spinal fractures involving posterior elements',
		'fieldid' 	=> 'q_x_1',
		'group'		=> '2',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '2. Primary or metastatic lesions to the thoracolumbosacral spine',
		'fieldid' 	=> 'q_x_2',
		'group'		=> '2',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '1. Lumbosacral fractures (L1-L3)',
		'fieldid' 	=> 'q_x_1',
		'group'		=> '3',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '2. Primary or metastatic lesions to the lumbosacral spine',
		'fieldid' 	=> 'q_x_2',
		'group'		=> '3',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '1. Cervical spine fractures (C3-C7) without neurologic deficit',
		'fieldid' 	=> 'q_x_1',
		'group'		=> '4',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '2. Torticollis',
		'fieldid' 	=> 'q_x_2',
		'group'		=> '4',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '3. Metastatic lesions without neurologic deficit',
		'fieldid' 	=> 'q_x_3',
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


?>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) if YES or N/A if not applicable</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="ml-20">GENERAL QUALIFICATIONS</span></th>
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
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="ml-20">Qualifications for Thoracolumbosacral Spinal Orthosis</span></th>
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
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="ml-20">Qualifications for Lumbosacral Spinal Orthosis</span></th>
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
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="ml-20">Qualifications for Cervicothoracic Spinal Orthosis</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES | N/A</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (is_array(@$q4list) && count(@$q4list) > 0 ) ? implode('',$q4list) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5">
	<table class="table table-sm table-bordered">
		<tbody>
			<tr>
				<td class="text-dark p-10 font-size-16 font-weight-bold align-middle border-bottom-0" title="Tick the box corresponding to the type of spinal orthosis to be given to the patient"><span class=" ml-20 ">Tick the box corresponding to the type of spinal orthosis to be given to the patient:</span></td>
			</tr>
			<tr>
				<td class="align-middle text-center border-top-0">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
		             	<div class="radio radio-custom custom-control radio-primary radio-inline">
			                <input type="radio" id="checklist[q_5_1_1]" name="checklist[q_5_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Thoracolumbosacral custom molded spinal orthosis" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_5_1'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_5_1_1]">Thoracolumbosacral custom molded spinal orthosis</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary radio-inline">
			                <input type="radio" id="checklist[q_5_1_2]" name="checklist[q_5_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Lumbosacral custom molded spinal orthosis" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_5_1'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_5_1_2]">Lumbosacral custom molded spinal orthosis</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary radio-inline">
			                <input type="radio" id="checklist[q_5_1_3]" name="checklist[q_5_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="3" title="Cervicothoracic custom molded spinal orthosis" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_5_1'] == '3' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_5_1_3]">Cervicothoracic custom molded spinal orthosis</label>
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


