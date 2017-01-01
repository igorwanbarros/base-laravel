<?php

namespace Igorwanbarros\BaseLaravel\Http\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        return view('base-laravel::home.index');
    }
}