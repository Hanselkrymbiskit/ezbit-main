<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


$ReqExt = [
  ['ext_name' => 'OpenSSL', 'php_extname' => 'openssl','mandatory'=>'Yes' ],
  ['ext_name' => 'GD', 'php_extname' => 'gd','mandatory'=>'Yes' ],
  ['ext_name' => 'Curl', 'php_extname' => 'curl','mandatory'=>'Yes' ],
  ['ext_name' => 'ImageMagick', 'php_extname' => 'imagick','mandatory'=>'Optional' ],
  ['ext_name' => 'Mbstring', 'php_extname' => 'mbstring','mandatory'=>'Yes' ],
  ['ext_name' => 'GMP', 'php_extname' => 'gmp','mandatory'=>'Yes' ],
  ['ext_name' => 'BCMath', 'php_extname' => 'bcmath','mandatory'=>'Yes' ],
  ['ext_name' => 'ICONV', 'php_extname' => 'iconv','mandatory'=>'Yes' ],
  ['ext_name' => 'IMAP', 'php_extname' => 'imap','mandatory'=>'Yes' ],
  ['ext_name' => 'ZLIB', 'php_extname' => 'zlib','mandatory'=>'Yes' ],
  ['ext_name' => 'Tokenizer', 'php_extname' => 'tokenizer','mandatory'=>'Yes' ],
  ['ext_name' => 'APC', 'php_extname' => 'apc','mandatory'=>'Optional' ],
  ['ext_name' => 'memcache', 'php_extname' => 'memcache','mandatory'=>'Optional' ],
  ['ext_name' => 'Windows Cache', 'php_extname' => 'wincache','mandatory'=>'Optional' ],
  ['ext_name' => 'OPcache', 'php_extname' => 'opcache','mandatory'=>'Optional' ],
  ['ext_name' => 'ZIP', 'php_extname' => 'zip','mandatory'=>'Yes' ],
];

$FuncReq = [
  ['func_name' => 'Daemon Asynchronous Function', 'functions' => 'diag_daemon'],
  ['func_name' => 'File Encryption (RSA/Cipher) Key  Generation', 'functions' => 'diag_cipherkey'],
  ['func_name' => 'Email Sending Function', 'functions' => 'diag_email'],
];

$DirReq = [
  ['dir_name' => 'htaccess File', 'path' => '.htaccess' , 'type' => 'file', 'write'=>true,'delete'=>'ignore'],
  ['dir_name' => 'htaccessOrig File', 'path' => '.htaccessOrig' , 'type' => 'file', 'write'=>'ignore','delete'=>'ignore'],
  ['dir_name' => 'BanIP File', 'path' => 'BanIP.txt' , 'type' => 'file', 'write'=>true,'delete'=>'ignore'],
  ['dir_name' => 'BanIPList File', 'path' => 'BanIPList.txt' , 'type' => 'file', 'write'=>'','delete'=>'ignore'],
  ['dir_name' => 'IPLookupList File', 'path' => 'IPLookupList.txt' , 'type' => 'file', 'write'=>'','delete'=>'ignore'],
  ['dir_name' => 'Openssl CNF File', 'path' => 'application/keystorage/openssl.cnf' , 'type' => 'file', 'write'=>'ignore','delete'=>'ignore'],
  ['dir_name' => 'Attachments', 'path' => 'attachments', 'type' => 'dir', 'write'=>true,'delete'=>true],
  ['dir_name' => 'Error Log', 'path' => 'application/logs/error_log', 'type' => 'dir', 'write'=>true,'delete'=>true],
  ['dir_name' => 'Database Log', 'path' => 'application/logs/db_log', 'type' => 'dir', 'write'=>true,'delete'=>true],
  ['dir_name' => 'Email Log', 'path' => 'application/logs/email_log', 'type' => 'dir', 'write'=>true,'delete'=>true],
  ['dir_name' => 'Debug Log', 'path' => 'application/logs/udebug_log', 'type' => 'dir', 'write'=>true,'delete'=>true],
];

