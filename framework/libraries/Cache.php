<?php
/**
 * Cache Library
 *
 *
 */

namespace Elips\Libraries;

use Elips\Libraries\CacheDriver\CacheFile;
use Elips\Libraries\CacheDriver\CacheAPC;

class Cache
{

    private static $cacheActive = false;
    private static $cacheEncrypt = false;
    private static $cacheDriver;

    /**
     * Init Cache
     */
    public static function init()
    {
        self::$cacheActive = get_app_config('cache', 'active');
        self::$cacheEncrypt = get_app_config('cache', 'encrypt');
        if (get_app_config('cache', 'driver') === 'file') {
            if(self::$cacheDriver === null){
                self::$cacheDriver = new CacheFile();
                self::$cacheDriver->cache_active = self::$cacheActive;
                self::$cacheDriver->cache_encrypt = self::$cacheEncrypt;
            }
        } elseif(get_app_config('cache', 'driver') === 'apc') {
            if(self::$cacheDriver === null) {
                self::$cacheDriver = new CacheAPC();
                self::$cacheDriver->cache_active = self::$cacheActive;
                self::$cacheDriver->cache_encrypt = self::$cacheEncrypt;
            }
        }
    }

    /**
     * Store Cache
     *
     * @param string $flag
     * @param mixed  $data
     * @param int    $max_age
     * @return bool
     */
    public static function store($flag, $data, $max_age = 60)
    {
        self::init();
        return self::$cacheDriver->store($flag, $data, $max_age);
    }

    /**
     * Save Cache (alias)
     *
     * @param string $flag
     * @param mixed  $data
     * @param int    $max_age
     * @return bool
     */
    public static function save($flag, $data, $max_age = 60)
    {
        self::init();
        return self::$cacheDriver->store($flag, $data, $max_age);
    }

    /**
     * Get Cache
     *
     * @param string $flag
     * @return mixed
     */
    public static function get($flag)
    {
        self::init();
        return self::$cacheDriver->get($flag);
    }

    /**
     * Delete Cache
     *
     * @param string $flag
     * @return bool
     */
    public static function delete($flag)
    {
        self::init();
        return self::$cacheDriver->delete($flag);
    }

}
