      </div>
      <!-- End Page Content -->
    </div>
    <!-- End Page -->

    <?php if($headerfooter){ ?>
    <!-- Footer -->
    <footer class="site-footer">
      <div class="site-footer-legal">© <?php echo strtoupper( (@$CI->system_settings['ApplicationFooter'] <> "") ? $CI->system_settings['ApplicationFooter'] : "2020 Germel Sevilla Template"); ?>, All RIGHT RESERVED</div>
      <div class="site-footer-right">
        PHILIPPINE HEALTH INSURANCE CORPORATION
      </div>
    </footer>
    <?php } ?>
     <!-- Core  -->
    <script src="<?php echo assetPath(); ?>global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/popper-js/umd/popper.min.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/animsition/animsition.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/mousewheel/jquery.mousewheel.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/asscrollable/jquery-asScrollable.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/waves/waves.js"></script>
    
    <!-- Plugins -->
    <script src="<?php echo assetPath(); ?>global/vendor/jquery-mmenu/jquery.mmenu.min.all.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/switchery/switchery.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/intro-js/intro.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/screenfull/screenfull.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/slidepanel/jquery-slidePanel.js"></script> 
    <script src="<?php echo assetPath(); ?>global/vendor/bootbox/bootbox.all.min.js"></script>

    <script src="<?php echo assetPath(); ?>global/vendor/toastr/toastr.js"></script>
    <!-- <script src="<?php echo assetPath(); ?>global/vendor/bootstrap-sweetalert/sweetalert.js"></script> -->
    <script src="<?php echo assetPath(); ?>global/vendor/bootstrap-sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/nprogress/nprogress.js"></script>
   
    <script src="<?php echo assetPath(); ?>global/vendor/matchheight/jquery.matchHeight-min.js"></script>
    <script src="<?php echo assetPath(); ?>global/vendor/peity/jquery.peity.min.js"></script> 
    <script src="<?php echo assetPath(); ?>global/vendor/jquery-placeholder/jquery.placeholder.js"></script>
    <?php
    if( isset($loadjsmain) && is_array($loadjsmain) )
    {
      foreach($loadjsmain as $js_name)
      {
        echo '<script src="'.assetPath().$js_name.'.js'.'"></script>';
      }
    }
    ?>

    <!-- Scripts -->
    <script src="<?php echo assetPath(); ?>global/js/Component.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Plugin.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Base.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Config.js"></script>
    
    <script src="<?php echo assetPath(); ?>js/Section/Menubar.js"></script>
    <script src="<?php echo assetPath(); ?>js/Section/Sidebar.js"></script>
    <script src="<?php echo assetPath(); ?>js/Section/PageAside.js"></script>
    <script src="<?php echo assetPath(); ?>js/Section/GridMenu.js"></script>
    
    <!-- Config -->
    <script src="<?php echo assetPath(); ?>global/js/config/colors.js"></script>
    <script src="<?php echo assetPath(); ?>js/config/tour.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" >Config.set('assets', '<?php echo assetPath(); ?>');</script>
    
    <!-- Page -->
    <script src="<?php echo assetPath(); ?>js/Site.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Plugin/asscrollable.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Plugin/slidepanel.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Plugin/switchery.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Plugin/toastr.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Plugin/animate-list.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Plugin/panel.js"></script>
    <script src="<?php echo assetPath(); ?>js/jquery.topzindex.min.js"></script>
    <script src="<?php echo assetPath(); ?>js/jquery.blockUI.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Plugin/matchheight.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Plugin/peity.js"></script>
    <script src="<?php echo assetPath(); ?>global/js/Plugin/material.js"></script>  
    <?php
    if( isset($loadjschild) && is_array($loadjschild) )
    {
      foreach($loadjschild as $js_name)
      {
        echo '<script src="'.assetPath().$js_name.'.js'.'"></script>';
      }
    }
    ?>
    
    
    <script src="<?php echo assetPath(); ?>js/coreutilities.js"></script>
    <script nonce="<?php echo @$CI->nonceV; ?>" >
      (function(document, window, $){
        'use strict';
    
        var Site = window.Site;
        $(document).ready(function(){
          Site.run();

          loadselectreference();

          $("div.site-menubar-footer").remove();
          $("div[class='site-menubar mm-menu mm-hasnavbar-bottom-1']").css('height','calc(100% - 60.01px)');

        });
      })(document, window, jQuery);

       toastr.options = {
          "closeButton": true,
          "debug": false,
          "newestOnTop": true,
          "progressBar": false,
          "positionClass": "toast-bottom-right",
          "preventDuplicates": true,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "300",
          "timeOut": "3000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }

    </script>
  </body>
</html>