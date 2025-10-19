<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use App\Models\Building;
use Core\Http\Request;
use Lib\FlashMessage;

class BuildingController extends Controller
{
    public function index(Request $request): void
    {
        $paginator = Building::paginate(page: $request->getParam('page', 1), route: 'buildings.index');
        $buildings = $paginator->registers();

        $title = 'Prédios Registrados';

        if ($request->acceptJson()) {
            $this->renderJson('buildings/index', compact('paginator', 'buildings', 'title'));
        } else {
            $this->render('buildings/index', compact('paginator', 'buildings', 'title'));
        }
    }

    public function new(): void
    {
        $building = new Building();

        $title = 'Novo Prédio';
        $this->render('buildings/new', compact('building', 'title'));
    }

    public function create(Request $request): void
    {
        $params = $request->getParams();
        $building = new Building($params['building']);

        if ($building->save()) {
            FlashMessage::success('Prédio registrado com sucesso!!');
            $this->redirectTo(route('buildings.index'));
        } else {
            FlashMessage::danger('Por favor verifique novamente os dados enviados! Cadastro não realizado.');
            $title = 'Novo Prédio';
            $this->render('buildings/new', compact('building', 'title'));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();

        $building = Building::findById($params['id']);

        $title = "Prédio: {$building->name}";
        $this->render('buildings/show', compact('building', 'title'));
    }

    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $building = Building::findById($params['id']);

        $title = "Editar Prédio: {$building->name}";
        $this->render('buildings/edit', compact('building', 'title'));
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
            $this->redirectTo(route('buildings.index'));
        } else {
            FlashMessage::danger('Por favor verifique novamente os dados enviados! Cadastro não foi editado.');
            $title = "Editar Prédio: {$building->name}";
            $this->render('buildings/edit', compact('building', 'title'));
        }
    }

    public function destroy(Request $request): void
    {
        $params = $request->getParams();

        $building = Building::findById($params['id']);
        $building->destroy();

        FlashMessage::success('Prédio removido com sucesso!');
        $this->redirectTo(route('buildings.index'));
    }
    /*
    public function deleteAll(): void
    {
        Building::deleteAll();
        FlashMessage::success('Todos os prédios foram removidos!');
        $this->renderJson(['success' => true]);
    }*/
}
