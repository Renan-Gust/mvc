<?php

namespace core\router;

use core\Controller;

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type');

class RouterBase extends Controller
{
    public function run($routes)
    {
        $method = Request::getMethod();
        $url = Request::getUrl();

        // Define os itens padrão
        $controller = 'ErrorController';
        $action = 'index';
        $args = [];

        if (!isset($routes[$method])) {
            return $this->render('404');
        }

        foreach ($routes[$method] as $route) {
            // Identifica os argumentos e substitui por regex
            $pattern = preg_replace('(\{[a-zA-Z0-9]{1,}\})', '([a-zA-Z0-9-_]{1,})', $route['endpoint']);

            // Faz o match da URL
            if (preg_match('#^(' . $pattern . ')*$#i', $url, $matches) === 1) {
                array_shift($matches);
                array_shift($matches);

                $args = Request::getArgs($route['endpoint'], $matches);

                // Seta o controller/action
                $callbackSplit = explode('@', $route['trigger']);
                $controller = $callbackSplit[0];
                if (isset($callbackSplit[1])) {
                    $action = $callbackSplit[1];
                }

                break;
            }
        }

        echo "<pre>";
        print_r($routes);
        echo "<pre>";

        $controller = "\src\controllers\\$controller";
        $definedController = new $controller();

        $definedController->router = $this;
        $definedController->$action($args);
    }
}
