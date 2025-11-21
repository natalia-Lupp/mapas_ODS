<?php

namespace App\Controllers\Admins;

use App\Models\Bathroom;
use App\Models\Building;
use App\Models\ImageModel;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;


//controller temporaria para subir  a visualização das views e rotas, assim como já
// padronizar o modelo que a views devem receber os dados
class ItemsBathroomController extends Controller
{
    protected string $layout = 'admin/application';

    public function index(Request $request): void
    {
        $buildingId = $request->getParam('building_id');
        $bathroomId = $request->getParam('bathroom_id');

        $bathroom = Bathroom::findById($bathroomId);

        $title = "Itens do Banheiro {$bathroom->floor}º Andar";

        // se não houver itens, vira array vazio
        $items = $bathroom->items ?? [];

        $this->render(
            'admin/items/index',
            compact('bathroom', 'buildingId', 'title', 'items')
        );
    }


    public function show(Request $request): void
    {
        $item = (object)[
            'id' => $request->getParam('id'),
            'type_name' => 'Torneira',
            'label' => 'Torneira Lado Esquerdo'
        ];

        $this->render('admin/items/show', [
            'item' => $item,
            'title' => 'Detalhes do Item'
        ]);
    }

    public function new(Request $request): void
    {
        $this->render('admin/items/new', [
            'title' => 'Novo Item'
        ]);
    }

    public function create(Request $request): void
    {
        $this->redirectTo(route('admin.buildings.bathrooms.items.index', [
            'building_id' => $request->getParam('building_id'),
            'bathroom_id' => $request->getParam('bathroom_id'),
        ]));
    }

    public function edit(Request $request): void
    {
        $item = (object)[
            'id' => $request->getParam('id'),
            'type_name' => 'Vaso Sanitário',
            'label' => 'Vaso Próximo à Janela'
        ];

        $this->render('admin/items/edit', [
            'item' => $item,
            'title' => 'Editar Item'
        ]);
    }

    public function update(Request $request): void
    {
        $this->redirectTo(route('admin.buildings.bathrooms.items.index', [
            'building_id' => $request->getParam('building_id'),
            'bathroom_id' => $request->getParam('bathroom_id'),
        ]));
    }

    public function destroy(Request $request): void
    {
        $this->redirectTo(route('admin.buildings.bathrooms.items.index', [
            'building_id' => $request->getParam('building_id'),
            'bathroom_id' => $request->getParam('bathroom_id'),
        ]));
    }
}
