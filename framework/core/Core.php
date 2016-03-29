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

    /**
     * Run Core
     */
    public function run()
    {
        require FW_PATH . 'Helpers/file.php';
        require FW_PATH . 'Helpers/input.php';

        $this->handleAutoload();
        Route::run();
    }

    /**
     * Handle Autoload
     */
    private function handleAutoload()
    {
        $autoload = null;
        if (file_exists(APP_PATH . 'config/autoload.php')) {
            require(APP_PATH . 'config/autoload.php');
        } elseif (APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'config/autoload.php\' not found!');
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
}
