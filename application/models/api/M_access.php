<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class M_access extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	}

  function getaccess_info($userid)
  {
    $return = [];

    $sql = "
      select 

          a.*,
          a.id as DataCount,
          d.FacilityName,
          d.HealthFacilityCode as FacilityCode,
          d.FacilityContactNo,
          e.organizationid,
          f.User_E_Key,
          cast(aes_decrypt(from_base64(`f`.`CompleteName`),(select `system_settings`.`SettingValue` from `system_settings` where (`system_settings`.`DataID` = 1))) as char charset utf8mb4) as 'Account_Name',
          d.emailaddress,
          JSON_ARRAYAGG(b.controller) as controller

      from rest_api_keys a 
      left join rest_api_access b  on b.`key` = a.api_key 
      left join user_register d on d.user_id = a.user_id 
      left join tbl_resource_organization e on e.hfhudcode = d.HealthFacilityCode
      left join user_profiles f on f.user_id = a.user_id

      where a.user_id = '".$userid."'

      group by a.api_key
    ";
    
    $runSQL = $this->sqlhelper->local->sql($sql)->row();
    if($runSQL['Count'] > 0)
    {
      $return = $runSQL["Data"];
    }


    return $return;
  }

  function getarchivekey($userid)
  {
    $return = [];

    $sql = "
      select 
        a.api_key
      from rest_api_keys_archive a
      where a.user_id = '".$userid."'
      group by a.api_key
    ";
    
    $runSQL = $this->sqlhelper->local->sql($sql)->result();
    if($runSQL['Count'] > 0)
    { 
      foreach( $runSQL["Data"] as $r => $c)
      {
        $return[] = $c['api_key'];
      }
    }

    return $return;
  }

}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */