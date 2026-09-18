<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\HandlerStack;

#[AllowDynamicProperties]
class Daemon
{
    public $CI;
    public function __construct()
    {
        $this->CI = & get_instance(); 
    }
    
    /* This Function will a Execute Background Process when called */
    /* 
        Parameters
            Model = Model Page
            method = method function in a Model
            params = parameter to be passed
            testmode = false
        
    */
    public function execute_background( /* model, method, params */ )
    {
        $url        = site_url().( ($this->CI->config->item( 'index_page' ) <> '') ? '/' : '').'daemons/index';

        try {
                
            $aparams = func_get_args();

            if($aparams[0] == 'daemontest' || $aparams[1] == 'daemontest' )
            {
                $client     = new GuzzleHttp\Client();
                $response = $client->request( 'POST', 
                                       $url,  [
                                        'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                                        'form_params' => array('data'=>urlencode( $this->CI->encryption->encrypt( serialize( $aparams ) ) )),
                                        'verify'=>false,
                                      ]);

                $xr = $response->getBody()->getContents();
                return ( @$xr <> '' ) ? trim($xr) : ''; 
            }
            else
            {
                $client     = new GuzzleHttp\Client(['timeout' => 0.05]);
                $response = $client->requestAsync( 'POST', 
                                           $url,  [
                                            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                                            'form_params' => array('data'=>urlencode( $this->CI->encryption->encrypt( serialize( $aparams ) ) )),
                                            'verify'=>false,
                                            // 'synchronous'=> false
                                          ]);

                $response->then(
                   
                    function (ResponseInterface $res) {
                        // log_message("error", $res->getStatusCode());
                        // log_message("error", $res->getBody()->getContents());
                    },
                    function (RequestException $e) {
                        log_message("error", $res->getMessage());
                        log_message("error", $res->getRequest()->getMethod());
                    },
     
                );

                try {
                    $response->wait();
                } catch (\Exception $ex) {
                    ## Handle                       
                }
            }

        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            #guzzle repose for future use
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            log_message("debug","Response New Call : ".$responseBodyAsString);

            // call via fsocket
            $this->execute_background2(func_get_args( ));
        }
    }
    
    public function execute_background2( $functionparamd = '' )
    {
         $url        = site_url().( ($this->CI->config->item( 'index_page' ) <> '') ? '/' : '').'daemons/index2';
        $functionparam = urlencode( $this->CI->encryption->encrypt( serialize( (($functionparamd) ? $functionparamd : func_get_args( ) ) ) ) );

        $parts = parse_url($url);
        if ( strcmp( $parts['scheme'], 'https' ) == 0 )
        {
            $port = 443;
            $host = "ssl://" . $parts['host'];
        }
        else 
        {
            $port = 80;
            $host = $parts['host'];
        }

        if ( ( $fp = fsockopen( $host, isset( $parts['port'] ) ? $parts['port'] : $port, $errno, $errstr, 30 ) ) === FALSE )
        {
            throw new Exception( "Internal server error: background process could not be started" );
        }

        // $ci->load->library( 'encrypt' );
        $post_string = "data=" . $functionparam;
        $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $host . "\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: " . strlen( $post_string ) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        $out .= $post_string;
        fwrite( $fp, $out );
        fclose( $fp );

    }
}