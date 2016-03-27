<?php
/**
 * Encryption Library
 *
 * Security encrypt & decrypt data
 *
 */

class Encryption
{

    private static $key_std;

    /**
     * @var string
     */
    private static $cipher = MCRYPT_RIJNDAEL_128;

    /**
     * @var string
     */
    private static $mode = MCRYPT_MODE_CBC;

    /**
     * Initialize Library
     */
    public static function init()
    {
        if (get_app_config('encryption_key') != '') {
            self::$key_std = get_app_config('encryption_key');
        } elseif(APP_ENV === 'development') {
            error_dump('Encryption key not yet set in \'' . APP_PATH . 'config/app.php\'!');die();
        }
    }

    /**
     * Encrypt Data
     *
     * @param $plaintext
     * @param string $key
     * @return string
     */
    public static function encode($plaintext, $key = '')
    {
        $key = self::getKey($key);

        $iv_size = mcrypt_get_iv_size(self::$cipher, self::$mode);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_RANDOM);
        $ciphertext = mcrypt_encrypt(self::$cipher, $key, $plaintext, self::$mode, $iv);

        $ciphertext = trim(base64_encode($iv . $ciphertext));
        return self::addSalt($ciphertext);
    }

    /**
     * Decrypt Data
     *
     * @param $ciphertext
     * @param string $key
     * @return null|string
     */
    public static function decode($ciphertext, $key = '')
    {
        $ciphertext = self::removeSalt($ciphertext);

        if (preg_match('/[^a-zA-Z0-9\/\+=]/', $ciphertext)) {
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

        if (strpos($result, '<b>Warning</b>:  mcrypt_decrypt():') === false) {
            return trim($result);
        } elseif(APP_ENV === 'development') {
            error_dump($result);
            die();
        }

        return null;
    }

    /**
     * Add ciphertext salt
     *
     * @param $ciphertext
     * @return string
     */
    private static function addSalt($ciphertext)
    {
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';
        if ($ciphertext != '') {
            for ($i = 0; $i < strlen($ciphertext); $i++) {
                $result .= $ciphertext[$i] . $keyspace[rand(0, strlen($keyspace) - 1)];
            }
        }

        return $result;
    }

    /**
     * Remove ciphertext salt
     *
     * @param $ciphertext
     * @return string
     */
    private static function removeSalt($ciphertext)
    {
        $result = '';
        if ($ciphertext != '') {
            for ($i = 0; $i < strlen($ciphertext); $i++) {
                if($i % 2 == 0){
                    $result .= $ciphertext[$i];
                }
            }
        }

        return $result;
    }

    /**
     * Get Encryption Key
     *
     * @param $key
     * @return string
     */
    private static function getKey($key)
    {
        if ($key === '') {
            $key = self::$key_std;
        }

        return md5(hash('sha256', $key));
    }

    /**
     * Set Encryption Cipher
     *
     * @param $cipher
     */
    public static function setCipher($cipher)
    {
        self::$cipher = $cipher;
    }

    /**
     * Set Encryption Mode
     *
     * @param $mode
     */
    public static function setMode($mode)
    {
        self::$mode = $mode;
    }

}
