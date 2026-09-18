<?php
   $CI =& get_instance();
	
   $getList = $CI->sqlhelper->local->select("ws_method_param_key")->where("FunctionID = ".$FunctionID."")->result();

?>

<div>
	<div style="padding:10px;border:1px solid #d6e9c6;margin-bottom:10px;background:#f7f7f7">
		<span id="i_copy" class="pull-right" style="color:#000;cursor:pointer;font-size:12px" data-clipboard-target="clipboard_pre"><i class=" fa fa-copy" style="margin-right:5px"></i>Copy</span>
		<code id="clipboard_pre">
			WS_Check()
		</code>
	</div>
	
</div>
<!-- view_data_content_2 -->
<script type="text/javascript">
$("tr[id*='view_data_content_']").click(function(){
	view_ws_data_content($(this).attr('id').replace('view_data_content_',''),$(this).attr('title'),DataTable_Display);
});
</script>
