<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$actionButtons = '
<button type="button" id="btnLogoutAll" name="btnActionsButton" class="btn btn-dark" title="Logout all selected User Session" disabled="disabled" style="display:none"><i class="fa fa-sign-out mr-5"></i>Sign Out</button>
';

$DataTableConfig =  array(
    'ajaxdatasource'  =>site_url('administrator/usersonline/listdata'),
    'tableid'         =>'usersonlinelist',
    'dbtable'         =>'',
    'dbtableprimary'  =>'',
    'title'           =>'',
    'defaultorder'    =>array(array(7,'desc')),
    'dataexport'      =>true,
    'tablecondensed'  =>false,
    'bactionscolumn'  =>array(
                          "enable"        =>true,
                          "add"           =>false,
                          "addmethod"     =>'', // js function call
                          "view"          =>true,
                          "viewmethod"    =>'', // js function call
                          "edit"          =>false,
                          "editmethod"    =>'', // js function call
                          "delete"        =>false,
                          "deletemethod"  =>'', // js function call
                          "checkbox"      =>true,
                          "indexnumber"   =>true,
                      ),
    'column'          =>array(
                            
                            array(
                              'fieldid'            => 'sessionid',
                              'fieldlabel'         => 'Session ID',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => false,
                              'width'              => '',
                              'className'          => '',
                              'visible'            => false,
                              'exportable'         => true,
                              'inputdisplay'       => 'text',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),

                            array(
                              'fieldid'            => 'userid',
                              'fieldlabel'         => 'User ID',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => true,
                              'width'              => '',
                              'className'          => '',
                              'visible'            => false,
                              'exportable'         => true,
                              'inputdisplay'       => 'text',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                            array(
                              'fieldid'            => 'username',
                              'fieldlabel'         => 'User Name',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '',
                              'className'          => '',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'text',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                            array(
                              'fieldid'          => 'emailaddress',
                              'fieldlabel'       => 'Email Address',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'       => false,
                              'advancesearch'      => false,
                              'orderable'      => true,
                              'width'          => '',
                              'className'        => '',
                              'visible'        => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'text',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                            array(
                              'fieldid'            => 'userclassification',
                              'fieldlabel'         => 'User Classification',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => true,
                              'width'              => '',
                              'className'          => '',
                              'visible'            => true,
                              'exportable'         => true,
                              'inputdisplay'       => 'text',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                            array(
                              'fieldid'            => 'userclassificationid',
                              'fieldlabel'         => 'User Classification',
                              'fieldtype'          => 'text',
                              'base64_encrypted'   => false,
                              'searchable'         => true,
                              'advancesearch'      => true,
                              'orderable'          => true,
                              'width'              => '',
                              'className'          => '',
                              'visible'            => false,
                              'exportable'         => false,
                              'inputdisplay'       => 'select',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '4',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                            array(
                              'fieldid'            => 'logdatetime',
                              'fieldlabel'         => 'Log Date Time',
                              'fieldtype'          => 'datetime',
                              'base64_encrypted'   => false,
                              'searchable'         => false,
                              'advancesearch'      => false,
                              'orderable'          => true,
                              'width'              => '',
                              'className'          => '',
                              'visible'            => true,
                              'exportable'         => false,
                              'inputdisplay'       => '',
                              'inputcustomattr'    => '',
                              'inputcustomclass'   => '',
                              'referenceid'        => '',
                              'referencefilter'    => '', 
                              'referenceorder'     => '', 
                              'parentfieldid'      => '',
                              'childfieldid'       => '',
                              'childfieldfilter'   => '',
                              'parentfieldid'      => '',
                            ),
                         
                          ),
    'scrollx'           =>false,
    'scrollpagination'  =>false,
    'actiobbuttons'     =>@$actionButtons, // html content
    'fixedcolumns'      =>array("leftColumns"=>0,"rightColumns"=>0), 
    'callbackjsfunction'=>'',
    'bordercolor'       =>'secondary', 
  );





?>

<div class="">  
    <?php echo $CI->datatables->ShowDataTable($DataTableConfig); ?>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
var SelectedUser = new Object();

$(document).ready(function(){
  var DTTableID = '<?php echo $DataTableConfig['tableid']; ?>';
  oTable_Obj[DTTableID].on( 'draw.dt', function (e,settings){
    $("input[type='checkbox'][name='DTListChxBx[]']").each(function(){
      if( SelectedUser.hasOwnProperty(atob(atob($(this).attr('dataid')))) && !$(this).prop('checked'))
      {
        $(this).click();
      }
    });
  }).draw();

  $("table[id='DTTable_<?php echo $DataTableConfig['tableid']; ?>']").on('click',"input[type='checkbox'][name='DTListChxBx[]']",function(){
     if( $(this).prop('checked') )
     {
        SelectedUser[atob(atob($(this).attr('dataid')))] = atob(atob($(this).attr('dataid')));
     }
     else
     {
        delete SelectedUser[atob(atob($(this).attr('dataid')))];
     }

     if( Object.keys(SelectedUser).length > 0)
     {
        $("button[name='btnActionsButton']").removeAttr('disabled').show();
     }
     else
     {
        $("button[name='btnActionsButton']").attr('disabled','disabled').hide();
     }
  });

  var refreshlist = setInterval(function(){
    oTable_Obj['usersonlinelist'].draw();
  },10000);

  $("body").on("click","a[grouplink*='signoutuser']",function()
  {
    clearInterval(refreshlist);
    var uid = $(this).attr('uid');
    Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success mr-5 w-p45',
        cancelButton: 'btn btn-secondary w-p45',
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
      }
    }).fire({
      title: 'Sign Out User Account',
      html: '<h1>'+$(this).attr('uname')+'</h1>',
      icon: 'question',
      showConfirmButton:true,
      confirmButtonText: 'Yes',
      showCancelButton: true,
      cancelButtonText: "No",  
      reverseButtons: false,
      allowEscapeKey:false,
      allowOutsideClick:false,
    }).then((result) => {

      if (result.value) 
      {
        
        var target_url = site_url+'Utilities/logoutcurrentuser';
        var PostParam = new FormData();
        PostParam.append('userid',uid);
        var rpResult = function(pp,rp){
            Swal.mixin({
              customClass: {
                confirmButton: 'btn btn-success mr-5 w-p45',
                cancelButton: 'btn btn-secondary w-p45',
              },
              buttonsStyling: false,
              willOpen: (fnRun) => {
                $(".swal2-container").css('z-index',$.topZIndex());
              }
            }).fire({
              title: 'Sign Out User Account',
              html: (rp.Status == 1) ? 'Successfully Signout!' : 'Failed to Signout User Account!',
              icon: (rp.Status == 1) ? 'success' : 'error',
              showConfirmButton:true,
              confirmButtonText: 'Close',
              showCancelButton: false,
              cancelButtonText: "No",  
              reverseButtons: false,
            }).then((result) => {

              if(rp.Status == 1)
              {
                oTable_Obj['usersonlinelist'].draw(); 
              }
              
              refreshlist = setInterval(function(){
                oTable_Obj['usersonlinelist'].draw();
              },10000);            
            });
        };
        submitFormData('json','',target_url,PostParam,rpResult,false);
      }

                   
    });


  });

  $("button[name='btnActionsButton']").click(function(){
      switch($(this).attr('id'))
      {
        case "btnLogoutAll":

          if( Object.keys(SelectedUser).length > 0)
          {
            clearInterval(refreshlist);
            Swal.mixin({
              customClass: {
                confirmButton: 'btn btn-success mr-5 w-p45',
                cancelButton: 'btn btn-secondary w-p45',
              },
              buttonsStyling: false,
              willOpen: (fnRun) => {
                $(".swal2-container").css('z-index',$.topZIndex());
              }
            }).fire({
              title: 'Sign Out Selected User Account',
              // html: '<h1>'+$(this).attr('uname')+'</h1>',
              icon: 'question',
              showConfirmButton:true,
              confirmButtonText: 'Yes',
              showCancelButton: true,
              cancelButtonText: "No",  
              reverseButtons: false,
              allowEscapeKey:false,
              allowOutsideClick:false,
            }).then((result) => {

              if (result.value) 
              {
                
                var target_url = site_url+'Utilities/logoutcurrentuser';
                var PostParam = new FormData();
                for(var ui in SelectedUser)
                {
                  PostParam.append('userid[]',SelectedUser[ui]);
                }
                
                var rpResult = function(pp,rp){
                    Swal.mixin({
                      customClass: {
                        confirmButton: 'btn btn-success mr-5 w-p45',
                        cancelButton: 'btn btn-secondary w-p45',
                      },
                      buttonsStyling: false,
                      willOpen: (fnRun) => {
                        $(".swal2-container").css('z-index',$.topZIndex());
                      }
                    }).fire({
                      title: 'Selected User Account',
                      html: (rp.Status == 1) ? 'Successfully Signout!' : 'Failed to Signout User Account!',
                      icon: (rp.Status == 1) ? 'success' : 'error',
                      showConfirmButton:true,
                      confirmButtonText: 'Close',
                      showCancelButton: false,
                      cancelButtonText: "No",  
                      reverseButtons: false,
                    }).then((result) => {

                      if(rp.Status == 1)
                      {
                        oTable_Obj['usersonlinelist'].draw(); 
                      }
                      
                      refreshlist = setInterval(function(){
                        oTable_Obj['usersonlinelist'].draw();
                      },10000);            
                    });
                };
                submitFormData('json','',target_url,PostParam,rpResult,false);
              }

                           
            });
          }
          else
          {
            Swal.mixin({
              customClass: {
                confirmButton: 'btn btn-success mr-5 w-p45',
                cancelButton: 'btn btn-secondary w-p45',
              },
              buttonsStyling: false,
              willOpen: (fnRun) => {
                $(".swal2-container").css('z-index',$.topZIndex());
              }
            }).fire({
              title: 'Sign Out User Account',
              html: 'Please select Users to Sign Out',
              icon: 'warning',
              showConfirmButton:true,
              confirmButtonText: 'Close',
              showCancelButton: false,
              cancelButtonText: "No",  
              reverseButtons: false,
            }).then((result) => {          
            });
          }

        break;
      }
  });

});  
</script>
