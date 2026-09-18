<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
*/
$config['useragent'] = 'none';
$config['protocol'] = 'smtp';
$config['smtp_crypto'] = 'tls';
$config['smtp_host'] = 'smtp.gmail.com';
$config['smtp_port'] = '587';
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['wordwrap(str)'] = TRUE;
$config['mailtype'] = 'html';
$config['newline'] = "\r\n";
$config['charset'] = 'iso-8859-1';
/* End of file email.php */
/* Location: ./application/config/email.php */