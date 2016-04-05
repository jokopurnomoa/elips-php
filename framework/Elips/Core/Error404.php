<?php

namespace Elips\Core;

use Elips\Libraries\Blade;

class Error404 extends Controller
{

    public function index()
    {
        Blade::render('404', $this->data);
    }

}
