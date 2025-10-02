<?php

namespace Lib\Authentication;

use App\Models\User;

class Auth
{
    public static ?User $user;

    public static function login(User $user): void
    {
        $_SESSION['user']['id'] = $user->id;
        self::$user = $user;
    }

    public static function user(): ?User
    {
        if (isset(User::$user)) {
            return self::$user;
        } elseif (isset($_SESSION['user']['id'])) {
            $id = $_SESSION['user']['id'];
            self::$user = User::findById($id);
            return self::$user;
        }

        return null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']['id']) && self::user() !== null;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']['id']);
    }
}
