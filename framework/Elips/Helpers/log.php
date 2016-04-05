<?php
/**
 * Log Helper
 *
 */

define('ELIPS_LOG_ERROR', 1);
define('ELIPS_LOG_WARNING', 2);
define('ELIPS_LOG_NOTICE', 3);
define('ELIPS_LOG_INFO', 4);
define('ELIPS_LOG_DEBUG', 5);

/**
 * Base logging
 * 
 * @param int $level
 * @param string $message
 */
function elips_log_base($level, $message)
{
    $path = MAIN_PATH . 'storage/logs/' . date('Y-m-d');
    $logLevel = '';
    switch ($level) {
        case ELIPS_LOG_ERROR : $logLevel = 'ERROR  ';
            break;
        case ELIPS_LOG_WARNING : $logLevel = 'WARNING';
            break;
        case ELIPS_LOG_NOTICE : $logLevel = 'NOTICE ';
            break;
        case ELIPS_LOG_INFO : $logLevel = 'INFO   ';
            break;
        case ELIPS_LOG_DEBUG : $logLevel = 'DEBUG  ';
            break;
    }
    $time = '[' . date('H:i:s') . ' - ' . $logLevel . '] ';
    write_file($path, $time . $message . PHP_EOL, 'a');
}

/**
 * Error logging
 *
 * @param string $message
 */
function log_error($message)
{
    elips_log_base(ELIPS_LOG_ERROR, $message);
}

/**
 * Warning logging
 *
 * @param string $message
 */
function log_warning($message)
{
    elips_log_base(ELIPS_LOG_WARNING, $message);
}

/**
 * Notice logging
 *
 * @param string $message
 */
function log_notice($message)
{
    elips_log_base(ELIPS_LOG_NOTICE, $message);
}

/**
 * Info logging
 *
 * @param string $message
 */
function log_info($message)
{
    elips_log_base(ELIPS_LOG_INFO, $message);
}

/**
 * Debug logging
 *
 * @param string $message
 */
function log_debug($message)
{
    elips_log_base(ELIPS_LOG_DEBUG, $message);
}
