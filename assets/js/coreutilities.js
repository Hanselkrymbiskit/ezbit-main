function dataURLToBlob(dataURL) {
    // Split the data URL into two parts
    const [header, base64Data] = dataURL.split(',');

    // Decode the base64 string
    const byteString = atob(base64Data);

    const mimeString = header.split(':')[1].split(';')[0];

    // Create an array of 8-bit unsigned integers
    const byteArray = new Uint8Array(byteString.length);
    for (let i = 0; i < byteString.length; i++) {
        byteArray[i] = byteString.charCodeAt(i);
    }

    // Create a Blob from the byte array
    return new Blob([byteArray], { type: mimeString });
}

function checkPasswordStrength(inputs,minlength) {
    var number = /([0-9])/;
    var alphabets = /([a-zA-Z])/;
    var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/; 
    var rscore = 0;
    var rmess = ''; 
  
    if (inputs.val().length < parseInt(minlength)) { 
        rscore = 1;
        rmess = "Weak (should be atleast "+parseInt(minlength)+" characters.)";
    } else {
        if (inputs.val().match(number) && inputs.val().match(alphabets) && inputs.val().match(special_characters)) {
          rscore = 3;
          rmess = "";
        } else { 
          rscore = 2;
          rmess = "Medium (should include alphabets, numbers and special characters.)";
        }
    }

    return {'score':rscore,'message':rmess};
}

// function ExcelTimetoJSDate(timestring)
// {
//   let fromExcel = 0,709722222222222; //translates to 17:02:00
//   let basenumber = (fromExcel*24)
//   let hour = Math.floor(basenumber).toString();
//   if (hour.length < 2) {
//       hour = '0'+hour;
//   }

//   var minute = Math.round((basenumber % 1)*60).toString();
//   if (minute.length < 2) {
//    minute = '0'+minute;
//   }
//   let Timestring = (hour+':'+minute+':00');

//   return Timestring;
// }
function copyToClipboard(text) {
    var sampleTextarea = document.createElement("textarea");
    document.body.appendChild(sampleTextarea);
    sampleTextarea.value = text; //save main text in it
    sampleTextarea.select(); //select textarea contenrs
    document.execCommand("copy");
    document.body.removeChild(sampleTextarea);
}

function ExcelDateToJSDate(serial) {
   var utc_days  = Math.floor(serial - 25569);
   var utc_value = utc_days * 86400;                                        
   var date_info = new Date(utc_value * 1000);

   var fractional_day = serial - Math.floor(serial) + 0.0000001;
   var total_seconds = Math.floor(86400 * fractional_day);
   var seconds = total_seconds % 60;
   total_seconds -= seconds;

   var hours = Math.floor(total_seconds / (60 * 60));
   var minutes = Math.floor(total_seconds / 60) % 60;

   return  moment(new Date(date_info.getFullYear(), date_info.getMonth(), date_info.getDate())).format('MM/DD/YYYY');
}

function ExcelTimetoJSDate(excel_date, time = false) {
  let day_time = excel_date % 1
  let meridiem = "AMPM"
  let hour = Math.floor(day_time * 24)
  let minute = Math.floor(Math.abs(day_time * 24 * 60) % 60)
  let second = Math.floor(Math.abs(day_time * 24 * 60 * 60) % 60)
  hour >= 12 ? meridiem = meridiem.slice(2, 4) : meridiem = meridiem.slice(0, 2)
  hour > 12 ? hour = hour - 12 : hour = hour
  hour = hour < 10 ? "0" + hour : hour
  minute = minute < 10 ? "0" + minute : minute
  second = second < 10 ? "0" + second : second
  let daytime = "" + hour + ":" + minute + ":" + second + " " + meridiem
  return time ? daytime : (new Date(0, 0, excel_date, 0, -new Date(0).getTimezoneOffset(), 0)).toLocaleDateString(navigator.language, {}) + " " + daytime
};

function checkIt(evt) {
  evt = (evt) ? evt : window.event
  var charCode = (evt.which) ? evt.which : evt.keyCode
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    return false
  }
  return true  ;                 
} 

function isNumberKey(evt)
{
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || (charCode > 57 && charCode != 190 && charCode != 110)))
   {
      return false;
   }

   return true;
}

function isCharKey(evt)
{
  var regex = new RegExp("^[a-zA-ZñÑ\\u00f1\\u00d1\\u00C0-\\u00FF ]*$");
  var strigChar = String.fromCharCode(!evt.charCode ? evt.which : evt.charCode);
  if (regex.test(strigChar)) {
      return true;
  }
  return false
}

function isPasswordAllowed(evt)
{
  var regex = new RegExp("^[a-zA-Z0-9ñÑ\\u00f1\\u00d1\\u0600-\\u06FF\\u0660-\\u0669\\u06F0-\\u06F9 _.-~!@#$%^&*-_+=?><]*$");
  var strigChar = String.fromCharCode(!evt.charCode ? evt.which : evt.charCode);
  if (regex.test(strigChar)) {
      return true;
  }
  return false
}

function isAlphaNum(evt)
{
  var regex = new RegExp("^[a-zA-Z0-9ñÑ\\u00f1\\u00d1\\u0600-\\u06FF\\u0660-\\u0669\\u06F0-\\u06F9 -]*$");
  var strigChar = String.fromCharCode(!evt.charCode ? evt.which : evt.charCode);
  if (regex.test(strigChar)) {
      return true;
  }
  return false
}

function isAllowedChar(evt)
{
  var regex = new RegExp("^[a-zA-Z0-9ñÑ\\u00f1\\u00d1\\u0600-\\u06FF\\u0660-\\u0669\\u06F0-\\u06F9 ()\\[\\]\\,_.-]*$");
  var strigChar = String.fromCharCode(!evt.charCode ? evt.which : evt.charCode);
  if (regex.test(strigChar)) {
      return true;
  }
  return false
}

function isDecimalKey(evt,obj)
{
 var charCode = (evt.which) ? evt.which : event.keyCode
 var value = obj.value;
 var dotcontains = value.indexOf(".") != -1;
 if (dotcontains)
     if (charCode == 46) return false;
 if (charCode == 46) return true;
 if (charCode > 31 && (charCode < 48 || charCode > 57))
     return false;
}

function isDecimalKeyNegative(evt,obj)
{
 var charCode = (evt.which) ? evt.which : event.keyCode
 var value = obj.value;
 var dotcontains = value.indexOf(".") != -1;
 var negativecontains = value.indexOf("-") != -1;
 // alert(negativecontains);
  if (dotcontains)
  {
   if (charCode == 46) return false;
  }
   
  if (negativecontains)
  {
    if (charCode == 45)
    {
      return false;
    }
  }
     
  if (charCode == 46){ return true; }
  if (charCode == 45)
  { 
    if(  !negativecontains && value.length !== 0 )
    {
      return false;
    }
    return true; 
  } 

 if (charCode > 31 && (charCode < 48 || charCode > 57))
 {
    return false;
 }
     
}


const hexToRGB = hex => {
   let r = 0, g = 0, b = 0;
   // handling 3 digit hex
   if(hex.length == 4){
      r = "0x" + hex[1] + hex[1];
      g = "0x" + hex[2] + hex[2];
      b = "0x" + hex[3] + hex[3];
      // handling 6 digit hex
   }else if (hex.length == 7){

      r = "0x" + hex[1] + hex[2];
      g = "0x" + hex[3] + hex[4];
      b = "0x" + hex[5] + hex[6];
   };

   return [+r,+g,+b];
}
const isFunction = value => value && (Object.prototype.toString.call(value) === "[object Function]" || "function" === typeof window[value] || value instanceof Function);

$.fn.hasAttr = function(name) {  
   return this.attr(name) !== undefined;
};

function CreatePDFfromHTML(htmlElement,fileName) {
    
    fullscreen = typeof fullscreen !== 'undefined' ? fullscreen : false;

    var HTML_Width = htmlElement.width();
    var HTML_Height = htmlElement.height();
    var top_left_margin =15;
    var PDF_Width = HTML_Width + (top_left_margin * 2);
    var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;
    var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

    html2canvas(htmlElement[0]).then(function (canvas) {
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) { 
            pdf.addPage(PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
        }
        pdf.save(fileName+".pdf");
    });
}


function lightOrDark(color) {

    // Variables for red, green, blue values
    var r, g, b, hsp;
    
    // Check the format of the color, HEX or RGB?
    if (color.match(/^rgb/)) {

        // If RGB --> store the red, green, blue values in separate variables
        color = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);
        
        r = color[1];
        g = color[2];
        b = color[3];
    } 
    else {
        
        // If hex --> Convert it to RGB: http://gist.github.com/983661
        color = +("0x" + color.slice(1).replace( 
        color.length < 5 && /./g, '$&$&'));

        r = color >> 16;
        g = color >> 8 & 255;
        b = color & 255;
    }
    
    // HSP (Highly Sensitive Poo) equation from http://alienryderflex.com/hsp.html
    hsp = Math.sqrt(
    0.299 * (r * r) +
    0.587 * (g * g) +
    0.114 * (b * b)
    );

    // Using the HSP value, determine whether the color is light or dark
    if (hsp>127.5) {

        return 'light';
    } 
    else {

        return 'dark';
    }
}
function IsURL(url) {
    return /^(?:\w+:)?\/\/([^\s\.]+\.\S{2}|localhost[\:?\d]*)\S*$/.test(url); 
 }


function capitalizeFirstLetter(string) {
    return string.replace(/^./, string[0].toUpperCase());
}

function numberWithCommas(x) {
    return (x) ? x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : x;
}

