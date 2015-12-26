<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

class Testing extends Base {

    public function index(){

        //echo Encryption::encode('Text to encrypt', 'ENCRYPT KEY');
        //echo lang('home');
        //echo base_url();
        //Session::set('name', 'Joko');
        //echo Session::get('name');
        /*
        Loader::loadLibrary('ImageLib');
        ImageLib::setConfig(array(
            'source_image' => './storage/the-lorax-2.jpg',
            'new_image' => './storage/the-lorax-thumb-2.jpg',
            'width' => 1000,
            'height' => 1000,
            'quality' => 80
        ));

        ImageLib::resize();
        */
        //Loader::loadLibrary('Cookies');

        //Cookies::set('example', 'Content');
        Encryption::decode('');

        $this->data['var1'] = 'John';
        $this->data['page'] = '{{$var1}}';
        Blade::render('testing', $this->data);
    }

}
