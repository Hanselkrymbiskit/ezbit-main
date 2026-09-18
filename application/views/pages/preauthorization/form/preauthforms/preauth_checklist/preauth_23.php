<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$qualitifationlist = [
	[
		'title' 	=> '1. The child’s chronological age is 0 to 17 years and 364 days old',
		'fieldid' 	=> 'q_x_1',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '2. The child does NOT have any condition that will compromise safety and functionality with the use of prosthesis, orthosis, wheelchair or seating device.',
		'fieldid' 	=> 'q_x_2',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '3. On physical examination, the child has no fresh or non-healing wound on the body part of interest',
		'fieldid' 	=> 'q_x_3',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '4. If acquired amputation, the limb is at least 3 months post-surgery',
		'fieldid' 	=> 'q_x_4',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '5. The child presents with any of the following:',
		'fieldid' 	=> 'q_x_a',
		'group'		=> '1',
		'sub'		=> false,
		'hassub'    => true,
		'noinput'	=> true,
	],
	[
		'title' 	=> '<b class="ml-20"> - </b>Disorders resulting to mobility impairment:',
		'fieldid' 	=> 'q_x_5',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => true,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-30"> - </b>Musculoskeletal conditions characterized with any of the following: limb loss (amputation), limb deficiency, limb deformity and spine deformity (Cobb’s angle of ≥ 20 degrees and Risser <4) classified into:',
		'fieldid' 	=> 'q_x_6',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => true,
		'noinput'	=> false,
	],

	[
		'title' 	=> '<b class="ml-40"> - </b>Gross Motor Function Classification System (GMFCS) 1 and 2 for prosthesis and orthosise',
		'fieldid' 	=> 'q_x_7',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-40"> - </b>GMFCS 3, 4, and 5 for seating device, wheelchair, prosthesis and orthosis (Note: For seating device, a child must be six months to six years and 364 days)',
		'fieldid' 	=> 'q_x_8',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-40"> - </b>Talipes equinovarus (clubfoot)',
		'fieldid' 	=> 'q_x_9',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-40"> - </b>Neuromuscular conditions characterized with any of the following: weakness or paralysis, imbalance, incoordination, sensory deficits classified into:',
		'fieldid' 	=> 'q_x_10',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => true,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-50"> - </b>GMFCS 1 and 2 for prosthesis and orthosis',
		'fieldid' 	=> 'q_x_11',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-50"> - </b>GMFCS 3, 4, and 5 for seating device, and wheelchair',
		'fieldid' 	=> 'q_x_12',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
	],
	[
		'title' 	=> '<b class="ml-20"> - </b>Cardiopulmonary, behavioral or cognitive conditions that impairs a child’s mobility',
		'fieldid' 	=> 'q_x_13',
		'group'		=> '1',
		'sub'		=> true,
		'hassub'    => false,
		'noinput'	=> false,
	],
];

$q1list = [];
foreach($qualitifationlist as $qkey => $qprop)
{

	$qgrp = 'q'.$qprop['group'].'list';

	if( !isset(${$qgrp}) )
	{
		$$qgrp = [];
	}

	$qprop['fieldid'] = str_replace('_x_','_'.$qprop['group'].'_',$qprop['fieldid']);

	$$qgrp[] = '
		<tr>
			<td class="text-dark p-10 font-size-16  align-middle" title="'.strip_tags($qprop['title']).'"><span class=" ml-20 ">'.$qprop['title'].'</span></td>
			<td class="align-middle text-center">
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
	<h6 class="text-right pr-20">Place a check mark (<i class="fa fa-fw fa-check"></i>) if YES</h6>
	<table class="table table-sm table-bordered">
		<thead>
			<tr class="bg-light">
				<th class="text-left font-weight-bold text-dark p-10 font-size-16 align-middle text-uppercase"><span class="ml-20">GENERAL QUALIFICATIONS</span></th>
				<th class="w-p20 text-center font-weight-bold text-dark p-10 font-size-16 align-middle">YES</th>
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
				<td colspan="3" class="text-dark p-10 font-size-16 font-weight-bold align-middle" title="Place a check on the box for the appropriate assistive device that will be given to the child"><span class=" ml-20 ">Place a (<i class="fa fa-fw fa-check"></i>) on the box for the appropriate assistive device that will be given to the child:</span></td>
			</tr>
			<tr>
				<td colspan="" class="text-dark p-10 font-size-16 font-weight-bold align-middle  text-center" title="Upper Extremity Prosthesis (GMFCS 1, and 2)">Upper Extremity Prosthesis (GMFCS 1, and 2)</td>

				<td class="align-middle text-left border-top-0 border-right-0">
					<div class="form-group form-material mb-0 ml-20" data-plugin="formMaterial">
		             	<div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_1_1_1]" name="checklist[q_2_1_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Shoulder disarticulation" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_1_1'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_1_1_1]">Shoulder disarticulation</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_1_1_2]" name="checklist[q_2_1_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Above elbow" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_1_1'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_1_1_2]">Above elbow</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_1_1_3]" name="checklist[q_2_1_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="3" title="Below elbow" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_1_1'] == '3' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_1_1_3]">Below elbow</label>
			            </div>
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_1_1_4]" name="checklist[q_2_1_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="4" title="Hand glove (2 or more fingers)" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_1_1'] == '4' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_1_1_4]">Hand glove (2 or more fingers)</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_1_1_5]" name="checklist[q_2_1_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="5" title="Finger (1 finger)" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_1_1'] == '5' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_1_1_5]">Finger (1 finger)</label>
			            </div>
			        </div>
				</td>
				<td class="text-left border-top-0 border-left-0">
					<h4>Laterality</h4>
					<div class="form-group form-material mb-0  ml-40" data-plugin="formMaterial">
		             	<div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_1_2_1]" name="checklist[q_2_1_2]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="R" title="Right Laterality" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_1_2'] == 'R' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_1_2_1]">Right</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_1_2_2]" name="checklist[q_2_1_2]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="L" title="Left Laterality" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_1_2'] == 'L' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_1_2_2]">Left</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_1_2_3]" name="checklist[q_2_1_2]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="B" title="Both Laterality" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_1_2'] == 'B' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_1_2_3]">Both</label>
			            </div>
			        </div>
				</td>
			</tr>
			<tr>
				<td colspan="" class="text-dark p-10 font-size-16 font-weight-bold align-middle  text-center" title="Lower Extremity Prosthesis (GMFCS 1, and 2)">Lower Extremity Prosthesis (GMFCS 1, and 2)</td>

				<td class="align-middle text-left border-top-0 border-right-0">
					<div class="form-group form-material mb-0 ml-20" data-plugin="formMaterial">
		             	<div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_2_1_1]" name="checklist[q_2_2_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="6" title="Hip disarticulation" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_2_1'] == '6' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_2_1_1]">Hip disarticulation</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_2_1_2]" name="checklist[q_2_2_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="7" title="Above knee or with knee disarticulation" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_2_1'] == '7' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_2_1_2]">Above knee or with knee disarticulation</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_2_1_3]" name="checklist[q_2_2_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="8" title="Below knee or ankle disarticulation" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_2_1'] == '8' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_2_1_3]">Below knee or ankle disarticulation</label>
			            </div>
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_2_1_4]" name="checklist[q_2_2_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="9" title="Partial foot" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_2_1'] == '9' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_2_1_4]">Partial foot</label>
			            </div>
			        </div>
				</td>
				<td class="text-left border-top-0 border-left-0">
					<h4>Laterality</h4>
					<div class="form-group form-material mb-0  ml-40" data-plugin="formMaterial">
		             	<div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_2_2_1]" name="checklist[q_2_2_2]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="R" title="Right Laterality" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_2_2'] == 'R' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_2_2_1]">Right</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_2_2_2]" name="checklist[q_2_2_2]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="L" title="Left Laterality" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_2_2'] == 'L' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_2_2_2]">Left</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_2_2_3]" name="checklist[q_2_2_2]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="B" title="Both Laterality" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_2_2'] == 'B' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_2_2_3]">Both</label>
			            </div>
			        </div>
				</td>
			</tr>
			<tr>
				<td colspan="" class="text-dark p-10 font-size-16 font-weight-bold align-middle  text-center" title="Orthosis (GMFCS 1, and 2)">Orthosis (GMFCS 1, and 2)</td>

				<td class="align-middle text-left border-top-0 border-right-0">
					<div class="form-group form-material mb-0 ml-20" data-plugin="formMaterial">
		             	<div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_3_1_1]" name="checklist[q_2_3_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Talipes Equinovarus (Club Foot)" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_3_1'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_3_1_1]">Talipes Equinovarus (Club Foot)</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_3_1_2]" name="checklist[q_2_3_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Ankle foot orthosis (AFO)" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_3_1'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_3_1_2]">Ankle foot orthosis (AFO)</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_3_1_3]" name="checklist[q_2_3_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="3" title="Knee ankle foot orthosis (KAFO)" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_3_1'] == '3' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_3_1_3]">Knee ankle foot orthosis (KAFO)</label>
			            </div>
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_3_1_4]" name="checklist[q_2_3_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="4" title="Hip knee ankle foot orthosis (HKAFO)" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_3_1'] == '4' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_3_1_2]">Hip knee ankle foot orthosis (HKAFO)</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_3_1_5]" name="checklist[q_2_3_1]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="5" title="Spinal bracing / orthosis" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_3_1'] == '5' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_3_1_3]">Spinal bracing / orthosis</label>
			            </div>
			        </div>
				</td>
				<td class="text-left border-top-0 border-left-0">
					<h4>Laterality</h4>
					<div class="form-group form-material mb-0  ml-40" data-plugin="formMaterial">
		             	<div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_3_2_1]" name="checklist[q_2_3_2]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="R" title="Right Laterality" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_3_2'] == 'R' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_3_2_1]">Right</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_3_2_2]" name="checklist[q_2_3_2]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="L" title="Left Laterality" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_3_2'] == 'L' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_3_2_2]">Left</label>
			            </div>  
			            <div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_3_2_3]" name="checklist[q_2_3_2]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="B" title="Both Laterality" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_3_2'] == 'B' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_3_2_3]">Both</label>
			            </div>
			        </div>
				</td>
			</tr>
			<tr>
				<td colspan="" class="text-dark p-10 font-size-16 font-weight-bold align-middle  text-center" title="Seating Device (GMFCS 3,4, and 5)">Seating Device (GMFCS 3,4, and 5)</td>

				<td class="align-middle text-left border-top-0 border-right-0">
					<label class="font-size-16 text-uppercase"><i>For ages 6 months to < 7 years old</i></label>
					<div class="form-group form-material mb-0 ml-20" data-plugin="formMaterial">
		             	<div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_4_1]" name="checklist[q_2_4]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="1" title="Seating device" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_4'] == '1' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_4_1]">Seating device</label>
			            </div>  
			        </div>
				</td>
				<td class="text-left border-top-0 border-left-0">
				
				</td>
			</tr>
			<tr>
				<td colspan="" class="text-dark p-10 font-size-16 font-weight-bold align-middle  text-center" title="Seating Device (GMFCS 3,4, and 5)">Wheelchair (GMFCS 3,4, and 5)</td>

				<td class="align-middle text-left border-top-0 border-right-0">
					<label class="font-size-16 text-uppercase"><i>For ages seven to 17 years and 364 days old</i></label>
					<div class="form-group form-material mb-0 ml-20" data-plugin="formMaterial">
		             	<div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_4_2]" name="checklist[q_2_4]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="2" title="Basic Wheelchair" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_4'] == '2' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_4_2]">Basic Wheelchair</label>
			            </div>  
			        </div>
			        <div class="form-group form-material mb-0 ml-20" data-plugin="formMaterial">
		             	<div class="radio radio-custom custom-control radio-primary">
			                <input type="radio" id="checklist[q_2_4_3]" name="checklist[q_2_4]" class="form-control"  forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" value="3" title="Intermediate Wheelchair" autocomplete="eFormData_off" <?php echo ((@$preauthFormData['checklist']['q_2_4'] == '3' ) ? 'checked' : ''); ?> <?php echo @$disabled; ?> >
			                <label class="font-size-16 text-uppercase" for="checklist[q_2_4_3]">Intermediate Wheelchair</label>
			            </div>  
			        </div>
				</td>
				<td class="text-left border-top-0 border-left-0">
				
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
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_patient]">Conforme by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_patient]" name="checklist[crtby_patient]" title="Patient/Parent/Guardian" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_patient']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>  
				</td>
				<td class="border-bottom-0 font-weight-bold font-size-16 pt-20 pl-20 pr-20 pb-0 w-p50">
					<div class="form-group form-material mb-0" data-plugin="formMaterial">
						<label class="label font-weight-bold text-uppercase"  for="checklist[crtby_attendingrms]">Certified correct by: </label>
		                <input type="text" class="form-control text-uppercase bt-white text-primary text-center" id="checklist[crtby_attendingrms]" name="checklist[crtby_attendingrms]" title="Certified correct by Attending Rehabilitation Medicine Specialist" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="Complete Name" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingrms']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingrms']; ?>" onKeyPress="return isAllowedChar(event)" <?php echo @$disabled; ?> required>       
	              	</div>
				</td>
			

			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Patient/Parent/Guardian</span>
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<span>Attending Rehabilitation Medicine Specialist</span>
				</td>
				

			</tr>
			<tr>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">  
				</td>
				<td class="border-top-0 font-size-16 p-0 w-p50 text-center">
					<div class="form-group form-material mb-0 row" data-plugin="formMaterial">
						<label class="col-md-6 label font-size-14 text-uppercase mt-5"  for="checklist[crtby_attendingrms_accreno]">PhilHealth Accreditation No :</label>
						<div class="col-md-6">
		                	<input type="text" class="form-control text-uppercase bt-white text-primary font-size-16" id="checklist[crtby_attendingrms_accreno]" name="checklist[crtby_attendingrms_accreno]" title="Rehabilitation Medicine Specialist - Accreditation No." forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" placeholder="0000-0000000-0" autocomplete="eFormData_off" formgroup="preauthFormData" value="<?php echo @$preauthFormData['checklist']['crtby_attendingrms_accreno']; ?>" dfvalue="<?php echo @$preauthFormData['checklist']['crtby_attendingrms_accreno']; ?>"   data-plugin="formatter"  data-pattern="[[9999]]-[[9999999]]-[[9]]" <?php echo @$disabled; ?> required>    
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


	$("input[id='checklist[crtby_attendingrms]'][forminputgroup='preauthFormData'],input[id='checklist[crtby_attendingrms_accreno]'][forminputgroup='preauthFormData']").keyup(function(){
		switch( $(this).attr('id') )
		{
			case 'checklist[crtby_attendingrms]':
				$("input[id='request[crtby_attendingrms]'][forminputgroup='preauthFormData']").val($(this).val());
			break;
			case 'checklist[crtby_attendingrms_accreno]':
				$("input[id='request[crtby_attendingrms_accreno]'][forminputgroup='preauthFormData']").val($(this).val());
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


