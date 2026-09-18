<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

?>

<?php if( ( is_array(@$preauthHistory) && count($preauthHistory) > 0 ) || ( is_array(@$preauthHStatus) && count($preauthHStatus) > 1 ) ){ ?>
<div class="row">
  <?php if(@$currenthistoryid <> ''){ ?>
  <div class="col-lg-12 mb-5 ">
    <h3 class="mt-0 bg-white border p-10  font-weight-bold">
      <i class="fa fa-calendar mr-10"></i>Pre-Auth Form Version Date : <?php echo date("M d, Y h:i:s A",strtotime($currenthistorydate)); ?>
      <?php 
      if( @$currenthistoryid <> '')
      {
        echo '
            <button type="button" class="btn btn-success float-right " onclick="window.location.href=\''.site_url('preauthorization/index/view/'.bin2hex(base64_encode( ((int) $recentid) )) ).'\'" title="Click to View Recent Pre-Authorization Form"><i class="icon md-calendar float-left mr-10 text-white font-size-16" aria-hidden="true"></i>View Recent Form</button>
          ';
      }
      ?>
    </h3>
  </div>
  <?php } ?>
  <div class="col-lg-10">
<?php } ?>

