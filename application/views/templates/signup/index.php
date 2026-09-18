<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();
?>

<div class="signup-page">
   <div class="brand">
      <img class="brand-img h-150" src="<?php echo assetPath(); ?>images/e-zbits_logo.png" alt="...">
   </div>
   <p class="text-dark">ACCOUNT SIGN-UP</p>
   <div class="page-register-bg">
      <div class="panel" id="signupFormWizardContainer">
         <div id="signupFormWizardPanelBodyContainer" class="panel-body text-left">
            
            <!-- Steps -->
            <div class="pearls row mb-5 pb-10 border border-top-0 border-left-0 border-right-0 border-style-dashed">
               <div class="pearl current col-4">
                  <div class="pearl-icon"><i class="fa fa-key" aria-hidden="true"></i></div>
                  <span class="pearl-title text-uppercase font-size-12">Credential & Security</span>
               </div>
               <div class="pearl col-4">
                  <div class="pearl-icon"><i class="fa fa-universal-access" aria-hidden="true"></i></div>
                  <span class="pearl-title  text-uppercase  font-size-12">Classification</span>
               </div>
             
               <div class="pearl col-4">
                  <div class="pearl-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
                  <span class="pearl-title  text-uppercase  font-size-12">Profile</span>
               </div>
            </div>
            <!-- End Steps -->

            <!-- Wizard Content -->
            <form id="signupFormWizard" class="w-p100 mt-10 text-dark" novalidate="novalidate" >
               <?php if( $CI->config->item('csrf_protection') ){ ?>
               <input type="hidden" name="<?php echo @$CI->security->get_csrf_token_name(); ?>" value="<?php echo @$CI->security->get_csrf_hash(); ?>" />
               <?php } ?>
               <div class="wizard-content">
                  <?php
                     $formTabs = array(
                       'credential' => array(
                             'title'         => 'CREDENTIAL & SECURITY',
                             'targetcontent' => 'credential',
                             'description' => '',
                       ),
                       'classification' => array(
                             'title'         => 'CLASSIFICATION',
                             'targetcontent' => 'classification',
                             'description' => '',
                       ),
                       'profile' => array(
                             'title'         => 'ACCOUNT PROFILE',
                             'targetcontent' => 'profile',
                             'description' => '',
                       ),
                     );

                     foreach($formTabs as $TabKey => $TabConfig)
                     {
                        $tabFormContent = '';
                        $phpFilePath = 'views/templates/signup/signup_tabs/'.$TabConfig['targetcontent'];

                        if ( is_file(APPPATH.$phpFilePath.'.php'))
                        {
                           $tabFormContent = $CI->load->view(str_replace('views/','',$phpFilePath), $CI->data, true);
                           $tabFormContent = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $tabFormContent));
                        }

                        echo $tabFormContent;
                     }
                  ?>
               </div>
            </form>
            <!-- Wizard Content -->
         </div>
      </div>
   </div>
