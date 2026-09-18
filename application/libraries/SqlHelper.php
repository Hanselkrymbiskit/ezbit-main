<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
#[AllowDynamicProperties]
/** extebd maximum execution time **/
// set_time_limit(1000);
/** memory **/
// ini_set("memory_limit","1G");
// ini_set("max_execution_time","1000");

class SqlHelper
{
	public $ajaxEvent = false;
	public $setAuditUserLog = false;
	public $ci;
	public $dbClass = NULL;
	public function __construct()
	{
		foreach ( $this->classList() as $reference => $class)
		{	  
			$this->$reference = $this->iniClass($class);
		}
	}
	public function __get($property)
	{
		$this->dbconnect = $this->dbClass;
		$this->dbconnect->con = $this->configDB->hostCredentials($property);
		$this->dbconnect->contype = $property;
		return $this->dbconnect;
	}

	public function setajaxEvent($option)
	{
		$this->ajaxEvent = $option;
	}

	public function getajaxEvent()
	{
		return $this->ajaxEvent;
	}

	public function classList()  {

		$class['dbClass']	 = 'db_queries';	
		$class['configDB']	 = 'db_hostConnection';
		return $class;

	}

	public function iniClass($class)
	{
	  	/** load the system classes **/
		$systemClass =  __DBCLASS . $class. '.php';		
		/** check if the file exists **/
		if(file_exists($systemClass))
		{   
		    /** include file **/
			include $systemClass;
	   		
			if( class_exists($class) )
			{ 
				$ini = new $class();	
				/** return the class **/
				return $ini;

			} 
			else
			{
				log_message("error","Error Loading Class");
			}
			
			
		} else log_message("error","Error Loading Class : File not exists");
		
		
	}


} ?>