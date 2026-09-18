<?php
 
/*
|---------------------------------------------------------------
| CASTING argc AND argv INTO LOCAL VARIABLES
|---------------------------------------------------------------
|
*/
$argc = $_SERVER['argc'];
$argv = $_SERVER['argv'];

set_time_limit(0);
if ($argc > 1)
{
	$SiteUrl = 'http://localhost:81/fhir-dss/';
	$ControllerMethod = $argv[1];

	$t = [];

	for($i=2;$i<=(count($argv) - 1); $i++)
	{
		$t[$argv[$i]] = '';
	}

	$Parameter = (count($t) > 0) ? http_build_query($t) : '';

	try
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $SiteUrl.@$ControllerMethod,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLINFO_HEADER_OUT => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_SSL_VERIFYPEER => false,
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_POSTFIELDS => @$Parameter,
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/x-www-form-urlencoded'
		  ),
		  CURLOPT_CUSTOMREQUEST => 'POST',
		));
		$response = curl_exec($curl);

		curl_close($curl);	
		echo $response;
	}
	catch(Exception $e)
	{
		echo "Failed Cron Call for ".$SiteUrl.@$ControllerMethod;
	}
} 



?>