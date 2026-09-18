function new_claims()
{
  
  var ihtml = `
    <div class="form-group form-material mb-0" data-plugin="formMaterial">
      <label class="label font-weight-bold font-size-16 text-uppercase"  for="patientinfo[patient_philhealthno]">Pre-Authorization CASE No:</label>
      <input type="text"  id="case_no" name="case_no"  class="form-control text-uppercase bt-white text-primary font-size-18 text-center"title="Pre-Authorization CASE No"  forminputgroup="claimsFormData" forminputgroupcheck="claimsFormData" placeholder="000000-00-00000" autocomplete="eFormData_off" formgroup="claimsFormData" value="" dfvalue=""   data-plugin="formatter"  data-pattern="[[999999]]-[[99]]-[[99999]]" required>       
    </div>

  `;
  var NewAppFormTitle = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-dark mr-5 w-p45',
        cancelButton: 'btn btn-dark  w-p45',
        input: 'form-control'
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
        Site.getInstance().initializePlugins(); 
      }
  }).fire({
      title: 'New Z Benefit Claims',
      icon:'info',
      html: ihtml,
      showCancelButton: true,
      confirmButtonText: 'Proceed',
      cancelButtonText:'Cancel',
      showLoaderOnConfirm: true,
      reverseButtons: false,
      allowOutsideClick: () => !Swal.isLoading(),
      allowEscapeKey:() => !Swal.isLoading(),
      preConfirm: (iLLness) => {
        
        if(!$("input[id='case_no'][name='case_no'][forminputgroup='claimsFormData']").val()){ Swal.showValidationMessage( 'Please enter Valid Pre-Authorization Case No.!' ) }
        else
        {
          return fetch(site_url+'claims/index/requestnewform', {
            method: 'post',
            body: "CaseNo="+$("input[id='case_no'][name='case_no'][forminputgroup='claimsFormData']").val()+"&UToken="+UToken+( (typeof csrfName !== 'undefined') ? '&'+csrfName+'='+csrfHash : ''),
            headers: { 'Content-type': 'application/x-www-form-urlencoded' }
          }).then(function(response) {
            if (!response.ok) {
              toastr.error("New Z-Benefit Claim Submission","Unable to Process Request."); 
              return { Status:0, Message:"Unable to Process Request." };
            }
            return response.json();
          }).catch(error => {
              toastr.error("New Z-Benefit Claim Submission","Unable to Process Request."); 
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
          toastr.success("New Z-Benefit Claim Submission","Redirecting to Z-Benefit Claim Form!");
          Swal.close();
          window.location.href=site_url+'claims/index/newclaims/'+btoa(rv.Message);
        }
        else
        {
          Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-danger',
            },
            buttonsStyling: false,
            willOpen: (fnRun) => {
              $(".swal2-container").css('z-index',$.topZIndex());
            }
          }).fire({
            title: "New Z Benefit Claims",
            html: rv.Message,
            icon: "error",
            confirmButtonText: 'Close',
          });
        }
      }
      else
      {
        
      }
  });
}

