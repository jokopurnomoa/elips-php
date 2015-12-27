<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

class Welcome extends Base {

    public function index(){

        Blade::render('welcome');

    }

    public function testCache(){
        $member_list = Cache::get('member_list_cache');

        if($member_list == null){
            echo 'NO CACHE';
            $sql = "SELECT *, (SELECT COUNT(member_id) FROM gxa_members) AS total_member FROM gxa_members LIMIT 200";
            $member_list = Database::getAllQuery($sql);
            Cache::store('member_list_cache', $member_list);
        }

        echo '<pre>';
        print_r($member_list);
        echo '</pre>';
    }

    public function testEmail(){
        Loader::loadLibrary('Email');

        Email::smtp(true);
        Email::host('smtp.gmail.com');
        Email::username('email@gmail.com');
        Email::password('secret');
        Email::SMTPSecure('ssl');
        Email::port(465);
        Email::SMTPAuth(true);

        Email::from('email@gmail.com', 'Name');
        Email::to('jokopurnomoa@gmail.com');

        Email::html(true);
        Email::subject('Subject Test');
        Email::message('Message Test');
        Email::send();

        echo Email::getErrorInfo();
    }

}
