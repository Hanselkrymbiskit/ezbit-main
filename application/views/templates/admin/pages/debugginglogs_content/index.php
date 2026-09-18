<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$filelist = array();        
$filelist_dir =  $CI->myutilities->getDirContents( $dir );
?>
<div style="height: 70vh">
  <div class="row h-p100">
    <div class="col-lg-2">
      <div class="panel p-15 pb-0 mb-0 h-p100">
        <div class="panel-body p-0">
          <h6>Log Date <?php echo (count($filelist_dir) > 0) ? '<i id="DeleteAllLogs" dir="'.$CI->encryption->encrypt($dir).'" class="fa fa-trash float-right fa-fw" title="Clear All Logs" style="cursor:pointer"></i>' : ''; ?></h6>
          <div style="overflow-y:  auto;height:65vh; ">
              <ul class="list-group  list-group-full">
          <?php 

            foreach($filelist_dir as $fRow => $fVal)
            {
                $dname = str_replace( ['log-','.php'],'',$fVal['filename']);
                echo '<li class="list-group-item" style="padding:3px 0px">
                        <a href="javascript:void(0)" onclick="viewlogs(\''.base64_encode(json_encode($fVal)).'\')"><i class="fa fa-fw fa-file mr-10">
                      </i>'.$dname.'</a>
                      </li>';
            }

          ?>
              </ul>         

          </div>
          
        </div>
      </div> 
    </div>

    <div class="col-lg-10">
      <div class="panel p-15 pb-0 mb-0 h-p100">
        <div class="panel-body p-0">
          <h6 style=""><span class="mr-20">File Date : <span id="FileDate"></span></span><span class="float-right">File Size : <span id="FileSize"></span></span></h6>
          <pre id="logcontentData" style="overflow-y:  auto;height:65vh;background: #3e3e3e;color:#fff; ">
          </pre>
        </div>
      </div>
    </div>
  </div>
</div>


<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

function viewlogs(param)
{
  var fParam = atob(param);
  fParam = JSON.parse(fParam);

  var FileDate = fParam['filename'].replace('log-','');
  FileDate = FileDate.replace('.php','');
  $("span[id='FileDate']").text(FileDate);
  $("span[id='FileSize']").text(fParam['sizemb']);

  postUrl = site_url+'administrator/debugginglogs/getlogs';
  
  var logresults = function(pp,rp){      
    if(rp.Status == 1)
    {
      $("pre[id='logcontentData']").html(rp.Message);
    }
  };

  var logparam = new FormData();
  logparam.append('LogPath',fParam['path']);
  submitFormData('json','',postUrl,logparam,logresults,true);  
} 

$(document).ready(function(){


$("i[id='DeleteAllLogs']").click(function(){
  postUrl = site_url+'administrator/debugginglogs/deletealllogs';
  
  var deletelogresults = function(pp,rp){      
    Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-dark',
        },
        buttonsStyling: false,
        willOpen: (fnRun) => {
          $(".swal2-container").css('z-index',$.topZIndex());
        }
      }).fire({
        title: "Delete Log File",
        html: rp.Message,
        icon: (rp.Status == 1) ? "success" : "error",
        confirmButtonText: 'Close',
      }).then((result) => {
                    
          if(rp.Status == 1)
          {
            window.location.reload();
          }

      });
  };

  var dellogparam = new FormData();
  dellogparam.append('DirPath',$(this).attr('dir'));
  submitFormData('json','',postUrl,dellogparam,deletelogresults,true);  
});


});  


</script>

