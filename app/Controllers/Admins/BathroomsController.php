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


    // INDEX
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
            // Consulta de banheiros específico para um prédio.
            $paginator = $building->getBathroomPaginator($page, $per_page, "/admin/buildings/{$building->id}/bathrooms");
        } else {
            // Consulta de todos os banheiros de todos os predios.
            $paginator = Bathroom::paginate(page: $page, per_page: $per_page, route: 'admin.buildings.bathrooms.index');
        }

        $this->render('admin/bathrooms/index', compact('title', 'building', 'paginator'));
    }

    // SHOW
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

        $floor = $bathroom->floor + 1;

        $titleNome = "Informações do Banheiro do Andar {$floor} do {$building->name}";
        $title = "Banheiro";

        //Carregar itens do banheiro mais itens! eeeeeh! preciso de cafe! ouvir Ado
        $items = $bathroom->itemService()->getItems();
        $taps = $items[0];
        $toilets = $items[1];
        $totalItems = $taps->quantity + $toilets->quantity;

        $this->render('admin/bathrooms/show', compact(
            'bathroom',
            'title',
            'titleNome',
            'building',
            'taps',
            'toilets',
            'totalItems'
        ));
    }

    // CREATE
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
        $itemParams = $params['items'] ?? [];

        $bathroom = new Bathroom([
            'floor' => $bathroomParams['floor'] ?? -1,
            'building_id' => $bathroomParams['building_id'] ?? 0
        ]);

        if ($bathroom->save()) {
            //Criar os itens do banheiro usando o service de itens (controller est vazia)
            $bathroom->itemService()->create([
                'taps' => intval($itemParams['taps'] ?? 0),
                'toilets' => intval($itemParams['toilets'] ?? 0),
            ]);

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

    // EDIT
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

        $title = "Editar Banheiro - {$building->name}";

        // o os itens vindo aqui agora! eeeeeeeh! Mano to com sono!
        $items = $bathroom->itemService()->getItems();
        $taps = $items[0];
        $toilets = $items[1];

        $this->render('admin/bathrooms/edit', compact(
            'title',
            'building',
            'bathroom',
            'taps',
            'toilets'
        ));
    }

    // UPDATE
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

        // Atualizar banheiro
        $bathroom->floor = $bathroomParams['floor'] ?? -1;
        $bathroom->building_id = $bathroomParams['building_id'] ?? 0;

        if ($bathroom->save()) {
            //Atualizar itens aqui tbm! o os itens.
            $bathroom->itemService()->update([
                'taps' => intval($params['items']['taps'] ?? 0),
                'toilets' => intval($params['items']['toilets'] ?? 0),
            ]);

            FlashMessage::success('Banheiro atualizado com sucesso!!');

            $this->redirectTo(route('admin.buildings.bathrooms.index', [
                'building_id' => $oldBuildingId
            ]));
        } else {
            FlashMessage::danger('Por favor verifique novamente os dados enviados! Atualização não realizada.');
            $this->redirectTo(route('admin.buildings.bathrooms.edit', [
                'id' => $bathroom->id
            ]));
        }
    }

    // DELETE
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

        // Buscar todas as imagens do banheiro
        $images = \App\Models\BathroomImage::where(['bathroom_id' => $bathroom->id]);

        // Pega o caminho do diretório lógico (ex: 'bathrooms/1/5')
        // Cria uma instância temporária de BathroomImage para acessar o
        //imageService e o getStoreDir()
        $tempImage = new \App\Models\BathroomImage(['bathroom_id' => $bathroom->id]);
        $imageService = $tempImage->imageService();
        $storeDir = $imageService->getStoreDir();

        foreach ($images as $image) {
            // O deleteImage() apaga o arquivo, remove o registro do DB e
            // TENTA REMOVER a pasta se estiver vazia.
            $image->imageService()->deleteImage();
        }

        // garante que o diretório seja removido se estiver vazio,
        // mesmo que não houvesse imagens registradas no banco.
        $imageService->deleteStoreDirIfEmpty($storeDir);


        // Exclui banheiro
        $bathroom->destroy();

        FlashMessage::success('Banheiro removido com sucesso!');

        $this->redirectTo(route('admin.buildings.bathrooms.index', [
            'building_id' => $buildingId
        ]));
    }
}
