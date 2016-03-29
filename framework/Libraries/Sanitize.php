<?php
/**
 * Sanitize Library
 *
 *
 */

namespace Elips\Libraries;

class Sanitize
{

    /**
     * Sanitize Email
     *
     * @param $email
     * @return mixed
     */
    public static function email($email)
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Sanitize URL
     *
     * @param $url
     * @return mixed
     */
    public static function url($url)
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * Sanitize Special Chars
     *
     * @param $string
     * @param string $flag
     * @return mixed
     */
    public static function specialChars($string, $flag = '')
    {
        if ($flag != '') {
            return filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS, $flag);
        } else {
            return filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
        }
    }

    /**
     * Sanitize Full Special Chars
     *
     * @param $string
     * @param $string
     * @return mixed
     */
    public static function fullSpecialChars($string, $flag = '')
    {
        if ($flag != '') {
            return filter_var($string, FILTER_SANITIZE_FULL_SPECIAL_CHARS, $flag);
        } else {
            return filter_var($string, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }

    /**
     * Sanitize Magic Quotes
     *
     * @param $string
     * @return mixed
     */
    public static function magicQuotes($string)
    {
        return filter_var($string, FILTER_SANITIZE_MAGIC_QUOTES);
    }

    /**
     * Sanitize String
     *
     * @param $string
     * @param $string
     * @return mixed
     */
    public static function string($string, $flag = '')
    {
        if ($flag != '') {
            return filter_var($string, FILTER_SANITIZE_STRING, $flag);
        } else {
            return filter_var($string, FILTER_SANITIZE_STRING);
        }
    }

    /**
     * Sanitize Number Integer
     *
     * @param $numberInt
     * @return mixed
     */
    public static function int($numberInt)
    {
        return filter_var($numberInt, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Sanitize Number Float
     *
     * @param $numberFloat
     * @param int $flag
     * @return mixed
     */
    public static function float($numberFloat, $flag = FILTER_FLAG_ALLOW_FRACTION)
    {
        return filter_var($numberFloat, FILTER_SANITIZE_NUMBER_FLOAT, $flag);
    }

    /**
     * Sanitize Stripped
     *
     * @param $string
     * @return mixed
     */
    public static function stripped($string)
    {
        return filter_var($string, FILTER_SANITIZE_STRIPPED);
    }

    /**
     * Sanitize Encoded
     *
     * @param $string
     * @param string $flag
     * @return mixed
     */
    public static function encoded($string, $flag = '')
    {
        if ($flag != '') {
            return filter_var($string, FILTER_SANITIZE_ENCODED, $flag);
        } else {
            return filter_var($string, FILTER_SANITIZE_ENCODED);
        }
    }

}
