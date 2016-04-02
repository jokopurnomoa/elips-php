<?php
/**
 * Base Route
 *
 * Routing application controller
 *
 */

namespace Elips\Core;

class Route
{
    private static $className;
    private static $methodName = 'index';
    private static $routeFound = false;
    private static $page404;
    private static $methodSeparator;
    private static $rootController;

    /**
     * Run Routing
     */
    public static function run()
    {
        $route = null;
        if(file_exists(APP_PATH . 'config/route.php')){
            $route = require APP_PATH . 'config/route.php';
        } elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'config/route.php\' not found');
            die();
        }

        self::$rootController = '';

        $uri = URI::getURI();
        $arrUri = explode('/', $uri);

        if ($route == null) {
            if (APP_ENV === 'development') {
                error_dump('Route config is not set correctly!');die();
            }
        }

        self::$page404 = self::getPage404Config($route);
        self::$methodSeparator = self::getMethodSeparator($route);

        self::loadRootController($route);
        self::getClassAndMethod($route, $uri);

        if (!self::$routeFound) {
            self::$className = (isset($arrUri[0]) ? $arrUri[0] : '') . 'Controller';
            self::$methodName = isset($arrUri[1]) ? $arrUri[1] : 'index';
        }

        if (self::$className != '' && self::$methodName != '' &&
            strtolower(self::$className) != strtolower(self::$rootController)) {

            $_modulePath = trim('Modules/' . ucfirst(trim(strtolower(str_replace('Controller', '', self::$className)))), '/') . '/';
            self::$className = ucfirst(self::$className);

            if (file_exists(APP_PATH . 'Controllers/' . self::$className . '.php')) {
                self::callClassMethod();
            } elseif (file_exists(APP_PATH . $_modulePath . 'Controllers/' . self::$className . '.php')) {
                $GLOBALS['modulePath'] = $_modulePath;
                self::callClassMethod();
            } elseif (file_exists(APP_PATH . 'views/404.blade.php')) {
                $class = new self::$page404();
                $class->index();
            } else {
                error_dump('404 Page Not Found!');
                die();
            }
        }

    }

    /**
     * Get page 404 config
     *
     * @param array $route
     * @return string
     */
    private static function getPage404Config($route)
    {
        if (isset($route['404'])) {
            if ($route['404'] != '') {
                return $route['404'];
            }
        }
        return 'Elips\Core\Error404';
    }

    /**
     * Get method separator
     *
     * @param array $route
     * @return string
     */
    private static function getMethodSeparator($route)
    {
        if (isset($route['method_separator'])) {
            if ($route['method_separator'] != '') {
                return $route['method_separator'];
            }
        }
        return '_';
    }

    /**
     * Load root controller
     *
     * @param array $route
     */
    private static function loadRootController($route)
    {
        if (isset($route['root_controller'])) {
            if ($route['root_controller'] != '') {
                $root_controller = $route['root_controller'] . 'Controller';
                if (!file_exists(APP_PATH . 'Controllers/' . $root_controller . '.php') && APP_ENV === 'development') {
                    error_dump('File \'' . APP_PATH . 'Controllers/' . $root_controller . '.php\' not found');die();
                }
            }
        }
    }

    /**
     * Get class and method
     *
     * @param array $route
     * @param string $uri
     */
    private static function getClassAndMethod($route, $uri)
    {
        if ($uri != '/' && $uri != '') {
            foreach ($route as $key => $value) {
                if (self::parseRoute($uri, trim($key, '/'), trim($value, '/'))) {
                    break;
                }
            }
        } elseif (isset($route['default_controller'])) {
            self::$className = $route['default_controller'];
            self::$routeFound = true;
        }

        self::$className .= 'Controller';
    }

    /**
     * Parse route
     *
     * @param string $uri
     * @param string $routeSource
     * @param string $routeDest
     * @return bool
     */
    private static function parseRoute($uri, $routeSource, $routeDest)
    {
        $arrUri = explode('/', $uri);
        $arrKey = explode('/', $routeSource);
        $arrValue = explode('/', $routeDest);

        if (count($arrValue) > 0 && count($arrKey) > 0) {
            if ($uri === $routeSource) {
                if (count($arrValue) === 1) {
                    self::$className = $arrValue[0];
                    self::$routeFound = true;
                }
                elseif (count($arrValue) >= 2) {
                    self::$className = $arrValue[0];
                    self::$methodName = $arrValue[1];
                    self::$routeFound = true;
                }
                return true;
            } else {
                if (count($arrKey) > 1) {
                    if ($arrUri[0] === $arrKey[0]) {
                        if ($arrKey[1] === '(:any)') {
                            if (count($arrValue) === 1) {
                                self::$className = $arrValue[0];
                                self::$routeFound = true;
                            }
                            elseif (count($arrValue) >= 2) {
                                self::$className = $arrValue[0];
                                if ($arrValue[1] === '(:any)' && isset($arrUri[1])) {
                                    self::$methodName = $arrUri[1];
                                } else {
                                    self::$methodName = $arrValue[1];
                                }
                                self::$routeFound = true;
                            }
                            return true;
                        }
                        elseif ($arrUri[1] === $arrKey[1] && count($arrValue) >= 2) {
                            self::$className = $arrValue[0];
                            self::$methodName = $arrValue[1];
                            self::$routeFound = true;
                            return true;
                        }
                    }
                }
            }
        } elseif (APP_ENV === 'development') {
            error_dump('Routing error!');die();
        }

        return false;
    }

    /**
     * Call method of class
     */
    private static function callClassMethod()
    {
        if($GLOBALS['modulePath'] != ''){
            $GLOBALS['modulePath'] = str_replace('/', '\\', str_replace('Modules/', '', $GLOBALS['modulePath']));
            $_className = 'App\\Modules\\' . $GLOBALS['modulePath'] . 'Controllers\\' . self::$className;
        } else {
            $_className = 'App\\Controllers\\' . self::$className;
        }

        $class = new $_className();

        if (method_exists(self::$className, self::$methodName)) {
            $methodName = self::$methodName;
            $class->$methodName();
        } else {
            $separatorFound = substr_count(self::$methodName, self::$methodSeparator);
            if ($separatorFound > 0) {
                for ($i=0; $i<$separatorFound; $i++) {
                    $separator_pos = strpos(self::$methodName, self::$methodSeparator);
                    if ($separator_pos !== false) {
                        self::$methodName = substr(self::$methodName, 0, $separator_pos) . ucfirst(substr(self::$methodName, $separator_pos + 1, strlen(self::$methodName) - $separator_pos - 1));
                    }
                }
            }

            if (APP_ENV === 'development') {
                $methodName = self::$methodName;
                $class->$methodName();
            } elseif (method_exists(self::$className, self::$methodName)) {
                $methodName = self::$methodName;
                $class->$methodName();
            } elseif (file_exists(APP_PATH . 'views/404.blade.php')) {
                require_once FW_PATH . 'core/' . self::$page404 . '.php';
                $class = new self::$page404();
                $class->index();
            } else {
                error_dump('404 Page Not Found!');
                die();
            }
        }
    }

}
