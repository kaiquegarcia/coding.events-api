<?php

use Laravel\Lumen\Routing\Router;

return static function (Router $router): void
{
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });
};
