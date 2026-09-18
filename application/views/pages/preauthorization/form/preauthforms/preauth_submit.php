<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\FpdiProtection\FpdiProtection;
use PHPCypherFile\PHPCypherFile;
?>

<div id="preauth_submit_maincontent" class="row">
	<?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>
	<div class="col-lg-12 col-md-12">
		<div class="card border border-info">
			<div class="card-body p-5">
				<h5><i class="text-info fa fa-fw fa-info-circle mr-10"></i>Instruction/Reminders : <span class="float-right text-danger">Maximum PDF File Size : 10MB | Password protected and encrypted PDF File cannot be uploaded</span></h5>
				<ul>
					<li>Click <b>Browse/Replace File</b> to Upload/Attach PDF File.</li>
					<li>Select PDF File then press "open".</li>
					<li>To view selected PDF File -> click the PDF Icon <i class="fa fa-file-pdf-o"></i> beside the selected PDF File Name.</li>
				</ul>
			</div>
		</div>
	</div>
	<?php }else{ echo '<div class="col-lg-12 col-md-12"><div class="card border border-info"><div class="card-body p-5"><h5 class="text-success">To view attachment/s file click PDF Icon <i class="fa fa-file-pdf-o"></i> then enter your account password.</h5></div></div></div>'; } ?>
	<?php
	$afcnt = (@$CI->system_settings['AttachmentFileCnt'] == '' || @$CI->system_settings['AttachmentFileCnt'] == 0 ) ? 6 : $CI->system_settings['AttachmentFileCnt'];
	for($ai=1;$ai<=$afcnt;$ai++)
	{
		
		$fileInfo = [];
		$fileClick = '';
		$fileuploadid = '';
		if( is_array(@$preauthFormData['attachments']) && isset($preauthFormData['attachments'][$ai - 1]) )
		{
			$fileuploadid = @$preauthFormData['attachments'][$ai - 1]['uploadid'];

			$fileInfo = $CI->sqlhelper->local->select('system_fileupload')->where("DataCount = '".$fileuploadid."'")->row();
			$fileInfo  = $fileInfo['Data'];

			$BlobContent = file_get_contents( ( strpos($fileInfo['File_Path'],"_enc") > -1 && file_exists( $fileInfo['File_Path']) ) ? $fileInfo['File_Path'] : str_replace("_enc.pdf",".pdf",$fileInfo['File_Path']) );

			if( strpos($fileInfo['File_Path'],"_enc") > -1 && file_exists($fileInfo['File_Path']) == true )
      		{
      			if( @$CI->userprofile['oPrivateKey'] <> '' )
	          	{
	          		$decryptedFile = str_replace("_enc.pdf","_dec.pdf",$fileInfo['File_Path']);
	          		$f_uprofile = $CI->myutilities->getUserProfile($fileInfo['Created_By']);
	          		$privKey = $f_uprofile['oPrivateKey'];
	          		$privKeyKey = $f_uprofile['User_E_Key'];
	          		$privateKey = openssl_pkey_get_private($CI->m_api->encryptdecryptString('decrypt',$CI->system_settings['PasswordHashing'],$privKey,'MCrypt','aes-128','ecb'),$privKeyKey );
	      			PHPCypherFile::decryptFile($fileInfo['File_Path'], $decryptedFile, $privateKey);
	          	}

	          	if( file_exists( $decryptedFile ) ) 
	          	{
	          		$BlobContent = file_get_contents($decryptedFile);
	          		$fileInfo['File_Path'] = $decryptedFile;
	          	}

      		}
      		else
      		{
      			$fileInfo['File_Path'] = str_replace("_enc.pdf",".pdf",$fileInfo['File_Path']);
      		}

			$pdf = new FpdiProtection('P', 'mm','A4', true);
			if( in_array((int) $CI->userclassification, [1,6,7]) )
			{
				$ownerPassword = $pdf->setProtection(
				    FpdiProtection::PERM_PRINT | FpdiProtection::PERM_COPY,
				    $CI->session->pdfpassword,
				    $CI->system_settings['PasswordHashing']
				);
			}
			else
			{
				$ownerPassword = $pdf->setProtection(
				    FpdiProtection::PERM_PRINT,
				    $CI->session->pdfpassword,
				    $CI->system_settings['PasswordHashing']
				);
			}

			preg_match_all('!\d+!', $BlobContent, $matches);
			$pdfversion = implode('.', $matches[0]);
			$pdfversion = substr($pdfversion,0,3);
			if($pdfversion > "1.4")
			{
				if (file_exists(str_replace(".pdf","_.pdf",$fileInfo['File_Path']))) 
				{
					unlink(str_replace(".pdf","_.pdf",$fileInfo['File_Path']));
				}

				if( strtoupper(substr(PHP_OS, 0, 3)) == "WIN" )
				{
					exec('gswin64 -dBATCH -dNOPAUSE -dQUIET -sDEVICE=pdfwrite -dCompatibilityLevel="1.4" -sOutputFile="'.str_replace(".pdf","_.pdf",$fileInfo['File_Path']).'" "'.$fileInfo['File_Path'].'" 2>&1',$execoutput);
				}
				else
				{
					exec('gs -dBATCH -dNOPAUSE -dQUIET -sDEVICE=pdfwrite -dCompatibilityLevel="1.4" -sOutputFile="'.str_replace(".pdf","_.pdf",$fileInfo['File_Path']).'" "'.$fileInfo['File_Path'].'" 2>&1',$execoutput);
				}
			}

			if (file_exists(str_replace(".pdf","_.pdf",$fileInfo['File_Path']))) 
			{
				$pageCount = $pdf->setSourceFile(str_replace(".pdf","_.pdf",$fileInfo['File_Path']));
			}
			else
			{
				$pageCount = $pdf->setSourceFile($fileInfo['File_Path']);
			}
		
	        // iterate through all pages
	        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
	          // import a page
	          $templateId = $pdf->importPage($pageNo);
	          // get the size of the imported page
	          $size = $pdf->getTemplateSize($templateId);

	          // create a page (landscape or portrait depending on the imported page size)
	          $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));

	          // use the imported page
	          $pdf->useTemplate($templateId);
	          // $pdf->SetFont('Helvetica');
	          // $pdf->SetXY(5, 5);
	          // $pdf->Write(8, 'Generated by FPDI');
	        }

	       	$BlobContent = $pdf->Output($fileInfo['File_FileName'],'S');

	       	if( isset($decryptedFile) && file_exists( $decryptedFile ) ) 
          	{
          		unlink($decryptedFile);
          	}

	        $pdf->Close();

			$fla = [
			 'FileName' => $fileInfo['File_FileName'],
			 'FileType' => 'application/pdf',
			 'FileData' => base64_encode($BlobContent),
			 'nonce'    => $CI->nonceV
			];

			$fileClick = 'onclick="javascript:ObjectFileViewer(\''.base64_encode(json_encode($fla)).'\')" ';
		}

		$filehtml = '
		<div class="col-md-6 col-lg-6 mb-10">
			<div class="card mb-0 bg-secondary" style="">
				<div id="cardbody" class="card-body p-10 bg-white" style="">
					<div class="row">
						<div class="col-md-10 col-lg-10">
							<div class="form-group form-material form-material-file mb-0" data-plugin="formMaterial">
								<label class="label font-weight-bold text-uppercase"  for="attachment['.$ai.']">Attachment '.$ai.' : </label>
								<input type="text" class="form-control text-uppercase font-size-16" placeholder="Browse / Replace File" inputfor="attachment['.$ai.']" value="'.@$fileInfo['File_FileName'].'" dvalue="'.@$fileInfo['File_FileName'].'" '.( (@$fileClick <> '' && @$ForCompliance == false) ? 'disabled' : '').' readonly>
								'.( (!@$ViewMode ||  @$ForCompliance == true) ? '
				                <input type="file" id="attachment['.$ai.']" name="attachment" fid="'.@$fileuploadid.'" title="Browse File" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" autocomplete="false" style="" accept="application/pdf">
				                ' : '' ).'
				                '.( ( @$ForCompliance == true && $fileuploadid) ? '<input type="hidden" id="attachment[Replacement_'.@$fileuploadid.']" name="attachment[Replacement_'.@$preauthFormData['attachments'][$ai - 1]['uploadid'].']" forminputgroup="preauthFormData" forminputgroupcheck="preauthFormData" autocomplete="false" value="">' : '').'
				            </div>
			            </div>
			            <div class="col-md-2 col-lg-2 text-center">
			            	<i id="FileViewer_attachment['.$ai.']" class="fa fa-solid fa-file-pdf font-size-50 '.(($fileClick == '') ? 'invisible' : '').'" role="button" title="View Attachment" '.@$fileClick.'></i>
			            </div>
		            </div>
				</div>
			</div>
		</div>
		';

		echo (@$ViewMode <> true || @$ForCompliance == true) ? $filehtml : ((@$fileClick <> '') ? $filehtml : '');
	}

	?>
 
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){
	<?php if(@$ViewMode <> true || @$ForCompliance == true){ ?>
	$("input[type='file'][id*='attachment[']").each(function(){
		$(this).change(function(){		
			fileattachedcnt  = 0; 
		    const fileitem = new DataTransfer();
		    var filenames = [];
		    var fileTypes = [
		     'application/pdf'
		    ];   

		    file = $(this)[0].files;

		    if(file.length > 0)
		    {
		    	file = file[0];

			    if( jQuery.inArray( file['type'], fileTypes ) < 0 || file['size'] > 10000000 )
			    {
		     		$("input[inputfor='"+$(this).attr('id')+"']").val('');
				    $(this).val('');
      		    	if( $("input[type='hidden'][id='attachment[Replacement_"+$(this).attr('fid')+"]'][forminputgroup='preauthFormData']").length > 0 )
				    {
				    	$("input[type='hidden'][id='attachment[Replacement_"+$(this).attr('fid')+"]'][forminputgroup='preauthFormData']").val('');
				    }
			     	$("i[id='FileViewer_"+$(this).attr('id')+"']").attr('onclick',"").hide();

					Swal.mixin({
						customClass: {
						  confirmButton: 'btn btn-danger',
						},
						buttonsStyling: false,
						willOpen: (fnRun) => {
						  $(".swal2-container").css('z-index',$.topZIndex());
						}
					}).fire({
						title: "Invalid Attachment File",
						html: 'Attachment must be a PDF File with maximum file size of 10mb!',
						icon: "error",
						confirmButtonText: 'Close',
					});

			    }
			    else
			    {
			      var VieweEleID = $(this).attr('id');
			      var fid =  $(this).attr('fid');
			     
			      var reader = new FileReader();
			      reader.onload = function (e,a,eleID) {

			        x = e.target.result;

			        var xfiles = new Blob([dataURLToBlob(x)], {type: 'application/pdf'});
			       
					xfiles.text().then(xy=> {
						if( (xy.substring(xy.lastIndexOf("<<"), xy.lastIndexOf(">>")).includes("/Encrypt") ) && xy.includes("Encrypt") )
						{
							$("input[inputfor='"+VieweEleID+"']").val('');
				    		$("input[type='file'][id='"+VieweEleID+"']").val('');
				    		$("i[id='FileViewer_"+$(this).attr('id')+"']").attr('onclick',"").hide();
					    	if( $("input[type='hidden'][id='attachment[Replacement_"+$(this).attr('fid')+"]'][forminputgroup='preauthFormData']").length > 0 )
						    {
						    	$("input[type='hidden'][id='attachment[Replacement_"+$(this).attr('fid')+"]'][forminputgroup='preauthFormData']").val('');
						    }

						    Swal.mixin({
								customClass: {
								  confirmButton: 'btn btn-danger',
								},
								buttonsStyling: false,
								willOpen: (fnRun) => {
								  $(".swal2-container").css('z-index',$.topZIndex());
								}
							}).fire({
								title: "Invalid Attachment File",
								html: 'Password protected and encrypted documents cannot be uploaded!',
								icon: "error",
								confirmButtonText: 'Close',
							});

						}
						else
						{
							var fla = {
					          'FileName' : file.name,
					          'FileType' : file.type,
					          'FileData' : '',
					          'FilePath' : e.target.result,
					          'nonce'    : '<?php echo $CI->nonceV; ?>'
					        };

					        $("i[id='FileViewer_"+VieweEleID+"']").attr('onclick',"javascript:ObjectFileViewer('"+btoa(JSON.stringify(fla))+"')").removeClass('invisible').show();
					        if( $("input[type='hidden'][id='attachment[Replacement_"+fid+"]'][forminputgroup='preauthFormData']").length > 0 )
						    {
						    	$("input[type='hidden'][id='attachment[Replacement_"+fid+"]'][forminputgroup='preauthFormData']").val('Y');
						    }
						}
					});

			      };
			    
			      reader.readAsDataURL(file);
			    }
		    }
		   	else
		   	{
		   		$("input[inputfor='"+$(this).attr('id')+"']").val('');
			    $(this).val('');
			    $("i[id='FileViewer_"+$(this).attr('id')+"']").attr('onclick',"").hide();
		    	if( $("input[type='hidden'][id='attachment[Replacement_"+$(this).attr('fid')+"]'][forminputgroup='preauthFormData']").length > 0 )
			    {
			    	$("input[type='hidden'][id='attachment[Replacement_"+$(this).attr('fid')+"]'][forminputgroup='preauthFormData']").val('');
			    }
		   	}
		});
	});
	<?php } ?>
});
</script>


