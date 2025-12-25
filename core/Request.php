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
}
