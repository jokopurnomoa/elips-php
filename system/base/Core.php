<?php
/**
 * CORE
 *
 * Loading base system
 *
 */

class Core {

    public function run(){
        require SYSTEM_PATH . 'helpers/error.php';
        require SYSTEM_PATH . 'libraries/URI.php';
        require SYSTEM_PATH . 'libraries/Benchmark.php';
        require SYSTEM_PATH . 'libraries/Encryption.php';

        require 'Loader.php';
        require 'Route.php';
        require 'Model.php';
        require 'Controller.php';

        Benchmark::startTime('execution_time');
        Encryption::init();
        $this->handleAutoload();
        Route::run();
    }

    private function handleAutoload(){
        $autoload = null;
        if(file_exists(APP_PATH . 'config/autoload.php')){
            require(APP_PATH . 'config/autoload.php');
        } else {
            errorDump('File \'' . APP_PATH . 'config/autoload.php\' not found!');die();
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
                    if(file_exists(APP_PATH . 'lang/' . $language . '/' . $language . '_lang.php')){
                        $lang = null;
                        require(APP_PATH . 'lang/' . $language . '/' . $language . '_lang.php');
                        global $___lang;
                        $___lang = $lang;
                    } else {
                        errorDump('File \'' . APP_PATH . 'lang/' . $language . '/' . $language . '_lang.php\' not found!');die();
                    }
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
