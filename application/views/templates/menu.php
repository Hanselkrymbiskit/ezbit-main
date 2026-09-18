 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

/* Standard Structure for Menu */
// $menuItemStructure = array(
//                         array(
//                           'menucaption'     => 'Dashboard',
//                           'iconClass'       => 'fa fa-fw fa-tachometer-alt',
//                           'iconImage'       => '',
//                           'targetlink'      => site_url('administrator/dashboard'),
//                           'submenuitem'     =>  array(
//                             array(
//                               'menucaption'     => 'Dashboard',
//                               'iconClass'       => 'fa fa-fw fa-tachometer-alt',
//                               'iconImage'       => '',
//                               'targetlink'      => site_url('administrator/dashboard'),
//                               'submenuitem'     =>  array()
//                             )
//                           )
//                         ),
//                       );

$adminMenuItem = array(
                  array(
                      'menucaption'     => 'Dashboard',
                      'iconClass'       => 'fa fa-fw fa-bar-chart',
                      'iconImage'       => '',
                      'targetlink'      => site_url('administrator'),
                      'submenuitem'     =>  array()
                  ),
                  array(
                            'menucaption'     => 'User Management',
                            'iconClass'       => 'fa fa-fw fa-users',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/useraccount'),
                            'submenuitem'     =>  array(
                                array(
                                  'menucaption'     => 'Registered User',
                                  'groupmenu'       => 'USER MANAGEMENT',
                                  'iconClass'       => 'fa fa-fw fa-regular fa-user',
                                  'iconImage'       => '',
                                  'targetlink'      => site_url('administrator/registered'),
                                  'submenuitem'     =>  array()
                                ),

                                array(
                                    'menucaption'     => 'User Account',
                                    'iconClass'       => 'fa fa-fw fa-users',
                                    'iconImage'       => '',
                                    'targetlink'      => site_url('administrator/useraccount'),
                                    'submenuitem'     =>  array()
                                ),

                            )
                        ),
            
                  // array(
                  //     'menucaption'     => 'Page Content',
                  //     'groupmenu'       => 'Page Management',
                  //     'iconClass'       => 'fa fa-fw fa-clone',
                  //     'iconImage'       => '',
                  //     'targetlink'      => '', 
                  //     'submenuitem'     =>  array(           
                  //                               array(
                  //                                   'menucaption'     => 'About US',
                  //                                   'iconClass'       => 'fa fa-fw fa-info-circle',
                  //                                   'iconImage'       => '',
                  //                                   'targetlink'      => site_url('administrator/pagecontent/aboutus'),
                  //                                   'submenuitem'         =>  array()
                  //                               ),
                  //                               array(
                  //                                   'menucaption'     => 'Contact US',
                  //                                   'iconClass'       => 'fa fa-fw fa-phone',
                  //                                   'iconImage'       => '',
                  //                                   'targetlink'      => site_url('administrator/pagecontent/contactus'),
                  //                                   'submenuitem'         =>  array()
                  //                               ),
                  //                               array(
                  //                                   'menucaption'     => 'News / Announcement',
                  //                                   'iconClass'       => 'fa fa-fw fa-bullhorn',
                  //                                   'iconImage'       => '',
                  //                                   'targetlink'      => site_url('administrator/pagecontent/newsannouncement'),
                  //                                   'submenuitem'         =>  array()
                  //                               ),
                  //                               array(
                  //                                   'menucaption'     => 'FAQs',
                  //                                   'iconClass'       => 'fa fa-fw fa-question-circle',
                  //                                   'iconImage'       => '',
                  //                                   'targetlink'      => site_url('administrator/pagecontent/faq'),
                  //                                   'submenuitem'         =>  array()
                  //                               ),
                  //                               array(
                  //                                   'menucaption'     => 'Download',
                  //                                   'iconClass'       => 'fa fa-fw fa-download',
                  //                                   'iconImage'       => '',
                  //                                   'targetlink'      => site_url('administrator/pagecontent/download'),
                  //                                   'submenuitem'         =>  array()
                  //                               ),

                  //                           )      
                  // ),
                  array(
                            'menucaption'     => 'Users Online',
                            'groupmenu'       => 'System Management',
                            'iconClass'       => 'fa fa-fw fa-list-ul',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/usersonline'),
                            'submenuitem'     => array()
                        ),
                 
                  array(
                            'menucaption'     => 'Email Logs',
                            'groupmenu'       => 'System Management',
                            'iconClass'       => 'fa fa-fw fa-envelope',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/emaillogs'),
                            'submenuitem'     => array()
                        ),

                  // array(
                  //     'menucaption'     => 'File Manager',
                  //     'iconClass'       => 'fa fa-fw fa-folder',
                  //     'iconImage'       => '',
                  //     'targetlink'      => '',
                  //     'submenuitem'     =>  array(
                  //       array(
                  //           'menucaption'     => 'File Attachments',
                  //           'groupmenu'       => 'File Managert',
                  //           'iconClass'       => 'fa fa-fw fa-paperclip',
                  //           'iconImage'       => '',
                  //           'targetlink'      => '',
                  //           'submenuitem'     => array()
                  //       ),
                  //     )
                  // ),

                  array(
                      'menucaption'     => 'System Settings',
                      'iconClass'       => 'fa fa-fw fa-gear',
                      'iconImage'       => '',
                      'targetlink'      => '', 
                      'submenuitem'         =>  array(           
                        array(
                            'menucaption'     => 'General Settings',
                            'iconClass'       => 'fa fa-fw fa-microchip',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/settings/general'),
                            'submenuitem'         =>  array()
                        ),
                        array(
                            'menucaption'     => 'Security Settings',
                            'iconClass'       => 'fa fa-fw fa-lock',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/settings/security'),
                            'submenuitem'         =>  array()
                        ),
                        array(
                            'menucaption'     => 'Email Settings',
                            'iconClass'       => 'fa fa-fw fa-envelope',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/settings/email'),
                            'submenuitem'         =>  array()
                        ),
                        array(
                            'menucaption'     => 'SMS Settings',
                            'iconClass'       => 'fa fa-fw fa-comment',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/settings/sms'),
                            'submenuitem'         =>  array()
                        ),
                      )      
                  ),

                  array(
                      'menucaption'     => 'Logs',
                      'iconClass'       => 'fa fa-fw fa-book',
                      'iconImage'       => '',
                      'targetlink'      => '', 
                      'submenuitem'         =>  array(    
                         array(
                            'menucaption'     => 'User Activity Logs',
                            'groupmenu'       => 'Logs',
                            'iconClass'       => 'fa fa-fw fa-list-ul',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/useractivitylogs'),
                            'submenuitem'     => array()
                        ),       
                        array(
                            'menucaption'     => 'Code Error',
                            'groupmenu'       => 'Logs',
                            'iconClass'       => 'fa fa-fw fa-code',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/debugginglogs/codes'),
                            'submenuitem'         =>  array()
                        ),
                        array(
                            'menucaption'     => 'Sql Statement Error',
                            'groupmenu'       => 'Logs',
                            'iconClass'       => 'fa fa-fw fa-database',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/debugginglogs/query'),
                            'submenuitem'         =>  array()
                        ),
                        array(
                            'menucaption'     => 'Sending Mail Error',
                            'groupmenu'       => 'Logs',
                            'iconClass'       => 'fa fa-fw fa-envelope',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/debugginglogs/email'),
                            'submenuitem'         =>  array()
                        ),
                      )      
                  ),
                  array(
                      'menucaption'     => 'System Security',
                      'iconClass'       => 'fa fa-fw fa-shield',
                      'iconImage'       => '',
                      'targetlink'      => '', 
                      'submenuitem'         =>  array(   
                        array(
                            'menucaption'     => 'MAX-LOGIN ATTEMPT IP/USER',
                            'iconClass'       => 'fa fa-fw fa-solid fa-user-lock',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/systemsecurity/maxloginattempt'),
                            'submenuitem'     => []            
                        ), 
                        array(
                            'menucaption'     => 'DETECTED SUSPICIOUS IP',
                            'iconClass'       => 'fa fa-fw fa-solid fa-triangle-exclamation',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/systemsecurity/detectedip'),
                            'submenuitem'     => []            
                        ),        
                        array(
                            'menucaption'     => 'BANNED IP',
                            'iconClass'       => 'fa fa-fw fa-code-fork',
                            'iconImage'       => '',
                            'targetlink'      => site_url('administrator/systemsecurity/bannedip'),
                            'submenuitem'     => []            
                        ),
                      )      
                  ),
                  array(
                      'menucaption'     => 'System Diagnose',
                      'iconClass'       => 'fa fa-fw fa-stethoscope',
                      'iconImage'       => '',
                      'targetlink'      => site_url('administrator/diagnose'), 
                      'submenuitem'         =>  array(           
                      )      
                  ),
              );


