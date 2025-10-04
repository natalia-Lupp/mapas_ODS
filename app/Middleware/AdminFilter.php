<?php

namespace App\Middleware;

use Core\Http\Middleware\RuleMiddleware;

class AdminFilter extends RuleMiddleware
{
    protected static string $rule = 'admin';
    protected static string $message = 'área restrita a administradores.';
    protected static string $redirect = 'login.view';
}
