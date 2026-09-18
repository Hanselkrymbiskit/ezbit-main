<?php
#[AllowDynamicProperties]
class Rijndael128Encryptor{
    public $CI;
    function __construct()
    {
        $this->CI = & get_instance();
        // Try to autologin
        // $this->autologin();
        // log_message("error","LOADED");
    }

    /*
     * Adds PKCS7 padding
     */
    private function addpadding($inputstring)
    {
        $blocksize = 128;
        $len = strlen($inputstring);
        $pad = $blocksize - ($len % $blocksize);
        $inputstring .= str_repeat(chr($pad), $pad);
        return $inputstring;
    }
 
    /*
     * Strips PKCS7 padding
     */
    private function strippadding($inputstring)
    {
        $slast = ord(substr($inputstring, -1));
        $slastc = chr($slast);
        if(preg_match("/$slastc{".$slast."}/", $inputstring)){
            $inputstring = substr($inputstring, 0, strlen($inputstring)-$slast);
            return $inputstring;
        } else {
            return false;
        }
    }
 
    /*
     * Encrypt method
     * Both Keys and IVs need to be 16 characters encoded in base64. 
     */ 
    public function encrypt($inputstring, $inputkey, $inputiv)
    {
            $key = base64_decode($inputkey);
            $iv = base64_decode($inputiv);
            // Pad text and encrypt
            $padded_string = $this->addpadding($inputstring);
            $encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $padded_string, MCRYPT_MODE_CBC, $iv);
            // Encode to base64 and return
            return base64_encode($encrypted_string);
    }
 
    /*
     * Decrypt method
     * Both Keys and IVs need to be 16 characters encoded in base64. 
     */ 
    public function decrypt($inputstring, $inputkey, $inputiv)
    {
            $key = base64_decode($inputkey);
            $iv = base64_decode($inputiv);
            // Decode from base64 and decrypt
            $decoded_string = base64_decode($inputstring);
            $decrypted_string = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $decoded_string, MCRYPT_MODE_CBC, $iv);
            // Unpad text and return
            return $this->strippadding($decrypted_string);
    }
}
?>