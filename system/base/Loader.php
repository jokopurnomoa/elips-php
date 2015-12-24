<?php
/**
 * LOADER
 *
 * Functions for load library, model, view, etc...
 *
 */

class Loader {

    public static function loadLibrary($library){
        if(file_exists(APP_PATH . 'libraries/' . $library . '.php')) {
            require APP_PATH . 'libraries/' . $library . '.php';
            if(method_exists($library, 'init')) {
                $library::init();
            }
        } elseif(file_exists(SYSTEM_PATH . 'libraries/' . $library . '.php')){
            require SYSTEM_PATH . 'libraries/' . $library . '.php';
            if(method_exists($library, 'init')) {
                $library::init();
            }
        } else {
            errorDump('File \'' . APP_PATH . 'libraries/' . $library . '.php\' not found!');die();
        }
    }

    public static function loadModel($model){
        if(file_exists(APP_PATH . 'models/' . $model . '.php')) {
            require APP_PATH . 'models/' . $model . '.php';
            if(method_exists($model, 'init')) {
                $model::init();
            }
        } else {
            errorDump('File \'' . APP_PATH . 'models/' . $model . '.php\' not found!');die();
        }
    }

    public static function loadView($view, $data = null, $buffered = false){
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
        } else {
            errorDump('File \'' . APP_PATH . 'views/' . $view . '.php\' not found!');die();
        }
    }

    public static function loadHelper($helper){
        if(file_exists(APP_PATH . 'helpers/' . $helper . '.php')){
            require APP_PATH . 'helpers/' . $helper . '.php';
        } elseif(file_exists(SYSTEM_PATH . 'helpers/' . $helper . '.php')){
            require SYSTEM_PATH . 'helpers/' . $helper . '.php';
        } else {
            errorDump('File \'' . APP_PATH . 'helpers/' . $helper . '.php\' not found!');die();
        }
    }

    public static function loadLanguage($language){
        if(file_exists(APP_PATH . 'lang/' . $language . '/' . $language . '_lang.php')){
            global $lang;
            require APP_PATH . 'lang/' . $language . '/' . $language . '_lang.php';
        } elseif(file_exists(SYSTEM_PATH . 'lang/' . $language . '/' . $language . '_lang.php')){
            require SYSTEM_PATH . 'lang/' . $language . '/' . $language . '_lang.php';
        } else {
            errorDump('File \'' . APP_PATH . 'helpers/' . $language . '.php\' not found!');die();
        }
    }

}
