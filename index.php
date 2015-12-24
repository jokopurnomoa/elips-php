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
define('ENVIRONTMENT', 'development');

// Path to the system folder
define('SYSTEM_PATH', 'system/');

// Path to the application folder
define('APP_PATH', 'app/');

$instance = null;
$lang = null;

if(ENVIRONTMENT === 'development'){
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
} elseif(ENVIRONTMENT === 'testing' || ENVIRONTMENT === 'production'){
    error_reporting(0);
    ini_set('display_errors', 'off');
} else {
    echo 'Application Environtment not set correctly...';die();
}

// get core class
require SYSTEM_PATH . 'base/Core.php';

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
    $__base_dir = explode('/', strrev(trim(__DIR__, '/')));
    return 'http' . (isset($_SERVER["HTTPS"]) == 'on' ? $_SERVER["HTTPS"] == 'on' ? 's' : '' : '') . '://' . $_SERVER['HTTP_HOST'] . '/' . trim(strrev($__base_dir[0]), '/') . '/';
}