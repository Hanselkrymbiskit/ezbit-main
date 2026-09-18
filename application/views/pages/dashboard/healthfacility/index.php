<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<div class="row">
	<?php 

		// ZPAMS-FIX (2026-09): was glob(".../dashboard/pro/rpt_file/*") -- pointed at the PRO
		// dashboard's report folder instead of this module's own, so Health Facility users never
		// saw any of their dashboard report widgets.
		$rptfiles = glob(VIEWPATH."/pages/dashboard/healthfacility/rpt_file/*");
      	foreach($rptfiles as $rptfile)
      	{ 
      		if(is_file($rptfile))
      		{
      			$CI->load->view(str_replace([VIEWPATH,'.php'],[''],$rptfile),$CI->data); 
	        }
      	}

	?>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

	if (screenfull.isEnabled) {

		$("a[name='rptfullscreenlink']").each(function(){
			$(this).click(function(){
				element = $(this).closest('.panel')[0];
				screenfull.toggle(element);
			});
		});

		screenfull.on('change', (evt) => {
			var did =  $(evt.target).attr('id');
			var canvasEle =  $("div[id='"+did+"']").find('canvas');
			if(screenfull.isFullscreen)
			{
				canvasEle.attr('dheight',canvasEle.css('height'));
				canvasEle.css('height','60vh');
			}
			else
			{
				canvasEle.css('height',canvasEle.attr('dheight'));
				canvasEle.removeAttr('dheight');
			}
			
		});
	}

});
</script>