<?php
/**
 * URI Library
 *
 * Get uri segment
 *
 */

namespace Elips\Core;

class URI
{

    /**
     * Get URI Segment
     *
     * @param $segment
     * @return string
     */
    public static function segment($segment)
    {
        $segment = $segment > 0 ? $segment : 1;
        $uri = explode('/', URI::getURI());
        return (isset($uri[$segment - 1]) ? $uri[$segment - 1] : '');
    }

    /**
     * Get URI Without Get Parameter
     *
     * @return mixed
     */
    public static function getURI()
    {
        if (!isset($_SERVER['REQUEST_URI']) OR !isset($_SERVER['SCRIPT_NAME'])) {
            return '';
        }

        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        } elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }

        $param_pos = strpos($uri, '?');
        if ($param_pos !== false) {
            $uri = substr($uri, 0, $param_pos);
        }

        return str_replace(array('//', '../'), '/', trim($uri, '/'));
    }

    /**
     * Get Full URI With Get Parameter
     *
     * @return mixed
     */
    public static function getFullURI()
    {
        if (!isset($_SERVER['REQUEST_URI']) OR !isset($_SERVER['SCRIPT_NAME'])) {
            return '';
        }

        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        } elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }

        return str_replace(array('//', '../'), '/', trim($uri, '/'));
    }

    /**
     * Redirect
     *
     * @param $link
     */
    public static function redirect($url)
    {
        header('location:' . $url);
    }

    /**
     * Base URL
     *
     * @return string
     */
    public static function baseUrl()
    {
        return base_url();
    }
}
