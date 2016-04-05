<?php
/**
 * Elips PHP autoload
 *
 */

spl_autoload_register(function($class) {

    if (strpos($class, 'Elips\\') !== false) {
        $path = __DIR__ . '/' . str_replace('\\', '/', str_replace('Elips\\', '', $class)) . '.php';
        if(file_exists($path)){
            require $path;
        }
    } elseif(strpos($class, 'App\\Controllers\\') !== false) {
        $path = APP_PATH . '/' . str_replace('\\', '/', str_replace('App\\', '', $class)) . '.php';
        if(file_exists($path)){
            require $path;
        }
    } elseif(strpos($class, 'App\\Models\\') !== false) {
        $path = APP_PATH . '/' . str_replace('\\', '/', str_replace('App\\', '', $class)) . '.php';
        if(file_exists($path)){
            require $path;
        }
    } elseif(strpos($class, 'App\\Modules\\') !== false) {
        $path = APP_PATH . str_replace('\\', '/', str_replace('App\\', '', $class)) . '.php';
        if(file_exists($path)){
            require $path;
        }
    }

});
