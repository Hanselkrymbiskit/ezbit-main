<?php require_once 'db_access.php';
#[AllowDynamicProperties]
class db_extends extends db_access {
		
		  /* default argument */	
		  private $arg = array('group by','order by','limit','offset');	
		  private $argument = null;
		  public $_obj;
		  	
		 /** extend update query ***/
		 final public function ex_update()
		 {
			 $arg =  func_get_arg(0);
			
			 $column = '';
			 if( is_array($arg) || is_object ($arg)  ) 
			  {			 
				  foreach( $arg as $index => $val )
				  {
				  	if($val <> "")
				  	{
				  		$values = $this->validate($val);
				  	}
				  	else
				  	{
				  		$values = $val;
				  	}
					// $values = $this->validate($val);	
						
					if($values <> ''){		
						$column .= "," . $index . "='" . $values."' ";
					}else {
						$column .= "," . $index . "= NULL ";
						
					}
						
				  }
			 		$column = substr($column,1);
			  } else {
				  
				  $arg =  func_get_args();
				  if ( !isset ($arg[1]) && $arg )
				  {
					  $column = $arg[0];
				  } else {
				  	$column = $arg[0] . "='" . $arg[1] . "'";
				  }
			  }
			
				$this->query = $this->query  . $column ;	
				$this->TargetFields = $column;
			  return $this; 

		 }	
			
			
		  /* extend the select query */	
		  final public function ex_select()
		  {
			  $args = func_get_args();
		
			  foreach ( $args as $in => $value )
			  {	
				
				if( $value != '' )
				{
				  $this->query .= ' ' . $this->arg[$in] . ' ' . $value; 	
				}
			  }
			   return $this;
		  }
		  
	
		  
		  /* extend the insert query */
		  final public function ex_insert() 
		  {
			 $args =  func_get_arg(0);

			 $column = '';
			 $values = '';   		
			 
			 if( is_array($args) || is_object ($args) ) 
			 {	
			  	foreach( $args as $index => $val )
				{
					$column .= ",".$index;
					$val = (is_null($val)) ? '' : $val;
					if( strlen($val) == 0 )
					{
						$values .= ", NULL";
					} 
					else 
					{
						if( is_numeric($val))
						{
							$values .= ",'".$val."'";
						}
						else
						{
							$values .= ",'" .$this->validate($val). "'";
						}
					
					}
				}
				
			 /** for single value **/	
			 } else {
				
				$getargs =  func_get_args();
			
				if( isset($getargs[1]) ) 
				{	 
			 		$column = " "  . $getargs[0];
					
					if( !is_numeric( $getargs[1] ))
					{
						$values = " '" .$getargs[1] ."'";
					}
					else
					{
						$values = " '" .$this->validate( $getargs[1] ) ."'";
					}
				} else $this->error('missing argument passed!');
			 
			 }
			  /*** reset values ***/
			$this->query = $this->query . ' (' . substr($column,1) . ') values (' . substr($values,1) .')';	
			
			return $this;
		  }
		 

		 final public function where($clause = '' , $operation = ' and ')
		 {
			 if ( $clause == '' ) {
				 return $this; //$this->error('undefined where clause');
			 }
			 if( is_array($clause) || is_object($clause) )
			 {
				$columns = ''; 
				foreach( $clause as $key => $value )
				{
					$columns .= "  ". $operation ."  " . $key . " = '" . $value . "'";	
				}
				$clause = substr($columns,6);
			}
			 
			 $this->query = $this->query.' where ' .  $clause; 

			 $this->WhereClause = $clause;
			 return $this;
		 }

		public function error($mess)
		{
			log_message("debug",$mess);
		}
		
	
}?>