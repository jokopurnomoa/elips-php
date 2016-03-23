<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

class WelcomeController extends Controller {

    public function index(){

        Blade::render('welcome');

    }

}
