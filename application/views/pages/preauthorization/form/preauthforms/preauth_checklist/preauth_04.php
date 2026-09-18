<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> '1. Biopsy result',
		'fieldid' 	=> 'q_1_1',
	],
	[
		'title' 	=> '2. No previous radiotherapy for cervical cancer',
		'fieldid' 	=> 'q_1_2',
	],
	[
		'title' 	=> '3. No previous chemotherapy for cervical cancer',
		'fieldid' 	=> 'q_1_3',
	],
	[
		'title' 	=> '4. Treatment plan',
		'fieldid' 	=> 'q_1_4',
	],
	[
		'title' 	=> '5. No uncontrolled co-morbid conditions',
		'fieldid' 	=> 'q_1_5',
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
	<h6 class="text-right mt-0 pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>)</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="w-p40 text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">FIGO Clinical Staging</span></th>
				<th class="w-p30 text-center font-weight-bold text-dark p-10 font-size-16 align-middle"></th>
				<th class="w-p30 text-center font-weight-bold text-dark p-10 font-size-16 align-middle w-150">DATE DONE (mm/dd/yyyy)</th>
			</tr>
		</thead>
		<tbody>
			<tr class="">
				<td class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">Stages: (Choose only one)  </span></td>
				<td class="text-center font-weight-bold text-dark p-10 font-size-16 align-middle">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
		                <select class="form-control text-uppercase text-primary font-size-16 text-center" id="checklist[q_2_1]" name="checklist[q_2_1]" title="FIGO Clinical Staging"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['q_2_1']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['q_2_1']; ?>" <?php echo @$disabled; ?>  required></select> 
	                </div>
				</td>
				<td class="text-center font-weight-bold text-dark p-10 font-size-16 align-middle">
					<div class="form-group form-material mb-0 text-center" data-plugin="formMaterial">
	                    <input type="text" class="form-control text-uppercase text-center" id="checklist[q_2_1_date]" name="checklist[q_2_1_date]" title="FIGO Clinical Staging - Date Done"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo (@$preauthFormData['checklist']['q_2_1_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist']['q_2_1_date'])) : ''; ?>" dfvalue="<?php echo (@$preauthFormData['checklist']['q_2_1_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist']['q_2_1_date'])) : ''; ?>" data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" <?php echo @$disabled; ?> required>       
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

				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingqynecologiconcologist]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingqynecologiconcologist]" name="checklist[crtby_attendingqynecologiconcologist]" title="Certified correct by Attending Gynecologic-Oncologist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingqynecologiconcologist']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingqynecologiconcologist']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span></span>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Gynecologic-Oncologist</span>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">

				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingqynecologiconcologist_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingqynecologiconcologist_accreno]" name="checklist[crtby_attendingqynecologiconcologist_accreno]" title="Gynecologic-Oncologist - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingqynecologiconcologist_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingqynecologiconcologist_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
		                </div>   
	              	</div>  
				</td>
			</tr>
		</tbody>
	</table>
</div>


<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){
	getreferencevalue({objselect:$("select[id='checklist[q_2_1]'][name='checklist[q_2_1]']"),referenceid:'207',filter:'',order:''},'','SELECT STAGES');

<?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>


	$("input[id*='checklist[crtby_'][forminputgroup='preauthFormData']").keyup(function(){
		$("input[id='"+$(this).attr('id').replace('checklist','request')+"'][forminputgroup='preauthFormData']").val($(this).val());
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


