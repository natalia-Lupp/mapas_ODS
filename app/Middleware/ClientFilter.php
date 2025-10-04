<?php

namespace App\Middleware;

use Core\Http\Middleware\RuleMiddleware;

class ClientFilter extends RuleMiddleware
{
    protected static string $rule = 'client';
    protected static string $message = 'torne-se nosso cliente para acessar nosso servisso.';
    protected static string $redirect = 'login.view';
}
