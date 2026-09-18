<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();
?>

<?php
  $emailstat = array(
    "PENDING"=>array(
      'caption' => 'Pending eMail',
      'id'    => 2,
      'class' =>'warning'
    ),
    "SUCCESS"=>array(
      'caption' => 'Successful eMail',
      'id'    => 1,
      'class' =>'success'
    ),
    
    "FAILED"=>array(
      'caption' => 'Failed eMail',
      'id'    => 0,
      'class' =>'danger'
    ),
    "TOTAL"=>array(
      'caption' => 'Total eMail',
      'id'    => 4,
      'class' =>'secondary'
    )
  );

  foreach( $emailstat  as $emailstatkeys => $emailstat_config)
  {
    echo '
    <div class="col-xl-3 col-md-6" >
      <div class="card card-shadow">
        <div class="card-block p-20 pt-10">
          <div class="clearfix">
            <div class="grey-800 float-left py-10 font-size-20 ">
              <i class="fa-solid fa-envelope fa-fw grey-600 font-size-20 vertical-align-bottom mr-5"></i>
              <span class="text-uppercase">'.$emailstat_config['caption'].'</span>
            </div>
          </div>
          <div class="mb-20 grey-500  font-size-30">
            <span id="MailLogs_'.strtolower($emailstatkeys).'">0</span>
          </div>
        </div>
      </div>
    </div>


  ';
  }
?>

<script type="text/javascript">

</script>