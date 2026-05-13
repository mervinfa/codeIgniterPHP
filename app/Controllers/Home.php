<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('v_home');
    }

     public function contact(): string
    {
       return view('v_contact');
    }
}
