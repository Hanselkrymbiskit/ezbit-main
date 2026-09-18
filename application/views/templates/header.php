<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();

$addedClass = [];
$addedBodyClass = '';
$addedPageClass = '';
// $CI->data['pageHeader'] = true;
$addedPageContentClass = 'container-fluid';
$CI->userprofile['CompleteName'] = ( @$CI->userprofile['CompleteName'] == '') ? '&nbsp;' : $CI->userprofile['CompleteName'];
if( isset($loadcss) && is_array($loadcss) )
{
  foreach($loadcss as $css_name)
  {
    $addedClass[] = '<link nonce="'.@$CI->nonceV.'" rel="stylesheet" href="'.assetPath().$css_name.'.css" type="text/css" >';
  }
}
$userclassname = ($CI->usertype < 4) ? strtoupper($CI->myutilities->getRef_Desc(2,$CI->usertype)) : strtoupper(@$CI->myutilities->getRef_Desc(4,$CI->userprofile['user_classification']));
$userSpecificName = '';

if( $CI->usertype > 3 )
{
  switch( (int) $CI->userprofile['user_classification'] )
  {    
    case 1:
    case 6:
    case 7:
      $userSpecificName = @$CI->userregistrationinfo['FacilityName'];
      $userSpecificName2 = @$CI->userregistrationinfo['Address'];
      $userSpecificName3 = @$CI->userregistrationinfo['FacilityContactNo'];
    break;
    
    case 2:
    case 3:
      $userSpecificName = $this->username;
      $userSpecificName2 = strtoupper($CI->myutilities->getRef_Desc(204,str_pad((int) $CI->userregistrationinfo['ProCode'],2,'0',STR_PAD_LEFT)));
      $userSpecificName3 = '';
    break;

    case 4:
    case 5:
      $userSpecificName = $this->username;
      $userSpecificName2 = 'Central Office';
      $userSpecificName3 = '';
    break;
    default:
    break;
  }

}
else
{
  $userSpecificName = $this->username;
  $userSpecificName2 = '';
  $userSpecificName3 = '';
}

$BlobContent = file_get_contents(FCPATH.'/assets/PrivacyNotice.pdf');
$fla = [
 'FileName' => 'Privacy Notice',
 'FileType' => 'application/pdf',
 'FileData' => base64_encode($BlobContent),
];

$pvnfilelinkAction = 'onclick="javascript:ObjectFileViewer(\''.base64_encode(json_encode($fla)).'\')" ';
$CI->data['pvnfilelinkAction'] = @$pvnfilelinkAction;

