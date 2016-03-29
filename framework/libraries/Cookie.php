<?php
/**
 * Cookie Library
 *
 */

namespace Elips\Libraries;

class Cookie
{

    /**
     * Set Cookie
     *
     * @param string    $name
     * @param string    $value
     * @param int       $expire
     * @param string    $path
     * @param string    $domain
     * @param bool      $secure
     * @param bool      $httpOnly
     * @return bool
     */
    public static function set($name, $value, $expire = 0, $path = '/', $domain = '', $secure = false, $httpOnly = false)
    {
        if ($expire > 0) {
            $expire = time() + (int)$expire;
        }
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    /**
     * Get Cookie
     *
     * @param string        $name
     * @return null|string
     */
    public static function get($name)
    {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
        return null;
    }

    /**
     * Delete Cookie
     *
     * @param string $name
     * @return bool
     */
    public static function delete($name)
    {
        return setcookie($name, null, -1, '/');
    }

}
