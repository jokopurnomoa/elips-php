<?php
/**
 * SessionFile
 *
 * Custom session uses file as storage
 *
 */

class SessionFile {

    static $session_id;
    static $session_name = 'ELIPS_PHP_SESSID';
    static $session_key = 'SUPER_SECRET_KEY';
    static $session_expire = 7200;
    static $session_match_ip = false;
    static $seperator = '#SESSION_SEPARATOR#';
    static $session_max_size = 1048576;

    public static function init($config = null){
        if(isset($config['session']['name'])){
            self::$session_name = $config['session']['name'];
        }

        if(isset($config['encryption_key'])){
            self::$session_key = $config['encryption_key'];
        }

        if(isset($config['session']['expire'])){
            self::$session_expire = $config['session']['expire'];
        }

        if(isset($config['session']['match_ip'])){
            self::$session_match_ip = $config['session']['match_ip'];
        }

        if(isset($config['session']['max_size'])){
            self::$session_max_size = $config['session']['max_size'];
        }

        self::generateSession();

        if(time() - self::get('DATE_CREATED') > self::$session_expire){

        }
    }

    private static function generateSession(){
        if(!isset($_COOKIE[self::$session_name])) {
            $session_id = $_SERVER['REMOTE_ADDR'] . self::$seperator . $_SERVER['HTTP_USER_AGENT'] . self::$seperator . $_SERVER['REQUEST_TIME_FLOAT'] . self::$seperator . md5(rand(9, 999999999));
            self::$session_id = Encryption::encode($session_id, self::$session_key);
            setcookie(self::$session_name, self::$session_id, time() + self::$session_expire);
            self::set('DATE_CREATED', time());
            self::set('LAST_ACTIVITY', time());
            return self::$session_id;
        }
    }

    private static function getSessionID(){
        if(isset($_COOKIE[self::$session_name])){
            if($_COOKIE[self::$session_name] != ''){
                return Encryption::decode($_COOKIE[self::$session_name], self::$session_key);
            }
        } else if(self::$session_id != ''){
            return Encryption::decode(self::$session_id, self::$session_key);
        }
        return null;
    }

    public static function get($key){
        $session_id = self::getSessionID();

        $ip_addr = null;
        $user_agent = null;
        if($session_id != ''){
            $arr_session_id = explode(self::$seperator, $session_id);
            if(count($arr_session_id) == 4){
                $ip_addr = $arr_session_id[0];
                $user_agent = $arr_session_id[1];
                if(self::$session_match_ip){
                    if($_SERVER['REMOTE_ADDR'] != $ip_addr || $_SERVER['HTTP_USER_AGENT'] != $user_agent){
                        $session_id = null;
                    }
                } else {
                    if($_SERVER['HTTP_USER_AGENT'] != $user_agent){
                        $session_id = null;
                    }
                }
            }
        }

        if($session_id != null){
            $session_id = sha1($session_id);
            if(file_exists('storage/sessions/' . $session_id)) {
                $handle = fopen('storage/sessions/' . $session_id, 'r');
                $string = fread($handle, self::$session_max_size);
                fclose($handle);

                $session_data = null;
                if($string != ''){
                    $session_data = (array)json_decode(trim(Encryption::decode($string, self::$session_key)));
                }

                if(isset($session_data[$key])){
                    return $session_data[$key];
                }
            }
        }
        return null;
    }

    public static function set($key, $value){
        $session_id = self::getSessionID();

        if($session_id != null){
            $session_id = sha1($session_id);
            $handle = fopen('storage/sessions/' . $session_id, 'w+');
            $string = fread($handle, self::$session_max_size);

            $session_data = null;
            if ($string != '') {
                $session_data = (array)json_decode(trim(Encryption::decode($string, self::$session_key)));
            }
            $session_data[$key] = $value;

            fwrite($handle, Encryption::encode(json_encode($session_data), self::$session_key));
            return fclose($handle);
        }
        return false;
    }

    public static function remove($key){
        $session_id = self::getSessionID();

        if(file_exists('storage/sessions/' . $session_id) && $session_id != '') {
            $handle = fopen('storage/sessions/' . $session_id, 'w+');
            $string = fread($handle, self::$session_max_size);
            $session_data = null;
            if ($string != '') {
                $session_data = (array)json_decode(trim(Encryption::decode($string, self::$session_key)));
            }
            unset($session_data[$key]);

            fwrite($handle, Encryption::encode(json_encode($session_data), self::$session_key));
            return fclose($handle);
        }
        return false;
    }

    public static function destroy(){
        $session_id = sha1(self::getSessionID());
        if(file_exists('storage/sessions/' . $session_id) && $session_id != '') {
            return unlink('storage/sessions/' . $session_id);
        }
        return false;
    }

}