function validateEmail(email) { 
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

Date.monthsDiff= function(day1,day2){
  var d1= day1,d2= day2;
  if(day1<day2){
    d1= day2;
    d2= day1;
  }
  var m= (d1.getFullYear()-d2.getFullYear())*12+(d1.getMonth()-d2.getMonth());
  if(d1.getDate()<d2.getDate()) --m;
  return m;
}
function calculateAspectRatioFit(srcWidth, srcHeight, maxWidth, maxHeight) {
    var ratio = Math.min(maxWidth / srcWidth, maxHeight / srcHeight);
    var rtnWidth = srcWidth * ratio;
    var rtnHeight = srcHeight * ratio;
    return {
        width: rtnWidth,
        height: rtnHeight
    };
}
function formatBytes(a,b=2){if(0===a)return"0 Bytes";const c=0>b?0:b,d=Math.floor(Math.log(a)/Math.log(1024));return parseFloat((a/Math.pow(1024,d)).toFixed(c))+" "+["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"][d]}

function DateChecker(Date1,Date2)
{
  var D1=Date1.split("/"); // Split into Month = DateSelected[0], Day = DateSelected[1], Year = DateSelected[2];
  var D2=Date2.split("/"); // Split into Month = DateSelected[0], Day = DateSelected[1], Year = DateSelected[2];
  var Result="";

  // Selected Date Year > Current Date Year
  if(Date1 == Date2) 
  {
    Result="equal";
  }
  else
  {

    // Selected Date Year = Current Date Year and Date Month > Current Date Month
    if((D1[2] > D2[2]))
    {
      Result="greater";
    }
    else if((D1[2] < D2[2]))
    {
      Result="less";
    }
    else // Equal in Year check for Month
    {
      if((D1[0] > D2[0]))
      {
        Result="greater";
      }
      else if((D1[0] < D2[0]))
      {
        Result="less";
      }
      else // Equal in Month check for Days
      {
        if((D1[1] > D2[1]))
        {
          Result="greater";
        }
        else if((D1[1] < D2[1]))
        {
          Result="less";
        }
        else // Equal in Month check for Days
        {
          Result="equal";
        }
      }
    }
  }
  return Result;
}

/** 
 * Forward port jQuery.live()
 * Wrapper for newer jQuery.on()
 * Uses optimized selector context 
 * Only add if live() not already existing.
// */


var loaderState = false;
function loader(show)
{ 
  show = typeof show !== 'undefined' ? show : true;
  if(typeof show !== 'boolean')
  {
    show = true;
  }

  var ldisplay = `
    <div style="margin-bottom:1rem">
      <img class="navbar-brand-logo" src="`+assetPath+`images/logo@2x.png" title="PHILHEALTH" style="height: 70px;width:70px;">
    </div>
    <div class="loader-index"><div></div><div></div><div></div><div></div><div></div><div></div></div>
  `;

  if(show)
  {
    Swal.mixin({
        willOpen: (fnRun) => {
          $(".swal2-container").css('z-index',$.topZIndex());
          loaderState = true;
        },
        willClose: (fnRun) => {
          console.log(loaderState);
        }
    }).fire({
      html: ldisplay,
      theme:'borderless',
      showConfirmButton: false,
      allowOutsideClick:false,
      allowEscapeKey:false,
    });

    // $.blockUI({ 
    //   message: ldisplay,
    //   css: { 
    //     backgroundColor: 'transparent', 
    //     color: '#000',
    //     border:'0px',
    //     'z-index':$.topZIndex() + 2000,
    //   },
    //   overlayCSS: { 
    //     backgroundColor: '#011902',
    //     opacity: '0.9',
    //     'z-index': $.topZIndex() + 2000,
    //   },
    //   onBlock:function(){ 
    //     loaderState = true;
    //     $("body").css({overflow:'hidden'});
    //   }
    //  });
  }
  else
  {
    // $.unblockUI({
    //   onUnblock:function(){ 
    //     loaderState = false;
    //     $("body").css({overflow:'auto'});
    //   }
    // });
    
    Swal.close()
  }
}


//
/*
  Reference for Select Element Drop Down via Ajax Request
  refobj = {
    'objselect'   : '',
    'referenceid' : '',
    'filter'      : '',
    'order'       : '',
  };
*/
let SelectReferenceObjectList = {};
function getreferencevalue(refobj,customObj,nullvaluetext,valueonly)
{
  refobj = typeof refobj !== 'undefined' ? refobj : '';
  nullvaluetext = typeof nullvaluetext !== 'undefined' ? nullvaluetext : '';
  xSelectReferenceObjectList = typeof customObj !== 'undefined' ? customObj : SelectReferenceObjectList;
  valueonly = typeof valueonly !== 'undefined' ? valueonly : false;

  try
  {
    var refvalue = '';
    if( typeof(refobj) == 'object' && Object.keys(refobj).length > 0 )
    {
      var posturl = site_url+"utilities/getReferenceValue"; 
      var postparam = new FormData();
      postparam.append('Objselect',$(refobj.objselect).attr('id'));
      postparam.append('ObjselectID',$(refobj.objselect).attr('id'));
      postparam.append('ObjselectName',$(refobj.objselect).attr('name'));
      postparam.append('DefaultValue',$(refobj.objselect).attr('dfvalue'));
      postparam.append('ReferenceID',refobj.referenceid);
      postparam.append('Filter',btoa(refobj.filter));
      postparam.append('Order',refobj.order);

      var callcompleted = function(postparam,returnparam){ 
     
        var postparamData = {};
        for(var pair of postparam.entries()) {
           postparamData[pair[0]] = pair[1]; 
          
        }
        postparam = postparamData;
        if( typeof(postparam) == 'object' && typeof(returnparam) == 'object' )
        {
          var pp = postparam;
          var rp = returnparam;
          if(valueonly)
          {
            return returnparam;
          }
          else
          {
            var parentID = (xSelectReferenceObjectList.hasOwnProperty(pp.ObjselectID)) ? xSelectReferenceObjectList[pp.ObjselectID]['parentfieldid'] : "";
            var parentName = (xSelectReferenceObjectList.hasOwnProperty(pp.ObjselectName)) ? xSelectReferenceObjectList[pp.ObjselectID]['parentfieldname'] : parentID;
            var selectele = $("select[id='"+pp.ObjselectID+"'][name='"+pp.ObjselectName+"']");
            var floatinglbl = ( $(selectele).hasOwnProperty('floatinglblclass') && $(selectele).attr('floatinglblclass') ) ? true : false; 
            var floatingmaterial = ( $(selectele).closest("div[class*='form-group form-material']").hasClass("floating") ) ? true : false;
            var optionnulltext = '';

            if( parentID !== "")
            {
              var SelectParentEle = $("select[id='"+parentID+"'][name='"+parentName+"']");
              optionnulltext = (floatinglbl) ? '' : ( (SelectParentEle.hasOwnProperty('title') ) ? 'Please Select '+SelectParentEle.attr('title') : ''); 
            }

            if( rp.Status == 1 )
            {      
              
              var selectoption = rp.Message;

              var optionnulltext = ($(selectele).hasOwnProperty('title')) ? (($(selectele).hasOwnProperty('title')) ? "Select "+$(selectele).attr('title') : '') : nullvaluetext;
              optionnulltext = (floatinglbl || floatingmaterial) ? '' : optionnulltext;
              // console.log(parentID);
              if( parentID !== "")
              {
                var SelectParentEle = $("select[id='"+parentID+"'][name='"+parentName+"']");
                if(SelectParentEle.val())
                {
                  $(selectele).html('<option value="">'+optionnulltext+'</option>');
                }
              }
              else
              {
                $(selectele).html('<option value="">'+optionnulltext+'</option>');
              }
              
              for(var op in selectoption)
              {
                var opitem = '<option value="'+selectoption[op]['ref_value']+'">'+selectoption[op]['ref_description']+'</option>';
                $(selectele).append(opitem);
              }

              if( typeof pp.DefaultValue !== 'undefined' && pp.DefaultValue !== "" )
              {
                if( $("select[id='"+pp.ObjselectID+"'] option[value='"+pp.DefaultValue+"']").length > 0 )
                {
                  $(selectele).val(pp.DefaultValue); 
                }   
              }

              $(selectele).change();
            } 
            else
            {
              $(selectele).html('<option value="">'+optionnulltext+'</option>');
            }
          }



        }

      };

      if(!valueonly)
      {
        $(refobj.objselect).html('<option value=""></option>');
      }
      
      submitFormData('json','',posturl,postparam,callcompleted,false);
    }
  }
  catch(error)
  {
       console.log(error);
  }
}

function addSelectReferenceObjectList(objid,objprop)
{
  objid = typeof objid !== 'undefined' ? objid : '';
  objprop = typeof objprop !== 'undefined' ? objprop : '';

  if(objid !== '' && typeof objprop == 'object' )
  {
    if( !SelectReferenceObjectList.hasOwnProperty(objid) )
    {
      SelectReferenceObjectList[objid] = objprop;
    }
  }
}
  
function loadselectreference(customobj)
{
   customobj = typeof customobj !== 'undefined' ? customobj : SelectReferenceObjectList;
   var SelectObj = customobj;
   try
   {
     if(Object.keys(SelectObj).length > 0)
     {
        var RefList = SelectObj;
        for(var SelectID in RefList)
        {
          if( !SelectReferenceObjectList.hasOwnProperty(SelectID) )
          {
            SelectReferenceObjectList[SelectID] = RefList[SelectID];
          }

          if( SelectReferenceObjectList[SelectID]['objectloaded'] !== true  )
          {
            var rFloating = (RefList[SelectID].hasOwnProperty('floatinglblclass')) ? ( ( typeof RefList[SelectID]['floatinglblclass'] == 'boolean' ) ? RefList[SelectID]['floatinglblclass'] : false )  : false;
            var SelectEle = $("select[id='"+SelectID+"'][name='"+SelectID+"']"+( (RefList[SelectID].hasOwnProperty('forminputgroup')) ? "[forminputgroup='"+RefList[SelectID]['forminputgroup']+"']" : "")); 
            if( !SelectEle.hasAttr('floatinglblclass') && rFloating )
            {
              SelectEle.attr('floatinglblclass',true);
            }

            refobj = {
              'objselect'   : $(SelectEle),
              'referenceid' : RefList[SelectID]['referenceid'],
              'filter'      : RefList[SelectID]['referencefilter'],
              'order'       : RefList[SelectID]['referenceorder'],
            };

            if( RefList[SelectID]['parentfieldid'] == '' ||  RefList[SelectID]['parentfieldid'] == 'undefined')
            {
              getreferencevalue(refobj,SelectObj,( ($(SelectEle).hasAttr('title')) ? 'SELECT '+($(SelectEle).attr('title')).toUpperCase() : ''));
            }
            else
            {
              var SelectParentEle = $("select[id='"+RefList[SelectID]['parentfieldid']+"'][name='"+RefList[SelectID]['parentfieldid']+"']"+( (RefList[SelectID].hasOwnProperty('parentforminputgroup')) ? "[forminputgroup='"+RefList[SelectID]['parentforminputgroup']+"']" : "")+"");
            }
           
            if( RefList[SelectID]['childfieldid'] !== "" )
            {
              if($(SelectEle).prop('functionchange') !== 'true' )
              {
                $(SelectEle).change(function(){ 
                
                  if( SelectObj[$(this).attr('id')]['childfieldid'] !== "" )
                  {
                    var ChildFldID = SelectObj[$(this).attr('id')]['childfieldid'];
                    var RefConfigChild = SelectObj[ChildFldID] ; 
                    if(typeof RefConfigChild == 'object')
                    {
                      childrefobj = {
                        'objselect'   : $("select[id='"+ChildFldID+"'][name='"+ChildFldID+"']"+( (SelectObj[$(this).attr('id')].hasOwnProperty('childforminputgroup')) ? ( (!SelectObj[$(this).attr('id')]['childforminputgroup'] || SelectObj[$(this).attr('id')]['childforminputgroup'] == 'undefined') ? '' : "[forminputgroup='"+SelectObj[$(this).attr('id')]['childforminputgroup']+"']") : '' )+""),
                        'referenceid' : ( RefConfigChild.hasOwnProperty('referenceid')) ? RefConfigChild['referenceid'] : '',
                        'filter'      : ( (RefConfigChild['referencefilter'] !== '' ) ? RefConfigChild['referencefilter']+' AND ' : '') + SelectObj[$(this).attr('id')]['childfieldfilter']+" = '"+$(this).val()+"'",
                        'order'       : RefConfigChild['referenceorder'],
                      }; 
                      getreferencevalue(childrefobj,SelectObj,( ($(childrefobj['objselect']).hasOwnProperty('title')) ? 'SELECT '+($(childrefobj['objselect']).attr('title')).toUpperCase() : ''));
                    }
                  }
                });

                $(SelectEle).attr('functionchange','true');
                SelectReferenceObjectList[SelectID]['functionchange'] = true;
              }
              
            }

            SelectReferenceObjectList[SelectID]['objectloaded'] = true;
          }

          

        }
     }
   }
   catch(err)
   {

   }
}

/** Datatable Function Utilities - Start - Germel Sevilla **/
/** Declare DataTable Object Handler **/
function searchResultAlert(targettableid,messagecontent)
{
  targettableid = typeof targettableid !== 'undefined' ? targettableid : '';
  messagecontent = typeof messagecontent !== 'undefined' ? messagecontent : '';
  
  var htmlResult =  '<div id="SearchBoxResultDisplay_'+targettableid+'" class="alert alert-info fade show" role="alert">';
      htmlResult +=   '<h6 class="alert-heading"><strong>Search Result for  </strong> <span style="cursor:pointer" class="float-right close" aria-hidden="true" data-dismiss="alert" aria-label="Close" title="Close Search Result">&times;</span></h6>';
      htmlResult +=   '<p style="margin-bottom: 0px;">'+messagecontent+'</p>';
      htmlResult += '</div>';

  return htmlResult;
}



/* DataTable -- Start */
var oTable_Obj = new Object();
var oTable_Obj_Return = new Object();

function gtsDataTable(DataTable_Property,CallonLoad)
{
    DataTable_Property.target_table = typeof DataTable_Property.target_table !== 'undefined' ? DataTable_Property.target_table : '';
    DataTable_Property.ajax_url = typeof DataTable_Property.ajax_url !== 'undefined' ? DataTable_Property.ajax_url : '';
    DataTable_Property.fixedcolumns = typeof DataTable_Property.fixedcolumns !== 'undefined' ? DataTable_Property.fixedcolumns : false;
    DataTable_Property.scrollY = typeof DataTable_Property.scrollY !== 'undefined' ? DataTable_Property.scrollY : '600';
    DataTable_Property.scrollX = typeof DataTable_Property.scrollX !== 'undefined' ? DataTable_Property.scrollX : false;
    DataTable_Property.scroller = typeof DataTable_Property.scroller !== 'undefined' ? DataTable_Property.scroller : false;
    DataTable_Property.order = typeof DataTable_Property.order !== 'undefined' ? DataTable_Property.order : '';
    DataTable_Property.columnDefs = typeof DataTable_Property.columnDefs !== 'undefined' ? DataTable_Property.columnDefs : '';
    DataTable_Property.dataexport = typeof DataTable_Property.dataexport !== 'undefined' ? DataTable_Property.dataexport : false;
    DataTable_Property.exporttitle = typeof DataTable_Property.exporttitle !== 'undefined' ? DataTable_Property.exporttitle : 'Exported Data';
    DataTable_Property.chkboxon = typeof DataTable_Property.chkboxon !== 'undefined' ? DataTable_Property.chkboxon : false;
    DataTable_Property.indexcol = typeof DataTable_Property.indexcol !== 'undefined' ? DataTable_Property.indexcol : false;
    DataTable_Property.searchable = typeof DataTable_Property.searchable !== 'undefined' ? DataTable_Property.searchable : 0; 
    DataTable_Property.advancesearchable = typeof DataTable_Property.advancesearchable !== 'undefined' ? DataTable_Property.advancesearchable : 0; 
    DataTable_Property.callback = typeof DataTable_Property.callback !== 'undefined' ? DataTable_Property.callback : '';
    DataTable_Property.keysColumn = typeof DataTable_Property.keysColumn !== 'undefined' ? DataTable_Property.keysColumn : '';
    DataTable_Property.lengthChange = typeof DataTable_Property.lengthChange !== 'undefined' ? DataTable_Property.lengthChange : true;

    CallonLoad = typeof CallonLoad !== 'undefined' ? CallonLoad : '';

    if(DataTable_Property.target_table && DataTable_Property.ajax_url)
    {
      var TargetTable = "DTTable_"+DataTable_Property.target_table;

      var IncludeAttrPost = {
        'TableID' : DataTable_Property.target_table
      };

      for(var aia in IncludeAttrPost)
      {
        var attrTMP = new Object();
        attrTMP[aia] = IncludeAttrPost[aia];
        $("table[id='"+TargetTable+"']").data(aia,attrTMP);
        attrTMP='';
      }
      
      fwidth = 75;
      if( !DataTable_Property.lengthChange && !DataTable_Property.dataexport)
      {
        fwidth = 100;
      }

      var DomHeader = '<"#DTTable_Header_'+DataTable_Property.target_table+'.DTTable_header" <"#DTTable_Header_Content_'+DataTable_Property.target_table+'"  <"float-left w-p25" <"#DTTable_ListChange_'+DataTable_Property.target_table+'.DTTable_listchange float-left mr-2"l>'+( (DataTable_Property.dataexport) ? '<"#DTTable_Buttons_'+DataTable_Property.target_table+'.DTTable_buttons float-left "B>' : '')+'>     <"float-right w-p'+fwidth+'" <"#DTTable_Filter_'+DataTable_Property.target_table+'.DTTable_filter "> > > >';
      var DomBody   = '<"#DTTable_Body_'+DataTable_Property.target_table+'" <"#DTTable_Body_Content.table-responsive" <"#DTTable_Tables_'+DataTable_Property.target_table+'.DTTable_body"rt>>>';
      var DomFooter = '<"#DTTable_Footer_'+DataTable_Property.target_table+'.DTTable_footer" <"#DTTable_Footer_Content_'+DataTable_Property.target_table+'" <"float-left w-p30" <"#DTTable_Infoholder_'+DataTable_Property.target_table+'.DTTable_infoholder"i>> <"float-right w-p70" <"#DTTable_Pagination_'+DataTable_Property.target_table+'.float-right"p> <"#DTTable_FocusedCell_'+DataTable_Property.target_table+'.mt-10 mr-20 float-right">> > >';
      var DomDisplay = DomHeader+DomBody+DomFooter;
   
      oTable_Obj[DataTable_Property.target_table] = $("table[id='"+TargetTable+"']").DataTable( {
          "processing": false,
          "serverSide": true,
          "lengthChange":DataTable_Property.lengthChange,
          "lengthMenu": [[10, 25, 50, 100, -1],[10, 25, 50, 100, "ALL"]],
          "pageLength":10,
          "pagingType":"full_numbers",
          "language":{
              "info": ((!DataTable_Property.scroller) ? "<span style='margin-right:10px'>Page _PAGE_ of _PAGES_</span>" : "")+"<span class='pull-right'>(showing _START_ to _END_ of _TOTAL_ data)</span>",
              "lengthMenu":"_MENU_",  
              // "processing":'<span style=""><i class="fa fa-fw fa-circle-notch fa-spin fa-2x"></i><br>Processing</span>',
              "emptyTable":'<b>No Data Available</b>',
              "infoFiltered": "",
              "infoEmpty": '<b>No Data to Display</b>',
              "zeroRecords": "<b>No Data to Display</b>"
          },
          "searching":true,
          // "ordering":isSorted,
          "ordering": true,
          "order": ( (DataTable_Property.order !== '') ? DataTable_Property.order : [] ),
          "dom":DomDisplay,
          // "fixedColumns": DataTable_Property.fixedcolumns, //((!DataTable_Property.scroller) ? false : DataTable_Property.fixedcolumns),
          "scrollY": parseInt(DataTable_Property.scrollY),
          "scrollX": DataTable_Property.scrollX,
          "scroller": DataTable_Property.scroller,
          "scrollCollapse":true,
          // "select": ((!DataTable_Property.chkboxon) ? false : {
          //   // 'style': 'multi',
          //   // 'selector':  '.dt-checkboxes',//'td:first-child'+((DataTable_Property.indexcol) ? '+td' : ''), // 'td:nth-child('+((DataTable_Property.indexcol) ? 1 : 0)+')',
          //   // 'className':'selected bg-secondary text-white'
          // }),
          // "responsinve":false,
          "keys":{
              'className': 'itemfocused border-bottom border-secondary font-weight-bold',
              'clipboard':false,
              'columns':DataTable_Property.keysColumn
          },
          "colReorder":false,
          "search": {
            "smart": true
          },   

          "buttons": [
                      {
                          "extend": "collection",
                          "className":"btn-dark",
                          "text": "<i class='fa fa-fw fa-download'></i>",
                          "buttons": [
                              {
                                "extend": "excelHtml5",
                                "extension": ".xlsx",
                                "title": DataTable_Property.exporttitle+" List",
                                "text":  "<span title='Download Data in Excel File Format' style='cursor:pointer'><i class='fa fa-fw fa-file-excel-o' style='margin-right:10px'></i>Excel Format</span>",
                                "exportOptions":{"columns":"thead th:not(.noExport)"},
                              },
                              {
                                "extend": "csvHtml5",
                                "extension": ".csv",
                                "title" :DataTable_Property.exporttitle+" List",
                                "text": "<span title='Download Data in CSV File Format' style='cursor:pointer'><i class='fa fa-fw  fa-file-text-o' style='margin-right:10px'></i>CSV Format</span>",
                                "exportOptions":{"columns":"thead th:not(.noExport)"},
                              }
                          ]
                      }
                   ],
          "order": DataTable_Property.order,
          "ajax":function(data,callback,settings){

                  $.extend(data, $("table[id='"+TargetTable+"']").data);

                  var DataTableATTR = ['filterParam','DefaultFilter','TableID'];
                  for(var x in DataTableATTR)
                  {
                    if( typeof DataTableATTR[x] == 'string')
                    {
                       if($("table[id='"+TargetTable+"']").data(DataTableATTR[x]))
                       {
                           $.extend(data,$("table[id='"+TargetTable+"']").data(DataTableATTR[x]));
                       }
                    }
                  }
                  
                  data['UToken'] = UToken;
                  data['pushparameter'] = DataTable_Property.pushparameter;
                  if (typeof csrfName !== 'undefined')
                  {
                    if( csrfName !== '')
                    {
                      data[csrfName] = csrfHash;
                    }
                  }
                  // Call Ajax Event with the Following Settings
                  settings.jqXHR = $.ajax( {
                      "dataType": 'json',
                      "type": "POST",
                      "url":  DataTable_Property.ajax_url,
                      "data": data,
                      "success": function(json) {
                          callback(json);
                          oTable_Obj_Return[DataTable_Property.target_table] = json;
                          NProgress.done();
                          $("i[id='ilisticon_"+DataTable_Property.target_table+"']").addClass('fa-list').removeClass('fa-spinner icon-spin');
                          // CallBack after DataTable Draw
                          if( DataTable_Property.callback !== "" )
                          {
                            var mcallback = DataTable_Property.callback;
                            if(  typeof mcallback == 'function' )
                            {
                              mcallback(json);
                            }
                            else if (mcallback && isFunction(mcallback) ) 
                            {
                              var mmcallback = window[mcallback];
                              mmcallback(json);
                            }
                          }
                      },
                      "error": function (request, status, error){
                        
                        var ejson = {
                          "draw"            : 1,
                          "recordsTotal"    : 0,
                          "recordsFiltered" : 0,
                          "data"            : []
                        };
                        
                        callback(ejson);
                        oTable_Obj_Return[DataTable_Property.target_table] = ejson;

                        NProgress.done();
                        $("i[id='ilisticon_"+DataTable_Property.target_table+"']").addClass('fa-list').removeClass('fa-spinner icon-spin');
                        if( DataTable_Property.callback !== "" )
                        {
                          var mcallback = DataTable_Property.callback;
                          if(  typeof mcallback == 'function' )
                          {
                            mcallback(ejson);
                          }
                          else if (mcallback && isFunction(mcallback) ) 
                          {
                            var mmcallback = window[mcallback];
                            mmcallback(ejson);
                          }
                        }
                      },
                      "complete": function(request,status){
                       // console.log(status);
                      }
                  });

                  // console.log(settings.jqXHR);

                  NProgress.configure({ 
                      showSpinner: false,
                      parent: "div[id='DataTable_MainPanel_"+DataTable_Property.target_table+"']", //"body",//"div[id='DataTable_MainPanel_"+DataTable_Property.target_table+"']",
                      template: '<div class="bar nprogress-bar-dark" role="bar"></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div>',
                  });
              
                  NProgress.start();
                  NProgress.set(0.4);

                  $("i[id='ilisticon_"+DataTable_Property.target_table+"']").addClass('fa-spinner icon-spin').removeClass('fa-list');
          },
          "drawCallback": function (oSettings) {
            // console.log(oSettings);
            $('.dataTables_paginate > .pagination').addClass('mb-0');
            $("select[name='DTTable_"+DataTable_Property.target_table+"_length'").addClass('border border-secondary').css('height','35px');
            $("table[id='DTTable_"+DataTable_Property.target_table+"']").parent().addClass('scrollbar-dark thin');
            
            if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
              $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
            }
          },
          "columnDefs": DataTable_Property.columnDefs
        } ); 
        
        if(DataTable_Property.scrollX)
        {
          // console.log(DataTable_Property.fixedcolumns);
          new $.fn.dataTable.FixedColumns( oTable_Obj[DataTable_Property.target_table] ,DataTable_Property.fixedcolumns);
        }
        
        // oTable_Obj[DataTable_Property.target_table].on( 'draw.dt', function (e,settings){
        //   setTimeout(function(){ oTable_Obj[DataTable_Property.target_table].columns.adjust() },350); 
        // });

        // Add Listener if Search has been made
        oTable_Obj[DataTable_Property.target_table].on( 'draw.dt order.dt search.dt', function (e,settings){
          setTimeout(function(){ oTable_Obj[DataTable_Property.target_table].columns.adjust() },350); 
          $("div[id='DTTable_FocusedCell_"+DataTable_Property.target_table+"']").html('');
          if(DataTable_Property.indexcol)
          {
            var pinfo = oTable_Obj[DataTable_Property.target_table].page.info();
            oTable_Obj[DataTable_Property.target_table].column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cntr=parseInt(pinfo.start)+i+1
                cell.innerHTML = cntr;
                rowData = oTable_Obj[DataTable_Property.target_table].row( i ).data();
                rowData[0]=cntr;
            });

          }
          
          $("a[grouplink][datatable='"+DataTable_Property.target_table+"']").each(function(){
              if( $(this).hasAttr('fmethod') )
              {
                $(this).unbind('click').click(function(){
                  if( $(this).attr('fmethod') !== "" )
                  {
                    var methoccalllink = $(this).attr('fmethod');
                    if(  typeof methoccalllink == 'function' )
                    {
                      methoccalllink($(this).attr('dataid'));
                    }
                    else if (methoccalllink && isFunction(methoccalllink) ) 
                    {
                      var mmethoccalllink = window[methoccalllink];
                      mmethoccalllink($(this).attr('dataid'));
                    }
                  }
                });
              }        
          });

        });

        if( $("button[id='btn_AddRecords'][name='DataTable_ActionButtons_"+DataTable_Property.target_table+"']").length )
        {
          $("button[id='btn_AddRecords'][name='DataTable_ActionButtons_"+DataTable_Property.target_table+"']").unbind('click').click(function(){
             
              if( $(this).hasAttr('fmethod') && $(this).attr('fmethod') )
              {
                var addMString = $(this).attr('fmethod'); 
                if( IsURL(addMString) )
                {
                  window.location.href = addMString;
                }
                else
                {
                  if(  typeof addMString == 'function' )
                  {
                    addMString();
                  }
                  else if (addMString && isFunction(addMString) ) 
                  {
                    var addFunctionCall = window[addMString];
                    addFunctionCall();
                  }
                }
              }
          });
        }

        if( DataTable_Property.chkboxon )
        {
          
          if( $("button[id='btn_EditSelected'][name='DataTable_ActionButtons_"+DataTable_Property.target_table+"']").length || $("button[id='btn_DeleteSelected'][name='DataTable_ActionButtons_"+DataTable_Property.target_table+"']").length )
          {
            oTable_Obj[DataTable_Property.target_table].on( 'select', function ( e, dt, type, indexes ) {
                  
                var selectedRowDataCount = oTable_Obj[DataTable_Property.target_table].rows( { selected: true } ).count();
                var CheckBoxHTML = oTable_Obj[DataTable_Property.target_table].rows( { selected: true } ).data()[indexes][1];
                // var ChkBoxDataID = $(CheckBoxHTML).find("[type='checkbox']").attr('dataid');
                
                // $("input[type='checkbox'][dataid='"+ChkBoxDataID+"']").attr('checked','checked');

                if( parseInt(selectedRowDataCount) > 0 )
                {
                  if( parseInt(selectedRowDataCount) == 1 )
                  {
                    
                    $("button[name='DataTable_ActionButtons_"+DataTable_Property.target_table+"'][id='btn_EditSelected']").removeAttr('disabled');
                  }
                  else
                  {
                    $("button[name='DataTable_ActionButtons_"+DataTable_Property.target_table+"'][id='btn_EditSelected']").attr('disabled','disabled');
                  }

                  $("button[name='DataTable_ActionButtons_"+DataTable_Property.target_table+"'][id='btn_DeleteSelected']").removeAttr('disabled');
                }
            } )
            .on( 'deselect', function ( e, dt, type, indexes ) {
                var selectedRowDataCount = oTable_Obj[DataTable_Property.target_table].rows( { selected: true } ).count();
                var CheckBoxHTML = oTable_Obj[DataTable_Property.target_table].rows( { selected: true } ).data()[indexes][1];
                // var ChkBoxDataID = $(CheckBoxHTML).find("[type='checkbox']").attr('dataid');
                //  $("input[type='checkbox'][dataid='"+ChkBoxDataID+"']").removeAttr('checked');
                if( parseInt(selectedRowDataCount) == 0 )
                {
                  $("button[name='DataTable_ActionButtons_"+DataTable_Property.target_table+"'][id='btn_EditSelected'],button[name='DataTable_ActionButtons_"+DataTable_Property.target_table+"'][id='btn_DeleteSelected']").attr('disabled','disabled');
                }
                else
                {
                  if( parseInt(selectedRowDataCount) == 1 )
                  {
                    $("button[name='DataTable_ActionButtons_"+DataTable_Property.target_table+"'][id='btn_EditSelected']").removeAttr('disabled');
                  }
                  else
                  {
                    $("button[name='DataTable_ActionButtons_"+DataTable_Property.target_table+"'][id='btn_EditSelected']").attr('disabled','disabled');
                  }
                }
            } );

            $("button[id='btn_EditSelected'][name='DataTable_ActionButtons_"+DataTable_Property.target_table+"'], button[id='btn_DeleteSelected'][name='DataTable_ActionButtons_"+DataTable_Property.target_table+"']").unbind('click').click(function(){

              var functionString = $(this).attr('fmethod'); 
              var selectedRowDataCount = oTable_Obj[DataTable_Property.target_table].rows( { selected: true } ).count();
              if( selectedRowDataCount > 0 && typeof functionString !== 'undefined' )
              {
                var selectedRowData= oTable_Obj[DataTable_Property.target_table].rows( { selected: true } ).data().toArray();
                var SelectedDataObject = {};
                for(var r in selectedRowData)
                {
                  var dataidCol = selectedRowData[r][((DataTable_Property.indexcol) ? 1 : 0)].replace(/\n/g, "");
                  SelectedDataObject[ selectedRowData[r]['DT_RowId'] ] = $(dataidCol).find('label').attr('for');
                }

                switch( $(this).attr('id') )
                {
                  
                  case "btn_EditSelected":
                    
                    if( selectedRowDataCount == 1)
                    {
                      if(  typeof functionString == 'function' )
                      {
                        functionString(SelectedDataObject);
                      }
                      else if (functionString && isFunction(functionString) ) 
                      {
                        var editFunctionCall = window[functionString];
                        editFunctionCall(SelectedDataObject);
                      }
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
                        title: "Invalid Record to Edit/Modify",
                        text: 'Please select 1 record to Edit/Modify',
                        icon: "error",
                        confirmButtonText: 'Close',
                      });
                    }
                    
                  break;

                  case "btn_DeleteSelected":
                    Swal.mixin({
                      customClass: {
                        confirmButton: 'btn btn-warning ml-1',
                        cancelButton: 'btn btn-secondary',
                      },
                      buttonsStyling: false,
                      willOpen: (fnRun) => {
                        $(".swal2-container").css('z-index',$.topZIndex());
                      }
                    }).fire({
                      title:  "Delete Selected Record(s)?",
                      text: "You have selected "+( (selectedRowDataCount > 1) ? "a total of "+selectedRowDataCount+" records" : selectedRowDataCount+" record"),
                      icon: 'warning',
                      showCancelButton: true,
                      cancelButtonText: "No",
                      showConfirmButton:true,
                      confirmButtonText: 'Yes',
                      reverseButtons: true,
                    }).then((result) => {
                      if (result.value) 
                      {
                        // Call Function for Delete
                        if(  typeof functionString == 'function' )
                        {
                          functionString(SelectedDataObject);
                        }
                        else if (functionString && isFunction(functionString) ) 
                        {
                          var deleteFunctionCall = window[functionString];
                          deleteFunctionCall(SelectedDataObject);
                        }
                      }
                    });
                  break;
                }
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
                  title: "Undefined Method Call!",
                  html: 'Please Define Method Call for this Button Element<br>[ '+$(this).attr('title')+' ]',
                  icon: "error",
                  confirmButtonText: 'Close',
                });
              }
            });
          }   
        }

        if( parseInt(DataTable_Property.searchable) > 0 && typeof DataTable_Property.searchable == "number")
        {
            var searhBoxHTML = '';
            searhBoxHTML += '<div class="form-group form-material" data-plugin="formMaterial">';
            searhBoxHTML += '   <div class="input-group">';
            // searhBoxHTML += '    <span class="input-group-addon"><i class="fa fa-search fa-fw mt-2"></i></span>';
            searhBoxHTML += '    <div class="form-control-wrap">';
            searhBoxHTML += '      <input type="text" class="form-control" id="DTSearch_Quick_'+DataTable_Property.target_table+'" name="DTSearch_Quick_'+DataTable_Property.target_table+'" placeholder="Quick Search..." autocomplete="off">';
            searhBoxHTML += '    </div>';
            searhBoxHTML += '    <span class="input-group-btn">';
            searhBoxHTML += '      <button class="btn btn-dark waves-effect waves-classic" type="button" groupbutton="DTSearchButton_'+DataTable_Property.target_table+'" id="btn_QuickSearch" title="Quick Search"><i class="fa fa-search fa-fw"></i></button>';
            
            if(parseInt(DataTable_Property.advancesearchable) > 0 && typeof DataTable_Property.advancesearchable == "number" )
            {
              searhBoxHTML += '      <button class="btn btn-dark waves-effect waves-classic" type="button" groupbutton="DTSearchButton_'+DataTable_Property.target_table+'" id="btn_AdvanceSearch" title="Advance Search" data-toggle="collapse" data-target="#AdvanceSearchBox_'+DataTable_Property.target_table+'" aria-expanded="false" aria-controls="AdvanceSearchBox_'+DataTable_Property.target_table+'"><i class="fa fa-search-plus fa-fw"></i></button>';
            }
            
            searhBoxHTML += '      <button class="btn btn-dark waves-effect waves-classic" type="button" groupbutton="DTSearchButton_'+DataTable_Property.target_table+'" id="btn_ClearSearch" title="Clear Search"><i class="fa fa-refresh fa-fw"></i></button>';
            searhBoxHTML += '    </span>';
            searhBoxHTML += '  </div>';
            searhBoxHTML += '</div>';

            $("div[id='DataTable_MainPanel_Body_TableContainer_"+DataTable_Property.target_table+"']").collapse();

            $("button[id='AdvanceSearchButton_"+DataTable_Property.target_table+"']").unbind('click').click(function(){
                       
                        var paramFilter = new Object();
                        var searchContentTxt = {};
                        
                        paramFilter['addedFilter'] = new Object();
                        paramFilter['addedFilterMode'] = new Object();

                        var searchFormData = $("form[id='AdvanceSearchForm_"+DataTable_Property.target_table+"']").serializeArray();
                                        
                        for(var si in searchFormData)
                        {
                          var sfRow = searchFormData[si];
                          var eleID = sfRow['name'];

                          if(eleID !== dtFlds)
                          {
                            if( eleID !== 'AdvanceSearchStrict_'+DataTable_Property.target_table )
                            {
                              if(sfRow['value'])
                              {
                                if( eleID.includes('SearchConditions_'))
                                {
                                  paramFilter['addedFilterMode'][eleID.replace('SearchConditions_','').replace('[FROM]','').replace('[TO]','')] = sfRow['value'];
                                }
                                else
                                {       

                                  if( paramFilter['addedFilter'].hasOwnProperty( eleID.replace('[FROM]','').replace('[TO]','') ) )
                                  {
                                    if( eleID.includes('[FROM]') || eleID.includes('[TO]'))
                                    {
                                      paramFilter['addedFilter'][eleID.replace('[FROM]','').replace('[TO]','')][( (eleID.includes('[FROM]')) ? 'FROM' : 'TO')] = sfRow['value'];
                                      searchContentTxt[eleID] = $("[name='"+eleID+"'][forminputgroup='DTTableAdvanceSearch_"+DataTable_Property.target_table+"']").val(); 
                                    }
                                    else
                                    {
                                      paramFilter['addedFilter'][eleID] = paramFilter['addedFilter'][eleID]+","+sfRow['value'];
                                      searchContentTxt[eleID] = searchContentTxt[eleID]+", "+$("label[for='"+eleID+"_"+sfRow['value']+"']").text();
                                    }
                                  }
                                  else
                                  {
                                    if( eleID.includes('[FROM]') || eleID.includes('[TO]'))
                                    {
                                      paramFilter['addedFilter'][eleID.replace('[FROM]','').replace('[TO]','')] = { 'FROM' : '', 'TO':'' };
                                      paramFilter['addedFilter'][eleID.replace('[FROM]','').replace('[TO]','')][( (eleID.includes('[FROM]')) ? 'FROM' : 'TO')] = sfRow['value'];
                                    }
                                    else
                                    {
                                      paramFilter['addedFilter'][eleID] = sfRow['value'];
                                    }

                                    switch( $("[name='"+sfRow['name']+"'][forminputgroup='DTTableAdvanceSearch_"+DataTable_Property.target_table+"']").prop("tagName") )
                                    {
                                      case "INPUT":
                                        var inputTYPE = $("[name='"+sfRow['name']+"'][forminputgroup='DTTableAdvanceSearch_"+DataTable_Property.target_table+"']").attr('type');
                                        if( inputTYPE == "checkbox" || inputTYPE == "radio" )
                                        {
                                          
                                          searchContentTxt[eleID] = $("label[for='"+eleID+"_"+sfRow['value']+"']").text();
                                        }
                                        else
                                        {
                                          searchContentTxt[eleID] = $("[name='"+eleID+"'][forminputgroup='DTTableAdvanceSearch_"+DataTable_Property.target_table+"']").val(); 
                                        }
                                      break;
                                      case "SELECT":
                                        searchContentTxt[eleID] = $("[name='"+eleID+"'][forminputgroup='DTTableAdvanceSearch_"+DataTable_Property.target_table+"'] option[value='"+sfRow['value']+"']").text();
                                        
                                      break;
                                      default:
                                        searchContentTxt[eleID] = $("[name='"+eleID+"'][forminputgroup='DTTableAdvanceSearch_"+DataTable_Property.target_table+"']").val();                                
                                      break;
                                    }

                                    
                                  }
                                }
                               
                              }
                            }
                          }

                          
                        }

                        paramFilter['StrictSearchMode'] = ($("input[id='AdvanceSearchStrict_"+DataTable_Property.target_table+"']").is(':checked')) ? 'AND' : 'OR' ;
                        if(Object.keys(paramFilter['addedFilter']).length > 0)
                        {              
                          var srObj = {};
    
                          for(var zi in searchContentTxt)
                          {
                            if(zi.includes('[FROM]') || zi.includes('[TO]'))
                            {
                              zi = zi.replace('[FROM]','').replace('[TO]','');

                              var SearfFromVal = searchContentTxt[zi+'[FROM]'];
                              var SearfToVal = searchContentTxt[zi+'[TO]'];
                              if(SearfFromVal && SearfToVal)
                              {
                                  if(DateChecker(SearfFromVal,SearfToVal) == 'greater')
                                  {
                                    var xSearfFromVal = SearfToVal;
                                    var xSearfToVal = SearfFromVal;

                                    SearfFromVal = xSearfFromVal;
                                    SearfToVal = xSearfToVal;
                                  }

                                  if( DateChecker(SearfFromVal,SearfToVal) == 'equal' )
                                  {
                                    srObj[$("label[id='ASearchLabel_"+zi+"'][forminputgroup='DTTableAdvanceSearchLabel_"+DataTable_Property.target_table+"']").text()] = ' <b class="text-success">'+$("select[id='SearchConditions_"+zi+"'][forminputgroup='DTTableAdvanceSearchConditions_"+DataTable_Property.target_table+"'] option[value='"+paramFilter['addedFilterMode'][zi]+"']").text().toLowerCase()+'</b> <b class="text-info">' + ( (SearfFromVal) ? SearfFromVal : SearfToVal ) + '</b>';
                                  }
                                  else
                                  {
                                    srObj[$("label[id='ASearchLabel_"+zi+"'][forminputgroup='DTTableAdvanceSearchLabel_"+DataTable_Property.target_table+"']").text()] = ' <b class="text-success">is between</b> <b class="text-info">' + SearfFromVal + ' <b class="text-success">and</b> '+SearfToVal+' </b>';
                                  }
                              }
                              else
                              {
                                srObj[$("label[id='ASearchLabel_"+zi+"'][forminputgroup='DTTableAdvanceSearchLabel_"+DataTable_Property.target_table+"']").text()] = ' <b class="text-success">'+$("select[id='SearchConditions_"+zi+"'][forminputgroup='DTTableAdvanceSearchConditions_"+DataTable_Property.target_table+"'] option[value='"+paramFilter['addedFilterMode'][zi]+"']").text().toLowerCase()+'</b> <b class="text-info">' + ( (SearfFromVal) ? SearfFromVal : SearfToVal ) + '</b>';
                              }
                            }
                            else
                            {
                              if( searchContentTxt[zi] )
                              {
                                if( (searchContentTxt[zi].split(",")).length > 1 )
                                {
                                  var vz = searchContentTxt[zi].split(",");
                                  vz = vz.map($.trim);
                                  vz = vz.join(' <b class="text-success">or</b> ');
                                  searchContentTxt[zi] = vz;
                                }
                              }

                              srObj[$("label[id='ASearchLabel_"+zi+"'][forminputgroup='DTTableAdvanceSearchLabel_"+DataTable_Property.target_table+"']").text()] = ' <b class="text-success">'+$("select[id='SearchConditions_"+zi+"'][forminputgroup='DTTableAdvanceSearchConditions_"+DataTable_Property.target_table+"'] option[value='"+paramFilter['addedFilterMode'][zi]+"']").text().toLowerCase()+'</b> <b class="text-info">' + searchContentTxt[zi] + '</b>';
                            }
                          }

                          var srText = `${Object.keys(srObj).map(key => `<b>${key}</b> ${srObj[key]}`).join(' <b class="text-danger">'+paramFilter['StrictSearchMode']+'</b> ')}`;  
                          $("p[id='SearchResultBoxText_"+DataTable_Property.target_table+"']").html(srText);
                          var divSearchBox = $("div[id='SearchResultBox_"+DataTable_Property.target_table+"']");
                          divSearchBox.slideDown(400,'linear',function(){
                            setTimeout(function(){
                              $(divSearchBox).slideUp(400,'linear',function(){
                              $(divSearchBox).find("p[parent='"+$(divSearchBox).attr('id')+"']").html('');
                              });
                            },5000);
                          });
                          // console.log(paramFilter);
                          $("table[id='"+TargetTable+"']").data('filterParam',paramFilter);
                          
                          oTable_Obj[DataTable_Property.target_table].columns.adjust().search('').draw();
                          
                          // oTable_Obj[DataTable_Property.target_table];
                          $("input[id='DTSearch_Quick_"+DataTable_Property.target_table+"']").val('');  
                        }
            });

            $("button[id='AdvanceSearchClearButton_"+DataTable_Property.target_table+"']").unbind('click').click(function(){

                    $("[forminputgroup='DTTableAdvanceSearch_"+DataTable_Property.target_table+"']").each(function(){
                      if( $(this).prop('tagName') == "INPUT"  )
                      {
                        if( $(this).attr('type') == "radio" ||  $(this).attr('type') == "checkbox" )
                        {
                          $(this).prop('checked',false);
                        }
                        else
                        {
                          $(this).val('');
                          $(this).tokenfield('setTokens', []);
                          $("input[id='"+$(this).attr("id")+"-tokenfield']").attr("placeholder",$(this).attr("placeholder"));
                        }
                      }
                      else
                      {
                        $(this).val('');
                      }
                    });

            });

            $("div[id='AdvanceSearchBox_"+DataTable_Property.target_table+"']").on({
                'show.bs.collapse': function () {
                  // console.log('triggered');
                  var divSearchBox = $("div[id='SearchResultBox_"+DataTable_Property.target_table+"']");
                  $(divSearchBox).slideUp(400,'linear',function(){
                      $(divSearchBox).find("p[parent='"+$(divSearchBox).attr('id')+"']").html('');
                  });
                 
                  $("div[id='DataTable_MainPanel_Body_TableContainer_"+DataTable_Property.target_table+"']").collapse('hide');
                  // $("div[id='DataTable_MainPanel_Body_TableContainer_"+DataTable_Property.target_table+"']").hide();
                },
                'shown.bs.collapse': function () {
            
                  

                  $("[groupbutton*='DTSearchButton_"+DataTable_Property.target_table+"'][id='btn_AdvanceSearch']").html('<i class="fa fa-search-minus"></i>');
                  

                },
                'hide.bs.collapse': function () {
                  // $("div[id='DataTable_MainPanel_Body_TableContainer_"+DataTable_Property.target_table+"']").collapse('show');

                  // $("button[id='AdvanceSearchButton_"+DataTable_Property.target_table+"']").unbind('click');
                  // $("button[id='AdvanceSearchClearButton_"+DataTable_Property.target_table+"']").unbind('click');
                },
                'hidden.bs.collapse': function () {
                  $("[groupbutton*='DTSearchButton_"+DataTable_Property.target_table+"'][id='btn_AdvanceSearch']").html('<i class="fa fa-search-plus"></i>');
                  // $("div[id='DataTable_MainPanel_Body_TableContainer_"+DataTable_Property.target_table+"']").show();
                  $("div[id='DataTable_MainPanel_Body_TableContainer_"+DataTable_Property.target_table+"']").collapse('show');
                }
            });

            $("div[id='DTTable_Filter_"+DataTable_Property.target_table+"']").html(searhBoxHTML);

            $("input[id='DTSearch_Quick_"+DataTable_Property.target_table+"']").on({
              'keypress':function(e){
                if(e.which == 13 && $(this).val() )
                {
                  $("[groupbutton*='DTSearchButton_"+DataTable_Property.target_table+"'][id='btn_QuickSearch']").click();
                }
              }
            });

            $("[groupbutton*='DTSearchButton_"+DataTable_Property.target_table+"']").unbind('click').click(function(){
              switch($(this).attr('id'))
              {
                case "btn_QuickSearch":
                  $("table[id='"+TargetTable+"']").data('filterParam','');
                  
                  var searchInputs = $("input[id='DTSearch_Quick_"+DataTable_Property.target_table+"']").val();
                  if( searchInputs )
                  {
                    
                    try
                    {
                      searchInputs = searchInputs.trim();
                    }
                    catch(err)
                    {
                      searchInputs = '';
                    }

                    oTable_Obj[DataTable_Property.target_table].search( searchInputs ).draw(); 
                  }

                   
                break;

                case "btn_AdvanceSearch":
                  
                break;

                case "btn_ClearSearch":
                  $("table[id='"+TargetTable+"']").data('filterParam','');
                  oTable_Obj[DataTable_Property.target_table].search('').draw();  
                  $("input[id='DTSearch_Quick_"+DataTable_Property.target_table+"']").val('');
                  $("[forminputgroup='DTTableAdvanceSearch_"+DataTable_Property.target_table+"']").each(function(){
                    if( $(this).prop('tagName') == "INPUT"  )
                    {
                      if( $(this).attr('type') == "radio" ||  $(this).attr('type') == "checkbox" )
                      {
                        $(this).prop('checked',false);
                      }
                      else
                      {
                        $(this).val('');
                        $(this).tokenfield('setTokens', []);
                        $("input[id='"+$(this).attr("id")+"-tokenfield']").attr("placeholder",$(this).attr("placeholder"));
                      }
                    }
                    else
                    {
                      $(this).val('');
                    }
                  });
                break;

              }
            });
        }
        
        oTable_Obj[DataTable_Property.target_table].on( 'key-focus', function ( e, datatable, cell ) {
            
            // events.prepend( '<div>Cell focus: <i>'+cell.data()+'</i></div>' );
            var idx = cell[0][0].column;
            var title = datatable.columns( idx ).header();
            var ColumnTitle = title[0].textContent;
            var RowCaption = '';

            var tmp = document.createElement("DIV");
            tmp.innerHTML = cell.data();
            $("div[id='DTTable_FocusedCell_"+DataTable_Property.target_table+"']").html("Focused on "+RowCaption+'Column <b>'+ColumnTitle+"</b> with a value of <b>"+(tmp.textContent || tmp.innerText || "")+"</b>");

        } ).on( 'key-refocus', function ( e, datatable, cell, originalEvent ) {
            datatable.cell.blur();
            $("div[id='DTTable_FocusedCell_"+DataTable_Property.target_table+"']").html('');
        } );
    }  

}

