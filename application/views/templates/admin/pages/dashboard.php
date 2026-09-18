<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();



$dbSize = '';


 $getCurrentDBSize = $CI->sqlhelper->local->select("information_schema.TABLES","table_schema as `Database`,SUM( (data_length + index_length) ) as `Size_in_Bytes`,SUM(TABLE_ROWS) as TotalRecordCount, COUNT(TABLE_NAME) as TotalTable")->where(" table_schema = '".$CI->defaultDB->database."' ")->ex_select('','(data_length + index_length) DESC','')->row();

?>

<div class="jumbotron" style="padding: 1rem 1.5rem;margin-bottom: 1rem;">
  <h1 class="display-4 text-success ">Active <i class="fa fa-check-circle fa-fw float-right"></i></h1>
  <hr class="my-4" style="    margin: .5rem !important;">
  <p>System Status. <span class="float-right">IP : <?php echo $_SERVER['SERVER_ADDR']; ?></span></p>
 
</div>

<div class="row">
 
  <div class="col-xl-4 col-lg-5">
    <div class="card shadow mb-4"  style="height:410px">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-server fa-fw"></i>&nbsp;Server Information</h6>
       
      </div>
      <!-- Card Body -->
      <div class="card-body"  style="height:360px;padding:0.25rem">

        <table class="table" style="border:0px !important;margin-bottom:0px !important">
        
          <tr>
            <td class="text-success" style="border:0px !important;font-weight:bold;vertical-align:middle">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Server Environment</div>
              <div class="h6 mb-0 font-weight-bold text-gray-800"><?php echo php_uname('s').', '.php_uname('r') ?></div>
            </td>
          </tr>
          <tr>
            <td class="text-success" style="border:0px !important;font-weight:bold;vertical-align:middle">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Web Environment</div>
              <div class="h6 mb-0 font-weight-bold text-gray-800"><?php echo $_SERVER["SERVER_SOFTWARE"]; ?></div>
            </td>
          </tr>
         <tr>
            <td class="text-success" style="border:0px !important;font-weight:bold;vertical-align:middle">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">PHP Version</div>
              <div class="h6 mb-0 font-weight-bold text-gray-800"><?php echo phpversion(); ?></div>
            </td>
          </tr>
          <tr>
            <td class="text-success" style="border:0px !important;font-weight:bold;vertical-align:middle">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">MYSQL Version</div>
              <div class="h6 mb-0 font-weight-bold text-gray-800">
                <?php 
                    if( $CI->DBConnected )
                    {
                      $dbversion = $CI->sqlhelper->local->select("users","version() as DBVersion")->ex_select('','','1')->row();
                      echo ($dbversion['Count'] > 0) ? str_replace("-log","",$dbversion['Data']['DBVersion']) : "";
                    }
                    else
                    {
                      echo "Database Connection Failed";
                    }  
                  ?>
              </div>
            </td>
          </tr>
          <tr>
            <td class="text-success" style="border:0px !important;font-weight:bold;vertical-align:middle">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Application Path</div>
              <div class="h6 mb-0 font-weight-bold text-gray-800"><?php echo FCPATH; ?></div>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  

  <?php
    // DB Information
    $dbinfoparam = array();
    $dbinfo = $CI->load->view('templates/admin/pages/dashboard_content/dbinfo', $CI->data, true);
    echo trim(str_replace(array("\n\r","\n","\r"),'',$dbinfo));
  ?>

  <?php
    // Storage Information
    $storageinfoparam = array();
    $storageinfo = $CI->load->view('templates/admin/pages/dashboard_content/storageinfo', $CI->data, true);
    echo trim(str_replace(array("\n\r","\n","\r"),'',$storageinfo));
  ?>
  

</div>



<script type="text/javascript">
// getstorageinfo('storageinfo');
// getdatabaseinfo('databaseinfo');
var AutoInfoRefresh = setInterval(function(){
    $("i[name*='refreshinfo']").each(function(){
        var inforefreshid = 'get'+$(this).attr('id');
        var fn = window[inforefreshid];
        if (typeof fn === "function"){       
          fn($(this).attr('id'));
        } 
    });
},10000);
</script>