<?php

namespace App\Controllers\Admin;

use Core\Http\Controllers\Controller;
use App\Models\Building;
use Core\Http\Request;

class HomeController extends Controller
{
    protected string $layout = 'admin/application';

    public function dashboard(Request $req): void
    {
        $title = 'Dashboard Admin - Mapas ODS';
        $this->render('admin/home/dashboard', compact('title'));
    }
}
