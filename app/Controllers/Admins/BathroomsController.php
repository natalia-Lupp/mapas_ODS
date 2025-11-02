<?php

namespace App\Controllers\Admins;

use App\Models\Bathroom;
use App\Models\Building;
use Core\Debug\Debugger;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class BathroomsController extends Controller
{
    protected string $layout = 'admin/application';

    public function index(Request $request): void
    {
        $building = Building::findById($request->getParam('building_id'));
        $page = $request->getParam('page', 1);
        $per_page = $request->getParam('per_page', 10);
        $paginator = null;
        if (isset($building)) {
            $building_id = $building->id;
            $paginator = $building->getBathroomPaginator($page, $per_page, "/admin/buildings/$building_id/bathrooms");
        } else {
            $paginator = Bathroom::paginate(page: $page, per_page: $per_page, route: 'admin.bathrooms.index');
        }
        $title = 'Todos Banheiros - Mapas ODS';
        $this->render('admin/bathrooms/index', compact('title', 'building', 'paginator'));
    }

    public function new(Request $request): void
    {
        $title = 'Adicionar Banheiros - Mapas ODS';
        $buildings = Building::all();
        $buildingId = $request->getParam('building_id', 0);
        $this->render('admin/bathrooms/new', compact('title', 'buildings', 'buildingId'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $bathroomParams = $params['bathroom'] ?? [];
        $image = $_FILES['image'] ?? [];
        $bathroom = new Bathroom([
          'floor' => $bathroomParams['floor'] ?? -1,
          'building_id' => $bathroomParams['building_id'] ?? 0,
          'image_name' => $image['name'] ?? '',
          'image_type' => $image['type'] ?? '',
          'image_size' => $image['size'] ?? 0,
          'image_temp_name' => $image['tmp_name'] ?? ''
        ]);

        if ($bathroom->save()) {
            FlashMessage::success('Banheiro registrado com sucesso!!');
            $this->redirectTo(route('admin.bathrooms.index', [
              'building_id' => $bathroom->id
            ]));
        } else {
            Debugger::dd($bathroom->getErrors());
            FlashMessage::danger('Por favor verifique novamente os dados enviados! Cadastro não realizado.');
            $this->redirectTo(route('admin.bathrooms.new'));
        }
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();

        $building = Bathroom::findById($params['id']);
        $building->destroy();
        FlashMessage::success('Banheiro removido com sucesso!');
        $this->redirectTo(route('admin.buildings.index'));
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
