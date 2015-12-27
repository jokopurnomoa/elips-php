<?php
/**
 * View library
 *
 * Basic load view
 *
 */

class View {

    public static function render($view, $data = null, $buffered = false){
        if(file_exists(APP_PATH . 'views/' . $view . '.php')){
            ob_start();
            if($data != null){
                foreach($data as $key => $val){
                    $$key = $val;
                }
            }
            require APP_PATH . 'views/' . $view . '.php';
            $__buffer = ob_get_contents();
            @ob_end_clean();

            if($buffered){
                return $__buffer;
            } else {
                echo $__buffer;
            }
        } elseif(APP_ENV === 'development') {
            error_dump('View : File \'' . APP_PATH . 'views/' . $view . '.php\' not found!');
            die();
        }
        return null;
    }

}
