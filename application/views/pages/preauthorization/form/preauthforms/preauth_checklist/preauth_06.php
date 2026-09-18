<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> '1.1. On chronic dialysis because of chronic kidney disease (CKD) stage 5',
		'fieldid' 	=> 'q_x_1',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> '1.2. For preemptive kidney transplantation',
		'fieldid' 	=> 'q_x_a',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => true,
		'noinput'	=> true,
		'marginleft'=> '',
	],
	[
		'title' 	=> '- Diabetic: 24-hour urine creatinine clearance or calculated glomerular filtration rate (GFR) (CKD-EPI formula) or nuclear GFR should be less than 20 mL/min /1.73m2.',
		'fieldid' 	=> 'q_x_2',
		'group'		=> '1',
		'NAinputs'	=> true,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> '- Non-diabetic: 24-hour urine creatinine clearance or calculated glomerular filtration rate (GFR) (CKD-EPI formula) or nuclear GFR should be less than 15 mL/min /1.73m2.',
		'fieldid' 	=> 'q_x_3',
		'group'		=> '1',
		'NAinputs'	=> true,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> '2. Single organ transplant',
		'fieldid' 	=> 'q_x_4',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> '3. Negative T and B Cell Crossmatch',
		'fieldid' 	=> 'q_x_5',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> '4. History of kidney transplantation',
		'fieldid' 	=> 'q_x_6',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> true,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> '4.1.a. Historical Panel Reactive Antibody (PRA) Class 1 & 2 negative',
		'fieldid' 	=> 'q_x_6_1',
		'group'		=> '1',
		'NAinputs'	=> true,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> '4.1.b. If Historical Panel Reactive Antibody (PRA) Class 1 and/or 2 is positive, must fulfill the following:',
		'fieldid' 	=> 'q_x_6_2',
		'group'		=> '1',
		'NAinputs'	=> true,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> 'i. Historical PRA less than or equal to 20%',
		'fieldid' 	=> 'q_x_6_2_1',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-40',
	],
	[
		'title' 	=> 'ii. No donor specific antibody (DSA) in the potential recipient',
		'fieldid' 	=> 'q_x_6_2_2',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-40',
	],
	[
		'title' 	=> '4.2.a. PRA Screening <20% in both Class I and Class II',
		'fieldid' 	=> 'q_x_6_3',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> '4.2.b. Absence of Donor Specific Antibodies (DSAs) by PRA Single Antigen Bead Assay',
		'fieldid' 	=> 'q_x_6_4',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> '5. Absence of all of the following: Hemi-paralysis; Mental incapacity; and Substance abuse for at least 6 months prior to the start of transplant work-up.',
		'fieldid' 	=> 'q_x_7',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> '6. Absence of current severe illness (congestive heart failure class 3-4), liver cirrhosis (findings of small liver with coarse granular/heterogeneous echo pattern with signs of portal hypertension), chronic lung disease requiring oxygen, etc.',
		'fieldid' 	=> 'q_x_8',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> '7.1. History of cancer',
		'fieldid' 	=> 'q_x_9',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> true,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> '7.2. If with indolent and low-grade cancers after curative surgical/ablative treatment:',
		'fieldid' 	=> 'q_x_10',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> true,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => true,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> 'Non-metastatic basal cell and squamous cell carcinoma of the skin, after complete removal',
		'fieldid' 	=> 'q_x_10_1',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> true,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> 'Small renal cell carcinoma (< 3 cm), after radical nephrectomy',
		'fieldid' 	=> 'q_x_10_2',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> true,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> 'Prostate cancer (Gleason score ≤ 6), after radical prostatectomy',
		'fieldid' 	=> 'q_x_10_3',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> true,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> 'Thyroid cancer (Follicular/Papillary <2 cm, of low-grade histology), after total/subtotal thyroidectomy',
		'fieldid' 	=> 'q_x_10_4',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> true,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> 'Breast ductal carcinoma in situ, after total mastectomy',
		'fieldid' 	=> 'q_x_10_5',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> true,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> '8.1. Hepatitis C Negative Recipient',
		'fieldid' 	=> 'q_x_11',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> true,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> '8.2. If Hepatitis C Positive Recipient',
		'fieldid' 	=> 'q_x_12',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> true,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => true,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> 'No liver cirrhosis',
		'fieldid' 	=> 'q_x_12_1',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> true,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> 'Completed the treatment for 12 weeks with Direct Acting Antivirals (DAA) before the kidney transplantation;',
		'fieldid' 	=> 'q_x_12_2',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> true,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> 'For recipients with deceased organ donors: On at least 4 weeks of DAA treatment before allocation of deceased donor kidney (and treatment should be continued for a total of 12 weeks post KT)',
		'fieldid' 	=> 'q_x_12_3',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> true,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> '9.1. HIV-Negative',
		'fieldid' 	=> 'q_x_13',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> true,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> '9.2. HIV-Positive: HIV-1 RNA viral load below detectable levels while on antiretroviral therapy (<50 copies/mL) and CD4+ count should be >200 cells/mm3',
		'fieldid' 	=> 'q_x_14',
		'group'		=> '1',
		'NAinputs'	=> true,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> '10. If the patient is HbsAg positive, all the following conditions must be met: <br><b class="ml-40"></b>a. absence of liver cirrhosis <br><b class="ml-40"></b>b. HBV- DNA <2,000 IU/ml',
		'fieldid' 	=> 'q_x_15',
		'group'		=> '1',
		'NAinputs'	=> true,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> '',
	],
	[
		'title' 	=> '11. If the recipient is CMV IgG negative, any of the following should qualify:',
		'fieldid' 	=> 'q_x_16_a',
		'group'		=> '1',
		'NAinputs'	=> false,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> false,
		'hassub'    => true,
		'noinput'	=> true,
		'marginleft'=> '',
	],
	[
		'title' 	=> 'a. Donor is CMV IgG negative; OR',
		'fieldid' 	=> 'q_x_16_1',
		'group'		=> '1',
		'NAinputs'	=> true,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
	],
	[
		'title' 	=> 'b. Recipient is CMV IgG negative and donor is CMV IgG positive',
		'fieldid' 	=> 'q_x_16_2',
		'group'		=> '1',
		'NAinputs'	=> true,
		'NOinputs'	=> false,
		'YNoCaption'=> false,
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
		'marginleft'=> 'ml-30',
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

	$itype = ( $qprop['NAinputs'] || $qprop['NOinputs'] ) ? 'radio' : 'checkbox';

	$$qgrp[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle '.$borderbottom.' '.$bordertop.'" title="'.strip_tags($qprop['title']).'">
				<span class=" ml-20 '.$qprop['marginleft'].' ">'.$qprop['title'].'</span>
				'.@$addons.'
			</td>
			<td class="align-middle text-center '.$borderbottom.' '.$bordertop.'">
				'.( ($qprop['noinput']) ? '' : '
				<div class="'.$itype.' '.$itype.'-custom custom-control '.$itype.'-primary '.( ($itype == 'radio') ? $itype.'-inline' : '').'">
                    <input type="'.$itype.'" id="checklist['.$qprop['fieldid'].'_Y]" name="checklist['.$qprop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="'.strip_tags($qprop['title']).' : YES" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$qprop['fieldid']] == 'Y' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$qprop['fieldid'].'_Y]">'.( ($qprop['YNoCaption']) ? '' : 'YES').'</label>
                </div> 
                '.( ($qprop['NOinputs']) ? '
            	<div class="'.$itype.' '.$itype.'-custom custom-control '.$itype.'-primary '.( ($itype == 'radio') ? $itype.'-inline' : '').'">
                    <input type="'.$itype.'" id="checklist['.$qprop['fieldid'].'_N]" name="checklist['.$qprop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="N" title="'.strip_tags($qprop['title']).' : No" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$qprop['fieldid']] == 'N' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$qprop['fieldid'].'_N]">No</label>
                </div>
            	' : '').'  
                '.( ($qprop['NAinputs']) ? '
            	<div class="'.$itype.' '.$itype.'-custom custom-control '.$itype.'-primary '.( ($itype == 'radio') ? $itype.'-inline' : '').'">
                    <input type="'.$itype.'" id="checklist['.$qprop['fieldid'].'_NA]" name="checklist['.$qprop['fieldid'].']" class="form-control" '.@$disabled.' forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="NA" title="'.strip_tags($qprop['title']).' : N/A" autocomplete="eFormData_off" '.((@$preauthFormData['checklist'][$qprop['fieldid']] == 'NA' ) ? 'checked' : '').'>
                    <label class="font-size-16 text-uppercase" for="checklist['.$qprop['fieldid'].'_NA]">N/A</label>
                </div>
            	' : '').'  
                ').'
			</td>
		</tr>
	';
}


?>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) on the appropriate answer</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="ml-20">History of Previous Kidney Transplantation</span></th>
				<th class="w-p50 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">
					<div class="checkbox checkbox-custom custom-control checkbox-primary">
	                    <input type="checkbox" id="checklist[q_0_1_NA]" name="checklist[q_0_1]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="NA" title="Not Applicable" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_1'] == 'NA' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_1_NA]">N/A</label>
	                </div>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr class="">
				<td class="w-p50 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">
					<div class="radio checkbox-custom custom-control radio-primary">
	                    <input type="radio" id="checklist[q_0_2_1]" name="checklist[q_0_2]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Not Applicable" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_2'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_2_1]">Living Organ Donor</label>
	                </div>
	                <div class="form-group form-material mb-0" data-plugin="formMaterial">
	                	<div class="radio radio-custom custom-control radio-primary radio-inline">
		                    <input type="radio" id="checklist[q_0_2_1_1]" name="checklist[q_0_2_1]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Related" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_2_1'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
		                    <label class="font-size-16 text-uppercase" for="checklist[q_0_2_1_1]">Related</label>
		                </div>
		                <div class="radio radio-custom custom-control radio-primary radio-inline">
		                    <input type="radio" id="checklist[q_0_2_1_2]" name="checklist[q_0_2_1]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Not Applicable" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_2_1'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
		                    <label class="font-size-16 text-uppercase" for="checklist[q_0_2_1_2]">Non-Related</label>
		                </div>
	                </div>

				</td>
				<td class="w-p50 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">
					<div class="radio checkbox-custom custom-control radio-primary">
	                    <input type="radio" id="checklist[q_0_2_2]" name="checklist[q_0_2]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Not Applicable" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_2'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_2_2]">Deceased Organ Donor</label>
	                </div>
				</td>
			</tr>

			<tr class="">
				<td class="w-p50 text-left font-weight-bold text-dark p-10 font-size-16 align-middle border-right-0">
					<div class="checkbox checkbox-custom custom-control checkbox-primary ml-30">
	                    <input type="checkbox" id="checklist[q_0_3_Y]" name="checklist[q_0_3]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="Y" title="Surgery" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_3'] == 'Y' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_3_Y]">Surgery</label>
	                </div>
	                <div class="form-group form-material mb-0 row w-500 ml-20" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[q_0_3_date]">(Date of Procedure (mm/dd/yyyy):</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[q_0_3_date]" name="checklist[q_0_3_date]" title="Date Signed" frmgrp="patientinformation"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="mm/dd/yyyy" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo (@$preauthFormData['checklist']['q_0_3_date'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['checklist']['q_0_3_date'])) : ''; ?>" dfvalue="<?php echo (@$preauthFormData['checklist']['q_0_3_date'] <> '') ? date("m/d/Y",strtotime(@$preauthFormData['checklist']['q_0_3_date'])) : ''; ?>"   data-plugin="formatter,datepicker"  data-pattern="[[99]]/[[99]]/[[9999]]" <?php echo @$disabled; ?>  required>       
		                </div>   
	              	</div> 
				</td>
				<td class="w-p50 text-center font-weight-bold text-dark p-10 font-size-16 align-middle border-left-0">
					<div class="radio radio-custom custom-control radio-primary radio-inline">
	                    <input type="radio" id="checklist[q_0_3_1_1]" name="checklist[q_0_2_1]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Surgery : Left" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_3_1'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_3_1_1]">Left</label>
	                </div>
	                <div class="radio radio-custom custom-control radio-primary radio-inline">
	                    <input type="radio" id="checklist[q_0_3_1_2]" name="checklist[q_0_2_1]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Surgery : Right" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_3_1'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_3_1_2]">Right</label>
	                </div>
				</td>
			</tr>

		</tbody>
	</table>
