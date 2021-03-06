<?php
/**
 * Core Library
 *
 * Loading base system
 *
 */

namespace Elips\Core;

class Core
{

    /**
     * @var
     */
    public $data;

    public function __construct()
    {
        /**
         * Initialize
         */
        $GLOBALS['config'] = null;
        $GLOBALS['lang'] = null;
        $GLOBALS['modulePath'] = '';
    }

    /**
     * Run Core
     */
    public function run()
    {
        /**
         * Require main helper
         */
        require FW_PATH . 'Helpers/file.php';
        require FW_PATH . 'Helpers/log.php';
        require FW_PATH . 'Helpers/input.php';
        require FW_PATH . 'Helpers/error.php';
        require FW_PATH . 'Helpers/url.php';
        require FW_PATH . 'Helpers/config.php';

        /**
         * Check assets request
         */
        if (self::isAssetsRequest(URI::getURI())) {
            exit;
        }

        /**
         * Require app config
         */
        if (file_exists(APP_PATH . 'Config/app.php')) {
            $config = require APP_PATH . 'Config/app.php';
            if (is_array($config) && $config !== null) {
                foreach($config as $key =>$value) {
                    $GLOBALS['config'][$key] = $value;
                }
            }
        } elseif (APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'Config/app.php\' not found!');die();
        }

        /**
         * Require database config
         */
        if(file_exists(APP_PATH . 'Config/database.php')){
            $config = require APP_PATH . 'Config/database.php';
            if (is_array($config) && $config !== null) {
                foreach($config as $key =>$value) {
                    $GLOBALS['config'][$key] = $value;
                }
            }
        } elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'Config/database.php\' not found!');die();
        }

        /**
         * Require mimes config
         */
        if(file_exists(APP_PATH . 'Config/mimes.php')){
            $config = require APP_PATH . 'Config/mimes.php';
            if (is_array($config) && $config !== null) {
                foreach($config as $key =>$value) {
                    $GLOBALS['config'][$key] = $value;
                }
            }
        } elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'Config/mimes.php\' not found!');die();
        }

        $this->handleAutoload();
        Route::run();
    }

    /**
     * Handle Autoload
     */
    private function handleAutoload()
    {
        $autoload = null;
        if (file_exists(APP_PATH . 'Config/autoload.php')) {
            $autoload = require(APP_PATH . 'Config/autoload.php');
        } elseif (APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'Config/autoload.php\' not found!');
            die();
        }

        $this->autoloadLanguage($autoload);
        $this->autoloadHelpers($autoload);
    }

    /**
     * Autoload Language
     *
     * @param $autoload
     */
    private function autoloadLanguage($autoload)
    {
        if (isset($autoload['languages'])) {
            if ($autoload['languages'] != null) {
                foreach ($autoload['languages'] as $language) {
                    Load::language($language);
                }
            }
        }
    }

    /**
     * Autoload Helpers
     *
     * @param $autoload
     */
    private function autoloadHelpers($autoload)
    {
        if (isset($autoload['helpers'])) {
            if ($autoload['helpers'] != null) {
                foreach ($autoload['helpers'] as $helper) {
                    Load::helper($helper);
                }
            }
        }
    }

    /**
     * Check assets request
     *
     * @param string $uri
     * @return bool
     */
    private static function isAssetsRequest($uri)
    {
        if (strpos($uri, base_url() . 'assets/') !== false) {
            return true;
        }
        if (strpos($uri, '.css') !== false) {
            return true;
        }
        if (strpos($uri, '.js') !== false) {
            return true;
        }
        if (strpos($uri, '.png') !== false) {
            return true;
        }
        if (strpos($uri, '.jpg') !== false) {
            return true;
        }
        if (strpos($uri, '.jpeg') !== false) {
            return true;
        }
        if (strpos($uri, '.gif') !== false) {
            return true;
        }
        if (strpos($uri, '.xml') !== false) {
            return true;
        }
        return false;
    }
}
