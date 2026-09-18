<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$hpt = [
	[
		'title' 	=> 'Surgery (Specify Site)',
		'fieldid' 	=> 'hpt_1',
	],
	[
		'title' 	=> 'Hormonal Therapy',
		'fieldid' 	=> 'hpt_2',
	],
	[
		'title' 	=> 'Cytotoxic Chemotherapy',
		'fieldid' 	=> 'hpt_3',
	],
	[
		'title' 	=> 'Targeted Therapy (Specify Number of Cycles Provided)',
		'fieldid' 	=> 'hpt_4',
	]
];

$hptlist = [];
foreach($hpt as $hptK => $hptprop)
{
	
	$etitle = $hptprop['title'];
	$addoninputs = '';
	switch($hptprop['fieldid'])
	{
		case 'hpt_1':
			$addoninputs = '<input type="text" class="form-control text-uppercase col-md-6 mt-5 ml-20" id="checklist[hpt_1_specify]" name="checklist[hpt_1_specify]" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" title="Surgery(Specify Site)" placeholder="specify site" autocomplete="eFormData_off" value="'.@$preauthFormData['checklist']['hpt_1_specify'].'" dfvalue="'.@$preauthFormData['checklist']['hpt_1_specify'].'" onKeyPress="return isAllowedChar(event)" '.( (@$ViewMode <> true && @$ForCompliance == false) ? 'disabled' : ( (@$preauthFormData['checklist']['hpt_1_specify'] == '') ? '' : @$disabled ) ).'>';
		break;

		case 'hpt_4':
			$addoninputs = '<input type="text" class="form-control text-uppercase col-md-6 mt-5 ml-20" id="checklist[hpt_4_specify]" name="checklist[hpt_4_specify]" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" title="Number of Cycles Provided" placeholder="Number of Cycles Provided" autocomplete="eFormData_off" value="'.@$preauthFormData['checklist']['hpt_4_specify'].'" dfvalue="'.@$preauthFormData['checklist']['hpt_4_specify'].'" onKeyPress="return isNumberKey(event)" '.( (@$ViewMode <> true && @$ForCompliance == false) ? 'disabled' : ( (@$preauthFormData['checklist']['hpt_4_specify'] == '') ? '' : @$disabled ) ).'>';
		break;
	}
	
	// $hptprop['title'] = str_replace(['%specifysite%','%specifynumber%'], [@$specifysite,@$specifynumber], $hptprop['title']);

	$hptlist[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle" title="'.strip_tags($etitle).'">
				<div class="form-group row form-material  ml-10 mb-0">
					<div class="checkbox checkbox-custom custom-control checkbox-primary text-left">
	                    <input type="checkbox" id="checklist['.$hptprop['fieldid'].']" name="checklist['.$hptprop['fieldid'].']" class="form-control" title="'.$etitle.'" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" withdatevalue="true" targetdatefld="checklist['.$hptprop['fieldid'].'_date]" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$hptprop['fieldid']] == 'Y' ) ? 'checked' : '').'>
	                    <label class="font-size-16 font-weight-bold text-uppercase" for="checklist['.$hptprop['fieldid'].']">'.$hptprop['title'].'</label>
	                </div> 
	                '.@$addoninputs.'
                </div>
			</td>
			<td class="align-middle text-center font-size-16 font-weight-bold">
			 	<div class="form-group form-material mt-10 mb-0 text-center w-300" data-plugin="formMaterial">
                    <input type="text" class="form-control text-uppercase text-center" id="checklist['.$hptprop['fieldid'].'_date]" name="checklist['.$hptprop['fieldid'].'_date]" title="'.$etitle.' - Date Done"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="'.( (@$preauthFormData['checklist'][$hptprop['fieldid'].'_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist'][$hptprop['fieldid'].'_date'])) : '') .'" dfvalue="'.( (@$preauthFormData['checklist'][$hptprop['fieldid'].'_date']) ? date("m/d/Y",strtotime(@$preauthFormData['checklist'][$hptprop['fieldid'].'_date'])) : '') .'"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" '.( (@$ViewMode <> true && @$ForCompliance == false) ? 'disabled' : ( (@$preauthFormData['checklist'][$hptprop['fieldid'].'_date'] == '') ? '' : @$disabled ) ).'>       
                </div>
			</td>
		</tr>
	';
}

?>

<div class="mb-5 border-bottom"">
	<h6 class="text-right mt-0 pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>)</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle"><span class="ml-20">History of Previous Treatment</span></th>
				<th class="text-center font-weight-bold text-dark p-10 font-size-16 align-middle w-300">Date of Procedure or Last Session or Cycles<br>(mm/dd/yyyy)</th>
			</tr>
		</thead>
		<tbody>
			<?php echo (count($hptlist) > 0 ) ? implode('',$hptlist) : ''; ?>
		</tbody>
	</table>
</div>

<div class="mb-5 border-bottom">
	<table class="table table-sm table-bordered">
		<tbody>
			<tr class="bg-light">
				<td class="valign-middle text-center w-p50"><label class="label font-weight-bold font-size-16 text-uppercase">Menstrual Stage</label></td>
				<td class="valign-middle text-center w-p50"><label class="label font-weight-bold font-size-16 text-uppercase">HER2 Status</label></td>
			</tr>
			<tr>
				<td class="text-center">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
		                <div class="radio radio-custom radio-control radio-primary radio-inline mr-20">
		                    <input type="radio" id="checklist[menstrual_Pre]" name="checklist[menstrual]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="PRE" title="Menstrual Stage : Pre-menopausal" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['menstrual'] == 'PRE' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[menstrual_Pre]">Pre-menopausal</label>
		                </div>   
		                <div class="radio radio-custom custom-control radio-primary radio-inline mr-20">
		                    <input type="radio" id="checklist[menstrual_Pst]" name="checklist[menstrual]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="PST" title="Menstrual Stage : Post-menopausal" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['menstrual'] == 'PST' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[menstrual_Pst]">Post-menopausal</label>
		                </div>  
		                <input type="hidden" id="checklist[menstrual]" name="checklist[menstrual]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="<?php echo @$preauthFormData['checklist']['menstrual']; ?>" title="Menstrual Stage" autocomplete="eFormData_off" <?php echo @$disabled; ?> required>    
	              	</div>  
				</td>
				<td class="text-center">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
		                <div class="radio radio-custom radio-control radio-primary radio-inline mr-20">
		                    <input type="radio" id="checklist[HER2_1]" name="checklist[HER2]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="0–1+(HER2Negative)" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['HER2'] == '1' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[HER2_1]">0–1+ (HER2 Negative)</label>
		                </div>   
		                <div class="radio radio-custom custom-control radio-primary radio-inline mr-20">
		                    <input type="radio" id="checklist[HER2_2]" name="checklist[HER2]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="2+(Borderline)" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['HER2'] == '2' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[HER2_2]">2+ (Borderline)</label>
		                </div>  
		                <div class="radio radio-custom custom-control radio-primary radio-inline mr-20">
		                    <input type="radio" id="checklist[HER2_3]" name="checklist[HER2]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="3" title="3+(HER2Positive)" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['HER2'] == '3' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[HER2_3]">3+ (HER2 Positive)</label>
		                </div>  
		                <input type="hidden" id="checklist[HER2]" name="checklist[HER2]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="<?php echo @$preauthFormData['checklist']['HER2']; ?>" title="Menstrual Stage" autocomplete="eFormData_off" <?php echo @$disabled; ?> required>    
	              	</div>  
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div class="mb-5 border-bottom">
	<table class="table table-sm table-bordered">
		<tbody>
			<tr class="bg-light">
				<td colspan="2"  class="valign-middle text-center w-p50"><label class="label font-weight-bold font-size-16 text-uppercase">Laterality and Clinical Staging</label></td>
			</tr>
			<tr>
				<td class="valign-middle text-center w-p50">
					<div class="form-group form-material" data-plugin="formMaterial">
		                <div class="checkbox checkbox-custom checkbox-control checkbox-primary checkbox-inline">
		                    <input type="checkbox" id="checklist[laterality_l]" name="checklist[laterality_l]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="Laterality Left" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['laterality_l'] == 'Y' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[laterality_l]">LEFT</label>
		                </div>   
	                </div>
				</td>
				<td class="valign-middle text-center w-p50">
					<div class="form-group form-material" data-plugin="formMaterial">
		                <div class="checkbox checkbox-custom checkbox-control checkbox-primary checkbox-inline">
		                    <input type="checkbox" id="checklist[laterality_r]" name="checklist[laterality_r]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="Laterality Right" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['laterality_r'] == 'Y' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[laterality_r]">RIGHT</label>
		                </div>   
	                </div>
				</td>
				
			</tr>
			<tr>
				<td class="valign-middle text-center w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
		                <select class="form-control text-uppercase text-primary font-size-16 text-center" id="checklist[clinical_staging_l]" name="checklist[clinical_staging_l]" title="Clinical Staging - Left"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['clinical_staging_l']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['clinical_staging_l']; ?>" <?php echo @$disabled; ?>  required></select> 
	                </div>
				</td>
				<td class="valign-middle text-center w-p50">
					<div class="form-group form-material  mb-0" data-plugin="formMaterial">
		                <select class="form-control text-uppercase text-primary font-size-16 text-center" id="checklist[clinical_staging_r]" name="checklist[clinical_staging_r]" title="Clinical Staging - Left"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['clinical_staging_r']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['clinical_staging_r']; ?>" <?php echo @$disabled; ?>  required></select> 
	                </div>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div class="mb-5 border-bottom">
	<table class="table table-sm table-bordered">
		<tbody>
			<tr class="bg-light">
				<td colspan="2"  class="valign-middle text-center w-p50"><label class="label font-weight-bold font-size-16 text-uppercase">Applicable Treatment Protocol</label></td>
			</tr>
			<tr>
				<td class="valign-middle text-center w-p50">
					<label class="label font-weight-bold font-size-16 text-uppercase  mt-5">SURGERY</label>
				</td>
				<td class="valign-middle text-center w-p50">
					<div class="form-group form-material mt-5 mb-0" data-plugin="formMaterial">
		                <div class="radio radio-custom radio-control radio-primary radio-inline mr-20">
		                    <input type="radio" id="checklist[atp_surgery_A]" name="checklist[atp_surgery]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="A" title="Applicable Treatment Protocol - Surgery" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['atp_surgery'] == 'A' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[atp_surgery_A]">Adjuvant</label>
		                </div>   
		                <div class="radio radio-custom custom-control radio-primary radio-inline mr-20">
		                    <input type="radio" id="checklist[atp_surgery_N]" name="checklist[atp_surgery]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="N" title="2+(Borderline)" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['atp_surgery'] == 'N' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[atp_surgery_N]">Neoadjuvant</label>
		                </div>  
		                <input type="hidden" id="checklist[atp_surgery]" name="checklist[atp_surgery]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="<?php echo @$preauthFormData['checklist']['atp_surgery']; ?>" title="Applicable Treatment Protocol - Surgery" autocomplete="eFormData_off" <?php echo @$disabled; ?> required>    
	              	</div>  
				</td>
			</tr>
			<tr>
				<td class="valign-middle text-center w-p50">
					<div class="form-group form-material" data-plugin="formMaterial">
		                <div class="checkbox checkbox-custom checkbox-control checkbox-primary checkbox-inline">
		                    <input type="checkbox" id="checklist[atp_hormonaltherapy]" name="checklist[atp_hormonaltherapy]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="Hormonal Therapy" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['atp_hormonaltherapy'] == 'Y' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[atp_hormonaltherapy]">Hormonal Therapy</label>
		                </div>   
	                </div>
				</td>
				<td class="valign-middle text-center w-p50">
					
				</td>
			</tr>
			<tr>
				<td class="valign-middle text-center w-p50">
					<label class="label font-weight-bold font-size-16 text-uppercase  mt-5">Cytotoxic Chemotherapy</label>
				</td>
				<td class="valign-middle text-center w-p50">
					<div class="form-group form-material mt-5 mb-0" data-plugin="formMaterial">
		                <div class="radio radio-custom radio-control radio-primary radio-inline mr-20">
		                    <input type="radio" id="checklist[atp_cchemotherapy_A]" name="checklist[atp_cchemotherapy]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="A" title="Applicable Treatment Protocol - Surgery" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['atp_cchemotherapy'] == 'A' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[atp_cchemotherapy_A]">Adjuvant</label>
		                </div>   
		                <div class="radio radio-custom custom-control radio-primary radio-inline mr-20">
		                    <input type="radio" id="checklist[atp_cchemotherapy_N]" name="checklist[atp_cchemotherapy]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="N" title="2+(Borderline)" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['atp_cchemotherapy'] == 'N' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[atp_cchemotherapy_N]">Neoadjuvant</label>
		                </div>  
		                <input type="hidden" id="checklist[atp_cchemotherapy]" name="checklist[atp_cchemotherapy]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="<?php echo @$preauthFormData['checklist']['atp_cchemotherapy']; ?>" title="Applicable Treatment Protocol - Surgery" autocomplete="eFormData_off" <?php echo @$disabled; ?> required>    
	              	</div>  
	              	<div class="text-left">
	              		<label class="ml-10 label font-weight-bold font-size-16 text-uppercase  mt-5">Protocol :</label>
	              		<div class="form-group form-material mt-5 mb-0 ml-20" data-plugin="formMaterial">
			                <div class="radio radio-custom radio-control radio-primary mr-20">
			                    <input type="radio" id="checklist[atp_cchemotherapy_protocol_ACT]" name="checklist[atp_cchemotherapy_protocol]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="ACT" title="Doxorubicin (A) + Cyclophosphamide (C) + Docetaxel (T)" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['atp_cchemotherapy_protocol'] == 'ACT' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
			                    <label class="font-size-16 text-uppercase text-dark" for="checklist[atp_cchemotherapy_protocol_ACT]">Doxorubicin (A) + Cyclophosphamide (C) + Docetaxel (T)</label>
			                </div>   
			                <div class="radio radio-custom custom-control radio-primary mr-20">
			                    <input type="radio" id="checklist[atp_cchemotherapy_ACP]" name="checklist[atp_cchemotherapy_protocol]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="ACP" title="Doxorubicin (A) + Cyclophosphamide (C) + Paclitaxel (Pacli)" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['atp_cchemotherapy_protocol'] == 'ACP' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
			                    <label class="font-size-16 text-uppercase text-dark" for="checklist[atp_cchemotherapy_ACP]">Doxorubicin (A) + Cyclophosphamide (C) + Paclitaxel (Pacli)</label>
			                </div> 
			                <div class="radio radio-custom custom-control radio-primary mr-20">
			                    <input type="radio" id="checklist[atp_cchemotherapy_TCB]" name="checklist[atp_cchemotherapy_protocol]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="TCB" title="Docetaxel (T) + Carboplatin (Cb)" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['atp_cchemotherapy_protocol'] == 'TCB' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
			                    <label class="font-size-16 text-uppercase text-dark" for="checklist[atp_cchemotherapy_TCB]">Docetaxel (T) + Carboplatin (Cb)</label>
			                </div>  
			                <input type="hidden" id="checklist[atp_cchemotherapy_protocol]" name="checklist[atp_cchemotherapy_protocol]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="<?php echo @$preauthFormData['checklist']['atp_cchemotherapy_protocol']; ?>" title="Cytotoxic Chemotherapy - Protocol" autocomplete="eFormData_off" <?php echo @$disabled; ?> required>    
		              	</div>  
	              	</div>
				</td>
			</tr>
			<tr>
				<td class="valign-middle text-center w-p50">
					<div class="form-group form-material" data-plugin="formMaterial">
		                <div class="checkbox checkbox-custom checkbox-control checkbox-primary checkbox-inline">
		                    <input type="checkbox" id="checklist[atp_targettherapy]" name="checklist[atp_targettherapy]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="Laterality Left" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['atp_targettherapy'] == 'Y' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[atp_targettherapy]">Targeted Therapy</label>
		                </div>   
	                </div>
				</td>
				<td class="valign-middle text-center w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold font-size-16 text-uppercase"  for="checklist[atp_targettherapy_specify]">If with previous targeted therapy,  Specify number of cycles to be provided: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[atp_targettherapy_specify]" name="checklist[atp_targettherapy_specify]" title="Targeted Therapy -  Specify number of cycles to be provided" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['atp_targettherapy_specify']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['atp_targettherapy_specify']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>  
				</td>
			</tr>
			<tr>
				<td class="valign-middle text-center w-p50">
					<div class="form-group form-material" data-plugin="formMaterial">
		                <div class="checkbox checkbox-custom checkbox-control checkbox-primary checkbox-inline">
		                    <input type="checkbox" id="checklist[atp_surveillance]" name="checklist[atp_surveillance]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="Surveillance" autocomplete="eFormData_off" <?php echo (@$preauthFormData['checklist']['atp_surveillance'] == 'Y' ) ? 'checked' : ''; ?> <?php echo @$disabled; ?>>
		                    <label class="font-size-16 text-uppercase text-dark" for="checklist[atp_surveillance]">Surveillance</label>
		                </div>   
	                </div>
				</td>
				<td class="valign-middle text-center w-p50">
					
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
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingmedoncologist]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingmedoncologist]" name="checklist[crtby_attendingmedoncologist]" title="Certified correct by Attending Medical Oncologist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingmedoncologist']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingmedoncologist']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>  
				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingsurgeon]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingsurgeon]" name="checklist[crtby_attendingsurgeon]" title="Certified correct by Attending Surgeon" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingsurgeon']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingsurgeon']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Medical Oncologist</span>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Surgeon</span>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingmedoncologist_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingmedoncologist_accreno]" name="checklist[crtby_attendingmedoncologist_accreno]" title="Attending Physician - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingmedoncologist_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingmedoncologist_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
		                </div>   
	              	</div>  
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingsurgeon_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingsurgeon_accreno]" name="checklist[crtby_attendingsurgeon_accreno]" title="Surgeon - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingsurgeon_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingsurgeon_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
		                </div>   
	              	</div>  
				</td>
			</tr>

			<tr>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingradoncologist]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingradoncologist]" name="checklist[crtby_attendingradoncologist]" title="Certified correct by Attending Radiologic Oncologist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingradoncologist']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingradoncologist']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>  
				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_patient]">Conforme by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_patient]" name="checklist[crtby_patient]" title="Certified correct by Attending Physician" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Patient Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>
	              	</div>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Radiologic Oncologist</span>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Patient</span>
				</td>
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingradoncologist_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingradoncologist_accreno]" name="checklist[crtby_attendingradoncologist_accreno]" title="Attending Radiologic Oncologist - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingradoncologist_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingradoncologist_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
		                </div>   
	              	</div>  
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_patient_signeddate]">Date Signed :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_patient_signeddate]" name="checklist[crtby_patient_signeddate]" title="Date Signed" frmgrp="patientinformation"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo (@$preauthFormData['checklist']['crtby_patient_signeddate'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['checklist']['crtby_patient_signeddate'])) : ''; ?>" dfvalue="<?php echo (@$preauthFormData['checklist']['crtby_patient_signeddate'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['checklist']['crtby_patient_signeddate'])) : ''; ?>"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" <?php echo @$disabled; ?>  required>       
		                </div>   
	              	</div>  
				</td>
			</tr>
		</tbody>
	</table>
