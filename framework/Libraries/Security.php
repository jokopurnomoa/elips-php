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
     * @param $tokenName
     * @return string
     */
    public static function generateCSRFToken($tokenName)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $token = sha1(bin2hex(openssl_random_pseudo_bytes(40, $cstrong)));
        } else {
            $token = sha1(uniqid() . rand(0, 99999999));
        }

        Session::set('CSRF_TOKEN_' . $tokenName, $token);
        return $token;
    }

    /**
     * Get CSRF Token
     *
     * @param $tokenName
     * @return mixed
     */
    public static function getCSRFToken($tokenName)
    {
        return Session::get('CSRF_TOKEN_' . $tokenName);
    }

    /**
     * Check Token Valid
     *
     * @param $tokenName
     * @param $token
     * @return bool
     */
    public static function isCSRFTokenValid($tokenName, $token)
    {
        if (Session::get('CSRF_TOKEN_' . $tokenName) === $token) {
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
