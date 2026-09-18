<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> '1. The child’s chronological age is 0 to 17 years and 364 days old (required for all)',
		'fieldid' 	=> 'q_x_1',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '2. The child must have undergone a visual disabilities assessment from an ophthalmologist where the child was categorized into Category 1, 2, 3, 4, or 5 visual disability and determined to need assistive devices with prescribed appropriate rehabilitation plan',
		'fieldid' 	=> 'q_x_2',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => true,
		'noinput'	=> false,
	],
	[
		'title' 	=> '3. The child needs an ocular prosthesis. Please tick corresponding box:',
		'fieldid' 	=> 'q_x_3',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => true,
		'noinput'	=> false,
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

	if($qprop['fieldid'] == 'q_1_2')
	{
		$vcl = [];
		for($i=1;$i<6;$i++)	
		{
			$vcl[] = '
				<div class="radio radio-custom custom-control radio-primary radio-inline mr-20">
	                <input type="radio" id="checklist[q_1_2_1_'.$i.']" name="checklist[q_1_2_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="'.$i.'" title="visual acuity '.$i.'" autocomplete="eFormData_off" '.((@$preauthFormData['checklist']['q_1_2_1'] == $i ) ? 'checked' : '').' '.@$disabled.' >
	                <label class="font-size-16 text-uppercase" for="checklist[q_1_2_1_'.$i.']">'.$i.'</label>
	            </div>  
			';
		}

		$addons = '
			<div class="form-group form-material mb-0 ml-60" data-plugin="formMaterial">
			<h5>Child’s best-corrected visual acuity in the better eye (please tick one):</h5>
           		'.( implode('',$vcl) ).'
	        </div>
		';
	}

	if($qprop['fieldid'] == 'q_1_3')
	{
		$vcl = [];
		for($i=1;$i<6;$i++)	
		{
			$vcl[] = '
				
			';
		}

		$addons = '
			<div class="form-group form-material mb-0 ml-60" data-plugin="formMaterial">
           		<div class="radio radio-custom custom-control radio-primary mr-20">
	                <input type="radio" id="checklist[q_1_3_1_1]" name="checklist[q_1_3_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="The child has an enucleated eye" autocomplete="eFormData_off" '.((@$preauthFormData['checklist']['q_1_3_1'] == '1' ) ? 'checked' : '').' '.@$disabled.' >
	                <label class="font-size-16 text-uppercase" for="checklist[q_1_3_1_1]">The child has an enucleated eye</label>
	            </div>  
	            <div class="radio radio-custom custom-control radio-primary mr-20">
	                <input type="radio" id="checklist[q_1_3_1_2]" name="checklist[q_1_3_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Other clinical indications determined by ophthalmologists" autocomplete="eFormData_off" '.((@$preauthFormData['checklist']['q_1_3_1'] == '2' ) ? 'checked' : '').' '.@$disabled.' >
	                <label class="font-size-16 text-uppercase" for="checklist[q_1_3_1_2]">Other clinical indications determined by ophthalmologists</label>
	            </div>  
	            <div class="form-group form-material mt-0 ml-30 mb-0" data-plugin="formMaterial">
                	<textarea id="checklist[q_1_3_2]" name="checklist[q_1_3_2]" class="form-control font-size-16 show" placeholder="other specify" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="'.@$preauthFormData['checklist']['q_1_3_2'].'" dfvalue="'.@$preauthFormData['checklist']['q_1_3_2'].'"  onKeyPress="return isAllowedChar(event)" '.@$disabled.'>'.((@$preauthFormData['checklist']['q_1_3_2'] <> '') ? $preauthFormData['checklist']['q_1_3_2'] : '').'</textarea>
            	</div>
	        </div>
		';
	}


	$$qgrp[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle '.$borderbottom.' '.$bordertop.'" title="'.strip_tags($qprop['title']).'">
				<span class=" ml-20 ">'.$qprop['title'].'</span>
				'.@$addons.'
			</td>
			<td class="align-top text-center '.$borderbottom.' '.$bordertop.'">
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
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) in the status column if YES</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="ml-20">GENERAL QUALIFICATIONS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">Status</th>
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
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_patient]">Conforme by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_patient]" name="checklist[crtby_patient]" title="Patient/Parent/Guardian" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>  
				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingophthalmologist]">Attested by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingophthalmologist]" name="checklist[crtby_attendingophthalmologist]" title="Certified correct by Attending Rehabilitation Medicine Specialist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingophthalmologist']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingophthalmologist']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
			

			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Patient/Parent/Guardian</span>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Ophthalmologist</span>
				</td>
				

			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">  
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingophthalmologist_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingophthalmologist_accreno]" name="checklist[crtby_attendingophthalmologist_accreno]" title="Rehabilitation Medicine Specialist - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingophthalmologist_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingophthalmologist_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
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


	$("input[id='checklist[crtby_attendingophthalmologist]'][forminputgroup='preauthFormData'],input[id='checklist[crtby_attendingophthalmologist_accreno]'][forminputgroup='preauthFormData']").keyup(function(){
		switch( $(this).attr('id') )
		{
			case 'checklist[crtby_attendingophthalmologist]':
				$("input[id='request[crtby_attendingophthalmologist]'][forminputgroup='preauthFormData']").val($(this).val());
			break;
			case 'checklist[crtby_attendingophthalmologist_accreno]':
				$("input[id='request[crtby_attendingophthalmologist_accreno]'][forminputgroup='preauthFormData']").val($(this).val());
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