</div>
<footer class="page-copyright page-copyright-inverse  text-dark">
      <div class="social font-weight-bold mt-30 mb-30">
         <a class="mx-5 text-dark text-uppercase" href="<?php echo site_url('login'); ?>">Login</a>  |
         <?php if($CI->system_settings['ContactUs'] == 1){ ?>
         <a class="mx-5 text-dark text-uppercase" href="<?php echo site_url('contactus'); ?>">Contact Us</a> |
         <?php } ?>
         <a class="mx-5 text-dark text-uppercase" href="javascript:void(0)" <?php echo $CI->data['pvnfilelinkAction']; ?> >Privacy Notice</a>
      </div>
      <div class="row">
         <div class="col-lg-12"><img class="brand-img mr-20 h-80" src="<?php echo assetPath(); ?>images/brand.png" alt="..." ><img class="brand-img h-80" src="<?php echo assetPath(); ?>images/bagongpilipinas2.png" alt="..." >
         </div>
      </div> 
      <p class="text-uppercase mt-40 text-dark font-weight-bold">© <?php echo $CI->system_settings['ApplicationFooter']; ?></p>
      <a nonce="<?php echo $CI->nonceV; ?>" href="https://www.vecteezy.com/free-vector/background-design" target="_blank" class="position-left-absolute-10"><img nonce="<?php echo $CI->nonceV; ?>" src="<?php echo assetPath(); ?>images/vecteezy-logo.png" class="h-30" crossorigin="anonymous"></a>
   </footer>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
   var recaptchaid = '';
   <?php if($CI->system_settings['ReCaptchaModule'] == 1 && $CI->system_settings['ReCaptchaatRegistration']  == 1 ){ ?>
   <?php if($_SERVER['HTTP_HOST'] <> 'localhost' && $_SERVER['HTTP_HOST'] <> '127.0.0.1'){ ?>
   function onrecaptchaloaded(){
     try{
       var recpatchetoken  = '';
       grecaptcha.ready(function() {
         try{
           
           grecaptcha.execute("<?php echo $CI->system_settings['ReCaptcha_PublicKey']; ?>", {action: 'signup'}).then(function(token) {
             recaptchaid = token;
         }); 
           
         }catch(e)
         {
           console.log('Failed to retrieve security keys from google recaptcha');
         }
              
       });
     }catch(e){
       onrecaptchaloaded()
     }
   }  
   <?php } ?>
   <?php } ?>


   $(document).ready(function(){
      
      $("input[forminputgroup*='signupform']").blur(function(){
         $(this).closest("div[class*='form-group form-material']").removeClass('has-success').addClass('has-danger');
         if( $(this).hasAttr('required') )
         {
            $(this).closest("div[class*='form-group form-material']").removeClass('has-danger').addClass('has-success');
            if( !$(this).val() )
            {
               $(this).closest("div[class*='form-group form-material']").removeClass('has-success').addClass('has-danger');
            }
         }
      });

      $("input[id='psswrd'],input[id='confirmpsswrd']").blur(function(){
         if($(this).val())
         {
            var pval = parseInt($(this).val().length);
            var checkp = checkPasswordStrength($(this),parseInt($(this).attr('minlength')));
            if( $(this).strength('getStatus') == 'strong' && checkp['score'] == 3 )
            {
              $("span[id='match_"+$(this).attr("id")+"']").hide().parent().hide();
            }
            
            if( pval < parseInt($(this).attr('minlength')) || pval > parseInt($(this).attr('maxlength')) )
            {
               $(this).val('');
               $("span[id='match_"+$(this).attr("id")+"']").text($(this).attr("title")+" doesn't comply w/ min of "+parseInt($(this).attr('minlength'))+" or max "+parseInt($(this).attr('maxlength'))+" length!").show().parent().show();

               $(this).closest("div[class*='form-group form-material']").removeClass('has-success').addClass('has-danger');
            }
         }

         if( $(this).val() !== "" && $("input[id='"+( ($(this).attr('id') == 'psswrd') ? 'confirm' : '')+"psswrd']").val()  !== "" )
         {
            $("span[id='match_psswrd'],span[id='match_confirmpsswrd']").parent().hide();
            $("span[id='match_psswrd'],span[id='match_confirmpsswrd']").hide();
            if( $(this).val() !== $("input[id='"+( ($(this).attr('id') == 'psswrd') ? 'confirm' : '')+"psswrd']").val() )
            {
               $(this).val('');
               $("span[id='match_psswrd'],span[id='match_confirmpsswrd']").text("Password & Confirm Password Doesn't Match").show().parent().show();
            }
         }
      }).keyup(function(){
         var pgstats = $(this).strength('getStatus');
         var pcheckpass = checkPasswordStrength($(this),parseInt($(this).attr('minlength')));
         $("span[id='match_"+$(this).attr("id")+"']").text(pcheckpass['message']).show().parent().show();
      });

      $("input[id='eaddrs'],input[id='unme']").blur(function(){

         if($(this).attr('id') == "eaddrs" && $(this).val() )
         {
            $("span[id='exists_eaddrs']").hide().parent().hide();
            if( !validateEmail($(this).val()) )
            {
              $(this).val('');
              $("span[id='exists_eaddrs']").text('Invalid Email Address!').show().parent().show();
            }
         }

         if( $(this).val() )
         {
            var cFormData = new FormData();
            var iid = $(this).attr('id');
            var txtm = '';
            switch( $(this).attr('id') )
            {
              case "eaddrs":
                 cFormData.append('eaddrs',$(this).val());
                 purl = 'checkemailexists';
                 txtm = 'Email Address already exists!';
              break;

              case "unme":
                 cFormData.append('unme',$(this).val());
                 purl = 'checkusernameexists';
                 txtm = 'User Name already exists!';
              break;

            }

            var chkResult = function(pp,pr){
               $("span[id='exists_"+iid+"']").hide().parent().hide();
               if(pr.Status == 0)
               {
                 $("input[id='"+iid+"']").val('').blur();
                 $("span[id='exists_"+iid+"']").text(txtm).show().parent().show();
               }
            };

           submitFormData('json','',site_url+'utilities/'+purl,cFormData,chkResult,false);
         }  

      }).keyup(function(){
         if($(this).val())
         {
            $(this).val( ($(this).val()).replace(" ","") );
         }
      });

      getreferencevalue({objselect:$("select[id='user_classification'][name='user_classification']"),referenceid:'4',filter:'Code in (1,6,7)',order:''});
      getreferencevalue({objselect:$("select[id='Sex'][name='Sex']"),referenceid:'23',filter:'',order:''});
      getreferencevalue({objselect:$("select[id='Suffixname'][name='Suffixname']"),referenceid:'24',filter:'',order:''});
      getreferencevalue({objselect:$("select[id='security_question'][name='security_question']"),referenceid:'10',filter:'',order:''});

      var classGroups = {
         1:['HealthFacilityCode','Region','Province','City','Address','FacilityContactNo'],
         6:['HealthFacilityCode','Region','Province','City','Address','FacilityContactNo'],
         7:['HealthFacilityCode','Region','Province','City','Address','FacilityContactNo']
      };

      $("select[id='user_classification']").change(function(){
           
           if( $(this).val() )
           {
            $(this).closest("div[class*='form-group form-material']").removeClass('has-danger').addClass('has-success');
           } 
           else
           {
            $(this).closest("div[class*='form-group form-material']").removeClass('has-success').addClass('has-danger');
           }

           $("div[parentelement='classification']").removeClass('has-danger').addClass('has-success').hide();
           $("div[parentelement='classification']").find("input").val('').removeAttr('required');
           $("div[parentelement='classification']").find("select[id!='user_classification']").val('').attr('dfvalue','').removeAttr('required').removeAttr('disabled').change();

           for(var eids in classGroups[parseInt($(this).val())])
           {
            var eleID = classGroups[parseInt($(this).val())][eids];
            $("[id='"+eleID+"'][name='"+eleID+"'][forminputgroup='signupform']").attr('required','required').closest("div[parentelement='classification']").removeClass('has-success').addClass('has-danger').show();
           }

       });

       $("select[id='HealthFacilityCode']").select2({
         dropdownParent: $("div[id='signupFormWizardPanelBodyContainer']").parent(),
         ajax: {
            url: site_url+"signup/searchHealthFacilityList",
            dataType: 'json',
            delay: 250,
            method: "POST",
            data: function (params) {

            var ObjPostParam = {
               'q': params.term, // search term
               'page': params.page,
               'UToken':UToken,
            };

            if( csrfName !== 'ci_csrf_token' && csrfHash !=='')
            {
              ObjPostParam[csrfName] = csrfHash;
            }

            return ObjPostParam;
             
         },
         processResults: function (data, params) {

               params.page = params.page || 1;

               return {
                  results: data.results,
                  pagination: {
                    more: (params.page * 10) < data.count_filtered
                  }
               };
            },
            cache: true
         },
         placeholder: 'Search for Health Facility Name',
         minimumInputLength: 1,
         templateResult: function(repo)
         {
           if (repo.loading) {
             return repo.text;
           }

           var $container = $(
             "<div class='select2-result-repository clearfix'>" +
               "<div class='select2-result-repository__meta'>" +
                 "<div class='select2-result-repository__title font-weight-bold font-size-12'></div>" +
                 "<div class='select2-result-repository__facilitycode font-size-12 text-left' ><span class='mr-5'>INST CODE :</span> </div>" +
                 "<div class='select2-result-repository__fcontact font-size-12 text-left' ><span class='mr-5'>FACILITY CONTACT NO :</span> </div>" +
                 "<div class='select2-result-repository__addressst font-size-12 text-left' ><span class='mr-5'>ADDRESS :</span> </div>" +
                 "<div class='select2-result-repository__address font-size-12'>" +
                   "<div class='select2-result-repository__region  font-size-12 text-left' ><span class='mr-5'>REGION :</span> </div>" +
                   "<div class='select2-result-repository__province  font-size-12 text-left' ><span class='mr-5'>PROVINCE :</span> </div>" +
                   "<div class='select2-result-repository__city  font-size-12 text-left'><span class='mr-5'>CITY / MUNICIPALITY :</span> </div>" +
                 "</div>" +
               "</div>" +
             "</div>"
           );

           $container.find(".select2-result-repository__title").text(repo.facilityname);
           $container.find(".select2-result-repository__facilitycode").append(repo.facilitycode);
           $container.find(".select2-result-repository__addressst").append(repo.address);
           $container.find(".select2-result-repository__region").append(repo.region);
           $container.find(".select2-result-repository__province").append(repo.province);
           $container.find(".select2-result-repository__city").append(repo.city);
           $container.find(".select2-result-repository__fcontact").append(repo.fcontact);
           return $container;
         }
       }).change(function(){

         if($(this).val())
         {
            var getCurrentSelectedData = $("select[id='HealthFacilityCode']").select2('data');
            HealthFacilityIDData = getCurrentSelectedData[0];
            var AutoFillSelect = {
               'RegionName'            : 'region',
               'ProvinceName'          : 'province',
               'CityName'              : 'city',
               'Region'                : 'regioncode',
               'Province'              : 'provincecode',
               'City'                  : 'citycode',
               'Address'               : 'address',
               'FacilityName'          : 'facilityname',
               'FacilityContactNo'     : 'fcontact',
            };

            for(var kk in AutoFillSelect)
            {
               var sEle = $("[forminputgroup='signupform'][id='"+kk+"'][name='"+kk+"']");
               $(sEle).attr({
                  'dfvalue':HealthFacilityIDData[AutoFillSelect[kk]],
                  'value':HealthFacilityIDData[AutoFillSelect[kk]],
                  'curval':HealthFacilityIDData[AutoFillSelect[kk]],
               });

               if(HealthFacilityIDData[AutoFillSelect[kk]])
               {

                  if(  $(sEle).closest("div[parentelement='classification']").hasClass('has-danger'))
                  {
                     $(sEle).closest("div[parentelement='classification']").removeClass('has-danger');
                  }

                  $(sEle).val(HealthFacilityIDData[AutoFillSelect[kk]]); 
                  switch( $(sEle).get(0).tagName )
                  {
                     case 'SELECT':
                        $(sEle).change(); 
                     break;

                     default:
                     break;
                  }
               }
            }
         }

      });

      $("span[class*='select2 select2-container']").css({'width':'100%'});

      <?php if( @$CI->system_settings['RegistrationFileAttachment'] == 1 ) { ?>
      var fileattachedcnt = 0;
      $("input[id='proofdocument']").change(function(){   
         fileattachedcnt  = 0; 
         const fileitem = new DataTransfer();
         var filenames = [];
         var fileTypes = [
          'application/pdf'
         ];   
         for (let file of $(this)[0].files)
         {
           if( jQuery.inArray( file['type'], fileTypes ) > 0 || file['size'] < 10000000 )
           {
               fileitem.items.add(file);
               filenames.push(file['name']);
               fileattachedcnt++;
           }
         }
         
         $(this)[0].onchange = null;
         $(this)[0].files = fileitem.files;

         if( filenames.length > 0 )
         {
           $("div[id='filelist']").addClass('border border-white p-5').html(filenames.join('<br>'));
           $("input[id='proofdocumentHolder']").val(filenames.length+' File(s) Selected');
           $("label[for='proofdocument']").parent().removeClass('has-danger').addClass('has-success');
         }
         else
         {
           $("input[id='proofdocumentHolder']").val('');
           $("div[id='filelist']").removeClass('border border-white p-5').html('');
         }
      });
      <?php } ?>

      $("select[id='security_question']").change(function(){ 
         var t_sqc =  $("input[name='security_question_custom']");
         t_sqc.removeAttr('required','required');
         
         var tc_sqc = t_sqc.closest(".form-group.form-material");
         if( !tc_sqc.hasClass('d-none') )
         {
            tc_sqc.addClass('d-none');
         }

         if( parseInt($(this).val()) == 20 )
         {
            t_sqc.attr('required','required');
            tc_sqc.removeClass('d-none');
         }
      });

      var defaultswiz = Plugin.getDefaults("wizard");
      var optionswiz = $.extend(true, {}, defaultswiz, {
         onInit: function onInit() {
         },
         onBeforeChange: function (current, next) {
         },
         onStateChange: function (event, current, next) {
         },
         validator: function validator() {
            var $this = $(this); 
            var reqerror = 0;
             $("div[id='"+$this[0]['id']+"'] input[forminputgroup='signupform'], div[id='"+$this[0]['id']+"'] select[forminputgroup='signupform']").each(function(){
               var divparent = $(this).closest(".form-group.form-material");
               if( $(this).hasAttr('required') && !$(this).val() )
               {
                 reqerror++;
                 $(divparent).removeClass('has-success').addClass('has-danger');
               }
               else
               {
                  var minlth = ($(this).hasAttr('minlength')) ? parseInt($(this).attr('minlength')) : '';
                  var maxlth = ($(this).hasAttr('maxlength')) ? parseInt($(this).attr('maxlength')) : '';
                  var vv = ($(this).val()) ? ($(this).val()).length : 0;
                  
                  var divhint = $(divparent).find("div.hint");

                  if( minlth && maxlth )
                  {
                     if(vv < minlth || minlth > maxlth)
                     {
                       reqerror++;
                       $(divparent).removeClass('has-success').addClass('has-danger');
                       $(divhint).show().find("span").text('Invalid character length, minimum is '+minlth+' and maximum of '+maxlth).show();
                     }
                     else
                     {
                       $(this).closest(".form-group.form-material").removeClass('has-danger').addClass('has-success');
                       $(divhint).hide().find("span").text('').hide();
                     }
                  }
                  else
                  {
                     if(!minlth && !maxlth)
                     {
                       $(divparent).removeClass('has-danger').addClass('has-success');
                       $(divhint).hide().find("span").text('').hide();
                     }
                     else
                     {
                       mmx = (minlth) ? minlth : maxlth;
                       mmxMessage = (minlth) ? 'minimum' : 'maximum';
                       if( (mmxMessage == 'minimum' && vv < mmx) || (mmxMessage == 'maximum' && vv > mmx) )
                       {
                         reqerror++;
                         $(divparent).removeClass('has-success').addClass('has-danger');
                         $(divhint).show().find("span").text('Invalid character length, '+mmxMessage+' characted length is '+minlth).show();
                       }
                     }
                  }
               }
            });

            switch($this[0]['id'])
            {
               case 'Step1':

                     $("input[id='psswrd'],input[id='confirmpsswrd']").each(function(){
                        if($(this).val())
                        {
                           var pval = parseInt($(this).val().length);
                           var checkp = checkPasswordStrength($(this),parseInt($(this).attr('minlength')));
                           if( $(this).strength('getStatus') !== 'strong' || checkp['score'] < 3 )
                           {
                              $(this).val('');
                              $("span[id='match_"+$(this).attr("id")+"']").text("Your Password does not meet policy requirements, Enter a different password!.").show().parent().show();
                              reqerror++;
                           }
                        }
                     });

               break;

               case 'Step2':

                  if( parseInt($("select[id='user_classification']").val()) > 1 )
                  {
                     <?php if( @$CI->system_settings['RegistrationFileAttachment'] == 1 ) { ?>
                     if( fileattachedcnt == 0 )
                     {
                        $("label[for='proofdocument']").parent().removeClass('has-success').addClass('has-danger');
                        reqerror++;
                     }
                     else
                     {
                        $("label[for='proofdocument']").parent().removeClass('has-danger').addClass('has-success');
                     }
                     <?php } ?>
                  }


                  // Validate Resident Data ID


               break;

            }

            return reqerror > 0 ? false : true;
         },
         onFinish: function onFinish() {
            try{

               loader(true);

               var SignUpFormData = getFormData('signupform');
               var signupdata = SignUpFormData['FormData'];
               signupdata.append('recapctha_response',recaptchaid);
               var signupURL = site_url+'signup/submit'
               var submitResult = function(sp,rp){
                 
                 Swal.mixin({
                    customClass: {
                      confirmButton: 'btn btn-'+((rp.Status == 0) ? 'danger' : 'success'),
                    },
                    buttonsStyling: false,
                    willOpen: (fnRun) => {
                      $(".swal2-container").css('z-index',$.topZIndex());
                    }
                 }).fire({
                    title: "Account Sign-Up",
                    html: rp.Message,
                    icon: ((rp.Status == 0) ? 'error' : 'success'),
                    confirmButtonText: 'Close',
                 }).then((result) => {

                   if( rp.Status == 1 )
                   { 
                    window.location.href=site_url+'login';
                   }
                   else
                   {
                     onrecaptchaloaded();
                   }
                            
                 });

               }

               submitFormData('json','',signupURL,signupdata,submitResult,true); 

            }catch(e)
            {
               loader(false);
               Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-danger',
                  },
                  buttonsStyling: false,
                  willOpen: (fnRun) => {
                    $(".swal2-container").css('z-index',$.topZIndex());
                  }
                }).fire({
                  title: "Account Sign-Up Failed",
                  html: 'Unable to Submit SignUp Form, Please try again later!',
                  icon: "error",
                  confirmButtonText: 'Close',
                });
            }
            
         },
         buttonsAppendTo: '#signupFormWizardPanelBodyContainer'
      });
      $("div[id='signupFormWizardContainer']").wizard(optionswiz);
   });
</script>
<?php if($CI->system_settings['ReCaptchaModule'] == 1 && $CI->system_settings['ReCaptchaatRegistration']  == 1 ){ ?>
<?php if($_SERVER['HTTP_HOST'] <> 'localhost' && $_SERVER['HTTP_HOST'] <> '127.0.0.1'){ ?>
<script nonce="<?php echo $CI->nonceV; ?>" src="https://www.google.com/recaptcha/api.js?onload=onrecaptchaloaded&render=<?php echo $CI->system_settings['ReCaptcha_PublicKey']; ?>" sync defer></script>
<?php } ?>
<?php } ?>