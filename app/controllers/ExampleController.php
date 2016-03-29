<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

namespace App\Controllers;

use Elips\Libraries\Blade;

class ExampleController
{

    public function index()
    {
        Blade::render('example');
    }

}
