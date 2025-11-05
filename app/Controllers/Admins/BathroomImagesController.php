<?php

namespace App\Controllers\Admins;

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
}
