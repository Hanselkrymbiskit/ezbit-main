<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function WS_Check($param = '') 
{
    $CI = & get_instance();
    return $CI->getname();
}
