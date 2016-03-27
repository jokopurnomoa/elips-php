<?php
/**
 * Input Library
 *
 *
 */

class Input
{

    /**
     * Get Method Input
     *
     * @param $name
     * @param bool $xss_clean
     * @return null|string
     */
    public static function get($name, $xss_clean = false)
    {
        return get_input($name, $xss_clean);
    }

    /**
     * Post Method Input
     *
     * @param $name
     * @param bool $xss_clean
     * @return null|string
     */
    public static function post($name, $xss_clean = false)
    {
        return post_input($name, $xss_clean);
    }

    /**
     * Post & Get Method Input (Post First)
     *
     * @param $name
     * @param bool $xss_clean
     * @return null
     */
    public static function postGet($name, $xss_clean = false)
    {
        return post_get_input($name, $xss_clean);
    }

}