/* DataTable -- End */

function formatReturnMessage(title,message,textcenter)
{
  title = typeof title !== 'undefined' ? title : "";
  message = typeof message !== 'undefined' ? message : "";
  textcenter = typeof textcenter !== 'undefined' ? textcenter : false;
  
  html   = '<div class="card" style="max-width: 100%;border:0px;">';
  html  += '  <div class="card-body scrollbar-default3 thin" style="padding:0px;max-height:83vh;overflow-y:auto">';
  html  += '    <h4 class="card-title text-center" style="font-weight: bold;margin-bottom:2rem">'+title+'</h4>';
  html  += '    <div class="'+((textcenter) ? 'text-center' : '')+'">'+message+'</div>';
  html  += '  </div>';
  html  += '</div>';

  return html;
}

function getFormData(forminputgroup)
{
  forminputgroup = typeof forminputgroup !== 'undefined' ? forminputgroup : 'Form_Data';
  returnError = typeof returnError !== 'undefined' ? returnError : false;
  
  var ErrorCount = 0;
  var NewFormData = new Object();
  
  NewFormData['ErrorData'] = {};

  var sFormData = $("[forminputgroup='"+forminputgroup+"']").closest('form').serializeArray();
  var data = new FormData();
  jQuery.each( sFormData, function( i, field ) {
       data.append(field.name, field.value);
  });

  $("input[forminputgroup='"+forminputgroup+"'][type='file']").each(function(){
    var file_data = $(this)[0].files;
    for (var i = 0; i < file_data.length; i++) {
        data.append($(this).attr('name')+"[]", file_data[i]);
    }
  });

  $("[forminputgroupcheck='"+forminputgroup+"']").each(function(){
    var itagname = $(this).get(0).tagName;
    // Get TagName
    switch( itagname )
    {
      case "INPUT":
      break;

      default:
      break;
    }

    if( $(this).prop('required') && !$(this).val() )
    {
      var dataVa = data.getAll($(this).attr('name'));
      if( dataVa.length == 0 || (dataVa.length > 0  && dataVa[0] == "") )
      { 
        ErrorCount++;
      
        NewFormData['ErrorData'][$(this).attr('id')] = ($(this).prop('title') && $(this).attr('title') !== '' ) ? $(this).attr('title') : $("label[class='form-control-label'][for='"+$(this).attr('id')+"']").text();
        if( !$(this).prop('disabled') )
        {
          $("label[class='form-control-label'][for='"+$(this).attr('id')+"']").closest("div.form-group.form-material").addClass('has-danger');
        } 
      }      
    }
  });
  
  // for(var pair of data.entries()) {
  //  console.log(pair[0]+ ' : '+ pair[1]);
  // }

  NewFormData['FormData'] = data;
  return NewFormData;
}

