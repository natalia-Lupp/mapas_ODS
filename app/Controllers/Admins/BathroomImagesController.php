<?php

namespace App\Controllers\Admins;

use App\Models\Bathroom;
use App\Models\Building;
use App\Models\ImageModel;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class BathroomImagesController extends Controller
{
    // CREATE
    public function create(Request $request): void
    {
        //pega os ids na url
        $building_id = $request->getParam('building_id');
        $bathroom_id = $request->getParam('id');

        /**
         * @var Building|null $building
         */
        $building = Building::findById($building_id); //identifica o predio
        if (!$building) {
            FlashMessage::danger('Prédio não encontrado!');
            $this->redirectTo(route('admin.buildings.index'));
            return;
        }
        /**
         * @var Bathroom|null $bathroom
         */
        $bathroom = $building->bathrooms()->findById($bathroom_id); // busca pelo por banheiro em predio
        if (!$bathroom) {
            FlashMessage::danger('Banheiro não encontrado!');
            $this->redirectTo(route('admin.buildings.bathrooms.index', [
                'building_id' => $building_id
            ]));
            return;
        }
        //dd($_FILES['bathroom_image']);
        //$image = $_FILES['bathroom_image'];

        /**
         * @var ImageModel | null $image
         */
        $image = $bathroom->images()->new(); //não ta com erro vs code que deu doido
        //(cria a imagem (objeto))

        if ($image->imageService()->upload($_FILES['bathroom_image'])) {
            // aqui identifica o caminho e gera o do arquivo
            //(q vem do hash de upload da service)
            FlashMessage::success("Imagem registrada com sucesso!");
        } else {
            $errors = implode("<br />", $image->getErrors());
            FlashMessage::danger("Imagem não registrada! " . $errors);
        }
        $this->redirectBack();
    }


    // DESTROY
    //tanto a controler de imagem (essa aqui) quanto o destroy de banheiro tem um seguimento pra
    // deletar as imagens e as pastas vazias, so não ta fazendo a exclusão da pasta predio qd vazia
    public function destroy(Request $request): void
    {
        $building_id = $request->getParam('building_id');
        $bathroom_id = $request->getParam('bathroom_id');
        $image_id = $request->getParam('image_id');


        /**
         * @var Building | null $building
         */
        $building = Building::findById($building_id);
        if (!$building) {
            FlashMessage::danger('Prédio não encontrado!');
            $this->redirectTo(route('admin.buildings.index'));
            return;
        }

        /**
         * @var Bathroom | null $bathroom
         */
        $bathroom = $building->bathrooms()->findById($bathroom_id);
        if (!$bathroom) {
            FlashMessage::danger('Banheiro não encontrado!');
            $this->redirectTo(route('admin.buildings.bathrooms.index', [
                'building_id' => $building_id
            ]));
            return;
        }

        /**
         * @var ImageModel | null $image
         */
        $image = $bathroom->images()->findById($image_id); //valida as imagens q tem no banheiro
        if (!$image) {
            FlashMessage::danger('Imagem não encontrada!');
            $this->redirectTo(route('admin.buildings.bathrooms.show', [
                'building_id' => $building_id,
                'id' => $bathroom_id
            ]));
            return;
        }

        if ($image->imageService()->deleteImage()) {
            FlashMessage::success('Imagem removida com sucesso!');
        } else {
            FlashMessage::danger('Falha ao remover a imagem!');
        }


        $this->redirectTo(route('admin.buildings.bathrooms.show', [
            'building_id' => $building_id,
            'id' => $bathroom_id
        ]));
    }
}
