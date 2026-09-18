<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
getreferencevalue({objselect:$("select[id='Category'][name='Category'][forminputgroup='faqformdata']"),referenceid:'9',filter:'',order:''},'','SELECT CATEGORY');
getreferencevalue({objselect:$("select[id='Enable'][name='Enable'][forminputgroup='faqformdata']"),referenceid:'36',filter:'',order:''},'','');

reqfld = ['Title','Category','Enable','Message_Content'];

$("div[groupdiv*='summernote']").summernote({
  placeholder: '',
  tabsize: 2,
  dialogsInBody:false,
  height: 380,
  tabDisable:false,
  codeviewFilter: true,
  codeviewIframeFilter: true,
  disableResizeEditor: true,
  disableResizeImage: true,
  toolbar: [
    // ['font',['fontsize','fontsizeunit']],
    ['style', ['bold','italic','underline']],
    // ['style2', ['strikethrough','superscript','subscript','clear']],
    // ['color',['color','forecolor','backcolor']],
    ['para', ['ul', 'ol', 'height']],
    // ['insert', ['table','link', 'picture']],
    // ['misc', ['undo', 'redo']]
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

        $("[id='"+$(this).attr('id')+"'][name='"+$(this).attr('id')+"'][forminputgroup='faqformdata']").val(contents);

      }
  }
});

$("[forminputgroup='faqformdata']").each(function(){
  
  $(this).attr({
    'oplaceholder': ($(this).hasAttr('oplaceholder')) ? $(this).attr('oplaceholder') : $(this).attr('placeholder'),
    'title':$("label[for='"+$(this).attr('id')+"']").text()
  });

  if( reqfld.indexOf($(this).attr('id')) != -1 )
  {
    if( $("i[id='required_"+$(this).attr('id')+"']").length == 0)
    {
      $("label[for='"+$(this).attr('id')+"']").addClass('w-p100 text-left').append('<i class="text-danger float-right fa fa-fw fa-asterisk" id="required_'+$(this).attr('id')+'"></i>');
    } 
  }

  $(this).attr('curval',$(this).attr('dfvalue'));
});
</script>