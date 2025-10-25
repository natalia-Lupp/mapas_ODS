<?php

use Core\Constants\Constants;
use Core\Debug\Debugger;
use Core\Router\Router;

if (!function_exists('dd')) {
    function dd(): void
    {
        Debugger::dd(...func_get_args());
    }
}

if (!function_exists('route')) {
    /**
     * @param string $name
     * @param mixed[] $params
     * @return string
     */
    function route(string $name, $params = []): string
    {
        return Router::getInstance()->getRoutePathByName($name, $params);
    }
}

if (!function_exists('render')) {
    /**
     * @param string $partial
     * @return string
     */
    function render(string $partial): string
    {
        return Constants::rootPath()->join('app/views/')->join($partial . "phtml");
    }
}
