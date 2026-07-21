<?php
/*
|----------------------------------------------------------
| Load environment variables from .env
|----------------------------------------------------------
|
| The .env file lives outside the web root (project root) and is
| never committed. Real environment variables always take precedence
| over values in the file.
|
*/
$envFile = __DIR__ . '/../.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Strip a single pair of surrounding quotes, if present.
        if (strlen($value) >= 2 && ($value[0] === '"' || $value[0] === "'") && $value[strlen($value) - 1] === $value[0]) {
            $value = substr($value, 1, -1);
        }

        if ($key !== '' && getenv($key) === false) {
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

/*
|----------------------------------------------------------
| Set application environtment
|----------------------------------------------------------
|
|     development
|     testing
|     production
|
| Defaults to 'production' so a missing/misconfigured .env never
| leaks errors in a live environment.
|
*/
define('APP_ENV', getenv('APP_ENV') ?: 'production');

/**
 * Path to the project / current path
 */
define('MAIN_PATH', __DIR__ . '/../elips/');

/**
 * Path to the system folder
 */
define('FW_PATH', __DIR__ . '/../elips/framework/Elips/');

/**
 * Path to the application folder
 */
define('APP_PATH', __DIR__ . '/../elips/app/');

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
require __DIR__ . '/../elips/framework/Elips/autoload.php';

/**
 * Register composer autoloader
 */
require __DIR__ . '/../elips/vendor/autoload.php';

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
