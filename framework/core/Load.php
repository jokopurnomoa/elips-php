<?php
/**
 * Base Loader
 *
 * Functions for load library, model, view, etc...
 *
 */

class Load
{

    /**
     * Loading Library
     *
     * @param $library
     */
    public static function library($library)
    {
        $module_path = MODULE_PATH;
        $paths = explode('/', $library);

        if (strpos($library, '/') !== false) {
            $module_path = str_replace('//', '/', 'modules/' . $paths[0] . '/');
            $library = trim(str_replace($paths[0] . '/', '', $library), '/');
        }

        if (file_exists(APP_PATH . 'libraries/' . $library . '.php')) {
            require APP_PATH . 'libraries/' . $library . '.php';
            if(method_exists($library, 'init')) {
                $library::init();
            }
        }
        elseif (file_exists(FW_PATH . 'libraries/' . $library . '.php')) {
            require FW_PATH . 'libraries/' . $library . '.php';
            if(method_exists($library, 'init')) {
                $library::init();
            }
        }
        elseif (file_exists(APP_PATH . $module_path . 'libraries/' . $library . '.php')) {
            require APP_PATH . $module_path . 'libraries/' . $library . '.php';
            if (method_exists($library, 'init')) {
                $library::init();
            }
        }
        elseif (APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'libraries/' . $library . '.php\' not found!');
            die();
        }
    }

    /**
     * Loading Model
     *
     * @param $model
     */
    public static function model($model)
    {
        $module_path = MODULE_PATH;
        $paths = explode('/', $model);

        if (strpos($model, '/') !== false) {
            $module_path = str_replace('//', '/', 'modules/' . $paths[0] . '/');
            $model = trim(str_replace($paths[0] . '/', '', $model), '/');
        }

        if (file_exists(APP_PATH . 'models/' . $model . '.php')) {
            require APP_PATH . 'models/' . $model . '.php';
            if(method_exists($model, 'init')) {
                $model::init();
            }
        }
        elseif (file_exists(APP_PATH . $module_path . 'models/' . $model . '.php')) {
            require APP_PATH . $module_path . 'models/' . $model . '.php';
            if(method_exists($model, 'init')) {
                $model::init();
            }
        }
        elseif (APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'models/' . $model . '.php\' not found!');
            die();
        }
    }

    /**
     * Loading View
     *
     * @param $view
     * @param null $data
     * @param bool $buffered
     */
    public static function view($view, $data = null, $buffered = false)
    {
        $module_path = MODULE_PATH;
        $paths = explode('/', $view);

        if (strpos($view, '/') !== false) {
            $module_path = str_replace('//', '/', 'modules/' . $paths[0] . '/');
            $view = trim(str_replace($paths[0] . '/', '', $view), '/');
        }

        if (file_exists(APP_PATH . 'views/' . $view . '.php')) {
            ob_start();
            if ($data !== null) {
                foreach ($data as $key => $val) {
                    $$key = $val;
                }
            }
            require APP_PATH . 'views/' . $view . '.php';
            $buffer = ob_get_contents();
            @ob_end_clean();


            if ($buffered) {
                return $buffer;
            } else {
                echo $buffer;
            }
        }
        elseif (file_exists(APP_PATH . $module_path . 'views/' . $view . '.php')) {
            ob_start();
            if ($data !== null) {
                foreach ($data as $key => $val) {
                    $$key = $val;
                }
            }
            require APP_PATH . $module_path . 'views/' . $view . '.php';
            $buffer = ob_get_contents();
            @ob_end_clean();


            if ($buffered) {
                return $buffer;
            } else {
                echo $buffer;
            }
        }
        elseif (APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'views/' . $view . '.php\' not found!');
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
        $module_path = MODULE_PATH;
        $paths = explode('/', $helper);

        if (strpos($helper, '/') !== false) {
            $module_path = str_replace('//', '/', 'modules/' . $paths[0] . '/');
            $helper = trim(str_replace($paths[0] . '/', '', $helper), '/');
        }

        if (file_exists(APP_PATH . 'helpers/' . $helper . '.php')) {
            require APP_PATH . 'helpers/' . $helper . '.php';
        }
        elseif (file_exists(FW_PATH . 'helpers/' . $helper . '.php')) {
            require FW_PATH . 'helpers/' . $helper . '.php';
        }
        elseif (file_exists(FW_PATH . $module_path . 'helpers/' . $helper . '.php')) {
            require FW_PATH . $module_path . 'helpers/' . $helper . '.php';
        }
        elseif (APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'helpers/' . $helper . '.php\' not found!');
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
        $module_path = MODULE_PATH;
        $paths = explode('/', $language);

        if (strpos($language, '/') !== false) {
            $module_path = str_replace('//', '/', 'modules/' . $paths[0] . '/');
            $language = trim(str_replace($paths[0] . '/', '', $language), '/');
        }

        if ($name === null) {
            $name = $language;
        }

        if (file_exists(APP_PATH . 'lang/' . $language . '/' . $name . '.php')) {
            require APP_PATH . 'lang/' . $language . '/' . $name . '.php';
        }
        elseif (file_exists(FW_PATH . 'lang/' . $language . '/' . $name . '.php')) {
            require FW_PATH . 'lang/' . $language . '/' . $name . '.php';
        }
        elseif (file_exists(FW_PATH . $module_path . 'lang/' . $language . '/' . $name . '.php')) {
            require FW_PATH . $module_path . 'lang/' . $language . '/' . $name . '.php';
        }
        elseif (APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'helpers/' . $language . '.php\' not found!');
            die();
        }
    }

}
