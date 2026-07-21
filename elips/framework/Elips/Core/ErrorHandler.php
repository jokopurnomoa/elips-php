<?php
/**
 * Global Error Handler
 *
 * Converts PHP errors, uncaught exceptions and fatal/shutdown errors into
 * entries under storage/logs/. Respects APP_ENV: details are displayed in
 * development and hidden (only logged) in testing/production.
 *
 */

namespace Elips\Core;

class ErrorHandler
{

    /**
     * Guard against recursion if logging itself triggers an error.
     *
     * @var bool
     */
    private static $logging = false;

    /**
     * Register all global handlers.
     */
    public static function register()
    {
        set_error_handler(array(__CLASS__, 'handleError'));
        set_exception_handler(array(__CLASS__, 'handleException'));
        register_shutdown_function(array(__CLASS__, 'handleShutdown'));
    }

    /**
     * Handle PHP errors (warnings, notices, deprecations, ...).
     *
     * Returns false so PHP's default handling still runs afterwards, which
     * keeps errors visible on screen when display_errors is on (development).
     *
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param int    $errline
     * @return bool
     */
    public static function handleError($errno, $errstr, $errfile = '', $errline = 0)
    {
        // Respect the "@" operator and the current error_reporting level.
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $message = 'PHP ' . self::levelLabel($errno) . ': ' . $errstr
            . ' in ' . $errfile . ' on line ' . $errline;

        self::writeLog(self::levelName($errno), $message);

        return false;
    }

    /**
     * Handle uncaught exceptions.
     *
     * @param \Throwable $e
     */
    public static function handleException($e)
    {
        $message = 'Uncaught ' . get_class($e) . ': ' . $e->getMessage()
            . ' in ' . $e->getFile() . ' on line ' . $e->getLine()
            . PHP_EOL . $e->getTraceAsString();

        self::writeLog('ERROR', $message);

        if (APP_ENV === 'development') {
            if (!headers_sent()) {
                http_response_code(500);
            }
            echo '<pre>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</pre>';
        } else {
            self::renderServerError();
        }
    }

    /**
     * Handle fatal errors on shutdown (E_ERROR, E_PARSE, ...).
     */
    public static function handleShutdown()
    {
        $error = error_get_last();

        if ($error === null) {
            return;
        }

        $fatal = E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING
            | E_COMPILE_ERROR | E_COMPILE_WARNING;

        if (!($error['type'] & $fatal)) {
            return;
        }

        $message = 'Fatal ' . self::levelLabel($error['type']) . ': ' . $error['message']
            . ' in ' . $error['file'] . ' on line ' . $error['line'];

        self::writeLog('ERROR', $message);

        if (APP_ENV !== 'development') {
            self::renderServerError();
        }
    }

    /**
     * Append a message to today's log file, mirroring the log helper format.
     *
     * @param string $level
     * @param string $message
     */
    private static function writeLog($level, $message)
    {
        if (self::$logging) {
            return;
        }
        self::$logging = true;

        if (defined('MAIN_PATH')) {
            $dir = MAIN_PATH . 'storage/logs/';
            if (is_dir($dir) && is_writable($dir)) {
                $path = $dir . date('Y-m-d');
                $line = '[' . date('H:i:s') . ' - ' . str_pad($level, 7) . '] ' . $message . PHP_EOL;
                @file_put_contents($path, $line, FILE_APPEND | LOCK_EX);
            }
        }

        self::$logging = false;
    }

    /**
     * Output a minimal 500 response for non-development environments.
     */
    private static function renderServerError()
    {
        if (!headers_sent()) {
            http_response_code(500);
        }
        echo 'Internal Server Error';
    }

    /**
     * Map a PHP error constant to a human-readable label.
     *
     * @param int $errno
     * @return string
     */
    private static function levelLabel($errno)
    {
        switch ($errno) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                return 'Error';
            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                return 'Warning';
            case E_NOTICE:
            case E_USER_NOTICE:
                return 'Notice';
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                return 'Deprecated';
            case E_PARSE:
                return 'Parse Error';
            default:
                return 'Error';
        }
    }

    /**
     * Map a PHP error constant to the log level column used in the log file.
     *
     * @param int $errno
     * @return string
     */
    private static function levelName($errno)
    {
        switch ($errno) {
            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:
                return 'WARNING';
            case E_NOTICE:
            case E_USER_NOTICE:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                return 'NOTICE';
            default:
                return 'ERROR';
        }
    }

}