?>

<div class="row">
  <div class="col-lg-4">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">PHP Extension/s</h3>
      </div>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Extension Name</th>
            <th>Enabled</th>
            <th>Mandatory</th>
          </tr>
        </thead>
        <tbody>
        <?php
          foreach ($ReqExt as $key => $extRow) {
            $eCnt = ( @$eCnt == '' ) ? 1 : ($eCnt + 1);

            $xtE = ((!extension_loaded($extRow['php_extname'])) ? 'No' : 'Yes');
            $xtEC = ($xtE == 'No') ? 'text-danger' : 'text-success';
            $xtEC = ( $extRow['mandatory'] == 'Optional' ) ? '' : $xtEC;
            echo '
                <tr class="'.@$xtEC.'">
                  <td>'.@$eCnt.'</td>
                  <td>'.$extRow['ext_name'].'</td>
                  <td>'.$xtE.'</td>
                  <td>'.$extRow['mandatory'].'</td>
                </tr>
            ';
          }
        ?>

        </tbody>
      </table>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="panel mb-5">
      <div class="panel-heading">
        <h3 class="panel-title">System Features / Function</h3>
      </div>
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th class="w-p25">Features / Function</th>
            <th class="w-p20 text-center">Result</th>
            <th class="w-p50 text-center"></th>
          </tr>
        </thead>
        <tbody id="funccheck">
        <?php
          foreach ($FuncReq as $key => $funcRow)
          {
            $fcCnt = ( @$fcCnt == '' ) ? 1 : ($fcCnt + 1);
            echo '
                <tr class="">
                  <td>'.$fcCnt.'</td>
                  <td>'.@$funcRow['func_name'].'</td>
                  <td id="'.@$funcRow['functions'].'" class="text-center"><i class="fa fa-spinner fa-spin "></i></td>
                  <td id="'.@$funcRow['functions'].'_reco" class="text-left"></td>
                </tr>
            ';
          }
        ?>
        </tbody>
      </table>
    </div>
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">File & Directory</h3>
      </div>
      <table class="table table-hover">
        <thead>
          <tr>
            <th class="">#</th>
            <th class=" ">File / Directory Name</th>
            <th class=" ">Path</th>
            <th class=" ">Type</th>
            <th class=" ">Exists</th>
            <th class="">Write</th>
            <th class="">Delete</th> 
            <th ></th>
          </tr>
        </thead>
        <tbody id="filescheck">
        <?php
          foreach ($DirReq as $key => $dirRow)
          {

            $dCnt = ( @$dCnt == '' ) ? 1 : ($dCnt + 1);
            if($dirRow['path'] <> '')
            {
              $pathExists =  (file_exists(FCPATH.@$dirRow['path'])) ? true : false;
              $checkAccess = $CI->myutilities->checkDirectoryAccess(FCPATH.@$dirRow['path']);
            }
            
            $jsid = base64_encode(str_replace(FCPATH,'',$dirRow['path']));
            $DirReq[$key]['id'] = $jsid;
            echo '

                <tr class="">
                  <td>'.$dCnt.'</td>
                  <td>'.@$dirRow['dir_name'].'</td>
                  <td>'.@$dirRow['path'].'</td>
                  <td>'.@$dirRow['type'].'</td>
                  <td>'.( (@$pathExists) ? '<i class="fa-solid fa-check text-success"></i>' : '<i class="fa-solid fa-xmark text-danger"></i>').'</td>
                  <td id="writeable_'.$jsid.'">'.( ($dirRow['write'] === 'ignore') ? '<i class="fa-solid fa-ban"></i>' : '').'</td>
                  <td id="deleteable_'.$jsid.'">'.( ($dirRow['delete'] === 'ignore') ? '<i class="fa-solid fa-ban"></i>' : '').'</td>
                  <td id="status_'.$jsid.'" class="text-center">'.( ($dirRow['write'] == 'ignore' && $dirRow['delete'] == 'ignore')  ? '' : '<i class="fa fa-spinner fa-spin "></i>' ).'</td>
                </tr>
            ';

          }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">

