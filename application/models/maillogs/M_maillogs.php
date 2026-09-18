<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class M_maillogs extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	}
  	
	function getMailLogStat()
	{

        $sql = "
        	select 
        	COUNT(CASE WHEN MailStatus = 2 THEN 1 END) as success,
        	COUNT(CASE WHEN MailStatus = 1 THEN 1 END) as failed,
        	COUNT(CASE WHEN MailStatus = 0 THEN 1 END) as pending,
        	count(*) as total 
        	from system_mailsender_log
        ";
        
        $getStatCount = $this->CI->sqlhelper->local->sql($sql)->row();
        $MailLogs = $getStatCount['Data'];
       
        return $MailLogs;
	}

	function getFailedMail()
	{
		$m = $this->CI->sqlhelper->local->select("system_mailsender_log","DataID,sToEmail,sSubject")->where("MailStatus <> 2")->ex_select("","DateTimeCreated asc","")->result();

		return $m['Data'];
	}

	function getMailData($DataID)
	{
		if($DataID == '')
		{
			return '';
		}
		else
		{
			$DataID = (int) $DataID;
			$getData = $this->CI->sqlhelper->local->select("system_mailsender_log")->where("DataID =".$DataID)->ex_select('','','1')->row();

			if($getData['Count'] > 0)
			{
				return $getData['Data'];
			}
			else
			{
				return '';
			}
		}
	}

}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */