<?php

namespace App\Controllers\Admins;

use App\Models\Bathroom;
use App\Models\Building;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class ItemsBathroomController extends Controller
{
    protected string $layout = 'admin/application';

    public function index(Request $request): void
    {
        // pega IDs
        $buildingId = intval($request->getParam('building_id'));
        $bathroomId = intval($request->getParam('bathroom_id'));

        // carrega o bathroom
        $bathroom = Bathroom::findById($bathroomId);

        // carrega o building (se existir)
        $building = null;
        if ($buildingId) {
            $building = Building::findById($buildingId);
        }

        // se existir o banheiro, monta o título certo
        $title = $bathroom
            ? "Itens do Banheiro {$bathroom->floor}º Andar"
            : "Itens do Banheiro";

        // itens ou array vazio
        $items = $bathroom->items ?? [];

        // paginação
        $page = $request->getParam('page', 1);
        $per_page = $request->getParam('per_page', 10);

        if ($bathroom && $building) {
            $paginator = $building->getBathroomPaginator(
                $page,
                $per_page,
                "/admin/buildings/{$building->id}/bathrooms/{$bathroom->id}/items"
            );
        } else {
            $paginator = Bathroom::paginate(
                page: $page,
                per_page: $per_page,
                route: 'admin.buildings.bathrooms.items.index'
            );
        }

        $this->render(
            'admin/items/index',
            compact('bathroom', 'buildingId', 'title', 'items', 'paginator', 'building')
        );
    }

    public function new(Request $request): void
    {
        $buildingId = $request->getParam('building_id');
        $bathroomId = $request->getParam('bathroom_id');

        $bathroom = Bathroom::findById($bathroomId);
        $building = Building::findById($buildingId); // <-- ADICIONE

        $title = "Novo Item para o banheiro {$bathroom->floor}º andar";

        $this->render('admin/items/new', compact('title', 'building', 'bathroom'));
    }


    public function create(Request $request): void
    {
        $this->redirectTo(route('admin.buildings.bathrooms.items.index', [
            'building_id' => $request->getParam('building_id'),
            'bathroom_id' => $request->getParam('bathroom_id'),
        ]));
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

    public function edit(Request $request): void
    {
        $buildingId = $request->getParam('building_id');
        $bathroomId = $request->getParam('bathroom_id');

        $building = Building::findById($buildingId);
        $bathroom = Bathroom::findById($bathroomId);
        $items = $bathroom->items()->get();

        $toilets = $items[1] ?? $bathroom->items()->new();
        $taps = $items[0] ?? $bathroom->items()->new();
        // items tem: torneiras e vasos
        $title = "Editar Itens do banheiro {$bathroom->floor}º andar";

        $this->render('admin/items/edit', compact(
            'title',
            'building',
            'bathroom',
            'toilets',
            'taps'
        ));
    }

    public function update(Request $request): void
    {
        $building_id = intval($request->getParam('building_id', '0'));
        $building = Building::findById($building_id);
        if (!isset($building)) {
            FlashMessage::danger('O prédio associado não foi encontrado.');
            $this->redirectTo(route('admin.dashboard'));
        }

        $bathroom_id = intval($request->getParam('bathroom_id', '0'));
        /**
         * @var ?Bathroom $bathroom
         */
        $bathroom = $building->bathrooms()->findById($bathroom_id);
        if (!isset($bathroom)) {
            FlashMessage::danger('O banheiro associado não foi encontrado.');
            $this->redirectTo(route('admin.buildings.bathrooms.index', [
                'building_id' => $building->id
            ]));
        }

        $itemsQantity = $request->getParam('items');
        if ($bathroom->itemService()->update($itemsQantity)) {
            FlashMessage::success('Itens atualizados com sucesso!');
            $this->redirectTo(route('admin.buildings.bathrooms.items.index', [
                'building_id' => $building_id,
                'bathroom_id' => $bathroom_id,
            ]));
        } else {
            FlashMessage::danger('Falha ao atualizar itens!');
            $this->redirectTo(route('admin.buildings.bathrooms.items.index', [
                'building_id' => $building_id,
                'bathroom_id' => $bathroom_id,
            ]));
        }
    }

    public function destroy(Request $request): void
    {
        $this->redirectTo(route('admin.buildings.bathrooms.items.index', [
            'building_id' => $request->getParam('building_id'),
            'bathroom_id' => $request->getParam('bathroom_id'),
        ]));
    }
}
