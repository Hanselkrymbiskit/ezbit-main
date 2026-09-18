
<?php //
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();
?>
  <?php if(@$headerfooter){ ?>   

  </div>
        <!-- End of Page Content -->

      </div>
      <!-- End of Main Content -->
 
      <!-- Footer -->
      <footer class="sticky-footer bg-white" style="    padding: 1rem 0rem !important;    height: 6.7vh;    background: none !important;margin-left:17vw">
         <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; <?php echo @$CI->system_settings['ApplicationFooter']; ?></span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

 <?php }else{ ?>

 </div> 
 <?php } ?>

 <?php
  if(isset($loadjsfooter) && $loadjsfooter <> '')
  {
    if(is_array($loadjsfooter))
    {
      foreach($loadjsfooter as $js_name)
      {
        echo '<script src="'.getjsPath().$js_name.'.js'.'" type="text/javascript"></script>';
      }
    }
    else
    {
      echo '<script src="'.getjsPath().$loadjsfooter.'.js'.'" type="text/javascript"></script>';
    }
  }
  ?>

  <script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript"> 
    $(document).ready(function(){
      
      $("[dttype]").each(function(){
        DateInputMask($(this));
      });

      // $("[title]").tooltip({'placement':'bottom','fallbackPlacement':'flip',delay: { "show": 500, "hide": 100 }});
      $("script[id*='removethis']").remove();
    });
  </script>

  <!-- Page level plugins -->
</body>

</html>