</div>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a (<i class="fa fa-fw fa-check"></i>) on the applicable procedure</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-center font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="">Type of Kidney Transplantation</span></th>
				<th class="text-center font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="">History of Previous Kidney Transplantation</span></th>
				<th class="text-center font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="">History of Previous Kidney Transplantation</span></th>
			</tr>
		</thead>
		<tbody>
			<tr class="">
				<td class="w-p30 text-left font-weight-bold text-dark p-10 font-size-16 align-top">
					<div class="radio checkbox-custom custom-control radio-primary mt-0 ml-30">
	                    <input type="radio" id="checklist[q_0_4_1]" name="checklist[q_0_4]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Living Organ Donor" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_4'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_4_1]">Living Organ Donor</label>
	                </div>
	                <div class="form-group form-material mb-0  mt-0 ml-50" data-plugin="formMaterial">
	                	<div class="radio radio-custom custom-control radio-primary radio-inline">
		                    <input type="radio" id="checklist[q_0_4_1_1]" name="checklist[q_0_4_1]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Related" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_4_1'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
		                    <label class="font-size-16 text-uppercase" for="checklist[q_0_4_1_1]">Related</label>
		                </div>
		                <div class="radio radio-custom custom-control radio-primary radio-inline">
		                    <input type="radio" id="checklist[q_0_4_1_2]" name="checklist[q_0_4_1]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Non-Related" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_4_1'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
		                    <label class="font-size-16 text-uppercase" for="checklist[q_0_4_1_2]">Non-Related</label>
		                </div>
	                </div>

				</td>
				<td rowspan="3" class="text-left font-weight-bold text-dark p-10 font-size-16 align-top">
					<div class="radio checkbox-custom custom-control radio-primary ml-30">
	                    <input type="radio" id="checklist[q_0_5_1]" name="checklist[q_0_5]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Not Applicable" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_5'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_5_1]">Basiliximab</label>
	                </div>
	                <div class="radio checkbox-custom custom-control radio-primary ml-30">
	                    <input type="radio" id="checklist[q_0_5_2]" name="checklist[q_0_5]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Rabbit anti-thymocyte globulin (rATG)" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_5'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_5_2]">Rabbit anti-thymocyte globulin (rATG)</label>
	                </div>
				</td>
				<td class="w-p40 text-left font-weight-bold text-dark p-10 font-size-16 align-top">
					<div class="radio checkbox-custom custom-control radio-primary ml-30">
	                    <input type="radio" id="checklist[q_0_6_1]" name="checklist[q_0_6]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Laparoscopic surgery for donor and open surgery for recipients" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_6'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_6_1]">Laparoscopic surgery for donor and open surgery for recipients</label>
	                </div>
	                <div class="radio checkbox-custom custom-control radio-primary ml-30">
	                    <input type="radio" id="checklist[q_0_6_2]" name="checklist[q_0_6]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Open surgery for both donor and recipients" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_6'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_6_2]">Open surgery for both donor and recipients</label>
	                </div>
				</td>
			</tr>
			
			<tr class="">
				<td rowspan="2" class="w-p30 text-left font-weight-bold text-dark p-10 font-size-16 align-top">
					<div class="radio checkbox-custom custom-control radio-primary ml-30 text-left">
	                    <input type="radio" id="checklist[q_0_4_2]" name="checklist[q_0_4]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Deceased Organ Donor" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_4'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
	                    <label class="font-size-16 text-uppercase" for="checklist[q_0_4_2]">Deceased Organ Donor</label>
	                </div>
				</td>

				<td class="bg-light text-center font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="">Type of Organ Preservation</span></td>
				
			</tr>

			<tr class="">
				<td class="w-p30 text-left font-weight-bold text-dark p-10 font-size-16 align-top">
					<div class="form-group form-material" data-plugin="formMaterial">
						<div class="radio checkbox-custom custom-control radio-primary ml-30">
		                    <input type="radio" id="checklist[q_0_7_1]" name="checklist[q_0_7]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Machine Perfusion" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_7'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
		                    <label class="font-size-16 text-uppercase" for="checklist[q_0_7_1]">Machine Perfusion</label>
		                </div>
		                <div class="radio checkbox-custom custom-control radio-primary ml-30">
		                    <input type="radio" id="checklist[q_0_7_2]" name="checklist[q_0_7]" class="form-control" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Cold storage" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_0_7'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled;?> >
		                    <label class="font-size-16 text-uppercase" for="checklist[q_0_7_2]">Cold storage</label>
		                </div>
	                </div>
				</td>
			</tr>

		</tbody>
	</table>
