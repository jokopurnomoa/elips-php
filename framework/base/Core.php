<?php
/**
 * CORE
 *
 * Loading base system
 *
 */

class Core {

    public function run(){
        require FW_PATH . 'helpers/error.php';
        require FW_PATH . 'libraries/Benchmark.php';

        Benchmark::startTime('execution_time');

        require 'Loader.php';
        require 'Route.php';
        require 'Model.php';
        require 'Controller.php';

        Loader::loadLibrary('Encryption');
        Loader::loadLibrary('Cache');
        Loader::loadLibrary('Blade');
        Loader::loadLibrary('URI');

        $this->handleAutoload();
        Route::run();
    }

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

    private function autoloadLanguage($autoload){
        if(isset($autoload['languages'])){
            if($autoload['languages'] != null){
                foreach($autoload['languages'] as $language){
                    Loader::loadLanguage($language);
                }
            }
        }
    }

    private function autoloadLibraries($autoload){
        if(isset($autoload['libraries'])){
            if($autoload['libraries'] != null){
                foreach($autoload['libraries'] as $library){
                    Loader::loadLibrary($library);
                }
            }
        }
    }

    private function autoloadModels($autoload){
        if(isset($autoload['models'])){
            if($autoload['models'] != null){
                foreach($autoload['models'] as $model){
                    Loader::loadModel($model);
                }
            }
        }
    }

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
