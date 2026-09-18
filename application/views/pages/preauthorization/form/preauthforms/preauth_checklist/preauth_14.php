<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> 'No previous radiotherapy for prostate cancer',
		'fieldid' 	=> 'q_1_1',
		'sub'		=> true,
		'hassub'    => true,
	],
	[
		'title' 	=> 'No uncontrolled co-morbid conditions',
		'fieldid' 	=> 'q_1_2',
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
		'title' 	=> '(T1a-T3c), Tumor Grade (Gleason’s score of 6-9) ',
		'fieldid' 	=> 'q_2_1',
		'noinput'	=> false,
		'sub'		=> false,
		'hassub'    => false,
	],
	[
		'title' 	=> 'No evidence of metastasis (documented by any of the following): ',
		'fieldid' 	=> 'q_2_a',
		'noinput'	=> true,
		'sub'		=> false,
		'hassub'    => true,
	],
	[
		'title' 	=> '- Bone scan  ',
		'fieldid' 	=> 'q_2_2',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '- Pelvic CT/MRI ',
		'fieldid' 	=> 'q_2_3',
		'noinput'	=> false,
		'sub'		=> true,
		'hassub'    => false,
	],
	[
		'title' 	=> '- PET Scan ',
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
			<td class="text-dark p-10 font-size-16 '.$borderbottom.' '.$bordertop.' align-middle" title="'.strip_tags($q2prop['title']).'"><span class=" ml-20 ">'.$q2prop['title'].'</span></td>
			<td class="align-middle text-center '.$borderbottom.' '.$bordertop.' ">
				'.( (@$q2prop['noinput'] == false) ? '
				<div class="checkbox checkbox-custom custom-control checkbox-primary">
                    <input type="checkbox" id="checklist['.$q2prop['fieldid'].']" name="checklist['.$q2prop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" withdatevalue="true" targetdatefld="checklist['.$q2prop['fieldid'].'_date]"  title="'.strip_tags($q2prop['title']).' : Yes" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$q2prop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$q2prop['fieldid'].']"></label>
                </div>   
                ' : '').'
			</td>
			<td class="align-middle text-center '.$borderbottom.' '.$bordertop.' ">
				'.( (@$q2prop['noinput'] == false) ? '
				<div class="form-group form-material mt-10 mb-0 text-center" data-plugin="formMaterial">
                    <input type="text" class="form-control text-uppercase text-center" id="checklist['.$q2prop['fieldid'].'_date]" name="checklist['.$q2prop['fieldid'].'_date]" title="'.strip_tags($q2prop['title']).' - Date Done"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="'.( (@$preauthFormData['checklist'][$q2prop['fieldid'].'_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist'][$q2prop['fieldid'].'_date'])) : '') .'" dfvalue="'.( (@$preauthFormData['checklist'][$q2prop['fieldid'].'_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist'][$q2prop['fieldid'].'_date'])) : '') .'"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" '.( (@$ViewMode <> true && @$ForCompliance == false) ? 'disabled' : ( (@$preauthFormData['checklist'][$q2prop['fieldid'].'_date'] == '') ? '' : @$disabled ) ).'>       
                </div>   
                ' : '').'
			</td>
		</tr>
	';
}

?>

<div class="mb-5">
	<h4 class="text-left mt-20 mb-0">ATTESTED BY ATTENDING PHYSICIAN  </h4>  
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) if YES</h6>
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
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingphysician]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingphysician]" name="checklist[crtby_attendingphysician]" title="Certified correct by Attending Physician" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingphysician']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingphysician']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Patient/Parent/Guardian</span>
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
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingphysician_accreno]" name="checklist[crtby_attendingphysician_accreno]" title="Physician - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingphysician_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingphysician_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
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


