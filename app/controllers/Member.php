<?php

class Member extends Base {

    public function index(){
        // Loader::loadLibrary('Database');
        // Loader::loadLibrary('Session');
        Loader::loadModel('MemberModel');

        $this->data['member_list'] = MemberModel::getAll(null, null, 200);

        Blade::render('member/list', $this->data);
    }

    public function add(){
        Blade::render('member/add', $this->data);
    }

    public function add_member(){
        Loader::loadLibrary('Upload');

        Upload::setConfig(array(
            'upload_path' => '/storage',
            'filename' => 'test_image'
        ));

        if(Upload::doUpload('image')){
            echo 'Success uploading file';
        }

        echo Upload::getError();
    }

}
