<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filedownload extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->data['pagetype'] = "filedownload";

        if( $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if( !isset($_POST['UToken']) || !$this->myutilities->validateToken($_POST['UToken']) )
            {
               $return = array('Status' => 0, 'Message' => 'Permission Denied!');

               echo json_encode($return);

            }
            else
            {
              unset($_POST['UToken']);
            }
        }
    }

    // function index()
    // {
    //     $this->data['title'] = "File Download"; 
    //     $this->data['PageTitle'] = "File Download"; 
    //     $indexFile = 'index';
    //     $headerfooter = false;

    //      $this->data['loadcss'] = array(
    //         'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
    //         'global/vendor/clockpicker/clockpicker',
    //         'global/vendor/jquery-labelauty/jquery-labelauty',
    //         'global/vendor/formvalidation/formValidation',        
    //         'global/vendor/summernote/summernote-lite',
    //     );

    //     $this->data['loadjsmain'] = array(
    //         'global/vendor/bootstrap-datepicker/bootstrap-datepicker',
    //         'global/vendor/clockpicker/bootstrap-clockpicker.min',
    //         'global/vendor/formatter/jquery.formatter',
    //         'global/vendor/formvalidation/formValidation.min',
    //         'global/vendor/formvalidation/framework/bootstrap4.min',
    //         'global/vendor/jquery-labelauty/jquery-labelauty', 
    //         'global/vendor/summernote/summernote.min',
    //     );

    //     $this->data['loadjschild'] = array(
    //         'global/js/Plugin/bootstrap-datepicker',
    //         'global/js/Plugin/clockpicker',
    //         'global/js/Plugin/formatter',
    //         'global/js/Plugin/jquery-labelauty',
    //         'global/js/Plugin/responsive-tabs',
    //         'global/js/Plugin/closeable-tabs',
    //         'global/js/Plugin/tabs',
    //         'global/js/Plugin/summernote',
    //     ); 

    //     if( $this->tank_auth->is_logged_in() )
    //     {
    //         $indexFile =  'index2';
    //         $headerfooter = true;

    //         $pageheaderaction = '
    //             <div id="PageTitle-Button-Holder" class="float-right">
    //                 <button type="button" id="btn_newDownloadItem" name="btnActionButton" class="btn btn-dark mr-5" title="New Download Item" onclick="window.location.href=\''.site_url('
    //                     download/newdownloaditem').'\'">
    //                     <i class="fa fa-plus mr-10"></i>New Download
    //                 </button>
    //                 '.@$aaddBack.'
    //             </div>      
    //         ';

    //         if( $this->usertype > 3 )
    //         {
    //             $pageheaderaction = '';
    //         }
             
    //         $pageheaderaction = '';
    //         $this->data['pageheaderaction'] = @$pageheaderaction;  
    //     }

    //     $this->load->template('templates/download/'.$indexFile,$this->data,'',$headerfooter);     
    // }

    function fileitem($DataCount = '',$FileTitle = '')
    {
        //$this->load->model('api/m_api');
        $decrypt = base64_decode($DataCount);
        $decrypt = explode(":",$decrypt);
        $DataCount = $this->m_api->encryptdecryptString('decrypt',$decrypt[1],$decrypt[0]);
        if($DataCount == "" )
        {
            goto ExitFunction;
        }

        // Get Download Item
        $dQUERY = "
            select
                *
            from system_fileupload a
            where DataCount = '".$DataCount."'
        ";

        $getDItem = $this->sqlhelper->local->sql($dQUERY)->row();
       
        if($getDItem['Count'] == 0 )
        {
            goto ExitFunction;
        }

        $FileDetails = $getDItem['Data'];
        $path = $FileDetails['File_Path'];
        if( $path )
        {
            $tmpPathEx = explode("/downloads/",$path);
            $path = FCPATH.'assets/'.$path;
            $dPath= $path;
            if(is_file($path))
            {
                // required for IE
                if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off'); }
                // get the file mime type using the file extension
                $this->load->helper('file');
                $name = $FileDetails['File_FileName'];
                $mime = get_mime_by_extension($path);

                // Build the headers to push out the file properly.
                header('Pragma: public');     // required
                header('Expires: 0');         // no cache
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($path)).' GMT');
                header('Cache-Control: private',false);
                header('Content-Type: '.$mime);  // Add the mime type from Code igniter.
                if(!isset($decrypt[2])){
                header('Content-Disposition: attachment; filename="'.strtoupper(basename( (@$FileTitle <> '') ? $FileTitle : $name).'"'));  // Add the file name
                }
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: '.filesize($path)); // provide file size
                header('Connection: close');
                $path = str_replace(FCPATH, site_url(), $path);
                readfile($dPath); // push it out
            }
        }
        else
        {
            redirect('');
        } 

        ExitFunction:
        if(!isset($decrypt[2]))
        {
            echo "<script nonce=\"myself\" >self.close();</script>";
        }

    }


}
   
   