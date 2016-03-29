<?php
/**
 * Cache Driver APC
 *
 *
 */

namespace Elips\Libraries\CacheDriver;

use Elips\Libraries\Encryption;

class CacheAPC
{

    /**
     * @var bool
     */
    public $cacheActive = false;

    /**
     * @var bool
     */
    public $cacheEncrypt = false;

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
     * @param int    $maxAge
     * @return bool
     */
    public function store($flag, $data, $maxAge = 60)
    {
        if ($this->cacheActive) {
            if ($this->cacheEncrypt) {
                $data = Encryption::encode($data);
            }
            return write_file(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cacheEncrypt ? '_encrypt' : '')), $data);
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
        if ($this->cacheActive) {
            $cache = apc_fetch(sha1($flag . ($this->cacheEncrypt ? '_encrypt' : '')));
            if ($cache != null) {
                if ($this->cacheEncrypt) {
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
        return apc_delete(sha1($flag . ($this->cacheEncrypt ? '_encrypt' : '')));
    }

}
