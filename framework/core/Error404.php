<?php

class Error404 extends Controller
{

    public function index()
    {
        Blade::render('404', $this->data);
    }

}
