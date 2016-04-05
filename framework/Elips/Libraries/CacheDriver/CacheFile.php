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
    public $cacheActive = false;

    /**
     * @var bool
     */
    public $cacheEncrypt = false;

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
        var_dump($this->cacheActive);

        if ($this->cacheActive) {
            $cache = array(
                'date_created' => time(),
                'max_age' => $maxAge,
                'data' => $data
            );

            if ($this->cacheEncrypt) {
                $cache = Encryption::encode(serialize($cache));
            } else {
                $cache = serialize($cache);
            }
            return write_file(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cacheEncrypt ? '_encrypt' : '')), $cache);
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
            $cache = read_file(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cacheEncrypt ? '_encrypt' : '')));
            if ($cache != null) {
                if ($this->cacheEncrypt) {
                    $cache = (array)@unserialize(trim(Encryption::decode($cache)));
                } else {
                    $cache = (array)@unserialize($cache);
                }

                if (($cache['date_created'] + $cache['max_age']) > time()) {
                    return $cache['data'];
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
        if (file_exists(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cacheEncrypt ? '_encrypt' : '')))) {
            return unlink(MAIN_PATH . 'storage/cache/' . sha1($flag . ($this->cacheEncrypt ? '_encrypt' : '')));
        }
        return false;
    }

}
