<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();


$menuitem = array(

                array(
                    'menucaption'     => 'Response Code',
                    'groupmenu'       => '',
                    'iconClass'       => 'fas fa-fw fa-info-circle',
                    'iconImage'       => '',
                    'targetlink'      => site_url('api/responsecode'),
                    'submenu'         =>  array()
                ),
                array(
                    'menucaption'     => 'API Authentication',
                    'groupmenu'       => '',
                    'iconClass'       => 'fa-fw fa-key',
                    'iconImage'       => '',
                    'targetlink'      => site_url('api/authsecurity'),
                    'submenu'         =>  array()
                ),
                array(
                    'menucaption'     => 'API Message Format',
                    'groupmenu'       => '',
                    'iconClass'       => 'fa-fw fa-code',
                    'iconImage'       => '',
                    'targetlink'      => site_url('api/messageformat'),
                    'submenu'         =>  array()
                ),
                array(
                    'menucaption'     => 'API Response Format',
                    'groupmenu'       => '',
                    'iconClass'       => 'fa-fw fa-code',
                    'iconImage'       => '',
                    'targetlink'      => site_url('api/responseformat'),
                    'submenu'         =>  array()
                ),
                array(
                    'menucaption'     => 'API Methods',
                    'groupmenu'       => '',
                    'iconClass'       => 'fa-fw fa-network-wired',
                    'iconImage'       => '',
                    'targetlink'      => site_url('api/methods'),
                    'submenu'         =>  array()
                ),
                array(
                    'menucaption'     => 'Reference Libraries',
                    'groupmenu'       => '',
                    'iconClass'       => 'fa-fw fa-table',
                    'iconImage'       => '',
                    'targetlink'      => site_url('api/referencelibraries'),
                    'submenu'         =>  array()
                ),
                // array(
                //     'menucaption'     => 'Page Content',
                //     'groupmenu'       => 'Page Management',
                //     'iconClass'       => 'fa-fw fa-wrench',
                //     'iconImage'       => '',
                //     'targetlink'      => '', 
                //     'submenu'         =>  array(           
                //       array(
                //           'menucaption'     => 'About US',
                //           'groupmenu'       => '',
                //           'iconClass'       => 'fa-fw fa-info-circle',
                //           'iconImage'       => '',
                //           'targetlink'      => site_url('administrator/dashboard'),
                //           'submenu'         =>  array()
                //       ),
                //       array(
                //           'menucaption'     => 'Contact US',
                //           'groupmenu'       => '',
                //           'iconClass'       => 'fa-fw fa-phone',
                //           'iconImage'       => '',
                //           'targetlink'      => site_url('administrator/dashboard'),
                //           'submenu'         =>  array()
                //       ),
                //       array(
                //           'menucaption'     => 'News / Announcement',
                //           'groupmenu'       => '',
                //           'iconClass'       => 'fa-fw fa-newspaper',
                //           'iconImage'       => '',
                //           'targetlink'      => site_url('administrator/dashboard'),
                //           'submenu'         =>  array()
                //       ),
                //       array(
                //           'menucaption'     => 'FAQs',
                //           'groupmenu'       => '',
                //           'iconClass'       => 'fa-fw fa-question-circle',
                //           'iconImage'       => '',
                //           'targetlink'      => site_url('administrator/dashboard'),
                //           'submenu'         =>  array()
                //       ),
                //       array(
                //           'menucaption'     => 'Download',
                //           'groupmenu'       => '',
                //           'iconClass'       => 'fa-fw fa-download',
                //           'iconImage'       => '',
                //           'targetlink'      => site_url('administrator/dashboard'),
                //           'submenu'         =>  array()
                //       ),

                //     )      
                // ),
              
                
            );

$TempMenuHolder = array();
$DividerCnt = 0;
$adminmenuHTML = '';

echo $CI->myutilities->genAccordionMenu('APIMainMenu','','',true,$menuitem);

?>
