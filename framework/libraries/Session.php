<?php
/**
 * Session
 *
 * Controll session driver
 *
 */

class Session {

    private static $session_driver;

    public static function init(){
        global $config;
        if($config['session']['driver'] == 'file'){
            require_once('SessionDriver/SessionFile.php');
            self::$session_driver = new SessionFile();
            self::$session_driver->init($config);
        } elseif(APP_ENV === 'development') {
            error_dump('Session Driver \'' . $config['session']['driver'] . '\' not avaiable.');die();
        }
    }

    public static function get($key){
        return self::$session_driver->get($key);
    }

    public static function set($key, $value){
        self::$session_driver->set($key, $value);
    }

    public static function remove($key){
        self::$session_driver->remove($key);
    }

    public static function destroy(){
        self::$session_driver->destroy();
    }

}
