<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

class Example2Controller extends BaseController {

    public function index(){

        Load::library('Database');
        Load::model('example/ExampleModel');

        $this->data['member_list'] = ExampleModel::getAll(null, null, 10);
        Blade::render('example/example', $this->data);

    }

}