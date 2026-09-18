
			<footer class="page-copyright">
			  <p>PHILIPPINE HEALTH INSURANCE CORPORATION</p>
			  <p>All RIGHT RESERVED.</p>
			</footer>
		</div>
    </div>
    <!-- End Page -->


    <!-- Core  -->
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/jquery/jquery.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/popper-js/umd/popper.min.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/bootstrap/bootstrap.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/animsition/animsition.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/mousewheel/jquery.mousewheel.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/asscrollable/jquery-asScrollable.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/waves/waves.js"></script>
    
    <!-- Plugins -->
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/jquery-mmenu/jquery.mmenu.min.all.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/switchery/switchery.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/intro-js/intro.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/screenfull/screenfull.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/vendor/slidepanel/jquery-slidePanel.js"></script>
    
    <!-- Scripts -->
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/js/Component.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/js/Plugin.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/js/Base.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/js/Config.js"></script>
    
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>js/Section/Menubar.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>js/Section/Sidebar.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>js/Section/PageAside.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>js/Section/GridMenu.js"></script>
    
    <!-- Config -->
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/js/config/colors.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>js/config/tour.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" >Config.set('assets', '<?php echo $assetsPath; ?>');</script>
    
    <!-- Page -->
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>js/Site.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/js/Plugin/asscrollable.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/js/Plugin/slidepanel.js"></script>
    <script nonce="<?php echo @$nonceV; ?>" src="<?php echo $assetsPath; ?>global/js/Plugin/switchery.js"></script>
    
    <script nonce="<?php echo @$nonceV; ?>" >
      (function(document, window, $){
        'use strict';
    
        var Site = window.Site;
        $(document).ready(function(){
          Site.run();
        });
      })(document, window, jQuery);
    </script>
    
  </body>
</html>
<?php if(@$phperror_){ die(); } ?>