$(document).ready(function(){
  
  const rdiag = function(){
    var filechk = <?php echo json_encode($DirReq); ?>;
    for(var fx in filechk)
    {
        var xformdata = new FormData();
        xformdata.append('params',JSON.stringify(filechk[fx]));
        if(filechk[fx].write === 'ignore' && filechk[fx].delete === 'ignore')
        {
          $("td[id='status_"+filechk[fx].id+"']").html('<i class="fa-solid fa-ban"></i>');
        }
        else
        {
          $("td[id='status_"+filechk[fx].id+"']").html('<i class="fa fa-spinner fa-spin "></i>');
          
          if(filechk[fx].write !== 'ignore')
          {
            $("td[id='writeable_"+filechk[fx].id+"']").html('');
          }

          if(filechk[fx].delete !== 'ignore')
          {
            $("td[id='deleteable_"+filechk[fx].id+"']").html('');
          }

          var fResult = function(pp,rp){
            
            var ppid = pp.get('params');
            ppid = JSON.parse(ppid);
            if( rp.Status == 1)
            {
              var frs = rp.Message;
              if(frs.write !== 'ignore')
              {
                
                $("td[id='writeable_"+frs.id+"']").html('<i class="fa-solid '+( (frs['writeable']) ? 'fa-check text-success' : 'fa-xmark text-danger')+' "></i>');
              }

              if(frs.delete !== 'ignore')
              {
                $("td[id='deleteable_"+frs.id+"']").html('<i class="fa-solid '+( (frs['deleteable']) ? 'fa-check text-success' : 'fa-xmark text-danger')+'"></i>');
              }
              
              $("td[id='status_"+frs.id+"']").html('');

            }
            else
            {
              if(ppid.write !== 'ignore')
              {
                $("td[id='writeable_"+ppid.id+"']").html('<i class="fa-solid fa-xmark text-danger"></i>');
              }

              if(ppid.delete !== 'ignore')
              {
                $("td[id='deleteable_"+ppid.id+"']").html('<i class="fa-solid fa-xmark text-danger"></i>');
              }
            }

            $("button[id='btnRunDiagnose']").removeAttr('disable');
          };

          target_url = site_url+'administrator/diagnose/filescheck';
          submitFormData('json','',target_url,xformdata,fResult,false);
        }
    }

    var funcchk = <?php echo json_encode($FuncReq); ?>;
    for(var fnx in funcchk)
    {
      var fxformdata = new FormData();
      fxformdata.append('params',JSON.stringify(funcchk[fnx]));
      $("td[id='"+funcchk[fnx].functions+"']").html('<i class="fa fa-spinner fa-spin "></i>');
      $("td[id='"+funcchk[fnx].functions+"_reco']").html('');
      var fxResult = function(pp,rp){
        var ppid = pp.get('params');
        ppid = JSON.parse(ppid);
        if( rp.Status == 1)
        {
          $("td[id='"+ppid.functions+"']").html('<i class="fa-solid fa-check text-success"></i>');
        }
        else
        {
          $("td[id='"+ppid.functions+"']").html('<i class="fa-solid fa-xmark text-danger"></i>');
        }
        $("td[id='"+ppid.functions+"_reco']").html(rp.Message);
      };
      target_url = site_url+'administrator/diagnose/'+funcchk[fnx].functions;
      submitFormData('json','',target_url,fxformdata,fxResult,false);
    }

  };

  rdiag();
  $("button[id='btnRunDiagnose']").click(function(){
    rdiag();
    $(this).attr('disable','disable');
  });


});  


</script>

