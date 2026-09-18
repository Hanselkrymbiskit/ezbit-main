<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$login_module = $CI->load->view('templates/login/login_module.php', $CI->data, true);
$login_module = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $login_module));

echo $login_module;

?>

