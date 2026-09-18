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
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[patient_lastname]" name="patientinfo[patient_lastname]" title="Patient Lastname" frmgrp="patientinformation"  placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_lastname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_lastname']; ?>" onKeyPress="return isAllowedChar(event)" disabled >       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_firstname]">First Name :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[patient_firstname]" name="patientinfo[patient_firstname]" title="Patient Firstname" frmgrp="patientinformation"   placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_firstname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_firstname']; ?>" onKeyPress="return isAllowedChar(event)" disabled  >       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_middlename]">Middle Name :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[patient_middlename]" name="patientinfo[patient_middlename]" title="Patient Middlename" frmgrp="patientinformation"   placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_middlename']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_middlename']; ?>" onKeyPress="return isAllowedChar(event)" disabled  >       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_suffix]">Suffix :</label>
	                <select class="form-control text-uppercase text-primary font-size-16" id="patientinfo[patient_suffix]" name="patientinfo[patient_suffix]" title="Patient Suffix"  forminputgroup="preauthFormData" frmgrp="patientinformation"  forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_suffix']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_suffix']; ?>" disabled  ></select>       
              	</div>  
			</td>
		</tr>
		<tr>
			<td>
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_sex]">Sex :</label>
	                <select class="form-control text-uppercase text-primary font-size-16" id="patientinfo[patient_sex]" name="patientinfo[patient_sex]" title="Patient Sex"  frmgrp="patientinformation"  placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_sex']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_sex']; ?>" disabled  ></select>       
              </div>  
			</td>
			<td>
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_dateofbirth]">Date of Birth :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[patient_dateofbirth]" name="patientinfo[patient_dateofbirth]" title="Patient Date of Birth." frmgrp="patientinformation"   placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo (@$preauthFormData['patientinfo']['patient_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['patient_dateofbirth'])) : ''; ?>" dfvalue="<?php echo (@$preauthFormData['patientinfo']['patient_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['patient_dateofbirth'])) : ''; ?>"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" disabled  >       
              	</div> 
			</td>
			<td colspan="2">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_philhealthno]">Philhealth ID Number :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[patient_philhealthno]" name="patientinfo[patient_philhealthno]" title="Patient PhilHealth ID No."  frmgrp="patientinformation"  placeholder="00-000000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_philhealthno']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_philhealthno']; ?>"   data-plugin="formatter"  data-pattern="[[99]]-[[999999999]]-[[9]]" disabled  >       
              	</div> 
			</td>
		</tr>

		<tr>
			<td rowspan="2" class="text-uppercase align-middle text-left w-200 bg-light"><span class="pl-10 font-size-30 text-dark">B. MEMBER</span></td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_lastname]">Last Name :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[member_lastname]" name="patientinfo[member_lastname]" title="Member Lastname"  frmgrp="memberinformation"  placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_lastname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_lastname']; ?>" onKeyPress="return isAllowedChar(event)" disabled  >       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_firstname]">First Name :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[member_firstname]" name="patientinfo[member_firstname]" title="Member Firstname"  frmgrp="memberinformation"   placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_firstname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_firstname']; ?>" onKeyPress="return isAllowedChar(event)" disabled  >       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_middlename]">Middle Name :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[member_middlename]" name="patientinfo[member_middlename]" title="Member Middlename"  frmgrp="memberinformation"   placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_middlename']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_middlename']; ?>" onKeyPress="return isAllowedChar(event)" disabled  >       
              	</div>  
			</td>
			<td class="">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_suffix]">Suffix :</label>
	                <select class="form-control text-uppercase text-primary font-size-16" id="patientinfo[member_suffix]" name="patientinfo[member_suffix]" title="Member Suffix"  frmgrp="memberinformation"  placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_suffix']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_suffix']; ?>" disabled  ></select>       
              	</div>  
			</td>
		</tr>
		<tr>
			<td>
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_sex]">Sex :</label>
	                <select class="form-control text-uppercase text-primary font-size-16" id="patientinfo[member_sex]" name="patientinfo[member_sex]" title="Member Sex"   placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_sex']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_sex']; ?>" disabled  ></select>       
              </div>  
			</td>
			<td>
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_dateofbirth]">Date of Birth :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[member_dateofbirth]" name="patientinfo[member_dateofbirth]" title="Patient Date of Birth."  placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo (@$preauthFormData['patientinfo']['member_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['member_dateofbirth'])) : ''; ?>" dfvalue="<?php echo (@$preauthFormData['patientinfo']['member_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['member_dateofbirth'])) : ''; ?>"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" disabled  >       
              	</div> 
			</td>
			<td colspan="2">
				<div class="form-group form-material mb-0" data-plugin="formMaterial">
					<label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[member_philhealthno]">Philhealth ID Number :</label>
	                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="patientinfo[member_philhealthno]" name="patientinfo[member_philhealthno]" title="Member Philhealth ID No."  frmgrp="memberinformation"   placeholder="00-000000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_philhealthno']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_philhealthno']; ?>"   data-plugin="formatter"  data-pattern="[[99]]-[[999999999]]-[[9]]" disabled  >       
              	</div> 
			</td>
		</tr>
	</table>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

	getreferencevalue({objselect:$("select[id='patientinfo[patient_sex]'][name='patientinfo[patient_sex]']"),referenceid:'23',filter:'',order:''},'','SELECT PATIENT SEX');
	getreferencevalue({objselect:$("select[id='patientinfo[member_sex]'][name='patientinfo[member_sex]']"),referenceid:'23',filter:'',order:''},'','SELECT MEMBER SEX');
	getreferencevalue({objselect:$("select[id='patientinfo[patient_suffix]'][name='patientinfo[patient_suffix]']"),referenceid:'24',filter:'',order:''},'','SELECT PATIENT SUFFIX');
	getreferencevalue({objselect:$("select[id='patientinfo[member_suffix]'][name='patientinfo[member_suffix]']"),referenceid:'24',filter:'',order:''},'','SELECT MEMBER SUFFIX');
  
});
</script>


