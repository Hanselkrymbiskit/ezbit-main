<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class M_systemsettings extends CI_Model
{
    public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	}
  
	function currentSettings()
	{
		$return = array();

		$getSettings = $this->CI->sqlhelper->local->select("system_settings")->result();
		foreach( $getSettings['Data'] as $settingsRow => $settingsCol )
		{
			$return[$settingsCol['SettingName']] = trim($settingsCol['SettingValue']);
		}

		return $return;
	}

  	function saveSettings($newValue)
  	{
    	$result = false;
      	$currentSettings = $this->currentSettings();
		$updateArray = array_intersect_key($newValue,$currentSettings);
		try
		{
			foreach( $updateArray as $toUpdate => $UpdateValue)
			{
				$updateValArray = array(
					'SettingValue' => 	$UpdateValue
				);
				$updateSettings_Result = $this->CI->sqlhelper->local->update("system_settings")->ex_update($updateValArray)->where("SettingName = '".$toUpdate."'")->run();
			
				if($updateSettings_Result['ErrorCode'] == "")
				{
					
				}
			}

			$result = true;	
		}
		catch (PDOException $e) {
	      log_message("error","Model -> saveSettings : ".$e->getMessage());
	    } catch (Exception $e) {
	      log_message("error","Model -> saveSettings : ".$e->getMessage());
	    }

    	return $result;
  	}

}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */