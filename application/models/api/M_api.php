<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class M_api extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	}
    
  function get_ekey($ws_key)
  {
    $getKey = $this->CI->sqlhelper->local->select('user_profiles')->where(" User_WS_Key = '".$ws_key."'")->row();
    return @$getKey['Data']['User_E_Key'];
  }

  function generatekeys($keylength=32)
  {
    $key = $this->CI->encryption->create_key($keylength);
    $codekeys = base64_encode($key);
    return $codekeys;
  }

  function encryptdecryptString($mode='encrypt',$keys='blank',$inputString='',$driver='openssl',$cipher='aes-128',$cmode='cbc',$hmac=FALSE,$hmac_digest='sha512')
  {
      // log_message("error",$keys);
      $decodedKeys = ($this->CI->myutilities->is_base64($keys)) ? base64_decode(trim($keys)) : $keys;
      // log_message("error",$decodedKeys);
      $driver_openssl_mode = ['CBC', 'CTR', 'CFB', 'CFB8', 'OFB', 'ECB', 'XTS'];
      $driver_mcrypt_mode = ['CBC', 'CTR', 'CFB', 'CFB8', 'OFB', 'OFB8', 'ECB'];
      $shalist = ['sha512','sha384','sha256','sha224'];
      $dmarray = 'driver_'.strtolower($driver).'_mode';
      $cipher_name = ['aes-128','aes-192','aes-256','rijndael-128','rijndael-192','rijndael-256','gost','twofish','cast-128','cast-256','loki97','saferplus','serpent','xtea','rc2','camellia-128','camellia-192','camellia-256','seed'];

      $enc_Set = array(
        'driver'      => strtolower($driver),
        'cipher'      => (!in_array($cipher, $cipher_name)) ? 'aes-128' : $cipher, //'aes-128'
        'mode'        => (!in_array(strtoupper($cmode), $$dmarray)) ? 'cbc' : strtolower($cmode), // cbc
        'key'         => $decodedKeys,
        'hmac'        => is_bool($hmac) ? $hmac : FALSE,
      );

      if($hmac)
      {
        $enc_Set['hmac_digest'] = (!in_array($hmac_digest,$shalist)) ? 'sha512' : $hmac_digest;
        $enc_Set['hmac_key'] = $this->CI->encryption->hkdf($decodedKeys,$hmac_digest, NULL, NULL,'encryption');
      }

      switch($mode)
      {
        case 'encrypt':
          $outputString = base64_encode($this->CI->encryption->encrypt($inputString,$enc_Set));
        break;
        case 'decrypt':
          $outputString = ($this->CI->myutilities->is_base64($inputString)) ? $this->CI->encryption->decrypt(base64_decode($inputString),$enc_Set) : '';
        break;
      }

      return @$outputString;
  }

  function getMethodList()
  {
    $methodList = $this->CI->sqlhelper->local->select("ws_method")->where("WS_Enable ='Y'")->ex_select('','','')->result();
    return ($methodList['Count'] > 0) ? $methodList['Data'] : '';
  }

  function methodDetails($methodname = '')
  {
    $getMethodData = $this->CI->sqlhelper->local->select("ws_method")->where("WS_Function_Name ='".trim(@$methodname)."'")->ex_select('','','1')->row();
    return ($getMethodData['Count'] > 0) ? $getMethodData['Data'] : '';
  }

  function getMethodParameter($FunctionID = '')
  {
    $mParameter = $this->CI->sqlhelper->local->select("ws_method_param_key")->where("FunctionID = ".((@$FunctionID == '') ? 0 : $FunctionID) )->ex_select('','','1')->row();

    $ParamKey = $mParameter['Data'];

    if( is_array($ParamKey) )
    {
      $getParameterList = $this->CI->sqlhelper->local->select("ws_method_param_key_child")->where("Type_ID = ".((@$ParamKey['Type_ID'] == '') ? 0 : @$ParamKey['Type_ID']) )->ex_select('','DataID asc','')->result();

      if($getParameterList['Count'] > 0 )
      {
        return $getParameterList['Data'];
      }
    }

    return '';
  }

}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */