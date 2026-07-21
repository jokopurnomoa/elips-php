<?php
/**
 * Encryption Library
 *
 * Security encrypt & decrypt data
 *
 */

namespace Elips\Libraries;

class Encryption
{

    private static $keyStd;

    /**
     * OpenSSL cipher method (mode is part of the name, e.g. aes-256-cbc).
     *
     * @var string
     */
    private static $cipher = 'aes-256-cbc';

    /**
     * Initialize Library
     */
    public static function init()
    {
        if (app_config('encryption_key') != '') {
            self::$keyStd = app_config('encryption_key');
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

        $iv_size = openssl_cipher_iv_length(self::$cipher);
        $iv = random_bytes($iv_size);
        $ciphertext = openssl_encrypt($plaintext, self::$cipher, $key, OPENSSL_RAW_DATA, $iv);

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
        $iv_size = openssl_cipher_iv_length(self::$cipher);

        if ($ciphertext_dec === false || strlen($ciphertext_dec) <= $iv_size) {
            return null;
        }

        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);

        $result = openssl_decrypt($ciphertext_dec, self::$cipher, $key, OPENSSL_RAW_DATA, $iv_dec);

        if ($result !== false) {
            return trim($result);
        } elseif (APP_ENV === 'development') {
            error_dump('Encryption::decode() failed to decrypt the given data.');
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
            $key = self::$keyStd;
        }

        return md5(hash('sha256', $key));
    }

    /**
     * Set Encryption Cipher
     *
     * Accepts an OpenSSL cipher method name, e.g. 'aes-256-cbc' or 'aes-128-cbc'.
     *
     * @param $cipher
     */
    public static function setCipher($cipher)
    {
        self::$cipher = $cipher;
    }

    /**
     * Set Encryption Mode (backward-compatibility shim)
     *
     * The block mode is now part of the OpenSSL cipher name, so use setCipher()
     * instead (e.g. 'aes-256-cbc'). Kept to avoid breaking existing callers.
     *
     * @param $mode
     * @deprecated
     */
    public static function setMode($mode)
    {
        // no-op: mode is encoded in the cipher name (see setCipher()).
    }

}
