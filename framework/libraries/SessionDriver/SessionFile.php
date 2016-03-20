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

        $this->generateSession();

        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $_SESSION['HTTP_USER_AGENT'] = 'UNDEFINED';
        }

        if(isset($_SERVER['REMOTE_ADDR'])){
            $_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
        }

        if(time() - $this->get('DATE_CREATED') > $this->session_expire){

        }
    }

    /**
     * Generate Session
     *
     * @return string
     */
    private function generateSession(){
        if(Cookie::get($this->session_name) == null){
            Cookie::set($this->session_name, sha1(session_id()));
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
     * Get Session Data
     *
     * @param $key
     * @return null
     */
    public function get($key){
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
            $string = read_file('storage/sessions/' . $session_id);
            $session_data = null;
            if($string != null){
                $session_data = (array)@unserialize(trim(Encryption::decode($string, $this->session_key)));
            }

            if(isset($session_data[$key])){
                return $session_data[$key];
            } elseif(isset($_SESSION[$key])){
                return $_SESSION[$key];
            }
        }
        return null;
    }

    /**
     * Set Session Data
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value){
        $session_id = $this->getSessionID();

        if($session_id != null){
            $session_data = null;

            if(file_exists('storage/sessions/' . $session_id) && $session_id != null) {
                $session_data = null;

                $string = read_file('storage/sessions/' . $session_id);

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

            return write_file('storage/sessions/' . $session_id, Encryption::encode(serialize($_SESSION), $this->session_key), 'w');
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

        if(file_exists('storage/sessions/' . $session_id) && $session_id != null) {
            return write_file('storage/sessions/' . $session_id, Encryption::encode(serialize($_SESSION), $this->session_key), 'w');
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
        if(file_exists('storage/sessions/' . $session_id) && $session_id != null) {
            return unlink('storage/sessions/' . $session_id);
        }
        return false;
    }

}
