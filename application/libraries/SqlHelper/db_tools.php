<?php 
// include 'chartClass.php';
//class db_tools extends jQplot
#[AllowDynamicProperties]
class db_tools
{
	public function validate($string = "")
	{
		if($string <> "")
		{
		 // 	if(get_magic_quotes_gpc())  
			// {
			//   $string = stripslashes($string);
			// }

			if(!is_array($string))
			{
				$string = addslashes($string);
			}
			else
			{
				foreach($string as $innerKey => $innerValue)
				{
					$string[$innerKey] =  addslashes($innerValue);
				}
			}

			// if(!is_array($string))
			// {
			// 	$string = strip_tags($string);
			// }
			// else
			// {
			// 	foreach($string as $innerKey => $innerValue)
			// 	{
			// 		$string[$innerKey] =  strip_tags($innerValue);
			// 	}
			// }
	
		 	return $string;
		} 

	}

	public function error($mess)
	{
		log_message("error",$mess);
	}
	
	
}
?>