<?php
/**
 * Cache Driver File
 *
 *
 */

namespace Elips\Libraries\CacheDriver;

use Elips\Libraries\Encryption;

class CacheFile
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
            $cache = array(
                'DATE_CREATED' => time(),
                'MAX_AGE' => $max_age,
                'DATA' => $data
            );

            if ($this->cache_encrypt) {
                $cache = Encryption::encode(serialize($cache));
            } else {
                $cache = serialize($cache);
            }
            return write_file(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')), $cache);
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
            $cache = read_file(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')));
            if ($cache != null) {
                if ($this->cache_encrypt) {
                    $cache = (array)@unserialize(trim(Encryption::decode($cache)));
                } else {
                    $cache = (array)@unserialize($cache);
                }

                if (($cache['DATE_CREATED'] + $cache['MAX_AGE']) > time()) {
                    return $cache['DATA'];
                }
            }
        }
        return null;
    }

    /**
     * Delete Cache
     *
     * @param string $flag
     */
    public function delete($flag)
    {
        if (file_exists(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')))) {
            return unlink(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cache_encrypt ? '_encrypt' : '')));
        }
        return false;
    }

}
