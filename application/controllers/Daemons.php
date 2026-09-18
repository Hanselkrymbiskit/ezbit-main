<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daemons extends MY_Controller {

	function __construct()
	{
		 parent::__construct();
	}

    public function index($tst = '')
    {    
        ignore_user_abort( 1 );
        try
        {
            if ( !in_array( $this->input->ip_address(), $this->config->item( 'proxy_ips' ) ) )
            {
                log_message( "error", "Daemon called from untrusted IP-address: " . $this->input->ip_address() );
                return;
            }

            if( isset($_POST['data']) )
            {
                $params = unserialize( $this->encryption->decrypt( urldecode( @$_POST['data'] ) ) ) ; // Use for Guzzle Call
                unset( $_POST );
                $model = array_shift( $params );
                $method = array_shift( $params );

                $this->load->model( $model );
                $x = explode("/",$model);
                $model = ( count($x) > 1 ) ? $x[(count($x) -1 )] : $model;
               
                try
                {
                    $rr = call_user_func_array( array( $this->$model, $method ), $params );
                    if ( $rr === FALSE )
                    {
                        log_message( "error", "Daemon could not call: " . $model . "::" . $method . "()" );
                    }
                    else
                    {
                        if( $method == 'daemontest')
                        {
                            echo $rr;
                        }
                    }
                }
                catch(Exception $call_err)
                {
                    log_message( "error", "Daemon call error : " .$call_err->getMessage() . $e->getFile( ) . $e->getLine( ) );
                }
            }
        }
        catch(Exception $e)
        {
            log_message( "error", "Daemon has error: " . $e->getMessage( ) . $e->getFile( ) . $e->getLine( ) );
        }    
       
    }

    public function index2()
    {    
        ignore_user_abort( 1 );
        try
        {
            if ( !in_array( $this->input->ip_address(), $this->config->item( 'proxy_ips' ) ) )
            {
                log_message( "error", "Daemon called from untrusted IP-address: " . $this->input->ip_address() );
                return;
            }

            if( isset($_POST['data']) )
            {

                $params = unserialize( $this->encryption->decrypt( $_POST['data']  ) ) ;
                // $params = unserialize( $this->encryption->decrypt( urldecode( $_POST['data'] ) ) ) ; // Use for Guzzle Call
                unset( $_POST );
                $model = array_shift( $params );
                $method = array_shift( $params );

                $this->load->model( $model );
                $x = explode("/",$model);
                $model = ( count($x) > 1 ) ? $x[(count($x) -1 )] : $model;
               
                try
                {
                    if ( call_user_func_array( array( $this->$model, $method ), $params ) === FALSE )
                    {
                        log_message( "error", "Daemon could not call: " . $model . "::" . $method . "()" );
                    }
                }
                catch(Exception $call_err)
                {
                    log_message( "error", "Daemon call erro : " .$call_err->getMessage() . $e->getFile( ) . $e->getLine( ) );
                }
            }

        }
        catch(Exception $e)
        {
            log_message( "error", "Daemon has error: " . $e->getMessage( ) . $e->getFile( ) . $e->getLine( ) );
        }    
       
    }
   
   
}