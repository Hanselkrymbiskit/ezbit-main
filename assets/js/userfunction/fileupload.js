function FileUpload(UploadTitle,FieldKey,FileHandlerURL,tableid)
{
  UploadTitle = typeof UploadTitle !== 'undefined' ? UploadTitle : "";
  FieldKey = typeof FieldKey !== 'undefined' ? FieldKey : new FormData();
  FileHandlerURL = typeof FileHandlerURL !== 'undefined' ? FileHandlerURL : "";
  tableid = typeof tableid !== 'undefined' ? tableid : "";

  var filebutton = '';
  filebutton += '<div id="uploadButtonsBar" class="font-size-12 float-right">';
  filebutton += '  <button type="button" class="btn btn-dark ml-2" id="BrowseFile" name="fuButtons" title="Add File to Upload">';
  filebutton += '    <i class="fa fa-plus mr-5"></i>Add File(s)';
  filebutton += '  </button>';

  filebutton += '  <button type="button" class="btn btn-dark ml-2" id="RemoveAllFile" name="fuButtons" title="Remove All Selected File" style="display:none" disabled="disabled">';
  filebutton += '    <i class="fa fa-trash mr-5"></i>Remove All File(s)';
  filebutton += '  </button>';

  filebutton += '  <button type="button" class="btn btn-dark ml-2" id="StartUpload" name="fuButtons" title="Start File Upload" style="display:none" disabled="disabled">';
  filebutton += '    <i class="fa fa-upload mr-5"></i>Start Upload';
  filebutton += '  </button>';

  filebutton += '  <button type="button" class="btn btn-dark ml-2" id="StopUpload" name="fuButtons" title="Close File Upload" style="display:none" disabled="disabled">';
  filebutton += '    <i class="fa fa-ban mr-5"></i>Stop Upload';
  filebutton += '  </button>';

  filebutton += '  <button type="button" class="btn btn-dark ml-2 bootbox-close-button" id="CloseUpload" name="fuButtons" title="Close File Upload">';
  filebutton += '    <i class="fa fa-times mr-5"></i>Close';
  filebutton += '  </button>';
  filebutton += '</div>';

  var fileUploadHTML = '';
  fileUploadHTML += '<input id="browsefileupload" type="file" name="files[]" multiple style="display:none" accept="application/pdf,image/gif,image/jpeg,image/jpg,image/png">'; 
  fileUploadHTML += '<div class="mb-5">';
  fileUploadHTML += '<span class="font-weight-bold w-p25">Total File Selected : <span id="totalselectedfile" name="stinfo" class="font-weight-bold text-primary ">0</span></span>';
  fileUploadHTML += '<span class="float-right font-weight-bold w-p25">Status : <span id="uploadprogressstatus" name="stinfo" class="font-weight-bold text-primary "></span></span>';
  fileUploadHTML += '<span class="float-right font-weight-bold w-p25">Uploaded File : <span id="totaluploadedfile" name="stinfo" class="font-weight-bold text-primary "></span></span>';
  fileUploadHTML += '<span class="float-right font-weight-bold w-p25">Unselected File : <span id="totalunselectedfile" name="stinfo" class="font-weight-bold text-primary ">0</span></span>';
  fileUploadHTML += '</div>';
  fileUploadHTML += '<div class="border border-dark">';
  fileUploadHTML += '  <table class="table table-sm table-striped mb-0">';
  fileUploadHTML += '    <thead>';
  fileUploadHTML += '      <tr class="bg-dark">';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p5 text-center"></th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p5 text-center">#</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p40 text-left">File Name</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center">Size</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center">Type</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p20 text-center">Status</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p20 text-center">File ID</th>';
  fileUploadHTML += '      </tr>';
  fileUploadHTML += '    </thead>';
  fileUploadHTML += '  </table>';
  fileUploadHTML += '  <div class="" style="overflow-y:auto">';
  fileUploadHTML += '    <table id="FileBrowseContent" class="table table-sm table-striped">';
  fileUploadHTML += '      <tbody>';
  fileUploadHTML += '      </tbody>';
  fileUploadHTML += '    </table>';
  fileUploadHTML += '  </div>';
  fileUploadHTML += '<div>';

  bootbox.dialog({
    size: 'xl',
    title: ((UploadTitle) ? UploadTitle+" " : "")+'File Upload'+filebutton,
    message: fileUploadHTML,
    closeButton:false,
    onShow: function(e) {

      var UploadDone = false;
      var UploadStart = false;
      var fileitem = new DataTransfer();
      fileitemIndexes = new Object();
      fileitemIndexesCnt = 0;
      fileUnselected = 0;
      fileUploadedSuccess = 0;
      fileUploadedFailed = 0;

      $("body").on('click',"i[id*='removeFileID_']",function(){
          if(!UploadStart)
          {
            var iID = ($(this).attr('id')).replace('removeFileID_','');
            $(this).removeClass('fa-times').addClass('fa-ban');
            $("tr[id='"+iID+"']").closest('tr').css({
              'text-decoration-line': 'line-through',
              'color': '#ff8282',
              'cursor':'default'
            });

            $(this).attr({
              'id':'addFileID_'+iID,
              'title':'Include File',
              'findex':fileitemIndexes[iID],
            });

            fileUnselected++;
            $("span[id='totalunselectedfile']").text(parseInt(fileUnselected));
            fileitemIndexesCnt--;
            $("span[id='totalselectedfile']").text(parseInt(fileitemIndexesCnt));
            delete fileitemIndexes[iID];
          }
      });

      $("body").on('click',"i[id*='addFileID_']",function(){
          if(!UploadStart)
          {
            var iID = ($(this).attr('id')).replace('addFileID_','');
            $(this).removeClass('fa-ban').addClass('fa-times');
            $("tr[id='"+iID+"']").closest('tr').css({
              'text-decoration-line': 'none',
              'color': '#757575',
              'cursor':'default'
            });

            $(this).attr({
              'id':'removeFileID_'+iID,
              'title':'Remove File',
            }).removeAttr('findex');

            fileUnselected--;
            $("span[id='totalunselectedfile']").text(parseInt(fileUnselected));
            fileitemIndexesCnt++;
            $("span[id='totalselectedfile']").text(parseInt(fileitemIndexesCnt));
            fileitemIndexes[iID]=$(this).attr('findex');
          }
      });

      $("table[id='FileBrowseContent']").parent().css('height',($(window).height() - 250)+'px');
      $("div[id='uploadButtonsBar']").closest('h5').css('width','100%');
      $("button[id='BrowseFile']").click(function(){
        $("input[id='browsefileupload']").click();
      });
      $("button[id='RemoveAllFile']").click(function(){
        if(!UploadStart)
        {
          fileitem = new DataTransfer();
          fileitemIndexes = new Object();
          fileitemIndexesCnt = 0;
          fileUnselected = 0;
          fileUploadedSuccess = 0;
          fileUploadedFailed = 0;
          $("input[id='browsefileupload']")[0].files = fileitem.files;
          $("table[id='FileBrowseContent'] tbody").html('');
          $("[name*='stinfo']").text('');
        }
      });

      $("input[id='browsefileupload']").change(function(){  
            if(UploadDone)
            {
              UploadDone = false;
              UploadStart = false;
              fileitem = new DataTransfer();
              fileitemIndexes = new Object();
              fileitemIndexesCnt = 0;
              fileUnselected = 0;
              fileUploadedSuccess = 0;
              fileUploadedFailed = 0;
              $("table[id='FileBrowseContent'] tbody").html('');
              $("[name*='stinfo']").text('');
            }

            if(!UploadStart)
            {
              
              var filenames = [];
              var fileTypes = [
               'image/jpeg',
               'image/pjpeg',
               'image/png',
               'image/gif',
               'image/jpg',
               'application/pdf'
              ];   

              for (let file of $(this)[0].files)
              {
                  if( jQuery.inArray( file['type'], fileTypes ) < 0 || file['size'] > 10000000 )
                  {
 
                  }
                  else
                  {
                    
                    if( !fileitem.hasOwnProperty(btoa(file['name'])) )
                    {
                      
                      fileitem.items.add(file); 
                      filenames.push(file['name']);
                      fileitemIndexes[btoa(file['name'])]=fileitemIndexesCnt;
                      fileitemIndexesCnt++;

                      var FType = file['type'].split("/");


              var UploaditemRow  = '<tr id="'+btoa(file['name'])+'">';
                  UploaditemRow += '<td class="w-p5 text-center"><i class="fa fa-times text-danger" id="removeFileID_'+btoa(file['name'])+'" name="RemoveSelectedFile" title="Remove File" style="cursor:pointer"></i></td>';
                  UploaditemRow += '<td class="w-p5 text-center">'+fileitemIndexesCnt+'</td>';
                
                  UploaditemRow += '<td class="w-p40 text-left">'+file['name']+'</td>';
                  UploaditemRow += '<td class="w-p10 text-center">'+formatBytes(file['size'])+'</td>';
                  UploaditemRow += '<td class="w-p10 text-center">'+FType[1]+'</td>';
                  UploaditemRow += '<td class="w-p20 text-center"><span id="fuStatus'+btoa(file['name'])+'"></span></td>';
                  UploaditemRow += '<td class="w-p10 text-center"><span id="fuFileID'+btoa(file['name'])+'"></span></td>';
                UploaditemRow += '</tr>';

                      $("table[id='FileBrowseContent'] tbody").append(UploaditemRow);
                    }
                  }
              }

              $(this)[0].files = fileitem.files 
          
              $("span[id='totalselectedfile']").html(fileitemIndexesCnt);
              
              if(parseInt(fileitemIndexesCnt) > 0)
              {
                $("button[id='StartUpload'],button[id='RemoveAllFile']").show().removeAttr('disabled');
                $("button[id='StartUpload']").addClass('btn-primary');
              }
            }
      });

      $("button[id='StartUpload']").unbind('click').click(function(){
        
        $(this).hide();
        $("i[id*='removeFileID_'],i[id*='addFileID_']").remove();
        $("span[id='uploadprogressstatus']").html('<i class="fa fa-spinner icon-spin mr-10"></i>Preparing File to Upload');

        UploadStart = true;
        $("button[id='BrowseFile'][name='fuButtons'],button[id='RemoveAllFile'][name='fuButtons'],button[id='CloseUpload'][name='fuButtons']").hide().attr('disabled','disabled');

        var CallBackFunctionList = new Object();
        var CallTimeOutList = new Object();
        var file_data = fileitem.files;
        for (var i = 0; i < file_data.length; i++) {
            var c_file_data = file_data[i];
            var file_data_name = btoa(c_file_data['name']);

            if( file_data_name in fileitemIndexes)
            {
              var fileFormData = new FormData();  
              fileFormData.append("FileU", file_data[i]);
              fileFormData.append("FieldKey", FieldKey);
              fileFormData.append("UToken", UToken);
              backendURL = FileHandlerURL; 

              $("table[id='FileBrowseContent'] tr[id='"+file_data_name+"']").addClass('text-info font-weight-bold');

              CallBackFunctionList[file_data_name] = function(FormData,postresult){
                
                var w = $("table[id='FileBrowseContent']").parent();
                var row = $("tr[id='"+postresult.FileName+"']");

                if (row.length){
                    w.scrollTop( row.offset().top - (w.height()/2) );
                }

                clearTimeout(CallTimeOutList[postresult.FileName]);

                $("table[id='FileBrowseContent'] tr[id='"+postresult.FileName+"']").focus();
                if( postresult.Status == 1 )
                {
                  $("span[id='fuStatus"+postresult.FileName+"']").html('<i class="fa fa-check mr-10 text-success"></i><i class="text-success">Uploaded');

                  $("span[id='fuFileID"+postresult.FileName+"']").html('<b>'+postresult.FileID+'</b>');

                  fileUploadedSuccess++;
                  $("table[id='FileBrowseContent'] tr[id='"+postresult.FileName+"']").removeClass('text-info').addClass('text-success');
                  $("span[id='totaluploadedfile']").html('<b>'+fileUploadedSuccess+'</b>');
                }
                else
                {
                  $("span[id='fuStatus"+postresult.FileName+"']").html('<i class="fa fa-times mr-10 text-danger"></i><i class="text-danger">Failed');
                  $("table[id='FileBrowseContent'] tr[id='"+postresult.FileName+"']").removeClass('text-info').addClass('text-danger');
                  fileUploadedFailed++;
                }

                var rawPercent = ((fileUploadedSuccess+fileUploadedFailed)/fileitemIndexesCnt)*100;
                var upPercent = rawPercent.toFixed(3);

                $("span[id='uploadprogressstatus']").html('<i class="fa '+((upPercent >= 100) ? 'fa-check' : 'fa-spinner icon-spin')+' mr-10"></i>Uploading File '+((upPercent >= 100) ? 100 : upPercent)+'%');

                if( rawPercent >= 100 )
                {
                  $("button[id='BrowseFile'][name='fuButtons'],button[id='RemoveAllFile'][name='fuButtons'],button[id='CloseUpload'][name='fuButtons']").show().removeAttr('disabled');

                  UploadDone = true;
                   oTable_Obj[tableid].draw();
                  fileitem = new DataTransfer();
                  $("input[id='browsefileupload']")[0].files = fileitem.files;       
                }

              };

              $("span[id='fuStatus"+file_data_name+"']").html('<i class="fa fa-spinner icon-spin mr-10 text-primary"></i><i class="text-primary">Uploading');

              setTimeout(function(backendURL,fileFormData,CallBackFunctionList,file_data_name,iterationcnt){

                submitFormData('json','',backendURL,fileFormData,CallBackFunctionList,false);
                CallTimeOutList[file_data_name] = setTimeout(function(){
                    
                    var w = $("table[id='FileBrowseContent']").parent();
                    var row = $("tr[id='"+file_data_name+"']");

                    if (row.length){
                        w.scrollTop( row.offset().top - (w.height()/2) );
                    }

                    $("span[id='fuStatus"+file_data_name+"']").html('<i class="fa fa-warning mr-10 text-warning" title="Take too long for the server to response, Uploaded File Status is unknown, please check on the Data List."></i><i class="text-warning">Server Time Out');
                    $("table[id='FileBrowseContent'] tr[id='"+file_data_name+"']").removeClass('text-info').addClass('text-warning');
                    fileUploadedFailed++;

                    var rawPercent = ((fileUploadedSuccess+fileUploadedFailed)/fileitemIndexesCnt)*100;
                    var upPercent = rawPercent.toFixed(3);
                    $("span[id='uploadprogressstatus']").html('<i class="fa '+((upPercent >= 100) ? 'fa-check' : 'fa-spinner icon-spin')+' mr-10"></i>Uploading File '+((upPercent >= 100) ? 100 : upPercent)+'%');

                    if( rawPercent >= 100 )
                    {
                      $("button[id='BrowseFile'][name='fuButtons'],button[id='RemoveAllFile'][name='fuButtons'],button[id='CloseUpload'][name='fuButtons']").show().removeAttr('disabled');

                      UploadDone = true;
                      
                      // Refresh
                      oTable_Obj[tableid].draw();
                    }

                },15000+(iterationcnt*1000)+500);

              },500,backendURL,fileFormData,CallBackFunctionList[file_data_name],file_data_name,i);
            }
            
        }
      });

    },
    onShown:function(e){

    },
    onHidden:function(e){
      $("i[id*='removeFileID_'],i[id*='addFileID_']").unbind('click');
    }
  });
}


