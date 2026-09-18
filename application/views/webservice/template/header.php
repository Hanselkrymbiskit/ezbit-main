<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();
//
$userClassification = (!isset($userClassification)) ? ($CI->usertype > 3) ? $CI->myutilities->getRef_Desc(4,$CI->userprofile['user_classification']) : $CI->myutilities->getRef_Desc(2,$CI->usertype) : @$userClassification;
$CI->data['userClassificationDesc'] = $userClassification;
?>
<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="text/html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap material admin template">
    <meta name="author" content="">
    
    <title><?php echo @$CI->system_settings['ApplicationAbbre']; ?> - API</title>

    <link rel="apple-touch-icon" href="<?php echo assetPath(); ?>images/apple-touch-icon.png">
    <link rel="shortcut icon" href="<?php echo assetPath(); ?>images/favicon.ico">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/css/bootstrap-extend.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/datatables/datatables.min.css">
    <!-- <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/datatables/Select-1.3.1/css/select.dataTables.min.css"> -->
    <link rel="stylesheet" href="<?php echo assetPath(); ?>css/dataTables.checkboxes.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>css/site.css">

    <link href="<?php echo assetPath(); ?>skins/greendark.css" rel="stylesheet" type="text/css">
    
    <!-- Plugins -->
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/animsition/animsition.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/asscrollable/asScrollable.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/switchery/switchery.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/intro-js/introjs.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/slidepanel/slidePanel.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/jquery-mmenu/jquery-mmenu.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/flag-icon-css/flag-icon.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/waves/waves.css">

    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/bootstrap-sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/bootstrap-sweetalert2/theme/material-ui-3.1.4/material-ui.min.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/nprogress/nprogress.min.css">  
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/toastr/toastr.css">
    <!-- Load CSS here -->
    <?php
      if( isset($loadcss) && is_array($loadcss) )
      {
        foreach($loadcss as $css_name)
        {
          echo '<link rel="stylesheet" href="'.assetPath().$css_name.'.css" type="text/css" >';
        }
      }
    ?>


    <!-- Fonts -->
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/fonts/material-design/material-design.min.css">
    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/fonts/web-icons/web-icons.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/fonts/font-awesome/font-awesome.css">
    <link rel="stylesheet" href="<?php echo assetPath(); ?>global/fonts/brand-icons/brand-icons.min.css">

    <link rel='stylesheet' href='<?php echo assetPath(); ?>css/fontgoogle.css'>
    
    <link rel="stylesheet" href="<?php echo assetPath(); ?>css/coresite.css">
    <!--[if lt IE 9]>
    <script src="<?php echo assetPath(); ?>global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
    
    <!--[if lt IE 10]>
    <script src="<?php echo assetPath(); ?>global/vendor/media-match/media.match.min.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/respond/respond.min.js"></script>
    <![endif]-->
    
    <!-- Scripts -->
    
    <script src="<?php echo assetPath(); ?>global/vendor/jquery/jquery.min.js"></script>
    <!-- <script src="<?php echo assetPath(); ?>global/vendor/jquery-ui/jquery-ui.min.js"></script> -->
    <script src="<?php echo assetPath(); ?>global/vendor/breakpoints/breakpoints.js"></script>
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
      var AccountName = '<?php echo @$CI->userprofile['CompleteName']; ?>';
      var activeAjaxConnections = 0;
    </script>
    <script nonce="<?php echo $CI->nonceV; ?>" >
      Breakpoints();
    </script>
  </head>
  <?php
    $addedBodyClass = '';
    $addedPageClass = '';
    $addedPageContentClass = '';
    $addedBodyClass = 'site-navbar-small';

    log_message("error",@$MethodTitle);
  ?>

  <body id="siteBody" class="animsition <?php echo $addedBodyClass.(($headerfooter) ? '' : ' pt-0'); ?>">
    <?php if($headerfooter){ ?>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">
    
      <div class="navbar-header">
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
          data-toggle="collapse">
          <i class="icon md-more" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center <?php echo ($this->tank_auth->is_logged_in()) ? 'site-gridmenu-toggle' : ''; ?>" data-toggle="gridmenu">
          <img class="navbar-brand-logo" src="<?php echo assetPath(); ?>images/logo@2x.png" title="PHILIPPINE HEALTH INSURANCE CORPORATION" style="height: 50px;margin-top: -14px;margin-left: -12px;">
          <span class="navbar-brand-text hidden-xs-down" style="font-size: 39px"> <?php echo @$CI->system_settings['ApplicationAbbre']; ?></span>
        </div>
      </div>
    
      <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
          <span class="navbar-brand-text font-weight-bold" style="font-size: 39px"><?php echo (@$MethodTitle == '') ? 'API - Manuals' : '<span class="text-white">'.@$MethodTitle.'</span>'; ?></span>
         
          <!-- Navbar Toolbar -->
          
          <!-- End Navbar Toolbar -->
         
          <!-- Navbar Toolbar Right -->
          <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right mt-1">      
            <li class="nav-item">
              <a class="nav-link navbar-avatar"  href="#" aria-expanded="false"
                data-animation="scale-up" role="button">
                <i class="fa fa-code-fork fa-fw mr-2"></i>
                REST URL
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link navbar-avatar"  href="#" aria-expanded="false"
                data-animation="scale-up" role="button">
                <i class="fa fa-code-fork fa-fw mr-2"></i>
                SOAP WSDL
              </a>
            </li>
          </ul>
     
          <!-- End Navbar Toolbar Right -->
        </div>
        <!-- End Navbar Collapse -->
      </div>
    </nav>    

    <div class="site-menubar">
    <?php echo trim(str_replace(array("\n\r","\n","\r"),'',$CI->load->view('webservice/template/menu', $CI->data, true))); ?>
    </div>

    <?php } ?>

    <!-- Page -->
    <div class="page <?php echo @$addedPageClass;?>" data-animsition-in="fade-in" data-animsition-out="fade-out" <?php echo (!$headerfooter) ? 'style="min-height:calc(100%) !important;"' : 'style="min-height:calc(100% - 44px);"'; ?>>
      <?php if($headerfooter){ ?>
      <!-- Page Header -->
      <?php if(@$MethodTitle == ''){ ?>
      <div class="page-header" style="padding-top:10px">
        <?php $breadcrumbshtml = @$CI->breadcrumbs->show(); echo (@$breadcrumbshtml <> '') ? $breadcrumbshtml : '&nbsp;'; ?>
        
        <?php echo (@$PageTitle <> "") ? '<h1 class="page-title">'.@$PageTitle.'</h1>' : ''; ?>
        <?php if(@$pageheaderaction <> ""){ ?>
        <div class="page-header-actions mt-15">
          <?php echo @$pageheaderaction; ?>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
      <!-- End Page Header -->
      <?php } ?>
      <!-- Page Content -->
      <div class="page-content <?php echo @$addedPageContentClass; ?>">
      
    

        
      