<?php
/**
 * Security Library
 *
 *
 */

class Security {

    /**
     * Generate CSRF Token
     *
     * @param $token_name
     * @return string
     */
    public static function generateCSRFToken($token_name){
        $token = sha1(bin2hex(openssl_random_pseudo_bytes(40, $cstrong)));
        Session::set('CSRF_TOKEN_' . $token_name, $token);
        return $token;
    }

    /**
     * Get CSRF Token
     *
     * @param $token_name
     * @return mixed
     */
    public static function getCSRFToken($token_name){
        return Session::get('CSRF_TOKEN_' . $token_name);
    }

    /**
     * XSS Filter
     *
     * @param $string
     * @return string
     */
    public static function xssFilter($string){
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

}
