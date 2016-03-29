<?php
/**
 * SessionFile Driver
 *
 * Custom session uses file as storage
 *
 */

namespace Elips\Libraries\SessionDriver;

use Elips\Libraries\Cookie;
use Elips\Libraries\Encryption;

class SessionFile
{

    var $session_id = null;
    var $session_name = 'ELIPS_PHP_SESSID';
    var $session_key = 'SUPER_SECRET_KEY';
    var $session_expire = 7200;
    var $session_match_ip = false;
    var $session_max_size = 1048576;
    var $session_path = 'storage/sessions/';
    var $session_data = array();
    var $session_delete_id = null;

    /**
     * Initialize Config
     *
     * @param null $config
     */
    public function init($config = null)
    {
        if (isset($config['name'])) {
            $this->session_name = $config['name'];
        }

        if (isset($config['encryption_key'])) {
            $this->session_key = $config['encryption_key'];
        }

        if (isset($config['expire'])) {
            $this->session_expire = $config['expire'];
        }

        if (isset($config['match_ip'])) {
            $this->session_match_ip = $config['match_ip'];
        }

        if (isset($config['max_size'])) {
            $this->session_max_size = $config['max_size'];
        }

        if (time() - $this->get('SESS_CREATED') > $this->session_expire) {
            $this->destroy();
        }

        $this->generateSession();
    }

    /**
     * Generate Random String
     *
     * @param $length
     * @return string
     */
    private function generateRandomString($length)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    /**
     * Generate Session Id
     *
     * @return string
     */
    private function generateSessionId()
    {
        return sha1(uniqid('', true).$this->generateRandomString(25).microtime(true));
    }

    /**
     * Generate Session
     *
     * @return string
     */
    private function generateSession()
    {
        if (Cookie::get($this->session_name) == null) {
            $this->session_data['SESS_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
            $this->session_data['SESS_IP_ADDR'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
            $this->session_data['SESS_CREATED'] = time();
            $this->session_data['SESS_EXPIRED'] = $this->session_expire;
            $this->session_data['SESS_LAST_ACTIVITY'] = time();

            $this->session_id = $this->generateSessionId();
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
    private function getSessionId()
    {
        if (Cookie::get($this->session_name) !== null) {
            return Cookie::get($this->session_name);
        }
        return $this->session_id;
    }

    /**
     * Self Get Session Data
     *
     * @return array|null
     */
    private function getSessionData()
    {
        $session_id = $this->getSessionId();

        $string = read_file($this->session_path . $session_id, $this->session_max_size);
        if ($string != null) {
            $this->session_data = (array)@unserialize(trim(Encryption::decode($string, $this->session_key)));

            if ($this->session_data != null) {
                $sess_user_agent = isset($this->session_data['SESS_USER_AGENT']) ? $this->session_data['SESS_USER_AGENT'] : '';
                $sess_ip_addr = isset($this->session_data['SESS_IP_ADDR']) ? $this->session_data['SESS_IP_ADDR'] : '';

                $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
                $ip_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';;

                if ($user_agent == $sess_user_agent) {
                    if (!$this->session_match_ip) {
                        return $this->session_data;
                    } elseif ($ip_addr == $sess_ip_addr) {
                        return $this->session_data;
                    }
                }
            }
        }
        return $this->session_data;
    }

    /**
     * Check Session Data Exists
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        $this->session_data = $this->getSessionData();

        if ($this->session_data != null) {
            if (isset($this->session_data[$key])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get Session Data
     *
     * @param string $key
     * @return null | mixed
     */
    public function get($key, $default = null)
    {
        $session_id = $this->getSessionId();
        $this->session_data = $this->getSessionData();

        if ($this->session_data != null) {
            if (isset($this->session_data[$key])) {
                return $this->session_data[$key];
            }

            $this->session_data['SESS_LAST_ACTIVITY'] = time();
            write_file($this->session_path . $session_id, Encryption::encode(serialize($this->session_data), $this->session_key));
        }

        return $default == null ? $default : null;
    }

    /**
     * Get All Session Data
     *
     * @return array|null
     */
    public function all()
    {
        return $this->getSessionData();
    }

    /**
     * Set Session Data
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function set($key, $value)
    {
        $session_id = $this->getSessionId();
        $this->session_data = $this->getSessionData();
        $this->session_data[$key] = $value;
        $this->session_data['SESS_LAST_ACTIVITY'] = time();
        return write_file($this->session_path . $session_id, Encryption::encode(serialize($this->session_data), $this->session_key));
    }

    /**
     * Remove Session Data
     *
     * @param $key
     * @return bool
     */
    public function remove($key)
    {
        $session_id = $this->getSessionId();
        $this->session_data = $this->getSessionData();
        unset($this->session_data[$key]);
        $this->session_data['SESS_LAST_ACTIVITY'] = time();
        return write_file($this->session_path . $session_id, Encryption::encode(serialize($this->session_data), $this->session_key));
    }

    /**
     * Destroy Session Data
     *
     * @return bool
     */
    public function destroy()
    {
        $session_id = $this->getSessionId();
        $this->session_data = null;
        delete_file($this->session_path . $session_id);
        Cookie::delete($this->session_name);
    }

    /**
     * Regenerate Session Id
     */
    public function regenerate()
    {
        $this->session_id = $this->getSessionId();
        $this->session_data = $this->getSessionData();
        delete_file($this->session_path . $this->session_id);

        $this->session_id = $this->generateSessionId();
        Cookie::set($this->session_name, $this->session_id);

        write_file($this->session_path . $this->session_id, Encryption::encode(serialize($this->session_data), $this->session_key));
    }

}
