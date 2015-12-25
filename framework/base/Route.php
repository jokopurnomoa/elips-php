<?php
/**
 * ROUTE
 *
 * Routing application controller
 *
 */

class Route {

    public static function run(){
        if(file_exists(APP_PATH . 'config/route.php')){
            require_once APP_PATH . 'config/route.php';
        } elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'config/route.php\' not found');
            die();
        }

        $classname = '';
        $methodname = 'index';
        $root_controller = '';

        $uri = URI::getURI();
        $arr_uri = explode('/', $uri);

        $route_found = false;
        if(isset($route)){
            if($route != null){
                /* Load root controller */
                if(isset($route['root_controller'])){
                    if($route['root_controller'] !== ''){
                        $root_controller = $route['root_controller'];
                        if(file_exists(APP_PATH . 'controllers/' . $route['root_controller'] . '.php')){
                            require_once APP_PATH . 'controllers/' . $route['root_controller'] . '.php';
                        } elseif(APP_ENV === 'development'){
                            error_dump('File \'' . APP_PATH . 'controllers/' . $route['root_controller'] . '.php\' not found');die();
                        }
                    }
                }
                /* End load root controller */

                if($uri != '/' && $uri != ''){
                    foreach($route as $key => $value){
                        $key = trim($key, '/');
                        $value = trim($value, '/');

                        $arr_key = explode('/', $key);
                        $arr_value = explode('/', $value);

                        if(count($arr_value) > 0 && count($arr_key) > 0){
                            if($uri == $key){
                                if(count($arr_value) == 1){
                                    $classname = $arr_value[0];
                                    $route_found = true;
                                }
                                elseif(count($arr_value) >= 2) {
                                    $classname = $arr_value[0];
                                    $methodname = $arr_value[1];
                                    $route_found = true;
                                }
                                break;
                            } else {
                                if(count($arr_key) > 1){
                                    if($arr_uri[0] == $arr_key[0]){
                                        if($arr_key[1] == '(:any)'){
                                            if(count($arr_value) == 1){
                                                $classname = $arr_value[0];
                                                $route_found = true;
                                            }
                                            elseif(count($arr_value) >= 2) {
                                                $classname = $arr_value[0];
                                                $methodname = $arr_value[1];
                                                $route_found = true;
                                            }
                                            break;
                                        }
                                    }
                                }
                            }
                        } elseif(APP_ENV === 'development') {
                            error_dump('Routing error!');die();
                        }
                    }
                } elseif(isset($route['default_controller'])){
                    $classname = $route['default_controller'];
                    $route_found = true;
                }
            }
        }

        if(!$route_found){
            $classname = isset($arr_uri[0]) ? $arr_uri[0] : '';
            $methodname = isset($arr_uri[1]) ? $arr_uri[1] : 'index';
        }

        if($classname !== '' && $methodname !== '' && strtolower($classname) !== strtolower($root_controller)){
            if(file_exists(APP_PATH . 'controllers/' . $classname . '.php')){
                require_once APP_PATH . 'controllers/' . $classname . '.php';
                $class = new $classname();
                $class->$methodname();
            } elseif(APP_ENV === 'development'){
                error_dump('File \'' . APP_PATH . 'controllers/' . $classname . '.php\' not found');die();
            }
        }
    }

}
