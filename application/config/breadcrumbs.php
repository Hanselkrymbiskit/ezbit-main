<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| BREADCRUMB CONFIG
| -------------------------------------------------------------------
| This file will contain some breadcrumbs' settings.
|
| $config['crumb_divider']		The string used to divide the crumbs
| $config['tag_open'] 			The opening tag for breadcrumb's holder.
| $config['tag_close'] 			The closing tag for breadcrumb's holder.
| $config['crumb_open'] 		The opening tag for breadcrumb's holder.
| $config['crumb_close'] 		The closing tag for breadcrumb's holder.
|
| Defaults provided for twitter bootstrap 2.0
*/

$config['crumb_divider'] = '&nbsp;<i class="fas fa-caret-right text-primary"></i>&nbsp;';
$config['tag_open'] = '<div class="container-fluid pl-0 pr-0 pb-10"><ol class="breadcrumb breadcrumb_ci bg-transparent p-0 pt-2 mb-0">';
$config['tag_close'] = '</ol><span id="sdateclock" class="float-right"></span></div>';
$config['crumb_open'] = '<li>';
$config['crumb_last_open'] = '<li class="active">';
$config['crumb_close'] = '</li>';


/* End of file breadcrumbs.php */
/* Location: ./application/config/breadcrumbs.php */