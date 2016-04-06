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
        self::$cacheActive = app_config('cache', 'active');
        self::$cacheEncrypt = app_config('cache', 'encrypt');
        if (app_config('cache', 'driver') === 'file') {
            if(self::$cacheDriver === null){
                self::$cacheDriver = new CacheFile();
                self::$cacheDriver->cacheActive = self::$cacheActive;
                self::$cacheDriver->cacheEncrypt = self::$cacheEncrypt;
            }
        } elseif(app_config('cache', 'driver') === 'apc') {
            if(self::$cacheDriver === null) {
                self::$cacheDriver = new CacheAPC();
                self::$cacheDriver->cacheActive = self::$cacheActive;
                self::$cacheDriver->cacheEncrypt = self::$cacheEncrypt;
            }
        }
    }

    /**
     * Store Cache
     *
     * @param string $flag
     * @param mixed  $data
     * @param int    $maxAge
     * @return bool
     */
    public static function store($flag, $data, $maxAge = 60)
    {
        self::init();
        return self::$cacheDriver->store($flag, $data, $maxAge);
    }

    /**
     * Save Cache (alias)
     *
     * @param string $flag
     * @param mixed  $data
     * @param int    $maxAge
     * @return bool
     */
    public static function save($flag, $data, $maxAge = 60)
    {
        self::init();
        return self::$cacheDriver->store($flag, $data, $maxAge);
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
