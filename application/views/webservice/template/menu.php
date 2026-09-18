<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

/* Standard Structure for Menu */
// $menuItemStructure = array(
//                         array(
//                           'menucaption'     => 'Dashboard',
//                           'iconClass'       => 'fa-fw fa-tachometer-alt',
//                           'iconImage'       => '',
//                           'targetlink'      => site_url('administrator/dashboard'),
//                           'submenuitem'     =>  array(
//                             array(
//                               'menucaption'     => 'Dashboard',
//                               'iconClass'       => 'fa-fw fa-tachometer-alt',
//                               'iconImage'       => '',
//                               'targetlink'      => site_url('administrator/dashboard'),
//                               'submenuitem'     =>  array()
//                             )
//                           )
//                         ),
//                       );


// Add API METHODS

$APIMethodList = $CI->sqlhelper->local->select("ws_method")->where("WS_Enable = 'Y'")->ex_select('','WS_Order asc','')->result();
$methodlink = [
  ['menucaption'     => 'WSCheck',
    'iconClass'       => 'fa-fw fa-code-fork',
    'iconImage'       => '',
    'targetlink'      => site_url('api/index/methods/WSCheck'),
    'submenuitem'     =>  [],
  ]
];
foreach($APIMethodList['Data'] as $mr => $mc )
{
  $methodlink[] = array(
                        'menucaption'     => $mc['WS_Function_Name'],
                        'iconClass'       => 'fa-fw fa-code-fork',
                        'iconImage'       => '',
                        'targetlink'      => site_url('api/index/methods/').$mc['WS_Function_Name'],
                        'submenuitem'     =>  array()
                  );
}


$defaultMenuItem = array(
                  array(
                        'menucaption'     => 'Response Code',
                        'iconClass'       => 'fa-fw fa-list',
                        'iconImage'       => '',
                        'targetlink'      => site_url('api/index/responsecode'),
                        'submenuitem'     =>  array()
                  ),
                  array(
                        'menucaption'     => 'Authentication',
                        'iconClass'       => 'fa-fw fa-handshake-o',
                        'iconImage'       => '',
                        'targetlink'      => site_url('api/index/authsecurity'),
                        'submenuitem'     =>  array()
                  ),
                  array(
                        'menucaption'     => 'API Methods',
                        'iconClass'       => 'fa-fw fa-code',
                        'iconImage'       => '',
                        'targetlink'      => site_url('api'),
                        'submenuitem'     => $methodlink , 
                  ),
                  array(
                        'menucaption'     => 'Message Format',
                        'iconClass'       => 'fa-fw fa-envelope',
                        'iconImage'       => '',
                        'targetlink'      => site_url('api/index/messageformat'),
                        'submenuitem'     =>  array()
                  ),
                  array(
                        'menucaption'     => 'Response Format',
                        'iconClass'       => 'fa-fw fa-envelope-o',
                        'iconImage'       => '',
                        'targetlink'      => site_url('api/index/responseformat'),
                        'submenuitem'     =>  array()
                  ),
                  array(
                        'menucaption'     => 'Reference Libraries',
                        'iconClass'       => 'fa-fw fa-list',
                        'iconImage'       => '',
                        'targetlink'      => site_url('cif/index'),
                        'submenuitem'     =>  array()
                  ),
              );






$unsetArray = [];
$menuitem = $defaultMenuItem;
echo $CI->myutilities->siteMenu($menuitem);

?>
