<?php 
require_once 'db_tools.php';
#[AllowDynamicProperties]
class db_connection extends db_tools
{    
    private $link;
	protected $lastInserted;
	protected	$re = array(
					'status'=>'success',
					'querymode'=>'',
					'message'=>'',
					'errorcode'=>''
				);

	public function runQuery()
	{
		$CI =& get_instance();

		$auditlogs = array(
				'QueryType'			=>$this->QueryType,
				'QueryStatement'	=>'',
				'QueryStatus'		=>'',
				'QueryRemarks'		=>'',
				'QueryStartDateTime'=>'',
				'QueryEndDateTime'	=>'',
			);
		
		$errorCode = '';
		$this->re['errorcode'] = '';
		try {
		 	
		 	switch($this->con['host_server'])
		 	{
		 		case 'mysqli':
		 		case 'mysql':
		 			if( @$this->con['ssl_verify'] <> '')
					{
						$pdo_options = array(
							// PDO::MYSQL_ATTR_SSL_KEY           	=> $sslkey,
							// PDO::MYSQL_ATTR_SSL_CERT          	=> $sslcert,
							PDO::MYSQL_ATTR_SSL_CA            		=> @$this->con['ssl_capath'],
							// PDO::ATTR_PERSISTENT 			  	 	=> false,
							PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT  => @$this->con['ssl_verify']
						);

						if( @$this->con['ssl_capath'] <> '')
						{
							$pdo_options[PDO::MYSQL_ATTR_SSL_CA ] = $this->con['ssl_capath'];
						}

					}

					
		 		break;

		 		case 'odbc':
		 		case 'sqlsrv':
		 		case 'mssql':
		 			$pdo_options = [
	 					// PDO::ATTR_PERSISTENT => true,
            			// PDO::ATTR_ERRMODE 	 => PDO::ERRMODE_EXCEPTION,
            			// PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL, 
            			// PDO::SQLSRV_ATTR_CURSOR_SCROLL_TYPE => PDO::SQLSRV_CURSOR_BUFFERED
		 			];

		 			// $this->link = @new PDO($this->con['host_name'],$this->con['host_user'],$this->con['host_pass'],$pdo_options);
		 		break;
		 	}

		 	$this->link = @new PDO($this->con['host_name'],$this->con['host_user'],$this->con['host_pass'],$pdo_options);

			if( !$this->link )
			{
			 	$this->re['querymode'] = "";
			 	if($this->ajaxEvent)
				{
					$this->re['status'] = 'error';
					$this->re['message'] = 'unable to connect';
					$this->re['errorcode'] = $this->link->errorCode();

					return $this->re;
				}
				else
				{
					log_message("db",'Unable to Connect to Database Host');
					// show_error('Unable to Connect to MySQL Host', 500, 'MySQL Error');
				}
			} 

			try {
			 	
			 	$auditlogs['QueryStatement'] = base64_encode($this->query);
				
				/**** run query *****/
				$auditlogs['QueryStartDateTime'] = date('Y-m-d H:i:s');
			    $result = $this->link->query( $this->query  ) ;
			    $auditlogs['QueryEndDateTime'] = date('Y-m-d H:i:s');
				$GLOBALS['Exuted_QUERY'][] = $this->query;
		 		if ( !$result )
			    {
				 	
				 	$errorCode = $this->link->errorCode();
				 	$error =  $this->link->errorInfo();
			
				 	$this->re['errorcode'] = $errorCode;
				 	$this->re['message'] = $error[2];
				 	
				 	log_message("db",$this->query);
				 	log_message("db",$error[2]);

			 		$auditlogs['QueryErrorCode'] = @$errorCode;
				 	$auditlogs['QueryStatus'] = 0;
				 	$auditlogs['QueryRemarks'] = $error[2];

				  	throw new PDOException($error[2]);
			   	}

			   	$auditlogs['QueryStatus'] = 1;
			   
			}catch(PDOException $e) {
			 
				throw new PDOException( $e->getMessage() );
				$auditlogs['QueryRemarks'] = $e->getMessage();
				$this->runAudit($auditlogs);
		 	}
		 
			$this->re['querymode'] = "";
		 	if( strpos($this->query,"insert into") !== false )
		 	{
		 		$result = $this->link->lastInsertId();
		 		$this->re['querymode'] = 'insert';
		 	}

		 	// $this->link->query('KILL CONNECTION_ID()');
		 	$this->link = null;
		 	
		 	if($this->ajaxEvent)
		    {
				$this->re['status'] = 'success';
				$this->re['message'] = $result;	
				$auditlogs['QueryRemarks'] = '';
				return $this->re;
		    }
		    else
		    {
		    	return $result; 
		    }

		} 
		catch (PDOException $e)
		{
	          	
	          	$errorCode = $this->link->errorCode();
				$this->re['status'] = 'error';
				$this->re['message'] = $e->getMessage();
				$this->re['errorcode'] = $errorCode;
		
				$auditlogs['QueryStatus'] 	 = 0;
				$auditlogs['QueryErrorCode'] = @$errorCode;
				$auditlogs['QueryRemarks'] 	 = $e->getMessage();
				
				log_message("db",$this->query);
			 	log_message("db",$e->getMessage());

				$this->runAudit($auditlogs);

				return $this->re;
    	}
	}
	  
	function runAudit($param)
	{
		try {
		    $CI =& get_instance();
		   
		    	$auditRun = @new PDO($CI->defaultDB->dsn,$CI->defaultDB->username,$CI->defaultDB->password);
				try {
				 	
				 	$iQuery = '';
				 	$iFields = [];
				 	$iValues = [];

				 	foreach($param as $keys=>$values)
				 	{
			 			$iFields[] = $keys;
			 			$iValues[] = ( is_array($values) ) ? "'".$this->validate(implode(", ",$values))."'" : ( (is_null($values) || $values == '') ? 'NULL' : "'".$this->validate($values)."'");
				 	}	
					
					$iQuery = "insert into system_query_audit(".implode(",",$iFields).") values(".implode(",",$iValues).")";
					/**** run query *****/
					$auresult = $auditRun->query( $iQuery  ) ;
				   	$auditRun = null;
				}catch(PDOException $e) {
					// log_message("db",$e->getMessage());
			 	}	
			 	catch (Exception $e) {
			        log_message("error","Model : ".__METHOD__." : [ ".$e->getMessage()." ]");
			    }
        } 
		catch (PDOException $e)
		{
          	log_message("db",$e->getMessage());
		}	
		catch (Exception $e) {
	        log_message("error","Model : ".__METHOD__." : [ ".$e->getMessage()." ]");
	    }

	}
	
} 

?>