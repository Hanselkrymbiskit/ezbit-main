<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$getSecurityQuestion = $CI->sqlhelper->local->select("ref_securityquestion")->ex_select("","","")->result();

?>
<div style="padding:1rem;font-size:12px !important;color:#000;">
  <div class="row">
      <div class=""  style="width:100%;">
        <div id="" class="card" style="color:#000;margin-bottom: 5px !important;">
          <div class="card-header card-header-transparent ">
            <h6 class="m-0 font-weight-bold" style="width: 100%">
                <i class="fa fa-lock" style="margin-right:10px"></i>Creating Password Guide
                <i class="fa fa-times fa-fw float-right bootbox-close-button" style="cursor:pointer"></i>
            </h6>
           
          </div>
          <div class="card-body" style="font-size:12px;">
             To ensure the integrity and security of this site, all authorized end-users are strongly required to <b>CREATE STRONG PASSWORDS</b>:
          </div>
        </div>
      </div>
  </div>
  <div class="row">
      <div class="float-left"  style="width:49.5%;margin-right: 1%">
        <div id="" class="card" style="margin-bottom: 5px !important;">
          <div class="card-header card-header-transparent ">
            <h6 class="m-0 font-weight-bold" style="width: 100%">
                <i class="fa fa-check-circle" style="margin-right:10px"></i>DO's
            </h6>
          </div>
         <div class="card-body">
              <div style="padding:5px;font-size:12px;height:190px">
                - Are at least eight (8) to sixteen (16) alphanumeric characters long.&nbsp;<br>
                - Contain both upper and lower case characters<strong> (e.g., a-z, A-Z)</strong>&nbsp;<br>
                - Have digits and punctuation characters as well as letters e.g.,&nbsp;<strong>(0-9),( . )&nbsp;</strong>
                  - Are not a word in any language, slang, dialect, jargon, etc.&nbsp;<br>
                  - Are not based on personal information, names of family, ATM account numbers, license numbers, etc.
                  
              </div>  
          </div>
        </div>
      </div>
      <div class="float-right"  style="width:49.5%;margin-left: 0%">
        <div id="" class="card" style="margin-bottom: 5px !important;">
          <div class="card-header card-header-transparent">
            <h6 class="m-0 font-weight-bold" style="width: 100%">
                <i class="fa fa-times-circle" style="margin-right:10px"></i>DONT's
            </h6>
          </div>
         <div class="card-body">
              <div style="padding:5px;font-size:12px;height:190px">
                  - The password is a word found in a dictionary (English or foreign)&nbsp;<br>
                  - The password is a common usage word such as: ( Names of family, pets, friends, co-workers, fantasy characters,&nbsp;etc.&nbsp;)<br>
                  - Computer terms and names, commands, sites, companies, hardware,&nbsp;software.&nbsp;<br>
                  - Birthdays and other personal information such as addresses and&nbsp;phone numbers.&nbsp;<br>
                  - Word or number patterns like aaabbb, qwerty, zyxwvuts, 123321,&nbsp; etc.&nbsp;<br>
                  - Any of the above spelled backwards.&nbsp;<br>
                  - Any of the above preceded or followed by a digit (e.g., secret1,&nbsp;1secret)&nbsp;
          
                </div>    
          </div>
        </div>
      </div>
  </div>
  <div class="row">
      <div class=""  style="width:100%;">
        <div id="" class="card" style="margin-bottom: 5px !important;">
          <div class="card-header card-header-transparent">
            <h6 class="m-0 font-weight-bold" style="width: 100%">
                <i class="fa fa-info-circle" style="margin-right:10px"></i>ALL PASSWORDS ARE TO BE TREATED AS SENSITIVE, CONFIDENTIAL&nbsp;INFORMATION.
            </h6>
           
          </div>
         <div class="card-body" style="font-size:12px;">
              <div class="float-left" style="width:40%;font-size:12px;height:170px">
                - Don't reveal a password over the phone to ANYONE&nbsp;<br>
                - Don't reveal a password in an email message&nbsp;<br>
                - Don't talk about a password in front of others&nbsp;<br>
                - Don't hint at the format of a password (e.g., "my family name")&nbsp;<br>
                - Don't reveal a password on questionnaires or security forms&nbsp;<br>
                - Don't share a password with family members&nbsp;<br>
                
                </div>
                <div class="float-right  " style="width:60%;font-size:12px;height:170px">
                  - Don't reveal a password to co-workers while on vacation&nbsp;<br>
                - Don't use the "Remember Password" feature of applications (e.g.,&nbsp;Firefox, IE, Chrome, Opera, etc.).&nbsp;<br>
                - Don't use the same password on several computers and/or services as&nbsp;once revealed, it would compromise the security within all the others&nbsp;in one go&nbsp;<br>
                &nbsp;-Before entering your User ID and password, make sure no one is&nbsp;watching you, to avoid the so-called "shoulder surfing" technique.&nbsp;<br>
                - Before using your User ID and password on a third-party computer,&nbsp;make sure it is well protected, and free of trojans and key loggers.&nbsp;<p></p>
                </div>
                
                <p class="muted text-white"> Source: &nbsp;<a target="_blank" rel="nofollow" class="text-white" href="http://www.google.com/url?sa=D&amp;q=http://oit.osu.edu/networking/osunet/Password_Best_Practices.pdf&amp;usg=AFQjCNHKxtwOkhbGxCakT25qRc1gNGWA_g">http://oit.osu.edu/networking/osunet/Password_Best_Practices.pdf</a>&nbsp; </p>
          </div>
        </div>
      </div>
  </div>
</div>