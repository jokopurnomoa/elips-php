<?php

class Member extends Base {

    public function index(){
        // Loader::loadLibrary('Database');
        // Loader::loadLibrary('Session');
        // Loader::loadModel('MemberModel');

        $this->data['member_list'] = MemberModel::getAll(10);

        Blade::render('member/list', $this->data);
    }

    public function add(){
        Blade::render('member/add', $this->data);
    }

}
