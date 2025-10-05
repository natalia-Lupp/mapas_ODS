<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $title = 'Home Page';
        $this->render('home/index', compact('title'));
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
}
