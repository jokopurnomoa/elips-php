<?php
/**
 * Cache library
 *
 *
 */

class Cache {

    private static $cache_active = false;
    private static $max_cache_size = 1024000;
    private static $cache_encrypt = false;

    public static function init(){
        $config = null;
        if(file_exists(APP_PATH . 'config/app.php')){
            require APP_PATH . 'config/app.php';
        } elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'config/app.php\' not found!');die();
        }

        self::$cache_active = $config['cache']['active'];
        self::$cache_encrypt = $config['cache']['encrypt'];
    }

    public static function setCache($flag, $data, $max_age = 3600){
        if(self::$cache_active){
            $handle = fopen(PROJECT_PATH . 'storage/cache/' . sha1($flag), 'w');
            $cache = array(
                'DATE_CREATED' => time(),
                'MAX_AGE' => $max_age,
                'DATA' => htmlspecialchars($data)
            );

            if(self::$cache_encrypt){
                fwrite($handle, Encryption::encode(json_encode($cache)));
            } else {
                fwrite($handle, json_encode($cache));
            }
            return fclose($handle);
        }
        return false;
    }

    public static function getCache($flag){
        if(self::$cache_active){
            if(file_exists(PROJECT_PATH . 'storage/cache/' . sha1($flag))){
                $handle = fopen(PROJECT_PATH . 'storage/cache/' . sha1($flag), 'r');
                $cache = fread($handle, self::$max_cache_size);
                if($cache != null){
                    if(self::$cache_encrypt) {
                        $cache = (array)json_decode(Encryption::decode($cache));
                    } else {
                        $cache = (array)json_decode($cache);
                    }

                    if(($cache['DATE_CREATED'] + $cache['MAX_AGE']) > time()){
                        return htmlspecialchars_decode($cache['DATA']);
                    }
                }
            }
        }
        return null;
    }

}