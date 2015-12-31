<?php
/**
 * Core Library
 *
 * Loading base system
 *
 */

class Core {

    /**
     * @var
     */
    public $data;

    /**
     * Run Core
     */
    public function run(){
        require FW_PATH . 'libraries/Benchmark.php';
        require FW_PATH . 'helpers/file.php';

        Benchmark::startTime('execution_time');

        require FW_PATH . 'base/Loader.php';
        require FW_PATH . 'base/Route.php';
        require FW_PATH . 'base/Model.php';
        require FW_PATH . 'base/Controller.php';

        Loader::loadLibrary('URI');
        Loader::loadLibrary('Encryption');
        Loader::loadLibrary('Security');
        Loader::loadLibrary('Cache');
        Loader::loadLibrary('Blade');
        Loader::loadLibrary('Input');
        Loader::loadHelper('input');

        $this->handleAutoload();
        Route::run();
    }

    /**
     * Handle Autoload
     */
    private function handleAutoload(){
        $autoload = null;
        if(file_exists(APP_PATH . 'config/autoload.php')){
            require(APP_PATH . 'config/autoload.php');
        } elseif(APP_ENV === 'development') {
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
    private function autoloadLanguage($autoload){
        if(isset($autoload['languages'])){
            if($autoload['languages'] != null){
                foreach($autoload['languages'] as $language){
                    Loader::loadLanguage($language);
                }
            }
        }
    }

    /**
     * Autoload Libraries
     *
     * @param $autoload
     */
    private function autoloadLibraries($autoload){
        if(isset($autoload['libraries'])){
            if($autoload['libraries'] != null){
                foreach($autoload['libraries'] as $library){
                    Loader::loadLibrary($library);
                }
            }
        }
    }

    /**
     * Autoload Models
     *
     * @param $autoload
     */
    private function autoloadModels($autoload){
        if(isset($autoload['models'])){
            if($autoload['models'] != null){
                foreach($autoload['models'] as $model){
                    Loader::loadModel($model);
                }
            }
        }
    }

    /**
     * Autoload Helpers
     *
     * @param $autoload
     */
    private function autoloadHelpers($autoload){
        if(isset($autoload['helpers'])){
            if($autoload['helpers'] != null){
                foreach($autoload['helpers'] as $helper){
                    Loader::loadHelper($helper);
                }
            }
        }
    }
}
