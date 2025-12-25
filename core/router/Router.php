<?php

namespace core\router;

use \core\router\RouterBase;

class Router extends RouterBase
{
    public $routes;
    protected $namedRoutes = [];

    public function get($endpoint, $trigger)
    {
        $this->routes['get'][] = [
            'endpoint' => $endpoint,
            'trigger' => $trigger,
            'alias' => null
        ];

        return $this;
    }

    public function post($endpoint, $trigger)
    {
        $this->routes['post'][] = [
            'endpoint' => $endpoint,
            'trigger' => $trigger,
            'alias' => null
        ];

        return $this;
    }

    public function put($endpoint, $trigger)
    {
        $this->routes['put'][] = [
            'endpoint' => $endpoint,
            'trigger' => $trigger,
            'alias' => null
        ];

        return $this;
    }

    public function patch($endpoint, $trigger)
    {
        $this->routes['patch'][] = [
            'endpoint' => $endpoint,
            'trigger' => $trigger,
            'alias' => null
        ];

        return $this;
    }

    public function delete($endpoint, $trigger)
    {
        $this->routes['delete'][] = [
            'endpoint' => $endpoint,
            'trigger' => $trigger,
            'alias' => null
        ];

        return $this;
    }

    public function alias($name)
    {
        $lastMethod = array_key_last($this->routes);

        if ($lastMethod && !empty($this->routes[$lastMethod])) {
            $lastIndex = array_key_last($this->routes[$lastMethod]);

            $this->routes[$lastMethod][$lastIndex]['alias'] = $name;
            $this->namedRoutes[$name] = $this->routes[$lastMethod][$lastIndex]['endpoint'];
        }
    }

    public function route($name)
    {
        return $this->namedRoutes[$name] ?? null;
    }

    // Passar args para esse route caso a rota tenha
}
