<?php
/**
 * Session
 *
 * Controll session driver
 *
 */

class Session {

    private static $session_driver;

    /**
     * Initialize Session Library
     */
    public static function init(){
        if(get_app_config('session', 'driver') === 'file'){
            require 'SessionDriver/SessionFile.php';
            self::$session_driver = new SessionFile();
            self::$session_driver->init(get_app_config('session'));
        } elseif(APP_ENV === 'development') {
            error_dump('Session Driver \'' . get_app_config('session', 'driver') . '\' not avaiable.');die();
        }
    }

    /**
     * Get Session Data
     *
     * @param $key
     * @return mixed
     */
    public static function get($key){
        return self::$session_driver->get($key);
    }

    /**
     * Set Session Data
     *
     * @param $key
     * @param $value
     */
    public static function set($key, $value){
        self::$session_driver->set($key, $value);
    }

    /**
     * Remove Session Data
     *
     * @param $key
     */
    public static function remove($key){
        self::$session_driver->remove($key);
    }

    /**
     * Destroy Session Data
     */
    public static function destroy(){
        self::$session_driver->destroy();
    }

}
