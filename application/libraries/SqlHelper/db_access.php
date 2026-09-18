<?php require_once 'db_connection.php';
#[AllowDynamicProperties]
class db_access extends db_connection
{

	/*** function multiple rows return ***/
	final public function result()
	{
		$arrayObj = array();

		$result = $this->runQuery();

		$return_Result = array(
				'ErrorCode'=>'',
				'Description'=>'',
				'Count'=>0,
				'Data'=>''
			);
		
		if($this->ajaxEvent)
    	{
			$return_Result['ErrorCode'] = $result['errorcode'];
			if($return_Result['ErrorCode'] <> ''  && $return_Result['ErrorCode'] <> '42S22')
			{
				$return_Result['Description'] = $result['message'];
				return $return_Result;
			}

			$result = $result['message'];	

    	}
    	
    	
    	$cnt = 0;
		while( $objRow = $result->fetchObject() )
		{
			$arrayObj[] = (array) $objRow;	
			$cnt++;
		}

		$return_Result['Count'] = $cnt; //$result->rowCount();
		
		$return_Result['Data'] = $arrayObj;	

		return $return_Result;

	}
	 
	/*** return single row result ***/
	final public function row()
	{
		$result = $this->runQuery();	
		$return_Result = array(
				'ErrorCode'=>'',
				'Description'=>'',
				'Count'=>0,
				'Data'=>''
			);

		if($this->ajaxEvent)
    	{
			$return_Result['ErrorCode'] = $result['errorcode'];
			if($return_Result['ErrorCode'] <> '' && $return_Result['ErrorCode'] <> '42S22')
			{
				$return_Result['Description'] = $result['message'];
				return $return_Result;
			}

			$result = $result['message'];
		
    	}

		$return_Result['Count'] = $result->rowCount();
		$return_Result['Data'] = $result->fetch(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);	
		
		return $return_Result;	
	}
	
	/*** return total number of result ***/
	final public function count()
	{
		$result = $this->runQuery();
		$return_Result = array(
				'ErrorCode'=>'',
				'Description'=>''
			);

		if($this->ajaxEvent)
    	{
			$return_Result['ErrorCode'] = $result['errorcode'];
			if($return_Result['ErrorCode'] <> '')
			{
				$return_Result['Description'] = $result['message'];
				return $return_Result;
			}		

			$result = $result['message'];
    	}

		
		return $result->rowCount();
	}
	
	

	
	/*** run a direct sql query ***/
	final public function run($confirm = true)
	{
		/*** direct  sql query ***/		
		$result = $this->runQuery();
		$return_Result = array(
				'ErrorCode'=>@$result['errorcode'],
				'Description'=>'',
				'Data'=>''
			);

		if($this->ajaxEvent)
    	{
			if($return_Result['ErrorCode'] <> '' )
			{
				$return_Result['Description'] = $result['message'];
				return $return_Result;
			}

			if($result['querymode'] == 'insert')
	    	{
	    		$return_Result['Data'] = $result['message'];
	    	}

	    	return $return_Result;
    	}

    	return $result;
	}
	
	final public function data( $columntotal = array() , $groupby = '')
	{
		$totalset = false;
	    $listed = array();
		$totalCol = 0;	
		$result = $this->result();
 		
 		if($result['ErrorCode'] == '' && $result['Count'] > 0)
		{
			$Display_DataTable = '<div class="shadow_box" style="padding:0px;margin-bottom:20px">
			<div style="overflow-x:auto;padding:0px">
				<table class="table table-bordered" style="margin:0px;font-size:12px">';
			$Display_DataTable .= '<thead>';
			$Display_DataTable .= '<tr class="success"><td colspan=100>Recordset</td></tr>';
			$Display_DataTable .= '<tr class="">';
			/*** table header and column ***/
			while(list($column) = each($result['Data'][0]))
			{
				if( $groupby != $column )
				{
					$Display_DataTable .= '<th>'.$column.'</th>';
					$totalCol++;
				}
			}
			
			$Display_DataTable .= '</tr>';
			$Display_DataTable .= '</thead>';
			$Display_DataTable .= '<tbody>';
			$bg = 0;
			
			foreach( $result['Data'] as  $key => $data )
			{
				$bg++;
				$Display_DataTable .= '<tr>';
				foreach( $data as $kk => $val ) 
				{  
				 	$Display_DataTable .= '<td>'.$val.'</td>';
				}
				$Display_DataTable .= '</tr>';
				 
				if( is_array($columntotal) && count($columntotal) > 0   )
				{ 
				   $totalset = true;
				   foreach ($columntotal as $varcol) 
				   {
				 	 @$$varcol += $data->$varcol; 
				   }
				  }
				
			}

			$Display_DataTable .= '<tr><td colspan=100>Total Data : '. $result['Count'] .'</td></tr>';
			$Display_DataTable .= '</tbody>';
		    $Display_DataTable .= '</table>


		    </div>
		    <div style="padding:5px"><h5 style="border-bottom:1px dashed #999">SQL Statement</h5>'.strtoupper($this->query).'</div></div>
				';
			echo $Display_DataTable;
			
		}
		else
		{
			$heading = 'Mysql Error';
			$qMessageBox = '
	    					<div class="shadow_box">
	    						<table class="table" style="margin:0px;font-size:12px">
	    							<tr>
										<td style="border:0px;width:100px">Error Code<span class="pull-right">:</span></td>
										<td style="border:0px">'.$result['ErrorCode'].'</td>
	    							</tr>
	    							<tr>
										<td style="border:0px">Description<span class="pull-right">:</span></td>
										<td style="border:0px">'.$result['Description'].'</td>
	    							</tr>
	    							<tr style="border-top:1px dashed #999">
										<td colspan="2" style="border:0px;">SQL Statement</td>
									</tr>
	    							<tr>
										<td  colspan="2" style="border:0px;padding-top:0px">
											<div style="border:1px solid #999;padding:5px;overflow-y:auto;max-height:100px">
												'.$this->query.'
											</div>
										</td>
	    							</tr>
	    						</table>
	    						
							</div>
	    					';

			echo $qMessageBox;
			// return $result;
		}

		return $this;
		
	}
	
	
	
}


?>