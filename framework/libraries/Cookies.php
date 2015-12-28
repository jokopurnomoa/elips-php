<?php
/**
 * Cookies
 *
 * Cookies library
 *
 */

class Cookies {

    /**
     * Set Cookie
     *
     * @param $cookie_name
     * @param $cookie_value
     * @param null $expire
     * @return bool
     */
    public static function set($cookie_name, $cookie_value, $expire = null){
        if($expire !== null){
            return setcookie($cookie_name, $cookie_value, $expire);
        } else {
            return setcookie($cookie_name, $cookie_value, time() + 7200);
        }
    }

    /**
     * Get Cookie
     *
     * @param $cookie_name
     * @return null
     */
    public static function get($cookie_name){
        if(isset($_COOKIE[$cookie_name])){
            return $_COOKIE[$cookie_name];
        }
        return null;
    }

}
