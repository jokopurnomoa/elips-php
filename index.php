<?php
/**
 * APPLICATION ENVIRONTMENT
 *
 *      development
 *      testing
 *      production
 *
 */

// Set application environtment
define('APP_ENV', 'development');

// Path to the project / current path
define('MAIN_PATH', './');

// Path to the system folder
define('FW_PATH', 'framework/');

// Path to the application folder
define('APP_PATH', 'app/');

$instance = null;
$lang = null;

if(APP_ENV === 'development'){
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
} elseif(APP_ENV === 'testing' || APP_ENV === 'production'){
    error_reporting(0);
    ini_set('display_errors', 'off');
} else {
    echo 'Application Environtment not set correctly...';die();
}

// get error helper
require FW_PATH . 'helpers/error.php';

// get app config
if(file_exists(APP_PATH . 'config/app.php')){
    require APP_PATH . 'config/app.php';
} elseif(APP_ENV === 'development') {
    error_dump('File \'' . APP_PATH . 'config/app.php\' not found!');die();
}

// get core class
require FW_PATH . 'base/Core.php';

// instantiate core class
$instance = new Core();
$instance->run();

// get core class instance
function get_instance(){
    global $instance;
    return $instance;
}

// get application base url
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