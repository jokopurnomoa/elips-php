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

    private static $session_driver;

    /**
     * Initialize Session Library
     */
    public static function init()
    {
        if (get_app_config('session', 'driver') === 'file') {
            if(self::$session_driver === null){
                self::$session_driver = new SessionFile();
                self::$session_driver->init(get_app_config('session'));
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
        return self::$session_driver->has($key);
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
        return self::$session_driver->get($key, $default);
    }

    /**
     * Get All Session Data
     *
     * @return mixed
     */
    public static function all()
    {
        self::init();
        return self::$session_driver->all();
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
        self::$session_driver->set($key, $value);
    }

    /**
     * Remove Session Data
     *
     * @param string $key
     */
    public static function remove($key)
    {
        self::init();
        self::$session_driver->remove($key);
    }

    /**
     * Destroy Session Data
     */
    public static function destroy()
    {
        self::init();
        self::$session_driver->destroy();
    }

    /**
     * Regenerate Session ID
     */
    public static function regenerate()
    {
        self::init();
        self::$session_driver->regenerate();
    }

}
