<?php //
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo @$title; ?></title>
  <!-- <link rel="preload" href="<?php echo getimagePath(); ?>DOHsmallest.png" as="image"> -->
 <!-- Load Default CSS-->
  <?php echo getDefaultCSS(); ?>
  <?php
  if(isset($loadcss) && $loadcss <> '')
  {
    if(is_array($loadcss))
    {
      foreach($loadcss as $css_name)
      {
        echo '<link href="'.getcssPath().$css_name.'.css'.'" rel="stylesheet" type="text/css">';
      }
    }
    else
    {
      echo '<link href="'.getcssPath().$loadcss.'.css'.'" rel="stylesheet" type="text/css">';
    }
  }
  ?>
   <!-- Load Default JS-->
  <?php echo getDefaultJS(); ?> 
  <?php
  if(isset($loadjs) && $loadjs <> '')
  {
    if(is_array($loadjs))
    {
      foreach($loadjs as $js_name)
      {
        echo '<script src="'.getjsPath().$js_name.'.js'.'" type="text/javascript"></script>';
      }
    }
    else
    {
      echo '<script src="'.getjsPath().$loadjs.'.js'.'" type="text/javascript"></script>';
    }
  }
  ?>
  <script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
    var UToken = '<?php echo base64_encode(base64_encode(date('Ymd'))); ?>';
    var site_url = '<?php echo site_url(); ?>';
    var assetPath = '<?php echo assetPath(); ?>';
    var imagepath = '<?php echo getimagePath(); ?>';
    var csspath = '<?php echo getcssPath(); ?>';
    var jspath = '<?php echo getjsPath(); ?>';
    var ServerDate = '<?php echo date("m/d/Y"); ?>';
    var currentPage = '<?php echo $CI->uri->segment(1); ?>';
    var currentDate = "<?php echo date("m/d/Y"); ?>";
    var currentServerTime = '<?php echo round(microtime(true) * 1000); ?>';
    var currentServerDateTime = '<?php echo date("m/d/Y h:i:s A"); ?>';
  </script>

</head>

<body id="page-top"  class="scrollbar-default3 thin"   style=" ">
<script  id="removethis" type="text/javascript">

<?php
  if( $CI->tank_auth->is_logged_in() )
  {
    echo "timer_function('clockholder');";
  } 
  
?>

var AjaxCalled = 0;
  
$(document).ajaxSend(function(e, xhr, opt){
  AjaxCalled++;
});
  
$(document).ajaxStop(function(){
  AjaxCalled = 0;
  loader(false);

  $(this).unbind("ajaxStop");
  $(this).unbind("ajaxSend");
});

  $(window).on({
    'load':function(){
      // loader(false);
      if( AjaxCalled == 0 || currentPage == 'administrator'  )
      {
        loader(false);
      }
    },
    'beforeunload':function(){
      loader(true);
    }
  }) 

  $("script[id='removethis']").remove();
</script>

  <!-- Page Wrapper -->
  <div id="wrapper" style=";background-color: #263238">
     
    <?php if(@$headerfooter){ ?>
    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light fixed-top bg-success topbar mb-4 static-top shadow">
      <!-- Sidebar Toggle (Topbar) -->
      <span class="navbar-brand d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block"><img id="PageBrandingImage" src="<?php echo getimagePath().'Brand.png'; ?>" class="lazyload" height="60" width="60"></span>

      <div style="width:  50%">
        <div style="padding-top:3px">
          <h1 class="" style="margin: 0px;font-family: Poppins-Regular;font-weight: bold;color: #263238;">
            <?php echo @$CI->system_settings['ApplicationAbbre']; ?><span style="margin-left: 10px;font-size:10px">v <?php echo @$CI->system_settings['ApplicationVersion']; ?></span>
          </h1>
        </div>
        <div style="margin-top: -7px;padding-left: 2px;">
          <h7 class="" style="font-size: 12px;color: #000">
            <?php echo @$CI->system_settings['ApplicationName']; ?>
          </h7>
        </div>
      </div>
      
      <ul class="navbar-nav nav-pills ml-auto">
        <!-- Nav Item - User Information -->
       <!--  <li class="nav-item no-arrow">
          <a class="nav-link" href="<?php echo site_url('login'); ?>" id="login" role="button" aria-haspopup="true" aria-expanded="false" style="color:#000">
            Login
          </a>    
        </li>
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link" href="<?php echo site_url('about'); ?>" id="contactus" role="button" aria-haspopup="true" aria-expanded="false" style="color:#000">
            About
          </a>   
        </li> -->
        <li class="nav-item dropdown no-arrow">

          <a class="nav-link" href="<?php echo site_url('contactus'); ?>" id="contactus" role="button" aria-haspopup="true" aria-expanded="false" style="color:#000">
            <i class="fas fa-code-branch" style="margin-right:5px"></i>REST URL
          </a>
         
        </li>
        <li class="nav-item dropdown no-arrow">

          <a class="nav-link" href="<?php echo site_url('faq'); ?>" id="contactus" role="button" aria-haspopup="true" aria-expanded="false" style="color:#000">
            <i class="fas fa-code-branch" style="margin-right:5px"></i>SOAP URL
          </a>
         
        </li>
    
      </ul>  
    </nav>
    <!-- End of Topbar -->

    <!-- Sidebar -->
    <?php 
      // Site Menu [ Left Menu ]
      echo trim(str_replace(array("\n\r","\n","\r"),'',$CI->load->view('webservice/template/menu', $CI->data, true)));
    ?>  
    <!-- End of Sidebar -->
  
    <!-- Start of Breadcrumbds -->
    <div id="PageBreadcrumbs-Holder" class="d-sm-flex align-items-center justify-content-between mb-4" style="position: fixed;left: 17vw;top: 4.375rem;background: #f8f9fc;width: 83%;padding: .2% 1.5% .2% 1.5%;z-index: 4;font-size: 10px;/* border: 1px solid; */margin-bottom: 0px !important;">
     <?php $breadcrumbshtml = @$CI->breadcrumbs->show(); echo (@$breadcrumbshtml <> '') ? $breadcrumbshtml : '&nbsp;'; ?>
    </div>
    <!-- End of Breadcrumbds -->
    <!-- Start of Page Title -->
    <?php if(trim(@$PageTitle) <> ""){ ?>
    <div id="PageTitle-Holder" class="d-sm-flex align-items-center justify-content-between mb-4" style="position: fixed;left: 17vw;top: 5.375rem;width: 83%;background: #f8f9fc;padding: 1% 1.5% 1% 1.5%;z-index: 4;">
      <h1 class="h3 mb-0 text-gray-800" style="width:100%;border-bottom: 1px solid #ebe5ec;padding-bottom: 10px !important;">
        <?php echo @$PageTitle; ?>
      </h1>
    </div>
    <?php }else{ $NoPageTitle = true; } ?>
    <!-- End of Page Title -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column " style="margin-top:<?php echo (trim(@$PageTitle) <> "") ? '17.9' : '9'; ?>vh;margin-left: 17vw">
     
      <!-- Main Content -->
      <div id="content" style="min-height:<?Php echo (@$NoPageTitle == true) ? '84' : '77'; ?>vh">
        <!-- Begin Page Content -->
        <div class="container-fluid" style="padding-top: 1.5rem;">
    <?php }else{ ?>   
    <div id="content" style="min-height:100vh;min-width:100vw"> 
    <?php } ?> 
            <!-- Site Content Title -->
            

          
        