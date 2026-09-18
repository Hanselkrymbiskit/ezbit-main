<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();

?>
        <?php if($CI->data['pageHeader']){ ?>
        </div>
        <?php } ?>
      </div>
      <!-- End Page Content -->
    </div>
    <!-- End Page -->

    <?php if($headerfooter){ ?>
    <!-- Footer -->
    <footer class="site-footer">
      <div nonce="<?php echo @$CI->nonceV; ?>" class="animation-slide-top mb-10 border border-left-0 border-right-0 border-bottom-0 border-secondary" data-plugin="appear" data-animate="slide-top"></div>
      <div class="site-footer-legal site-text-color  animation-slide-right" data-plugin="appear" data-animate="slide-right"><div>© <?php echo strtoupper( (@$CI->system_settings['ApplicationFooter'] <> "") ? $CI->system_settings['ApplicationFooter'] : "2020 Germel Sevilla Template"); ?>, All RIGHTS RESERVED. |<a class="ml-10 text-uppercase" href="javascript:void(0)" <?php echo @$CI->data['pvnfilelinkAction']; ?> >Privacy Notice</a></div>
        <div>
          <p class="mb-0"></p>
          <p class="mb-0"></p>
          <p class="mb-0"></p> 
        </div>
      </div>

      <div class="site-footer-right  animation-slide-left"  data-plugin="appear" data-animate="slide-left" >
        <div class="mt-10">
          <img nonce="<?php echo @$CI->nonceV; ?>" class="navbar-brand-logo h-70 mt--10 ml-10" src="<?php echo assetPath(); ?>images/transparency-seal-160x160-1.png" >
          <img nonce="<?php echo @$CI->nonceV; ?>" class="navbar-brand-logo h-70 mt--15" src="<?php echo assetPath(); ?>images/corporate-governace.png">
          <img nonce="<?php echo @$CI->nonceV; ?>" class="navbar-brand-logo h-70 mt--15" src="<?php echo assetPath(); ?>images/foi-logo-160x160-1.png">
        </div>
      </div>
    </footer>
    <?php } ?>
     <!-- Core  -->
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/popper-js/umd/popper.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/bootstrap/bootstrap.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/animsition/animsition.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/mousewheel/jquery.mousewheel.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/asscrollbar/jquery-asScrollbar.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/asscrollable/jquery-asScrollable.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/ashoverscroll/jquery-asHoverScroll.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/waves/waves.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/moment/moment.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>js/userfunction/jquery.inactivityTimeout.js"></script>
    
    <!-- Plugins -->
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/jquery-mmenu/jquery.mmenu.min.all.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/switchery/switchery.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/intro-js/intro.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/screenfull/screenfull.js"></script>
    <!-- <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/screenfull/screenfull_602.js"></script> -->
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/slidepanel/jquery-slidePanel.min.js"></script> 
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/bootbox/bootbox.all.min.js"></script>

    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/toastr/toastr.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/bootstrap-sweetalert2/dist/sweetalert2.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/nprogress/nprogress.js"></script>
   
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/matchheight/jquery.matchHeight-min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/peity/jquery.peity.min.js"></script> 
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/jquery-placeholder/jquery.placeholder.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/vendor/jquery-appear/jquery.appear.js"></script>
    <?php
    if( isset($loadjsmain) && is_array($loadjsmain) )
    {
      foreach($loadjsmain as $js_name)
      {
        echo '<script nonce="'.@$CI->nonceV.'" src="'.assetPath().$js_name.'.js'.'"></script>';
      }
    }
    ?>

    <!-- Scripts -->
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Component.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Base.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Config.js"></script>
    
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>js/Section/Menubar.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>js/Section/Sidebar.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>js/Section/PageAside.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>js/Plugin/menu.js"></script> 
    <!-- Config -->
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/config/colors.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>">Config.set('assets', '<?php echo assetPath(); ?>');</script>
    
    <!-- Page -->
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>js/Site.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin/asscrollable.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin/slidepanel.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin/switchery.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin/toastr.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin/animate-list.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin/panel.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>js/jquery.topzindex.min.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>js/jquery.blockUI.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>js/userfunction/html2canvas.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin/matchheight.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin/peity.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin/jquery-placeholder.js"></script>  
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin/material.js"></script>  
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>global/js/Plugin/jquery-appear.js"></script> 
    
    <?php
    if( isset($loadjschild) && is_array($loadjschild) )
    {
      foreach($loadjschild as $js_name)
      {
        echo '<script nonce="'.@$CI->nonceV.'" src="'.assetPath().$js_name.'.js'.'"></script>';
      }
    }
    ?>
    <script nonce="<?php echo @$CI->nonceV; ?>" src="<?php echo assetPath(); ?>js/coreutilities.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" type="text/javascript" id="removethis">
      (function(document, window, $){
        'use strict';
    
        var Site = window.Site;
        $(document).ready(function(){
          Site.run();

          loadselectreference();

          <?php if( $CI->tank_auth->is_logged_in() && $CI->usertype > 3){ ?>
          checksnapshotrequest('<?php echo $CI->userid; ?>');
          <?php } ?>
          

          document.addEventListener('hide.bs.modal', function (event) {
              if (document.activeElement) {
              document.activeElement.blur();
              }
          });
          document.addEventListener('hidden.bs.modal', function (event) {
              if (document.activeElement) {
              document.activeElement.blur();
              }
          });

          $("script[id*='removethis']").remove();

          const observer = new MutationObserver(function(mutations) {
              mutations.forEach(function(mutation) {
                  mutation.addedNodes.forEach(function(node) {
                      if (node.nodeType === 1) { 
                          if( $(node).hasAttr('style') )
                          {
                              $(node).attr('nonce','<?php echo @$CI->nonceV; ?>');
                          }
                         
                      }
                  });
              });
          });

          observer.observe(document.body, { childList: true, subtree: true });
          <?php if($this->tank_auth->is_logged_in()){ ?> 
          $(document).inactivityTimeout({ 'inactivityWait':300, dialogWait: 60, 'logoutUrl':site_url+'logout' });
          <?php } ?>
        });
      })(document, window, jQuery);
    </script>
  </body>
</html>