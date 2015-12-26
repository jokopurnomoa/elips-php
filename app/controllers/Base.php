<?php
/**
 * ROOT CONTROLLER
 *
 *
 */

class Base extends Controller {

    public function __construct(){
        Loader::loadLibrary('Blade');
    }

}
