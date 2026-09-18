function newfaq(dataid)
{
  dataid = typeof dataid !== 'undefined' ? dataid : '';
  mode = typeof mode !== 'undefined' ? mode : 1;
  tableid = typeof tableid !== 'undefined' ? tableid : 'campaign_List';

  var posturl = site_url+"faq/getform";
  var postparam = new FormData();
  postparam.append('DataID',dataid);
  var processresponse = function(postparam,returnparam){
    var pp = postparam;
    var rp = returnparam;
    if(rp.Status == 1)
    {
      faqform(dataid,rp.FormHTML,rp.FormJS,tableid);
    }
    else
    {
      Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-dark',
        },
        buttonsStyling: false,
        willOpen: (fnRun) => {
          $(".swal2-container").css('z-index',$.topZIndex());
        }
      }).fire({
        // title: "CREC REQUEST",
        text: rp.Message,
        icon: 'error',
        confirmButtonText: 'Close',
      });
    }
  };

  submitFormData('json','',posturl,postparam,processresponse,true);
}

function faqform(dataid,formhtml,formjs,tableid)
{
  dataid = typeof dataid !== 'undefined' ? dataid : '';
  var reqfld = '';
  var btnActionBox = '';
  btnActionBox += '<div class="font-size-12 p-15">';
  btnActionBox += '<div class="float-left">';
  btnActionBox += '<h6 class="mt-0 text-uppercase"><i class="text-danger fa fa-fw fa-asterisk mr-10"></i>Required Fields</span></h6>';
  btnActionBox += '</div>';
  btnActionBox += '<div class="float-right">';
  btnActionBox += '  <button type="button" class="btn btn-dark ml-2" id="btnSave" name="btnActions" title="SAVE FAQ FORM" style="">';
  btnActionBox += '    <i class="fa fa-save fa-fw mr-5"></i>SUBMIT';
  btnActionBox += '  </button>';
  btnActionBox += '  <button type="button" class="btn btn-dark ml-2 bootbox-close-button" id="btnClose" name="btnActions" title="CLOSE FAQ FORM" style="">';
  btnActionBox += '    <i class="fa fa-times fa-fw mr-5"></i>CLOSE';
  btnActionBox += '  </button>';
  btnActionBox += '</div>';
  btnActionBox += '</div>';
  bootbox.dialog({
    size: 'xl',
    message: '<div class="border border-secondary w-p40>"'+atob(formhtml)+'</div>'+btnActionBox,
    closeButton:false,
    onShow: function(e) {
      $(".modal-xl").css('max-width','60vw');
     
    },
    onShown:function(e){
      eval(atob(formjs));
      Site.getInstance().initializePlugins();
      $("div[id='FormMainPanel']").show();
      $("button[name*='btnActions']").click(function(){
        switch($(this).attr('id'))
        {
          case 'btnSave':
            
            $("[forminputgroup='faqformdata']").each(function(){
              if( reqfld.indexOf($(this).attr('id')) != -1 )
              {
                $(this).attr('required','required');
              }
            });

            var gFormData = getFormData('faqformdata');

            
            if( Object.keys(gFormData['ErrorData']).length > 0)
            {
              var rRequired = function(){ 
                $("[forminputgroup='faqformdata']").each(function(){
                  $(this).removeAttr('required');
                });
              };
              popFormDataError(gFormData['ErrorData'],'FAQ FORM');
            }
            else
            {
              
              var apiPath = site_url+'faq/submitform';
              var apiResult = function(pp,rp){
                
                if(rp.Status == 1)
                {
                  oTable_Obj['FAQsList'].draw();   
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
                  title: "FAQ FORM",
                  text: rp.Message,
                  icon: (rp.Status == 1) ? 'success' : 'error',
                  confirmButtonText: '<i class="fa fa-times fa-fw mr-10"></i>CLOSE',
                  showCancelButton: false,
                  cancelButtonText:'',
                  reverseButtons:false,
                  allowOutsideClick:false,
                  allowEscapeKey:false
                }).then((result) => {
                  
                  if (result.value) 
                  {
                    $("button[id='btnClose'][name='btnActions']").click();
                  }

                });
              };

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
                title: "FAQ FORM",
                text: 'PROCEED FAQ FORM SUBMISSION!',
                icon: 'question',
                confirmButtonText: 'YES',
                showCancelButton:true,
                cancelButtonText:'NO',
                reverseButtons:false,
                allowOutsideClick:false,
                allowEscapeKey:false
              }).then((result) => {
                
                if (result.value) 
                {
                  submitFormData('json','',apiPath,gFormData['FormData'],apiResult,true,true,0);
                }

              });
            }
          break;
        }
      });
    },
    onHidden:function(e){
    },  
  });
}

function delfaq(dataid,faqquestion)
{
  dataid = typeof dataid !== 'undefined' ? dataid : '';
  faqquestion = typeof faqquestion !== 'undefined' ? faqquestion : '';

  var posturl = site_url+"faq/deleteFAQ";
  var postparam = new FormData();
  postparam.append('faqid',dataid);

  var processresponse = function(postparam,returnparam){
    var pp = postparam;
    var rp = returnparam;

    if(rp.Status == 1)
    {
      oTable_Obj['FAQsList'].draw();   
    }
    
    Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-dark',
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
      }
    }).fire({
      // title: "CREC REQUEST",
      text: rp.Message,
      icon: 'error',
      confirmButtonText: 'Close',
    });

  };

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
    title: "DELETE FAQ",
    html: '<h3 class="text-success">"'+faqquestion.toUpperCase()+'"</h3>',
    icon: 'question',
    confirmButtonText: 'YES',
    showCancelButton:true,
    cancelButtonText:'NO',
    reverseButtons:false,
    allowOutsideClick:false,
    allowEscapeKey:false
  }).then((result) => {
    
    if (result.value) 
    {
      submitFormData('json','',posturl,postparam,processresponse,true);
    }

  });
  
}