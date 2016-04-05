<?php
/*
|----------------------------------------------------------
| Set application environtment
|----------------------------------------------------------
|
|     development
|     testing
|     production
|
*/
define('APP_ENV', 'development');

/**
 * Path to the project / current path
 */
define('MAIN_PATH', __DIR__ . '/');

/**
 * Path to the system folder
 */
define('FW_PATH', __DIR__ . '/framework/Elips/');

/**
 * Path to the application folder
 */
define('APP_PATH', __DIR__ . '/app/');

/**
 * Checking Application Environtment
 */
if(APP_ENV === 'development'){
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
} elseif(APP_ENV === 'testing' || APP_ENV === 'production'){
    error_reporting(0);
    ini_set('display_errors', 'off');
} else {
    echo 'Application Environtment not set correctly...';die();
}

/**
 * Register elips autoloader
 */
require __DIR__ . '/framework/Elips/autoload.php';

/**
 * Register composer autoloader
 */
require __DIR__ . '/vendor/autoload.php';

/**
 * Starting benchmark
 */
use Elips\Libraries\Benchmark;
Benchmark::startTime('execution_time');

/**
 * Instantiate core class
 */
$instance = new \Elips\Core\Core();
$instance->run();

/**
 * Get core class instance
 *
 * @return Core|null
 */
function get_instance(){
    return $GLOBALS['instance'];
}
