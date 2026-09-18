<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<div class="panel border border-secondary mb-0" style="height:65vh">
	<div class="panel-body nav-tabs-animate nav-tabs-horizontal p-10" data-plugin="tabs">
		<h4>
			<span>Client Window [ Snapshot ]</span>
			<span class="float-right"><button type="button" class="btn btn-dark" id="btnRequestSnapshot" name="clientwindowAction" title="Request Client Window Snapshot">Request Snapshot</button></span>
		</h4>
    	<div id="snapshotContainer" style="overflow:auto;height:58vh;">
    	</div>
	</div>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

$("button[name*='clientwindowAction']").click(function(){
	switch( $(this).attr('id') )
	{
		case "btnRequestSnapshot":
			var runRequestTimeout = '';
			var intervalRequest = '';
			var snapshotLoaded = false;
			var snapshotStatusRequest = false;
			var requestData = new FormData();
			requestData.append('user_id','<?php echo $currentuserid; ?>');
			postUrl = site_url+'administrator/snapshot/requestsnapshot';
			
			var presult=function(pp,rp){
             	Swal.mixin({
				customClass: {
					confirmButton: 'btn btn-dark',
				},
				buttonsStyling: false,
				willOpen: (fnRun) => {
					$(".swal2-container").css('z-index',$.topZIndex());
				}
				}).fire({
					title: "Request Client Window Snapshot",
					html: rp.Message,
					icon: ((rp.Status == 0) ? 'error' : 'success'),
					confirmButtonText: 'Close',
				}).then((result) => {
				    if( rp.Status == 1 )
				    {
				    	$("div[id='snapshotContainer']").html('<i class="fa fa-spinner icon-spin" style="font-size:250px"></i>');
						$("div[id='snapshotContainer']").css({
							'text-align':'center'
						});

						$("button[id='btnRequestSnapshot'][name='clientwindowAction']").hide();

						snapshotStatusRequest = true;

						window.intervalRequest = setInterval(function(SnapshotID){
							if( snapshotStatusRequest )
							{	
								var requestsData = new FormData();
								requestsData.append('user_id','<?php echo $currentuserid; ?>');
								requestsData.append('SnapshotID',rp.SnapshotID);
								postUrls = site_url+'administrator/snapshot/getsnapshot';
								var psresult=function(pp,rp){			
									if( rp.Status == 1)
									{
										snapshotStatusRequest = false;
										snapshotLoaded = true;
										clearTimeout(window.runRequestTimeout);
										clearInterval(window.intervalRequest);

										$("div[id='snapshotContainer']").html('<canvas id="SSData" height="'+rp.SHeight+'" width="'+rp.SWidth+'"></canvas>');

										canvas = document.getElementById("SSData");
						                ctx = canvas.getContext("2d");
						                var img = new Image();
						                img.onload = function() {
						                    ctx.clearRect(0, 0, canvas.width, canvas.height);
						                    ctx.drawImage(img, 0, 0);
						                };
						                img.src = 'data:image/png;base64,'+rp.SnapshotImage;
									}
									$("button[id='btnRequestSnapshot'][name='clientwindowAction']").show();
									
								};
								submitFormData('json','',postUrls,requestsData,psresult,false);
							}		
						},3000,rp.SnapshotID);


						window.runRequestTimeout = setTimeout(function(){
								clearInterval(window.intervalRequest);
								Swal.mixin({
								customClass: {
									confirmButton: 'btn btn-dark',
								},
								buttonsStyling: false,
								willOpen: (fnRun) => {
									$(".swal2-container").css('z-index',$.topZIndex());
								}
								}).fire({
									title: "Request Client Window Snapshot",
									html: "Client did not respond or disapproved Snapshot Request!",
									icon: ((rp.Status == 0) ? 'error' : 'success'),
									confirmButtonText: 'Close',
								}).then((result) => {
									$("button[id='btnRequestSnapshot'][name='clientwindowAction']").show();
									$("div[id='snapshotContainer']").html('');
								});

						},40000);
				    }
              	});
            };

			submitFormData('json','',postUrl,requestData,presult,true);

		break;
	}
});



});  
</script>