function ViewFileDetails(FileID)
{
  FileID = typeof FileID !== 'undefined' ? FileID : "";
  if(FileID !== "" )
  {
      backendURL = site_url+'fileupload/index/details';

      var sFormData = new FormData();
      sFormData.append("FileID", FileID);
      sFormData.append("UToken", UToken);

      var CallBackFileDetails = function(fd,pd){
        if(pd.Status == 1)
        {


          bootbox.dialog({
               size: 'xl',
               title: '<span id="VAttachedTitle">'+pd.FileName+'</span><i class="fas fa-times bootbox-close-button float-right text-grey" style="cursor:pointer"></i>',
               message: '<object data="data:'+pd.FileType+';base64,'+pd.FileData+'#toolbar=0" type="'+pd.FileType+'" style="height:'+($(window).height() - 150)+'px;width:100%"></object>',
               closeButton:false,
               onShow: function(e) {
                  $("span[id='VAttachedTitle']").closest('h5').css('width','100%');
               },
               onShown:function(e){

               }
           })
        }else
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
            title: "Failed to Load File Detail",
            text: pd.Message,
            icon: 'error',
            confirmButtonText: 'Close',
          }).then((result) => {
           
          });
        }
      };

      submitFormData('json','',backendURL,sFormData,CallBackFileDetails,true);
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
      title: "Failed to Load File Detail",
      text: 'Permission Denied!',
      icon: 'error',
      confirmButtonText: 'Close',
    }).then((result) => {
     
    });
  }
}