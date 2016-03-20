<?php
/**
 * SessionFile Driver
 *
 * Custom session uses file as storage
 *
 */

class SessionFile {

    var $session_id = null;
    var $session_name = 'ELIPS_PHP_SESSID';
    var $session_key = 'SUPER_SECRET_KEY';
    var $session_expire = 7200;
    var $session_match_ip = false;
    var $seperator = '#SESSION_SEPARATOR#';
    var $session_max_size = 1048576;
    var $session_path = 'storage/sessions/';

    /**
     * Initialize Config
     *
     * @param null $config
     */
    public function init($config = null){
        if(function_exists('session_status')){
            if(session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        } elseif(session_id() == null) {
            session_start();
        }

        if(isset($config['name'])){
            $this->session_name = $config['name'];
        }

        if(isset($config['encryption_key'])){
            $this->session_key = $config['encryption_key'];
        }

        if(isset($config['expire'])){
            $this->session_expire = $config['expire'];
        }

        if(isset($config['match_ip'])){
            $this->session_match_ip = $config['match_ip'];
        }

        if(isset($config['max_size'])){
            $this->session_max_size = $config['max_size'];
        }

        $session_id = $this->generateSession();

        if(time() - $this->get('SESS_CREATED') > $this->session_expire){
            if(is_array($_SESSION)){
                foreach($_SESSION as $k => $v){
                    unset($_SESSION[$k]);
                }
            }
            delete_file($this->session_path . $session_id);
            Cookie::delete($this->session_name);
        }
    }

    /**
     * Generate Session
     *
     * @return string
     */
    private function generateSession(){
        if(Cookie::get($this->session_name) == null){
            if(isset($_SERVER['HTTP_USER_AGENT'])){
                $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
            } else {
                $_SESSION['HTTP_USER_AGENT'] = 'UNDEFINED';
            }

            if(isset($_SERVER['REMOTE_ADDR'])){
                $_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
            }

            $_SESSION['SESS_CREATED'] = time();
            $_SESSION['SESS_EXPIRED'] = $this->session_expire;

            $this->session_id = sha1(session_id());
            Cookie::set($this->session_name, $this->session_id);
            return $this->session_id;
        } else {
            return Cookie::get($this->session_name);
        }
    }

    /**
     * Get Session Id
     *
     * @return null|string
     */
    private function getSessionID(){
        if(Cookie::get($this->session_name) !== null){
            return Cookie::get($this->session_name);
        }

        return sha1(session_id());
    }

    /**
     * Self Get Session Data
     *
     * @return array|null
     */
    private function getSessionData(){
        $session_id = $this->getSessionID();

        $sess_user_agent = null;
        $sess_ip_addr = null;
        $user_agent = null;
        $ip_addr = null;

        if(isset($_SESSION['HTTP_USER_AGENT'])){
            $sess_user_agent = $_SESSION['HTTP_USER_AGENT'];
        }

        if(isset($_SESSION['REMOTE_ADDR'])){
            $sess_ip_addr = $_SESSION['REMOTE_ADDR'];
        }

        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
        }

        if(isset($_SERVER['REMOTE_ADDR'])){
            $ip_addr = $_SERVER['REMOTE_ADDR'];
        }

        if($user_agent == $sess_user_agent){
            if($this->session_match_ip){
                if($ip_addr != $sess_ip_addr){
                    $session_id = null;
                }
            }
        } else {
            $session_id = null;
        }

        if($session_id != null){
            $string = read_file($this->session_path . $session_id, get_app_config('session', 'max_size'));
            $session_data = null;
            if($string != null){
                return (array)@unserialize(trim(Encryption::decode($string, $this->session_key)));
            }
        }

        return null;
    }

    /**
     * Check Session Data Exists
     *
     * @param string $key
     * @return bool
     */
    public function has($key){
        $session_data = $this->getSessionData();

        if($session_data != null){
            if(isset($session_data[$key])){
                return true;
            }
        }

        if(isset($_SESSION[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Get Session Data
     *
     * @param string $key
     * @return null | mixed
     */
    public function get($key, $default = null){
        $session_data = $this->getSessionData();

        if($session_data != null){
            if(isset($session_data[$key])){
                return $session_data[$key];
            }
        }

        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }

        return $default == null ? $default : null;
    }

    /**
     * Get All Session Data
     *
     * @return array|null
     */
    public function all(){
        return $this->getSessionData();
    }

    /**
     * Set Session Data
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function set($key, $value){
        $session_id = $this->getSessionID();

        if($session_id != null){
            $session_data = null;

            if(file_exists($this->session_path . $session_id) && $session_id != null) {
                $session_data = null;

                $string = read_file($this->session_path . $session_id, get_app_config('session', 'max_size'));

                if($string != null){
                    $session_data = (array)@unserialize(trim(Encryption::decode($string, $this->session_key)));
                }

                if($session_data != null){
                    if(is_array($session_data)){
                        foreach($session_data as $k => $v){
                            if($k != 0){
                                $_SESSION[$k] = $v;
                            }
                        }
                    }
                }
            }
            $_SESSION[$key] = $value;

            return write_file($this->session_path . $session_id, Encryption::encode(serialize($_SESSION), $this->session_key), 'w');
        }
        return false;
    }

    /**
     * Remove Session Data
     *
     * @param $key
     * @return bool
     */
    public function remove($key){
        $session_id = $this->getSessionID();

        unset($_SESSION[$key]);

        if(file_exists($this->session_path . $session_id) && $session_id != null) {
            return write_file($this->session_path . $session_id, Encryption::encode(serialize($_SESSION), $this->session_key), 'w');
        }
        return false;
    }

    /**
     * Destroy Session Data
     *
     * @return bool
     */
    public function destroy(){
        $session_id = $this->getSessionID();
        session_destroy();
        if($session_id != null) {
            delete_file($this->session_path . $session_id);
        }
        return false;
    }

    /**
     * Regenerate Session ID
     */
    public function regenerate(){
        $this->session_id = $this->getSessionID();
        session_regenerate_id();

        if($this->session_id != null) {
            delete_file($this->session_path . $this->session_id);
        }

        $this->session_id = sha1(session_id());
        Cookie::set($this->session_name, $this->session_id);

        write_file($this->session_path . $this->session_id, Encryption::encode(serialize($_SESSION), $this->session_key), 'w');
    }

}
