<?php
/**
 * Session
 *
 * Controll session driver
 *
 */

class Session {

    private static $session_driver;
    private static $config = null;

    public static function init(){
        $config = null;
        if(file_exists(APP_PATH . 'config/app.php')){
            require(APP_PATH . 'config/app.php');
            self::$config = $config;
        } else {
            errorDump('File \'' . APP_PATH . 'config/app.php\' not found!');die();
        }

        if(self::$config['session']['driver'] == 'file'){
            require_once('SessionDriver/SessionFile.php');
            self::$session_driver = new SessionFile();
            self::$session_driver->init(self::$config);
        } else {
            errorDump('Session Driver \'' . self::$config['session']['driver'] . '\' not avaiable.');die();
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
