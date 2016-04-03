<?php
/**
 * Log Library
 *
 *
 */

namespace Elips\Libraries;

class Log
{

    /**
     * Error logging
     * 
     * @param string $message
     */
    public static function error($message)
    {
        log_error($message);
    }

    /**
     * Warning logging
     * 
     * @param string $message
     */
    public static function warning($message)
    {
        log_warning($message);
    }

    /**
     * Notice logging
     * 
     * @param string $message
     */
    public static function notice($message)
    {
        log_notice($message);
    }

    /**
     * Info logging
     * 
     * @param string $message
     */
    public static function info($message)
    {
        log_info($message);
    }

    /**
     * Debug logging
     * 
     * @param string $message
     */
    public static function debug($message)
    {
        log_debug($message);
    }

}
