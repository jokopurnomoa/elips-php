<?php
/**
 * Cache Library
 *
 *
 */

class Cache
{

    private static $cache_active = false;
    private static $cache_encrypt = false;
    private static $cache_driver;

    /**
     * Init Cache
     */
    public static function init()
    {
        self::$cache_active = get_app_config('cache', 'active');
        self::$cache_encrypt = get_app_config('cache', 'encrypt');
        if (get_app_config('cache', 'driver') === 'file') {
            require_once 'CacheDriver/CacheFile.php';
            self::$cache_driver = new CacheFile();
            self::$cache_driver->cache_active = self::$cache_active;
            self::$cache_driver->cache_encrypt = self::$cache_encrypt;
        } elseif(get_app_config('cache', 'driver') === 'apc') {
            require_once 'CacheDriver/CacheAPC.php';
            self::$cache_driver = new CacheAPC();
            self::$cache_driver->cache_active = self::$cache_active;
            self::$cache_driver->cache_encrypt = self::$cache_encrypt;
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
        return self::$cache_driver->store($flag, $data, $max_age);
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
        return self::$cache_driver->store($flag, $data, $max_age);
    }

    /**
     * Get Cache
     *
     * @param string $flag
     * @return mixed
     */
    public static function get($flag)
    {
        return self::$cache_driver->get($flag);
    }

    /**
     * Delete Cache
     *
     * @param string $flag
     * @return bool
     */
    public static function delete($flag)
    {
        return self::$cache_driver->delete($flag);
    }

}
