<?php
/**
 * Cache Driver APC
 *
 *
 */

class CacheAPC {

    /**
     * @var bool
     */
    public $cache_active = false;

    /**
     * @var bool
     */
    public $cache_encrypt = false;

    /**
     * Constructor
     */
    public function __construct(){
        if(!extension_loaded('apc') && !ini_get('apc.enabled') && APP_ENV === 'development'){
            error_dump('APC Cache is disabled!');
            die();
        }
    }

    /**
     * Store Cache
     *
     * @param $flag
     * @param $data
     * @param int $max_age
     * @return bool
     */
    public function store($flag, $data, $max_age = 60){
        if($this->cache_active){
            if($this->cache_encrypt){
                return apc_store(sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')), Encryption::encode($data), $max_age);
            } else {
                return apc_store(sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')), $data, $max_age);
            }
        }
        return false;
    }

    /**
     * Get Cache
     *
     * @param $flag
     * @return null
     */
    public function get($flag){
        if($this->cache_active){
            $cache = apc_fetch(sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')));
            if($cache != null){
                if($this->cache_encrypt) {
                    $cache = trim(Encryption::decode($cache));
                }

                return $cache;
            }
        }
        return null;
    }

    /**
     * Delete Cache
     *
     * @param $flag
     * @return bool|string[]
     */
    public function delete($flag){
        return apc_delete(sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')));
    }

}
