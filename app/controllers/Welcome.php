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

    public function test(){
        $this->data['member_list'] = Cache::get('member_list_cache');

        if($this->data['member_list'] == null){
            echo 'NO CACHE';
            $sql = "SELECT *, (SELECT COUNT(member_id) FROM gxa_members) AS total_member FROM gxa_members LIMIT 200";
            $this->data['member_list'] = Database::getAllQuery($sql);
            Cache::store('member_list_cache', $this->data['member_list']);
        }

        print_r($this->data['member_list']);
    }

}
