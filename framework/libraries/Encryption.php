<?php
/**
 * Encryption
 *
 * Security encrypt & decrypt data
 *
 */

class Encryption {

    private static $key_std;
    private static $cipher = MCRYPT_RIJNDAEL_256;
    private static $mode = MCRYPT_MODE_CBC;

    public static function init(){
        global $config;
        if(isset($config['encryption_key'])){
            if($config['encryption_key'] != ''){
                self::$key_std = $config['encryption_key'];
            } elseif(APP_ENV === 'development') {
                error_dump('Encryption key not yet set in \'' . APP_PATH . 'config/app.php\'!');die();
            }
        } elseif(APP_ENV === 'development') {
            error_dump('Encryption key not yet set in \'' . APP_PATH . 'config/app.php\'!');die();
        }
    }

    public static function encode($plaintext, $key = ''){
        $key = self::getKey($key);

        $iv_size = mcrypt_get_iv_size(self::$cipher, self::$mode);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $ciphertext = mcrypt_encrypt(self::$cipher, $key, $plaintext, self::$mode, $iv);

        return base64_encode($iv . $ciphertext);
    }

    public static function decode($ciphertext, $key = ''){
        if (preg_match('/[^a-zA-Z0-9\/\+=]/', $ciphertext)){
            return false;
        }

        $key = self::getKey($key);

        $ciphertext_dec = base64_decode($ciphertext);
        $iv_size = mcrypt_get_iv_size(self::$cipher, self::$mode);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);

        ob_start();
        echo mcrypt_decrypt(self::$cipher, $key, $ciphertext_dec, self::$mode, $iv_dec);
        $result = ob_get_contents();
        @ob_end_clean();

        if(strpos($result, '<b>Warning</b>:  mcrypt_decrypt():') === false){
            return $result;
        } elseif(APP_ENV === 'development'){
            error_dump($result);
            die();
        }

        return null;
    }

    private static function getKey($key){
        if($key === ''){
            $key = self::$key_std;
        }
        return md5(sha1($key));
    }

    public static function setCipher($cipher){
        self::$cipher = $cipher;
    }

    public static function setMode($mode){
        self::$mode = $mode;
    }
}
