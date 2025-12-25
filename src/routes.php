<?php

use core\router\Router;

$router = new Router();

$router->get('/', 'HomeController@index')->alias('aaa');
$router->get('/renan', 'HomeController@index')->alias('renan');
$router->post('/renan/post', 'HomeController@index')->alias('renanpost');
