<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<div class="col-lg-12">
  <?php 
    $downloadlist = $CI->load->view('templates/download/downloadlist.php', $CI->data, true);
    $downloadlist = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $downloadlist));
    echo str_replace(array('text-white'), 'text-dark', $downloadlist);
  ?>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){


});  
</script>
