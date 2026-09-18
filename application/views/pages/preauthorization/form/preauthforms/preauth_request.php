<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();



if(@$preauthforms <> '')
{
	$PREAUTH_REQUEST = '';
	$phpFilePath = 'views/pages/preauthorization/form/preauthforms/preauth_request/preauth_'.$preauthforms;
	if( is_file(APPPATH.$phpFilePath.'.php'))
	{
		$PREAUTH_REQUEST = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $CI->load->view(str_replace('views/','',$phpFilePath), $CI->data, true)));
	}
}


?>

<div id="preauth_request_maincontent">
	<?php echo @$PREAUTH_REQUEST; ?>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){


  
});
</script>


