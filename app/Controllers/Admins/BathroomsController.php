<?php

namespace App\Controllers\Admins;

use App\Models\Bathroom;
use App\Models\Building;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class BathroomsController extends Controller
{
    protected string $layout = 'admin/application';

    public function index(Request $request): void
    {
        $building = Building::findById($request->getParam('building_id'));

        $title = 'Todos Banheiros - Mapas ODS';
        $this->render('admin/bathrooms/index', compact('title', 'building'));
    }

    public function new(): void
    {
        $title = 'Adicionar Banheiros - Mapas ODS';
        $this->render('admin/bathrooms/new', compact('title'));
    }
}

/*
  public function bathroom(Request $request, int $id)
   $building = Building::findById($id);
        if (!$building) {
            FlashMessage::danger('Prédio não encontrado!');
            $this->render('admin/buildings/bathroom', compact('building', 'title'));
        }
*/
