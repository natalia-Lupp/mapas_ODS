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

    // -------------------------------------------------------------------------
    // READ (Leitura)
    // -------------------------------------------------------------------------

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
            // Consulta de banheiros específica para um prédio.
            $paginator = $building->getBathroomPaginator($page, $per_page, "/admin/buildings/{$building->id}/bathrooms");
        } else {
            // Consulta de todos os banheiros.
            $paginator = Bathroom::paginate(page: $page, per_page: $per_page, route: 'admin.buildings.bathrooms.index');
        }

        $this->render('admin/bathrooms/index', compact('title', 'building', 'paginator'));
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();

        $bathroom = Bathroom::findById($params['id']);

        if (!$bathroom) {
            FlashMessage::danger('Banheiro não encontrado!');
            $this->redirectTo(route('admin.buildings.index'));
            return;
        }

        $building = Building::findById($bathroom->building_id);

        // Ajustando para que o andar 0 seja o 1º Andar, etc.
        $floor = $bathroom->floor + 1;

        $titleNome = "Informações do Banheiro do Andar {$floor} do {$building->name}";
        $title = "Banheiro";

        $this->render('admin/bathrooms/show', compact('bathroom', 'title', 'titleNome', 'building'));
    }

    // -------------------------------------------------------------------------
    // CREATE (Criação)
    // -------------------------------------------------------------------------

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
        $bathroom = new Bathroom([
            'floor' => $bathroomParams['floor'] ?? -1,
            'building_id' => $bathroomParams['building_id'] ?? 0
        ]);

        if ($bathroom->save()) {
            FlashMessage::success('Banheiro registrado com sucesso!!');
            $this->redirectTo(route('admin.buildings.bathrooms.index', [
                'building_id' => $bathroom->building_id
            ]));
        } else {
            FlashMessage::danger('Por favor verifique novamente os dados enviados! Cadastro não realizado.');
            $this->redirectTo(route('admin.buildings.bathrooms.new', [
                'building_id' => $bathroom->building_id
            ]));
        }
    }

    // -------------------------------------------------------------------------
    // UPDATE (Edição)
    // -------------------------------------------------------------------------

    public function edit(Request $request): void
    {
        $id = $request->getParam('id', 0);

        if ($id <= 0) {
            FlashMessage::danger('Banheiro não encontrado!');
            $this->redirectTo(route('admin.buildings.index'));
            return;
        }

        $bathroom = Bathroom::findById($id);

        if (!$bathroom) {
            FlashMessage::danger('Banheiro não encontrado!');
            $this->redirectTo(route('admin.buildings.index'));
            return;
        }

        $building = Building::findById($bathroom->building_id);
        if (!$building) {
            FlashMessage::danger('Prédio vinculado não encontrado!');
            $this->redirectTo(route('admin.buildings.index'));
            return;
        }

        $title = "Editar Banheiro - {$building->name}";

        $this->render('admin/bathrooms/edit', compact(
            'title',
            'building',
            'bathroom'
        ));
    }


    public function update(Request $request): void
    {
        $params = $request->getParams();
        $bathroomParams = $request->getParam('bathroom', []);

        $bathroom = Bathroom::findById(intval($params['id']));

        if (!$bathroom) {
            FlashMessage::danger('Banheiro não encontrado!');
            $this->redirectTo(route('admin.buildings.index'));
            return;
        }

        $oldBuildingId = $bathroom->building_id;

        $bathroom->floor = $bathroomParams['floor'] ?? -1;
        $bathroom->building_id = $bathroomParams['building_id'] ?? 0;

        if ($bathroom->save()) {
            FlashMessage::success('Banheiro atualizado com sucesso!!');
            $this->redirectTo(route('admin.buildings.bathrooms.index', [
                'building_id' => $oldBuildingId // Redireciona para o prédio original.
            ]));
        } else {
            FlashMessage::danger('Por favor verifique novamente os dados enviados! Atualização não realizada.');
            $this->redirectTo(route('admin.buildings.bathrooms.edit', [
                'id' => $bathroom->id
            ]));
        }
    }

    // -------------------------------------------------------------------------
    // DELETE (Exclusão)
    // -------------------------------------------------------------------------

    public function destroy(Request $request): void
    {
        $params = $request->getParams();

        $bathroom = Bathroom::findById($params['id']);

        if (!$bathroom) {
            FlashMessage::danger('Banheiro não encontrado!');
            $this->redirectBack();
            return;
        }

        $buildingId = $bathroom->building_id;

        // 🔥 Buscar imagens SEM get(), porque where() já retorna um array!
        $images = \App\Models\BathroomImage::where(['bathroom_id' => $bathroom->id]);

        foreach ($images as $image) {
            $image->imageService()->deleteImage();
            $image->destroy();
        }

        // Agora sim, sem FK pendente
        $bathroom->destroy();

        FlashMessage::success('Banheiro removido com sucesso!');

        $this->redirectTo(route('admin.buildings.bathrooms.index', [
            'building_id' => $buildingId
        ]));
    }
}
