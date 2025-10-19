<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use App\Controllers\AuthController;
use App\Models\Building;
use Core\Http\Request;
use Lib\Authentication\Auth;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->isAuthenticated();
    }

    public function dashboardAdmin(Request $req): void
    {
        $title = 'Dashboard Admin - Mapas ODS';
        $page = $req->getParam('page', 1);
        $paginator = Building::paginate(page:$page, route:'dashboard.admin');
        $this->render('buildings/dashboard.admin', compact('title', 'paginator'));
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
