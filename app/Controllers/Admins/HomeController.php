<?php

namespace App\Controllers\Admins;

use App\Models\Bathroom;
use App\Models\Building;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class HomeController extends Controller
{
    protected string $layout = 'admin/application';

    public function dashboard(Request $req): void
    {
        $title = 'Dashboard Admin - Mapas ODS';

        $totalBuildings = Building::count();
        $totalBathrooms = Bathroom::count();

        // Media de banherios por predios 
        $averageBathroomsPerBuilding = $totalBuildings > 0  // comentario pra eu lembrar que aqui é pra não dividir por zero
            ? round($totalBathrooms / $totalBuildings)
            : 0;

        $this->render('admin/home/dashboard', compact(
            'title',
            'totalBuildings',
            'totalBathrooms',
            'averageBathroomsPerBuilding'
        ));
    }
}