$defaultMenuItem = [
  [
    'menucaption'     => 'Dashboard',
    'iconClass'       => 'fa fa-fw fa-bar-chart ',
    'iconImage'       => '',
    'targetlink'      => site_url('dashboard'),
    'submenuitem'     => []
  ],
  [
    'menucaption'     => 'Pre-Authorization',
    'iconClass'       => 'fa fa-fw fa-brands fa-wpforms',
    'iconImage'       => '',
    'targetlink'      => site_url('preauthorization'),
    'submenuitem'     => []
  ],
  [
    'menucaption'     => 'Claims',
    'iconClass'       => 'fa fa-fw fa-brands fa-wpforms',
    'iconImage'       => '',
    'targetlink'      => site_url('claims'),
    'submenuitem'     => []
  ],
  [
    'menucaption'     => 'Z-Ben Coordinator',
    'iconClass'       => 'fa fa-fw fa-users ',
    'iconImage'       => '',
    'targetlink'      => site_url('coordinator'),
    'submenuitem'     => []
  ],
  [
    'menucaption'     => 'Z-Ben Facilities',
    'iconClass'       => 'fa fa-fw fa-hospital',
    'iconImage'       => '',
    'targetlink'      => site_url('facilities'),
    'submenuitem'     => []
  ],
];

