<?php

namespace App\Controllers\Api;

use App\Models\Bathroom;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class ConsumptionsApiController extends Controller
{
    protected string $layout = 'admin/application';


    // INDEX
    public function index(Request $request): void
    {
        $bathroomId = intval($request->getParam('bathroom_id'));
        $bathroom = null;

        if ($bathroomId > 0) {
            $bathroom = Bathroom::findById($bathroomId);
        }

        $page = $request->getParam('page', 1);
        $per_page = $request->getParam('per_page', 10);

        if (isset($bathroom)) {
            $paginator = $bathroom->consumptions()
                                ->paginate($page, $per_page);
            $this->renderJson(
                'api/admin/consumptions/index',
                compact(
                    'bathroom',
                    'paginator'
                )
            );
        } else {
            http_response_code(422);
            $this->renderJson(
                'api/admin/consumptions/errors/unprocessableEntity'
            );
        }
    }
}
