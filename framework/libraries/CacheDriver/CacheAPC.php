<?php
/**
 * Cache Driver APC
 *
 *
 */

namespace Elips\Libraries\CacheDriver;

class CacheAPC
{

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
    public function __construct()
    {
        if (!extension_loaded('apc') && !ini_get('apc.enabled') && APP_ENV === 'development') {
            error_dump('APC Cache is disabled!');
            die();
        }
    }

    /**
     * Store Cache
     *
     * @param string $flag
     * @param mixed  $data
     * @param int    $max_age
     * @return bool
     */
    public function store($flag, $data, $max_age = 60)
    {
        if ($this->cache_active) {
            if ($this->cache_encrypt) {
                $data = Encryption::encode($data);
            }
            return write_file(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')), $data);
        }
        return false;
    }

    /**
     * Get Cache
     *
     * @param string $flag
     * @return null|mixed
     */
    public function get($flag)
    {
        if ($this->cache_active) {
            $cache = apc_fetch(sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')));
            if ($cache != null) {
                if ($this->cache_encrypt) {
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
     * @param string $flag
     * @return bool
     */
    public function delete($flag)
    {
        return apc_delete(sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')));
    }

}