</div>


<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){
	getreferencevalue({objselect:$("select[id='checklist[clinical_staging_l]'][name='checklist[clinical_staging_l]']"),referenceid:'205',filter:'',order:''},'','SELECT LEFT Clinical Staging');
	getreferencevalue({objselect:$("select[id='checklist[clinical_staging_r]'][name='checklist[clinical_staging_r]']"),referenceid:'205',filter:'',order:''},'','SELECT RIGHT Clinical Staging');
<?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>
		
	$("input[type='radio'][name*='checklist['][forminputgroup='preauthFormData']").click(function(){
		if( $(this).prop('checked') )
		{
			$("input[type='hidden'][name='"+$(this).attr('name')+"'][forminputgroup='preauthFormData']").val($(this).val());
		}
	});

	$("input[id*='checklist[crtby_'][forminputgroup='preauthFormData']").keyup(function(){
		$("input[id='"+$(this).attr('id').replace('checklist','request')+"'][forminputgroup='preauthFormData']").val($(this).val());
	});
  

	$("[withdatevalue='true'][forminputgroup='preauthFormData']").click(function(){
		var tgtID = $(this).attr('targetdatefld');
		if( $(this).prop('checked') )
		{
			$("[id='"+tgtID+"'][forminputgroup='preauthFormData']").removeAttr('disabled').attr('required','required');
			$("[id='"+tgtID.replace('_date','_specify')+"'][forminputgroup='preauthFormData']").removeAttr('disabled').attr('required','required');
		}
		else
		{
			$("[id='"+tgtID+"'][forminputgroup='preauthFormData']").attr('disabled','disabled').removeAttr('required');
			$("[id='"+tgtID.replace('_date','_specify')+"'][forminputgroup='preauthFormData']").attr('disabled','disabled').removeAttr('required');
		}
	});

<?php } ?>

  
});
</script>


