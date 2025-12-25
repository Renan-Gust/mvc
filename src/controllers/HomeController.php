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
}
