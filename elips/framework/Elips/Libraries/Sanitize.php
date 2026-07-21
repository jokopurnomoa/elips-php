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
        // FILTER_SANITIZE_MAGIC_QUOTES was deprecated in PHP 8.1; addslashes() is the replacement.
        return addslashes((string) $string);
    }

    /**
     * Sanitize String
     *
     * @param $string
     * @param string $flag
     * @return mixed
     */
    public static function string($string, $flag = '')
    {
        // FILTER_SANITIZE_STRING was deprecated in PHP 8.1. Emulate its default
        // behaviour: strip tags, then HTML-encode the remaining special characters.
        return htmlspecialchars(strip_tags((string) $string), ENT_QUOTES, 'UTF-8');
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
        // FILTER_SANITIZE_STRIPPED (alias of FILTER_SANITIZE_STRING) was deprecated in PHP 8.1.
        return htmlspecialchars(strip_tags((string) $string), ENT_QUOTES, 'UTF-8');
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
