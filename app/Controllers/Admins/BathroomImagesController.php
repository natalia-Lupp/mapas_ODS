<?php

namespace App\Controllers\Admins;

use App\Models\Bathroom;
use App\Models\Building;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class BathroomImagesController extends Controller
{

    public function create(Request $request)
    {
        $building_id = $request->getParam('building_id');
        $bathroom_id = $request->getParam('id');

        $building = Building::findById($building_id);
        $bathroom = $building->bathrooms()->findById($bathroom_id);

        //dd($_FILES['bathroom_image']);
        //$image = $_FILES['bathroom_image'];
        $image = $bathroom->images()->new(); //não ta com erro vs code que deu doido

        if ($image->imageService()->upload($_FILES['bathroom_image'])) {
            FlashMessage::success("Imagem registrada com sucesso!");
        } else {
            $errors = implode("<br />", $image->errors);
            FlashMessage::danger("Imagem não registrada!" . $errors);
        }
        $this->redirectBack();
    }

    public function destroyImage(Request $request): void
    {
        $params = $request->getParams();
        $bathroom = Bathroom::findById(intval($params['id']));

        if (!$bathroom) {
            FlashMessage::danger('Banheiro não encontrado!');
            $this->redirectTo(route('admin.buildings.index'));
            return;
        }

        $image = $bathroom->images()->findById($bathroom->id);
        if ($image->imageService()->deleteImage()) {
          FlashMessage::success('Imagem do banheiro removida com sucesso!');
          $this->redirectTo(route('admin.bathrooms.edit', ['id' => $bathroom->id]));
        } else {
          FlashMessage::danger('Ocorreu um erro ao tentar remover a imagem do banheiro!');
          $this->redirectTo(route('admin.bathrooms.edit', ['id' => $bathroom->id]));
        }
    }
}
