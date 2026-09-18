function receivedPreAuth(patitle,case_no,ecase_no)
{
  if(case_no && ecase_no)
  {
    var ihtml = `
                <h3 class="text-primary">`+patitle+`</h3>
                <div class="p-10 border border-success">
                  <h4 class="text-center">CASE NO</h4>
                  <h2 class="text-center">`+case_no+`</h2>
                </div>`;

    var rPreAuth = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success text-white mr-5 w-p45',
          cancelButton: 'btn btn-success text-white  w-p45',
          input: 'form-control'
        },
        buttonsStyling: false,
        willOpen: (fnRun) => {
          $(".swal2-container").css('z-index',$.topZIndex());
        }
    }).fire({
        title: 'Receive Pre-Authorization',
        icon:'question',
        html: ihtml,
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-fw fa-check mr-10"></i>RECEIVE',
        cancelButtonText:'<i class="fa fa-fw fa-times mr-10"></i>CANCEL',
        showLoaderOnConfirm: true,
        reverseButtons: false,
        allowOutsideClick: () => !Swal.isLoading(),
        allowEscapeKey:() => !Swal.isLoading(),
    }).then((result) => {
        if (result.value)
        {
          // toastr.success("New Pre-Authorization","Redirecting to Pre-Authorization Form!");
          PreAuthData = new FormData();
          PreAuthData.append('case_no', ecase_no);
          var apiPath = site_url+'preauthorization/index/receivedPreAuth';
          var apiResult = function(pp,rp){

            if(rp.Status == 1)
            {
              toastr.success("Pre-Authorization","Pre-Auth Case No : "+case_no+" Successfully received!");
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
              title: $(".page-title").text().replace('Pre-Authorization : ',''),
              html: rp.Message,
              icon: (rp.Status == 1) ? 'success' : 'error',
              confirmButtonText: '<i class="fa fa-times fa-fw mr-10"></i>CLOSE',
              reverseButtons:false,
              allowOutsideClick:false,
              allowEscapeKey:false
            }).then((result) => {
              
              if(rp.Status == 1)
              {
                window.location.reload();
              }

            });
          }

          submitFormData('json','',apiPath,PreAuthData,apiResult,true,true,0);


        }
    });
  }

  
}

function statusPreAuth(patitle,case_no,ecase_no,astatus)
{
  if(case_no && ecase_no)
  {
    var btn_cl = '', btn_txt = '', eremarks = false;
    switch(parseInt(astatus))
    {
      case 4:
        btn_cl = 'warning text-dark'; 
        btn_txt = 'FOR COMPLIANCE';
        eremarks = true;
      break;

      case 6:
        btn_cl = 'success';
        btn_txt = 'APPROVE';
      break;

      case 5:
        btn_cl = 'danger';
        btn_txt = 'DISAPPROVE';
        eremarks = true;
      break;
    }

    remarkshtml = `
      <div>
        <h6 class="mb-0 mt-10 text-center font-weight-bold font-size-16 text-primary">`+btn_txt+` REMARKS</h6>
        <div class="form-group form-material mt-0 mb-0 h-400 w-800 text-left" data-plugin="formMaterial">
            <div id="ad_remarks" groupdiv="ad_remarks"></div>
            <textarea id="ad_remarks" name="ad_remarks" class="form-control" forminputgroup="adFormData" forminputgroupcheck="adFormData" style="display:none"></textarea>
        </div>
      </div>

    `;

    var ihtml = `
                <div class="p-5 border border-success">
                  <h4 class="text-center mt-0 mb-5">CASE NO</h4>
                  <h2 class="text-center mt-0 mb-0">`+case_no+`</h2>
                </div>
                <div>
                `+((eremarks) ? remarkshtml : '');

    var rPreAuth = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-'+btn_cl+'  mr-5 w-p45',
          cancelButton: 'btn btn-dark text-white  w-p45',
          input: 'form-control'
        },
        buttonsStyling: false,
        willOpen: (fnRun) => {
          $(".swal2-container").css('z-index',$.topZIndex());

          if(eremarks)
          {
            $("div[groupdiv='ad_remarks']").summernote({
                placeholder: '',
                tabsize: 2,
                dialogsInBody:false,
                height: '330px',
                tabDisable:false,
                codeviewFilter: true,
                codeviewIframeFilter: true,
                disableResizeEditor: true,
                disableResizeImage: true,
                toolbar: [
                    // ['font',['fontsize','fontsizeunit']],
                    ['style', ['bold','italic','underline']],
                    ['style2', ['strikethrough','superscript','subscript','clear']],
                    ['color',['color','forecolor','backcolor']],
                    ['para', ['ul', 'ol', 'height']],
                    // ['insert', ['table']],
                    ['misc', ['undo', 'redo']]
                ],
                callbacks: {
                    onInit: function() {
                        $(".note-editable").addClass('scrollbar-dark thin');
                        $(".note-editor").addClass('mb-0');
                    },
                    onDialogShown: function(e) {
                        $(".note-modal").css('z-index',$.topZIndex());       
                    },
                    onChange: function(contents, $editable) { 
                        $("[id='"+$(this).attr('id')+"'][name='"+$(this).attr('id')+"'][forminputgroup='adFormData']").val(contents);
                    }
                }
            });
          }

        }
    }).fire({
        title: 'Update Pre-Authorization Status',
        icon:'question',
        html: ihtml,
        width:'1000px',
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-fw fa-check mr-10"></i>'+btn_txt,
        cancelButtonText:'<i class="fa fa-fw fa-times mr-10"></i>CANCEL',
        showLoaderOnConfirm: true,
        reverseButtons: false,
        allowOutsideClick: () => !Swal.isLoading(),
        allowEscapeKey:() => !Swal.isLoading(),
    }).then((result) => {
        if (result.value)
        {
          PreAuthData = new FormData();
          PreAuthData.append('case_no', ecase_no);
          PreAuthData.append('preauth_status', parseInt(astatus));
          console.log($("[id='ad_remarks'][name='ad_remarks'][forminputgroup='adFormData']").val());
          PreAuthData.append('remarks', ((eremarks) ? $("[id='ad_remarks'][name='ad_remarks'][forminputgroup='adFormData']").val() : ''));
          var apiPath = site_url+'preauthorization/index/astatusPreAuth';
          var apiResult = function(pp,rp){

            if(rp.Status == 1)
            {
              toastr.success("Pre-Authorization",rp.Message);
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
              title: $(".page-title").text().replace('Pre-Authorization : ',''),
              html: rp.Message,
              icon: (rp.Status == 1) ? 'success' : 'error',
              confirmButtonText: '<i class="fa fa-times fa-fw mr-10"></i>CLOSE',
              reverseButtons:false,
              allowOutsideClick:false,
              allowEscapeKey:false
            }).then((result) => {
              
              if(rp.Status == 1)
              {
                window.location.reload();
              }

            });
          }
          submitFormData('json','',apiPath,PreAuthData,apiResult,true,true,0);
        }

    });
  }

  
}