function popFormDataError(Obj,ftitle,CallBackFunction,callbackparam)
{
  ftitle = typeof ftitle !== 'undefined' ? ftitle : '';
  CallBackFunction = typeof CallBackFunction !== 'undefined' ? CallBackFunction : '';
  callbackparam = typeof callbackparam !== 'undefined' ? callbackparam : '';
  if( Object.keys(Obj).length > 0 )
  {
    var eMessage = '<ul class="list-group " style="margin-bottom:0px;font-size:12px">';
    var eCnt = 0;
    var eLength = Object.keys(Obj).length;
    for( var elementid in Obj)
    {    
      
      if( eCnt >= 10)
      {
         eMessage += '<li class="list-group-item text-danger" id="eMessage_'+elementid+'" style="padding:0.5rem 1.25rem"><i>( '+(eLength-eCnt)+' other required fields )</i></li>';
         break;
      }
      else
      {
         eMessage += '<li class="list-group-item text-danger" id="eMessage_'+elementid+'" style="padding:0.5rem 1.25rem">'+Obj[elementid]+'<i class="fa fa-fw fa-exclamation-triangle float-right"></i></li>';  
      }
      eCnt++;
    }
    eMessage += '</ul>';
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
    title: ftitle,
    html: 'Please fill out all required Fields<div class="border-danger" style="margin-top:5px;width:100%;text-align: left;border: 1px solid;border-radius: 5px;">'+eMessage+'</div>',
    icon: "error",
    confirmButtonText: 'Close',
  }).then((result) => {
    if(CallBackFunction && isFunction(CallBackFunction)) 
    {
      CallBackFunction(callbackparam);
    }    
  });
}

