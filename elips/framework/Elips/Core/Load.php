<?php
/**
 * Base Loader
 *
 * Functions for load library, model, view, etc...
 *
 */

namespace Elips\Core;

class Load
{

    /**
     * Loading View
     *
     * @param $view
     * @param null $data
     * @param bool $buffered
     */
    public static function view($view, $data = null, $buffered = false)
    {
        global $modulePath;
        $paths = explode('/', $view);

        if (strpos($view, '/') !== false) {
            $modulePath = str_replace('//', '/', 'Modules/' . $paths[0] . '/');
            $view = trim(str_replace($paths[0] . '/', '', $view), '/');
        }

        if (file_exists(APP_PATH . 'Views/' . $view . '.php')) {
            ob_start();
            if ($data !== null) {
                foreach ($data as $key => $val) {
                    $$key = $val;
                }
            }
            require APP_PATH . 'Views/' . $view . '.php';
            $buffer = ob_get_contents();
            @ob_end_clean();


            if ($buffered) {
                return $buffer;
            } else {
                echo $buffer;
            }
        }
        elseif (file_exists(APP_PATH . $modulePath . 'Views/' . $view . '.php')) {
            ob_start();
            if ($data !== null) {
                foreach ($data as $key => $val) {
                    $$key = $val;
                }
            }
            require APP_PATH . $modulePath . 'Views/' . $view . '.php';
            $buffer = ob_get_contents();
            @ob_end_clean();


            if ($buffered) {
                return $buffer;
            } else {
                echo $buffer;
            }
        }
        elseif (APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'Views/' . $view . '.php\' not found!');
            die();
        }
    }

    /**
     * Loading Helper
     *
     * @param $helper
     */
    public static function helper($helper)
    {
        global $modulePath;
        $paths = explode('/', $helper);

        if (strpos($helper, '/') !== false) {
            $modulePath = str_replace('//', '/', 'Modules/' . $paths[0] . '/');
            $helper = trim(str_replace($paths[0] . '/', '', $helper), '/');
        }

        if (file_exists(APP_PATH . 'Helpers/' . $helper . '.php')) {
            require_once APP_PATH . 'Helpers/' . $helper . '.php';
        }
        elseif (file_exists(FW_PATH . 'Helpers/' . $helper . '.php')) {
            require_once FW_PATH . 'Helpers/' . $helper . '.php';
        }
        elseif (file_exists(FW_PATH . $modulePath . 'Helpers/' . $helper . '.php')) {
            require_once FW_PATH . $modulePath . 'Helpers/' . $helper . '.php';
        }
        elseif (APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'Helpers/' . $helper . '.php\' not found!');
            die();
        }
    }

    /**
     * Loading Language
     *
     * @param $language
     */
    public static function language($language, $name = null)
    {
        global $modulePath;
        $paths = explode('/', $language);

        if (strpos($language, '/') !== false) {
            $modulePath = str_replace('//', '/', 'Modules/' . $paths[0] . '/');
            $language = trim(str_replace($paths[0] . '/', '', $language), '/');
        }

        if ($name === null) {
            $name = $language;
        }

        if (file_exists(APP_PATH . 'Lang/' . $language . '/' . $name . '.php')) {
            require_once APP_PATH . 'Lang/' . $language . '/' . $name . '.php';
        }
        elseif (file_exists(FW_PATH . 'Lang/' . $language . '/' . $name . '.php')) {
            require_once FW_PATH . 'Lang/' . $language . '/' . $name . '.php';
        }
        elseif (file_exists(FW_PATH . $modulePath . 'Lang/' . $language . '/' . $name . '.php')) {
            require_once FW_PATH . $modulePath . 'Lang/' . $language . '/' . $name . '.php';
        }
        elseif (APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'Helpers/' . $language . '.php\' not found!');
            die();
        }
    }

}
