let frmViewMode = $("script[src*='views/pages/claims/forms/index.js']").attr('data-viewmode');
function WizardonFinish(forcompliance){ 
  var forcompliance = typeof x == 'boolean' ? forcompliance : false; 
  try{

    var ClaimFormData = getFormData('claimFormData');
    var ClaimData = ClaimFormData['FormData'];

    if( Object.keys(ClaimFormData['ErrorData']).length > 0)
    {
      var edData = {};
      if( Object.keys(ClaimFormData['ErrorData']).length > 0 )
      {
        var eObj=ClaimFormData['ErrorData'];
        for( var elementid in eObj)
        {    
          var divparent = $("[id='"+elementid+"'][forminputgroup='claimFormData']").closest(".form-group.form-material");
          var divtitle = $("[id='"+elementid+"'][forminputgroup='claimFormData']").closest(".tab-pane");
        
          edData[elementid] = divtitle.attr('title') + ' > ' + eObj[elementid];
          if(!$("[id='"+elementid+"'][forminputgroup='claimFormData']").prop('disabled'))
          {
            $(divparent).removeClass('has-success').addClass('has-danger');
          }
        }
      }
      popFormDataError(edData,'Z-Benefit Claims');
    }
    else
    {
    
      var apiPath = site_url+'claims/index/submitform';
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
          title: $(".page-title").text().replace('Z - Benefit Claims : ',''),
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
            window.location.href=site_url+'claims';
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
        title: "Z - Benefit Claims",
        html: '<p>I consent to the examination by PhilHealth of my medical records for the sole purpose of verifying the veracity of the Z-claim </p>',
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
            title: "Z - Benefit Claims",
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
                title: "Z - Benefit Claims",
                html: 'Please be reminded that any modification cannot be made after submitting this Z-Benefit Claim Form, '+( (forcompliance) ? 'Proceed Re-Submission?' : 'Proceed Submission?'),
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
                  submitFormData('json','',apiPath,ClaimData,apiResult,true,true,0);
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
        title: "Z - Benefit Claims",
        html: 'Unable to Submit Z-Benefit Claim Form, Please try again later!',
        icon: "error",
        confirmButtonText: 'Close',
      });
  }


}
function WizardonCancel(){ window.location.href = site_url+'claims'; }
