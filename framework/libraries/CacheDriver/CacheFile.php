<?php
/**
 * Cache Driver File
 *
 *
 */

class CacheFile {

    public $cache_active = false;
    public $max_cache_size = 1024000;
    public $cache_encrypt = false;

    public function store($flag, $data, $max_age = 60){
        if($this->cache_active){
            $handle = fopen(PROJECT_PATH . 'storage/cache/' . sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')), 'w');
            $cache = array(
                'DATE_CREATED' => time(),
                'MAX_AGE' => $max_age,
                'DATA' => $data
            );
            if($this->cache_encrypt){
                fwrite($handle, Encryption::encode(serialize($cache)));
            } else {
                fwrite($handle, serialize($cache));
            }
            return fclose($handle);
        }
        return false;
    }

    public function get($flag){
        if($this->cache_active){
            if(file_exists(PROJECT_PATH . 'storage/cache/' . sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')))){
                $handle = fopen(PROJECT_PATH . 'storage/cache/' . sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')), 'r');
                $cache = trim(fread($handle, $this->max_cache_size));
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
        }
        return null;
    }

}