function submitFormData(returnformat,headerparam,backendURL,pFormData,CallBackFunction,ShowLoader,asyncCall,timeoutduration)
{
  returnformat = typeof returnformat !== 'undefined' ? returnformat : "json";
  headerparam = typeof headerparam !== 'undefined' ? headerparam : "";
  backendURL = typeof backendURL !== 'undefined' ? backendURL : "";
  pFormData = typeof pFormData !== 'undefined' ? pFormData : new FormData();
  CallBackFunction = typeof CallBackFunction !== 'undefined' ? CallBackFunction : "";
  ShowLoader = typeof ShowLoader !== 'undefined' ? ShowLoader : true;
  asyncCall = typeof asyncCall == 'boolean' ? asyncCall : true;
  timeoutduration = typeof timeoutduration == 'undefined' ? 30000 : parseInt(timeoutduration);
  if( !$.isNumeric(timeoutduration) )
  {
    timeoutduration = 30000;
  }

  if( pFormData == "" )
  {
    pFormData = new FormData();
  }

  if(typeof pFormData == 'object')
  {
    if(pFormData instanceof FormData == false)
    {
      pFormData['UToken'] = UToken; 

      if (typeof csrfName !== 'undefined')
      {
        if( csrfName !== 'ci_csrf_token' && csrfHash !=='')
        {
          pFormData[csrfName] = csrfHash;
        }
      }

      
    }
    else
    {
      pFormData.append('UToken', UToken);
      if (typeof csrfName !== 'undefined')
      {
        if( csrfName !== 'ci_csrf_token' && csrfHash !=='')
        {
          pFormData.append(csrfName,csrfHash);
        }
      }
    }
  }

  if(!asyncCall)
  {
    ShowLoader = false;
    var ReturnObject = new Object();
    ReturnObject['postparam']     = pFormData;
    ReturnObject['resultparam']   = '';
  }

  if( backendURL !== "" )
  {
    if(ShowLoader){ loader(true); }
    if( timeoutduration > 0 )
    {

      var postDataTimeout = setTimeout(function(){
            if(!asyncCall)
            {
              ReturnObject['resultparam'] = '';
            } 
            else
            {
              if(ShowLoader)
              {
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
                  title: "Failed Request!",
                  text: "Failed to Process Request from Server",
                  icon: "error",
                  confirmButtonText: 'Close',
                });
              }
            }   
          }, timeoutduration);
    }
    

    $.ajax({
          type: 'post',
          url: backendURL,
          headers:headerparam,
          cache: false,
          showLoader: true,
          global: false,
          data : pFormData,
          async:asyncCall,
          method: "POST",
          processData: (pFormData instanceof FormData) ? false : true,
          contentType: (pFormData instanceof FormData) ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
          beforeSend: function(xhr) {
            activeAjaxConnections++;
          },
          success: function(postresult) {
            if( timeoutduration > 0)
            {
              clearTimeout(postDataTimeout);
            }
            
            if(ShowLoader)
            {
              loader(false);
            }
           
            activeAjaxConnections--;

            if(0 == activeAjaxConnections)
            {
              if( $("div[class='blockUI blockOverlay']").length > 0)
              {
                loader(false);
              }
            }


          },
          error: function (request, status, error){
            activeAjaxConnections--;
            if(timeoutduration > 0)
            {
               clearTimeout(postDataTimeout);
            }
           
            if(ShowLoader)
            {
              loader(false);
            }

            if(0 == activeAjaxConnections)
            {
              if( $("div[class='blockUI blockOverlay']").length > 0)
              {
                loader(false);
              }
            }
          },
          complete: function(request,status){
            
            var postresult = (request.responseText !== '') ? request.responseText : '' ;
            //console.log(postresult);
            try{
              postresult = JSON.parse(postresult);
            }catch(e){
              PostResult = '';
              //console.log('INVALID RETURN');
            }

            switch(status)
            {
              case "success":
                if(!asyncCall)
                {
                  ReturnObject['resultparam'] = postresult;
                }
                else
                {
                  if(CallBackFunction && isFunction(CallBackFunction)) 
                  {
                    CallBackFunction(pFormData,postresult);
                  }    
                }
              break;

              default:
                
                if(!asyncCall)
                {
                  ReturnObject['resultparam'] = '';
                }
                else
                {
                  if(ShowLoader)
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
                      title: "Failed Request!",
                      text: "Failed to Process Request from Server",
                      icon: "error",
                      confirmButtonText: 'Close',
                    });
                  }

                }
               
              break;
            }

          }
    });
  }

  if(!asyncCall)
  {
    return ReturnObject;
  }
}

function system_totp(u,p)
{
   paramonsuccess = typeof paramonsuccess !== 'undefined' ? paramonsuccess : "";
   callbackonsuccess = typeof callbackonsuccess !== 'undefined' ? callbackonsuccess : "";
   callbackonfailed = typeof callbackonfailed !== 'undefined' ? callbackonfailed : "";

  Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-info mr-5 w-p45',
      cancelButton: 'btn btn-secondary w-p45',
    },
    buttonsStyling: false,
    willOpen: (fnRun) => {
      $(".swal2-container").css('z-index',$.topZIndex());
    }
  }).fire({
    title: 'Two-Factor Authentication',
    icon:'info',
    input: 'text',
    inputPlaceholder:'Authentication Code',
    inputAttributes: {
      style: 'text-align:center',
      maxlength: 6,
      minlength: 6,
      onkeypress:"return isNumberKey(event)"
    },
    showCancelButton: true,
    confirmButtonText: 'Verify',
    cancelButtonText:'Cancel',
    showLoaderOnConfirm: true,
    reverseButtons: false,
    theme:'borderless',
    preConfirm: (PV) => {
      
      if(!PV){ Swal.showValidationMessage( 'Please enter your authentication code!' ) }
      else
      {
        return fetch(site_url+'login/authenticate', {
          method: 'post',
          body: "login="+u+"&password="+p+"&authcode="+btoa(PV)+"&UToken="+UToken+( (typeof csrfName !== 'undefined') ? '&'+csrfName+'='+csrfHash : ''),
          headers: { 'Content-type': 'application/x-www-form-urlencoded' }
        }).then(function(response) {
          if (!response.ok) {
            return { Status:0, Message:"Unable to connect to Two-Factor Authentication Verification Service." };
          }
          return response.json();
        }).catch(error => {
            return { Status:0, Message:"Unable to connect to Two-Factor Authentication Verification Service." };
        });
      }

    },
    allowOutsideClick: () => !Swal.isLoading(),
    allowEscapeKey:() => !Swal.isLoading(),
  }).then((result) => {
    if (result.value) {
      rv = result.value;
      if( rv.Status == 1 )
      {
          let timerInterval
          Swal.mixin({
            willOpen: (fnRun) => {
              $(".swal2-container").css('z-index',$.topZIndex());
            }
          }).fire({
            title:  "Welcome<br>"+rv.AccountName+"",
            text: "You will now redirected to the Home Page",
            icon: 'success',
            confirmButtonText: 'Continue',
            showConfirmButton:false,
            reverseButtons: true,
            timer:2000,
            timerProgressBar: true,
            theme:'borderless',
            //background: '#ffffff66',
            willOpen: () => {
              Swal.showLoading()
              timerInterval = setInterval(() => {
                const content = Swal.getContent()
                if (content) {
                  const b = content.querySelector('b')
                  if (b) {
                    b.textContent = Swal.getTimerLeft()
                  }
                }
              }, 100);

              window.location.href=site_url;

            },
            willClose: () => {
              clearInterval(timerInterval)
            }
          }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer)
            {
              window.location.href=site_url;
            }
          });
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
          title: "",
          text: atob(rv.Message),
          icon: "error",
          confirmButtonText: 'Close',
          closeOnConfirm: true
        }).then((result) => {
          system_totp(u,p);
        });
      }
    }
    else
    {
      window.location.href=site_url;
    }
  });

}

function system_login(obj,token,iconcaptcha)
{
  var u = $("[formgroup='LoginForm_Data'][id='ieml']").val();
  var p = $("[formgroup='LoginForm_Data'][id='ipswrd']").val();
  token = typeof token !== 'undefined' ? token : "";
  iconcaptcha = typeof iconcaptcha !== 'undefined' ? iconcaptcha : "";
  if(u && p)
  {
    postUrl = site_url+'login/authenticate';

    var login_form_val = new FormData();
    login_form_val.append('login', btoa(u));
    login_form_val.append('password', btoa(p));
    login_form_val.append('recapctha_response',token);

    if(iconcaptcha)
    {
      login_form_val.append('ic-rq', $("form[id='LoginForm']").find('input[name="ic-rq"]').val());
      login_form_val.append('ic-wid', $("form[id='LoginForm']").find('input[name="ic-wid"]').val());
      login_form_val.append('ic-cid', $("form[id='LoginForm']").find('input[name="ic-cid"]').val());
      login_form_val.append('ic-hp', $("form[id='LoginForm']").find('input[name="ic-hp"]').val());
    }


    obj.append('<i id="signinbutton_spinner" class="icon fa-circle-o-notch icon-spin" style="margin-left:10px"></i>');
    obj.attr('disabled','disabled');
    
    var LoginForm_Result = function(FormData,PostResult){   
        obj.removeAttr('disabled');              
        $("[formgroup='LoginForm_Data'][id='inputEmail'],[formgroup='LoginForm_Data'][id='inputPassword']").val('');
        $("i[id='signinbutton_spinner']").remove();

        if(PostResult.Status == 1)
        { 
          if(PostResult.hasOwnProperty('RequireTwoFactorVerify') && PostResult.RequireTwoFactorVerify == 1)
          {
            // TOTP
              system_totp(btoa(u),btoa(p));
          }
          else
          {
            let timerInterval
            Swal.mixin({
              willOpen: (fnRun) => {
                $(".swal2-container").css('z-index',$.topZIndex());
              }
            }).fire({
              title:  "Welcome<br>"+PostResult.AccountName+"",
              text: "You will now redirected to the Home Page",
              icon: 'success',
              confirmButtonText: 'Continue',
              showConfirmButton:false,
              reverseButtons: true,
              timer:2000,
              timerProgressBar: true,
              theme:'borderless',
              //background: '#ffffff66',
              willOpen: () => {
                Swal.showLoading()
                timerInterval = setInterval(() => {
                  const content = Swal.getContent()
                  if (content) {
                    const b = content.querySelector('b')
                    if (b) {
                      b.textContent = Swal.getTimerLeft()
                    }
                  }
                }, 100);

                window.location.href=site_url;
              },
              willClose: () => {
                clearInterval(timerInterval)
              }
            }).then((result) => {
              if (result.dismiss === Swal.DismissReason.timer)
              {
                // window.location.href=site_url;
              }
            });
          }
        }
        else
        {
          if(token)
          {
            onrecaptchaloaded();
          }
          
          if(iconcaptcha)
          {
            IconCaptcha.reset()
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
            title: "Sign-In Failed!",
            text: atob(PostResult.Message),
            icon: "error",
            confirmButtonText: 'Close',
          });
        }
    };

    submitFormData('json','',postUrl,login_form_val,LoginForm_Result,false);
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
      title: "Sign-In Failed!",
      text: 'Please Enter Username/Email & Password!',
      icon: "error",
      confirmButtonText: 'Close',
    });
  }

}

function system_logout(accountname)
{
  Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-warning ml-1 w-p45',
      cancelButton: 'btn btn-secondary  w-p45',
    },
    buttonsStyling: false,
    willOpen: (fnRun) => {
      $(".swal2-container").css('z-index',$.topZIndex());
    }
  }).fire({
    title:  accountname,
    html: '<h4 class="text-white">Are you sure you want to sign out?</h4>',
    icon: 'warning',
    showCancelButton: true,
    cancelButtonText: "NO",
    showConfirmButton:true,
    confirmButtonText: 'YES',
    reverseButtons: true,
    allowEscapeKey:false,
    allowOutsideClick:false,
    theme:'borderless',
  }).then((result) => {
    if (result.value) 
    {
      window.location.href=site_url+'logout';
      Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
        },
        buttonsStyling: false,
        willOpen: (fnRun) => {
          $(".swal2-container").css('z-index',$.topZIndex());
        }
      }).fire({
        title: "Thank You, "+accountname+"!",
        html: "Your account was successfully<br>Sign Out!",
        icon: "success",
        confirmButtonText: 'Close',
      });

    }
  });
}

function viewAPIAccess(WSKey,EKey)
{
  WSKey = typeof WSKey !== 'undefined' ? WSKey : "";
  EKey = typeof EKey !== 'undefined' ? EKey : "";
  
  var keysHTML = '<div class="form-group form-material text-left" data-plugin="formMaterial"><div><label class="form-control-label" for="inputText">API Key</label><i name="APIcopytoclipboard" target="API_WSKey" title="API KEY" class="fa fa-fw fa-copy float-right" style="cursor:pointer;"></i></div><input type="text" class="form-control text-center border border-info" id="API_WSKey" disabled="disabled" value="'+WSKey+'"></div><div class="form-group form-material text-left" data-plugin="formMaterial"><div><label class="form-control-label" for="inputText">Encryption Key</label><i name="APIcopytoclipboard" target="API_EKey" title="ENCRYPTION KEY" class="fa fa-fw fa-copy float-right" style="cursor:pointer;"></i></div><input type="text" class="form-control text-center border border-info" id="API_EKey" disabled="disabled" value="'+EKey+'"></div>';

  Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-dark',
    },
    buttonsStyling: false,
    willOpen: (fnRun) => {
      $(".swal2-container").css('z-index',$.topZIndex());
      $("i[name*='APIcopytoclipboard']").click(function(){
        toastr.success(($(this).attr('title')),'COPY TO CLIPBOARD');
        copyToClipboard($("input[id='"+$(this).attr('target')+"']").val());
      });
    }
  }).fire({
    title:  "API Authentication",
    html: keysHTML,
    // icon: 'warning',
    showConfirmButton:true,
    confirmButtonText: 'Close',
    // theme:'borderless'
  }).then((result) => {
    if (result.value) 
    {
     

    }
  });

}



function passwordverification(paramonsuccess,callbackonsuccess,callbackonfailed)
{
   paramonsuccess = typeof paramonsuccess !== 'undefined' ? paramonsuccess : "";
   callbackonsuccess = typeof callbackonsuccess !== 'undefined' ? callbackonsuccess : "";
   callbackonfailed = typeof callbackonfailed !== 'undefined' ? callbackonfailed : "";

  Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-warning ml-2',
      cancelButton: 'btn btn-secondary',
    },
    buttonsStyling: false,
    willOpen: (fnRun) => {
      $(".swal2-container").css('z-index',$.topZIndex());
    }
  }).fire({
    title: 'Password Verification',
    icon:'warning',
    input: 'password',
    inputPlaceholder:'Please Enter Your Password',
    inputAttributes: {
      style: 'text-align:center'
    },
    showCancelButton: true,
    confirmButtonText: 'Verify',
    cancelButtonText:'Cancel',
    showLoaderOnConfirm: true,
    reverseButtons: true,
    preConfirm: (PV) => {
      
      if(!PV){ Swal.showValidationMessage( 'Please enter your account password!' ) }
      else
      {
        return fetch(site_url+'utilities/passwordverification', {
          method: 'post',
          body: "password_verification="+btoa(PV)+"&UToken="+UToken+( (typeof csrfName !== 'undefined') ? '&'+csrfName+'='+csrfHash : ''),
          headers: { 'Content-type': 'application/x-www-form-urlencoded' }
        }).then(function(response) {
          if (!response.ok) {
            return { Status:0, Message:"Unable to connect to Password Verification Service." };
          }
          return response.json();
        }).catch(error => {
            return { Status:0, Message:"Unable to connect to Password Verification Service." };
        });
      }

    },
    allowOutsideClick: () => !Swal.isLoading(),
    allowEscapeKey:() => !Swal.isLoading(),
  }).then((result) => {
    if (result.value) {
      rv = result.value;
      if( rv.Status == 1 )
      {
        toastr.success("Password Verification","Account Password successfully Verified!");
        Swal.close();
        if( typeof paramonsuccess === "object" )
        {
          if(paramonsuccess instanceof FormData == false)
          {
            paramonsuccess['PVToken'] = rv.Message; 
          }
          else
          {
            paramonsuccess.append('PVToken', rv.Message);
          }
        }
        else
        {
          paramonsuccess = rv.Message;
        }

        if(  typeof callbackonsuccess == 'function' )
        {
          callbackonsuccess(paramonsuccess);
        }
        else if (callbackonsuccess && isFunction(callbackonsuccess) ) 
        {
          var fnSuccess = window[callbackonsuccess];
          fnSuccess(paramonsuccess);
        }
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
          title: "Password Verification Failed!",
          text: rv.Message,
          icon: "error",
          confirmButtonText: 'Close',
          closeOnConfirm: true
        }).then((result) => {
            if(  typeof callbackonfailed == 'function' )
            {
              callbackonfailed();
            }
            else if ( isFunction(callbackonfailed) ) 
            {
              var fnCancel = window[callbackonfailed];
              fnCancel();
            }
        });
      }
    }else
    {
      if(  typeof callbackonfailed == 'function' )
      {
        callbackonfailed();
      }
      else if ( isFunction(callbackonfailed) ) 
      {
        var fnCancel = window[callbackonfailed];
        fnCancel();
      }
    }
  });

}

function send_testmail()
{
  
  var testmailHTML = `
    <div class="text-left">
      <div class="form-group form-material" data-plugin="formMaterial">
        <label class="form-control-label" for="inputEmail">Email Address :</label>
        <input type="email" class="form-control" id="iEmail" name="iEmail" placeholder="Email" forminputgroup="testMail">
      </div>
      <div class="form-group form-material" data-plugin="formMaterial">
        <label class="form-control-label" for="inputEmail">Subject :</label>
        <input type="text" class="form-control" id="iSubject" name="iSubject" placeholder="Email Subject"  forminputgroup="testMail">
      </div>
      <div class="form-group form-material" data-plugin="formMaterial">
        <label class="form-control-label" for="textarea">Message Content :</label>
        <textarea class="form-control" id="iContent" name="iContent" rows="4" forminputgroup="testMail"></textarea>
      </div>
    </div>
  `;

  Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-dark mr-2 w-p45',
      cancelButton: 'btn btn-dark w-p45',
    },
    buttonsStyling: false,
    willOpen: (fnRun) => {
      $(".swal2-container").css('z-index',$.topZIndex());
    }
  }).fire({
    title: 'SEND TEST MAIL',
    icon:'info',
    html:testmailHTML,
    showCancelButton: true,
    confirmButtonText: 'SEND MAIL',
    cancelButtonText:'CANCEL',
    showLoaderOnConfirm: true,
    reverseButtons: false,
    preConfirm: (EmailAddress) => {
      
      if(!$("[forminputgroup='testMail'][id='iEmail']").val() || !validateEmail($("[forminputgroup='testMail'][id='iEmail']").val()) )
      { 
        Swal.showValidationMessage( 'Please enter email address!' ) 
      }
      else
      {
        return EmailAddress;
      }
    },
    allowOutsideClick: () => !Swal.isLoading(),
    allowEscapeKey:() => !Swal.isLoading(),
  }).then((result) => {
    if (result.value) {
      var formdata = new FormData();
      formdata.append('emailaddress',btoa($("[forminputgroup='testMail'][id='iEmail']").val()));
      formdata.append('emailsubject',btoa($("[forminputgroup='testMail'][id='iSubject']").val()));
      formdata.append('emailcontent',btoa($("[forminputgroup='testMail'][id='iContent']").val()));
      var testMailResult = function(pp,rp){
            Swal.mixin({
              customClass: {
                confirmButton: 'btn btn-dark',
              },
              buttonsStyling: false,
              willOpen: (fnRun) => {
                $(".swal2-container").css('z-index',$.topZIndex());
              }
            }).fire({
              title: "",
              html: ( (rp.Status == 1) ? 'Email Successfully Sent!' : 'Failed to Send Email!'),
              icon: ( (rp.Status == 1) ? 'success' : 'error'),
              confirmButtonText: 'Close',
            }).then((result) => {
              
            });
        };

        target_url = site_url+'utilities/send_test_mail'
        submitFormData('json','',target_url,formdata,testMailResult,true);
    }
  });

}


function resendFailedMail()
{
  resendHTML  = '<div class="card border-white mb-0">';
    resendHTML += '<div class="card-block p-0">';
      resendHTML += '<h4 class="card-title">Resend Email<span class="float-right"><button type="button" class="btn btn-dark" id="btn_StartResend" disabled="disabled" name="btnResendButtonAction"><i class="fas fa-send-o fa-fw mr-5"></i>Start Resend</button><button type="button" class="btn btn-dark" id="btn_StopResend" name="btnResendButtonAction" disabled="disabled" style="display:none"><i class="fas fa-stop-circle-o fa-fw ml-5 mr-5"></i>Cancel Resend</button><button type="button" class="btn btn-dark bootbox-close-button ml-5" id="btn_CloseResend" name="btnResendButtonAction"><i class="fas fa-remove fa-fw mr-5"></i>Close</button></span></h4>';
      resendHTML += '<div class="mt-20">';

        resendHTML += '<div style="height:80px">';

          resendHTML += '<div class="card border border-secondary mb-5 float-left" style="height:80px;width:24%;margin-right:1.33%">';
            resendHTML += '<div class="card-block p-5">';
              resendHTML += '<h6 class="card-title">Mail to Resend</h6>';
              resendHTML += '<h4 id="MailCount" name="ResendMailGroup" class="card-title text-center">0</h4>';
            resendHTML += '</div>';
          resendHTML += '</div>';

          resendHTML += '<div class="card border border-secondary mb-5 float-left" style="height:80px;width:24%;margin-right:1.33%">';
            resendHTML += '<div class="card-block p-5">';
              resendHTML += '<h6 class="card-title">Success</h6>';
              resendHTML += '<h4 id="SuccessCount" name="ResendMailGroup" class="card-title text-center">0</h4>';
            resendHTML += '</div>';
          resendHTML += '</div>';

          resendHTML += '<div class="card border border-secondary mb-5 float-left" style="height:80px;width:24%;margin-right:1.33%">';
            resendHTML += '<div class="card-block p-5">';
              resendHTML += '<h6 class="card-title">Failed</h6>';
              resendHTML += '<h4 id="FailedCount" name="ResendMailGroup" class="card-title text-center">0</h4>';
            resendHTML += '</div>';
          resendHTML += '</div>';

          resendHTML += '<div class="card border border-secondary mb-5 float-left" style="height:80px;width:24%" >';
            resendHTML += '<div class="card-block p-5">';
              resendHTML += '<h6 class="card-title">Status</h6>';
              resendHTML += '<h4 id="Status" name="ResendMailGroup" class="card-title text-center" style=""></h4>';
            resendHTML += '</div>';
          resendHTML += '</div>';


        resendHTML += '</div>';

        resendHTML += '<div class="card border border-secondary mt-5 mb-0">';
          resendHTML += '<div class="card-block p-5">';
            resendHTML += '<h4 class="card-title">Logs</h4>';
            resendHTML += '<div id="LogsBox" name="ResendMailGroup" class="p-0" style="font-size:12px !important">';
              resendHTML += '<table class="table table-sm mb-0"><thead><tr><th class="text-center w-p10">#</th><th class="text-center w-p30">Send To</th><th class="text-center w-p30">Subject</th><th class="text-center">Result</th></tr></thead></table>';
            resendHTML += '</div>';
            resendHTML += '<div id="LogsBox" name="ResendMailGroup" class="p-0" style="overflow-y:auto;height:400px;font-size:12px !important">';
              resendHTML += '<table id="TableLogsBox" class="table table-sm table-striped mb-0"><tbody></tbody></table>';   
            resendHTML += '</div>';
          resendHTML += '</div>';
        resendHTML += '</div>';

      resendHTML += '</div>';
    resendHTML += '</div>';
  resendHTML += '</div>';

  var MailLogsResult = '';

  bootbox.dialog({
      size: 'xl',
      // title: "Password DO's and DONT's",
      message: resendHTML,
      closeButton:false,
      onShow: function(e) {
        
        $("h4[name='ResendMailGroup'][id='MailCount']").html('<i class="fas fa-spinner fa-fw icon-spin"></i>');
        MailLogsResult = submitFormData('json','',site_url+'administrator/emaillogs/getFailedMail','','',false,false);
        if( MailLogsResult['resultparam'] !== '' )
        {
          var ResultMailLog = MailLogsResult['resultparam'];

          if(ResultMailLog.Status == 1)
          {
            $("h4[name='ResendMailGroup'][id='MailCount']").html(parseInt((ResultMailLog.Message).length));
          }
        }
      },
      onShown:function(e){
        var ResultMailLog = MailLogsResult['resultparam'];
        var MailtoResend = parseInt((ResultMailLog.Message).length);
        console.log(ResultMailLog.Message);
        if(ResultMailLog.Status == 1 && MailtoResend > 0)
        {
          resendRunning = false;
          $("button[id='btn_StartResend']").removeAttr('disabled').click(function(){
            resendRunning = true;
            $("button[id='btn_CloseResend']").attr('disabled','disabled').hide();
            $("button[id='btn_StopResend']").removeAttr('disabled').show();
            $(this).attr('disabled','disabled').hide();

            var mCnt = 1;
            var MailResendList = ResultMailLog.Message;
            var SuccessSend = 0;
            var FailedSend = 0;

            var processResendMail = function(MailResendList,mCnt){
              
              if(mCnt > MailtoResend)
              {
                $("h4[name='ResendMailGroup'][id='Status']").html('<i class="fas fa-send fa-fw mr-10"></i>'+MailtoResend+'/'+MailtoResend+'<i class="fas fa-check fa-fw ml-10"></i>');

                var  logRow  = '<tr class="bg-warning">';
                      logRow += '<td colspan="4" class="text-left text-dark font-weight-bold">Resending All Mail Completed. <span class="float-right"><b>Status Result = <span class="text-success">Successfull Resend : '+SuccessSend+'</span> | <span class="text-danger">Failed Resend : '+FailedSend+'</span></b></span></td>';
                    logRow += '</tr>';

                $("table[id='TableLogsBox'] tbody").prepend(logRow);
                $("button[id='btn_StopResend']").attr('disabled','disabled').hide();
                $("button[id='btn_CloseResend']").removeAttr('disabled').show();
              }
              else
              {
                if(resendRunning)
                {
                  var mailData = MailResendList[mCnt-1];
      
                  $("h4[name='ResendMailGroup'][id='Status']").html('<i class="fas fa-send fa-fw mr-10"></i>'+mCnt+'/'+MailtoResend+'<i class="fas fa-spinner fa-fw icon-spin ml-10"></i>');

                  var logRow    = '<tr id="MailID_'+mailData['DataID']+'" class="font-weight-bold ">';
                        logRow += '<td class="text-center w-p10">'+(mCnt++)+'</td>';
                        logRow += '<td class="text-center w-p30">'+mailData['sToEmail']+'</td>';
                        logRow += '<td class="text-center w-p30">'+mailData['sSubject']+'</td>';
                        logRow += '<td class="text-center" id="Result_'+mailData['DataID']+'"><span class="text-info mr-20">Resending Email. . . .<i class="fas fa-spinner fa-fw icon-spin"></i></span></td>';
                      logRow   += '</tr>';

                  $("table[id='TableLogsBox'] tbody").prepend(logRow);

                  // Call Ajax Resend
                  var resendCallBack = function(pp,rp)
                  {
                    if(rp !== "")
                    {
                      if(rp.Status == 1)
                      {
                        SuccessSend++;
                        $("h4[name='ResendMailGroup'][id='SuccessCount']").html(parseInt(SuccessSend));
                        $("table[id='TableLogsBox'] tbody tr[id='MailID_"+mailData['DataID']+"'] td[id='Result_"+mailData['DataID']+"']").html('<span class="text-success mr-20"><i class="fas fa-check fa-fw mr-10"></i>Email Successfully Resend</span>');
                      }
                      else
                      {
                        FailedSend++;
                        $("h4[name='ResendMailGroup'][id='FailedCount']").html(parseInt(FailedSend));
                        $("table[id='TableLogsBox'] tbody tr[id='MailID_"+mailData['DataID']+"'] td[id='Result_"+mailData['DataID']+"']").html('<span class="text-danger mr-20"><i class="fas fa-warning fa-fw mr-10"></i>Failed to Resend Email</span>');
                      }
                    }
                    else
                    {
                        FailedSend++;
                        $("h4[name='ResendMailGroup'][id='FailedCount']").html(parseInt(FailedSend));
                        $("table[id='TableLogsBox'] tbody tr[id='MailID_"+mailData['DataID']+"'] td[id='Result_"+mailData['DataID']+"']").html('<span class="text-danger mr-20"><i class="fas fa-warning fa-fw mr-10"></i>Failed to Resend Email</span>');
                     
                    }
                    
                    oTable_Obj['system_mailsender_log'].draw();
                    processResendMail(MailResendList,mCnt);

                  };

                  resendURL = site_url+'administrator/emaillogs/resendmail';
                  submitFormData('json','',resendURL,mailData,resendCallBack,false,true);
                }
                else
                {
                  
                  $("h4[name='ResendMailGroup'][id='Status']").html('<i class="fas fa-send fa-fw mr-10"></i>'+(parseInt(mCnt)-1)+'/'+MailtoResend+'<i class="fas fa-check fa-fw ml-10"></i>');
 

                  var  logRow  = '<tr class="bg-warning">';
                      logRow += '<td colspan="4" class="text-left text-dark font-weight-bold">Resending Mail has been Cancelled. <span class="float-right"><b>Status Result = <span class="text-success">Successfull Resend : '+SuccessSend+'</span> | <span class="text-danger">Failed Resend : '+FailedSend+'</span></b></span></td>';
                    logRow += '</tr>';


                  $("table[id='TableLogsBox'] tbody").prepend(logRow);
                  $("button[id='btn_CloseResend']").removeAttr('disabled').show();
                  $("button[id='btn_StopResend']").attr('disabled','disabled').hide();
                }
              }
              
            };

            processResendMail(MailResendList,mCnt);

          });   
          
          $("button[id='btn_StopResend']").removeAttr('disabled').click(function(){
            resendRunning = false;
            $(this).attr('disabled','disabled').html('Cencelling...');
          }); 
        }
      }
  })
}

