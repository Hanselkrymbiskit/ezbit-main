<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_preauth_reports extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	  	
	  	$this->load->model('preauthorization/m_preauth');
	}
	
	function preauth_status_stats($f = '')
	{
		$result = [];
		try
    {
			$total = 0;
			$q = "
				select 

					a.preauth_status as 'ckey',
					count(*) as 'cval'

				from trans_preauth_form a 
				".( ($f) ? " where ".$f : "")."
				group by a.preauth_status
			";

			$rq = $this->sqlhelper->{$this->m_general->conProCode()}->sql($q)->result();
			if($rq['Count'] > 0)
			{
				foreach($rq['Data'] as $r => $c)
				{
					$total += (int) $c['cval'];
					$result[( ($c['ckey'] == 2) ? 1 : $c['ckey'])] = $c['cval'];
				}
			}

			$result['total'] = $total;
		}
    catch (PDOException $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    } catch (Exception $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    }

		return $result;
	}

	function preauth_primary_stats($f = '')
	{
		$result = [];
		try
    {
			$total = 0;
			$q = "
				select 
					b.primarycondition_code as 'ckey',
					count(*) as 'cval'
				from trans_preauth_form a 
				join ref_primarycondition_preauthlist b on b.code = a.preauth_type 
				where a.preauth_status = 6
				".( ($f) ? " and ".$f : "")."
				group by b.primarycondition_code
			";

			$rq = $this->sqlhelper->{$this->m_general->conProCode()}->sql($q)->result();
			if($rq['Count'] > 0)
			{
				foreach($rq['Data'] as $r => $c)
				{
					$total += (int) $c['cval'];
					$result[( ($c['ckey'] == 2) ? 1 : $c['ckey'])] = $c['cval'];
				}
			}
		
			$result['total'] = $total;
		}
    catch (PDOException $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    } catch (Exception $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    }
		return $result;
	}
}
