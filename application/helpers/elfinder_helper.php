<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function initialize_elfinder($value=''){
	$CI =& get_instance();
	// $CI->load->helper('path');
	// $opts = array(
	//     //'debug' => true, 
	//     'roots' => array(
	//       array( 
	//         'driver' => 'LocalFileSystem', 
	//         'path'   => './uploads/files/', 
	//         'URL'    => site_url('assets/files').'/'
	//         // more elFinder options here
	//       ) 
	//     )
	// );


	$opts = array(
                'roots' => array(
                    array( 
                        'driver'        => 'LocalFileSystem',
                        'path'          => FCPATH . '/files',
                        'URL'           => base_url('files'),
                        'uploadDeny'    => array('all'),                  // All Mimetypes not allowed to upload
                        'uploadAllow'   => array('image', 'text/plain', 'application/pdf'),// Mimetype `image` and `text/plain` allowed to upload
                        'uploadOrder'   => array('deny', 'allow'),        // allowed Mimetype `image` and `text/plain` only
                        'accessControl' => array($this, 'elfinderAccess'),// disable and hide dot starting files (OPTIONAL)
                        // more elFinder options here
                    ) 
                ),
            );

	return $opts;
}
?>