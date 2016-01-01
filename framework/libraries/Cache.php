<?php
/**
 * Cache Library
 *
 *
 */

class Cache {

    private static $cache_active = false;
    private static $cache_encrypt = false;
    private static $cache_driver;

    /**
     * Init Cache
     */
    public static function init(){
        global $config;
        self::$cache_active = $config['cache']['active'];
        self::$cache_encrypt = $config['cache']['encrypt'];
        if($config['cache']['driver'] === 'file'){
            require 'CacheDriver/CacheFile.php';
            self::$cache_driver = new CacheFile();
            self::$cache_driver->cache_active = self::$cache_active;
            self::$cache_driver->cache_encrypt = self::$cache_encrypt;
        } else if($config['cache']['driver'] === 'apc'){
            require 'CacheDriver/CacheAPC.php';
            self::$cache_driver = new CacheAPC();
            self::$cache_driver->cache_active = self::$cache_active;
            self::$cache_driver->cache_encrypt = self::$cache_encrypt;
        }
    }

    /**
     * Store Cache
     *
     * @param $flag
     * @param $data
     * @param int $max_age
     * @return mixed
     */
    public static function store($flag, $data, $max_age = 60){
        return self::$cache_driver->store($flag, $data, $max_age);
    }

    /**
     * Save Cache (alias)
     *
     * @param $flag
     * @param $data
     * @param int $max_age
     * @return mixed
     */
    public static function save($flag, $data, $max_age = 60){
        return self::$cache_driver->store($flag, $data, $max_age);
    }

    /**
     * Get Cache
     *
     * @param $flag
     * @return mixed
     */
    public static function get($flag){
        return self::$cache_driver->get($flag);
    }

}
