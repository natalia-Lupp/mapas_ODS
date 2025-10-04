<?php

namespace Config;

class App
{
    public static array $middlewareAliases = [
      'auth'   => \App\Middleware\Authenticate::class,
      'client' => \App\Middleware\ClientFilter::class,
      'admin' => \App\Middleware\AdminFilter::class
    ];
}
