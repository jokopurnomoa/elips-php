<?php
/**
 * ROOT CONTROLLER
 *
 *
 */

class Base extends Controller {

    public function __construct(){
        Loader::loadLibrary('Blade');

        $this->data['tpl_head'] = Blade::render('slices/head', $this->data, true);
        $this->data['tpl_header'] = Blade::render('slices/header', $this->data, true);
        $this->data['tpl_sidebar'] = Blade::render('slices/sidebar', $this->data, true);
        $this->data['tpl_footer'] = Blade::render('slices/footer', $this->data, true);
    }

}
