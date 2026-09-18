<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_coordinator extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	  
	}
	
	function getProfile($coorID)
	{
		$return = ['Status'=>0,'Message'=>''];

		try{

			$sql = "
			 	select  
            a.*,
            CAST(AES_DECRYPT(FROM_BASE64(b.Lastname),FROM_BASE64('".$this->system_settings['PasswordHashing']."')) AS CHAR ) as pLastname,
            CAST(AES_DECRYPT(FROM_BASE64(b.Firstname),FROM_BASE64('".$this->system_settings['PasswordHashing']."')) AS CHAR ) as pFirstname,
            CAST(AES_DECRYPT(FROM_BASE64(b.Middlename),FROM_BASE64('".$this->system_settings['PasswordHashing']."')) AS CHAR ) as pMiddlename,
            CAST(AES_DECRYPT(FROM_BASE64(b.CompleteName),FROM_BASE64('".$this->system_settings['PasswordHashing']."')) AS CHAR ) as CompleteName,
            c.primarycondition_access
        from user_register a 
        left join user_profiles b on b.user_id = a.user_id and b.registration_id = a.DataID 
        left join trans_zben_coordinator_access c on c.user_id = a.user_id
        where a.DataID = '".$coorID."'
			";

			$gh = $this->sqlhelper->{$this->m_general->conProCode()}->sql($sql)->row();
			if($gh['Count'] > 0)
			{
				$return['Status'] = 1;
				$return['Message'] = $gh['Data'];
			}

		}catch (PDOException $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
    } catch (Exception $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    }

      return $return;
	}

	function getCoorZBenServices($tUser,$preauthType=false)
	{
		$return = ['Status'=>0,'Message'=>''];
		try{

			$rd = $this->sqlhelper->{$this->m_general->conProCode()}->select('trans_zben_coordinator_access')->where("user_id = '".$tUser."'")->row();
			if($rd['Count'] > 0)
			{
				$return['Status'] = 1;

				if($preauthType)
				{
					if($rd['Data']['primarycondition_access'] <> '')
					{
						$primaryC = explode(",",$rd['Data']['primarycondition_access']);
						$preauthlist = $this->sqlhelper->{$this->m_general->conProCode()}->select('ref_primarycondition_preauthlist')->where("primarycondition_code in ('".(implode("','",$primaryC))."')")->result();
						$preAuth = [];
						foreach($preauthlist['Data'] as $pr => $pc)
						{
							$preAuth[] = $pc['code'];
						}

						$return['Message'] = $preAuth;
					}
				}
				else
				{
					$return['Message'] = $rd['Data']['primarycondition_access'];
				}
			}

		}catch (PDOException $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
    } catch (Exception $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    }

    return $return;
	}

	function addZBenServices($ZBenCode,$tUser)
	{
		$return = ['Status'=>0,'Message'=>''];

		try{

			$gZben = $this->getCoorZBenServices($tUser);
			if($gZben['Status'] == 0)
			{
				 $iserv = [
				 		'healthfacility_code'			=> $this->userregistrationinfo['HealthFacilityCode'],
						'user_id'									=> $tUser,
						'primarycondition_access' => $ZBenCode,
						'created_by'						  => $this->userid,
						'created_datetime'				=> date("Y-m-d H:i:s"),
				 ];

				 $riserv = $this->sqlhelper->{$this->m_general->conProCode()}->insert('trans_zben_coordinator_access')->ex_insert($iserv)->run();
				 if($riserv['ErrorCode'] == '')
				 {
				 	$return = ['Status'=>1,'Message'=>''];
				 }
			}
			else
			{
				$ZBen = ($gZben['Message'] <> '') ? explode(",",$gZben['Message']) : [];
				if(!in_array($ZBenCode, $ZBen))
				{
					$ZBen[] = $ZBenCode;
				}
				sort($ZBen);
				$ZBen = implode(",",$ZBen);

				$userv = [
			 		'primarycondition_access' => $ZBen,
					'updated_by'						  => $this->userid,
					'updated_datetime'				=> date("Y-m-d H:i:s"),
			 	];

				$ruserv = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_zben_coordinator_access')->ex_update($userv)->where("healthfacility_code = '".$this->userregistrationinfo['HealthFacilityCode']."' and user_id = '".$tUser."' ")->run();
				if($ruserv['ErrorCode'] == '')
				{
					$return = ['Status'=>1,'Message'=>''];
				}

			}

		}catch (PDOException $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
    } catch (Exception $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    }

    return $return;

	}

	function removeZBenServices($ZBenCode,$tUser)
	{
		$return = ['Status'=>0,'Message'=>''];

		try{

			$gZben = $this->getCoorZBenServices($tUser);
			if($gZben['Status'] == 1)
			{
				$ZBen = explode(",",$gZben['Message']);
				sort($ZBen);
				$ZBen = array_diff($ZBen, array($ZBenCode));
				$ZBen = implode(",",$ZBen);

				$userv = [
			 		'primarycondition_access' => $ZBen,
					'updated_by'						  => $this->userid,
					'updated_datetime'				=> date("Y-m-d H:i:s"),
			 	];

				$ruserv = $this->sqlhelper->{$this->m_general->conProCode()}->update('trans_zben_coordinator_access')->ex_update($userv)->where("healthfacility_code = '".$this->userregistrationinfo['HealthFacilityCode']."' and user_id = '".$tUser."' ")->run();
				if($ruserv['ErrorCode'] == '')
				{
					$return = ['Status'=>1,'Message'=>''];
				}

			}

		}catch (PDOException $e) {
        log_message("error",__METHOD__." | ".$e->getMessage());
    } catch (Exception $e) {
      log_message("error",__METHOD__." | ".$e->getMessage());
    }

    return $return;

	}

}