function resendactivation(param)
{
  param = typeof param !== 'undefined' ? param : "";
  if(param instanceof FormData == true)
  {
    Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-info w-p45 mr-2',
        cancelButton: 'btn btn-secondary w-p45 ',
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
      }
    }).fire({
      title: 'Account Information',
      icon:'info',
      html: 'Resend Account Activation Link',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText:'No',
    }).then((result) => {
      if (result.value) {
          
          var sendResult = function(pp,rp){
              Swal.mixin({
                customClass: {
                  confirmButton: 'btn btn-'+( (rp.Status == 1) ? 'success' : 'danger'),
                },
                buttonsStyling: false,
                willOpen: (fnRun) => {
                  $(".swal2-container").css('z-index',$.topZIndex());
                }
              }).fire({
                title: "Account Activation Link",
                text: ( (rp.Status == 1) ? 'Successfully Resend!' : 'Failed to Resend, Please try again later!'),
                icon: ( (rp.Status == 1) ? 'success' : 'error'),
                confirmButtonText: 'Close',
              });
          };

          target_url = site_url+'utilities/resendactivationlink';
          submitFormData('json','',target_url,param,sendResult);

      }
    });
  }
}

function resendaccountinfo(param)
{
  param = typeof param !== 'undefined' ? param : "";
  if(param instanceof FormData == true)
  {
    Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-info w-p45 mr-2',
        cancelButton: 'btn btn-secondary w-p45 ',
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
      }
    }).fire({
      title: 'Account Information',
      icon:'info',
      html: 'Resend Account Information to Registered Email Address',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText:'No',
    }).then((result) => {
      if (result.value) {
          
          var sendResult = function(pp,rp){
              Swal.mixin({
                customClass: {
                  confirmButton: 'btn btn-'+( (rp.Status == 1) ? 'success' : 'danger'),
                },
                buttonsStyling: false,
                willOpen: (fnRun) => {
                  $(".swal2-container").css('z-index',$.topZIndex());
                }
              }).fire({
                title: "Resend Account Information",
                text: ( (rp.Status == 1) ? 'Successfully Resend!' : 'Failed to Resend, Please try again later!'),
                icon: ( (rp.Status == 1) ? 'success' : 'error'),
                confirmButtonText: 'Close',
              });
          };

          target_url = site_url+'utilities/resendaccountinfo';
          submitFormData('json','',target_url,param,sendResult);

      }
    });
  }
}

function changeuseremailaddress(param)
{
  param = typeof param !== 'undefined' ? param : "";
  if(param instanceof FormData == true)
  {
    Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-warning ml-2',
        cancelButton: 'btn btn-secondary',
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
      }
    }).fire({
      title: 'Change Email Address',
      icon:'warning',
      input: 'email',
      inputPlaceholder:'newemail@address.com',
      inputAttributes: {
        style: 'text-align:center'
      },
      showCancelButton: true,
      confirmButtonText: 'Change Email',
      cancelButtonText:'Cancel',
      showLoaderOnConfirm: true,
      reverseButtons: true,
      preConfirm: (EmailAddress) => {
        
        if(!EmailAddress || !validateEmail(EmailAddress)){ Swal.showValidationMessage( 'Please enter new email address!' ) }
        else
        {
          param.append('new_email',EmailAddress);
          return param;
        }

      },
      allowOutsideClick: () => !Swal.isLoading(),
      allowEscapeKey:() => !Swal.isLoading(),
    }).then((result) => {
      if (result.value) {
        postparam = result.value;
        
        var ceResult = function(pp,rp){
              Swal.mixin({
                customClass: {
                  confirmButton: 'btn btn-'+( (rp.Status == 1) ? 'success' : 'danger'),
                },
                buttonsStyling: false,
                willOpen: (fnRun) => {
                  $(".swal2-container").css('z-index',$.topZIndex());
                }
              }).fire({
                title: "Change Email Address",
                html: rp.Message,
                icon: ( (rp.Status == 1) ? 'success' : 'error'),
                confirmButtonText: 'Close',
              }).then((result) => {
                if(rp.Status == 1)
                {
                  window.location.reload();
                }
              });
          };

          target_url = site_url+'utilities/changeemail';
          submitFormData('json','',target_url,postparam,ceResult,true);

      }else{
        
      }
    });
  }
}

function resetpassword(param)
{
  param = typeof param !== 'undefined' ? param : "";
  if(param instanceof FormData == true)
  {
    Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-warning w-p45 mr-2',
        cancelButton: 'btn btn-secondary w-p45 ',
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
      }
    }).fire({
      title: 'Reset Password',
      icon:'warning',
      text: 'Are you sure you want to Reset this Account Password?',
      showCancelButton: true,
      cancelButtonText: "No",
      showConfirmButton:true,
      confirmButtonText: 'Yes',
      reverseButtons: false,
    }).then((result) => {
      if (result.value) {

        postparam = param;
        
        var rpResult = function(pp,rp){
              Swal.mixin({
                customClass: {
                  confirmButton: 'btn btn-'+( (rp.Status == 1) ? 'success' : 'danger'),
                },
                buttonsStyling: false,
                willOpen: (fnRun) => {
                  $(".swal2-container").css('z-index',$.topZIndex());
                }
              }).fire({
                title: "Reset Password",
                html: rp.Message,
                icon: ( (rp.Status == 1) ? 'success' : 'error'),
                confirmButtonText: 'Close',
              }).then((result) => {
                if(rp.Status == 1)
                {
                  window.location.reload();
                }
              });
          };

          target_url = site_url+'utilities/resetpassword';
          submitFormData('json','',target_url,postparam,rpResult,true);

      }
    });
  }
}

function accountstatusupdate(param)
{
  param = typeof param !== 'undefined' ? param : "";
  if(param instanceof FormData == true)
  {
    Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-warning w-p45 mr-2',
        cancelButton: 'btn btn-secondary w-p45 ',
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
      }
    }).fire({
      title: (( param.get('status') == 1 ) ? 'Activate Account' : 'Deactivate Account'),
      icon:'warning',
      text: '',
      showCancelButton: true,
      cancelButtonText: "No",
      showConfirmButton:true,
      confirmButtonText: 'Yes',
      reverseButtons: false,
    }).then((result) => {
      if (result.value) {

        postparam = param;
        
        var pResult = function(pp,rp){
              Swal.mixin({
                customClass: {
                  confirmButton: 'btn btn-'+( (rp.Status == 1) ? 'success' : 'danger'),
                },
                buttonsStyling: false,
                willOpen: (fnRun) => {
                  $(".swal2-container").css('z-index',$.topZIndex());
                }
              }).fire({
                title: 'Account Status',
                html: rp.Message,
                icon: ( (rp.Status == 1) ? 'success' : 'error'),
                confirmButtonText: 'Close',
              }).then((result) => {
                if(rp.Status == 1)
                {
                  window.location.reload();
                }
              });
          };

          target_url = site_url+'utilities/changeaccountstatus';
          submitFormData('json','',target_url,postparam,pResult,true);

      }
    });
  }
}

function bannedaccount(param,vptoken)
{
  param = typeof param !== 'undefined' ? param : "";
  var pstatus = parseInt(param.get('status'));
  if(param instanceof FormData == true)
  {
    Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-warning w-p45 mr-2',
        cancelButton: 'btn btn-secondary  w-p45 ',
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
      }
    }).fire({
      title: (( pstatus !== 0 ) ? 'Banned Account' : 'Unbanned Account'),
      icon:'warning',

      showCancelButton: true,
      confirmButtonText: (( pstatus !== 0 ) ? 'Banned' : 'Unbanned'),
      cancelButtonText:'Cancel',
      showLoaderOnConfirm: true,
      reverseButtons: false,
      input: 'text',
      inputPlaceholder: (( pstatus !== 0 ) ? 'Banned Reason' : 'Remarks'),
      inputAttributes: {
        style: 'text-align:left;',
      },
      preConfirm: (ReasonMess) => {
        if( pstatus !== 0 )
        {
          if(!ReasonMess){ Swal.showValidationMessage( 'Please enter reason for banning this account!' ) }
          else
          {
            param.append('reason',ReasonMess);
            return param;
          }
        }
        else
        {
          param.append('reason',ReasonMess);
          return param;
        }
      },
      allowOutsideClick: () => !Swal.isLoading(),
      allowEscapeKey:() => !Swal.isLoading(),
    }).then((result) => {
      if (result.value) {
        postparam = result.value;
        
        var ceResult = function(pp,rp){
              Swal.mixin({
                customClass: {
                  confirmButton: 'btn btn-'+( (rp.Status == 1) ? 'success' : 'danger'),
                },
                buttonsStyling: false,
                willOpen: (fnRun) => {
                  $(".swal2-container").css('z-index',$.topZIndex());
                }
              }).fire({
                title: "Account Status",
                html: rp.Message,
                icon: ( (rp.Status == 1) ? 'success' : 'error'),
                confirmButtonText: 'Close',
              }).then((result) => {
                if(rp.Status == 1)
                {
                  window.location.reload();
                }
              });
          };

          target_url = site_url+'utilities/changebannedstatus';
          submitFormData('json','',target_url,postparam,ceResult,true);

      }else{
        
      }
    });
  }
}

function renewaccount(param)
{
  param = typeof param !== 'undefined' ? param : "";
  if(param instanceof FormData == true)
  {
    Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-warning w-p45 mr-2',
        cancelButton: 'btn btn-secondary w-p45 ',
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
      }
    }).fire({
      title: 'Renew Account',
      icon:'warning',
      text: 'Renew Account Expiration Date?',
      showCancelButton: true,
      cancelButtonText: "No",
      showConfirmButton:true,
      confirmButtonText: 'Yes',
      reverseButtons: false,
    }).then((result) => {
      if (result.value) {

        postparam = param;
        
        var rpResult = function(pp,rp){
              Swal.mixin({
                customClass: {
                  confirmButton: 'btn btn-'+( (rp.Status == 1) ? 'success' : 'danger'),
                },
                buttonsStyling: false,
                willOpen: (fnRun) => {
                  $(".swal2-container").css('z-index',$.topZIndex());
                }
              }).fire({
                title: "Renew Account",
                html: rp.Message,
                icon: ( (rp.Status == 1) ? 'success' : 'error'),
                confirmButtonText: 'Close',
              }).then((result) => {
                if(rp.Status == 1)
                {
                  window.location.reload();
                }
              });
          };

          target_url = site_url+'utilities/renewaccount';
          submitFormData('json','',target_url,postparam,rpResult,true);

      }
    });
  }
}

