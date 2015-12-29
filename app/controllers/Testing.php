<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

class Testing extends Base {

    public function index(){

    }

    public function cache(){

        Loader::loadLibrary('Database');

        $member_list = Cache::get('member_list_cache');

        if($member_list == null){
            echo 'NO CACHE';
            $sql = "SELECT * FROM gxa_members LIMIT 200";
            $member_list = Database::getAllQuery($sql);
            Cache::store('member_list_cache', $member_list);
        }

        echo '<pre>';
        print_r($member_list);
        echo '</pre>';
    }

    public function database(){
        Loader::loadLibrary('Database');

        Database::beginTransaction();
        Database::insert('test', array('val1' => 'A', 'val2' => 'B'));
        $insert_id = Database::insertId();
        Database::update('test', 'test_id', $insert_id - 1, array('val1' => 'A2', 'val2' => 'B2'));
        Database::delete('test', 'val1', 'A2');
        Database::commit();
    }

    public function database2(){
        Loader::loadLibrary('Database');

        $sql = "SELECT * FROM gxa_members WHERE email = ? AND name LIKE ?";
        $data = Database::getAllQuery($sql, array('jokopurnomoa@gmail.com', '%Elips%'));
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public function email(){
        Loader::loadLibrary('Email');

        Email::smtp(true);
        Email::host('smtp.gmail.com');
        Email::username('email@gmail.com');
        Email::password('secret');
        Email::SMTPSecure('ssl');
        Email::port(465);

        Email::from('email@gmail.com', 'Name');
        Email::to('jokopurnomoa@gmail.com');

        Email::html(true);
        Email::subject('Subject Test');
        Email::message('Message Test');
        Email::send();

        echo Email::getErrorInfo();
    }

    public function session(){
        Loader::loadLibrary('Session');

        Session::set('name', 'Joko');
        echo Session::get('name');
    }

    public function methodGet(){
        echo get_input('name');
    }

    public function memory(){
        echo Benchmark::memoryUsage();
        echo '<br>';

    }

}
