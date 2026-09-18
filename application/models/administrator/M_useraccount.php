<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use Ramsey\Uuid\Uuid;
class M_useraccount extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	}
  
  function getUserStatusStatistics()
  {
    $getStat = $this->CI->sqlhelper->local->select('v_001_user_infostatus','User_Status as Status, count(User_Status) as Total')->where(@$w)->ex_select('User_Status','','');
    $getStat = $getStat->result();
    return ($getStat['Count'] > 0 ) ? $getStat['Data'] : '';
  }

  function getusertypelist()
  {
    $qtable = "select 
                a.id as UserType, 
                SUM(CASE WHEN b.User_Status = 1 THEN 1 ELSE 0 END) as 'ActiveCnt',
                SUM(CASE WHEN b.User_Status = 2 THEN 1 ELSE 0 END) as 'InactiveCnt',
                SUM(CASE WHEN b.User_Status = 3 THEN 1 ELSE 0 END) as 'ExpiredCnt',
                SUM(CASE WHEN b.User_Status = 4 THEN 1 ELSE 0 END) as 'BannedCnt',
                count(b.User_TypeID) as TotalCount
                from users_type a 
                left join v_001_user_infostatus b on b.User_TypeID = a.id 
                group by a.id 
                order by a.id asc";

    $getStat = $this->CI->sqlhelper->local->sql($qtable);
    $getStat = $getStat->result();
    return ($getStat['Count'] > 0 ) ? $getStat['Data'] : '';
  }

  function getuserclassificationlist()
  {
    $qtable = "select 
                a.Code as UserClassification, 
                SUM(CASE WHEN b.User_Status = 1 THEN 1 ELSE 0 END) as 'ActiveCnt',
                SUM(CASE WHEN b.User_Status = 2 THEN 1 ELSE 0 END) as 'InactiveCnt',
                SUM(CASE WHEN b.User_Status = 3 THEN 1 ELSE 0 END) as 'ExpiredCnt',
                SUM(CASE WHEN b.User_Status = 4 THEN 1 ELSE 0 END) as 'BannedCnt',
                count(b.User_ClassificationID) as TotalCount
                from ref_userclassification a 
               
                left join v_001_user_infostatus b on b.User_ClassificationID = a.Code ".@$w." 
                 ".@$w2."
                group by a.Code 
                order by a.Code asc";
    $getStat = $this->CI->sqlhelper->local->sql($qtable);
    $getStat = $getStat->result();
    return ($getStat['Count'] > 0 ) ? $getStat['Data'] : '';
  }


  function validateFormData($SubmittedFormData)
  {
    $return = '';


    return $return;
  }

  function insertNewAccount($SubmittedFormData)
  {
    
    $return = '';

    $ws_uuid = Uuid::uuid4();
    $ws_uuid = $ws_uuid->toString();

    // Default Value 
    $expirydate = (date('Y')+1).date('-m-d');
    $created_by = $this->tank_auth->get_user_id();
    $created_datetime = date("Y-m-d H:i:s");
    $userid = '';
    $User_WS_Key = '';
    $User_E_Key = base64_encode( $this->CI->encryption->create_key(16) );
    $new_password_key = $this->myutilities->genpasswordhash($this->myutilities->generatePassword($this->system_settings['PasswordLength_Max']));
    $new_password_requested = $created_datetime;

    foreach($SubmittedFormData as $dKey => $dVal )
    {
      if( !isset($$dKey) )
      {
        $$dKey = $this->myutilities->dbValueFormatter($dKey, $dVal);
      }   
    }
 
    // Check for Duplicate User Name
    if( $this->myutilities->isUserNameExists($username) )
    {
      $return = 'Username already exists!';
      goto exitCode;
    }

    // Check for Duplicate Email
    if( $this->myutilities->chk_user_email($email) )
    {
      $return = 'Email Address already exists!';
      goto exitCode;
    }

    if( $this->myutilities->chk_registered_email($email) )
    {
      $return = 'Email Address already exists!';
      goto exitCode;
    }

    $CompleteName = ucwords(strtolower($firstname)).' '.ucwords(strtolower($lastname)).' '.( (@$suffixname =='NA') ? '' : ucfirst(strtolower($suffixname)) );
    $CompleteName = $this->m_api->encryptdecryptString('encrypt',$this->system_settings['PasswordHashing'],$CompleteName,'MCrypt','aes-128','ecb');

    $userData = array(

      'users' => array(
        'usertype'          => $usertype,
        'username'          => $username,
        'email'             => $email,
        'activated'         => 1,
        'activated_datetime'=> date('Y-m-d H:i:s'),
        'expirydate'        => $expirydate,
        'created_by'        => $created_by,
        'created_datetime'  => $created_datetime,
        'new_password_key'  => $new_password_key,
        'new_password_requested' => $new_password_requested,
        'password'          => $new_password_key.'-'.$created_datetime,

      ),

      'user_profiles' => array(
        'user_classification' =>$userclassification,
        'user_id'             =>$userid,
        'emailaddress'        =>$email,
        'CompleteName'        =>@$CompleteName,
        'lastname'            =>$lastname,
        'firstname'           =>$firstname,
        'middlename'          =>$middlename,
        'suffixname'          =>$suffixname,
        'dateofbirth'         =>$dateofbirth,
        'sex'                 =>$sex,
        'Landline_no'         =>$landlineno,
        'Mobile_no'           =>$mobileno,
        'security_question'   =>20,
        'security_question_custom'  => 'Please enter your registered email address!',
        'security_answer'     => @$email,
        'User_WS_Key'         =>@$User_WS_Key,
        'User_E_Key'          =>@$User_E_Key,
        'created_by'          =>$created_by,
        'created_datetime'    =>$created_datetime,
      ),
    );

    // Prepare Insert Statement Data
    try{
      $Insert_Users = $this->CI->sqlhelper->local->insert("users")->ex_insert($userData['users'])->run();
      // log_message("debug",$Insert_Users);
      if( $Insert_Users['ErrorCode'] == "" )
      {
        // Insert User Profile
        $userData['user_profiles']['user_id'] = $Insert_Users['Data'];
        $Insert_UserProfile = $this->CI->sqlhelper->local->insert("user_profiles")->ex_insert($userData['user_profiles'])->run();
        if( $Insert_UserProfile['ErrorCode'] == "" )
        {
          // Insert User Profile
          $profileid = $Insert_UserProfile['Data'];
          $return = $Insert_Users['Data'];
        }
        else
        {
          // Delete Inserted User ID
          $Delete_UserID = $this->CI->sqlhelper->local->delete('users')->where(" id = ".$userData['user_profiles']['user_id'])->run();
          $return = 'Failed to Create New User Account, Please try again later!';
        }
      }
      else
      {
        $return = 'Failed to Create New User Account, Please try again later!';
      }
    }catch(Exception $er)
    {
      $return = 'Failed to Create New User Account, Please try again later!';
    }

    exitCode:
    return $return;
  }

  function checkActivationLog($userid = '')
  {
      $chk = $this->CI->sqlhelper->local->select("user_activation_log")->where("user_id = ".$userid)->ex_select("","","1")->row();
      return $chk['Count'];
  }

  function insertUserStatusLog($userid,$createdby,$createddatetime)
  {
    $userstatus = $this->CI->sqlhelper->local->select("v_001_user_infostatus")->where("User_ID =".$userid)->ex_select("","","1")->row();
    if($userstatus['Count'] > 0)
    {
      $insert = array(
        'userid'          => $userid,
        'userstatus'      => $userstatus['Data']['User_Status'],
        'created_datetime'=> date("Y-m-d H:i:s",strtotime($createddatetime)),
        'created_by'      => $createdby,
      );

      $runInsert = $this->CI->sqlhelper->local->insert("users_status_log")->ex_insert($insert)->run();
    } 
  }

  function activateUserAccount($userid = '',$activateDateTime = '',$activationPage = false)
  {
    if($activationPage)
    {
      $activationlog_Array = array(
                              'activation_datetime' => date("Y-m-d H:i:s",strtotime($activateDateTime)),
                              'user_id'             => $userid
                            );
      if($this->checkActivationLog($userid) == 0)
      {
        $insertActivationLog = $this->CI->sqlhelper->local->insert("user_activation_log")->ex_insert($activationlog_Array)->run();
      }
    }
    
    // Activate User
    $updateUserTable = array(
      'activated'           => 1,
      'active'              => 1,
      'activated_datetime'  => date("Y-m-d H:i:s",strtotime($activateDateTime)),
    );

    if(!$activationPage)
    {
      $updateUserTable['modified_by'] = $this->CI->userid;
      $updateUserTable['modified_datetime'] = date("Y-m-d H:i:s",strtotime($activateDateTime));
    }

    $runUpdateUser = $this->CI->sqlhelper->local->update("users")->ex_update($updateUserTable)->where("id=".$userid)->run();
    if( $runUpdateUser['ErrorCode'] == "" )
    {
      if(!$activationPage)
      {
        // Add User Status Log
        $this->insertUserStatusLog($userid,$this->CI->userid,$activateDateTime);
      }
      return true;
    }

    return false;
  }

  
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */