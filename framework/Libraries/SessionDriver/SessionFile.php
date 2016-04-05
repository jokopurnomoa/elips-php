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

    var $sessionId = null;
    var $sessionName = 'ELIPS_PHP_SESSID';
    var $sessionKey = 'SUPER_SECRET_KEY';
    var $sessionExpire = 7200;
    var $sessionMatchIP = false;
    var $sessionMaxSize = 1048576;
    var $sessionPath = 'storage/sessions/';
    var $sessionData = array();
    var $sessionDeleteId = null;
    var $sessionEncrypt = false;

    public function __construct()
    {

    }

    public function __destruct()
    {
        if ($this->sessionEncrypt) {
            $data = Encryption::encode(serialize($this->sessionData), $this->sessionKey);
        } else {
            $data = serialize($this->sessionData);
        }
        return write_file(MAIN_PATH . $this->sessionPath . $this->sessionId, $data);
    }

    /**
     * Initialize Config
     *
     * @param null $config
     */
    public function init($config = null)
    {
        if (isset($config['name'])) {
            $this->sessionName = $config['name'];
        }

        if (isset($config['encryption_key'])) {
            $this->sessionKey = $config['encryption_key'];
        }

        if (isset($config['expire'])) {
            $this->sessionExpire = $config['expire'];
        }

        if (isset($config['match_ip'])) {
            $this->sessionMatchIP = $config['match_ip'];
        }

        if (isset($config['max_size'])) {
            $this->sessionMaxSize = $config['max_size'];
        }

        if (isset($config['encrypt'])) {
            $this->sessionEncrypt = $config['encrypt'];
        }

        if (time() - $this->get('SESS_CREATED') > $this->sessionExpire) {
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
        if (Cookie::get($this->sessionName) == null) {
            $this->sessionData['SESS_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
            $this->sessionData['SESS_IP_ADDR'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
            $this->sessionData['SESS_CREATED'] = time();
            $this->sessionData['SESS_EXPIRED'] = $this->sessionExpire;
            $this->sessionData['SESS_LAST_ACTIVITY'] = time();

            $this->sessionId = $this->generateSessionId();
            Cookie::set($this->sessionName, $this->sessionId, $this->sessionExpire);
            return $this->sessionId;
        } else {
            return Cookie::get($this->sessionName);
        }
    }

    /**
     * Get Session Id
     *
     * @return null|string
     */
    private function getSessionId()
    {
        if (Cookie::get($this->sessionName) !== null) {
            $this->sessionId = Cookie::get($this->sessionName);
            return $this->sessionId;
        }
        return $this->sessionId;
    }

    /**
     * Self Get Session Data
     *
     * @return array|null
     */
    private function getSessionData()
    {
        $sessionId = $this->getSessionId();

        $string = read_file(MAIN_PATH . $this->sessionPath . $sessionId, $this->sessionMaxSize);
        if ($string != null) {
            if ($this->sessionEncrypt) {
                $this->sessionData = (array)@unserialize(trim(Encryption::decode($string, $this->sessionKey)));
            } else {
                $this->sessionData = (array)@unserialize($string);
            }

            if ($this->sessionData != null) {
                $sess_user_agent = isset($this->sessionData['SESS_USER_AGENT']) ? $this->sessionData['SESS_USER_AGENT'] : '';
                $sess_ip_addr = isset($this->sessionData['SESS_IP_ADDR']) ? $this->sessionData['SESS_IP_ADDR'] : '';

                $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
                $ip_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';;

                if ($user_agent == $sess_user_agent) {
                    if (!$this->sessionMatchIP) {
                        return $this->sessionData;
                    } elseif ($ip_addr == $sess_ip_addr) {
                        return $this->sessionData;
                    }
                }
            }
        }
        return $this->sessionData;
    }

    /**
     * Check Session Data Exists
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        $this->sessionData = $this->getSessionData();

        if ($this->sessionData != null) {
            if (isset($this->sessionData[$key])) {
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
        $this->sessionData = $this->getSessionData();

        if ($this->sessionData != null) {
            if (isset($this->sessionData[$key])) {
                return $this->sessionData[$key];
            }

            $this->sessionData['SESS_LAST_ACTIVITY'] = time();
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
        $this->sessionData = $this->getSessionData();
        $this->sessionData[$key] = $value;
        $this->sessionData['SESS_LAST_ACTIVITY'] = time();
    }

    /**
     * Remove Session Data
     *
     * @param $key
     * @return bool
     */
    public function remove($key)
    {
        $this->sessionData = $this->getSessionData();
        unset($this->sessionData[$key]);
        $this->sessionData['SESS_LAST_ACTIVITY'] = time();
    }

    /**
     * Destroy Session Data
     *
     * @return bool
     */
    public function destroy()
    {
        $sessionId = $this->getSessionId();
        $this->sessionData = null;
        delete_file($this->sessionPath . $sessionId);
        Cookie::delete($this->sessionName);
    }

    /**
     * Regenerate Session Id
     */
    public function regenerate()
    {
        $this->sessionId = $this->getSessionId();
        $this->sessionData = $this->getSessionData();
        delete_file($this->sessionPath . $this->sessionId);

        $this->sessionId = $this->generateSessionId();
        Cookie::set($this->sessionName, $this->sessionId);
    }

}
