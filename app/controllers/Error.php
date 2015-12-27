<?php

class Error extends Base {

    public function index(){
        Blade::render('404', $this->data);
    }

}
