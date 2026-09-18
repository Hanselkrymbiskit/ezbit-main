<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<div id="preauth_empowerment_maincontent" class="row">
	
	<div class="col-lg-12 col-md-12">
		<div class="panel  mb-5">
			<div class="panel-body">
				<h3 class="border-bottom-only border-style-dashed border-secondary">A. Member/Patient Information </h3>
				<div class="card border-0">
					<div class="card-body p-0">
						<h5>PATIENT (Last name, First name, Middle name, Suffix)</h5>
						<table class="table table-sm table-bordered">
							<tr>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_patient_lastname]">Last Name :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_patient_lastname]" name="memberempowerment[a_patient_lastname]" title="Patient Lastname" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_lastname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_lastname']; ?>" onKeyPress="return isAllowedChar(event)" disabled>       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_patient_firstname]">First Name :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_patient_firstname]" name="memberempowerment[a_patient_firstname]" title="Patient Firstname" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_firstname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_firstname']; ?>" onKeyPress="return isAllowedChar(event)" disabled >       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_patient_middlename]">Middle Name :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_patient_middlename]" name="memberempowerment[a_patient_middlename]" title="Patient Middlename" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_middlename']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_middlename']; ?>" onKeyPress="return isAllowedChar(event)" disabled >       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_patient_suffix]">Suffix :</label>
						                <select class="form-control text-uppercase text-primary font-size-16" id="memberempowerment[a_patient_suffix]" name="memberempowerment[a_patient_suffix]" title="Patient Suffix"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_suffix']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_suffix']; ?>" disabled ></select>       
					              	</div>  
								</td>
								<td>
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_patient_sex]">Sex :</label>
						                <select class="form-control text-uppercase text-primary font-size-16" id="memberempowerment[a_patient_sex]" name="memberempowerment[a_patient_sex]" title="Patient Sex"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_sex']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_sex']; ?>" disabled></select>       
					              </div>  
								</td>
								<td>
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_patient_dateofbirth]">Date of Birth :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_patient_dateofbirth]" name="memberempowerment[a_patient_dateofbirth]" title="Patient Date of Birth." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo (@$preauthFormData['patientinfo']['patient_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['patient_dateofbirth'])) : ''; ?>" dfvalue="<?php echo (@$preauthFormData['patientinfo']['patient_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['patient_dateofbirth'])) : ''; ?>"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" disabled >       
					              	</div> 
								</td>
								<td>
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_patient_philhealthno]">Philhealth ID Number :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_patient_philhealthno]" name="memberempowerment[a_patient_philhealthno]" title="Patient PhilHealth ID No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="00-000000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['patient_philhealthno']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['patient_philhealthno']; ?>"   data-plugin="formatter"  data-pattern="[[99]]-[[999999999]]-[[9]]" disabled >       
					              	</div> 
								</td>
							</tr>
						</table>
						<h5>MEMBER  (if patient is a dependent) (Last name, First name, Middle name, Suffix)</h5>
						<table class="table table-sm table-bordered mb-0">
							<tr>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_lastname]">Last Name :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_member_lastname]" name="memberempowerment[a_member_lastname]" title="Member Lastname" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_lastname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_lastname']; ?>" onKeyPress="return isAllowedChar(event)" disabled>       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_firstname]">First Name :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_member_firstname]" name="memberempowerment[a_member_firstname]" title="Member Firstname" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_firstname']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_firstname']; ?>" onKeyPress="return isAllowedChar(event)" disabled >       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_middlename]">Middle Name :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_member_middlename]" name="memberempowerment[a_member_middlename]" title="Member Middlename" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_middlename']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_middlename']; ?>" onKeyPress="return isAllowedChar(event)" disabled >       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_suffix]">Suffix :</label>
						                <select class="form-control text-uppercase text-primary font-size-16" id="memberempowerment[a_member_suffix]" name="memberempowerment[a_member_suffix]" title="Member Suffix"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_suffix']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_suffix']; ?>" disabled ></select>       
					              	</div>  
								</td>
								<td>
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_sex]">Sex :</label>
						                <select class="form-control text-uppercase text-primary font-size-16" id="memberempowerment[a_member_sex]" name="memberempowerment[a_member_sex]" title="Member Sex"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_sex']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_sex']; ?>" disabled></select>       
					              </div>  
								</td>
								<td>
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_dateofbirth]">Date of Birth :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_member_dateofbirth]" name="memberempowerment[a_member_dateofbirth]" title="Member Date of Birth." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo (@$preauthFormData['patientinfo']['member_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['member_dateofbirth'])) : ''; ?>" dfvalue="<?php echo (@$preauthFormData['patientinfo']['member_dateofbirth'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['patientinfo']['member_dateofbirth'])) : ''; ?>"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" disabled >       
					              	</div> 
								</td>
								<td>
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_philhealthno]">Philhealth ID Number :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_member_philhealthno]" name="memberempowerment[a_member_philhealthno]" title="Member PhilHealth ID No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="00-000000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['patientinfo']['member_philhealthno']; ?>" dfvalue="<?php echo @$preauthFormData['patientinfo']['member_philhealthno']; ?>"   data-plugin="formatter"  data-pattern="[[99]]-[[999999999]]-[[9]]" disabled >       
					              	</div> 
								</td>
							</tr>
						</table>
						<table class="table table-sm table-bordered mb-0">
							<tr>
								<td class="w-p60">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_permanent_address_st]">Permanent Address Street,Blk,lot,subdivision,bldg :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_member_permanent_address_st]" name="memberempowerment[a_member_permanent_address_st]" title="Member Permanent Address" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['memberempowerment']['a_member_permanent_address_st']; ?>" dfvalue="<?php echo @$preauthFormData['memberempowerment']['a_member_permanent_address_st']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
					              	</div>  
								</td>
								<td class="w-p40">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_permanent_address_city]">Permanent Address City :</label>
						                
						                	<?php 
						                		
						                		if( @$preauthFormData['memberempowerment']['a_member_permanent_address_city'] <> '')
					                			{ 
					                				$actyVal = '<option value="'.@$preauthFormData['memberempowerment']['a_member_permanent_address_city'].'" selected="selected">'.$CI->myutilities->getRef_Desc(13,@$preauthFormData['memberempowerment']['a_member_permanent_address_city']).'</option>';
					                			} 
					                			
						                		if( (@$ViewMode <> true || @$ForCompliance == true ) && in_array($CI->userclassification,[1,6,7]) )
						                		{
						                			echo '<select class="form-control text-uppercase text-primary font-size-16 w-p100" id="memberempowerment[a_member_permanent_address_city]" name="memberempowerment[a_member_permanent_address_city]" title="Member Permanent Address : City" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="'.@$preauthFormData['memberempowerment']['a_member_permanent_address_city'].'" dfvalue="'.@$preauthFormData['memberempowerment']['a_member_permanent_address_city'].'" required>'.@$actyVal.'</select>';
						                		}
						                		else
						                		{
						                			echo '
						                				<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_member_permanent_address_city]" name="memberempowerment[a_member_permanent_address_city]" title="Member Permanent Address : City" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="'.$CI->myutilities->getRef_Desc(13,@$preauthFormData['memberempowerment']['a_member_permanent_address_city']).'" dfvalue="'.$CI->myutilities->getRef_Desc(13,@$preauthFormData['memberempowerment']['a_member_permanent_address_city']).'" '.@$disabled.' required>   
						                			';
						                		}
						                		

				                			?>
						                   
					              	</div>  
								</td>
								
							</tr>
						</table>
						<table class="table table-sm table-bordered mb-0">
							<tr>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_telephoneno]">Telephone Number :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_member_telephoneno]" name="memberempowerment[a_member_telephoneno]" title="Member Telephone Number" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['memberempowerment']['a_member_telephoneno']; ?>" dfvalue="<?php echo @$preauthFormData['memberempowerment']['a_member_telephoneno']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> >       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_mobileno]">Mobile Number :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_member_mobileno]" name="memberempowerment[a_member_mobileno]" title="Member Mobile No" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['memberempowerment']['a_member_mobileno']; ?>" dfvalue="<?php echo @$preauthFormData['memberempowerment']['a_member_mobileno']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?>  >       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[a_member_emailaddress]">Email Address :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[a_member_emailaddress]" name="memberempowerment[a_member_emailaddress]" title="Member Email Address" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['memberempowerment']['a_member_emailaddress']; ?>" dfvalue="<?php echo @$preauthFormData['memberempowerment']['a_member_emailaddress']; ?>" onKeyPress="return isPasswordAllowed(event)" <?php echo @$disabled; ?>  >       
					              	</div>  
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-12 col-md-12">
		<div class="panel mb-5">
			<div class="panel-body">
				<h3 class="border-bottom-only border-style-dashed border-secondary">B. Clinical Information </h3>
				<div class="card border-0">
					<div class="card-body p-0">
						<table class="table table-sm table-bordered">
					<?php
						$b_set = [
							'b_1' => ['label'=>'Description of condition', 'info' => ''],
							'b_2' => ['label'=>'Applicable Treatment Plan agreed upon with healthcare provider' , 'info' => ''],
							'b_3' => ['label'=>'Applicable alternative Treatment Plan agreed upon with health care provider' , 'info' => '']
						];
						$b_set_cnt = 0;
						foreach($b_set as $eid => $eprop)
						{
							$b_set_cnt++;
							echo '
						<tr>
							<td class="valign-middle w-p40"><label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment['.$eid.']">'.$b_set_cnt.'. '.@$eprop['label'].' </label>'.( (@$eprop['info'] <> '' ) ? '<p class="font-size-14">'.$eprop['info'].'</p>' : '').'</td>
							<td>
								<div class="form-group form-material mb-0" data-plugin="formMaterial">
					                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment['.$eid.']" name="memberempowerment['.$eid.']" title="'.@$eprop['label'].'" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="'.@$preauthFormData['memberempowerment'][$eid].'" dfvalue="'.@$preauthFormData['memberempowerment'][$eid].'" onKeyPress="return isAllowedChar(event)" '.@$disabled.' required>       
				              	</div>  
							</td>
						</tr>

							';

						}
					?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-lg-12 col-md-12">
		<div class="panel mb-5">
			<div class="panel-body">
				<h3 class="border-bottom-only border-style-dashed border-secondary">C. Treatment Schedule and Follow-up Visit/s  </h3>
				<div class="card border-0">
					<div class="card-body p-0">
						<table class="table table-sm table-bordered">
					<?php
						$c_set = [
							'c_1_date' => ['label'=>'Date of initial admission to HF or consult a (mm/dd/yyyy) ', 'info' => 'For ZMORPH/children with disabilities (CWDs), this refers to the consult prior to the provision of the device and/or rehabilitation. For PD First, this refers to the date of medical consultation or visit to the PD Provider prior to the start of the first PD exchange.  '],
							'c_2_date' => ['label'=>'Tentative Date/s of succeeding admission to HF or consult b (mm/dd/yyyy)' , 'info' => 'For ZMORPH/CWDS, this refers to the measurement, fitting and adjustments of the device. For the PD First, this refers to the next visit to the PD Provider.'],
							'c_3_date' => ['label'=>'Tentative Date/s of follow-up visit/s c (mm/dd/yyyy) ' , 'info' => 'For ZMORPH/CWD, this refers to the external lower limb post-prosthesis rehabilitation consult.']
						];
						$c_set_cnt = 0;
						foreach($c_set as $eid => $eprop)
						{
							$c_set_cnt++;
							$cdval = ( (@$preauthFormData['memberempowerment'][$eid] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['memberempowerment'][$eid])) : '');
							echo '
						<tr>
							<td class="valign-middle w-p70"><label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment['.$eid.']">'.$c_set_cnt.'. '.@$eprop['label'].' </label>'.( (@$eprop['info'] <> '' ) ? '<p class="font-size-14">'.$eprop['info'].'</p>' : '').'</td>
							<td>
								<div class="form-group form-material mb-0" data-plugin="formMaterial">
					                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment['.$eid.']" name="memberempowerment['.$eid.']" title="'.@$eprop['label'].'" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="'.$cdval.'" dfvalue="'.$cdval.'" data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" '.@$disabled.' required>       
				              	</div>  
							</td>
						</tr>

							';

						}
					?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-12 col-md-12">
		<div class="panel mb-5">
			<div class="panel-body">
				<h3 class="border-bottom-only border-style-dashed border-secondary">D. Member Education<span class="float-right font-size-14 text-right text-info">Please select appropriate answer</span></h3>
				<div class="card border-0">
					<div class="card-body p-0">
						
						<table class="table table-sm table-bordered">
					<?php
						$d_set = [
							'd_1' 	=> ['havesub'=>false,'subdata'=>false,'label'=>'My health care provider explained the nature of my condition/disability', 'info' => ''],
							'd_2' 	=> ['havesub'=>false,'subdata'=>false,'label'=>'My health care provider explained the treatment options/intervention' , 'info' => 'For ZMORPH, this refers to the need for pre- and post-device provision and rehabilitation.'],
							'd_3' 	=> ['havesub'=>false,'subdata'=>false,'label'=>'The possible side effects/adverse effects of treatment/intervention were explained to me.' , 'info' => ''],
							'd_4' 	=> ['havesub'=>false,'subdata'=>false,'label'=>'My health care provider explained the mandatory services and other services required for the treatment of my condition/intervention.' , 'info' => ''],
							'd_5' 	=> ['havesub'=>false,'subdata'=>false,'label'=>'I am satisfied with the explanation given to me by my health care provider' , 'info' => ''],
							'd_6' 	=> ['havesub'=>false,'subdata'=>false,'label'=>' I have been fully informed that I will be cared for by all the pertinent medical and allied specialties, as needed, present in the PhilHealth contracted HF of my choice and that preferring another contracted HF for the said specialized care will not affect my treatment in any way.' , 'info' => ''],
							'd_7' 	=> ['havesub'=>false,'subdata'=>false,'label'=>' My health care provider explained the importance of adhering to my treatment plan/intervention. This includes completing the course of treatment/intervention in the contracted HF where my treatment/intervention was initiated. ' , 'info' => 'Note: Non-adherence of the patient to the agreed treatment plan/intervention in the HF may result to denial of filed claims for the succeeding tranches and which should not be filed as case rates. '],
							'd_8' 	=> ['havesub'=>false,'subdata'=>false,'label'=>'My health care provider gave me the schedule/s of my follow-up visit/s. ' , 'info' => ''],
							'd_9' 	=> ['havesub'=>false,'subdata'=>false,'label'=>'My health care provider gave me information where to go for financial and other means of support, when needed.' , 'info' => '<ol type="a"><li>Government agency (ex. PCSO, PMS, LGU, etc.)</li><li>Civil society or non-government organization</li><li>Patient Support Group</li><li>Corporate Foundation</li><li>Others (ex. Media, Religious Group, Politician, etc.)</li></ol>'],
							'd_10' 	=> ['havesub'=>false,'subdata'=>false,'label'=>' I have been furnished by my health care provider with a list of other contracted HFs for the specialized care of my condition.' , 'info' => ''],
							'd_11' 	=> ['havesub'=>true,'subdata'=>false,'label'=>'I have been fully informed by my health care provider of the PhilHealth membership policies and benefit availment on the Z Benefits:' , 'info' => ''],
							'd_11_a' 	=> ['havesub'=>false,'subdata'=>true,'label'=>'a.  I fulfill all selections criteria for my condition/disability.' , 'info' => ''],
							'd_11_b' 	=> ['havesub'=>false,'subdata'=>true,'label'=>'b.  The “no balance billing” (NBB) policy was explained to me.' , 'info' => 'Note:  NBB policy is applicable to the following members when admitted in ward accommodation: sponsored, indigent, household help, senior citizens and iGroup members with valid Group Policy Contract (GPC) and their qualified dependents.'],
							'd_11_c' 	=> ['havesub'=>false,'subdata'=>true,'label'=>'c.  I understand that I may choose not to avail of the NBB and may be charged out of pocket expenses' , 'info' => ''],
							'd_11_d' 	=> ['havesub'=>false,'subdata'=>true,'label'=>'d.  In case I choose to upgrade my room accommodation or avail of additional services that are not included in the benefit package, I understand that I can no longer demand the hospital to grant me the privilege given to NBB patients (that is, no out of pocket payment upon discharge from the hospital)' , 'info' => ''],
							'd_11_e' 	=> ['havesub'=>false,'subdata'=>true,'label'=>'e.  I opt out of the NBB policy of PhilHealth and I am willing to pay on top of my PhilHealth benefits ' , 'info' => ''],
							'd_11_f' 	=> ['havesub'=>false,'subdata'=>true,'label'=>'f.  I agree to pay as mush as PHP ______* for the following: %inputsdetailhere%' , 'info' => 'This is an estimated amount that guides the patient on how much the out of pocket may be and should not be a basis for auditing claims reimbursement.<br>For the Z Benefits Package for Breast Cancer, the co-payment of the patient shall be based per treatment phase in accordance with the agreed treatment plan of the MDT. No-copayment shall be applicable for benefits under targeted therapy.'],
							'd_11_g' 	=> ['havesub'=>false,'subdata'=>true,'label'=>'g.  I understand that there may be an additional payment on top of my PhilHealth benefits.' , 'info' => ''],
							'd_11_h' 	=> ['havesub'=>false,'subdata'=>true,'label'=>'h.  I agree to pay as mush as PHP ______* as additional payment on top of my PhilHealth benefits. %inputsdetailhere2%' , 'info' => '- This is an estimated amount that guides the patient on how much the out of pocket may be and should not be a basis for auditing claims reimbursement.<br>- For the Z Benefits Package for Breast Cancer, the co-payment of the patient shall be based per treatment phase in accordance with the agreed treatment plan of the MDT. No-copayment shall be applicable for benefits under targeted therapy.'],
							'd_12' 	=> ['havesub'=>false,'subdata'=>false,'label'=>'Only five (5) days shall be deducted from the 45 confinement days benefit limit per year for the duration of my treatment/intervention under the Z Benefits. ' , 'info' => ''],
						];
						$d_set_cnt = 0;
						foreach($d_set as $eid => $eprop)
						{
							if(!$eprop['subdata']){ $d_set_cnt++; }
							$cdvalY = (@$preauthFormData['memberempowerment'][$eid] == 'Y' ) ? 'checked' : '';
							$cdvalN = (@$preauthFormData['memberempowerment'][$eid] == 'N' ) ? 'checked' : '';
							$cdvalNA = (@$preauthFormData['memberempowerment'][$eid] == 'NA' ) ? 'checked' : '';

							$inputsdetailhere = '

							<div class="form-group form-material mt-10 mb-10" data-plugin="formMaterial">
								<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[d_11_f_php]">PHP :</label>
				                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[d_11_f_php]" name="memberempowerment[d_11_f_php]" title="I agree to pay as mush as PHP" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="'.@$preauthFormData['memberempowerment']['d_11_f_php'].'" dfvalue="'.@$preauthFormData['memberempowerment']['d_11_f_php'].'" onKeyPress="return isDecimalKey(event,this)" '.@$disabled.' required>       
			              	</div>  
			              	<div class="form-group form-material mb-0" data-plugin="formMaterial">
					                <div class="radio radio-custom radio-control radio-primary radio-inline mr-20">
					                    <input type="radio" id="memberempowerment[d_11_f_following_U]" name="memberempowerment[d_11_f_following]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="U" title="I choose to upgrade my room accommodation" autocomplete="eFormData_off" '.( (@$preauthFormData['memberempowerment']['d_11_f_following'] == 'U' ) ? 'checked' : '' ).' '.@$disabled.'>
					                    <label class="font-size-16 text-uppercase text-dark" for="memberempowerment[d_11_f_following_U]">I choose to upgrade my room accommodation</label>
					                </div>   
					                <div class="radio radio-custom custom-control radio-primary radio-inline mr-20">
					                    <input type="radio" id="memberempowerment[d_11_f_following_S]" name="memberempowerment[d_11_f_following]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="S" title="additional services, specify " autocomplete="eFormData_off" '.( (@$preauthFormData['memberempowerment']['d_11_f_following'] == 'S' ) ? 'checked' : '' ).' '.@$disabled.'>
					                    <label class="font-size-16 text-uppercase text-dark" for="memberempowerment[d_11_f_following_S]">additional services, specify</label>
					                </div>   
					                <input type="hidden" id="memberempowerment[d_11_f_following]" name="memberempowerment[d_11_f_following]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="'.@$preauthFormData['memberempowerment']['d_11_f_following'].'" title="I agree to pay for the following:" autocomplete="eFormData_off" '.@$disabled.' required>    
			              	</div>  

							';

							$inputsdetailhere2 = '
							<div class="form-group form-material mt-10 mb-0" data-plugin="formMaterial">
								<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[d_11_h_php]">PHP :</label>
				                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[d_11_h_php]" name="memberempowerment[d_11_h_php]" title="I agree to pay as mush as PHP" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="'.@$preauthFormData['memberempowerment']['d_11_h_php'].'" dfvalue="'.@$preauthFormData['memberempowerment']['d_11_h_php'].'" onKeyPress="return isDecimalKey(event,this)" '.@$disabled.' required>       
			              	</div> 
							';

							$etitle = str_replace(['%inputsdetailhere%','%inputsdetailhere2%'], ['',''], $eprop['label'] );
							$eprop['label'] = str_replace(['%inputsdetailhere%','%inputsdetailhere2%'], [$inputsdetailhere,$inputsdetailhere2], $eprop['label'] );

							echo '
						<tr>
							<td class="valign-middle w-p70 '.(($eprop['havesub']) ? 'border-bottom-0' : '').' '.(($eprop['subdata']) ? 'border-top-0 border-bottom-0' : '').'"><label class="label font-weight-bold font-size-16 text-uppercase '.(($eprop['subdata']) ? 'ml-30' : '').'"  for="memberempowerment['.$eid.']">'.((!$eprop['subdata']) ? $d_set_cnt.'. ' : '').@$eprop['label'].' </label>'.( (@$eprop['info'] <> '' ) ? '<p class="font-size-14">'.$eprop['info'].'</p>' : '').'</td>
							<td class="text-center '.(($eprop['havesub']) ? 'border-bottom-0' : '').' '.(($eprop['subdata']) ? 'border-top-0 border-bottom-0' : '').'">
								<div class="form-group form-material mb-0" data-plugin="formMaterial">
					                <div class="radio radio-custom radio-control radio-primary radio-inline mr-20">
					                    <input type="radio" id="memberempowerment['.$eid.'_Y]" name="memberempowerment['.$eid.']" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="'.@$etitle.' : YES" autocomplete="eFormData_off" '.$cdvalY.' '.@$disabled.'>
					                    <label class="font-size-16 text-uppercase text-dark" for="memberempowerment['.$eid.'_Y]">YES</label>
					                </div>   
					                <div class="radio radio-custom custom-control radio-primary radio-inline mr-20">
					                    <input type="radio" id="memberempowerment['.$eid.'_N]" name="memberempowerment['.$eid.']" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="N" title="'.@$etitle.' : NO" autocomplete="eFormData_off" '.$cdvalN.' '.@$disabled.'>
					                    <label class="font-size-16 text-uppercase text-dark" for="memberempowerment['.$eid.'_N]">NO</label>
					                </div>  
					                <div class="radio radio-custom custom-control radio-primary radio-inline">
					                    <input type="radio" id="memberempowerment['.$eid.'_NA]" name="memberempowerment['.$eid.']" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="NA" title="'.@$etitle.' : N/A" autocomplete="eFormData_off" '.$cdvalNA.' '.@$disabled.'>
					                    <label class="font-size-16 text-uppercase text-dark" for="memberempowerment['.$eid.'_NA]">N/A</label>
					                </div>  
					                <input type="hidden" id="memberempowerment['.$eid.']" name="memberempowerment['.$eid.']" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="'.@$preauthFormData['memberempowerment'][$eid].'" title="'.@$etitle.'" autocomplete="eFormData_off" '.@$disabled.' required>    
				              	</div>  
							</td>
						</tr>

							';

						}
					?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-12 col-md-12">
		<div class="panel mb-5">
			<div class="panel-body">
				<h3 class="border-bottom-only border-style-dashed border-secondary">E.  Member Roles and Responsibilities<span class="float-right font-size-14 text-right text-info">Please select appropriate answer</span></h3>
				<div class="card border-0">
					<div class="card-body p-0">
						
						<table class="table table-sm table-bordered">
					<?php
						$e_set = [
							'e_1' 	=> ['havesub'=>false,'subdata'=>false,'label'=>' I understand that I am responsible for adhering to my treatment schedule. ', 'info' => ''],
							'e_2' 	=> ['havesub'=>false,'subdata'=>false,'label'=>' I understand that adherence to my treatment schedule is important in terms of clinical outcomes and a pre-requisite to the full entitlement of the Z benefits. ' , 'info' => ''],
							'e_3' 	=> ['havesub'=>false,'subdata'=>false,'label'=>'I understand that it is my responsibility to follow and comply with all the policies and procedures of PhilHealth and the health care provider in order to avail of the full Z benefit package.  In the event that I fail to comply with policies and procedures of PhilHealth and the health care provider, I waive the privilege of availing the Z benefits. ' , 'info' => ''],
				
						];
						$e_set_cnt = 0;
						foreach($e_set as $eid => $eprop)
						{
							if(!$eprop['subdata']){ $d_set_cnt++; }
							$cdvalY = (@$preauthFormData['memberempowerment'][$eid] == 'Y' ) ? 'checked' : '';
							$cdvalN = (@$preauthFormData['memberempowerment'][$eid] == 'N' ) ? 'checked' : '';
							$cdvalNA = (@$preauthFormData['memberempowerment'][$eid] == 'NA' ) ? 'checked' : '';

							$etitle = $eprop['label'];

							echo '
						<tr>
							<td class="valign-middle w-p70 '.(($eprop['havesub']) ? 'border-bottom-0' : '').' '.(($eprop['subdata']) ? 'border-top-0 border-bottom-0' : '').'"><label class="label font-weight-bold font-size-16 text-uppercase '.(($eprop['subdata']) ? 'ml-30' : '').'"  for="memberempowerment['.$eid.']">'.((!$eprop['subdata']) ? $e_set_cnt.'. ' : '').@$eprop['label'].' </label>'.( (@$eprop['info'] <> '' ) ? '<p class="font-size-14">'.$eprop['info'].'</p>' : '').'</td>
							<td class="text-center '.(($eprop['havesub']) ? 'border-bottom-0' : '').' '.(($eprop['subdata']) ? 'border-top-0 border-bottom-0' : '').'">
								<div class="form-group form-material mb-0" data-plugin="formMaterial">
					                <div class="radio radio-custom radio-control radio-primary radio-inline mr-20">
					                    <input type="radio" id="memberempowerment['.$eid.'_Y]" name="memberempowerment['.$eid.']" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="'.@$etitle.' : YES" autocomplete="eFormData_off" '.$cdvalY.' '.@$disabled.'>
					                    <label class="font-size-16 text-uppercase text-dark" for="memberempowerment['.$eid.'_Y]">YES</label>
					                </div>   
					                <div class="radio radio-custom custom-control radio-primary radio-inline mr-20">
					                    <input type="radio" id="memberempowerment['.$eid.'_N]" name="memberempowerment['.$eid.']" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="N" title="'.@$etitle.' : NO" autocomplete="eFormData_off" '.$cdvalN.' '.@$disabled.'>
					                    <label class="font-size-16 text-uppercase text-dark" for="memberempowerment['.$eid.'_N]">NO</label>
					                </div>  
					                <div class="radio radio-custom custom-control radio-primary radio-inline">
					                    <input type="radio" id="memberempowerment['.$eid.'_NA]" name="memberempowerment['.$eid.']" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="NA" title="'.@$etitle.' : N/A" autocomplete="eFormData_off" '.$cdvalNA.' '.@$disabled.'>
					                    <label class="font-size-16 text-uppercase text-dark" for="memberempowerment['.$eid.'_NA]">N/A</label>
					                </div>  
					                <input type="hidden" id="memberempowerment['.$eid.']" name="memberempowerment['.$eid.']" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="'.@$preauthFormData['memberempowerment'][$eid].'" title="'.@$etitle.'" autocomplete="eFormData_off" '.@$disabled.' required>    
				              	</div>  
							</td>
						</tr>

							';

						}
					?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-12 col-md-12">
		<div class="panel  mb-5">
			<div class="panel-body">
				<h3 class="border-bottom-only border-style-dashed border-secondary">F. PhilHealth Z Coordinator Contact Details</h3>
				<div class="card border-0">
					<div class="card-body p-0">
						<h5>Name of PhilHealth Z Coordinator assigned at the Health Facility</h5>
						<table class="table table-sm table-bordered mb-0">
							<tr>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[z__lastname]">Last Name :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[z_coordinator_lastname]" name="memberempowerment[z_coordinator_lastname]" title="Z Coordinator Lastname" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_lastname']; ?>" dfvalue="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_lastname']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[z_coordinator_firstname]">First Name :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[z_coordinator_firstname]" name="memberempowerment[z_coordinator_firstname]" title="Member Firstname" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_firstname']; ?>" dfvalue="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_firstname']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?>  required>       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[z_coordinator_middlename]">Middle Name :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[z_coordinator_middlename]" name="memberempowerment[z_coordinator_middlename]" title="Z Coordinator Middlename" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_middlename']; ?>" dfvalue="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_middlename']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?>  required>       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[z_coordinator_suffix]">Suffix :</label>
						                <select class="form-control text-uppercase text-primary font-size-16" id="memberempowerment[z_coordinator_suffix]" name="memberempowerment[z_coordinator_suffix]" title="Z Coordinator Suffix"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_suffix']; ?>" dfvalue="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_suffix']; ?>" <?php echo @$disabled; ?>  required></select>       
					              	</div>  
								</td>
							</tr>
						</table>
						<table class="table table-sm table-bordered mb-0">
							<tr>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[z_coordinator_telephoneno]">Telephone Number :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[z_coordinator_telephoneno]" name="memberempowerment[z_coordinator_telephoneno]" title="Z Coordinator Telephone Number" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_telephoneno']; ?>" dfvalue="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_telephoneno']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> >       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[z_coordinator_mobileno]">Mobile Number :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[z_coordinator_mobileno]" name="memberempowerment[z_coordinator_mobileno]" title="Z Coordinator Mobile No" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_mobileno']; ?>" dfvalue="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_mobileno']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?>  >       
					              	</div>  
								</td>
								<td class="">
									<div class="form-group form-material mb-0" data-plugin="formMaterial">
										<label class="label font-weight-bold font-size-16 text-uppercase"  for="memberempowerment[z_coordinator_emailaddress]">Email Address :</label>
						                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="memberempowerment[z_coordinator_emailaddress]" name="memberempowerment[z_coordinator_emailaddress]" title="Z Coordinator Email Address" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_emailaddress']; ?>" dfvalue="<?php echo @$preauthFormData['memberempowerment']['z_coordinator_emailaddress']; ?>" onKeyPress="return isPasswordAllowed(event)" <?php echo @$disabled; ?>  >       
					              	</div>  
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){
	getreferencevalue({objselect:$("select[id='memberempowerment[a_patient_suffix]'][name='memberempowerment[a_patient_suffix]']"),referenceid:'24',filter:'',order:''},'','SELECT PATIENT SUFFIX');
	getreferencevalue({objselect:$("select[id='memberempowerment[a_member_suffix]'][name='memberempowerment[a_member_suffix]']"),referenceid:'24',filter:'',order:''},'','SELECT MEMBER SUFFIX');
	getreferencevalue({objselect:$("select[id='memberempowerment[a_patient_sex]'][name='memberempowerment[a_patient_sex]']"),referenceid:'23',filter:'',order:''},'','SELECT MEMBER SEX');
	getreferencevalue({objselect:$("select[id='memberempowerment[a_member_sex]'][name='memberempowerment[a_member_sex]']"),referenceid:'23',filter:'',order:''},'','SELECT MEMBER SEX');
	getreferencevalue({objselect:$("select[id='memberempowerment[z_coordinator_suffix]'][name='memberempowerment[z_coordinator_suffix]']"),referenceid:'24',filter:'',order:''},'','SELECT Z-COORDINATOR SUFFIX');
	
	$("input[type='radio'][name*='memberempowerment['][forminputgroup='preauthFormData']").click(function(){
		if( $(this).prop('checked') )
		{
			$("input[type='hidden'][name='"+$(this).attr('name')+"'][forminputgroup='preauthFormData']").val($(this).val());
		}
	});

 	$("select[id='memberempowerment[a_member_permanent_address_city]']").select2({
 		dropdownParent: $("div[id='preauth_empowerment_maincontent']").parent(),
 		width:'100%',
     	ajax: {
            url: site_url+"preauthorization/index/searchCityList",
            dataType: 'json',
            delay: 250,
            method: "POST",
            data: function (params) {

            var ObjPostParam = {
               'q': params.term, 
               'page': params.page,
               'UToken':UToken,
            };

            if( csrfName !== 'ci_csrf_token' && csrfHash !=='')
            {
              ObjPostParam[csrfName] = csrfHash;
            }

            return ObjPostParam;
             
     	},
     	processResults: function (data, params) {

               params.page = params.page || 1;

               return {
                  results: data.results,
                  pagination: {
                    more: (params.page * 10) < data.count_filtered
                  }
               };
            },
            cache: true
     	},
     	placeholder: 'Search for City / Municipality',
     	minimumInputLength: 1,
     	templateResult: function(repo)
     	{
           if (repo.loading) {
             return repo.text;
           }

           var $container = $(
             "<div class='select2-result-repository clearfix font-size-16'>" +
               "<div class='select2-result-repository__meta font-size-16'>" +
                 "<div class='select2-result-repository__title font-weight-bold font-size-16'></div>" +
                 "<div class='select2-result-repository__province font-size-16 text-left'><span class='mr-5'>PROVINCE :</span> </div>" +
                 "<div class='select2-result-repository__region font-size-16 text-left'><span class='mr-5'>REGION :</span> </div>" +
               "</div>" +
             "</div>"
           );

           $container.find(".select2-result-repository__title").text(repo.cityname);
           $container.find(".select2-result-repository__region").append(repo.region);
           $container.find(".select2-result-repository__province").append(repo.province);
           return $container;
     	}
  	}).change(function(){

	 	if($(this).val())
	 	{
	        var getCurrentSelectedData = $("select[id='memberempowerment[a_member_permanent_address_city]']").select2('data');
	        CityIDData = getCurrentSelectedData[0];
	        if(!$(".select2-selection__rendered").hasClass('font-size-16'))
	        {
	        	$(".select2-selection__rendered").addClass('font-size-16');
	        }
	        
	 	}

	});

 	if(!$(".select2-selection__rendered").hasClass('font-size-16'))
    {
    	$(".select2-selection__rendered").addClass('font-size-16');
    }

});
</script>


