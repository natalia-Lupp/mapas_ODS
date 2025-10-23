<?php

namespace App\Controllers\Users;

use Core\Http\Controllers\Controller;
use Core\Http\Request;

class HomeController extends Controller
{
    public function dashboard(Request $req): void
    {
        $title = 'Dashboard Client - Mapas ODS';
        $this->render('/users/home/dashboard', compact('title'));
    }
}
