<?php
#[AllowDynamicProperties]
class db_hostConnection  {
  
  function hostCredentials($host)
  {
	
		$CI =& get_instance();

		$ci_con_var = ($host == 'local') ? 'defaultDB' : $host;
		$Credentials = array (
			'host_server'	=>	@$CI->{$ci_con_var}->dbdriver,
			'host_name'		=>	@$CI->{$ci_con_var}->dsn,
			'host_user'		=>	@$CI->{$ci_con_var}->username,
			'host_pass'		=>	@$CI->{$ci_con_var}->password,
			'host_db'			=>	@$CI->{$ci_con_var}->database,
			'host_port'		=>	@$CI->{$ci_con_var}->port,
			'aes_key'			=>	@$CI->{$ci_con_var}->aes_key,
			'char_set'		=>	@$CI->{$ci_con_var}->char_set,
			'ssl_capath'	=>	@$CI->{$ci_con_var}->encrypt['ssl_capath'],
			'ssl_verify'	=>	@$CI->{$ci_con_var}->encrypt['ssl_verify'],
		);

		if( isset($CI->{$ci_con_var}) && @$CI->{$ci_con_var}->database == '' )
		{
			log_message("db",$CI->{$ci_con_var});
			log_message("db",'No available connection for '. $ci_con_var);
		}
		
  	return @$Credentials;
  }
		
} 
?>