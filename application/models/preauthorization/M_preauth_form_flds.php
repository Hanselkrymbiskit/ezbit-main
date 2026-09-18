<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use setasign\Fpdi\Tcpdf\Fpdi;
class M_preauth_form_flds extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	  
	}
	
	function preauth_01($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'age_1y_10y_364d'=>['label'=>'Age 1 to 10 years and 364 days','required'=>false,'valuetype'=>'text','referenceid'=>'22','encrypted'=>false],										
												'bone_marrow_aspirate'=>['label'=>'Bone marrow aspirate morphology ALL FAB L1 or L2*','required'=>false,'valuetype'=>'text','referenceid'=>'22','encrypted'=>false],	
												'no_cns_csfcell'=>['label'=>'No CNS involvement based on CSF cell count and differential count','required'=>false,'valuetype'=>'text','referenceid'=>'22','encrypted'=>false],
												'no_cns_clinicalfindings'=>['label'=>'No CNS involvement based on Clinical findings','required'=>false,'valuetype'=>'text','referenceid'=>'22','encrypted'=>false],
												'notesticular'=>['label'=>'no testicular involvement','required'=>false,'valuetype'=>'text','referenceid'=>'22','encrypted'=>false],
												'bc_wbc_count'=>['label'=>'BC WBC count <50,000/µL or <50,000 cells/µL or <50 x 10^3/µL or <50 x 10^9/L','required'=>false,'valuetype'=>'text','referenceid'=>'22','encrypted'=>false],
												'bc_wbc_count_date'=>['label'=>'BC WBC count <50,000/µL or <50,000 cells/µL or <50 x 10^3/µL or <50 x 10^9/L - Date','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'csf_cell_count'=>['label'=>'CSF cell count white blood cell (WBC) not more than 5 x 10^6/L','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'csf_cell_count_date'=>['label'=>'CSF cell count white blood cell (WBC) not more than 5 x 10^6/L - Date','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'crtby_attendingphysician'=>['label'=>'Certified correct by Attending Physician','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingphysician_accreno'=>['label'=>'Certified correct by Attending Physician - PhilHealth Accreditation No','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true]
											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'with_copayment_purpose'=>['label'=>'With co-payment, purpose','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingphysician'=>['label'=>'Certified correct by - Attending Physician','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingphysician_accreno'=>['label'=>'Attending Physician - PhilHealth Accreditation No','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory'=>['label'=>'Certified correct by - Executive Director/Chief of Hospital/ Medical Director/ Medical Center Chief','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno'=>['label'=>'Executive Director/Chief of Hospital/ Medical Director/ Medical Center Chief - PhilHealth Accreditation No','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_parent_guardian'=>['label'=>'Conforme by Parent/Guardian','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
										],
			];

			return $FormData;
		
	}

	function preauth_02($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'hpt_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'hpt_1_specify' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'hpt_1_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'hpt_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'hpt_2_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'hpt_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'hpt_3_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'hpt_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'hpt_4_specify' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'hpt_4_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'menstrual' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'HER2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'laterality_l' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'laterality_r' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'clinical_staging_l' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'clinical_staging_r' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'atp_surgery' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'atp_hormonaltherapy' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'atp_cchemotherapy' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'atp_cchemotherapy_protocol' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'atp_targettherapy' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'atp_targettherapy_specify' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'atp_surveillance' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingmedoncologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingmedoncologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingradoncologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingradoncologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient_signeddate' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'with_copayment_purpose'=>['label'=>'With co-payment, purpose','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingmedoncologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingmedoncologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingradoncologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingradoncologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_03($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_age' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2_a' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2_b' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2_c' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2_d' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3_a' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3_b' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_1_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_3_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_2_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'crtby_attendingcardiologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingcardiologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingcardiovascularsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingcardiovascularsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient_signeddate' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'with_copayment_purpose'=>['label'=>'With co-payment, purpose','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingcardiologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingcardiologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingcardiovascularsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingcardiovascularsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_04($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'crtby_attendingqynecologiconcologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingqynecologiconcologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'with_copayment_purpose'=>['label'=>'With co-payment, purpose','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'treatmentmodality'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'208','encrypted'=>false],
											'crtby_attendingqynecologiconcologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingqynecologiconcologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_05($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_6' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_7' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_8' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_8_specify' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingmedoncologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingmedoncologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingmedoncologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingmedoncologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_06($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_3_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_0_3_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_4_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_6' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_7' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_6' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_6_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_6_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_6_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_6_2_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_6_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_6_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_7' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_8' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_9' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_10' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_10_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_10_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_10_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_10_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_10_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_11' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_12' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_12_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_12_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_12_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_13' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_14' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_15' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_16_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_16_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingnephrologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingnephrologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingtransplantsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingtransplantsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient_signeddate' => ['label'=>'','required'=>true,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingnephrologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingnephrologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingtransplantsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingtransplantsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_08($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_0_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2_date'=>['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_1_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_4'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_5'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_6'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_7'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_8'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient_signeddate' => ['label'=>'','required'=>true,'valuetype'=>'date','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'with_copayment_purpose'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'typeimplant'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
										],
			];

			return $FormData;
		
	}

	function preauth_09($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_0_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2_date'=>['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_1_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_4'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient_signeddate' => ['label'=>'','required'=>true,'valuetype'=>'date','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'with_copayment_purpose'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
										],
			];

			return $FormData;
		
	}

	function preauth_10($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_0_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2_date'=>['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_1_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient_signeddate' => ['label'=>'','required'=>true,'valuetype'=>'date','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'with_copayment_purpose'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'typeimplant'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
										],
			];

			return $FormData;
		
	}

	function preauth_11($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_0_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2_date'=>['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_1_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient_signeddate' => ['label'=>'','required'=>true,'valuetype'=>'date','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'with_copayment_purpose'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'typeimplant'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
										],
			];

			return $FormData;
		
	}

	function preauth_12($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_0_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2_date'=>['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_1_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_4'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_5'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient_signeddate' => ['label'=>'','required'=>true,'valuetype'=>'date','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'with_copayment_purpose'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
										],
			];

			return $FormData;
		
	}

	function preauth_13($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_0_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_0_2_date'=>['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_1_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_4'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient_signeddate' => ['label'=>'','required'=>true,'valuetype'=>'date','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'with_copayment_purpose'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'typeimplant'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingortopedicsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
										],
			];

			return $FormData;
		
	}

	function preauth_14($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1_date'=>['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_2'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2_date'=>['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_3'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3_date'=>['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_4'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_4_date'=>['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'crtby_attendingphysician' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingphysician_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'with_copayment_purpose'=>['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingphysician' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingphysician_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
										],
			];

			return $FormData;
		
	}

	function preauth_15($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_4_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'209','encrypted'=>false],
												'crtby_attendingsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingmedoncologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingmedoncologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingradiationoncologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingradiationoncologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingsurgeon' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingsurgeon_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingmedoncologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingmedoncologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingradiationoncologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingradiationoncologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_16($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_6' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_1_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_3_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_2_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_3_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_3_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_3_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_4_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_3_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_5_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_3_6' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_6_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'crtby_attendingpediatriccardiologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingpediatriccardiologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingpediatriccardiologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingpediatriccardiology_type' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingpediatriccardiologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_17($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_4_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_5_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_6' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_6_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'crtby_attendingpediatriccardiologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingpediatriccardiologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingpediatriccardiologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingpediatriccardiology_type' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingpediatriccardiologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_18($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_4_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_5_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_6' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_6_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'q_2_7' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_7_date' => ['label'=>'','required'=>false,'valuetype'=>'date','referenceid'=>'','encrypted'=>false],
												'crtby_attendingpediatriccardiologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingpediatriccardiologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingpediatriccardiologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingpediatriccardiology_type' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingpediatriccardiologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_19($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_6' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_7' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingrms' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingrms_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingrms' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingrms_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_20($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingrms' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingrms_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingrms' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingrms_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_21($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_6' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_4_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_5_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_5_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_5_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingrms' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingrms_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingrms' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingrms_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}
	
	function preauth_22($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_3_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_4_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_4_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_4_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_5_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingrms' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingrms_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingrms' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingrms_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_23($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_5' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_6' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_7' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_8' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_9' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_10' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_11' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_12' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_13' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_2_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_3_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingrms' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingrms_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingmedspecialist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingmedspecialist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_24($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingmedspecialist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingmedspecialist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingmedspecialist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingmedspecialist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_25($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_4' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingotolaryngologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingotolaryngologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingotolaryngologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingotolaryngologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

	function preauth_26($submittedFld = '')
	{
			$FormData = [
				'checklist'=> [
												'q_1_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_2_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'q_1_3_1' => ['label'=>'','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>false],
												'crtby_attendingophthalmologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_attendingophthalmologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
												'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

											],
				'request'=> [
											'copayment'=>['label'=>'PhilHealth policy on co-payment','required'=>true,'valuetype'=>'text','referenceid'=>'201','encrypted'=>false],
											'copayment_amount'=>['label'=>'With co-payment, Amount','required'=>false,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingophthalmologist' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_attendingophthalmologist_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_medicaldirectory_accreno' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],
											'crtby_patient' => ['label'=>'','required'=>true,'valuetype'=>'text','referenceid'=>'','encrypted'=>true],

										],
			];

			return $FormData;
		
	}

}
