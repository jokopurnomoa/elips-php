<?php

class Error extends Controller
{

    public function index()
    {
        Blade::render('404', $this->data);
    }

}
