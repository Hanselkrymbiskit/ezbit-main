function new_preauth(preauthf)
{
  var preauthf = typeof preauthf == 'undefined' ? '' : preauthf; 
  var ihtml = '<div class="form-group form-material mt-10"><label class="form-control-label font-weight-bold text-uppercase text-primary" for="select">Illness/Primary Condition</label><select id="preauth_illnesstype" name="preauth_illnesstype" forminputgroup="preauth_form" class="form-control" autocomplete="off"></select></div>';

  var NewAppFormTitle = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-dark mr-5 w-p45',
        cancelButton: 'btn btn-dark  w-p45',
        input: 'form-control'
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
        preauthFilter = (preauthf) ? " and code in ("+atob(preauthf)+")" : "";
        // preauthFilter = '';
        getreferencevalue({objselect:$("select[id='preauth_illnesstype'][name='preauth_illnesstype'][forminputgroup='preauth_form']"),referenceid:'200',filter:"enabledForm = 'Y'"+preauthFilter,order:''},'','SELECT ILLNESS / PRIMARY CONDITION');
      }
  }).fire({
      title: 'New Pre-Authorization',
      icon:'info',
      html: ihtml,
      showCancelButton: true,
      confirmButtonText: 'Proceed',
      cancelButtonText:'Cancel',
      showLoaderOnConfirm: true,
      reverseButtons: false,
      // theme:'borderless',
      allowOutsideClick: () => !Swal.isLoading(),
      allowEscapeKey:() => !Swal.isLoading(),
      preConfirm: (iLLness) => {
        
        if(!$("select[id='preauth_illnesstype'][name='preauth_illnesstype'][forminputgroup='preauth_form']").val()){ Swal.showValidationMessage( 'Please select ILLNESS / PRIMARY CONDITION!' ) }
        else
        {
          return fetch(site_url+'preauthorization/index/requestnewform', {
            method: 'post',
            body: "IllnessType="+$("select[id='preauth_illnesstype'][name='preauth_illnesstype'][forminputgroup='preauth_form']").val()+"&UToken="+UToken+( (typeof csrfName !== 'undefined') ? '&'+csrfName+'='+csrfHash : ''),
            headers: { 'Content-type': 'application/x-www-form-urlencoded' }
          }).then(function(response) {
            if (!response.ok) {
              toastr.error("New Pre-Authorization","Unable to Process Request."); 
              return { Status:0, Message:"Unable to Process Request." };
            }
            return response.json();
          }).catch(error => {
              toastr.error("New Pre-Authorization","Unable to Process Request."); 
              return { Status:0, Message:"Unable to Process Request." };
          });
          
        }

      },
  }).then((result) => {
      if (result.value)
      {
        rv = result.value;
        if( rv.Status == 1 )
        {
          toastr.success("New Pre-Authorization","Redirecting to Pre-Authorization Form!");
          Swal.close();
          loader();
          window.location.href=site_url+'preauthorization/index/newpreauth/'+btoa(rv.Message);
        }
      }
      else
      {
       
      }
  });
}

