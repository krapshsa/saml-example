<?php

namespace App;

class Router
{
    private $routes = [];

    public function add($url, $action) {
        $this->routes[$url] = $action;
    }

    public function dispatch($url) {
        if (array_key_exists($url, $this->routes)) {
            return call_user_func($this->routes[$url]);
        }
        echo "No route defined for this URL";
    }
}