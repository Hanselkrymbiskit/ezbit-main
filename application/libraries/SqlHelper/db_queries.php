<?php 
require_once 'db_extends.php';
#[AllowDynamicProperties]
class db_queries extends db_extends 
{
	protected $query;	
	protected $table;

	public $ajaxEvent = False;
	public $AuditUserLog = "";
	public $QueryType = '';
	public $TargetTable = '';
	public $TargetFields = '';
	public $WhereClause = '';
	public $NewID = '';

	  public function setajaxEvent($option = False)
	  {
		$this->ajaxEvent = $option;
		// return $this;
	  }

	  public function ajaxEvent($option = False)
	  {
			$this->ajaxEvent = $option;
			return $this;
	  }

	  public function setAuditUserLog($val = '')
	  {
			$this->AuditUserLog = $val;
			// return $this;
	  }

	  public function AuditUserLog($val = '')
	  {
			$this->AuditUserLog = $val;
			return $this;
	  }

	  public function sql( $sql = "")
	  {
		$this->query = $sql;
		return $this;
	  }

	  public function select( $table , $column = '*')
	  {
		return $this->sqlquerystatement(__FUNCTION__,$table,$column);
	  }

	  public function insert($table)
	  {
		return $this->sqlquerystatement(__FUNCTION__,$table);
	  }
	  
	  public function update($table)
	  {
	  	return $this->sqlquerystatement(__FUNCTION__,$table);
	  }
	  
	  public function delete($table)
	  {
		return $this->sqlquerystatement(__FUNCTION__,$table);
	  }

	  public function sqlquerystatement($qtype,$table,$column = '*')
	  {
	  	
		$this->TargetTable = $table;

		if ( $table == '' )
		{
			return 'undefined database table passed !';
		} 

		$this->table = $table;
		$queryHolder = strtolower($qtype);

	  	switch( strtolower($qtype) )
	  	{
	  		case "select":
	  			$queryHolder .=  " " . $column . " from " . $table . " ";		
				$this->TargetFields = $column;
				$this->QueryType = 'S';
	  			break;
	  		case "insert":
	  			$queryHolder .=  " into ". $table ." ";
	  			$this->QueryType = 'I';
	  			break;
  			case "update":
  				$queryHolder .=  " " .$table . " set ";  
  				$this->QueryType = 'U';
	  			break;
  			case "delete":
  				$queryHolder .=  " from " . $table . " ";
  				$this->QueryType = 'D';
	  			break;
	  	}


	  	$this->query = $queryHolder;
		

		return $this;

	  }

	  // This will Return SQL Statement
	  public function rtrnSql()	{ 
	  	return $this->query;
	  }

      public function show( $die = false ) {

      	// Change this later for CI Template Display

      	// show_error( $this->query,404,'SQL HELPER');
		include  __DBUTILITY . 'show_query.php';
	
		if( $die == true ) die ();
	  
        return $this;
			  
	  }
		
}

?>
