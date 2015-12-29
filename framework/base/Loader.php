<?php
/**
 * Base Loader
 *
 * Functions for load library, model, view, etc...
 *
 */

class Loader {

    /**
     * Loading Library
     *
     * @param $library
     */
    public static function loadLibrary($library){
        global $__module_path;

        if(file_exists(APP_PATH . 'libraries/' . $library . '.php')) {
            require APP_PATH . 'libraries/' . $library . '.php';
            if(method_exists($library, 'init')) {
                $library::init();
            }
        }
        elseif(file_exists(FW_PATH . 'libraries/' . $library . '.php')){
            require FW_PATH . 'libraries/' . $library . '.php';
            if(method_exists($library, 'init')) {
                $library::init();
            }
        }
        elseif(file_exists(APP_PATH . $__module_path . 'libraries/' . $library . '.php')){
            require APP_PATH . $__module_path . 'libraries/' . $library . '.php';
            if(method_exists($library, 'init')) {
                $library::init();
            }
        }
        elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'libraries/' . $library . '.php\' not found!');
            die();
        }
    }

    /**
     * Loading Model
     *
     * @param $model
     */
    public static function loadModel($model){
        global $__module_path;

        if(file_exists(APP_PATH . 'models/' . $model . '.php')) {
            require APP_PATH . 'models/' . $model . '.php';
            if(method_exists($model, 'init')) {
                $model::init();
            }
        }
        elseif(file_exists(APP_PATH . $__module_path . 'models/' . $model . '.php')) {
            require APP_PATH . $__module_path . 'models/' . $model . '.php';
            if(method_exists($model, 'init')) {
                $model::init();
            }
        }
        elseif(APP_ENV === 'development') {
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
    public static function loadView($view, $data = null, $buffered = false){
        global $__module_path;

        if(file_exists(APP_PATH . 'views/' . $view . '.php')){
            ob_start();
            if($data != null){
                foreach($data as $key => $val){
                    $$key = $val;
                }
            }
            require APP_PATH . 'views/' . $view . '.php';
            $buffer = ob_get_contents();
            @ob_end_clean();


            if($buffered){
                return $buffer;
            } else {
                echo $buffer;
            }
        }
        elseif(file_exists(APP_PATH . $__module_path . 'views/' . $view . '.php')){
            ob_start();
            if($data != null){
                foreach($data as $key => $val){
                    $$key = $val;
                }
            }
            require APP_PATH . $__module_path . 'views/' . $view . '.php';
            $buffer = ob_get_contents();
            @ob_end_clean();


            if($buffered){
                return $buffer;
            } else {
                echo $buffer;
            }
        }
        elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'views/' . $view . '.php\' not found!');
            die();
        }
    }

    /**
     * Loading Helper
     *
     * @param $helper
     */
    public static function loadHelper($helper){
        global $__module_path;

        if(file_exists(APP_PATH . 'helpers/' . $helper . '.php')){
            require APP_PATH . 'helpers/' . $helper . '.php';
        }
        elseif(file_exists(FW_PATH . 'helpers/' . $helper . '.php')){
            require FW_PATH . 'helpers/' . $helper . '.php';
        }
        elseif(file_exists(FW_PATH . $__module_path . 'helpers/' . $helper . '.php')){
            require FW_PATH . $__module_path . 'helpers/' . $helper . '.php';
        }
        elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'helpers/' . $helper . '.php\' not found!');
            die();
        }
    }

    /**
     * Loading Language
     *
     * @param $language
     */
    public static function loadLanguage($language, $name = null){
        global $__module_path;

        if($name === null){
            $name = $language;
        }

        if(file_exists(APP_PATH . 'lang/' . $language . '/' . $name . '.php')){
            require APP_PATH . 'lang/' . $language . '/' . $name . '.php';
        }
        elseif(file_exists(FW_PATH . 'lang/' . $language . '/' . $name . '.php')){
            require FW_PATH . 'lang/' . $language . '/' . $name . '.php';
        }
        elseif(file_exists(FW_PATH . $__module_path . 'lang/' . $language . '/' . $name . '.php')){
            require FW_PATH . $__module_path . 'lang/' . $language . '/' . $name . '.php';
        }
        elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'helpers/' . $language . '.php\' not found!');
            die();
        }
    }

}
