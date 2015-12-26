<?php
/**
 * Security library
 *
 *
 */

class Security {

    public static function generateCSRFToken($token_name){
        $token = sha1(bin2hex(openssl_random_pseudo_bytes(40, $cstrong)));
        Session::set('CSRF_TOKEN_' . $token_name, $token);
        return $token;
    }

    public static function getCSRFToken($token_name){
        return Session::get('CSRF_TOKEN_' . $token_name);
    }

    public static function xssFilter($string){
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

}
