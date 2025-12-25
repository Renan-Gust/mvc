<?php

namespace core;

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

        if (!isset($routes[$method][$url])) {
            return $this->render('404');
        }

        foreach ($routes[$method] as $route => $callback) {
            // Identifica os argumentos e substitui por regex
            $pattern = preg_replace('(\{[a-zA-Z0-9]{1,}\})', '([a-zA-Z0-9-_]{1,})', $route);

            // Faz o match da URL
            if (preg_match('#^(' . $pattern . ')*$#i', $url, $matches) === 1) {
                array_shift($matches);
                array_shift($matches);

                // Pega todos os argumentos para associar
                $items = [];
                if (preg_match_all('(\{[a-zA-Z0-9-_]{1,}\})', $route, $m)) {
                    $items = preg_replace('(\{|\})', '', $m[0]);
                }

                // Faz a associação
                $args = [];
                foreach ($matches as $key => $match) {
                    $args[$items[$key]] = $match;
                }

                // Seta o controller/action
                $callbackSplit = explode('@', $callback);
                $controller = $callbackSplit[0];
                if (isset($callbackSplit[1])) {
                    $action = $callbackSplit[1];
                }

                break;
            }
        }

        $controller = "\src\controllers\\$controller";
        $definedController = new $controller();
        $definedController->$action($args);
    }
}
