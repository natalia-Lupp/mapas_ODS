<?php

namespace Database\Populate;

use App\Models\User;

class UserPopulate
{
    public static function populate(): void
    {

        $numberOfResgisters = 4;
        $basePassword = 'SenhaSenha';

        for ($i = 1; $i <= $numberOfResgisters; $i++) {
          $password = $basePassword . strval($i);
          $user = new User([
              'password' =>  $password,
              'password_confirmation' => $password,
              'email' => "user$i@email.com",
              'name' => "user$i"
          ]);
          $user->save();
        }

    }
}
