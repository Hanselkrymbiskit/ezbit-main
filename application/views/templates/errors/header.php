<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();

$site_url = (( isset($_SERVER['HTTPS']) ) ?  "https://" : "http://").$_SERVER['HTTP_HOST'];
$site_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

$assetsPath = $site_url.'assets/';
$cssPath = $assetsPath.'css/';
$jsPath = $assetsPath.'js/';
$imgPath = $assetsPath.'img/';
$nonceV = '';
if( $_SERVER['REQUEST_METHOD'] == 'GET' )
{
    $bin2hex = bin2hex(openssl_random_pseudo_bytes(32));
    if(@$_SERVER['UNIQUE_ID'] <> '')
    {
      $nonceV = base64_encode(@$_SERVER['UNIQUE_ID']); //$bin2hex;
    }
}

// $cspHeader = "default-src 'self' *.".$_SERVER['HTTP_HOST']." 'nonce-".@$nonceV."'; img-src 'self' *.".$_SERVER['HTTP_HOST']."  data: 'nonce-".@$nonceV."';style-src 'self' *.".$_SERVER['HTTP_HOST']." 'nonce-".@$nonceV."'; style-src-attr 'self' *.".$_SERVER['HTTP_HOST']." 'unsafe-inline' ; style-src-elem 'self' *.".$_SERVER['HTTP_HOST']." 'unsafe-inline'; script-src-elem 'self' *.".$_SERVER['HTTP_HOST']." 'nonce-".@$nonceV."' 'unsafe-inline';script-src 'self' *.".$_SERVER['HTTP_HOST']." 'unsafe-inline' ;form-action 'none'";

// header("Content-Security-Policy: ".$cspHeader);

?>
<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Error Page">
    <meta name="author" content="Germel Sevilla">
    
    <title><?php echo @$heading; ?></title>
    
    <link nonce="<?php echo @$nonceV; ?>"rel="apple-touch-icon" href="<?php echo $assetsPath; ?>images/apple-touch-icon.png">
    <link nonce="<?php echo @$nonceV; ?>"rel="shortcut icon" href="<?php echo $assetsPath; ?>images/favicon.ico">
    
    <!-- Stylesheets -->
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/css/bootstrap.min.css">
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/css/bootstrap-extend.min.css">
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>css/site.min.css">
    <link nonce="<?php echo @$nonceV; ?>"href="<?php echo $assetsPath; ?>skins/green<?php echo ( isset($adminMenu) && $adminMenu == true ) ? 'dark' : ''; ?>.css" rel="stylesheet" type="text/css">

    <!-- Plugins -->
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/vendor/animsition/animsition.css">
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/vendor/asscrollable/asScrollable.css">
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/vendor/switchery/switchery.css">
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/vendor/intro-js/introjs.css">
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/vendor/slidepanel/slidePanel.css">
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/vendor/jquery-mmenu/jquery-mmenu.css">
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/vendor/flag-icon-css/flag-icon.css">
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/vendor/waves/waves.css">
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>css/pages/errors.css">
    
    
    <!-- Fonts -->
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/fonts/material-design/material-design.min.css">
    <link nonce="<?php echo @$nonceV; ?>"rel="stylesheet" href="<?php echo $assetsPath; ?>global/fonts/brand-icons/brand-icons.min.css">
    <link nonce="<?php echo @$nonceV; ?>"rel='stylesheet' href='<?php echo $assetsPath; ?>css/fontgoogle.css'>
    
    <!--[if lt IE 9]>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
    
    <!--[if lt IE 10]>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/media-match/media.match.min.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/respond/respond.min.js"></script>
    <![endif]-->
    
    <!-- Scripts -->
    <script nonce="<?php echo @$nonceV; ?>"src="<?php echo $assetsPath; ?>global/vendor/breakpoints/breakpoints.js"></script>
    <script id="removethis" nonce="<?php echo @$nonceV; ?>" type="text/javascript">
      var UToken = '<?php echo base64_encode(base64_encode(date('Ymd'))); ?>';
      var site_url = '<?php echo $site_url; ?>';
      var assetPath = '<?php echo $assetsPath; ?>';
      var imagepath = '<?php echo $assetsPath."images/"; ?>';
      var csspath = '<?php echo $assetsPath."css/"; ?>';
      var jspath = '<?php echo $assetsPath."js/"; ?>';
      var ServerDate = '<?php echo date("m/d/Y"); ?>';
    </script>
    <script nonce="<?php echo @$nonceV; ?>" >
      Breakpoints();
    </script>
  </head>
  <body class="animsition page-error page-error-404 layout-full">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->


    <!-- Page -->
    <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
      <div class="page-content vertical-align-middle">
          <header>
            <h1 class="animation-slide-top"><?php echo (http_response_code() == 200) ? 500 : http_response_code(); ?></h1>
            <p><?php echo (http_response_code() == 200) ? 'Internal Server Error' : 'Page Not Found !' ?></p>
          </header>
          <p class="error-advise">YOU SEEM TO BE TRYING TO FIND HIS WAY HOME</p>
          <a class="btn btn-primary btn-round" href="<?php echo $site_url; ?>">GO TO HOME PAGE</a>