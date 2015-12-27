<?php
/**
 * Cache library
 *
 *
 */

class Cache {

    private static $cache_active = false;
    private static $max_cache_size = 1024000;
    private static $cache_encrypt = false;
    private static $cache_driver;

    public static function init(){
        $config = null;
        if(file_exists(APP_PATH . 'config/app.php')){
            require APP_PATH . 'config/app.php';
        } elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'config/app.php\' not found!');die();
        }

        self::$cache_active = $config['cache']['active'];
        self::$max_cache_size = $config['cache']['max_size'];
        self::$cache_encrypt = $config['cache']['encrypt'];
        if($config['cache']['driver'] === 'file'){
            require 'CacheDriver/CacheFile.php';
            self::$cache_driver = new CacheFile();
            self::$cache_driver->cache_active = self::$cache_active;
            self::$cache_driver->cache_encrypt = self::$cache_encrypt;
            self::$cache_driver->max_cache_size = self::$max_cache_size;
        }
    }

    public static function setCache($flag, $data, $max_age = 60){
        return self::$cache_driver->setCache($flag, $data, $max_age);
    }

    public static function getCache($flag){
        return self::$cache_driver->getCache($flag);
    }

}
