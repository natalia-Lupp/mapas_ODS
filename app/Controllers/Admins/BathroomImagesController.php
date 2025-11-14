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
    public function create(Request $request): void
    {
        $building_id = $request->getParam('building_id');
        $bathroom_id = $request->getParam('id');

        /**
         * @var Building $building
         */
        $building = Building::findById($building_id);

        /**
         * @var Bathroom $bathroom
         */
        $bathroom = $building->bathrooms()->findById($bathroom_id);

        //dd($_FILES['bathroom_image']);
        //$image = $_FILES['bathroom_image'];

        /**
         * @var ImageModel $image
         */
        $image = $bathroom->images()->new(); //não ta com erro vs code que deu doido

        if ($image->imageService()->upload($_FILES['bathroom_image'])) {
            FlashMessage::success("Imagem registrada com sucesso!");
        } else {
            $errors = implode("<br />", $image->getErrors());
            FlashMessage::danger("Imagem não registrada!" . $errors);
        }
        $this->redirectBack();
    }

    public function destroy(Request $request): void
    {
        $building_id = $request->getParam('building_id');
        $bathroom_id = $request->getParam('bathroom_id');
        $image_id = $request->getParam('image_id');

        $building = Building::findById($building_id);
        if (!$building) {
              FlashMessage::danger('Prédio não encontrado!');
              $this->redirectTo(route('admin.buildings.index'));
              return;
        }

        $bathroom = $building->bathrooms()->findById($bathroom_id);
        if (!$bathroom) {
             FlashMessage::danger('Banheiro não encontrado!');
             $this->redirectTo(route('admin.buildings.bathrooms.index', [
            'building_id' => $building_id
             ]));
             return;
        }

        $image = $bathroom->images()->findById($image_id);
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
