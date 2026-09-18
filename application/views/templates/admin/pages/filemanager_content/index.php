<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<div id="scriptPart">
<script type="text/javascript">
  $(document).ready(function(){
       $('#myform').submit().remove();
       $("iframe[id='my-iframe']").show();
       $("div[id='scriptPart']").remove();
  });
</script>
</div>
<iframe id="my-iframe" name="my-iframe" src="<?php echo site_url('assets/filemanager/dialog.php?type=0&akey='.$CI->encryption->encrypt($CI->config->item('encryption_key'))); ?>" style="width:100%;height:73vh;border:0px">
</iframe>