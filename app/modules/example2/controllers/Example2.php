<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

class Example2 extends Base {

    public function index(){

        Loader::loadLibrary('Database');
        Loader::loadModel('example/ExampleModel');

        $this->data['member_list'] = ExampleModel::getAll(null, null, 10);
        Blade::render('example/example', $this->data);

    }

}
