<?php

namespace src\controllers;

use core\Controller;

class HomeController extends Controller
{
    public function index(){
        $name = "Renan";

        $this->render('home', [
            "name" => $name
        ]);
    }
}