function forgotpassword(secretquestion,userid)
{
  secretquestion = typeof secretquestion !== 'undefined' ? secretquestion : "";
  userid = typeof userid !== 'undefined' ? userid : "";

  Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-warning mr-2 w-p45',
      cancelButton: 'btn btn-secondary w-p45',
    },
    buttonsStyling: false,
    willOpen: (fnRun) => {
      $(".swal2-container").css('z-index',$.topZIndex());
    }
  }).fire({
    title: 'Forgot Password',
    icon:'warning',
    text:(secretquestion) ? secretquestion : '',
    input: (secretquestion) ? 'password' : 'text',
    inputPlaceholder: (secretquestion) ? 'Account Secret Answer' : 'User Name/Registered Email Address',
    inputAttributes: {
      style: 'text-align:center',
      autocomplete: 'off'
    },
    showCancelButton: true,
    confirmButtonText: (secretquestion) ? 'Submit' : 'Continue',
    cancelButtonText: 'Cancel',
    showLoaderOnConfirm: true,
    reverseButtons: false,
    preConfirm: (PV) => {
      
      var pMess = (secretquestion) ? 'Please enter your Account Secret Answer' : 'Please enter your User Name/Registered Email Address';
      var finalLink = (secretquestion) ? 'confirmrequest' : 'newrequest';
      var bodyParam = (secretquestion) ? "userid="+userid+"&securityanswer="+btoa(PV)+"&UToken="+UToken+( (typeof csrfName !== 'undefined') ? '&'+csrfName+'='+csrfHash : '') : "useridemail="+btoa(PV)+"&UToken="+UToken+( (typeof csrfName !== 'undefined') ? '&'+csrfName+'='+csrfHash : '');

      if(!PV){ Swal.showValidationMessage( pMess ) }
      else
      {
        return fetch(site_url+'resetpassword/'+finalLink, {
          method: 'post',
          body: bodyParam,
          headers: { 'Content-type': 'application/x-www-form-urlencoded' }
        }).then(function(response) {
          if (!response.ok) {
            return { Status:0, Message:"Unable to connect to Forgot Password Service." };
          }
          return response.json();
        }).catch(error => {
            return { Status:0, Message:"Unable to connect to Forgot Password Service." };
        });
      }
    },
    allowOutsideClick: () => !Swal.isLoading(),
    allowEscapeKey:() => !Swal.isLoading(),
  }).then((result) => {
    if (result.value) 
    {
      rv = result.value;
      if( rv.Status == 1 )
      {
        if(secretquestion)
        {
           Swal.mixin({
              customClass: {
                confirmButton: 'btn btn-success',
              },
              buttonsStyling: false,
              willOpen: (fnRun) => {
                $(".swal2-container").css('z-index',$.topZIndex());
              }
            }).fire({
              title: "Forgot Password",
              html: rv.Message,
              icon: 'success',
              confirmButtonText: 'Close',
            }).then((result) => {

            });

        }else{
          forgotpassword(rv.secretquestion,rv.userid);
        }
        
      }else{
        Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-danger',
          },
          buttonsStyling: false,
          willOpen: (fnRun) => {
            $(".swal2-container").css('z-index',$.topZIndex());
          }
        }).fire({
          title: "Forgot Password",
          icon: 'error',
          html: rv.Message, 
          confirmButtonText: 'Close',
        }).then((result) => {

        });
      }
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
          title: "Forgot Password",
          html: rv.Message,
          icon: 'error',
          confirmButtonText: 'Close',
        }).then((result) => {

        });
    }
  });

}

function changeuserlevel(param)
{
  param = typeof param !== 'undefined' ? param : "";
  if(param instanceof FormData == true)
  {
    


    Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-warning w-p45 mr-2',
        cancelButton: 'btn btn-secondary w-p45 ',
      },
      buttonsStyling: false,
      willOpen: (fnRun) => {
        $(".swal2-container").css('z-index',$.topZIndex());
      }
    }).fire({
      title: 'Reset Password',
      icon:'warning',
      text: 'Are you sure you want to Reset this Account Password?',
      showCancelButton: true,
      cancelButtonText: "No",
      showConfirmButton:true,
      confirmButtonText: 'Yes',
      reverseButtons: false,
    }).then((result) => {
      if (result.value) {

        postparam = param;
        
        var rpResult = function(pp,rp){
              Swal.mixin({
                customClass: {
                  confirmButton: 'btn btn-'+( (rp.Status == 1) ? 'success' : 'danger'),
                },
                buttonsStyling: false,
                willOpen: (fnRun) => {
                  $(".swal2-container").css('z-index',$.topZIndex());
                }
              }).fire({
                title: "Reset Password",
                html: rp.Message,
                icon: ( (rp.Status == 1) ? 'success' : 'error'),
                confirmButtonText: 'Close',
              }).then((result) => {
                if(rp.Status == 1)
                {
                  window.location.reload();
                }
              });
          };

          target_url = site_url+'utilities/resetpassword';
          submitFormData('json','',target_url,postparam,rpResult,true);

      }
    });
  }
}


function submitCreatePassword(param,token)
{
  
  param = typeof param !== 'undefined' ? param : "";
  token = typeof token !== 'undefined' ? token : "";

  if(param && token)
  {
    var FormData = getFormData('NewPasswordForm_Data');
   
        FormData['FormData'].append('recaptchatoken', token);
        FormData['FormData'].append('passwordkey', param);

        if( Object.keys(FormData['ErrorData']).length > 0)
        {
          popFormDataError(FormData['ErrorData'],'New Account Password','onrecaptchaloaded');
        }
        else
        {
          var postUrl = site_url+'resetpassword/submitnewpassword';
          var NewPasswordResult = function(FormData,postresult){
              Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-dark',
                  },
                  buttonsStyling: false,
                  willOpen: (fnRun) => {
                    $(".swal2-container").css('z-index',$.topZIndex());
                  }
                }).fire({
                  title: 'New Account Password',
                  text: postresult.Message,
                  icon: (postresult.Status == 1) ? 'success' : 'error',
                  confirmButtonText: 'Close',
                }).then((result) => {
                   if(postresult.Status == 1)
                   {
                      window.location.href=site_url;
                   }
                   else
                   {
                    onrecaptchaloaded();
                   }
                });
          };

          submitFormData('json','',postUrl,FormData['FormData'],NewPasswordResult);
        }
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
        title: "Create New Account Password",
        text: 'Invalid New Password & Confirm Password!',
        icon: 'error',
        confirmButtonText: 'Close',
      }).then((result) => {
        onrecaptchaloaded();
      });
  } 
}


function submitContactUsForm(token)
{
  token = typeof token !== 'undefined' ? token : "";
  if(token)
  {
    var FormData = getFormData('contactusform');
        if(token !== 0)
        {
           FormData['FormData'].append('recaptchatoken', token);
        }
       
        if( Object.keys(FormData['ErrorData']).length > 0)
        {
          popFormDataError(FormData['ErrorData'],'','onrecaptchaloaded');
        }
        else
        {
          var postUrl = site_url+'contactus/index/submitform';
          var ContactUSResult = function(FormData,postresult){
              Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-dark',
                  },
                  buttonsStyling: false,
                  willOpen: (fnRun) => {
                    $(".swal2-container").css('z-index',$.topZIndex());
                  }
                }).fire({
                  title: (postresult.Status == 1) ? 'Thank You' : 'Please try again.',
                  text: postresult.Message,
                  icon: (postresult.Status == 1) ? 'success' : 'error',
                  confirmButtonText: 'Close',
                }).then((result) => {
                   if(postresult.Status == 1)
                   {
                      if(token == 0)
                      {
                        oTable_Obj['cticketlist'].draw();
                      }else{
                        window.location.href=site_url;
                      }
                      
                   }
                   else
                   {
                     if(token == 0)
                      {
                        oTable_Obj['cticketlist'].draw();
                      }else{
                        onrecaptchaloaded();
                      }
                   }
                });
          };

          submitFormData('json','',postUrl,FormData['FormData'],ContactUSResult);
        }
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
        title: "Failed to Submit Form",
        text: '',
        icon: 'error',
        confirmButtonText: 'Close',
      }).then((result) => {
        if(token == 0)
        {
          oTable_Obj['cticketlist'].draw();
        }else{
          onrecaptchaloaded();
        }
      });
  } 
}

function getEncryptionKey()
{
  var getKeys_Result = function(FormData,PostResult){
    $("i[id='generatekeyicon']").remove();
    if( PostResult.Status == 1)
    {
      $("input[id='generatedkeys']").val(atob(PostResult.Message));
    }
  };

  var sFormData = new FormData();
  var postUrl = site_url+'api/index/generatekeys';
  submitFormData('json','',postUrl,sFormData,getKeys_Result,false);
  $("button[id='btn_generateKey']").append('<i id="generatekeyicon" class="fas fa-spin fa-spinner" style="margin-left:10px"></i>');
}

function TryEncryptDecrypt(mode,obj)
{
  mode = typeof mode !== 'undefined' ? mode : 1;

  var gk =  ($("input[id='generatedkeys']").val()).trim();
  var istring = ($("textarea[id='InputString']").val()).trim();
  var sFormData = new FormData();
  sFormData.append('generatedkeys',gk);
  sFormData.append('InputString',istring);
  sFormData.append('mode',mode);

  if( !gk || !istring )
  {
    $("textarea[id='OutputString']").val('');
    Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-dark',
        },
        buttonsStyling: false,
        willOpen: (fnRun) => {
          $(".swal2-container").css('z-index',$.topZIndex());
        }
      }).fire({
        title: "Please enter INPUT String to Encrypt/Decrypt",
        text: '',
        icon: 'error',
        confirmButtonText: 'Close',
      }).then((result) => {
      
      });
  }
  else
  {
    var getResult = function(FormData,PostResult){
      $("i[id='enloadericon']").remove();
      if( PostResult.Status == 1)
      {
        $("textarea[id='OutputString']").val(PostResult.Message);
      }
    };

    var postUrl = site_url+'api/index/encryptdecryptString';

    submitFormData('json','',postUrl,sFormData,getResult,false);
    obj.append('<i id="enloadericon" class="fas fa-spin fa-spinner" style="margin-left:10px"></i>');
  }
}

function ObjectFileViewer(ObjectBlobContent)
{
  ObjectBlobContent = typeof ObjectBlobContent !== 'undefined' ? ObjectBlobContent : "";

  if(typeof ObjectBlobContent == "string")
  {
    ObjectBlobContent = atob(ObjectBlobContent);
    ObjectBlobContent = JSON.parse(ObjectBlobContent);
  }
  var toolbar = '#toolbar=0';
  if(ObjectBlobContent.hasOwnProperty('ToolBar') && ObjectBlobContent.ToolBar == 'true')
  {
    toolbar = '#toolbar=1';
  }

  if(typeof ObjectBlobContent == "object" )
  {
    var fData = (ObjectBlobContent.hasOwnProperty('FilePath')) ? ObjectBlobContent.FilePath+toolbar : 'data:'+ObjectBlobContent.FileType+';base64,'+ObjectBlobContent.FileData+toolbar;


    bootbox.dialog({
       size: 'xl',
       title: '<span id="ViewAttachedTitle_'+btoa(ObjectBlobContent.FileName)+'">'+ObjectBlobContent.FileName+'</span><i class="fas fa-times bootbox-close-button float-right text-grey" style="cursor:pointer"></i>',
       message: '<object id="fileviewer"nonce="'+ObjectBlobContent.nonce+'"  data="'+fData+'" type="'+ObjectBlobContent.FileType+'" style="height:'+($(window).height() - 150)+'px;width:100%;border:1px solid #ccc"></object>',
       closeButton:false,
       onShow: function(e) {
          $("span[id='ViewAttachedTitle_"+btoa(ObjectBlobContent.FileName)+"']").parent().parent().addClass('pb-0');
          $("span[id='ViewAttachedTitle_"+btoa(ObjectBlobContent.FileName)+"']").closest('h5').css('width','100%');
       },
       onShown:function(e){
         $("div[class='bootbox modal fade show'").attr('tabindex',1);
       }
    });
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
      icon: 'error',
      confirmButtonText: 'Close',
    }).then((result) => {
     
      });
    
  }

}

function checksnapshotrequest(userid)
{
  var target_url = site_url+'Utilities/snapshotrequest';
  var PostParam = new FormData();
  PostParam.append('user_id',userid);
  PostParam.append('UToken',UToken);
  var rpResult = function(pp,rp){
    if(rp.Status == 1)
    {
        Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-dark mr-10 w-p45',
            cancelButton: 'btn btn-dark w-p45',
          },
          buttonsStyling: false,
          willOpen: (fnRun) => {
            $(".swal2-container").css('z-index',$.topZIndex());
          }
        }).fire({
          title: "Client Window Snapshot Request",
          html: 'System Administrator is requesting for a Snapshot of your current page for better support/troubleshooting',
          icon: 'question',
          showCancelButton: true,
          cancelButtonText: "No",
          showConfirmButton:true,
          confirmButtonText: 'Allow',
          reverseButtons: false,
          allowEscapeKey: false,
          allowEnterKey:false,
          allowOutsideClick:false,
        }).then((result) => {
            
          var ActionForm = new FormData();
          ActionForm.append('user_id',userid);
          ActionForm.append('SnapshotID',rp.RequestData['id']);
          ActionForm.append('UToken',UToken);
          if (result.value)
          {
            // Get Screenshot
            html2canvas(document.body).then(function(canvas) {
              ActionForm.append('snapshot',canvas.toDataURL("image/png"));
              ActionForm.append('snapshotWidth',$(canvas).attr('width'));
              ActionForm.append('snapshotHeight',$(canvas).attr('height'));
            });

            ActionForm.append('snapshotstatus',1);
          }
          else
          {
            ActionForm.append('snapshotstatus',0);
          }

          var atarget_url = site_url+'Utilities/snapshotrequestaction';
          
          setTimeout(function(atarget_url,ActionForm,userid){
            submitFormData('json','',atarget_url,ActionForm,'',false);
            setTimeout(function(userid){
              checksnapshotrequest(userid);   
            },2000,userid);
          },2000,atarget_url,ActionForm,userid);
          

        });
    }
    else
    {
      setTimeout(function(userid){
        checksnapshotrequest(userid);
      },5000,userid)
    } 
  };

  submitFormData('json','',target_url,PostParam,rpResult,false);
}

function updateClock(e){
  var e = typeof e !== 'undefined' ? e : "sdateclock"; 
  var currentTime = new Date(currentServerDateTime);

  var currentHours = currentTime.getHours ( );
  var currentMinutes = currentTime.getMinutes ( );
  var currentSeconds = currentTime.getSeconds ( );

  // setInterval(function(){

  //     // Pad the minutes and seconds with leading zeros, if required
  //     currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
  //     currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

  //     // Choose either "AM" or "PM" as appropriate
  //     var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

  //     // Convert the hours component to 12-hour format if needed
  //     currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

  //     // Convert an hours component of "0" to "12"
  //     currentHours = ( currentHours == 0 ) ? 12 : currentHours;

  //     // Compose the string for display
  //     var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
      
  //     $("#"+e+"").html(currentTimeString);      
  // },1000);

 
}

toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": true,
  "progressBar": false,
  "positionClass": "toast-bottom-right",
  "preventDuplicates": true,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "300",
  "timeOut": "3000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};

$("a[id='viewAPIAccess']").click(function(){
  viewAPIAccess($(this).attr('api_ws'),$(this).attr('api_e'));
});

$("a[id='system_logout']").click(function(){
  system_logout($(this).attr('cname'));
});

// window.oncontextmenu = function () {
//   return false;
// };

// document.addEventListener("keydown", function(event){
//     var key = event.key || event.keyCode;

//     if (key == 123) {
//         return false;
//     } else if ((event.ctrlKey && event.shiftKey && key == 73) || (event.ctrlKey && event.shiftKey && key == 74)) {
//         return false;
//     }
// }, false);