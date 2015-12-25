<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/17/15
 * Time: 11:50 PM
 */

class Controller {

    var $data;
    var $instance;

    public function __construct(){
        $this->instance = get_instance();
    }

}
