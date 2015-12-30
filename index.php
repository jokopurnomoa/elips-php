<?php
/**
 * Application Environtment
 *
 *      development
 *      testing
 *      production
 *
 */

// Set application environtment
define('APP_ENV', 'development');

// Path to the project / current path
define('MAIN_PATH', __DIR__ . '/');

// Path to the system folder
define('FW_PATH', __DIR__ . '/framework/');

// Path to the application folder
define('APP_PATH', __DIR__ . '/app/');

/**
 * Instance Core Class
 */
$instance = null;

/**
 * Language Var
 */
$lang = null;

/**
 * Module Path
 */
$__module_path = '';

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

// Get error helper
require FW_PATH . 'helpers/error.php';

// Get app config
if(file_exists(APP_PATH . 'config/app.php')){
    require APP_PATH . 'config/app.php';
} elseif(APP_ENV === 'development') {
    error_dump('File \'' . APP_PATH . 'config/app.php\' not found!');die();
}

// Get core class
require FW_PATH . 'base/Core.php';

// Instantiate core class
$instance = new Core();
$instance->run();

// Get core class instance
function get_instance(){
    global $instance;
    return $instance;
}

// Get application base url
function base_url(){
    global $config;
    if(isset($config['base_url'])){
        if($config['base_url'] !== ''){
            return trim($config['base_url'], '/') . '/';
        }
    }

    $base_dir = explode('/', strrev(trim(__DIR__, '/')));
    return 'http' . (isset($_SERVER["HTTPS"]) == 'on' ? $_SERVER["HTTPS"] == 'on' ? 's' : '' : '') . '://' . $_SERVER['HTTP_HOST'] . '/' . trim(strrev($base_dir[0])) . '/';
}
