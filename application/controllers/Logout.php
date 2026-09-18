<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
#[AllowDynamicProperties]
class Logout extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$result = $this->sqlhelper->local->delete('users_online')->where(" `userid` ='".$this->tank_auth->get_user_id()."'")->run();
		try{
			if( isset($_SESSION['userloginlogdatetime']) && isset($_SESSION['userloginlogid']) )
			{
				$logoutdatetime = date("Y-m-d H:i:s");
				$Date1 = date("Y-m-d H:i:s",strtotime($logoutdatetime));
				$Date2 = date("Y-m-d H:i:s",strtotime($_SESSION['userloginlogdatetime']));
				$duration = date_diff(new DateTime($Date1),new DateTime($Date2));
				$duration = $duration->format('%h Hours %i Minute %s Seconds');
				$uloglogin = [
		            'logoutdatetime'   => $logoutdatetime,
		            'duration'		   => @$duration,
		        ];

		        $insertLogLogin = $this->sqlhelper->local->update('users_online_log')->ex_update($uloglogin)->where("userid='".$this->tank_auth->get_user_id()."' and `id` = '".@$_SESSION['userloginlogid']."'")->run();
		        
		        unset($_SESSION['userloginlogid']);
		        unset($_SESSION['userloginlogdatetime']);
			}	
		}
		catch(Exception $ee)
		{
			log_message("error","Logout Error ".$ee->getMessage());
		}
		

		$this->tank_auth->logout();

		if($this->session->iniCounterNo)
		{
			$cno = $this->session->iniCounterNo;
			unset($this->session->iniCounterNo);
			$deleteSession = $this->sqlhelper->local->delete('system_sessions')->where(" `id` ='".session_id()."'")->run();
			redirect('');		
		}
		else
		{
			$deleteSession = $this->sqlhelper->local->delete('system_sessions')->where(" `id` ='".session_id()."'")->run();
			redirect('');
		}

	}
}

/* End of file logout.php */
/* Location: ./application/controllers/logout.php */