<?php

namespace App\Controllers\Admins;

use App\Models\Bathroom;
use Core\Http\Controllers\Controller;
use App\Models\Building;
use Core\Http\Request;

class HomeController extends Controller
{
    protected string $layout = 'admin/application';

    public function dashboard(Request $req): void
    {
        $title = 'Dashboard Admin - Mapas ODS';

        $totalBuildings = Building::count();
        $totalBathrooms = Bathroom::count();

        $this->render('admin/home/dashboard', compact('title', 'totalBuildings', 'totalBathrooms'));
    }
}
