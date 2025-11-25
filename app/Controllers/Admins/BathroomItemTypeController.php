<?php

namespace App\Controllers\Admins;

use App\Models\BathroomItemType;
use Core\Debug\Debugger;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class BathroomItemTypeController extends Controller
{
    protected string $layout = 'admin/application';

    public function index(Request $request): void
    {
        $page = $request->getParam('page', 1);
        $per_page = $request->getParam('per_page', 10);

        $title = "tipos de itens de banheiro monitorados";

        $paginator = BathroomItemType::paginate($page, $per_page, 'admin.bathroom_item_types.index');

        $this->render(
            'admin/itemTypes/index',
            compact('title', 'paginator')
        );
    }

    public function show(Request $request): void
    {
        $id = intval($request->getParam('id', 0));

        $title = "tipo de iten de banheiro monitorado";
        $type = BathroomItemType::findById($id);

        if (isset($type)) {
          $this->render(
              'admin/itemTypes/show',
              compact('title', 'type')
          );
        } else {
            FlashMessage::danger('tipo de item não encontrado!');
            $this->redirectBack();
        }

    }

    public function new(Request $request): void
    {
        $title = "Criar novo tipo de iten de banheiro monitorado";
        $type = new BathroomItemType();
        $this->render('admin/itemTypes/new', compact('title', 'type'));
    }

    public function edit(Request $request): void
    {
        $id = intval($request->getParam('id', 0));
        $type = BathroomItemType::findById($id);
        $title = "Editar tipo de iten de banheiro monitorado";
        if (isset($type)) {
          $this->render(
              'admin/itemTypes/edit',
              compact('title', 'type')
          );
        } else {
            FlashMessage::danger('tipo de item não encontrado!');
            $this->redirectBack();
        }
    }

    public function create(Request $request): void
    {
      $typeParam = $request->getParam('type', []);
      $type = new BathroomItemType($typeParam);
      if ($type->save()) {
        FlashMessage::success('Typo de item criada com sucesso!');
        $this->redirectTo(route('admin.bathroom_item_types.index'));
      } else {
        $errors = $type->getErrors();
        foreach ($errors as $prop => $error) {
          FlashMessage::danger("$prop: $error");
        }
        $this->redirectTo(route('admin.bathroom_item_types.new'));
      }
    }

    public function update(Request $request): void
    {
      $id = intval($request->getParam('id', 0));
      $typeParam = $request->getParam('type', []);
      $type = BathroomItemType::findById($id);
      if (isset($type)){
        $type->name = $typeParam['name'] ?? '';
        $type->vendor_consumption_expenditure = $typeParam['vendor_consumption_expenditure'] ?? 0;
        if ($type->save()) {
          FlashMessage::success('Typo de item atualizado com sucesso!');
          $this->redirectTo(route('admin.bathroom_item_types.index'));
        } else {
          $errors = $type->getErrors();
          foreach ($errors as $prop => $error) {
            FlashMessage::danger("$prop: $error");
          }
          $this->redirectTo(route('admin.bathroom_item_types.edit'), [
            'id' => $id
          ]);
        }

      } else {
        FlashMessage::danger('tipo de item não encontrado!');
        $this->redirectBack();
      }
    }

    public function destroy(Request $request): void
    {
      $id = intval($request->getParam('id', 0));
      $type = BathroomItemType::findById($id);

      if (isset($type) && $type->destroy()) {
          FlashMessage::success('Tipo de item excluido com sucesso!');
          $this->redirectTo(route('admin.bathroom_item_types.index'));
      } else {
          FlashMessage::danger('Falha ao excluir tipo de item!');
          $this->redirectTo(route('admin.bathroom_item_types.index'));
      }
    }
}
