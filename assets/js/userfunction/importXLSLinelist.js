function ImportXLSLinelist(UploadTitle,FieldKey,FileHandlerURL,tableid)
{
  UploadTitle = typeof UploadTitle !== 'undefined' ? UploadTitle : "";
  FieldKey = typeof FieldKey !== 'undefined' ? FieldKey : new FormData();
  FileHandlerURL = typeof FileHandlerURL !== 'undefined' ? FileHandlerURL : "";
  tableid = typeof tableid !== 'undefined' ? tableid : "";

  var filebutton = '';
  filebutton += '<div id="uploadButtonsBar" class="font-size-12 float-right">';
  filebutton += '  <button type="button" class="btn btn-dark ml-2" id="BrowseFile" name="fuButtons" title="Browse XLS Linelist Template" style="padding:2px 10px">';
  filebutton += '    <i class="fa fa-file-excel-o mr-5"></i>Browse File';
  filebutton += '  </button>';

  filebutton += '  <button type="button" class="btn btn-dark ml-2" id="StartUpload" name="fuButtons" title="Start File Upload" style="display:none;padding:2px 10px" disabled="disabled">';
  filebutton += '    <i class="fa fa-upload mr-5"></i>Start Upload';
  filebutton += '  </button>';

  filebutton += '  <button type="button" class="btn btn-dark ml-2" id="StopUpload" name="fuButtons" title="Close File Upload" style="display:none;padding:2px 10px" disabled="disabled">';
  filebutton += '    <i class="fa fa-ban mr-5"></i>Stop Upload';
  filebutton += '  </button>';

  filebutton += '  <button type="button" class="btn btn-dark ml-2 bootbox-close-button" id="CloseUpload" name="fuButtons" title="Close File Upload" style="padding:2px 10px">';
  filebutton += '    <i class="fa fa-times mr-5"></i>Close';
  filebutton += '  </button>';
  filebutton += '</div>';

  var fileUploadHTML = '';
  fileUploadHTML += '<input id="browsefileupload" type="file" name="files" style="display:none" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">'; 
  fileUploadHTML += '<div class="mb-5">';
    fileUploadHTML += '<div class="">';
      fileUploadHTML += '<table class="table table-sm border-0 mb-0">';
        fileUploadHTML += '<tr>';
          fileUploadHTML += '<td class="w-p40"><span class="small font-weight-bold">File Name : </span><span id="FileName" name="importDisplay" class="small text-primary font-weight-bold"></span></td>';
          fileUploadHTML += '<td class="w-p20"><span class="small font-weight-bold">File Size : </span><span id="FileSize" name="importDisplay" class="small text-primary font-weight-bold"></span></td>';
          fileUploadHTML += '<td class="w-p20"><span class="small font-weight-bold">File Type : </span><span id="FileType" name="importDisplay" class="small text-primary font-weight-bold"></span></td>';
          fileUploadHTML += '<td class="w-p20"><span class="small font-weight-bold">Sheet Count : </span><span id="SheetCount" name="importDisplay" class="small text-primary font-weight-bold"></span></td>';
        fileUploadHTML += '</tr>';
      fileUploadHTML += '</table>';
       fileUploadHTML += '<table class="table table-sm border-0 mb-0">';
        fileUploadHTML += '<tr>';
          fileUploadHTML += '<td class="w-p25"><span class="small font-weight-bold mr-5">Linelist Record Count  : </span><span id="LinelistRecord_Count" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25"><span class="small font-weight-bold mr-5">To Upload: </span><span id="LinelistRecord_ToUpload" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25"><span class="small font-weight-bold mr-5">Success : </span><span id="LinelistRecord_Success" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25"><span class="small font-weight-bold mr-5">Failed : </span><span id="LinelistRecord_Failed" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
        fileUploadHTML += '</tr>';
        fileUploadHTML += '<tr>';
          fileUploadHTML += '<td class="w-p25"><span class="small font-weight-bold mr-5">AEFI Event Record Count  : </span><span id="AEFIEventRecord_Count" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25"><span class="small font-weight-bold mr-5">To Upload : </span><span id="AEFIEventRecord_ToUpload" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25"><span class="small font-weight-bold mr-5">Success : </span><span id="AEFIEventRecord_Success" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25"><span class="small font-weight-bold mr-5">Failed : </span><span id="AEFIEventRecord_Failed" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
        fileUploadHTML += '</tr>';
        fileUploadHTML += '<tr>';
          fileUploadHTML += '<td class="w-p100" colspan="4"><span class="small font-weight-bold mr-5">Status :</span><span id="Status" name="importDisplay" class="small text-primary font-weight-bold"></span><i id="xlsstatusLoader" class="fa fa-circle-o-notch icon-spin float-right" style="display:none"></i></td>';
        fileUploadHTML += '</tr>';
      fileUploadHTML += '</table>';
    fileUploadHTML += '</div>';
   fileUploadHTML += '</div>';
  fileUploadHTML += '<div class="border border-dark">';
  fileUploadHTML += '  <div class="" style="overflow-y:scroll">';
  fileUploadHTML += '  <table class="table table-sm table-striped mb-0">';
  fileUploadHTML += '    <thead>';
  fileUploadHTML += '      <tr class="bg-dark">';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p5 text-center font-size-10" title="Excel Row">#</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold text-center font-size-10" style="width:2%"><i class="fa fa-search"></i></th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">VF ID</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">Last Name</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">First Name</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">Middle Name</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">Suffix Name</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">Date of Birth</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">Sex</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p5 text-center font-size-10">AEFI</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold text-center font-size-10">Status</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold text-center font-size-10" id="ColumnRemarks" style="display:none">Remarks</th>';
  fileUploadHTML += '      </tr>';
  fileUploadHTML += '    </thead>';
  fileUploadHTML += '  </table>';
  fileUploadHTML += '  </div>';
  fileUploadHTML += '  <div class="" style="overflow-y:scroll">';
  fileUploadHTML += '   <table id="FileBrowseContent" class="table table-sm table-striped">';
  fileUploadHTML += '    <thead>';
  fileUploadHTML += '      <tr class="bg-dark" style="display:none">';
          fileUploadHTML += '<td class="w-p40"><span class="small font-weight-bold">File Name : </span><span id="FileName" name="importDisplay" class="small text-primary font-weight-bold"></span></td>';
          fileUploadHTML += '<td class="w-p20"><span class="small font-weight-bold">File Size : </span><span id="FileSize" name="importDisplay" class="small text-primary font-weight-bold"></span></td>';
          fileUploadHTML += '<td class="w-p20"><span class="small font-weight-bold">File Type : </span><span id="FileType" name="importDisplay" class="small text-primary font-weight-bold"></span></td>';
          fileUploadHTML += '<td class="w-p20"><span class="small font-weight-bold">Sheet Count : </span><span id="SheetCount" name="importDisplay" class="small text-primary font-weight-bold"></span></td>';
          fileUploadHTML += '<td colspan="2"></td>';
  fileUploadHTML += '      </tr>';
  fileUploadHTML += '      <tr class="bg-dark" style="display:none">';
          fileUploadHTML += '<td class="w-p25" colspan="3"><span class="small font-weight-bold mr-5">Linelist Record Count  : </span><span id="LinelistRecord_Count2" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25" colspan="3"><span class="small font-weight-bold mr-5">To Upload: </span><span id="LinelistRecord_ToUpload2" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25" colspan="3"><span class="small font-weight-bold mr-5">Success : </span><span id="LinelistRecord_Success2" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25" colspan="3"><span class="small font-weight-bold mr-5">Failed : </span><span id="LinelistRecord_Failed2" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td colspan="2"></td>';
  fileUploadHTML += '      </tr>';
  fileUploadHTML += '      <tr class="bg-dark" style="display:none">';
          fileUploadHTML += '<td class="w-p25" colspan="3"><span class="small font-weight-bold mr-5">AEFI Event Record Count  : </span><span id="AEFIEventRecord_Count2" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25" colspan="3"><span class="small font-weight-bold mr-5">To Upload : </span><span id="AEFIEventRecord_ToUpload2" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25" colspan="3"><span class="small font-weight-bold mr-5">Success : </span><span id="AEFIEventRecord_Success2" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td class="w-p25" colspan="3"><span class="small font-weight-bold mr-5">Failed : </span><span id="AEFIEventRecord_Failed2" name="importDisplay" class="small text-primary font-weight-bold">0</span></td>';
          fileUploadHTML += '<td colspan="2"></td>';
  fileUploadHTML += '      </tr>';
  fileUploadHTML += '      <tr class="bg-dark" style="display:none">';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p5 text-center font-size-10" title="Excel Row">#</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold text-center font-size-10" style="width:2%"><i class="fa fa-search"></i></th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">VF ID</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">Last Name</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">First Name</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">Middle Name</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">Suffix Name</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">Date of Birth</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p10 text-center font-size-10">Sex</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p5 text-center font-size-10">AEFI</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p5 text-center font-size-10" style="display:none">AEFI Success</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold w-p5 text-center font-size-10" style="display:none">AEFI Failed</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold text-center font-size-10">Status</th>';
  fileUploadHTML += '        <th class="text-white font-weight-bold text-center font-size-10" id="ColumnRemarks" style="display:none">Remarks</th>';
  fileUploadHTML += '      </tr>';
  fileUploadHTML += '    </thead>';
  fileUploadHTML += '      <tbody>';
  fileUploadHTML += '      </tbody>';
  fileUploadHTML += '    </table>';
  fileUploadHTML += '  </div>';
  fileUploadHTML += '<div>';

  bootbox.dialog({
    size: 'xl',
    title: ((UploadTitle) ? UploadTitle+" " : "")+'Import Linelist'+filebutton,
    message: fileUploadHTML,
    closeButton:false,
    onShow: function(e) {

      $("div[id='uploadButtonsBar']").closest(".modal-header").addClass('p-5');
      $("input[id='browsefileupload']").closest(".modal-body").addClass('p-5');

      $("table[id='FileBrowseContent']").parent().css('height',($(window).height() - 275)+'px');
      $("div[id='uploadButtonsBar']").closest('h5').css('width','100%');
      
      var LineListDataFinal = {};
      var UploadingStats = false;
      $("button[name='fuButtons']").click(function(){
        switch( $(this).attr('id') )
        {
          case 'BrowseFile':
            LineListDataFinal = {};
            $("span[name*='importDisplay']").text('');
            $("table[id='FileBrowseContent'] tbody").html('');
            $("button[id='StartUpload'][name='fuButtons'],button[id='StopUpload'][name='fuButtons']").attr('disabled','disabled').hide();
            $("input[id='browsefileupload']").val('');
            $("input[id='browsefileupload']").click();
          break;

          case 'StartUpload':

                  var ss = '<div class="form-group form-material text-left" data-plugin="formMaterial">';
                    ss += '<label class="form-control-label" for="select">Linelist Case Type</label>';
                    ss += '<select class="form-control" id="selectCaseType">';
                    ss += '  <option value="">Please select Case Type</option>';
                    ss += '  <option value="Y">Serious Case</option>';
                    ss += '  <option value="N">Non-Serious Case</option>';
                    ss += '</select>';
                  ss += '</div>';
                  Swal.mixin({
                    customClass: {
                      confirmButton: 'btn btn-dark w-p45 mr-2',
                      cancelButton: 'btn btn-dark w-p45 ',
                    },
                    buttonsStyling: false,
                    willOpen: (fnRun) => {
                      $(".swal2-container").css('z-index',$.topZIndex());
                      Site.getInstance().initializePlugins();

                      $("select.swal2-select").attr('class','form-control');
                     
                    }
                  }).fire({
                    title: 'Linelist Import',
                    icon:'info',
                    html:ss,
                    
                    // width:'500px',
                    showCancelButton: true,
                    confirmButtonText: 'Start Upload',
                    cancelButtonText:'Close',
                    reverseButtons:false,
                    allowEscapeKey:false,
                    allowOutsideClick:false,
                    preConfirm: () => {
                        
                      if(!$("select[id='selectCaseType']").val()){ Swal.showValidationMessage( 'Please select Linelist Case Type!' ) }
                      else
                      {
                        return $("select[id='selectCaseType']").val();
                      }

                    }
                  }).then((result) => {
                      if (result.value)
                      {
                        $("i[id='xlsstatusLoader']").show();
                        var iFileData = new FormData();
                        iFileData.append('Serious' , result.value);
                        iFileData.append('Import_FileName' , $("span[id='FileName'][name='importDisplay']:first").text());
                        iFileData.append('Import_FileSize' , $("span[id='FileSize'][name='importDisplay']:first").text());
                        iFileData.append('Import_FileType' , $("span[id='FileType'][name='importDisplay']:first").text());
                        iFileData.append('Linelist_RecordCount' , $("span[id='LinelistRecord_ToUpload'][name='importDisplay']").text());
                        iFileData.append('Linelist_SuccessCount' , $("span[id='LinelistRecord_Success'][name='importDisplay']").text());
                        iFileData.append('Linelist_FailedCount' , $("span[id='LinelistRecord_Failed'][name='importDisplay']").text());
                        iFileData.append('Linelist_AEFIRecordCount' , $("span[id='AEFIEventRecord_ToUpload'][name='importDisplay']").text());
                        iFileData.append('Linelist_AEFISuccessCount' , $("span[id='AEFIEventRecord_Success'][name='importDisplay']").text());
                        iFileData.append('Linelist_AEFIFailedCount' , $("span[id='AEFIEventRecord_Failed'][name='importDisplay']").text());
                        var idatafileinforesult = function(pp,rp){
                          if(rp.Status == 1)
                          {
                            $("button[id='StopUpload'][name='fuButtons']").removeAttr('disabled').show();
                            window.UploadingStats = true;
                            // window.LineListDataFinal['ImportFileID'] = rp.ImportFileID;
                            $("span[name='importDisplay'][id='Status']").text('Starting Linelist Data Import/Upload, Please wait. . .');
                            Upload(rp.ImportFileID,window.LineListDataFinal,1,0);
                          }
                          else
                          {
                            $("span[name='importDisplay'][id='Status']").text('Failed to Request Linelist Import - File Information, Please try again');
                            window.UploadingStats = true;
                            $("button[id='StartUpload'][name='fuButtons'],button[id='BrowseFile'][name='fuButtons'], button[id='CloseUpload'][name='fuButtons']").show();
                            $("i[id='xlsstatusLoader']").hide();

                            Swal.mixin({
                              customClass: {
                                confirmButton: 'btn btn-dark',
                                cancelButton: 'btn btn-secondary',
                              },
                              buttonsStyling: false,
                              willOpen: (fnRun) => {
                                $(".swal2-container").css('z-index',$.topZIndex());
                              }
                            }).fire({
                              title: 'Linelist Data Import',
                              html: rp.Message,
                              icon: 'error',
                              showConfirmButton:true,
                              confirmButtonText: 'Close',
                              showCancelButton: false,
                              cancelButtonText: "Close",  
                              reverseButtons: false,
                            }).then((result) => {

                            });
                          }


                        };

                        postUrl = site_url+'fileupload/linelist/importdataFileInfo';
                        $("span[name='importDisplay'][id='Status']").text('Requesting for Linelist Import - File Information. . . .');
                        submitFormData('json','',postUrl,iFileData,idatafileinforesult,false);
                        $("button[id='StartUpload'][name='fuButtons'],button[id='BrowseFile'][name='fuButtons'], button[id='CloseUpload'][name='fuButtons']").hide();
                           
                      }
                  });

          break;

          case 'StopUpload':
            $("button[id='StopUpload'][name='fuButtons']").attr('disabled','disabled').hide();
            window.UploadingStats = false;
            $("span[name='importDisplay'][id='Status']").text('Stopping Upload Process......');
          break;
        }
      });

      $("input[id='browsefileupload']").change(function(){  
          if (this.files && this.files[0])
          {
            
            var xlsFiles = $(this)[0].files;
            var xlsFileParam = xlsFiles[0];
            $("span[name='importDisplay'][id='FileName']").text(xlsFileParam['name']);
            $("span[name='importDisplay'][id='FileSize']").text(formatBytes(xlsFileParam['size']));
            var dFileType = ( xlsFileParam['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") ? 'xlsx' : 'xls';
            $("span[name='importDisplay'][id='FileType']").text(dFileType);
            $("span[name='importDisplay'][id='Status']").text('Selected File : '+xlsFileParam['name']);

            if (!window.File || !window.FileReader || !window.FileList || !window.Blob)
            {
              alert('The File APIs are not fully supported in this browser.');
            }
            else
            {
              $("button[id='BrowseFile'][name='fuButtons']").hide();
              $("button[id='CloseUpload'][name='fuButtons']").hide();

              var xlsxflag = (dFileType == "xlsx") ? true : false;
              var reader = new FileReader();
              //For Browsers other than IE.
              $("span[name='importDisplay'][id='Status']").text('Verifying File Content : '+xlsFileParam['name']+' might take a while depending on the Record Size.');
              $("i[id='xlsstatusLoader']").show();
              if (reader.readAsBinaryString) 
              {
                reader.onload = function (e) { 
                    $("span[name='importDisplay'][id='Status']").text('Reading Linelist Sheet Record. . . .');
                    ProcessExcel(e.target.result);
                };
                reader.readAsBinaryString(xlsFileParam);
              }
              else 
              {
                //For IE Browser.
                reader.onload = function (e) {
                    var data = "";
                    var bytes = new Uint8Array(e.target.result);
                    for (var i = 0; i < bytes.byteLength; i++) {
                        data += String.fromCharCode(bytes[i]);
                    }

                    $("span[name='importDisplay'][id='Status']").text('Reading Linelist Sheet Record. . . .');
                    ProcessExcel(data);
                };
                reader.readAsArrayBuffer(xlsFileParam);
              }


            }
          }
          else
          {
            LineListDataFinal = {};
            $("span[name*='importDisplay']").text('');
            $("table[id='FileBrowseContent'] tbody").html('');
            $("button[id='StartUpload'][name='fuButtons']").attr('disabled','disabled').hide();
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

function ProcessExcel(data) {
    //Read the Excel File data.

    var workbook = XLSX.read(data, {
        type: 'binary'
    });


    var SheetsName = workbook.SheetNames;
    $("span[name='importDisplay'][id='SheetCount']").text(SheetsName.length);

    if( SheetsName.indexOf('LineList') < 0 )
    {
       $("span[name='importDisplay'][id='Status']").text('Invalid AEFI Excel Template, Linelist Sheet not detected!');
    }
    else
    {

      var Linelist = workbook.SheetNames[ SheetsName.indexOf('LineList') ];
      var LinelistexcelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[Linelist]);
      
      $("span[name='importDisplay'][id='LinelistRecord_Count']").text(LinelistexcelRows.length - 1);
      $("span[name='importDisplay'][id='LinelistRecord_Count2']").text($("span[name='importDisplay'][id='LinelistRecord_Count']").text());
     
      var LineListSheetData = {};
      var LineListGroupInfo = {};
      var AEFIListSheetData = {};
      var LinelistCount = 0;
      var grpCounter = 0;
      var grpIncrement = 1;
      var MainKeyHolder = Object.keys(LinelistexcelRows[0]);

      LineListSheetData[grpIncrement] = {};
      for (var i = 1; i < LinelistexcelRows.length; i++) 
      {
          var llDRow = LinelistexcelRows[i];

          if( llDRow['Lastname'] && llDRow['Firstname'] && llDRow['Middlename'] && llDRow['Suffixname'] && llDRow['DateofBirth'] && llDRow['Sex'] )
          {
            LinelistCount++;
            grpCounter++;

            if(grpCounter > 10)
            {
              grpIncrement++;
              grpCounter = 0;
              LineListSheetData[grpIncrement] = {};
            }

            var fLinelistRow = {};
            fLinelistRow['AdverseEvents'] = {}
            for(var ik in MainKeyHolder)
            {
              fLinelistRow[MainKeyHolder[ik]] = (MainKeyHolder[ik] in llDRow) ? llDRow[MainKeyHolder[ik]] : "";
            }

            LineListSheetData[grpIncrement][i] = fLinelistRow;
            LineListGroupInfo['Row_'+i]=grpIncrement;

            var tr  = '<tr id="llr_'+i+'" name="linelistRow" groupData="'+grpIncrement+'" DataNo="'+i+'" >';
                  tr += '<td class="font-size-10 text-uppercase text-center w-p5">'+i+'</td>';
                  tr += '<td class="font-size-10 text-uppercase text-center" style="width:2%"><i id="XLSRowID_'+i+'" groupData="'+grpIncrement+'" DataNo="'+i+'" class="fa fa-search" style="View Complete Row Content" style="cursor:pointer" onclick="javascript:rowdetails(this)"></i></td>';
                  tr += '<td class="font-size-10 text-uppercase text-center w-p10">'+llDRow['VF_SafetyID']+'</td>';
                  tr += '<td class="font-size-10 text-uppercase text-center w-p10">'+llDRow['Lastname']+'</td>';
                  tr += '<td class="font-size-10 text-uppercase text-center w-p10">'+llDRow['Firstname']+'</td>';
                  tr += '<td class="font-size-10 text-uppercase text-center w-p10">'+llDRow['Middlename']+'</td>';
                  tr += '<td class="font-size-10 text-uppercase text-center w-p10">'+llDRow['Suffixname']+'</td>';
                  tr += '<td class="font-size-10 text-uppercase text-center w-p10">'+llDRow['DateofBirth']+'</td>';
                  tr += '<td class="font-size-10 text-uppercase text-center w-p10">'+(llDRow['Sex']).replace('_M','').replace('_F','')+'</td>';
                  tr += '<td class="font-size-10 text-uppercase text-center w-p5" id="AEFICellCount_'+i+'">0</td>';
                  tr += '<td class="font-size-10 text-uppercase text-center w-p5" style="display:none" id="AEFICellCountSuccess_'+i+'">0</td>';
                  tr += '<td class="font-size-10 text-uppercase text-center w-p5" style="display:none" id="AEFICellCountFailed_'+i+'">0</td>';
                  tr += '<td class="font-size-10 text-uppercase text-center text-info" id="RowDataStatus_'+i+'"></td>';
                  tr += '<td class="font-size-10 text-uppercase text-center text-info" style="display:none" id="RowDataRemarks_'+i+'"></td>';
                tr += '</tr>';
            $("table[id='FileBrowseContent'] tbody").append(tr);
          }   
      }

      $("span[name='importDisplay'][id='LinelistRecord_ToUpload']").text(parseInt(LinelistCount));
      $("span[name='importDisplay'][id='LinelistRecord_ToUpload2']").text($("span[name='importDisplay'][id='LinelistRecord_ToUpload']").text());
      $("span[name='importDisplay'][id='Status']").text('Reading AdeverseEventList Sheet Record. . . .');

      var AdeverseEventList = workbook.SheetNames[ SheetsName.indexOf('AdeverseEventList') ];
      var AdeverseEventListexcelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[AdeverseEventList]);
      $("span[name='importDisplay'][id='AEFIEventRecord_Count']").text( (AdeverseEventListexcelRows.length > 1) ? AdeverseEventListexcelRows.length - 1 : 0);
      $("span[name='importDisplay'][id='AEFIEventRecord_Count2']").text($("span[name='importDisplay'][id='AEFIEventRecord_Count']").text());
      var AdeverseEventListCount = 0;
      for (var a = 1; a < AdeverseEventListexcelRows.length; a++) 
      {
        var adverseDRow = AdeverseEventListexcelRows[a];
        var vAid = adverseDRow['LinelistRow'] - 2;
        if( 'Row_'+vAid in LineListGroupInfo)
        {
          var AEFIDataSets = {
                'MeddraCode':adverseDRow['MeddraCode'],
                'DateAEFI_Started':adverseDRow['DateAEFI_Started'],
                'TimeAEFI_Started':adverseDRow['TimeAEFI_Started']
              };

          if( AEFIDataSets['MeddraCode'] && AEFIDataSets['DateAEFI_Started'] && AEFIDataSets['TimeAEFI_Started'] )
          {
            AdeverseEventListCount++;
            LineListSheetData[LineListGroupInfo['Row_'+vAid]][vAid]['AdverseEvents'][a] = AEFIDataSets;
            var caefiCnt = Object.keys(LineListSheetData[LineListGroupInfo['Row_'+vAid]][vAid]['AdverseEvents']).length;
            $("td[id='AEFICellCount_"+vAid+"']").text(caefiCnt);
          }
        }
      }

      $("span[name='importDisplay'][id='AEFIEventRecord_ToUpload']").text(parseInt(AdeverseEventListCount));
      $("span[name='importDisplay'][id='AEFIEventRecord_ToUpload2']").text(parseInt($("span[name='importDisplay'][id='AEFIEventRecord_ToUpload']").text()));
      $("span[name='importDisplay'][id='Status']").text('Reading Linelist and AdeverseEventList Completed, Please click Upload Button to Start Import Process.');

      $("span[name='importDisplay'][id='LinelistRecord_Success'],span[name='importDisplay'][id='LinelistRecord_Failed'],span[name='importDisplay'][id='AEFIEventRecord_Success'],span[name='importDisplay'][id='AEFIEventRecord_Failed']").text(parseInt(0));

      if(LinelistCount > 0)
      {
        $("button[id='StartUpload'][name='fuButtons']").removeAttr('disabled').show();
        window.LineListDataFinal=LineListSheetData;

      }
    }
    
    $("button[id='BrowseFile'][name='fuButtons']").show();
    $("button[id='CloseUpload'][name='fuButtons']").show();
    $("i[id='xlsstatusLoader']").hide();
};

function Upload(importfileid,data,cnt,dcnt)
{
  // console.log(data);
  if(window.UploadingStats)
  {
    if(cnt <= Object.keys(data).length )
    {
      var SetContent = data[cnt];
      var ldcnt = (dcnt == 0) ? 1 : dcnt;
      for(var ikey in SetContent)
      {
        $("td[id='RowDataStatus_"+ikey+"']").html('<i class="fa fa-spinner icon-spin"></i><i class="ml-5">Processing. . .</i>');
        dcnt++;
      }

      $("span[name='importDisplay'][id='Status']").text('Processing Record '+ldcnt+' to '+dcnt+' out of '+parseInt($("span[id='LinelistRecord_ToUpload']").text())+' Linelist Record');
      $("i[id='xlsstatusLoader']").show();

      var PostimportResult = function(pp,rp){
        // console.log(rp);
        clearTimeout(skipLoop);
        if(rp.Status == 1)
        {
          var vData = rp.ImportResult;
          for(var vr in vData)
          {
            var istatus = '';
            if(vData[vr]['ImportStatus'] == 1)
            {
              istatus = '<i class="fa fa-check text-success mr-5"></i>CIF Control No : \''+vData[vr]['CIFControlNo']+"'";
              $("span[id='LinelistRecord_Success']").text( parseInt($("span[id='LinelistRecord_Success']").text()) + 1 );
              $("span[id='LinelistRecord_Success2']").text( parseInt($("span[id='LinelistRecord_Success']").text()) );

              for( var aefiE in  vData[vr]['AdverseEvents'] )
              {
                if(vData[vr]['AdverseEvents'][aefiE]['importstatus'] == 1)
                {
                  $("span[id='AEFIEventRecord_Success']").text( parseInt($("span[id='AEFIEventRecord_Success']").text()) + 1 );
                  $("span[id='AEFIEventRecord_Success2']").text( parseInt($("span[id='AEFIEventRecord_Success']").text()) );

                  $("td[id='AEFICellCountSuccess_"+vr+"']").text( parseInt($("td[id='AEFICellCountSuccess_"+vr+"']").text()) + 1 );
                  
                }
                else
                {
                  $("span[id='AEFIEventRecord_Failed']").text( parseInt($("span[id='AEFIEventRecord_Failed']").text()) + 1);
                  $("span[id='AEFIEventRecord_Failed2']").text( parseInt($("span[id='AEFIEventRecord_Failed']").text()) );

                  $("td[id='AEFICellCountFailed_"+vr+"']").text( parseInt($("td[id='AEFICellCountFailed_"+vr+"']").text()) + 1 ); 
                  $("td[id='RowDataRemarks_"+vr+"']").text( $("td[id='RowDataRemarks_"+vr+"']").text() + vData[vr]['AdverseEvents'][aefiE]['importRemarks'] );
                }
              }
            }
            else
            {
              istatus = '<i class="fa fa-times text-danger mr-5"></i>Data Import Failed!';
              $("td[id='RowDataRemarks_"+vr+"']").html(vData[vr]['ImportRemarks']);
              $("span[id='LinelistRecord_Failed']").text( parseInt($("span[id='LinelistRecord_Failed']").text()) + 1 );
              $("span[id='LinelistRecord_Failed2']").text( parseInt($("span[id='LinelistRecord_Failed']").text()) );
              $("span[id='AEFIEventRecord_Failed']").text( parseInt($("span[id='AEFIEventRecord_Failed']").text()) + ((vData[vr].hasOwnProperty('AdverseEvents')) ? Object.keys(vData[vr]['AdverseEvents']).length : 0 )  );
              $("span[id='AEFIEventRecord_Failed2']").text( parseInt($("span[id='AEFIEventRecord_Failed']").text()) );

              $("td[id='AEFICellCountFailed_"+vr+"']").text( parseInt( ((vData[vr].hasOwnProperty('AdverseEvents')) ? Object.keys(vData[vr]['AdverseEvents']).length : 0 ) )  );
            }

            $("td[id='RowDataStatus_"+vr+"']").html(istatus).attr("title",vData[vr]['ImportRemarks']);
          }
        }
        else
        {
          for(var pk in pp)
          {
            if(pk !== "UToken" && pk !=='importfileid')
            {
              $("td[id='RowDataRemarks_"+pk+"']").html("Unable to Process Request");
              $("span[id='LinelistRecord_Failed']").text( parseInt($("span[id='LinelistRecord_Failed']").text()) + 1 );
              $("span[id='LinelistRecord_Failed2']").text( parseInt($("span[id='LinelistRecord_Failed']").text()) );
              $("td[id='RowDataStatus_"+pk+"']").html('<i class="fa fa-times text-danger mr-5"></i>Data Import Failed!').attr("title",'Unable to Process Request');
              $("span[id='AEFIEventRecord_Failed']").text( parseInt($("span[id='AEFIEventRecord_Failed']").text()) + ((pp[pk].hasOwnProperty('AdverseEvents')) ? Object.keys(pp[pk]['AdverseEvents']).length : 0 ) );
              $("span[id='AEFIEventRecord_Failed2']").text( parseInt($("span[id='AEFIEventRecord_Failed']").text()) );

              $("td[id='AEFICellCountFailed_"+pk+"']").text( parseInt( ((pp[pk].hasOwnProperty('AdverseEvents')) ? Object.keys(pp[pk]['AdverseEvents']).length : 0 ) )  );
            }
          }
        }

        cnt++; // Will be moved inside ajax event
        Upload(importfileid,data,cnt,dcnt); // Will be moved inside ajax event

      };

      SetContent['importfileid'] = importfileid;
      postUrl = site_url+'fileupload/linelist/importdata';
      submitFormData('json','',postUrl,SetContent,PostimportResult,false);

      var skipLoop = setTimeout(function(importfileid,data,cnt,dcnt){
        var SetContent = data[cnt];
        for(var pk in SetContent)
        {
            $("td[id='RowDataRemarks_"+pk+"']").html("Unable to Process Request");
            $("span[id='LinelistRecord_Failed']").text( parseInt($("span[id='LinelistRecord_Failed']").text()) + 1 );
            $("span[id='LinelistRecord_Failed2']").text( parseInt($("span[id='LinelistRecord_Failed']").text()) );
            $("td[id='RowDataStatus_"+pk+"']").html('<i class="fa fa-times text-danger mr-5"></i>Data Import Failed!').attr("title",'Unable to Process Request');
            $("span[id='AEFIEventRecord_Failed']").text( parseInt($("span[id='AEFIEventRecord_Failed']").text()) + ((SetContent[pk].hasOwnProperty('AdverseEvents')) ? Object.keys(SetContent[pk]['AdverseEvents']).length : 0 ) );
            $("span[id='AEFIEventRecord_Failed2']").text( parseInt($("span[id='AEFIEventRecord_Failed']").text()) );

            $("td[id='AEFICellCountFailed_"+pk+"']").text( parseInt( ((SetContent[pk].hasOwnProperty('AdverseEvents')) ? Object.keys(SetContent[pk]['AdverseEvents']).length : 0 ) )  );
        }

        cnt++; // Will be moved inside ajax event
        Upload(importfileid,data,cnt,dcnt); // Will be moved inside ajax event

      },30000,importfileid,data,cnt,dcnt);

    }
    else
    {
      $("span[name='importDisplay'][id='Status']").text('All '+parseInt($("span[id='LinelistRecord_ToUpload']").text())+' Linelist Record successfully processed, please review the List for Status and Remarks');
      $("i[id='xlsstatusLoader']").hide();
      $("button[id='BrowseFile'][name='fuButtons'], button[id='CloseUpload'][name='fuButtons']").show();
      $("button[id='StopUpload'][name='fuButtons']").attr('disabled','disabled').hide();

      

      Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-dark',
            cancelButton: 'btn btn-secondary',
          },
          buttonsStyling: false,
          willOpen: (fnRun) => {
            $(".swal2-container").css('z-index',$.topZIndex());
          }
        }).fire({
          title: 'Linelist Data Import',
          html: 'Linelist Import successfully Completed,  please review the Downloaded List for Status and Remarks ',
          icon: 'success',
          showConfirmButton:true,
          confirmButtonText: 'Close',
          showCancelButton: false,
          cancelButtonText: "Close",  
          reverseButtons: false,
        }).then((result) => {
            $("table[id='FileBrowseContent']").table2excel({
                name: "Linelist Import Result",
                filename: "Linelist Import Result" //do not include extension
            }); 

            oTable_Obj['importlinelistdatalist'].draw(); 
        });
    }
  }
  else
  {
    $("span[name='importDisplay'][id='Status']").text('Linelist Uploading successfully stopped at record '+(dcnt+1)+', please download and review status and results!');
    $("button[id='BrowseFile'][name='fuButtons'], button[id='CloseUpload'][name='fuButtons']").show();
    $("i[id='xlsstatusLoader']").hide();
    $("button[id='StopUpload'][name='fuButtons']").attr('disabled','disabled').hide();

    Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-dark',
          cancelButton: 'btn btn-secondary',
        },
        buttonsStyling: false,
        willOpen: (fnRun) => {
          $(".swal2-container").css('z-index',$.topZIndex());
        }
      }).fire({
        title: 'Linelist Data Import',
        html: 'Linelist Import Interrupted,  please review the Downloaded List for Status and Remarks ',
        icon: 'error',
        showConfirmButton:true,
        confirmButtonText: 'Close',
        showCancelButton: false,
        cancelButtonText: "Close",  
        reverseButtons: false,
      }).then((result) => {
          $("table[id='FileBrowseContent']").table2excel({
              name: "Linelist Import Result",
              filename: "Linelist Import Result" //do not include extension
          }); 

          oTable_Obj['importlinelistdatalist'].draw(); 
      });
  }
}


function rowdetails(ele)
{
  console.log($(ele).attr('groupdata'));
}