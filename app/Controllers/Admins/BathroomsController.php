<?php

namespace App\Controllers\Admins;

use App\Models\Bathroom;
use App\Models\Building;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class BathroomsController extends Controller
{
    protected string $layout = 'admin/application';

    public function index(Request $request): void
    {
        $buildingId = intval($request->getParam('building_id'));
        $building = null;
        $title = 'Todos Banheiros - Mapas ODS';

        if ($buildingId > 0) {
            $building = Building::findById($buildingId);
            if ($building) {
                $title = "Banheiros {$building->name}";
            }
        }

        $page = $request->getParam('page', 1);
        $per_page = $request->getParam('per_page', 10);

        if ($building) {
            $paginator = $building->getBathroomPaginator($page, $per_page, "/admin/buildings/{$building->id}/bathrooms");
        } else {
            $paginator = Bathroom::paginate(page: $page, per_page: $per_page, route: 'admin.bathrooms.index');
        }

        $this->render('admin/bathrooms/index', compact('title', 'building', 'paginator'));
    }

    public function new(Request $request): void
    {
        $buildingId = intval($request->getParam('building_id', 0));
        $building = null;

        if ($buildingId > 0) {
            $building = Building::findById($buildingId);
            if (!$building) {
                FlashMessage::danger('Prédio não encontrado!');
                $this->redirectTo(route('admin.buildings.index'));
                return;
            }
        } else {
            FlashMessage::danger('Prédio não informado!');
            $this->redirectTo(route('admin.buildings.index'));
            return;
        }

        $title = "Cadastrar Banheiros - {$building->name}";
        $this->render('admin/bathrooms/new', compact('title', 'building'));
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
            $this->redirectTo(route('admin.buildings.bathrooms.index', [
                'building_id' => $bathroom->building_id
            ]));
        } else {
            FlashMessage::danger('Por favor verifique novamente os dados enviados! Cadastro não realizado.');
            $this->redirectTo(route('admin.bathrooms.new'));
        }
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();

        $bathroom = Bathroom::findById($params['id']);
        $bathroom->destroy();
        FlashMessage::success('Banheiro removido com sucesso!');
        $this->redirectTo(route('admin.buildings.bathrooms.index', [
          'building_id' => $bathroom->building_id
        ]));
    }

    public function edit(Request $request): void
    {
        $id = $request->getParam('id', 0);
        if ($id > 0) {
            $bathroom = Bathroom::findById($id);
            if (!isset($bathroom)) {
                FlashMessage::danger('Banheiro não encontrado!');
                $this->redirectTo(route('admin.buildings.index'));
            }
            $title = 'Editar Banheiros - Mapas ODS';
            $buildings = Building::all();
            $buildingId = $bathroom->building_id;
            $this->render('admin/bathrooms/edit', compact('title', 'buildings', 'buildingId', 'bathroom'));
        } else {
            FlashMessage::danger('Banheiro não encontrado!');
            $this->redirectTo(route('admin.buildings.index'));
        }
    }

    public function update(Request $request): void
    {
        $params = $request->getParams();
        $bathroom = Bathroom::findById(intval($params['id']));
        $image = $_FILES['image'] ?? [];
        $bathroomParams = $request->getParam('bathroom', []);
        $oldId = $bathroom->id;
        $bathroom->floor = $bathroomParams['floor'] ?? -1;
        $bathroom->building_id = $bathroomParams['building_id'] ?? 0;
        $bathroom->image_name = $image['name'] ?? '';
        $bathroom->image_type = $image['type'] ?? '';
        $bathroom->image_size = $image['size'] ?? '';
        $bathroom->image_temp_name = $image['tmp_name'] ?? '';
        if ($bathroom->save()) {
            FlashMessage::success('Banheiro atualizado com sucesso!!');
            $this->redirectTo(route('admin.buildings.bathrooms.index', [
                'building_id' => $oldId
            ]));
        } else {
            FlashMessage::danger('Por favor verifique novamente os dados enviados! Cadastro não realizado.');
            $this->redirectTo(route('admin.bathrooms.edit'));
        }
    }
    public function show(Request $request): void
    {
        $params = $request->getParams();

        $bathroom = Bathroom::findById($params['id']);

        $title = "Banheiro";
        $this->render('admin/bathrooms/show', compact('bathroom', 'title'));
    }

    public function destroyImage(Request $request): void
    {
        $params = $request->getParams();
        $bathroom = Bathroom::findById(intval($params['id']));
        $bathroom->deleteImage();
        FlashMessage::success('Im agem do banheiro removido com sucesso!');
        $this->redirectTo(route('admin.bathrooms.edit', ['id' => $bathroom->id]));
    }
}
