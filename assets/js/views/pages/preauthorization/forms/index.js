let frmViewMode = $("script[src*='views/pages/preauthorization/forms/index.js']").attr('data-viewmode');
function WizardonFinish(forcompliance){ 
  var forcompliance = typeof x == 'boolean' ? forcompliance : false; 
  try{

    var PreAuthFormData = getFormData('preauthFormData');
    var PreAuthData = PreAuthFormData['FormData'];

    if( Object.keys(PreAuthFormData['ErrorData']).length > 0)
    {
      var edData = {};
      if( Object.keys(PreAuthFormData['ErrorData']).length > 0 )
      {
        var eObj=PreAuthFormData['ErrorData'];
        for( var elementid in eObj)
        {    
          var divparent = $("[id='"+elementid+"'][forminputgroup='preauthFormData']").closest(".form-group.form-material");
          var divtitle = $("[id='"+elementid+"'][forminputgroup='preauthFormData']").closest(".tab-pane");
        
          edData[elementid] = divtitle.attr('title') + ' > ' + eObj[elementid];
          if(!$("[id='"+elementid+"'][forminputgroup='preauthFormData']").prop('disabled'))
          {
            $(divparent).removeClass('has-success').addClass('has-danger');
          }
        }
      }
      popFormDataError(edData,'PRE-AUTHORIZATION');
    }
    else
    {
    
      var apiPath = site_url+'preauthorization/index/submitform';
      var apiResult = function(pp,rp){

        Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-dark w-p45 mr-2',
            cancelButton: 'btn btn-dark w-p45 ',
          },
          buttonsStyling: false,
          willOpen: (fnRun) => {
            $(".swal2-container").css('z-index',$.topZIndex());
          }
        }).fire({
          title: $(".page-title").text().replace('Pre-Auth : ',''),
          html: rp.Message,
          icon: (rp.Status == 1) ? 'success' : 'error',
          confirmButtonText: '<i class="fa fa-times fa-fw mr-10"></i>CLOSE',
          showCancelButton: false,
          cancelButtonText:'<i class="fa fa-times fa-fw mr-10"></i>CLOSE',
          reverseButtons:false,
          allowOutsideClick:false,
          allowEscapeKey:false
        }).then((result) => {
          
          if(rp.Status == 1)
          {
            window.location.href=site_url+'preauthorization';
          }

        });
      }

      Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-dark w-p45 mr-2',
          cancelButton: 'btn btn-dark w-p45 ',
        },
        buttonsStyling: false,
        willOpen: (fnRun) => {
          $(".swal2-container").css('z-index',$.topZIndex());
        }
      }).fire({
        title: "PRE-AUTHORIZATION",
        html: '<p>I consent to the examination by PhilHealth of my medical records for the sole purpose of verifying the veracity of the Z-claim </p>',
        icon: 'info',
        confirmButtonText: '<i class="fa fa-fw fa-thumbs-up mr-10"></i>YES',
        showCancelButton:true,
        cancelButtonText:'<i class="fa fa-fw fa-thumbs-down mr-10"></i>NO',
        reverseButtons:false,
        allowOutsideClick:false,
        allowEscapeKey:false,
      }).then((result) => {
        
        if (result.value) 
        {
          Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-dark w-p45 mr-2',
              cancelButton: 'btn btn-dark w-p45 ',
            },
            buttonsStyling: false,
            willOpen: (fnRun) => {
              $(".swal2-container").css('z-index',$.topZIndex());
            }
          }).fire({
            title: "PRE-AUTHORIZATION",
            html: '<p>I consent to have my medical data entered electronically in the ZBITS as a requirement for the Z Benefits. I authorize PhilHealth to disclose my personal health information to its contracted partners </p>',
            icon: 'info',
            confirmButtonText: '<i class="fa fa-fw fa-thumbs-up mr-10"></i>YES',
            showCancelButton:true,
            cancelButtonText:'<i class="fa fa-fw fa-thumbs-down mr-10"></i>NO',
            reverseButtons:false,
            allowOutsideClick:false,
            allowEscapeKey:false
          }).then((result) => {
            
            if (result.value) 
            {
              Swal.mixin({
                customClass: {
                  confirmButton: 'btn btn-dark w-p45 mr-2',
                  cancelButton: 'btn btn-dark w-p45 ',
                },
                buttonsStyling: false,
                willOpen: (fnRun) => {
                  $(".swal2-container").css('z-index',$.topZIndex());
                }
              }).fire({
                title: "PRE-AUTHORIZATION",
                html: 'Please be reminded that any modification cannot be made after submitting this Pre-Authorization Form, '+( (forcompliance) ? 'Proceed Re-Submission?' : 'Proceed Submission?'),
                icon: 'question',
                confirmButtonText: '<i class="fa fa-fw fa-send-o mr-10"></i>YES',
                showCancelButton:true,
                cancelButtonText:'<i class="fa fa-fw fa-times mr-10"></i>NO',
                reverseButtons:false,
                allowOutsideClick:false,
                allowEscapeKey:false
              }).then((result) => {
                
                if (result.value) 
                {
                  submitFormData('json','',apiPath,PreAuthData,apiResult,true,true,0);
                }

              });
            }

          });
        }

      });

    }
    

  }catch(e){
   
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
        title: "PRE-AUTHORIZATION",
        html: 'Unable to Submit Pre-Authorization Form, Please try again later!',
        icon: "error",
        confirmButtonText: 'Close',
      });
  }


}
function WizardonCancel(){ window.location.href = site_url+'preauthorization'; }
