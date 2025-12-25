<?php

namespace core;

class Request
{

    public static function getUrl()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function getArgs($route, $matches)
    {
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

        return $args;
    }
}
