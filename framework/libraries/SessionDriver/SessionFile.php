<?php
/**
 * SessionFile
 *
 * Custom session uses file as storage
 *
 */

class SessionFile {

    var $session_id;
    var $session_name = 'ELIPS_PHP_SESSID';
    var $session_key = 'SUPER_SECRET_KEY';
    var $session_expire = 7200;
    var $session_match_ip = false;
    var $seperator = '#SESSION_SEPARATOR#';
    var $session_max_size = 1048576;

    public function init($config = null){
        if(isset($config['session']['name'])){
            $this->session_name = $config['session']['name'];
        }

        if(isset($config['encryption_key'])){
            $this->session_key = $config['encryption_key'];
        }

        if(isset($config['session']['expire'])){
            $this->session_expire = $config['session']['expire'];
        }

        if(isset($config['session']['match_ip'])){
            $this->session_match_ip = $config['session']['match_ip'];
        }

        if(isset($config['session']['max_size'])){
            $this->session_max_size = $config['session']['max_size'];
        }

        $this->generateSession();

        if(time() - $this->get('DATE_CREATED') > $this->session_expire){

        }
    }

    private function generateSession(){
        if(!isset($_COOKIE[$this->session_name])) {
            $session_id = $_SERVER['REMOTE_ADDR'] . $this->seperator . $_SERVER['HTTP_USER_AGENT'] . $this->seperator . $_SERVER['REQUEST_TIME_FLOAT'] . $this->seperator . md5(rand(9, 999999999));
            $this->session_id = Encryption::encode($session_id, $this->session_key);
            setcookie($this->session_name, $this->session_id, time() + $this->session_expire);
            return $this->session_id;
        }
    }

    private function getSessionID(){
        if(isset($_COOKIE[$this->session_name])){
            if($_COOKIE[$this->session_name] != ''){
                return Encryption::decode($_COOKIE[$this->session_name], $this->session_key);
            }
        } else if($this->session_id != ''){
            return Encryption::decode($this->session_id, $this->session_key);
        }
        return null;
    }

    public function get($key){
        $session_id = $this->getSessionID();

        $ip_addr = null;
        $user_agent = null;
        if($session_id != ''){
            $arr_session_id = explode($this->seperator, $session_id);
            if(count($arr_session_id) == 4){
                $ip_addr = $arr_session_id[0];
                $user_agent = $arr_session_id[1];
                if($this->session_match_ip){
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
                $string = fread($handle, $this->session_max_size);
                fclose($handle);

                $session_data = null;
                if($string != ''){
                    $session_data = (array)json_decode(trim(Encryption::decode($string, $this->session_key)));
                }

                if(isset($session_data[$key])){
                    return $session_data[$key];
                }
            }
        }
        return null;
    }

    public function set($key, $value){
        $session_id = $this->getSessionID();

        if($session_id != null){
            $session_id = sha1($session_id);
            $session_data = null;

            if(!file_exists('storage/sessions/' . $session_id)){
                $session_data['DATE_CREATED'] = time();
            }
            $handle = fopen('storage/sessions/' . $session_id, 'w+');
            $string = fread($handle, $this->session_max_size);

            if($string != '') {
                $session_data = (array)json_decode(trim(Encryption::decode($string, $this->session_key)));
            }
            $session_data[$key] = $value;
            $session_data['LAST_ACTIVITY'] = time();

            fwrite($handle, Encryption::encode(json_encode($session_data), $this->session_key));
            return fclose($handle);
        }
        return false;
    }

    public function remove($key){
        $session_id = $this->getSessionID();

        if(file_exists('storage/sessions/' . $session_id) && $session_id != '') {
            $handle = fopen('storage/sessions/' . $session_id, 'w+');
            $string = fread($handle, $this->session_max_size);
            $session_data = null;
            if($string != '') {
                $session_data = (array)json_decode(trim(Encryption::decode($string, $this->session_key)));
            }
            unset($session_data[$key]);

            fwrite($handle, Encryption::encode(json_encode($session_data), $this->session_key));
            return fclose($handle);
        }
        return false;
    }

    public function destroy(){
        $session_id = sha1($this->getSessionID());
        if(file_exists('storage/sessions/' . $session_id) && $session_id != '') {
            return unlink('storage/sessions/' . $session_id);
        }
        return false;
    }

}
