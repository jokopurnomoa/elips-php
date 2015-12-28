<?php
/**
 * Base Controller
 *
 *
 */

class Controller {

    var $data;
    var $instance;

    /**
     * Controller Constructor
     */
    public function __construct(){
        $this->instance = get_instance();
    }

}
