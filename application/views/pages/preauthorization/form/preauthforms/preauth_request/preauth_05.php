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
			<table class="table table-sm border">
				<tbody>
					<tr>
						<td class="text-dark border-0 text-left font-size-16 p-20">The patient is aware of the PhilHealth policy on co-payment and agreed to avail of the benefit package (please tick appropriate box): </td>
					</tr>
					<tr>
						<td class="border-top-0  pt-0 pl-20 pr-20 pb-20 ">
							<div class="form-group form-material mb-0" data-plugin="formMaterial">
								<div class="radio checkbox-custom custom-control checkbox-primary mb-0 mt-0">
				                    <input type="radio" id="request[copayment][nbb]" name="request[copayment]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="nbb" title="No Balance Billing (NBB)" autocomplete="eFormData_off" <?php echo @$disabled; ?>  <?php echo (@$preauthFormData['request']['copayment'] == 'nbb' ) ? 'checked' : ''; ?> >
				                    <label class="font-size-16 text-uppercase text-dark" for="request[copayment][nbb]"><i class="ml-20">No Balance Billing (NBB)</i></label>
				                </div>   
				                <div class="radio checkbox-custom custom-control checkbox-primary mb-0 mt-0">
				                    <input type="radio" id="request[copayment][wcp]" name="request[copayment]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="wcp" title="Co-payment" autocomplete="eFormData_off" <?php echo @$disabled; ?> <?php echo (@$preauthFormData['request']['copayment'] == 'wcp' ) ? 'checked' : ''; ?> >
				                    <label class="font-size-16 text-uppercase text-dark" for="request[copayment][wcp]"><i class="ml-20">Co-pay (indicate amount) Php :</i></label>
				                    <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16  mt--10 w-p75 float-right" id="request[copayment_amount]" name="request[copayment_amount]" title="I agree to pay as mush as PHP" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['copayment_amount']; ?>" dfvalue="<?php echo @$preauthFormData['request']['copayment_amount']; ?>" onKeyPress="return isDecimalKey(event,this)" <?php echo @$disabled; ?> required>  
				                </div>
			                </div>   
			                <input type="hidden" id="request[copayment]" name="request[copayment]" class="form-control" <?php echo @$disabled; ?> forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="<?php echo @$preauthFormData['request']['copayment']; ?>" title="PhilHealth policy on co-payment " autocomplete="eFormData_off" required>
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
								<label class="label font-weight-bold text-uppercase"  for="request[crtby_attendingsurgeon]">Certified correct by: </label>
				                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="request[crtby_attendingsurgeon]" name="request[crtby_attendingsurgeon]" title="Certified correct by Attending Surgeon" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_attendingsurgeon']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_attendingsurgeon']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> readonly required>       
			              	</div>
						</td>
						<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
							<div class="form-group form-material mb-0" data-plugin="formMaterial">
								<label class="label font-weight-bold text-uppercase"  for="request[crtby_attendingmedoncologist]">Certified correct by: </label>
				                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="request[crtby_attendingmedoncologist]" name="request[crtby_attendingmedoncologist]" title="Certified correct by Attending Medical Oncologist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_attendingmedoncologist']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_attendingmedoncologist']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> readonly required>       
			              	</div>  
						</td>
					</tr>
					<tr>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							<span>Attending Surgeon</span>
						</td>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							<span>Attending Medical Oncologist</span>
						</td>
					</tr>
					<tr>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
								<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="request[crtby_attendingsurgeon_accreno]">PhilHealth Accreditation No :</label>
								<div class="col-md-6">
				                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="request[crtby_attendingsurgeon_accreno]" name="request[crtby_attendingsurgeon_accreno]" title="Attending Surgeon - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_attendingsurgeon_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_attendingsurgeon_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
				                </div>   
			              	</div>  
						</td>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
								<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="request[crtby_attendingmedoncologist_accreno]">PhilHealth Accreditation No :</label>
								<div class="col-md-6">
				                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="request[crtby_attendingmedoncologist_accreno]" name="request[crtby_attendingmedoncologist_accreno]" title="Attending Medical Oncologist - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_attendingmedoncologist_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_attendingmedoncologist_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
				                </div>   
			              	</div>  
						</td>
					</tr>
					<tr>
						<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
							<div class="form-group form-material mb-0" data-plugin="formMaterial">
								<label class="label font-weight-bold text-uppercase"  for="request[crtby_patient]">Conforme by: </label>
				                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="request[crtby_patient]" name="request[crtby_patient]" title="Conforme Patient" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Patient Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_patient']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_patient']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> readonly required>
			              	</div>
						</td>
						<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
							<div class="form-group form-material mb-0" data-plugin="formMaterial">
								<label class="label font-weight-bold text-uppercase"  for="request[crtby_medicaldirectory]">Certified correct by: </label>
				                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="request[crtby_medicaldirectory]" name="request[crtby_medicaldirectory]" title="Certified correct by Executive Director/Chief of Hospital/ Medical Director/ Medical Center Chief" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_medicaldirectory']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_medicaldirectory']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> readonly required>       
			              	</div>
						</td>
					</tr>
					<tr>
						<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
							<span>Patient</span>
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
				                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="request[crtby_medicaldirectory_accreno]" name="request[crtby_medicaldirectory_accreno]" title="Executive Director/Chief of Hospital/ Medical Director/ Medical Center Chief - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['request']['crtby_medicaldirectory_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['request']['crtby_medicaldirectory_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> readonly  required>    
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


