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
        $config = null;

        if(file_exists(APP_PATH . 'config/app.php')){
            require APP_PATH . 'config/app.php';
        } elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'config/app.php\' not found!');die();
        }

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

        return mcrypt_decrypt(self::$cipher, $key, $ciphertext_dec, self::$mode, $iv_dec);
    }

    private static function getKey($key){
        if($key === ''){
            $key = sha1(self::$key_std);
        } else {
            $key = sha1($key);
        }

        return md5($key);
    }

    public static function setCipher($cipher){
        self::$cipher = $cipher;
    }

    public static function setMode($mode){
        self::$mode = $mode;
    }
}
