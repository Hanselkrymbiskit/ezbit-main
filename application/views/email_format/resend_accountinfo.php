<?php
    $CI =& get_instance();

    $userDetails = $CI->myutilities->getUserDetails($User_ID);
    $userProfile = $CI->myutilities->getUserProfile($User_ID);
    $userRegistrationDetails = $CI->myutilities->getUserProfile($User_ID);

?>
<div>
  <p>Please check your <?php echo $CI->system_settings['ApplicationAbbre']; ?> account information below.</p>
  <div style="border:1px solid #666;padding:5px;margin-bottom:15px;margin-top:15px">
    <table id="" name="" class="table table-bordered" style="border-radius:0px;font-size:12px;margin:0 !important;">
      <?php if( is_array($userRegistrationDetails) ) { ?>
      <tr style="">
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:200px">
          <span class="" style="font-weight:bold;padding-left:20px">
            Classification
          </span>
        </td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:5px;border-left:0px">:</td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;border-left:0px">
          <span style="padding-left:20px;font-weight:bold;border-radius:0px">
            <?php echo $CI->myutilities->getRef_Desc(4,(int) $userProfile['user_classification']);?>
          </span>
        </td>
      </tr> 
      <?php } ?>
      <tr style="">
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:200px">
          <span class="" style="font-weight:bold;padding-left:20px">
            User Name
          </span>
        </td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:5px;border-left:0px">:</td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;border-left:0px">
          <span style="padding-left:20px;font-weight:bold;border-radius:0px">
            <?php echo $User_Name;?>
          </span>
        </td>
      </tr>
      <tr style="">
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:200px">
          <span class="" style="font-weight:bold;padding-left:20px">
            Last Name
          </span>
        </td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:5px;border-left:0px">:</td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;border-left:0px">
          <span style="padding-left:20px;font-weight:bold;border-radius:0px">
            <?php echo $userProfile['Lastname'];?>
          </span>
        </td>
      </tr>
      <tr style="">
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:200px">
          <span class="" style="font-weight:bold;padding-left:20px">
            First Name
          </span>
        </td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:5px;border-left:0px">:</td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;border-left:0px">
          <span style="padding-left:20px;font-weight:bold;border-radius:0px">
            <?php echo $userProfile['Firstname'];?>
          </span>
        </td>
      </tr>
      <tr style="">
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:200px">
          <span class="" style="font-weight:bold;padding-left:20px">
            Middle Name
          </span>
        </td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:5px;border-left:0px">:</td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;border-left:0px">
          <span style="padding-left:20px;font-weight:bold;border-radius:0px">
            <?php echo $userProfile['Middlename'];?>
          </span>
        </td>
      </tr>
      <tr style="">
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:200px">
          <span class="" style="font-weight:bold;padding-left:20px">
            Suffix Name
          </span>
        </td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:5px;border-left:0px">:</td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;border-left:0px">
          <span style="padding-left:20px;font-weight:bold;border-radius:0px">
            <?php echo $userProfile['Suffixname'];?>
          </span>
        </td>
      </tr>
      <tr style="">
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:200px">
          <span class="" style="font-weight:bold;padding-left:20px">
            Sex
          </span>
        </td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:5px;border-left:0px">:</td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;border-left:0px">
          <span style="padding-left:20px;font-weight:bold;border-radius:0px">
            <?php echo $userProfile['Sex'];?>
          </span>
        </td>
      </tr>
      <tr style="">
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:200px">
          <span class="" style="font-weight:bold;padding-left:20px">
            Date of Birth
          </span>
        </td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:5px;border-left:0px">:</td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;border-left:0px">
          <span style="padding-left:20px;font-weight:bold;border-radius:0px">
            <?php echo date("F d, Y",strtotime($userProfile['DateofBirth']));?>
          </span>
        </td>
      </tr>
      <tr style="">
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:200px">
          <span class="" style="font-weight:bold;padding-left:20px">
            Account Expiration Date
          </span>
        </td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;width:5px;border-left:0px">:</td>
        <td style="border-bottom:1px dashed #999;padding:4px;border:0px;background:transparent;border-left:0px">
          <span style="padding-left:20px;font-weight:bold;border-radius:0px">
            <?php echo date("F d, Y",strtotime($User_Expiration));?>
          </span>
        </td>
      </tr>
    </table>
  </div>
  <p>If you have any concern please contact the <?php echo $CI->system_settings['ApplicationAbbre']; ?> Helpdesk Support.</p>
</div>