</div>

<div class="mb-5">
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) on the appropriate answer; otherwise, indicate a remark as applicable</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="ml-20">Selection Criteria</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">Remarks</th>
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
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingnephrologist]">Attested by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingnephrologist]" name="checklist[crtby_attendingnephrologist]" title="Certified correct by Attending Nephrologist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingnephrologist']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingnephrologist']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingtransplantsurgeon]">Attested by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingtransplantsurgeon]" name="checklist[crtby_attendingtransplantsurgeon]" title="Certified correct by Attending Transplant Surgeon" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingtransplantsurgeon']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingtransplantsurgeon']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
			

			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Nephrologist</span>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Transplant Surgeon</span>
				</td>
				

			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">  
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingnephrologist_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingnephrologist_accreno]" name="checklist[crtby_attendingnephrologist_accreno]" title="Nephrologist - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingnephrologist_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingnephrologist_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
		                </div>   
	              	</div>  
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingtransplantsurgeon_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingtransplantsurgeon_accreno]" name="checklist[crtby_attendingtransplantsurgeon_accreno]" title="Rehabilitation Medicine Specialist - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingtransplantsurgeon_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingtransplantsurgeon_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
		                </div>   
	              	</div>  
				</td>
			</tr>

			<tr>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					
				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_patient]">Conforme by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_patient]" name="checklist[crtby_patient]" title="Patient/Parent/Guardian" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>  
				</td>

			

			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span></span>
				</td>
				

				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Patient/Parent/Guardian</span>
				</td>
				
			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">  
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
<?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>
	
	$("input[id*='checklist[crtby_'][forminputgroup='preauthFormData']").keyup(function(){
		$("input[id='"+$(this).attr('id').replace('checklist','request')+"'][forminputgroup='preauthFormData']").val($(this).val());
	});


	$("input[id='checklist[crtby_attendingtransplantsurgeon]'][forminputgroup='preauthFormData'],input[id='checklist[crtby_attendingtransplantsurgeon_accreno]'][forminputgroup='preauthFormData']").keyup(function(){
		switch( $(this).attr('id') )
		{
			case 'checklist[crtby_attendingtransplantsurgeon]':
				$("input[id='request[crtby_attendingtransplantsurgeon]'][forminputgroup='preauthFormData']").val($(this).val());
			break;
			case 'checklist[crtby_attendingtransplantsurgeon_accreno]':
				$("input[id='request[crtby_attendingtransplantsurgeon_accreno]'][forminputgroup='preauthFormData']").val($(this).val());
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


