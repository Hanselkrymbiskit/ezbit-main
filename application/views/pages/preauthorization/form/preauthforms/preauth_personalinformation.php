<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>
 
<div>
	<table class="table table-sm table-bordered">
		<tr>
			<td rowspan="2" class="text-uppercase align-middle text-left w-200 bg-light"><span class="pl-10 font-size-30 text-dark">A. PATIENT</span></td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_lastname]">Last Name :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[patient_lastname]" name="patientinfo[patient_lastname]" title="Patient Lastname" frmgrp="patientinformation" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_lastname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_lastname']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_firstname]">First Name :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[patient_firstname]" name="patientinfo[patient_firstname]" title="Patient Firstname" frmgrp="patientinformation"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_firstname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_firstname']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?>  required>       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_middlename]">Middle Name :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[patient_middlename]" name="patientinfo[patient_middlename]" title="Patient Middlename" frmgrp="patientinformation"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_middlename']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_middlename']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?>  required>       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_suffix]">Suffix :</label>
	                <select class="form-control text-uppercase text-primary font-size-16" id="patientinfo[patient_suffix]" name="patientinfo[patient_suffix]" title="Patient Suffix"  forminputgroup="preauthFormData" frmgrp="patientinformation"  forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_suffix']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_suffix']; ?>" <?php echo @$disabled; ?>  required></select>       
              	</div>  
			</td>
		</tr>
		<tr>
			<td>
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_sex]">Sex :</label>
	                <select class="form-control text-uppercase text-primary font-size-16" id="patientinfo[patient_sex]" name="patientinfo[patient_sex]" title="Patient Sex"  frmgrp="patientinformation" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_sex']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_sex']; ?>" <?php echo @$disabled; ?>  required></select>       
              </div>  
			</td>
			<td>
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_dateofbirth]">Date of Birth :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[patient_dateofbirth]" name="patientinfo[patient_dateofbirth]" title="Patient Date of Birth." frmgrp="patientinformation"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo (@$preauthFormData['patientinfo']['patient_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['patient_dateofbirth'])) : ''; ?>" dfvalue="<?php echo (@$preauthFormData['patientinfo']['patient_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['patient_dateofbirth'])) : ''; ?>"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" <?php echo @$disabled; ?>  required>       
              	</div> 
			</td>
			<td colspan="2">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_philhealthno]">Philhealth ID Number :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[patient_philhealthno]" name="patientinfo[patient_philhealthno]" title="Patient PhilHealth ID No."  frmgrp="patientinformation" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="00-000000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_philhealthno']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_philhealthno']; ?>"   data-plugin="formatter"  data-pattern="[[99]]-[[999999999]]-[[9]]" <?php echo @$disabled; ?>  required>       
              	</div> 
			</td>
		</tr>
		<tr>
			<td rowspan="3" class="text-uppercase align-middle text-left w-200 bg-light"><span class="pl-10 font-size-30 text-dark">B. MEMBER</span></td>
			<td colspan="4">
				<div class="checkbox-inline checkbox-custom custom-control checkbox-primary">
                    <input type="checkbox" id="patientinfo[patient_is_member]" name="patientinfo[patient_is_member]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="Same as Patient" autocomplete="eFormData_off" <?php echo (@$preauthFormData['patientinfo']['patient_is_member'] == 'Y' ) ? 'checked' : ''; ?>  <?php echo @$disabled; ?> >
                    <label class="font-size-12 text-uppercase" for="patientinfo[patient_is_member]>">Same as Patient <i>(Answer the following only if the patient is a dependent)</i></label>
                </div>   
              </div>  
			</td>
		</tr>
		<tr>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_lastname]">Last Name :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[member_lastname]" name="patientinfo[member_lastname]" title="Member Lastname"  frmgrp="memberinformation" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_lastname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_lastname']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?>  required>       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_firstname]">First Name :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[member_firstname]" name="patientinfo[member_firstname]" title="Member Firstname"  frmgrp="memberinformation"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_firstname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_firstname']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?>  required>       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_middlename]">Middle Name :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[member_middlename]" name="patientinfo[member_middlename]" title="Member Middlename"  frmgrp="memberinformation"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_middlename']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_middlename']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?>  required>       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_suffix]">Suffix :</label>
	                <select class="form-control text-uppercase text-primary font-size-16" id="patientinfo[member_suffix]" name="patientinfo[member_suffix]" title="Member Suffix"  frmgrp="memberinformation" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_suffix']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_suffix']; ?>" <?php echo @$disabled; ?>  required></select>       
              	</div>  
			</td>
		</tr>
		<tr>
			<td>
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_sex]">Sex :</label>
	                <select class="form-control text-uppercase text-primary font-size-16" id="patientinfo[member_sex]" name="patientinfo[member_sex]" title="Member Sex"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_sex']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_sex']; ?>" <?php echo @$disabled; ?>  required></select>       
              </div>  
			</td>
			<td>
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_dateofbirth]">Date of Birth :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[member_dateofbirth]" name="patientinfo[member_dateofbirth]" title="Patient Date of Birth." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo (@$preauthFormData['patientinfo']['member_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['member_dateofbirth'])) : ''; ?>" dfvalue="<?php echo (@$preauthFormData['patientinfo']['member_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['member_dateofbirth'])) : ''; ?>"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" <?php echo @$disabled; ?>  required>       
              	</div> 
			</td>
			<td colspan="2">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_philhealthno]">Philhealth ID Number :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[member_philhealthno]" name="patientinfo[member_philhealthno]" title="Member Philhealth ID No."  frmgrp="memberinformation"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="00-000000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_philhealthno']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_philhealthno']; ?>"   data-plugin="formatter"  data-pattern="[[99]]-[[999999999]]-[[9]]" <?php echo @$disabled; ?>  required>       
              	</div> 
			</td>
		</tr>
