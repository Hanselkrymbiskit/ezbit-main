<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_dashboard extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	}
	
	function rptFilter()
	{
		$filter = "";

		switch( (int) $this->userclassification )
		{
			case 1: //Health Facility - Z Benefits Administrator
			case 6: //Health Facility - Z Benefits Coordinator
			case 7: //Health Facility - Executive Director/Chief of Hospital/Medical Director/Medical Center Chief

			break;

			case 2: //PRO - Benefits Administration Section (BAS)
			case 3: //PhilHealth Regional Office - Administrator
				// ZPAMS-FIX (2026-09): added (int) cast -- ProCode from session data was being
				// compared against a numeric DB column without coercion, which could silently
				// fail to match and scope classification 2/3 users out of their own report data.
				$filter = " b.healthfacility_pro = '".((int) $this->userregistrationinfo['ProCode'])."' ";
			break;
			
			default:

			break;
			
		}

		return $filter;
	} 

	function rpt_monthlyrequest($year = '',$preauth_status = 6)
	{
		$year = ($year == '') ? date('Y') : $year;
		$result = [];
	 	try
    {
			$w = $this->rptFilter();
			$cmnt = [];
			for($m = 1; $m <= (int) date('m'); $m++)
			{
				$cmnt[] = "COUNT(CASE WHEN YEAR(DATE(b.submitted_datetime)) = '".$year."' AND MONTH(DATE(b.submitted_datetime)) = '".$m."' THEN 1 END) as '".str_pad($m,2,0,STR_PAD_LEFT)."'";
			}

			$q = "
				select 
					a.`code` as 'Code',
					a.display as 'Display',
					".implode(",",$cmnt).",
					COUNT(CASE WHEN YEAR(DATE(b.submitted_datetime)) = '".$year."' THEN 1 END) as 'Total'
				from ref_primarycondition a 
				left join (select d.*,c.primarycondition_code from trans_preauth_form d join ref_primarycondition_preauthlist c on d.preauth_type = c.`code`) b on b.primarycondition_code = a.`code` and b.preauth_status = ".$preauth_status." ".( ($w) ? " and ".$w : "")." 
				group by a.`code`
			";
		
			$rq = $this->sqlhelper->{$this->m_general->conProCode()}->sql($q)->result();
			if( $rq['ErrorCode'] == '' )
			{
				$result = $rq['Data'];
				log_message("error",$result);
			}
    }
    catch (PDOException $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    } catch (Exception $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    }

    return $result;
	}

}