?>
<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="text/html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <title><?php echo @$CI->system_settings['ApplicationAbbre']; ?></title>
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="apple-touch-icon" href="<?php echo assetPath(); ?>images/apple-touch-icon.png">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="shortcut icon" href="<?php echo assetPath(); ?>images/favicon.ico">
    <!-- Stylesheets -->
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/css/bootstrap.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/css/bootstrap-extend.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/datatables/datatables.min.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/datatables/FixedColumns-3.3.1/css/fixedColumns.bootstrap.min.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>css/dataTables.checkboxes.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>css/site.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" href="<?php echo assetPath(); ?>skins/green<?php echo ( isset($adminMenu) && $adminMenu == true ) ? 'dark' : 'dark'; ?>.css" rel="stylesheet" type="text/css">
    <!-- Plugins -->
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/animsition/animsition.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/asscrollable/asScrollable.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/switchery/switchery.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/intro-js/introjs.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/slidepanel/slidePanel.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/jquery-mmenu/jquery-mmenu.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/flag-icon-css/flag-icon.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/waves/waves.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/bootstrap-sweetalert2/dist/sweetalert2.min.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/bootstrap-sweetalert2/themes/borderless.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/nprogress/nprogress.min.css">  
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/vendor/toastr/toastr.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>css/scrollbarstyle.css">
    <?php 
      $pagetype = (is_null(@$pagetype)) ? '' : $pagetype;
      switch( strtolower(@$pagetype) )
      {
        case "login":
        case "contactus":

          $CI->data['pageHeader'] = false;
          $addedClass[] = '<link nonce="'.@$CI->nonceV.'" rel="stylesheet" href="'.assetPath().'css/login.css">';
          $addedBodyClass = 'page-login-v2 layout-full page-dark';
          $addedPageClass = ''; //'vertical-align text-center';
          $addedPageContentClass = ''; // 'vertical-align-middle';

          $addedBodyClass = 'page-login layout-full page-dark';
          $addedPageClass = 'vertical-align text-center h-v100 top-0';
          $addedPageContentClass = 'vertical-align-middle';
        break;

        case "resetpassword":
        case "signup":
          $CI->data['pageHeader'] = false;
          $addedClass[] = '<link nonce="'.@$CI->nonceV.'" rel="stylesheet" href="'.assetPath().'css/register.css">';
          $addedBodyClass = 'page-register layout-full page-dark';
          $addedPageClass = 'vertical-align text-center';
          $addedPageContentClass = 'vertical-align-middle';
        break;
        case "activate":
          $CI->data['pageHeader'] = false;
          $addedClass[] = '<link nonce="'.@$CI->nonceV.'" rel="stylesheet" href="'.assetPath().'css/register.css">';
          $addedBodyClass = 'page-register layout-full page-dark';
          $addedPageClass = 'noheader vertical-align text-center';
          $addedPageContentClass = 'vertical-align-middle';

        break;
      }

      echo (@$addedClass <> '' && count($addedClass) > 0 ) ? implode("",@$addedClass) : ''; 
 
    ?>
    <!-- Fonts -->
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/fonts/material-design/material-design.min.css">
    <!-- Icons -->
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/fonts/web-icons/web-icons.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/fonts/font-awesome/6.7.2/css/all.min.css">

    <!-- <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/fonts/font-awesome/4.7.0/css/font-awesome.css"> -->
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>global/fonts/brand-icons/brand-icons.min.css">
    <link nonce="<?php echo @$CI->nonceV; ?>" rel='stylesheet' href='<?php echo assetPath(); ?>css/fontgoogle.css'>
    <link nonce="<?php echo @$CI->nonceV; ?>" rel="stylesheet" href="<?php echo assetPath(); ?>css/coresite.css">
    <!--[if lt IE 9]>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
    
    <!--[if lt IE 10]>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/media-match/media.match.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/respond/respond.min.js"></script>
    <![endif]-->
    
    <!-- Scripts -->
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/jquery/jquery.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/breakpoints/breakpoints.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" id="removethis" type="text/javascript">
      <?php if( $CI->config->item('csrf_protection') ){ ?>
      var csrfName = '<?php echo @$CI->security->get_csrf_token_name(); ?>';
      var csrfHash = '<?php echo @$CI->security->get_csrf_hash(); ?>';
      <?php } ?>
      var UToken = '<?php echo base64_encode($CI->m_api->encryptdecryptString('encrypt',$CI->system_settings['PasswordHashing'],date('Ymd'),'MCrypt','aes-128','ecb')); ?>';
      var site_url = '<?php echo site_url().( (php_sapi_name() === 'cli') ? "/" : "" ); ?>';
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
      var SysName = '<?php echo @$CI->system_settings['ApplicationAbbre']; ?>';
      var activeAjaxConnections = 0;
      var dtFlds = '<?php echo $CI->csrfToken; ?>'; 
    </script>
    <script nonce="<?php echo @$CI->nonceV; ?>" >
      Breakpoints();
    </script>
  </head>
  <body nonce="<?php echo @$CI->nonceV; ?>" id="siteBody" class="siteBody animsition <?php echo @$addedBodyClass.((@$CI->data['pageHeader']) ? '' : ' pt-0'); ?> scrollbar-dark thin">
  <?php if($headerfooter){ ?>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <nav class="site-navbar navbar navbar-default navbar-inverse navbar-fixed-top navbar-mega site-navbartop" role="navigation" >

      <div class="navbar-header hidden-sm-up ">
        <?php if($this->tank_auth->is_logged_in()){ ?>
        <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
          data-toggle="menubar">
          <span class="sr-only">Toggle navigation</span>
          <span class="hamburger-bar"></span>
        </button>
        <?php } ?>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
          data-toggle="collapse">
          <i class="icon md-more" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center hidden-sm-up <?php echo ($this->tank_auth->is_logged_in()) ? 'site-gridmenu-toggle' : ''; ?>" data-toggle="gridmenu">
          <img class="navbar-brand-logo h-40 mt--10 ml--5" src="<?php echo assetPath(); ?>images/Z_logo.png" >
        </div>
      </div>
    
      <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
          
          <!-- Navbar Toolbar -->
          <ul class="nav navbar-toolbar text-uppercase">
            <li class="nav-item hidden-float" id="toggleMenubar">
              <a class="nav-link text-white pt-15 pb-15" data-toggle="menubar" href="#" role="button">
                <i class="icon hamburger hamburger-arrow-left">
                  <span class="sr-only">Toggle menubar</span>
                  <span class="hamburger-bar"></span>
                </i>
              </a>
            </li>
            <li class="nav-item hidden-sm-down" id="toggleFullscreen">
              <a class="nav-link fa fa-home pt-15 pb-15 text-white" href="<?php echo site_url(''); ?>"  role="button" data-placement="bottom" data-toggle="tooltip" data-original-title="Home">
                <span class="sr-only">Home</span>
              </a>
            </li>
            <li class="nav-item hidden-sm-down" id="toggleFullscreen">
              <a class="nav-link icon icon-fullscreen pt-15 pb-15  text-white"  onclick="screenfull.toggle();" role="button" data-placement="bottom" data-toggle="tooltip" data-original-title="Toggle Fullscreen">
                <span class="sr-only">Toggle fullscreen</span>
              </a>
            </li>
         
            <?php if($this->tank_auth->is_logged_in()){ ?>
              <?php if(  (int) $CI->usertype <= 2 && @$adminMenu == false ){ ?>
            <li class="nav-item hidden-sm-down" id="Administrator">
              <a class="nav-link fa fa-gear pt-15 pb-15  text-white" href="<?php echo site_url('administrator'); ?>" role="button" data-placement="bottom" data-toggle="tooltip" data-original-title="Administrator Panel">
                <span class="sr-only">Administrator Panel</span>
              </a>
            </li>
              <?php } ?>
            <?php } ?>
          </ul>
          <!-- End Navbar Toolbar -->
          <?php if($this->tank_auth->is_logged_in()){ ?>
          <!-- Navbar Toolbar Right -->
          <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
           
            <?php if($this->tank_auth->is_logged_in()){ ?>  
            <li class="nav-item hidden-sm-down">
              <div class="nav-user-display float-left" >
                <span class="">
                  <div class="text-uppercase text-white nud-complete-name"><?php echo @$CI->userprofile['CompleteName']; ?></div>
                  <div class="nud-classification"><?php echo@$CI->userprofile['emailaddress']; ?></div>
                  <div class="nud-specific-name1"><?php echo @$userclassname; ?></div>
                </span> 
              </div>         
              <div class="nav-user-display float-right border border-left border-top-0 border-right-0 border-bottom-0 border-style-dashed border-warning pl-10">
                <span class="">
                  <div class="nud-specific-name1"><?php echo @$userSpecificName; ?></div>
                  <div class="nud-specific-name2"><?php echo @$userSpecificName2; ?></div>
                  <div class="nud-specific-name3"><?php echo @$userSpecificName3; ?></div>
                </span> 
              </div>           
            </li>
            <?php } ?>
            <li class="nav-item dropdown">
              <a class="nav-link navbar-avatar pt-10 pb-10" data-toggle="dropdown" href="#" aria-expanded="false"
                data-animation="scale-up" role="button">
                <span class="avatar <?php echo ($this->tank_auth->is_logged_in()) ? 'avatar-online' : ''; ?>">
                  <img class="h-30 w-30" src="<?php echo @$CI->UserProfilePic; ?>" onerror="this.onerror=null;this.src='<?php echo assetPath(); ?>images/profilepic/guestuser2.png';" alt="...">
                  <i></i>
                </span>
              </a>
              <div class="dropdown-menu" role="menu">
                <?php if($this->tank_auth->is_logged_in()){ ?>  
                <a class="dropdown-item" href="<?php echo site_url('myaccount/profile'); ?>" role="menuitem"><i class="fa fa-fw fa-id-badge mr-5" aria-hidden="true"></i> Profile</a>
                <?php if( @$CI->usertype > 3 || @$CI->usertype < 3){ ?>
                <a class="dropdown-item" id="viewAPIAccess" api_ws="<?php echo @$CI->userprofile['User_WS_Key']; ?>" api_e="<?php echo ($CI->usertype > 3) ? @$CI->userprofile['User_E_Key'] : ''; ?>" role="menuitem"><i class="fa  fa-fw fa-key mr-5" aria-hidden="true"></i> API Auth</a>
                <?php } ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" id="system_logout" cname="<?php echo @$CI->userprofile['CompleteName']; ?>" role="menuitem"><i class="icon md-power" aria-hidden="true"></i> Sign-Out</a>
                <?php }else{ ?>
                <a class="dropdown-item" href="<?php echo site_url('login'); ?>"  role="menuitem"><i class="icon md-sign-in" aria-hidden="true"></i> Login</a>
                <a class="dropdown-item" href="<?php echo site_url('signup'); ?>" role="menuitem"><i class="icon md-pin-account" aria-hidden="true"></i> Register</a>
                <?php } ?>
              </div>
            </li>
          </ul>
          <!-- End Navbar Toolbar Right -->
          <?php } ?>
        </div>
        <!-- End Navbar Collapse -->
    
        <!-- Site Navbar Seach -->
        
        <!-- End Site Navbar Seach -->
      </div>
    </nav> 
    <nav class="site-navbar navbar navbar-default navbar-inverse navbar-fixed-top navbar-mega hidden-sm-down h-80 template-sitenav bg-white" role="navigation">
      <div class="navbar-container mt-10 ml-20 mr-20 w-p100">
        <div  class="float-left">
          <img class="navbar-brand-logo h-70 w-250" src="<?php echo assetPath(); ?>images/brand.png">
          <img class="navbar-brand-logo h-70 w-80 ml-20" src="<?php echo assetPath(); ?>images/bagongpilipinas2.png">
        </div>
        <div class="float-right ">
          <img class="navbar-brand-logo mt--10 mr--20 h-80 w-1000" src="<?php echo assetPath(); ?>images/e-zbits3.png">
        </div>
      </div>
      <?php if($this->tank_auth->is_logged_in()){ ?>
      <div class="breadcrumb_container">
        <div class="breadcrumb_container_sub pt-5 pr-30 pl-30 pb-0">
        <?php echo ( ($breadcrumbs = @$CI->breadcrumbs->show()) <> '' ) ? @$breadcrumbs : '&nbsp;'; ?>

        </div>
      </div>
      <?php } ?>
      <?php if($CI->data['pageHeader']){ ?>
      <!-- Page Header -->
      <div class="page-header pt-10">
        <?php echo (@$PageTitle <> "") ? '<h1 class="page-title text-uppercase" >'.@$PageTitle.'</h1>' : ''; ?>
        <?php if(@$pageheaderaction <> ""){ ?>
        <div class="page-header-actions">
          <?php echo @$pageheaderaction; ?>
        </div>
        <?php } ?>
      </div>
      <!-- End Page Header -->
      <?php } ?>
    </nav>   
    
    <?php if($this->tank_auth->is_logged_in()){ ?>
    <div class="site-menubar">
      <div class="site-menubar-body">
        <div>
          <div>
          <?php echo trim(str_replace(array("\n\r","\n","\r"),'',$CI->load->view('templates/menu', $CI->data, true))); ?>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>

  <?php } ?>
    <!-- Page -->

    <div id="MainPageDiv" nonce="<?php echo @$CI->nonceV; ?>" class="page <?php echo @$addedPageClass; ?> " data-animsition-in="fade-in" data-animsition-out="fade-out" >
      <!-- Page Content -->
      <div class="page-content <?php echo @$addedPageContentClass; ?> ">
          <?php if($CI->data['pageHeader']){ ?>
          <div class="" >
          <?php } ?>
      
    

        
