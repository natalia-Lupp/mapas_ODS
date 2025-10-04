<?php

namespace Core\Http\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

abstract class RuleMiddleware implements Middleware
{
    protected static string $rule = '';
    protected static string $message = '';
    protected static string $redirect = 'root';
    public function handle(Request $request): void
    {
        $user = Auth::user();
        if (!isset($user) || !$user->hasRule(static::$rule)) {
            FlashMessage::danger(static::$message);
            $this->redirectTo(route(static::$redirect));
        }
    }

    private function redirectTo(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}
