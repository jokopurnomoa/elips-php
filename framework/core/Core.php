<?php
/**
 * Core Library
 *
 * Loading base system
 *
 */

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
        require FW_PATH . 'helpers/file.php';
        require FW_PATH . 'core/Load.php';
        require FW_PATH . 'core/Route.php';
        require FW_PATH . 'core/Model.php';
        require FW_PATH . 'core/Controller.php';

        Load::library('URI');
        Load::library('Encryption');
        Load::library('Cookie');
        Load::library('Security');
        Load::library('Cache');
        Load::library('Blade');
        Load::library('Input');
        Load::helper('input');

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
        $this->autoloadLibraries($autoload);
        $this->autoloadModels($autoload);
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
     * Autoload Libraries
     *
     * @param $autoload
     */
    private function autoloadLibraries($autoload)
    {
        if (isset($autoload['libraries'])) {
            if ($autoload['libraries'] != null) {
                foreach ($autoload['libraries'] as $library) {
                    Load::library($library);
                }
            }
        }
    }

    /**
     * Autoload Models
     *
     * @param $autoload
     */
    private function autoloadModels($autoload)
    {
        if (isset($autoload['models'])) {
            if ($autoload['models'] != null) {
                foreach ($autoload['models'] as $model) {
                    Load::model($model);
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
