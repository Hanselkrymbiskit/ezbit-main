<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>
<div class="row"  data-plugin="matchHeight" data-by-row="true">
  <div class="col-xl-2 col-md-6" >
    <div class="card card-shadow" >
      <div class="card-block p-20 pt-10">
        <div class="clearfix">
          <div class="grey-800 float-left py-10 font-size-20 ">
            <i class="fa-solid fa-code-fork fa-fw grey-600 font-size-20 vertical-align-bottom mr-5"></i>
            <span class="text-uppercase">Git Commit Version</span>
          </div>
        </div>
        <div class="mb-20 grey-500  font-size-16">
        <?php
          
          // if (file_exists(FCPATH.'/.git/refs/heads/main')) 
          // {
          //   $HEAD_hash = trim(file_get_contents(FCPATH.'/.git/refs/heads/main'));
          // }

          // try
          // {
          //   $files = glob(FCPATH.'/.git/refs/tags/*');
          //   foreach(array_reverse($files) as $file) {
          //     $contents = trim(file_get_contents($file));

          //     if($HEAD_hash === $contents)
          //     {
          //       $HEAD_hash = basename($file);
          //       exit;
          //     }
          //   }
          // } catch (Exception $e) {
          //   log_message("error",__METHOD__ .' | '.$e->getMessage());
          // }

          $gitVersion = new \Antalaron\GitVersion\GitVersion();
          $HEAD_hash = $gitVersion->getVersion(FCPATH);
          echo (@$HEAD_hash == '') ? '' : @$HEAD_hash;

        ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-2 col-md-6" >
    <div class="card card-shadow" >
      <div class="card-block p-20 pt-10">
        <div class="clearfix">
          <div class="grey-800 float-left py-10 font-size-20 ">
            <i class="fa-solid fa-hard-drive fa-fw grey-600 font-size-20 vertical-align-bottom mr-5"></i>
            <span class="text-uppercase">Available Storage</span>
          </div>
        </div>
        <div class="mb-5 grey-500  font-size-30">
        <?php
          try
          {
          echo '
            <table class="table table-sm mb-0 border-0"><tr><td class="border-0 font-size-16">Apps Size : </td><td class="border-0 font-size-16 text-right"><span id="storagesize_apps"></span></td></tr><tr><td class="border-0 font-size-16">Server Free Space : </td><td class="border-0 font-size-16 text-right"><span id="storagesize_freespace"></span></td></tr></table>
          ';
          } catch (Exception $e) {
            log_message("error",__METHOD__ .' | '.$e->getMessage());
          }  
        ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-2 col-md-6" >
    <div class="card card-shadow" >
      <div class="card-block p-20 pt-10">
        <div class="clearfix">
          <div class="grey-800 float-left py-10 font-size-20 ">
            <i class="fa-solid fa-server fa-fw grey-600 font-size-20 vertical-align-bottom mr-5"></i>
            <span class="text-uppercase">Server Environment</span>
          </div>
        </div>
        <div class="mb-20 grey-500  font-size-30">
        <?php echo php_uname('s').', '.php_uname('r') ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-2 col-md-6" >
    <div class="card card-shadow">
      <div class="card-block p-20 pt-10">
        <div class="clearfix">
          <div class="grey-800 float-left py-10 font-size-20 ">
            <i class="fa-solid fa-cloud fa-fw grey-600 font-size-20 vertical-align-bottom mr-5"></i>
            <span class="text-uppercase">Web Environment</span>
          </div>
        </div>
        <div class="mb-20 grey-500  font-size-30">
        <?php 

          $ss = explode("/",$_SERVER["SERVER_SOFTWARE"]); 
          echo $ss[0];
        
        ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-2 col-md-6" >
    <div class="card card-shadow">
      <div class="card-block p-20 pt-10">
        <div class="clearfix">
          <div class="grey-800 float-left py-10 font-size-20 ">
            <i class="fa-solid fa-code fa-fw grey-600 font-size-20 vertical-align-bottom mr-5"></i>
            <span class="text-uppercase">PHP Version</span>
          </div>
        </div>
        <div class="mb-20 grey-500  font-size-30">
        <?php echo phpversion(); ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-2 col-md-6" >
    <div class="card card-shadow"  >
      <div class="card-block p-20 pt-10">
        <div class="clearfix">
          <div class="grey-800 float-left py-10 font-size-20 ">
            <i class="fa-solid fa-database fa-fw grey-600 font-size-20 vertical-align-bottom mr-5"></i>
            <span class="text-uppercase">MYSQL Version</span>
          </div>
        </div>
        <div class="mb-20 grey-500  font-size-30">
        <?php 
          if( $CI->DBConnected )
          {
            $dbversion = $CI->sqlhelper->local->sql("select version() as DBVersion")->row();
            echo ($dbversion['Count'] > 0) ? str_replace("-log","",$dbversion['Data']['DBVersion']) : "";
          }
          else
          {
            echo "Database Connection Failed";
          }  
        ?>
        </div>
      </div>
    </div>
  </div>

  

<?php
  $usersinfoparam = array();
  $usersinfo = $CI->load->view('templates/admin/pages/dashboard_content/usersinfo', $CI->data, true);
  echo trim(str_replace(array("\n\r","\n","\r"),'',$usersinfo));
?> 

<?php
  $maillogs = $CI->load->view('templates/admin/pages/dashboard_content/maillogs', $CI->data, true);
  echo trim(str_replace(array("\n\r","\n","\r"),'',$maillogs));
?> 


</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){
  rptData = new FormData();
  var apiPath = site_url+'utilities/get_StorageSpace';
  var apiResult = function(pp,rp){
     if(rp.Status == 1)
    {
      if( Object.keys(rp.Message).length > 0 )
      {
        for(var i in rp.Message)
        {
          $("span[id='"+i+"']").html(rp.Message[i]);
        } 
      }
      
    }
  }
  submitFormData('json','',apiPath,rptData,apiResult,false,true,0);
});
</script>