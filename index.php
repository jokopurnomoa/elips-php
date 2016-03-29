<?php

/**
 * Register elips autoloader
 */
require __DIR__ . '/framework/autoload.php';

/**
 * Register composer autoloader
 */
require __DIR__ . '/vendor/autoload.php';

use Elips\Libraries\Benchmark;

/**
 * Set application environtment
 *
 *      development
 *      testing
 *      production
 */
define('APP_ENV', 'development');

/**
 * Path to the project / current path
 */
define('MAIN_PATH', __DIR__ . '/');

/**
 * Path to the system folder
 */
define('FW_PATH', __DIR__ . '/framework/');

/**
 * Path to the application folder
 */
define('APP_PATH', __DIR__ . '/app/');

/**
 * App config
 */
$config = null;

/**
 * Instance Core Class
 */
$instance = null;

/**
 * Language Var
 */
$lang = null;

/**
 * Module path
 */
$modulePath = '';

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
 * Starting benchmark
 */
Benchmark::startTime('execution_time');

/**
 * Require error helper
 */
require FW_PATH . 'Helpers/error.php';

/**
 * Require url helper
 */
require FW_PATH . 'Helpers/url.php';

/**
 * Require config helper
 */
require FW_PATH . 'Helpers/config.php';


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
