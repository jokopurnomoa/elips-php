<?php
/**
 * Security Library
 *
 *
 */

namespace Elips\Libraries;

class Security
{

    /**
     * Generate CSRF Token
     *
     * @param $token_name
     * @return string
     */
    public static function generateCSRFToken($token_name)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $token = sha1(bin2hex(openssl_random_pseudo_bytes(40, $cstrong)));
        } else {
            $token = sha1(uniqid() . rand(0, 99999999));
        }

        Session::set('CSRF_TOKEN_' . $token_name, $token);
        return $token;
    }

    /**
     * Get CSRF Token
     *
     * @param $token_name
     * @return mixed
     */
    public static function getCSRFToken($token_name)
    {
        return Session::get('CSRF_TOKEN_' . $token_name);
    }

    /**
     * Check Token Valid
     *
     * @param $token_name
     * @param $token
     * @return bool
     */
    public static function isCSRFTokenValid($token_name, $token)
    {
        if (Session::get('CSRF_TOKEN_' . $token_name) === $token) {
            return true;
        }
        return false;
    }

    /**
     * XSS Filter
     *
     * @param $string
     * @return string
     */
    public static function xssFilter($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

}
