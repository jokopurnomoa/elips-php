<?php
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
 * Path to the module folder
 */
define('MODULE_PATH', __DIR__ . '');

/**
 * Instance Core Class
 */
$instance = null;

/**
 * Language Var
 */
$lang = null;

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
 * Require Benchmark library
 */
require FW_PATH . 'libraries/Benchmark.php';

/**
 * Starting benchmark
 */
Benchmark::startTime('execution_time');

/**
 * Require error helper
 */
require FW_PATH . 'helpers/error.php';

/**
 * Require config helper
 */
require FW_PATH . 'helpers/config.php';

/**
 * Require app config
 */
if(file_exists(APP_PATH . 'config/app.php')){
    require APP_PATH . 'config/app.php';
} elseif(APP_ENV === 'development') {
    error_dump('File \'' . APP_PATH . 'config/app.php\' not found!');die();
}

/**
 * Require database config
 */
if(file_exists(APP_PATH . 'config/database.php')){
    require APP_PATH . 'config/database.php';
} elseif(APP_ENV === 'development') {
    error_dump('File \'' . APP_PATH . 'config/database.php\' not found!');die();
}

/**
 * Require mimes config
 */
if(file_exists(APP_PATH . 'config/mimes.php')){
    require APP_PATH . 'config/mimes.php';
} elseif(APP_ENV === 'development') {
    error_dump('File \'' . APP_PATH . 'config/mimes.php\' not found!');die();
}

/**
 * Require core class
 */
require FW_PATH . 'base/Core.php';

/**
 * Instantiate core class
 */
$instance = new Core();
$instance->run();

/**
 * Get core class instance
 *
 * @return Core|null
 */
function get_instance(){
    global $instance;
    return $instance;
}

/**
 * Get application base url
 *
 * @return string
 */
function base_url(){
    $base_url = get_app_config('base_url');
    if($base_url !== ''){
        return trim($base_url, '/') . '/';
    }

    $base_dir = explode('/', strrev(trim(__DIR__, '/')));
    return 'http' . (isset($_SERVER["HTTPS"]) == 'on' ? $_SERVER["HTTPS"] == 'on' ? 's' : '' : '') . '://' . $_SERVER['HTTP_HOST'] . '/' . trim(strrev($base_dir[0])) . '/';
}
