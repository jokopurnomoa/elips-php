<?php
/**
 * Input Library
 *
 *
 */

namespace Elips\Libraries;

class Input
{

    /**
     * Get Method Input
     *
     * @param $name
     * @param bool $xssClean
     * @return null|string
     */
    public static function get($name, $xssClean = false)
    {
        return get_input($name, $xssClean);
    }

    /**
     * Post Method Input
     *
     * @param $name
     * @param bool $xssClean
     * @return null|string
     */
    public static function post($name, $xssClean = false)
    {
        return post_input($name, $xssClean);
    }

    /**
     * Post & Get Method Input (Post First)
     *
     * @param $name
     * @param bool $xssClean
     * @return null
     */
    public static function postGet($name, $xssClean = false)
    {
        return post_get_input($name, $xssClean);
    }

}
