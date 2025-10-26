<?php

namespace App\Controllers\Admins;

use Core\Http\Controllers\Controller;
use App\Models\Building;
use Core\Exceptions\ReferentialIntegrityException;
use Core\Http\Request;
use Lib\FlashMessage;

class BuildingsController extends Controller
{
    protected string $layout = 'admin/application';

    public function index(Request $request): void
    {
        $paginator = Building::paginate(page: $request->getParam('page', 1), route: 'admin.buildings.index');
        $buildings = $paginator->registers();

        $title = 'Prédios Registrados';

        $this->render('admin/buildings/index', compact('paginator', 'buildings', 'title'));
    }

    public function new(): void
    {
        $building = new Building();
        $title = 'Novo Prédio';

        $this->render('admin/buildings/new', compact('building', 'title'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $building = new Building($params['building']);

        if ($building->save()) {
            FlashMessage::success('Prédio registrado com sucesso!!');
            $this->redirectTo(route('admin.buildings.index'));
        } else {
            FlashMessage::danger('Por favor verifique novamente os dados enviados! Cadastro não realizado.');
            $title = 'Novo Prédio';
            $this->render('admin/buildings/new', compact('building', 'title'));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();

        $building = Building::findById($params['id']);

        $title = "Prédio: {$building->name}";
        $this->render('admin/buildings/show', compact('building', 'title'));
    }

    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $building = Building::findById($params['id']);

        $title = "Editar Prédio: {$building->name}";
        $this->render('admin/buildings/edit', compact('building', 'title'));
    }

    public function update(Request $request): void
    {
        $id = $request->getParam('id');
        $params = $request->getParam('buildings');

        $building = Building::findById($id);
        Building::findById($id);
        $building->name = $params['name'];
        $building->n_floors = $params['n_floors'];

        if ($building->save()) {
            FlashMessage::success("Predio {$building->name} atualizado com sucesso!");
            $this->redirectTo(route('admin.buildings.index'));
        } else {
            FlashMessage::danger('Por favor verifique novamente os dados enviados! Cadastro não foi editado.');
            $title = "Editar Prédio: {$building->name}";
            $this->render('admin/buildings/edit', compact('building', 'title'));
        }
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();

        $building = Building::findById($params['id']);
        try {
            $building->destroy();
            FlashMessage::success('Prédio removido com sucesso!');
        } catch (ReferentialIntegrityException $ex) {
            FlashMessage::danger($ex->getMessage());
        }
        $this->redirectTo(route('admin.buildings.index'));
    }
}