$unsetArray = [];


switch((int) $CI->usertype)
{
  case (int) $CI->usertype < 3:
  
  break;
  
  case 3:


  default:
   
    if(@$CI->userclassification <> '')
    {
      switch( (int) $CI->userclassification)
      {
        case 1:
        case 6:
        case 7:
          $unsetArray = [4];
          // $userlink = base64_encode($this->m_api->encryptdecryptString('encrypt',base64_encode($this->system_settings['PasswordHashing']),$this->userid));
          // $defaultMenuItem[2]['targetlink']=site_url('api/access/details/'.$userlink);
        break;
        
      }
    }
    
  break;
}

// function unsetMenus($menuindex = '',$parentindex = '')
// {

//   if( is_array($menuindex) )
//   {
//     foreach ($menuindex as $mkey => $mval)
//     {
//       if( $mval <> '')
//       {
//         if( is_array($mval) && count($mval) > 0)
//         {
//           unsetMenus($mval,$defaultMenuItem[$mkey]['submenuitem']);
//         }
//         else
//         {
//           unset($defaultMenuItem[$mkey]['submenuitem'][$mval]);
//         }
//       }
//     }
//   }
//   else
//   {
//     unset($defaultMenuItem[$indexMenu]);
//   }
// }


if( count($unsetArray) > 0)
{
  foreach($unsetArray as $indexMenu )
  {
    // unsetMenus($indexMenu);
    // if( is_array($indexMenu) )
    // {
    //   unset($defaultMenuItem[$indexMenu]); 
    // }
    // else
    // {
      unset($defaultMenuItem[$indexMenu]); 
    // }

  }
}

$menuitem = ( isset($adminMenu) && $adminMenu == true ) ? $adminMenuItem : $defaultMenuItem;
echo $CI->myutilities->siteMenu($menuitem);

?>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">


</script>