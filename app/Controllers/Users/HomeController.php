<?php

namespace App\Controllers\User;

use Core\Http\Controllers\Controller;
use Core\Http\Request;

class HomeController extends Controller
{
    protected string $layout = 'user/application';

    public function dashboard(Request $req): void
    {
        $title = 'Dashboard Client - Mapas ODS';
        $this->render('user/home/dashboard', compact('title'));
    }
}
