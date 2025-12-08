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

    // logica temporariamente não utilizada devido a mudança de requisitos
    /*
    public function destroy(Request $request): void
    {
        $buildingId = intval($request->getParam('building_id'));
        $bathroomId = intval($request->getParam('bathroom_id'));
        $itemId = intval($request->getParam('id'));

        // Busca o item
        $item = \App\Models\BathroomItem::findById($itemId);

        if (!$item) {
            FlashMessage::danger('Item não encontrado.');
            $this->redirectBack();
            return;
        }

        // Confere se o item pertence ao banheiro correto
        if ($item->bathroom_id != $bathroomId) {
            FlashMessage::danger('Este item não pertence ao banheiro informado.');
            $this->redirectBack();
            return;
        }

        // Remove o item
        $item->destroy();

        FlashMessage::success('Item removido com sucesso!');

        $this->redirectTo(route('admin.buildings.bathrooms.items.index', [
            'building_id' => $buildingId,
            'bathroom_id' => $bathroomId
        ]));
    } */
}