<?php if($SelectionCriteria){ ?>
		<tr>
			<td colspan="2" class="text-uppercase align-middle text-right w-200 border-right-0"><span class="pl-10 font-size-20 mr-50">Fulfilled selections criteria</span></td>
			<td colspan="3" class="border-0">
				<div class="radio checkbox-custom custom-control checkbox-primary">
                    <input type="radio" id="patientinfo[fulfilled_selection_criteria_Y]" name="patientinfo[fulfilled_selection_criteria]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="Fulfilled selections criteria : Yes" autocomplete="eFormData_off" <?php echo (@$preauthFormData['patientinfo']['fulfilled_selection_criteria'] == 'Y' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?> >
                    <label class="font-size-16 text-uppercase text-dark" for="patientinfo[fulfilled_selection_criteria_Y]">Yes <i class="ml-20"> If yes, proceed to pre-authorization application</i></label>
                </div>   
                <div class="radio checkbox-custom custom-control checkbox-primary">
                    <input type="radio" id="patientinfo[fulfilled_selection_criteria_N]" name="patientinfo[fulfilled_selection_criteria]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="N" title="Fulfilled selections criteria : No" autocomplete="eFormData_off" <?php echo (@$preauthFormData['patientinfo']['fulfilled_selection_criteria'] == 'N' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?> >
                    <label class="font-size-16 text-uppercase  text-dark" for="patientinfo[fulfilled_selection_criteria_N]">No <i class="ml-20"> If no, specify reason/s and encode</i></label>
                    <div class="form-group form-material mt-15 mb-0" data-plugin="formMaterial">
                    	<textarea id="patientinfo[fulfilled_selection_criteria_reason]" name="patientinfo[fulfilled_selection_criteria_reason]" class="form-control font-size-16" placeholder="specify reason/s here..."  forminputgroup="preauthFormData" title="Fulfilled selections Criteria [No] - specify reason/s" forminputgroupcheck="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['fulfilled_selection_criteria_reason']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> ><?php echo (@$preauthFormData['patientinfo']['fulfilled_selection_criteria_reason'] <> '') ? @$preauthFormData['patientinfo']['fulfilled_selection_criteria_reason'] : ''; ?></textarea>
                	</div>
                </div>   
                <input type="hidden" id="patientinfo[fulfilled_selection_criteria]" name="patientinfo[fulfilled_selection_criteria]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['fulfilled_selection_criteria']; ?>" title="Fulfilled selections criteria" autocomplete="eFormData_off" <?php echo @$disabled; ?>  required>
              </div>  
			</td>
		</tr>
<?php } ?>
	</table>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

	getreferencevalue({objselect:$("select[id='patientinfo[patient_sex]'][name='patientinfo[patient_sex]']"),referenceid:'23',filter:'',order:''},'','SELECT PATIENT SEX');
	getreferencevalue({objselect:$("select[id='patientinfo[member_sex]'][name='patientinfo[member_sex]']"),referenceid:'23',filter:'',order:''},'','SELECT MEMBER SEX');
	getreferencevalue({objselect:$("select[id='patientinfo[patient_suffix]'][name='patientinfo[patient_suffix]']"),referenceid:'24',filter:'',order:''},'','SELECT PATIENT SUFFIX');
	getreferencevalue({objselect:$("select[id='patientinfo[member_suffix]'][name='patientinfo[member_suffix]']"),referenceid:'24',filter:'',order:''},'','SELECT MEMBER SUFFIX');

	var request_patientname_fx = function(){
		var patient_suffix = $("select[id='patientinfo[patient_suffix]'][forminputgroup='preauthFormData']").val();
		var request_patientname_suffix = (patient_suffix !== '' && patient_suffix !== "NA" ) ? patient_suffix : '';
		var request_patientname = $("input[id='patientinfo[patient_lastname]'][forminputgroup='preauthFormData']").val() + ', ' + $("input[id='patientinfo[patient_firstname]'][forminputgroup='preauthFormData']").val() + ', ' + ( (request_patientname_suffix) ? request_patientname_suffix+', ' : '') +  $("input[id='patientinfo[patient_middlename]'][forminputgroup='preauthFormData']").val();
		$("span[id='request_patientname']").text(request_patientname);
		$("input[id='checklist[crtby_patient]']").val(request_patientname);
		$("input[id='request[crtby_patient]']").val(request_patientname);
	};

	var automemberempowerment_fx = function(){
		$("[frmgrp='patientinformation'][forminputgroup='preauthFormData']").each(function(){
			$("[id='"+($(this).attr('id').replace('patientinfo[','memberempowerment[a_'))+"'][forminputgroup='preauthFormData']").val($(this).val());
			if($("[id='patientinfo[patient_is_member]'][forminputgroup='preauthFormData']").prop('checked'))
			{
				$("[id='"+($(this).attr('id').replace('patientinfo[patient_','memberempowerment[a_member_'))+"'][forminputgroup='preauthFormData']").val($(this).val());
				$("[id='"+($(this).attr('id').replace('[patient_','[member_'))+"'][forminputgroup='preauthFormData']").val($(this).val());
			}
		});
	};

	$("input[type='text'][forminputgroup='preauthFormData']").keyup(function(){
		switch( $(this).attr('id') )
		{
			case 'patientinfo[patient_firstname]':
			case 'patientinfo[patient_middlename]':
			case 'patientinfo[patient_lastname]':
				request_patientname_fx();
				automemberempowerment_fx();
			break;
			case 'patientinfo[patient_philhealthno]':
				automemberempowerment_fx();
			break;
		}
	});

	$("select[forminputgroup='preauthFormData']").change(function(){
		switch( $(this).attr('id') )
		{
			case 'patientinfo[patient_sex]':
				
					if( $("[type='checkbox'][id='checklist[notesticular]']").length )
					{ 
						if( $(this).val() == 'F')
						{
							$("[type='checkbox'][id='checklist[notesticular]']").closest(".checkbox-custom").parent().append('<span id="checklist_notesticular_na" class="">N/A</span>');
							$("[type='checkbox'][id='checklist[notesticular]']").closest(".checkbox-custom").hide();
							
						}else{
							$("[type='checkbox'][id='checklist[notesticular]']").closest(".checkbox-custom").show();
							$("span[id='checklist_notesticular_na']").remove();
						}
					}

				automemberempowerment_fx();

			break;

			case 'patientinfo[patient_suffix]':
				request_patientname_fx();
				automemberempowerment_fx();
			break;
		}
	});

	$("[name='patientinfo[fulfilled_selection_criteria]'][forminputgroup='preauthFormData']").click(function(){
		if( $(this).prop('checked') && $(this).val() == 'N' )
		{
			$("[name='patientinfo[fulfilled_selection_criteria_reason]']").removeAttr('disabled').attr('required','required');
		}
		else
		{
			$("[name='patientinfo[fulfilled_selection_criteria_reason]']").attr('disabled','disabled').removeAttr('required');
		}
	});

	$("input[type='checkbox'][forminputgroup='preauthFormData'], input[type='radio'][forminputgroup='preauthFormData']").click(function(){
		
		switch($(this).attr('name'))
		{
			case 'patientinfo[patient_is_member]':
				if( $(this).prop('checked') )
				{
					automemberempowerment_fx();

					$("[name*='patientinfo[member_']").each(function(){
						$(this).attr({
							'disabled':'disabled',
						}).removeAttr('required');

						$(this).closest(".form-group.form-material").removeClass('has-success').removeClass('has-danger');
					});
				}
				else
				{
					$("[name*='patientinfo[member_']").removeAttr('disabled').attr('required','required');
				}
			break;

			<?php if($SelectionCriteria){ ?>
			case 'patientinfo[fulfilled_selection_criteria]':
				if( $(this).prop('checked') )
				{
					$("input[type='hidden'][name='patientinfo[fulfilled_selection_criteria]'][forminputgroup='preauthFormData']").val($(this).val());
					$('#preAuthFormWizard').smartWizard("unsetState", [1,2], 'disable');
					$('#preAuthFormWizard').smartWizard("unsetState", [1,2], "hidden");
			   	 	if( $("input[type='hidden'][name='patientinfo[fulfilled_selection_criteria]']").val() == 'N' )
			   	 	{
			   	 		$('#preAuthFormWizard').smartWizard("setState", [1,2], 'disable');
			   	 		$('#preAuthFormWizard').smartWizard("setState", [1,2], "hidden");
			   	 		$("div[id='preauth_checklist_maincontent'], div[id='preauth_request_maincontent']").find("[forminputgroup='preauthFormData']").each(function(){ 
			   	 			if($(this).hasAttr('required'))
			   	 			{
			   	 				$(this).attr('defreq','true').removeAttr('required');
			   	 			}
			   	 		});
			   	 	}
			   	 	else
					{
						$("div[id='preauth_checklist_maincontent'], div[id='preauth_request_maincontent']").find("[forminputgroup='preauthFormData']").each(function(){ 
			   	 			if($(this).hasAttr('defreq'))
			   	 			{
			   	 				$(this).attr('required','required').removeAttr('defreq');
			   	 			}
			   	 		});
					}
				}
				
			break;
			<?php } ?>
		}
		
	});
  
});
</script>


