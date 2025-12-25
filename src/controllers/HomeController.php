<?php

namespace src\controllers;

use core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $name = $this->getBaseUrl();

        $this->render('home', [
            "name" => $name
        ]);
    }

    public function index2()
    {
        $name2 = 'b';

        $this->render('home', [
            "name" => $name2
        ]);
    }
}
