<?php
/**
 * Session
 *
 * Controll session driver
 *
 */

namespace Elips\Libraries;

use Elips\Libraries\SessionDriver\SessionFile;

class Session
{

    private static $sessionDriver;

    /**
     * Initialize Session Library
     */
    public static function init()
    {
        if (get_app_config('session', 'driver') === 'file') {
            if(self::$sessionDriver === null){
                self::$sessionDriver = new SessionFile();
                self::$sessionDriver->init(get_app_config('session'));
            }
        } elseif(APP_ENV === 'development') {
            error_dump('Session Driver \'' . get_app_config('session', 'driver') . '\' not avaiable.');die();
        }
    }

    /**
     * Check Session Data Exists
     *
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        self::init();
        return self::$sessionDriver->has($key);
    }

    /**
     * Get Session Data
     *
     * @param string $key
     * @param string $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        self::init();
        return self::$sessionDriver->get($key, $default);
    }

    /**
     * Get All Session Data
     *
     * @return mixed
     */
    public static function all()
    {
        self::init();
        return self::$sessionDriver->all();
    }

    /**
     * Set Session Data
     *
     * @param string $key
     * @param string $value
     */
    public static function set($key, $value)
    {
        self::init();
        self::$sessionDriver->set($key, $value);
    }

    /**
     * Remove Session Data
     *
     * @param string $key
     */
    public static function remove($key)
    {
        self::init();
        self::$sessionDriver->remove($key);
    }

    /**
     * Destroy Session Data
     */
    public static function destroy()
    {
        self::init();
        self::$sessionDriver->destroy();
    }

    /**
     * Regenerate Session ID
     */
    public static function regenerate()
    {
        self::init();
        self::$sessionDriver->regenerate();
    }

}
