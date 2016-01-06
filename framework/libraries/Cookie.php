<?php
/**
 * Cookie
 *
 * Cookie library
 *
 */

class Cookie {

    /**
     * Set Cookie
     *
     * @param $cookie_name
     * @param $cookie_value
     * @param null $expire
     * @return bool
     */
    public static function set($cookie_name, $cookie_value, $expire = null){
        if($expire != null){
            return setcookie($cookie_name, $cookie_value, time() + $expire, '/');
        } else {
            return setcookie($cookie_name, $cookie_value, time() + 7200, '/');
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

    /**
     * Delete Cookie
     *
     * @param $cookie_name
     * @return bool
     */
    public static function delete($cookie_name){
        return setcookie($cookie_name, null, -1, '/');
    }

}
