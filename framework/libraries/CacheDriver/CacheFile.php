<?php
/**
 * Cache Driver File
 *
 *
 */

class CacheFile {

    /**
     * @var bool
     */
    public $cache_active = false;

    /**
     * @var bool
     */
    public $cache_encrypt = false;

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
            $cache = array(
                'DATE_CREATED' => time(),
                'MAX_AGE' => $max_age,
                'DATA' => $data
            );
            if($this->cache_encrypt){
                return write_file(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')), Encryption::encode(serialize($cache)));
            } else {
                return write_file(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')), serialize($cache));
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
            $cache = read_file(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')));
            if($cache != null){
                if($this->cache_encrypt) {
                    $cache = (array)unserialize(trim(Encryption::decode($cache)));
                } else {
                    $cache = (array)unserialize($cache);
                }

                if(($cache['DATE_CREATED'] + $cache['MAX_AGE']) > time()){
                    return $cache['DATA'];
                }
            }
        }
        return null;
    }

}
