<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use App\Controllers\AuthController;
use Lib\Authentication\Auth;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->isAuthenticated();

    }

    public function dashboardAdmin(): void
    {
        $title = 'Dashboard Admin - Mapas ODS';
        $this->render('home/dashboard.admin', compact('title'));
    }

    public function dashboardClient(): void
    {
        $title = 'Dashboard Client - Mapas ODS';
        $this->render('home/dashboard.client', compact('title'));
    }

    public function isAuthenticated(): void
    {
        if (Auth::check()) {
            $authController = new AuthController();
            $authController->redirectByRole();
            return;
        } else {
            $this->redirectTo(route('login'));
            return;
        }
    }
}
