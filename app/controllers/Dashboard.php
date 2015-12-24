<?php

class Dashboard extends Base {

    public function index(){
        require APP_PATH . 'external/example.php';

        $this->data['execution_time'] = Benchmark::getTime('execution_time');
        Blade::render('dashboard', $this->data);
    }

    public function test(){
        echo Session::get('name');
    }

}
