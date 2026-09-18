<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();



?>
<div class="row">
	<div class="col-lg-2"></div>
	<div class="col-lg-8">
		<div class="mb-5">
			<table class="table table-sm border">
				<tbody>
					<tr>
						<td class="border-0 font-weight-bold font-size-16 p-20">
							<h5>Date of Request : <?php echo (@$preauthFormData['patientinfo']['submitted_datetime'] <> '') ? date("m/d/Y",strtotime($preauthFormData['patientinfo']['submitted_datetime'])) : date("m/d/Y"); ?></h5>
						</td>
					</tr>
					<tr>
						<td class=" border-0 font-size-16 text-center p-20 text-dark">
							<div>
								<table class="table table-sm border-0">
									<tbody>
										<tr>
											<td colspan="3" class="text-dark border-0 text-center font-size-16">This is to request approval for provision of services under the Z benefit package for</td>
										</tr>
										<tr>
											<td class="text-dark border-top-0 border-bottom font-weight-bold font-size-16 text-center w-p45 pl-20 pt-20 pr-20 pb-5">
												<span id="request_patientname" class="text-primary text-uppercase"></span>
											</td>
											<td class="text-dark border-0 font-weight-bold font-size-16 text-center pl-20 pt-20 pr-20 pb-5">
												in
											</td>
											<td class="text-dark border-top-0 border-bottom font-weight-bold font-size-16 text-center w-p45 pl-20 pt-20 pr-20 pb-5">
												<span id="request_facilityname" class="text-primary"><?php echo (@$preauthFormData['patientinfo']['healthfacility_code']) ? $CI->myutilities->getRef_Desc(104,$preauthFormData['patientinfo']['healthfacility_code']) : @$CI->userregistrationinfo['FacilityName']; ?></span>
											</td>
										</tr>
										<tr>
											<td class="text-dark border-top-0 font-size-16 text-center w-p45 pl-20 pr-20">
												<span>(Patient’s last, first, suffix, middle name)</span>
											</td>
											<td class="text-dark border-0 font-weight-bold font-size-16 text-center  pl-20 pr-20">
												
											</td>
											<td class="text-dark border-top-0 font-size-16 text-center  w-p45 pl-20 pr-20">
												<span>(Name of HCP)</span>
											</td>
										</tr>
										<tr>
											<td colspan="3" class="border-0 text-center font-size-16 p-20">under the terms and conditions as agreed for availment of the Z Benefit Package.</td>
										</tr>
									</tbody>
								</table>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="mb-5">
			<div class="row">
				<div class="col-md-6 col-lg-6">
					<table class="table table-sm border">
						<tbody>
							<tr>
								<td class="text-dark border-0 text-left font-size-16 p-20">The patient is aware of the PhilHealth policy on co-payment and agreed to avail of the benefit package (please tick appropriate box): </td>
							</tr>
							<tr>
								<td class="border-top-0  pt-0 pl-20 pr-20 pb-20 ">
									<div class="radio checkbox-custom custom-control checkbox-primary mb-0 mt-0">
					                    <input type="radio" id="request[copayment][wocp]" name="request[copayment]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="wocp" title="PhilHealth policy on co-payment : Without co-payment" autocomplete="eFormData_off" <?php echo @$disabled; ?>  <?php echo (@$preauthFormData['request']['copayment'] == 'wocp' ) ? 'checked' : ''; ?> >
					                    <label class="font-size-16 text-uppercase text-dark" for="request[copayment][wocp]"><i class="ml-20">Without co-payment</i></label>
					                </div>   
					                <div class="radio checkbox-custom custom-control checkbox-primary mb-0 mt-0">
					                    <input type="radio" id="request[copayment][wcp]" name="request[copayment]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="wcp" title="PhilHealth policy on co-payment : With co-payment" autocomplete="eFormData_off" <?php echo @$disabled; ?> <?php echo (@$preauthFormData['request']['copayment'] == 'wcp' ) ? 'checked' : ''; ?> >
					                    <label class="font-size-16 text-uppercase text-dark" for="request[copayment][wcp]"><i class="ml-20">With co-payment, for the purpose of:</i></label>
					                    <div class="form-group form-material mt-15 mb-0" data-plugin="formMaterial">
					                    	<textarea id="request[with_copayment_purpose]" name="request[with_copayment_purpose]" class="form-control font-size-16 show" placeholder="specify purpose here..." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="<?php echo @$preauthFormData['request']['with_copayment_purpose']; ?>"  onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> ><?php echo (@$preauthFormData['request']['with_copayment_purpose'] <> '') ? $preauthFormData['request']['with_copayment_purpose'] : ''; ?></textarea>
					                	</div>
					                </div>   
					                <input type="hidden" id="request[copayment]" name="request[copayment]" class="form-control" <?php echo @$disabled; ?> forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="<?php echo @$preauthFormData['request']['copayment']; ?>" title="PhilHealth policy on co-payment " autocomplete="eFormData_off" required>
								</td>
							</tr>
						
						</tbody>
					</table>
				</div>
				<div class="col-md-6 col-lg-6">
					<table class="table table-sm border">
						<tbody>
							<tr>
								<td class="text-dark border-0 text-left font-size-16 p-20">Type of implant being applied for: </td>
							</tr>
							<tr>
								<td class="border-top-0  pt-0 pl-20 pr-20 pb-20 ">
									<div class="radio checkbox-custom custom-control checkbox-primary mb-0 mt-0">
					                    <input type="radio" id="request[typeimplant_13]" name="request[typeimplant]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="13" title="Arm and forearm, plating " autocomplete="eFormData_off" <?php echo @$disabled; ?>  <?php echo (@$preauthFormData['request']['typeimplant'] == '13' ) ? 'checked' : ''; ?> >
					                    <label class="font-size-16 text-uppercase text-dark" for="request[typeimplant_13]"><i class="ml-20"></i>Arm and forearm, plating </label>
					                </div>   
					                <div class="radio checkbox-custom custom-control checkbox-primary mb-0 mt-0">
					                    <input type="radio" id="request[typeimplant_14]" name="request[typeimplant]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="14" title="Arm and forearm, pinning " autocomplete="eFormData_off" <?php echo @$disabled; ?> <?php echo (@$preauthFormData['request']['typeimplant'] == '14' ) ? 'checked' : ''; ?> >
					                    <label class="font-size-16 text-uppercase text-dark" for="request[typeimplant_14]"><i class="ml-20"></i>Arm and forearm, pinning </label>
					                </div> 
					                <div class="radio checkbox-custom custom-control checkbox-primary mb-0 mt-0">
					                    <input type="radio" id="request[typeimplant_15]" name="request[typeimplant]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="15" title="Wrist, plating " autocomplete="eFormData_off" <?php echo @$disabled; ?> <?php echo (@$preauthFormData['request']['typeimplant'] == '15' ) ? 'checked' : ''; ?> >
					                    <label class="font-size-16 text-uppercase text-dark" for="request[typeimplant_15]"><i class="ml-20"></i>Wrist, plating</label>
					                </div> 
					              	<div class="radio checkbox-custom custom-control checkbox-primary mb-0 mt-0">
					                    <input type="radio" id="request[typeimplant_16]" name="request[typeimplant]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="16" title="Wrist, pinning " autocomplete="eFormData_off" <?php echo @$disabled; ?> <?php echo (@$preauthFormData['request']['typeimplant'] == '16' ) ? 'checked' : ''; ?> >
					                    <label class="font-size-16 text-uppercase text-dark" for="request[typeimplant_16]"><i class="ml-20"></i>Wrist, pinning </label>
					                </div>
								</td>
							</tr>
						
						</tbody>
					</table>
				</div>
			</div>
			
		</div>

		<div class="mb-5">
			<table class="table table-sm table-bordered">
				<tbody>
					<tr>
						<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
							<div class="form-group form-material mb-0" data-plugin="formMaterial">
								<label class="label font-weight-bold text-uppercase"  for="request[crtby_patient]">Conforme by: </label>
				                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="request[crtby_patient]" name="request[crtby_patient]" title="Patient/Parent/Guardian" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_patient']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_patient']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
			              	</div>  
						</td>
						<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
							<div class="form-group form-material mb-0" data-plugin="formMaterial">
								<label class="label font-weight-bold text-uppercase"  for="request[crtby_attendingortopedicsurgeon]">Certified correct by: </label>
				                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="request[crtby_attendingortopedicsurgeon]" name="request[crtby_attendingortopedicsurgeon]" title="Certified correct by Attending Orthopedic Surgeon" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_attendingortopedicsurgeon']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_attendingortopedicsurgeon']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
			              	</div>
						</td>
					</tr>
					<tr>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							<span>Patient/Parent/Guardian</span>
						</td>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							<span>Attending Orthopedic Surgeon</span>
						</td>
					</tr>
					<tr>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							
						</td>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
								<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="request[crtby_attendingortopedicsurgeon_accreno]">PhilHealth Accreditation No :</label>
								<div class="col-md-6">
				                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="request[crtby_attendingortopedicsurgeon_accreno]" name="request[crtby_attendingortopedicsurgeon_accreno]" title="Orthopedic Surgeon - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_attendingortopedicsurgeon_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_attendingortopedicsurgeon_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
				                </div>   
			              	</div>  
						</td>
					</tr>

					<tr>
						<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
 
						</td>
						<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
							<div class="form-group form-material mb-0" data-plugin="formMaterial">
								<label class="label font-weight-bold text-uppercase"  for="request[crtby_medicaldirectory]">Certified correct by: </label>
				                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="request[crtby_medicaldirectory]" name="request[crtby_medicaldirectory]" title="Certified correct by Executive Director/Chief of Hospital/ Medical Director/ Medical Center Chief" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_medicaldirectory']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_medicaldirectory']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
			              	</div>
						</td>
					</tr>
					<tr>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							<span></span>
						</td>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							<span>Executive Director/Chief of Hospital/ Medical Director/ Medical Center Chief</span>
						</td>
					</tr>
					<tr>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
  
						</td>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
								<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="request[crtby_medicaldirectory_accreno]">PhilHealth Accreditation No :</label>
								<div class="col-md-6">
				                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="request[crtby_medicaldirectory_accreno]" name="request[crtby_medicaldirectory_accreno]" title="Executive Director/Chief of Hospital/ Medical Director/ Medical Center Chief - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_medicaldirectory_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_medicaldirectory_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?>  required>    
				                </div>   
			              	</div>
						</td>
					</tr>

					
				</tbody>
			</table>
		</div>

	</div>
	<div class="col-lg-2"></div>
</div>



<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

<?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>

	$("[type='radio'][name='request[copayment]'][forminputgroup='preauthFormData']").click(function(){
		
		if( $(this).prop('checked') && $(this).val() == 'wcp' )
		{
			$("[name='request[with_copayment_purpose]']").removeAttr('disabled').attr('required','required');
		}
		else
		{
			$("[name='request[with_copayment_purpose]']").attr('disabled','disabled').removeAttr('required');
		}

		$("[type='hidden'][name='request[copayment]']").val($(this).val());
	});

<?php } ?>
});
</script>


