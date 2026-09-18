<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class M_sms extends CI_Model
{
  public $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->sqlhelper->local->setajaxevent(1);
	}
  	
	function sendsms($mobileno = '', $smsmessage='')
	{
		
	}

	function twilioSend($mobileno = '', $smsmessage='')
	{
		// Your Account SID from www.twilio.com/console
		$sid = $this->CI->system_settings['TwilioSID']; 
		// Your Auth Token from www.twilio.com/console
		$token = $this->CI->system_settings['TwilioAuth'];
		// Your Auth Token from www.twilio.com/console
		$twilionumber = $this->CI->system_settings['TwilioNumber']; 

		$client = new Twilio\Rest\Client($sid, $token);
		$message = $client->messages->create(
		  $mobileno, // Text this number
		  [
		    'from' => $twilionumber, // From a valid Twilio number
		    'body' => $smsmessage
		  ]
		);

		print $message->sid;
	}

	function nexmoSend($mobileno = '', $smsmessage='')
	{
		$Parameter = [
			'api_key' => $this->CI->system_settings['NexmoAPIKey'],
			'api_secret' => $this->CI->system_settings['NexmoSecretKey'],
			'from' => $this->CI->system_settings['ApplicationAbbre'],
			'to' => $mobileno,
			'text' => $smsmessage,
		];

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $SiteUrl.@$ControllerMethod,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_POSTFIELDS => $Parameter,
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/x-www-form-urlencoded'
		  ),
		  CURLOPT_CUSTOMREQUEST => 'POST',
		));
		
		$response = curl_exec($curl);

		curl_close($curl);	
	}
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */